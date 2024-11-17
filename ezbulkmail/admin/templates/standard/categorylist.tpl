<h1>{intl-category_list} - {current_category_name}</h1>

<hr noshade="noshade" size="4" />
<form action="{www_dir}{index}/bulkmail/categorylist/{current_category_id}" method="post">

<p class="boxtext">{intl-single_list_select}:</p>
<select name="SingleListID">
<option value="-1" {multi_list_selected}>{intl-multi_list_site}</option>
<!-- BEGIN single_category_item_tpl -->
<option value="{category_id}" {single_list_selected}>{category_name}</option>
<!-- END single_category_item_tpl -->
</select>
<br /><br />

<!-- BEGIN category_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="15%">{intl-category_name}:</th>
	<th width="69%">{intl-category_description}:</th>
	<th width="9%">{intl-category_is_public}:</th>
        <th width="5%">{intl-subscription_count}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bulkmail/categorylist/{category_id}">{category_name}</a>
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
	<td class="{td_class}">
	{category_is_public}
	</td>
	<td class="{td_class}">
	{subscription_count}
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bulkmail/categoryedit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{category_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezb{category_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}"><input type="checkbox" name="CategoryArrayID[]" value="{category_id}" /></td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_tpl -->

<!-- BEGIN bulkmail_tpl -->
<hr noshade="noshade" size="4">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="20%">{intl-bulkmail_subject}:</th>
	<th width="79%">{intl-sent_date}:</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN bulkmail_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bulkmail/view/{bulkmail_id}">{bulkmail_subject}</a>
	</td>
	<td class="{td_class}">
	{sent_date}
	</td>
	<td class="{td_class}"><input type="checkbox" name="BulkMailArrayID[]" value="{bulkmail_id}" /></td>
</tr>
<!-- END bulkmail_item_tpl -->
</table>
<!-- END bulkmail_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/bulkmail/categorylist/{current_category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/bulkmail/categorylist/{current_category_id}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/bulkmail/categorylist/{current_category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input type="submit" class="stdbutton" name="New" value="{intl-new}" /></td>
  <td>&nbsp</td>
  <td><input type="submit" class="stdbutton" name="Delete" value="{intl-delete_selected}" /></td>
</tr>
</table>

<hr noshade="noshade" size="4" />
<input type="submit" class="okbutton" name="Ok" value="{intl-ok}" />
</form>
