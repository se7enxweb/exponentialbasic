<h1>{intl-appointments}: {intl-year_view}</h1>
<hr noshade size="4" />

	<h2>{year_number}</h2>
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a class="path" href="{www_dir}{index}/calendar/yearview/{prev_year_number}">&lt;&lt; {intl-previous_year}</a>
	</td>
	<td align="right">
	<a class="path" href="{www_dir}{index}/calendar/yearview/{next_year_number}">{intl-next_year} &gt;&gt;</a>
	</td>
</tr>
</table>

<table width="100%" cellspacing="10">
{begin_tr}
<!-- BEGIN month_tpl -->
     <td valign="top">

<a href="{www_dir}{index}/calendar/monthview/{year_number}/{month_number}"><b>{month_name}:</b></a>
<br />
<table width="100%" border="1" cellspacing="0" cellpadding="2">
<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}">
<a class="small" href="{www_dir}{index}/calendar/dayview/{year_number}/{month_number}/{day_number}">{day_number}</a>
</td>
<!-- END day_tpl -->

<!-- BEGIN empty_day_tpl -->
<td class="{td_class}">
&nbsp;
</td>
<!-- END empty_day_tpl -->

</tr>
<!-- END week_tpl -->
</table>

    </td>
{end_tr}

<!-- END month_tpl -->
</table>

<form action="{www_dir}{index}/calendar/appointmentedit/edit/">

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
<input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
<input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
<input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
</form>

