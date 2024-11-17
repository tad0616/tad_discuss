<?php
use Xmf\Request;
use XoopsModules\Tadtools\FooTable;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_discuss\Tools;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tad_discuss_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$TadUpFiles = new TadUpFiles('tad_discuss');

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$BoardID = Request::getInt('BoardID');
$DiscussID = Request::getInt('DiscussID');
$files_sn = Request::getInt('files_sn');

switch ($op) {
    default:
        list_tad_discuss_board(0);
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoopsTpl->assign('tad_discuss_adm', $tad_discuss_adm);
$xoTheme->addStylesheet('modules/tad_discuss/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//列出所有tad_discuss_board資料
function list_tad_discuss_board($ofBoardID = 0, $mode = 'tpl')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsTpl, $TadUpFiles, $xoopsModuleConfig, $tad_discuss_adm;

    //取得本模組編號
    $module_id = $xoopsModule->mid();

    //取得目前使用者的群組編號
    $groups = $xoopsUser ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];

    $gpermHandler = xoops_getHandler('groupperm');

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss_board') . '` WHERE `BoardEnable`=? AND `ofBoardID`=? ORDER BY `BoardSort`';
    $result = Utility::query($sql, 'si', ['1', $ofBoardID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (!$gpermHandler->checkRight('forum_read', $BoardID, $groups, $module_id)) {
            continue;
        }
        $post = $gpermHandler->checkRight('forum_post', $BoardID, $groups, $module_id);

        //$pic=get_pic_file('BoardID' , $BoardID , 1 , 'thumb');
        $TadUpFiles->set_col('BoardID', $BoardID);
        $pic = $TadUpFiles->get_pic_file('thumb'); //thumb 小圖, images 大圖（default）, file 檔案
        $pic = empty($pic) ? 'images/board.png' : $pic;

        $display_number = isset($xoopsModuleConfig['display_number']) ? (int) $xoopsModuleConfig['display_number'] : 7;
        $list_tad_discuss = list_tad_discuss_short($BoardID, $display_number);

        $fun = ($tad_discuss_adm) ? "<a href='admin/main.php?op=tad_discuss_board_form&BoardID=$BoardID'><img src='images/edit.png' alt='" . _TAD_EDIT . "'></a>" : '';
        $BoardManager = implode(' , ', getBoardManager($BoardID, 'uname'));

        $BoardNum = get_board_num($BoardID);
        $DiscussNum = get_board_num($BoardID, false);

        $all_content[$i]['post'] = $post;
        $all_content[$i]['pic'] = $pic;
        $all_content[$i]['BoardTitle'] = $BoardTitle;
        $all_content[$i]['BoardID'] = $BoardID;
        $all_content[$i]['ofBoardID'] = $ofBoardID;
        $all_content[$i]['fun'] = $fun;
        $all_content[$i]['BoardNum'] = sprintf(_MD_TADDISCUS_BOARD_DISCUSS, number_format($BoardNum));
        $all_content[$i]['DiscussNum'] = sprintf(_MD_TADDISCUS_ALL_DISCUSS, number_format($DiscussNum));
        $all_content[$i]['list_tad_discuss'] = $list_tad_discuss;
        $all_content[$i]['BoardManager'] = $BoardManager;
        $all_content[$i]['subBoard'] = list_tad_discuss_board($BoardID, 'return');

        $i++;
    }

    if ('return' === $mode) {
        return $all_content;
    }

    $FooTable = new FooTable();
    $FooTableJS = $FooTable->render();

    $xoopsTpl->assign('FooTableJS', $FooTableJS);
    $xoopsTpl->assign('all_content', $all_content);

    if ($xoopsUser) {
        $xoopsTpl->assign('login', true);
    } else {
        $xoopsTpl->assign('login', false);
    }
}

//列出所有tad_discuss資料
function list_tad_discuss_short($BoardID = null, $limit = null)
{
    global $xoopsDB;

    $myts = \MyTextSanitizer::getInstance();
    $andBoardID = empty($BoardID) ? '' : "AND a.BoardID = ?";
    $andLimit = null !== $limit ? "LIMIT 0, ?" : '';

    $params = [];
    if (!empty($BoardID)) {
        $params[] = $BoardID;
    }
    if (null !== $limit) {
        $params[] = $limit;
    }

    // 抓取討論資料
    $sql = 'SELECT a.*, b.* FROM `' . $xoopsDB->prefix('tad_discuss') . '` AS a
    LEFT JOIN `' . $xoopsDB->prefix('tad_discuss_board') . "` AS b ON a.BoardID = b.BoardID
    WHERE a.ReDiscussID = 0 $andBoardID
    ORDER BY a.LastTime DESC $andLimit";

    $result = Utility::query($sql, str_repeat('i', count($params)), $params) or Utility::web_error($sql, __FILE__, __LINE__);

    $main_data = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $renum = get_re_num($DiscussID);
        //$show_re_num=empty($renum)?"":sprintf(_MD_TADDISCUS_RE_DISCUSS,$renum);

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        $LastTime = mb_substr($LastTime, 0, 10);

        $isPublic = Tools::isPublic($onlyTo, $uid, $BoardID);
        $onlyToName = Tools::getOnlyToName($onlyTo);
        $DiscussTitle = $isPublic ? $myts->htmlSpecialChars($DiscussTitle) : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);

        $DiscussTitle = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussTitle);
        $DiscussTitle = str_replace('.gif]', ".gif' alt='emoji' class='emoji'>", $DiscussTitle);

        $main_data[$i]['LastTime'] = $LastTime;
        $main_data[$i]['DiscussID'] = $DiscussID;
        $main_data[$i]['BoardID'] = $BoardID;
        $main_data[$i]['DiscussTitle'] = $DiscussTitle;
        $main_data[$i]['uid_name'] = $uid_name;
        $main_data[$i]['renum'] = $renum;

        $i++;
    }

    return $main_data;
}
