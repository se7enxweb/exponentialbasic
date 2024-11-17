<h1>{intl-year_report} - {this_year}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN result_list_tpl -->
<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<span class="boxtext">{intl-total_page_views}:</span> {total_page_views}
	</td>
	<td align="right">
	<span class="boxtext">{intl-pages_pr_month}:</span> {pages_pr_month}
	</td>
</tr>
<tr>
	<td colspan="2">
	{year}
	</td>
</tr>
</table>

<!-- BEGIN month_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="50%">
		<!-- BEGIN month_link_tpl --><span class="path"><a href="{www_dir}{index}/stats/monthreport/{this_year}/{this_month}">
		<!-- END month_link_tpl -->{month_named}<!-- BEGIN month_link_end_tpl -->
		</a></span>
		<!-- END month_link_end_tpl -->
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
			<td colspan="2" width="{page_view_percent}%" bgcolor="#eeeeee">
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
<!-- END month_tpl -->


<!-- BEGIN year_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<!-- BEGIN year_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/stats/yearreport/{previous_year}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END year_previous_tpl -->
	
	<!-- BEGIN year_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END year_previous_inactive_tpl -->

	<!-- BEGIN year_next_tpl -->
	<td align="right">
	<a class="path" href="{www_dir}{index}/stats/yearreport/{next_year}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END year_next_tpl -->

	<!-- BEGIN year_next_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END year_next_inactive_tpl -->
</tr>
</table>
<!-- END year_tpl -->

<!-- END result_list_tpl -->
