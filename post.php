<?php

/*-----------引入檔案區--------------*/
include_once "header.php";
include_once "up_file.php";
/*-----------function區--------------*/

if($_GET['mode']=="mkpic"){
  if($xoopsModuleConfig['security_images']=='1'){
    $num1=rand(0,9);
    $num2=rand(0,9);
    $num3=rand(0,9);
    $num=$num1.$num2.$num3;
    $_SESSION['security_code']=$num;
    mkpic($num);
    exit;
  }
}


//tad_discuss編輯表單
function tad_discuss_form($BoardID="",$DiscussID="",$ReDiscussID=""){
  global $xoopsDB,$xoopsUser,$xoopsModuleConfig,$xoopsModule;

  if(empty($xoopsUser)){
    $main="<div class='need_login'>"._MD_TADDISCUS_NEEDLOGIN."</div>";
    return $main;
    exit;
  }

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
  if(!$gperm_handler->checkRight('forum_post',$BoardID,$groups,$module_id)){
    $main="<div class='need_login'>"._MD_TADDISCUS_NEEDLOGIN."</div>";
    return $main;
    exit;
  }

  /*
  //秀出管理員回覆訊息，當開啟辨識身份時，要抓取使用者名稱
  if($xoopsModuleConfig['auto_id']=='1' and !empty($xoopsUser) ){
    $loginname=$xoopsUser->getVar('loginname');
    $name=$xoopsUser->getVar('name');
    if(!empty($name)){
      $publisher=$name;
    }elseif(!empty($loginname)){
      $publisher=$loginname;
    }else{
      $publisher=$xoopsUser->getVar('uname');
    }
    $publisher_txt=(!empty($sn))?"<div class='remsg'>".sprintf(_MD_TADDISCUS_RE_MSG,$sn)."</div>":"<div class='remsg'>".sprintf(_MD_TADDISCUS_ADD_MSG,$publisher)."</div>";
    $publisher_txt.="<input type='hidden' name='publisher' value='$publisher'>";
  }else{
    $publisher=(empty($_SESSION['publisher']))?_MD_TADDISCUS_DEFAULT_PUBLISHER:$_SESSION['publisher'];
    $publisher_txt=(!empty($sn))?"<div class='remsg'>".sprintf(_MD_TADDISCUS_RE_MSG,$sn)."</div>":"<input type='text' class='name' name='publisher' value='$publisher' style='width: 100%' onClick=\"if(this.value=='"._MD_TADDISCUS_DEFAULT_PUBLISHER."')this.value=''\">";
  }

  //檢查是否是屬於不需要認證的群組

  $no_chk=is_no_chk();
  if($xoopsModuleConfig['security_images']=='1' and !$no_chk){
    $security_images="<tr><td colspan=2 class='col'><img src='".XOOPS_URL."/modules/tad_discuss/post.php?mode=mkpic' align=absmiddle hspace=3>"._MD_TADDISCUS_INPUT_CODE."<input type='text' name='security_images' size=2></td></tr>";
  }else{
    $security_images="";
  }
  */

  $loginname=$xoopsUser->getVar('loginname');
  $name=$xoopsUser->getVar('name');
  if(!empty($name)){
    $publisher=$name;
  }elseif(!empty($loginname)){
    $publisher=$loginname;
  }else{
    $publisher=$xoopsUser->getVar('uname');
  }

  $publisher_txt=(!empty($ReDiscussID))?"<div class='remsg'>".sprintf(_MD_TADDISCUS_RE_MSG,$ReDiscussID)."</div>":"<div class='remsg'>".sprintf(_MD_TADDISCUS_ADD_MSG,$publisher)."</div>";

  $js=$smile_all="";
  $_SESSION['cbox_use_smile']= 1;

  if($_SESSION['cbox_use_smile']=='1'){
    //找出表情圖
    $dir = "images/smiles/";
    if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
          if(substr($file,0,1)=="." or substr($file,0,1)!="s")continue;
          $key=substr($file,1,-4);
          $smile_gif[$key]=$file;
        }
        closedir($dh);
      }
    }

    sort($smile_gif);
    $smile_li="";
    foreach($smile_gif as $file){
      $smile_li.="<li><img src='".XOOPS_URL."/modules/tad_discuss/{$dir}{$file}'  width='19' height='19' alt='{$file}' onClick='insertAtCursor(document.myForm.DiscussContent,\"[{$file}]\")' ></li>\n";
    }

    $js="
    $(document).ready(function() {
      var w=$('.carousel').width();
      if(w > 600) w=600;
      var smile_num=Math.floor((w-60)/19);
      $(\".jCarouselLite\").jCarouselLite({
        btnNext: \".next\",
        btnPrev: \".prev\",
        visible: smile_num,
        scroll: smile_num
      });
    });

    function insertAtCursor(myField, myValue) {
      //IE support
      if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
      } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
        + myValue
        + myField.value.substring(endPos, myField.value.length);
      } else {
        myField.value += myValue;
      }
    }";

    $smile_all="
    <tr><td colspan=2 id='smile'>
      <div class='carousel' style='height:24px;'>
        <a href='#' class='prev'>&nbsp</a>
        <div class='jCarouselLite' align=center><ul>$smile_li</ul></div>
        <a href='#' class='next'>&nbsp</a>
        <div class='clear'></div>
      </div>
    </td></tr>";
  }

  $jquery=get_jquery();

  $DiscussTitleForm=empty($ReDiscussID)?"
  <tr>
    <td colspan=2><input type='text' name='DiscussTitle' value='"._MD_TADDISCUS_INPUT_TITLE."' style='width:100%' onClick=\"if(this.value=='"._MD_TADDISCUS_INPUT_TITLE."')this.value=''\">
    </td>
  </tr>":"";

  $main="
  {$jquery}
  <script type='text/javascript' src='".XOOPS_URL."/modules/tad_discuss/class/jcarousellite_1.0.1.min.js'></script>

  <script type='text/javascript'>
  $js
  var minChr = 4;
  var nowChr = 0;
  function count(value){
     nowChr = value.length;
  }
  function check(){
      if(nowChr < minChr){
        alert('".sprintf(_MD_TADDISCUS_MSG_MIN,$xoopsModuleConfig['input_min'])."');
        return;
      }
      document.myForm.submit();
  }
  </script>
  <div class='cbox'>
  <form action='{$_SERVER['PHP_SELF']}' method='post' name='myForm' id='myForm' enctype='multipart/form-data' >
  <table class='cbox_tbl' style='width:98%'>
  <tr>
    <td class='col'>{$publisher_txt}
    <!--div style='font-size:10px'>\$BoardID=$BoardID,\$DiscussID=$DiscussID,\$ReDiscussID=$ReDiscussID</div-->
    </td>
    <td>
      <img src='images/reload.png' alt='reload' align='absmiddle' hspace=2 onclick=\"window.open('".XOOPS_URL."/modules/tad_discuss/cbox.php?BoardID={$BoardID}','discussCboxMain');window.open('".XOOPS_URL."/modules/tad_discuss/post.php?BoardID={$BoardID}','discussCboxForm');\">
      <font onclick=\"window.open('".XOOPS_URL."/modules/tad_discuss/cbox.php?BoardID={$BoardID}','discussCboxMain');window.open('".XOOPS_URL."/modules/tad_discuss/post.php?BoardID={$BoardID}','discussCboxForm');\" style='cursor:pointer;color:#3366CC'>"._MD_TADDISCUS_RELOAD."</font>
  </td>
  </tr>

  $DiscussTitleForm


  <tr>
    <td class='col' colspan=2>
      <textarea name='DiscussContent' id='DiscussContent' style='width:100%' onkeyUp='count(this.value)' onClick=\"if(this.value=='"._MD_TADDISCUS_MSG."')this.value=''\">"._MD_TADDISCUS_MSG."</textarea>
    </td>
  </tr>

  <tr>
    <td class='col' colspan=2 style='text-align:right;'>
      $security_images
      <input type='checkbox' name='only_root' value='1'>"._MD_TADDISCUS_ONLY_ROOT."
      <input type='hidden' name='BoardID' value='{$BoardID}'>
      <input type='hidden' name='DiscussID' value='{$DiscussID}'>
      <input type='hidden' name='ReDiscussID' value='{$ReDiscussID}'>
      <input type='hidden' name='op' value='insert_tad_discuss'>

      <input type='button' value='"._TAD_SAVE."' style='height:100%' onClick='check();'>
    </td>
  </tr>

  $smile_all

  </table>
  </form></div>";


  return $main;
}



