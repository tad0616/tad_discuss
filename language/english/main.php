<?php
xoops_loadLanguage('main', 'tadtools');
if (!defined('_TAD_NEED_TADTOOLS')) {
    define('_TAD_NEED_TADTOOLS', "This module needs TadTools module. You can download TadTools from <a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad's web</a>.");
}

define('_MD_TADDISCUS_SMNAME1', 'Discussion List');
define('_MD_TADDISCUS_SMNAME2', 'All discussions');
define('_MD_TADDISCUS_BOARDMANAGER', 'Moderators');
define('_MD_TADDISCUS_UID', 'Author');
define('_MD_TADDISCUS_LAST_RE', 'Latest response');
define('_MD_TADDISCUS_DISCUSSTITLE', 'Subject');
define('_MD_TADDISCUS_DISCUSSRE', 'Reply');
define('_MD_TADDISCUS_RE', 'I want to reply');
define('_MD_TADDISCUS_INPUT_TITLE', 'Please enter the title');
define('_MD_TADDISCUS_DISCUSS_EMPTY', 'There is no discussion of any topic, you can add a message to try ...');
define('_MD_TADDISCUS_ADD_DISCUSS', 'New discussion topic');
define('_MD_TADDISCUS_RE_DISCUSS', '%s articles responded ');
define('_MD_TADDISCUS_BOARD_DISCUSS', '%s topics');
define('_MD_TADDISCUS_ALL_DISCUSS', '%s is discussed');
define('_MD_TADDISCUS_NEEDLOGIN', 'First log in to post.');
define('_MD_TADDISCUS_RELOAD', 'Reloading');
define('_MD_TADDISCUS_NEED_LOGIN', "first <a href='" . XOOPS_URL . "/user.php' target='_top'> Login </a> to post. <span onclick = \' window.open ('" . XOOPS_URL . "/modules/tad_discuss/cbox.php BoardID =%s ',' discussCboxMain? '); window.open ('" . XOOPS_URL . "/modules/tad_discuss/post.php BoardID =%s ','? discussCboxForm '); \'style =' cursor: pointer; color: # 3366CC '> <img src =' images/reload.png 'alt =' reload 'align =' absmiddle 'hspace = 2>" . _MD_TADDISCUS_RELOAD . '</span> ');
define('_MD_TADDISCUS_TALK', 'said:');
define('_MD_TADDISCUS_HAD_LIKE', 'You\'ve had this post for a discussion of articles Attitude Hello!');
define('_MD_TADDISCUS_BOARD_UNABLE', 'The forum has been closed, cannot show message.');
define('_MD_TADDISCUS_MSG', 'Message');
define('_MD_TADDISCUS_ONLY_ROOT', 'Private');
define('_MD_TADDISCUS_DEFAULT_PUBLISHER', 'Visitors');
define('_MD_TADDISCUS_RE_MSG', '%s "No reply" messages');
define('_MD_TADDISCUS_MSG_MIN', "Will need at least %s characters, you minimum input '+ (minChr -nowChr) +' words.");
define('_MD_TADDISCUS_INPUT_CODE', 'Left figures as follows:');
define('_MD_TADDISCUS_ADD_MSG', '"%s" message:');
define('_MD_TADDISCUS_ONLYTO', 'Sorry, this is Private Message for "%s" ');
define('_MD_TADDISCUS_NEED_BOARDID', 'Please select on menu from the top of the drop-down to select an appropriate forum to post messages, or press <button type=\'button\' style=\'font-size: 80%;border:1px solid gray;\'>' . _MD_TADDISCUS_DISCUSSRE . '</button>  also line! <div onclick=\'window.open(\'' . XOOPS_URL . '/modules/tad_discuss/cbox.php?BoardID=%s\',\'discussCboxMain\');window.open(\'' . XOOPS_URL . '/modules/tad_discuss/post.php?BoardID=%s\', \'discussCboxForm\');\' style=\'cursor:pointer;color:#3366CC\'><img src=\'images/reload.png\' alt=\'reload\' align=\'absmiddle\' hspace=2>' . _MD_TADDISCUS_RELOAD . '</div>');
define('_MD_TADDISCUS_BOARD_EMPTY', 'There are no discussion forums!');
define('_MD_TADDISCUS_THE_DISCUSS_EMPTY', 'Is empty!!');
define('_MD_TADDISCUS_FOUND_SPAM', 'It contain inappropriate words or statements, can not add data.');
define('_MD_TADDISCUS_INPUT_BOARDTITLE', 'Please enter the forum name');
define('_MD_TADDISCUS_ADD_BOARD', 'Quickly create forums and set automatically settings');
define('_MD_TADDISCUS_SETUPRULE', 'Web site feature value (if null value only create a discussion, do not set automatically settings)');
define('_MD_TADDISCUS_LOCK', 'Currently private, click to make public');
define('_MD_TADDISCUS_UNLOCK', 'Currently public, click to make private');
define('_MD_TADDISCUS_SAVE_OK', 'Saved successfully!');

define('_MD_TADDISCUS_TXTLOCK', 'Locked: form can\'t be submited');
define('_MD_TADDISCUS_TXTUNLOCK', 'Unlocked: form can be submitted');
define('_MD_TADDISCUS_CAPTCHA_ERROR', 'Did not pass validation, can not be saved.');

define('_MD_TADDISCUS_NOBODY', 'Passing visitors');
