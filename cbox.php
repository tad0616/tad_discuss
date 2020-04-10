<?php
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

$TadUpFiles = new TadUpFiles('tad_discuss');

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$BoardID = system_CleanVars($_REQUEST, 'BoardID', 0, 'int');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');
$files_sn = system_CleanVars($_REQUEST, 'files_sn', '', 'int');

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
  <link rel='stylesheet' type='text/css' media='screen' href='" . XOOPS_URL . "/modules/tad_discuss/cbox.css'>
</head>
<body bgcolor='#FFFFFF' style='scrollbar-face-color:#EDF3F7;scrollbar-shadow-color:#EDF3F7;scrollbar-highlight-color:#EDF3F7;scrollbar-3dlight-color:#FFFFFF;scrollbar-darkshadow-color:#FFFFFF;scrollbar-track-color:#FFFFFF;scrollbar-arrow-color:#232323;scrollbar-base-color:#FFFFFF;'>
  {$main}
</body>
</html>";

/*-----------function區--------------*/

//列出所有tad_discuss資料
function list_tad_discuss_cbox($DefBoardID = '')
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $TadUpFiles, $isAdmin;

    //$cbox_show_num=empty($_SESSION['cbox_show_num'])?20:$_SESSION['cbox_show_num'];
    $limit = 20;

    //取得本模組編號
    $module_id = $xoopsModule->mid();

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $now_uid = $xoopsUser->uid();
        $groups = $xoopsUser->getGroups();
    } else {
        $now_uid = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
    }
    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('forum_read', $DefBoardID, $groups, $module_id)) {
        header('location:index.php');
    }

    $jquery = Utility::get_jquery();

    $andBoardID = (empty($DefBoardID)) ? '' : "and a.BoardID='$DefBoardID'";
    $andLimit = ($limit > 0) ? "limit 0,$limit" : '';

    $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_discuss') . ' as a left join ' . $xoopsDB->prefix('tad_discuss_board') . " as b on a.BoardID = b.BoardID where a.ReDiscussID='0' and b.BoardEnable='1' $andBoardID  order by a.LastTime desc limit 0,10";

    $cbox_root_msg_color = system_CleanVars($_REQUEST, 'cbox_root_msg_color', '#B4C58D', 'string');
    $bg_color = system_CleanVars($_REQUEST, 'bg_color', '#FFFFFF', 'string');
    $font_color = system_CleanVars($_REQUEST, 'font_color', '#000000', 'string');

    if ($isAdmin) {
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

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $i = 1;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //原cbox為 $sn,$publisher,$msg,$post_date,$ip,$only_root,$root_msg
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
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
        if ($show_tool and $isAdmin) {
            $tool = "<img src='" . XOOPS_URL . "/modules/tad_discuss/images/del2.gif' width=12 height=12 align=bottom hspace=2 onClick=\"delete_tad_discuss_func($DiscussID)\">";
        }
        $re_button = isPublic($onlyTo, $uid, $DefBoardID) ? "<button type='button' style='font-size: 80%;border:1px solid gray;float:right;' onClick=\"window.open('" . XOOPS_URL . "/modules/tad_discuss/post.php?DiscussID={$DiscussID}&ReDiscussID={$DiscussID}&BoardID={$BoardID}','discussCboxForm')\">" . _MD_TADDISCUS_DISCUSSRE . '</button>' : '';

        $MainDiscussTitle = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $MainDiscussTitle);
        $MainDiscussTitle = str_replace('.gif]', ".gif' hspace=2 align='absmiddle'>", $MainDiscussTitle);

        $MainDiscussContent = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussContent);
        $MainDiscussContent = str_replace('.gif]', ".gif' hspace=2 align='absmiddle'>", $MainDiscussContent);
        $MainDiscussID = $DiscussID;

        if ($onlyTo) {
            $titleColor = 'red';
            $contentColor = 'red';
        } else {
            $titleColor = 'darkblue';
            $contentColor = 'black';
        }

        $isPublic = isPublic($onlyTo, $uid, $DefBoardID);
        $onlyToName = getOnlyToName($onlyTo);

        $MainDiscussTitle = $isPublic ? $MainDiscussTitle : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);
        $MainDiscussContent = $isPublic ? $MainDiscussContent : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);
        $files = isPublic($onlyTo, $uid, $DefBoardID) ? $files : '';

        $dot = $isPublic ? 'greenpoint' : 'lock';
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

        $sql = 'select * from ' . $xoopsDB->prefix('tad_discuss') . " where ReDiscussID='$DiscussID' order by ReDiscussID , DiscussDate";
        $result2 = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
            if ($show_tool and $isAdmin) {
                $tool = "<img src='" . XOOPS_URL . "/modules/tad_discuss/images/del2.gif' width=12 height=12 align=bottom hspace=2 onClick=\"delete_tad_discuss_func($DiscussID)\">";
            }

            $DiscussContent = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussContent);
            $DiscussContent = str_replace('.gif]', ".gif' hspace=2 align='absmiddle'>", $DiscussContent);

            if ($onlyTo) {
                $ContentColor = 'red';
            } else {
                $ContentColor = $font_color;
            }

            $onlyToName = getOnlyToName($onlyTo);
            $DiscussContent = isPublic($onlyTo, $uid, $DefBoardID) ? $DiscussContent : sprintf(_MD_TADDISCUS_ONLYTO, $onlyToName);
            $files = isPublic($onlyTo, $uid, $DefBoardID) ? $files : '';

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
