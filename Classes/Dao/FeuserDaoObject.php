<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2006 Carsten Lausen <cl@e-netconsulting.de>
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
 * feuser object & Dao database access classes
 * These classes handle feuser objects.
 */
class Tx_Commerce_Dao_FeuserDaoObject extends Tx_Commerce_Dao_BasicDaoObject {
	/**
	 * @var integer
	 */
	public $tx_commerce_tt_address_id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @return self
	 */
	public function __construct() {
			// add any mapped fields to object
		/** @var Tx_Commerce_Dao_FeuserAddressFieldmapper $feuserAddressMapper */
		$feuserAddressMapper = t3lib_div::makeInstance('Tx_Commerce_Dao_FeuserAddressFieldmapper');
		$fields = $feuserAddressMapper->getFeuserFields();

		foreach ($fields as $field) {
			$this->$field = NULL;
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return integer
	 */
	public function getTx_commerce_tt_address_id() {
		return $this->tx_commerce_tt_address_id;
	}

	/**
	 * @param integer $value
	 * @return void
	 */
	public function setTx_commerce_tt_address_id($value) {
		$this->tx_commerce_tt_address_id = $value;
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/Classes/Dao/FeuserDaoObject.php']) {
	/** @noinspection PhpIncludeInspection */
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/Classes/Dao/FeuserDaoObject.php']);
}

?>