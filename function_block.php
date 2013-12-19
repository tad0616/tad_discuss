<?php

//悄悄話檢查
if(!function_exists('isPublic')){
  function isPublic($onlyTo="",$publisher_uid=0,$BoardID=NULL){
    global $xoopsUser,$isAdmin;
    if(empty($onlyTo))return true;
    if(empty($xoopsUser))return false;
    if($isAdmin)return true;

    $onlyToArr=explode(",",$onlyTo);
    $now_uid=$xoopsUser->uid();

    if(in_array($now_uid,$onlyToArr))return true;
    if($publisher_uid==$now_uid)return true;

    if($BoardID){
      $board=get_tad_discuss_board($BoardID);
      $BoardManagerArr=explode(',',$board['BoardManager']);
      if(in_array($now_uid,$BoardManagerArr))return true;
    }
    return false;
  }
}


//將悄悄話的對象轉換為真實姓名
if(!function_exists('getOnlyToName')){
  function getOnlyToName($onlyTo=""){
    if(empty($onlyTo))return;
    $onlyToUidArr=explode(',',$onlyTo);
    foreach($onlyToUidArr as $uid){
      $onlyToName=XoopsUser::getUnameFromId($uid,1);
      if(empty($onlyToName))$onlyToName=XoopsUser::getUnameFromId($uid,0);
      $name[]=$onlyToName;
    }
    $nameStr=implode(' , ',$name);
    return $nameStr;
  }
}
?>