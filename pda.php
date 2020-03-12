<?php
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
if (file_exists(__DIR__ . '/mainfile.php')) {
    require_once __DIR__ . '/mainfile.php';
} elseif (dirname(dirname(__DIR__)) . '/mainfile.php') {
    require_once dirname(dirname(__DIR__)) . '/mainfile.php';
}
require_once __DIR__ . '/function.php';
$TadUpFiles = new TadUpFiles('tad_discuss');
/*-----------function區--------------*/

//列出所有tad_discuss_board資料
function list_tad_discuss_board($show_function = 1)
{
    global $xoopsDB, $isAdmin, $xoopsModule, $xoopsUser, $TadUpFiles;

    //取得本模組編號
    $module_id = $xoopsModule->mid();
    $module_name = $xoopsModule->name();

    //$isAdmin=isAdmin();

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $groups = $xoopsUser->getGroups();
    } else {
        $uid = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
    }
    $gpermHandler = xoops_getHandler('groupperm');

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss_board') . "` WHERE BoardEnable='1' ORDER BY BoardSort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $all_content = '';

    if (0 == $xoopsDB->getRowsNum($result)) {
        $all_content .= _MD_TADDISCUS_BOARD_EMPTY;
    }

    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        if (!$gpermHandler->checkRight('forum_read', $BoardID, $groups, $module_id)) {
            continue;
        }

        $TadUpFiles->set_col('BoardID', $BoardID, 1);
        $pic = $TadUpFiles->get_pic_file('thumb'); //thumb 小圖, images 大圖（default）, file 檔案
        $pic = empty($pic) ? 'images/board.png' : $pic;

        $list_tad_discuss = list_tad_discuss_short($BoardID, 7);

        $fun = ($show_function) ? "
    <a href='admin/main.php?op=tad_discuss_board_form&BoardID=$BoardID' rel='external'><i class='icon-wrench'></i></a>" : '';

        $add = "<span class='ui-li-count'><a href='#form_{$BoardID}'><i class='icon-pencil'></i></span></a>";

        //$viewboard="<a href='{$_SERVER['PHP_SELF']}?op=show_board&BoardID={$BoardID}'><i class='icon-chevron-right'></i></a>";

        $BoardNum = get_board_num($BoardID);
        $DiscussNum = get_board_num($BoardID, false);

        $BoardNum = sprintf(_MD_TADDISCUS_BOARD_DISCUSS, number_format($BoardNum));
        $DiscussNum = sprintf(_MD_TADDISCUS_ALL_DISCUSS, number_format($DiscussNum));

        if ($xoopsUser) {
            $form_data .= tad_discuss_form($BoardID, '', $DefDiscussID, 'jqm');
        } else {
            $form_data .= "
        <div data-role='page' id='form_{$BoardID}'>
          <div data-theme='c' data-role='header' data-position='fixed'>
            <h3>{$title}</h3>
            <a href='#index' data-icon='delete' data-iconpos='notext' class='ui-btn-right'>Menu</a>
          </div>
          <div data-role='content'>
            <div id='form-area'>
              " . _MD_TADDISCUS_NEEDLOGIN . '
            </div>
          </div>
        </div>
      ';
        }

        $all_content .= "
        <ul data-role='listview' data-inset='true' data-header-theme='c' data-divider-theme='c'>
        <li data-role='list-divider'>{$fun} <a href='{$_SERVER['PHP_SELF']}?op=show_board&BoardID={$BoardID}' style='color:#3E3E3E'>{$BoardTitle} ({$BoardNum} · {$DiscussNum})</a> {$add}</li>
        {$list_tad_discuss}
        </ul>
    ";
    }

    $login = login_m();

    $page = "
  <!-- index -->
  <div data-role='page' id='index'>
    <div data-theme='c' data-role='header' data-position='fixed'>
      <a href='#login' data-icon='bars' data-iconpos='notext'>Menu</a>
      <h3>{$module_name}</h3>
    </div>
    <div data-role='content'>
      {$all_content}
    </div>
    <div data-role='panel' data-position='left' data-display='push' id='login' data-theme='c'>
      {$login}
    </div>
  </div>
  {$form_data}
  ";

    return $page;
}

