<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2012-10-23
// $Id:$
// ------------------------------------------------------------------------- //

//需加入模組語系
define("_TAD_NEED_TADTOOLS"," 需要 tadtools 模組，可至<a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad教材網</a>下載。");

define("_MD_TADDISCUS_SMNAME1", "討論區列表");
define("_MD_TADDISCUS_SMNAME2", "所有討論");
define("_MD_TADDISCUS_BOARDMANAGER","板主");
define("_MD_TADDISCUS_UID","作者");
define("_MD_TADDISCUS_LAST_RE","最新回應");
define("_MD_TADDISCUS_DISCUSSTITLE","主題");
define("_MD_TADDISCUS_DISCUSSRE","回覆");
define("_MD_TADDISCUS_RE","我要回覆");
define("_MD_TADDISCUS_INPUT_TITLE","請輸入標題");
define("_MD_TADDISCUS_DISCUSS_EMPTY","尚無任何討論主題，您可以新增一篇留言試試∼");
define("_MD_TADDISCUS_ADD_DISCUSS","新增討論主題");
define("_MD_TADDISCUS_RE_DISCUSS","%s 篇回文");
define("_MD_TADDISCUS_BOARD_DISCUSS","%s 個主題");
define("_MD_TADDISCUS_ALL_DISCUSS","%s 則討論");
define("_MD_TADDISCUS_NEEDLOGIN","要先登入才能發表。");
define("_MD_TADDISCUS_RELOAD","重整");
define("_MD_TADDISCUS_NEED_LOGIN","要先<a href='".XOOPS_URL."/user.php' target='_top'>登入</a>才能發表。<span onclick=\"window.open('".XOOPS_URL."/modules/tad_discuss/cbox.php?BoardID=%s','discussCboxMain');window.open('".XOOPS_URL."/modules/tad_discuss/post.php?BoardID=%s','discussCboxForm');\" style='cursor:pointer;color:#3366CC'><img src='images/reload.png' alt='reload' align='absmiddle' hspace=2>"._MD_TADDISCUS_RELOAD."</span>");
define("_MD_TADDISCUS_TALK","說道：");
define("_MD_TADDISCUS_HAD_LIKE","您已經針對過此篇討論文章表態過囉！");
define("_MD_TADDISCUS_BOARD_UNABLE","該討論區已關閉，無法留言。");
define("_MD_TADDISCUS_MSG","留言");
define("_MD_TADDISCUS_ONLY_ROOT","私密");
define("_MD_TADDISCUS_DEFAULT_PUBLISHER","訪客");
define("_MD_TADDISCUS_RE_MSG","回覆 %s 號留言");
define("_MD_TADDISCUS_MSG_MIN","最少要有 %s 個字，你少輸入了'+(minChr -nowChr)+'個字。");
define("_MD_TADDISCUS_INPUT_CODE","左圖數字為：");
define("_MD_TADDISCUS_ADD_MSG","「%s」的留言：");
define("_MD_TADDISCUS_ONLYTO","這是給「%s」的悄悄話喔！");
define("_MD_TADDISCUS_NEED_BOARDID","請先從上方下拉選單選擇一個適當的討論區才能發布訊息，或直接按 <button type='button' style='font-size:11px;border:1px solid gray;'>"._MD_TADDISCUS_DISCUSSRE."</button> 也行！<div onclick=\"window.open('".XOOPS_URL."/modules/tad_discuss/cbox.php?BoardID=%s','discussCboxMain');window.open('".XOOPS_URL."/modules/tad_discuss/post.php?BoardID=%s','discussCboxForm');\" style='cursor:pointer;color:#3366CC'><img src='images/reload.png' alt='reload' align='absmiddle' hspace=2>"._MD_TADDISCUS_RELOAD."</div>");
define('_MD_TADDISCUS_BOARD_EMPTY' , "目前沒有任何討論版！");
define('_MD_TADDISCUS_THE_DISCUSS_EMPTY' , "已無此文！！");
define('_MD_TADDISCUS_FOUND_SPAM' , "內含不當詞彙或言論，無法新增資料。");
?>