<?php
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_discuss_adm_spam.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

$TadUpFiles = new TadUpFiles('tad_discuss');
/*-----------function區--------------*/

//列出所有垃圾留言
function list_spam()
{
    global $xoopsModuleConfig, $xoopsTpl;

    $new = [];
    if (!empty($_POST['new_spam_keyword'])) {
        $new = explode(',', $_POST['new_spam_keyword']);
    }

    $all_spam_keyword = array_merge($xoopsModuleConfig['spam_keyword'], $new);

    $spam_keyword = explode(',', $xoopsModuleConfig['spam_keyword']);
    $i = 0;
    foreach ($spam_keyword as $keyword) {
        $all_keyword[$i]['keyword'] = $keyword;
        $all_keyword[$i]['checked'] = 'checked';
        $i++;
    }

    $xoopsTpl->assign('all_keyword', $all_keyword);
    $xoopsTpl->assign('now_op', 'list_spam');
    $xoopsTpl->assign('jquery', Utility::get_jquery());
}

//搜尋垃圾
function search_spam()
{
    global $xoopsDB, $xoopsTpl, $xoopsModule;
    $new = [];
    if (!empty($_POST['new_spam_keyword'])) {
        $new = explode(',', $_POST['new_spam_keyword']);
    }

    $all_spam_keyword = array_merge($_POST['spam_keyword'], $new);

    foreach ($all_spam_keyword as $spam_keyword) {
        $spam_keyword = trim($spam_keyword);
        $sql = 'select * from `' . $xoopsDB->prefix('tad_discuss') . "` where `DiscussTitle` like '%{$spam_keyword}%' or `DiscussContent` like '%{$spam_keyword}%'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 0;
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
            foreach ($all as $k => $v) {
                $$k = $v;
                $all_content[$i][$k] = $v;
            }
            //以uid取得使用者名稱
            $uid_name = \XoopsUser::getUnameFromId($uid, 1);
            if (empty($uid_name)) {
                $uid_name = \XoopsUser::getUnameFromId($uid, 0);
            }

            $all_content[$i]['uid_name'] = $uid_name;
            $all_content[$i]['spam_keyword'] = $spam_keyword;
            $i++;
        }
    }
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('now_op', 'search_spam');

    if ($_POST['new_spam_keyword']) {
        $module_id = $xoopsModule->mid();
        $sql = 'update `' . $xoopsDB->prefix('config') . "` set `conf_value`= CONCAT(`conf_value`,',{$_POST['new_spam_keyword']}') where `conf_name`='spam_keyword' and `conf_modid`='$module_id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }
}

//刪除垃圾
function del_spam()
{
    global $TadUpFiles;
    if (empty($_POST['SpamDiscussID'])) {
        return;
    }

    foreach ($_POST['SpamDiscussID'] as $SpamDiscussID) {
        delete_tad_discuss($SpamDiscussID);
    }
}

function update_config($item = '')
{
    global $xoopsModuleConfig, $xoopsModule, $xoopsDB;
    $keys = explode(',', $xoopsModuleConfig['spam_keyword']);
    $keys = array_diff($keys, [$item]);
    $new_spam_keyword = implode(',', $keys);

    $module_id = $xoopsModule->mid();
    $sql = 'update `' . $xoopsDB->prefix('config') . "` set `conf_value`= '{$new_spam_keyword}' where `conf_name`='spam_keyword' and `conf_modid`='$module_id'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$BoardID = system_CleanVars($_REQUEST, 'BoardID', 0, 'int');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case 'search_spam':
        list_spam();
        search_spam();
        break;
    case 'del_spam':
        del_spam();
        header("location:{$_SERVER['PHP_SELF']}");
        exit;
        break;
    case 'update_config':
        update_config($_POST['item']);
        break;
    //預設動作
    default:
        list_spam();
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