//列出所有tad_discuss資料
function list_tad_discuss_short($BoardID = null, $limit = null)
{
    global $xoopsDB, $xoopsModule, $xoopsUser;

    $andBoardID = (empty($BoardID)) ? '' : "and a.BoardID='$BoardID'";
    $andLimit = ($limit > 0) ? "limit 0,$limit" : '';
    $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_discuss') . ' as a left join ' . $xoopsDB->prefix('tad_discuss_board') . " as b on a.BoardID = b.BoardID where a.ReDiscussID='0' $andBoardID  order by a.LastTime desc $andLimit";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //$main_data="<table style='width:100%'>";
    //$i=0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $memberHandler = xoops_getHandler('member');
        $user = $memberHandler->getUser($uid);
        if (is_object($user)) {
            $ts = \MyTextSanitizer::getInstance();
            $pic_avatar = $ts->htmlSpecialChars($user->getVar('user_avatar'));
        }

        $pic_avatar = (empty($pic_avatar) or 'blank.gif' === $pic_avatar) ? 'images/nobody.png' : XOOPS_URL . '/uploads/' . $pic_avatar;

        $renum = get_re_num($DiscussID);
        //$show_re_num=empty($renum)?"":sprintf(_MD_TADDISCUS_RE_DISCUSS,$renum);

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        $LastTime = mb_substr($LastTime, 0, 10);

        $renum = _MD_TADDISCUS_DISCUSSRE . $renum;

        $DiscussTitle = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussTitle);
        $DiscussTitle = str_replace('.gif]', ".gif' hspace=2 align='absmiddle'>", $DiscussTitle);
        $main_data .= "
      <li class='inner-wrap ui-icon-alt'><a href='{$_SERVER['PHP_SELF']}?op=show_one&DiscussID={$DiscussID}&BoardID={$BoardID}'><img src='{$pic_avatar}' alt='{$uid_name}'>
        <h2>{$DiscussTitle}</h2>
        <p style='color:#666'><strong>{$uid_name} · {$LastTime} · {$renum}</strong></p></a>
      </li>
    ";
    }

    return $main_data;
}

