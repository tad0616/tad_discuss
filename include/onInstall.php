<?php

use XoopsModules\Tad_discuss\Utility;

include dirname(__DIR__) . '/preloads/autoloader.php';

function xoops_module_install_tad_discuss(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_discuss');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_discuss/thumbs');

    return true;
}
