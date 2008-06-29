<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 - 2008 Ingo Schmitt <is@marketing-factory.de>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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
 * Part of the COMMERCE (Advanced Shopping System) extension.
 *
 * @author	
 * @package TYPO3
 * @subpackage tx_commerce
 */



/**
 * Misc COMMERCE functions
 *
 * 
 * @author  Ingo Schmitt <is@marketing-factory.de>
 * @package TYPO3
 * @subpackage tx_commerce
 * 
 * $Id$
 */
 
require_once(t3lib_extmgm::extPath('commerce').'lib/class.tx_commerce_basket.php');

class tx_commerce_div {





	/***************************************
	 *
	 *	 files folders paths
	 *
	 ***************************************/



	/**
	 * convert a path to a relative path if possible
	 * @author	Ren� Fritz <r.fritz@colorcube.de>
	 * @param	string		Path to convert
	 * @param	string		Path which will be used as base path. Otherwise PATH_site is used.
	 * @return	string		Relative path
	 * @deprecated 
	 */
	 /*
	function getRelPath ($path, $mountpath=NULL) {

		$mountpath = is_null($mountpath) ? PATH_site : $mountpath;

			// remove the site path from the beginning to make the path relative
			// all other's stay absolute
		return preg_replace('#^'.preg_quote($mountpath).'#','',$path);
	}
	*/


	/**
	 * Convert a path to an absolute path
	 * @author	Ren� Fritz <r.fritz@colorcube.de>
	 * @param	string		Path to convert
	 * @param	string		Path which will be used as base path. Otherwise PATH_site is used.
	 * @return	string		Absolute path
	 * @deprecated 
	 
	function getAbsPath ($path) {
		if(t3lib_div::isAbsPath($path)) {
			return $path;
		}
		$mountpath = is_null($mountpath) ? PATH_site : $mountpath;
		return $mountpath.$path;
	}
	*/
	/**
	 * Formates a price for the designated output
	 * @author	Ingo Schmitt <is@marketing-factory.de>
	 * @param 	float	price
	 * @return	string	formated Price
	 * @deprecated 
	 * @todo configurable
	 */
	
	function formatPrice($price)
	{
		return sprintf("%01.2f", $price);
		
	}
	
	/**
	 * This method initilize the basket for the fe_user from
	 * Session. If the basket is already initialized nothing happend 
	 * at this point.
	 * 
	 * @return void
	 */
	function initializeFeUserBasket() {
		
		if(is_object($GLOBALS['TSFE']->fe_user->tx_commerce_basket)) {
			return;
		}
		
		$GLOBALS['TSFE']->fe_user->tx_commerce_basket = t3lib_div::makeInstance('tx_commerce_basket');	
		$GLOBALS['TSFE']->fe_user->tx_commerce_basket->set_session_id($GLOBALS['TSFE']->fe_user->id);
		$GLOBALS['TSFE']->fe_user->tx_commerce_basket->load_data();
		return;
	}
	
	
	/***
	 * Remove Products from list wich have no articles wich are available
	 * from Stockn
	 * 
	 * @param	$productUids = array()	Array	List of productUIDs to work onn
	 * @param	$dontRemoveArticles = 1	integer	switch to show or not show articles
	 * @return	Array	Cleaned up Productarrayt
	 */	
	function removeNoStockProducts($productUids = array(),$dontRemoveProducts = 1) {
		if($dontRemoveProducts == 1) {
			return $productUids;
		}

		foreach ( $productUids as $arrayKey => $productUid ) {
			$productObj = t3lib_div::makeInstance('tx_commerce_product');
			$productObj->init($productUid);
			$productObj->load_data();
			
			if(!($productObj->hasStock())) {
				unset($productUids[$arrayKey]);
			}
			$productObj = NULL;
		}

		return $productUids;
	}
	
