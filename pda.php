<?php
/*-----------引入檔案區--------------*/
if(file_exists("mainfile.php")){
  include_once "mainfile.php";
}elseif("../../mainfile.php"){
  include_once "../../mainfile.php";
}
include_once "function.php";
include_once "up_file.php";
/*-----------function區--------------*/


//列出所有tad_discuss_board資料
function list_tad_discuss_board($show_function=1){
	global $xoopsDB , $isAdmin , $xoopsModule , $xoopsUser;

  //取得本模組編號
  $module_id = $xoopsModule->getVar('mid');
  $module_name = $xoopsModule->getVar('name');

  //$isAdmin=isAdmin();

  //取得目前使用者的群組編號
	if($xoopsUser) {
		$uid=$xoopsUser->getVar('uid');
		$groups=$xoopsUser->getGroups();
	}else{
    $uid=0;
		$groups = XOOPS_GROUP_ANONYMOUS;
	}
	$gperm_handler =& xoops_gethandler('groupperm');

	$sql = "select * from `".$xoopsDB->prefix("tad_discuss_board")."` where BoardEnable='1' order by BoardSort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


	$all_content="";

  if (mysql_num_rows($result) == 0) { 
    $all_content.=_MD_TADDISCUS_BOARD_EMPTY;
  }

	while($all=$xoopsDB->fetchArray($result)){
		//以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
		foreach($all as $k=>$v){
			$$k=$v;
		}
		
		if(!$gperm_handler->checkRight('forum_read',$BoardID,$groups,$module_id))continue;

    $pic=get_pic_file('BoardID' , $BoardID , 1 , 'thumb');
    $pic=empty($pic)?"images/board.png":$pic;

    $list_tad_discuss=list_tad_discuss_short($BoardID,7);


    $fun=($show_function)?"
    <a href='admin/main.php?op=tad_discuss_board_form&BoardID=$BoardID' rel='external'><i class='icon-wrench'></i></a>":"";
    
    $add="<span class='ui-li-count'><a href='#form_{$BoardID}'><i class='icon-pencil'></i></span></a>";

    $viewboard="<a href='{$_SERVER['PHP_SELF']}?op=show_board&BoardID={$BoardID}'><i class='icon-chevron-right'></i></a>";


    $BoardNum=get_board_num($BoardID);
    $DiscussNum=get_board_num($BoardID,false);

		$BoardNum=sprintf(_MD_TADDISCUS_BOARD_DISCUSS,number_format($BoardNum));
		$DiscussNum=sprintf(_MD_TADDISCUS_ALL_DISCUSS,number_format($DiscussNum));

    if($xoopsUser){
        $form_data.=tad_discuss_form($BoardID,'',$DefDiscussID,'jqm');
      } else {
        $form_data.="
          <div data-role='page' id='form_{$BoardID}'>
            <div data-theme='c' data-role='header' data-position='fixed'>
              <h3>{$title}</h3>
              <a href='#index' data-icon='delete' data-iconpos='notext' class='ui-btn-right'>Menu</a>
            </div>
            <div data-role='content'>
              <div id='form-area'>
                "._MD_TADDISCUS_NEEDLOGIN."
              </div>
            </div>
          </div>        
        ";
      }

    $all_content.="
        <ul data-role='listview' data-inset='true' data-header-theme='c' data-divider-theme='c'>
        <li data-role='list-divider'>{$fun} {$BoardTitle} ({$BoardNum} · {$DiscussNum}) {$viewboard} {$add}</li>
        {$list_tad_discuss}
        </ul>
    ";

	}

    $login=login_m();

    $page="
    <!-- index -->
    <div data-role='page' id='index'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <a href='#login' data-icon='bars' data-iconpos='notext'>Menu</a>
        <h3>{$module_name}</h3>
      </div>
      <div data-role='content'>
        {$all_content}
      </div>
      <div data-role='panel' data-position='left' data-display='push' id='login' data-theme='c'>
        {$login}
      </div>
    </div>
    {$form_data}
  ";

  return $page;
}

