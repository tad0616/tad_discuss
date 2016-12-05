<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
  <div class="container-fluid">
  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/jqueryCookie/jquery.cookie.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    var $tabs = $("#tad_discuss_grouppermform_tabs").tabs({ cookie: { expires: 30 } , collapsible: false});
  });
  </script>
  <div id="tad_discuss_grouppermform_tabs">
    <ul>
      <li><a href="#tabs-1"><{$smarty.const._MA_TADDISCUS_READ_POWER}></a></li>
      <li><a href="#tabs-2"><{$smarty.const._MA_TADDISCUS_POST_POWER}></a></li>
    </ul>
    <div id="tabs-1">
      <{$forum_read}>
    </div>
    <div id="tabs-2">
      <{$forum_post}>
    </div>
  </div>
</div>