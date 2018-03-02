<?php
//區塊主函式 (會產生一個即時留言簿區塊)
function tad_discuss_cbox($options)
{
    global $xoopsUser, $xoopsDB;

    //取得本模組編號
    $modhandler  = xoops_getHandler('module');
    $xoopsModule = $modhandler->getByDirname("tad_discuss");
    $module_id   = $xoopsModule->getVar('mid');

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $uid    = $xoopsUser->getVar('uid');
        $groups = $xoopsUser->getGroups();
    } else {
        $uid    = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
    }

    $block['now_uid']    = $uid;
    $block['BoardID']    = $DefBoardID = $options[0];
    $block['apply_rule'] = $apply_rule = $options[5];

    if ($apply_rule) {
        $url      = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
        $all_rule = get_rule();
        foreach ($all_rule as $toBoardID => $patten_arr) {
            foreach ($patten_arr as $patten) {
                $patten_arr = explode("?", $patten);

                if (strpos($url, $patten_arr[0]) and (preg_match("/{$patten_arr[1]}&/", $url) or preg_match("/{$patten_arr[1]}$/", $url))) {
                    $block['BoardID'] = $DefBoardID = $toBoardID;
                    break 2;
                }
            }
        }
    }

    $gperm_handler = xoops_getHandler('groupperm');
    if (!$gperm_handler->checkRight('forum_read', $DefBoardID, $groups, $module_id)) {
        return;
    }

    //引入TadTools的jquery
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/jquery.php")) {
        redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/jquery.php";

    $block['jquery_path'] = get_jquery();

    $form = "";
    if (empty($DefBoardID)) {
        $form = "<select class='form-control' name='BoardID' onChange=\"window.open('" . XOOPS_URL . "/modules/tad_discuss/cbox.php?BoardID='+this.value,'discussCboxMain'); window.open('" . XOOPS_URL . "/modules/tad_discuss/post.php?BoardID='+this.value,'discussCboxForm');\">
            <option value=''>" . _MB_TADDISCUS_ALL_BOARD . "</option>";
        $sql  = "SELECT * FROM `" . $xoopsDB->prefix("tad_discuss_board") . "` WHERE BoardEnable='1' ORDER BY BoardSort";
        $result = $xoopsDB->query($sql) or web_error($sql);
        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $selected = ($DefBoardID == $BoardID) ? "selected" : "";
            $form     .= "
              <option value='{$BoardID}' $selected>{$BoardTitle}</option>
              ";
        }

        $form .= "</select>";
    } else {
        $sql = "select BoardID,BoardTitle from `" . $xoopsDB->prefix("tad_discuss_board") . "` where BoardID='{$DefBoardID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        list($BoardID, $BoardTitle) = $xoopsDB->fetchRow($result);
        $form .= "
              <h3><a href='" . XOOPS_URL . "/modules/tad_discuss/discuss.php?BoardID={$BoardID}'>{$BoardTitle}</a></h3>
              ";
    }

    $block['SelectBoard']  = $form;
    $block['height']       = $options[1];
    $block['border_color'] = urlencode($options[2]);
    $block['bg_color']     = urlencode($options[3]);
    $block['font_color']   = urlencode($options[4]);

    $setupRule          = str_replace(XOOPS_URL, "", "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI']);
    $setupRule          = str_replace("/modules/", "", $setupRule);
    $block['setupRule'] = $setupRule;

    return $block;
}

//區塊編輯函式
function tad_discuss_cbox_edit($options)
{
    global $xoopsDB;
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/jquery.php";
    $jquery = get_jquery();
    $form   = "
      $jquery
      <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/mColorPicker/javascripts/mColorPicker.js' charset='UTF-8'></script>

      <script type='text/javascript'>
        $('#color').mColorPicker({
          imageFolder: '" . XOOPS_URL . "/modules/tadtools/mColorPicker/images/'
        });
      </script>


      <div>" . _MB_TADDISCUS_SELECT_BOARD . "<select name='options[0]'>
        <option value='0'>" . _MB_TADDISCUS_ALL_BOARD . "</option>";
    $sql    = "SELECT * FROM `" . $xoopsDB->prefix("tad_discuss_board") . "` WHERE BoardEnable='1' ORDER BY BoardSort";
    $result = $xoopsDB->query($sql) or web_error($sql);
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $selected = ($options[0] == $BoardID) ? "selected" : "";
        $form     .= "
        <option value='{$BoardID}' $selected>{$BoardTitle}</option>
        <div></div>
        ";
    }

    $options5_1 = $options[5] == '1' ? "checked" : "";
    $options5_0 = $options[5] == '0' ? "checked" : "";

    $form .= "</select></div>
      <div>" . _MB_TADDISCUS_HEIGHT . "<input type='text' name='options[1]' value='{$options[1]}' size=4> px</div>
      <div>" . _MB_TADDISCUS_BORDER_COLOR . "<input type='text' data-hex='true'  name='options[2]' value='{$options[2]}' size=10></div>
      <div>" . _MB_TADDISCUS_BG_COLOR . "<input type='text' data-hex='true'  name='options[3]' value='{$options[3]}' size=10></div>
      <div>" . _MB_TADDISCUS_FONT_COLOR . "<input type='text' data-hex='true'  name='options[4]' value='{$options[4]}' size=10></div>
      <div><a href='" . XOOPS_URL . "/modules/tad_discuss/admin/cbox_setup.php' target='_blank'>" . _MB_TADDISCUS_APPLY_RULE . "</a>
      <input type='radio' name='options[5]' value='1' $options5_1>" . _YES . "
      <input type='radio' name='options[5]' value='0' $options5_0>" . _NO . "
      </div>
      ";
    return $form;
}

if (!function_exists("get_rule")) {
    function get_rule()
    {
        global $xoopsDB;

        $sql = "SELECT * FROM `" . $xoopsDB->prefix("tad_discuss_cbox_setup") . "` ";
        $result = $xoopsDB->query($sql) or web_error($sql);

        $all_content = "";
        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $setupID , $setupName , $setupRule , $BoardID
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $all_content[$BoardID][] = addslashes($setupRule);
        }

        return $all_content;
    }
}
