<?php
use Xmf\Request;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_discuss_adm_spam.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

$TadUpFiles = new TadUpFiles('tad_discuss');

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$BoardID = Request::getInt('BoardID');
$DiscussID = Request::getInt('DiscussID');
$bad_group_uid = Request::getInt('bad_group_uid');

switch ($op) {

    case 'bad_group':
        bad_group($bad_group_uid);
        header("location:" . XOOPS_URL . "/modules/system/admin.php?fct=users&op=users_edit&uid=$bad_group_uid");
        exit;

    case 'search_spam':
        list_spam();
        search_spam();
        break;

    case 'del_spam':
        del_spam();
        header("location:{$_SERVER['PHP_SELF']}");
        exit;

    case 'update_config':
        update_config($_POST['item']);
        break;

    //預設動作
    default:
        list_spam();
        break;

}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/

//列出所有垃圾留言
function list_spam()
{
    global $xoopsModuleConfig, $xoopsTpl;

    $new_spam_keyword = $clean_spam_keyword = [];
    if (!empty($_POST['new_spam_keyword'])) {
        $new_spam_keyword = explode(',', $_POST['new_spam_keyword']);
        foreach ($new_spam_keyword as $value) {
            $clean_spam_keyword[$value] = $value;
        }
    }

    $spam_keyword = explode(',', $xoopsModuleConfig['spam_keyword']);
    foreach ($spam_keyword as $value) {
        $clean_spam_keyword[$value] = $value;
    }

    $i = 0;
    foreach ($clean_spam_keyword as $keyword) {
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
    global $xoopsDB, $xoopsTpl, $xoopsModule, $xoopsModuleConfig;
    $bad_group_id = $xoopsModuleConfig['bad_group'];
    $xoopsTpl->assign('bad_group_id', $bad_group_id);

    $new_spam_keyword = $clean_spam_keyword = [];
    if (!empty($_POST['new_spam_keyword'])) {
        $new_spam_keyword = explode(',', $_POST['new_spam_keyword']);
        foreach ($new_spam_keyword as $value) {
            $clean_spam_keyword[$value] = $value;
        }
    }

    $spam_keyword = explode(',', $xoopsModuleConfig['spam_keyword']);
    foreach ($spam_keyword as $value) {
        $clean_spam_keyword[$value] = $value;
    }

    foreach ($clean_spam_keyword as $spam_keyword) {
        $spam_keyword = trim($spam_keyword);
        $sql = 'select a.DiscussID, a.ReDiscussID, a.DiscussTitle, a.uid, a.DiscussDate, a.Counter, b.name, b.uname, c.groupid from `' . $xoopsDB->prefix('tad_discuss') . "` as a
        left join `" . $xoopsDB->prefix('users') . "` as b on a.uid=b.uid
        left join `" . $xoopsDB->prefix('groups_users_link') . "` as c on a.uid=c.uid and c.groupid='$bad_group_id'
        where a.`DiscussTitle` like '%{$spam_keyword}%' or a.`DiscussContent` like '%{$spam_keyword}%'
        order by a.uid";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 0;
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
            foreach ($all as $k => $v) {
                $$k = $v;
                $all_content[$i][$k] = $v;
            }

            $all_content[$i]['uid_name'] = $name ? $name : $uname;
            $all_content[$i]['spam_keyword'] = $spam_keyword;
            $all_content[$i]['bad_group'] = $bad_group_id == $groupid ? true : false;
            $i++;
        }
    }

    if ($bad_group_id > 3) {
        $all_uid = [];
        $sql = 'select uid from `' . $xoopsDB->prefix('groups_users_link') . "`
        where groupid='$bad_group_id'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($uid) = $xoopsDB->fetchRow($result)) {
            $all_uid[] = $uid;
        }
        $sql = 'select a.DiscussID, a.ReDiscussID, a.DiscussTitle, a.uid, a.DiscussDate, a.Counter, b.name, b.uname, c.groupid from `' . $xoopsDB->prefix('tad_discuss') . "` as a
        left join `" . $xoopsDB->prefix('users') . "` as b on a.uid=b.uid
        left join `" . $xoopsDB->prefix('groups_users_link') . "` as c on a.uid=c.uid and c.groupid='$bad_group_id'
        where a.`uid` in(" . implode(',', $all_uid) . ") and a.DiscussDate > '2005-01-01'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i = 0;
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
            foreach ($all as $k => $v) {
                $$k = $v;
                $all_content[$i][$k] = $v;
            }

            $all_content[$i]['uid_name'] = $name ? $name : $uname;
            $all_content[$i]['spam_keyword'] = $spam_keyword;
            $all_content[$i]['bad_group'] = $bad_group_id == $groupid ? true : false;
            $i++;
        }
    }

    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('now_op', 'search_spam');

    if ($_POST['new_spam_keyword']) {
        $all_clean_spam_keyword = implode(',', $clean_spam_keyword);
        $module_id = $xoopsModule->mid();
        $sql = 'update `' . $xoopsDB->prefix('config') . "` set `conf_value`= '$all_clean_spam_keyword' where `conf_name`='spam_keyword' and `conf_modid`='$module_id'";
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

function bad_group($bad_group_uid)
{
    global $xoopsDB, $xoopsModuleConfig;
    $bad_group_id = $xoopsModuleConfig['bad_group'];
    $sql = 'delete from `' . $xoopsDB->prefix('groups_users_link') . "` where `uid`='$bad_group_uid'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $sql = "insert into `" . $xoopsDB->prefix('groups_users_link') . "` (`groupid`, `uid`) values('$bad_group_id','$bad_group_uid')";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}
