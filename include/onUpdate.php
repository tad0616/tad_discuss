<?php

function xoops_module_update_tad_discuss(&$module, $old_version)
{
    global $xoopsDB;
    if (chk_chk1()) {
        go_update1();
    }

    if (chk_chk2()) {
        go_update2();
    }

    if (chk_chk3()) {
        go_update3();
    }

    if (chk_chk4()) {
        go_update4();
    }

    if (chk_chk5()) {
        go_update5();
    }

    if (chk_uid()) {
        go_update_uid();
    }

    if (chk_files_center()) {
        go_update_files_center();
    }

    if (chk_chk6()) {
        go_update6();
    }

    //新增檔案欄位
    if (chk_fc_tag()) {
        go_fc_tag();
    }
    return true;
}

//新增悄悄話欄位
function chk_chk1()
{
    global $xoopsDB;
    $sql    = "SELECT count(`onlyTo`) FROM " . $xoopsDB->prefix("tad_discuss");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update1()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_discuss") . " ADD `onlyTo` VARCHAR(255) NOT NULL DEFAULT ''";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);
    return true;
}

//新增父討論區
function chk_chk2()
{
    global $xoopsDB;
    $sql    = "SELECT count(`ofBoardID`) FROM " . $xoopsDB->prefix("tad_discuss_board");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update2()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_discuss_board") . " ADD `ofBoardID` SMALLINT(6) UNSIGNED NOT NULL DEFAULT 0 AFTER `BoardID`";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);
    return true;
}

//新增發布者姓名
function chk_chk3()
{
    global $xoopsDB;
    $sql    = "SELECT count(`publisher`) FROM " . $xoopsDB->prefix("tad_discuss");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update3()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_discuss") . " ADD `publisher` VARCHAR(255) NOT NULL DEFAULT '' AFTER `uid`";
    $xoopsDB->queryF($sql);
    $sql    = "SELECT `uid` FROM " . $xoopsDB->prefix("tad_discuss") . " GROUP BY uid";
    $result = $xoopsDB->query($sql);
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $publisher = get_name_from_uid($uid);
        if ($publisher) {
            $sql = "update " . $xoopsDB->prefix("tad_discuss") . " set `publisher`='{$publisher}' where `uid`='{$uid}'";
            $xoopsDB->queryF($sql);
        }
    }
    return true;
}

//新增original_filename欄位
function chk_chk4()
{
    global $xoopsDB;
    $sql    = "SELECT count(`original_filename`) FROM " . $xoopsDB->prefix("tad_discuss_files_center");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update4()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_discuss_files_center") . "
  ADD `original_filename` VARCHAR(255) NOT NULL DEFAULT '',
  ADD `hash_filename` VARCHAR(255) NOT NULL DEFAULT '',
  ADD `sub_dir` VARCHAR(255) NOT NULL DEFAULT ''";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());

    $sql = "update " . $xoopsDB->prefix("tad_discuss_files_center") . " set
  `original_filename`=`description`";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
}

function get_name_from_uid($uid = "")
{
    global $xoopsDB;
    $sql                = "select uname,name from `" . $xoopsDB->prefix("users") . "` where uid ='{$uid}'";
    $result             = $xoopsDB->queryF($sql) or die($sql);
    list($uname, $name) = $xoopsDB->fetchRow($result);
    if (!empty($name)) {
        return $name;
    }

    return $uname;
}

