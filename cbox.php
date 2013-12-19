<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
include_once XOOPS_ROOT_PATH."/modules/tadtools/TadUpFiles.php" ;
$TadUpFiles=new TadUpFiles("tad_discuss");

$op=isset($_REQUEST['op'])?$_REQUEST['op']:"";
switch($op){
  //下載檔案
  case "tufdl":
  $files_sn=isset($_GET['files_sn'])?intval($_GET['files_sn']):"";
  $TadUpFiles->add_file_counter($files_sn);
  exit;
  break;
}
/*-----------function區--------------*/

//列出所有tad_discuss資料
function list_tad_discuss_cbox($DefBoardID=""){
  global $xoopsDB,$xoopsModule,$xoopsModuleConfig,$xoopsUser,$TadUpFiles;

  //$cbox_show_num=empty($_SESSION['cbox_show_num'])?20:$_SESSION['cbox_show_num'];
  $limit=20;


  //取得本模組編號
  $module_id = $xoopsModule->getVar('mid');

  //取得目前使用者的群組編號
  if($xoopsUser) {
    $now_uid=$xoopsUser->getVar('uid');
    $groups=$xoopsUser->getGroups();
  }else{
    $now_uid=0;
    $groups = XOOPS_GROUP_ANONYMOUS;
  }
  $gperm_handler =& xoops_gethandler('groupperm');
  if(!$gperm_handler->checkRight('forum_read',$DefBoardID,$groups,$module_id)){
    header('location:index.php');
  }

  $jquery=get_jquery();

  $andBoardID=(empty($DefBoardID))?"":"and a.BoardID='$DefBoardID'";
  $andLimit=($limit > 0)?"limit 0,$limit":"";

  $sql = "select a.*,b.* from ".$xoopsDB->prefix("tad_discuss")." as a left join ".$xoopsDB->prefix("tad_discuss_board")." as b on a.BoardID = b.BoardID where a.ReDiscussID='0' and b.BoardEnable='1' $andBoardID  order by a.LastTime desc limit 0,10";


  //判斷是否對該模組有管理權限
  if ($xoopsUser) {
    $isAdmin=$xoopsUser->isAdmin($module_id);
  }else{
    $isAdmin=false;

  }

  $cbox_root_msg_color=empty($_GET['border_color'])?"#B4C58D":$_GET['border_color'];
  $bg_color=empty($_GET['bg_color'])?"#FFFFFF":$_GET['bg_color'];
  $font_color=empty($_GET['font_color'])?"#000000":$_GET['font_color'];

  if($isAdmin){
    $del_js="
    function delete_tad_discuss_func(DiscussID){
      var sure = window.confirm('"._BP_DEL_CHK."');
      if (!sure)  return;
      location.href=\"{$_SERVER['PHP_SELF']}?BoardID={$DefBoardID}&op=delete_tad_discuss&DiscussID=\" + DiscussID;
    }";
  }else{
    $del_js="";
  }


  $data="
  <style>
  .triangle-border {
      position:relative;
      padding:15px;
      margin:1em 0 3em;
      border:5px solid $cbox_root_msg_color;
      color:#333;
      background:$bg_color;
      /* css3 */
      -webkit-border-radius:10px;
      -moz-border-radius:10px;
      border-radius:10px;
  }

  .triangle-border:before {
      content:'';
      position:absolute;
      bottom:-20px; /* value = - border-top-width - border-bottom-width */
      left:40px; /* controls horizontal position */
      border-width:20px 20px 0;
      border-style:solid;
      border-color:$cbox_root_msg_color transparent;
      /* reduce the damage in FF3.0 */
      display:block;
      width:0;
  }

  /* creates the smaller  triangle */
  .triangle-border:after {
      content:'';
      position:absolute;
      bottom:-13px; /* value = - border-top-width - border-bottom-width */
      left:47px; /* value = (:before left) + (:before border-left) - (:after border-left) */
      border-width:13px 13px 0;
      border-style:solid;
      border-color:$bg_color transparent;
      /* reduce the damage in FF3.0 */
      display:block;
      width:0;
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
  ";
  $i=2;


  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $main_data="";

  $i=1;
  while($all=$xoopsDB->fetchArray($result)){
    //原cbox為 $sn,$publisher,$msg,$post_date,$ip,$only_root,$root_msg
    //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $files="";
    $TadUpFiles->set_col("DiscussID" , $DiscussID );
    $allfiles=$TadUpFiles->get_file();
    foreach($allfiles as $ff){
      $files.=($ff['kind']=="img")?"<a href='{$ff['path']}' class='fancybox_Discuss thumb' rel='DiscussID_{$DiscussID}' target='_top'><img src='{$ff['tb_path']}' alt='{$ff['description']}'></a>":"<a href='{$ff['path']}'><img src='images/file.png'></a>";
    }
    //以uid取得使用者名稱
    $publisher=XoopsUser::getUnameFromId($uid,1);
    if(empty($publisher))$publisher=XoopsUser::getUnameFromId($uid,0);
    $MainDiscussTitle=$DiscussTitle;

    $bgcss=($i%2)?"color:#000000;background-color:#FAFBFC":"color:#000000;background-color:#EDF3F7";

    $post_date=substr(date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($DiscussDate))),0,16);
    //$post_date=substr($date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($DiscussDate))),0,16);

    $show_tool=$gperm_handler->checkRight('forum_post',$BoardID,$groups,$module_id);
    $tool="";
    if($show_tool and $isAdmin){
      $tool="<img src='".XOOPS_URL."/modules/tad_discuss/images/del2.gif' width=12 height=12 align=bottom hspace=2 onClick=\"delete_tad_discuss_func($DiscussID)\">";
    }
    $re_button=isPublic($onlyTo,$uid)?"<button type='button' style='font-size:11px;border:1px solid gray;float:right;' onClick=\"window.open('".XOOPS_URL."/modules/tad_discuss/post.php?DiscussID={$DiscussID}&ReDiscussID={$DiscussID}&BoardID={$BoardID}','discussCboxForm')\">"._MD_TADDISCUS_DISCUSSRE."</button>":"";



    $MainDiscussContent=str_replace("[s","<img src='".XOOPS_URL."/modules/tad_discuss/images/smiles/s",$DiscussContent);
    $MainDiscussContent=str_replace(".gif]",".gif' hspace=2 align='absmiddle'>",$MainDiscussContent);
    $MainDiscussID=$DiscussID;

    if($onlyTo){
      $titleColor="red";
    }else{
      $titleColor="darkblue";
    }

    $isPublic=isPublic($onlyTo,$uid);
    $onlyToName=getOnlyToName($onlyTo);
    $MainDiscussTitle=$isPublic?$MainDiscussTitle:sprintf(_MD_TADDISCUS_ONLYTO,$onlyToName);
    $MainDiscussContent=$isPublic?$MainDiscussContent:sprintf(_MD_TADDISCUS_ONLYTO,$onlyToName);
    $files=isPublic($onlyTo,$uid)?$files:"";

    $MainDiscussContent=strip_word_html($MainDiscussContent);

    $dot=$isPublic?"greenpoint":"lock";
    $mainDiscuss="
    <div style='padding:8px 1px;'>
      $re_button
      <img src='images/$dot.gif'>
      <a href='discuss.php?DiscussID={$DiscussID}' style='text-decoration:none;color:{$titleColor};border-bottom:1px dotted gray;' target='_top'>{$MainDiscussTitle}</a>
    </div>

    <div class='txt_msg' style='word-wrap:break-word;word-break:break-all;-moz-binding: url(wordwrap.xml#wordwrap);overflow: hidden;line-height:150%;padding:8px 1px;'>
      <div class='cbox_publisher'>{$publisher}</div>: {$MainDiscussContent}
    </div>
    {$files}
    <div class='cbox_date'><span style='display:block-inline;background-color:#0080C0;color:white;border-radius: 3px;padding:1px 3px;margin-right:4px;'> 1F </span> <span style='border-bottom:1px dotted #FF0080'>{$post_date}{$tool}</span></div>";



    $sql = "select * from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID' order by ReDiscussID , DiscussDate";
    $result2 = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $re="";
    $f=2;
    while($all=$xoopsDB->fetchArray($result2)){
      //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
      foreach($all as $k=>$v){
        $$k=$v;
      }

      $files="";
      $TadUpFiles->set_col("DiscussID" , $DiscussID );
      $allfiles=$TadUpFiles->get_file();
      foreach($allfiles as $ff){
        $files.=($ff['kind']=="img")?"<a href='{$ff['path']}' class='fancybox_Discuss' rel='DiscussID_{$DiscussID}' target='_parent'><img src='{$ff['tb_path']}'></a>":"<a href='{$ff['path']}'><img src='images/file.png'></a>";
      }

      //以uid取得使用者名稱
      $publisher=XoopsUser::getUnameFromId($uid,1);
      if(empty($publisher))$publisher=XoopsUser::getUnameFromId($uid,0);

      $post_date=substr(date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($DiscussDate))),0,16);

      $tool="";
      if($show_tool and $isAdmin){
        $tool="<img src='".XOOPS_URL."/modules/tad_discuss/images/del2.gif' width=12 height=12 align=bottom hspace=2 onClick=\"delete_tad_discuss_func($DiscussID)\">";
      }

      $DiscussContent=str_replace("[s","<img src='".XOOPS_URL."/modules/tad_discuss/images/smiles/s",$DiscussContent);
      $DiscussContent=str_replace(".gif]",".gif' hspace=2 align='absmiddle'>",$DiscussContent);

      $DiscussContent=strip_word_html($DiscussContent);

      if($onlyTo){
        $ContentColor="red";
      }else{
        $ContentColor=$font_color;
      }

      $onlyToName=getOnlyToName($onlyTo);
      $DiscussContent=isPublic($onlyTo,$uid)?$DiscussContent:sprintf(_MD_TADDISCUS_ONLYTO,$onlyToName);
      $files=isPublic($onlyTo,$uid)?$files:"";

      $re.="
      <div class='triangle-border' style='line-height:150%;'>
        <div class='txt_msg' style='word-wrap:break-word;word-break:break-all;-moz-binding: url(wordwrap.xml#wordwrap);overflow: hidden;line-height:150%;padding:8px 1px;color:{$ContentColor}'>
          {$DiscussContent}
          $re_button
        </div>
      </div>
      {$files}
      <div class='cbox_date'>
        <span style='display:block-inline;background-color:#0080C0;color:white;border-radius: 3px;padding:1px 3px;margin-right:4px;'> {$f}F </span>
        <div class='cbox_publisher'>{$publisher}</div>
        <span style='border-bottom:1px dotted #FF0080'>{$post_date}{$tool}</span>
      </div>
      <div style='clear:both;'></div>
      ";
      $f++;
    }

    $data.="
    <div style='width:100%;font-size:12px;line-height:150%;{$bgcss}'>
      {$mainDiscuss}
      {$re}
    </div>";
    $i++;
  }



  return $data;
}

