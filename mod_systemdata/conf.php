<?php

define('TYPO3_MOD_PATH', '../typo3conf/ext/commerce/mod_systemdata/');
$BACK_PATH = '../../../../typo3/';

$MLANG['default']['tabs_images']['tab'] = 'moduleicon.gif';
$MLANG['default']['ll_ref'] = 'LLL:EXT:commerce/mod_systemdata/locallang_mod.xml';

$MCONF['script'] = 'index.php';
$MCONF['name'] = 'txcommerceM1_systemdata';
$MCONF['access'] = 'user,group';
$MCONF['navFrameScript'] = 'class.tx_commerce_systemdata_navframe.php';

?>