<?php
//引入TadTools的函式庫
if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php";
include_once "function_block.php";

/********************* 自訂函數 *********************/


//對話框格式
function talk_bubble($BoardID='',$DiscussID='',$DiscussContent='',$dir='left',$uid="",$publisher="",$DiscussDate='',$mode='',$Good=0,$Bad=0,$width=100,$onlyTo=""){
  global $xoopsUser,$xoopsTpl,$xoopsModuleConfig,$TadUpFiles;
  $member_handler = xoops_gethandler('member');
  $user = $member_handler->getUser($uid);
  if (is_object($user)) {
    $ts = MyTextSanitizer::getInstance();
    $uid_name=empty($publisher)?$ts->htmlSpecialChars($user->getVar('name')):$publisher;
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
  //$files=show_files("DiscussID" , $DiscussID , true , '' , true , false);
  if($_REQUEST['op']!='tad_discuss_form'){
    $TadUpFiles->set_col("DiscussID" , $DiscussID );
    $files=$TadUpFiles->show_files("upfile",true,NULL,false,false);  //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數
  }

  $files=isPublic($onlyTo,$uid,$BoardID)?$files:"";
  $DiscussDate=date('Y-m-d H:i:s',xoops_getUserTimestamp(strtotime($DiscussDate)));
  if($xoopsModuleConfig['display_mode']=="mobile"){
    $DiscussDate=substr($DiscussDate,0,16);
  }


  $onlyToName=getOnlyToName($onlyTo);

  $all['width']=$width;
  $all['dir']=$dir;
  $all['pic']=$pic;
  $all['pic_css']=$pic_css;
  $all['pic_js']=$pic_js;
  $all['fun']=$fun;
  $all['like']=$like;
  $all['uid']=$uid;
  $all['uid_name']=$uid_name;
  $all['DiscussDate']=$DiscussDate;
  //$all['DiscussContent']=$DiscussContent;
  $all['DiscussContent']=isPublic($onlyTo,$uid,$BoardID)?$DiscussContent:sprintf(_MD_TADDISCUS_ONLYTO,$onlyToName);
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
  $now_uid=is_object($xoopsUser)?$xoopsUser->getVar('uid'):"0";

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

    if(empty($publisher)){
      $uid_name=XoopsUser::getUnameFromId($uid,1);
      if(empty($uid_name))$uid_name=XoopsUser::getUnameFromId($uid,0);
    }else{
      $uid_name=$publisher;
    }

    //最後回應者
    $sql2 = "select uid,publisher from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID' order by DiscussDate desc limit 0,1";
    $result2 = $xoopsDB->queryF($sql2) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    //if($isAdmin)die($sql2);
    list($last_uid,$last_uid_name)=$xoopsDB->fetchRow($result2);
    //if($isAdmin and $BoardID==19)die("<div>$sql2</div>\$last_uid={$last_uid}");
    if(empty($last_uid_name)){
      if(empty($last_uid)){
        $last_uid_name=$uid_name;
      }else{
        $last_uid_name=XoopsUser::getUnameFromId($last_uid,1);
        if(empty($last_uid_name))$last_uid_name=XoopsUser::getUnameFromId($last_uid,0);
      }
    }

    $LastTime=date('Y-m-d H:i:s',xoops_getUserTimestamp(strtotime($LastTime)));
    $LastTime=substr($LastTime,0,16);
    $DiscussDate=date('Y-m-d H:i:s',xoops_getUserTimestamp(strtotime($DiscussDate)));
    $DiscussDate=substr($DiscussDate,0,16);

    $isPublic=isPublic($onlyTo,$uid,$DefBoardID);
    $onlyToName=getOnlyToName($onlyTo);


    $DiscussTitle=str_replace("[s","<img src='".XOOPS_URL."/modules/tad_discuss/images/smiles/s",$DiscussTitle);
    $DiscussTitle=str_replace(".gif]",".gif' hspace=2 align='absmiddle'>",$DiscussTitle);

    $main_data[$i]['LastTime']=$LastTime;
    $main_data[$i]['DiscussID']=$DiscussID;
    $main_data[$i]['BoardID']=$BoardID;
    $main_data[$i]['DiscussTitle']=$isPublic?$DiscussTitle:sprintf(_MD_TADDISCUS_ONLYTO,$onlyToName);
    $main_data[$i]['uid_name']=$uid_name;
    $main_data[$i]['renum']=$renum;
    $main_data[$i]['DiscussDate']=$DiscussDate;
    $main_data[$i]['LastTime']=$LastTime;
    $main_data[$i]['last_uid_name']=$last_uid_name;
    $main_data[$i]['isPublic']=$isPublic;
    $i++;

  }

  $xoopsTpl->assign('main_data',$main_data);
  $xoopsTpl->assign('DefBoardID',$DefBoardID);


  $post_tool=($xoopsUser and !empty($DefBoardID) )?"<a href='{$_SERVER['PHP_SELF']}?op=tad_discuss_form&BoardID=$DefBoardID' class='link_button_r'><img src='images/edit.png' align='absmiddle' hspace=4 alt='"._MD_TADDISCUS_ADD_DISCUSS."'>"._MD_TADDISCUS_ADD_DISCUSS."</a>":"";

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
  global $TadUpFiles;
  if(empty($DefBoardID))return ;
  $Board=get_tad_discuss_board($DefBoardID);
  //$pic=get_pic_file('BoardID' , $Board['BoardID'] , 1 , 'thumb');
  $TadUpFiles->set_col('BoardID' , $DefBoardID);
  $pic=$TadUpFiles->get_pic_file('thumb'); //thumb 小圖, images 大圖（default）, file 檔案
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
//die("aa".var_export($board));
  $uid=$xoopsUser->uid();

//  echo "<p>{$isAdmin}?{$uid} -- {$board['BoardManager']}</p>";
  if($isAdmin){
    return true;
  }elseif(in_array($uid,$BoardManagerArr)){
    return true;
  }elseif($uid==$discuss_uid){
    return true;
  }
  return false;
}

