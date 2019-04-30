<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
  <{if $now_op=="tad_discuss_cbox_setup_form"}>
    <h3><{$smarty.const._MA_TADDISCUS_RULE_SETUP}></h3>
    <form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data" role="form">

      <!--註記-->
      <div class="row">
        <label class="col-sm-3 text-right">
          <{$smarty.const._MA_TADDISCUS_SETUPNAME}>
        </label>
        <div class="col-sm-5">
          <input type="text" name="setupName" id="setupName" class="form-control validate[required]" value="<{$setupName}>" placeholder="<{$smarty.const._MA_TADDISCUS_SETUPNAME}>">
        </div>
      </div>

      <!--偵測字串-->
      <div class="row">
        <label class="col-sm-3 text-right">
          <{$smarty.const._MA_TADDISCUS_SETUPRULE}>
        </label>
        <div class="col-sm-5">
          <input type="text" name="setupRule" id="setupRule" class="form-control validate[]" value="<{$setupRule}>" placeholder="<{$smarty.const._MA_TADDISCUS_SETUPRULE}>">
        </div>
      </div>

      <div class="row">
        <label class="col-sm-3 text-right">
          <{$smarty.const._MA_TADDISCUS_TO_BOARDID}>
        </label>
        <div class="col-sm-2">
          <select name="BoardID" class="form-control" size=1>
            <option value="" <{if $BoardID == ""}>selected="selected"<{/if}>></option>
            <{foreach from=$option item=option}>
              <option value="<{$option.BoardID}>" <{if $BoardID == $option.BoardID}>selected="selected"<{/if}>><{$option.BoardTitle}></option>
            <{/foreach}>
          </select>
        </div>

        <div class="col-sm-2">
          <input type="text" name="newBorard" id="newBorard" class="form-control validate[]" placeholder="<{$smarty.const._MA_TADDISCUS_ADD_BOARD}>">
        </div>

        <div class="col-sm-2">
          <!--設定流水號-->
          <input type='hidden' name="setupID" value="<{$setupID}>">
          <input type="hidden" name="setupSort" value="<{$setupSort}>">
          <input type="hidden" name="op" value="<{$next_op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>


    </form>
  <{/if}>


  <!--列出所有資料-->
  <{if $all_content}>

    <{$jquery}>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
          var order = $(this).sortable("serialize");
          $.post("save_sort_rule.php", order, function(theResponse){
              $("#save_msg").html(theResponse);
          });
        }
        });
    });


    function delete_tad_discuss_cbox_setup_func(setupID){
      var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
      if (!sure)  return;
      location.href="<{$action}>?op=delete_tad_discuss_cbox_setup&setupID=" + setupID;
    }
    </script>

    <div id="save_msg"></div>

    <table class="table table-striped table-hover">
      <thead>
      <tr>
        <th><!--註記--><{$smarty.const._MA_TADDISCUS_SETUPNAME}></th>
        <th><!--偵測字串--><{$smarty.const._MA_TADDISCUS_SETUPRULE}></th>
        <th><!--討論版編號--><{$smarty.const._MA_TADDISCUS_TO_BOARDID}></th>
        <th><{$smarty.const._TAD_FUNCTION}></th>
      </tr>
      </thead>

      <tbody id="sort">
      <{foreach from=$all_content item=data}>
        <tr id="tr_<{$data.setupID}>">
          <td><{$data.setupName}></td>
          <td><{$data.setupRule}></td>
          <td><{$data.BoardTitle}></td>
          <td>
            <a href="javascript:delete_tad_discuss_cbox_setup_func(<{$data.setupID}>);" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
            <a href="<{$action}>?op=tad_discuss_cbox_setup_form&setupID=<{$data.setupID}>" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>
          </td>
        </tr>
      <{/foreach}>
      </tbody>
    </table>
  <{/if}>

  <!--顯示某一筆資料-->
  <{if $now_op=="show_one_tad_discuss_cbox_setup"}>
    <h2 class="text-center"><{$title}></h2>
    <hr>

    <div class="row">
        <!--偵測字串-->
      <div class="col-sm-3 text-right">
        <{$smarty.const._MA_TADDISCUS_SETUPRULE}>
      </div>
      <div class="col-sm-9">
        <{$setupRule}>
      </div>
    </div>

    <div class="row">
      <!--討論版編號-->
      <div class="col-sm-3 text-right">
        <{$smarty.const._MA_TADDISCUS_TO_BOARDID}>
      </div>
      <div class="col-sm-9">
        <{$BoardID}>
      </div>
    </div>
  <{/if}>
</div>