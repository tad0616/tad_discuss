<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2012-10-23
// $Id:$
// ------------------------------------------------------------------------- //

include_once "../../tadtools/language/{$xoopsConfig['language']}/admin_common.php";
define("_TAD_NEED_TADTOOLS"," 需要 tadtools 模組，可至<a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad教材網</a>下載。");


define("_MA_TADDISCUS_BOARDID","討論區編號");
define("_MA_TADDISCUS_BOARDTITLE","討論區名稱");
define("_MA_TADDISCUS_BOARDDESC","討論區說明");
define("_MA_TADDISCUS_BOARDMANAGER","板主");
define("_MA_TADDISCUS_BOARDENABLE","啟動");
define("_MA_TAD_DISCUSS_BOARD_FORM","討論區設定");
define("_MA_TADDISCUS_BOARDPIC","討論區代表圖");
define("_MA_TADDISCUS_SELECT_DEL","選取欲刪除檔案");
define("_MA_TADDISCUS_BOARD_DISCUSS","有 %s 個主題");
define("_MA_TADDISCUS_ALL_DISCUSS","共 %s 則討論");
define("_MA_TADDISCUS_UPDATE_ERROR","更新失敗！ ");
define("_MA_TADDISCUS_SORT_OK","排序完成！");
define("_MA_TADDISCUS_AMOUNT","文章數量");
define("_MA_TADDISCUS_MOVE","整併此版文章至...");
define("_MA_TADDISCUS_MERGE","整併");
define("_MA_TADDISCUS_NO_XFORUM","沒安裝 Xforum 無需轉移討論文章。");
define("_MA_TADDISCUS_NO_CBOX","沒安裝 Tad Cbox 即時留言簿無需整合。");
define("_MA_TADDISCUS_IMPORT_FORM_CBOX","開始匯入即時留言簿轉換內容");
define("_MA_TADDISCUS_CBOX","即時留言簿");
define("_MA_TADDISCUS_CBOX_DESC","從即時留言簿轉換過來的內容");
define("_MA_TADDISCUS_CBOX_EXIST","已發現<a href='../discuss.php?BoardID=%s'>即時留言簿</a>內容，無須再匯入");
define("_MA_TADDISCUS_CBOX_FORCE_UPDATE","<p style='text-align:center;'><a href='copycbox.php?op=forceUpdate&BoardID=%s' class='btn btn-primary'>我要重新匯入即時留言簿</a></p>");
define("_MA_TADDISCUS_COPY_DISCUSS","開始複製內容");
define("_MA_TADDISCUS_COPY","複製");
define("_MA_TADDISCUS_COPY_AMOUNT","已轉移主題/文章數");
define("_MA_TADDISCUS_READ_POWER","讀取權限");
define("_MA_TADDISCUS_POST_POWER","寫入權限");
define("_MA_TADDISCUS_CREATE","建立「%s」");
define("_MA_TADDISCUS_IMPORT","匯入「%s」內容");
define("_MA_TADDISCUS_POWER_STATUS","權限轉移");
define("_MA_TADDISCUS_POWER_OK","已完成");
define("_MA_TADDISCUS_BATCH_DEL","批次刪除");
define("_MA_TADDISCUS_COPY_DISCUSS_FORCE","清除重匯（適用已匯入後再次重匯）");
define("_MA_TADDISCUS_ADD_BOARD","新增討論區");
define("_MA_TADDISCUS_SETUPID","設定流水號");
define("_MA_TADDISCUS_SETUPNAME","規則名稱或說明");
define("_MA_TADDISCUS_SETUPRULE","當區塊在網址偵測到這些字串時");
define("_MA_TADDISCUS_TO_BOARDID","便自動切換到：");
define("_MA_TADDISCUS_RULE_SETUP","留言簿「自動切換討論區」設定");
define("_MA_TADDISCUS_SETUPSORT","規則優先權");
?>