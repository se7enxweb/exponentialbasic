<!-- BEGIN user_error_tpl -->
	<!-- BEGIN no_user_error_tpl -->
	<p class="error">{intl-no_user_error}</p>
	<!-- END no_user_error_tpl -->

	<!-- BEGIN wrong_user_error_tpl -->
	<p class="error">{intl-wrong_user_error}</p>
	<!-- END wrong_user_error_tpl -->
<!-- END user_error_tpl -->


<!-- BEGIN no_error_tpl -->

<!-- BEGIN title_error_tpl -->
<p class="error">{intl-title_error}</p>
<!-- END title_error_tpl -->

<!-- BEGIN group_error_tpl -->
<p class="error">{intl-group_error}</p>
<!-- END group_error_tpl -->

<!-- BEGIN start_time_error_tpl -->
<p class="error">{intl-start_time_error}</p>
<!-- END start_time_error_tpl -->

<!-- BEGIN stop_time_error_tpl -->
<p class="error">{intl-stop_time_error}</p>
<!-- END stop_time_error_tpl -->


<form method="post" name="EventEdit" action="{www_dir}{index}/groupeventcalendar/eventedit/{action_value}/{event_id}/">

<!-- BEGIN top_buttons_tpl -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="right">
<tr>
<td style="height: 30px; text-align: right;">&nbsp;
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
      <!-- BEGIN add_file_list_tpl -->
	<span class="gcalSwitchBox">
	<input class="gcalSwitchBox" type="submit" name="AddFile" value="{intl-event_file_list}" 
	onmouseout="this.className='gcalSwitchBox'" onmouseover="this.className='gcalSwitchBoxSelect'" />
	</span>
      <!-- END add_file_list_tpl -->

</td>
</tr>
</table>
<!-- END top_buttons_tpl -->

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="gcalEventEditMain">
<tr>
	<td id="gcalBigHeader" style="border: 0px; background: url('{www_dir}{index}/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;" colspan=7>
	<span class="gcalBigHeader">
    {intl-event_edit}
    </span>
	</td>
</tr>
<tr>
<td width="100%"align="center">
<div class="gcalWrapFrm">
<p class="boxtext">{intl-event_title}:</p>
<input class="gcalTextFrm" type="text" size="50" name="Name" value="{name_value}"
 onFocus="this.className='gcalTextFocusFrm'"
 onBlur="this.className='gcalTextFrm'" />

<p class="boxtext">{intl-event_location}:</p>
<input class="gcalTextFrm" type="text" size="50" name="Location" value="{location_value}"
onFocus="this.className='gcalTextFocusFrm'"
 onBlur="this.className='gcalTextFrm'" />

<p class="boxtext">{intl-event_url}:</p>
<input class="gcalTextFrm" type="text" size="50" name="Url" value="{url_value}"
onFocus="this.className='gcalTextFocusFrm'"
 onBlur="this.className='gcalTextFrm'" />

<p class="boxtext">{intl-event_description}:</p>
<textarea class="gcalTextFrm" name="Description" cols="50" rows="7" wrap="soft"
onFocus="this.className='gcalTextFocusFrm'"
 onBlur="this.className='gcalTextFrm'" >{description_value}</textarea>
</div> <!-- end gcalWrapFrm -->

<div class="gcalWrapFrm">
<div class="gcalGroupBoxFrm">
<!-- BEGIN group_name_edit_tpl -->
<span class="boxtext">{intl-event_group}:&nbsp;&nbsp;{group_name}</span><br />
<input type="hidden" name="StoreByGroupID" value="{group_id}" />
<!-- END group_name_edit_tpl -->

<!-- BEGIN group_name_new_tpl -->

<span class="boxtext">{intl-event_group}:</span><br />
<select name="StoreByGroupID">
<option value="">Select</option>
<!-- BEGIN group_item_tpl -->
<option {group_is_selected} value="{group_member_id}">{group_member_name}</option>
<!-- END group_item_tpl -->
</select>

<!-- END group_name_new_tpl -->
<br />
<div align="left" style="margin-left: 25%;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input {is_private}  type="checkbox" name="IsPrivate" />&nbsp;<span class="check">{intl-private_event}</span>
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input {is_event_alarm_notice} type="checkbox" name="IsEventAlarmNotice" />&nbsp;<span class="check">{intl-event_notification}</span>
<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="FileUploadFlag" />&nbsp;<span class="check">{intl-AddFileFlag}</span>
</div>

</div>

<div class="gcalGroupBoxFrm" style="padding: 3px;">
<table border="0" cellpadding="0" cellspacing="0" width="62%">
<tr>
<td valign="top">
  <span class="boxtext">{intl-type}:</span>
</td>
<td valign="top">
  <select name="TypeID">
  <option></option>
  <!-- BEGIN value_tpl -->
    <option value="{option_value}" {type_is_selected}>{option_level}{option_name}</option>
  <!-- END value_tpl -->
  </select>
  <br />
</td>
</tr>
<tr>
<td valign="top">
  <span class="boxtext">{intl-category}:</span>
</td>
<td valign="top">
   <select name="CategoryID">
   <option></option>
   <!-- BEGIN category_value_tpl -->
     <option value="{option_category_value}" {category_is_selected}>{option_category_level}{category_name}</option>
   <!-- END category_value_tpl -->
   </select>