	/***
	 * Remove article from product for frontendviewing, if articles
	 * with no stock should not shown
	 * 
	 * @param	$productObj	Object	ProductObject to work on
	 * @param	$dontRemoveArticles = 1	integer	switch to show or not show articles
	 * @return	Object	Cleaned up Productobjectt
	 */
		function removeNoStockArticles( $productObj, $dontRemoveArticles = 1 ) {
		if($dontRemoveArticles == 1) {
			return $productObj;
		}
		$articleUids = $productObj->getArticleUids();
		$articles = $productObj->getArticleObjects();
		foreach ( $articleUids as $arrayKey => $articleUid ) {			
			if($articles[$articleUid]->getStock() <= 0 ) {
				unset($productObj->articles_uids[$arrayKey]);
				unset($productObj->articles[$articleUid]);
			}
		}

		return $productObj;
	}
	
	/**
	* Generates a session key for identifiing session contents and matching to user
	* @param	String	Key
	* @return	Encoded Key as mixture of key and FE-User Uid
	* 
	*/
	function generateSessionKey($key) {

		$extConf = unserialize($GLOBALS["TYPO3_CONF_VARS"]["EXT"]["extConf"]["commerce"]);	

		if (intval($extConf['userSessionMd5Encrypt']) == 1) {
			$sessionKey = md5($key.":".$GLOBALS['TSFE']->fe_user->user['uid']);
		} else {
			$sessionKey = $key.":".$GLOBALS['TSFE']->fe_user->user['uid'];
		}
		
		$hookObjectsArr = array();
		if (is_array ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_div.php']['generateSessionKey']))	{
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_div.php']['generateSessionKey'] as $classRef)	{
				$hookObjectsArr[] = &t3lib_div::getUserObj($classRef);
			}
		}
		foreach($hookObjectsArr as $hookObj) {
			if (method_exists($hookObj, 'postGenerateSessionKey')) {
				$sessionKey = $hookObj->postGenerateSessionKey($key);
			}
		}
		
