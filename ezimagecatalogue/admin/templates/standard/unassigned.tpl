<form method="post" action="{www_dir}{index}/imagecatalogue/unassigned/{offset}/{limit}/" enctype="multipart/form-data">

<h1>{intl-images}</h1>


<hr noshade="noshade" size="4" />

<!-- BEGIN image_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN detail_view_tpl -->
<tr>
	<!-- BEGIN detail_read_tpl -->
	<td valign="top" class="{td_class}">
	<a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/imagecatalogue/unassigned/{offset}/{limit}"><img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /></a>
	</td>
	<td class="{td_class}">
	{image_size}&nbsp;{image_unit}
	</td>

	<td class="{td_class}">
	<select name="CategoryArrayID[]">
	<option	value="-1">{intl-do_not_update}</option>
	<!-- BEGIN value_tpl -->
	<option	value="{option_value}">{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/imagecatalogue/image/edit/{image_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezimg{image_id}-red','','{www_dir}/ezimagecatalogue/user/{image_dir}/redigerminimrk.gif',1)"><img name="ezimg{image_id}-red" border="0" src="{www_dir}/ezimagecatalogue/user/{image_dir}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%">
	<input type="hidden" name="ImageArrayID[]" value="{image_id}">
	</td>
	<!-- END detail_read_tpl -->
</tr>
<!-- END detail_view_tpl -->
</table>
<!-- END image_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/imagecatalogue/unassigned/{item_previous_index}/{limit}/">&lt;&lt;&nbsp;{intl-prev}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/imagecatalogue/unassigned/{item_index}/{limit}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/imagecatalogue/unassigned/{item_next_index}/{limit}">{intl-next}&nbsp;&gt;&gt;</a>
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

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Update" value="{intl-update}">

</form>


