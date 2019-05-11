<?php

use XoopsModules\Tad_discuss\Update;

if (!class_exists('XoopsModules\Tad_discuss\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}
function xoops_module_update_tad_discuss(&$module, $old_version)
{
    global $xoopsDB;
    if (Update::chk_chk1()) {
        Update::go_update1();
    }

    if (Update::chk_chk2()) {
        Update::go_update2();
    }

    if (Update::chk_chk3()) {
        Update::go_update3();
    }

    if (Update::chk_chk4()) {
        Update::go_update4();
    }

    if (Update::chk_chk5()) {
        Update::go_update5();
    }

    if (Update::chk_uid()) {
        Update::go_update_uid();
    }

    if (Update::chk_files_center()) {
        Update::go_update_files_center();
    }

    if (Update::chk_chk6()) {
        Update::go_update6();
    }

    //新增檔案欄位
    if (Update::chk_fc_tag()) {
        Update::go_fc_tag();
    }

    return true;
}
