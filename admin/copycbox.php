<?php
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_discuss_adm_copycbox.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------function區--------------*/

//列出所有tad_discuss_board資料
function list_cbox()
{
    global $xoopsDB, $xoopsModule, $isAdmin, $xoopsTpl;

    //取得某模組編號
    $moduleHandler = xoops_getHandler('module');
    $ThexoopsModule = $moduleHandler->getByDirname('tad_cbox');
    if ($ThexoopsModule) {
        $mod_id = $ThexoopsModule->mid();
        $xoopsTpl->assign('show_error', '0');
    } else {
        $xoopsTpl->assign('show_error', '1');
        $xoopsTpl->assign('msg', _MA_TADDISCUS_NO_CBOX);

        return;
    }

    $sql = 'SELECT BoardID FROM `' . $xoopsDB->prefix('tad_discuss_board') . "` WHERE BoardTitle = '" . _MA_TADDISCUS_CBOX . "'";
    $result = $xoopsDB->query($sql) or die($sql);
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
    global $xoopsDB, $xoopsUser;

    $sql = 'select uid from `' . $xoopsDB->prefix('users') . "` where uname ='$publisher' or name='{$publisher}'";
    $result = $xoopsDB->query($sql) or die($sql);
    list($uid) = $xoopsDB->fetchRow($result);

    return $uid;
}

//新增資料到tad_discuss_board中
function copycbox($BoardID = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModule;
    set_time_limit(0);
    $myts = \MyTextSanitizer::getInstance();

    //取得目前使用者uid
    $root_uid = $xoopsUser->uid();

    if (empty($BoardID)) {
        //取得最大排序
        $sql = 'SELECT max(`BoardSort`) FROM ' . $xoopsDB->prefix('tad_discuss_board') . ' GROUP BY BoardSort';
        $result = $xoopsDB->queryF($sql);
        list($sort) = $xoopsDB->fetchRow($result);
        $sort++;

        //建立討論區
        $sql = 'insert into ' . $xoopsDB->prefix('tad_discuss_board') . " (`ofBoardID`, `BoardTitle`, `BoardDesc`, `BoardManager`, `BoardSort`, `BoardEnable`) VALUES(0 , '" . _MA_TADDISCUS_CBOX . "' , '" . _MA_TADDISCUS_CBOX_DESC . "' , '{$root_uid}' ,'{$sort}' , '1')";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $BoardID = $xoopsDB->getInsertId();

        //轉移權限（新權限）
        $mid = $xoopsModule->mid();
        //讀取權限
        $sql = 'insert into `' . $xoopsDB->prefix('group_permission') . "` (`gperm_groupid`, `gperm_itemid`, `gperm_modid`, `gperm_name`) values('1', '{$BoardID}', '{$mid}', 'forum_read'),('2', '{$BoardID}', '{$mid}', 'forum_read'),('3', '{$BoardID}', '{$mid}', 'forum_read')";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

        //寫入權限
        $sql = 'insert into `' . $xoopsDB->prefix('group_permission') . "` (`gperm_groupid`, `gperm_itemid`, `gperm_modid`, `gperm_name`) values('1', '{$BoardID}', '{$mid}', 'forum_post'),('2', '{$BoardID}', '{$mid}', 'forum_post')";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    } else {
        $sql = 'delete from ' . $xoopsDB->prefix('tad_discuss') . " where BoardID='{$BoardID}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }

    //讀取留言簿資料
    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_cbox') . ' ORDER BY post_date ';
    $result = $xoopsDB->queryF($sql);
    while (list($sn, $publisher, $msg, $post_date, $ip, $only_root, $root_msg) = $xoopsDB->fetchRow($result)) {
        $onlyTo = ($only_root) ? $root_uid : '';
        $DiscussTitle = xoops_substr($msg, 0, 60);

        $uid = get_uid_from_uname($publisher);
        $DiscussTitle = $myts->addSlashes($DiscussTitle);
        $msg = $myts->addSlashes($msg);
        $root_msg = $myts->addSlashes($root_msg);

        $sql = 'insert into ' . $xoopsDB->prefix('tad_discuss') . " ( `ReDiscussID`, `uid`, `publisher`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `BoardID`, `LastTime`, `Counter`, `FromIP`, `Good`, `Bad` , `onlyTo`) VALUES(0 , '{$uid}', '$publisher' , '{$DiscussTitle}' , '{$msg}' ,'{$post_date}' ,'{$BoardID}' ,'{$post_date}' ,'888' ,'{$ip}' ,'' ,'' ,'{$onlyTo}')";
        $xoopsDB->queryF($sql);
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

            $sql = 'insert into ' . $xoopsDB->prefix('tad_discuss') . " ( `ReDiscussID`, `uid`, `publisher`, `DiscussTitle`, `DiscussContent`, `DiscussDate`, `BoardID`, `LastTime`, `Counter`, `FromIP`, `Good`, `Bad` , `onlyTo`) VALUES('{$DiscussID}' , '{$root_uid}', '{$publisher}' , 'RE:{$DiscussTitle}' , '{$root_msg}' ,'{$post_date}' ,'{$BoardID}' ,'{$post_date}' ,'888' ,'{$ip}' ,'' ,'' , '{$onlyToUid}')";
            $xoopsDB->queryF($sql);
        }
    }

    return $BoardID;
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$BoardID = system_CleanVars($_REQUEST, 'BoardID', 0, 'int');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case 'copycbox':
        $BoardID = copycbox();
        header("location: ../discuss.php?BoardID={$BoardID}");
        exit;
        break;
    case 'forceUpdate':
        copycbox($BoardID);
        header("location: ../discuss.php?BoardID={$BoardID}");
        exit;
        break;
    //預設動作
    default:
        list_cbox();
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
