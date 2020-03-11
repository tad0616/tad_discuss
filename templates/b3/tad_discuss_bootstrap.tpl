<div class="row">
  <div class="col-sm-2">
    <img src="<{$discuss.pic}>" width=100 class="img-rounded" alt="discuss pic">
    <span style="font-size: 62.5%"><{$discuss.DiscussDate}></span>
    <div><{if $discuss.uid}><a href="<{$xoops_url}>/userinfo.php?uid=<{$discuss.uid}>"><{$discuss.uid_name}></a><{else}><{$discuss.uid_name}><{/if}></div>
    <{if $discuss.fun}>
      <a href="javascript:delete_tad_discuss_func(<{$discuss.DiscussID}>);" class="btn btn-default"><img src="images/delete.png" alt="<{$smarty.const._TAD_DEL}>"></a>
      <a href="discuss.php?op=tad_discuss_form&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>" class="btn btn-default"><img src="images/edit.png" alt="<{$smarty.const._TAD_EDIT}>"></a>
    <{/if}>
  </div>
  <div class="col-sm-10">
    <div class="well talk" style="background-color:<{if $discuss.onlyTo}>#FEEDD3<{else}>white<{/if}>;line-height:150%;">
      <{$discuss.DiscussContent}>
        <div class="text-right">
        <{if $discuss.DiscussID}>
          <{if $discuss.onlyTo}>
            <{if $isAdmin or $now_uid==$discuss.uid}>
              <a href="discuss.php?op=unlock&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>&ReDiscussID=<{$ReDiscussID}>" class="btn btn-danger" title="<{$smarty.const._MD_TADDISCUS_LOCK}>"><img src="images/lock.png" alt="<{$smarty.const._MD_TADDISCUS_LOCK}>"></a>
            <{else}>
              <img src="images/lock.png" alt="<{$smarty.const._MD_TADDISCUS_ONLY_ROOT}>">
            <{/if}>
          <{else}>
            <{if $isAdmin or $now_uid==$discuss.uid}>
              <a href="discuss.php?op=lock&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>&ReDiscussID=<{$ReDiscussID}>" class="btn btn-default" title="<{$smarty.const._MD_TADDISCUS_UNLOCK}>"><img src="images/unlock.png" alt="<{$smarty.const._MD_TADDISCUS_UNLOCK}>"></a>
            <{/if}>
          <{/if}>
        <{/if}>

        <{if $discuss.fun}>
          <a href="javascript:delete_tad_discuss_func(<{$discuss.DiscussID}>);" class="btn btn-default"><img src="images/delete.png" alt="<{$smarty.const._TAD_DEL}>"></a>
          <a href="discuss.php?op=tad_discuss_form&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>" class="btn btn-default"><img src="images/edit.png" alt="<{$smarty.const._TAD_EDIT}>"></a>
        <{/if}>

        <{if $discuss.like}>
          <a href="javascript:like('unlike',<{$discuss.DiscussID}>)" id="unlike<{$discuss.DiscussID}>" class="btn btn-default"><span style="color:#FF6600"><{$discuss.Bad}></span> <img src="images/unlike.png" alt="unlike" align="absmiddle"></a>

          <a href="javascript:like('like',<{$discuss.DiscussID}>)" id="like<{$discuss.DiscussID}>" class="btn btn-default"><img src="images/like.png" alt="like" align="absmiddle" >
          <span style="color:#0066FF"><{$discuss.Good}></span></a>
        <{/if}>
      </div>
      <{$discuss.files}>

      <{if $discuss.show_sig and $discuss.user_sig}>
        <div style="<{if $discuss.sig_style}><{$discuss.sig_style}><{else}>font-size: 75%; color: gray; border-top: 1px dashed gray; padding-top: 10px; margin-top: 10px;<{/if}>">
          <{$discuss.user_sig}>
        </div>
      <{/if}>

    </div>
  </div>
</div>