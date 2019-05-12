<?php
use XoopsModules\Tadtools\MColorPicker;
use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (會產生一個即時留言簿區塊)
function tad_discuss_cbox($options)
{
    global $xoopsUser, $xoopsDB, $xoTheme;

    //取得本模組編號
    $moduleHandler = xoops_getHandler('module');
    $xoopsModule = $moduleHandler->getByDirname('tad_discuss');
    $module_id = $xoopsModule->mid();

    //取得目前使用者的群組編號
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $groups = $xoopsUser->getGroups();
    } else {
        $uid = 0;
        $groups = XOOPS_GROUP_ANONYMOUS;
    }

    $block['now_uid'] = $uid;
    $block['BoardID'] = $DefBoardID = $options[0];
    $block['apply_rule'] = $apply_rule = $options[5];

    if ($apply_rule) {
        $http = 'http://';
        if (!empty($_SERVER['HTTPS'])) {
            $http = ('on' === $_SERVER['HTTPS']) ? 'https://' : 'http://';
        }
        $url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $all_rule = get_rule();
        foreach ($all_rule as $toBoardID => $patten_arr) {
            foreach ($patten_arr as $patten) {
                $patten_arr = explode('?', $patten);

                if (mb_strpos($url, $patten_arr[0]) and (preg_match("/{$patten_arr[1]}&/", $url) or preg_match("/{$patten_arr[1]}$/", $url))) {
                    $block['BoardID'] = $DefBoardID = $toBoardID;
                    break 2;
                }
            }
        }
    }

    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('forum_read', $DefBoardID, $groups, $module_id)) {
        return;
    }

    $block['jquery_path'] = Utility::get_jquery();

    $form = '';
    if (empty($DefBoardID)) {
        $form = "<select class='form-control' name='BoardID' onChange=\"window.open('" . XOOPS_URL . "/modules/tad_discuss/cbox.php?BoardID='+this.value,'discussCboxMain'); window.open('" . XOOPS_URL . "/modules/tad_discuss/post.php?BoardID='+this.value,'discussCboxForm');\">
            <option value=''>" . _MB_TADDISCUS_ALL_BOARD . '</option>';
        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss_board') . "` WHERE BoardEnable='1' ORDER BY BoardSort";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $selected = ($DefBoardID == $BoardID) ? 'selected' : '';
            $form .= "
            <option value='{$BoardID}' $selected>{$BoardTitle}</option>
            ";
        }

        $form .= '</select>';
    } else {
        $sql = 'select BoardID,BoardTitle from `' . $xoopsDB->prefix('tad_discuss_board') . "` where BoardID='{$DefBoardID}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($BoardID, $BoardTitle) = $xoopsDB->fetchRow($result);
        $form .= "
            <h3><a href='" . XOOPS_URL . "/modules/tad_discuss/discuss.php?BoardID={$BoardID}'>{$BoardTitle}</a></h3>
            ";
    }

    $block['SelectBoard'] = $form;
    $block['height'] = $options[1];
    $block['border_color'] = urlencode($options[2]);
    $block['bg_color'] = urlencode($options[3]);
    $block['font_color'] = urlencode($options[4]);

    $setupRule = str_replace(XOOPS_URL, '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    $setupRule = str_replace('/modules/', '', $setupRule);
    $block['setupRule'] = $setupRule;

    return $block;
}

//區塊編輯函式
function tad_discuss_cbox_edit($options)
{
    global $xoopsDB;

    $MColorPicker = new MColorPicker('.color');
    $MColorPicker->render();

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss_board') . "` WHERE BoardEnable='1' ORDER BY BoardSort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $opt = '';
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $BoardID , $BoardTitle , $BoardDesc , $BoardManager , $BoardEnable
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $selected = ($options[0] == $BoardID) ? 'selected' : '';
        $opt .= "
        <option value='{$BoardID}' $selected>{$BoardTitle}</option>
        ";
    }

    $options5_1 = '1' == $options[5] ? 'checked' : '';
    $options5_0 = '0' == $options[5] ? 'checked' : '';

    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADDISCUS_SELECT_BOARD . "</lable>
            <div class='my-content'>
                <select name='options[0]' class='my-input'>
                    <option value='0'>" . _MB_TADDISCUS_ALL_BOARD . "</option>
                    $opt
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADDISCUS_HEIGHT . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADDISCUS_BORDER_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[2]' value='{$options[2]}' size=8>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADDISCUS_BG_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[3]' value='{$options[3]}' size=8>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADDISCUS_FONT_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[4]' value='{$options[4]}' size=8>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'><a href='" . XOOPS_URL . "/modules/tad_discuss/admin/cbox_setup.php' target='_blank'>" . _MB_TADDISCUS_APPLY_RULE . "</a></lable>
            <div class='my-content'>
                <input type='radio' name='options[5]' value='1' $options5_1>" . _YES . "
                <input type='radio' name='options[5]' value='0' $options5_0>" . _NO . '
            </div>
        </li>
    </ol>';

    return $form;
}

if (!function_exists('get_rule')) {
    function get_rule()
    {
        global $xoopsDB;

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_discuss_cbox_setup') . '` ';
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $all_content = [];
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            //以下會產生這些變數： $setupID , $setupName , $setupRule , $BoardID
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $all_content[$BoardID][] = addslashes($setupRule);
        }

        return $all_content;
    }
}
