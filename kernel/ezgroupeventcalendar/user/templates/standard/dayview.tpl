 <div id="calendarWrap">
<script>
 function objChangeVisiblity(objName){
  document.getElementById(objName).style.visibility = 'hidden';
 }
</script>
<div id="overDiv" style="position: absolute; visibility: hidden; z-index: 1000;"></div>
<form method="post" action="{www_dir}{index}/groupeventcalendar/dayview/">
 <table border="0" cellspacing="0" cellpadding="0" id="gcalDayViewSortBy">
 <tr>
  <td id="gcalDayViewSortByHeader"><a href="javascript:objChangeVisiblity('gcalDayViewSortBy')" style="text-decoration: none;"><img src="{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalX.png" style="z-index: 1001; margin-right:7px; margin-top: 2px;" alt="close" border="0" /></a>
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

		<input class="gcalDayViewButton" style="background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalButtonBg.png') repeat;" type="submit" Name="GetByGroup" value="{intl-show}">
	</td>

</tr>
</table>
</form>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td>

	<table width="160" border="0" cellspacing="0" cellpadding="0" id="gcalDayViewMonthTable">
	<tr><td colspan="7" id="gcalDayViewMonthTableHeader" style="height: 12px; background: no-repeat url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalDayViewMonthTableHeader.png'); font-size: 2px;"><a href="javascript:objChangeVisiblity('gcalDayViewMonthTable')" style="text-decoration: none;"><img src="{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalX.png" style="height: 12px; z-index: 1001; margin-right:7px;" alt="close" border="0" /></a></td>
</tr></td></tr>
    <tr>
	<td align="center" colspan=7 width="100%" style="background: no-repeat url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalDayViewMonthTableSubHeader.png');">
		<a  class="gcalDayViewMonthTableHeader" href="{www_dir}{index}/groupeventcalendar/dayview/{pm_year_number}/{pm_month_number}/{pm_day_number}/{group_print_id}/">&lt;&lt;&nbsp;</a>
		<a  class="gcalDayViewMonthTableHeader" href="{www_dir}{index}/groupeventcalendar/monthview/{year_number}/{month_number}/{group_print_id}/">{month_name}</a>
		<a  class="gcalDayViewMonthTableHeader" href="{www_dir}{index}/groupeventcalendar/dayview/{nm_year_number}/{nm_month_number}/{nm_day_number}/{group_print_id}/">&nbsp;&gt;&gt;</a>

	</td>
</tr>
        <!-- BEGIN week_tpl -->
		<tr>
			<!-- BEGIN day_tpl -->
			<td class="gcalDayViewMonthTableDay">
			<a class="gcalDayViewMonthTableDay" href="{www_dir}{index}/groupeventcalendar/dayview/{year_number}/{month_number}/{day_number}/{group_print_id}/">{day_number}</a>
			</td>
			<!-- END day_tpl -->

			<!-- BEGIN empty_day_tpl -->
			<td class="gcalDayViewMonthTableEmpty">
			&nbsp;
			</td>
			<!-- END empty_day_tpl -->
		</tr>
		<!-- END week_tpl -->

		</table>

</td>
<td valign="bottom">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<form method="post" action="{www_dir}{index}/groupeventcalendar/eventedit/edit/">
	<td align="right" colspan="10" style="padding: 5px;">
	<!-- BEGIN valid_editor_tpl -->
     <input class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'" type="submit" name="GoNew" value="{intl-new_event}">&nbsp;
	 <input class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'" onmouseout="this.className='gcalSwitchBox'" type="submit" name="DeleteEvents" value="{intl-delete_events}">
    <!-- END valid_editor_tpl --> 
     <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'"
      onclick="document.getElementById('gcalDayViewSortBy').style.visibility = 'visible';
      var posx = getMouse(event, 'x');
      var posy = getMouse(event, 'y');
      document.getElementById('gcalDayViewSortBy').style.left = posx + 'px';
      document.getElementById('gcalDayViewSortBy').style.top = posy+ 'px';">
      Sort By
      </span>
     <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'"
      onclick="document.getElementById('gcalDayViewMonthTable').style.visibility = 'visible';
      var posx = getMouse(event, 'x');
      var posy = getMouse(event, 'y');
      document.getElementById('gcalDayViewMonthTable').style.left = posx + 'px';
      document.getElementById('gcalDayViewMonthTable').style.top = posy+ 'px';">
      Show Calendar
      </span>
      <!--
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/dayview/{the_year}/{the_month}/{the_day}/{group_print_id}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-day}</a>
      </span>
      -->
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/weekview/{the_year}/{the_month}/{the_day}/{group_print_id}" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-week}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/monthview/{the_year}/{the_month}/{group_print_id}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-month}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/yearview/{the_year}/{group_print_id}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-year}</a>
      </span>
      <span class="gcalSwitchBox" onmouseover="this.className='gcalSwitchBoxSelect'"
      onmouseout="this.className='gcalSwitchBox'">
      <a href="{www_dir}{index}/groupeventcalendar/dayview/{year_cur}/{month_cur}/{day_cur}/{group_print_id}/" style="text-decoration:none; font-weight:normal;font-size:12px;">{intl-today}</a>
      </span>

	</td>
