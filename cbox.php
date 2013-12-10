<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
/*-----------function區--------------*/

//列出所有tad_cbox資料
function list_tad_discuss_cbox($DefBoardID=""){
  global $xoopsDB,$xoopsModule,$xoopsModuleConfig,$xoopsUser;

  //$cbox_show_num=empty($_SESSION['cbox_show_num'])?20:$_SESSION['cbox_show_num'];
  $limit=20;


  //取得本模組編號
  $module_id = $xoopsModule->getVar('mid');

  //取得目前使用者的群組編號
  if($xoopsUser) {
    $uid=$xoopsUser->getVar('uid');
    $groups=$xoopsUser->getGroups();
  }else{
    $uid=0;
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

  $cbox_root_msg_color=(empty($_SESSION['cbox_root_msg_color']))?"#E5ECC7":$_SESSION['cbox_root_msg_color'];


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
    border:5px solid $cbox_root_msg_color;
  }

  .triangle-border:before {
    border-color:$cbox_root_msg_color transparent;
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

    //以uid取得使用者名稱
    $publisher=XoopsUser::getUnameFromId($uid,1);
    if(empty($publisher))$publisher=XoopsUser::getUnameFromId($uid,0);
    $MainDiscussTitle=$DiscussTitle;

    $bgcss=($i%2)?"color:#000000;background-color:#FFFFFF":"color:#000000;background-color:#EDF3F7";

    $post_date=substr(date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($DiscussDate))),0,16);
    //$post_date=substr($date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($DiscussDate))),0,16);

    /*
    if($only_root=='1' and !$isAdmin){
      $msg="<font class='lock_msg'>"._MA_TADCBOX_LOCK_MSG."</font>";
    }
    */

    $tool=($isAdmin)?"<img src='".XOOPS_URL."/modules/tad_discuss/images/re.gif' width=16 height=12 align=bottom hspace=2 onClick=\"window.open('".XOOPS_URL."/modules/tad_discuss/post.php?DiscussID={$DiscussID}&ReDiscussID={$DiscussID}&BoardID={$BoardID}','discussCboxForm')\"><img src='".XOOPS_URL."/modules/tad_discuss/images/del2.gif' width=12 height=12 align=bottom hspace=2 onClick=\"delete_tad_discuss_func($DiscussID)\">":"";

    $MainDiscussContent=str_replace("[s","<img src='".XOOPS_URL."/modules/tad_discuss/images/smiles/s",$DiscussContent);
    $MainDiscussContent=str_replace(".gif]",".gif' hspace=2 align='absmiddle'>",$MainDiscussContent);
    $MainDiscussID=$DiscussID;

    $sql = "select * from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID' order by ReDiscussID , DiscussDate";
    $result2 = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $re="";
    $f=2;
    while($all=$xoopsDB->fetchArray($result2)){
      //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
      foreach($all as $k=>$v){
        $$k=$v;
      }

      //以uid取得使用者名稱
      $publisher2=XoopsUser::getUnameFromId($uid,1);
      if(empty($publisher2))$publisher2=XoopsUser::getUnameFromId($uid,0);

      $post_date2=substr(date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($DiscussDate))),0,16);
      $tool2=($isAdmin)?"<img src='".XOOPS_URL."/modules/tad_discuss/images/re.gif' width=16 height=12 align=bottom hspace=2 onClick=\"window.open('".XOOPS_URL."/modules/tad_discuss/post.php?DiscussID={$MainDiscussID}&ReDiscussID={$MainDiscussID}&BoardID={$BoardID}','discussCboxForm')\"><img src='".XOOPS_URL."/modules/tad_discuss/images/del2.gif' width=12 height=12 align=bottom hspace=2 onClick=\"delete_tad_discuss_func($DiscussID)\">":"";

      $DiscussContent=str_replace("[s","<img src='".XOOPS_URL."/modules/tad_discuss/images/smiles/s",$DiscussContent);
      $DiscussContent=str_replace(".gif]",".gif' hspace=2 align='absmiddle'>",$DiscussContent);

      $re.="
      <div class='txt_msg' style='word-wrap:break-word;word-break:break-all;-moz-binding: url(wordwrap.xml#wordwrap);overflow: hidden;line-height:150%;padding:8px 1px;'>
        <div class='cbox_publisher'>{$publisher2}</div>: {$DiscussContent}
      </div>
      <div class='cbox_date'><span style='display:block-inline;background-color:#0080C0;color:white;border-radius: 3px;padding:1px 3px;margin-right:4px;'> {$f}F </span> <span style='border-bottom:1px dotted #FF0080'>{$post_date2}{$tool2}</span></div>
      ";
      $f++;
    }

    $data.="
    <div style='width:100%;font-size:12px;line-height:150%;{$bgcss}'>
      <div style='padding:8px 1px;'>
        <img src='images/greenpoint.gif'>
        <a href='discuss.php?DiscussID={$DiscussID}' style='text-decoration:none;color:darkblue;border-bottom:1px dotted gray;' target='_top'>{$MainDiscussTitle}</a>
      </div>

      <div class='txt_msg' style='word-wrap:break-word;word-break:break-all;-moz-binding: url(wordwrap.xml#wordwrap);overflow: hidden;line-height:150%;padding:8px 1px;'>
        <div class='cbox_publisher'>{$publisher}</div>: {$MainDiscussContent}
      </div>
      <div class='cbox_date'><span style='display:block-inline;background-color:#0080C0;color:white;border-radius: 3px;padding:1px 3px;margin-right:4px;'> 1F </span> <span style='border-bottom:1px dotted #FF0080'>{$post_date}{$tool}</span></div>
      {$re}
    </div>";
    $i++;
  }



  return $data;
}

