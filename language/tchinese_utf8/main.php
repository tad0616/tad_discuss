<?php
xoops_loadLanguage('main', 'tadtools');

define('_MD_TADDISCUS_SMNAME1', '討論區列表');
define('_MD_TADDISCUS_SMNAME2', '所有討論');
define('_MD_TADDISCUS_BOARDMANAGER', '板主');
define('_MD_TADDISCUS_UID', '作者');
define('_MD_TADDISCUS_LAST_RE', '最新回應');
define('_MD_TADDISCUS_DISCUSSTITLE', '主題');
define('_MD_TADDISCUS_DISCUSSRE', '回覆');
define('_MD_TADDISCUS_RE', '我要回覆');
define('_MD_TADDISCUS_INPUT_TITLE', '請輸入標題');
define('_MD_TADDISCUS_DISCUSS_EMPTY', '尚無任何討論主題');
define('_MD_TADDISCUS_ADD_DISCUSS', '新增討論主題');
define('_MD_TADDISCUS_RE_DISCUSS', '%s 篇回文');
define('_MD_TADDISCUS_BOARD_DISCUSS', '%s 個主題');
define('_MD_TADDISCUS_ALL_DISCUSS', '%s 則討論');
define('_MD_TADDISCUS_NEEDLOGIN', '要先登入才能發表。');
define('_MD_TADDISCUS_RELOAD', '重整');
define('_MD_TADDISCUS_NEED_LOGIN', '要先<a href="' . XOOPS_URL . '/user.php" target="_top">登入</a>才能發表。<span onclick="window.open(\'' . XOOPS_URL . '/modules/tad_discuss/cbox.php?BoardID=%s\',\'discussCboxMain\');window.open(\'' . XOOPS_URL . '/modules/tad_discuss/post.php?BoardID=%s\',\'discussCboxForm\');" style="cursor:pointer;color:#3366CC"><img src="images/reload.png" alt="reload" align="absmiddle" hspace=2>' . _MD_TADDISCUS_RELOAD . '</span>');
define('_MD_TADDISCUS_TALK', '說道：');
define('_MD_TADDISCUS_HAD_LIKE', '您已經針對過此篇討論文章表態過囉！');
define('_MD_TADDISCUS_BOARD_UNABLE', '該討論區已關閉，無法留言。');
define('_MD_TADDISCUS_MSG', '留言');
define('_MD_TADDISCUS_ONLY_ROOT', '私密');
define('_MD_TADDISCUS_DEFAULT_PUBLISHER', '訪客');
define('_MD_TADDISCUS_RE_MSG', '回覆 %s 號留言');
define('_MD_TADDISCUS_MSG_MIN', '最少要有 %s 個字，你少輸入了"+(minChr -nowChr)+"個字。');
define('_MD_TADDISCUS_INPUT_CODE', '左圖數字為：');
define('_MD_TADDISCUS_ADD_MSG', '「%s」的留言：');
define('_MD_TADDISCUS_ONLYTO', '這是給「%s」的悄悄話喔！');
define('_MD_TADDISCUS_NEED_BOARDID', '請先從上方選單選擇一個適當的討論區才能發布訊息，或直接按 <button type="button" style="font-size: 80%;border:1px solid gray;">' . _MD_TADDISCUS_DISCUSSRE . '</button> 也行！');
define('_MD_TADDISCUS_BOARD_EMPTY', '目前沒有任何討論版！');
define('_MD_TADDISCUS_THE_DISCUSS_EMPTY', '已無此文！！');
define('_MD_TADDISCUS_FOUND_SPAM', '內含不當詞彙或言論，無法新增資料。');
define('_MD_TADDISCUS_INPUT_BOARDTITLE', '請輸入討論區名稱');
define('_MD_TADDISCUS_ADD_BOARD', '快速建立討論區並設定自動跳轉');
define('_MD_TADDISCUS_SETUPRULE', '網址特徵值（若空值則僅建立討論區，不建立自動跳轉規則）');
define('_MD_TADDISCUS_LOCK', '目前為私密狀態，點我解除私密狀態');
define('_MD_TADDISCUS_UNLOCK', '目前為公開狀態，點我改為私密狀態');
define('_MD_TADDISCUS_SAVE_OK', '討論文章發布成功！');
define('_MD_TADDISCUS_NOBODY', '路過的訪客');

define('_MD_TADDISCUS_TXTLOCK', '鎖定中，滑動解鎖後才能執行送出');
define('_MD_TADDISCUS_TXTUNLOCK', '已可執行送出');
define('_MD_TADDISCUS_CAPTCHA_ERROR', '未通過驗證，無法儲存。');
