 <div id="calendarWrap">
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">

  <tr>
    <td align="right">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <!-- BEGIN valid_editor_tpl -->
    <form method="post" action="{www_dir}{index}/groupeventcalendar/eventedit/edit/{event_id}/">
	<input type="hidden" name="eventArrayID[]" value={event_id}>
    <td style="height: 25px;" >
    <input class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'" type="submit"  value="{intl-edit_event}">&nbsp;
    </td><td>
    <input class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'" type="submit" name="DeleteEvents" value="{intl-delete_events}"></form>
     </td>
       <!-- END valid_editor_tpl -->
      <td>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/dayview/{the_year}/{the_month}/{the_day}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-day}</a>
      </span>
      
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/weekview/{the_year}/{the_month}/{the_day}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-week}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/monthview/{the_year}/{the_month}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-month}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/yearview/{the_year}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-year}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/dayview/{year_cur}/{month_cur}/{day_cur}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-today}</a>
      </span>
      </td></tr></table>
    </td>
  </tr>
</table>

<!-- BEGIN error_tpl -->
<p class="error">{intl-error}</p>
<!-- END error_tpl -->

<!-- BEGIN view_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="gcalViewBg">
<tr>
	<td id="gcalBigHeader" style="border: 0px; background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;" colspan=7>
	<span class="gcalBigHeader">
    {event_title}
    </span>
	</td>
</tr>
<tr><td>
<table width="100%" cellspacing="4" cellpadding="0" border="0">
<tr>
	<td colspan=2 align="left">
	<!-- BEGIN private_tpl -->
	<i>{intl-private}</i><br />
	<!-- END private_tpl -->
	<!-- BEGIN public_tpl -->
	<i>{intl-public}</i><br />
	<!-- END public_tpl -->
	</td>
</tr>
<tr>
        <td width="20%" class="gcalViewLabel">
        {intl-description}:
        </td> <td  class="gcalViewResult">
                {event_description}
                </td>
</tr>


<tr>
        <td class="gcalViewLabel">
        {intl-date}:
        </td>
        <td class="gcalViewResult">
        {event_date}
        </td>
</tr>
<tr>
        <td class="gcalViewLabel">
        {intl-time}:
        </td>

        <td class="gcalViewResult">
        {event_starttime} - {event_stoptime}
        </td>
</tr>

<tr>
	<td class="gcalViewLabel">
	{intl-group}:
	</td>
        <td class="gcalViewResult">
        {event_owner}
        </td>
</tr>
<tr>
        <td class="gcalViewLabel">
        {intl-type}:
        </td>
	<td class="gcalViewResult">
	{event_type}
	</td>
</tr>

<tr>
	<td class="gcalViewLabel">
	{intl-category}:
	</td>
	<td class="gcalViewResult">
	{event_category}
	</td>
</tr>
<tr>
		<td class="gcalViewLabel">
	{intl-priority}:
	</td>
	<td class="gcalViewResult">
        <!-- BEGIN lowest_tpl -->
        {intl-lowest}
        <!-- END lowest_tpl -->
	<!-- BEGIN low_tpl -->
	{intl-low}
	<!-- END low_tpl -->
	<!-- BEGIN normal_tpl -->
	{intl-normal}
	<!-- END normal_tpl -->
        <!-- BEGIN medium_tpl -->
        {intl-medium}
        <!-- END medium_tpl -->
	<!-- BEGIN high_tpl -->
	{intl-high}
	<!-- END high_tpl -->
        <!-- BEGIN highest_tpl -->
        {intl-highest}
        <!-- END highest_tpl -->
	</td>
</tr>

<tr>
        <td class="gcalViewLabel">
        {intl-location}:
        </td>
        <td class="gcalViewResult">
        {event_location}
        </td>
