<h1>{intl-month_report} - {this_month_named} {this_year}</h1>

<hr noshade size="4" />

<!-- BEGIN result_list_tpl -->

<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<span class="boxtext">{intl-total_page_views}:</span> {total_page_views}
	</td>
	<td align="right">
	<span class="boxtext">{intl-pages_pr_day}:</span> {pages_pr_day}
	</td>
</tr>
<tr>
	<td colspan="2">
	{month}
	</td>
</tr>
</table>

<!-- BEGIN day_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="50%">
		<!-- BEGIN day_link_tpl -->
		<span class="path"><a href="{www_dir}{index}/stats/dayreport/{this_year}/{this_month}/{current_day}">{intl-day}: {current_day}</a></span>
		<!-- END day_link_tpl -->
		<!-- BEGIN no_day_link_tpl -->
		{intl-day}: {current_day}
		<!-- END no_day_link_tpl -->
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
<!-- END day_tpl -->

<!-- BEGIN month_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<!-- BEGIN month_previous_tpl -->
	<td width="40%">
	<a class="path" href="{www_dir}{index}/stats/monthreport/{previous_year}/{previous_month}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END month_previous_tpl -->
	
	<!-- BEGIN month_previous_inactive_tpl -->
	<td width="40%">
	&nbsp;
	</td>
	<!-- END month_previous_inactive_tpl -->

	<td width="20%" align="center">
	<a class="path" href="{www_dir}{index}/stats/yearreport/{this_year}">[ {intl-year_report} ]</a>
	</td>

	<!-- BEGIN month_next_tpl -->
	<td width="40%" align="right">
	<a class="path" href="{www_dir}{index}/stats/monthreport/{next_year}/{next_month}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END month_next_tpl -->

	<!-- BEGIN month_next_inactive_tpl -->
	<td width="40%">
	&nbsp;
	</td>
	<!-- END month_next_inactive_tpl -->

</tr>
</table>
<!-- END month_tpl -->

<!-- END result_list_tpl -->
