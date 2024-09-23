<{$toolbar|default:''}>


<{if $op=="tad_discuss_form"}>

  <{foreach from=$form_data item=discuss}>
    <{include file="db:tad_discuss_form.tpl"}>
  <{/foreach}>

<{elseif $op=="show_one_tad_discuss"}>
  <{$js|default:''}>
  <span class="badge badge-info"><a href="discuss.php?BoardID=<{$BoardID|default:''}>" style="color:white;"><{$BoardTitle|default:''}></a></span>
  <{if $DiscussTitle|default:false}>
    <h2><{$DiscussTitle|default:''}></h2>
  <{else}>
    <h2 class="sr-only visually-hidden">No Discuss Title</h2>
  <{/if}>
  <{foreach from=$discuss_data item=discuss}>
    <{if $display_mode=="top" || $display_mode=="bottom"}>
      <{include file="db:tad_discuss_talk_bubble_vertical.tpl"}>
    <{elseif $display_mode=="mobile"}>
      <{include file="db:tad_discuss_mobile.tpl"}>
    <{elseif $display_mode=="clean"}>
      <{include file="db:tad_discuss_clean.tpl"}>
    <{elseif $display_mode=="default" || $display_mode=="left"}>
      <{include file="db:tad_discuss_talk_bubble.tpl"}>
    <{else}>
      <{include file="db:tad_discuss_bootstrap.tpl"}>
    <{/if}>
  <{/foreach}>

  <div class="row">
    <div class="col-sm-12 text-center"><{$bar|default:''}></div>
  </div>


  <link rel="stylesheet" type="text/css" media="screen" href="reset.css">
  <script src="<{$xoops_url}>/modules/tadtools/multiple-file-upload/jquery.MultiFile.js"></script>

  <form action="discuss.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <{foreach item=discuss from=$form_data}>
      <{if $display_mode=="top" || $display_mode=="bottom"}>
        <{include file="db:tad_discuss_talk_bubble_vertical.tpl"}>
      <{elseif $display_mode=="mobile"}>
        <{include file="db:tad_discuss_mobile.tpl"}>
      <{elseif $display_mode=="clean"}>
        <{include file="db:tad_discuss_clean.tpl"}>
      <{elseif $display_mode=="default" || $display_mode=="left"}>
        <{include file="db:tad_discuss_talk_bubble.tpl"}>
      <{else}>
        <{include file="db:tad_discuss_bootstrap.tpl"}>
      <{/if}>
    <{/foreach}>
  </form>

<{elseif $main_data}>
  <{if $ShowBoardTitle|default:false}>
    <h2><{$ShowBoardTitle|default:''}></h2>
  <{else}>
    <h2 class="sr-only visually-hidden">No Discuss Title</h2>
  <{/if}>

	<{$FooTableJS|default:''}>
	<div class="well card card-body bg-light m-1" style="background-color:white;">
  	<table class="table table-striped table-hover">
  	<thead>
  	<tr>
  		<th id="discuss_BoardTitle" data-class="expand"><{$smarty.const._MD_TADDISCUS_DISCUSSTITLE}></th>
  		<th id="discuss_BoardImg" data-hide="phone"><{$smarty.const._MD_TADDISCUS_DISCUSSRE}></th>
  		<th id="discuss_uid_name" data-hide="phone"><{$smarty.const._MD_TADDISCUS_UID}></th>
  		<th id="discuss_renum" data-hide="phone"><{$smarty.const._MD_TADDISCUS_LAST_RE}></th>
  	</tr>
  	</thead>

  	<tbody>

    <{if $main_data|default:false}>
      <{foreach item=discuss from=$main_data}>
        <tr>
      		<td headers="discuss_BoardTitle">
            <img src="images/<{if $discuss.onlyTo|default:false}>lock.png<{else}>greenpoint.gif<{/if}>" alt="<{$discuss.DiscussTitle}>" title="<{$discuss.DiscussTitle}>" align="absmiddle" style="margin-right:3px;"><a href="discuss.php?DiscussID=<{$discuss.DiscussID}>&BoardID=<{$discuss.BoardID}>" style="color:<{if $discuss.onlyTo|default:false}>maroon<{else}>#505050<{/if}>"><{$discuss.DiscussTitle}></a>
          </td>
      		<td headers="discuss_BoardImg" class="text-center"><{$discuss.renum}></td>
      		<td headers="discuss_uid_name"><div style="font-size: 62.5%;"><{$discuss.DiscussDate}></div><div><{$discuss.uid_name}></div></td>
      		<td headers="discuss_renum"><div style="font-size: 62.5%;"><{$discuss.LastTime}></div><div><{$discuss.last_uid_name}></div></td>
    		</tr>
      <{/foreach}>
    <{else}>
      <tr>
        <td headers="discuss_BoardTitle" colspan=4 class="text-center">
          <{if $post|default:false}>
            <img src="images/add.png" align="absmiddle" hspace=4 alt="<{$smarty.const._MD_TADDISCUS_ADD_DISCUSS}>">
            <a href="discuss.php?op=tad_discuss_form&BoardID=<{$DefBoardID|default:''}>"><{$smarty.const._MD_TADDISCUS_DISCUSS_EMPTY}></a>
          <{/if}>
        </td>
      </tr>
    <{/if}>
  	<tr>
    	<td colspan=5>
    	<{$post_tool|default:''}>
      </td>
    </tr>
  	</tbody>
  	</table>
	</div>
  <{$bar|default:''}>
<{else}>
  <h2><{$smarty.const._MD_TADDISCUS_DISCUSS_EMPTY}></h2>
  <div class="jumbotron bg-light p-5 rounded-lg m-3">
    <{if $smarty.session.tad_discuss_adm|default:false}>
      <a href="discuss.php?op=tad_discuss_form&BoardID=<{$smarty.get.BoardID}>"><{$smarty.const._MD_TADDISCUS_DISCUSS_EMPTY}></a>
    <{else}>
      <{$smarty.const._MD_TADDISCUS_DISCUSS_EMPTY}>
    <{/if}>
  </div>
<{/if}>

<{include file='db:system_notification_select.tpl'}>
