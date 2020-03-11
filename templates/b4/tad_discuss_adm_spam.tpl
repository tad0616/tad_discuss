<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
  <{if $now_op=='list_spam' or $now_op=='search_spam'}>
    <{$jquery}>
    <script type="text/javascript">
    $().ready(function(){
      $("#clickAll").click(function() {
        if($("#clickAll").prop("checked")){
          $("input.spam_keyword").each(function() {
          $(this).prop("checked", true);
        });
      }else{
       $("input.spam_keyword").each(function() {
           $(this).prop("checked", false);
       });
      }
      });
      $("#clickAll2").click(function() {
        if($("#clickAll2").prop("checked")){
          $("input.SpamDiscuss").each(function() {
          $(this).prop("checked", true);
        });
      }else{
       $("input.SpamDiscuss").each(function() {
           $(this).prop("checked", false);
       });
      }
      });


    });

    function delme(id){
      $.post("spam.php", { op: "update_config", item: $('#chk'+id).val()},
        function(data) {
        $('#k'+id).remove();
      });
    }
    </script>

    <div id="msg"></div>
    <form action="spam.php" method="post" role="form">
      <div style="display:inline-block; float: left; font-size: 81.25%; padding:4px 8px;">
        <input type="checkbox" value="<{$spam.keyword}>" id="clickAll" checked>
        <label style="display: inline;" for="clickAll">
          ALL
        </label>
      </div>
      <{assign var="i" value=0}>
      <{foreach from=$all_keyword item=spam}>
        <{assign var="i" value=$i+1}>
        <div id="k<{$i}>" style="display:inline-block; float: left; font-size: 81.25%; padding:4px 8px;">
            <input name="spam_keyword[]" class="spam_keyword" id="chk<{$i}>" type="checkbox" value="<{$spam.keyword}>" <{$spam.checked}>>
            <label style="display: inline;" for="chk<{$i}>">
              <{$spam.keyword}>
              <a href="javascript:delme('<{$i}>');"><img src="../images/del2.gif" alt="<{$smarty.const._TAD_DEL}>" style="vertical-align:middle;"></a>
            </label>
        </div>
      <{/foreach}>

      <div style="clear:both;"></div>

      <div class="row">
        <div class="col-sm-12">
          <div class="input-group">
            <input name="new_spam_keyword" type="text" class="form-control" placeholder="<{$smarty.const._MA_TADDISCUS_NEW_SPAM_KEYWORD}>">
            <input type="hidden" name="op" value="search_spam">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary"><{$smarty.const._SUBMIT}></button>
            </span>
          </div>
        </div>
      </div>

    </form>
  <{/if}>

  <{if $now_op=='search_spam'}>
    <form action="spam.php" method="post" role="form">
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th><input type="checkbox" id="clickAll2" checked>DiscussID</th>
          <th>ReDiscussID</th>
          <th>DiscussTitle</th>
          <th>DiscussDate</th>
          <th>uid_name</th>
          <th>Counter</th>
          <th>keyword</th>
        </tr>
        <{foreach from=$all_content item=spam}>
          <tr>
            <td>
              <label class="checkbox-inline">
                <input name="SpamDiscussID[<{$spam.DiscussID}>]" type="checkbox" class="SpamDiscuss" value="<{$spam.DiscussID}>" checked><{$spam.DiscussID}>
              </label>
            </td>
            <td>
              <{$spam.ReDiscussID}>
            </td>
            <td>
              <a href="../discuss.php?DiscussID=<{$spam.DiscussID}>&BoardID=<{$spam.BoardID}>" target="_blank"><{$spam.DiscussTitle}></a>
            </td>
            <td>
              <{$spam.DiscussDate}>
            </td>
            <td>
              <{$spam.uid_name}>
            </td>
            <td>
              <{$spam.Counter}>
            </td>
            <td>
              <{$spam.spam_keyword}>
            </td>
          </tr>
        <{/foreach}>
      </table>
      <input type="hidden" name="op" value="del_spam">
      <button type="submit" class="btn btn-danger"><{$smarty.const._TAD_DEL}></button>
    </form>
  <{/if}>
</div>