//新增設定表格
function chk_chk5()
{
    global $xoopsDB;
    $sql    = "SELECT count(*) FROM " . $xoopsDB->prefix("tad_discuss_cbox_setup");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update5()
{
    global $xoopsDB;

    $sql = "CREATE TABLE `" . $xoopsDB->prefix("tad_discuss_cbox_setup") . "` (
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
function chk_uid()
{
    global $xoopsDB;
    $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
  WHERE table_name = '" . $xoopsDB->prefix("tad_discuss") . "' AND COLUMN_NAME = 'uid'";
    $result     = $xoopsDB->query($sql);
    list($type) = $xoopsDB->fetchRow($result);
    if ($type == 'smallint') {
        return true;
    }

    return false;
}

//執行更新
function go_update_uid()
{
    global $xoopsDB;
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tad_discuss") . "` CHANGE `uid` `uid` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT 0";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);
    return true;
}

//修正col_sn欄位
function chk_files_center()
{
    global $xoopsDB;
    $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
  WHERE table_name = '" . $xoopsDB->prefix("tad_discuss_files_center") . "' AND COLUMN_NAME = 'col_sn'";
    $result     = $xoopsDB->query($sql);
    list($type) = $xoopsDB->fetchRow($result);
    if ($type == 'smallint') {
        return true;
    }

    return false;
}

//執行更新
function go_update_files_center()
{
    global $xoopsDB;
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tad_discuss_files_center") . "` CHANGE `col_sn` `col_sn` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT 0";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, _LINE__);
    return true;
}

//更新BoardID為0的回覆留言
function chk_chk6()
{
    global $xoopsDB;
    $sql    = "SELECT count(*) FROM " . $xoopsDB->prefix("tad_discuss") . " WHERE BoardID=0";
    $result = $xoopsDB->query($sql);
    if (!empty($result)) {
        return true;
    }

    return false;
}

function go_update6()
{
    global $xoopsDB;
    $sql    = "SELECT DiscussID,ReDiscussID FROM " . $xoopsDB->prefix("tad_discuss") . " WHERE BoardID=0";
    $result = $xoopsDB->query($sql);
    while (list($DiscussID, $ReDiscussID) = $xoopsDB->fetchRow($result)) {
        $sql2          = "select BoardID from " . $xoopsDB->prefix("tad_discuss") . " where DiscussID='$ReDiscussID'";
        $result2       = $xoopsDB->query($sql2);
        list($BoardID) = $xoopsDB->fetchRow($result2);

        $sql3 = "update " . $xoopsDB->prefix("tad_discuss") . " set BoardID='$BoardID' where DiscussID='$DiscussID'";
        $xoopsDB->query($sql3);
    }
    return true;
}

//新增檔案欄位
function chk_fc_tag()
{
    global $xoopsDB;
    $sql    = "SELECT count(`tag`) FROM " . $xoopsDB->prefix("tad_discuss_files_center");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_fc_tag()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_discuss_files_center") . "
    ADD `upload_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳時間',
    ADD `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上傳者',
    ADD `tag` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '註記'
    ";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
}

//建立目錄
if (!function_exists('mk_dir')) {
    function mk_dir($dir = "")
    {
        //若無目錄名稱秀出警告訊息
        if (empty($dir)) {
            return;
        }

        //若目錄不存在的話建立目錄
        if (!is_dir($dir)) {
            umask(000);
            //若建立失敗秀出警告訊息
            mkdir($dir, 0777);
        }
    }
}

//拷貝目錄
if (!function_exists('full_copy')) {
    function full_copy($source = "", $target = "")
    {
        if (is_dir($source)) {
            @mkdir($target);
            $d = dir($source);
            while (false !== ($entry = $d->read())) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                $Entry = $source . '/' . $entry;
                if (is_dir($Entry)) {
                    full_copy($Entry, $target . '/' . $entry);
                    continue;
                }
                copy($Entry, $target . '/' . $entry);
            }
            $d->close();
        } else {
            copy($source, $target);
        }
    }
}

if (!function_exists('rename_win')) {
    function rename_win($oldfile, $newfile)
    {
        if (!rename($oldfile, $newfile)) {
            if (copy($oldfile, $newfile)) {
                unlink($oldfile);
                return true;
            }
            return false;
        }
        return true;
    }
}

if (!function_exists('delete_directory')) {
    function delete_directory($dirname)
    {
        if (is_dir($dirname)) {
            $dir_handle = opendir($dirname);
        }

        if (!$dir_handle) {
            return false;
        }

        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file)) {
                    unlink($dirname . "/" . $file);
                } else {
                    delete_directory($dirname . '/' . $file);
                }
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
}
