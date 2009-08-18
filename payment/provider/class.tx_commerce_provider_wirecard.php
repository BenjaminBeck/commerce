<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Volker Graubaum <vg@e-netconsulting.de>
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
 * @author Volker Graubaum <vg@e-netconsulting.de>
 * @internal Maintainer Michael Staatz <michael.staatz@e-netconsulting.com>
 */


// library for credit card checks
require_once(t3lib_extmgm::extPath('commerce') .'lib/class.tx_commerce_ccvs_lib.php');
require_once(t3lib_extmgm::extPath('commerce') .'payment/libs/class.tx_commerce_payment_wirecard_lib.php');
require_once(t3lib_extmgm::extPath('commerce') .'payment/provider/class.tx_commerce_provider_abstract.php');

class tx_commerce_provider_wirecard extends tx_commerce_provider_abstract {

		// You have to set the type for each new provider to get criterias and
		// configuration for each from ext_locaconf.php
	protected $type = 'wirecard';

	/**
	 * The locallang array for this payment module
	 * This is only needed, if individual fields are defined
	 */
	public $LOCAL_LANG = array ( );


	function getAdditonalFieldsConfig() {
		$result = array(
			'cc_type.' => array (
				'mandatory' => 1,
				'type' => 'select',
				'values' => array (
					'Visa',
					'Mastercard',
					'Amercican Express',
					'Diners Club',
					'JCB',
					'Switch',
					'VISA Carte Bancaire',
					'Visa Electron',
					'UATP',
				),
			),
			'cc_number.' => array ('mandatory' => 1),
			'cc_expirationYear.' => array ('mandatory' => 1),
			'cc_expirationMonth.' => array ('mandatory' => 1),
			'cc_holder.' => array ('mandatory' => 1),
			'cc_checksum.' => array ('mandatory' => 1),
		);
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
	function finishingFunction($config, $session, $basket) {
		// classdefinition is in payment/libs/class.tx_commerce_payment_wirecard_lib.php
		$paymentLib = new payment();

		$paymentLib->paymentmethod = 'creditcard';
		$paymentLib->paymenttype = 'cc';

		$paymentLib->PaymentData = array(
			'kk_number' => $session['payment']['cc_number'],
			'exp_month' => $session['payment']['cc_expirationMonth'],
			'exp_year' => $session['payment']['cc_expirationYear'],
			'holder' => $session['payment']['cc_holder'],
			'cvc' => $session['payment']['cc_checksum']
		);

		$actCurrency = $this->parentObject->conf['currency'] != '' ?  $this->parentObject->conf['currency'] : 'EUR';

		$paymentLib->TransactionData = array (
			'amount' => $basket->get_gross_sum(),
			'currency' => $actCurrency,
		);

		$paymentLib->sendData = $paymentLib->getwirecardXML();

		$back = $paymentLib->sendTransaction();

		if (!$back) {

			$this->errorMessages = $paymentLib->getError();
			return false;


		} else {
			$this->paymentRefId = $paymentLib->referenzID;

			/*
				Irgendwo hier müsste diese ReferenzID gesichert werden, damit sie
				in "updateOrder" in den Datensatz geschrieben werden kann.
			*/
			return true;
		}
	}

	/**
	 * This method can make something with the created order. For example add the
	 * reference id for payments with creditcards.
	 */
	function updateOrder($orderUid, $session) {
		/*
			Hier muss die vom checkout erzeugte Order geupdatet werden!
			Bei Kreditkartenzahlung muss eine Referenz ID im Feld payment_ref_id
			gespeichert werden. (Ich habe keine Ahnung voher die kommt, aber ich
			schätze mal das müsste wirecard liefern!?)
			Die UID des angelegten order Datensatzes steht in $orderUid! Um die
			Order upzudaten müsste folgendes reichen:
		*/
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			'tx_commerce_orders','uid = '.$orderUid,
			array('payment_ref_id' => $this->paymentRefId)
		);
		/*
			Es müsste also irgendwo in dieser Methode oder in der "fisnishingFunction"
			die Variable $this->paymentRefId gesetzt werden.
			Das wars dann.
		*/
	}

	/**
	 * Returns the last error message
	 */
	function getLastError($finish = 0) {
		if($finish){
			return $this->getReadableError();
		}else{
			return $this->errorMessages[(count($this->errorMessages) -1)];
		}
	}

	// creditcard Error Code Handling
	function getReadableError(){
		$back = '';
		reset($this->errorMessages);
		while (list($k,$v) = each($this->errorMessages)) {
			$back .= $v;
		}
		return $back;
	}
}


if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/commerce/payment/class.tx_commerce_payment_provider_wirecard.php"])	{
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/commerce/payment/class.tx_commerce_payment_provider_wirecard.php"]);
}

?>