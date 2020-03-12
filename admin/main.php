<?php
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_discuss_adm_main.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
$TadUpFiles = new TadUpFiles('tad_discuss');

/*-----------function區--------------*/
//tad_discuss_board編輯表單
function tad_discuss_board_form($BoardID = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $xoopsTpl, $TadUpFiles;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    //抓取預設值
    if (!empty($BoardID)) {
        $DBV = get_tad_discuss_board($BoardID);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定「BoardID」欄位預設值
    $BoardID = (!isset($DBV['BoardID'])) ? $BoardID : $DBV['BoardID'];

    //設定「ofBoardID」欄位預設值
    $ofBoardID = (!isset($DBV['ofBoardID'])) ? 0 : $DBV['ofBoardID'];

    //設定「BoardTitle」欄位預設值
    $BoardTitle = (!isset($DBV['BoardTitle'])) ? null : $DBV['BoardTitle'];

    //設定「BoardDesc」欄位預設值
    $BoardDesc = (!isset($DBV['BoardDesc'])) ? '' : $DBV['BoardDesc'];

    //設定「BoardManager」欄位預設值
    $BoardManager = (!isset($DBV['BoardManager'])) ? $xoopsUser->uid() : $DBV['BoardManager'];

    //設定「BoardEnable」欄位預設值
    $BoardEnable = (!isset($DBV['BoardEnable'])) ? '1' : $DBV['BoardEnable'];

    $op = (empty($BoardID)) ? 'insert_tad_discuss_board' : 'update_tad_discuss_board';
    //$op="replace_tad_discuss_board";

    $BoardManagerArr = explode(',', $BoardManager);

    $memberHandler = xoops_getHandler('member');
    $usercount = $memberHandler->getUserCount(new \Criteria('level', 0, '>'));

    if ($usercount < 2000) {
        $select = new XoopsFormSelect('', 'BoardManager', $BoardManagerArr, 5, true);
        $member_handler = xoops_getHandler('member');
        $criteria = new CriteriaCompo();
        $criteria->setSort('uname');
        $criteria->setOrder('ASC');
        $criteria->setLimit(2000);
        $criteria->setStart(0);

        $select->addOptionArray($memberHandler->getUserList($criteria));
        $select->setExtra("class='span12'");
        $user_menu = $select->render();
    } else {
        $user_menu = "<textarea name='BoardManager' style='width:100%;'>$BoardManager</textarea>
    <div>user uid, ex:\"1,27,103\"</div>";
    }

    //取得本模組編號
    $module_id = $xoopsModule->mid();
    $modulepermHandler = xoops_getHandler('groupperm');
    $read_group = $modulepermHandler->getGroupIds('forum_read', $BoardID, $module_id);
    $post_group = $modulepermHandler->getGroupIds('forum_post', $BoardID, $module_id);

    if (empty($read_group)) {
        $read_group = [1, 2, 3];
    }

    if (empty($post_group)) {
        $post_group = [1, 2];
    }

    //可見群組
    $SelectGroup_name = new \XoopsFormSelectGroup('', 'forum_read', true, $read_group, 6, true);
    $SelectGroup_name->setExtra("class='col-sm-12'");
    $enable_read_group = $SelectGroup_name->render();

    //可上傳群組
    $SelectGroup_name = new \XoopsFormSelectGroup('', 'forum_post', true, $post_group, 6, true);
    $SelectGroup_name->setExtra("class='col-sm-12'");
    $enable_post_group = $SelectGroup_name->render();

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    $TadUpFiles->set_col('BoardID', $BoardID); //若 $show_list_del_file ==true 時一定要有
    $upform = $TadUpFiles->upform(false, 'upfile', 1, true, 'gif|jpg|png|GIF|JPG|PNG');

    $xoopsTpl->assign('BoardID', $BoardID);
    $xoopsTpl->assign('ofBoardID', $ofBoardID);
    $xoopsTpl->assign('BoardTitle', $BoardTitle);
    $xoopsTpl->assign('BoardDesc', $BoardDesc);
    $xoopsTpl->assign('enable_read_group', $enable_read_group);
    $xoopsTpl->assign('enable_post_group', $enable_post_group);
    $xoopsTpl->assign('upform', $upform);
    $xoopsTpl->assign('user_menu', $user_menu);
    $xoopsTpl->assign('BoardEnable1', Utility::chk($BoardEnable, '1'));
    $xoopsTpl->assign('BoardEnable0', Utility::chk($BoardEnable, '0'));
    $xoopsTpl->assign('next_op', $op);

    $xoopsTpl->assign('op', 'tad_discuss_board_form');

    $notBoardID = empty($BoardID) ? '' : "and BoardID!='{$BoardID}'";
    $ofBoardArr = [];
    $i = 0;
    $sql = 'select BoardID,BoardTitle from `' . $xoopsDB->prefix('tad_discuss_board') . "` where BoardEnable='1' and `ofBoardID`=0 $notBoardID order by BoardSort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($BoardID, $BoardTitle) = $xoopsDB->fetchRow($result)) {
        $ofBoardArr[$i]['BoardID'] = $BoardID;
        $ofBoardArr[$i]['BoardTitle'] = $BoardTitle;
        $i++;
    }
    $xoopsTpl->assign('ofBoardArr', $ofBoardArr);
}