</td>
</tr>
<tr>
<td valign="top">
   <span class="boxtext">{intl-priority}:</span>
</td>
<td valign="top">
   <select name="Priority">
      <option value="0" {0_selected}>{intl-lowest_priority}</option>
      <option value="1" {1_selected}>{intl-low_priority}</option>
      <option value="2" {2_selected}>{intl-normal_priority}</option>
      <option value="3" {3_selected}>{intl-medium_priority}</option>
      <option value="4" {4_selected}>{intl-high_priority}</option>
      <option value="5" {5_selected}>{intl-highest_priority}</option>
   </select>
</td>
</tr>
<tr>
<td valign="top">
   <span class="boxtext">{intl-status}:</span>
</td>
<td valign="top">
   <select name="Status">
   <option value="0" {0_status_selected}>{intl-tentative_status}</option>
   <option value="1" {1_status_selected}>{intl-confirmed_status}</option>
   <option value="2" {2_status_selected}>{intl-cancelled_status}</option>
   </select>
</td>
</tr>
</table>

</div>
</div> <!-- end gcalWrapFrm -->

<div class="gcalWrapFrm">
<!-- BEGIN dhtml_form_datetime_select_tpl -->
<p class="boxtext">{intl-select-date-time}:</p>

<input class="gcalCalTextFrm" type="text" name="dateCal" id="sel1" size="22" value='{date_calendar}' readonly><input class="gcalSubmitFrm" style="height: 25px; border-left: 0px;" type="reset" value=" ... " onclick="return showCalendar('sel1', '%Y-%m-%d');"
onmouseout="this.className='gcalSubmitFrm'"
onmouseover="this.className='gcalSubmitOverFrm'">
<!-- END dhtml_form_datetime_select_tpl -->

<br />
<div class="gcalGroupBoxFrm" style="padding: 3px;">
<input {is_all_day} type="checkbox" name="IsAllDay" onChange="resetTimeSelect();" />&nbsp;<span class="check">{intl-all_day_event}</span>

<!-- BEGIN html_form_datetime_select_tpl -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
	<td valign="top">
	<p class="boxtext">{intl-event_start}:</p>
		<select name="Start_Hour" onChange="resetAllDayCheck();">
		<option></option>
		<!-- BEGIN start_hour_item_tpl -->
		<option value="{start_hour}" {is_start_hour_selected}>{start_hour}</option>
		<!-- END start_hour_item_tpl -->
		</select>
		<i> {intl-hour} </i>
		&nbsp;&nbsp;
		<select name="Start_Minute" onChange="resetAllDayCheck();">
		<option></option>
		<!-- BEGIN start_minute_item_tpl -->
		<option value="{start_minute}" {is_start_minute_selected}>{start_minute}</option>
		<!-- END start_minute_item_tpl -->
		</select>
		<i>{intl-minute}</i>
	</td>
	<td>&nbsp;</td>
	<td valign="top">
	<p class="boxtext">{intl-event_stop}:</p>
		<select name="Stop_Hour" onChange="resetAllDayCheck();">
		<option></option>
		<!-- BEGIN stop_hour_item_tpl -->
		<option value="{stop_hour}" {is_stop_hour_selected}>{stop_hour}</option>
		<!-- END stop_hour_item_tpl -->
		</select>
		<i> {intl-hour} </i>
		&nbsp;&nbsp;
		<select name="Stop_Minute" onChange="resetAllDayCheck();">
		<option></option>
		<!-- BEGIN stop_minute_item_tpl -->
		<option value="{stop_minute}" {is_stop_minute_selected}>{stop_minute}</option>
		<!-- END stop_minute_item_tpl -->
		</select>
		<i>{intl-minute}</i>
	</td>
</tr>
<tr>
	<td>
		<!-- BEGIN start_ampm_radio_tpl -->
                <input type="radio" name="Start_AM_PM" onchange="alert(document.forms.EventEdit.Start_AM_PM.value);" value="am" {start_am}>&nbsp;&nbsp;am&nbsp;&nbsp;
                <input type="radio" name="Start_AM_PM" value="pm" {start_pm}>&nbsp;&nbsp;pm
                <!-- END start_ampm_radio_tpl -->
        </td>
	<td>&nbsp;</td>
        <td>
                <!-- BEGIN stop_ampm_radio_tpl -->
                <input type="radio" name="Stop_AM_PM" value="am" {stop_am}>&nbsp;&nbsp;am&nbsp;&nbsp;
                <input type="radio" name="Stop_AM_PM" value="pm" {stop_pm}>&nbsp;&nbsp;pm
                <!-- END stop_ampm_radio_tpl -->
        </td>
</tr>
</table>
<!-- END html_form_datetime_select_tpl -->
</div>
</div> <!-- end gcalWrapFrm -->
<br />
<!-- start recurring_event stuff -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td align="center"><p class="boxtext">{intl-recurring_event}:</p></td>
</tr>
<tr>
	<td valign="top" align="center">
		<span id="gcalRecurSpan" class="gcalRCheckFrm">
		 <input {is_recurring} type="checkbox" name="IsRecurring" 
		 onclick="toggleRecurringEventForm()" />&nbsp;{intl-make_recurring}
		</span>
	</td>
