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
$xoopsTpl->assign('tad_discuss_adm', $tad_discuss_adm);
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
        $sql = 'SELECT a.`DiscussID`, a.`ReDiscussID`, a.`DiscussTitle`, a.`uid`, a.`DiscussDate`, a.`Counter`, b.`name`, b.`uname`, c.`groupid` FROM `' . $xoopsDB->prefix('tad_discuss') . '` AS a
        LEFT JOIN `' . $xoopsDB->prefix('users') . '` AS b ON a.`uid` = b.`uid`
        LEFT JOIN `' . $xoopsDB->prefix('groups_users_link') . '` AS c ON a.`uid` = c.`uid` AND c.`groupid` = ?
        WHERE a.`DiscussTitle` LIKE ? OR a.`DiscussContent` LIKE ?
        ORDER BY a.`uid`';
        $result = Utility::query($sql, 'iss', [$bad_group_id, "%$spam_keyword%", "%$spam_keyword%"]) or Utility::web_error($sql, __FILE__, __LINE__);

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
        $sql = 'SELECT `uid` FROM `' . $xoopsDB->prefix('groups_users_link') . '` WHERE `groupid` = ?';
        $result = Utility::query($sql, 'i', [$bad_group_id]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($uid) = $xoopsDB->fetchRow($result)) {
            $all_uid[] = $uid;
        }
        $sql = 'SELECT a.`DiscussID`, a.`ReDiscussID`, a.`DiscussTitle`, a.`uid`, a.`DiscussDate`, a.`Counter`, b.`name`, b.`uname`, c.`groupid` FROM `' . $xoopsDB->prefix('tad_discuss') . '` AS a
        LEFT JOIN `' . $xoopsDB->prefix('users') . '` AS b ON a.`uid`=b.`uid`
        LEFT JOIN `' . $xoopsDB->prefix('groups_users_link') . '` AS c ON a.`uid`=c.`uid` AND c.`groupid`=?
        WHERE a.`uid` IN(' . implode(',', $all_uid) . ') AND a.`DiscussDate` > ?';
        $result = Utility::query($sql, 'is', [$bad_group_id, '2005-01-01']) or Utility::web_error($sql, __FILE__, __LINE__);

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
        $sql = 'UPDATE `' . $xoopsDB->prefix('config') . '` SET `conf_value`=? WHERE `conf_name`=\'spam_keyword\' AND `conf_modid`=?';
        Utility::query($sql, 'si', [$all_clean_spam_keyword, $module_id]) or Utility::web_error($sql, __FILE__, __LINE__);

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
    $sql = 'UPDATE `' . $xoopsDB->prefix('config') . '` SET `conf_value`=? WHERE `conf_name`=\'spam_keyword\' AND `conf_modid`=?';
    Utility::query($sql, 'si', [$new_spam_keyword, $module_id]) or Utility::web_error($sql, __FILE__, __LINE__);

}

function bad_group($bad_group_uid)
{
    global $xoopsDB, $xoopsModuleConfig;
    $bad_group_id = $xoopsModuleConfig['bad_group'];
    $sql = 'DELETE FROM `' . $xoopsDB->prefix('groups_users_link') . '` WHERE `uid`=?';
    Utility::query($sql, 'i', [$bad_group_uid]) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'INSERT INTO `' . $xoopsDB->prefix('groups_users_link') . '` (`groupid`, `uid`) VALUES (?, ?)';
    Utility::query($sql, 'ii', [$bad_group_id, $bad_group_uid]) or Utility::web_error($sql, __FILE__, __LINE__);

}
