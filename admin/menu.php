<?php
$adminmenu = [];
$icon_dir = '2.6' === mb_substr(XOOPS_VERSION, 6, 3) ? '' : 'images/';

$i = 1;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_HOME;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_HOME_DESC;
$adminmenu[$i]['icon'] = 'images/admin/home.png';

$i++;
$adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU1;
$adminmenu[$i]['link'] = 'admin/main.php';
$adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU1;
$adminmenu[$i]['icon'] = 'images/admin/chat.png';

$i++;
$adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU3;
$adminmenu[$i]['link'] = 'admin/groupperm.php';
$adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU3;
$adminmenu[$i]['icon'] = 'images/admin/keys.png';

$i++;
$adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU6;
$adminmenu[$i]['link'] = 'admin/spam.php';
$adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU6;
$adminmenu[$i]['icon'] = 'images/admin/spam.png';

$i++;
$adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU5;
$adminmenu[$i]['link'] = 'admin/cbox_setup.php';
$adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU5;
$adminmenu[$i]['icon'] = 'images/admin/book_edit.png';

if (file_exists(XOOPS_ROOT_PATH . '/modules/xforum/xoops_version.php')) {
    $i++;
    $adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU2;
    $adminmenu[$i]['link'] = 'admin/copybb.php';
    $adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU2;
    $adminmenu[$i]['icon'] = 'images/admin/synchronized.png';
}

if (file_exists(XOOPS_ROOT_PATH . '/modules/newbb/xoops_version.php')) {
    $i++;
    $adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU7;
    $adminmenu[$i]['link'] = 'admin/copynewbb.php';
    $adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU7;
    $adminmenu[$i]['icon'] = 'images/admin/synchronized.png';
}

if (file_exists(XOOPS_ROOT_PATH . '/modules/tad_cbox/xoops_version.php')) {
    $i++;
    $adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU4;
    $adminmenu[$i]['link'] = 'admin/copycbox.php';
    $adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU4;
    $adminmenu[$i]['icon'] = 'images/admin/synchronized.png';
}

$i++;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon'] = 'images/admin/about.png';