</tr>
</table>
<div id="gcalRecurringFormWrap">
 {intl-repeat_int} <input type="text" size="4" name="RecurFreq" value="{recur_freq}" /> 
 <select name="RecurType" onChange="toggleRecurTypeLayer();">
   <option value="day" {rtselect_day}>{intl-event_day}</option>
   <option value="week" {rtselect_week}>{intl-event_week}</option>
   <option value="month" {rtselect_month}>{intl-event_month}</option>
   <option value="year" {rtselect_year}>{intl-event_year}</option>
 </select>

 <div id="gcalRecurringWeekly" class="gcalGroupBoxFrm">
 <input type="checkbox" value="mon" name="RecurWeekly[]" id="RecurWeekly" {recur_weekly_mon} />&nbsp;<span class="check">{intl-mon}</span><br />
 <input type="checkbox" value="tue" name="RecurWeekly[]" id="RecurWeekly" {recur_weekly_tue} />&nbsp;<span class="check">{intl-tue}</span><br />
 <input type="checkbox" value="wed" name="RecurWeekly[]" id="RecurWeekly" {recur_weekly_wed} />&nbsp;<span class="check">{intl-wed}</span><br />
 <input type="checkbox" value="thu" name="RecurWeekly[]" id="RecurWeekly" {recur_weekly_thu} />&nbsp;<span class="check">{intl-thu}</span><br />
 <input type="checkbox" value="fri" name="RecurWeekly[]" id="RecurWeekly" {recur_weekly_fri} />&nbsp;<span class="check">{intl-fri}</span><br />
 <input type="checkbox" value="sat" name="RecurWeekly[]" id="RecurWeekly" {recur_weekly_sat} />&nbsp;<span class="check">{intl-sat}</span><br />
 <input type="checkbox" value="sun" name="RecurWeekly[]" id="RecurWeekly" {recur_weekly_sun} />&nbsp;<span class="check">{intl-sun}</span>
 </div>
 <div id="gcalRecurringMonthly" class="gcalGroupBoxFrm">
   <input type="radio" name="RecurTypeMonth" value="daily" {start_daily} />&nbsp;&nbsp;
   [current date] {intl-of_the_month}. <i>ex. 26th of the month.</i>
   <br />
   <input type="radio" name="RecurTypeMonth" value="numdayname" {start_numdayname} />&nbsp;&nbsp;
   [first|second|third|fourth] [day name] {intl-of_the_month}. <i>ex. Third Monday {intl-of_the_month}.</i>
   <br />
   <input type="radio" name="RecurTypeMonth" value="strdayname" {start_strdayname} />&nbsp;&nbsp;
   [last] [day name] {intl-of_the_month}. <i> ex. Last Thursday {intl-of_the_month}.</i>
 </div>
 <div class="gcalGroupBoxFrm">
 <input type="radio" style="padding-bottom:5px;" name="RepeatOptions" value="forever" {repeat_forever} /> {intl-repeat_forever}
 <br />
 <input type="radio" style="padding-bottom:5px;" name="RepeatOptions" value="numTimes" {repeat_times} /> {intl-repeat_number} <input class="gcalTextFrm" style="margin-bottom: 3px; padding: 1px; padding-left: 3px;" type="text" size="4" name="NumberOfTimes" value='{num_times}'
 onFocus="this.className='gcalTextFocusFrm'"
 onBlur="this.className='gcalTextFrm'"
 />
 <br />
 <input type="radio" name="RepeatOptions"  value="untilDate" {repeat_until} /> {intl-repeat_until} <input class="gcalCalTextFrm" type="text" size="20" name="UntilDate" value='{until_date}' id="untilDate" readonly><input class="gcalSubmitFrm" style="height: 25px; border-left: 0px;" type="reset" value=" ... "
onmouseout="this.className='gcalSubmitFrm'"
onmouseover="this.className='gcalSubmitOverFrm'"
 onclick="return showCalendar('untilDate', '%Y-%m-%d');" >
 </div>
 <div class="gcalGroupBoxFrm">
{intl-repeat_exceptions} <br />
 <a onclick='addToList()' style="font-size: 9px;" class="gcalSubmitFrm">{intl-repeat_exception_add}</a>
 <a onclick='removeFromList("ExceptSelect")' style="font-size: 9px;" class="gcalSubmitFrm">{intl-repeat_exception_remove}</a><br /><br />
 <input type="text" class="gcalCalTextFrm" size=12 name="RecurExceptions" id="RecurExceptions" readonly /><input class="gcalSubmitFrm" style="height: 25px; border-left: 0px;" type="reset" value=" ... " 
 onmouseout="this.className='gcalSubmitFrm'"
 onmouseover="this.className='gcalSubmitOverFrm'"
 onclick="return showCalendar('RecurExceptions', '%Y-%m-%d');" > <br /><br />
 <select name="ExceptSelect[]" size=4 style="border: 2px solid black; outline:none; margin: 5px; width: 100px;" id="ExceptSelect" multiple>
<!-- BEGIN recur_exceptions_tpl -->
{recur_exception}
<!-- END recur_exceptions_tpl --> 
 </select>
 </div>
</div>
</td>
</tr>
</table>
<!-- End recurring event stuff -->

