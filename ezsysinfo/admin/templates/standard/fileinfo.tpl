<h1>{intl-file_info}</h1>

<hr noshade="noshade" size="4" />
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
</tr>
	<th>
	{intl-mount_point}:
	</th>
	<th>
	{intl-fs_type}:
	</th>
	<th>
	{intl-device}:
	</th>
	<th width="200">
	{intl-capacity}:
	</th>
	<th>
	{intl-free}:
	</th>
	<th>
	{intl-used}:
	</th>
	<th>
	{intl-total}:
	</th>
</tr>

<!-- BEGIN disk_tpl -->
</tr>
	<td>
	{mount_point}
	</td>
	<td>
	{fs_type}
	</td>
	<td>
	{device}
	</td>
	<td width="200">

	<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td width="{capacity_percent}%" bgcolor="#ffee00">
	&nbsp;
	</td>
	<td width="{capacity_inverted_percent}%"  bgcolor="#eeeeee">
	&nbsp;
	</td>
	</tr>
	</table>

	</td>
	<td>
	{free}
	</td>
	<td>
	{used}
	</td>
	<td>
	{total}
	</td>
</tr>
<!-- END disk_tpl -->
<tr>
	<td>
	</td>
	<td>
	</td>
	<td>
	</td>

	<td>
	<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td width="{sum_capacity_percent}%" bgcolor="#ffee00">
	&nbsp;
	</td>
	<td width="{sum_capacity_inverted_percent}%"  bgcolor="#eeeeee">
	&nbsp;
	</td>
	</tr>
	</table>
	</td>

	<td>
	{sum_free}
	</td>
	<td>
	{sum_used}
	</td>
	<td>
	{sum_total}
	</td>
</tr>
</table>
<br />