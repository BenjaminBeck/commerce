<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007 - 2012 Ingo Schmitt <typo3@marketing-factory.de>
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

class Tx_Commerce_Controller_CategoriesController extends tx_commerce_db_list {
	/**
	 * @var string
	 */
	public $extKey = COMMERCE_EXTKEY;

	/**
	 * Initializing the module
	 *
	 * @return void
	 */
	public function init() {
		Tx_Commerce_Utility_FolderUtility::init_folders();
		$this->control = array (
			'category' => array (
				'dataClass' => 'tx_commerce_leaf_categorydata',
				'parent' => 'parent_category'
			),
			'product' => array (
				'dataClass' => 'tx_commerce_leaf_productdata',
				'parent' => 'categories'
			)
		);

		$this->scriptNewWizard = 'class.tx_commerce_cmd_wizard.php';
		$this->scriptNewWizard = '../../Controller/WizardController.php';
		parent::init();
	}
}

class_alias('Tx_Commerce_Controller_CategoriesController', 'tx_commerce_categories');

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/Classes/Controller/CategoriesController.php']) {
		/** @noinspection PhpIncludeInspection */
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/Classes/Controller/CategoriesController.php']);
}

?>