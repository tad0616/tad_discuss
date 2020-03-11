<{if $discuss.dir!="top"}>
  <div class="triangle-border talk" style="line-height:150%;margin:26px 0px 16px;background-color:<{if $discuss.onlyTo}>#FEEDD3<{else}>white<{/if}>;">
    <{$discuss.DiscussContent}>
    <br><{$discuss.files}><span style="width:96%;display:block;margin:10px auto 0px;padding:2px 8px;font-size: 75%;color:silver;text-align:right;">
    <{if $discuss.fun}>
    	<a href="javascript:delete_tad_discuss_func(<{$discuss.DiscussID}>);"><img src="images/delete.png" alt="<{$smarty.const._TAD_DEL}>"></a> |
    	<a href="discuss.php?op=tad_discuss_form&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>"><img src="images/edit.png" alt="<{$smarty.const._TAD_EDIT}>"></a>
    <{/if}>

    <{$discuss.DiscussDate}>

    <{if $discuss.like}>
      <div style="float:right;color:#B0B0B0;border:1px solid gray;padding:2px 6px;margin:0px 6px;">
      <span id="unlike<{$discuss.DiscussID}>" style="color:#FF6600"><{$discuss.Bad}></span>
      <img src="images/unlike.png" alt="unlike" align="absmiddle" onClick="like('unlike',<{$discuss.DiscussID}>);"> |
      <img src="images/like.png" alt="like" align="absmiddle" onClick="like('like',<{$discuss.DiscussID}>);">
      <span id="like<{$discuss.DiscussID}>" style="color:#0066FF"><{$discuss.Good}></span></div>
    <{/if}>
    </span><span style="clean:both;display:block;"></span>

    <{if $discuss.show_sig and $discuss.user_sig}>
      <div style="<{if $discuss.sig_style}><{$discuss.sig_style}><{else}>font-size: 75%; color: gray; border-top: 1px dashed gray; padding-top: 10px; margin-top: 10px;<{/if}>">
        <{$discuss.user_sig}>
      </div>
    <{/if}>
  </div>
<{/if}>

  <div style="width:<{$discuss.width}>px;text-align:center;">
    <div style="border:1px solid #cfcfcf;width:<{$discuss.width}>px;height:<{$discuss.width}>px;background: transparent url(<{$discuss.pic}>) no-repeat center;-moz-border-radius: 5px;-khtml-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;position:relative;background-size:cover;<{$discuss.pic_css}>" <{$discuss.pic_js}>>
      <div style="position:absolute;right:6px;bottom:6px;" class="name_shadow"><{$discuss.uid_name}></div>
    </div>
  </div>

<{if $discuss.dir=="top"}>
  <div class="triangle-border top talk" style="line-height:150%;background-color:<{if $discuss.onlyTo}>#FEEDD3<{else}>white<{/if}>;">
    <{$discuss.DiscussContent}>
    <br><{$discuss.files}><span style="width:96%;display:block;margin:10px auto 0px;padding:2px 8px;font-size: 75%;color:silver;text-align:right;">

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


    <{if $discuss.fun}>
    	<a href="javascript:delete_tad_discuss_func(<{$discuss.DiscussID}>);"><img src="images/delete.png" alt="<{$smarty.const._TAD_DEL}>"></a> |
    	<a href="discuss.php?op=tad_discuss_form&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>"><img src="images/edit.png" alt="<{$smarty.const._TAD_EDIT}>"></a>
    <{/if}>

    <{$discuss.DiscussDate}>

    <{if $discuss.like}>
      <div style="float:right;color:#B0B0B0;border:1px solid gray;padding:2px 6px;margin:0px 6px;">
      <span id="unlike<{$discuss.DiscussID}>" style="color:#FF6600"><{$discuss.Bad}></span>
      <img src="images/unlike.png" alt="unlike" align="absmiddle" onClick="like('unlike',<{$discuss.DiscussID}>);"> |
      <img src="images/like.png" alt="like" align="absmiddle" onClick="like('like',<{$discuss.DiscussID}>);">
      <span id="like<{$discuss.DiscussID}>" style="color:#0066FF"><{$discuss.Good}></span></div>
    <{/if}>
    </span><span style="clean:both;display:block;"></span>

    <{if $discuss.show_sig and $discuss.user_sig}>
      <div style="<{if $discuss.sig_style}><{$discuss.sig_style}><{else}>font-size: 75%; color: gray; border-top: 1px dashed gray; padding-top: 10px; margin-top: 10px;<{/if}>">
        <{$discuss.user_sig}>
      </div>
    <{/if}>
  </div>
<{/if}>

<div style="clear:both;"></div>