//列出所有tad_discuss資料
function list_tad_discuss_short($BoardID=null,$limit=null){
  global $xoopsDB,$xoopsModule,$xoopsUser;

  $andBoardID=(empty($BoardID))?"":"and a.BoardID='$BoardID'";
  $andLimit=($limit > 0)?"limit 0,$limit":"";
  $sql = "select a.*,b.* from ".$xoopsDB->prefix("tad_discuss")." as a left join ".$xoopsDB->prefix("tad_discuss_board")." as b on a.BoardID = b.BoardID where a.ReDiscussID='0' $andBoardID  order by a.LastTime desc $andLimit";

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //$main_data="<table style='width:100%'>";
  //$i=0;
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $member_handler = xoops_gethandler('member');
    $user = $member_handler->getUser($uid);
    if (is_object($user)) {
      $ts = MyTextSanitizer::getInstance();
      $pic_avatar=$ts->htmlSpecialChars($user->getVar('user_avatar'));
    }

    $pic_avatar=(empty($pic_avatar) or $pic_avatar=='blank.gif')?"images/nobody.png":XOOPS_URL."/uploads/".$pic_avatar;

    $renum=get_re_num($DiscussID);
    //$show_re_num=empty($renum)?"":sprintf(_MD_TADDISCUS_RE_DISCUSS,$renum);

    $uid_name=XoopsUser::getUnameFromId($uid,1);
    $LastTime=substr($LastTime,0,10);

    $renum=_MD_TADDISCUS_DISCUSSRE.$renum;

    $main_data.="
      <li class='inner-wrap ui-icon-alt'><a href='{$_SERVER['PHP_SELF']}?op=show_one&DiscussID={$DiscussID}&BoardID={$BoardID}'><img src='{$pic_avatar}'>
        <h2>{$DiscussTitle}</h2>
        <p style='color:#666'><strong>{$uid_name} · {$LastTime} · {$renum}</strong></p></a>
      </li>
    ";
  }
  return $main_data;

}

