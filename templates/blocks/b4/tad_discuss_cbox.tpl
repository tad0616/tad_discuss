<{if $block}>
  <{$block.jquery_path}>
  <div class="row">
    <div class="col-sm-12">
      <{$block.SelectBoard}>
    </div>
  </div>

  <div class="row">
    <div id="cboxdiv" class="col-sm-12">
      <iframe title="discuss content" frameborder="0" title="show" width="100%" height="<{$block.height}>" src="<{$xoops_url}>/modules/tad_discuss/cbox.php?BoardID=<{$block.BoardID}>&amp;border_color=<{$block.border_color}>&amp;bg_color=<{$block.bg_color}>&amp;font_color=<{$block.font_color}>" marginheight="2" marginwidth="2" scrolling="auto" allowtransparency="yes" name="discussCboxMain" style="border:#ababab 1px solid;" id="discussCboxMain"></iframe>

      <br>

      <iframe title="discuss form" frameborder="0" title="post" width="100%" height="190" src="<{$xoops_url}>/modules/tad_discuss/post.php?BoardID=<{$block.BoardID}><{if $block.BoardID==0}>&setupRule=<{$block.setupRule}><{/if}>" marginheight="2" marginwidth="2" scrolling="no" allowtransparency="yes" name="discussCboxForm" style="border:#ababab 1px solid;border-top:0px" id="discussCboxForm"></iframe>
      <{if $block.BoardID}>
        <p><a href="<{$xoops_url}>/modules/tad_discuss/discuss.php?BoardID=<{$block.BoardID}>"><{$snarty.const._MB_TADDISCUS_CBOX_VIEW_ALL}></a></p>
      <{/if}>
    </div>
  </div>
<{/if}>