<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp; <a href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a> 
 &raquo; <a href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{intl-headline}</h1>

<!-- BEGIN view_forums_tpl -->
<table class="list" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <th colspan="2">{intl-forum}:</th>
        <th>{intl-forum_description}:</th>
        <th class="right">{intl-threads}/<br />{intl-messages}:</th>
    </tr>
    <!-- BEGIN forum_item_tpl -->
    <tr>
	<td class="{td_class}" width="1%">
	    <img src="{www_dir}/design/base/images/icons/forum.gif" width="16" height="16" border="0" alt="Forum" />
	</td>
	<td class="{td_class}" width="50%">
	    <a href="{www_dir}{index}/forum/messagelist/{forum_id}/">{name}</a>
        </td>
	<td class="{td_class}" width="48%">
	    <span class="small">{description}</span>
        </td>
	<td class="{td_class}" align="right" width="1%">
	    {threads} / {messages}
        </td>
    </tr>
    <!-- END forum_item_tpl -->

</table>
<!-- END view_forums_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->
	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->
	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->
	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

        </div>
        <!-- end #contentWrap -->