<script language="JavaScript">
<!--hide this script from non-javascript-enabled browsers
toggleRecurTypeLayer();
toggleRecurringEventForm();
function toggleRecurTypeLayer() {
var frm = document.forms.EventEdit;
if ( frm.IsRecurring.checked == true ) { 
 hideDiv('gcalRecurringWeekly', 'gcalRecurringMonthly');
 var field = document.EventEdit.RecurType
 var option = field.options[field.selectedIndex].value;
 if (option == "week") { showDiv('gcalRecurringWeekly'); }
 else if (option == "month") { showDiv('gcalRecurringMonthly') }
 }
}

function hideDiv() {
var arga = hideDiv.arguments;
var argb = arga.length;

if (document.getElementById) {
	 for (var i=0; i < argb; i++) {
		 document.getElementById(arga[i]).style.display = "none";
	 }
  }
else if (document.all) {
	for (var i=0; i < argb; i++) {
document.all[arga[i]].style.display = "none";
	 }
  }
else if (document.layers) {
	for (var i=0; i < argb; i++) {
document.layers[arga[i]].display = "none";
	 }
  }
}

function showDiv() {
var arga = showDiv.arguments;
var argb = arga.length;

if (document.getElementById) {
			for (var i=0; i < argb; i++) {
document.getElementById(arga[i]).style.display = "block";
	 }
  }
else if (document.all) {
			for (var i=0; i < argb; i++) {
document.all[arga[i]].style.display = "block";	
	 }
  }
else if (document.layers) {
		for (var i=0; i < argb; i++) {
document.layers[arga[i]].display = "block";
	 }
  }
}

function toggleRecurringEventForm() {
    var field = document.forms.EventEdit;
    if ( field.IsRecurring.checked == true ) {
        showDiv('gcalRecurringFormWrap');
	document.getElementById('gcalRecurSpan').className='gcalRCheckFocusFrm';
	} else {
	hideDiv('gcalRecurringFormWrap');
	document.getElementById('gcalRecurSpan').className='gcalRCheckFrm';
    }
}
function resetAllDayCheck() {
    var field = document.forms.EventEdit;
    if ( field.IsAllDay.checked == true ) {
	if (! isEmpty(field.Start_Hour.selectedIndex) || ! isEmpty(field.Start_Minute.selectedIndex) || ! isEmpty(field.Stop_Hour.selectedIndex) || ! isEmpty(field.Stop_Minute.selectedIndex)) {
            if ( confirm( "By selecting a specific time frame, you cannot have All Day Event selected.  Are you sure you wish to specify a time frame?  If yes press OK, if now press CANCEL" ) ) {
	        field.IsAllDay.checked = false;
	    }
	    else
	    {
                field.Start_Hour.selectedIndex = "";
                field.Start_Minute.selectedIndex = "";
	        field.Stop_Hour.selectedIndex = "";
	        field.Stop_Minute.selectedIndex = "";
	    }
        }
    }
}

function resetTimeSelect() {
    var field = document.forms.EventEdit;
    if (! isEmpty(field.Start_Hour.selectedIndex) || ! isEmpty(field.Start_Minute.selectedIndex) || ! isEmpty(field.Stop_Hour.selectedIndex) || ! isEmpty(field.Stop_Minute.selectedIndex)) {
        if ( field.IsAllDay.checked == true ) {
	    if ( confirm( "By selecting All Day Event, you cannot specify a time frame.  Are you sure you wish to select All Day Event?  If yes press OK, if now press CANCEL" ) ) {
                field.Start_Hour.selectedIndex = "";
                field.Start_Minute.selectedIndex = "";
	        field.Stop_Hour.selectedIndex = "";
	        field.Stop_Minute.selectedIndex = "";
	    }
	    else
	    {
	        field.IsAllDay.checked = false;
	    }
        }
    }
}

function isEmpty(inputStr) {
    if (inputStr == null || inputStr == "") {
        return true;
    }
    return false;
}

// utility functions for date calculations

function isLeapYear(intYear) 
{
    if (intYear % 100 == 0) 
    {
        if (intYear % 400 == 0) { return true; }
    }
    else 
    {
        if ((intYear % 4) == 0) { return true; }
    }
    return false;
}

function isDayValidForThisMonthAndYear(intDay,intMonth,intYear) 
{
    if ((intMonth == 4 || intMonth == 6 || intMonth == 9 || intMonth == 11) && intDay > 30 ) 
    {
        return false;
    }

    if (intMonth == 2) 
    {
        if (isLeapYear(intYear)) 
        {
            if (intDay > 29) 
                {
                return false;
                }
        }
        else 
        {
            if (intDay > 28) 
            {
                return false;
            }
        }
    }
    return true;
}

function getDaysInMonth(month,year)  {
var days;
if (month==1 || month==3 || month==5 || month==7 || month==8 || month==10 || month==12)  days=31;
else if (month==4 || month==6 || month==9 || month==11) days=30;
else if (month==2)  {
if (isLeapYear(year)) { days=29; }
else { days=28; }
}
return (days);
}
function convertTo24(hour, minute, ampm)
{
var ret;
if (hour.substring(0, 1) == 0)
ret = hour.substring(1,2);
else
ret = hour;
if (ampm[1].checked) ret = ret + 12

ret += minute;
return parseInt(ret);
}
function addToList(textField, selectField) {
   tex = document.forms.EventEdit.RecurExceptions;
   sel = document.forms.EventEdit.ExceptSelect;
   if ( ( tex.value == "" ) ) {
      alert("You cannot add blank values!");
   } else {
      var len = sel.length++; // Increase the size of list and return the size
      sel.options[len].value = tex.value;
      sel.options[len].text = tex.value;
      sel.selectedIndex = len; // Highlight the one just entered (shows the user that it was entered)
   } // Ends the check to see if the value entered on the form is empty
}

