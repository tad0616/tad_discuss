<?php
//引入TadTools的函式庫
if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php";

/********************* 自訂函數 *********************/



//對話框格式
function talk_bubble($BoardID='',$DiscussID='',$DiscussContent='',$dir='left',$uid="",$DiscussDate='',$mode='',$Good=0,$Bad=0,$width=100){
  global $xoopsUser,$xoopsTpl,$xoopsModuleConfig;
  $member_handler = xoops_gethandler('member');
  $user = $member_handler->getUser($uid);
  if (is_object($user)) {
    $ts = MyTextSanitizer::getInstance();
    $uid_name=$ts->htmlSpecialChars($user->getVar('name'));
    if(empty($uid_name))$uid_name=$ts->htmlSpecialChars($user->getVar('uname'));
    $pic=$ts->htmlSpecialChars($user->getVar('user_avatar'));
  }

  $pic=(empty($pic) or $pic=='blank.gif')?"images/nobody.png":XOOPS_URL."/uploads/".$pic;

  $pic_js=$pic_css="";

  $now_uid=is_object($xoopsUser)?$xoopsUser->getVar('uid'):"0";

  if($now_uid==$uid){
    $pic_js="onClick=\"location.href='".XOOPS_URL."/edituser.php?op=avatarform'\"";
    $pic_css="cursor:pointer;";
  }


  $like=(!empty($DiscussID) and $_REQUEST['op']!='tad_discuss_form')?true:false;
  $fun=(isMine($uid,$BoardID) and !empty($BoardID) and !empty($DiscussID) and $_REQUEST['op']!='tad_discuss_form')?true:false;
  $files=show_files("DiscussID" , $DiscussID , true , '' , true , false);

  $DiscussDate=date('Y-m-d H:i:s',xoops_getUserTimestamp(strtotime($DiscussDate)));
  if($xoopsModuleConfig['display_mode']=="mobile"){
    $DiscussDate=substr($DiscussDate,0,16);
  }

  $all['width']=$width;
  $all['dir']=$dir;
  $all['pic']=$pic;
  $all['pic_css']=$pic_css;
  $all['pic_js']=$pic_js;
  $all['fun']=$fun;
  $all['like']=$like;
  $all['uid_name']=$uid_name;
  $all['DiscussDate']=$DiscussDate;
  $all['DiscussContent']=$DiscussContent;
  $all['DiscussID']=$DiscussID;
  $all['BoardID']=$BoardID;
  $all['Bad']=$Bad;
  $all['Good']=$Good;
  $all['files']=$files;
  if($mode=="return"){
    return $all;
  }else{
    $xoopsTpl->assign('discuss',$all);
  }
}



