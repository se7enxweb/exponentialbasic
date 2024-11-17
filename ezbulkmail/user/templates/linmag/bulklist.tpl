<h1>{intl-category_list} - {current_category_name}</h1>

<hr noshade="noshade" size="4" />
<form action="{www_dir}{index}/bulkmail/bulklist/{current_category_id}" method="post">

<!-- BEGIN bulkmail_tpl -->
<hr noshade="noshade" size="4">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="99%">{intl-bulkmail_subject}:</th>
	<th width="1%">{intl-sent_date}:</th>
</tr>
<!-- BEGIN bulkmail_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bulkmail/view/{bulkmail_id}">{bulkmail_subject}</a>
	</td>
	<td class="{td_class}">
	{sent_date}
	</td>
</tr>
<!-- END bulkmail_item_tpl -->
</table>
<!-- END bulkmail_tpl -->

<!-- BEGIN no_bulkmail_tpl -->
<p class="error">{intl-no_bulkmail_error}</p>
<!-- END no_bulkmail_tpl -->

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
<input type="submit" class="okbutton" name="Ok" value="{intl-ok}" />
</form>