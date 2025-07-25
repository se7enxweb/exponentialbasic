<?
// 
// $Id: eventedit.php,v 1.36 2001/03/12 13:55:47 fh Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

// include_once( "classes/ezhttptool.php" );

$URL = explode( "/", $_SERVER['REQUEST_URI'] );

if( isset( $URL[8] ) && is_numeric( $URL[8] ) )
    $masterGroupID = $URL[8];
else
    $masterGroupID = 0;


if ( isset( $DeleteEvents ) )
{
    $Action = "DeleteEvents";
}

if ( isset( $GoDay ) )
{
    //include_once( "classes/ezdate.php" );

    $session =& eZSession::globalSession();
    $session->fetch();

    $year = $session->variable( "Year" );
    $month = $session->variable( "Month" );
    $day = $session->variable( "Day" );

    $date = new eZDate( $year, $month, $day );
    if ( $date->daysInMonth() < $day )
        $day = $date->daysInMonth();

    eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
    exit();
}
else if ( isSet( $GoWeek ) )
{
  //include_once( "classes/ezdate.php" );

  $session =& eZSession::globalSession();
  $session->fetch();

  $year = $session->variable( "Year" );
  $month = $session->variable( "Month" );
  $day = $session->variable( "Day" );

  $date = new eZDate( $year, $month, $day );
  if ( $date->daysInMonth() < $day )
    $day = $date->daysInMonth();

  eZHTTPTool::header( "Location: /groupeventcalendar/weekview/$year/$month/$day/" );
  exit();
}
else if ( isSet( $GoMonth ) )
{
    $session =& eZSession::globalSession();
    $session->fetch();

    $year = $session->variable( "Year" );
    $month = $session->variable( "Month" );

    eZHTTPTool::header( "Location: /groupeventcalendar/monthview/$year/$month/" );
    exit();
}
else if ( isSet( $GoYear ) )
{
    $session =& eZSession::globalSession();
    $session->fetch();

    $year = $session->variable( "Year" );

    eZHTTPTool::header( "Location: /groupeventcalendar/yearview/$year/" );
    exit();
}
else if ( isSet( $GoToday ) )
{
    $today = new eZDate();

    $year = addZero( $today->year() );
    $month = addZero( $today->month() );
    $day = addZero( $today->day() );

    eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
    exit();
}
else if ( isSet( $GoNew ) )
{
    $today = new eZDate();

    $year = addZero( $today->year() );
    $month = addZero( $today->month() );
    $day = addZero( $today->day() );

    eZHTTPTool::header( "Location: /groupeventcalendar/eventedit/new/$year/$month/$day/" );
    exit();
}

if ( isSet( $AddFile ) )
{
  //  $Action = "AddFile";
  // add files
  eZHTTPTool::header( "Location: /groupeventcalendar/eventedit/filelist/$eventID/" );
  exit();
}


// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "classes/ezdate.php" );
// include_once( "classes/eztime.php" );
// //include_once( "classes/eztimetime.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );

// include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
// include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );
// include_once( "ezgroupeventcalendar/classes/ezgroupeventcategory.php" );

// include_once( "ezgroupeventcalendar/classes/ezgroupeditor.php" );
// include_once( "ezgroupeventcalendar/classes/ezgroupnoshow.php" );

$ini =& INIFile::globalINI();

$SiteDesign = $ini->read_var( "site", "SiteDesign" );
$Language = $ini->read_var( "eZGroupEventCalendarMain", "Language" );
$StartTimeStr = $ini->read_var( "eZGroupEventCalendarMain", "DayStartTime" );
$StopTimeStr = $ini->read_var( "eZGroupEventCalendarMain", "DayStopTime" );
$timeSelect = $ini->read_var( "eZGroupEventCalendarMain", "TwelveHourSelect" );

$Locale = new eZLocale( $Language );

$user = eZUser::currentUser();



////////////////////////////////////////////
// die("Hello");
/*

$stamp = '20361230235959';

$the_stamp = new eZDateTime;
// $the_stamp = $the_stamp->setMySQLTimeStamp($stamp);
$the_stamp_unix = $the_stamp->timeStamp();

die("core: $the_stamp_unix");

*/
////////////////////////////////////////////

if ( $user == false )
    $userID = false;
else
    $userID = $user->id();

if ( isset( $Action ) && $Action == "New"  )
{
    $event = new eZGroupEvent();
}
else
    $event = new eZGroupEvent( $EventID );

// We don't need to record this
// $session->setVariable( "ShowOtherCalenderGroups", $groupID );

$t = new eZTemplate( "kernel/ezgroupeventcalendar/user/" . $ini->read_var( "eZGroupEventCalendarMain", "TemplateDir" ),
                     "kernel/ezgroupeventcalendar/user/intl/", $Language, "eventedit.php" );

$t->set_file( "event_edit_tpl", "eventedit.tpl" );

$t->setAllStrings();

$t->set_block( "event_edit_tpl", "user_error_tpl", "user_error" );
$t->set_block( "user_error_tpl", "no_user_error_tpl", "no_user_error" );
$t->set_block( "user_error_tpl", "wrong_user_error_tpl", "wrong_user_error" );
$t->set_block( "user_error_tpl", "wrong_user_error_new_tpl", "wrong_user_error_new" );

$t->set_block( "event_edit_tpl", "no_error_tpl", "no_error" );
$t->set_block( "no_error_tpl", "title_error_tpl", "title_error" );
$t->set_block( "no_error_tpl", "group_error_tpl", "group_error" );

$t->set_block( "no_error_tpl", "start_hour_item_tpl", "start_hour_item" );
$t->set_block( "no_error_tpl", "start_minute_item_tpl", "start_minute_item" );
$t->set_block( "no_error_tpl", "stop_hour_item_tpl", "stop_hour_item" );
$t->set_block( "no_error_tpl", "stop_minute_item_tpl", "stop_minute_item" );
$t->set_block( "no_error_tpl", "start_ampm_radio_tpl", "start_ampm_radio" );
$t->set_block( "no_error_tpl", "stop_ampm_radio_tpl", "stop_ampm_radio" );

$t->set_block( "no_error_tpl", "start_time_error_tpl", "start_time_error" );
$t->set_block( "no_error_tpl", "stop_time_error_tpl", "stop_time_error" );
$t->set_block( "no_error_tpl", "value_tpl", "value" );
$t->set_block( "no_error_tpl", "category_value_tpl", "category_value" );

$t->set_block( "no_error_tpl", "month_tpl", "month" );
$t->set_block( "no_error_tpl", "day_tpl", "day" );
$t->set_block( "no_error_tpl", "year_tpl", "year" );
$t->set_block( "no_error_tpl", "group_name_edit_tpl", "group_name_edit" );
$t->set_block( "no_error_tpl", "group_name_new_tpl", "group_name_new" );

$t->set_block( "no_error_tpl", "add_file_list_tpl", "add_file_list" );

$t->set_block( "group_name_new_tpl", "group_item_tpl", "group_item" );

$t->set_block ( "no_error_tpl", "top_buttons_tpl", "top_buttons");

//history bar block
$t->set_block( "event_edit_tpl", "group_history_tpl", "group_history" );
$t->set_block( "event_edit_tpl", "edit_history_tpl", "edit_history" );
$t->set_block( "event_edit_tpl", "new_history_tpl", "new_history" );

$t->set_block( "no_error_tpl", "recur_exceptions_tpl", "recur_exceptions");
$t->set_var( "sitedesign", $SiteDesign );

$t->set_var( "group_history", "" );


////////////////////////////////////////////

if ( isset( $Action ) && "Edit" == $Action)
{
	$theDate = $event->dateTime();
	$curDate = new eZDate();
	$t->set_var( "the_year", $theDate->year() );
	$t->set_var( "the_month", addZero($theDate->month()) );
	$t->set_var( "the_day", addZero($theDate->day()) );
	$t->set_var( "year_cur", $curDate->year() );
	$t->set_var( "month_cur", $curDate->month() );
	$t->set_var( "day_cur", $curDate->day() );
	$t->parse( "top_buttons", "top_buttons_tpl");
} 
else
{
 	$t->set_var( "top_buttons", "" );
}