function removeFromList() {
    field = document.forms.EventEdit.ExceptSelect;
   if ( field.length == -1) {  // If the list is empty
      alert("There are no values which can be removed!");
   } else {
      var selected = field.selectedIndex;
      if (selected == -1) {
         alert("You must select an entry to be removed!");
      } else {  // Build arrays with the text and values to remain
         var replaceTextArray = new Array(field.length-1);
         var replaceValueArray = new Array(field.length-1);
         for (var i = 0; i < field.length; i++) {
            // Put everything except the selected one into the array
            if ( i < selected) { replaceTextArray[i] = field.options[i].text; }
            if ( i > selected ) { replaceTextArray[i-1] = field.options[i].text; }
            if ( i < selected) { replaceValueArray[i] = field.options[i].value; }
            if ( i > selected ) { replaceValueArray[i-1] = field.options[i].value; }
         }
         field.length = replaceTextArray.length;  // Shorten the input list
         for (i = 0; i < replaceTextArray.length; i++) { // Put the array back into the list
            field.options[i].value = replaceValueArray[i];
            field.options[i].text = replaceTextArray[i];
         }
      } // Ends the check to make sure something was selected
   } // Ends the check for there being none in the list
}

// selects
function selectAll() {
field = document.forms.EventEdit.ExceptSelect;
for (i = 0; i < field.length; i++) {
field.options[i].selected = true;
}
}
function trim(s)   // from http://www.vermontsoftware.com/Javascript/trim.html
{
  // Remove leading spaces and carriage returns
  
  while ((s.substring(0,1) == ' ') || (s.substring(0,1) == '\n') || (s.substring(0,1) == '\r'))
  {
    s = s.substring(1,s.length);
  }

  // Remove trailing spaces and carriage returns

  while ((s.substring(s.length-1,s.length) == ' ') || (s.substring(s.length-1,s.length) == '\n') || (s.substring(s.length-1,s.length) == '\r'))
  {
    s = s.substring(0,s.length-1);
  }
  return s;
}
// form validation function

