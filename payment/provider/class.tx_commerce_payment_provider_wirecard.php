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
 * Wirecard payment provider implementation
 *
 * Testing data:
 * Card type			Test number
 * Visa      			4111 1111 1111 1111
 * MasterCard			5500 0000 0000 0004
 * American Express		3400 0000 0000 009
 * Diner's Club			3000 0000 0000 04
 * Carte Blanche		3000 0000 0000 04
 * Discover				6011 0000 0000 0004
 * JCB					3088 0000 0000 0009
 *
 * @package commerce
 * @subpackage payment
 * @author Volker Graubaum <vg@e-netconsulting.de>
 */
class tx_commerce_payment_provider_wirecard extends tx_commerce_payment_provider_abstract {

	/**
	 * @var string Provider type
	 */
	protected $type = 'wirecard';

	/**
	 * @var string Payment type
	 */
	public $LOCAL_LANG = array();

	/**
	 * @var string
	 */
	public $paymentRefId;

	/**
	 * Returns an array containing some configuration for the fields the customer shall enter his data into.
	 *
	 * @return mixed NULL for no data
	 */
	public function getAdditionalFieldsConfig() {
		$result = array(
			'cc_type.' => array (
				'mandatory' => 1,
				'type' => 'select',
				'values.' => array (
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
			'cc_number.' => array(
				'mandatory' => 1
			),
			'cc_expirationYear.' => array(
				'mandatory' => 1
			),
			'cc_expirationMonth.' => array(
				'mandatory' => 1
			),
			'cc_holder.' => array(
				'mandatory' => 1
			),
			'cc_checksum.' => array(
				'mandatory' => 1
			),
		);

		return $result;
	}

	/**
	 * This method is called in the last step. Here can be made some final checks or whatever is
	 * needed to be done before saving some data in the database.
	 * Write any errors into $this->errorMessages!
	 * To save some additional data in the database use the method updateOrder().
	 *
	 * @param array $config Configuration from TYPO3_CONF_VARS
	 * @param array $session Current session data
	 * @param tx_commerce_basket $basket Basket object
	 * @return boolean TRUE if everything was ok
	 */
	public function finishingFunction(array $config = array(), array $session = array(), tx_commerce_basket $basket = NULL) {
			// Class definition is in payment/libs/class.tx_commerce_payment_wirecard_lib.php
		$paymentLib = t3lib_div::makeInstance('payment');

			// I think there is a new URL for testing with wirecard, so overwrite
			// the old value. you can replace this with your own.
		$paymentLib->url = 'https://c3-test.wirecard.com';

		/*
		user data can be found in
			$_SESSION['billing']
			$_SESSION['delivery']
			$GLOBALS['TSFE']->fe_user->user

		$paymentLib->userData = array(
			'firstname' =>
		);

		$paymentLib->userData(
			array(
				"firstname" => $formData['firstname'],
				"lastname"  => $formData['lastname'],
				"street"	=> $formData['strees'],
				"zip"		=> $formData['zip'],
				"city"		=> $formData['city'],
				"telephone" => $formData['telephone'],
				"country"	=> $formData['contry'],
				"email"		=> $formData['email'],
				"userid"	=> $formData['userid']
			)
		);
		*/

		$paymentLib->paymentmethod = 'creditcard';
		$paymentLib->paymenttype = 'cc';

		$paymentLib->PaymentData = array(
			'kk_number' => $session['payment']['cc_number'],
			'exp_month' => $session['payment']['cc_expirationMonth'],
			'exp_year' => $session['payment']['cc_expirationYear'],
			'holder' => $session['payment']['cc_holder'],
			'cvc' => $session['payment']['cc_checksum']
		);

		$actCurrency = $this->paymentObject->getPObj()->conf['currency'] != '' ?  $this->paymentObject->getPObj()->conf['currency'] : 'EUR';

		$paymentLib->TransactionData = array(
			'amount' => $basket->getGrossSum(),
			'currency' => $actCurrency,
		);

		$paymentLib->sendData = $paymentLib->getwirecardXML();

		$back = $paymentLib->sendTransaction();

		if (!$back) {
			$this->errorMessages = array_merge($this->errorMessages, (array)$paymentLib->getError());
			return FALSE;
		} else {
			$this->paymentRefId = $paymentLib->referenzID;
				// The ReferenceID should be stored here, so that it can be
				// added to the record in updateOrder()
			return TRUE;
		}
	}

	/**
	 * Update order data after order has been finished
	 *
	 * @param integer $orderUid Id of this order
	 * @param array $session Session data
	 * @return void
	 */
	public function updateOrder($orderUid, array $session = array()) {
			// Update order that was created by checkout process
			// With credit card payment a reference ID has to be stored in field payment_ref_id (I
			// have no idea where it comes from, maybe it is given by wirecard?!)
			// To update the order something like this should be sufficient:
			// $this->paymentRefId should probably be set in finishingFunction()
		/** @var t3lib_db $database */
		$database = $GLOBALS['TYPO3_DB'];
		$database->exec_UPDATEquery(
			'tx_commerce_orders', 'uid = ' . $orderUid,
			array('payment_ref_id' => $this->paymentRefId)
		);
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/com_pay_wirecard/class.tx_commerce_payment_provider_wirecard.php']) {
	/** @noinspection PhpIncludeInspection */
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/com_pay_wirecard/class.tx_commerce_payment_provider_wirecard.php']);
}

?>