
<h1>{intl-stats_headline}"{company_name}"</h1>
<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<span class="boxtext">{intl-total_page_views}:</span> {total_page_views}
	</td>
</tr>
<tr>
	<td colspan="2">
	{date_nav}
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
		<!-- BEGIN month_link_tpl --><span class="path">
<!--  <a href="{www_dir}{index}/stats/monthreport/{this_year}/{this_month}"> -->
		<!-- END month_link_tpl -->{month_named}<!-- BEGIN month_link_end_tpl -->
<!--  		</a> -->
</span>
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

<br />

<!-- BEGIN date_nav_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<!-- BEGIN year_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/contact/company/stats/year/{company_id}/{previous_year}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END year_previous_tpl -->
	
	<!-- BEGIN year_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END year_previous_inactive_tpl -->

	<!-- BEGIN year_next_tpl -->
	<td align="right">
	<a class="path" href="{www_dir}{index}/contact/company/stats/year/{company_id}/{next_year}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END year_next_tpl -->

	<!-- BEGIN year_next_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END year_next_inactive_tpl -->
</tr>
</table>
<!-- END date_nav_tpl -->

<br />

<form method="post" action="{www_dir}{index}/contact/company/list/{category_id}/">

<input class="okbutton" type="submit" name="Back" value="{intl-back}" />

</form>