//以流水號秀出某筆tad_discuss資料內容
function show_one_tad_discuss($DefDiscussID, $g2p)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $isAdmin, $xoopsModuleConfig;

    //$isAdmin=isAdmin();

    if (empty($DefDiscussID)) {
        return;
    }
    $DefDiscussID = (int) $DefDiscussID;
    $discuss = get_tad_discuss($DefDiscussID);

    //取得本模組編號
    $module_id = $xoopsModule->mid();

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $groups = $xoopsUser->getGroups();
    } else {
        $uid = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
    }

    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('forum_read', $discuss['BoardID'], $groups, $module_id)) {
        header('location:index.php');
    }

    if (0 != $discuss['ReDiscussID']) {
        header("location: {$_SERVER['PHP_SELF']}?DiscussID={$discuss['ReDiscussID']}&BoardID={$discuss['BoardID']}");
    }

    $memberHandler = xoops_getHandler('member');
    $user = $memberHandler->getUser($uid);
    if (is_object($user)) {
        $ts = \MyTextSanitizer::getInstance();
        $uid_name = $ts->htmlSpecialChars($user->name());
        if (empty($uid_name)) {
            $uid_name = $ts->htmlSpecialChars($user->uname());
        }

        $pic = $ts->htmlSpecialChars($user->getVar('user_avatar'));
    }

    $pic = (empty($pic) or 'blank.gif' === $pic) ? 'images/nobody.png' : XOOPS_URL . '/uploads/' . $pic;

    add_tad_discuss_counter($DefDiscussID);

    $js = "
  <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/jqueryCookie/jquery.cookie.js'></script>
  <link rel='stylesheet' type='text/css' media='screen' href='reset.css'>
    <script>
    function like(op,DiscussID){
     if($.cookie('like'+DiscussID)){
        alert('" . _MD_TADDISCUS_HAD_LIKE . "');
     }else{
      $.post('like.php',  {op: op , DiscussID: DiscussID} , function(data) {
        $('#'+op+DiscussID).html(data);
      });

      $.cookie('like'+DiscussID , true , { expires: 7 });
     }
    }


    function delete_tad_discuss_func(DiscussID){
      var sure = window.confirm('" . _TAD_DEL_CONFIRM . "');
      if (!sure)  return;
      location.href=\"{$_SERVER['PHP_SELF']}?op=delete_tad_discuss&ReDiscussID=$DefDiscussID&BoardID={$discuss['BoardID']}&DiscussID=\" + DiscussID;
    }
  </script>";

    $Board = get_tad_discuss_board($discuss['BoardID']);

    $sql = 'select * from ' . $xoopsDB->prefix('tad_discuss') . " where DiscussID='$DefDiscussID' or ReDiscussID='$DefDiscussID' order by ReDiscussID , DiscussDate";

    //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, $xoopsModuleConfig['show_bubble_amount'], 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $discuss_data = '';
    $i = $xoopsModuleConfig['show_bubble_amount'] * ($g2p - 1) + 1;

    $memberHandler = xoops_getHandler('member');
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $DiscussContent = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussContent);
        $DiscussContent = str_replace('.gif]', ".gif' hspace=2 align='absmiddle'>", $DiscussContent);

        $discuss_data = talk_bubble($BoardID, $DiscussID, $DiscussContent, $dir, $uid, $publisher, $DiscussDate, 'return', $Good, $Bad, $width, $onlyTo);

        if ($discuss_data['like']) {
            $like = "
      <div class='like-unlike'>
      <span>{$Bad}</span> <a href='javascript:like(\"unlike\",{$discuss_data['DiscussID']});'><i class='icon-thumbs-down'></i></a> | <a href='javascript:like(\"like\",{$discuss_data['DiscussID']});'><i class='icon-thumbs-up'></i></a> <span>{$Good}</span>
      </div>
      ";
        }

        if ($discuss_data['fun']) {
            $form_data_edit .= tad_discuss_form($discuss_data['BoardID'], $discuss_data['DiscussID'], '', 'jqm');
        }

        $edit = $discuss_data['fun'] ?
        "<div class='edit-area'><a href='javascript:delete_tad_discuss_func({$discuss_data['DiscussID']});'><i class='icon-trash'></i></a> |
        <a href='#form_{$discuss_data['DiscussID']}'><i class='icon-pencil'></i></a></div>" : '';

        $main .= "
    <div class='content-head'><div class='avatar'><img src='{$discuss_data['pic']}'>{$discuss_data['uid_name']}</div>
    <div class='time-mark'><div class='tmwrap'><div class='tmcell'>#{$i}<br>{$discuss_data['DiscussDate']}</div></div></div>
    <div class='clearfix'></div>
    </div>
    <div class='content-box'>
      {$discuss_data['DiscussContent']}
      <div class='content-files'>{$discuss_data['files']}</div>
      <div class='content-footer'>
      {$like}{$edit}
      <div class='clearfix'></div></div>
    </div>";

        $i++;
    }

    if ($xoopsUser) {
        $form_data = tad_discuss_form($BoardID, '', $DefDiscussID);
    } else {
        $form_data = _MD_TADDISCUS_NEEDLOGIN;
    }

    $title = $discuss['DiscussTitle'];

    $title = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $title);
    $title = str_replace('.gif]', ".gif' hspace=2 align='absmiddle'>", $title);

    $page = "
      <!-- showone -->
      <div data-role='page' id='index'>
        <div data-theme='c' data-role='header' data-position='fixed'>
          <a href='{$_SERVER['PHP_SELF']}' data-icon='arrow-l'>Back</a>
          <h3>{$title}</h3>
          <a href='#form' data-icon='edit' data-iconpos='notext' class='ui-btn-right'>Menu</a>
        </div>
        <div data-role='content'>
        $js
          {$main}
          {$bar}
        </div>
      </div>
    <!-- formadd -->
    <div data-role='page' id='form'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <h3>{$title}</h3>
        <a href='#index' data-icon='delete' data-iconpos='notext' class='ui-btn-right'>Menu</a>
      </div>
      <div data-role='content'>
        <div id='form-area'>
          {$form_data}
        </div>
      </div>
    </div>
    {$form_data_edit}

    ";

    return $page;
}

