<?php
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/modules/tadtools/TadUpFiles.php';
$TadUpFiles = new TadUpFiles('tad_discuss');
/*-----------function區--------------*/

if ('mkpic' === $_GET['mode']) {
    if ('1' == $xoopsModuleConfig['security_images']) {
        $num = mt_rand(100, 999);
        $_SESSION['security_code'] = $num;
        mkpic($num);
        exit;
    }
}

//tad_discuss編輯表單
function tad_discuss_form($BoardID = '', $DiscussID = '', $ReDiscussID = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $xoopsModule, $TadUpFiles, $isAdmin;

    if (empty($BoardID)) {
        if ($isAdmin and '1' == $xoopsModuleConfig['display_fast_setup']) {

            $FormValidator = new FormValidator('#myForm', true);
            $formValidator_code = $FormValidator->render();

            $boardTitle = _MD_TADDISCUS_INPUT_BOARDTITLE;
            $setupRule = $_GET['setupRule'];

            $main = "
            <body class='error_bg'>
              <h3 style='display: none;'>POST Form</h3>
              <div class='error_bg' style='color:#6C0000;font-size: 75%;line-height:150%;padding:10px 10px;'>
              " . sprintf(_MD_TADDISCUS_NEED_BOARDID, $BoardID, $BoardID) . "
                $formValidator_code
                <form action='post.php' method='post'>
                  <input type='text' name='boardTitle' id='boardTitle' style='width:100%'; class='validate[required]' value='$boardTitle' onClick=\"if(this.value=='" . _MD_TADDISCUS_INPUT_BOARDTITLE . "'){this.value='';}\">
                  <input type='text' name='setupRule' id='setupRule' style='width:100%'; class='validate[required]' value='$setupRule'>
                  <input type='hidden' name='op' value='fast_add_borard'>
                  <div>" . _MD_TADDISCUS_SETUPRULE . "</div>
                  <input type='submit' value='" . _MD_TADDISCUS_ADD_BOARD . "'>
                </form>
              </div>
            </body>";
        } else {
            $main = "
            <body class='error_bg'>
              <h3 style='display: none;'>POST Form</h3>
              <div class='error_bg' style='color:#6C0000;font-size: 92%;line-height:180%;padding:20px 10px;'>
              " . sprintf(_MD_TADDISCUS_NEED_BOARDID, $BoardID, $BoardID) . '
              </div>
            </body>';
        }

        return $main;
        exit;
    }

    $TadUpFiles->set_col('DiscussID', $DiscussID); //若 $show_list_del_file ==true 時一定要有
    $upform = $TadUpFiles->upform(false, 'upfile', 100, false);

    //取得本模組編號
    $module_id = $xoopsModule->mid();

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $groups = $xoopsUser->getGroups();
        $name = $xoopsUser->name();
        if (!empty($name)) {
            $publisher = $name;
        } else {
            $publisher = $xoopsUser->uname();
        }
    } else {
        $uid = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
        $publisher = _MD_TADDISCUS_DEFAULT_PUBLISHER;
    }

    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('forum_post', $BoardID, $groups, $module_id)) {
        $main = "
        <body>
        <h1 style=\"display:none;\">Need Login</h1>
        <div class='need_login'>" . sprintf(_MD_TADDISCUS_NEED_LOGIN, $BoardID, $BoardID) . '</div>
        </body>';

        return $main;
    }

    $publisher_txt = (!empty($ReDiscussID)) ? "<div class='remsg'>" . sprintf(_MD_TADDISCUS_RE_MSG, $ReDiscussID) . '</div>' : "<div class='remsg'>" . sprintf(_MD_TADDISCUS_ADD_MSG, $publisher) . '</div>';

    $js = $smile_all = '';
    $_SESSION['cbox_use_smile'] = 1;

    if ('1' == $_SESSION['cbox_use_smile']) {
        //找出表情圖
        $dir = 'images/smiles/';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (false !== ($file = readdir($dh))) {
                    if ('.' === mb_substr($file, 0, 1) or 's' !== mb_substr($file, 0, 1)) {
                        continue;
                    }

                    $key = mb_substr($file, 1, -4);
                    $smile_gif[$key] = $file;
                }
                closedir($dh);
            }
        }

        sort($smile_gif);
        $smile_li = '';
        foreach ($smile_gif as $file) {
            $smile_li .= "<li><img src='" . XOOPS_URL . "/modules/tad_discuss/{$dir}{$file}'  width='19' height='19' alt='{$file}' onClick='insertAtCursor(document.myForm.DiscussContent,\"[{$file}]\")' ></li>\n";
        }

        $js = "
        $(document).ready(function() {
          var w=$('.carousel').width();
          if(w > 600) w=600;
          var smile_num=Math.floor((w-60)/19);
          $(\".jCarouselLite\").jCarouselLite({
            btnNext: \".next\",
            btnPrev: \".prev\",
            visible: smile_num,
            scroll: smile_num
          });
        });

        function insertAtCursor(myField, myValue) {
          //IE support
          if (document.selection) {
            myField.focus();
            sel = document.selection.createRange();
            sel.text = myValue;
          } else if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
          } else {
            myField.value += myValue;
          }
        }";

        $smile_all = "
        <tr><td colspan=2 id='smile'>
          <div class='carousel' style='height:24px;'>
            <a href='#' class='prev'>&nbsp</a>
            <div class='jCarouselLite' align=center><ul>$smile_li</ul></div>
            <a href='#' class='next'>&nbsp</a>
            <div class='clear'></div>
          </div>
        </td></tr>";
    }

    $jquery = Utility::get_jquery();

    $DiscussTitleForm = empty($ReDiscussID) ? "
    <tr>
      <td colspan=2><input type='text' name='DiscussTitle' value='' style='width:100%' placeholder='" . _MD_TADDISCUS_INPUT_TITLE . "' class='form-control validate[required]'>
      </td>
    </tr>" : '';

    $main = "
      <body bgcolor='#FCFCFC'>
      {$jquery}
      <script type='text/javascript' src='" . XOOPS_URL . "/modules/tad_discuss/class/jquery.jcarousellite.min.js'></script>

      <script type='text/javascript'>
      $js
      var minChr = 4;
      var nowChr = 0;
      function count(value){
         nowChr = value.length;
      }
      function check(){
          if(nowChr < minChr){
            alert('" . sprintf(_MD_TADDISCUS_MSG_MIN, $xoopsModuleConfig['input_min']) . "');
            return;
          }
          document.myForm.submit();
      }
      </script>
      <h3 style='display:none;'>Post Form</h3>
      <div class='cbox'>
      <form action='{$_SERVER['PHP_SELF']}' method='post' name='myForm' id='myForm' enctype='multipart/form-data' >
      <table class='cbox_tbl' style='width:98%'>
      <tr>
        <td class='col'>{$publisher_txt}
        <!--div style='font-size: 62.5%'>\$BoardID=$BoardID,\$DiscussID=$DiscussID,\$ReDiscussID=$ReDiscussID</div-->
        </td>
        <td>
          <img src='images/reload.png' alt='reload' align='absmiddle' hspace=2 onclick=\"window.open('" . XOOPS_URL . "/modules/tad_discuss/cbox.php?BoardID={$BoardID}','discussCboxMain');window.open('" . XOOPS_URL . "/modules/tad_discuss/post.php?BoardID={$BoardID}','discussCboxForm');\">
          <span onclick=\"window.open('" . XOOPS_URL . "/modules/tad_discuss/cbox.php?BoardID={$BoardID}','discussCboxMain');window.open('" . XOOPS_URL . "/modules/tad_discuss/post.php?BoardID={$BoardID}','discussCboxForm');\" style='cursor:pointer;color:#3366CC'>" . _MD_TADDISCUS_RELOAD . "</span>
      </td>
      </tr>

      $DiscussTitleForm


      <tr>
        <td class='col' colspan=2>
          <textarea name='DiscussContent' id='DiscussContent' style='width:100%' onkeyUp='count(this.value)' onClick=\"if(this.value=='" . _MD_TADDISCUS_MSG . "')this.value=''\">" . _MD_TADDISCUS_MSG . "</textarea>
        </td>
      </tr>

      <tr>
        <td class='col' colspan=2 style='text-align:right;'>
          $security_images
          <input type='checkbox' name='only_root' value='1'><span style='font-size:80%'>" . _MD_TADDISCUS_ONLY_ROOT . "</span>
          <input type='hidden' name='BoardID' value='{$BoardID}'>
          <input type='hidden' name='DiscussID' value='{$DiscussID}'>
          <input type='hidden' name='ReDiscussID' value='{$ReDiscussID}'>
          <input type='hidden' name='publisher' value='{$publisher}'>
          <input type='hidden' name='op' value='insert_tad_discuss'>

          <input type='button' value='" . _TAD_SAVE . "' style='height:100%' onClick='check();'>
          $upform
        </td>
      </tr>

      $smile_all

      </table>
      </form></div>
      </body>";

    return $main;
}