//print the group name in the history bar if a group is selected
if( $masterGroupID != 0 )
{
	$group = new eZUserGroup( $masterGroupID );

	$t->set_var( "group_print_name", $group->name() );
	$t->set_var( "group_print_id", $group->id() );
	$t->parse( "group_history", "group_history_tpl", true );
}
elseif ( $event->groupID() && $masterGroupID == 0 )
{
	$group = new eZUserGroup( $event->groupID() );

	$t->set_var( "group_print_name", $group->name() );
	$t->set_var( "group_print_id", $group->id() );
	$t->parse( "group_history", "group_history_tpl", true );
}
else
	$t->set_var( "group_history", "" );


$t->set_var( "edit_history", "" );
$t->set_var( "new_history", "" );

$t->set_var( "group_name_edit", "" );
$t->set_var( "group_name_new", "" );


$t->set_var( "add_file_list", "" );

// no user logged on
if( $userID == false )
{
    $t->set_var( "no_error", "" );
    $t->set_var( "wrong_user_error", "" );

    $t->parse( "no_user_error", "no_user_error_tpl" );
    $t->parse( "user_error", "user_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );

    $groupError = true;
    $errorPrint = true;
}

//set the group to a non valid group
$groupID = "-1";

// Groups that should not use be used with the calendar
$noShowGroup = new eZGroupNoShow();

// Determine editing permissions
$permission = new eZGroupEditor();
$editor     = false;


////////////////////////////////////////////
// ifAuthenticatUser
if ( $user == true )
{
	//if the user has root access, return all the groups else just return the group they are a member of
	if( $user->hasRootAccess() == true || eZPermission::checkPermission( $user, "eZGroupEventCalendar", "WriteToRoot" ) )
	{
		$groups = new eZUserGroup();
		$groupsList = $groups->getAll( true );

		$editor = true;
	}
	else
		$groupsList = $user->groups();

	//	die( "editor:" . $editor );

	if( isset( $Action ) && $Action == "New" )
	{
		$Group = $masterGroupID;
	}
	else
	{
		$Group = $event->groupID();
	}

	//Determin if the user has editing permissions
	if( $editor == true )
	{
		$groupID = $Group;
	}
	elseif( $permission->hasEditPermission( $user->id(), $Group ) == true && $editor == false )
	{
		$editor = true;
		$groupID = $Group;
	}
	elseif( $permission->getByGroup( $Group ) == false && $editor == false )
	{
		foreach( $groupsList as $groups )
		{
			if( $permission->groupHasEditor( $groups->id() ) == false && $noShowGroup->groupEntry( $groups->id() ) == false )
			{
				if( $Group != 0 && $Group )
				{
					$editor = true;
					$groupID = $Group;
					break;
				}
				elseif( $Group == 0 )
				{
					$editor = true;
					$groupID = $Group;
					break;
				}
			}
		}
	}
}


if ( ( isset( $Action ) && $Action == "New" || isset( $Action ) && $Action == "Insert" || isset( $Action ) && $Action == "Update" || isset( $Action ) && $Action == "Edit" ) && $groupsList )
{

	$groupError = true;
	foreach ( $groupsList as $groups )
	{
		if( isset( $Action ) && $Action == "New" )
		{
			if( ($masterGroupID == $groups->id() || $masterGroupID == 0) && $editor == true  )
			{
				$groupError = false;
				break;
			}
		}

		if( isset( $Action ) && $Action == "Edit" )
		{
		  // kracker: add support for event->groupID == 0
			if( $event->groupID() == 0 && $editor == true || $event->groupID() == $groups->id() && $editor == true  )
			{
				$groupError = false;
				break;
			}
		}

		if( isset( $Action ) && $Action == "Insert" || isset( $Action ) && $Action == "Update")
		{
			if( ($StoreByGroupID == $groups->id() || $StoreByGroupID == 0) && $editor == true )
			{
				$groupError = false;
				break;
			}
		}
	}
}

if ( $event->groupID() )
	$session->setVariable( "ShowOtherCalenderGroups", $event->groupID() );
elseif( $masterGroupID != 0 && !isset( $EventID ) )
	$session->setVariable( "ShowOtherCalenderGroups", $masterGroupID );


// only the specified group member is allowed to edit or delete an event
if ( isset( $Action ) && $Action == "Edit" && $groupError == true )
{

    $t->set_var( "no_error", "" );
    $t->set_var( "no_user_error", "" );

    $t->parse( "wrong_user_error", "wrong_user_error_tpl" );
    $t->parse( "user_error", "user_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );

    $groupError = true;
}


if ( isset( $Action ) && $Action == "DeleteEvents" )
{
	//initialize the error array
	$error = array();

	//get the date from the first event to be deleted
	$tmpAppointment = new eZGroupEvent( $eventArrayID[0]);
        $datetime = $tmpAppointment->dateTime();

    if ( count ( $eventArrayID ) != 0 )
    {
		foreach( $eventArrayID as $ID )
        {
			array_push( $error, $ID );
			$event = new eZGroupEvent( $ID );
			foreach ( $groupsList as $groups )
			{
				//If the user has a matching group set their group id to the matching group else there group id will be -1.
				if( $event->groupID() == $groups->id() || $event->groupID() == 0  )
				{
					$dump = array_pop( $error );
					$event->delete();
					exec("secure_clearcache.sh");
					break;
				}
			}
		}
	}

    // user not allowed to delete this appointment
	if( count( $error ) > 0 )
    {
		$t->set_var( "no_error", "" );
		$t->set_var( "no_user_error", "" );

		$t->parse( "wrong_user_error", "wrong_user_error_tpl" );
		$t->parse( "user_error", "user_error_tpl" );
		$t->pparse( "output", "appointment_edit_tpl" );

		$groupError = true;
    }
	else
	{ 
		$year = addZero( $datetime->year() );
		$month = addZero( $datetime->month() );
		$day = addZero( $datetime->day() );
		//deleteCache( "default", $Language, $year, $month, $day, $groupID );

		eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
		exit();
	}
}


// Allowed format for start and stop time:
// 14 14:30 14:0 1430
// the : can be replaced with any non number character

if ( ( isset( $Action ) && $Action == "Insert" || isset( $Action ) && $Action == "Update")  && $groupError == false )
{
  //die("what?");
  $dateArr = explode("-", $dateCal);
  $Year = $dateArr[0];
  $Month = $dateArr[1];
  $Day = $dateArr[2];

  // die("$Year $Month $Day");
  if ( isSet( $Cancel ) )
  {
    $event = new eZGroupEvent( $EventID );
    $dt = $event->dateTime();
    $year = $dt->year();
    $month = $dt->month();
    $day = $dt->day();
    
    eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
    exit();
  }

  $user = eZUser::currentUser();

  if ( $user )
  {
	if( isset( $TypeID ) && is_numeric( $TypeID ) )
	{
	  $TypeID = (int)$TypeID;
	}
	else
	{
	  $TypeID = 0;
	}
    
	$type = new eZGroupEventType( $TypeID );
    
    if ( isset( $Action ) && $Action == "Update" )
      $event = new eZGroupEvent( $EventID );
    else
      $event = new eZGroupEvent();
    
    $category = new eZGroupEventCategory( $CategoryID );
    
    $event->setDescription( $Description );
    $event->setLocation( $Location );
    $event->setUrl( $Url );
    
    $event->setType( $type );
    $event->setCategory( $category );
    
        $event->setPriority( $Priority );
        $event->setStatus( $Status );
	
	
	if ( isset( $IsPrivate ) && $IsPrivate == "on" )
	  $event->setIsPrivate( true );
        else
	  $event->setIsPrivate( false );

	if ( isset( $IsEventAlarmNotice ) && $IsEventAlarmNotice == "on" )
          $event->setEventAlarmNotice( true );
        else
          $event->setEventAlarmNotice( false );

        if ( $Name != "" )
	{
	  $event->setName( $Name );
	}
        else
        {
	  $TitleError = true;
        }

	// wanted to reserve 0 for events in all group category
	//	if ( $StoreByGroupID != 0 )
	if ( $StoreByGroupID != "" )
        { 
	    $group = new eZUserGroup( $StoreByGroupID );
            $event->setGroup( $group );
        }
        else
        {
            $GroupInsertError = true;
        }

        // start/stop time for the day
        $dayStartTime = new eZTime();
        $dayStopTime = new eZTime();
	
        if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StartTimeStr, $dayStartArray ) )
        {
            $hour = $dayStartArray[2];
            $dayStartTime->setHour( $hour );

            $min = $dayStartArray[3];
            $dayStartTime->setMinute( $min );

            $dayStartTime->setSecond( 0 );
        }

        if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StopTimeStr, $dayStopArray ) )
        {
            $hour = $dayStopArray[2];
            $dayStopTime->setHour( $hour );

            $min = $dayStopArray[3];
            $dayStopTime->setMinute( $min );

            $dayStopTime->setSecond( 0 );
        }

        // start/stop time for the appointment
        $startTime = new eZTime();
        $stopTime = new eZTime();

        $startTime->setSecond( 0 );
        $stopTime->setSecond( 0 );

        if ( isset( $IsAllDay ) && $IsAllDay == "on" )
        {
			$starthour = $dayStartArray[2];
			$startmin  = $dayStartArray[3];
			$stophour  = $dayStopArray[2];
			$stopmin   = $dayStopArray[3];

            $startTime->setHour( $starthour );
            $startTime->setMinute( $startmin  );
            $stopTime->setHour( $stophour );
            $stopTime->setMinute( $stopmin  );
		}
		else
		{
			if ( $Start_Hour != '' && $Start_Minute != '' )
			{
				$hour = $Start_Hour;
				$hour = convertToTwentyFour( $hour, $Start_AM_PM );

				settype( $hour, "integer" );
				$startHour = $hour;

				$startTime->setHour( $hour );

				$min = $Start_Minute;
				settype( $min, "integer" );

				$startTime->setMinute( $min );
			}
			else
			{
				$StartTimeError = true;
			}

			if ( $Stop_Hour != '' && $Stop_Minute != '')
			{
				$hour = $Stop_Hour;

				$hour = convertToTwentyFour( $hour, $Stop_AM_PM );
				settype( $hour, "integer" );

				if( $hour >= $startHour )
				{
					$stopTime->setHour( $hour );

					$min = $Stop_Minute;
					settype( $min, "integer" );

					$stopTime->setMinute( $min );
				}
				else
					$StopTimeError = true;
			}
			else
			{
				$StopTimeError = true;
			}
		}

		$pStartTimeHour = $startTime->hour();
		$pStartTimeMinute = addZero( $startTime->minute() );

        $pStopTimeHour = $stopTime->hour();
        $pStopTimeMinute = addZero( $stopTime->minute() );

		var_dump( $Year, $Month, $Day, $startTime->hour(),
		$startTime->minute(), $startTime->second() );
        
		$datetime = new eZDateTime( $Year, $Month, $Day,  
		$startTime->hour() , $startTime->minute(),
		 $startTime->second() );

        // $datetime->setSecondsElapsedHMS( $pStartTimeHour, $pStartTimeMinute, 0 );

        $event->setDateTime( $datetime );
		//die();
        if ( $stopTime->isGreater( $startTime, true ) )
        {
            $StopTimeError = true;
        }

	// setting recurrance variables to be used by the store() function
	$event->setRecurFreq ($RecurFreq );
	$event->setIsRecurring ( isset( $IsRecurring ) ? $IsRecurring : 0 );
	$event->setRecurType ( $RecurType );
	// we will now check the RecurType to see if it is a weekly recurrance
	if ($event->RecurType == 'week') 
	  // it is, so let's set the RecurDay property (checkbox group is named RecurWeekly)
	  $event->setRecurDay( $RecurWeekly );
	else 
	// if not, we will set it as blank (which is the default of the setRecurDay method)
	  $event->setRecurDay();
	  // the $RecurExceptions var is actually the text/calendar field, the select box array is $ExceptSelect
	  $event->setRecurExceptions( isset( $ExceptSelect ) ? $ExceptSelect : false );

	// now we check to see if the RecurType is month
	if ($event->RecurType == 'month')
	  // it is, so let's set RecurMonthlyType
	  $event->setRecurMonthlyType( $RecurTypeMonth, $datetime );
	  // if not, we set it to blank
        else
	  $event->setRecurMonthlyType();

	////////////////////////////////////////////
	// die("what-is-t00");
	// die($RepeatOptions);

	// feed repeat options to setFinishTime
	if ( isset( $RepeatOptions ) && $RepeatOptions=='numTimes') {
	  $event->setFinishDateNot($NumberOfTimes, $RecurFreq, $RecurType, $datetime);
	  // die("what-is-t1");
	} elseif ( isset( $RepeatOptions ) && $RepeatOptions=='untilDate') {
	  //die("what-is: $UntilDate");
	  $event->setFinishDateUntil($UntilDate);
	  //die("what-is: $UntilDate");
	   //die("what-is-42");
	
	} else {// must be forever
	  //die("what-is-t12");
	  $event->SetFinishDateForever();
        }

	////////////////////////////////////////////
	//die("what-is-t2");
	

	
	  /*
            $duration = new eZTime( $stopTime->hour() - $startTime->hour(),
                                    $stopTime->minute() - $startTime->minute() );
	  */

	  // formant hour, minute, second : the 1.0 release had a major bug related to the above code mssing the ,0 in duration time span

	  $duration = new eZTime( $pStopTimeHour - $pStartTimeHour,
				  $pStopTimeMinute - $pStartTimeMinute, 0 );
 var_dump( value: $duration );
            $event->setDuration( $duration );
	    $adur = $duration->mysqlTime();


	    $aa = $pStopTimeMinute - $pStartTimeMinute;

	    /*
            print( "dir m: $pStopTimeHour - $pStartTimeHour |  $pStopTimeMinute - $pStartTimeMinute | $aa"  );

	    print( "dir t: " . $adur );
	    print("<br />Set Start: ". $pStartTimeHour ." : ". $pStartTimeMinute);
	    print("<br />Set  Stop: ". $pStopTimeHour  ." : ". $pStopTimeMinute);

	    die();
	    */
	    //  : check to see if this is a recurring event
       }

  //die("here!");

        if ( !isset( $TitleError) || isset( $TitleError	) && $TitleError == false && $GroupInsertError == false && $StartTimeError == false && $StopTimeError == false )
        {
            $resultz = $event->store();
            //exec("secure_clearcache.sh");
	    /*
              $year = addZero( $datetime->year() );
              $month = addZero( $datetime->month() );
              $day = addZero( $datetime->day() );
	    */
			deleteCache( "default", $Language, $Year, $Month, $Day, $groupID );

	    	// redirect switch
            if ( $FileUploadFlag ){
              	eZHTTPTool::header( "Location: /groupeventcalendar/eventedit/filelist/$event->ID/" );
            } else {
	       		// eZHTTPTool::header( "Location: /groupeventcalendar/eventedit/edit/$event->ID/" );
	            eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$Year/$Month/$Day/" );;
            }
        }
        else
        {
	    // : js gui calendar regeneraion
          if (isset($dateCal))
           $t->set_var( "date_calendar", $dateCal);
          else 
	  {
	   $today = new eZDateTime();

           $tyear = $today->year();
           $tmonth = $today->month();
           $tday = $today->day();
           $t->set_var( "date_calendar", $tyear.'-'.$tmonth.'-'.$tday );
          }
	  // recurring events regeneration
    $t->set_var( "is_recurring", '');
    $t->set_var( "recur_freq", "1" );  
    $t->set_var( "recur_weekly_mon", "" ); 
    $t->set_var( "rtselect_day", "" ); 
    $t->set_var( "rtselect_week", "" ); 
    $t->set_var( "rtselect_month", "" ); 
    $t->set_var( "rtselect_year", "" ); 
    $t->set_var( "start_daily", "" ); 
    $t->set_var( "start_strdayname", "" ); 
    $t->set_var( "start_numdayname", "" );
    $t->set_var( "recur_weekly_mon", "" );
    $t->set_var( "recur_weekly_tue", "" );
    $t->set_var( "recur_weekly_wed", "" );
    $t->set_var( "recur_weekly_thu", "" );
    $t->set_var( "recur_weekly_fri", "" );
    $t->set_var( "recur_weekly_sat", "" );
    $t->set_var( "recur_weekly_sun", "" );
    $t->set_var( "recur_weekly_sun", "" );
    $t->set_var( "until_date", "" );
    $t->set_var( "num_times", "" );
    $t->set_var( "repeat_until", "");
    $t->set_var( "repeat_times", "");
    $t->set_var( "repeat_forever", "");
	 if (isset($IsRecurring))
	 {
	 $t->set_var( "is_recurring", 'checked');
	 $t->set_var( "recur_freq", $RecurFreq);
	 // recur type stuff...
	 if ('day' == $RecurType) 
	  $t->set_var( "rtselect_day", "selected" ); 
	 elseif ('week' == $RecurType) 
	  $t->set_var( "rtselect_week", "selected" );
	 elseif ('month' == $RecurType)
	  $t->set_var( "rtselect_month", "selected" );
	 else 
	  $t->set_var( "rtselect_year", "" );
	 if ('daily' == $RecurTypeMonth)
	  $t->set_var( "start_daily", "checked" );
	 elseif ('strdayname' == $RecurTypeMonth)
	  $t->set_var( "start_strdayname", "checked" );
	 else
	  $t->set_var( "start_numdayname", "checked" );
	 if (is_array($RecurWeekly)) 
	 {
	  foreach ($RecurWeekly as $rwd)
	  {
	   $t->set_var( "recur_weekly_".$rwd , "checked");
	  }
	 }
	
	if (isset($UntilDate))
	 $t->set_var( "until_date", $UntilDate );
	if (isset($NumberOfTimes))
         $t->set_var( "num_times", $NumberOfTimes );
	if ('forever' == $RepeatOptions)
	 $t->set_var( "repeat_forever", "checked");
	elseif ('untilDate' == $RepeatOptions)
	 $t->set_var( "repeat_until", 'checked');
	else
	 $t->set_var( "repeat_times", 'checked');
       
       if (is_array($ExceptSelect))
       foreach ($ExceptSelect as $ex) 
       {
			echo 'adding<br>';
			$t->set_var('recur_exception', "<option>$ex</option>");
			$t->parse( "recur_exceptions", "recur_exceptions_tpl", true );
       }
      else {
           	$t->set_var('recur_exception', '');
        $t->set_var('recur_exceptions', '');
      }
      } // end of recurring events
    
            $t->set_var( "name_value", stripslashes($event->name()) );
            $t->set_var( "description_value", stripslashes($event->description()) );

            $t->set_var( "location_value", $event->location() );
            $t->set_var( "url_value", $event->url() );
	    
	    $t->set_var( "group_name_new", "" );
			
			if ( $user )
			{
				// include_once( "ezuser/classes/ezusergroup.php" );

				$t->set_var( "group_item", "" );

				// build the group drop down list
				foreach( $groupsList as $groups )
				  {

				    // Add entry for event to be in all groups
				    $t->set_var( "group_member_name", "All Groups" );
				    $t->set_var( "group_member_id", 0 );

				    // if ( $groups->id() == $StoreByGroupID )
				    if ( $event->groupID() == 0 )
				      $t->set_var( "group_is_selected", "selected" );
				    else
				      $t->set_var( "group_is_selected", "" );

				    $t->parse( "group_item", "group_item_tpl", true );


					if( $noShowGroup->groupEntry( $groups->id() ) == false )
					{
						$t->set_var( "group_member_name", $groups->name() );
						$t->set_var( "group_member_id", $groups->id() );

						if ( $groups->id() == $StoreByGroupID )
							$t->set_var( "group_is_selected", "selected" );
						else
							$t->set_var( "group_is_selected", "" );

						$t->parse( "group_item", "group_item_tpl", true );
					}
				}

				$t->parse( "group_name_new", "group_name_new_tpl", true );
			}

			$t->parse( "new_history", "new_history_tpl", true );

            if ( $event->isPrivate() )
                $t->set_var( "is_private", "checked" );
            else
                $t->set_var( "is_private", "" );

	    if ( $event->isEventAlarmNotice() )
	      $t->set_var( "is_event_alarm_notice", "checked" );
	    else
	      $t->set_var( "is_event_alarm_notice", "" );

			/* what we need to store this date. timestamp */

			$eventStartTime =& $event->startTime();
			$startHour		= ( addZero( $eventStartTime->hour() ) );
			$startMinute	= ( addZero( $eventStartTime->minute() ) );

			$eventStopTime  =& $event->stopTime();
			$stopHour		= ( addZero( $eventStopTime->hour() ) );
			$stopMinute		= ( addZero( $eventStopTime->minute() ) );

			if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StartTimeStr, $dayStartArray ) )
			{
				$dayStarthour = $dayStartArray[2];
				$dayStartMin  = $dayStartArray[3];
			}

			if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StopTimeStr, $dayStopArray ) )
			{
				$dayStopHour = $dayStopArray[2];
				$dayStopMin  = $dayStopArray[3];
			}

			$t->set_var( "is_all_day", "" );
			if( $startHour = $dayStarthour && $startMinute == $dayStartMin && $stopHour == $dayStopHour && $stopMinute == $dayStopMin )
			$t->set_var( "is_all_day", "checked" );

			$minuteInterval = $ini->read_var( "eZGroupEventCalendarMain", "MinutesSelectInterval" );
			$minute_array    = array();

			for( $i=0; $i<60; $i = $i + $minuteInterval )
			{
				$i = addZero( $i );
				array_push ($minute_array, "$i");
			}

			if( $timeSelect == "enabled" )
			{
				$hour_array   = array('01','02','03','04','05','06','07','08','09','10','11','12');

				if( $startHour >= 12 )
				{
					$t->set_var( "start_am", "unchecked" );
					$t->set_var( "start_pm", "checked" );
				}
				else
				{
					$t->set_var( "start_pm", "unchecked" );
					$t->set_var( "start_am", "checked" );
				}

				if( $stopHour >= 12 )
				{
					$t->set_var( "stop_am", "unchecked" );
					$t->set_var( "stop_pm", "checked" );
				}
				else
				{
					$t->set_var( "stop_pm", "unchecked" );
					$t->set_var( "stop_am", "checked" );
				}

				$startHour = convertToTwelve( $startHour );
				$stopHour = convertToTwelve( $stopHour );

				$t->parse( "start_ampm_radio", "start_ampm_radio_tpl" );
				$t->parse( "stop_ampm_radio", "stop_ampm_radio_tpl" ); 
			}
			else
			{
				$t->set_var( "start_ampm_radio", "" );
				$t->set_var( "stop_ampm_radio", "" ); 
				$hour_array   = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
			}


			foreach( $hour_array as $hour )
			{
				$t->set_var( "is_start_hour_selected", $hour == $startHour ? "selected" : "" );
				$t->set_var( "start_hour", $hour );
				$t->parse( "start_hour_item", "start_hour_item_tpl", true );

				$t->set_var( "is_stop_hour_selected", $hour == $stopHour ? "selected" : "" );
				$t->set_var( "stop_hour", $hour );
				$t->parse( "stop_hour_item", "stop_hour_item_tpl", true );
			}
			foreach( $minute_array as $minute )
			{
				$t->set_var( "is_start_minute_selected", $minute == $startMinute ? "selected" : "" );
				$t->set_var( "start_minute", $minute );
				$t->parse( "start_minute_item", "start_minute_item_tpl", true );

				$t->set_var( "is_stop_minute_selected", $minute == $stopMinute ? "selected" : "" );
				$t->set_var( "stop_minute", $minute );
				$t->parse( "stop_minute_item", "stop_minute_item_tpl", true );
			}
        }
    }


