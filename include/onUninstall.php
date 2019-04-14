<?php

function xoops_module_uninstall_tad_discuss(&$module)
{
    global $xoopsDB;
    $date = date('Ymd');

    rename(XOOPS_ROOT_PATH . '/uploads/tad_discuss', XOOPS_ROOT_PATH . "/uploads/tad_discuss_bak_{$date}");

    return true;
}
