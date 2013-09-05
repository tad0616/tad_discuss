<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2012-10-23
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "tad_discuss_discuss_tpl.html";
include_once XOOPS_ROOT_PATH."/header.php";
include_once "up_file.php";
/*-----------function區--------------*/



/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$DiscussID=empty($_REQUEST['DiscussID'])?"":intval($_REQUEST['DiscussID']);
$BoardID=empty($_REQUEST['BoardID'])?"":intval($_REQUEST['BoardID']);
$files_sn=empty($_REQUEST['files_sn'])?"":intval($_REQUEST['files_sn']);

$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
$xoopsTpl->assign( "jquery" , get_jquery(true)) ;

switch($op){

	default:
	list_tad_discuss();
	break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH.'/footer.php';
?>