<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
  <{if $show_error=='1'}>
  <{$error}>
    <div class="jumbotron">
      <h1><{$smarty.const._MA_TADDISCUS_NO_NEWBB}></h1>
    </div>
  <{elseif $op=='listBoard'}>
    <div class="pull-right">
    <a href="copynewbb.php?op=copyDiscuss&BoardID=<{$BoardID}>" class="btn btn-lg btn-info"><{$smarty.const._MA_TADDISCUS_COPY_DISCUSS}></a>
    <a href="copynewbb.php?op=copyDiscuss&BoardID=<{$BoardID}>&mode=force" class="btn btn-lg btn-warning"><{$smarty.const._MA_TADDISCUS_COPY_DISCUSS_FORCE}></a>
    </div>
    <div class="clearfix"></div>

    <form action="copynewbb.php" method="post" class="form">
    <table class="table table-striped table-bordered table-hover">
    <tr>
      <th>#</th>
      <th>Function</th>
      <th>DiscussID</th>
      <th>uid</th>
      <th>DiscussTitle</th>
      <th>DiscussDate</th>
      <th>BoardID</th>
      <th>LastTime</th>
      <th>Counter</th>
      <th>FromIP</th>
    </tr>

    <tbody>
    <{foreach item=bb from=$all_content}>
      <tr>
        <td><{$bb.i}></td>
        <td><a href="copynewbb.php?op=delnewbb&topic_id=<{$bb.topic_id}>&BoardID=<{$bb.BoardID}>" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a></td>
        <td><{$bb.topic_id}></td>
        <td><{$bb.topic_poster}></td>
        <td>
        <label class="checkbox inline">
        <input type="checkbox" name="batch_del[]" value="<{$bb.topic_id}>">  <{$bb.topic_title}>
        </label>
        </td>
        <td><{$bb.topic_time}></td>
        <td><{$bb.forum_id}></td>
        <td><{$bb.post_time}></td>
        <td><{$bb.topic_views}></td>
        <td><{$bb.poster_ip}></td>
      </tr>
    <{/foreach}>
    </tbody>
    </table>
    <input type="hidden" name="BoardID" value="<{$BoardID}>">
    <input type="hidden" name="op" value="batch_del">
    <button type="submit" class="btn btn-large btn-danger pull-right"><{$smarty.const._MA_TADDISCUS_BATCH_DEL}></button>
    </form>
    <div class="clearfix"></div>

  <{else}>
    <table class="table table-striped table-bordered table-hover">
    <tr>
      <th>BoardID</th>
      <th>BoardTitle</th>
      <th>BoardDesc</th>
      <th>BoardManager</th>
      <th>BoardSort</th>
      <th><{$smarty.const._MA_TADDISCUS_COPY}></th>
      <th><{$smarty.const._MA_TADDISCUS_COPY_AMOUNT}></th>
      <th><{$smarty.const._MA_TADDISCUS_READ_POWER}></th>
      <th><{$smarty.const._MA_TADDISCUS_POST_POWER}></th>
      <th><{$smarty.const._MA_TADDISCUS_POWER_STATUS}></th>
    </tr>

    <tbody>

    <{foreach item=bb from=$all_content}>

    <tr>
        <td><{$bb.forum_id}></td>
        <td><{$bb.forum_name}></td>
        <td><{$bb.forum_desc}></td>
        <td><{$bb.moderator}></td>
        <td><{$bb.forum_order}></td>
        <td><{$bb.tool}></td>
        <td nowrap><{$bb.discuss_num}> / <{$bb.discuss_num2}></td>
        <td><{$bb.forum_read}></td>
        <td><{$bb.forum_post}></td>
        <td><{$bb.power_status}></td>
      </tr>
    <{/foreach}>

    </tbody>

    <tr>
      <td colspan=6 class='bar'>
      <{$add_button}>
      <{$bar}>
      </td>
    </tr>
    </table>
  <{/if}>
</div>