//以流水號秀出某筆tad_discuss資料內容
function show_one_tad_discuss($DefDiscussID=""){
  global $xoopsDB,$xoopsModule,$xoopsUser,$isAdmin,$xoopsModuleConfig;

  $isAdmin=isAdmin();

  if(empty($DefDiscussID)){
    return;
  }else{
  
    $DefDiscussID=intval($DefDiscussID);
    $discuss=get_tad_discuss($DefDiscussID);
    


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
    if(!$gperm_handler->checkRight('forum_read',$discuss['BoardID'],$groups,$module_id)){
      header('location:index.php');
    }

    
    if($discuss['ReDiscussID']!=0){
      header("location: {$_SERVER['PHP_SELF']}?DiscussID={$discuss['ReDiscussID']}&BoardID={$discuss['BoardID']}");
    }

    $member_handler = xoops_gethandler('member');
    $user = $member_handler->getUser($uid);
    if (is_object($user)) {
      $ts = MyTextSanitizer::getInstance();
      $uid_name=$ts->htmlSpecialChars($user->getVar('name'));
      if(empty($uid_name))$uid_name=$ts->htmlSpecialChars($user->getVar('uname'));
      $pic=$ts->htmlSpecialChars($user->getVar('user_avatar'));
    }

  $pic=(empty($pic) or $pic=='blank.gif')?"images/nobody.png":XOOPS_URL."/uploads/".$pic;
  }

  add_tad_discuss_counter($DefDiscussID);
  
  $js="
  <script type='text/javascript' src='".XOOPS_URL."/modules/tadtools/jqueryCookie/jquery.cookie.js'></script>
  <link rel='stylesheet' type='text/css' media='screen' href='reset.css' />
    <script>
    function like(op,DiscussID){
     if($.cookie('like'+DiscussID)){
        alert('"._MD_TADDISCUS_HAD_LIKE."');
     }else{
      $.post('like.php',  {op: op , DiscussID: DiscussID} , function(data) {
        $('#'+op+DiscussID).html(data);
      });

      $.cookie('like'+DiscussID , true , { expires: 7 });
     }
    }


    function delete_tad_discuss_func(DiscussID){
      var sure = window.confirm('"._TAD_DEL_CONFIRM."');
      if (!sure)  return;
      location.href=\"{$_SERVER['PHP_SELF']}?op=delete_tad_discuss&ReDiscussID=$DefDiscussID&BoardID={$discuss['BoardID']}&DiscussID=\" + DiscussID;
    }
  </script>";


  $Board=get_tad_discuss_board($discuss['BoardID']);


  
  $sql = "select * from ".$xoopsDB->prefix("tad_discuss")." where DiscussID='$DefDiscussID' or ReDiscussID='$DefDiscussID' order by ReDiscussID , DiscussDate";

  //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql , $xoopsModuleConfig['show_bubble_amount'] , 10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];
  
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $discuss_data="";
  $i=1;

  $member_handler = xoops_gethandler('member');
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
    foreach($all as $k=>$v){
      $$k=$v;
    }  

    $discuss_data=talk_bubble($BoardID,$DiscussID,$DiscussContent,$dir,$uid,$DiscussDate,'return',$Good,$Bad,$width);

    if($discuss_data['like']){
      $like="
      <div class='like-unlike'>
      <span>{$Bad}</span> <a href='javascript:like(\"unlike\",{$discuss_data['DiscussID']});'><i class='icon-thumbs-down'></i></a> | <a href='javascript:like(\"like\",{$discuss_data['DiscussID']});'><i class='icon-thumbs-up'></i></a> <span>{$Good}</span>
      </div>
      ";
      }

    if($discuss_data['fun']){
        $form_data_edit.=tad_discuss_form($discuss_data['BoardID'],$discuss_data['DiscussID'],'','jqm');
      }

    $edit=$discuss_data['fun']?
      "<div class='edit-area'><a href='javascript:delete_tad_discuss_func({$discuss_data['DiscussID']});'><i class='icon-trash'></i></a> |
        <a href='#form_{$discuss_data['DiscussID']}'><i class='icon-pencil'></i></a></div>":"";

    $main.="
    <div class='content-head'><div class='avatar'><img src='{$discuss_data['pic']}'>{$discuss_data['uid_name']}</div>
    <div class='time-mark'><div class='tmwrap'><div class='tmcell'>#{$i}<br>{$discuss_data['DiscussDate']}</div></div></div>
    <div class='clearfix'></div>
    </div>
    <div class='content-box'>
      {$discuss_data['DiscussContent']}
      <div class='content-files'>{$discuss_data['files']}</div>
      <div class='content-footer'>
      {$like}{$edit}
      <div class='clearfix'></div></div>
    </div>";

    $i++;
  }

  if($xoopsUser){
      $form_data=tad_discuss_form($BoardID,'',$DefDiscussID);
    } else {
      $form_data=_MD_TADDISCUS_NEEDLOGIN;
    }

  $title=$discuss['DiscussTitle'];

    $page="
      <!-- showone -->
      <div data-role='page' id='index'>
        <div data-theme='c' data-role='header' data-position='fixed'>
          <a href='{$_SERVER['PHP_SELF']}' data-icon='arrow-l'>Back</a>
          <h3>{$title}</h3>
          <a href='#form' data-icon='edit' data-iconpos='notext' class='ui-btn-right'>Menu</a>
        </div>
        <div data-role='content'>
        $js
          {$main}
          {$bar}
        </div>
      </div>
    <!-- formadd -->
    <div data-role='page' id='form'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <h3>{$title}</h3>
        <a href='#index' data-icon='delete' data-iconpos='notext' class='ui-btn-right'>Menu</a>
      </div>
      <div data-role='content'>
        <div id='form-area'>
          {$form_data}
        </div>
      </div>
    </div>
    {$form_data_edit}

    ";

  return $page;
}

