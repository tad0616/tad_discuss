<?php
include_once "header.php";
include_once "../function.php";
$sort = 1;
foreach ($_POST['tr'] as $BoardID) {
    $sql = "update " . $xoopsDB->prefix("tad_discuss_board") . " set `BoardSort`='{$sort}' where `BoardID`='{$BoardID}'";
    $xoopsDB->queryF($sql) or die("" . _MA_TADDISCUS_UPDATE_ERROR . " (" . date("Y-m-d H:i:s") . ")");
    $sort++;
}

echo _MA_TADDISCUS_SORT_OK . " (" . date("Y-m-d H:i:s") . ")";
