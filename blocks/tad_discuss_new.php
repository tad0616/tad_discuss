<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2012-10-23
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (最新討論(tad_discuss_new))
function tad_discuss_new($options){
	global $xoopsDB,$xoopsUser;
  include_once XOOPS_ROOT_PATH."/modules/tad_discuss/function_block.php";
  $now_uid=is_object($xoopsUser)?$xoopsUser->getVar('uid'):"0";

	$andLimit=($options[0] > 0)?"limit 0,$options[0]":"";
	$sql = "select a.*,b.* from ".$xoopsDB->prefix("tad_discuss")." as a left join ".$xoopsDB->prefix("tad_discuss_board")." as b on a.BoardID = b.BoardID where a.ReDiscussID='0' order by a.LastTime desc $andLimit";

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$main_data="";
  $i=1;
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
    foreach($all as $k=>$v){
      $$k=$v;
    }


    $renum=block_get_re_num($DiscussID);
    $renum=empty($renum)?"0":$renum;

    $uid_name=XoopsUser::getUnameFromId($uid,1);
    if(empty($uid_name))$uid_name=XoopsUser::getUnameFromId($uid,0);

    //最後回應者
    $sql2 = "select uid from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID' and `DiscussDate` = '$LastTime'";
    $result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    list($last_uid)=$xoopsDB->fetchRow($result2);
    if(empty($last_uid)){
      $last_uid_name=$uid_name;
    }else{
      $last_uid_name=XoopsUser::getUnameFromId($last_uid,1);
      if(empty($last_uid_name))$last_uid_name=XoopsUser::getUnameFromId($last_uid,0);
    }

    $LastTime=substr($LastTime,0,16);
    $DiscussDate=substr($DiscussDate,0,16);

    $class=$i%2?'odd':'even';

    $isPublic=isPublic($onlyTo,$uid,$BoardID);
    $onlyToName=getOnlyToName($onlyTo);
    $DiscussTitle=$isPublic?$DiscussTitle:sprintf(_MB_TADDISCUS_ONLYTO,$onlyToName);

    $block['discuss'][$i]['class']=$class;
    $block['discuss'][$i]['DiscussTitle']=$DiscussTitle;
    $block['discuss'][$i]['renum']=$renum;
    $block['discuss'][$i]['DiscussID']=$DiscussID;
    $block['discuss'][$i]['BoardID']=$BoardID;
    $block['discuss'][$i]['DiscussDate']=$DiscussDate;
    $block['discuss'][$i]['uid_name']=$uid_name;
    $block['discuss'][$i]['LastTime']=$LastTime;
    $block['discuss'][$i]['last_uid_name']=$last_uid_name;
    $block['discuss'][$i]['isPublic']=$isPublic;

		$i++;
	}
  $block['DISCUSSTITLE']=_MB_TADDISCUS_DISCUSSTITLE;
  $block['DISCUSSRE']=_MB_TADDISCUS_DISCUSSRE;
  $block['UID']=_MB_TADDISCUS_UID;
  $block['LAST_RE']=_MB_TADDISCUS_LAST_RE;


	return $block;
}

//區塊編輯函式
function tad_discuss_new_edit($options){

	$form="
	"._MB_TADDISCUS_TAD_DISCUSS_NEW_EDIT_BITEM0."
	<INPUT type='text' name='options[0]' value='{$options[0]}'>
	";
	return $form;
}


//取得回覆數量
if(!function_exists('block_get_re_num')){
  function block_get_re_num($DiscussID=""){
  	global $xoopsDB,$xoopsUser;
    if(empty($DiscussID)) return 0;
  	$sql = "select count(*) from ".$xoopsDB->prefix("tad_discuss")." where ReDiscussID='$DiscussID'";
  	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    list($counter)=$xoopsDB->fetchRow($result);
    return $counter;
  }
}

?>