//列出所有tad_discuss資料
function list_tad_discuss_m($DefBoardID=null){
  global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsModuleConfig,$isAdmin;
  
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

    $member_handler = xoops_gethandler('member');
    $user = $member_handler->getUser($uid);
    if (is_object($user)) {
      $ts = MyTextSanitizer::getInstance();
      $pic_avatar=$ts->htmlSpecialChars($user->getVar('user_avatar'));
    }

    $pic_avatar=(empty($pic_avatar) or $pic_avatar=='blank.gif')?"images/nobody.png":XOOPS_URL."/uploads/".$pic_avatar;

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

    $LastTime=substr($LastTime,0,16);
    $DiscussDate=substr($DiscussDate,0,16);
    
    $renum=_MD_TADDISCUS_DISCUSSRE.$renum;

    $main_data.="
      <li class='inner-wrap ui-icon-alt'><a href='{$_SERVER['PHP_SELF']}?op=show_one&DiscussID={$DiscussID}&BoardID={$BoardID}'><img src='$pic_avatar'>
        <h2>{$DiscussTitle}</h2>
        <p style='color:#666'><strong>{$uid_name} · {$LastTime} · {$renum}</strong></p></a>
      </li>";

  }

  $Board=get_tad_discuss_board($DefBoardID);
  if(!empty($DefBoardID)){
  $title=$Board['BoardTitle'];
  }

  if($xoopsUser){
      $form_data=tad_discuss_form($BoardID,'',$DefDiscussID);
    } else {
      $form_data=_MD_TADDISCUS_NEEDLOGIN;
    }

  if(empty($main_data))$main_data="<li>"._MD_TADDISCUS_DISCUSS_EMPTY."</li>";

  $login=login_m();

  $data="
    <!-- index -->
    <div data-role='page' id='index'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <a href='#login' data-icon='bars' data-iconpos='notext'>Menu</a>
        <h3>{$title}</h3>
        <a href='#form' data-icon='edit' data-iconpos='notext' class='ui-btn-right'>add</a>
      </div>
      <div data-role='content'>
        <ul data-role='listview' data-inset='false' data-header-theme='c'>
        {$main_data}
        </ul>
        <div style='margin-top:30px;'>{$bar}</div>
      </div>
      <div data-role='panel' data-position='left' data-display='push' id='login' data-theme='c'>
        {$login}
      </div>
    </div>
    <!-- formadd -->
    <div data-role='page' id='form'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <h3>{$title}</h3>
        <a href='#index' data-icon='delete' data-iconpos='notext' class='ui-btn-right'>Menu</a>
      </div>
      <div data-role='content'>
        <div id='form-area'>
          {$form_data}
        </div>
      </div>
    </div>
    {$form_data_edit}

    ";
  

  //raised,corners,inset
  //$main=div_3d($ShowBoardTitle,$data,"corners","width:100%");

  return $data;
}

//tad_discuss編輯表單
function tad_discuss_form($BoardID="",$DefDiscussID="",$DefReDiscussID="",$mode=""){
  global $xoopsDB,$xoopsUser,$isAdmin,$xoopsModuleConfig,$xoopsModule,$xoopsTpl;

  if(empty($xoopsUser)){
    redirect_header("pda.php",3, _MD_TADDISCUS_NEEDLOGIN);
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
    header('location:pda.php');
  }


  //抓取預設值
  if(!empty($DefDiscussID)){
    $DBV=get_tad_discuss($DefDiscussID);
  }else{
    $DBV=array();
  }

  //預設值設定


  //設定「DiscussID」欄位預設值
  $DiscussID=(!isset($DBV['DiscussID']))?$DefDiscussID:$DBV['DiscussID'];

  //設定「ReDiscussID」欄位預設值
  $ReDiscussID=(!isset($DBV['ReDiscussID']))?$DefReDiscussID:$DBV['ReDiscussID'];

  //設定「uid」欄位預設值
  $uid=(!isset($DBV['uid']))?'':$DBV['uid'];
  $uid=(is_object($xoopsUser) and empty($uid))?$xoopsUser->getVar('uid'):$uid;

  //設定「DiscussTitle」欄位預設值
  $DiscussTitle=(!isset($DBV['DiscussTitle']))?_MD_TADDISCUS_INPUT_TITLE:$DBV['DiscussTitle'];

  //設定「DiscussContent」欄位預設值
  $DiscussContent=(!isset($DBV['DiscussContent']))?"":$DBV['DiscussContent'];

  //設定「DiscussDate」欄位預設值
  $DiscussDate=(!isset($DBV['DiscussDate']))?date("Y-m-d H:i:s"):$DBV['DiscussDate'];

  //設定「BoardID」欄位預設值
  $BoardID=(!isset($DBV['BoardID']))?$BoardID:$DBV['BoardID'];

  //設定「LastTime」欄位預設值
  $LastTime=(!isset($DBV['LastTime']))?date("Y-m-d H:i:s"):$DBV['LastTime'];

  //設定「Counter」欄位預設值
  $Counter=(!isset($DBV['Counter']))?"":$DBV['Counter'];

  $op=(empty($DiscussID))?"insert_tad_discuss":"update_tad_discuss";
  //$op="replace_tad_discuss";

  if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
   redirect_header("pda.php",3, _MD_NEED_TADTOOLS);
  }
  $ID=empty($DiscussID)?$BoardID:$DiscussID;
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#myForm{$ID}",true);
  $formValidator_code=$formValidator->render('bottomLeft');

  $RE=!empty($DefReDiscussID)?get_tad_discuss($DefReDiscussID):array();

  $DiscussTitle=empty($DefReDiscussID)?"<input type='text' name='DiscussTitle' size='20' value='{$DiscussTitle}' id='DiscussTitle' class='validate[required]' onClick=\"if(this.value=='"._MD_TADDISCUS_INPUT_TITLE."')this.value='';\"><br>":"<input type='hidden' name='DiscussTitle' value='RE:{$RE['DiscussTitle']}'>";

  $Board=get_tad_discuss_board($BoardID);
  if($Board['BoardEnable']=='0')redirect_header('pda.php',3,_MD_TADDISCUS_BOARD_UNABLE);
  //$BoardTitle=(empty($DefDiscussID) and empty($DefReDiscussID))?"<h1><a href='discuss.php?BoardID=$BoardID'>{$Board['BoardTitle']}</a></h1>":"";
  //die('$BoardID:'.$BoardID.',$DefDiscussID:'.$DefDiscussID.',$DefReDiscussID:'.$DefReDiscussID);
  if(!empty($BoardID) and empty($DefDiscussID) and empty($DefReDiscussID)){
    $BoardTitle=get_board_title($BoardID);
  }
