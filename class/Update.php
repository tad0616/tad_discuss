<?php

namespace XoopsModules\Tad_discuss;

use XoopsModules\Tadtools\Utility;

/*
Update Class Definition

You may not change or alter any portion of this comment or credits of
supporting developers from this source code or any supporting source code
which is considered copyrighted (c) material of the original comment or credit
authors.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

/**
 * Class Update
 */
class Update
{
    //新增悄悄話欄位
    public static function chk_chk1()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`onlyTo`) FROM ' . $xoopsDB->prefix('tad_discuss');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update1()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_discuss') . " ADD `onlyTo` VARCHAR(255) NOT NULL DEFAULT ''";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    //新增父討論區
    public static function chk_chk2()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`ofBoardID`) FROM ' . $xoopsDB->prefix('tad_discuss_board');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update2()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_discuss_board') . ' ADD `ofBoardID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 AFTER `BoardID`';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    //新增發布者姓名
    public static function chk_chk3()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`publisher`) FROM ' . $xoopsDB->prefix('tad_discuss');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update3()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_discuss') . " ADD `publisher` VARCHAR(255) NOT NULL DEFAULT '' AFTER `uid`";
        $xoopsDB->queryF($sql);
        $sql = 'SELECT `uid` FROM ' . $xoopsDB->prefix('tad_discuss') . ' GROUP BY uid';
        $result = $xoopsDB->query($sql);
        while (list($uid) = $xoopsDB->fetchRow($result)) {
            $publisher = get_name_from_uid($uid);
            if ($publisher) {
                $sql = 'update ' . $xoopsDB->prefix('tad_discuss') . " set `publisher`='{$publisher}' where `uid`='{$uid}'";
                $xoopsDB->queryF($sql);
            }
        }

        return true;
    }

    //新增original_filename欄位
    public static function chk_chk4()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`original_filename`) FROM ' . $xoopsDB->prefix('tad_discuss_files_center');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update4()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_discuss_files_center') . "
        ADD `original_filename` VARCHAR(255) NOT NULL DEFAULT '',
        ADD `hash_filename` VARCHAR(255) NOT NULL DEFAULT '',
        ADD `sub_dir` VARCHAR(255) NOT NULL DEFAULT ''";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());

        $sql = 'update ' . $xoopsDB->prefix('tad_discuss_files_center') . ' set
        `original_filename`=`description`';
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
    }

    public function get_name_from_uid($uid = '')
    {
        global $xoopsDB;
        $sql = 'select uname,name from `' . $xoopsDB->prefix('users') . "` where uid ='{$uid}'";
        $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
        list($uname, $name) = $xoopsDB->fetchRow($result);
        if (!empty($name)) {
            return $name;
        }

        return $uname;
    }

    //新增設定表格
    public static function chk_chk5()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_discuss_cbox_setup');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update5()
    {
        global $xoopsDB;

        $sql = 'CREATE TABLE `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . "` (
        `setupID` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
        `setupName` VARCHAR(255) NOT NULL DEFAULT '',
        `setupRule` VARCHAR(255) NOT NULL DEFAULT '',
        `BoardID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0,
        `setupSort` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0,
        PRIMARY KEY (`setupID`)
        ) ENGINE=MyISAM;";
        $xoopsDB->queryF($sql);
    }

    //修正uid欄位
    public static function chk_uid()
    {
        global $xoopsDB;
        $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = '" . $xoopsDB->prefix('tad_discuss') . "' AND COLUMN_NAME = 'uid'";
        $result = $xoopsDB->query($sql);
        list($type) = $xoopsDB->fetchRow($result);
        if ('smallint' === $type) {
            return true;
        }

        return false;
    }

    //執行更新
    public static function go_update_uid()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('tad_discuss') . '` CHANGE `uid` `uid` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT 0';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    //修正col_sn欄位
    public static function chk_files_center()
    {
        global $xoopsDB;
        $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = '" . $xoopsDB->prefix('tad_discuss_files_center') . "' AND COLUMN_NAME = 'col_sn'";
        $result = $xoopsDB->query($sql);
        list($type) = $xoopsDB->fetchRow($result);
        if ('smallint' === $type) {
            return true;
        }

        return false;
    }

    //執行更新
    public static function go_update_files_center()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('tad_discuss_files_center') . '` CHANGE `col_sn` `col_sn` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT 0';
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    //更新BoardID為0的回覆留言
    public static function chk_chk6()
    {
        global $xoopsDB;
        $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_discuss') . ' WHERE BoardID=0';
        $result = $xoopsDB->query($sql);
        if (!empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_update6()
    {
        global $xoopsDB;
        $sql = 'SELECT DiscussID,ReDiscussID FROM ' . $xoopsDB->prefix('tad_discuss') . ' WHERE BoardID=0';
        $result = $xoopsDB->query($sql);
        while (list($DiscussID, $ReDiscussID) = $xoopsDB->fetchRow($result)) {
            $sql2 = 'select BoardID from ' . $xoopsDB->prefix('tad_discuss') . " where DiscussID='$ReDiscussID'";
            $result2 = $xoopsDB->query($sql2);
            list($BoardID) = $xoopsDB->fetchRow($result2);

            $sql3 = 'update ' . $xoopsDB->prefix('tad_discuss') . " set BoardID='$BoardID' where DiscussID='$DiscussID'";
            $xoopsDB->query($sql3);
        }

        return true;
    }

    //新增檔案欄位
    public static function chk_fc_tag()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`tag`) FROM ' . $xoopsDB->prefix('tad_discuss_files_center');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_fc_tag()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_discuss_files_center') . "
        ADD `upload_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳時間',
        ADD `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上傳者',
        ADD `tag` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '註記'
        ";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
    }

}
