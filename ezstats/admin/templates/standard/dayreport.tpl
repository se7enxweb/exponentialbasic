<h1>{intl-day_report} - {this_day} {this_month_named} {this_year}</h1>

<hr noshade size="4" />

<br />
<!-- BEGIN result_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<span class="boxtext">{intl-total_page_views}:</span> {total_page_views}
	</td>
	<td align="right">
	<span class="boxtext">{intl-pages_pr_hour}:</span> {pages_pr_hour}
	</td>
</tr>
<tr>
	<td colspan="2">
	{day}
	</td>
</tr>
</table>

<!-- BEGIN hour_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="50%">
		<span class="path">{current_hour}:00 - {current_hour}:59</span>
		</td>
		<td align="right">
		{page_view_count} {intl-pages} ({percent_count}%)
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<!-- BEGIN percent_marker_tpl -->
			<td width="{page_view_percent}%" bgcolor="#ffee00">
			<img src="{www_dir}/design/admin/images/1x1.gif" width="1" height="10" border="0"></td>
			<td width="{page_view_inverted_percent}%"  bgcolor="#eeeeee">
			<img src="{www_dir}/design/admin/images/1x1.gif" width="1" height="10" border="0"></td>
			<!-- END percent_marker_tpl -->
			<!-- BEGIN no_percent_marker_tpl -->
			<td width="{page_view_percent}%" bgcolor="#eeeeee">
			<img src="{www_dir}/design/admin/images/1x1.gif" width="1" height="10" border="0"></td>
			<!-- END no_percent_marker_tpl -->
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<!-- END hour_tpl -->

<!-- BEGIN day_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<!-- BEGIN day_previous_tpl -->
	<td width="25%">
	<a class="path" href="{www_dir}{index}/stats/dayreport/{previous_year}/{previous_month}/{previous_day}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END day_previous_tpl -->
	
	<!-- BEGIN day_previous_inactive_tpl -->
	<td width="25%">
	&nbsp;
	</td>
	<!-- END day_previous_inactive_tpl -->

	<td width="25%" align="center">
	<a class="path" href="{www_dir}{index}/stats/monthreport/{this_year}/{this_month}">[ {intl-month_report} ]</a>
	</td>

	<td width="25%" align="center">
	<a class="path" href="{www_dir}{index}/stats/yearreport/{this_year}">[ {intl-year_report} ]</a>
	</td>

	<!-- BEGIN day_next_tpl -->
	<td width="25%" align="right">
	<a class="path" href="{www_dir}{index}/stats/dayreport/{next_year}/{next_month}/{next_day}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END day_next_tpl -->

	<!-- BEGIN day_next_inactive_tpl -->
	<td width="25%">
	&nbsp;
	</td>
	<!-- END day_next_inactive_tpl -->

</tr>
</table>
<!-- END day_tpl -->

<!-- END result_list_tpl -->