function formCheck(form) 
{
frm = document.forms.EventEdit;
selectAll();
///////////////////////////////////////////////////////////////////////////////////////
// broswer sniffer
///////////////////////////////////////////////////////////////////////////////////////

// Ultimate client-side JavaScript client sniff. Version 3.02
// (C) Netscape Communications 1999-2001.  Permission granted to reuse and distribute.
// Revised 17 May 99 to add is_nav5up and is_ie5up (see below).
// Revised 20 Dec 00 to add is_gecko and change is_nav5up to is_nav6up
//                      also added support for IE5.5 Opera4&5 HotJava3 AOLTV
// Revised 22 Feb 01 to correct Javascript Detection for IE 5.x, Opera 4, 
//                      correct Opera 5 detection
//                      add support for winME and win2k
//                      synch with browser-type-oo.js
// Revised 26 Mar 01 to correct Opera detection

// Everything you always wanted to know about your JavaScript client
// but were afraid to ask. Creates "is_" variables indicating:
// (1) browser vendor:
//     is_nav, is_ie, is_opera, is_hotjava, is_webtv, is_TVNavigator, is_AOLTV
// (2) browser version number:
//     is_major (integer indicating major version number: 2, 3, 4 ...)
//     is_minor (float   indicating full  version number: 2.02, 3.01, 4.04 ...)
// (3) browser vendor AND major version number
//     is_nav2, is_nav3, is_nav4, is_nav4up, is_nav6, is_nav6up, is_gecko, is_ie3,
//     is_ie4, is_ie4up, is_ie5, is_ie5up, is_ie5_5, is_ie5_5up, is_hotjava3, is_hotjava3up,
//     is_opera2, is_opera3, is_opera4, is_opera5, is_opera5up
// (4) JavaScript version number:
//     is_js (float indicating full JavaScript version number: 1, 1.1, 1.2 ...)
// (5) OS platform and version:
//     is_win, is_win16, is_win32, is_win31, is_win95, is_winnt, is_win98, is_winme, is_win2k
//     is_os2
//     is_mac, is_mac68k, is_macppc
//     is_unix
//     is_sun, is_sun4, is_sun5, is_suni86
//     is_irix, is_irix5, is_irix6
//     is_hpux, is_hpux9, is_hpux10
//     is_aix, is_aix1, is_aix2, is_aix3, is_aix4
//     is_linux, is_sco, is_unixware, is_mpras, is_reliant
//     is_dec, is_sinix, is_freebsd, is_bsd
//     is_vms
//
// See http://www.it97.de/JavaScript/JS_tutorial/bstat/navobj.html and
// http://www.it97.de/JavaScript/JS_tutorial/bstat/Browseraol.html
// for detailed lists of userAgent strings.
//
// Note: you don't want your Nav4 or IE4 code to "turn off" or
// stop working when new versions of browsers are released, so
// in conditional code forks, use is_ie5up ("IE 5.0 or greater") 
// is_opera5up ("Opera 5.0 or greater") instead of is_ie5 or is_opera5
// to check version in code which you want to work on future
// versions.

    // convert all characters to lowercase to simplify testing
    var agt=navigator.userAgent.toLowerCase();

    // *** BROWSER VERSION ***
    // Note: On IE5, these return 4, so use is_ie5up to detect IE5.
    var is_major = parseInt(navigator.appVersion);
    var is_minor = parseFloat(navigator.appVersion);

    // Note: Opera and WebTV spoof Navigator.  We do strict client detection.
    // If you want to allow spoofing, take out the tests for opera and webtv.
    var is_nav  = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1)
                && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1)
                && (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1));
    var is_nav2 = (is_nav && (is_major == 2));
    var is_nav3 = (is_nav && (is_major == 3));
    var is_nav4 = (is_nav && (is_major == 4));
    var is_nav4up = (is_nav && (is_major >= 4));
    var is_navonly      = (is_nav && ((agt.indexOf(";nav") != -1) ||
                          (agt.indexOf("; nav") != -1)) );
    var is_nav6 = (is_nav && (is_major == 5));
    var is_nav6up = (is_nav && (is_major >= 5));
    var is_gecko = (agt.indexOf('gecko') != -1);


    var is_ie     = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
    var is_ie3    = (is_ie && (is_major < 4));
    var is_ie4    = (is_ie && (is_major == 4) && (agt.indexOf("msie 5")==-1) );
    var is_ie4up  = (is_ie && (is_major >= 4));
    var is_ie5    = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.0")!=-1) );
    var is_ie5_5  = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.5") !=-1));
    var is_ie5up  = (is_ie && !is_ie3 && !is_ie4);
    var is_ie5_5up =(is_ie && !is_ie3 && !is_ie4 && !is_ie5);

    // KNOWN BUG: On AOL4, returns false if IE3 is embedded browser
    // or if this is the first browser window opened.  Thus the
    // variables is_aol, is_aol3, and is_aol4 aren't 100% reliable.
    var is_aol   = (agt.indexOf("aol") != -1);
    var is_aol3  = (is_aol && is_ie3);
    var is_aol4  = (is_aol && is_ie4);
    var is_aol5  = (agt.indexOf("aol 5") != -1);
    var is_aol6  = (agt.indexOf("aol 6") != -1);

    var is_opera = (agt.indexOf("opera") != -1);
    var is_opera2 = (agt.indexOf("opera 2") != -1 || agt.indexOf("opera/2") != -1);
    var is_opera3 = (agt.indexOf("opera 3") != -1 || agt.indexOf("opera/3") != -1);
    var is_opera4 = (agt.indexOf("opera 4") != -1 || agt.indexOf("opera/4") != -1);
    var is_opera5 = (agt.indexOf("opera 5") != -1 || agt.indexOf("opera/5") != -1);
    var is_opera5up = (is_opera && !is_opera2 && !is_opera3 && !is_opera4);

    var is_webtv = (agt.indexOf("webtv") != -1); 

    var is_TVNavigator = ((agt.indexOf("navio") != -1) || (agt.indexOf("navio_aoltv") != -1)); 
    var is_AOLTV = is_TVNavigator;

    var is_hotjava = (agt.indexOf("hotjava") != -1);
    var is_hotjava3 = (is_hotjava && (is_major == 3));
    var is_hotjava3up = (is_hotjava && (is_major >= 3));

    // *** JAVASCRIPT VERSION CHECK ***
    var is_js;
    if (is_nav2 || is_ie3) is_js = 1.0;
    else if (is_nav3) is_js = 1.1;
    else if (is_opera5up) is_js = 1.3;
    else if (is_opera) is_js = 1.1;
    else if ((is_nav4 && (is_minor <= 4.05)) || is_ie4) is_js = 1.2;
    else if ((is_nav4 && (is_minor > 4.05)) || is_ie5) is_js = 1.3;
    else if (is_hotjava3up) is_js = 1.4;
    else if (is_nav6 || is_gecko) is_js = 1.5;
    // NOTE: In the future, update this code when newer versions of JS
    // are released. For now, we try to provide some upward compatibility
    // so that future versions of Nav and IE will show they are at
    // *least* JS 1.x capable. Always check for JS version compatibility
    // with > or >=.
    else if (is_nav6up) is_js = 1.5;
    // NOTE: ie5up on mac is 1.4
    else if (is_ie5up) is_js = 1.3

    // HACK: no idea for other browsers; always check for JS version with > or >=
    else is_js = 0.0;

    // *** PLATFORM ***
    var is_win   = ( (agt.indexOf("win")!=-1) || (agt.indexOf("16bit")!=-1) );
    // NOTE: On Opera 3.0, the userAgent string includes "Windows 95/NT4" on all
    //        Win32, so you can't distinguish between Win95 and WinNT.
    var is_win95 = ((agt.indexOf("win95")!=-1) || (agt.indexOf("windows 95")!=-1));

    // is this a 16 bit compiled version?
    var is_win16 = ((agt.indexOf("win16")!=-1) || 
               (agt.indexOf("16bit")!=-1) || (agt.indexOf("windows 3.1")!=-1) || 
               (agt.indexOf("windows 16-bit")!=-1) );  

    var is_win31 = ((agt.indexOf("windows 3.1")!=-1) || (agt.indexOf("win16")!=-1) ||
                    (agt.indexOf("windows 16-bit")!=-1));

    var is_winme = ((agt.indexOf("win 9x 4.90")!=-1));
    var is_win2k = ((agt.indexOf("windows nt 5.0")!=-1));

    // NOTE: Reliable detection of Win98 may not be possible. It appears that:
    //       - On Nav 4.x and before you'll get plain "Windows" in userAgent.
    //       - On Mercury client, the 32-bit version will return "Win98", but
    //         the 16-bit version running on Win98 will still return "Win95".
    var is_win98 = ((agt.indexOf("win98")!=-1) || (agt.indexOf("windows 98")!=-1));
    var is_winnt = ((agt.indexOf("winnt")!=-1) || (agt.indexOf("windows nt")!=-1));
    var is_win32 = (is_win95 || is_winnt || is_win98 || 
                    ((is_major >= 4) && (navigator.platform == "Win32")) ||
                    (agt.indexOf("win32")!=-1) || (agt.indexOf("32bit")!=-1));

    var is_os2   = ((agt.indexOf("os/2")!=-1) || 
                    (navigator.appVersion.indexOf("OS/2")!=-1) ||   
                    (agt.indexOf("ibm-webexplorer")!=-1));

    var is_mac    = (agt.indexOf("mac")!=-1);
    // hack ie5 js version for mac
    if (is_mac && is_ie5up) is_js = 1.4;
    var is_mac68k = (is_mac && ((agt.indexOf("68k")!=-1) || 
                               (agt.indexOf("68000")!=-1)));
    var is_macppc = (is_mac && ((agt.indexOf("ppc")!=-1) || 
                                (agt.indexOf("powerpc")!=-1)));

    var is_sun   = (agt.indexOf("sunos")!=-1);
    var is_sun4  = (agt.indexOf("sunos 4")!=-1);
    var is_sun5  = (agt.indexOf("sunos 5")!=-1);
    var is_suni86= (is_sun && (agt.indexOf("i86")!=-1));
    var is_irix  = (agt.indexOf("irix") !=-1);    // SGI
    var is_irix5 = (agt.indexOf("irix 5") !=-1);
    var is_irix6 = ((agt.indexOf("irix 6") !=-1) || (agt.indexOf("irix6") !=-1));
    var is_hpux  = (agt.indexOf("hp-ux")!=-1);
    var is_hpux9 = (is_hpux && (agt.indexOf("09.")!=-1));
    var is_hpux10= (is_hpux && (agt.indexOf("10.")!=-1));
    var is_aix   = (agt.indexOf("aix") !=-1);      // IBM
    var is_aix1  = (agt.indexOf("aix 1") !=-1);    
    var is_aix2  = (agt.indexOf("aix 2") !=-1);    
    var is_aix3  = (agt.indexOf("aix 3") !=-1);    
    var is_aix4  = (agt.indexOf("aix 4") !=-1);    
    var is_linux = (agt.indexOf("inux")!=-1);
    var is_sco   = (agt.indexOf("sco")!=-1) || (agt.indexOf("unix_sv")!=-1);
    var is_unixware = (agt.indexOf("unix_system_v")!=-1); 
    var is_mpras    = (agt.indexOf("ncr")!=-1); 
    var is_reliant  = (agt.indexOf("reliantunix")!=-1);
    var is_dec   = ((agt.indexOf("dec")!=-1) || (agt.indexOf("osf1")!=-1) || 
           (agt.indexOf("dec_alpha")!=-1) || (agt.indexOf("alphaserver")!=-1) || 
           (agt.indexOf("ultrix")!=-1) || (agt.indexOf("alphastation")!=-1)); 
    var is_sinix = (agt.indexOf("sinix")!=-1);
    var is_freebsd = (agt.indexOf("freebsd")!=-1);
    var is_bsd = (agt.indexOf("bsd")!=-1);
    var is_unix  = ((agt.indexOf("x11")!=-1) || is_sun || is_irix || is_hpux || 
                 is_sco ||is_unixware || is_mpras || is_reliant || 
                 is_dec || is_sinix || is_aix || is_linux || is_bsd || is_freebsd);

    var is_vms   = ((agt.indexOf("vax")!=-1) || (agt.indexOf("openvms")!=-1));

