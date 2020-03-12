<?php
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_discuss_adm_cbox_setup.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
$TadUpFiles = new TadUpFiles('tad_discuss');
/*-----------function區--------------*/
//tad_discuss_cbox_setup編輯表單
function tad_discuss_cbox_setup_form($setupID = '')
{
    global $xoopsDB, $xoopsTpl;

    //抓取預設值
    if (!empty($setupID)) {
        $DBV = get_tad_discuss_cbox_setup($setupID);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定「setupID」欄位預設值
    $setupID = !isset($DBV['setupID']) ? $setupID : $DBV['setupID'];
    $xoopsTpl->assign('setupID', $setupID);

    //設定「setupName」欄位預設值
    $setupName = !isset($DBV['setupName']) ? null : $DBV['setupName'];
    $xoopsTpl->assign('setupName', $setupName);

    //設定「setupRule」欄位預設值
    $setupRule = !isset($DBV['setupRule']) ? '' : $DBV['setupRule'];
    $xoopsTpl->assign('setupRule', $setupRule);

    //設定「BoardID」欄位預設值
    $BoardID = !isset($DBV['BoardID']) ? '' : $DBV['BoardID'];
    $xoopsTpl->assign('BoardID', $BoardID);

    //設定「setupSort」欄位預設值
    $setupSort = !isset($DBV['setupSort']) ? tad_discuss_cbox_setup_max_sort() : $DBV['setupSort'];
    $xoopsTpl->assign('setupSort', $setupSort);

    $op = (empty($setupID)) ? 'insert_tad_discuss_cbox_setup' : 'update_tad_discuss_cbox_setup';
    //$op="replace_tad_discuss_cbox_setup";

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('now_op', 'tad_discuss_cbox_setup_form');
    $xoopsTpl->assign('next_op', $op);

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss_board') . "` WHERE BoardEnable='1' ORDER BY BoardSort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $option[$i]['BoardID'] = $BoardID;
        $option[$i]['BoardTitle'] = $BoardTitle;
        $i++;
    }
    $xoopsTpl->assign('option', $option);
}

//更新tad_discuss_cbox_setup某一筆資料
function update_tad_discuss_cbox_setup($setupID = '')
{
    global $xoopsDB, $xoopsUser;

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->uid() : '';

    $myts = \MyTextSanitizer::getInstance();
    $_POST['setupName'] = $myts->addSlashes($_POST['setupName']);
    $_POST['setupRule'] = $myts->addSlashes($_POST['setupRule']);

    $sql = 'update `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . "` set
   `setupName` = '{$_POST['setupName']}' ,
   `setupRule` = '{$_POST['setupRule']}' ,
   `BoardID` = '{$_POST['BoardID']}'
  where `setupID` = '$setupID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $setupID;
}

//列出所有tad_discuss_cbox_setup資料
function list_tad_discuss_cbox_setup()
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . '` ORDER BY `setupSort`';

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $setupID , $setupName , $setupRule , $BoardID , $setupSort
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $Board = get_tad_discuss_board($BoardID);

        $all_content[$i]['setupID'] = $setupID;
        $all_content[$i]['setupName'] = "<a href='{$_SERVER['PHP_SELF']}?setupID={$setupID}'>{$setupName}</a>";
        $all_content[$i]['setupRule'] = $setupRule;
        $all_content[$i]['BoardID'] = $BoardID;
        $all_content[$i]['BoardTitle'] = $Board['BoardTitle'];
        $all_content[$i]['setupSort'] = $setupSort;
        $i++;
    }

    //$xoopsTpl->assign('bar' , $bar);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('isAdmin', $isAdmin);
    $xoopsTpl->assign('all_content', $all_content);

    $xoopsTpl->assign('jquery', Utility::get_jquery(true));
    //$xoopsTpl->assign('now_op' , 'list_tad_discuss_cbox_setup');
}

//以流水號取得某筆tad_discuss_cbox_setup資料
function get_tad_discuss_cbox_setup($setupID = '')
{
    global $xoopsDB;
    if (empty($setupID)) {
        return;
    }

    $sql = 'select * from `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . "` where `setupID` = '{$setupID}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//刪除tad_discuss_cbox_setup某筆資料資料
function delete_tad_discuss_cbox_setup($setupID = '')
{
    global $xoopsDB, $isAdmin;
    $sql = 'delete from `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . "` where `setupID` = '{$setupID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//以流水號秀出某筆tad_discuss_cbox_setup資料內容
function show_one_tad_discuss_cbox_setup($setupID = '')
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    if (empty($setupID)) {
        return;
    }
    $setupID = (int) $setupID;

    $sql = 'select * from `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . "` where `setupID` = '{$setupID}' ";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $all = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $setupID , $setupName , $setupRule , $BoardID
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    $xoopsTpl->assign('setupID', $setupID);
    $xoopsTpl->assign('setupName', "<a href='{$_SERVER['PHP_SELF']}?setupID={$setupID}'>{$setupName}</a>");
    $xoopsTpl->assign('setupRule', $setupRule);
    $xoopsTpl->assign('BoardID', $BoardID);

    $xoopsTpl->assign('now_op', 'show_one_tad_discuss_cbox_setup');
    $xoopsTpl->assign('title', $setupName);
    $xoopsTpl->assign('setupSort', $setupSort);
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$BoardID = system_CleanVars($_REQUEST, 'BoardID', 0, 'int');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');
$setupID = system_CleanVars($_REQUEST, 'setupID', 0, 'int');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    //替換資料
    case 'replace_tad_discuss_cbox_setup':
        replace_tad_discuss_cbox_setup();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    //新增資料
    case 'insert_tad_discuss_cbox_setup':
        insert_tad_discuss_cbox_setup($_POST['setupName'], $_POST['setupRule'], $_POST['newBorard'], $_POST['BoardID']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    //更新資料
    case 'update_tad_discuss_cbox_setup':
        update_tad_discuss_cbox_setup($setupID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    //輸入表格
    case 'tad_discuss_cbox_setup_form':
        tad_discuss_cbox_setup_form($setupID);
        list_tad_discuss_cbox_setup();
        break;
    //刪除資料
    case 'delete_tad_discuss_cbox_setup':
        delete_tad_discuss_cbox_setup($setupID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    //預設動作
    default:
        tad_discuss_cbox_setup_form($setupID);
        list_tad_discuss_cbox_setup();
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
