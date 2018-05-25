<?php
include_once XOOPS_ROOT_PATH . '/modules/tadtools/language/' . $xoopsConfig['language'] . '/modinfo_common.php';

define('_MI_TADDISCUS_NAME', '互動討論區');
define('_MI_TADDISCUS_AUTHOR', 'tad');
define('_MI_TADDISCUS_DESC', '簡易的討論留言模組');

define('_MI_TADDISCUS_ADMENU1', '主管理介面');
define('_MI_TADDISCUS_ADMENU2', '轉移 Xforum');
define('_MI_TADDISCUS_ADMENU3', '權限設定');
define('_MI_TADDISCUS_ADMENU4', '整合留言簿');
define('_MI_TADDISCUS_ADMENU5', '跳轉設定');
define('_MI_TADDISCUS_ADMENU6', '垃圾留言管理');
define('_MI_TADDISCUS_ADMENU7', '轉移 newbb');
define('_MI_TADDISCUS_BNAME1', '最新討論');
define('_MI_TADDISCUS_BDESC1', '最新討論(tad_discuss_new)');
define('_MI_TADDISCUS_BNAME2', '最熱門討論');
define('_MI_TADDISCUS_BDESC2', '最熱門討論(tad_discuss_hot)');
define('_MI_TADDISCUS_BNAME3', '即時留言簿');
define('_MI_TADDISCUS_BDESC3', '即時留言簿(tad_discuss_cbox)');
define('_MI_TADDISCUS_DISPLAY_MODE', '討論區顯示模式');
define('_MI_TADDISCUS_DISPLAY_MODE_DESC', '設定偏好的討論區顯示模式');
define('_MI_TADDISCUS_CONF0_OPT1', '左右對話模式');
define('_MI_TADDISCUS_CONF0_OPT2', '頭像在左對話模式');
define('_MI_TADDISCUS_CONF0_OPT3', '頭像在上對話模式');
define('_MI_TADDISCUS_CONF0_OPT4', 'Mobile01風格');
define('_MI_TADDISCUS_CONF0_OPT5', '清爽風格');
define('_MI_TADDISCUS_CONF0_OPT6', '頭像在下對話模式');
define('_MI_TADDISCUS_CONF0_OPT7', 'BootStrap風格（預設）');
define('_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT', '每頁顯示幾篇討論主題');
define('_MI_TADDISCUS_SHOW_DISCUSS_AMOUNT_DESC', '設定每頁顯示幾篇討論主題以進行分頁');
define('_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT', '每頁顯示幾篇討論對話');
define('_MI_TADDISCUS_SHOW_BUBBLE_AMOUNT_DESC', '設定每頁顯示幾篇討論對話以進行分頁');
define('_MI_TADDISCUS_GLOBAL_NOTIFY', '全局通知');
define('_MI_TADDISCUS_BOARD_NOTIFY', '討論區通知');
define('_MI_TADDISCUS_GLOBAL_NOTIFY_ME', '有新討論就通知我');
define('_MI_TADDISCUS_GLOBAL_NOTIFY_SUBJECT', '[{X_SITENAME}] {X_MODULE} 有新的討論文章');
define('_MI_TADDISCUS_BOARD_NOTIFY_ME', '該討論區有新討論就通知我');
define('_MI_TADDISCUS_BOARD_NOTIFY_SUBJECT', '[{X_SITENAME}] {X_MODULE} 指定的討論區下有新的討論');
define('_MI_TADDISCUS_SPAM_KEYWORD', '控管詞彙');
define('_MI_TADDISCUS_SPAM_KEYWORD_DESC', '將不希望出現在討論區裡的關鍵字列出，以便搜尋或防治（請用,隔開）');
define('_MI_TADDISCUS_SPAM_KEYWORD_DEFAULT', '交友,兼差,援交,叫妹,約茶,愛愛,好濕,胸圍,快速賺錢,網約,做愛,寫真,一夜情,約妹,叫小姐,身材,狂歡,外約,學生妹,送茶,肉棒,馬眼,口爆,顏射,小穴穴,品茶,美眉,美 眉,小模,情人,淫窖,激情,性福,美少女,大奶,好茶,雞排,茶,外送,茶坊,兼職,加盟,在家網路創業,拆裝潢,到府估價,抓漏,正妹,援/交,女/人,外/送');
define('_MI_TADDISCUS_DISPLAY_FAST_SETUP', '在即時留言簿啟用快速新增討論區功能');
define('_MI_TADDISCUS_DISPLAY_FAST_SETUP_DESC', '此功能僅管理員可見，可供管理員快速新增討論區及新增自動轉向設定');
define('_MI_TADDISCUS_DISPLAY_NUMBER', '討論區首頁顯示留言數');
define('_MI_TADDISCUS_DISPLAY_NUMBER_DESC', '討論區首頁各討論區列表下要呈現幾篇留言？');

define('_MI_TADDISCUS_SHOW_SIG', '是否使用簽名檔？');
define('_MI_TADDISCUS_SHOW_SIG_DESC', '簽名檔請至個人帳號中編輯');
define('_MI_TADDISCUS_SIG_STYLE', '簽名檔樣式');
define('_MI_TADDISCUS_SIG_STYLE_DESC', '可以自行用CSS語法調整簽名樣式外觀');
define('_MI_TADDISCUS_SHOW_LIKE', '顯示按讚按鈕');
define('_MI_TADDISCUS_SHOW_LIKE_DESC', '可設定是否顯示每篇文章按讚的工具');

define('_MI_TADDISCUS_DEF_EDITOR', '預設編輯器');
define('_MI_TADDISCUS_DEF_EDITOR_DESC', '設定使用的編輯器');