//列出所有tad_discuss資料
function list_tad_discuss($DefBoardID=null){
  global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsModuleConfig,$isAdmin,$xoopsTpl;

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


  $andBoardID=(empty($DefBoardID))?"":"and a.BoardID='$DefBoardID'";
  $andLimit=($limit > 0)?"limit 0,$limit":"";
  $sql = "select a.*,b.* from ".$xoopsDB->prefix("tad_discuss")." as a left join ".$xoopsDB->prefix("tad_discuss_board")." as b on a.BoardID = b.BoardID where a.ReDiscussID='0' and b.BoardEnable='1' $andBoardID  order by a.LastTime desc";

  //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql , $xoopsModuleConfig['show_discuss_amount'] , 10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $main_data="";
  $i=1;
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $renum=get_re_num($DiscussID);
    $renum=empty($renum)?"0":$renum;

    $uid_name=XoopsUser::getUnameFromId($uid,1);
    if(empty($uid_name))$uid_name=XoopsUser::getUnameFromId($uid,0);

    //最後回應者
    $sql2 = "select uid from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID' order by DiscussDate desc limit 0,1";
    $result2 = $xoopsDB->queryF($sql2) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    //if($isAdmin)die($sql2);
    list($last_uid)=$xoopsDB->fetchRow($result2);
    //if($isAdmin and $BoardID==19)die("<div>$sql2</div>\$last_uid={$last_uid}");
    if(empty($last_uid)){
      $last_uid_name=$uid_name;
    }else{
      $last_uid_name=XoopsUser::getUnameFromId($last_uid,1);
      if(empty($last_uid_name))$last_uid_name=XoopsUser::getUnameFromId($last_uid,0);
    }

    $LastTime=date('Y-m-d H:i:s',xoops_getUserTimestamp(strtotime($LastTime)));
    $LastTime=substr($LastTime,0,16);
    $DiscussDate=date('Y-m-d H:i:s',xoops_getUserTimestamp(strtotime($DiscussDate)));
    $DiscussDate=substr($DiscussDate,0,16);



    $main_data[$i]['LastTime']=$LastTime;
    $main_data[$i]['DiscussID']=$DiscussID;
    $main_data[$i]['BoardID']=$BoardID;
    $main_data[$i]['DiscussTitle']=$DiscussTitle;
    $main_data[$i]['uid_name']=$uid_name;
    $main_data[$i]['renum']=$renum;
    $main_data[$i]['DiscussDate']=$DiscussDate;
    $main_data[$i]['LastTime']=$LastTime;
    $main_data[$i]['last_uid_name']=$last_uid_name;
    $i++;

  }
  //die(var_dump($main_data));
  $xoopsTpl->assign('main_data',$main_data);
  $xoopsTpl->assign('DefBoardID',$DefBoardID);


  $post_tool=($xoopsUser and !empty($DefBoardID) )?"<a href='{$_SERVER['PHP_SELF']}?op=tad_discuss_form&BoardID=$BoardID' class='link_button_r'><img src='images/edit.png' align='absmiddle' hspace=4 alt='"._MD_TADDISCUS_ADD_DISCUSS."'>"._MD_TADDISCUS_ADD_DISCUSS."</a>":"";

  if(file_exists(XOOPS_ROOT_PATH."/modules/tadtools/FooTable.php")){
    include_once XOOPS_ROOT_PATH."/modules/tadtools/FooTable.php";

    $FooTable = new FooTable();
    $FooTableJS=$FooTable->render();
  }

  $ShowBoardTitle="";
  if(!empty($DefBoardID)){
    $ShowBoardTitle=get_board_title($DefBoardID);
  }


  $xoopsTpl->assign('FooTableJS',$FooTableJS);
  $xoopsTpl->assign('post_tool',$post_tool);
  $xoopsTpl->assign('bar',$bar);
  $xoopsTpl->assign('ShowBoardTitle',$ShowBoardTitle);
}

//討論區標題
function get_board_title($DefBoardID=''){
  if(empty($DefBoardID))return ;
  $Board=get_tad_discuss_board($DefBoardID);
  $pic=get_pic_file('BoardID' , $Board['BoardID'] , 1 , 'thumb');
  $pic=empty($pic)?XOOPS_URL."/modules/tad_discuss/images/board.png":$pic;
  $main="<div style='width:90px;height:60px;background: transparent url($pic) no-repeat center top;-moz-border-radius: 5px;-khtml-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;position:relative;float:left;margin:0px 10px 6px 0px;' alt='{$Board['BoardTitle']}' title='{$Board['BoardTitle']}'></div>{$Board['BoardTitle']}<div style='font-size:11px;color:gray;font-weight:normal;cursor:pointer;' onClick=\"location.href='discuss.php?BoardID={$DefBoardID}'\">{$Board['BoardDesc']}</div><div style='clear:both'></div>";
  return $main;
}

//以流水號取得某筆tad_discuss資料
function get_tad_discuss($DiscussID=""){
  global $xoopsDB;
  if(empty($DiscussID))return;
  $sql = "select * from ".$xoopsDB->prefix("tad_discuss")." where DiscussID='$DiscussID'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  return $data;
}

//以流水號取得某筆tad_discuss_board資料
function get_tad_discuss_board($BoardID=""){
  global $xoopsDB;
  if(empty($BoardID))return;
  $sql = "select * from `".$xoopsDB->prefix("tad_discuss_board")."` where `BoardID` = '{$BoardID}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  return $data;
}


//取得文章數量
function get_board_num($BoardID="",$onlyMainDiscuss=true){
  global $xoopsDB,$xoopsModule,$xoopsUser;
  if(empty($BoardID)) return 0;
  $andMainDiscuss=($onlyMainDiscuss)?"and ReDiscussID='0'":"";
  $sql = "select count(*) from ".$xoopsDB->prefix("tad_discuss")." where BoardID='$BoardID' {$andMainDiscuss}";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($counter)=$xoopsDB->fetchRow($result);
  return $counter;
}

//取得回覆數量
function get_re_num($DiscussID=""){
  global $xoopsDB,$xoopsModule,$xoopsUser;
  if(empty($DiscussID)) return 0;
  $sql = "select count(*) from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($counter)=$xoopsDB->fetchRow($result);
  return $counter;
}


