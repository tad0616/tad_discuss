<?php
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_discuss_adm_groupperm.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
/*-----------function區--------------*/

//引入XOOPS的權限表單物件檔
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

//取得本模組編號
$module_id = $xoopsModule->mid();

$sql = 'SELECT BoardID,BoardTitle FROM `' . $xoopsDB->prefix('tad_discuss_board') . '` ORDER BY BoardSort';
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

while (false !== ($all = $xoopsDB->fetchArray($result))) {
    //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    //權限項目陣列
    $item_list[$BoardID] = $BoardTitle;
}

//頁面標題
$title_of_form = _MA_TADDISCUS_READ_POWER;

//權限名稱
$perm_name = 'forum_read';

//權限描述
$perm_desc = _MA_TADDISCUS_READ_POWER;

//建立XOOPS權限表單
$formi = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);

//將權限項目設進表單中
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$forum_read = $formi->render();

//頁面標題
$title_of_form = _MA_TADDISCUS_POST_POWER;

//權限名稱
$perm_name = 'forum_post';

//權限描述
$perm_desc = _MA_TADDISCUS_POST_POWER;

//建立XOOPS權限表單
$formi = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);

//將權限項目設進表單中
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$forum_post = $formi->render();

$xoopsTpl->assign('forum_read', $forum_read);
$xoopsTpl->assign('forum_post', $forum_post);
$xoopsTpl->assign('jquery', Utility::get_jquery(true));

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
