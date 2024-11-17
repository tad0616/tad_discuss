<?php
$modversion = [];
global $xoopsConfig;

//---模組基本資訊---//
$modversion['name'] = _MI_TADDISCUS_NAME;
$modversion['version'] = $_SESSION['xoops_version'] >= 20511 ? '3.0.0-Stable' : '3.0';
// $modversion['version'] = '2.58';
$modversion['description'] = _MI_TADDISCUS_DESC;
$modversion['author'] = _MI_TADDISCUS_AUTHOR;
$modversion['credits'] = 'geek01';
$modversion['help'] = 'page=help';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2024-11-18';
$modversion['module_website_url'] = 'https://tad0616.net';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5.10';

//---paypal資訊---//
$modversion['paypal'] = [
    'business' => 'tad0616@gmail.com',
    'item_name' => 'Donation : ' . _MI_TAD_WEB,
    'amount' => 0,
    'currency_code' => 'USD',
];

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = [
    'tad_discuss',
    'tad_discuss_board',
    'tad_discuss_files_center',
    'tad_discuss_cbox_setup',
];

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
$modversion['templates'] = [
    ['file' => 'tad_discuss_index.tpl', 'description' => 'tad_discuss_index.tpl'],
    ['file' => 'tad_discuss_discuss.tpl', 'description' => 'tad_discuss_discuss.tpl'],
    ['file' => 'tad_discuss_adm_main.tpl', 'description' => 'tad_discuss_adm_main.tpl'],
    ['file' => 'tad_discuss_adm_groupperm.tpl', 'description' => 'tad_discuss_adm_groupperm.tpl'],
    ['file' => 'tad_discuss_adm_copybb.tpl', 'description' => 'tad_discuss_adm_copybb.tpl'],
    ['file' => 'tad_discuss_adm_copynewbb.tpl', 'description' => 'tad_discuss_adm_copynewbb.tpl'],
    ['file' => 'tad_discuss_adm_spam.tpl', 'description' => 'tad_discuss_adm_spam.tpl'],
    ['file' => 'tad_discuss_form.tpl', 'description' => 'tad_discuss_form.tpl'],
    ['file' => 'tad_discuss_mobile.tpl', 'description' => 'tad_discuss_mobile.tpl'],
    ['file' => 'tad_discuss_talk_bubble.tpl', 'description' => 'tad_discuss_talk_bubble.tpl'],
    ['file' => 'tad_discuss_clean.tpl', 'description' => 'tad_discuss_clean.tpl'],
    ['file' => 'tad_discuss_talk_bubble_vertical.tpl', 'description' => 'tad_discuss_talk_bubble_vertical.tpl'],
    ['file' => 'tad_discuss_adm_copycbox.tpl', 'description' => 'tad_discuss_adm_copycbox.tpl'],
    ['file' => 'tad_discuss_adm_cbox_setup.tpl', 'description' => 'tad_discuss_adm_cbox_setup.tpl'],
    ['file' => 'tad_discuss_bootstrap.tpl', 'description' => 'tad_discuss_bootstrap.tpl'],
];

//---區塊設定 (索引為固定值，若欲刪除區塊記得補上索引，避免區塊重複)---//
$modversion['blocks'] = [
    1 => [
        'file' => 'tad_discuss_new.php',
        'name' => _MI_TADDISCUS_BNAME1,
        'description' => _MI_TADDISCUS_BDESC1,
        'show_func' => 'tad_discuss_new',
        'template' => 'tad_discuss_new.tpl',
        'edit_func' => 'tad_discuss_new_edit',
        'options' => '10',
    ],
    [
        'file' => 'tad_discuss_hot.php',
        'name' => _MI_TADDISCUS_BNAME2,
        'description' => _MI_TADDISCUS_BDESC2,
        'show_func' => 'tad_discuss_hot',
        'template' => 'tad_discuss_hot.tpl',
        'edit_func' => 'tad_discuss_hot_edit',
        'options' => '10|30',
    ],
    [
        'file' => 'tad_discuss_cbox.php',
        'name' => _MI_TADDISCUS_BNAME3,
        'description' => _MI_TADDISCUS_BDESC3,
        'show_func' => 'tad_discuss_cbox',
        'template' => 'tad_discuss_cbox.tpl',
        'edit_func' => 'tad_discuss_cbox_edit',
        'options' => '|350|#B4C58D|#FFFFFF|#000000|0',
    ],
];