//列出所有tad_discuss資料
function list_tad_discuss_m($DefBoardID = null)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsModuleConfig, $isAdmin;

    //取得本模組編號
    $module_id = $xoopsModule->mid();

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $groups = $xoopsUser->getGroups();
    } else {
        $uid = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
    }
    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('forum_read', $DefBoardID, $groups, $module_id)) {
        header('location:index.php');
    }

    $andBoardID = (empty($DefBoardID)) ? '' : "and a.BoardID='$DefBoardID'";
    $andLimit = ($limit > 0) ? "limit 0,$limit" : '';
    $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_discuss') . ' as a left join ' . $xoopsDB->prefix('tad_discuss_board') . " as b on a.BoardID = b.BoardID where a.ReDiscussID='0' and b.BoardEnable='1' $andBoardID  order by a.LastTime desc";

    //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, $xoopsModuleConfig['show_discuss_amount'], 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $main_data = '';
    $i = 1;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $memberHandler = xoops_getHandler('member');
        $user = $memberHandler->getUser($uid);
        if (is_object($user)) {
            $ts = \MyTextSanitizer::getInstance();
            $pic_avatar = $ts->htmlSpecialChars($user->getVar('user_avatar'));
        }

        $pic_avatar = (empty($pic_avatar) or 'blank.gif' === $pic_avatar) ? 'images/nobody.png' : XOOPS_URL . '/uploads/' . $pic_avatar;

        $renum = get_re_num($DiscussID);
        $renum = empty($renum) ? '0' : $renum;

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 0);
        }

        //最後回應者
        $sql2 = 'select uid from ' . $xoopsDB->prefix('tad_discuss') . " where ReDiscussID='$DiscussID' order by DiscussDate desc limit 0,1";
        $result2 = $xoopsDB->queryF($sql2) or Utility::web_error($sql2);
        //if($isAdmin)die($sql2);
        list($last_uid) = $xoopsDB->fetchRow($result2);
        //if($isAdmin and $BoardID==19)die("<div>$sql2</div>\$last_uid={$last_uid}");
        if (empty($last_uid)) {
            $last_uid_name = $uid_name;
        } else {
            $last_uid_name = \XoopsUser::getUnameFromId($last_uid, 1);
            if (empty($last_uid_name)) {
                $last_uid_name = \XoopsUser::getUnameFromId($last_uid, 0);
            }
        }

        $LastTime = mb_substr($LastTime, 0, 16);
        $DiscussDate = mb_substr($DiscussDate, 0, 16);

        $renum = _MD_TADDISCUS_DISCUSSRE . $renum;

        $DiscussTitle = str_replace('[s', "<img src='" . XOOPS_URL . '/modules/tad_discuss/images/smiles/s', $DiscussTitle);
        $DiscussTitle = str_replace('.gif]', ".gif' hspace=2 align='absmiddle'>", $DiscussTitle);
        $main_data .= "
      <li class='inner-wrap ui-icon-alt'><a href='{$_SERVER['PHP_SELF']}?op=show_one&DiscussID={$DiscussID}&BoardID={$BoardID}'><img src='$pic_avatar' alt='{$uid_name}'>
        <h2>{$DiscussTitle}</h2>
        <p style='color:#666'><strong>{$uid_name} · {$LastTime} · {$renum}</strong></p></a>
      </li>";
    }

    $Board = get_tad_discuss_board($DefBoardID);
    if (!empty($DefBoardID)) {
        $title = $Board['BoardTitle'];
    }

    if ($xoopsUser) {
        $form_data = tad_discuss_form($BoardID, '', $DefDiscussID);
    } else {
        $form_data = _MD_TADDISCUS_NEEDLOGIN;
    }

    if (empty($main_data)) {
        $main_data = '<li>' . _MD_TADDISCUS_DISCUSS_EMPTY . '</li>';
    }

    $login = login_m();

    $data = "
    <!-- index -->
    <div data-role='page' id='index'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <a href='#login' data-icon='bars' data-iconpos='notext'>Menu</a>
        <h3>{$title}</h3>
        <a href='#form' data-icon='edit' data-iconpos='notext' class='ui-btn-right'>add</a>
      </div>
      <div data-role='content'>
        <ul data-role='listview' data-inset='false' data-header-theme='c'>
        {$main_data}
        </ul>
        <div style='margin-top:30px;'>{$bar}</div>
      </div>
      <div data-role='panel' data-position='left' data-display='push' id='login' data-theme='c'>
        {$login}
      </div>
    </div>
    <!-- formadd -->
    <div data-role='page' id='form'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <h3>{$title}</h3>
        <a href='#index' data-icon='delete' data-iconpos='notext' class='ui-btn-right'>Menu</a>
      </div>
      <div data-role='content'>
        <div id='form-area'>
          {$form_data}
        </div>
      </div>
    </div>
    {$form_data_edit}

    ";

    return $data;
}