//更新tad_discuss_board某一筆資料
function update_tad_discuss_board($BoardID = '')
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $myts = \MyTextSanitizer::getInstance();
    $_POST['BoardDesc'] = $myts->addSlashes($_POST['BoardDesc']);
    $_POST['BoardTitle'] = $myts->addSlashes($_POST['BoardTitle']);

    $BoardManager = is_array($_POST['BoardManager']) ? implode(',', $_POST['BoardManager']) : $_POST['BoardManager'];

    $sql = 'update `' . $xoopsDB->prefix('tad_discuss_board') . "` set
   `ofBoardID` = '{$_POST['ofBoardID']}' ,
   `BoardTitle` = '{$_POST['BoardTitle']}' ,
   `BoardDesc` = '{$_POST['BoardDesc']}' ,
   `BoardManager` = '{$BoardManager}' ,
   `BoardEnable` = '{$_POST['BoardEnable']}'
  where `BoardID` = '$BoardID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //寫入權限
    saveItem_Permissions($_POST['forum_read'], $BoardID, 'forum_read');
    saveItem_Permissions($_POST['forum_post'], $BoardID, 'forum_post');

    $TadUpFiles->set_col('BoardID', $BoardID);
    $TadUpFiles->upload_file('upfile', 1024, 120, null, '', true);

    return $BoardID;
}

//列出所有tad_discuss_board資料
function list_tad_discuss_board($ofBoardID = 0, $mode = 'tpl')
{
    global $xoopsDB, $xoopsModule, $isAdmin, $xoopsTpl, $TadUpFiles;

    $sql = 'select * from `' . $xoopsDB->prefix('tad_discuss_board') . "` where `ofBoardID`='{$ofBoardID}' order by BoardSort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //$pic=get_pic_file('BoardID' , $BoardID , 1 , 'thumb');

        $TadUpFiles->set_col('BoardID', $BoardID);
        $pic = $TadUpFiles->get_pic_file('thumb'); //thumb 小圖, images 大圖（default）, file 檔案

        $pic = empty($pic) ? '../images/board.png' : $pic;

        $BoardNum = get_board_num($BoardID);
        $BoardNum2 = get_board_num($BoardID, false);

        $color = ('0' == $BoardEnable) ? '#f5f5f5' : 'white';

        $BoardManagerArr = explode(',', $BoardManager);
        $manager = [];
        foreach ($BoardManagerArr as $uid) {
            if (empty($uid)) {
                continue;
            }

            $uid_name = \XoopsUser::getUnameFromId($uid, 1);
            if (empty($uid_name)) {
                $uid_name = \XoopsUser::getUnameFromId($uid, 0);
            }

            $manager[] = $uid_name;
        }
        $BoardManager = implode(' , ', $manager);

        $all_content[$i]['BoardID'] = $BoardID;
        $all_content[$i]['color'] = $color;
        $all_content[$i]['pic'] = $pic;
        $all_content[$i]['BoardTitle'] = $BoardTitle;
        $all_content[$i]['BoardDesc'] = $BoardDesc;
        $all_content[$i]['BoardNum'] = sprintf(_MA_TADDISCUS_BOARD_DISCUSS, number_format($BoardNum));
        $all_content[$i]['BoardNum2'] = sprintf(_MA_TADDISCUS_ALL_DISCUSS, number_format($BoardNum2));
        $all_content[$i]['BoardManager'] = $BoardManager;
        $all_content[$i]['BoardEnable'] = $BoardEnable;
        $all_content[$i]['subBoard'] = list_tad_discuss_board($BoardID, 'return');
        $all_content[$i]['board_menu_options'] = get_tad_discuss_board_menu_options($BoardID);

        $i++;
    }

    if ('return' === $mode) {
        return $all_content;
    }

    $xoopsTpl->assign('all_content', $all_content);

    $xoopsTpl->assign('jquery', Utility::get_jquery(true));
}