function mkpic($num = 0)
{
    header('Content-type: image/png');
    $im = @imagecreatetruecolor(28, 18);
    $text_color = imagecolorallocate($im, 255, 255, 255);
    imagestring($im, 2, 5, 2, $num, $text_color);
    imagepng($im);
    imagedestroy($im);
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$BoardID = system_CleanVars($_REQUEST, 'BoardID', 0, 'int');
$DiscussID = system_CleanVars($_REQUEST, 'DiscussID', 0, 'int');
$ReDiscussID = system_CleanVars($_REQUEST, 'ReDiscussID', 0, 'int');

switch ($op) {
    //新增資料
    case 'insert_tad_discuss':
        insert_tad_discuss(true);
        header("location: {$_SERVER['PHP_SELF']}?op=reload&BoardID=$BoardID");
        exit;

    case 'fast_add_borard':
        $BoardID = insert_tad_discuss_cbox_setup($_POST['boardTitle'], $_POST['setupRule'], $_POST['boardTitle']);
        header("location: {$_SERVER['PHP_SELF']}?op=reload&BoardID=$BoardID");
        exit;

    default:
        $main = tad_discuss_form($BoardID, $DiscussID, $ReDiscussID);
        break;
}

/*-----------秀出結果區--------------*/
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='" . _CHARSET . "'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Post Form</title>
  <link rel='stylesheet' type='text/css' media='screen' href='" . XOOPS_URL . "/modules/tad_discuss/cbox.css'>
";

if ('reload' === $op) {
    echo "<script type='text/javascript'>
    window.open('" . XOOPS_URL . "/modules/tad_discuss/cbox.php?BoardID={$BoardID}','discussCboxMain');
    window.open('" . XOOPS_URL . "/modules/tad_discuss/post.php?BoardID={$BoardID}','discussCboxForm');
    </script>";
}

if (!empty($_GET['msg'])) {
    echo "<script type='text/javascript'>alert('{$_GET['msg']}')</script>";
}

echo "\n</head>";
echo $main;
echo "\n</html>";