$modversion['config'] = [
    [
        'name' => 'display_mode',
        'title' => '_MI_TADDISCUS_DISPLAY_MODE',
        'description' => '_MI_TADDISCUS_DISPLAY_MODE_DESC',
        'formtype' => 'select',
        'valuetype' => 'text',
        'default' => 'bootstrap',
        'options' => [
            _MI_TADDISCUS_CONF0_OPT1 => 'default',
            _MI_TADDISCUS_CONF0_OPT2 => 'left',
            _MI_TADDISCUS_CONF0_OPT3 => 'top',
            _MI_TADDISCUS_CONF0_OPT6 => 'bottom',
            _MI_TADDISCUS_CONF0_OPT4 => 'mobile',
            _MI_TADDISCUS_CONF0_OPT5 => 'clean',
            _MI_TADDISCUS_CONF0_OPT7 => 'bootstrap',
        ],
    ],
    [
        'name' => 'show_discuss_amount',
        'title' => '_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT',
        'description' => '_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT_DESC',
        'formtype' => 'textbox',
        'valuetype' => 'int',
        'default' => '20',
    ],
    [
        'name' => 'show_bubble_amount',
        'title' => '_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT',
        'description' => '_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT_DESC',
        'formtype' => 'textbox',
        'valuetype' => 'int',
        'default' => '20',
    ],
    [
        'name' => 'spam_keyword',
        'title' => '_MI_TADDISCUS_SPAM_KEYWORD',
        'description' => '_MI_TADDISCUS_SPAM_KEYWORD_DESC',
        'formtype' => 'textarea',
        'valuetype' => 'text',
        'default' => _MI_TADDISCUS_SPAM_KEYWORD_DEFAULT,
    ],
    [
        'name' => 'display_fast_setup',
        'title' => '_MI_TADDISCUS_DISPLAY_FAST_SETUP',
        'description' => '_MI_TADDISCUS_DISPLAY_FAST_SETUP_DESC',
        'formtype' => 'yesno',
        'valuetype' => 'int',
        'default' => '0',
    ],
    [
        'name' => 'display_number',
        'title' => '_MI_TADDISCUS_DISPLAY_NUMBER',
        'description' => '_MI_TADDISCUS_DISPLAY_NUMBER_DESC',
        'formtype' => 'text',
        'valuetype' => 'int',
        'default' => '7',
    ],
    [
        'name' => 'show_sig',
        'title' => '_MI_TADDISCUS_SHOW_SIG',
        'description' => '_MI_TADDISCUS_SHOW_SIG_DESC',
        'formtype' => 'yesno',
        'valuetype' => 'int',
        'default' => '1',
    ],
    [
        'name' => 'sig_style',
        'title' => '_MI_TADDISCUS_SIG_STYLE',
        'description' => '_MI_TADDISCUS_SIG_STYLE_DESC',
        'formtype' => 'textarea',
        'valuetype' => 'text',
        'default' => 'font-size: 75%; color: gray; border-top: 1px dashed gray; padding: 10px 0px; margin: 10px 0xp;',
    ],
    [
        'name' => 'show_like',
        'title' => '_MI_TADDISCUS_SHOW_LIKE',
        'description' => '_MI_TADDISCUS_SHOW_LIKE_DESC',
        'formtype' => 'yesno',
        'valuetype' => 'int',
        'default' => '1',
    ],
    [
        'name' => 'bad_group',
        'title' => '_MI_TADDISCUS_BAD_GROUP',
        'description' => '_MI_TADDISCUS_BAD_GROUP_DESC',
        'formtype' => 'group',
        'valuetype' => 'int',
    ],
];

//---模組通知設定---//
$modversion['hasNotification'] = 1;
$modversion['notification']['category'] = [
    [
        'name' => 'global',
        'title' => _MI_TADDISCUS_GLOBAL_NOTIFY,
        'description' => _MI_TADDISCUS_GLOBAL_NOTIFY,
        'subscribe_from' => ['index.php', 'discuss.php'],
    ],
    [
        'name' => 'board',
        'title' => _MI_TADDISCUS_BOARD_NOTIFY,
        'description' => _MI_TADDISCUS_BOARD_NOTIFY,
        'subscribe_from' => ['discuss.php'],
        'item_name' => 'BoardID',
        'allow_bookmark' => 1,
    ],
];

$modversion['notification']['event'] = [
    [
        'name' => 'new_discuss',
        'category' => 'global',
        'title' => _MI_TADDISCUS_GLOBAL_NOTIFY_ME,
        'caption' => _MI_TADDISCUS_GLOBAL_NOTIFY_ME,
        'description' => _MI_TADDISCUS_GLOBAL_NOTIFY_ME,
        'mail_template' => 'new_discuss',
        'mail_subject' => _MI_TADDISCUS_GLOBAL_NOTIFY_SUBJECT,
        'admin_only' => '0',
        'invisible' => '0',
    ],
    [
        'name' => 'new_board_discuss',
        'category' => 'board',
        'title' => _MI_TADDISCUS_BOARD_NOTIFY_ME,
        'caption' => _MI_TADDISCUS_BOARD_NOTIFY_ME,
        'description' => _MI_TADDISCUS_BOARD_NOTIFY_ME,
        'mail_template' => 'new_board_discuss',
        'mail_subject' => _MI_TADDISCUS_BOARD_NOTIFY_SUBJECT,
        'admin_only' => '0',
        'invisible' => '0',
    ],
];
