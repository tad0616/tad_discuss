<?php

//悄悄話檢查
if(!function_exists('isPublic')){
  function isPublic($onlyTo="",$publisher_uid=0){
    global $xoopsUser;
    if(empty($onlyTo))return true;
    $onlyToArr=explode(",",$onlyTo);
    $now_uid=is_object($xoopsUser)?$xoopsUser->getVar('uid'):"0";
    if(in_array($now_uid,$onlyToArr))return true;
    if($publisher_uid==$now_uid)return true;
    return false;
  }
}


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