<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_discuss_adm_copycbox.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$BoardID = Request::getInt('BoardID');
$DiscussID = Request::getInt('DiscussID');

switch ($op) {

    case 'copycbox':
        $BoardID = copycbox();
        header("location: ../discuss.php?BoardID={$BoardID}");
        exit;

    case 'forceUpdate':
        copycbox($BoardID);
        header("location: ../discuss.php?BoardID={$BoardID}");
        exit;

    //預設動作
    default:
        list_cbox();
        break;

}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/

//列出所有tad_discuss_board資料
function list_cbox()
{
    global $xoopsDB, $xoopsModule, $xoopsTpl;

    //取得某模組編號
    $moduleHandler = xoops_getHandler('module');
    $ThexoopsModule = $moduleHandler->getByDirname('tad_cbox');
    if ($ThexoopsModule) {
        $xoopsTpl->assign('show_error', '0');
    } else {
        $xoopsTpl->assign('show_error', '1');
        $xoopsTpl->assign('msg', _MA_TADDISCUS_NO_CBOX);

        return;
    }

    $sql = 'SELECT `BoardID` FROM `' . $xoopsDB->prefix('tad_discuss_board') . '` WHERE `BoardTitle` = ?';
    $result = Utility::query($sql, 's', [_MA_TADDISCUS_CBOX]) or die($sql);

    list($BoardID) = $xoopsDB->fetchRow($result);
    if (!empty($BoardID)) {
        $xoopsTpl->assign('show_error', '1');
        $xoopsTpl->assign('msg', sprintf(_MA_TADDISCUS_CBOX_EXIST, $BoardID));
        $xoopsTpl->assign('other_msg', sprintf(_MA_TADDISCUS_CBOX_FORCE_UPDATE, $BoardID));

        return;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_cbox') . '` ORDER BY post_date DESC';

    //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 20, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or die($sql);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： `sn`, `publisher`, `msg`, `post_date`, `ip`, `only_root`, `root_msg`
        foreach ($all as $k => $v) {
            $$k = $v;
            $all_content[$i][$k] = $v;
            if (1 == $only_root) {
                preg_match_all("/\(by (.*?)\)/", $root_msg, $match);
                $root_name = $match[1][0];
                $root_uid = get_uid_from_uname($root_name);
                $all_content[$i]['only_root'] = $root_name . " ({$root_uid})";
            }
        }

        $all_content[$i]['uid'] = get_uid_from_uname($publisher);

        $i++;
    }

    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('add_button', $add_button);
    $xoopsTpl->assign('bar', $bar);
}

function get_uid_from_uname($publisher = '')
{
    global $xoopsDB;
    $sql = 'SELECT `uid` FROM `' . $xoopsDB->prefix('users') . '` WHERE `uname` = ? OR `name` = ?';
    $result = Utility::query($sql, 'ss', [$publisher, $publisher]) or die($sql);

    list($uid) = $xoopsDB->fetchRow($result);

    return $uid;
}

//新增資料到tad_discuss_board中
function copycbox($BoardID = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModule;
    set_time_limit(0);

    //取得目前使用者uid
    $root_uid = $xoopsUser->uid();

    if (empty($BoardID)) {
        //取得最大排序
        $sql = 'SELECT MAX(`BoardSort`) FROM `' . $xoopsDB->prefix('tad_discuss_board') . '` GROUP BY `BoardSort`';
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        list($sort) = $xoopsDB->fetchRow($result);
        $sort++;

        //建立討論區
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_discuss_board') . "` (`ofBoardID`, `BoardTitle`, `BoardDesc`, `BoardManager`, `BoardSort`, `BoardEnable`) VALUES (0, ?, ?, ?, ?, ?)";
        Utility::query($sql, 'ssiis', [_MA_TADDISCUS_CBOX, _MA_TADDISCUS_CBOX_DESC, $root_uid, $sort, '1']) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $BoardID = $xoopsDB->getInsertId();

        //轉移權限（新權限）
        $mid = $xoopsModule->mid();
        //讀取權限
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('group_permission') . '` (`gperm_groupid`, `gperm_itemid`, `gperm_modid`, `gperm_name`) VALUES(1, ?, ?, ?),(2, ?, ?, ?),(3, ?, ?, ?)';
        Utility::query($sql, 'iisiisiis', [$BoardID, $mid, 'forum_read', $BoardID, $mid, 'forum_read', $BoardID, $mid, 'forum_read']) or Utility::web_error($sql, __FILE__, __LINE__);

        //寫入權限
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('group_permission') . '` (`gperm_groupid`, `gperm_itemid`, `gperm_modid`, `gperm_name`) VALUES(1, ?, ?, ?),(2, ?, ?, ?)';
        Utility::query($sql, 'iisiis', [$BoardID, $mid, 'forum_post', $BoardID, $mid, 'forum_post']) or Utility::web_error($sql, __FILE__, __LINE__);

    } else {
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `BoardID`=?';
        Utility::query($sql, 'i', [$BoardID]) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    //讀取留言簿資料
    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_cbox') . '` ORDER BY `post_date`';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($sn, $publisher, $msg, $post_date, $ip, $only_root, $root_msg) = $xoopsDB->fetchRow($result)) {
        $onlyTo = ($only_root) ? $root_uid : '';
        $DiscussTitle = xoops_substr($msg, 0, 60);

        $uid = get_uid_from_uname($publisher);
        $msg = Wcag::amend($msg);
        $root_msg = Wcag::amend($root_msg);

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_discuss') . '` (`ReDiscussID`, `uid`, `publisher`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `BoardID`, `LastTime`, `Counter`, `FromIP`, `Good`, `Bad` , `onlyTo`) VALUES(0, ?, ?, ?, ?, ?, ?, ?, 888, ?, "", "", ?)';
        Utility::query($sql, 'issssisss', [$uid, $publisher, $DiscussTitle, $msg, $post_date, $BoardID, $post_date, $ip, $onlyTo]);

        $DiscussID = $xoopsDB->getInsertId();

        if ($root_msg) {
            preg_match_all("/\(by (.*?)\)/", $root_msg, $match);
            $publisher = $match[1][0];
            if (empty($publisher)) {
                $publisher = $xoopsUser->name();
            } else {
                $root_uid = get_uid_from_uname($publisher);
            }

            $onlyToUid = ($only_root) ? $uid : '';
            if (empty($publisher)) {
                $publisher = $xoopsUser->uname();
            }

            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_discuss') . '` ( `ReDiscussID`, `uid`, `publisher`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `BoardID`, `LastTime`, `Counter`, `FromIP`, `Good`, `Bad`, `onlyTo`) VALUES (?, ?, ?, RE:?, ?, ?, ?, ?, 888, ?, ?, ?, ?)';
            Utility::query($sql, 'iissssisssss', [$DiscussID, $root_uid, $publisher, $DiscussTitle, $root_msg, $post_date, $BoardID, $post_date, $ip, '', '', $onlyToUid]);

        }
    }

    return $BoardID;
}