</tr>
</table>
</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid gray;">
<!-- BEGIN day_view_long_date_header_tpl -->
<tr>
	<td id="gcalBigHeader" style="background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;" colspan="10">
    <span class="gcalBigHeader"><a style="font-size: large; text-decoration: none; color: #000;" href="{www_dir}{index}/groupeventcalendar/monthview/{year_number}/{month_number}/{group_print_id}/">{long_date}</a></span></td>
</tr><tr>
<td width="5%" class="gcalDayViewTopBar">
<a class="gcalSmallLink" 
href="{www_dir}{index}/groupeventcalendar/dayview/{pd_year_number}/{pd_month_number}/{pd_day_number}/{group_print_id}/"> &lt;&lt; </a></td>
	<!-- BEGIN day_links_tpl -->
	<td width="13%" onmouseover="this.className='gcalDayViewTopBarSelect'"
    onmouseout="this.className='{class_name}'"
    onclick="location.href = '{www_dir}{index}/groupeventcalendar/dayview/{top_year_number}/{top_month_number}/{top_day_number}/{group_print_id}/'"
    class="{class_name}">{day_name}</td>
	<!-- END day_links_tpl -->
<td width="5%" class="gcalDayViewTopBar"><a class="gcalSmallLink" href="{www_dir}{index}/groupeventcalendar/dayview/{nd_year_number}/{nd_month_number}/{nd_day_number}/{group_print_id}/"> &gt;&gt; </a></td>
</tr>
<!-- BEGIN all_day_event_tpl -->
<tr>
<td width="100%" colspan=9>
<table width=100% cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="5%" class="gcalDayViewTopBar" style="cursor: default; font-size: 8px;">All Day</td>
<td width="88%"
onclick="location.href = '{www_dir}{index}/groupeventcalendar/eventview/{all_day_id}/'"
style="cursor: pointer; background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalAllDayEvent.png') repeat;">
<a class="gcalAllDay" href="{www_dir}{index}/groupeventcalendar/eventview/{all_day_id}/"
onmouseover="return overlib('<div class=\'olWrapAllDay\'><div class=\'olListAllDay\'>Name</div>{all_day_overlib_name}<div class=\'olListAllDay\'>Time</div> {all_day_start} - {all_day_stop}<div class=\'olListAllDay\'>Description </div>{all_day_desc}</div>');"
onmouseout="return nd();">{all_day_name}</a></td>

	<!-- BEGIN all_day_delete_check_tpl -->
		<td width="1%" align="right" style="cursor: pointer; background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalAllDayEvent.png') repeat;">
		  <a href="{www_dir}{index}/groupeventcalendar/eventedit/edit/{all_day_id}/">
          <img name="ezcal{event_id}-red" border="0" src="/images/redigermini.gif" width="12" height="12" align="top" alt="Edit" /></a>
		</td>
		<td width="1%" align="right" style="cursor: pointer; background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalAllDayEvent.png') repeat;">
		  <input type="checkbox" name="eventArrayID[]" value={all_day_id}>
		</td>
		<!-- END all_day_delete_check_tpl -->
<td width="5%" class="gcalDayViewTopBar" style="cursor: default; font-size: 8px;">All Day</td></tr>
</table></td></tr>
<!-- END all_day_event_tpl -->
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gcalBorder" style="background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalDayViewBg.png') repeat;">
<tr>
<td width=5% valign="top">
<table height="100%" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN time_display_tpl -->
<tr>
	<!-- BEGIN new_event_link_tpl -->
	<td class="{td_class}"  width="100%" id="gcalDayViewEventTimesHeight" style="text-align: center; border: 1px solid gray; border-right: 2px solid gray; background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;">
	<a class="path" style="font-size: 10px;" href="{www_dir}{index}/groupeventcalendar/eventedit/new/{year_number}/{month_number}/{day_number}/{display_start_time}/{group_print_id}/">{short_time}</a>
	</td>
	<!-- END new_event_link_tpl -->

	<!-- BEGIN no_new_event_link_tpl -->
	<td class="{td_class}" width="100%" id="gcalDayViewEventTimesHeight"
    style="text-align: center; border: 1px solid gray; border-right: 2px solid gray;  font-size: 10px;
    background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;" >{short_time}</td>
	<!-- END no_new_event_link_tpl -->