$t->set_var( "user_error", "" );

if ( ( isset( $Action ) && $Action == "Insert" || isset( $Action ) && $Action == "Update")  && $groupError == true )
{
    $t->set_var( "no_error", "" );
    $t->set_var( "no_user_error", "" );

    $t->parse( "wrong_user_error", "wrong_user_error_tpl" );
    $t->parse( "user_error", "user_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );
    
}

if ( isset( $Action ) && $Action == "Update" && $groupError == false )
{
    $t->set_var( "name_value", stripslashes($Name) );
    $t->set_var( "description_value", stripslashes($Description) );
    
    $t->set_var( "location_value", $Location );
    $t->set_var( "url_value", $Url );

    $t->set_var( "start_value", $Start );
    $t->set_var( "stop_value", $Stop );

    $typeID = $TypeID;
    $categoryID = $CategoryID;

    $t->set_var( "0_selected", "" );
    $t->set_var( "1_selected", "" );
    $t->set_var( "2_selected", "" );

	if ( isset( $Priority ) && is_numeric( $Priority ) )
	{
		if ( $Priority == 0 )
        	$t->set_var( "0_selected", "selected" );
		else if ( $Priority == 1 )
			$t->set_var( "1_selected", "selected" );
		else if ( $Priority == 2 )
			$t->set_var( "2_selected", "selected" );
		else if ( $Priority == 3 )
			$t->set_var( "3_selected", "selected" );
		else if ( $Priority == 4 )
			$t->set_var( "4_selected", "selected" );
		else if ( $Priority == 5 )
			$t->set_var( "5_selected", "selected" );
		else if ( $Priority == 6 )
			$t->set_var( "6_selected", "selected" );
	}
	else
	{
		$Priority = 0;
	}


    $t->set_var( "0_status_selected", "" );
    $t->set_var( "1_status_selected", "" );
    $t->set_var( "2_status_selected", "" );

    if ( $Status == 0 )
      $t->set_var( "0_status_selected", "selected" );
    else if ( $Status == 1 )
      $t->set_var( "1_status_selected", "selected" );
    else if ( $Status == 2 )
      $t->set_var( "2_status_selected", "selected" );
    else
      $t->set_var( "1_status_selected", "selected" );

    if ( $IsPrivate == "on" )
        $t->set_var( "is_private", "checked" );
    else
        $t->set_var( "is_private", "" );

    if ( $EventAlarmNotice == "on" )
      $t->set_var( "is_event_alarm_notice", "checked" );
    else
      $t->set_var( "is_event_alarm_notice", "" );

    $t->set_var( "action_value", $Action );
    $t->set_var( "appointment_id", $EventID );
}


$today = new eZDate();
if( !isset( $year ) || !isset( $month ) || !isset( $day ) )
{
	$Year = $today->year();
	$Month = $today->month();
	$Day = $today->day();
}
$tmpdate = new eZDate( $Year, $Month, $Day );

$t->set_var( "edit", "" );

if ( isset( $Action ) && $Action == "Edit" && $groupError == false )
{

    $t->parse( "add_file_list", "add_file_list_tpl" );

    $event = new eZGroupEvent( $EventID );
    $t->set_var( "name_value", stripslashes($event->name()) );
    
    $t->set_var( "url_value", $event->url() );
    $t->set_var( "location_value", $event->location() );

    $t->set_var( "event_id", $event->id() );
    $t->set_var( "description_value", stripslashes($event->description()) );

    // : adding recurring event template vars
        $t->set_var( "is_recurring", '');
    $t->set_var( "recur_freq", "1" );  
    $t->set_var( "recur_weekly_mon", "" ); 
    $t->set_var( "rtselect_day", "" ); 
    $t->set_var( "rtselect_week", "" ); 
    $t->set_var( "rtselect_month", "" ); 
    $t->set_var( "rtselect_year", "" ); 
    $t->set_var( "start_daily", "" ); 
    $t->set_var( "start_strdayname", "" ); 
    $t->set_var( "start_numdayname", "" );
    $t->set_var( "recur_weekly_mon", "" );
    $t->set_var( "recur_weekly_tue", "" );
    $t->set_var( "recur_weekly_wed", "" );
    $t->set_var( "recur_weekly_thu", "" );
    $t->set_var( "recur_weekly_fri", "" );
    $t->set_var( "recur_weekly_sat", "" );
    $t->set_var( "recur_weekly_sun", "" );
    $t->set_var( "recur_weekly_sun", "" );
    $t->set_var( "until_date", "" );
    $t->set_var( "num_times", "" );
    $t->set_var( "repeat_until", "");
    $t->set_var( "repeat_times", "");
    $t->set_var( "repeat_forever", "");
    if ($event->isRecurring()) {
      $t->set_var( "is_recurring", 'checked' );
      $t->set_var( "recur_freq", $event->recurFreq() );
      $t->set_var( "rtselect_".$event->recurType(), 'selected' );
      if ('week' == $event->recurType()) {
        foreach ($event->recurDay() as $keyDay) 
	{
	$t->set_var( "recur_weekly_".$keyDay, 'checked' );
	}
      }
      if ('month' == $event->recurType()) 
      {
        $t->set_var( "start_".$event->recurMonthlyType(), 'checked');
      }
      
      if ($event->repeatTimes()) 
      {
        $t->set_var( "repeat_times", 'checked' );
	$t->set_var( "num_times", $event->repeatTimes());
      } 
      elseif ($event->repeatUntilDate()) 
      {
        $t->set_var( "repeat_until", 'checked' );
	$t->set_var( "until_date", $event->repeatUntilDate());
      }
      else
      {
        $t->set_var( "repeat_forever", 'checked' );
      }
      // $repEx is just a holder for the array returned by repeatExceptions
      // if there are no exceptions, it will judged false
      $repEx = $event->recurExceptions();
      if (is_array($repEx)) 
      {
       foreach ($repEx as $ex) 
       {
        $t->set_var("recur_exception", "<option>$ex</option>");
	$t->parse( "recur_exceptions", "recur_exceptions_tpl", true );
       }
      }
     else
     {
        $t->set_var('recur_exception', '');
	$t->set_var('recur_exceptions', '');
     }   
       // still need to add exception handling, once it's all ready
    }

	// include_once("ezuser/classes/ezusergroup.php" );
	$group = new eZUserGroup( $groupID );
	
	//$t->set_var( "group_name", $group->name() );
	//$t->set_var( "group_id", $group->id() );
	//$t->parse( "group_name_edit", "group_name_edit_tpl", true );


	// build the group drop down list
	$noshow_array = $noShowGroup->getAll();
	$t->set_var( "group_item", "" );

	// Add entry for event to be in all groups
	$t->set_var( "group_member_name", "All Groups" );
	$t->set_var( "group_member_id", 0 );

	// if ( $groups->id() == $StoreByGroupID )
	if ( $event->groupID() == 0 )
	  $t->set_var( "group_is_selected", "selected" );
	else
	  $t->set_var( "group_is_selected", "" );

	$t->parse( "group_item", "group_item_tpl", true );

	foreach( $groupsList as $groups )
	{
		if( $noShowGroup->groupEntry( $groups->id() ) == false )
		{
			$t->set_var( "group_member_id", $groups->id() );
			$t->set_var( "group_member_name", $groups->name() );

			if ( $groups->id() == $group->id() )
				$t->set_var( "group_is_selected", "selected" );
			else
				$t->set_var( "group_is_selected", "" );

			$t->parse( "group_item", "group_item_tpl", true );
		}
	}

	$t->parse( "group_name_new", "group_name_new_tpl", true );
	
	$t->set_var( "group_print_id", $group->id() );
	$t->set_var( "group_print_name", $group->name() );
	$t->set_var( "event_title", stripslashes($event->name()) );

	//set the history bar data
	$t->parse( "edit_history", "edit_history_tpl", true );

        $type =& $event->type();
        $typeID = $type->id();

        $category =& $event->category();
        $categoryID = $category->id();

        $date  = $event->dateTime();
        $year  = $date->year();
        $month = $date->month();
        $day   = $date->day();

		//var_dump( $day, $month, $year, $date );
		// echo "<hr>";
        $tmpdate = new eZDate( $year, $month, $day );
	
	$startTime   =& $event->dateTime();
	$startHour   = addZero( $startTime->hour() );
	$startMinute = ( addZero( $startTime->minute() ) );

	$stopTime    =& $event->duration();
	$stopHour    = ( addZero( $stopTime->hour() ) );
	$stopMinute  = ( addZero( $stopTime->minute() ) );

	// $stopMinute = $stopMinute +1 ;
	print ( "Echo DT:" . $startHour ." / ". $startMinute );
        print ( "<br />Echo DT:" . $stopHour ." / ". $stopMinute );



	if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StartTimeStr, $dayStartArray ) )
    {
		$dayStarthour = $dayStartArray[2];
		$dayStartMin  = $dayStartArray[3];
    }

    if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StopTimeStr, $dayStopArray ) )
    {
		$dayStopHour = $dayStopArray[2];
		$dayStopMin  = $dayStopArray[3];
    }

	$t->set_var( "is_all_day", "" );
	$t->set_var( "is_start_hour_selected", "" );
	$t->set_var( "is_start_minute_selected", "" );
	$t->set_var( "is_stop_hour_selected", "" );
	$t->set_var( "is_stop_minute_selected", "" );
	$t->set_var( "start_ampm_radio", "" );
	$t->set_var( "stop_ampm_radio", "" );
	$allDay = false;

	var_dump( $dayStarthour, $dayStartMin, $dayStopHour, $dayStopMin );
	echo "<hr> Some: ";
	var_dump( $startHour, $startMinute );
	echo "<hr>";
	var_dump(  $stopHour, $stopMinute );
	echo "<hr>";
	//die('hitters');
	if( $startHour == $dayStarthour && $startMinute == $dayStartMin && $stopHour == ($dayStopHour -1) && $stopMinute == $dayStopMin )
	{
		$allDay = true;
		$t->set_var( "is_all_day", "checked" );
	}

	$minuteInterval = $ini->read_var( "eZGroupEventCalendarMain", "MinutesSelectInterval" );

	$minute_array    = array();
	for( $i=0; $i<60; $i = $i + $minuteInterval )
	{
		$i = addZero( $i );
		array_push ($minute_array, "$i");
	}

	if( $timeSelect == "enabled" )
	{
		$hour_array   = array('01','02','03','04','05','06','07','08','09','10','11','12');
		if( $startHour >= 12 )
		{
//			die( "<hr>");
			$t->set_var( "start_am", "unchecked" );
			$t->set_var( "start_pm", "checked" );
		}
		else
		{
			$t->set_var( "start_pm", "unchecked" );
			$t->set_var( "start_am", "checked" );
		}

		if( $stopHour >= 12 )
		{
			$t->set_var( "stop_am", "unchecked" );
			$t->set_var( "stop_pm", "checked" );
		}
		else
		{
			$t->set_var( "stop_pm", "unchecked" );
			$t->set_var( "stop_am", "checked" );
		}

		var_dump( $startHour, $stopHour, $allDay, $timeSelect );
		echo "<hr>";
		$startHour = convertToTwelve( $startHour );
		$stopHour = convertToTwelve( $stopHour );

		var_dump( $startHour, $stopHour, $allDay, $timeSelect );
		//die( "timeSelect is enabled" );
		$t->parse( "start_ampm_radio", "start_ampm_radio_tpl" );
		$t->parse( "stop_ampm_radio", "stop_ampm_radio_tpl" ); 
	}
	else
	{
		$t->set_var( "start_ampm_radio", "" );
		$t->set_var( "stop_ampm_radio", "" ); 
		$hour_array   = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
	}

	foreach( $hour_array as $hour )
	{
		if( $allDay == false && $hour == $startHour )
			$t->set_var( "is_start_hour_selected", "selected" );
		else
			$t->set_var( "is_start_hour_selected", "" );

		$t->set_var( "start_hour", $hour );
		$t->parse( "start_hour_item", "start_hour_item_tpl", true );


		if( $allDay == false && $hour == $stopHour )
			$t->set_var( "is_stop_hour_selected", "selected" );
		else
			$t->set_var( "is_stop_hour_selected", "" );

		$t->set_var( "stop_hour", $hour );
		$t->parse( "stop_hour_item", "stop_hour_item_tpl", true );
	}

