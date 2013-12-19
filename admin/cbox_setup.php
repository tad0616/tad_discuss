<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_discuss_adm_cbox_setup.html";
include_once "header.php";
include_once "../function.php";
/*-----------function區--------------*/
//tad_discuss_cbox_setup編輯表單
function tad_discuss_cbox_setup_form($setupID=""){
  global $xoopsDB , $xoopsTpl;
  //include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
  //include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

  //抓取預設值
  if(!empty($setupID)){
    $DBV=get_tad_discuss_cbox_setup($setupID);
  }else{
    $DBV=array();
  }

  //預設值設定


  //設定「setupID」欄位預設值
  $setupID=!isset($DBV['setupID'])?$setupID:$DBV['setupID'];
  $xoopsTpl->assign('setupID' , $setupID);

  //設定「setupName」欄位預設值
  $setupName=!isset($DBV['setupName'])?null:$DBV['setupName'];
  $xoopsTpl->assign('setupName' , $setupName);

  //設定「setupRule」欄位預設值
  $setupRule=!isset($DBV['setupRule'])?"":$DBV['setupRule'];
  $xoopsTpl->assign('setupRule' , $setupRule);

  //設定「BoardID」欄位預設值
  $BoardID=!isset($DBV['BoardID'])?"":$DBV['BoardID'];
  $xoopsTpl->assign('BoardID' , $BoardID);

  //設定「setupSort」欄位預設值
  $setupSort=!isset($DBV['setupSort'])?tad_discuss_cbox_setup_max_sort():$DBV['setupSort'];
  $xoopsTpl->assign('setupSort' , $setupSort);

  $op=(empty($setupID))?"insert_tad_discuss_cbox_setup":"update_tad_discuss_cbox_setup";
  //$op="replace_tad_discuss_cbox_setup";

  if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
    redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#myForm",true);
  $formValidator_code=$formValidator->render();

  $xoopsTpl->assign('action' , $_SERVER["PHP_SELF"]);
  $xoopsTpl->assign('formValidator_code' , $formValidator_code);
  $xoopsTpl->assign('now_op' , 'tad_discuss_cbox_setup_form');
  $xoopsTpl->assign('next_op' , $op);


  $sql = "select * from `".$xoopsDB->prefix("tad_discuss_board")."` where BoardEnable='1' order by BoardSort";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $option[$i]['BoardID']=$BoardID;
    $option[$i]['BoardTitle']=$BoardTitle;
    $i++;
  }
  $xoopsTpl->assign('option' , $option);
}



//新增資料到tad_discuss_cbox_setup中
function insert_tad_discuss_cbox_setup(){
  global $xoopsDB,$xoopsUser;

  //取得使用者編號
  $uid=($xoopsUser)?$xoopsUser->getVar('uid'):"";

  $myts =& MyTextSanitizer::getInstance();
  $_POST['setupName']=$myts->addSlashes($_POST['setupName']);
  $_POST['setupRule']=$myts->addSlashes($_POST['setupRule']);


  $sql = "insert into `".$xoopsDB->prefix("tad_discuss_cbox_setup")."`
  (`setupName` , `setupRule` , `BoardID` , `setupSort`)
  values('{$_POST['setupName']}' , '{$_POST['setupRule']}' , '{$_POST['BoardID']}' , '{$_POST['setupSort']}')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //取得最後新增資料的流水編號
  $setupID = $xoopsDB->getInsertId();
  return $setupID;
}

//更新tad_discuss_cbox_setup某一筆資料
function update_tad_discuss_cbox_setup($setupID=""){
  global $xoopsDB,$xoopsUser;

  //取得使用者編號
  $uid=($xoopsUser)?$xoopsUser->getVar('uid'):"";

  $myts =& MyTextSanitizer::getInstance();
  $_POST['setupName']=$myts->addSlashes($_POST['setupName']);
  $_POST['setupRule']=$myts->addSlashes($_POST['setupRule']);


  $sql = "update `".$xoopsDB->prefix("tad_discuss_cbox_setup")."` set
   `setupName` = '{$_POST['setupName']}' ,
   `setupRule` = '{$_POST['setupRule']}' ,
   `BoardID` = '{$_POST['BoardID']}'
  where `setupID` = '$setupID'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $setupID;
}

