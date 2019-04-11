<?php

use XoopsModules\Tad_discuss\Utility;

function xoops_module_update_tad_discuss(&$module, $old_version)
{
    global $xoopsDB;
    if (Utility::chk_chk1()) {
        Utility::go_update1();
    }

    if (Utility::chk_chk2()) {
        Utility::go_update2();
    }

    if (Utility::chk_chk3()) {
        Utility::go_update3();
    }

    if (Utility::chk_chk4()) {
        Utility::go_update4();
    }

    if (Utility::chk_chk5()) {
        Utility::go_update5();
    }

    if (Utility::chk_uid()) {
        Utility::go_update_uid();
    }

    if (Utility::chk_files_center()) {
        Utility::go_update_files_center();
    }

    if (Utility::chk_chk6()) {
        Utility::go_update6();
    }

    //新增檔案欄位
    if (Utility::chk_fc_tag()) {
        Utility::go_fc_tag();
    }
    return true;
}


