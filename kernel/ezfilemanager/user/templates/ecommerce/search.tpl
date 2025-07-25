<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<td>
	<h1>{intl-head_line}</h1>
	<div class="boxtext">({file_start}-{file_end}/{file_total})</div>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/filemanager/search/" method="get">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<h2>{intl-search_for}: &quot;{search_text}&quot;</h2>

<!-- BEGIN file_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<tr>
    <th>&nbsp;</th>
    <th>{intl-file_name}:</th>
    <th>{intl-description}:</th>
    <th>{intl-size}:</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
</tr>
<!-- BEGIN file_tpl -->
<tr>
	<!-- BEGIN read_tpl -->
	<td class="{td_class}" width="1%" valign="top">
	<img src="{www_dir}/images/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%" valign="top">
	<a href="{www_dir}{index}/filemanager/fileview/{file_id}/">{original_file_name}</a><br />
	</td>
	<td class="{td_class}" width="56%" valign="top">
	{file_description}
	<td class="{td_class}" width="1%" valign="top">
	{file_size}&nbsp;{file_unit}
	</td>
	<td class="{td_class}" width="1%" valign="top">
	<a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-dl','','{www_dir}/images/downloadminimrk.gif',1)"><img name="ezf{file_id}-dl" border="0" src="{www_dir}/images/downloadmini.gif" width="16" height="16" align="top" alt="Download" /></a>
	</td>
	<!-- END read_tpl -->
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_list_tpl -->
<!-- BEGIN empty_search_tpl -->
<p class="error">{intl-empty_search}</p>
<!-- END empty_search_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/filemanager/search/parent/{url_text}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/filemanager/search/parent/{url_text}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/filemanager/search/parent/{url_text}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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