//tad_discuss編輯表單
function tad_discuss_form($BoardID = '', $DefDiscussID = '', $DefReDiscussID = '', $mode = '')
{
    global $xoopsDB, $xoopsUser, $isAdmin, $xoopsModuleConfig, $xoopsModule, $xoopsTpl, $TadUpFiles;

    if (empty($BoardID)) {
        return;
    }

    //取得本模組編號
    $module_id = $xoopsModule->mid();

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $groups = $xoopsUser->getGroups();
    } else {
        $uid = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
    }

    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('forum_post', $BoardID, $groups, $module_id)) {
        if ('jqm' === $mode) {
            return;
        }

        header('location:pda.php');
    }

    //抓取預設值
    if (!empty($DefDiscussID)) {
        $DBV = get_tad_discuss($DefDiscussID);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定「DiscussID」欄位預設值
    $DiscussID = (!isset($DBV['DiscussID'])) ? $DefDiscussID : $DBV['DiscussID'];

    //設定「ReDiscussID」欄位預設值
    $ReDiscussID = (!isset($DBV['ReDiscussID'])) ? $DefReDiscussID : $DBV['ReDiscussID'];

    //設定「uid」欄位預設值
    $uid = (!isset($DBV['uid'])) ? '' : $DBV['uid'];
    $uid = (is_object($xoopsUser) and empty($uid)) ? $xoopsUser->uid() : $uid;

    //設定「DiscussTitle」欄位預設值
    $DiscussTitle = (!isset($DBV['DiscussTitle'])) ? '' : $DBV['DiscussTitle'];

    //設定「DiscussContent」欄位預設值
    $DiscussContent = (!isset($DBV['DiscussContent'])) ? '' : $DBV['DiscussContent'];

    //設定「DiscussDate」欄位預設值
    $DiscussDate = (!isset($DBV['DiscussDate'])) ? date('Y-m-d H:i:s') : $DBV['DiscussDate'];

    //設定「BoardID」欄位預設值
    $BoardID = (!isset($DBV['BoardID'])) ? $BoardID : $DBV['BoardID'];

    //設定「LastTime」欄位預設值
    $LastTime = (!isset($DBV['LastTime'])) ? date('Y-m-d H:i:s') : $DBV['LastTime'];

    //設定「Counter」欄位預設值
    $Counter = (!isset($DBV['Counter'])) ? '' : $DBV['Counter'];

    $op = (empty($DiscussID)) ? 'insert_tad_discuss' : 'update_tad_discuss';
    //$op="replace_tad_discuss";

    $ID = empty($DiscussID) ? $BoardID : $DiscussID;
    $FormValidator = new FormValidator("#myForm{$ID}", true);
    $formValidator_code = $FormValidator->render('bottomLeft');

    $RE = !empty($DefReDiscussID) ? get_tad_discuss($DefReDiscussID) : [];

    $DiscussTitle = empty($DefReDiscussID) ? "<input type='text' name='DiscussTitle' size='20' value='{$DiscussTitle}' id='DiscussTitle' class='validate[required]' onClick=\"if(this.value=='" . _MD_TADDISCUS_INPUT_TITLE . "')this.value='';\"><br>" : "<input type='hidden' name='DiscussTitle' value='RE:{$RE['DiscussTitle']}'>";

    $Board = get_tad_discuss_board($BoardID);
    if ('0' == $Board['BoardEnable']) {
        redirect_header('pda.php', 3, _MD_TADDISCUS_BOARD_UNABLE);
    }

    //$BoardTitle=(empty($DefDiscussID) and empty($DefReDiscussID))?"<h1><a href='discuss.php?BoardID=$BoardID'>{$Board['BoardTitle']}</a></h1>":"";
    //die('$BoardID:'.$BoardID.',$DefDiscussID:'.$DefDiscussID.',$DefReDiscussID:'.$DefReDiscussID);
    if (!empty($BoardID) and empty($DefDiscussID) and empty($DefReDiscussID)) {
        $BoardTitle = get_board_title($BoardID);
    }
    //die($BoardTitle);
    //$files=show_files("DiscussID" , $DiscussID , true , '' , true , false);

    //$TadUpFiles->set_col("DiscussID" , $DiscussID );
    //files=$TadUpFiles->show_files("upfile",true,NULL,false,false);  //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數

    $TadUpFiles->set_col('DiscussID', $DefDiscussID); //若 $show_list_del_file ==true 時一定要有
    $TadUpFiles->set_thumb($thumb_width = '120px', $thumb_height = '70px', $thumb_bg_color = 'transparent');
    $upform = $TadUpFiles->upform(false, 'upfile', 100, true);

    $DiscussContent = "
  $formValidator_code
  <form data-ajax='false' action='pda.php' method='post' id='myForm{$ID}' class='myForm' enctype='multipart/form-data'>
  $DiscussTitle
  <textarea name='DiscussContent' cols='50' rows=8 id='DiscussContent' class='validate[required,minSize[5]]' style='width:320px; height:150px;font-size: 75%;line-height:150%;border:1px dotted #B0B0B0;'>{$DiscussContent}</textarea>
  <input type='hidden' name='BoardID' value='{$BoardID}'>
  <input type='hidden' name='DiscussID' value='{$DefDiscussID}'>
  <input type='hidden' name='ReDiscussID' value='{$ReDiscussID}'>
  <input type='hidden' name='op' value='{$op}'>
  <span style='display:block;float:right;'><button type='submit' class=''>" . _TAD_SAVE . "</button></span>
  <div class='showfiles'>{$upform}</div></form>";

    $DiscussDate = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($DiscussDate)));

    //$all[0]=talk_bubble($BoardID,$DiscussID,$DiscussContent,$dir,$uid,$DiscussDate,'return',null,null,$width);

    $discuss = get_tad_discuss($DefDiscussID);
    $title = empty($discuss['DiscussTitle']) ? $Board['BoardTitle'] : $discuss['DiscussTitle'];
    $main .= "
    <!-- form -->
    <div data-role='page' id='form_{$ID}'>
      <div data-theme='c' data-role='header' data-position='fixed'>
        <h3>{$title}</h3>
        <a href='#index' data-icon='delete' data-iconpos='notext' class='ui-btn-right'>Menu</a>
      </div>
      <div data-role='content'>
        <div id='form-area'>
          {$DiscussContent}
        </div>
      </div>
    </div>
  ";
    if ('jqm' === $mode) {
        return $main;
    }

    return $DiscussContent;
}