//是否有管理權（或由自己發布的），判斷是否要秀出管理工具
function isMine($discuss_uid=null,$BoardID=null){
  global $xoopsUser,$isAdmin;
  if(empty($xoopsUser))return false;
  $board=get_tad_discuss_board($BoardID);
  $BoardManagerArr=explode(',',$board['BoardManager']);

  $uid=is_object($xoopsUser)?$xoopsUser->getVar('uid'):"0";
  if($isAdmin){
    return true;
  }elseif(in_array($uid,$BoardManagerArr)){
    return true;
  }elseif($uid==$discuss_uid){
    return true;
  }
  return false;
}

//更新刪除時是否限制身份
function onlyMine(){
  global $xoopsUser,$isAdmin;
  $uid=is_object($xoopsUser)?$xoopsUser->getVar('uid'):"0";
  $board=get_tad_discuss_board($BoardID);
  $BoardManagerArr=explode(',',$board['BoardManager']);

  if($isAdmin){
    return;
  }elseif(in_array($uid,$BoardManagerArr)){
    return;
  }
  return "and uid='$uid'";
}



//刪除tad_discuss某筆資料資料
function delete_tad_discuss($DiscussID=""){
  global $xoopsDB,$xoopsUser,$isAdmin;

  if(!$xoopsUser)return;

  $uid=$xoopsUser->getVar('uid');
  $anduid=onlyMine();

  $sql = "delete from ".$xoopsDB->prefix("tad_discuss")." where DiscussID='$DiscussID' $anduid";
  $result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $total=get_re_num($DiscussID);

  if($total >0 ){
    $sql = "delete from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  }
}



//新增資料到tad_discuss中
function insert_tad_discuss(){
  global $xoopsDB,$xoopsUser;

  //取得使用者編號
  if(!$xoopsUser)return;

  $uid=($xoopsUser)?$xoopsUser->getVar('uid'):"";

  $myts = MyTextSanitizer::getInstance();
  $_POST['DiscussContent']=$myts->addSlashes($_POST['DiscussContent']);

  if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $myip = $_SERVER['REMOTE_ADDR'];
  } else {
    $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $myip = $myip[0];
  }

  //$now=date('Y-m-d H:i:s',xoops_getUserTimestamp(time()));
  $Discuss=get_tad_discuss($_POST['ReDiscussID']);
  $DiscussTitle=empty($_POST['DiscussTitle'])?"RE:".$Discuss['DiscussTitle']:$_POST['DiscussTitle'];
  $DiscussTitle=$myts->addSlashes($DiscussTitle);

  $time=date("Y-m-d H:i:s");
  $sql = "insert into ".$xoopsDB->prefix("tad_discuss")."   (`ReDiscussID` , `uid` , `DiscussTitle` , `DiscussContent` , `DiscussDate` , `BoardID` , `LastTime` , `Counter` , `FromIP`)
  values('{$_POST['ReDiscussID']}' , '{$uid}' , '{$DiscussTitle}' , '{$_POST['DiscussContent']}' , '{$time}', '{$_POST['BoardID']}' , '{$time}' , '{$_POST['Counter']}', '$myip')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //取得最後新增資料的流水編號
  $DiscussID=$xoopsDB->getInsertId();

  $xoopsUser->incrementPost();
  upload_file("DiscussID" , $DiscussID , 500);

  $ToDiscussID= $DiscussID;
  if(!empty($_POST['ReDiscussID'])){
    $sql = "update ".$xoopsDB->prefix("tad_discuss")." set `LastTime` = '{$time}'
    where `DiscussID` = '{$_POST['ReDiscussID']}' or `ReDiscussID` = '{$_POST['ReDiscussID']}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $ToDiscussID=$_POST['ReDiscussID'];
  }

  //全局
  $extra_tags['DISCUSS_TITLE'] = $_POST['DiscussTitle'];
  $extra_tags['DISCUSS_CONTENT'] = strip_tags($_POST['DiscussContent']);

  $extra_tags['DISCUSS_URL'] = XOOPS_URL."/modules/tad_discuss/discuss.php?DiscussID={$ToDiscussID}&BoardID={$_POST['BoardID']}";
  $notification_handler =& xoops_gethandler('notification');
  $notification_handler->triggerEvent("global", null , "new_discuss", $extra_tags , null, null,0);

  //分類
  if(!empty($_POST['BoardID'])){
    $Board=get_tad_discuss_board($_POST['BoardID']);
    $extra_tags['BOARD_TITLE'] = $Board['BoardTitle'];
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->triggerEvent("board", $_POST['BoardID'] , "new_board_discuss", $extra_tags , null, null,0);
  }

  if(!empty($_POST['ReDiscussID']))return $_POST['ReDiscussID'];
  return $DiscussID;
}


?>
