<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die("XOOPS root path not defined");
}

function get_tad_discuss($category, $item_id)
{
    global $xoopsDB;
    if ($category == 'global') {
        $item['name'] = '';
        $item['url']  = '';
        return $item;
    }

    if ($category == 'board') {
        $sql          = 'SELECT BoardTitle FROM ' . $xoopsDB->prefix('tad_discuss_board') . ' WHERE BoardID = ' . $item_id;
        $result       = $xoopsDB->query($sql); // TODO: error check
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['BoardTitle'];
        $item['url']  = XOOPS_URL . '/modules/tad_discuss/discuss.php?BoardID=' . $item_id;
        return $item;
    }
}