function strip_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br><img><div><p><iframe><ul><ol><li><a>')
{
  mb_regex_encoding('UTF-8');
  //replace MS special characters first
  $search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u');
  $replace = array('\'', '\'', '"', '"', '-');
  $text = preg_replace($search, $replace, $text);
  //make sure _all_ html entities are converted to the plain ascii equivalents - it appears
  //in some MS headers, some html entities are encoded and some aren't
  $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
  //try to strip out any C style comments first, since these, embedded in html comments, seem to
  //prevent strip_tags from removing html comments (MS Word introduced combination)
  if(mb_stripos($text, '/*') !== FALSE){
      $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm');
  }
  //introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be
  //'<1' becomes '< 1'(note: somewhat application specific)
  $text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text);
  $text = strip_tags($text, $allowed_tags);
  //eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one
  $text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text);
  //strip out inline css and simplify style tags
  $search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu');
  $replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>');
  $text = preg_replace($search, $replace, $text);
  //on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears
  //that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains
  //some MS Style Definitions - this last bit gets rid of any leftover comments */
  $num_matches = preg_match_all("/\<!--/u", $text, $matches);
  if($num_matches){
        $text = preg_replace('/\<!--(.)*--\>/isu', '', $text);
  }
  return $text;
}

/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$BoardID=(empty($_REQUEST['BoardID']))?"":intval($_REQUEST['BoardID']);
$DiscussID=(empty($_REQUEST['DiscussID']))?"":intval($_REQUEST['DiscussID']);

