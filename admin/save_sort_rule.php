<?php
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
$sort = 1;
foreach ($_POST['tr'] as $setupID) {
    $sql = 'update ' . $xoopsDB->prefix('tad_discuss_cbox_setup') . " set `setupSort`='{$sort}' where `setupID`='{$setupID}'";
    $xoopsDB->queryF($sql) or die('' . _MA_TADDISCUS_UPDATE_ERROR . ' (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo _MA_TADDISCUS_SORT_OK . ' (' . date('Y-m-d H:i:s') . ')';
