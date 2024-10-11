<?php
use XoopsModules\Tadtools\Utility;
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
$sort = 1;
foreach ($_POST['tr'] as $BoardID) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_discuss_board') . '` SET `BoardSort`=? WHERE `BoardID`=?';
    Utility::query($sql, 'ii', [$sort, $BoardID]) or die('' . _MA_TADDISCUS_UPDATE_ERROR . ' (' . date('Y-m-d H:i:s') . ')');

    $sort++;
}

echo _MA_TADDISCUS_SORT_OK . ' (' . date('Y-m-d H:i:s') . ')';
