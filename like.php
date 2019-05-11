<?php
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
/*-----------function區--------------*/
$DiscussID = (int)$_POST['DiscussID'];
if ('like' === $_POST['op']) {
    like('Good', $DiscussID);
} elseif ('unlike' === $_POST['op']) {
    like('Bad', $DiscussID);
}

function like($col = '', $DiscussID = '')
{
    global $xoopsDB, $xoopsModule;
    $sql = 'update `' . $xoopsDB->prefix('tad_discuss') . "` set `{$col}` = `{$col}`+1 where `DiscussID` = '$DiscussID'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    $sql = "select `{$col}` from `" . $xoopsDB->prefix('tad_discuss') . "` where `DiscussID` = '$DiscussID'";
    $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    list($all) = $xoopsDB->fetchRow($result);
    echo $all;
}
