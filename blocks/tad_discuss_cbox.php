<?php
//區塊主函式 (會產生一個即時留言簿區塊)
function tad_discuss_cbox($options){
  global $xoopsUser,$xoopsModule,$xoopsDB;


  if(empty($xoopsUser)){
    return;
  }

  //取得本模組編號
  $modhandler = &xoops_gethandler('module');
  $xoopsModule = &$modhandler->getByDirname("tad_discuss");
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
  if(!$gperm_handler->checkRight('forum_read' , $options[0] , $groups , $module_id)){
    return;
  }


  //引入TadTools的jquery
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
  redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";

  $block['jquery_path']=get_jquery();
  $block['BoardID']=$options[0];


  $form="";
  if(empty($options[0])){

    $form="<select name='BoardID' onChange=\"window.open('".XOOPS_URL."/modules/tad_discuss/cbox.php?BoardID='+this.value,'discussCboxMain'); window.open('".XOOPS_URL."/modules/tad_discuss/post.php?BoardID='+this.value,'discussCboxForm');\">
      <option value=''>"._MB_TADDISCUS_ALL_BOARD."</option>";
    $sql = "select * from `".$xoopsDB->prefix("tad_discuss_board")."` where BoardEnable='1' order by BoardSort";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    while($all=$xoopsDB->fetchArray($result)){
      //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
      foreach($all as $k=>$v){
        $$k=$v;
      }

      $selected=($options[0]==$BoardID)?"selected":"";
      $form.="
      <option value='{$BoardID}' $selected>{$BoardTitle}</option>
      ";
    }

    $form.="</select>";
  }

  $block['SelectBoard']=$form;
  return $block;
}


//區塊編輯函式
function tad_discuss_cbox_edit($options){
  global $xoopsDB;
  $form=_MB_TADDISCUS_SELECT_BOARD."<select name='options[0]'>
    <option value='0'>"._MB_TADDISCUS_ALL_BOARD."</option>";
  $sql = "select * from `".$xoopsDB->prefix("tad_discuss_board")."` where BoardEnable='1' order by BoardSort";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $selected=($options[0]==$BoardID)?"selected":"";
    $form.=_MB_TADDISCUS_SELECT_BOARD."
    <option value='{$BoardID}' $selected>{$BoardTitle}</option>
    ";
  }

  $form.="</select>";
  return $form;
}

?>
