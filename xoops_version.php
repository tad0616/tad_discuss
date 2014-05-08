<?php
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_TADDISCUS_NAME;
$modversion['version']	= '1.4';
$modversion['description'] = _MI_TADDISCUS_DESC;
$modversion['author'] = _MI_TADDISCUS_AUTHOR;
$modversion['credits']	= 'geek01';
$modversion['help'] = 'page=help';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(dirname(__FILE__));


//---模組狀態資訊---//
$modversion['release_date'] = '2014-02-20';
$modversion['module_website_url'] = 'http://tad0616.net';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'http://tad0616.net';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php']= 5.2;
$modversion['min_xoops']='2.5';

//---paypal資訊---//
$modversion ['paypal'] = array();
$modversion ['paypal']['business'] = 'tad0616@gmail.com';
$modversion ['paypal']['item_name'] = 'Donation : ' . _MI_TAD_WEB;
$modversion ['paypal']['amount'] = 0;
$modversion ['paypal']['currency_code'] = 'USD';


//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "tad_discuss";
$modversion['tables'][2] = "tad_discuss_board";
$modversion['tables'][3] = "tad_discuss_files_center";
$modversion['tables'][4] = "tad_discuss_cbox_setup";

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

//---安裝設定---//
$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/tad_discuss_search.php";
$modversion['search']['func'] = "tad_discuss_search";

//---樣板設定---//
$modversion['templates'] = array();
$i=1;
$modversion['templates'][$i]['file'] = 'tad_discuss_index_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_index_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_discuss_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_discuss_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_main.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_main.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_groupperm.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_groupperm.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_copybb.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_copybb.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_copynewbb.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_copynewbb.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_spam.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_spam.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_form_tpl.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_form_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_form.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_form.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_mobile.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_mobile.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_talk_bubble.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_talk_bubble.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_clean.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_clean.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_talk_bubble_vertical.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_talk_bubble_vertical.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_copycbox.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_copycbox.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_cbox_setup.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_cbox_setup.html';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_bootstrap.html';
$modversion['templates'][$i]['description'] = 'tad_discuss_bootstrap.html';


//---區塊設定---//
$modversion['blocks'][1]['file'] = "tad_discuss_new.php";
$modversion['blocks'][1]['name'] = _MI_TADDISCUS_BNAME1;
$modversion['blocks'][1]['description'] = _MI_TADDISCUS_BDESC1;
$modversion['blocks'][1]['show_func'] = "tad_discuss_new";
$modversion['blocks'][1]['template'] = "tad_discuss_new.html";
$modversion['blocks'][1]['edit_func'] = "tad_discuss_new_edit";
$modversion['blocks'][1]['options'] = "10";

$modversion['blocks'][2]['file'] = "tad_discuss_hot.php";
$modversion['blocks'][2]['name'] = _MI_TADDISCUS_BNAME2;
$modversion['blocks'][2]['description'] = _MI_TADDISCUS_BDESC2;
$modversion['blocks'][2]['show_func'] = "tad_discuss_hot";
$modversion['blocks'][2]['template'] = "tad_discuss_hot.html";
$modversion['blocks'][2]['edit_func'] = "tad_discuss_hot_edit";
$modversion['blocks'][2]['options'] = "10|30";

$modversion['blocks'][3]['file'] = "tad_discuss_cbox.php";
$modversion['blocks'][3]['name'] = _MI_TADDISCUS_BNAME3;
$modversion['blocks'][3]['description'] = _MI_TADDISCUS_BDESC3;
$modversion['blocks'][3]['show_func'] = "tad_discuss_cbox";
$modversion['blocks'][3]['template'] = "tad_discuss_cbox.html";
$modversion['blocks'][3]['edit_func'] = "tad_discuss_cbox_edit";
$modversion['blocks'][3]['options'] = "|350|#B4C58D|#FFFFFF|#000000|0";

$modversion['config'][0]['name']	= 'display_mode';
$modversion['config'][0]['title']	= '_MI_TADDISCUS_DISPLAY_MODE';
$modversion['config'][0]['description']	= '_MI_TADDISCUS_DISPLAY_MODE_DESC';
$modversion['config'][0]['formtype']	= 'select';
$modversion['config'][0]['valuetype']	= 'text';
$modversion['config'][0]['default']	= 'bootstrap';
$modversion['config'][0]['options']	= array(_MI_TADDISCUS_CONF0_OPT1 => 'default',_MI_TADDISCUS_CONF0_OPT2 => 'left',_MI_TADDISCUS_CONF0_OPT3 => 'top',_MI_TADDISCUS_CONF0_OPT6 => 'bottom',_MI_TADDISCUS_CONF0_OPT4 => 'mobile',_MI_TADDISCUS_CONF0_OPT5 => 'clean',_MI_TADDISCUS_CONF0_OPT7 => 'bootstrap');

