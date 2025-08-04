 <div id="calendarWrap" class="gcalYearView">
<table width="100%" cellspacing="2" cellpadding="0" border="0">
<tr>
	<td align="right" colspan="10" style="padding: 5px;">
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/dayview/{date_year}/{date_month}/{date_day}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-day}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/weekview/{date_year}/{date_month}/{date_day}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-week}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/monthview/{date_year}/{date_month}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-month}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/yearview/{date_year}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-year}</a>
      </span>
      </td>
      </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0"  align="center" style="border: 1px solid gray; text-align: center;">

<tr>

 <td  id="gcalBigHeader" style="border: 0px; background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;">
  <a class="gcalMonthViewNext" href="{www_dir}{index}/groupeventcalendar/yearview/{prev_year_number}/">&lt;&lt;</a> &nbsp; &nbsp;
  <span style="font-size: 20px;">{year_number}</span>  &nbsp; &nbsp;
  <a class="gcalMonthViewNext" href="{www_dir}{index}/groupeventcalendar/yearview/{next_year_number}/">&gt;&gt;</a>
  </td>
</tr>
<tr><td>
<table width="100%" cellspacing="10">
{begin_tr}
<!-- BEGIN month_tpl -->
     <td valign="top"
     >

<div class="gcalYearViewHeading"
style="background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalSmallYearHeader.png') repeat;">
<a class="gcalYearViewMonthName" href="{www_dir}{index}/groupeventcalendar/monthview/{year_number}/{month_number}/">{month_name}</a></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gcalYearViewTable">
<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}">
<a class="{td_class}" href="{www_dir}{index}/groupeventcalendar/dayview/{year_number}/{month_number}/{day_number}/">{day_number}</a>
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
</td></tr></table>
</div>