///////////////////////////////////////////////////////////////////////////////////////
// begin form validation
///////////////////////////////////////////////////////////////////////////////////////

    // if this client is running an unusable version of JavaScript, skip this validation
    if (is_js < 1.1)
    {
        return true;
    }

    // read in values from the form
    /* spectrum removing this section.
    var strSuppliedDay = form.Day.options[form.Day.selectedIndex].text;
    var strSuppliedMonth = form.Month.options[form.Month.selectedIndex].text;
    var strSuppliedYear = form.Year.options[form.Year.selectedIndex].text;


    // convert supplied date strings to IETF date format
    //var strSuppliedDate = "" + strSuppliedDay + " " + strSuppliedMonth + " " + strSuppliedYear + " 00:00:00 CST";
    var strSuppliedDate = "" + strSuppliedDay + " " + strSuppliedMonth + " " + strSuppliedYear + " 23:59:59 CST";

    // get today's date in GMTmilliseconds format
    var Today = new Date();

    // convert supplied date to GMTmilliseconds format
    var SuppliedDate = new Date(strSuppliedDate);

    // if the resulting date object is not a number, there's something wrong with it
    if (isNaN(SuppliedDate)) 
    {
        alert("INVALID CANCEL BY DATE: please supply a valid 'cancel by' date.");
        return false;
    }

    // Compare Today to SuppliedDate
    // If SuppliedDate is less than Today, alert the user, and return false
    if (SuppliedDate <= Today)
    {

        alert("INVALID EVENT DATE: please supply a date that occurs in the future.");
        return false;
    }

    // check to see if the supplied day value is valid for the supplied month and year
    if (!isDayValidForThisMonthAndYear(parseInt(strSuppliedDay), form.Month.selectedIndex, parseInt(strSuppliedYear)))
    {
        alert("INVALID EVENT DATE: are you sure there are " + strSuppliedDay + " days in " + strSuppliedMonth + "?");
        return false;
    }
    */


 var startHourF = document.forms.EventEdit.Start_Hour;
 var startMinuteF = document.forms.EventEdit.Start_Minute;
 var startAMPM = document.forms.EventEdit.Start_AM_PM;
 var stopHourF = document.forms.EventEdit.Stop_Hour;
 var stopMinuteF = document.forms.EventEdit.Stop_Minute;
 var stopAMPM = document.forms.EventEdit.Stop_AM_PM;
 if (frm.IsAllDay.checked == false)
 {
  if (startHourF.value == '' || startMinuteF.value == '' || stopHourF.value == '' || stopMinuteF.value == '')
  {
   alert("Start and Stop Times cannot be blank.")
   return false;
  }
  var compStart = convertTo24(startHourF.value, startMinuteF.value, startAMPM);
  var compStop = convertTo24(stopHourF.value, stopMinuteF.value, stopAMPM);
  if (compStart >= compStop)
  {
   alert ("The start time must be earlier than the stop time.");
   return false;
  }
 }
 
 if (frm.Name.value == '')
 {
  alert("The title field cannot be blank.");
  return false;
 }
        // start recurring event checks