		return $sessionKey;
		
	}

	

	/**
	* Invokes the HTML mailing class
	*
	* @author	Tom Rüther <tr@e-netconsulting.de>
	* @since	29th June 2008
	* @param	array  $mailconf configuration for the mailerengine 
	* Example for $mailconf 
	*
	* $mailconf = array(
	* 	'plain' => Array (
	* 				'content'=> '' 	// plain content as string
	* 				),
	* 	'html' => Array (
	* 		'content'=> '', 			// html content as string
	* 		'path' => '', 
	* 		'useHtml' => '' 			// is set mail is send as multipart
	* 	),
	* 	'defaultCharset' => 'utf-8',		// your chartset
	* 	'encoding' => '8-bit',			// your encoding
	* 	'attach' => Array (),			// your attachment as array
	* 	'alternateSubject' => '',			// is subject empty will be ste alternateSubject
	* 	'recipient' => '', 				// comma seperate list of recipient
	* 	'recipient_copy' =>  '',			// bcc
	* 	'fromEmail' => '', 				// fromMail
	* 	'fromName' => '',				// fromName
	* 	'replayTo' => '', 				// replayTo
	* 	'priority' => '3', 				// priority of your Mail - 1 = highest, 5 = lowest, 3 = normal
	* 	'callLocation' => 'myFunction' 		// Where call the function it is nescesary when you will use hooks?
	* );
	*
	* @return	void
	*/
	function sendMail($mailconf) {
	
		$hookObjectsArr = array();
		if (is_array ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_div.php']['sendMail']))	{
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_div.php']['sendMail'] as $classRef)	{
				$hookObjectsArr[] = &t3lib_div::getUserObj($classRef);
			}
		}

		foreach($hookObjectsArr as $hookObj)	{
			if (method_exists($hookObj, 'preProcessHtmlMail'))	{
				$htmlMail=$hookObj->postProcessHtmlMail($mailconf);
			}
		}
		
		// validate e-mail addesses
		$mailconf['recipient'] = tx_commerce_div::validEmailList($mailconf['recipient']);
	
		if ($mailconf['recipient']) {
			$parts = spliti('<title>|</title>', $mailconf['htmlContent'], 3);
			$subject = trim($parts[1]) ? strip_tags(trim($parts[1])) : $mailconf['alternateSubject'];
			$htmlMail = t3lib_div::makeInstance('t3lib_htmlmail');
			$htmlMail->charset = $mailconf['defaultCharset'];
			$htmlMail->start();
			
			if($mailconf['encoding'] =='base64') {
				$htmlMail->useBase64();
			} elseif($mailconf['encoding'] == '8bit') {
				$htmlMail->use8Bit();
			}
			
			$htmlMail->mailer = 'TYPO3 Mailer :: commerce';
			$htmlMail->subject = $subject;
			$htmlMail->from_email = tx_commerce_div::validEmailList($mailconf['fromEmail']);
			$htmlMail->from_name = $mailconf['fromName'];
			$htmlMail->from_name = implode(' ' , t3lib_div::trimExplode(',', $htmlMail->from_name));
			$htmlMail->replyto_email = $mailconf['replyTo'] ? $mailconf['replyTo'] :$mailconf['fromEmail'];
			$htmlMail->replyto_name = $mailconf['replyTo'] ? '' : $mailconf['fromName'];
			$htmlMail->replyto_name = implode(' ' , t3lib_div::trimExplode(',', $htmlMail->replyto_name));
			
			if(isset($mailconf['recipient_copy']) && $mailconf['recipient_copy'] != '') {
				$mailconf['recipient_copy'] = tx_commerce_div::validEmailList($mailconf['recipient_copy']);
				
				if($mailconf['recipient_copy'] != '') $htmlMail->add_header('Bcc: '.$mailconf['recipient_copy']);
			}

			$htmlMail->returnPath = $mailconf['fromEmail'];
			$htmlMail->organisation = $mailconf['formName'];
			$htmlMail->priority = $mailconf['priority'];

			// add Html content
			if ($mailconf['html']['useHtml'] && trim($mailconf['html']['content'])) {
				$htmlMail->theParts['html']['content'] = $mailconf['html']['content'];
				$htmlMail->theParts['html']['path'] = $mailconf['html']['path'];
				$htmlMail->extractMediaLinks();
				$htmlMail->extractHyperLinks();
				$htmlMail->fetchHTMLMedia();
				$htmlMail->substMediaNamesInHTML(0);
				$htmlMail->substHREFsInHTML();
				$htmlMail->setHTML($htmlMail->encodeMsg($htmlMail->theParts['html']['content']));
			}
			// add Plan-Text content 
			$htmlMail->addPlain($htmlMail->encodeMsg($mailconf['plain']['content']));
			
			// add attachment 
			if (is_array($mailconf['attach'])) {
				foreach($mailconf['attach'] as $file) {	
					if ($file && file_exists($file)) {
						$htmlMail->addAttachment($file);
					}
				}
			}
			
			// set Headerdata
			$htmlMail->setHeaders();
			$htmlMail->setContent();
			$htmlMail->setRecipient($mailconf['recipient']);
			
			foreach($hookObjectsArr as $hookObj)	{
				if (method_exists($hookObj, 'postProcessMail'))	{
					$htmlMail=$hookObj->postProcessMail($htmlMail);
				}
			}
			$htmlMail->sendtheMail();
			
			return true;
		}		
		return false;	
	}

	/**
	* Helperfunction for email validation
	*
	* @author	Tom Rüther <tr@e-netconsulting.de>
	* @since	29th June 2008
	* @param	array	$list comma seperierte list of email addresses
	*
	* @return	string
	*/

	
	function validEmailList($list) {
	
		$dataArray = t3lib_div::trimExplode(',',$list);
			
		foreach ($dataArray as $data) {
			if (t3lib_div::validEmail($data))	{
				$returnArray[] = $data;
			}					
		}
		if(is_array($returnArray)) $newList = implode(',',$returnArray);
		
		return $newList;
	}


}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/commerce/lib/class.tx_commerce_div.php"])	{
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/commerce/lib/class.tx_commerce_div.php"]);
}
?>