//更新tad_discuss某一筆資料
function update_tad_discuss($DiscussID = '')
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $myts = \MyTextSanitizer::getInstance();
    $_POST['DiscussTitle'] = $myts->addSlashes($_POST['DiscussTitle']);
    $_POST['DiscussContent'] = $myts->addSlashes($_POST['DiscussContent']);

    if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $myip = $_SERVER['REMOTE_ADDR'];
    } else {
        $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $myip = $myip[0];
    }

    $anduid = onlyMine($DiscussID);

    //$now=date('Y-m-d H:i:s',xoops_getUserTimestamp(time()));

    $sql = 'update ' . $xoopsDB->prefix('tad_discuss') . " set
   `DiscussTitle` = '{$_POST['DiscussTitle']}' ,
   `DiscussContent` = '{$_POST['DiscussContent']}' ,
   `LastTime` = now(),
   `FromIP` = '$myip'
  where DiscussID='$DiscussID' $anduid";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $TadUpFiles->set_col('DiscussID', $DiscussID);
    $TadUpFiles->upload_file('upfile', 1024, 120, null, '', true);

    return $DiscussID;
}

//判斷是否為管理員
function isAdmin()
{
    global $xoopsUser, $xoopsModule;
    $isAdmin = false;
    if ($xoopsUser) {
        $module_id = $xoopsModule->mid();
        $isAdmin = $xoopsUser->isAdmin($module_id);
    }

    return $isAdmin;
}

