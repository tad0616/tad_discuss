<?php
$adminmenu = [
    ['title' => _MI_TADDISCUS_ADMENU1, 'link' => 'admin/main.php', 'icon' => 'images/admin/chat.png'],
    ['title' => _MI_TADDISCUS_ADMENU3, 'link' => 'admin/groupperm.php', 'icon' => 'images/admin/keys.png'],
    ['title' => _MI_TADDISCUS_ADMENU6, 'link' => 'admin/spam.php', 'icon' => 'images/admin/spam.png'],
    ['title' => _MI_TADDISCUS_ADMENU5, 'link' => 'admin/cbox_setup.php', 'icon' => 'images/admin/book_edit.png'],
];

if (file_exists(XOOPS_ROOT_PATH . '/modules/xforum/xoops_version.php')) {
    $adminmenu[] = ['title' => _MI_TADDISCUS_ADMENU2, 'link' => 'admin/copybb.php', 'icon' => 'images/admin/synchronized.png'];
}

if (file_exists(XOOPS_ROOT_PATH . '/modules/newbb/xoops_version.php')) {
    $adminmenu[] = ['title' => _MI_TADDISCUS_ADMENU7, 'link' => 'admin/copynewbb.php', 'icon' => 'images/admin/synchronized.png'];
}

if (file_exists(XOOPS_ROOT_PATH . '/modules/tad_cbox/xoops_version.php')) {
    $adminmenu[] = ['title' => _MI_TADDISCUS_ADMENU4, 'link' => 'admin/copycbox.php', 'icon' => 'images/admin/synchronized.png'];
}

$adminmenu[] = ['title' => _MI_TAD_ADMIN_ABOUT, 'link' => 'admin/about.php', 'icon' => 'images/admin/about.png'];
