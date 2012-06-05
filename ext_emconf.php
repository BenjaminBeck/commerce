<?php

########################################################################
# Extension Manager/Repository config file for ext "commerce".
#
# Auto generated 03-05-2011 22:56
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Commerce',
	'description' => 'TYPO3 commerce shopping system',
	'category' => 'module',
	'shy' => 0,
	'dependencies' => 'cms,tt_address,dynaflex,moneylib,static_info_tables',
	'conflicts' => 'mc_autokeywords',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod_main,mod_category,mod_access,mod_orders,mod_systemdata,mod_statistic',
	'state' => 'beta',
	'internal' => 0,
	'uploadfolder' => 1,
	'createDirs' => 'uploads/tx_commerce/rte',
	'modify_tables' => 'tt_address,fe_users',
	'clearCacheOnLoad' => 1,
	'lockType' => 'L',
	'author' => 'Ingo Schmitt,Volker Graubaum,Thomas Hempel',
	'author_email' => 'team@typo3-commerce.org',
	'author_company' => 'Marketing Factory Consulting GmbH,e-netconsulting KG,n@work Internet Informationssysteme GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.13.1',
	'_md5_values_when_last_written' => 'a:442:{s:10:"change.svn";s:4:"6a62";s:20:"class.ext_update.php";s:4:"99fb";s:36:"class.tx_commerce_articlecreator.php";s:4:"7bb4";s:37:"class.tx_commerce_attributeeditor.php";s:4:"79d5";s:28:"class.ux_localrecordlist.php";s:4:"9d5d";s:23:"class.ux_modulemenu.php";s:4:"1b1f";s:33:"class.ux_t3lib_parsehtml_proc.php";s:4:"3a84";s:25:"class.ux_versionindex.php";s:4:"e9ea";s:21:"ext_conf_template.txt";s:4:"3192";s:25:"ext_df_article_config.php";s:4:"6b68";s:25:"ext_df_product_config.php";s:4:"d1f2";s:12:"ext_icon.gif";s:4:"613c";s:17:"ext_localconf.php";s:4:"d265";s:14:"ext_tables.php";s:4:"a853";s:14:"ext_tables.sql";s:4:"34d6";s:25:"ext_tables_static+adt.sql";s:4:"8a05";s:16:"locallang_be.xml";s:4:"9721";s:23:"locallang_be_errors.xml";s:4:"5f4f";s:16:"locallang_cm.xml";s:4:"b0e5";s:16:"locallang_db.xml";s:4:"601c";s:21:"locallang_treelib.xml";s:4:"1ae4";s:26:"cli/class.cli_commerce.php";s:4:"23ab";s:28:"dao/class.address_mapper.php";s:4:"7aaa";s:28:"dao/class.address_object.php";s:4:"bca5";s:30:"dao/class.address_observer.php";s:4:"734e";s:28:"dao/class.address_parser.php";s:4:"75ac";s:23:"dao/class.basic_dao.php";s:4:"40ec";s:30:"dao/class.basic_dao_mapper.php";s:4:"e2f7";s:30:"dao/class.basic_dao_parser.php";s:4:"eb23";s:26:"dao/class.basic_mapper.php";s:4:"0b6c";s:26:"dao/class.basic_object.php";s:4:"1cf1";s:26:"dao/class.basic_parser.php";s:4:"e177";s:40:"dao/class.feuser_address_fieldmapper.php";s:4:"48a9";s:27:"dao/class.feuser_object.php";s:4:"fafa";s:30:"dao/class.feusers_observer.php";s:4:"9a5d";s:48:"dcafiles/class.tx_commerce_articles_dfconfig.php";s:4:"e85e";s:50:"dcafiles/class.tx_commerce_categories_dfconfig.php";s:4:"729e";s:39:"dcafiles/class.tx_commerce_dcahooks.php";s:4:"5204";s:48:"dcafiles/class.tx_commerce_products_dfconfig.php";s:4:"4a2e";s:18:"doc/guidelines.txt";s:4:"2e63";s:14:"doc/hooks.html";s:4:"1319";s:14:"doc/manual.sxw";s:4:"b0e5";s:40:"hooks/class.tx_commerce_articlehooks.php";s:4:"dc18";s:44:"hooks/class.tx_commerce_browselinkshooks.php";s:4:"871a";s:35:"hooks/class.tx_commerce_cmhooks.php";s:4:"fb7f";s:35:"hooks/class.tx_commerce_dmhooks.php";s:4:"36a0";s:37:"hooks/class.tx_commerce_irrehooks.php";s:4:"6d9b";s:39:"hooks/class.tx_commerce_linkhandler.php";s:4:"b547";s:42:"hooks/class.tx_commerce_ordermailhooks.php";s:4:"68f8";s:43:"hooks/class.tx_commerce_pi4hooksHandler.php";s:4:"5ea9";s:42:"hooks/class.tx_commerce_tceforms_hooks.php";s:4:"9634";s:43:"hooks/class.tx_commerce_tcehooksHandler.php";s:4:"2acf";s:47:"hooks/class.tx_commerce_userauthgroup_hooks.php";s:4:"a7d0";s:40:"hooks/class.tx_commerce_versionhooks.php";s:4:"b2c4";s:57:"hooks/class.tx_srfeuserregister_commerce_hooksHandler.php";s:4:"177b";s:33:"lib/class.tx_commerce_article.php";s:4:"fd8e";s:39:"lib/class.tx_commerce_article_price.php";s:4:"f10c";s:35:"lib/class.tx_commerce_attribute.php";s:4:"96b6";s:41:"lib/class.tx_commerce_attribute_value.php";s:4:"cffe";s:38:"lib/class.tx_commerce_basic_basket.php";s:4:"6492";s:32:"lib/class.tx_commerce_basket.php";s:4:"4ea5";s:37:"lib/class.tx_commerce_basket_item.php";s:4:"d687";s:31:"lib/class.tx_commerce_belib.php";s:4:"9d0a";s:37:"lib/class.tx_commerce_browsetrees.php";s:4:"e904";s:34:"lib/class.tx_commerce_category.php";s:4:"48aa";s:34:"lib/class.tx_commerce_ccvs_lib.php";s:4:"d246";s:39:"lib/class.tx_commerce_create_folder.php";s:4:"ad2b";s:33:"lib/class.tx_commerce_db_alib.php";s:4:"d779";s:36:"lib/class.tx_commerce_db_article.php";s:4:"a127";s:38:"lib/class.tx_commerce_db_attribute.php";s:4:"7909";s:44:"lib/class.tx_commerce_db_attribute_value.php";s:4:"53bd";s:37:"lib/class.tx_commerce_db_category.php";s:4:"2293";s:33:"lib/class.tx_commerce_db_list.php";s:4:"e3a0";s:39:"lib/class.tx_commerce_db_list_extra.inc";s:4:"2135";s:34:"lib/class.tx_commerce_db_price.php";s:4:"0529";s:36:"lib/class.tx_commerce_db_product.php";s:4:"1d12";s:29:"lib/class.tx_commerce_div.php";s:4:"a7a5";s:38:"lib/class.tx_commerce_element_alib.php";s:4:"0bab";s:49:"lib/class.tx_commerce_feusers_localrecordlist.php";s:4:"ca85";s:35:"lib/class.tx_commerce_folder_db.php";s:4:"0b20";s:38:"lib/class.tx_commerce_forms_select.php";s:4:"3ad6";s:30:"lib/class.tx_commerce_item.php";s:4:"1deb";s:36:"lib/class.tx_commerce_navigation.php";s:4:"c3b2";s:47:"lib/class.tx_commerce_order_localrecordlist.php";s:4:"2ac5";s:32:"lib/class.tx_commerce_pibase.php";s:4:"077f";s:33:"lib/class.tx_commerce_product.php";s:4:"dce5";s:36:"lib/class.tx_commerce_statistics.php";s:4:"3658";s:44:"mod_access/class.sc_mod_access_perm_ajax.php";s:4:"0231";s:48:"mod_access/class.tx_commerce_access_navframe.php";s:4:"d23f";s:20:"mod_access/clear.gif";s:4:"cc11";s:19:"mod_access/conf.php";s:4:"5c08";s:20:"mod_access/index.php";s:4:"ddab";s:21:"mod_access/legend.gif";s:4:"1a1c";s:24:"mod_access/locallang.xml";s:4:"6af6";s:28:"mod_access/locallang_mod.xml";s:4:"9d90";s:40:"mod_access/locallang_mod_access_perm.xml";s:4:"651a";s:25:"mod_access/moduleicon.gif";s:4:"c751";s:18:"mod_access/perm.js";s:4:"ed51";s:18:"mod_access/tree.js";s:4:"76f3";s:41:"mod_access/templates/alt_db_navframe.html";s:4:"7dc0";s:30:"mod_access/templates/perm.html";s:4:"76ac";s:52:"mod_category/class.tx_commerce_category_navframe.php";s:4:"e285";s:45:"mod_category/class.tx_commerce_cmd_wizard.php";s:4:"243c";s:46:"mod_category/class.user_attributeedit_func.php";s:4:"cb92";s:22:"mod_category/clear.gif";s:4:"cc11";s:21:"mod_category/conf.php";s:4:"d45f";s:22:"mod_category/index.php";s:4:"e52a";s:26:"mod_category/locallang.xml";s:4:"a056";s:30:"mod_category/locallang_mod.xml";s:4:"4cd1";s:27:"mod_category/moduleicon.gif";s:4:"af32";s:43:"mod_category/templates/alt_db_navframe.html";s:4:"7dc0";s:16:"mod_cce/conf.php";s:4:"855f";s:20:"mod_cce/copyPaste.js";s:4:"5e25";s:25:"mod_cce/locallang_mod.xml";s:4:"a909";s:30:"mod_cce/tx_commerce_cce_db.php";s:4:"dd00";s:27:"mod_cce/templates/copy.html";s:4:"76ac";s:45:"mod_clickmenu/class.tx_commerce_clickmenu.php";s:4:"4e69";s:36:"mod_clickmenu/commerce_clickmenu.php";s:4:"8ee6";s:39:"mod_main/class.tx_commerce_navframe.php";s:4:"5443";s:18:"mod_main/clear.gif";s:4:"cc11";s:17:"mod_main/conf.php";s:4:"d054";s:18:"mod_main/index.php";s:4:"92cc";s:22:"mod_main/locallang.xml";s:4:"4744";s:26:"mod_main/locallang_mod.xml";s:4:"4b43";s:23:"mod_main/moduleicon.gif";s:4:"18c2";s:47:"mod_orders/class.tx_commerce_order_navframe.php";s:4:"1480";s:47:"mod_orders/class.tx_commerce_order_pagetree.php";s:4:"fc2f";s:40:"mod_orders/class.user_orderedit_func.php";s:4:"cd40";s:20:"mod_orders/clear.gif";s:4:"cc11";s:19:"mod_orders/conf.php";s:4:"e7d4";s:20:"mod_orders/index.php";s:4:"736c";s:24:"mod_orders/locallang.xml";s:4:"283a";s:28:"mod_orders/locallang_mod.xml";s:4:"8e65";s:25:"mod_orders/moduleicon.gif";s:4:"134b";s:21:"mod_perftest/conf.php";s:4:"6fe5";s:22:"mod_perftest/index.php";s:4:"e93a";s:30:"mod_perftest/locallang_mod.xml";s:4:"1e6f";s:58:"mod_perftest/lib/class.tx_commerce_browseTree_perfTest.php";s:4:"9638";s:60:"mod_perftest/lib/class.tx_commerce_leaf_perfcategorydata.php";s:4:"9979";s:60:"mod_perftest/lib/class.tx_commerce_leaf_perfcategoryview.php";s:4:"88ec";s:55:"mod_perftest/lib/class.tx_commerce_perfcategorytree.php";s:4:"d70f";s:56:"mod_perftest/lib/class.tx_commerce_perfleaf_category.php";s:4:"da11";s:48:"mod_perftest/lib/class.tx_commerce_perfsuite.php";s:4:"1e6f";s:38:"mod_perftest/lib/Benchmark/Iterate.php";s:4:"6a24";s:39:"mod_perftest/lib/Benchmark/Profiler.php";s:4:"221e";s:36:"mod_perftest/lib/Benchmark/Timer.php";s:4:"7ce7";s:54:"mod_statistic/class.tx_commerce_statistic_navframe.php";s:4:"53e2";s:23:"mod_statistic/clear.gif";s:4:"cc11";s:22:"mod_statistic/conf.php";s:4:"5e2d";s:23:"mod_statistic/index.php";s:4:"fcf4";s:27:"mod_statistic/locallang.xml";s:4:"026e";s:31:"mod_statistic/locallang_mod.xml";s:4:"b52c";s:35:"mod_statistic/locallang_weekday.xml";s:4:"ad63";s:28:"mod_statistic/moduleicon.gif";s:4:"0c75";s:54:"mod_systemdata/class.tx_commerce_category_navframe.php";s:4:"3bd4";s:24:"mod_systemdata/clear.gif";s:4:"cc11";s:23:"mod_systemdata/conf.php";s:4:"d48b";s:24:"mod_systemdata/index.php";s:4:"d0ba";s:28:"mod_systemdata/locallang.xml";s:4:"f7ed";s:32:"mod_systemdata/locallang_mod.xml";s:4:"ca9c";s:29:"mod_systemdata/moduleicon.gif";s:4:"766b";s:52:"mod_tracking/class.tx_commerce_category_navframe.php";s:4:"0e0a";s:22:"mod_tracking/clear.gif";s:4:"cc11";s:21:"mod_tracking/conf.php";s:4:"e24a";s:22:"mod_tracking/index.php";s:4:"a73f";s:26:"mod_tracking/locallang.xml";s:4:"b126";s:30:"mod_tracking/locallang_mod.xml";s:4:"894c";s:27:"mod_tracking/moduleicon.gif";s:4:"017d";s:24:"mod_treebrowser/conf.php";s:4:"405b";s:25:"mod_treebrowser/index.php";s:4:"daed";s:33:"mod_treebrowser/locallang_mod.php";s:4:"c15c";s:37:"patches/class.t3lib_tcemain.php.patch";s:4:"cb7f";s:33:"patches/jsfunc.evalfield.js.patch";s:4:"87df";s:52:"payment/class.tx_commerce_payment_cashondelivery.php";s:4:"8f66";s:48:"payment/class.tx_commerce_payment_creditcard.php";s:4:"dd6a";s:43:"payment/class.tx_commerce_payment_debit.php";s:4:"5981";s:45:"payment/class.tx_commerce_payment_invoice.php";s:4:"9797";s:48:"payment/class.tx_commerce_payment_prepayment.php";s:4:"0238";s:32:"payment/locallang_creditcard.xml";s:4:"4d70";s:33:"payment/ccvs_language/ccvs_en.inc";s:4:"407f";s:33:"payment/ccvs_language/ccvs_es.inc";s:4:"dfe2";s:55:"payment/libs/class.tx_commerce_payment_wirecard_lib.php";s:4:"d8e9";s:24:"pi1/category_product.tpl";s:4:"8f8a";s:29:"pi1/class.tx_commerce_pi1.php";s:4:"eaac";s:37:"pi1/class.tx_commerce_pi1_wizicon.php";s:4:"a003";s:24:"pi1/flexform_product.xml";s:4:"9d77";s:17:"pi1/locallang.xml";s:4:"8499";s:21:"pi1/locallang_tca.xml";s:4:"2d9e";s:29:"pi2/class.tx_commerce_pi2.php";s:4:"cc0a";s:37:"pi2/class.tx_commerce_pi2_wizicon.php";s:4:"9fc9";s:17:"pi2/locallang.xml";s:4:"91d2";s:20:"pi2/shoppingcart.tpl";s:4:"e1dc";s:18:"pi2/res/basket.gif";s:4:"2c98";s:22:"pi2/res/basket_del.gif";s:4:"e9fa";s:29:"pi3/class.tx_commerce_pi3.php";s:4:"2f4d";s:37:"pi3/class.tx_commerce_pi3_wizicon.php";s:4:"5f09";s:17:"pi3/locallang.xml";s:4:"cfb0";s:26:"pi3/template_adminmail.tpl";s:4:"dbaf";s:31:"pi3/template_adminmail_html.tpl";s:4:"8ba0";s:25:"pi3/template_checkout.tpl";s:4:"2466";s:25:"pi3/template_usermail.tpl";s:4:"7e25";s:30:"pi3/template_usermail_html.tpl";s:4:"dbeb";s:29:"pi4/class.tx_commerce_pi4.php";s:4:"7b1f";s:37:"pi4/class.tx_commerce_pi4_wizicon.php";s:4:"2b15";s:17:"pi4/locallang.xml";s:4:"bb1c";s:26:"pi4/template_addresses.tpl";s:4:"c10c";s:29:"pi6/class.tx_commerce_pi6.php";s:4:"6e6e";s:37:"pi6/class.tx_commerce_pi6_wizicon.php";s:4:"8b36";s:15:"pi6/invoice.tpl";s:4:"49d1";s:17:"pi6/locallang.xml";s:4:"01a5";s:20:"res/css/commerce.css";s:4:"4948";s:20:"res/icons/ce_wiz.gif";s:4:"81e5";s:33:"res/icons/table/address_types.gif";s:4:"a38a";s:36:"res/icons/table/address_types__x.gif";s:4:"385c";s:27:"res/icons/table/article.gif";s:4:"6100";s:30:"res/icons/table/article__d.gif";s:4:"ffd5";s:30:"res/icons/table/article__f.gif";s:4:"5321";s:31:"res/icons/table/article__fu.gif";s:4:"ba4b";s:30:"res/icons/table/article__h.gif";s:4:"8401";s:31:"res/icons/table/article__hf.gif";s:4:"8401";s:32:"res/icons/table/article__hfu.gif";s:4:"e77c";s:31:"res/icons/table/article__ht.gif";s:4:"d1a9";s:32:"res/icons/table/article__htf.gif";s:4:"d1a9";s:33:"res/icons/table/article__htfu.gif";s:4:"bb05";s:32:"res/icons/table/article__htu.gif";s:4:"bb05";s:31:"res/icons/table/article__hu.gif";s:4:"e77c";s:30:"res/icons/table/article__t.gif";s:4:"abb0";s:31:"res/icons/table/article__tf.gif";s:4:"abb0";s:32:"res/icons/table/article__tfu.gif";s:4:"e0d4";s:31:"res/icons/table/article__tu.gif";s:4:"e0d4";s:30:"res/icons/table/article__u.gif";s:4:"f387";s:30:"res/icons/table/article__x.gif";s:4:"e64f";s:33:"res/icons/table/article_types.gif";s:4:"a38a";s:46:"res/icons/table/attribute_correlationtypes.gif";s:4:"a38a";s:35:"res/icons/table/attribute_value.gif";s:4:"48a0";s:38:"res/icons/table/attribute_value__d.gif";s:4:"2c71";s:38:"res/icons/table/attribute_value__f.gif";s:4:"378e";s:38:"res/icons/table/attribute_value__h.gif";s:4:"0223";s:39:"res/icons/table/attribute_value__hf.gif";s:4:"0223";s:39:"res/icons/table/attribute_value__ht.gif";s:4:"775a";s:40:"res/icons/table/attribute_value__htf.gif";s:4:"775a";s:38:"res/icons/table/attribute_value__t.gif";s:4:"a464";s:39:"res/icons/table/attribute_value__tf.gif";s:4:"a464";s:38:"res/icons/table/attribute_value__x.gif";s:4:"e6c1";s:30:"res/icons/table/attributes.gif";s:4:"9eee";s:35:"res/icons/table/attributes_free.gif";s:4:"4d0c";s:38:"res/icons/table/attributes_free__d.gif";s:4:"6eed";s:38:"res/icons/table/attributes_free__f.gif";s:4:"3cf7";s:39:"res/icons/table/attributes_free__fu.gif";s:4:"210d";s:38:"res/icons/table/attributes_free__h.gif";s:4:"a19a";s:39:"res/icons/table/attributes_free__hf.gif";s:4:"a19a";s:40:"res/icons/table/attributes_free__hfu.gif";s:4:"9d96";s:39:"res/icons/table/attributes_free__ht.gif";s:4:"3953";s:40:"res/icons/table/attributes_free__htf.gif";s:4:"3953";s:41:"res/icons/table/attributes_free__htfu.gif";s:4:"26bf";s:40:"res/icons/table/attributes_free__htu.gif";s:4:"26bf";s:39:"res/icons/table/attributes_free__hu.gif";s:4:"9d96";s:38:"res/icons/table/attributes_free__t.gif";s:4:"82e2";s:39:"res/icons/table/attributes_free__tf.gif";s:4:"82e2";s:40:"res/icons/table/attributes_free__tfu.gif";s:4:"d342";s:39:"res/icons/table/attributes_free__tu.gif";s:4:"d342";s:38:"res/icons/table/attributes_free__u.gif";s:4:"7d06";s:38:"res/icons/table/attributes_free__x.gif";s:4:"b144";s:35:"res/icons/table/attributes_list.gif";s:4:"9eee";s:38:"res/icons/table/attributes_list__d.gif";s:4:"34c8";s:38:"res/icons/table/attributes_list__f.gif";s:4:"ece1";s:39:"res/icons/table/attributes_list__fu.gif";s:4:"dac9";s:38:"res/icons/table/attributes_list__h.gif";s:4:"5976";s:39:"res/icons/table/attributes_list__hf.gif";s:4:"5976";s:40:"res/icons/table/attributes_list__hfu.gif";s:4:"39e1";s:39:"res/icons/table/attributes_list__ht.gif";s:4:"0816";s:40:"res/icons/table/attributes_list__htf.gif";s:4:"0816";s:41:"res/icons/table/attributes_list__htfu.gif";s:4:"04a3";s:40:"res/icons/table/attributes_list__htu.gif";s:4:"04a3";s:39:"res/icons/table/attributes_list__hu.gif";s:4:"39e1";s:38:"res/icons/table/attributes_list__t.gif";s:4:"f765";s:39:"res/icons/table/attributes_list__tf.gif";s:4:"f765";s:40:"res/icons/table/attributes_list__tfu.gif";s:4:"8c13";s:39:"res/icons/table/attributes_list__tu.gif";s:4:"8c13";s:38:"res/icons/table/attributes_list__u.gif";s:4:"89f0";s:38:"res/icons/table/attributes_list__x.gif";s:4:"9ad9";s:27:"res/icons/table/baskets.gif";s:4:"9c34";s:30:"res/icons/table/baskets__x.gif";s:4:"be4e";s:30:"res/icons/table/categories.gif";s:4:"7a56";s:33:"res/icons/table/categories__d.gif";s:4:"31ca";s:33:"res/icons/table/categories__f.gif";s:4:"66c4";s:34:"res/icons/table/categories__fu.gif";s:4:"93cf";s:33:"res/icons/table/categories__h.gif";s:4:"7179";s:34:"res/icons/table/categories__hf.gif";s:4:"7179";s:35:"res/icons/table/categories__hfu.gif";s:4:"8e19";s:34:"res/icons/table/categories__ht.gif";s:4:"1ab1";s:36:"res/icons/table/categories__htfu.gif";s:4:"47a9";s:35:"res/icons/table/categories__htu.gif";s:4:"47a9";s:34:"res/icons/table/categories__hu.gif";s:4:"8e19";s:33:"res/icons/table/categories__t.gif";s:4:"d765";s:34:"res/icons/table/categories__tf.gif";s:4:"d765";s:35:"res/icons/table/categories__tfu.gif";s:4:"0f62";s:34:"res/icons/table/categories__tu.gif";s:4:"0f62";s:33:"res/icons/table/categories__u.gif";s:4:"df7a";s:33:"res/icons/table/categories__x.gif";s:4:"5db2";s:35:"res/icons/table/commerce_folder.gif";s:4:"c469";s:38:"res/icons/table/commerce_folder__h.gif";s:4:"2ddb";s:35:"res/icons/table/commerce_globus.gif";s:4:"e472";s:25:"res/icons/table/dummy.gif";s:4:"a38a";s:32:"res/icons/table/manufacturer.gif";s:4:"e596";s:35:"res/icons/table/manufacturer__d.gif";s:4:"730a";s:35:"res/icons/table/manufacturer__h.gif";s:4:"21f9";s:35:"res/icons/table/manufacturer__x.gif";s:4:"cec7";s:34:"res/icons/table/moveordermails.gif";s:4:"4422";s:37:"res/icons/table/moveordermails__f.gif";s:4:"0f79";s:38:"res/icons/table/moveordermails__fu.gif";s:4:"9ea0";s:37:"res/icons/table/moveordermails__h.gif";s:4:"ba63";s:38:"res/icons/table/moveordermails__hf.gif";s:4:"ba63";s:39:"res/icons/table/moveordermails__hfu.gif";s:4:"836c";s:38:"res/icons/table/moveordermails__ht.gif";s:4:"2943";s:39:"res/icons/table/moveordermails__htf.gif";s:4:"2943";s:40:"res/icons/table/moveordermails__htfu.gif";s:4:"217c";s:39:"res/icons/table/moveordermails__htu.gif";s:4:"217c";s:38:"res/icons/table/moveordermails__hu.gif";s:4:"836c";s:37:"res/icons/table/moveordermails__t.gif";s:4:"35e6";s:38:"res/icons/table/moveordermails__tf.gif";s:4:"35e6";s:39:"res/icons/table/moveordermails__tfu.gif";s:4:"df82";s:38:"res/icons/table/moveordermails__tu.gif";s:4:"df82";s:37:"res/icons/table/moveordermails__u.gif";s:4:"95c7";s:37:"res/icons/table/moveordermails__x.gif";s:4:"bc28";s:30:"res/icons/table/newclients.gif";s:4:"9c62";s:33:"res/icons/table/newclients__x.gif";s:4:"c4f7";s:42:"res/icons/table/nn_articles_attributes.gif";s:4:"a38a";s:44:"res/icons/table/nn_categories_attributes.gif";s:4:"a38a";s:44:"res/icons/table/nn_categories_categories.gif";s:4:"a38a";s:42:"res/icons/table/nn_products_attributes.gif";s:4:"a38a";s:42:"res/icons/table/nn_products_categories.gif";s:4:"a38a";s:34:"res/icons/table/order_articles.gif";s:4:"af66";s:37:"res/icons/table/order_articles__x.gif";s:4:"1ca3";s:31:"res/icons/table/order_types.gif";s:4:"a38a";s:34:"res/icons/table/order_types__d.gif";s:4:"ed7f";s:34:"res/icons/table/order_types__x.gif";s:4:"385c";s:26:"res/icons/table/orders.gif";s:4:"1758";s:29:"res/icons/table/orders__d.gif";s:4:"296b";s:29:"res/icons/table/orders__x.gif";s:4:"8e40";s:30:"res/icons/table/orders_add.gif";s:4:"5f8a";s:34:"res/icons/table/orders_add_int.gif";s:4:"cac0";s:35:"res/icons/table/orders_add_user.gif";s:4:"bb07";s:39:"res/icons/table/orders_add_user_int.gif";s:4:"7582";s:30:"res/icons/table/orders_int.gif";s:4:"899b";s:31:"res/icons/table/orders_user.gif";s:4:"549d";s:35:"res/icons/table/orders_user_int.gif";s:4:"ea92";s:25:"res/icons/table/price.gif";s:4:"20b9";s:28:"res/icons/table/price__d.gif";s:4:"7ad1";s:28:"res/icons/table/price__f.gif";s:4:"f44a";s:29:"res/icons/table/price__fu.gif";s:4:"2232";s:28:"res/icons/table/price__h.gif";s:4:"d47c";s:29:"res/icons/table/price__hf.gif";s:4:"d47c";s:30:"res/icons/table/price__hfu.gif";s:4:"3c6d";s:29:"res/icons/table/price__ht.gif";s:4:"e3f9";s:30:"res/icons/table/price__htf.gif";s:4:"e3f9";s:31:"res/icons/table/price__htfu.gif";s:4:"4633";s:30:"res/icons/table/price__htu.gif";s:4:"4633";s:29:"res/icons/table/price__hu.gif";s:4:"3c6d";s:28:"res/icons/table/price__t.gif";s:4:"7f36";s:29:"res/icons/table/price__tf.gif";s:4:"7f36";s:30:"res/icons/table/price__tfu.gif";s:4:"4f01";s:29:"res/icons/table/price__tu.gif";s:4:"4f01";s:28:"res/icons/table/price__u.gif";s:4:"930f";s:28:"res/icons/table/price__x.gif";s:4:"fd1d";s:28:"res/icons/table/products.gif";s:4:"5d89";s:31:"res/icons/table/products__d.gif";s:4:"4c36";s:31:"res/icons/table/products__f.gif";s:4:"f715";s:32:"res/icons/table/products__fu.gif";s:4:"5274";s:31:"res/icons/table/products__h.gif";s:4:"9254";s:32:"res/icons/table/products__hf.gif";s:4:"9254";s:33:"res/icons/table/products__hfu.gif";s:4:"7969";s:32:"res/icons/table/products__ht.gif";s:4:"b7f0";s:33:"res/icons/table/products__htf.gif";s:4:"b7f0";s:34:"res/icons/table/products__htfu.gif";s:4:"b53e";s:33:"res/icons/table/products__htu.gif";s:4:"b53e";s:32:"res/icons/table/products__hu.gif";s:4:"7969";s:31:"res/icons/table/products__t.gif";s:4:"3a65";s:32:"res/icons/table/products__tf.gif";s:4:"3a65";s:33:"res/icons/table/products__tfu.gif";s:4:"4529";s:32:"res/icons/table/products__tu.gif";s:4:"4529";s:31:"res/icons/table/products__u.gif";s:4:"4408";s:31:"res/icons/table/products__x.gif";s:4:"b5ae";s:32:"res/icons/table/salesfigures.gif";s:4:"0c75";s:35:"res/icons/table/salesfigures__x.gif";s:4:"c4f7";s:28:"res/icons/table/supplier.gif";s:4:"5676";s:31:"res/icons/table/supplier__d.gif";s:4:"25d0";s:31:"res/icons/table/supplier__h.gif";s:4:"b251";s:31:"res/icons/table/supplier__x.gif";s:4:"260b";s:28:"res/icons/table/tracking.gif";s:4:"a17a";s:31:"res/icons/table/tracking__x.gif";s:4:"c865";s:34:"res/icons/table/tracking_codes.gif";s:4:"18e7";s:37:"res/icons/table/tracking_codes__x.gif";s:4:"635c";s:31:"res/icons/table/user_states.gif";s:4:"a38a";s:34:"res/icons/table/user_states__d.gif";s:4:"ed7f";s:34:"res/icons/table/user_states__x.gif";s:4:"385c";s:21:"res/logo/commerce.jpg";s:4:"576a";s:22:"res/logo/mail_logo.gif";s:4:"90dd";s:20:"static/constants.txt";s:4:"a2e3";s:16:"static/setup.txt";s:4:"736b";s:16:"tcafiles/tca.php";s:4:"85f0";s:37:"tcafiles/tx_commerce_articles.tca.php";s:4:"c19c";s:45:"tcafiles/tx_commerce_attribute_values.tca.php";s:4:"78ae";s:39:"tcafiles/tx_commerce_attributes.tca.php";s:4:"fb72";s:36:"tcafiles/tx_commerce_baskets.tca.php";s:4:"eb62";s:39:"tcafiles/tx_commerce_categories.tca.php";s:4:"ccc4";s:41:"tcafiles/tx_commerce_manufacturer.tca.php";s:4:"0f9f";s:43:"tcafiles/tx_commerce_moveordermails.tca.php";s:4:"57b9";s:39:"tcafiles/tx_commerce_newclients.tca.php";s:4:"ce95";s:43:"tcafiles/tx_commerce_order_articles.tca.php";s:4:"a693";s:35:"tcafiles/tx_commerce_orders.tca.php";s:4:"f6bb";s:37:"tcafiles/tx_commerce_products.tca.php";s:4:"4d0a";s:41:"tcafiles/tx_commerce_salesfigures.tca.php";s:4:"e8ab";s:37:"tcafiles/tx_commerce_supplier.tca.php";s:4:"e08b";s:37:"tcafiles/tx_commerce_tracking.tca.php";s:4:"305a";s:25:"tree/class.browsetree.php";s:4:"a6d2";s:23:"tree/class.langbase.php";s:4:"8f85";s:19:"tree/class.leaf.php";s:4:"2de1";s:23:"tree/class.leafData.php";s:4:"270c";s:25:"tree/class.leafMaster.php";s:4:"78e7";s:29:"tree/class.leafMasterData.php";s:4:"8ef0";s:24:"tree/class.leafSlave.php";s:4:"e24e";s:28:"tree/class.leafSlaveData.php";s:4:"1abb";s:23:"tree/class.leafView.php";s:4:"142b";s:21:"tree/class.mounts.php";s:4:"7890";s:44:"treelib/class.tx_commerce_categorymounts.php";s:4:"8c05";s:42:"treelib/class.tx_commerce_categorytree.php";s:4:"2bcf";s:42:"treelib/class.tx_commerce_leaf_article.php";s:4:"8ead";s:46:"treelib/class.tx_commerce_leaf_articledata.php";s:4:"3be1";s:46:"treelib/class.tx_commerce_leaf_articleview.php";s:4:"c0d7";s:43:"treelib/class.tx_commerce_leaf_category.php";s:4:"15b9";s:47:"treelib/class.tx_commerce_leaf_categorydata.php";s:4:"b829";s:47:"treelib/class.tx_commerce_leaf_categoryview.php";s:4:"8adc";s:42:"treelib/class.tx_commerce_leaf_product.php";s:4:"5942";s:46:"treelib/class.tx_commerce_leaf_productdata.php";s:4:"554c";s:46:"treelib/class.tx_commerce_leaf_productview.php";s:4:"f776";s:37:"treelib/class.tx_commerce_tcefunc.php";s:4:"12db";s:45:"treelib/class.tx_commerce_treelib_browser.php";s:4:"6a6e";s:46:"treelib/class.tx_commerce_treelib_tceforms.php";s:4:"6475";s:47:"treelib/link/class.tx_commerce_categorytree.php";s:4:"7896";s:52:"treelib/link/class.tx_commerce_leaf_categoryview.php";s:4:"e043";s:51:"treelib/link/class.tx_commerce_leaf_productview.php";s:4:"e570";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'tt_address' => '2.1.0-',
			'dynaflex' => '1.13.2-',
			'moneylib' => '1.3.0-',
			'static_info_tables' => '2.0.0-',
			'php' => '5.3.0-0.0.0',
			'typo3' => '4.5.0-0.0.0',
		),
		'conflicts' => array(
			'mc_autokeywords' => '',
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>