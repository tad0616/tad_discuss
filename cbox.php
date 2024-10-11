<?php
use Xmf\Request;
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_discuss\Tools;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

$TadUpFiles = new TadUpFiles('tad_discuss');

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$BoardID = Request::getInt('BoardID');
$DiscussID = Request::getInt('DiscussID');
$files_sn = Request::getInt('files_sn');

switch ($op) {
    //刪除資料
    case 'delete_tad_discuss':
        delete_tad_discuss($DiscussID);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //下載檔案
    case 'tufdl':
        $TadUpFiles->add_file_counter($files_sn);
        exit;

    default:
        $main = list_tad_discuss_cbox($BoardID);
        break;
}

error_reporting(0);
$xoopsLogger->activated = false;

/*-----------秀出結果區--------------*/
Utility::get_jquery();

$FancyBox = new FancyBox('.fancybox_Discuss');
$FancyBox->render();

echo "
<!DOCTYPE html>
<html lang='en'>
  <head>
  <meta charset='" . _CHARSET . "'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Post List</title>
  <link rel='stylesheet' type='text/css' media='screen' href='" . XOOPS_URL . "/modules/tad_discuss/css/cbox.css'>
</head>
<body bgcolor='#FFFFFF' style='scrollbar-face-color:#EDF3F7;scrollbar-shadow-color:#EDF3F7;scrollbar-highlight-color:#EDF3F7;scrollbar-3dlight-color:#FFFFFF;scrollbar-darkshadow-color:#FFFFFF;scrollbar-track-color:#FFFFFF;scrollbar-arrow-color:#232323;scrollbar-base-color:#FFFFFF;'>
  {$main}
</body>
</html>";

/*-----------function區--------------*/

