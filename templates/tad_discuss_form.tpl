<{if $BoardTitle}>
    <h2><{$BoardTitle}></h2>
<{else}>
    <h2 class="sr-only visually-hidden">No Discuss Title</h2>
<{/if}>
<link rel="stylesheet" type="text/css" media="screen" href="reset.css">

<script src="<{$xoops_url}>/modules/tadtools/multiple-file-upload/jquery.MultiFile.js"></script>

<form action="discuss.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <{if $display_mode=="top" || $display_mode=="bottom"}>
        <{include file="db:tad_discuss_talk_bubble_vertical.tpl"}>
    <{elseif $display_mode=="mobile"}>
        <{include file="db:tad_discuss_mobile.tpl"}>
    <{elseif $display_mode=="clean"}>
        <{include file="db:tad_discuss_clean.tpl"}>
    <{elseif $display_mode=="default"}>
        <{include file="db:tad_discuss_talk_bubble.tpl"}>
    <{else}>
        <{include file="db:tad_discuss_bootstrap.tpl"}>
    <{/if}>
    <input type="hidden" name="uid" value="<{$uid}>">
</form>