</tr>
<!-- END time_display_tpl -->
</table></td>
<td width="95%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<!-- BEGIN time_table_tpl -->
<!-- <tr><td style="height: 15px; width: 0px; overflow: hidden;"></td> -->
<!-- BEGIN fifteen_event_tpl -->
 <td class="{td_class}" valign="top" style=" overflow: hidden;
    background-color: #cc6666; background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalEventTransBg.png') repeat;">
 	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="88%" nowrap valign="top" class="gcalEventTopBar" style="height: 15px;">&nbsp;
		<a class='gcalDayEventText' href="{www_dir}{index}/groupeventcalendar/eventview/{event_id}/" onmouseover="
return overlib('<div class=\'olList\'>Name</div>{overlib_event_name}<div class=\'olList\'>Time</div> {event_start} - {event_stop}<div class=\'olList\'>Description </div>{overlib_event_description}');"
onmouseout="return nd();">{event_name}&nbsp;&nbsp;</a>

		</td>

		<!-- BEGIN fifteen_delete_check_tpl -->
		<td width="10%" valign="top" align="right" class="gcalEventTopBar" style="vertical-align: middle;">
                  <input type="checkbox" name="eventArrayID[]" value={event_id} style="width: 10px; height: 10px; text-decoration: none;"><a href="{www_dir}{index}/groupeventcalendar/eventedit/edit/{event_id}/">
                  <img name="ezcal{event_id}-red" border="0" src="/design/admin/images/redigermini.gif" width="12" height="12" align="top" alt="Edit" style="padding-top: 2px;" /></a>
		</td>
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">
		  &nbsp;
		</td>
		<!-- END fifteen_delete_check_tpl -->

		<!-- BEGIN fifteen_no_delete_check_tpl -->
            <td width="1%" valign="top" align="right" class="gcalEventTopBar">&nbsp;</td>
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">&nbsp;</td>
		<!-- END fifteen_no_delete_check_tpl -->
	</tr>
	</table>
	</td>
<!-- END fifteen_event_tpl -->
   <!-- BEGIN public_event_tpl -->
    <td class="{td_class}" valign="top" rowspan="{rowspan_value}" style="border: 1px solid black; overflow: hidden;
    background-color: #cc6666; background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalEventTransBg.png') repeat;">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="98%" nowrap valign="top" class="gcalEventTopBar" style="overflow: hidden; height: 15px;">
		<a class='gcalDayEventText' href="{www_dir}{index}/groupeventcalendar/eventview/{event_id}/" onmouseover="
return overlib('<div class=\'olList\'>Name</div>{overlib_event_name}<div class=\'olList\'>Time</div> {event_start} - {event_stop}<div class=\'olList\'>Description </div>{overlib_event_description}');"
onmouseout="return nd();">&nbsp;{event_name}&nbsp;</a>

		</td>

		<!-- BEGIN delete_check_tpl -->
		<td width="1%" align="right" class="gcalEventTopBar">
		  <a href="{www_dir}{index}/groupeventcalendar/eventedit/edit/{event_id}/">
          <img name="ezcal{event_id}-red" border="0" src="/images/redigermini.gif" width="12" height="12" alt="Edit" /></a>
		</td>
		<td width="1%" align="right" class="gcalEventTopBar">
		  <input type="checkbox" name="eventArrayID[]" value={event_id}>
		</td>
		<!-- END delete_check_tpl -->

		<!-- BEGIN no_delete_check_tpl -->
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">&nbsp;</td>
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">&nbsp;</td>
		<!-- END no_delete_check_tpl -->
	</tr>
	<tr>
		<td colspan="3"><div class="gcalDayEventText" style="font-weight: bold; overflow: hidden; height: {event_div_height}px;">
		&nbsp;{event_description}
        </div>
		</td>
	</tr>
	</table>
	</td>
	<!-- END public_event_tpl -->
	<!-- BEGIN private_event_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}">
	<b><i>{event_groupName} - {intl-private_event}</i></b>
	</td>
	<!-- END private_event_tpl -->
	<!-- BEGIN no_event_tpl -->
	<td valign="top" rowspan="{rowspan_value}" style="height: 15px;"></td>
	<!-- END no_event_tpl -->

<!-- END time_table_tpl -->
</tr>
</td></tr>
</table></td></tr>
 </table>
</form>
</div>
<script language="javascript">
  var mthDiv = document.getElementById("gcalDayViewMonthTableHeader");
  var mtDiv   = document.getElementById("gcalDayViewMonthTable");
  Drag.init(mthDiv, mtDiv);
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
