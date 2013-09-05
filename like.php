<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
/*-----------function區--------------*/
$DiscussID=intval($_POST['DiscussID']);
if($_POST['op']=='like'){
  like('Good',$DiscussID);
}elseif($_POST['op']=='unlike'){
  like('Bad',$DiscussID);
}


function like($col='',$DiscussID=""){
	global $xoopsDB , $xoopsModule;
	$sql = "update `".$xoopsDB->prefix("tad_discuss")."` set `{$col}` = `{$col}`+1 where `DiscussID` = '$DiscussID'";
	$xoopsDB->queryF($sql) or die($sql);

	$sql = "select `{$col}` from `".$xoopsDB->prefix("tad_discuss")."` where `DiscussID` = '$DiscussID'";
	$result=$xoopsDB->queryF($sql) or die($sql);
  list($all)=$xoopsDB->fetchRow($result);
  echo $all;
}
?>