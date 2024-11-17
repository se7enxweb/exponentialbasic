
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-search} - ({forum_start}-{forum_end}/{forum_total})</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>


<hr noshade size="4" />

<h2>{intl-searchfor} "{url_text}"</h2>
<br>

<!-- BEGIN empty_result_tpl -->
<h3 class="error">{intl-empty_result}</h3>
<!-- END empty_result_tpl -->


<!-- BEGIN search_result_tpl -->
<form method="post" action="{www_dir}{index}/forum/messageedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
    <th>{intl-topic}:</th>
    <th>{intl-author}:</th>
    <th>{intl-time}:</th>
    <th colspan="2">&nbsp;</th>
</tr>
    <!-- BEGIN message_tpl -->
<tr>
    	<td class="{td_class}">
	<a href="{www_dir}{index}/forum/message/{message_id}/">
	{message_topic}
	</a>
	</td>
    	<td class="{td_class}">
	{user}
	</td>
    	<td class="{td_class}">
	{postingtime}
	</td>

     <td width="1%" class="{td_class}">
	 <a href="{www_dir}{index}/forum/messageedit/edit/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('efm{message_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="efm{message_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="" alt="Edit" /></a>
     </td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="MessageArrayID[]" value="{message_id}">
	</td>

</tr>
    <!-- END message_tpl -->

</table>
<hr noshade="noshade" size="4" />
<input type="hidden" Name="RefererURL" value="/forum/search/?Offset={this_offset}&URLQueryString={url_url_text}" />
<input class="stdbutton" type="submit" Name="DeleteMessages" value="{intl-deletemessages}">
</form>
<!-- END search_result_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td>
	    <!-- BEGIN type_list_tpl -->
	    <br />
	    <table cellpadding="0" cellspacing="0" border="0">
		<tr>
		    <!-- BEGIN type_list_previous_tpl -->
		    <td>
			&lt;&lt;&nbsp;<a class="path" href="{www_dir}{index}/forum/search/parent/{url_text}/{item_previous_index}">{intl-previous}</a>&nbsp;|
		    </td>
		    <!-- END type_list_previous_tpl -->
		    
		    <!-- BEGIN type_list_previous_inactive_tpl -->
		    <td class="inactive">
			{intl-previous}&nbsp;
		    </td>
		    <!-- END type_list_previous_inactive_tpl -->

		    <!-- BEGIN type_list_item_list_tpl -->

		    <!-- BEGIN type_list_item_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/forum/search/parent/{url_text}/{item_index}">{type_item_name}</a>&nbsp;|
		    </td>
		    <!-- END type_list_item_tpl -->

		    <!-- BEGIN type_list_inactive_item_tpl -->
		    <td class="inactive">
			&nbsp;{type_item_name}&nbsp;|
		    </td>
		    <!-- END type_list_inactive_item_tpl -->

		    <!-- END type_list_item_list_tpl -->

		    <!-- BEGIN type_list_next_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/forum/search/parent/{url_text}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_tpl -->

		    <!-- BEGIN type_list_next_inactive_tpl -->
		    <td class="inactive">
			{intl-next}&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_inactive_tpl -->
		</tr>
	    </table>
	    <!-- END type_list_tpl -->
	</td>
    </tr>
</table>
