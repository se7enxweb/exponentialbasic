<h1>{intl-head_line}</h1>

<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-poll}:
	</th>

	<th>
	{intl-description}:
	</th>
</tr>
<!-- BEGIN poll_item_tpl -->
<tr class="{td_class}">
	<td> 
	<a href="{www_dir}{index}/poll/{action}/{poll_id}/">{poll_name}</a>
	</td>
	<td>
	{poll_description}
	</td>
</tr>
<!-- END poll_item_tpl -->
</table>
