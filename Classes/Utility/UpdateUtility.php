<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Update Class for DB Updates of version 2.0.0
 *
 * Basically checks for the new Tree, if all records have a MM
 * relation to Record UID 0 if not, these records are created
 *
 * @author Ingo Schmitt
 */
class Tx_Commerce_Utility_UpdateUtility {
	/**
	 * Performes the Updates
	 * Outputs HTML Content
	 *
	 * @return string
	 */
	public function main() {
		$createdRelations = $this->createParentMmRecords();
		$createDefaultRights = $this->createDefaultRights();

		$htmlCode = array();

		$htmlCode[] = 'This updates were performed successfully:
			<ul>';

		if ($createdRelations > 0) {
			$htmlCode[] = '<li>' . $createdRelations .
				' updated mm-Relations for the Category Records. <b>Please Check you Category Tree!</b></li>';
		}
		if ($createDefaultRights > 0) {
			$htmlCode[] = '<li>' . $createDefaultRights .
				' updated User-rights on categories. Set to rights on the commerce products folder</li>';

		}
		$htmlCode[] = '</ul>';

		return implode(chr(10), $htmlCode);
	}

	/**
	 * Sets the default user rights, based on the
	 * <User-Rights in the commerce-products folder
	 *
	 * @return int
	 */
	public function createDefaultRights() {
		$countRecords = 0;

		/**
		 * Get data from folder
		 */
		list($modPid) = Tx_Commerce_Domain_Repository_FolderRepository::initFolders('Commerce', 'commerce');
		list($prodPid) = Tx_Commerce_Domain_Repository_FolderRepository::initFolders('Products', 'commerce', $modPid);
		$resrights = $this->getDatabaseConnection()->exec_SELECTquery(
			'perms_userid, perms_groupid, perms_user, perms_group, perms_everybody',
			'pages',
			'uid = ' . $prodPid
		);
		$data = $this->getDatabaseConnection()->sql_fetch_assoc($resrights);

		$result = $this->getDatabaseConnection()->exec_SELECTquery(
			'uid',
			'tx_commerce_categories',
			'perms_user = 0 OR perms_group = 0 OR perms_everybody = 0'
		);
		while (($row = $this->getDatabaseConnection()->sql_fetch_assoc($result))) {
			$this->getDatabaseConnection()->exec_UPDATEquery('tx_commerce_categories', 'uid = ' . $row['uid'], $data);
			$countRecords++;
		}

		return ++$countRecords;
	}

	/**
	 * Creates the missing MM records for categories
	 * below the root (UID=0) element
	 *
	 * @return int Num Records Changed
	 */
	public function createParentMmRecords() {
		$countRecords = 0;

		$result = $this->getDatabaseConnection()->exec_SELECTquery(
			'uid',
			'tx_commerce_categories',
			'uid NOT IN (SELECT uid_local FROM tx_commerce_categories_parent_category_mm)
				AND tx_commerce_categories.deleted = 0
				AND sys_language_uid = 0 AND l18n_parent = 0'
		);
		while (($row = $this->getDatabaseConnection()->sql_fetch_assoc($result))) {
			$data = array(
				'uid_local' => $row['uid'],
				'uid_foreign' => 0,
				'tablenames' => '',
				'sorting' => 99,
			);

			$this->getDatabaseConnection()->exec_INSERTquery('tx_commerce_categories_parent_category_mm', $data);
			$countRecords++;
		}
		return $countRecords;
	}

	/**
	 * Check if the Update is necessary
	 *
	 * @return bool True if update should be perfomed
	 */
	public function access() {
		if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('commerce')) {
			return FALSE;
		}

		$result = $this->getDatabaseConnection()->exec_SELECTquery(
			'uid',
			'tx_commerce_categories',
			'uid NOT IN (SELECT uid_local FROM tx_commerce_categories_parent_category_mm)
				AND tx_commerce_categories.deleted = 0 AND sys_language_uid = 0 AND l18n_parent = 0'
		);

		if ($result && ($this->getDatabaseConnection()->sql_num_rows($result) > 0)) {
			return TRUE;
		}

		/**
		 * No userrights set at all, must be an update.
		 */
		$result = $this->getDatabaseConnection()->exec_SELECTquery(
			'uid',
			'tx_commerce_categories',
			'perms_user = 0 AND perms_group = 0 AND perms_everybody = 0'
		);
		if ($result && ($this->getDatabaseConnection()->sql_num_rows($result) > 0)) {
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * Get database connection
	 *
	 * @return \TYPO3\CMS\Dbal\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}
}
