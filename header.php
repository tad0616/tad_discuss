<?php
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

require_once __DIR__ . '/function.php';

if ('1' == $xoopsModuleConfig['use_pda'] and false === mb_strpos($_SESSION['theme_kind'], 'bootstrap')) {
    Utility::mobile_device_detect(true, false, true, true, true, true, true, 'pda.php', false);
}

//判斷是否對該模組有管理權限
if (!isset($_SESSION['tad_discuss_adm'])) {
    $_SESSION['tad_discuss_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_MD_TADDISCUS_SMNAME1] = 'index.php';
$interface_menu[_MD_TADDISCUS_SMNAME2] = 'all.php';
if ($xoopsUser and !empty($_GET['BoardID'])) {
    $interface_menu[_MD_TADDISCUS_ADD_DISCUSS] = "discuss.php?op=tad_discuss_form&BoardID={$_GET['BoardID']}";
}

if ($_SESSION['tad_discuss_adm']) {
    $interface_menu[_TAD_TO_ADMIN] = 'admin/main.php';
}