function mkpic($num=0){
  header("Content-type: image/png");
  $im = @imagecreatetruecolor(28, 18);
  $text_color = imagecolorallocate($im, 255, 255, 255);
  imagestring($im, 2, 5, 2, $num, $text_color);
  imagepng($im);
  imagedestroy($im);
}

/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$BoardID=(empty($_REQUEST['BoardID']))?"":intval($_REQUEST['BoardID']);
$DiscussID=(empty($_REQUEST['DiscussID']))?"":intval($_REQUEST['DiscussID']);
$ReDiscussID=(empty($_REQUEST['ReDiscussID']))?"":intval($_REQUEST['ReDiscussID']);

switch($op){

  //新增資料
  case "insert_tad_discuss":
  insert_tad_discuss();
  header("location: {$_SERVER['PHP_SELF']}?op=reload&BoardID=$BoardID");
  break;

  default:
  $main=tad_discuss_form($BoardID,$DiscussID,$ReDiscussID);
  break;
}

/*-----------秀出結果區--------------*/
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html>
<head>
<meta http-equiv='content-type' content='text/html; charset="._CHARSET."'>
<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/tad_discuss/cbox.css' />";

if($op=="reload"){
  echo "<script type='text/javascript'>
  window.open('".XOOPS_URL."/modules/tad_discuss/cbox.php?BoardID={$BoardID}','discussCboxMain');
  window.open('".XOOPS_URL."/modules/tad_discuss/post.php?BoardID={$BoardID}','discussCboxForm');
  </script>";
}

if(!empty($_GET['msg'])){
  echo "<script type='text/javascript'>alert('{$_GET['msg']}')</script>";
}


echo "

</head>
<body bgcolor='#FFFFFF'>";
echo $main;
echo "</body>
</html>";

?>
