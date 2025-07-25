 <div id="calendarWrap">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form method="post" action="{www_dir}{index}/groupeventcalendar/weekview/">
 <table border="0" cellspacing="0" cellpadding="0" id="gcalDayViewSortBy">
 <tr>
  <td id="gcalDayViewSortByHeader"><img src="{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalX.png" style="margin-right:7px;"  alt="close" onclick="document.getElementById('gcalDayViewSortBy').style.visibility = 'hidden';" />
 </td>
 </tr>
<tr>
	<td valign="top" style="text-align: center; padding: 5px;">
		<span>{intl-group}:</span><br />
		<select class="gcalDayViewSelect" name="GetByGroupID">
		<option value="0">{intl-default}</option>
		<!-- BEGIN group_item_tpl -->
		<option {group_is_selected} value="{group_id}">{group_name}</option>
		<!-- END group_item_tpl -->
		</select>
         <br />
		<span>{intl-type}:</span><br />
		<select class="gcalDayViewSelect" name="GetByTypeID">
		<option value="0">{intl-default_type}</option>
		<!-- BEGIN type_item_tpl -->
		<option {type_is_selected} value="{type_id}">{type_name}</option>
		<!-- END type_item_tpl -->
		</select><br /><br />

		<input class="gcalDayViewButton" style="background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalButtonBg.png') repeat;" type="submit" Name="GetByGroup" value="{intl-show}">
	</td>

</tr>
</table>
</form>

<!-- BEGIN week_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td align="right" colspan="10" style="padding: 5px;">
     <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'" onclick="document.getElementById('gcalDayViewSortBy').style.visibility = 'visible'; var posx = getMouse(event, 'x'); var posy = getMouse(event, 'y'); document.getElementById('gcalDayViewSortBy').style.left = posx + 'px'; document.getElementById('gcalDayViewSortBy').style.top = posy+ 'px';">
      Sort By...
      </span>
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
<table width="100%" border="0" cellspacing="0" cellpadding="2"  style="border: 1px solid gray;">

<tr>

 <td colspan=7 id="gcalBigHeader" style="border: 0px; background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;">
  <a class="gcalMonthViewNext" href="{www_dir}{index}/groupeventcalendar/weekview/{prev_year_number}/{prev_month_number}/{prev_day_number}/">&lt;&lt;</a> &nbsp; &nbsp;
  <span style="font-size: 20px;">{intl-week} {week_num}, {month_name} - {current_year_number}</span>  &nbsp; &nbsp;
  <a class="gcalMonthViewNext" href="{www_dir}{index}/groupeventcalendar/weekview/{next_year_number}/{next_month_number}/{next_day_number}/">&gt;&gt;</a>
  </td>
</tr>
<tr>
<!-- BEGIN week_day_tpl -->
	<th width="14%" style=" text-align: center; background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalMonthViewHeaderBg.png') repeat;">
	{week_day_name}
	</th>
<!-- END week_day_tpl -->
</tr>

<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}" valign="top" style="height: 100px; border: #cc6666 solid 1px;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
     <!-- BEGIN day_link_tpl -->
      <a class="gcalBoxText" style="margin-left: 5px 5px 5px 5px;" href="{www_dir}{index}/groupeventcalendar/dayview/{year_number}/{month_number_p}/{day_number}/{selected_group_id}/" title="Dayview">{day_number}</a>
    <!-- END day_link_tpl -->
    <!-- BEGIN day_no_link_tpl -->
      <a class="gcalBoxText" style="margin-left: 5px 5px 5px 5px;" href="" onmouseover="window.status='No Events in Day'; return true" onmouseout="window.status=''; return true" title="No Events in Day">{day_number}</a>
    <!-- END day_no_link_tpl -->
    </td>
    <td>
     <!-- BEGIN new_event_link_tpl -->
      <div align="right"><a class="path" href="{www_dir}{index}/groupeventcalendar/eventedit/new/{year_number}/{month_number}/{day_number}/">+</a></div>
     <!-- END new_event_link_tpl -->

     <!-- BEGIN no_new_event_link_tpl -->
      &nbsp;
     <!-- END no_new_event_link_tpl -->

    </td>
  </tr>
  <tr>
    <td colspan="2">
      <img src="/images/1x1.gif" height="4" width="2" border="0" alt="" /><br />
    </td>
  </tr>
<!-- BEGIN private_appointment_tpl -->
 <tr valign="top">
   <td width="8" class="tdmini"><!-- <img src="/sitedesign/{sitedesign}/images/dot.gif" border="0" alt="" /> --></td>
   <td class="tdmini"><div class="small"><i>{appointment_group}</i> - <b>{intl-pvt_event}<br /></div></td>
 </tr>
<!-- END private_appointment_tpl -->

<!-- BEGIN public_appointment_tpl -->
 <tr valign="top">
   <td style="padding-bottom:5px;">
   <a class="gcalMonthViewNames" href="{www_dir}{index}/groupeventcalendar/eventview/{appointment_id}/" onmouseover="
   return overlib('<div class=\'olList\'>Name</div>{overlib_full_name}<div class=\'olList\'>Time</div> {event_start_time} - {event_stop_time}<div class=\'olList\'>Description </div>{overlib_description}');"
   onmouseout="return nd();">
   {appointment_name}</a>
   <div style="font-size: 10px;">{event_start_time} - {event_stop_time}</div>
   </td>
 <tr>
<!-- END public_appointment_tpl -->
</table>
</td>
<!-- END day_tpl -->

</tr>
</table>
<!-- END week_tpl -->
<script language="javascript">
  Drag.init(document.getElementById("gcalDayViewSortBy"));
divX=0
divY=0
function getMouse(fnEvent, type)
{
    if(typeof(fnEvent.clientX)=='number' && typeof(fnEvent.clientY)=='number')
		{
		divX = fnEvent.clientX
		divY = fnEvent.clientY
		}
	else if(typeof(fnEvent.x)=='number' && typeof(fnEvent.y)=='number')
		{
		divX = fnEvent.x
		divY = fnEvent.y
		}
	else
		{
		divX = 500
		divY = 500
		}
  if (type == 'x')
   return divX;
  else
   return divY;
}

</script>
</div>