//取得tad_discuss_board分類選單的選項（單層選單）
function get_tad_discuss_board_menu_options($default_BoardID = '0')
{
    global $xoopsDB, $xoopsModule;
    $sql = 'SELECT `BoardID` , `ofBoardID` , `BoardTitle` FROM `' . $xoopsDB->prefix('tad_discuss_board') . '` ORDER BY `BoardSort`';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $option = '';
    while (list($BoardID, $ofBoardID, $BoardTitle) = $xoopsDB->fetchRow($result)) {
        if ($BoardID == $default_BoardID) {
            continue;
        }

        $option .= "<option value=$BoardID>{$BoardTitle}</option>";
    }

    return $option;
}

//刪除tad_discuss_board某筆資料資料
function delete_tad_discuss_board($BoardID = '')
{
    global $xoopsDB, $isAdmin, $TadUpFiles;
    $sql = 'select DiscussID from ' . $xoopsDB->prefix('tad_discuss') . " where BoardID='$BoardID' and ReDiscussID=0";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($DiscussID) = $xoopsDB->fetchRow($result)) {
        delete_tad_discuss($DiscussID);
    }

    $sql = 'delete from `' . $xoopsDB->prefix('tad_discuss_board') . "` where `BoardID` = '{$BoardID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    //del_files('' , "BoardID" , $BoardID);
    $TadUpFiles->set_col('BoardID', $BoardID); //若要整個刪除
    $TadUpFiles->del_files();
}

//合併討論區
function moveToBoardID($BoardID = '', $NewBoardID = '')
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    if (empty($BoardID) or empty($NewBoardID)) {
        return;
    }

    $sql = 'update `' . $xoopsDB->prefix('tad_discuss') . "` set `BoardID` = '{$NewBoardID}' where `BoardID` = '$BoardID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from `' . $xoopsDB->prefix('tad_discuss_board') . "` where `BoardID` = '$BoardID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $TadUpFiles->set_col('BoardID', $BoardID); //若要整個刪除
    $TadUpFiles->del_files();
}

function changeBoardStatus($BoardID = '', $act = '0')
{
    global $xoopsDB, $xoopsUser;

    if (empty($BoardID)) {
        return;
    }

    $sql = 'update `' . $xoopsDB->prefix('tad_discuss_board') . "` set `BoardEnable` = '{$act}' where `BoardID` = '$BoardID'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$BoardID = system_CleanVars($_REQUEST, 'BoardID', 0, 'int');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');
$NewBoardID = system_CleanVars($_REQUEST, 'NewBoardID', 0, 'int');
$files_sn = system_CleanVars($_REQUEST, 'files_sn', 0, 'int');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    //替換資料
    case 'replace_tad_discuss_board':
        replace_tad_discuss_board();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    //新增資料
    case 'insert_tad_discuss_board':
        $BoardID = insert_tad_discuss_board($_POST['BoardTitle']);
        header("location: {$_SERVER['PHP_SELF']}?BoardID=$BoardID");
        exit;
        break;
    //更新資料
    case 'update_tad_discuss_board':
        update_tad_discuss_board($BoardID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    //輸入表格
    case 'tad_discuss_board_form':
        tad_discuss_board_form($BoardID);
        break;
    //刪除資料
    case 'delete_tad_discuss_board':
        delete_tad_discuss_board($BoardID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    case 'moveToBoardID':
        moveToBoardID($BoardID, $NewBoardID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    case 'changeBoardStatus':
        changeBoardStatus($BoardID, $_GET['act']);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;
    //預設動作
    default:
        if (empty($BoardID)) {
            list_tad_discuss_board(0);
        } else {
            header("location: ../discuss.php?BoardID=$BoardID");
            exit;
        }
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
