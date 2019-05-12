<?php
//討論留言搜尋程式
function tad_discuss_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    //處理許功蓋
    if (get_magic_quotes_gpc()) {
        foreach ($queryarray as $k => $v) {
            $arr[$k] = addslashes($v);
        }
        $queryarray = $arr;
    }
    $sql = 'SELECT `DiscussID`,`DiscussTitle`,`DiscussDate`, `uid` FROM ' . $xoopsDB->prefix('tad_discuss') . ' WHERE 1';
    if (0 != $userid) {
        $sql .= ' AND uid=' . $userid . ' ';
    }
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((`DiscussTitle` LIKE '%{$queryarray[0]}%'  OR `DiscussContent` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(`DiscussTitle` LIKE '%{$queryarray[$i]}%' OR  `DiscussContent` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ') ';
    }
    $sql .= 'ORDER BY  `DiscussDate` DESC';
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret = [];
    $i = 0;
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'images/cup.png';
        $ret[$i]['link'] = 'discuss.php?DiscussID=' . $myrow['DiscussID'];
        $ret[$i]['title'] = $myrow['DiscussTitle'];
        $ret[$i]['time'] = strtotime($myrow['DiscussDate']);
        $ret[$i]['uid'] = $myrow['uid'];
        $i++;
    }

    return $ret;
}
