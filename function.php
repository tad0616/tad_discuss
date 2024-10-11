<?php
use Xmf\Request;
use XoopsModules\Tadtools\FooTable;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;
use XoopsModules\Tad_discuss\Tools;

xoops_loadLanguage('main', 'tadtools');

/********************* 自訂函數 ********************
 * @param string $BoardID
 * @param string $DiscussID
 * @param string $DiscussContent
 * @param string $dir
 * @param string $uid
 * @param string $publisher
 * @param string $DiscussDate
 * @param string $mode
 * @param int    $Good
 * @param int    $Bad
 * @param int    $width
 * @param string $onlyTo
 * @return mixed
 */

//對話框格式
function talk_bubble($BoardID = '', $DiscussID = '', $DiscussContent = '', $dir = 'left', $uid = '', $publisher = '', $DiscussDate = '', $mode = '', $Good = 0, $Bad = 0, $width = 100, $onlyTo = '')
{
    global $xoopsUser, $xoopsTpl, $xoopsModuleConfig, $TadUpFiles;

    $op = Request::getString('op');
    $memberHandler = xoops_getHandler('member');
    $user = $memberHandler->getUser($uid);
    $pic = XOOPS_URL . '/modules/tad_discuss/images/nobody.png';
    $uid_name = _MD_TADDISCUS_NOBODY;
    $user_sig = '';
    if (is_object($user) and $uid) {
        $ts = \MyTextSanitizer::getInstance();
        $uid_name = empty($publisher) ? $ts->htmlSpecialChars($user->name()) : $publisher;
        if (empty($uid_name)) {
            $uid_name = $ts->htmlSpecialChars($user->uname());
        }
        $user_sig = $user->user_sig();
        $user_avatar = $ts->htmlSpecialChars($user->getVar('user_avatar'));
        $pic = !empty($user_avatar) ? XOOPS_URL . '/uploads/' . $user_avatar : $pic;
    }

    $pic_js = $pic_css = '';

    $now_uid = is_object($xoopsUser) ? $xoopsUser->uid() : '0';

    if ($now_uid == $uid) {
        $pic_js = "onClick=\"location.href='" . XOOPS_URL . "/edituser.php?op=avatarform'\"";
        $pic_css = 'cursor:pointer;';
    }

    $like = (!empty($DiscussID) && 'tad_discuss_form' !== $op) ? true : false;
    //$fun=(isMine($uid,$BoardID) and !empty($BoardID) and !empty($DiscussID) and $op!='tad_discuss_form')?true:false;

    $fun = (isMine($uid, $BoardID) && 'tad_discuss_form' !== $op && !empty($DiscussID)) ? true : false;

    //$files=show_files("DiscussID" , $DiscussID , true , '' , true , false);
    if ('tad_discuss_form' !== $op) {
        $TadUpFiles->set_col('DiscussID', $DiscussID);
        $files = $TadUpFiles->show_files('upfile', true, null, true, false); //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數
    }

    $files = Tools::isPublic($onlyTo, $uid, $BoardID) ? $files : '';
    $DiscussDate = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($DiscussDate)));
    if ('mobile' === $xoopsModuleConfig['display_mode']) {
        $DiscussDate = mb_substr($DiscussDate, 0, 16);
    }

    $onlyToName = Tools::getOnlyToName($onlyTo);

    $all['width'] = $width;
    $all['dir'] = $dir;
    $all['pic'] = $pic;
    $all['pic_css'] = $pic_css;
    $all['pic_js'] = $pic_js;
    $all['fun'] = $fun;

    if ('1' == $xoopsModuleConfig['show_like']) {
        $all['like'] = $like;
    } else {
        $all['like'] = '';
    }

    $all['uid'] = $uid;
    $all['uid_name'] = $uid_name;
    $all['user_sig'] = $user_sig;
    $all['DiscussDate'] = $DiscussDate;
    //$all['DiscussContent']=$DiscussContent;
    $all['DiscussContent'] = Tools::isPublic($onlyTo, $uid, $BoardID) ? $DiscussContent : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);
    $all['DiscussID'] = $DiscussID;
    $all['BoardID'] = $BoardID;
    $all['Bad'] = $Bad;
    $all['Good'] = $Good;
    $all['files'] = $files;
    $all['onlyTo'] = $onlyTo;
    $all['show_sig'] = $xoopsModuleConfig['show_sig'];
    $all['sig_style'] = $xoopsModuleConfig['sig_style'];
    //die(var_export($all));
    if ('return' === $mode) {
        return $all;
    }
    $xoopsTpl->assign('discuss', $all);
}

