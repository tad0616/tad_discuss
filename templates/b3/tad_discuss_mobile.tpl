<table>
  <tr>
    <td style="border:1px solid #cfcfcf; width:120px;height:120px;background: transparent url(<{$discuss.pic}>) no-repeat center;background-size:cover;<{$discuss.pic_css}>" <{$discuss.pic_js}>>
    </td>
    <td style="background-color:white;vertical-align:top;">
      <div style="float:right;padding:10px;margin:10px;">

        <{if $discuss.DiscussID}>
          <{if $discuss.onlyTo}>
            <{if $isAdmin or $now_uid==$discuss.uid}>
              <a href="discuss.php?op=unlock&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>&ReDiscussID=<{$ReDiscussID}>" class="mobile_btn" title="<{$smarty.const._MD_TADDISCUS_LOCK}>"><img src="images/lock.png" alt="<{$smarty.const._MD_TADDISCUS_LOCK}>"></a>
            <{else}>
              <img src="images/lock.png" alt="<{$smarty.const._MD_TADDISCUS_ONLY_ROOT}>">
            <{/if}>
          <{else}>
            <{if $isAdmin or $now_uid==$discuss.uid}>
              <a href="discuss.php?op=lock&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>&ReDiscussID=<{$ReDiscussID}>" class="mobile_btn" title="<{$smarty.const._MD_TADDISCUS_UNLOCK}>"><img src="images/unlock.png" alt="<{$smarty.const._MD_TADDISCUS_UNLOCK}>"></a>
            <{/if}>
          <{/if}>
        <{/if}>


        <{if $discuss.fun}>
          <a href="discuss.php?op=tad_discuss_form&BoardID=<{$discuss.BoardID}>&DiscussID=<{$discuss.DiscussID}>" class="mobile_btn"><{$smarty.const._TAD_EDIT}></a>
          <a href="javascript:delete_tad_discuss_func(<{$discuss.DiscussID}>);" class="mobile_btn"><{$smarty.const._TAD_DEL}></a>
        <{/if}>
        <{if $discuss.like}>
          <a href="javascript:like('unlike',<{$discuss.DiscussID}>);" class="mobile_btn">
          <span id="unlike<{$discuss.DiscussID}>" style="color:#FF6600"><{$discuss.Bad}></span><img src="images/unlike.png" alt="unlike" align="absmiddle"></a>
          <a href="javascript:like('like',<{$discuss.DiscussID}>);" class="mobile_btn"><img src="images/like.png" alt="like" align="absmiddle">
          <span id="like<{$discuss.DiscussID}>" style="color:#0066FF"><{$discuss.Good}></span></a>
        <{/if}>

      </div>

      <div style="background-color:<{if $discuss.onlyTo}>#FEEDD3<{else}>#EEEEEF<{/if}>;width:100%;height:76px;margin-top:10px;">
        <div style="color:#265827;text-decoration:none;font-family:Verdana;font-weight:bold;font-size: 92%;padding:10px;"><{$discuss.uid_name}></div>
        <div style="color:black;font-weight:normal;font-size: 75%;padding: 0px 0px 0px 10px;"><{$discuss.DiscussDate}> #<{$discuss.i}></div>
      </div>
    </td>
  </tr>
  <tr style="background-color:white;">
    <td colspan=2>
    <div class="mobilesty talk" style="line-height:160%;margin:10px 10px 10px 0px;font-size: 92%;text-align:justify;"><{$discuss.DiscussContent}></div>
    <{$discuss.files}>
    <div style="clear:both;height:40px;"></div>

    <{if $discuss.show_sig and $discuss.user_sig}>
      <div style="<{if $discuss.sig_style}><{$discuss.sig_style}><{else}>font-size: 75%; color: gray; border-top: 1px dashed gray; padding-top: 10px; margin-top: 10px;<{/if}>">
        <{$discuss.user_sig}>
      </div>
    <{/if}>
    </td>
  </tr>
</table>
