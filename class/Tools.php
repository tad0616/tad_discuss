<?php

namespace XoopsModules\Tad_discuss;

use XoopsModules\Tadtools\Utility;

class Tools
{

    //悄悄話檢查
    public static function isPublic($onlyTo = '', $publisher_uid = 0, $BoardID = null)
    {
        global $xoopsUser, $tad_discuss_adm;
        if (empty($onlyTo)) {
            return true;
        }

        if (empty($xoopsUser)) {
            return false;
        }

        if ($tad_discuss_adm) {
            return true;
        }

        $onlyToArr = explode(',', $onlyTo);
        $now_uid = $xoopsUser->uid();

        if (in_array($now_uid, $onlyToArr)) {
            return true;
        }

        if ($publisher_uid == $now_uid) {
            return true;
        }

        if ($BoardID) {
            $board = self::get_tad_discuss_board($BoardID);
            $BoardManagerArr = explode(',', $board['BoardManager']);
            if (in_array($now_uid, $BoardManagerArr)) {
                return true;
            }
        }

        return false;
    }

    //將悄悄話的對象轉換為真實姓名
    public static function getOnlyToName($onlyTo = '')
    {
        global $xoopsDB;
        if (empty($onlyTo)) {
            return;
        }

        $sql = 'SELECT `name`, `uname` FROM `' . $xoopsDB->prefix('users') . '` WHERE `uid` IN(?)';
        $result = Utility::query($sql, 's', [$onlyTo]) or Utility::web_error($sql, __FILE__, __LINE__);

        $allname = [];
        while (list($name, $uname) = $xoopsDB->fetchRow($result)) {
            $allname[] = empty($name) ? $uname : $name;
        }
        $nameStr = implode(' , ', $allname);

        return $nameStr;
    }

    //以流水號取得某筆tad_discuss_board資料
    public static function get_tad_discuss_board($BoardID = '')
    {
        global $xoopsDB;
        if (empty($BoardID)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss_board') . '` WHERE `BoardID` = ?';
        $result = Utility::query($sql, 'i', [$BoardID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $data = $xoopsDB->fetchArray($result);

        return $data;
    }

}
