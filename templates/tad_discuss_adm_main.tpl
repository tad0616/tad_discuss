
<div class="container-fluid">
  <{if $op=="tad_discuss_board_form"}>
  <h1><{$smarty.const._MA_TAD_DISCUSS_BOARD_FORM}></h1>


    <form action="main.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
      <div class="form-group row mb-3">
        <!--討論區名稱-->
        <label class="col-sm-1 col-form-label text-sm-right control-label">
          <{$smarty.const._MA_TADDISCUS_OFBOARDID}>
        </label>
        <div class="col-sm-2">
          <select name="ofBoardID" class="form-control">
            <option value="0" <{if $of.BoardID==$ofBoardID}>selected<{/if}>></option>
            <{foreach from=$ofBoardArr item=of}>
              <option value="<{$of.BoardID}>" <{if $of.BoardID==$ofBoardID}>selected<{/if}>><{$of.BoardTitle}></option>
            <{/foreach}>
          </select>
        </div>
        <div class="col-sm-6">
          <input type="text" name="BoardTitle" value="<{$BoardTitle|default:''}>" id="BoardTitle" class="form-control validate[required]" placeholder="<{$smarty.const._MA_TADDISCUS_BOARDTITLE}>" >
        </div>

        <div class="col-sm-3">
          <!--狀態-->
          <div class="form-check-inline radio-inline">
              <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="BoardEnable" value="1" <{if $BoardEnable!='0'}>checked<{/if}>>
                  <{$smarty.const._TAD_ENABLE}>
              </label>
          </div>
          <div class="form-check-inline radio-inline">
              <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="BoardEnable" value="0" <{if $BoardEnable=='0'}>checked<{/if}>>
                  <{$smarty.const._TAD_UNABLE}>
              </label>
          </div>
        </div>
      </div>

      <div class="form-group row mb-3">
        <!--討論區說明-->
        <div class="col-sm-9">
          <textarea name="BoardDesc" rows=3 id="BoardDesc" class="form-control" placeholder="<{$smarty.const._MA_TADDISCUS_BOARDDESC}>"><{$BoardDesc|default:''}></textarea>
        </div>
        <!--討論區圖片-->
        <div class="col-sm-3">
        <{$upform|default:''}>
        </div>
      </div>

      <div class="form-group row mb-3">
        <!--讀取權限-->
        <div class="col-sm-3"><label><{$smarty.const._MA_TADDISCUS_READ_POWER}></label><{$enable_read_group|default:''}></div>
        <!--寫入權限-->
        <div class="col-sm-3"><label><{$smarty.const._MA_TADDISCUS_POST_POWER}></label><{$enable_post_group|default:''}></div>
        <!--板主-->
        <div class="col-sm-3"><label><{$smarty.const._MA_TADDISCUS_BOARDMANAGER}></label><{$user_menu|default:''}></div>
        <div class="col-sm-3">
          <input type="hidden" name="BoardID" value="<{$BoardID|default:''}>">
          <input type="hidden" name="op" value="<{$next_op|default:''}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>

    </form>
  <{else}>

    <script type="text/javascript">
    $(document).ready(function(){
        $("#sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
            var order = $(this).sortable("serialize");
            $.post("save_sort.php", order, function(theResponse){
                $("#save_msg").html(theResponse);
            });
        }
        });
    });
    </script>

    <{if $all_content|default:false}>

      <div id="save_msg"></div>

      <table class="table table-striped">
        <tr>
          <th colspan=2><{$smarty.const._MA_TADDISCUS_BOARDTITLE}></th>
          <th><{$smarty.const._MA_TADDISCUS_AMOUNT}></th>
          <th><{$smarty.const._MA_TADDISCUS_BOARDMANAGER}></th>
          <th><{$smarty.const._MA_TADDISCUS_BOARDENABLE}></th>
          <th><{$smarty.const._TAD_FUNCTION}></th>
        </tr>

        <tbody id="sort">
          <{foreach from=$all_content item=all}>
          <tr id="tr_<{$all.BoardID}>" style="background-color:<{$all.color}>;">
            <td>
              <img src="<{$all.pic}>" alt="<{$all.BoardTitle}>" title="<{$all.BoardTitle}>" class="img-thumbnail" width="120">
            </td>
            <td>
              <a href="main.php?BoardID=<{$all.BoardID}>"><{$all.BoardTitle}></a>
              <div style="width:300px;font-size: 75%;color:gray;margin:8px 0px"><{$all.BoardDesc}></div>
            </td>
            <td>
              <div style="margin:6px 0px;"><{$all.BoardNum}></div>
              <div><{$all.BoardNum2}></div>
            </td>
            <td><{$all.BoardManager}></td>
            <td>
              <{if $all.BoardEnable=="1"}>
                <a href="main.php?op=changeBoardStatus&act=0&BoardID=<{$all.BoardID}>"><img src="../images/yes.gif" alt="<{$smarty.const._YES}>"></a>
              <{else}>
                <a href="main.php?op=changeBoardStatus&act=1&BoardID=<{$all.BoardID}>"><img src="../images/no.gif" alt="<{$smarty.const._NO}>"></a>
              <{/if}>
            </td>
            <td>
              <form action="main.php" method="post" class="form-horizontal" role="form">
                <div class="form-group row mb-3">
                  <div class="col-sm-6">
                    <select name="NewBoardID" class="form-control">
                      <option value=""><{$smarty.const._MA_TADDISCUS_MOVE}></option>
                      <{$all.board_menu_options}>
                    </select>
                  </div>
                  <div class="col-sm-6">
                    <input type="hidden" name="BoardID" value="<{$all.BoardID}>">
                    <input type="hidden" name="op" value="moveToBoardID">
                    <button type="submit" class="btn btn-sm btn-xs btn-info"><{$smarty.const._MA_TADDISCUS_MERGE}></button>

                    <{if $all.BoardEnable==0}>
                      <a href="javascript:delete_tad_discuss_board_func(<{$all.BoardID}>);" class="btn btn-sm btn-xs btn-danger"><{$smarty.const._TAD_DEL}>
                      </a>
                    <{/if}>
                    <a href="main.php?op=tad_discuss_board_form&BoardID= <{$all.BoardID}>" class="btn btn-sm btn-xs btn-warning"><{$smarty.const._TAD_EDIT}>
                    </a>
                  </div>
                </div>
              </form>
            </td>
          </tr>

          <!--子分類-->
          <{if $all.subBoard|default:false}>
            <{foreach item=sb from=$all.subBoard}>
              <tr id="tr_<{$sb.BoardID}>" style="background-color:<{$sb.color}>;">
                <td>
                  <img src="<{$sb.pic}>" alt="<{$sb.BoardTitle}>" title="<{$sb.BoardTitle}>" class="img-thumbnail" width="100" style="margin-left: 20px;">
                </td>
                <td>
                  <a href="main.php?BoardID=<{$sb.BoardID}>"><{$sb.BoardTitle}></a>
                  <div style="width:300px;font-size: 75%;color:gray;margin:8px 0px"><{$sb.BoardDesc}></div>
                </td>
                <td>
                  <div style="margin:6px 0px;"><{$sb.BoardNum}></div>
                  <div><{$sb.BoardNum2}></div>
                </td>
                <td><{$sb.BoardManager}></td>
                <td>
                  <{if $sb.BoardEnable=="1"}>
                    <a href="main.php?op=changeBoardStatus&act=0&BoardID=<{$sb.BoardID}>"><img src="../images/yes.gif" alt="<{$smarty.const._YES}>"></a>
                  <{else}>
                    <a href="main.php?op=changeBoardStatus&act=1&BoardID=<{$sb.BoardID}>"><img src="../images/no.gif" alt="<{$smarty.const._NO}>"></a>
                  <{/if}>
                </td>
                <td>
                  <form action="main.php" method="post" class="form-horizontal" role="form">
                    <div class="form-group row mb-3">
                      <div class="col-sm-6">
                        <select name="NewBoardID" class="form-control">
                          <option value=""><{$smarty.const._MA_TADDISCUS_MOVE}></option>
                          <{$sb.board_menu_options}>
                        </select>
                      </div>
                      <div class="col-sm-6">
                        <input type="hidden" name="BoardID" value="<{$sb.BoardID}>">
                        <input type="hidden" name="op" value="moveToBoardID">
                        <button type="submit" class="btn btn-sm btn-xs btn-info"><{$smarty.const._MA_TADDISCUS_MERGE}></button>

                        <{if $sb.BoardEnable==0}>
                          <a href="javascript:delete_tad_discuss_board_func(<{$sb.BoardID}>);" class="btn btn-sm btn-xs btn-danger"><{$smarty.const._TAD_DEL}>
                          </a>
                        <{/if}>
                        <a href="main.php?op=tad_discuss_board_form&BoardID= <{$sb.BoardID}>" class="btn btn-sm btn-xs btn-warning"><{$smarty.const._TAD_EDIT}>
                        </a>
                      </div>
                    </div>
                  </form>
                </td>
              </tr>
              <{/foreach}>
            <{/if}>
          <{/foreach}>
        </tbody>
      </table>
    <{/if}>

    <a href="main.php?op=tad_discuss_board_form" class="btn btn-info"><{$smarty.const._MA_TADDISCUS_ADD_BOARD}></a>
  <{/if}>
</div>