//跳過HTML的換行
function breakLongWords($str, $maxLength, $char){
    $wordEndChars = array(" ", "\n", "\r", "\f", "\v", "\0");
    $count = 0;
    $newStr = "";
    $openTag = false;
    for($i=0; $i<strlen($str); $i++){
        $newStr .= $str{$i};

        if($str{$i} == "<"){
          $openTag = true;
          continue;
        }

        if(($openTag) && ($str{$i} == ">")){
          $openTag = false;
          continue;
        }

        if(!$openTag){
          if(!in_array($str{$i}, $wordEndChars)){//If not word ending char
            $count++;
            if($count==$maxLength){//if current word max length is reached
              $ch=substr($newStr,$count-1,1);
              if(ord($ch)>127){
                $count++;
                continue;
              }

              $newStr .= $char;//insert word break char
              $count = 0;
            }
          }else{//Else char is word ending, reset word char count
            $count = 0;
          }
        }

    }//End for
    return $newStr;
}

// 將字串中的網址加入超連結
function parseURL($strURL = null){
  $regex = "{ ((https?|telnet|gopher|file|wais|ftp):[\\w/\\#~:.?+=&%@!\\-]+?)(?=[.:?\\-]*(?:[^\\w/\\#~:.?+=&%@!\\-]|$)) }x";

  return preg_replace($regex,"<a href=\"$1\" target=\"_blank\">$1</a>",$strURL);
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

echo "
<html>
  <head>
  <meta http-equiv='content-type' content='text/html; charset="._CHARSET."'>
  <link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/tad_discuss/cbox.css' />
</head>
<body bgcolor='#FFFFFF' style='scrollbar-face-color:#EDF3F7;scrollbar-shadow-color:#EDF3F7;scrollbar-highlight-color:#EDF3F7;scrollbar-3dlight-color:#FFFFFF;scrollbar-darkshadow-color:#FFFFFF;scrollbar-track-color:#FFFFFF;scrollbar-arrow-color:#232323;scrollbar-base-color:#FFFFFF;'>
  {$main}
</body>
</html>";
?>
