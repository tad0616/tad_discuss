<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_discuss_adm_groupperm.html";
include_once "header.php";
include_once "../function.php";
/*-----------function區--------------*/

//引入XOOPS的權限表單物件檔
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

//取得本模組編號
$module_id = $xoopsModule->getVar('mid');

$sql    = "select BoardID,BoardTitle from `" . $xoopsDB->prefix("tad_discuss_board") . "` order by BoardSort";
$result = $xoopsDB->query($sql) or web_error($sql);

while ($all = $xoopsDB->fetchArray($result)) {
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
$formi = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);

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
$formi = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);

//將權限項目設進表單中
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$forum_post = $formi->render();

$xoopsTpl->assign('forum_read', $forum_read);
$xoopsTpl->assign('forum_post', $forum_post);
$xoopsTpl->assign('jquery', get_jquery(true));

/*-----------秀出結果區--------------*/
include_once 'footer.php';