//die($BoardTitle);
  $DiscussContent="  
  $formValidator_code
  <script type='text/javascript' src='class/nicEdit.js'></script>
  <script type='text/javascript'>
    bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
  </script>
  <form data-ajax='false' action='pda.php' method='post' id='myForm{$ID}' enctype='multipart/form-data'>
  $DiscussTitle
  <textarea name='DiscussContent' cols='50' rows=8 id='DiscussContent' class='validate[required,minSize[5]]' style='width:320px; height:150px;font-size:12px;line-height:150%;border:1px dotted #B0B0B0;'>{$DiscussContent}</textarea>
  <input type='hidden' name='BoardID' value='{$BoardID}'>
  <input type='hidden' name='DiscussID' value='{$DefDiscussID}'>
  <input type='hidden' name='ReDiscussID' value='{$ReDiscussID}'>
  <input type='hidden' name='op' value='{$op}'>
  <span style='display:block;float:right;'><button type='submit' class=''>"._TAD_SAVE."</button></span>
  ".list_del_file("DiscussID",$DefDiscussID)."</form>";

  $DiscussDate=date('Y-m-d H:i:s',xoops_getUserTimestamp(strtotime($DiscussDate)));

    
  //$all[0]=talk_bubble($BoardID,$DiscussID,$DiscussContent,$dir,$uid,$DiscussDate,'return',null,null,$width);


  $discuss=get_tad_discuss($DefDiscussID);
  $title=empty($discuss['DiscussTitle'])?$Board['BoardTitle']:$discuss['DiscussTitle']; 
  $main.="
    <!-- form -->
    <div data-role='page' id='form_{$ID}'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <h3>{$title}</h3>
        <a href='#index' data-icon='delete' data-iconpos='notext' class='ui-btn-right'>Menu</a>
      </div>
      <div data-role='content'>
        <div id='form-area'>
          {$DiscussContent}
        </div>
      </div>
    </div>
  ";
  if($mode=="jqm"){
    return $main;
  } else {
    return $DiscussContent;
  }
}

//新增資料到tad_discuss中
function insert_tad_discuss(){
  global $xoopsDB,$xoopsUser;

  //取得使用者編號
  if(!$xoopsUser)return;
  
   $uid=($xoopsUser)?$xoopsUser->getVar('uid'):"";

  $myts = MyTextSanitizer::getInstance();
  $_POST['DiscussTitle']=$myts->addSlashes($_POST['DiscussTitle']);
  $_POST['DiscussContent']=$myts->addSlashes($_POST['DiscussContent']);

  if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $myip = $_SERVER['REMOTE_ADDR'];
  } else {
      $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      $myip = $myip[0];
  }
  
  //$now=date('Y-m-d H:i:s',xoops_getUserTimestamp(time()));

  $sql = "insert into ".$xoopsDB->prefix("tad_discuss")."   (`ReDiscussID` , `uid` , `DiscussTitle` , `DiscussContent` , `DiscussDate` , `BoardID` , `LastTime` , `Counter` , `FromIP`)
  values('{$_POST['ReDiscussID']}' , '{$uid}' , '{$_POST['DiscussTitle']}' , '{$_POST['DiscussContent']}' , now() , '{$_POST['BoardID']}' , now() , '{$_POST['Counter']}', '$myip')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //取得最後新增資料的流水編號
  $DiscussID=$xoopsDB->getInsertId();
  
  $xoopsUser->incrementPost();
  upload_file("DiscussID" , $DiscussID , 500);

  $ToDiscussID= $DiscussID;
  if(!empty($_POST['ReDiscussID'])){
    $sql = "update ".$xoopsDB->prefix("tad_discuss")." set `LastTime` = now()
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

//更新tad_discuss某一筆資料
function update_tad_discuss($DiscussID=""){
  global $xoopsDB,$xoopsUser;

  $myts = MyTextSanitizer::getInstance();
  $_POST['DiscussTitle']=$myts->addSlashes($_POST['DiscussTitle']);
  $_POST['DiscussContent']=$myts->addSlashes($_POST['DiscussContent']);

  if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $myip = $_SERVER['REMOTE_ADDR'];
  } else {
      $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      $myip = $myip[0];
  }

  $anduid=onlyMine();
  

  //$now=date('Y-m-d H:i:s',xoops_getUserTimestamp(time()));

  $sql = "update ".$xoopsDB->prefix("tad_discuss")." set
   `DiscussTitle` = '{$_POST['DiscussTitle']}' ,
   `DiscussContent` = '{$_POST['DiscussContent']}' ,
   `LastTime` = now(),
   `FromIP` = '$myip'
  where DiscussID='$DiscussID' $anduid";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  
  upload_file("DiscussID" , $DiscussID , 500);
  return $DiscussID;
}


