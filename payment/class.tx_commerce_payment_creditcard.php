<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 - 2006 Thomas Hempel (thomas@work.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * 
 *
 * @package commerce
 * @subpackage payment
 * @author Thomas Hempel <thomas@work.de>
 * @internal Maintainer Thomas Hempel
 * 
 * $Id: class.tx_commerce_payment_creditcard.php 501 2007-01-15 14:57:56Z ingo $
 */
 
 /*
  * for testing
  *
  * Kreditkarte			Testnummer 
  * Visa      			4111 1111 1111 1111
  * MasterCard			5500 0000 0000 0004
  * American Express		3400 0000 0000 009
  * Diner's Club		3000 0000 0000 04  
  * Carte Blanche		3000 0000 0000 04
  * Discover			6011 0000 0000 0004
  * JCB				3088 0000 0000 0009
  *
  */
 
// library for credit card checks
require_once(t3lib_extmgm::extPath('commerce') .'lib/class.tx_commerce_ccvs_lib.php');
require_once(t3lib_extmgm::extPath('commerce') .'payment/libs/class.tx_commerce_payment_wirecard_lib.php');
 
class tx_commerce_payment_creditcard {
	/**
	 * The locallang array for this payment module
	 * This is only needed, if individual fields are defined
	 */
	var $LOCAL_LANG = array ( );
	

	/// In this var the wrong fields are stored (for future use)
	var $errorFields = array();
	
	/// This var holds the errormessages (keys are the fieldnames)
	var $errorMessages = array();
	
	function needAdditionalData($pObj) {
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		$basePath = t3lib_extMgm::extPath($pObj->extKey).dirname($this->scriptRelPath).'payment/locallang_creditcard.php';
		$this->LOCAL_LANG = t3lib_div::readLLfile($basePath,$this->pObj->LLkey);
		if ($this->pObj->altLLkey)    {
			$tempLOCAL_LANG = t3lib_div::readLLfile($basePath,$this->pObj->altLLkey);
			$this->LOCAL_LANG = array_merge(is_array($this->LOCAL_LANG) ? $this->LOCAL_LANG : array(),$tempLOCAL_LANG);
		}

		return true;
	}

	function getAdditonalFieldsConfig($pObj) {
		if($this->provider !== null){
			return $this->provider->getAdditonalFieldsConfig();
		}
		return null;
	}
	
	function proofData($formData,$pObj) {
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		$ccvs = new CreditCardValidationSolution();
		$result = $ccvs->validateCreditCard($formData['cc_number'],$formData['cc_checksum']);
		$this->errorMessages[] = $ccvs->CCVSError;
		
		$config['sourceFields.'] = $this->getAdditonalFieldsConfig($this->pObj);
		
		foreach ($this->pObj->MYSESSION['payment'] as $name => $value)	{
			if ($config['sourceFields.'][$name .'.']['mandatory'] == 1 && strlen($value) == 0)	{
				$this->formError[$name] = $this->pObj->pi_getLL('error_field_mandatory');
				$result = false;
			}
			
			$eval = explode(',', $config['sourceFields.'][$name .'.']['eval']);
			foreach ($eval as $method)	{
				$method = explode('_', $method);
				switch (strtolower($method[0]))	{
					case 'email':
						if (!t3lib_div::validEmail($value))	{
							$this->formError[$name] = $this->pObj->pi_getLL('error_field_email');
							$result = false;
						}
						break;
					case 'username':
						if($GLOBALS['TSFE']->loginUser){
						break;
						}	
					    
						if (!$this->pObj->checkUserName($value))	{
							$this->formError[$name] = $this->pObj->pi_getLL('error_field_username');
							$result = false;
						}
						break;
					case 'string':
						if (!is_string($value))	{
							$this->formError[$name] = $this->pObj->pi_getLL('error_field_string');
							$result = false;
						}
						break;
					case 'int':
						if (!is_integer($value))	{
							$this->formError[$name] = $this->pObj->pi_getLL('error_field_int');
							$result = false;
						}
						break;
					case 'min':
						if (strlen((string)$value) < intval($method[1]))	{
							$this->formError[$name] = $this->pObj->pi_getLL('error_field_min');
							$result = false;
						}
						break;
					case 'max':
						if (strlen((string)$value) > intval($method[1]))	{
							$this->formError[$name] = $this->pObj->pi_getLL('error_field_max');
							$result = false;
						}
						break;
					case 'alpha':
						if (preg_match('/[0-9]/', $value) === 1)	{
							$this->formError[$name] = $this->pObj->pi_getLL('error_field_alpha');
							$result = false;
						}
						break;
				}
			}
		}
		unset($ccvs);
		if($result && $this->provider !== null){
		    return $this->provider->proofData($formData,$result);
		}
		return $result;
	}
	
	/**
	 * This method is called in the last step. Here can be made some final checks or whatever is
	 * needed to be done before saving some data in the database.
	 * Write any errors into $this->errorMessages!
	 * To save some additonal data in the database use the method updateOrder().
	 *
	 * @param	array	$config: The configuration from the TYPO3_CONF_VARS
	 * @param	array	$basket: The basket object
	 *
	 * @return boolean	True or false
	 */
	function finishingFunction($config,$session, $basket,$pObj) {		
	
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		if($this->provider !== null){
		    return $this->provider->finishingFunction($config,$session, $basket);
		}		
		return false
	}
	
}


if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/commerce/payment/class.tx_commerce_payment_creditcard.php"])	{
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/commerce/payment/class.tx_commerce_payment_creditcard.php"]);
}

?>