//新增資料到tad_discuss_board中
function insert_tad_discuss_board($BoardTitle = '')
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $BoardDesc = Wcag::amend($_POST['BoardDesc']);

    $BoardManager = is_array($_POST['BoardManager']) ? implode(',', $_POST['BoardManager']) : $_POST['BoardManager'];
    if (empty($BoardManager)) {
        $BoardManager = $xoopsUser->uid();
    }

    $forum_read = Request::getArray('forum_read', [1, 2, 3]);
    $forum_post = Request::getArray('forum_post', [1, 2]);
    $BoardEnable = Request::getInt('BoardEnable', 1);
    $ofBoardID = Request::getInt('ofBoardID');

    $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_discuss_board') . '`
    (`ofBoardID`, `BoardTitle`, `BoardDesc`, `BoardManager`, `BoardEnable`)
    VALUES (?, ?, ?, ?, ?)';
    Utility::query($sql, 'issss', [$ofBoardID, $BoardTitle, $BoardDesc, $BoardManager, $BoardEnable]) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $BoardID = $xoopsDB->getInsertId();

    //寫入權限
    saveItem_Permissions($forum_read, $BoardID, 'forum_read');
    saveItem_Permissions($forum_post, $BoardID, 'forum_post');

    $TadUpFiles->set_col('BoardID', $BoardID);
    $TadUpFiles->upload_file('upfile', 1024, 120, null, '', true);

    return $BoardID;
}

//新增資料到tad_discuss_cbox_setup中
function insert_tad_discuss_cbox_setup($setupName = '', $setupRule = '', $newBorard = '', $BoardID = '')
{
    global $xoopsDB;

    if (!empty($newBorard)) {
        $BoardID = insert_tad_discuss_board($newBorard);
    }

    $setupSort = tad_discuss_cbox_setup_max_sort();

    $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . '` (`setupName`, `setupRule`, `BoardID`, `setupSort`) VALUES (?, ?, ?, ?)';
    Utility::query($sql, 'ssii', [$setupName, $setupRule, $BoardID, $setupSort]) or Utility::web_error($sql, __FILE__, __LINE__);

    return $BoardID;
}

