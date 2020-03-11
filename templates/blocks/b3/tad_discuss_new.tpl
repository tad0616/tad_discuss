<{$block.NewFooTableJS}>
<table summary="list_table" id="new_discuss" cellspacing="1" class="table outer table">
  <thead>
    <tr>
      <th id="board_title"><{$smarty.const._MB_TADDISCUS_BOARDTITLE}></th>
      <th id="discuss_title" data-class='expand'><{$smarty.const._MB_TADDISCUS_DISCUSSTITLE}></th>
      <th id="discuss_re" class="text-center" data-hide='phone'><{$smarty.const._MB_TADDISCUS_DISCUSSRE}></th>
      <th id="discuss_uid" data-hide='phone'><{$smarty.const._MB_TADDISCUS_UID}></th>
      <th id="discuss_last_re" data-hide='phone'><{$smarty.const._MB_TADDISCUS_LAST_RE}></th>
    </tr>
  </thead>
  <tbody>

  <{foreach item=discuss from=$block.discuss}>
    <tr class="<{$discuss.class}>">
      <td headers="board_title"><a href="<{$xoops_url}>/modules/tad_discuss/discuss.php?BoardID=<{$discuss.BoardID}>"><{$discuss.ShowBoardTitle}></a></td>
      <td headers="discuss_title">
        <img src="<{$xoops_url}>/modules/tad_discuss/images/<{if $discuss.isPublic}>greenpoint.gif<{else}>lock.png<{/if}>" alt="<{$discuss.DiscussTitle}>" title="<{$discuss.DiscussTitle}>" align="absmiddle" style="margin-right:3px;"><a href="<{$xoops_url}>/modules/tad_discuss/discuss.php?DiscussID=<{$discuss.DiscussID}>&BoardID=<{$discuss.BoardID}>" style="color:#505050"><{$discuss.showDiscussTitle}></a>
      </td>
      <td headers="discuss_re" class="text-center">
        <{$discuss.renum}>
      </td>
      <td headers="discuss_uid">
        <div style="font-size: 62.5%;"><{$discuss.DiscussDate}></div>
        <div><{if $discuss.uid}><a href="<{$xoops_url}>/userinfo.php?uid=<{$discuss.uid}>"><{$discuss.uid_name}></a><{else}><{$discuss.uid_name}><{/if}></div>
      </td>
      <td headers="discuss_last_re">
        <div style="font-size: 62.5%;"><{$discuss.LastTime}></div>
        <div><{if $discuss.last_uid}><a href="<{$xoops_url}>/userinfo.php?uid=<{$discuss.last_uid}>"><{$discuss.last_uid_name}></a><{else}><{$discuss.last_uid_name}><{/if}></div>
      </td>
    </tr>
  <{/foreach}>

  </tbody>
</table>
