<div style="border:1px solid #cfcfcf;margin:4px;padding:10px;background:<{if $discuss.onlyTo}>#FEEDD3<{else}>white<{/if}>;-moz-border-radius: 5px;-khtml-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;">

  <div style="border:1px solid #cfcfcf;float:right; width:<{$discuss.width}>px;height:<{$discuss.width}>px;background: transparent url(<{$discuss.pic}>) no-repeat center;background-size:cover;margin:0px 0px 6px 6px;z-index:1000;position:relative;<{$discuss.pic_css}>" <{$discuss.pic_js}>>
  </div>

  <div><{$discuss.uid_name}> <{$smarty.const._MD_TADDISCUS_TALK}></div>

  <div style="font-size: 80%;margin:5px 0px;color:#CC0000">
    <{$discuss.DiscussDate}>
  </div>

  <div class="talk" style="line-height:160%;margin:10px 0px;text-align:justify;z-index:1;position:relative;">
    <{$discuss.DiscussContent}>
  </div>

  <{$discuss.files}>

  <{if $discuss.like}>
    <div style="float:right;color:#B0B0B0;border:1px solid gray;padding:2px 6px;margin:0px 6px;">
      <span id="unlike<{$discuss.DiscussID}>" style="color:#FF6600"><{$discuss.Bad}></span>
      <img src="images/unlike.png" alt="unlike" align="absmiddle" onClick="like('unlike',<{$discuss.DiscussID}>);"> |
      <img src="images/like.png" alt="like" align="absmiddle" onClick="like('like',<{$discuss.DiscussID}>);">
      <span id="like<{$discuss.DiscussID}>" style="color:#0066FF"><{$discuss.Good}></span>

    </div>
  <{/if}>

  <{if $discuss.fun}>
    <div style="text-align:left;">

      <{if $discuss.DiscussID}>
        <{if $discuss.onlyTo}>
          <{if $isAdmin or $now_uid==$discuss.uid}>
            <a href="discuss.php?op=unlock&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>&ReDiscussID=<{$ReDiscussID}>" title="<{$smarty.const._MD_TADDISCUS_LOCK}>"><img src="images/lock.png" alt="<{$smarty.const._MD_TADDISCUS_LOCK}>"></a>
          <{else}>
            <img src="images/lock.png" alt="<{$smarty.const._MD_TADDISCUS_ONLY_ROOT}>">
          <{/if}>
        <{else}>
          <{if $isAdmin or $now_uid==$discuss.uid}>
            <a href="discuss.php?op=lock&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>&ReDiscussID=<{$ReDiscussID}>" title="<{$smarty.const._MD_TADDISCUS_UNLOCK}>"><img src="images/unlock.png" alt="<{$smarty.const._MD_TADDISCUS_UNLOCK}>"></a>
          <{/if}>
        <{/if}>
        |
      <{/if}>


    	<a href="javascript:delete_tad_discuss_func(<{$discuss.DiscussID}>);"><img src="images/delete.png" alt="<{$smarty.const._TAD_DEL}>"></a> |
    	<a href="discuss.php?op=tad_discuss_form&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>"><img src="images/edit.png" alt="<{$smarty.const._TAD_EDIT}>"></a>
    </div>
  <{/if}>

  <{if $discuss.show_sig and $discuss.user_sig}>
    <div style="clean:both;display:block;height:20px;"></div>
    <div style="<{if $discuss.sig_style}><{$discuss.sig_style}><{else}>font-size: 75%; color: gray; border-top: 1px dashed gray; padding: 10px 0px; margin: 10px 0px;<{/if}>">
      <{$discuss.user_sig}>
    </div>
  <{/if}>
</div>