//自動取得tad_discuss_cbox_setup的最新排序
function tad_discuss_cbox_setup_max_sort()
{
    global $xoopsDB;
    $sql = 'SELECT MAX(`setupSort`) FROM `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . '`';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    list($sort) = $xoopsDB->fetchRow($result);

    return ++$sort;
}

//儲存權限
function saveItem_Permissions($groups, $itemid, $perm_name)
{
    global $xoopsModule;
    $module_id = $xoopsModule->mid();
    $gpermHandler = xoops_getHandler('groupperm');

    // First, if the permissions are already there, delete them
    $gpermHandler->deleteByModule($module_id, $perm_name, $itemid);

    // Save the new permissions
    if (count($groups) > 0) {
        foreach ($groups as $group_id) {
            $gpermHandler->addRight($perm_name, $itemid, $group_id, $module_id);
        }
    }
}

//列出所有tad_discuss資料
function list_tad_discuss($DefBoardID = null)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsModuleConfig, $xoopsTpl;
    $myts = \MyTextSanitizer::getInstance();
    //取得本模組編號
    $module_id = $xoopsModule->mid();

    $uid = $xoopsUser ? $xoopsUser->uid() : 0;
    $groups = $xoopsUser ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];

    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('forum_read', $DefBoardID, $groups, $module_id)) {
        header('location:index.php');
    }

    $post = $gpermHandler->checkRight('forum_post', $DefBoardID, $groups, $module_id);
    $xoopsTpl->assign('post', $post);

    $andBoardID = (empty($DefBoardID)) ? '' : "and a.BoardID='$DefBoardID'";
    $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_discuss') . ' as a left join ' . $xoopsDB->prefix('tad_discuss_board') . " as b on a.BoardID = b.BoardID where a.ReDiscussID='0' and b.BoardEnable='1' $andBoardID  order by a.LastTime desc";

    //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, $xoopsModuleConfig['show_discuss_amount'], 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $main_data = [];
    $i = 1;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $renum = get_re_num($DiscussID);
        $renum = empty($renum) ? '0' : $renum;

        if (empty($publisher)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 1);
            if (empty($uid_name)) {
                $uid_name = \XoopsUser::getUnameFromId($uid, 0);
            }
        } else {
            $uid_name = $publisher;
        }

        //最後回應者
        $sql2 = 'SELECT `uid`,`publisher` FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `ReDiscussID`=? ORDER BY `DiscussDate` DESC LIMIT 0,1';
        $result2 = Utility::query($sql2, 'i', [$DiscussID]) or Utility::web_error($sql2);

        list($last_uid, $last_uid_name) = $xoopsDB->fetchRow($result2);
        if (empty($last_uid_name)) {
            if (empty($last_uid)) {
                $last_uid_name = $uid_name;
            } else {
                $last_uid_name = \XoopsUser::getUnameFromId($last_uid, 1);
                if (empty($last_uid_name)) {
                    $last_uid_name = \XoopsUser::getUnameFromId($last_uid, 0);
                }
            }
        }

        $LastTime = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($LastTime)));
        $LastTime = mb_substr($LastTime, 0, 16);
        $DiscussDate = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($DiscussDate)));
        $DiscussDate = mb_substr($DiscussDate, 0, 16);

        $isPublic = Tools::isPublic($onlyTo, $uid, $DefBoardID);
        $onlyToName = Tools::getOnlyToName($onlyTo);

        $DiscussTitle = $myts->htmlSpecialChars($DiscussTitle);
        $DiscussTitle = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussTitle);
        $DiscussTitle = str_replace('.gif]', ".gif' alt='emoji' class='emoji'>", $DiscussTitle);

        $main_data[$i]['LastTime'] = $LastTime;
        $main_data[$i]['DiscussID'] = $DiscussID;
        $main_data[$i]['BoardID'] = $BoardID;
        $main_data[$i]['DiscussTitle'] = $isPublic ? $DiscussTitle : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);
        $main_data[$i]['uid_name'] = $uid_name;
        $main_data[$i]['renum'] = $renum;
        $main_data[$i]['DiscussDate'] = $DiscussDate;
        $main_data[$i]['LastTime'] = $LastTime;
        $main_data[$i]['last_uid_name'] = $last_uid_name;
        $main_data[$i]['isPublic'] = $isPublic;
        $main_data[$i]['onlyTo'] = $onlyTo;
        $i++;
    }

    $xoopsTpl->assign('main_data', $main_data);
    $xoopsTpl->assign('DefBoardID', $DefBoardID);

    $post_tool = ($post and !empty($DefBoardID)) ? "<a href='{$_SERVER['PHP_SELF']}?op=tad_discuss_form&BoardID=$DefBoardID' class='btn btn-default btn-secondary'><img src='images/edit.png' align='absmiddle' hspace=4 alt='" . _MD_TADDISCUS_ADD_DISCUSS . "'>" . _MD_TADDISCUS_ADD_DISCUSS . '</a>' : '';

    $FooTable = new FooTable();
    $FooTableJS = $FooTable->render();

    $ShowBoardTitle = '';
    if (!empty($DefBoardID)) {
        $ShowBoardTitle = get_board_title($DefBoardID);
    }

    $xoopsTpl->assign('FooTableJS', $FooTableJS);
    $xoopsTpl->assign('post_tool', $post_tool);
    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('ShowBoardTitle', $ShowBoardTitle);
}

//討論區標題
function get_board_title($DefBoardID = '')
{
    global $TadUpFiles;
    if (empty($DefBoardID)) {
        return;
    }

    $Board = Tools::get_tad_discuss_board($DefBoardID);
    //$pic=get_pic_file('BoardID' , $Board['BoardID'] , 1 , 'thumb');
    $TadUpFiles->set_col('BoardID', $DefBoardID);
    $pic = $TadUpFiles->get_pic_file('thumb'); //thumb 小圖, images 大圖（default）, file 檔案
    $pic = empty($pic) ? XOOPS_URL . '/modules/tad_discuss/images/board.png' : $pic;
    $main = "<div style='width:90px;height:60px;background: transparent url($pic) no-repeat center top;-moz-border-radius: 5px;-khtml-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;position:relative;float:left;margin:0px 10px 6px 0px;' alt='{$Board['BoardTitle']}' title='{$Board['BoardTitle']}'></div>{$Board['BoardTitle']}<div style='font-size: 80%;color:gray;font-weight:normal;cursor:pointer;' onClick=\"location.href='discuss.php?BoardID={$DefBoardID}'\">{$Board['BoardDesc']}</div><div style='clear:both'></div>";

    return $main;
}

//以流水號取得某筆tad_discuss資料
function get_tad_discuss($DiscussID = '')
{
    global $xoopsDB;
    if (empty($DiscussID)) {
        return;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `DiscussID` =?';
    $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//取得文章數量
function get_board_num($BoardID = '', $onlyMainDiscuss = true)
{
    global $xoopsDB;
    if (empty($BoardID)) {
        return 0;
    }

    $andMainDiscuss = ($onlyMainDiscuss) ? "AND `ReDiscussID` = 0" : '';
    $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `BoardID`=? ' . $andMainDiscuss;
    $result = Utility::query($sql, 'i', [$BoardID]) or Utility::web_error($sql, __FILE__, __LINE__);

    list($counter) = $xoopsDB->fetchRow($result);

    return $counter;
}

//取得回覆數量
function get_re_num($DiscussID = '')
{
    global $xoopsDB;
    if (empty($DiscussID)) {
        return 0;
    }

    $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `ReDiscussID`=?';
    $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

    list($counter) = $xoopsDB->fetchRow($result);

    return $counter;
}

//是否有管理權（或由自己發布的），判斷是否要秀出管理工具
function isMine($discuss_uid = null, $BoardID = null)
{
    global $xoopsUser;
    if (empty($xoopsUser)) {
        return false;
    }

    if ($BoardID) {
        $board = Tools::get_tad_discuss_board($BoardID);
        $BoardManagerArr = explode(',', $board['BoardManager']);
    } else {
        $BoardManagerArr = [];
    }
    $uid = $xoopsUser->uid();

    if ($_SESSION['tad_discuss_adm']) {
        return true;
    } elseif (in_array($uid, $BoardManagerArr)) {
        return true;
    } elseif ($uid == $discuss_uid) {
        return true;
    }

    return false;
}

//取得版主姓名
function getBoardManager($BoardID = '', $mode = '')
{
    if (empty($BoardID)) {
        return false;
    }

    $board = Tools::get_tad_discuss_board($BoardID);
    $BoardManagerArr = explode(',', $board['BoardManager']);
    foreach ($BoardManagerArr as $uid) {
        $BoardManagerName = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($BoardManagerName)) {
            $BoardManagerName = \XoopsUser::getUnameFromId($uid, 0);
        }

        $name[] = "<a href='" . XOOPS_URL . "/userinfo.php?uid={$uid}'>{$BoardManagerName}</a>";
    }

    return $name;
}

//更新刪除時是否限制身份
function onlyMine($DiscussID = '')
{
    global $xoopsUser;
    $uid = is_object($xoopsUser) ? $xoopsUser->uid() : '0';
    $Discuss = get_tad_discuss($DiscussID);
    $board = Tools::get_tad_discuss_board($Discuss['BoardID']);
    $BoardManagerArr = explode(',', $board['BoardManager']);

    if ($_SESSION['tad_discuss_adm']) {
        return;
    } elseif (in_array($uid, $BoardManagerArr)) {
        return;
    }

    return "AND `uid`=$uid";
}

//刪除tad_discuss某筆資料資料
function delete_tad_discuss($DiscussID = '')
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    if (!$xoopsUser) {
        return;
    }

    $anduid = onlyMine($DiscussID);

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `DiscussID`=? ' . $anduid;
    if (Utility::query($sql, 'i', [$DiscussID])) {
        $TadUpFiles->set_col('DiscussID', $DiscussID); //若要整個刪除
        $TadUpFiles->del_files();

        $sql = 'SELECT `DiscussID` FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `ReDiscussID`=?';
        $result = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($DiscussID) = $xoopsDB->fetchRow($result)) {
            delete_tad_discuss($DiscussID);
        }
    } else {
        Utility::web_error($sql, __FILE__, __LINE__);
    }
}

//檢查是否有不當言論
function chk_spam($content = '')
{
    global $xoopsModuleConfig;
    $content = str_replace('/', '', $content);
    $content = str_replace(' ', '', $content);
    $content = str_replace('\\', '', $content);
    $content = str_replace('-', '', $content);
    $content = str_replace('+', '', $content);
    $content = str_replace('.', '', $content);
    $content = str_replace('|', '', $content);

    $keys = explode(',', $xoopsModuleConfig['spam_keyword']);
    foreach ($keys as $key) {
        $strpos = mb_strpos($content, $key);
        if (false !== $strpos) {
            return true;
        }
    }

    return false;
}

//新增資料到tad_discuss中
function insert_tad_discuss($nl2br = false)
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $memberHandler = xoops_getHandler('member');

    $uid = ($xoopsUser) ? $xoopsUser->uid() : (int) $_POST['uid'];

    $myts = \MyTextSanitizer::getInstance();

    if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $myip = $_SERVER['REMOTE_ADDR'];
    } else {
        $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $myip = $myip[0];
    }

    $ReDiscussID = isset($_POST['ReDiscussID']) ? (int) $_POST['ReDiscussID'] : 0;
    //$now=date('Y-m-d H:i:s',xoops_getUserTimestamp(time()));
    $Discuss = get_tad_discuss($ReDiscussID);
    $DiscussTitle = empty($_POST['DiscussTitle']) ? 'RE:' . $Discuss['DiscussTitle'] : $_POST['DiscussTitle'];
    $publisher = (string) $_POST['publisher'];
    $BoardID = (int) $_POST['BoardID'];

    $DiscussContent = Wcag::amend($_POST['DiscussContent']);
    if ($nl2br) {
        $DiscussContent = nl2br($DiscussContent);
    }

    if (chk_spam($DiscussTitle)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADDISCUS_FOUND_SPAM);
    }

    if (chk_spam($DiscussContent)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADDISCUS_FOUND_SPAM);
    }

    $onlyTo = '';
    if ('1' == $_POST['only_root'] and !empty($ReDiscussID)) {
        $onlyTo = $Discuss['uid'];
    } elseif ('1' == $_POST['only_root']) {
        $adminusers = $memberHandler->getUsersByGroup(1);
        $onlyTo = implode(',', $adminusers);
    }

    $time = date('Y-m-d H:i:s');
    $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_discuss') . '` (`ReDiscussID`, `uid`, `publisher`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `BoardID`, `LastTime`, `Counter`, `FromIP`, `onlyTo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?)';
    Utility::query($sql, 'iissssisss', [$ReDiscussID, $uid, $publisher, $DiscussTitle, $DiscussContent, $time, $BoardID, $time, $myip, $onlyTo]) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $DiscussID = $xoopsDB->getInsertId();

    if ($xoopsUser) {
        $xoopsUser->incrementPost();
    }

    $TadUpFiles->set_col('DiscussID', $DiscussID);
    //$TadUpFiles->upload_file($upname,$width,$thumb_width,$files_sn,$desc,$safe_name=false,$hash=false);
    $TadUpFiles->upload_file('upfile', 1024, 120, null, '', true);

    $ToDiscussID = $DiscussID;
    if (!empty($ReDiscussID)) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_discuss') . '` SET `LastTime` = ? WHERE `DiscussID` = ? OR `ReDiscussID` = ?';
        Utility::query($sql, 'sii', [$time, $ReDiscussID, $ReDiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $ToDiscussID = $ReDiscussID;
    }

    //全局
    $extra_tags['DISCUSS_TITLE'] = $myts->htmlSpecialChars($_POST['DiscussTitle']);
    $extra_tags['DISCUSS_CONTENT'] = strip_tags($_POST['DiscussContent']);
    $extra_tags['DISCUSS_URL'] = XOOPS_URL . "/modules/tad_discuss/discuss.php?DiscussID={$ToDiscussID}&BoardID={$_POST['BoardID']}";

    $notificationHandler = xoops_getHandler('notification');
    $notificationHandler->triggerEvent('global', 0, 'new_discuss', $extra_tags, null, null, 0);

    //分類
    if (!empty($_POST['BoardID'])) {
        $Board = Tools::get_tad_discuss_board($_POST['BoardID']);
        $extra_tags['BOARD_TITLE'] = $myts->htmlSpecialChars($Board['BoardTitle']);
        $notificationHandler = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('board', $_POST['BoardID'], 'new_board_discuss', $extra_tags, null, null, 0);
    }

    if (!empty($ReDiscussID)) {
        return $ReDiscussID;
    }

    return $DiscussID;
}