if (frm.IsRecurring.checked) {
    // integer regexp 
    var intReg = new RegExp("^[0-9]+$");
    // validate RecurFreq - must be positive int
    if (intReg.exec(frm.RecurFreq.value) == null)
    {
     alert("Please use a whole number for Recurrance Frequency.")
     return false;
    }
    // if RecurType is week, make sure RecurWeekly has at least one box checked 
    if (frm.RecurType.value == "week")
    {
     var rwCheck = false;
     for (i=0; i<frm.RecurWeekly.length; i++)
     {
      if (frm.RecurWeekly[i].checked)
       var rwCheck = true;
     }
     if (!rwCheck)
     {
      alert ("You must check at least one day to repeat this event.");
      return false;
     }
    }
    
    // if RecurType is month, make sure RecurTypeMonth has a radio button checked.
    if (frm.RecurType.value == "month")
    {
     var rmCheck = false;
     for (i=0; i<frm.RecurTypeMonth.length; i++)
     {
      if (frm.RecurTypeMonth[i].checked)
       var rmCheck = true;
     }
     if (!rmCheck)
     {
      alert ("You must select the type of monthly recurrance you would like.");
      return false;
     }
    }
    
    // make sure a repeat option is checked
     var roCheck = false;
     for (i=0; i<frm.RepeatOptions.length; i++)
     {
      if (frm.RepeatOptions[i].checked)
       var roCheck = true;
     }
     if (!roCheck)
     {
      alert ("You must select a repeat option.");
      return false;
     }
    
    // if Number of times is checked, make sure NumberOfTimes is a positive int
    if (frm.RepeatOptions[1].checked && intReg.exec(frm.NumberOfTimes.value) == null)
    {
     alert ("The number of times to repeat the event must be a whole number.");  
     return false;
    }
    // if until date is checked, make sure UntilDate is filled
    
    if (frm.RepeatOptions[2].checked && !frm.UntilDate.value)
    {
     alert ("You must specify the date you would like this event to expire.");  
     return false;
    }
}
    // if we made it this far, the date must be good
    return true;

}

// stop hiding -->
</script>

<hr noshade size="4" />

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td>
        <input class="gcalSwitchBox" type="submit" name="Submit" value="{intl-ok}"
        onclick="return formCheck(this)" 
	onmouseout="this.className='gcalSwitchBox'"
	onmouseover="this.className='gcalSwitchBoxSelect'"
	/>
        <input class="gcalSwitchBox" type="submit" name="Cancel" value="{intl-cancel}" 
	onmouseout="this.className='gcalSwitchBox'"
	onmouseover="this.className='gcalSwitchBoxSelect'"
	/>
        <input type="hidden" name="Action" value="{action_value}" />
        <input type="hidden" name="eventID" value="{event_id}" />
    </td>
    <td align="right">
        <input type="hidden" name="eventArrayID[]" value={event_id}>
        <input class="gcalSwitchBox" type="submit" name="DeleteEvents" value="{intl-delete_events}"
	onmouseout="this.className='gcalSwitchBox'"
	onmouseover="this.className='gcalSwitchBoxSelect'"
	>
    </td>
</tr>
</table>

</form>
<!-- END no_error_tpl -->
