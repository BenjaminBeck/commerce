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
 * Database Class for tx_commerce_articles. All database calle should
 * be made by this class. In most cases you should use the methodes
 * provided by tx_commerce_article to get informations for articles.
 * Inherited from tx_commerce_db_alib
 */
class tx_commerce_db_article extends tx_commerce_db_alib {
	/**
	 * @var string
	 */
	public $databaseTable = 'tx_commerce_articles';

	/**
	 * @var string
	 */
	public $databaseAttributeRelationTable = 'tx_commerce_articles_article_attributes_mm';

	/**
	 * returns the parent Product uid
	 *
	 * @param integer $uid Article uid
	 * @param boolean $translationMode
	 * @return integer product uid
	 */
	public function get_parent_product_uid($uid, $translationMode = FALSE) {
		$data = parent::getData($uid, $translationMode);
		$result = FALSE;

		if ($data) {
				// Backwards Compatibility
			if ($data['uid_product']) {
				$result = $data['uid_product'];
			} elseif ($data['products_uid']) {
				$result = $data['products_uid'];
			}
		}
		return $result;
	}

	/**
	 * gets all prices form database related to this product
	 *
	 * @param integer $uid Article uid
	 * @param integer $count = Number of Articles for price_scale_amount, default 1
	 * @param string $orderField
	 * @return array of Price UID
	 */
	public function getPrices($uid, $count = 1, $orderField = 'price_net') {
		$uid = intval($uid);
		$count = intval($count);
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_article.php']['priceOrder']) {
			$hookObj = &t3lib_div::getUserObj($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_article.php']['priceOrder']);
			if (method_exists($hookObj, 'priceOrder')) {
				$orderField = $hookObj->priceOrder($orderField);
			}
		}

			// hook to define any additional restrictions in where clause (Melanie Meyer, 2008-09-17)
		$additionalWhere = '';
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_article.php']['additionalPriceWhere']) {
			$hookObj = &t3lib_div::getUserObj($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_article.php']['additionalPriceWhere']);
			if (method_exists($hookObj, 'additionalPriceWhere')) {
				$additionalWhere = $hookObj->additionalPriceWhere($this, $uid);
			}
		}

		if ($uid > 0) {
			$price_uid_list = array();
			$proofSql = '';
			if (is_object($GLOBALS['TSFE']->sys_page)) {
				$proofSql = $GLOBALS['TSFE']->sys_page->enableFields('tx_commerce_article_prices', $GLOBALS['TSFE']->showHiddenRecords);
			}

			/** @var t3lib_db $database */
			$database = $GLOBALS['TYPO3_DB'];

			$result = $database->exec_SELECTquery(
				'uid,fe_group',
				'tx_commerce_article_prices',
				'uid_article = ' . $uid . ' AND price_scale_amount_start <= ' . $count . ' AND price_scale_amount_end >= ' . $count . $proofSql . $additionalWhere,
				'',
				$orderField
			);
			if ($database->sql_num_rows($result) > 0) {
				while ($return_data = $database->sql_fetch_assoc($result)) {
						// Some users of the prices depend on fe_group being 0 when no group is selected. See bug #8894
					if ($return_data['fe_group'] == '') {
						$return_data['fe_group'] = '0';
					}
					$price_uid_list[$return_data['fe_group']][] = $return_data['uid'];
				}
				$database->sql_free_result($result);
				return $price_uid_list;
			} else {
				$this->error('exec_SELECTquery(\'uid\', \'tx_commerce_article_prices\', \'uid_article = \' . $uid); returns no Result');
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * gets all prices form database related to this product
	 *
	 * @param integer $uid Article uid
	 * @param integer $count = Number of Articles for price_scale_amount, default 1
	 * @param string $orderField
	 * @return array of Price UID
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use tx_commerce_db_article::getPrices instead
	 */
	public function get_prices($uid, $count = 1, $orderField = 'price_net') {
		t3lib_div::logDeprecatedFunction();
		return $this->getPrices($uid, $count, $orderField);
	}

	/**
	 * Returns an array of all scale price amounts
	 *
	 * @param integer $uid Article uid
	 * @param integer $count
	 * @return array of Price UID
	 */
	public function getPriceScales($uid,$count = 1) {
		$uid = intval($uid);
		$count = intval($count);
		if ($uid > 0) {
			$proofSql = '';
			$price_uid_list = array();
			if (is_object($GLOBALS['TSFE']->sys_page)) {
				$proofSql = $GLOBALS['TSFE']->sys_page->enableFields('tx_commerce_article_prices', $GLOBALS['TSFE']->showHiddenRecords);
			}

			/** @var t3lib_db $database */
			$database = $GLOBALS['TYPO3_DB'];

			$result = $database->exec_SELECTquery('uid,price_scale_amount_start, price_scale_amount_end',
				'tx_commerce_article_prices',
				'uid_article = ' . $uid  . ' AND price_scale_amount_start >= ' . $count . $proofSql
			);
			if ($database->sql_num_rows($result) > 0) {
				while ($return_data = $database->sql_fetch_assoc($result)) {
					$price_uid_list[$return_data['price_scale_amount_start']][$return_data['price_scale_amount_end']] = $return_data['uid'];
				}
				$database->sql_free_result($result);
				return $price_uid_list;
			} else {
				$this->error('exec_SELECTquery(\'uid\', \'tx_commerce_article_prices\', \'uid_article = \' . $uid); returns no Result');
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * gets all attributes from this product
	 *
	 * @param integer $uid Product uid
	 * @return array of attribute UID
	 */
	public function getAttributes($uid) {
		return parent::getAttributes($uid, '');
	}

	/**
	 * gets all attributes from this product
	 *
	 * @param integer $uid Product uid
	 * @return array of attribute UID
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use tx_commerce_db_article::getAttributes instead
	 */
	public function get_attributes($uid) {
		t3lib_div::logDeprecatedFunction();
		return $this->getAttributes($uid);
	}

	/**
	 * Returns the attribute Value from the given Article attribute pair
	 *
	 * @param integer $uid Article UID
	 * @param integer $attributeUid Attribute UID
	 * @param boolean $valueListAsUid if true, returns not the value from the valuelist, instaed the uid
	 * @return string
	 */
	public function getAttributeValue($uid, $attributeUid, $valueListAsUid = FALSE) {
		$uid = (int) $uid;
		$attributeUid = (int) $attributeUid;

		if ($uid > 0) {
				// First select attribute, to detecxt if is valuelist
			$proofSql = '';
			if (is_object($GLOBALS['TSFE']->sys_page)) {
				$proofSql = $GLOBALS['TSFE']->sys_page->enableFields('tx_commerce_attributes', $GLOBALS['TSFE']->showHiddenRecords);
			}

			/** @var t3lib_db $database */
			$database = $GLOBALS['TYPO3_DB'];

			$result = $database->exec_SELECTquery(
				'DISTINCT uid,has_valuelist',
				'tx_commerce_attributes',
				'uid = ' . (int) $attributeUid . $proofSql
			);
			if ($database->sql_num_rows($result) == 1) {
				$return_data = $database->sql_fetch_assoc($result);
				if ($return_data['has_valuelist'] == 1) {
						// Attribute has a valuelist, so do separate query
					$a_result = $database->exec_SELECTquery(
						'DISTINCT distinct tx_commerce_attribute_values.value,tx_commerce_attribute_values.uid',
						'tx_commerce_articles_article_attributes_mm, tx_commerce_attribute_values',
						'tx_commerce_articles_article_attributes_mm.uid_valuelist = tx_commerce_attribute_values.uid' .
							' AND uid_local = ' . $uid .
							' AND uid_foreign = ' . $attributeUid
					);
					if ($database->sql_num_rows($a_result) == 1) {
						$value_data = $database->sql_fetch_assoc($a_result);
						if ($valueListAsUid == TRUE) {
							return $value_data['uid'];
						} else {
							return $value_data['value'];
						}
					}
				} else {
						// attribute has no valuelist, so do normal query
					$a_result = $database->exec_SELECTquery(
						'DISTINCT value_char,default_value',
						'tx_commerce_articles_article_attributes_mm',
						'uid_local = ' . $uid . ' AND uid_foreign = ' . $attributeUid
					);
					if ($database->sql_num_rows($a_result) == 1) {
						$value_data = $database->sql_fetch_assoc($a_result);
						if ($value_data['value_char']) {
							return $value_data['value_char'];
						} else {
							return 	$value_data['default_value'];
						}
					} else {
						$this->error('More than one Value for thsi attribute');
					}
				}
			} else {
				$this->error('Could not get Attribute for call');
			}
		} else {
			$this->error('no Uid');
		}

		return '';
	}

	/**
	 * returns the supplier name to a given UID, selected from tx_commerce_supplier
	 *
	 * @param integer $supplierUid
	 * @return string Supplier name
	 */
	public function getSupplierName($supplierUid) {
		/** @var t3lib_db $database */
		$database = $GLOBALS['TYPO3_DB'];

		if ($supplierUid > 0) {
			$result = $database->exec_SELECTquery(
				'title',
				'tx_commerce_supplier',
				'uid = ' . (int) $supplierUid
			);
			if ($database->sql_num_rows($result) == 1) {
				$return_data = $database->sql_fetch_assoc($result);
				return $return_data['title'];
			}
		}
		return FALSE;
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/lib/class.tx_commerce_db_article.php']) {
	/** @noinspection PhpIncludeInspection */
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/lib/class.tx_commerce_db_article.php']);
}

?>