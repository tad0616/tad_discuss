<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_discuss_adm_copybb.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$BoardID = Request::getInt('BoardID');
$DiscussID = Request::getInt('DiscussID');
$topic_id = Request::getInt('topic_id');

switch ($op) {

    case 'copyBoard':
        copyBoard($BoardID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'listBoard':
        listBoard($BoardID);
        break;

    case 'delXforum':
        delXforum($topic_id);
        header("location: {$_SERVER['PHP_SELF']}?op=listBoard&BoardID=$BoardID");
        exit;

    case 'copyDiscuss':
        copyDiscuss($BoardID, $_POST['mode']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'powerSet':
        powerSet($BoardID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'batch_del':
        batch_del($_POST['batch_del']);
        header("location: {$_SERVER['PHP_SELF']}?op=listBoard&BoardID=$BoardID");
        exit;

    //預設動作
    default:
        list_xforum();
        break;

}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/

//列出所有tad_discuss_board資料
function list_xforum()
{
    global $xoopsDB, $xoopsModule, $xoopsTpl;

    //取得某模組編號
    $moduleHandler = xoops_getHandler('module');
    $ThexoopsModule = $moduleHandler->getByDirname('xforum');
    if ($ThexoopsModule) {
        $mod_id = $ThexoopsModule->mid();
        $xoopsTpl->assign('show_error', '0');
    } else {
        $xoopsTpl->assign('show_error', '1');
        $xoopsTpl->assign('msg', _MA_TADDISCUS_NO_XFORUM);

        return;
    }

    //轉移權限(原權限)
    $sql = 'SELECT `gperm_groupid`, `gperm_itemid`, `gperm_name` FROM `' . $xoopsDB->prefix('group_permission') . '` WHERE `gperm_modid` =?';
    $result = Utility::query($sql, 'i', [$mod_id]) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($gperm_groupid, $gperm_itemid, $gperm_name) = $xoopsDB->fetchRow($result)) {
        $power[$gperm_itemid][$gperm_name][$gperm_groupid] = $gperm_groupid;
    }

    //轉移權限（新權限）
    $mid = $xoopsModule->mid();
    $sql = 'SELECT `gperm_groupid`, `gperm_itemid`, `gperm_name` FROM `' . $xoopsDB->prefix('group_permission') . '` WHERE `gperm_modid` = ?';
    $result = Utility::query($sql, 'i', [$mid]) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($gperm_groupid, $gperm_itemid, $gperm_name) = $xoopsDB->fetchRow($result)) {
        $now_power[$gperm_itemid][$gperm_name][$gperm_groupid] = $gperm_groupid;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('xf_forums') . '` WHERE `forum_topics` > 0 ORDER BY `forum_order`';
    $result = Utility::query($sql) or die($sql);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： `forum_id`, `forum_name`, `forum_desc`, `parent_forum`, `forum_moderator`, `forum_topics`, `forum_posts`, `forum_last_post_id`, `cat_id`, `forum_type`, `allow_html`, `allow_sig`, `allow_subject_prefix`, `hot_threshold`, `forum_order`, `attach_maxkb`, `attach_ext`, `allow_polls`, `domain`, `domains`, `languages`
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $cols = [];
        preg_match_all('/"([0-9]+)"/', $forum_moderator, $cols);
        $moderator = implode(',', $cols[1]);

        $tool = chkcopy($forum_id) ? "<a href='{$_SERVER['PHP_SELF']}?op=listBoard&BoardID={$forum_id}'>" . sprintf(_MA_TADDISCUS_IMPORT, $forum_name) . '</a>' : "<a href='{$_SERVER['PHP_SELF']}?op=copyBoard&BoardID={$forum_id}'>" . sprintf(_MA_TADDISCUS_CREATE, $forum_name) . '</a>';

        $discuss_num = get_board_num($forum_id);
        $discuss_num2 = get_board_num($forum_id, false);

        //forum_access,forum_post,forum_view
        $forum_read = array_combine($power[$forum_id]['forum_view'], $power[$forum_id]['forum_access']);
        $forum_post = implode(',', $power[$forum_id]['forum_post']);
        $forum_read = implode(',', $forum_read);

        $power_status = count($now_power[$forum_id]['forum_read']) > 0 ? _MA_TADDISCUS_POWER_OK : "<a href='{$_SERVER['PHP_SELF']}?op=powerSet&BoardID={$forum_id}&read={$forum_read}&post={$forum_post}'>" . _MA_TADDISCUS_POWER_STATUS . '</a>';

        $all_content[$i]['forum_id'] = $forum_id;
        $all_content[$i]['forum_name'] = $forum_name;
        $all_content[$i]['forum_desc'] = $forum_desc;
        $all_content[$i]['moderator'] = $moderator;
        $all_content[$i]['forum_order'] = $forum_order;
        $all_content[$i]['tool'] = $tool;
        $all_content[$i]['discuss_num'] = $discuss_num;
        $all_content[$i]['discuss_num2'] = $discuss_num2;
        $all_content[$i]['forum_read'] = $forum_read;
        $all_content[$i]['forum_post'] = $forum_post;
        $all_content[$i]['power_status'] = $power_status;
        $i++;
    }

    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('add_button', $add_button);
    $xoopsTpl->assign('bar', $bar);
}

function chkcopy($forum_id)
{
    global $xoopsDB;

    $sql = 'SELECT `BoardID` FROM `' . $xoopsDB->prefix('tad_discuss_board') . '` WHERE `BoardID` =?';
    $result = Utility::query($sql, 'i', [$forum_id]) or die($sql);

    list($sn) = $xoopsDB->fetchRow($result);

    return $sn;
}

//新增資料到tad_discuss_board中
function copyBoard($BoardID = '')
{
    global $xoopsDB;

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('xf_forums') . '` WHERE `forum_id` = ?';
    $result = Utility::query($sql, 'i', [$BoardID]) or die($sql);

    $all = $xoopsDB->fetchArray($result);
    //以下會產生這些變數： `forum_id`, `forum_name`, `forum_desc`, `parent_forum`, `forum_moderator`, `forum_topics`, `forum_posts`, `forum_last_post_id`, `cat_id`, `forum_type`, `allow_html`, `allow_sig`, `allow_subject_prefix`, `hot_threshold`, `forum_order`, `attach_maxkb`, `attach_ext`, `allow_polls`, `domain`, `domains`, `languages`
    foreach ($all as $k => $v) {
        $$k = $v;
    }
    $cols = [];
    preg_match_all('/"([0-9]+)"/', $forum_moderator, $cols);
    $BoardManager = implode(',', $cols[1]);

    $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_discuss_board') . '`
    (`BoardID`, `ofBoardID`, `BoardTitle`, `BoardDesc`, `BoardManager`, `BoardSort`, `BoardEnable`)
    VALUES (?, ?, ?, ?, ?, ?, ?)';
    Utility::query($sql, 'iisssis', [$forum_id, $parent_forum, $forum_name, $forum_desc, $BoardManager, $forum_order, '1']) or Utility::web_error($sql, __FILE__, __LINE__);

    return $BoardID;
}

function listBoard($BoardID = '')
{
    global $xoopsDB, $xoopsTpl;

    $sql = 'SELECT a.*, b.`post_time`, b.`poster_ip` FROM `' . $xoopsDB->prefix('xf_topics') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('xf_posts') . '` AS b ON a.`topic_last_post_id` = b.`post_id` WHERE a.`forum_id` = ? ORDER BY a.`topic_id`';
    $result = Utility::query($sql, 'i', [$BoardID]) or die($sql);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數：`topic_id`, `topic_title`, `topic_poster`, `topic_time`, `topic_views`, `topic_replies`, `topic_last_post_id`, `forum_id`, `topic_status`, `topic_subject`, `topic_sticky`, `topic_digest`, `digest_time`, `approved`, `poster_name`, `rating`, `votes`, `topic_haspoll`, `poll_id`
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $topic_time = date('Y-m-d H:i:s', $topic_time);
        $post_time = date('Y-m-d H:i:s', $post_time);
        $poster_ip = long2ip($poster_ip);

        $all_content[$i]['topic_id'] = $topic_id;
        $all_content[$i]['topic_poster'] = $topic_poster;
        $all_content[$i]['topic_title'] = $topic_title;
        $all_content[$i]['BoardID'] = $BoardID;
        $all_content[$i]['topic_time'] = $topic_time;
        $all_content[$i]['forum_id'] = $forum_id;
        $all_content[$i]['post_time'] = $post_time;
        $all_content[$i]['topic_views'] = $topic_views;
        $all_content[$i]['poster_ip'] = $poster_ip;
        $all_content[$i]['i'] = $i;
        $i++;
    }

    //`DiscussID`, `ReDiscussID`, `uid`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `BoardID`, `LastTime`, `Counter`, `FromIP`, `Good`, `Bad`

    $xoopsTpl->assign('BoardID', $BoardID);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('op', 'listBoard');
}

function delXforum($topic_id = '')
{
    global $xoopsDB;
    $sql = 'SELECT `post_id` FROM `' . $xoopsDB->prefix('xf_posts') . '` WHERE `topic_id` = ?';
    $result = Utility::query($sql, 'i', [$topic_id]) or die($sql);

    while (list($post_id) = $xoopsDB->fetchRow($result)) {
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('xf_posts_text') . '` WHERE `post_id`=?';
        Utility::query($sql, 'i', [$post_id]) or Utility::web_error($sql, __FILE__, __LINE__);

        $sql = 'DELETE FROM `' . $xoopsDB->prefix('xf_posts') . '` WHERE `post_id`=?';
        Utility::query($sql, 'i', [$post_id]) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('xf_topics') . '` WHERE `topic_id`=?';
    Utility::query($sql, 'i', [$topic_id]) or Utility::web_error($sql, __FILE__, __LINE__);

}

function batch_del($batch_del = [])
{
    foreach ($batch_del as $topic_id) {
        delXforum($topic_id);
    }
}

function get_name_from_uid($uid = '')
{
    global $xoopsDB;
    $sql = 'SELECT `uname`, `name` FROM `' . $xoopsDB->prefix('users') . '` WHERE `uid` = ?';
    $result = Utility::query($sql, 'i', [$uid]) or Utility::web_error($sql, __FILE__, __LINE__);

    list($uname, $name) = $xoopsDB->fetchRow($result);
    if (!empty($name)) {
        return $name;
    }

    return $uname;
}

function copyDiscuss($BoardID = '', $mode = '')
{
    global $xoopsDB;

    if ('force' === $mode) {
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `BoardID`=?';
        Utility::query($sql, 'i', [$BoardID]) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    $sql = 'SELECT a.`topic_id`, a.`topic_title`, a.`topic_poster`, a.`topic_time`, a.`topic_views`, a.`topic_last_post_id`, b.`post_id`, b.`poster_ip`, c.`post_text` FROM `' . $xoopsDB->prefix('xf_topics') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('xf_posts') . '` AS b ON a.`topic_id` = b.`topic_id` AND b.`pid` = 0 LEFT JOIN `' . $xoopsDB->prefix('xf_posts_text') . '` AS c ON b.`post_id` = c.`post_id` WHERE a.`forum_id` = ? ORDER BY a.`topic_id`';
    $result = Utility::query($sql, 'i', [$BoardID]) or die($sql);

    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $post_text = Wcag::amend($post_text);

        $topic_time = date('Y-m-d H:i:s', $topic_time);
        $LastTime = getLastTime($topic_last_post_id);
        $poster_ip = long2ip($poster_ip);
        $publisher = get_name_from_uid($topic_poster);

        //主題
        $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_discuss') . '` (`DiscussID`, `ReDiscussID`, `uid`, `publisher`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `BoardID`, `LastTime`, `Counter`, `FromIP`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        Utility::query($sql, 'iiissssisis', [$post_id, 0, $topic_poster, $publisher, $topic_title, $post_text, $topic_time, $BoardID, $LastTime, $topic_views, $poster_ip]) or Utility::web_error($sql, __FILE__, __LINE__);

        $ReDiscussID = $post_id;

        //底下文章
        //`post_id`, `pid`, `topic_id`, `forum_id`, `post_time`, `uid`, `poster_name`, `poster_ip`, `subject`, `dohtml`, `dosmiley`, `doxcode`, `dobr`, `doimage`, `icon`, `attachsig`, `approved`, `post_karma`, `attachment`, `require_reply`, `tags`
        $sql2 = 'SELECT a.`post_id`, a.`uid`, a.`subject`, a.`post_time`, a.`poster_ip`, b.`post_text` FROM `' . $xoopsDB->prefix('xf_posts') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('xf_posts_text') . '` AS b ON a.`post_id` = b.`post_id` WHERE a.`topic_id` = ? AND a.`pid` != 0 ORDER BY a.`post_id`';
        $result2 = Utility::query($sql2, 'i', [$topic_id]) || die($sql2);

        while (false !== ($all2 = $xoopsDB->fetchArray($result2))) {
            foreach ($all2 as $k => $v) {
                $$k = $v;
            }

            $post_text = Wcag::amend($post_text);

            $post_time = date('Y-m-d H:i:s', $post_time);
            $poster_ip = long2ip($poster_ip);
            $publisher = get_name_from_uid($uid);

            //主題
            $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_discuss') . '` (`DiscussID`, `ReDiscussID`, `uid`, `publisher`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `BoardID`, `LastTime`, `Counter`, `FromIP`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)';
            Utility::query($sql, 'iiissssiss', [$post_id, $ReDiscussID, $uid, $publisher, $subject, $post_text, $post_time, $BoardID, $LastTime, $poster_ip]) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }
}

function getLastTime($post_id)
{
    global $xoopsDB;
    $sql2 = 'SELECT `post_time` FROM `' . $xoopsDB->prefix('xf_posts') . '` WHERE `post_id` = ?';
    $result2 = Utility::query($sql2, 'i', [$post_id]) || die($sql2);

    list($post_time) = $xoopsDB->fetchRow($result2);
    $post_time = date('Y-m-d H:i:s', $post_time);

    return $post_time;
}

function powerSet($BoardID = '')
{
    global $xoopsDB, $xoopsModule;
    $mid = $xoopsModule->mid();
    $read = explode(',', $_GET['read']);
    foreach ($read as $gperm_groupid) {
        $sql = 'REPLACE INTO `' . $xoopsDB->prefix('group_permission') . '` (`gperm_groupid`, `gperm_itemid`, `gperm_modid`, `gperm_name`) VALUES (?, ?, ?, ?)';
        Utility::query($sql, 'iiis', [$gperm_groupid, $BoardID, $mid, 'forum_read']) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    $post = explode(',', $_GET['post']);
    foreach ($post as $gperm_groupid) {
        $sql = 'REPLACE INTO `' . $xoopsDB->prefix('group_permission') . '` (`gperm_groupid`, `gperm_itemid`, `gperm_modid`, `gperm_name`) VALUES (?,?,?,?)';
        Utility::query($sql, 'iiis', [$gperm_groupid, $BoardID, $mid, 'forum_post']) or Utility::web_error($sql, __FILE__, __LINE__);

    }
}