//刪除tad_discuss某筆資料
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

//判斷是否為管理員
function isAdmin(){
  global $xoopsUser,$xoopsModule;
  $isAdmin=false;
  if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
  }
  return $isAdmin;
}

//新增tad_discuss計數器
function add_tad_discuss_counter($DiscussID=''){
  global $xoopsDB,$xoopsModule;
  $sql = "update ".$xoopsDB->prefix("tad_discuss")." set `Counter`=`Counter`+1 where `DiscussID`='{$DiscussID}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

function login_m(){
  global $xoopsDB,$xoopsUser;

if($xoopsUser){
  $main="
<ul data-role='listview' data-theme='c' data-divider-theme='c' style='margin-top:-16px;'>
    <li data-icon='delete' style='background-color:#111;'>
      <a href='#' data-rel='close'>User Menu</a>
    </li>
    <li><a title='Administration Menu' href='".XOOPS_URL."/admin.php' rel='external'>Administration Menu</a></li>
    <li><a title='View Account' href='".XOOPS_URL."/user.php' rel='external'>View Account</a></li>
    <li><a title='Edit Account' href='".XOOPS_URL."/edituser.php' rel='external'>Edit Account</a></li>
    <li><a title='Notifications' href='".XOOPS_URL."/notifications.php' rel='external'>Notifications</a></li>
    <li><a title='Inbox' href='".XOOPS_URL."/viewpmsg.php' rel='external'>Inbox</a></li>
    <li><a title='Logout' href='".XOOPS_URL."/user.php?op=logout' rel='external'>Logout</a></li>
</ul>";
}else{
  $main="
<ul data-role='listview' data-theme='c' data-divider-theme='c' style='margin-top:-16px;'>
    <li data-icon='delete' style='background-color:#111;'>
      <a href='#' data-rel='close'>User Login</a>
    </li>
    <li>
<form method='post' action='".XOOPS_URL."/user.php' data-ajax='false'>
  User:<br>
  <input type='text' maxlength='25' value='' size='12' name='uname'>
   Password:<br>
  <input type='password' maxlength='32' size='12' name='pass'><br>
  <input type='hidden' value='/modules/tad_discuss/pda.php' name='xoops_redirect'>
  <input type='hidden' value='login' name='op'>
  <button type='submit' name='submit' value='Login'>Login</button><br>
</form>
</li>
</ul>
";
}
return $main;
}

/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$DiscussID=empty($_REQUEST['DiscussID'])?"":intval($_REQUEST['DiscussID']);
$BoardID=empty($_REQUEST['BoardID'])?"":intval($_REQUEST['BoardID']);
$files_sn=empty($_REQUEST['files_sn'])?"":intval($_REQUEST['files_sn']);

switch($op){

  //新增資料
  case "insert_tad_discuss":
  $DiscussID=insert_tad_discuss();
  header("location: {$_SERVER['PHP_SELF']}?op=show_one&DiscussID=$DiscussID&BoardID=$BoardID");
  break;

  //更新資料
  case "update_tad_discuss":
  update_tad_discuss($DiscussID);
  //$ID=empty($ReDiscussID)?$DiscussID:$ReDiscussID;
  //header("location: {$_SERVER['PHP_SELF']}?op=show_one&DiscussID=$ID&BoardID=$BoardID");
  header("location: {$_SERVER['HTTP_REFERER']}");
  break;


  //刪除資料
  case "delete_tad_discuss":
  delete_tad_discuss($DiscussID);
  header("location: {$_SERVER['PHP_SELF']}?BoardID=$BoardID");
  break;

  //輸入表格
  case "tad_discuss_form":
  $main=tad_discuss_form($BoardID,$DiscussID,$ReDiscussID);
  break;

  //單一討論
  case "show_one":
  $main=show_one_tad_discuss($DiscussID);
  break;

  //單一討論區
  case "show_board":
  $main=list_tad_discuss_m($BoardID);
  break;  

  default:
  $isAdmin=isAdmin();
  $main=list_tad_discuss_board($isAdmin);
  break;
}

/*-----------秀出結果區--------------*/



echo "
<!DOCTYPE html>
<html lang='"._LANGCODE."'>
<head>
  <meta charset='"._CHARSET."'>
  <meta name='viewport' content='initial-scale=1.0, user-scalable=no'>
  <title>{$title}</title>  
  <link href='http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css' rel='stylesheet' type='text/css'/>  
  <link href='".XOOPS_URL."/modules/tadtools/bootstrap/css/bootstrap.css' rel='stylesheet' type='text/css'/>
  <style>
  /*.ui-header .ui-title {
    margin: 0.6em 2% 0.8em !important;
  }*/
  h1, h2, h3 {
    line-height: 1.1em;
  }
  h2.ui-li-heading {
    white-space: normal;
    font-size: 15px;
  }
  /*.ui-li .ui-btn-inner a.ui-link-inherit {
    padding: 0.4em 20px;
  }*/
  #menu a.ui-link-inherit {
    padding: 0.8em 15px 0.8em 40px;
  }
  #menu a.ui-link-inherit img{
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.22);
    padding: 2px;
    background-color: #FFF;
    top: 0.8em;
  }
  .inner-content li{
    background-color: transparent;
    border: 0;
  }  
  .inner-body {
    white-space: normal;
  }
  .inner-body img{
    max-width:100% !important;
    height:auto;
  }
  .inner-body .ui-li-desc{
    font-size:1em;
    margin-top: 0.8em;
    white-space: normal;
  }
  .read-more {
    margin-top: 20px;
    text-align: center;
  }
  /*.ui-content {
    padding: 10px 5px !important;
  }*/
  .content-head {}
  .content-box {
    border: 1px solid #B3B3B3;
    border-radius: 5px;
    margin: 10px 0;
    padding: 10px;
    background-color: #FFFFFF;
  }
  .avatar {
    float: left;
    font-weight: bold;
  }
  .avatar img {
    border-radius: 10px;
    box-shadow: 2px 2px 2px 0px #B3B3B3;
    max-width:60px;
    max-height:60px;
    vertical-align:text-bottom;
    margin-right: 5px;
  }
  .time-mark {
    float: right;
  }
  .time-mark .tmwrap{
    display: table;
    height: 60px;
  }
  .time-mark .tmcell{
    font-size: 0.75em;
    display: table-cell;
    vertical-align:bottom;
    text-align:right;
  }
  #form-area {}
  .clearfix:after {
      content: '.';
      display: block;
      height: 0;
      clear: both;
      visibility: hidden;
  }
  .content-files {}
  .content-footer {}
  .edit-area {
    float: left;
    margin-top: 20px;
  }
  .like-unlike{
    float: right;
    margin-top: 20px;
  }
  .clearfix {display: inline-block;}

  /* Hides from IE-mac \*/
  * html .clearfix {height: 1%;}
  .clearfix {display: block;}
  /* End hide from IE-mac */
  </style>
  
  <script src='".XOOPS_URL."/modules/tadtools/jquery/jquery.js' type='text/javascript'></script>
  <script>
    $(document).bind('mobileinit', function(){
      $.mobile.defaultPageTransition = 'slide';
      //$.mobile.page.prototype.options.addBackBtn = true;
      $.mobile.ajaxEnabled = false;
    });
  </script>
  <script src='http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js' type='text/javascript'></script>
   
</head>
<body>
<!-- Home -->

    {$main}

</body>
</html>";
?>
