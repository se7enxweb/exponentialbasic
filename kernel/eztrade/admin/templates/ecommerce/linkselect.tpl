<!-- BEGIN normal_select_tpl -->
<form method="post" action="{www_dir}{index}/trade/productedit/link/list/{product_id}">

<h1>{intl-head_line}</h1>
<hr noshade="noshade" size="4" />

<!-- BEGIN tree_select_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN category_list_tpl -->
<tr>
	<th>
	{intl-category}:
	</th>
	<th>
	{intl-url}:
	</th>
	<th>
	{intl-select}:
	</th>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}{category_url}">{category_name}</a>
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}{category_orig_url}" target="_blank">{category_orig_url}</a>
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="CategorySelect[]" value="{category_item_id}">
	</td>
</tr>
<!-- END category_item_tpl -->
<!-- END category_list_tpl -->
<tr>
	<td>&nbsp;
	</td>
</tr>
<!-- BEGIN object_list_tpl -->
<tr>
	<th>
	{intl-item}:
	</th>
	<th>
	{intl-url}:
	</th>
	<th>
	{intl-select}:
	</th>
</tr>
<!-- BEGIN object_item_tpl -->
<tr>
	<td class="{td_class}">
	{object_name}
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}{object_orig_url}" target="_blank">{object_orig_url}</a>
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="ItemSelect[]" value="{item_id}">
	</td>
</tr>
<!-- END object_item_tpl -->
<!-- END object_list_tpl -->
</table>

<br />

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/productedit/link/select/{product_id}/{module_type}/{section_id}/{category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
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
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productedit/link/select/{product_id}/{module_type}/{section_id}/{category_id}/{item_index}">{type_item_name}</a>&nbsp;|
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	&nbsp;&lt;{type_item_name}&gt;&nbsp;|
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productedit/link/select/{product_id}/{module_type}/{section_id}/{category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<br />
<!-- END type_list_tpl -->
<!-- END tree_select_tpl -->
<!-- BEGIN url_select_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" name="URLName" value="" />
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-url}:</p>
	<input type="text" name="URL" value="" />
	</td>
</tr>
</table>

<br />

<!-- END url_select_tpl -->

<input type="hidden" name="SectionID" value="{section_id}" />
<input type="hidden" name="ModuleType" value="{module_type}" />
<input class="okbutton" type="submit" name="ItemInsert" value="{intl-insert_selected}" />
<input class="stdbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
<!-- END normal_select_tpl -->

<!-- BEGIN module_select_tpl -->
<form method="post" action="{www_dir}{index}/trade/productedit/link/list/{product_id}">

<h1>{intl-head_line_choose}</h1>
<hr noshade="noshade" size="4" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN module_item_tpl -->
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/admin/images/{site_style}/menu-arrow.gif" width="10" height="12" border="0" /><br /></td>
	<td width="99%"class="menu"><a class="menu" href="{www_dir}{index}/trade/productedit/link/select/{product_id}/{module_name}/{module_type}/{section_id}">{module_type_name}</a></td>
</tr>
<!-- END module_item_tpl -->
</table>

<br />

<input type="hidden" name="SectionID" value="{section_id}" />
<input type="hidden" name="ModuleType" value="{module_type}" />
<input class="stdbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
<!-- END module_select_tpl -->
