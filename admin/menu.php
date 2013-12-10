<?php
$adminmenu = array();
$icon_dir=substr(XOOPS_VERSION,6,3)=='2.6'?"":"images/";

$i = 1;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_HOME ;
$adminmenu[$i]['link'] = 'admin/index.php' ;
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_HOME_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/home.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU1;
$adminmenu[$i]['link'] = "admin/main.php";
$adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU1 ;
$adminmenu[$i]['icon'] = "images/admin/chat.png";

$i++;
$adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU3;
$adminmenu[$i]['link'] = "admin/groupperm.php";
$adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU3 ;
$adminmenu[$i]['icon'] = "images/admin/group_full_security_32.png";

$i++;
$adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU2;
$adminmenu[$i]['link'] = "admin/copybb.php";
$adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU2 ;
$adminmenu[$i]['icon'] = "images/admin/copy_doc.png";

$i++;
$adminmenu[$i]['title'] = _MI_TADDISCUS_ADMENU4;
$adminmenu[$i]['link'] = "admin/copycbox.php";
$adminmenu[$i]['desc'] = _MI_TADDISCUS_ADMENU4 ;
$adminmenu[$i]['icon'] = "images/admin/copy_doc.png";

$i++;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon'] = 'images/admin/about.png';
?>
