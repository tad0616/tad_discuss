<?php
/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "tad_discuss_discuss.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";
include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php";
$TadUpFiles = new TadUpFiles("tad_discuss");
/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op        = system_CleanVars($_REQUEST, 'op', '', 'string');
$BoardID   = system_CleanVars($_REQUEST, 'BoardID', 0, 'int');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');
$files_sn  = system_CleanVars($_REQUEST, 'files_sn', 0, 'int');

$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
$xoopsTpl->assign("jquery", get_jquery(true));

switch ($op) {

    default:
        list_tad_discuss();
        break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH . '/footer.php';
