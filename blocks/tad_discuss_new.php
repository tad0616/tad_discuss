<?php
//區塊主函式 (最新討論(tad_discuss_new))
function tad_discuss_new($options)
{
    global $xoopsDB, $xoopsUser;
    include_once XOOPS_ROOT_PATH . "/modules/tad_discuss/function_block.php";
    $now_uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : "0";

    $andLimit = ($options[0] > 0) ? "limit 0,$options[0]" : "";
    $sql      = "select a.*,b.* from " . $xoopsDB->prefix("tad_discuss") . " as a left join " . $xoopsDB->prefix("tad_discuss_board") . " as b on a.BoardID = b.BoardID where a.ReDiscussID='0' order by a.LastTime desc $andLimit";

    $result = $xoopsDB->query($sql) or web_error($sql);

    $main_data = "";
    $i         = 1;
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $DiscussID , $ReDiscussID , $uid , $DiscussTitle , $DiscussContent , $DiscussDate , $BoardID , $LastTime , $Counter
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $renum = block_get_re_num($DiscussID);
        $renum = empty($renum) ? "0" : $renum;

        $uid_name = XoopsUser::getUnameFromId($uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($uid, 0);
        }

        //最後回應者
        $sql2           = "select uid from " . $xoopsDB->prefix("tad_discuss") . " where ReDiscussID='$DiscussID' and `DiscussDate` = '$LastTime'";
        $result2        = $xoopsDB->query($sql2) or web_error($sql2);
        list($last_uid) = $xoopsDB->fetchRow($result2);
        if (empty($last_uid)) {
            $last_uid_name = $uid_name;
        } else {
            $last_uid_name = XoopsUser::getUnameFromId($last_uid, 1);
            if (empty($last_uid_name)) {
                $last_uid_name = XoopsUser::getUnameFromId($last_uid, 0);
            }

        }

        $LastTime    = substr($LastTime, 0, 16);
        $DiscussDate = substr($DiscussDate, 0, 16);

        $class = $i % 2 ? 'odd' : 'even';

        $showDiscussTitle = str_replace("[s", "<img src='" . XOOPS_URL . "/modules/tad_discuss/images/smiles/s", $DiscussTitle);
        $showDiscussTitle = str_replace(".gif]", ".gif' hspace=2 align='absmiddle'>", $showDiscussTitle);

        $isPublic     = isPublic($onlyTo, $uid, $BoardID);
        $onlyToName   = getOnlyToName($onlyTo);
        $DiscussTitle = $isPublic ? $DiscussTitle : sprintf(_MB_TADDISCUS_ONLYTO, $onlyToName);

        $block['discuss'][$i]['class']            = $class;
        $block['discuss'][$i]['DiscussTitle']     = $DiscussTitle;
        $block['discuss'][$i]['showDiscussTitle'] = $showDiscussTitle;
        $block['discuss'][$i]['renum']            = $renum;
        $block['discuss'][$i]['DiscussID']        = $DiscussID;
        $block['discuss'][$i]['BoardID']          = $BoardID;
        $block['discuss'][$i]['DiscussDate']      = $DiscussDate;
        $block['discuss'][$i]['uid_name']         = $uid_name;
        $block['discuss'][$i]['LastTime']         = $LastTime;
        $block['discuss'][$i]['last_uid_name']    = $last_uid_name;
        $block['discuss'][$i]['isPublic']         = $isPublic;
        $block['discuss'][$i]['ShowBoardTitle']   = $BoardTitle;
        $block['discuss'][$i]['last_uid']         = $last_uid;
        $block['discuss'][$i]['uid']              = $uid;

        $i++;
    }

    return $block;
}

//區塊編輯函式
function tad_discuss_new_edit($options)
{

    $form = "
	" . _MB_TADDISCUS_TAD_DISCUSS_NEW_EDIT_BITEM0 . "
	<INPUT type='text' name='options[0]' value='{$options[0]}'>
	";
    return $form;
}

//取得回覆數量
if (!function_exists('block_get_re_num')) {
    function block_get_re_num($DiscussID = "")
    {
        global $xoopsDB, $xoopsUser;
        if (empty($DiscussID)) {
            return 0;
        }

        $sql           = "select count(*) from " . $xoopsDB->prefix("tad_discuss") . " where ReDiscussID='$DiscussID'";
        $result        = $xoopsDB->query($sql) or web_error($sql);
        list($counter) = $xoopsDB->fetchRow($result);
        return $counter;
    }
}