//列出所有tad_discuss資料
function list_tad_discuss_cbox($DefBoardID = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $TadUpFiles;

    //取得本模組編號
    $module_id = $xoopsModule->mid();

    //取得目前使用者的群組編號
    $groups = $xoopsUser ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];
    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('forum_read', $DefBoardID, $groups, $module_id)) {
        header('location:index.php');
    }

    $jquery = Utility::get_jquery();

    $cbox_root_msg_color = Request::getString('cbox_root_msg_color', '#B4C58D');
    $bg_color = Request::getString('bg_color', '#FFFFFF');
    $font_color = Request::getString('font_color', '#000000');

    if ($_SESSION['tad_discuss_adm']) {
        $del_js = "
        function delete_tad_discuss_func(DiscussID){
          var sure = window.confirm('" . _TAD_DEL_CONFIRM . "');
          if (!sure)  return;
          location.href=\"{$_SERVER['PHP_SELF']}?BoardID={$DefBoardID}&op=delete_tad_discuss&DiscussID=\" + DiscussID;
        }";
    } else {
        $del_js = '';
    }

    $data = "
    <style>

    .triangle-border.top {
      border:5px solid $cbox_root_msg_color;
      background:$bg_color;
    }

    .triangle-border.top:before {
      border-color:$cbox_root_msg_color transparent;
    }

    .triangle-border.top:after {
      border-color:$bg_color transparent;
    }

    .MainDiscussContent {
      position: relative;
      padding: 15px;
      margin: 1em 0 3em;
      border: 5px solid $cbox_root_msg_color;
      color: $font_color;
      text-align: left;
      background: $bg_color;
    /* css3 */
      -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
      border-radius: 10px;
    }

    </style>
    $jquery
    <script type='text/javascript'>
      $del_js
      $(document).ready(function(){
        $('img').css('max-width','100%');
        $('iframe').css('width','100%');
      });
    </script>
    <h3 style='display: none;'>All Posts</h3>
    ";
    $i = 2;

    $andBoardID = (empty($DefBoardID)) ? '' : "AND a.`BoardID`=?";

    $sql = 'SELECT a.*, b.* FROM `' . $xoopsDB->prefix('tad_discuss') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_discuss_board') . '` AS b ON a.`BoardID` = b.`BoardID` WHERE a.`ReDiscussID` = 0 AND b.`BoardEnable` = ? ' . $andBoardID . ' ORDER BY a.`LastTime` DESC LIMIT 0,10';
    $params = (empty($DefBoardID)) ? ['1'] : ['1', $DefBoardID];
    $types = (empty($DefBoardID)) ? 's' : 'si';

    $result = Utility::query($sql, $types, $params) or Utility::web_error($sql, __FILE__, __LINE__);

    $i = 1;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $files = '';
        $TadUpFiles->set_col('DiscussID', $DiscussID);
        $allfiles = $TadUpFiles->get_file();
        foreach ($allfiles as $ff) {
            $files .= ('img' === $ff['kind']) ? "<a href='{$ff['path']}' class='fancybox_Discuss thumb' rel='group' target='_top'><img src='{$ff['tb_path']}' alt='{$ff['description']}'></a>" : "<a href='{$ff['path']}'><img src='images/file.png'></a>";
        }
        //以uid取得使用者名稱
        $publisher = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($publisher)) {
            $publisher = \XoopsUser::getUnameFromId($uid, 0);
        }

        $MainDiscussTitle = $DiscussTitle;

        $bgcss = ($i % 2) ? 'color:#000000;background-color:#FAFBFC' : 'color:#000000;background-color:#EDF3F7';
        $FBG_color = ($i % 2) ? '#0080C0' : '#C00080';

        $post_date = mb_substr(date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($DiscussDate))), 0, 16);
        //$post_date=substr($date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($DiscussDate))),0,16);

        $show_tool = $gpermHandler->checkRight('forum_post', $BoardID, $groups, $module_id);
        $tool = '';
        if ($show_tool and $_SESSION['tad_discuss_adm']) {
            $tool = "<img src='" . XOOPS_URL . "/modules/tad_discuss/images/del2.gif' width=12 height=12 align=bottom hspace=2 onClick=\"delete_tad_discuss_func($DiscussID)\">";
        }
        $re_button = Tools::isPublic($onlyTo, $uid, $DefBoardID) ? "<button type='button' style='font-size: 80%;border:1px solid gray;float:right;' onClick=\"window.open('" . XOOPS_URL . "/modules/tad_discuss/post.php?DiscussID={$DiscussID}&ReDiscussID={$DiscussID}&BoardID={$BoardID}','discussCboxForm')\">" . _MD_TADDISCUS_DISCUSSRE . '</button>' : '';

        $MainDiscussTitle = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $MainDiscussTitle);
        $MainDiscussTitle = str_replace('.gif]', ".gif' alt='emoji' class='emoji'>", $MainDiscussTitle);

        $MainDiscussContent = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussContent);
        $MainDiscussContent = str_replace('.gif]', ".gif' alt='emoji' class='emoji'>", $MainDiscussContent);
        $MainDiscussID = $DiscussID;

        if ($onlyTo) {
            $titleColor = 'red';
            $contentColor = 'red';
        } else {
            $titleColor = 'darkblue';
            $contentColor = 'black';
        }

        $isPublic = Tools::isPublic($onlyTo, $uid, $DefBoardID);
        $onlyToName = Tools::getOnlyToName($onlyTo);

        $MainDiscussTitle = $isPublic ? $MainDiscussTitle : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);
        $MainDiscussContent = $isPublic ? $MainDiscussContent : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);
        $files = Tools::isPublic($onlyTo, $uid, $DefBoardID) ? $files : '';

        //die("{$DiscussTitle}<br>{$DiscussContent}");
        $showTitle = ($DiscussTitle == $DiscussContent) ? '' : "
          {$re_button}
          <a href='discuss.php?DiscussID={$DiscussID}' style='text-decoration:none;color:{$titleColor};border-bottom:1px dotted gray;' target='_top'>{$MainDiscussTitle}</a>
        ";
        $re_button2 = ($DiscussTitle == $DiscussContent) ? $re_button : '';

        $mainDiscuss = "
        <div class='txt_msg' style='word-wrap:break-word;word-break:break-all;-moz-binding: url(wordwrap.xml#wordwrap);overflow: hidden;line-height:150%;padding:8px 1px;'>
          {$re_button2}
          <span class='f' style='background-color:{$FBG_color}'> 1F </span>
          <div class='cbox_publisher'>{$publisher}</div>: {$showTitle}
          <div class='cbox_date'>
            {$post_date}{$tool}
          </div>
          <div class='MainDiscussContent' style='line-height:150%;color:$contentColor'>
            {$MainDiscussContent}
            {$files}
          </div>
        </div>
        <div style='clear:both;'></div>
        ";

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss') . '` WHERE `ReDiscussID`=? ORDER BY `ReDiscussID`, `DiscussDate`';
        $result2 = Utility::query($sql, 'i', [$DiscussID]) or Utility::web_error($sql, __FILE__, __LINE__);

        $re = '';
        $f = 2;
        while (false !== ($all = $xoopsDB->fetchArray($result2))) {
            //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $files = '';
            $TadUpFiles->set_col('DiscussID', $DiscussID);
            $allfiles = $TadUpFiles->get_file();
            foreach ($allfiles as $ff) {
                $files .= ('img' === $ff['kind']) ? "<a href='{$ff['path']}' class='fancybox_Discuss thumb' rel='DiscussID_{$DiscussID}' target='_parent'><img src='{$ff['tb_path']}' alt='{$ff['tb_path']}'></a>" : "<a href='{$ff['path']}'><img src='images/file.png' alt='pic'></a>";
            }

            //以uid取得使用者名稱
            $publisher = \XoopsUser::getUnameFromId($uid, 1);
            if (empty($publisher)) {
                $publisher = \XoopsUser::getUnameFromId($uid, 0);
            }

            $post_date = mb_substr(date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($DiscussDate))), 0, 16);

            $tool = '';
            if ($show_tool and $_SESSION['tad_discuss_adm']) {
                $tool = "<img src='" . XOOPS_URL . "/modules/tad_discuss/images/del2.gif' width=12 height=12 align=bottom hspace=2 onClick=\"delete_tad_discuss_func($DiscussID)\">";
            }

            $DiscussContent = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussContent);
            $DiscussContent = str_replace('.gif]', ".gif' alt='emoji' class='emoji'>", $DiscussContent);

            if ($onlyTo) {
                $ContentColor = 'red';
            } else {
                $ContentColor = $font_color;
            }

            $onlyToName = Tools::getOnlyToName($onlyTo);
            $DiscussContent = Tools::isPublic($onlyTo, $uid, $DefBoardID) ? $DiscussContent : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);
            $files = Tools::isPublic($onlyTo, $uid, $DefBoardID) ? $files : '';

            $re .= "
            $re_button
            <span class='f' style='background-color:{$FBG_color}'> {$f}F </span>
            <div class='cbox_publisher'>{$publisher}</div>:
            <div class='cbox_date'>
              <span style='border-bottom:1px dotted #FF0080;color:gray;'>{$post_date}{$tool}</span>
            </div>
            <div class='triangle-border top' style='line-height:150%;'>
              <div class='txt_msg' style='word-wrap:break-word;word-break:break-all;-moz-binding: url(wordwrap.xml#wordwrap);overflow: hidden;line-height:150%;padding:8px 1px;color:{$ContentColor}'>
                {$DiscussContent}
              </div>
            {$files}
            </div>
            <div style='clear:both;'></div>
            ";
            $f++;
        }

        $data .= "
        <div style='width:100%;font-size: 75%;line-height:150%;{$bgcss}'>
          {$mainDiscuss}
          {$re}
        </div>";
        $i++;
    }

    return $data;
}
