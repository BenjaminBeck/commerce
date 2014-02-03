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
 * Basic class for handling products
 *
 * Libary for Frontend-Rendering of products. This class
 * should be used for all Frontend renderings. No database calls
 * to the commerce tables should be made directly.
 *
 * This Class is inhertited from tx_commerce_element_alib, all
 * basic database calls are made from a separate database Class
 *
 * Do not acces class variables directly, allways use the get and set methods,
 * variables will be changed in php5 to private
 *
 * @author Ingo Schmitt <is@marketing-factory.de>
 * @package TYPO3
 * @subpackage tx_commerce
 */
class tx_commerce_product extends tx_commerce_element_alib {
	/**
	 * Data Variables
	 */
		// Title of the product e.g.productname (private)
	public $title = '';
	public $pid = 0;
		// Subtitle of the product (private)
	public $subtitle = '';
		//  product description (private)
	public $description = '';
	public $teaser = '';
	public $teaserimages;
		// images database field (private)
	public $images = '';
		// Images for the product (private)
	public $images_array = array();
		// Images for the product (private)
	public $teaserImagesArray = array();
		// array of tx_commcerc_article (private)
	public $articles = array();
		// Array of tx_commerce_article_uid (private)
	public $articles_uids = array();
	public $attributes = array();
	public $attributes_uids = array();
	public $relatedpage = '';
	public $relatedProducts = array();
	public $relatedProduct_uids = array();
	public $relatedProducts_loaded = FALSE;

	/**
	 * @var tx_commerce_db_product
	 */
	public $databaseConnection;

	/**
	 * @var int Maximum Articles to render for this product. Normally PHP_INT_MAX
	 */
	public $renderMaxArticles = PHP_INT_MAX;

		// Versioning
	public $t3ver_oid = 0;
	public $t3ver_id = 0;
	public $t3ver_label = '';
	public $t3ver_wsid = 0;
	public $t3ver_state = 0;
	public $t3ver_stage = 0;
	public $t3ver_tstamp = 0;

	/**
	 * @var boolean articlesLoaded TRUE if artciles are loaded, so load articles can simply return with the values from the object
	 */
	protected $articlesLoaded = FALSE;

	/**
	 * Constructor, basically calls init
	 */
	public function __construct() {
		if ((func_num_args() > 0) && (func_num_args() <= 2)) {
			$uid = func_get_arg(0);
			if (func_num_args() == 2) {
				$lang_uid = func_get_arg(1);
			} else {
				$lang_uid = 0;
			}
			$this->init($uid, $lang_uid);
		}
	}