//列出所有tad_discuss_cbox_setup資料
function list_tad_discuss_cbox_setup(){
  global $xoopsDB , $xoopsTpl , $isAdmin;

  $sql = "select * from `".$xoopsDB->prefix("tad_discuss_cbox_setup")."` order by `setupSort`";

  //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql,20,10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $all_content="";
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $setupID , $setupName , $setupRule , $BoardID , $setupSort
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $Board=get_tad_discuss_board($BoardID);

    $all_content[$i]['setupID']=$setupID;
    $all_content[$i]['setupName']="<a href='{$_SERVER['PHP_SELF']}?setupID={$setupID}'>{$setupName}</a>";
    $all_content[$i]['setupRule']=$setupRule;
    $all_content[$i]['BoardID']=$BoardID;
    $all_content[$i]['BoardTitle']=$Board['BoardTitle'];
    $all_content[$i]['setupSort']=$setupSort;
    $i++;
  }

  //刪除確認的JS

  $xoopsTpl->assign('bar' , $bar);
  $xoopsTpl->assign('action' , $_SERVER['PHP_SELF']);
  $xoopsTpl->assign('isAdmin' , $isAdmin);
  $xoopsTpl->assign('all_content' , $all_content);
  //$xoopsTpl->assign('now_op' , 'list_tad_discuss_cbox_setup');
}


//以流水號取得某筆tad_discuss_cbox_setup資料
function get_tad_discuss_cbox_setup($setupID=""){
  global $xoopsDB;
  if(empty($setupID))return;
  $sql = "select * from `".$xoopsDB->prefix("tad_discuss_cbox_setup")."` where `setupID` = '{$setupID}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  return $data;
}

//刪除tad_discuss_cbox_setup某筆資料資料
function delete_tad_discuss_cbox_setup($setupID=""){
  global $xoopsDB , $isAdmin;
  $sql = "delete from `".$xoopsDB->prefix("tad_discuss_cbox_setup")."` where `setupID` = '{$setupID}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

//以流水號秀出某筆tad_discuss_cbox_setup資料內容
function show_one_tad_discuss_cbox_setup($setupID=""){
  global $xoopsDB , $xoopsTpl , $isAdmin;

  if(empty($setupID)){
    return;
  }else{
    $setupID=intval($setupID);
  }



  $sql = "select * from `".$xoopsDB->prefix("tad_discuss_cbox_setup")."` where `setupID` = '{$setupID}' ";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $all=$xoopsDB->fetchArray($result);

  //以下會產生這些變數： $setupID , $setupName , $setupRule , $BoardID
  foreach($all as $k=>$v){
    $$k=$v;
  }



  $xoopsTpl->assign('setupID',$setupID);
  $xoopsTpl->assign('setupName',"<a href='{$_SERVER['PHP_SELF']}?setupID={$setupID}'>{$setupName}</a>");
  $xoopsTpl->assign('setupRule',$setupRule);
  $xoopsTpl->assign('BoardID',$BoardID);

  $xoopsTpl->assign('now_op' , 'show_one_tad_discuss_cbox_setup');
  $xoopsTpl->assign('title' , $setupName);
  $xoopsTpl->assign('setupSort',$setupSort);
}

//自動取得tad_discuss_cbox_setup的最新排序
function tad_discuss_cbox_setup_max_sort(){
  global $xoopsDB;
  $sql = "select max(`setupSort`) from `".$xoopsDB->prefix("tad_discuss_cbox_setup")."`";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($sort)=$xoopsDB->fetchRow($result);
  return ++$sort;
}

/*-----------執行動作判斷區----------*/
$op = empty($_REQUEST['op'])? "":$_REQUEST['op'];
$DiscussID=empty($_REQUEST['DiscussID'])?"":intval($_REQUEST['DiscussID']);
$BoardID=empty($_REQUEST['BoardID'])?"":intval($_REQUEST['BoardID']);
$setupID=empty($_REQUEST['setupID'])?"":intval($_REQUEST['setupID']);

switch($op){
  /*---判斷動作請貼在下方---*/

    //替換資料
    case "replace_tad_discuss_cbox_setup":
    replace_tad_discuss_cbox_setup();
    header("location: {$_SERVER['PHP_SELF']}");
    break;

    //新增資料
    case "insert_tad_discuss_cbox_setup":
    $setupID=insert_tad_discuss_cbox_setup();
    header("location: {$_SERVER['PHP_SELF']}?setupID=$setupID");
    break;

    //更新資料
    case "update_tad_discuss_cbox_setup":
    update_tad_discuss_cbox_setup($setupID);
    header("location: {$_SERVER['PHP_SELF']}");
    break;

    //輸入表格
    case "tad_discuss_cbox_setup_form":
    tad_discuss_cbox_setup_form($setupID);
    list_tad_discuss_cbox_setup();
    break;

    //刪除資料
    case "delete_tad_discuss_cbox_setup":
    delete_tad_discuss_cbox_setup($setupID);
    header("location: {$_SERVER['PHP_SELF']}");
    break;

    //預設動作
    default:
    tad_discuss_cbox_setup_form($setupID);
    list_tad_discuss_cbox_setup();
    break;


  /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
include_once 'footer.php';

?>