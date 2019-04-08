<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
  <{if $show_error=='1'}>
    <div class="jumbotron">
      <h1><{$msg}></h1>
      <{$other_msg}>
    </div>
  <{else}>
    <a href="copycbox.php?op=copycbox" class="btn btn-lg btn-danger pull-right"><{$smarty.const._MA_TADDISCUS_IMPORT_FORM_CBOX}></a>
    <{$bar}>
    <table class="table table-striped table-bordered table-hover">
    <tr>
      <th>publisher</th>
      <th>uid</th>
      <th>msg</th>
      <th>post_date</th>
      <th>ip</th>
      <th>only_root</th>
      <th>root_msg</th>
    </tr>

    <tbody>

    <{foreach item=bb from=$all_content}>

    <tr>
        <td><{$bb.publisher}></td>
        <td><{$bb.uid}></td>
        <td><{$bb.msg}></td>
        <td><{$bb.post_date}></td>
        <td><{$bb.ip}></td>
        <td><{$bb.only_root}></td>
        <td><{$bb.root_msg}></td>
      </tr>
    <{/foreach}>

    </tbody>
    </table>
    <{$bar}>
  <{/if}>
</div>