</tr>
<tr>
        <td class="gcalViewLabel">
        {intl-status}:
        </td>
        <td class="gcalViewResult">
        <!-- BEGIN tentative_tpl -->
        {intl-tentative}
        <!-- END tentative_tpl -->
        <!-- BEGIN confirmed_tpl -->
        {intl-confirmed}
        <!-- END confirmed_tpl -->
        <!-- BEGIN cancelled_tpl -->
        {intl-cancelled}
        <!-- END cancelled_tpl -->
        </td>
</tr>
<tr>
<td class="gcalViewLabel">{intl-url}:</td>
<td class="gcalViewResult"><a href="{event_url}" target="_blank" style="text-decoration: none;">{event_url}</a></td>
</tr>
<!-- BEGIN recurring_event_tpl -->
<tr>
<td colspan="2" align="left" class="gcalRecurInfo">
{intl-recur_info}
</td>
</tr>
<tr>
<td class="gcalViewLabel">
{intl-freq}:
</td>
<td class="gcalViewResult">
{recur_freq}
</td>
</tr>
<tr>
<td class="gcalViewLabel">
{intl-type}:
</td>
<td class="gcalViewResult">
{recur_type}
</td>
</tr>
<!-- BEGIN recurring_days_week_tpl -->
<tr>
<td class="gcalViewLabel">Days of Week</td>
<td class="gcalViewResult">
<!-- BEGIN recurring_days_tpl -->
{recur_days_week}
<!-- END recurring_days_tpl -->
</td>
</tr>
<!-- END recurring_days_week_tpl -->
<tr>
<!-- BEGIN recurring_monthly_type_tpl -->
<td class="gcalViewLabel">
{intl-month_type}:
</td>
<td class="gcalViewResult">
{recur_monthly_type}
</td>
<!-- END recurring_monthly_type_tpl -->
</tr>
<tr>
<td class="gcalViewLabel">
{repeat_type}:
</td>
<td class="gcalViewResult">
{repeat_message}
</td>
</tr>
<td class="gcalViewLabel">
{intl-exceptions}:
</td>
<td class="gcalViewResult">
<!-- BEGIN recurring_exceptions_tpl -->
<div class="gcalMultiListing">{recur_exception}</div>
<!-- END recurring_exceptions_tpl -->
<!-- END recurring_event_tpl -->
</td>
</tr>
</table>
</td></tr>
</table>
<!-- BEGIN attached_file_list_tpl -->
<br />
<table width="100%" cellspacing="2" cellpadding="0" border="0">
<tr><th>{intl-attached_files}:</th></tr>
<!-- BEGIN attached_file_tpl -->
<tr>
     <td width="50%" class="{td_class}">
     <a style="text-decoration: none;" href="{www_dir}{index}/filemanager/download/{file_id}/">{file_name}</a>
     </td>
     <td width="50%" class="{td_class}" align="right">
     <div class="p">( <a href="{www_dir}{index}/filemanager/download/{file_id}/">{file_size}&nbsp;{file_unit}</a> )</div>
     </td>
</tr>
<tr>
     <td colspan="2" valign="top" class="{td_class}">
	{file_description}
     </td>
</tr>
<!-- END attached_file_tpl -->

<!-- BEGIN attached_image_tpl -->
<tr>
     <td width="50%" class="{td_class}">
     <img src="{www_dir}{index}/filemanager/download/{image_id}/" alt="{image_name}" title="{image_name}" /><br />
     </td>
     <td width="50%" class="{td_class}" align="right">
     <a style="text-decoration: none;" href="{www_dir}{index}/filemanager/download/{image_id}/">{image_name}</a>&nbsp;&nbsp;
     <div class="p">( <a href="{www_dir}{index}/filemanager/download/{image_id}/">{file_size}&nbsp;{file_unit}</a> )</div>
     </td>
</tr>
<tr>
     <td colspan="2" valign="top" class="{td_class}">
        {image_description}
     </td>
</tr>
<!-- END attached_image_tpl -->

</table>
<br />
<!-- END attached_file_list_tpl -->

<!-- END view_tpl -->
</div>