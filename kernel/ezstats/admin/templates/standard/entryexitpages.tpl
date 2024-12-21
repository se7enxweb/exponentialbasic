<h1>{intl-entry_exit_page_report}</h1>

<hr noshade size="4" />

{month}

<h2>{intl-exit_pages}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-page_uri}:
	</th>
	<td align="right">
	<b>{intl-exit_count}:</b>
	</td>
</tr>

<!-- BEGIN exit_page_tpl -->
<tr class="{bg_color}">
	<td>
	{page_uri}
	</td>
	<td align="right">
	{exit_count}
	</td>
</tr>
<!-- END exit_page_tpl -->

</table>

<h2>{intl-entry_pages}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-page_uri}:
	</th>
	<td align="right">
	<b>{intl-entry_count}:</b>
	</td>
</tr>

<!-- BEGIN entry_page_tpl -->
<tr class="{bg_color}">
	<td>
	{page_uri}
	</td>
	<td align="right">
	{entry_count}
	</td>
</tr>
<!-- END entry_page_tpl -->
</table>

<!-- BEGIN month_tpl -->
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN month_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/stats/entryexitreport/{previous_year}/{previous_month}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END month_previous_tpl -->
	
	<!-- BEGIN month_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END month_previous_inactive_tpl -->
	<!-- BEGIN month_next_tpl -->
	<td align="right">
	<a class="path" href="{www_dir}{index}/stats/entryexitreport/{next_year}/{next_month}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END month_next_tpl -->

	<!-- BEGIN month_next_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END month_next_inactive_tpl -->

</tr>
</table>

<br />

<!-- END month_tpl -->