var_dump($startMinute, $stopMinute);
	foreach( $minute_array as $minute )
	{
		if( $allDay == false && $minute == $startMinute )
			$t->set_var( "is_start_minute_selected", "selected" );
		else
			$t->set_var( "is_start_minute_selected", "" );

		$t->set_var( "start_minute", $minute );
		$t->parse( "start_minute_item", "start_minute_item_tpl", true );

		if( $allDay == false && $minute == $stopMinute )
			$t->set_var( "is_stop_minute_selected", "selected" );
		else
			$t->set_var( "is_stop_minute_selected", "" );

		$t->set_var( "stop_minute", $minute );
		$t->parse( "stop_minute_item", "stop_minute_item_tpl", true );
	}

    $t->set_var( "0_selected", "" );
    $t->set_var( "1_selected", "" );
    $t->set_var( "2_selected", "" );
    $t->set_var( "3_selected", "" );
    $t->set_var( "4_selected", "" );
    $t->set_var( "5_selected", "" );
    $t->set_var( "6_selected", "" );

    if ( $event->priority() == 0 )
        $t->set_var( "0_selected", "selected" );
    else if ( $event->priority() == 1 )
        $t->set_var( "1_selected", "selected" );
    else if ( $event->priority() == 2 )
        $t->set_var( "2_selected", "selected" );
    else if ( $event->priority() == 3 )
      $t->set_var( "3_selected", "selected" );
    else if ( $event->priority() == 4 )
      $t->set_var( "4_selected", "selected" );
    else if ( $event->priority() == 5 )
      $t->set_var( "5_selected", "selected" );
    else if ( $event->priority() == 6 )
      $t->set_var( "6_selected", "selected" );

    $t->set_var( "0_status_selected", "" );
    $t->set_var( "1_status_selected", "" );
    $t->set_var( "2_status_selected", "" );

    if ( $event->status() == 0 )
      $t->set_var( "0_status_selected", "selected" );
    else if ( $event->status() == 1 )
      $t->set_var( "1_status_selected", "selected" );
    else if ( $event->status() == 2 )
      $t->set_var( "2_status_selected", "selected" );


        $dt =& $event->dateTime();
	$today = new eZDate();

	$tempYear = addZero( $today->year() );
        //$tempYear = $tmpdate->year();
	$yearsPrint = $ini->read_var( "eZGroupEventCalendarMain", "YearsPrint" );

	// : setting new day time in template
	$t->set_var( "date_calendar", "$year-$month-$day");

	/*
	for( $i=1; $i<=$yearsPrint; $i++ )
	{
		if( $dt->year() == $tempYear )
		{
			$t->set_var( "year_value", $tempYear );
			$t->set_var( "is_year_selected", "selected" );
			$i--;
		}
		else
		{
			$t->set_var( "year_value", $tempYear );
			$t->set_var( "is_year_selected", "" );
		}

		$tempYear++;
		$t->parse( "year", "year_tpl", true );
	}
	*/

	if ( $event->isPrivate() )
	  $t->set_var( "is_private", "checked" );
	else
	  $t->set_var( "is_private", "" );

        if ( $event->isEventAlarmNotice() )
          $t->set_var( "is_event_alarm_notice", "checked" );
        else
          $t->set_var( "is_event_alarm_notice", "" );

    $t->set_var( "action_value", "update" );
}

