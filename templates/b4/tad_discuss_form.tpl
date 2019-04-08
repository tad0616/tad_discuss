<h2><{$BoardTitle}></h2>

<link rel="stylesheet" type="text/css" media="screen" href="reset.css">
<{if $def_editor!="CKEditor"}>
    <script type="text/javascript" src="class/nicEdit.js"></script>
    <script type="text/javascript">
        bkLib.onDomLoaded(function() { new nicEditor({fullPanel : true, iconsPath : 'class/nicEditorIcons.gif'}).panelInstance('DiscussContent') });
    </script>
<{/if}>
<script src="<{$xoops_url}>/modules/tadtools/multiple-file-upload/jquery.MultiFile.js"></script>

<form action="discuss.php" method="post" id="myForm" enctype="multipart/form-data" role="form">
    <{if $display_mode=="top" || $display_mode=="bottom"}>
        <{includeq file="db:tad_discuss_talk_bubble_vertical.tpl"}>
    <{elseif $display_mode=="mobile"}>
        <{includeq file="db:tad_discuss_mobile.tpl"}>
    <{elseif $display_mode=="clean"}>
        <{includeq file="db:tad_discuss_clean.tpl"}>
    <{elseif $display_mode=="default"}>
        <{includeq file="db:tad_discuss_talk_bubble.tpl"}>
    <{else}>
        <{includeq file="db:tad_discuss_bootstrap.tpl"}>
    <{/if}>
    <input type="hidden" name="uid" value="<{$uid}>">
</form>