<?php
use Xmf\Request;
$BoardID = Request::getInt('BoardID');
//判斷是否對該模組有管理權限
if (!isset($_SESSION['tad_discuss_adm'])) {
    $_SESSION['tad_discuss_adm'] = isset($xoopsUser) && \is_object($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_MD_TADDISCUS_SMNAME1] = 'index.php';
$interface_icon[_MD_TADDISCUS_SMNAME1] = 'fa-list-alt';

$interface_menu[_MD_TADDISCUS_SMNAME2] = 'all.php';
$interface_icon[_MD_TADDISCUS_SMNAME2] = 'fa-comments';

if (isset($xoopsUser) and !empty($BoardID) or $_SERVER['PHP_SELF'] == '/admin.php') {
    $interface_menu[_MD_TADDISCUS_ADD_DISCUSS] = "discuss.php?op=tad_discuss_form&BoardID={$BoardID}";
    $interface_icon[_MD_TADDISCUS_ADD_DISCUSS] = "fa-plus";
}