//新增tad_discuss計數器
function add_tad_discuss_counter($DiscussID = '')
{
    global $xoopsDB, $xoopsModule;
    $sql = 'update ' . $xoopsDB->prefix('tad_discuss') . " set `Counter`=`Counter`+1 where `DiscussID`='{$DiscussID}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

function login_m()
{
    global $xoopsDB, $xoopsUser, $isAdmin;

    $admin_menu = $isAdmin ? "<li><a title='Administration Menu' href='" . XOOPS_URL . "/admin.php' rel='external'>Administration Menu</a></li>" : '';
    if ($xoopsUser) {
        $main = "
<ul data-role='listview' data-theme='c' data-divider-theme='c' style='margin-top:-16px;'>
    <li data-icon='delete' style='background-color:#111;'>
      <a href='#' data-rel='close'>User Menu</a>
    </li>
    {$admin_menu}
    <li><a title='View Account' href='" . XOOPS_URL . "/user.php' rel='external'>View Account</a></li>
    <li><a title='Edit Account' href='" . XOOPS_URL . "/edituser.php' rel='external'>Edit Account</a></li>
    <li><a title='Notifications' href='" . XOOPS_URL . "/notifications.php' rel='external'>Notifications</a></li>
    <li><a title='Inbox' href='" . XOOPS_URL . "/viewpmsg.php' rel='external'>Inbox</a></li>
    <li><a title='Logout' href='" . XOOPS_URL . "/user.php?op=logout' rel='external'>Logout</a></li>
</ul>";
    } else {
        $main = "
<ul data-role='listview' data-theme='c' data-divider-theme='c' style='margin-top:-16px;'>
    <li data-icon='delete' style='background-color:#111;'>
      <a href='#' data-rel='close'>User Login</a>
    </li>
    <li>
<form method='post' action='" . XOOPS_URL . "/user.php' data-ajax='false'>
  User:<br>
  <input type='text' maxlength='25' value='' size='12' name='uname'>
   Password:<br>
  <input type='password' maxlength='32' size='12' name='pass'><br>
  <input type='hidden' value='/modules/tad_discuss/pda.php' name='xoops_redirect'>
  <input type='hidden' value='login' name='op'>
  <button type='submit' name='submit' value='Login'>Login</button><br>
</form>
</li>
</ul>
";
    }

    return $main;
}

/*-----------執行動作判斷區----------*/
$op = empty($_REQUEST['op']) ? '' : $_REQUEST['op'];
$DiscussID = empty($_REQUEST['DiscussID']) ? '' : (int) $_REQUEST['DiscussID'];
$BoardID = empty($_REQUEST['BoardID']) ? '' : (int) $_REQUEST['BoardID'];
$files_sn = empty($_REQUEST['files_sn']) ? '' : (int) $_REQUEST['files_sn'];
$g2p = empty($_REQUEST['g2p']) ? '1' : (int) $_REQUEST['g2p'];

switch ($op) {
    //新增資料
    case 'insert_tad_discuss':
        $DiscussID = insert_tad_discuss();
        header("location: {$_SERVER['PHP_SELF']}?op=show_one&DiscussID=$DiscussID&BoardID=$BoardID");
        break;
    //更新資料
    case 'update_tad_discuss':
        update_tad_discuss($DiscussID);
        //$ID=empty($ReDiscussID)?$DiscussID:$ReDiscussID;
        //header("location: {$_SERVER['PHP_SELF']}?op=show_one&DiscussID=$ID&BoardID=$BoardID");
        header("location: {\Xmf\Request::getString('HTTP_REFERER', '', 'SERVER')}");
        break;
    //刪除資料
    case 'delete_tad_discuss':
        delete_tad_discuss($DiscussID);
        header("location: {$_SERVER['PHP_SELF']}?BoardID=$BoardID");
        break;
    //輸入表格
    case 'tad_discuss_form':
        $main = tad_discuss_form($BoardID, $DiscussID, $ReDiscussID);
        break;
    //單一討論
    case 'show_one':
        $main = show_one_tad_discuss($DiscussID, $g2p);
        break;
    //單一討論區
    case 'show_board':
        $main = list_tad_discuss_m($BoardID);
        break;
    //下載檔案
    case 'tufdl':
        $files_sn = isset($_GET['files_sn']) ? (int) $_GET['files_sn'] : '';
        $TadUpFiles->add_file_counter($files_sn, $hash = false);
        exit;
        break;
    default:
        $isAdmin = isAdmin();
        $main = list_tad_discuss_board($isAdmin);
        break;
}

/*-----------秀出結果區--------------*/

echo "
<!DOCTYPE html>
<html lang='" . _LANGCODE . "'>
<head>
  <meta charset='" . _CHARSET . "'>
  <meta name='viewport' content='initial-scale=1.0, user-scalable=no'>
  <title>{$title}</title>
  <link href='http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css' rel='stylesheet' type='text/css'>
  <link href='" . XOOPS_URL . "/modules/tadtools/bootstrap3/css/bootstrap.css' rel='stylesheet' type='text/css'>
  <style>
  /*.ui-header .ui-title {
    margin: 0.6em 2% 0.8em !important;
  }*/
  h1, h2, h3 {
    line-height: 1.1em;
  }
  h2.ui-li-heading {
    white-space: normal;
    font-size: 93.75%;
  }
  /*.ui-li .ui-btn-inner a.ui-link-inherit {
    padding: 0.4em 20px;
  }*/
  #menu a.ui-link-inherit {
    padding: 0.8em 15px 0.8em 40px;
  }
  #menu a.ui-link-inherit img{
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.22);
    padding: 2px;
    background-color: #FFF;
    top: 0.8em;
  }
  .inner-content li{
    background-color: transparent;
    border: 0;
  }
  .inner-body {
    white-space: normal;
  }
  .inner-body img{
    max-width:100% !important;
    height:auto;
  }
  .inner-body .ui-li-desc{
    font-size:1em;
    margin-top: 0.8em;
    white-space: normal;
  }
  .read-more {
    margin-top: 20px;
    text-align: center;
  }
  /*.ui-content {
    padding: 10px 5px !important;
  }*/
  .content-head {}
  .content-box {
    border: 1px solid #B3B3B3;
    border-radius: 5px;
    margin: 10px 0;
    padding: 10px;
    background-color: #FFFFFF;
    word-wrap: break-word;
    word-break: normal;
  }
  .avatar {
    float: left;
    font-weight: bold;
  }
  .avatar img {
    border-radius: 10px;
    box-shadow: 2px 2px 2px 0px #B3B3B3;
    max-width:60px;
    max-height:60px;
    vertical-align:text-bottom;
    margin-right: 5px;
  }
  .time-mark {
    float: right;
  }
  .time-mark .tmwrap{
    display: table;
    height: 60px;
  }
  .time-mark .tmcell{
    font-size: 0.75em;
    display: table-cell;
    vertical-align:bottom;
    text-align:right;
  }
  #form-area {}
  .clearfix:after {
      content: '.';
      display: block;
      height: 0;
      clear: both;
      visibility: hidden;
  }
  .content-files {}
  .content-footer {}
  .edit-area {
    float: left;
    margin-top: 20px;
  }
  .like-unlike{
    float: right;
    margin-top: 20px;
  }
  .clearfix {display: inline-block;}

  /* Hides from IE-mac \*/
  * html .clearfix {height: 1%;}
  .clearfix {display: block;}
  /* End hide from IE-mac */
  </style>

  <script src='" . XOOPS_URL . "/modules/tadtools/jquery/jquery.js' type='text/javascript'></script>
  <script>
    $(document).bind('mobileinit', function(){
      $.mobile.defaultPageTransition = 'slide';
      $.mobile.ajaxEnabled = false;
      $.mobile.ignoreContentEnabled = true;
    });
  </script>
   <script>
    $(document).on('pagecreate', function(){
      $('.myForm>div,.nicEdit-main').css('width','100%');
      $('.showfiles :input').attr('data-role','none');
    });
  </script>
  <script type='text/javascript' src='class/nicEdit.js'></script>
  <script type='text/javascript'>
    bkLib.onDomLoaded(function() { new nicEditor({fullPanel : true, iconsPath : 'class/nicEditorIcons.gif'}).panelInstance('DiscussContent') });
  </script>
  <script src='http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js' type='text/javascript'></script>

</head>
<body>
<!-- Home -->

    {$main}

</body>
</html>";
