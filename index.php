<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2012-10-23
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "tad_discuss_index_tpl.html";
include_once XOOPS_ROOT_PATH."/header.php";
include_once "up_file.php";
/*-----------function區--------------*/


//列出所有tad_discuss_board資料
function list_tad_discuss_board($show_function=1){
	global $xoopsDB , $xoopsModule , $isAdmin, $xoopsUser, $xoopsTpl;

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

	$sql = "select * from `".$xoopsDB->prefix("tad_discuss_board")."` where BoardEnable='1' order by BoardSort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


	$all_content="";

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
		<a href='admin/main.php?op=tad_discuss_board_form&BoardID=$BoardID'><img src='images/edit.png' alt='"._TAD_EDIT."'></a>":"";


    $BoardNum=get_board_num($BoardID);
    $DiscussNum=get_board_num($BoardID,false);
    
		$all_content[$i]['pic']=$pic;
		$all_content[$i]['BoardTitle']=$BoardTitle;
		$all_content[$i]['BoardID']=$BoardID;
		$all_content[$i]['fun']=$fun;
		$all_content[$i]['BoardNum']=sprintf(_MD_TADDISCUS_BOARD_DISCUSS,number_format($BoardNum));
		$all_content[$i]['DiscussNum']=sprintf(_MD_TADDISCUS_ALL_DISCUSS,number_format($DiscussNum));
		$all_content[$i]['list_tad_discuss']=$list_tad_discuss;
		$i++;
	}

	//if(empty($all_content))return "";

	if(file_exists(XOOPS_ROOT_PATH."/modules/tadtools/FooTable.php")){
    include_once XOOPS_ROOT_PATH."/modules/tadtools/FooTable.php";

    $FooTable = new FooTable();
    $FooTableJS=$FooTable->render();
  }

	$xoopsTpl->assign('FooTableJS',$FooTableJS);
	$xoopsTpl->assign('all_content',$all_content);
	
	if($xoopsUser){
	 $xoopsTpl->assign('login',true);
	}else{
	 $xoopsTpl->assign('login',false);
  }
}



//列出所有tad_discuss資料
function list_tad_discuss_short($BoardID=null,$limit=null){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsTpl;

	$andBoardID=(empty($BoardID))?"":"and a.BoardID='$BoardID'";
	$andLimit=($limit > 0)?"limit 0,$limit":"";
	$sql = "select a.*,b.* from ".$xoopsDB->prefix("tad_discuss")." as a left join ".$xoopsDB->prefix("tad_discuss_board")." as b on a.BoardID = b.BoardID where a.ReDiscussID='0' $andBoardID  order by a.LastTime desc $andLimit";

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//$main_data="<table style='width:100%'>";
  $i=0;
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $renum=get_re_num($DiscussID);
    //$show_re_num=empty($renum)?"":sprintf(_MD_TADDISCUS_RE_DISCUSS,$renum);

    $uid_name=XoopsUser::getUnameFromId($uid,1);
    $LastTime=substr($LastTime,0,10);

		$main_data[$i]['LastTime']=$LastTime;
		$main_data[$i]['DiscussID']=$DiscussID;
		$main_data[$i]['BoardID']=$BoardID;
		$main_data[$i]['DiscussTitle']=$DiscussTitle;
		$main_data[$i]['uid_name']=$uid_name;
		$main_data[$i]['renum']=$renum;
    $i++;
	}
	return $main_data;

}


/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$DiscussID=empty($_REQUEST['DiscussID'])?"":intval($_REQUEST['DiscussID']);
$BoardID=empty($_REQUEST['BoardID'])?"":intval($_REQUEST['BoardID']);
$files_sn=empty($_REQUEST['files_sn'])?"":intval($_REQUEST['files_sn']);

$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
$xoopsTpl->assign( "jquery" , get_jquery(true)) ;

switch($op){

	default:
	list_tad_discuss_board($isAdmin);
	break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH.'/footer.php';
?>