	/**
	 * Class initialization
	 *
	 * @param integer $uid uid of product
	 * @param integer $langUid language uid, default 0
	 * @return boolean TRUE if initialization was successful
	 */
	public function init($uid, $langUid = 0) {
		$uid = intval($uid);
		$langUid = intval($langUid);
		$this->databaseClass = 'tx_commerce_db_product';
		$this->fieldlist = array('uid', 'title', 'pid', 'subtitle', 'description', 'teaser', 'images', 'teaserimages', 'relatedpage', 'l18n_parent', 'manufacturer_uid', 't3ver_oid', 't3ver_id', 't3ver_label', 't3ver_wsid', 't3ver_stage', 't3ver_state', 't3ver_tstamp');

		if ($uid > 0) {
			$this->uid = $uid;
			$this->lang_uid = $langUid;
			$this->databaseConnection = t3lib_div::makeInstance($this->databaseClass);

			$hookObjectsArr = array();
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_product.php']['postinit'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/lib/class.tx_commerce_product.php']['postinit'] as $classRef) {
					$hookObjectsArr[] = &t3lib_div::getUserObj($classRef);
				}
			}
			foreach ($hookObjectsArr as $hookObj) {
				if (method_exists($hookObj, 'postinit')) {
					/** @noinspection PhpUndefinedMethodInspection *//** @noinspection PhpUndefinedMethodInspection */
					$hookObj->postinit($this);
				}
			}

			return TRUE;
		} else {
			return FALSE;
		}
	}

	/*******************************************************************************************
	 * Public Methods
	 *******************************************************************************************/

	/**
	 * Return product title
	 *
	 * @return string Product title
	 * @access public
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getTitle instead
	 */
	public function get_title() {
		t3lib_div::logDeprecatedFunction();

		return $this->getTitle();
	}

	/**
	 * Return product title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Return product pid
	 *
	 * @return integer Product pid
	 * @access public
	 */
	public function get_pid() {
		return $this->pid;
	}

	/**
	 * Returns the uid of the live version of this product
	 *
	 * @return integer UID of live version of this product
	 */
	public function get_t3ver_oid() {
		return $this->t3ver_oid;
	}

	/**
	 * Returns the related page of the product
	 *
	 * @return integer Related page
	 * @access public
	 */
	public function getRelatedPage() {
		return $this->relatedpage;
	}

	/**
	 * Return product subtitle
	 *
	 * @return string Product subtitle
	 * @access public
	 */
	public function get_subtitle() {
		return $this->subtitle;
	}

	/**
	 * Return Product description
	 *
	 * @return string Product description
	 * @access public
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Return Product description
	 *
	 * @return string Product description
	 * @access public
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getDescription instead
	 */
	public function get_description() {
		t3lib_div::logDeprecatedFunction();

		return $this->getDescription();
	}

	/**
	 * Returns the product teaser
	 *
	 * @return string Product teaser
	 * @access public
	 */
	public function get_teaser() {
		return $this->teaser;
	}

	/**
	 * Returns an Array of Images
	 *
	 * @return array Images of this product
	 * @access public
	 */
	public function getTeaserImages() {
		return $this->teaserImagesArray;
	}

	/**
	 * Get list of article uids
	 *
	 * @return array Article uids
	 */
	public function getArticleUids() {
		return $this->articles_uids;
	}

	/**
	 * Get list of article objects
	 *
	 * @return array Article objects
	 */
	public function getArticleObjects() {
		return $this->articles;
	}

	/**
	 * Get number of articles of this product
	 *
	 * @return integer Number of articles
	 */
	public function getNumberOfArticles() {
		return count($this->articles);
	}

	/**
	 * Load article list of this product and store in private class variable
	 *
	 * @return array Article uids
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use loadArticles instead
	 */
	public function load_articles() {
		t3lib_div::logDeprecatedFunction();

		return $this->loadArticles();
	}

	/**
	 * Load article list of this product and store in private class variable
	 *
	 * @return array Article uids
	 */
	public function loadArticles() {
		if ($this->articlesLoaded == FALSE) {
			$uidToLoadFrom = $this->uid;
			if (($this->get_t3ver_oid() > 0) && ($this->get_t3ver_oid() <> $this->uid) && (is_Object($GLOBALS['TSFE']) && $GLOBALS['TSFE']->beUserLogin)) {
				$uidToLoadFrom = $this->get_t3ver_oid();
			}
			if ($this->articles_uids = $this->databaseConnection->get_articles($uidToLoadFrom)) {
				foreach ($this->articles_uids as $article_uid) {
					/** @var tx_commerce_article $article */
					$article = t3lib_div::makeInstance('tx_commerce_article');
					$article->init($article_uid, $this->lang_uid);
					$article->loadData();
					$this->articles[$article_uid] = $article;
				}
				$this->articlesLoaded = TRUE;
				return $this->articles_uids;
			} else {
				return FALSE;
			}
		} else {
			return $this->articles_uids;
		}
	}

	/**
	 * Load data and divide comma sparated images in array
	 * inherited from parent
	 *
	 * @param mixed $translationMode Translation mode of the record, default FALSE to use the default way of translation
	 * @return tx_commerce_product
	 */
	public function loadData($translationMode = FALSE) {
		$return = parent::loadData($translationMode);

		/** @noinspection PhpParamsInspection */
		$this->images_array = t3lib_div::trimExplode(',', $this->images);
		$this->teaserImagesArray = t3lib_div::trimExplode(',', $this->teaserimages);

		return $return;
	}

	/**
	 * Get category master parent category
	 *
	 * @return array uid of category
	 */
	public function getMasterparentCategory() {
		return $this->databaseConnection->getParentCategories($this->uid);
	}

	/**
	 * Get related products
	 *
	 * @return array Related product objecs
	 */
	public function getRelatedProducts() {
		if (!$this->relatedProducts_loaded) {
			$this->relatedProduct_uids = $this->databaseConnection->get_related_product_uids($this->uid);
			if (count($this->relatedProduct_uids) > 0) {
				foreach ($this->relatedProduct_uids as $productId => $categoryId) {
					/** @var tx_commerce_product $product */
					$product = t3lib_div::makeInstance('tx_commerce_product');
					$product->init($productId, $this->lang_uid);
					$product->loadData();
					$product->loadArticles();
						// Check if the user is allowed to access the product and if the product has at least one article
					if ($product->isAccessible() && $product->getNumberOfArticles() >= 1) {
						$this->relatedProducts[] = $product;
					}
				}
			}
			$this->relatedProducts_loaded = TRUE;
		}

		return $this->relatedProducts;
	}

	/**
	 * Get all parent categories
	 *
	 * @return array Parent categories of product
	 */
	public function getParentCategories() {
		return $this->databaseConnection->getParentCategories($this->uid);
	}

	/**
	 * Get l18n overlays of this product
	 *
	 * @return array l18n overlay objects
	 */
	public function get_l18n_products() {
		$uid_lang = $this->databaseConnection->get_l18n_products($this->uid);
		return $uid_lang;
	}

	/**
	 * Get list of articles of this product filtered by given attribute UID and attribute value
	 *
	 * @param array $attribute_Array (
	 * 			array('AttributeUid'=>$attributeUID, 'AttributeValue'=>$attributeValue),
	 * 			array('AttributeUid'=>$attributeUID, 'AttributeValue'=>$attributeValue),
	 * 		...
	 * 		)
	 * @param boolean|integer $proofUid Proof if script is running without instance and so without a single product
	 * @return array of article uids
	 */
	public function get_Articles_by_AttributeArray($attribute_Array, $proofUid = 1) {
		$whereUid = $proofUid ? ' and tx_commerce_articles.uid_product = ' . intval($this->uid) : '';

		$first = 1;

		if (is_array($attribute_Array)) {
			/** @var t3lib_db $database */
			$database = & $GLOBALS['TYPO3_DB'];
			$attribute_uid_list = array();
			foreach ($attribute_Array as $uid_val_pair) {
					// Initialize arrays to prevent warningn in array_intersect()
				$next_array = array();

				$addwheretmp = '';

					// attribute char wird noch nicht verwendet, dafuer muss eine Pruefung auf die ID
				if (is_string($uid_val_pair['AttributeValue'])) {
					$addwheretmp .= ' OR (tx_commerce_attributes.uid = ' . intval($uid_val_pair['AttributeUid']) .
						' AND tx_commerce_articles_article_attributes_mm.value_char="' .
						$database->quoteStr($uid_val_pair['AttributeValue'], 'tx_commerce_articles_article_attributes_mm') .
						'" )';
				}

					// Nach dem charwert immer ueberpruefen, solange value_char noch nicht drin ist.
				if (is_float($uid_val_pair['AttributeValue']) || is_integer(intval($uid_val_pair['AttributeValue']))) {
					$addwheretmp .= ' OR (tx_commerce_attributes.uid = ' . intval($uid_val_pair['AttributeUid']) .
						' AND tx_commerce_articles_article_attributes_mm.default_value in ("' .
						$database->quoteStr($uid_val_pair['AttributeValue'], 'tx_commerce_articles_article_attributes_mm') . '" ) )';
				}

				if (is_float($uid_val_pair['AttributeValue']) || is_integer(intval($uid_val_pair['AttributeValue']))) {
					$addwheretmp .= ' OR (tx_commerce_attributes.uid = ' . intval($uid_val_pair['AttributeUid']) .
						' AND tx_commerce_articles_article_attributes_mm.uid_valuelist in ("' .
						$database->quoteStr($uid_val_pair['AttributeValue'], 'tx_commerce_articles_article_attributes_mm') . '") )';
				}

				$addwhere = ' AND (0 ' . $addwheretmp . ') ';

				$result = $database->exec_SELECT_mm_query(
					'distinct tx_commerce_articles.uid',
					'tx_commerce_articles',
					'tx_commerce_articles_article_attributes_mm',
					'tx_commerce_attributes',
					$addwhere . ' AND tx_commerce_articles.hidden = 0 and tx_commerce_articles.deleted = 0' . $whereUid
				);

				if (($result) && ($database->sql_num_rows($result) > 0)) {
					while ($return_data = $database->sql_fetch_assoc($result)) {
						$next_array[] = $return_data['uid'];
					}
					$database->sql_free_result($result);
				}

					// Return only the first article that exists in all arrays
					// that's why the first array get set and then array intersect checks the matching
				if ($first) {
					$attribute_uid_list = $next_array;
					$first = 0;
				} else {
					$attribute_uid_list = array_intersect($attribute_uid_list, $next_array);
				}
			}

			if (count($attribute_uid_list) > 0) {
				sort($attribute_uid_list);
				return $attribute_uid_list;
		}
	}

		return array();
	}

	/**
	 * Get list of articles of this product filtered by given attribute UID and attribute value
	 *
	 * @see get_Articles_by_AttributeArray()
	 * @param attribute_UID
	 * @param attribute_value
	 * @return array of article uids
	 */
	public function get_Articles_by_Attribute($attributeUid, $attributeValue) {
		return $this->get_Articles_by_AttributeArray(array(array('AttributeUid' => $attributeUid, 'AttributeValue' => $attributeValue)));
	}

	/**
	 * Compare an array record by its sorting value
	 *
	 * @param array $array1 Left
	 * @param array $array2 Right
	 */
	public static function compareBySorting($array1, $array2) {
		return $array1['sorting'] - $array2['sorting'];
	}

	/**
	 * Get attribute matrix of products and articles
	 * Both products and articles have a mm relation to the attribute table
	 * This method gets the attributes of a product or an article and compiles them to an unified array of attributes
	 * This method handles the different types of values of an attribute: character values, integer values and value lists
	 *
	 * @param mixed $articleList Array of restricted product articles (usually shall, must, ...), FALSE for all, FALSE for product attribute list
	 * @param mixed $attributeListInclude Array of restricted attributes, FALSE for all
	 * @param boolean $valueListShowValueInArticleProduct TRUE if 'showvalue' field of value list table should be cared of
	 * @param string $sortingTable Name of table with sorting field of table to order records
	 * @param boolean $localizationAttributeValuesFallbackToDefault TRUE if a fallback to default value should be done if a localization of an attribute value or value char is not available in localized row
	 * @param string $parentTable Name of parent table, either tx_commerce_articles or tx_commerce_products
	 * @return mixed Array if attributes where found, else FALSE
	 */
	public function getAttributeMatrix(
			$articleList = FALSE,
			$attributeListInclude = FALSE,
			$valueListShowValueInArticleProduct = TRUE,
			$sortingTable = 'tx_commerce_articles_article_attributes_mm',
			$localizationAttributeValuesFallbackToDefault = FALSE,
			$parentTable = 'tx_commerce_articles'
		) {
		/** @var t3lib_db $database */
		$database = & $GLOBALS['TYPO3_DB'];

			// Early return if no product is given
		if (!$this->uid > 0) {
			return FALSE;
		}

		if ($parentTable == 'tx_commerce_articles') {
				// mm table for article->attribute
			$mmTable = 'tx_commerce_articles_article_attributes_mm';
		} else {
				// mm table for product->attribute
			$mmTable = 'tx_commerce_products_attributes_mm';
		}

			// Execute main query
		$attributeDataArrayRessource = $database->sql_query(
			$this->getAttributeMatrixQuery(
				$parentTable,
				$mmTable,
				$sortingTable,
				$articleList,
				$attributeListInclude
			)
		);

			// Accumulated result array
		$targetDataArray = array();

			// Attributes uids are added to this array if there is no language overlay for an attribute
			// to prevent fetching of non-existing language overlays in subsequent rows for the same attribute
		$attributeLanguageOverlayBlacklist = array();

			// Compile target data array
		while ($attributeDataRow = $database->sql_fetch_assoc($attributeDataArrayRessource)) {
				// AttributeUid affected by this reord
			$currentAttributeUid = $attributeDataRow['attributes_uid'];

				// Don't handle this row if a prior row was already unable to fetch a language overlay of the attribute
			if ($this->lang_uid > 0 && count(array_intersect(array($currentAttributeUid), $attributeLanguageOverlayBlacklist)) > 0) {
				continue;
			}

				// Initialize array for this attribute uid and fetch attribute language overlay for localization
			if (!isset($targetDataArray[$currentAttributeUid])) {
					// Initialize target row and fill in attribute values
				$targetDataArray[$currentAttributeUid]['title'] = $attributeDataRow['attributes_title'];
				$targetDataArray[$currentAttributeUid]['unit'] = $attributeDataRow['attributes_unit'];
				$targetDataArray[$currentAttributeUid]['values'] = array();
				$targetDataArray[$currentAttributeUid]['valueuidlist'] = array();
				$targetDataArray[$currentAttributeUid]['valueformat'] = $attributeDataRow['attributes_valueformat'];
				$targetDataArray[$currentAttributeUid]['Internal_title'] = $attributeDataRow['attributes_internal_title'];
				$targetDataArray[$currentAttributeUid]['icon'] = $attributeDataRow['attributes_icon'];

					// Fetch language overlay of attribute if given
					// Overwrite title, unit and Internal_title (sic!) of attribute
				if ($this->lang_uid > 0) {
					$overwriteValues = array();
					$overwriteValues['uid'] = $currentAttributeUid;
					$overwriteValues['pid'] = $attributeDataRow['attributes_pid'];
					$overwriteValues['sys_language_uid'] = $attributeDataRow['attritubes_sys_language_uid'];
					$overwriteValues['title'] = $attributeDataRow['attributes_title'];
					$overwriteValues['unit'] = $attributeDataRow['attributes_unit'];
					$overwriteValues['internal_title'] = $attributeDataRow['attributes_internal_title'];
					$languageOverlayRecord = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
						'tx_commerce_attributes',
						$overwriteValues,
						$this->lang_uid,
						$this->translationMode
					);
					if ($languageOverlayRecord) {
						$targetDataArray[$currentAttributeUid]['title'] = $languageOverlayRecord['title'];
						$targetDataArray[$currentAttributeUid]['unit'] = $languageOverlayRecord['unit'];
						$targetDataArray[$currentAttributeUid]['Internal_title'] = $languageOverlayRecord['internal_title'];
					} else {
							// Throw away array if there is no lang overlay, add to blacklist
						unset($targetDataArray[$currentAttributeUid]);
						$attributeLanguageOverlayBlacklist[] = $currentAttributeUid;
						continue;
					}
				}
			}

				// There is a nasty difference between article and product attributes regarding default_value field:
				// For attributes: default_value must be an integer value and string values are stored in value_char
				// For products: Everything is stored in default_value
			$defaultValue = FALSE;
			if ($parentTable == 'tx_commerce_articles') {
				if ($attributeDataRow['default_value'] > 0) {
					$defaultValue = TRUE;
				}
			} else {
				if (strlen($attributeDataRow['default_value']) > 0) {
					$defaultValue = TRUE;
				}
			}

				// Handle value, default_value and value lists of attributes
			if ((strlen($attributeDataRow['value_char']) > 0) || $defaultValue) {
					// Localization of value_char
				if ($this->lang_uid > 0) {
						// Get uid of localized article (lang_uid = selected lang and l18n_parent = current article)
					$localizedArticleUid = $database->exec_SELECTgetRows(
						'uid',
						$parentTable,
						'l18n_parent=' . $attributeDataRow['parent_uid'] .
							' AND sys_language_uid=' . $this->lang_uid .
							$GLOBALS['TSFE']->sys_page->enableFields($parentTable, $GLOBALS['TSFE']->showHiddenRecords)
					);

						// Fetch the article-attribute mm record with localized article uid and current attribute
					$localizedArticleUid = (int)$localizedArticleUid[0]['uid'];
					if ($localizedArticleUid > 0) {
						$selectFields = array();
						$selectFields[] = 'default_value';
							// Again difference between product->attribute and article->attribute
						if ($parentTable == 'tx_commerce_articles') {
							$selectFields[] = 'value_char';
						}
							// Fetch mm record with overlay values
						$localizedArticleAttributeValues = $database->exec_SELECTgetRows(
							implode(', ', $selectFields),
							$mmTable,
							'uid_local=' . $localizedArticleUid .
								' AND uid_foreign=' . $currentAttributeUid
						);
							// Use value_char if set, else check for default_value, else use non localized value if enabled fallback
						if (strlen($localizedArticleAttributeValues[0]['value_char']) > 0) {
							$targetDataArray[$currentAttributeUid]['values'][] = $localizedArticleAttributeValues[0]['value_char'];
						} elseif (strlen($localizedArticleAttributeValues[0]['default_value']) > 0) {
							$targetDataArray[$currentAttributeUid]['values'][] = $localizedArticleAttributeValues[0]['default_value'];
						} elseif ($localizationAttributeValuesFallbackToDefault) {
							$targetDataArray[$currentAttributeUid]['values'][] = $attributeDataRow['value_char'];
						}
					}
				} else {
						// Use value_char if set, else default_value
					if (strlen($attributeDataRow['value_char']) > 0) {
						$targetDataArray[$currentAttributeUid]['values'][] = $attributeDataRow['value_char'];
					} else {
						$targetDataArray[$currentAttributeUid]['values'][] = $attributeDataRow['default_value'];
					}
				}
			} elseif ($attributeDataRow['uid_valuelist']) {
					// Get value list rows
				$valueListArrayRows = $database->exec_SELECTgetRows(
					'*',
					'tx_commerce_attribute_values',
					'uid IN (' . $attributeDataRow['uid_valuelist'] . ')'
				);
				foreach ($valueListArrayRows as $valueListArrayRow) {
						// Ignore row if this value list has already been calculated
						// This might happen if method is called with multiple article uid's
					if (count(array_intersect(array($valueListArrayRow['uid']), $targetDataArray[$currentAttributeUid]['valueuidlist'])) > 0) {
						continue;
					}

						// Value lists must be localized. So overwrite current row with localization record
					if ($this->lang_uid > 0) {
						$valueListArrayRow = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
							'tx_commerce_attribute_values',
							$valueListArrayRow,
							$this->lang_uid,
							$this->translationMode
						);
					}
					if (!$valueListArrayRow) {
						continue;
					}

						// Add value list row to target array
					if ($valueListShowValueInArticleProduct || $valueListArrayRow['showvalue'] == 1) {
						$targetDataArray[$currentAttributeUid]['values'][] = $valueListArrayRow;
						$targetDataArray[$currentAttributeUid]['valueuidlist'][] = $valueListArrayRow['uid'];
					}
				}
			}
		}

			// Free resources of main query
		$database->sql_free_result($attributeDataArrayRessource);

			// Return "I didn't found anything, so I'm not an array"
			// This hack is a re-implementation of the original matrix behaviour
		if (count($targetDataArray) == 0) {
			return FALSE;
		}

			// Sort value lists by sorting value
		foreach ($targetDataArray as $attributeUid => $attributeValues) {
			if (count($attributeValues['valueuidlist']) > 1) {
					// compareBySorting is a special callback function to order the array by its sorting value
				usort($targetDataArray[$attributeUid]['values'], array('tx_commerce_product', 'compareBySorting'));

					// Sort valuelist as well to get deterministic array output
				sort($attributeValues['valueuidlist']);
				$targetDataArray[$attributeUid]['valueuidlist'] = $attributeValues['valueuidlist'];
			}
		}

		return $targetDataArray;
	}

	/**
	 * Create query to get all attributes of articles or products
	 * This is a join over three tables:
	 * 		parent table, either tx_commerce_articles or tx_commerce_producs
	 * 		corresponding mm table
	 * 		tx_commerce_attributes
	 *
	 * @param string $parentTable Name of the parent table, either tx_commerce_articles or tx_commerce_products
	 * @param string $mmTable Name of the mm table, either tx_commerce_articles_article_attributes_mm or tx_commerce_products_attributes_mm
	 * @param string $sortingTable Name of table with .sorting field to order records
	 * @param mixed $articleList Array of some restricted articles of this product (shall, must, ...), FALSE for all articles of product, FALSE if $parentTable = tx_commerce_products
	 * @param mixed $attributeList Array of restricted attributes, FALSE for all attributes
	 * @return string Query to be executed
	 */
	protected function getAttributeMatrixQuery(
			$parentTable = 'tx_commerce_articles',
			$mmTable = 'tx_commerce_articles_article_attributes_mm',
			$sortingTable = 'tx_commerce_articles_article_attributes_mm',
			$articleList = FALSE,
			$attributeList = FALSE
		) {

		/** @var t3lib_db $database */
		$database = & $GLOBALS['TYPO3_DB'];

		$selectFields = array();
		$selectWhere = array();

			// Distinguish differences between product->attribute and article->attribute query
		if ($parentTable == 'tx_commerce_articles') {
				// Load full article list of product if not given
			if ($articleList === FALSE) {
				$articleList = $this->loadArticles();
			}
				// Get article attributes of current product only
			$selectWhere[] = $parentTable . '.uid_product = ' . $this->uid;
				// value_char is only available in article->attribute mm table
			$selectFields[] = $mmTable . '.value_char';
				// Restrict article list if given
			if (is_array($articleList) && count($articleList) > 0) {
				$selectWhere[] = $parentTable . '.uid IN (' . implode(',', $articleList) . ')';
			}
		} else {
				// Get attributes of current product only
			$selectWhere[] = $parentTable . '.uid = ' . $this->uid;
		}

		$selectFields[] = $parentTable . '.uid AS parent_uid';
		$selectFields[] = 'tx_commerce_attributes.uid AS attributes_uid';
		$selectFields[] = 'tx_commerce_attributes.pid AS attributes_pid';
		$selectFields[] = 'tx_commerce_attributes.sys_language_uid AS attributes_sys_language_uid';
		$selectFields[] = 'tx_commerce_attributes.title AS attributes_title';
		$selectFields[] = 'tx_commerce_attributes.unit AS attributes_unit';
		$selectFields[] = 'tx_commerce_attributes.valueformat AS attributes_valueformat';
		$selectFields[] = 'tx_commerce_attributes.internal_title AS attributes_internal_title';
		$selectFields[] = 'tx_commerce_attributes.icon AS attributes_icon';
		$selectFields[] = $mmTable . '.default_value';
		$selectFields[] = $mmTable . '.uid_valuelist';
		$selectFields[] = $sortingTable . '.sorting';

		$selectFrom = array();
		$selectFrom[] = $parentTable;
		$selectFrom[] = $mmTable;
		$selectFrom[] = 'tx_commerce_attributes';

			// mm join restriction
		$selectWhere[] = $parentTable . '.uid = ' . $mmTable . '.uid_local';
		$selectWhere[] = 'tx_commerce_attributes.uid = ' . $mmTable . '.uid_foreign';

			// Restrict attribute list if given
		if (is_array($attributeList) && !empty($attributeList)) {
			$selectWhere[] = 'tx_commerce_attributes.uid IN (' . implode(',', $attributeList) . ')';
		}

			// Get enabled rows only
		$selectWhere[] = ' 1 ' . $GLOBALS['TSFE']->sys_page->enableFields(
			'tx_commerce_attributes',
			$GLOBALS['TSFE']->showHiddenRecords
		);
		$selectWhere[] = ' 1 ' . $GLOBALS['TSFE']->sys_page->enableFields(
			$parentTable,
			$GLOBALS['TSFE']->showHiddenRecords
		);

			// Order rows by given sorting table
		$selectOrder = $sortingTable . '.sorting';

			// Compile query
		$attributeMmQuery = $database->SELECTquery(
			'DISTINCT ' . implode(', ', $selectFields),
			implode(', ', $selectFrom),
			implode(' AND ', $selectWhere),
			'',
			$selectOrder
		);

		return($attributeMmQuery);
	}

	/**
	 * Generates the matrix for attribute values for attribute select options in FE
	 *
	 * @param array $attributeValues of attribute->value pairs, used as default.
	 * @return array Values
	 */
	public function getSelectAttributeValueMatrix(&$attributeValues = array()) {
		$values = array();
		$levelAttributes = array();

		/** @var t3lib_db $database */
		$database = & $GLOBALS['TYPO3_DB'];

		if ($this->uid > 0) {
			$articleList = $this->loadArticles();

			$addWhere = '';
			if (is_array($articleList) && count($articleList) > 0) {
				$queryArticleList = implode(',', $articleList);
				$addWhere = 'uid_local IN (' . $queryArticleList . ')';
			}

			$articleAttributes = $database->exec_SELECTgetRows(
				'uid_local,uid_foreign,uid_valuelist',
				'tx_commerce_articles_article_attributes_mm',
				$addWhere,
				'',
				'uid_local,sorting'
			);

			$levels = array();
			$article = FALSE;
			$attributeValuesList = array();
			$attributeValueSortIndex = array();

			foreach ($articleAttributes as $articleAttribute) {
				$attributeValuesList[] = $articleAttribute['uid_valuelist'];
				if ($article != $articleAttribute['uid_local']) {
					$current = &$values;
					if (count($levels)) {
						foreach ($levels as $level) {
							if (!isset($current[$level])) {
								$current[$level] = array();
							}
							$current = &$current[$level];
						}
					}
					$levels = array();
					$levelAttributes = array();
					$article = $articleAttribute['uid_local'];
				}
				$levels[] = $articleAttribute['uid_valuelist'];
				$levelAttributes[] = $articleAttribute['uid_foreign'];
			}

			$current = &$values;
			if (count($levels)) {
				foreach ($levels as $level) {
					if (!isset($current[$level])) {
						$current[$level] = array();
					}
					$current = &$current[$level];
				}
			}

				// Get the sorting value for all attribute values
			if (count($attributeValuesList) > 0) {
				$attributeValuesList = array_unique($attributeValuesList);
				$attributeValuesList = implode($attributeValuesList, ',');
				$attributeValueSortQuery = $database->exec_SELECTquery(
					'sorting,uid',
					'tx_commerce_attribute_values',
					'uid IN (' . $attributeValuesList . ')'
				);
				while ($attributeValueSort = $database->sql_fetch_assoc($attributeValueSortQuery)) {
					$attributeValueSortIndex[$attributeValueSort['uid']] = $attributeValueSort['sorting'];
				}
			}
		}

		$selectMatrix = array();
		$possible = $values;
		$impossible = array();

		foreach ($levelAttributes as $kV) {
			$tImpossible = array();
			$tPossible = array();
			$selected = $attributeValues[$kV];
			if (!$selected) {
				/** @var tx_commerce_attribute $attributeObj */
				$attributeObj = t3lib_div::makeInstance('tx_commerce_attribute');
				$attributeObj->init($kV, $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid']);
				$attributeObj->loadData();
				$attributeValues[$kV] = $selected = $attributeObj->getFirstAttributeValueUid($possible);
			}

			foreach ($impossible as $key => $val) {
				$selectMatrix[$kV][$key] = 'disabled';
				foreach ($val as $k => $v) {
					$tImpossible[$k] = $v;
				}
			}

			foreach ($possible as $key => $val) {
				$selectMatrix[$kV][$key] = $selected == $key ? 'selected' : 'possible';
				foreach ($val as $k => $v) {
					if (!$selected || $key == $selected) {
						$tPossible[$k] = $v;
					} else {
						$tImpossible[$k] = $v;
					}
				}
			}

			$possible = $tPossible;
			$impossible = $tImpossible;
		}

		return $selectMatrix;
	}

	/**
	 * Generates a Matrix from these concerning articles for all attributes and the values therefor
	 *
	 * @param mixed $articleList Uids of articles or FALSE
	 * @param mixed $attribute_include Array of attribute uids to include or FALSE for all attributes
	 * @param boolean $showHiddenValues Wether or net hidden values should be shown
	 * @param string $sortingTable Default order by of attributes
	 * @return boolean|array
	 */
	public function get_selectattribute_matrix($articleList = FALSE, $attribute_include = FALSE, $showHiddenValues = TRUE, $sortingTable = 'tx_commerce_articles_article_attributes_mm') {
		$return_array = array();

			// If no list is given, take complate arctile-list from product
		if ($this->uid > 0) {
			if ($articleList == FALSE) {
				$articleList = $this->loadArticles();
			}

			$addwhere = '';
			if (is_array($attribute_include)) {
				if (!is_null($attribute_include[0])) {
					$addwhere .= ' AND tx_commerce_attributes.uid in (' . implode(',', $attribute_include) . ')';
				}
			}

			$addwhere2 = '';
			if (is_array($articleList) && count($articleList) > 0) {
				$query_article_list = implode(',', $articleList);
				$addwhere2 = ' AND tx_commerce_articles.uid in (' . $query_article_list . ')';
			}

			/** @var t3lib_db $database */
			$database = & $GLOBALS['TYPO3_DB'];
			$result = $database->exec_SELECT_mm_query(
				'distinct tx_commerce_attributes.uid,tx_commerce_attributes.sys_language_uid,tx_commerce_articles.uid as article ,tx_commerce_attributes.title, tx_commerce_attributes.unit, tx_commerce_attributes.valueformat, tx_commerce_attributes.internal_title,tx_commerce_attributes.icon,tx_commerce_attributes.iconmode, ' . $sortingTable . '.sorting',
				'tx_commerce_articles',
				'tx_commerce_articles_article_attributes_mm',
				'tx_commerce_attributes',
				' AND tx_commerce_articles.uid_product = ' . $this->uid . ' ' .
					$addwhere .
					$addwhere2 .
					' order by ' . $sortingTable . '.sorting'
			);

			$addwhere = $addwhere2;

			if (($result) && ($database->sql_num_rows($result) > 0)) {
				while ($data = $database->sql_fetch_assoc($result)) {
						// Language overlay
					if ($this->lang_uid > 0) {
						$proofSQL = '';
						if (is_object($GLOBALS['TSFE']->sys_page)) {
							$proofSQL = $GLOBALS['TSFE']->sys_page->enableFields('tx_commerce_attributes', $GLOBALS['TSFE']->showHiddenRecords);
						}
						$result2 = $database->exec_SELECTquery(
							'*',
							'tx_commerce_attributes',
							'uid = ' . $data['uid'] . ' ' . $proofSQL
						);

							// Result should contain only one Dataset
						if ($database->sql_num_rows($result2) == 1) {
							$return_data = $database->sql_fetch_assoc($result2);
							$database->sql_free_result($result2);
							$return_data = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_commerce_attributes', $return_data, $this->lang_uid, $this->translationMode);

							if (!is_array($return_data)) {
									// No Translation possible, so next interation
								continue;
							}

						$data['title'] = $return_data['title'];
						$data['unit'] = $return_data['unit'];
						$data['internal_title'] = $return_data['internal_title'];
						}
					}

					$valueshown = FALSE;

						// Only get select attributs, since we don't need any other in selectattribut Matrix and we need the arrayKeys in this case
						// @since 13.12.2005 Get the localized values from tx_commerce_articles_article_attributes_mm
						// @author Ingo Schmitt <is@marketing-factory.de>
					$valuelist = array();
					$attribute_uid = $data['uid'];

					$result_value = $database->exec_SELECT_mm_query(
						'distinct tx_commerce_articles_article_attributes_mm.uid_valuelist',
						'tx_commerce_articles',
						'tx_commerce_articles_article_attributes_mm',
						'tx_commerce_attributes',
						' AND tx_commerce_articles_article_attributes_mm.uid_valuelist>0 ' .
							' AND tx_commerce_articles.uid_product = ' . $this->uid .
							' AND tx_commerce_attributes.uid=' . $attribute_uid .
							$addwhere
					);
					if (($valueshown == FALSE) && ($result_value) && ($database->sql_num_rows($result_value) > 0)) {
						while ($value = $database->sql_fetch_assoc($result_value)) {
							if ($value['uid_valuelist'] > 0) {
								$resvalue = $database->exec_SELECTquery(
									'*',
									'tx_commerce_attribute_values',
									'uid = ' . $value['uid_valuelist']
								);
								$row = $database->sql_fetch_assoc($resvalue);
								if ($this->lang_uid > 0) {
									$row = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_commerce_attribute_values', $row, $this->lang_uid, $this->translationMode);
									if (!is_array($row)) {
										continue;
									}
								}
								if (($showHiddenValues == TRUE) || (($showHiddenValues == FALSE) && ($row['showvalue'] == 1))) {
									$valuelist[$row['uid']] = $row;
									$valueshown = TRUE;
								}
							}
						}
						usort($valuelist, array('tx_commerce_product', 'compareBySorting'));
					}

					if ($valueshown == TRUE) {
						$return_array[$attribute_uid] = array(
							'title' => $data['title'],
							'unit' => $data['unit'],
							'values' => $valuelist,
							'valueformat' => $data['valueformat'],
							'Internal_title' => $data['internal_title'],
							'icon' => $data['icon'],
							'iconmode' => $data['iconmode'],
						);
					}
				}
				return $return_array;
			}
		}

		return FALSE;
	}

	/**
	 * @param array|boolean $attributeArray
	 * @return array|boolean
	 */
	public function getRelevantArticles($attributeArray = FALSE) {
			// First we need all possible Attribute id's (not attribute value id's)
		foreach ($this->attribute as $attribute) {
			$att_is_in_array = FALSE;
			foreach ($attributeArray as $attribute_temp) {
				if ($attribute_temp['AttributeUid'] == $attribute->uid) {
					$att_is_in_array = TRUE;
				}
			}
			if (!$att_is_in_array) {
				$attributeArray[] = array('AttributeUid' => $attribute->uid, 'AttributeValue' => NULL);
			}
		}

		if ($this->uid > 0 && is_array($attributeArray) && count($attributeArray)) {
			$unionSelects = array();
			foreach ($attributeArray as $attr) {
				if ($attr['AttributeValue']) {
					$unionSelects[] = 'SELECT uid_local AS article_id,uid_valuelist FROM tx_commerce_articles_article_attributes_mm,tx_commerce_articles WHERE uid_local = uid AND uid_valuelist = ' . intval($attr['AttributeValue']) . ' AND tx_commerce_articles.uid_product = ' . $this->uid . ' AND uid_foreign = ' . intval($attr['AttributeUid']) . $GLOBALS['TSFE']->sys_page->enableFields('tx_commerce_articles', $GLOBALS['TSFE']->showHiddenRecords);
				} else {
					$unionSelects[] = 'SELECT uid_local AS article_id,uid_valuelist FROM tx_commerce_articles_article_attributes_mm,tx_commerce_articles WHERE uid_local = uid AND tx_commerce_articles.uid_product = ' . $this->uid . ' AND uid_foreign = ' . intval($attr['AttributeUid']) . $GLOBALS['TSFE']->sys_page->enableFields('tx_commerce_articles', $GLOBALS['TSFE']->showHiddenRecords);
				}
			}
			$sql = '';
			if (is_array($unionSelects)) {
				$sql .= ' SELECT count(article_id) AS counter, article_id FROM ( ' . implode(" \n UNION \n ", $unionSelects);
				$sql .= ') AS data GROUP BY article_id having COUNT(article_id) >= ' . (count($unionSelects) - 1) . '';
			}

			/** @var t3lib_db $database */
			$database = & $GLOBALS['TYPO3_DB'];

			$res = $database->sql_query($sql);
			$article_uid_list = array();
			while ($row = $database->sql_fetch_assoc($res)) {
				$article_uid_list[] = $row['article_id'];
			}
			return $article_uid_list;
		}
		return FALSE;
	}

	/**
	 * Returns list of articles (from this product) filtered by price
	 *
	 * @param integer $priceMin smallest unit (e.g. cents)
	 * @param integer $priceMax biggest unit (e.g. cents)
	 * @param boolean|integer $usePriceGrossInstead Normally we check for net price, switch to gross price
	 * @param boolean|integer $proofUid If script is running without instance and so without a single product
	 * @return array of article uids
	 */
	public function getArticlesByPrice($priceMin = 0, $priceMax = 0, $usePriceGrossInstead = 0, $proofUid = 1) {
			// first get all real articles, then create objects and check prices
			// do not get prices directly from DB because we need to take (price) hooks into account
		$table = 'tx_commerce_articles';
		$where = '1=1';
		if ($proofUid) {
			$where .= ' and tx_commerce_articles.uid_product = ' . $this->uid;
		}

		$where .= ' and article_type_uid=1';
		$where .= $GLOBALS['TSFE']->sys_page->enableFields($table, $GLOBALS['TSFE']->showHiddenRecords);
		$groupBy = '';
		$orderBy = 'sorting';
		$limit = '';

		/** @var t3lib_db $database */
		$database = & $GLOBALS['TYPO3_DB'];

		$res = $database->exec_SELECTquery('uid', $table, $where, $groupBy, $orderBy, $limit);
		$rawArticleUidList = array();
		while ($row = $database->sql_fetch_assoc($res)) {
			$rawArticleUidList[] = $row['uid'];
		}
		$database->sql_free_result($res);

			// Run price test
		$articleUidList = array();
		foreach ($rawArticleUidList as $rawArticleUid) {
			$tmpArticle = t3lib_div::makeInstance('tx_commerce_article');
			$tmpArticle->init($rawArticleUid, $this->lang_uid);
			$tmpArticle->loadData();
			$myPrice = $usePriceGrossInstead ? $tmpArticle->get_price_gross() : $tmpArticle->get_price_net();
			if (($priceMin <= $myPrice) && ($myPrice <= $priceMax)) {
				$articleUidList[] = $tmpArticle->getUid();
			}
		}

		if (count($articleUidList) > 0) {
			return $articleUidList;
		} else {
			return FALSE;
		}
	}

	/**
	 * Evaluates the cheapest article for current product by gross price
	 *
	 * @param integer $usePriceNet If true, Compare prices by net instead of gross
	 * @return integer|boolean article id, FALSE if no article
	 */
	public function GetCheapestArticle($usePriceNet = 0) {
		$this->loadArticles();
		if (!is_array($this->articles_uids) || !count($this->articles_uids)) {
			return FALSE;
		}

		$priceArr = array();
		$articleCount = count($this->articles_uids);
		for ($j = 0; $j < $articleCount; $j++) {
			$article = & $this->articles[$this->articles_uids[$j]];
			if (is_object($article) && ($article instanceof tx_commerce_article)) {
				$priceArr[$article->getUid()] = ($usePriceNet) ? $article->get_price_net() : $article->get_price_gross();
			}
		}

		asort($priceArr);
		reset($priceArr);

		return current(array_keys($priceArr));
	}

	/**
	 * Get manufacturer UID of the product if set
	 *
	 * @return integer UID of manufacturer
	 */
	public function getManufacturerUid() {
		if (isset($this->manufacturer_uid)) {
			return $this->manufacturer_uid;
		}
		return FALSE;
	}

	/**
	 * Get manufacturer title
	 *
	 * @return string manufacturer title
	 */
	public function getManufacturerTitle() {
		$result = '';

		if ($this->getManufacturerUid()) {
			$result = $this->databaseConnection->getManufacturerTitle($this->getManufacturerUid());
	}

		return $result;
	}

	/**
	 * Returns TRUE if one Article of Product have more than
	 * null articles on stock
	 *
	 * @return boolean TRUE if one article of product has stock > 0
	 */
	public function hasStock() {
		$this->loadArticles();
		$result = FALSE;
		/** @var tx_commerce_article $article */
		foreach ($this->articles as $article) {
			if ($article->getStock() > 0) {
				$result = TRUE;
			}
		}
		return $result;
	}

	/**
	 * Carries out the move of the product to the new parent
	 * Permissions are NOT checked, this MUST be done beforehanda
	 *
	 * @param integer $uid uid of the move target
	 * @param string $op Operation of move (can be 'after' or 'into'
	 * @return boolean True on success
	 */
	public function move($uid, $op = 'after') {
		if ('into' == $op) {
				// Uid is a future parent
			$parent_uid = $uid;
		} else {
			return FALSE;
		}

			// Update parent_category
		$set = $this->databaseConnection->updateRecord($this->uid, array('categories' => $parent_uid));

			// Update relations only, if parent_category was successfully set
		if ($set) {
			$catList = array($parent_uid);
			$catList = tx_commerce_belib::getUidListFromList($catList);
			$catList = tx_commerce_belib::extractFieldArray($catList, 'uid_foreign', TRUE);
			tx_commerce_belib::saveRelations($this->uid, $catList, 'tx_commerce_products_categories_mm', TRUE);
		} else {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Sets renderMaxArticles Value in the Object
	 *
	 * @param integer $count New Value
	 * @return void
	 */
	public function setRenderMaxArticles($count) {
		$this->renderMaxArticles = (int)$count;
	}

	/**
	 * Get renderMaxArticles Value in the Object
	 *
	 * @return int RenderMaxArticles
	 */
	public function getRenderMaxArticles() {
		return $this->renderMaxArticles;
	}

	/*******************************************************************************************
	 * Deprecated methods
	 *******************************************************************************************/

	/**
	 * @see tx_comemrce_product::getARticleUids();
	 * @deprecated Will be removed after 2011-02-27
	 */
	public function getArticles() {
		return $this->getArticleUids();
	}

	/**
	 * Returns an Array of Images
	 *
	 * @return array Images of this product
	 * @access public
	 */
	public function getImages() {
		return $this->images_array;
	}

	/**
	 * Get category master parent category
	 *
	 * @deprecated Will be removed after 2011-02-27
	 * @see getMasterparentCategory()
	 * @return integer uid of master parent category
	 */
	public function getMasterparentCategorie() {
		return $this->getMasterparentCategory();
	}

	/**
	 * Gets the category master parent
	 *
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getMasterparentCategory instead
	 */
	public function get_masterparent_categorie() {
		t3lib_div::logDeprecatedFunction();
		return $this->getMasterparentCategory();
	}

	/**
	 * Get all parent categories
	 * @return array Uids of categories
	 *
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getImages instead
	 */
	public function get_parent_categories() {
		t3lib_div::logDeprecatedFunction();
		return $this->getParentCategories();
	}

	/**
	 * Returns an Array of Images
	 *
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getImages instead
	 */
	public function get_images() {
		t3lib_div::logDeprecatedFunction();
		return $this->getImages();
	}

	/**
	 * Sets a short description
	 *
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use typoscript instead
	 */
	public function set_leng_description($leng = 150) {
		t3lib_div::logDeprecatedFunction();
		$this->description = substr($this->description, 0, $leng) . '...';
	}

	/**
	 * Returns the attribute matrix
	 *
	 * @see getAttributeMatrix()
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getAttributeMatrix instead
	 */
	public function get_attribute_matrix($articleList = FALSE, $attribute_include = FALSE, $showHiddenValues = TRUE, $sortingTable = 'tx_commerce_articles_article_attributes_mm', $fallbackToDefault = FALSE) {
		t3lib_div::logDeprecatedFunction();
		return $this->getAttributeMatrix($articleList, $attribute_include, $showHiddenValues, $sortingTable, $fallbackToDefault);
	}

	/**
	 * Returns the attribute matrix
	 *
	 * @see getAttributeMatrix()
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getAttributeMatrix instead
	 */
	public function get_atrribute_matrix($articleList = FALSE, $attribute_include = FALSE, $showHiddenValues = TRUE, $sortingTable = 'tx_commerce_articles_article_attributes_mm') {
		t3lib_div::logDeprecatedFunction();
		return $this->getAttributeMatrix($articleList, $attribute_include, $showHiddenValues, $sortingTable);
	}

	/**
	 * Returns the attribute matrix
	 *
	 * @see getAttributeMatrix()
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getAttributeMatrix instead
	 */
	public function get_product_attribute_matrix($attribute_include = FALSE, $showHiddenValues = TRUE, $sortingTable = 'tx_commerce_products_attributes_mm') {
		t3lib_div::logDeprecatedFunction();
		return $this->getAttributeMatrix(FALSE, $attribute_include, $showHiddenValues, $sortingTable, FALSE, 'tx_commerce_products');
	}

	/**
	 * Generates a Matrix fro these concerning products for all Attributes and the values therfor
	 *
	 * @see getAttributeMatrix()
	 * @deprecated since commerce 0.14.0, this function will be removed in commerce 0.16.0, please use getAttributeMatrix instead
	 */
	public function get_product_atrribute_matrix($attribute_include = FALSE, $showHiddenValues = TRUE, $sortingTable = 'tx_commerce_products_attributes_mm') {
		t3lib_div::logDeprecatedFunction();
		return $this->getAttributeMatrix(FALSE, $attribute_include, $showHiddenValues, $sortingTable, FALSE, 'tx_commerce_products');
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/lib/class.tx_commerce_product.php']) {
	/** @noinspection PhpIncludeInspection */
	include_once ($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/commerce/lib/class.tx_commerce_product.php']);
}

?>