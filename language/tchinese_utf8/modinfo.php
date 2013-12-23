<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2012-10-23
// $Id:$
// ------------------------------------------------------------------------- //
include_once XOOPS_ROOT_PATH."/modules/tadtools/language/{$xoopsConfig['language']}/modinfo_common.php";

define("_MI_TADDISCUS_NAME","互動討論區");
define("_MI_TADDISCUS_AUTHOR","tad");
define("_MI_TADDISCUS_DESC","簡易的討論留言模組");

define("_MI_TADDISCUS_ADMENU1", "主管理介面");
define("_MI_TADDISCUS_ADMENU2", "論壇轉移");
define("_MI_TADDISCUS_ADMENU3", "權限設定");
define("_MI_TADDISCUS_ADMENU4", "整合留言簿");
define("_MI_TADDISCUS_ADMENU5", "設定留言簿");
define("_MI_TADDISCUS_ADMENU6", "垃圾留言管理");
define("_MI_TADDISCUS_BNAME1","最新討論");
define("_MI_TADDISCUS_BDESC1","最新討論(tad_discuss_new)");
define("_MI_TADDISCUS_BNAME2","最熱門討論");
define("_MI_TADDISCUS_BDESC2","最熱門討論(tad_discuss_hot)");
define("_MI_TADDISCUS_BNAME3","即時留言簿");
define("_MI_TADDISCUS_BDESC3","即時留言簿(tad_discuss_cbox)");
define("_MI_TADDISCUS_DISPLAY_MODE","討論區顯示模式");
define("_MI_TADDISCUS_DISPLAY_MODE_DESC","設定偏好的討論區顯示模式");
define("_MI_TADDISCUS_CONF0_OPT1","左右對話模式");
define("_MI_TADDISCUS_CONF0_OPT2","頭像在左對話模式");
define("_MI_TADDISCUS_CONF0_OPT3","頭像在上對話模式");
define("_MI_TADDISCUS_CONF0_OPT4","Mobile01風格");
define("_MI_TADDISCUS_CONF0_OPT5","清爽風格");
define("_MI_TADDISCUS_CONF0_OPT6","頭像在下對話模式");
define("_MI_TADDISCUS_CONF0_OPT7","BootStrap風格（預設）");
define("_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT","每頁顯示幾篇討論主題");
define("_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT_DESC","設定每頁顯示幾篇討論主題以進行分頁");
define("_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT","每頁顯示幾篇討論對話");
define("_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT_DESC","設定每頁顯示幾篇討論對話以進行分頁");
define('_MI_TADDISCUS_GLOBAL_NOTIFY' , '全局通知');
define('_MI_TADDISCUS_BOARD_NOTIFY' , '討論區通知');
define('_MI_TADDISCUS_GLOBAL_NOTIFY_ME' , '有新討論就通知我');
define('_MI_TADDISCUS_GLOBAL_NOTIFY_SUBJECT' , '[{X_SITENAME}] {X_MODULE} 有新的討論文章');
define('_MI_TADDISCUS_BOARD_NOTIFY_ME' , '該討論區有新討論就通知我');
define('_MI_TADDISCUS_BOARD_NOTIFY_SUBJECT' , '[{X_SITENAME}] {X_MODULE} 指定的討論區下有新的討論');
define("_MI_TADDISCUS_SPAM_KEYWORD","控管詞彙");
define("_MI_TADDISCUS_SPAM_KEYWORD_DESC","將不希望出現在討論區裡的關鍵字列出，以便搜尋或防治（請用,隔開）");
define("_MI_TADDISCUS_SPAM_KEYWORD_DEFAULT","交友,兼差,援交,叫妹,約茶,愛愛,好濕,胸圍,快速賺錢,網約,做愛");

?>