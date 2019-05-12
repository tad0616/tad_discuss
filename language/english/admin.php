<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2012-10-23
// $Id:$
// ------------------------------------------------------------------------- //
xoops_loadLanguage('admin_common', 'tadtools');
if (!defined('_TAD_NEED_TADTOOLS')) {
define('_TAD_NEED_TADTOOLS', "This module needs TadTools module. You can download TadTools from <a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad's web</a>.");
}

define('_MA_TADDISCUS_BOARDID', 'Forum ID');
define('_MA_TADDISCUS_BOARDTITLE', 'Forum Title');
define('_MA_TADDISCUS_BOARDDESC', 'Forum Description');
define('_MA_TADDISCUS_OFBOARDID', 'Parent Forum');
define('_MA_TADDISCUS_BOARDMANAGER', 'Moderators');
define('_MA_TADDISCUS_BOARDENABLE', 'Enabled');
define('_MA_TAD_DISCUSS_BOARD_FORM', 'Forum settings');
define('_MA_TADDISCUS_BOARDPIC', 'Forum picture');
define('_MA_TADDISCUS_SELECT_DEL', 'To delete the selected file');
define('_MA_TADDISCUS_BOARD_DISCUSS', 'There are %s topics');
define('_MA_TADDISCUS_ALL_DISCUSS', 'with total of %s posts');
define('_MA_TADDISCUS_UPDATE_ERROR', 'Update failed!');
define('_MA_TADDISCUS_SORT_OK', 'Sorting complete!');
define('_MA_TADDISCUS_AMOUNT', 'Number of articles');
define('_MA_TADDISCUS_MOVE', 'Move this version of the article to ...');
define('_MA_TADDISCUS_MERGE', 'Merge');
define('_MA_TADDISCUS_NO_XFORUM', 'XForum is not installed.');
define('_MA_TADDISCUS_NO_NEWBB', 'NewBB is not installed');
define('_MA_TADDISCUS_NO_CBOX', 'Tad CBox (ChatBox) is not installed');
define('_MA_TADDISCUS_IMPORT_FORM_CBOX', 'Start import of ChatBox content into Discussion Forums');
define('_MA_TADDISCUS_CBOX', 'Instant ChatBox');
define('_MA_TADDISCUS_CBOX_DESC', 'Import ChatBox content into Discussion Forums');
define('_MA_TADDISCUS_COPY_DISCUSS', 'Start Copying Content');
define('_MA_TADDISCUS_COPY', 'Copy');
define('_MA_TADDISCUS_COPY_AMOUNT', 'Thread has been transferred / articles');
define('_MA_TADDISCUS_READ_POWER', 'Read permissions');
define('_MA_TADDISCUS_POST_POWER', 'Write permissions');
define('_MA_TADDISCUS_CREATE', 'Create "%s" ');
define('_MA_TADDISCUS_IMPORT', 'Import "%s" Content');
define('_MA_TADDISCUS_POWER_STATUS', 'Transfer of authority');
define('_MA_TADDISCUS_POWER_OK', 'Completed');
define('_MA_TADDISCUS_BATCH_DEL', 'Batch delete');
define('_MA_TADDISCUS_COPY_DISCUSS_FORCE', 'Clear the heavy exchange (applicable after heavy exchange has been imported again)');

define('_MA_TADDISCUS_ADD_BOARD', 'Add board');

define('_MA_TADDISCUS_SETUPNAME', 'The rule name or description');
define('_MA_TADDISCUS_SETUPRULE', 'Web site feature value');
define('_MA_TADDISCUS_TO_BOARDID', 'Will automatically jump to:');
define('_MA_TADDISCUS_RULE_SETUP', 'Instant Chatbox "Auto Jump Forum" setup');
define('_MA_TADDISCUS_NEW_SPAM_KEYWORD', "Enter new keywords, separated by ','");