// print out error messages
if ( isset( $TitleError ) && $TitleError == true )
{
    $t->parse( "title_error", "title_error_tpl" );
    $t->set_var( "action_value", "insert" );
}
else
    $t->set_var( "title_error", "" );

if ( isset( $GroupInsertError ) && $GroupInsertError == true )
{
    $t->parse( "group_error", "group_error_tpl" );
    $t->set_var( "action_value", "insert" );
}
else
    $t->set_var( "group_error", "" );

if ( isset( $StartTimeError ) && $StartTimeError == true )
{
    $t->parse( "start_time_error", "start_time_error_tpl" );
    $t->set_var( "action_value", "insert" );
}
else
    $t->set_var( "start_time_error", "" );

if ( isset( $StopTimeError ) && $StopTimeError == true )
{
    $t->parse( "stop_time_error", "stop_time_error_tpl" );
    $t->set_var( "action_value", "insert" );
}
else
    $t->set_var( "stop_time_error", "" );




if ( isset( $Action ) && $Action == "New" && $groupError == false )
{
	$user = eZUser::currentUser();

	$t->set_var( "group_name_new", "" );
	if ( $user )
	{
		//include_once( "ezuser/classes/ezusergroup.php" );

		$t->set_var( "group_item", "" );

		// build the group drop down list

		$noshow_array = $noShowGroup->getAll();

		// Add entry for event to be in all groups
		$t->set_var( "group_member_name", "All Groups" );
		$t->set_var( "group_member_id", 0 );

		// if ( $groups->id() == $StoreByGroupID )
		if ( $event->groupID() == 0 )
		  $t->set_var( "group_is_selected", "selected" );
		else
		  $t->set_var( "group_is_selected", "" );

		$t->parse( "group_item", "group_item_tpl", true );


		foreach( $groupsList as $groups )
		{
			if( $noShowGroup->groupEntry( $groups->id() ) == false )
			{
				$t->set_var( "group_member_id", $groups->id() );
				$t->set_var( "group_member_name", $groups->name() );

				if ( $groups->id() == $masterGroupID )
					$t->set_var( "group_is_selected", "selected" );
				else
					$t->set_var( "group_is_selected", "" );

				$t->parse( "group_item", "group_item_tpl", true );
			}
		}

		$t->parse( "group_name_new", "group_name_new_tpl", true );
	}

	$t->parse( "new_history", "new_history_tpl", true );

    $t->set_var( "add_file_list", "" );

    $t->set_var( "action_value", "insert" );
    $t->set_var( "appointment_id", "new" );
    $t->set_var( "name_value", "" );
    $t->set_var( "location_value", "" );
    $t->set_var( "url_value", "" );
    $t->set_var( "event_id", "" ); 
    
    $t->set_var( "recur_freq", "1" ); 
    $t->set_var( "is_recurring", "" ); 
    $t->set_var( "recur_weekly_mon", "" ); 
    $t->set_var( "rtselect_day", "" ); 
    $t->set_var( "rtselect_week", "" ); 
    $t->set_var( "rtselect_month", "" ); 
    $t->set_var( "rtselect_year", "" ); 
    $t->set_var( "start_daily", "" ); 
    $t->set_var( "start_strdayname", "" ); 
    $t->set_var( "start_numdayname", "" );
    $t->set_var( "recur_weekly_mon", "" );
    $t->set_var( "recur_weekly_tue", "" );
    $t->set_var( "recur_weekly_wed", "" );
    $t->set_var( "recur_weekly_thu", "" );
    $t->set_var( "recur_weekly_fri", "" );
    $t->set_var( "recur_weekly_sat", "" );
    $t->set_var( "recur_weekly_sun", "" );
    $t->set_var( "recur_weekly_sun", "" );
    $t->set_var( "until_date", "" );
    $t->set_var( "num_times", "" );
    $t->set_var( "repeat_until", "");
    $t->set_var( "repeat_times", "");
    $t->set_var( "repeat_forever", "");
        
    $t->set_var( "description_value", "" );
    $t->set_var( "is_private", "" );
    $t->set_var( "is_event_alarm_notice", "" );

    $t->set_var( "start_value", "" );
    $t->set_var( "stop_value", "" );
 
    $t->set_var( "is_all_day", "" );

    $t->set_var( "0_selected", "" );
    $t->set_var( "1_selected", "" );
    $t->set_var( "2_selected", "" );
    $t->set_var( "3_selected", "" );
    $t->set_var( "4_selected", "" );
    $t->set_var( "5_selected", "" );
    $t->set_var( "6_selected", "" );

    $priority = $ini->read_var( "eZGroupEventCalendarMain", "Priority" );

    if ( $priority== 0 )
        $t->set_var( "0_selected", "selected" );
    else if ( $priority == 1 )
        $t->set_var( "1_selected", "selected" );
    else if ( $priority == 2 )
        $t->set_var( "2_selected", "selected" );
    else if ( $priority == 3 )
      $t->set_var( "3_selected", "selected" );
    else if ( $priority == 4 )
      $t->set_var( "4_selected", "selected" );
    else if ( $priority == 5 )
      $t->set_var( "5_selected", "selected" );
    else if ( $priority == 6 )
      $t->set_var( "6_selected", "selected" );


    $t->set_var( "0_status_selected", "" );
    $t->set_var( "1_status_selected", "" );
    $t->set_var( "2_status_selected", "" );

    $status = $ini->read_var( "eZGroupEventCalendarMain", "Status" );

    if ( $status == 0 )
      $t->set_var( "0_status_selected", "selected" );
    else if ( $status == 1 )
      $t->set_var( "1_status_selected", "selected" );
    else if ( $status == 2 )
      $t->set_var( "2_status_selected", "selected" );
    
    if ( $Year != 0 )
        $year = $Year;
    else
        $year = $today->year();

    if ( $Month != 0 )
        $month = $Month;
    else
        $month = $today->month();

    if ( $Day != 0 )
        $day = $Day;
    else
        $day = $today->day();

$t->set_var( "date_calendar", "$year-$month-$day" );	
	
    $tmpdate = new eZDate( $year, $month, $day );

	$minuteInterval = $ini->read_var( "eZGroupEventCalendarMain", "MinutesSelectInterval" );

	$minute_array    = array();
	for( $i=0; $i<60; $i = $i + $minuteInterval )
	{
		$i = addZero( $i );
		array_push ($minute_array, "$i");
	}

	if ( $StartTime != 0 )
	{
		$startHour   = substr( $StartTime, 0, 2);
		$startMinute = substr( $StartTime, 2, 3);
	}
	else
	{
		$startHour   = '';
		$startMinute = '';
	}

	if( $timeSelect == "enabled" )
	{
		$hour_array   = array('01','02','03','04','05','06','07','08','09','10','11','12');

		if( isset( $startHour ) && $startHour >= 12 )
		{
			$t->set_var( "start_am", "unchecked" );
			$t->set_var( "start_pm", "checked" );
		}
		else
		{
			$t->set_var( "start_pm", "unchecked" );
			$t->set_var( "start_am", "checked" );
		}

		if( isset( $stopHour ) && $stopHour >= 12 )
		{
			$t->set_var( "stop_am", "unchecked" );
			$t->set_var( "stop_pm", "checked" );
		}
		else
		{
			$t->set_var( "stop_pm", "unchecked" );
			$t->set_var( "stop_am", "checked" );
		}

		$startHour = convertToTwelve( $startHour );

		$t->parse( "start_ampm_radio", "start_ampm_radio_tpl" );
		$t->parse( "stop_ampm_radio", "stop_ampm_radio_tpl" ); 
	}
	else
	{
		$t->set_var( "start_ampm_radio", "" );
		$t->set_var( "stop_ampm_radio", "" ); 
		$hour_array   = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
	}

        $t->set_var( "is_start_hour_selected", "" );
	$t->set_var( "is_start_minute_selected", "" );
	$t->set_var( "is_stop_hour_selected", "" );
	$t->set_var( "is_stop_minute_selected", "" );

	foreach( $hour_array as $hour )
	{
		if ( $StartTime != 0 )
			$t->set_var( "is_start_hour_selected", $hour == $startHour ? "selected" : "" );
		$t->set_var( "start_hour", $hour );
		$t->parse( "start_hour_item", "start_hour_item_tpl", true );

		$t->set_var( "stop_hour", $hour );
		$t->parse( "stop_hour_item", "stop_hour_item_tpl", true );
	}

	foreach( $minute_array as $minute )
	{
		if ( $StartTime != 0 )
			$t->set_var( "is_start_minute_selected", $minute == $startMinute ? "selected" : "" );
		$t->set_var( "start_minute", $minute );
		$t->parse( "start_minute_item", "start_minute_item_tpl", true );

		$t->set_var( "stop_minute", $minute );
		$t->parse( "stop_minute_item", "stop_minute_item_tpl", true );
	}
}
elseif(isset( $Action ) && $Action == "New" && $groupError == true && $errorPrint == false )
{
    $t->set_var( "no_error", "" );
    $t->set_var( "no_user_error", "" );

    $t->parse( "wrong_user_error", "wrong_user_error_tpl" );
    $t->parse( "user_error", "user_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );

}

// print the event types
$type = new eZGroupEventType();
$typeList =& $type->getTree();

foreach ( $typeList as $type )
{
    if ( $type[1] > 1 )
        $t->set_var( "option_level", str_repeat( "&nbsp;&nbsp;", $type[1] - 1 ) );
    else
        $t->set_var( "option_level", "" );

    if ( isset( $typeID ) && $typeID )
    {	
        if ( $typeID == $type[0]->id() )
        {
            $t->set_var( "type_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "type_is_selected", "" );
        }
    }
	else
	{
		$t->set_var( "type_is_selected", "" );
	}
    
    $t->set_var( "option_name", $type[0]->name() );
    $t->set_var( "option_value", $type[0]->id() );
    
    $t->parse( "value", "value_tpl", true );
}

// print the event categorys
$category = new eZGroupEventCategory();
$categoryList =& $category->getTree();

foreach ( $categoryList as $category )
{
  if ( $category[1] > 1 )
    $t->set_var( "option_category_level", str_repeat( "&nbsp;&nbsp;", $category[1] - 1 ) );
  else
    $t->set_var( "option_category_level", "" );

  if ( isset( $categoryID ) && $categoryID )
    {
      if ( $categoryID == $category[0]->id() )
        {
	  $t->set_var( "category_is_selected", "selected" );
        }
      else
        {
	  $t->set_var( "category_is_selected", "" );
        }
    }
  else
    {
      $t->set_var( "category_is_selected", "" );
    }

  $t->set_var( "category_name", $category[0]->name() );
  $t->set_var( "option_category_value", $category[0]->id() );

  $t->parse( "category_value", "category_value_tpl", true );
}

// set day combobox
/*-- removed by 
$daysInMonth = $tmpdate->daysInMonth();
//for ( $i=1; $i<=$daysInMonth; $i++ )
for ( $i=1; $i<=31; $i++ )
{
    if ( $tmpdate->day() == $i )
	{
		$t->set_var( "day_value", $i );
        $t->set_var( "selected", "selected" );
	}
    else
        $t->set_var( "selected", "" );

    if ( $Action == "Edit" && $groupError == false )
    {
        if ( $dt->day() == $i )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    
    $t->set_var( "day_id", $i );
    $t->set_var( "day_name", $i );

    $t->parse( "day", "day_tpl", true );
}
/*
// set month combobox /
/*-- removed code
$month = $tmpdate->month();
for ( $i=1; $i<13; $i++ )
{
    if ( $month == $i )   // don't use $tmpdate->month() since it gets changed
	{
		$t->set_var( "month_value", $i );
        $t->set_var( "selected", "selected" );
	}
    else
        $t->set_var( "selected", "" );

    if ( $Action == "Edit" && $groupError == false )
    {
        if ( $dt->month() == $i )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }

    $tmpdate->setMonth( $i );
    $t->set_var( "month_id", $i );
    $t->set_var( "month_name", $Locale->monthName( $tmpdate->monthName() ) );

    $t->parse( "month", "month_tpl", true );
}
*/
if ( isset( $Action ) && $Action != "Edit" )
{
    $t->set_var( "year_value", $tmpdate->year() );
	$t->set_var( "is_year_selected", "selected" );
	$t->parse( "year", "year_tpl", true );
	$yearsPrint = $ini->read_var( "eZGroupEventCalendarMain", "YearsPrint" );
	for( $i=1; $i<=$yearsPrint; $i++ )
	{
		$t->set_var( "year_value", $tmpdate->year() + $i );
		$t->set_var( "is_year_selected", "" );
		$t->parse( "year", "year_tpl", true );
	}
}

if ( $groupError == false )
{
    $t->parse( "no_error", "no_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );
}


// deletes the dayview cache file for a given day
function deleteCache( $siteStyle, $language, $year, $month, $day, $groupID )
{
    if (file_exists("ezgroupeventcalendar/user/cache/dayview.tpl-$siteStyle-$language-$year-$month-$day-$groupID.cache"))
     unlink( "ezgroupeventcalendar/user/cache/dayview.tpl-$siteStyle-$language-$year-$month-$day-$groupID.cache" );
    if (file_exists("ezgroupeventcalendar/user/cache/monthview.tpl-$siteStyle-$language-$year-$month-$groupID.cache"))
     unlink( "ezgroupeventcalendar/user/cache/monthview.tpl-$siteStyle-$language-$year-$month-$groupID.cache" );
    if (file_exists("ezgroupeventcalendar/user/cache/dayview.tpl-$siteStyle-$language-$year-$month-$day-$groupID-private.cache"))
     unlink( "ezgroupeventcalendar/user/cache/dayview.tpl-$siteStyle-$language-$year-$month-$day-$groupID-private.cache" );
    if (file_exists("ezgroupeventcalendar/user/cache/monthview.tpl-$siteStyle-$language-$year-$month-$groupID-private.cache"))
     unlink( "ezgroupeventcalendar/user/cache/monthview.tpl-$siteStyle-$language-$year-$month-$groupID-private.cache" );
}

//Adds a "0" in front of the value if it's below 10.
function addZero( $value )
{
    settype( $value, "integer" );
    $ret = $value;
    if ( $ret < 10 )
    {
        $ret = "0". $ret;
    }
    return $ret;
}

function convertToTwelve( $value )
{
	switch( $value )
	{
		case "13" :
		{
			$ret = 1;
		}
		break;

		case "14" :
		{
			$ret = 2;
		}
		break;

		case "15" :
		{
			$ret = 3;
		}
		break;

		case "16" :
		{
			$ret = 4;
		}
		break;

		case "17" :
		{
			$ret = 5;
		}
		break;

		case "18" :
		{
			$ret = 6;
		}
		break;

		case "19" :
		{
			$ret = 7;
		}
		break;

		case "20" :
		{
			$ret = 8;
		}
		break;

		case "21" :
		{
			$ret = 9;
		}
		break;

		case "22" :
		{
			$ret = 10;
		}
		break;

		case "23" :
		{
			$ret = 11;
		}
		break;

		case "00" :
		{
			$ret = 12;
		}
		break;

		default :
		{
			$ret = $value;
		}
		break;
	}

	$ret = addZero( $ret );

	return $ret;
}

function convertToTwentyFour( $value, $period )
{
	switch( $value )
	{
		case "01" :
		{
			if( $period == "pm" )
				$ret = 13;
			else
				$ret = $value;
		}
		break;

		case "02" :
		{
			if( $period == "pm" )
				$ret = 14;
			else
				$ret = $value;
		}
		break;

		case "03" :
		{
			if( $period == "pm" )
				$ret = 15;
			else
				$ret = $value;
		}
		break;

		case "04" :
		{
			if( $period == "pm" )
				$ret = 16;
			else
				$ret = $value;
		}
		break;

		case "05" :
		{
			if( $period == "pm" )
				$ret = 17;
			else
				$ret = $value;
		}
		break;

		case "06" :
		{
			if( $period == "pm" )
				$ret = 18;
			else
				$ret = $value;
		}
		break;

		case "07" :
		{
			if( $period == "pm" )
				$ret = 19;
			else
				$ret = $value;
		}
		break;

		case "08" :
		{
			if( $period == "pm" )
				$ret = 20;
			else
				$ret = $value;
		}
		break;

		case "09" :
		{
			if( $period == "pm" )
				$ret = 21;
			else
				$ret = $value;
		}
		break;

		case "10" :
		{
			if( $period == "pm" )
				$ret = 22;
			else
				$ret = $value;
		}
		break;

		case "11" :
		{
			if( $period == "pm" )
				$ret = 23;
			else
				$ret = $value;
		}
		break;

		case "12" :
		{
			if( $period == "pm" )
			{
				$ret = 12;
			}
			else
			{
				$ret = 0;
			}
		}
		break;

		default :
		{
			$ret = $value;
		}
		break;
	}

	$ret = addZero( $ret );

	return $ret;
}

?>