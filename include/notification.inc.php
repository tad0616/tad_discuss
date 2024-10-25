<?php
use XoopsModules\Tadtools\Utility;
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

function get_tad_discuss($category, $item_id)
{
    global $xoopsDB;
    if ('global' === $category) {
        $item['name'] = '';
        $item['url'] = '';

        return $item;
    }

    if ('board' === $category) {
        $sql = 'SELECT `BoardTitle` FROM `' . $xoopsDB->prefix('tad_discuss_board') . '` WHERE `BoardID` =?';
        $result = Utility::query($sql, 'i', [$item_id]) or Utility::web_error($sql, __FILE__, __LINE__);

        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['BoardTitle'];
        $item['url'] = XOOPS_URL . '/modules/tad_discuss/discuss.php?BoardID=' . $item_id;

        return $item;
    }
}
