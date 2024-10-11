<?php
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
/*-----------function區--------------*/
$DiscussID = (int) $_POST['DiscussID'];
if ('like' === $_POST['op']) {
    like('Good', $DiscussID);
} elseif ('unlike' === $_POST['op']) {
    like('Bad', $DiscussID);
}

function like($col = '', $DiscussID = '')
{
    global $xoopsDB;
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_discuss') . '` SET `' . $col . '` = `' . $col . '`+1 WHERE `DiscussID` =?';
    Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'SELECT `' . $col . '` FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `DiscussID` =?';
    $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

    list($all) = $xoopsDB->fetchRow($result);
    echo $all;
}
