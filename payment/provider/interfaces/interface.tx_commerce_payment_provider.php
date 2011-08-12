<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) Christian Kuhn <lolli@schwarzbu.ch>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Payment provider interface
 *
 * @package commerce
 * @author Christian Kuhn <lolli@schwarzbu.ch>
 */
interface tx_commerce_payment_provider {

	/**
	 * Constructor gets parent object
	 *
	 * @param tx_commerce_payment $paymentObject
	 */
	public function __construct(tx_commerce_payment $paymentObject);

	/**
	 * Get parent object
	 *
	 * @return tx_commerce_pibase Parent object instance
	 */
	public function getPaymentObject();

	/**
	 * Get payment type
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Return TRUE if this payment type is allowed.
	 *
	 * @return boolean
	 */
	public function isAllowed();

	/**
	 * Determine if additional data is needed
	 *
	 * @return bool True if additional data is needed
	 */
	public function needAdditionalData();

	/**
	 * Get configuration of additional fields
	 *
	 * @return mixed|null
	 */
	public function getAdditionalFieldsConfig();

	/**
	 * Check if provided data is ok
	 *
	 * @param array $formData Current form data
	 * @param boolean $parentResult Already determined result of payment object
	 * @return bool TRUE if data is ok
	 */
	public function proofData(array $formData = array(), $parentResult = TRUE);

	/**
	 * Wether or not finishing an order is allowed
	 *
	 * @param array $config Current configuration
	 * @param array $session Session data
	 * @param tx_commerce_basket $basket Basket object
	 * @return bool True is finishing order is allowed
	 */
	public function finishingFunction(array $config= array(), array $session = array(), tx_commerce_basket $basket = NULL);

	/**
	 * Method called in finishIt function
	 *
	 * @param array $globalRequest _REQUEST
	 * @param array $session Session array
	 * @return boolean TRUE if data is ok
	 */
	public function checkExternalData(array $globalRequest = array(), array $session = array());

	/**
	 * Update order data after order has been finished
	 *
	 * @param integer $orderUid Id of this order
	 * @param array $session Session data
	 * @return void
	 */
	public function updateOrder($orderUid, array $session = array());

	/**
	 * Get error message if form data was not ok
	 *
	 * @return string error message
	 */
	public function getLastError();
}
?>