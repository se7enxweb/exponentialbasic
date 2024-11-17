<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<h1>{intl-latest_served_pages}</h1>
	</td>
</tr>
<tr>
	<td><span class="boxtext">({item_start}-{item_end}/{item_count})</span></td>
</tr>
</table>

<hr noshade size="4" />

<!-- BEGIN page_view_list_tpl -->

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
	<th>
	{intl-request_time}
	</th>
	<th>
	{intl-remote_ip}:
	</th>
	<th>
	{intl-remote_hostname}:
	</th>
	<td align="right">
	<b>{intl-request_page}:</b>
	</td>
</tr>
<!-- BEGIN page_view_tpl -->
<tr class="{bg_color}">
	<td>
	{request_time}
	</td>
	<td>
	{remote_ip}
	</td>
	<td>
	{remote_host_name}
	</td>
	<td align="right">
	{request_page}
	</td>
</tr>
<!-- END page_view_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/stats/pageviewlist/last/{item_limit}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	<a class="path" href="{www_dir}{index}/stats/pageviewlist/last/{item_limit}/{item_index}">{type_item_name}</a>
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	&lt;&nbsp;{type_item_name}&nbsp;&gt;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	| <a class="path" href="{www_dir}{index}/stats/pageviewlist/last/{item_limit}/{item_next_index}/">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

<!-- END page_view_list_tpl -->

