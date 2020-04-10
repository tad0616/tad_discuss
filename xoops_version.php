<?php
$modversion = [];

//---模組基本資訊---//
$modversion['name'] = _MI_TADDISCUS_NAME;
$modversion['version'] = '2.54';
$modversion['description'] = _MI_TADDISCUS_DESC;
$modversion['author'] = _MI_TADDISCUS_AUTHOR;
$modversion['credits'] = 'geek01';
$modversion['help'] = 'page=help';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2020/04/10';
$modversion['module_website_url'] = 'https://tad0616.net';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5';

//---paypal資訊---//
$modversion['paypal'] = [];
$modversion['paypal']['business'] = 'tad0616@gmail.com';
$modversion['paypal']['item_name'] = 'Donation : ' . _MI_TAD_WEB;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][1] = 'tad_discuss';
$modversion['tables'][2] = 'tad_discuss_board';
$modversion['tables'][3] = 'tad_discuss_files_center';
$modversion['tables'][4] = 'tad_discuss_cbox_setup';

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

//---安裝設定---//
$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/tad_discuss_search.php';
$modversion['search']['func'] = 'tad_discuss_search';

//---樣板設定---//
$modversion['templates'] = [];
$i = 1;
$modversion['templates'][$i]['file'] = 'tad_discuss_index.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_index.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_discuss.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_discuss.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_main.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_main.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_groupperm.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_groupperm.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_copybb.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_copybb.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_copynewbb.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_copynewbb.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_spam.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_spam.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_form.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_form.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_mobile.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_mobile.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_talk_bubble.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_talk_bubble.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_clean.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_clean.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_talk_bubble_vertical.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_talk_bubble_vertical.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_copycbox.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_copycbox.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_adm_cbox_setup.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_adm_cbox_setup.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tad_discuss_bootstrap.tpl';
$modversion['templates'][$i]['description'] = 'tad_discuss_bootstrap.tpl';

//---區塊設定---//
$modversion['blocks'][1]['file'] = 'tad_discuss_new.php';
$modversion['blocks'][1]['name'] = _MI_TADDISCUS_BNAME1;
$modversion['blocks'][1]['description'] = _MI_TADDISCUS_BDESC1;
$modversion['blocks'][1]['show_func'] = 'tad_discuss_new';
$modversion['blocks'][1]['template'] = 'tad_discuss_new.tpl';
$modversion['blocks'][1]['edit_func'] = 'tad_discuss_new_edit';
$modversion['blocks'][1]['options'] = '10';

$modversion['blocks'][2]['file'] = 'tad_discuss_hot.php';
$modversion['blocks'][2]['name'] = _MI_TADDISCUS_BNAME2;
$modversion['blocks'][2]['description'] = _MI_TADDISCUS_BDESC2;
$modversion['blocks'][2]['show_func'] = 'tad_discuss_hot';
$modversion['blocks'][2]['template'] = 'tad_discuss_hot.tpl';
$modversion['blocks'][2]['edit_func'] = 'tad_discuss_hot_edit';
$modversion['blocks'][2]['options'] = '10|30';

$modversion['blocks'][3]['file'] = 'tad_discuss_cbox.php';
$modversion['blocks'][3]['name'] = _MI_TADDISCUS_BNAME3;
$modversion['blocks'][3]['description'] = _MI_TADDISCUS_BDESC3;
$modversion['blocks'][3]['show_func'] = 'tad_discuss_cbox';
$modversion['blocks'][3]['template'] = 'tad_discuss_cbox.tpl';
$modversion['blocks'][3]['edit_func'] = 'tad_discuss_cbox_edit';
$modversion['blocks'][3]['options'] = '|350|#B4C58D|#FFFFFF|#000000|0';

$i = 0;
$modversion['config'][$i]['name'] = 'display_mode';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_DISPLAY_MODE';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_DISPLAY_MODE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'bootstrap';
$modversion['config'][$i]['options'] = [_MI_TADDISCUS_CONF0_OPT1 => 'default', _MI_TADDISCUS_CONF0_OPT2 => 'left', _MI_TADDISCUS_CONF0_OPT3 => 'top', _MI_TADDISCUS_CONF0_OPT6 => 'bottom', _MI_TADDISCUS_CONF0_OPT4 => 'mobile', _MI_TADDISCUS_CONF0_OPT5 => 'clean', _MI_TADDISCUS_CONF0_OPT7 => 'bootstrap'];

$i++;
$modversion['config'][$i]['name'] = 'show_discuss_amount';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '20';

$i++;
$modversion['config'][$i]['name'] = 'show_bubble_amount';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '20';

$i++;
$modversion['config'][$i]['name'] = 'use_pda';
$modversion['config'][$i]['title'] = '_MI_USE_PDA_TITLE';
$modversion['config'][$i]['description'] = '_MI_USE_PDA_TITLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';

$i++;
$modversion['config'][$i]['name'] = 'spam_keyword';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_SPAM_KEYWORD';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_SPAM_KEYWORD_DESC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = _MI_TADDISCUS_SPAM_KEYWORD_DEFAULT;

$i++;
$modversion['config'][$i]['name'] = 'display_fast_setup';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_DISPLAY_FAST_SETUP';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_DISPLAY_FAST_SETUP_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'display_number';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_DISPLAY_NUMBER';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_DISPLAY_NUMBER_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '7';

$i++;
$modversion['config'][$i]['name'] = 'show_sig';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_SHOW_SIG';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_SHOW_SIG_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';

$i++;
$modversion['config'][$i]['name'] = 'sig_style';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_SIG_STYLE';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_SIG_STYLE_DESC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'font-size: 75%; color: gray; border-top: 1px dashed gray; padding: 10px 0px; margin: 10px 0xp;';

$i++;
$modversion['config'][$i]['name'] = 'show_like';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_SHOW_LIKE';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_SHOW_LIKE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';

$i++;
$modversion['config'][$i]['name'] = 'def_editor';
$modversion['config'][$i]['title'] = '_MI_TADDISCUS_DEF_EDITOR';
$modversion['config'][$i]['description'] = '_MI_TADDISCUS_DEF_EDITOR_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'nicEditor';
$modversion['config'][$i]['options'] = ['nicEditor' => 'nicEditor', 'CKEditor' => 'CKEditor'];

//---模組通知設定---//
$modversion['hasNotification'] = 1;

$modversion['notification']['category'][1]['name'] = 'global';
$modversion['notification']['category'][1]['title'] = _MI_TADDISCUS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description'] = _MI_TADDISCUS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['subscribe_from'] = ['index.php', 'discuss.php'];

$modversion['notification']['category'][2]['name'] = 'board';
$modversion['notification']['category'][2]['title'] = _MI_TADDISCUS_BOARD_NOTIFY;
$modversion['notification']['category'][2]['description'] = _MI_TADDISCUS_BOARD_NOTIFY;
$modversion['notification']['category'][2]['subscribe_from'] = ['discuss.php'];
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
