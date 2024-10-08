<div class="container-fluid">
  <{if $show_error=='1'}>
    <div class="jumbotron bg-light p-5 rounded-lg m-3">
      <h1><{$msg|default:''}></h1>
      <{$other_msg|default:''}>
    </div>
  <{else}>
    <a href="copycbox.php?op=copycbox" class="btn btn-lg btn-danger pull-right float-right pull-end"><{$smarty.const._MA_TADDISCUS_IMPORT_FORM_CBOX}></a>
    <{$bar|default:''}>
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
    <{$bar|default:''}>
  <{/if}>
</div>