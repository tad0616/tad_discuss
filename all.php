<?php
use Xmf\Request;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_discuss_discuss.tpl';
require __DIR__ . '/header.php';

require_once XOOPS_ROOT_PATH . '/header.php';
$TadUpFiles = new TadUpFiles('tad_discuss');
/*-----------執行動作判斷區----------*/
$op = Request::getString('op');

switch ($op) {
    default:
        list_tad_discuss();
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoopsTpl->assign('tad_discuss_adm', $tad_discuss_adm);
$xoTheme->addStylesheet('modules/tad_discuss/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/
