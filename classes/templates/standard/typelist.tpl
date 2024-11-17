<!-- BEGIN list_page -->
<table width="100%" border="0">
<tr>
	<td valign="bottom">
		<h1>{intl-list_headline}</h1>
	</td>
	<!-- BEGIN search_item_tpl -->
	<td rowspan="2" align="right">
	    <form action="{www_dir}{index}/address/{type}/search/" method="post">
	    	<input type="text" name="SearchText" size="12" value="{search_form_text}" />
		<input type="submit" value="{intl-search}" />
	    </form>
	</td>
	<!-- END search_item_tpl -->
</tr>
</table>

<form method="post" action="{www_dir}{index}{item_form_command}/">

<hr noshade="noshade" size="4" />
<!-- BEGIN list_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<!-- BEGIN name_header_item_tpl -->
	<th>{intl-name}:</th>
<!-- END name_header_item_tpl -->
<!-- BEGIN custom_header_item_tpl -->
	<th>{custom_header}:</th>
<!-- END custom_header_item_tpl -->
	{extra_type_header}
	<th>&nbsp;</th> <!-- Move down header -->
	                <!-- Separator header -->
	<th>&nbsp;</th> <!-- Move up header -->
	<th>&nbsp;</th> <!-- Edit button header -->
<!-- BEGIN delete_header_item_tpl -->
	<th>&nbsp;</th>
<!-- END delete_header_item_tpl -->
</tr>
<!-- BEGIN line_item_tpl -->
<tr class="{bg_color}">

<!-- BEGIN type_item_tpl -->
<!-- BEGIN item_plain_tpl -->
	<td>
        {item_name}
	</td>
<!-- END item_plain_tpl -->
<!-- BEGIN item_linked_tpl -->
	<td>
        <a href="{www_dir}{index}{item_url_command}/{item_id}">{item_name}</a>
	</td>
<!-- END item_linked_tpl -->
<!-- END type_item_tpl -->
	{extra_type_item}

<!-- BEGIN item_move_down_tpl -->
	<td width="1%"><a href="{www_dir}{index}{item_down_command}/{item_id}"><img src="{www_dir}/images/move-down.gif" height="12" width="12" border="0" alt="Move down" /></a></td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%"><a href="{www_dir}{index}{item_up_command}/{item_id}"><img src="{www_dir}/images/move-up.gif" height="12" width="12" border="0" alt="Move up" /></a></td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_up_tpl -->

	<td width="1%">
	<a href="{www_dir}{index}{item_edit_command}/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezc{item_id}-red" border="0" src="{www_dir}/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

<!-- BEGIN delete_box_item_tpl -->
	<td width="1%">
	<input type="checkbox" name="ItemArrayID[]" value="{item_id}">
	</td>
<!-- END delete_box_item_tpl -->

</tr>
<!-- END line_item_tpl -->
</table>
<!-- END list_item_tpl -->

<!-- BEGIN type_list_tpl -->
<table>
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/address/{type}/{action}/{item_previous_index}/{search_text}">&lt;&lt;&nbsp;{intl-previous}</a>
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
	|&nbsp;<a class="path" href="{www_dir}{index}/address/{type}/{action}/{item_index}/{search_text}">{type_item_name}</a>
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/address/{type}/{action}/{item_next_index}/{search_text}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->


<!-- BEGIN no_line_item_tpl -->
<p class="boxtext">{intl-no_item}</p>
<!-- END no_line_item_tpl -->
<hr noshade="noshade" size="4" />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="New" value="{intl-new}">
	</td>
	<td>&nbsp;</td>
<!-- BEGIN delete_button_item_tpl -->
	<td>
        <input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}">
	</td>
<!-- END delete_button_item_tpl -->
</tr>
</table>
</form>

<!-- END list_page -->