//取得版主姓名
function getBoardManager($BoardID="",$mode=""){
  if(empty($BoardID))return false;
  $board=get_tad_discuss_board($BoardID);
  $BoardManagerArr=explode(',',$board['BoardManager']);
  foreach($BoardManagerArr as $uid){
    $BoardManagerName=XoopsUser::getUnameFromId($uid,1);
    if(empty($BoardManagerName))$BoardManagerName=XoopsUser::getUnameFromId($uid,0);
    $name[]="<a href='".XOOPS_URL."/userinfo.php?uid={$uid}'>{$BoardManagerName}</a>";
  }
  return $name;
}

//更新刪除時是否限制身份
function onlyMine($DiscussID=""){
  global $xoopsUser,$isAdmin,$xoopsModule;
  $uid=is_object($xoopsUser)?$xoopsUser->uid():"0";
  $Discuss=get_tad_discuss($DiscussID);
  $board=get_tad_discuss_board($Discuss['BoardID']);
  $BoardManagerArr=explode(',',$board['BoardManager']);

  if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
  }

  if($isAdmin){
    return;
  }elseif(in_array($uid,$BoardManagerArr)){
    return;
  }
  return "and uid='$uid'";
}



//刪除tad_discuss某筆資料資料
function delete_tad_discuss($DiscussID=""){
  global $xoopsDB,$xoopsUser,$isAdmin,$TadUpFiles;

  if(!$xoopsUser)return;

  $anduid=onlyMine($DiscussID);

  $sql = "delete from ".$xoopsDB->prefix("tad_discuss")." where DiscussID='$DiscussID' $anduid";
  //die($sql);
  if($xoopsDB->queryF($sql)){

    $TadUpFiles->set_col('DiscussID',$DiscussID); //若要整個刪除
    $TadUpFiles->del_files();

    $sql = "select DiscussID from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    while(list($DiscussID)=$xoopsDB->fetchRow($result)){
      delete_tad_discuss($DiscussID);
    }
  }else{
    redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  }
}