switch($op){
  //刪除資料
  case "delete_tad_discuss";
  delete_tad_discuss($DiscussID);
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  default:
  $main=list_tad_discuss_cbox($BoardID);
  break;
}

/*-----------秀出結果區--------------*/
$jquery=get_jquery();
echo "
<html>
  <head>
  <meta http-equiv='content-type' content='text/html; charset="._CHARSET."'>
  $jquery

  <script type='text/javascript' src='".XOOPS_URL."/modules/tadtools/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js'></script>
  <script type='text/javascript' language='javascript' src='".XOOPS_URL."/modules/tadtools/fancyBox/source/jquery.fancybox.js?v=2.1.4'></script>
  <link rel='stylesheet' href='".XOOPS_URL."/modules/tadtools/fancyBox/source/jquery.fancybox.css?v=2.1.4' type='text/css' media='screen' />
  <link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/tadtools/fancyBox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5' />
  <script type='text/javascript' src='".XOOPS_URL."/modules/tadtools/fancyBox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5'></script>
  <link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/tadtools/fancyBox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7' />
  <script type='text/javascript' src='".XOOPS_URL."/modules/tadtools/fancyBox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7'></script>
  <script type='text/javascript' src='".XOOPS_URL."/modules/tadtools/fancyBox/source/helpers/jquery.fancybox-media.js?v=1.0.5'></script>
    <script type='text/javascript'>
    $(document).ready(function() {
      $('.fancybox_Discuss').fancybox({
        openEffect  : 'none',
        closeEffect : 'none',
        autoPlay  : true
      });

    });
  </script>
  <link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/tad_discuss/cbox.css' />
</head>
<body bgcolor='#FFFFFF' style='scrollbar-face-color:#EDF3F7;scrollbar-shadow-color:#EDF3F7;scrollbar-highlight-color:#EDF3F7;scrollbar-3dlight-color:#FFFFFF;scrollbar-darkshadow-color:#FFFFFF;scrollbar-track-color:#FFFFFF;scrollbar-arrow-color:#232323;scrollbar-base-color:#FFFFFF;'>
  {$main}
</body>
</html>";
?>
