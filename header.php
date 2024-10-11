<?php
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

require_once __DIR__ . '/function.php';

//判斷是否對該模組有管理權限
if (!isset($_SESSION['tad_discuss_adm'])) {
    $_SESSION['tad_discuss_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_MD_TADDISCUS_SMNAME1] = 'index.php';
$interface_icon[_MD_TADDISCUS_SMNAME1] = 'fa-list-alt';

$interface_menu[_MD_TADDISCUS_SMNAME2] = 'all.php';
$interface_icon[_MD_TADDISCUS_SMNAME2] = 'fa-comments';
if ($xoopsUser and !empty($_GET['BoardID'])) {
    $interface_menu[_MD_TADDISCUS_ADD_DISCUSS] = "discuss.php?op=tad_discuss_form&BoardID={$_GET['BoardID']}";
    $interface_icon[_MD_TADDISCUS_ADD_DISCUSS] = "fa-plus";
}