//檢查是否有不當言論
function chk_spam($content=""){
  global $xoopsModuleConfig;
  $keys=explode(",",$xoopsModuleConfig['spam_keyword']);
  foreach($keys as $key){
    $strpos=strpos($content, $key);
    if($strpos!==false){
      return true;
    }
  }
  return false;
}

//新增資料到tad_discuss中
function insert_tad_discuss($nl2br=false){
  global $xoopsDB,$xoopsUser,$TadUpFiles;

  //取得使用者編號
  if(!$xoopsUser)return;

  $member_handler =& xoops_gethandler('member');

  $uid=($xoopsUser)?$xoopsUser->getVar('uid'):"";

  $myts = MyTextSanitizer::getInstance();
  $_POST['DiscussContent']=$myts->addSlashes($_POST['DiscussContent']);

  if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $myip = $_SERVER['REMOTE_ADDR'];
  } else {
    $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $myip = $myip[0];
  }

  $ReDiscussID=isset($_POST['ReDiscussID'])?intval($_POST['ReDiscussID']):0;
  //$now=date('Y-m-d H:i:s',xoops_getUserTimestamp(time()));
  $Discuss=get_tad_discuss($ReDiscussID);
  $DiscussTitle=empty($_POST['DiscussTitle'])?"RE:".$Discuss['DiscussTitle']:$_POST['DiscussTitle'];
  $DiscussTitle=$myts->addSlashes($DiscussTitle);
  $publisher=$myts->addSlashes($_POST['publisher']);
  $BoardID=intval($_POST['BoardID']);

  $DiscussContent=$myts->addSlashes($_POST['DiscussContent']);
  if($nl2br)$DiscussContent=nl2br($DiscussContent);

  if(chk_spam($DiscussTitle))redirect_header($_SERVER['PHP_SELF'],3, _MD_TADDISCUS_FOUND_SPAM);
  if(chk_spam($DiscussContent))redirect_header($_SERVER['PHP_SELF'],3, _MD_TADDISCUS_FOUND_SPAM);


  $onlyTo="";
  if($_POST['only_root']=='1' and !empty($ReDiscussID)){
    $onlyTo=$Discuss['uid'];
  }elseif($_POST['only_root']=='1'){
    $adminusers = $member_handler->getUsersByGroup(1);
    $onlyTo=implode(',',$adminusers);
  }

  $time=date("Y-m-d H:i:s");
  $sql = "insert into ".$xoopsDB->prefix("tad_discuss")."   (`ReDiscussID` , `uid` , `publisher` , `DiscussTitle` , `DiscussContent` , `DiscussDate` , `BoardID` , `LastTime` , `Counter` , `FromIP` , `onlyTo`)
  values('{$ReDiscussID}' , '{$uid}' , '{$publisher}' , '{$DiscussTitle}' , '{$DiscussContent}' , '{$time}', '{$BoardID}' , '{$time}' , '0', '$myip' , '{$onlyTo}')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //取得最後新增資料的流水編號
  $DiscussID=$xoopsDB->getInsertId();

  $xoopsUser->incrementPost();

  $TadUpFiles->set_col("DiscussID" , $DiscussID);
//$TadUpFiles->upload_file($upname,$width,$thumb_width,$files_sn,$desc,$safe_name=false,$hash=false);
  $TadUpFiles->upload_file("upfile",1024,120,NULL,"",true);

  $ToDiscussID= $DiscussID;
  if(!empty($ReDiscussID)){
    $sql = "update ".$xoopsDB->prefix("tad_discuss")." set `LastTime` = '{$time}'
    where `DiscussID` = '{$ReDiscussID}' or `ReDiscussID` = '{$ReDiscussID}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $ToDiscussID=$ReDiscussID;
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

  if(!empty($ReDiscussID))return $ReDiscussID;
  return $DiscussID;
}




?>
