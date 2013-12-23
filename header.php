<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2012-10-23
// $Id:$
// ------------------------------------------------------------------------- //

include_once "../../mainfile.php";

if($xoopsModuleConfig['use_pda']=='1'){
  if(file_exists(XOOPS_ROOT_PATH."/modules/tadtools/mobile_device_detect.php")){
    include_once XOOPS_ROOT_PATH."/modules/tadtools/mobile_device_detect.php";
    mobile_device_detect(true, false, true, true, true, true, true, 'pda.php', false);
  }
}

include_once "function.php";

//判斷是否對該模組有管理權限
$isAdmin=false;
if ($xoopsUser) {
  $module_id = $xoopsModule->getVar('mid');
  $isAdmin=$xoopsUser->isAdmin($module_id);
}

$interface_menu[_MD_TADDISCUS_SMNAME1]="index.php";
$interface_menu[_MD_TADDISCUS_SMNAME2]="all.php";
if($xoopsUser and !empty($_GET['BoardID'])){
  $interface_menu[_MD_TADDISCUS_ADD_DISCUSS]="discuss.php?op=tad_discuss_form&BoardID={$_GET['BoardID']}";
}

if($isAdmin){
  $interface_menu[_TAD_TO_ADMIN]="admin/main.php";
}
?>