<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2005 - 2011 Ingo Schmitt <is@marketing-factory.de>
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
 * Database Class for tx_commerce_article_prices. All database calle should
 * be made by this class. In most cases you should use the methodes
 * provided by tx_commerce_article_price to get informations for articles.
 * Inherited from tx_commerce_db_alib
 *
 * Basic abtract Class for Database Query for
 * Database retrival class fro product
 * inherited from tx_commerce_db_alib
 */
class tx_commerce_db_price extends tx_commerce_db_alib {
	/**
	 * @var string table concerning the data
	 */
	protected $databaseTable = 'tx_commerce_article_prices';

	/**
	 * @param integer $uid UID for Data
	 * @return array assoc Array with data
	 * @todo implement access_check concering category tree
	 * Special Implementation for prices, as they don't have a localisation'
	 */
	public function getData($uid) {
		$uid = intval($uid);
		$proofSQL = '';
		/** @var t3lib_db $database */
		$database = $GLOBALS['TYPO3_DB'];

		if (is_object($GLOBALS['TSFE']->sys_page)) {
			$proofSQL = $GLOBALS['TSFE']->sys_page->enableFields($this->databaseTable, $GLOBALS['TSFE']->showHiddenRecords);
		}

		$result = $database->exec_SELECTquery('*',
			$this->databaseTable,
			'uid = ' . $uid  . $proofSQL
		);

			// Result should contain only one Dataset
		if ($database->sql_num_rows($result) == 1) {
			$returnData = $database->sql_fetch_assoc($result);
			$database->sql_free_result($result);

			return $returnData;
		} else {
				// error Handling
			$this->error('exec_SELECTquery(\'*\',' . $this->databaseTable . ',\'uid = ' . $uid . '\'); returns no or more than one Result');
			return FALSE;
		}
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/lib/class.tx_commerce_db_price.php']) {
	/** @noinspection PhpIncludeInspection */
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/lib/class.tx_commerce_db_price.php']);
}

?>