$modversion['config'][1]['name']	= 'show_discuss_amount';
$modversion['config'][1]['title']	= '_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT';
$modversion['config'][1]['description']	= '_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT_DESC';
$modversion['config'][1]['formtype']	= 'textbox';
$modversion['config'][1]['valuetype']	= 'int';
$modversion['config'][1]['default']	= '20';

$modversion['config'][2]['name']	= 'show_bubble_amount';
$modversion['config'][2]['title']	= '_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT';
$modversion['config'][2]['description']	= '_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT_DESC';
$modversion['config'][2]['formtype']	= 'textbox';
$modversion['config'][2]['valuetype']	= 'int';
$modversion['config'][2]['default']	= '20';

$modversion['config'][3]['name'] = 'use_pda';
$modversion['config'][3]['title'] = '_MI_USE_PDA_TITLE';
$modversion['config'][3]['description'] = '_MI_USE_PDA_TITLE_DESC';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = '1';

$modversion['config'][4]['name']  = 'spam_keyword';
$modversion['config'][4]['title'] = '_MI_TADDISCUS_SPAM_KEYWORD';
$modversion['config'][4]['description'] = '_MI_TADDISCUS_SPAM_KEYWORD_DESC';
$modversion['config'][4]['formtype']  = 'textarea';
$modversion['config'][4]['valuetype'] = 'text';
$modversion['config'][4]['default'] = _MI_TADDISCUS_SPAM_KEYWORD_DEFAULT;

$modversion['config'][5]['name']  = 'display_fast_setup';
$modversion['config'][5]['title'] = '_MI_TADDISCUS_DISPLAY_FAST_SETUP';
$modversion['config'][5]['description'] = '_MI_TADDISCUS_DISPLAY_FAST_SETUP_DESC';
$modversion['config'][5]['formtype']  = 'yesno';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = '0';




//---模組通知設定---//
$modversion['hasNotification'] = 1;

$modversion['notification']['category'][1]['name'] = 'global';
$modversion['notification']['category'][1]['title'] = _MI_TADDISCUS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description'] =  _MI_TADDISCUS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php','discuss.php');


$modversion['notification']['category'][2]['name'] = 'board';
$modversion['notification']['category'][2]['title'] = _MI_TADDISCUS_BOARD_NOTIFY;
$modversion['notification']['category'][2]['description'] =  _MI_TADDISCUS_BOARD_NOTIFY;
$modversion['notification']['category'][2]['subscribe_from'] = array('discuss.php');
$modversion['notification']['category'][2]['item_name'] = 'BoardID';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;


$modversion['notification']['event'][1]['name'] = 'new_discuss';
$modversion['notification']['event'][1]['category'] = 'global';
$modversion['notification']['event'][1]['title'] = _MI_TADDISCUS_GLOBAL_NOTIFY_ME;
$modversion['notification']['event'][1]['caption'] = _MI_TADDISCUS_GLOBAL_NOTIFY_ME;
$modversion['notification']['event'][1]['description'] = _MI_TADDISCUS_GLOBAL_NOTIFY_ME;
$modversion['notification']['event'][1]['mail_template'] = 'new_discuss';
$modversion['notification']['event'][1]['mail_subject'] = _MI_TADDISCUS_GLOBAL_NOTIFY_SUBJECT;
$modversion['notification']['event'][1]['admin_only'] = '0';
$modversion['notification']['event'][1]['invisible'] = '0';

$modversion['notification']['event'][2]['name'] = 'new_board_discuss';
$modversion['notification']['event'][2]['category'] = 'board';
$modversion['notification']['event'][2]['title'] = _MI_TADDISCUS_BOARD_NOTIFY_ME;
$modversion['notification']['event'][2]['caption'] = _MI_TADDISCUS_BOARD_NOTIFY_ME;
$modversion['notification']['event'][2]['description'] = _MI_TADDISCUS_BOARD_NOTIFY_ME;
$modversion['notification']['event'][2]['mail_template'] = 'new_board_discuss';
$modversion['notification']['event'][2]['mail_subject'] = _MI_TADDISCUS_BOARD_NOTIFY_SUBJECT;
$modversion['notification']['event'][2]['admin_only'] = '0';
$modversion['notification']['event'][2]['invisible'] = '0';

?>