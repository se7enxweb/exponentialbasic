<?php
// 
// $Id: dayview.php 7697 2001-10-08 14:39:10Z jhe $
//
// Created on: <08-Jan-2001 12:48:35 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );

include_once( "ezcalendar/classes/ezappointment.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZCalendarMain", "Language" );
$StartTimeStr = $ini->read_var( "eZCalendarMain", "DayStartTime" );
$StopTimeStr = $ini->read_var( "eZCalendarMain", "DayStopTime" );
$IntervalStr = $ini->read_var( "eZCalendarMain", "DayInterval" );

$Locale = new eZLocale( $Language );

$user =& eZUser::currentUser();
$session =& eZSession::globalSession();
$session->fetch();

if ( $user == false )
    $userID = false;
else
    $userID = $user->id();

if ( isset( $GetByUser ) )
    $userID = $GetByUserID;

if ( ( $session->variable( "ShowOtherCalendarUsers" ) == false ) || ( isSet( $GetByUser ) ) )
{
    $session->setVariable( "ShowOtherCalendarUsers", $userID );
}
else
{
    $userID = $session->variable( "ShowOtherCalendarUsers" );
}

$tmpUser = new eZUser( $userID );
$date = new eZDate();

if ( isset( $Year ) && $Year != "" && isset( $Month ) && $Month != "" && isset( $Day ) && $Day != "" )
{
    $date->setYear( $Year );
    $date->setMonth( $Month );
    $date->setDay( $Day );
}
else
{
    $Year = $session->variable( "Year" );
    $Month = $session->variable( "Month" );
    $Day = $session->variable( "Day" );
    if ( !$Year && !$Month && !$Day )
    {
        $Year = $date->year();
        $Month = $date->month();
        $Day = $date->day();
    }
    else
    {
        $date->setYear( $Year );
        $date->setMonth( $Month );
        $date->setDay( $Day );
    }
}

$session->setVariable( "Year", $Year );
$session->setVariable( "Month", $Month );
$session->setVariable( "Day", $Day );

$zMonth = addZero( $Month );
$zDay = addZero( $Day );
$isMyCalendar = ( $user && $user->id() == $userID ) ? "-private" : "";
$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl", $Language, "dayview.php",
                     "default", "ezcalendar" . "/user", "$Year-$zMonth-$zDay-$userID" . $isMyCalendar );

$t->set_file( "day_view_page_tpl", "dayview.tpl" );

if ( $StoreSiteCache && $t->hasCache() )
{
    print( $t->cache() );
}
else
{
    $t->setAllStrings();

    $t->set_block( "day_view_page_tpl", "user_item_tpl", "user_item" );
    $t->set_block( "day_view_page_tpl", "time_table_tpl", "time_table" );
    $t->set_block( "time_table_tpl", "no_appointment_tpl", "no_appointment" );
    $t->set_block( "time_table_tpl", "private_appointment_tpl", "private_appointment" );
    $t->set_block( "time_table_tpl", "public_appointment_tpl", "public_appointment" );
    $t->set_block( "public_appointment_tpl", "delete_check_tpl", "delete_check" );
    $t->set_block( "day_view_page_tpl", "week_tpl", "week" );
    $t->set_block( "week_tpl", "day_tpl", "day" );
    $t->set_block( "week_tpl", "empty_day_tpl", "empty_day" );

    $t->set_var( "month_number", $Month );
    $t->set_var( "year_number", $Year );
    $t->set_var( "day_number", $Day );
    $t->set_var( "long_date", $Locale->format( $date, false ) );


    $today = new eZDate();
    $tmpDate = new eZDate( $date->year(), $date->month(), $date->day() );
    $tmpAppointment = new eZAppointment();

    // fetch the appointments for the selected day
    $appointments =& $tmpAppointment->getByDate( $tmpDate, $tmpUser, false );

    // set start/stop and interval times
    $startTime = new eZTime();
    $stopTime = new eZTime();
    $interval = new eZTime();

    if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StartTimeStr, $startArray ) )
    {
        $hour = $startArray[2];
        $startTime->setHour( $hour );

        $min = $startArray[3];
        $startTime->setMinute( $min );

        $startTime->setSecond( 0 );
    }

    if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StopTimeStr, $stopArray ) )
    {
        $hour = $stopArray[2];
        $stopTime->setHour( $hour );

        $min = $stopArray[3];
        $stopTime->setMinute( $min );

        $stopTime->setSecond( 0 );
    }

    if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $IntervalStr, $intervalArray ) )
    {
        $hour = $intervalArray[2];
        $interval->setHour( $hour );

        $min = $intervalArray[3];
        $interval->setMinute( $min );

        $interval->setSecond( 0 );
    }

    // increase schedule span to fit early/late appointments
    $midNight = new eZTime();
    $midNight->setSecondsElapsed( 0 );
    $lastInterval = $midNight->subtract( $interval );
    $firstInterval = $midNight->add( $interval );

    foreach ( $appointments as $appointment )
    {
        if ( !$appointment->allDay() )
        {
            $appStartTime =& $appointment->startTime();
            $appStopTime =& $appointment->stopTime();

            if ( $appStartTime->isGreater( $firstInterval ) )
                $startTime = $midNight;

            while ( $appStartTime->isGreater( $startTime ) )
            {
                $startTime = $startTime->subtract( $interval );
            }

            if ( $lastInterval->isGreater( $appStopTime ) )
            {
                $stopTime = new eZDateTime( $date->year(), $date->month(), $date->day(), 23, 59 );
                $stopTime->setTimeStamp( $stopTime->timestamp() );
             }

            while ( $stopTime->isGreater( $appStopTime ) )
            {
                $stopTime = $stopTime->add( $interval );
            }
        }
        else
        {
            $appStartTime =& $appointment->startTime();
            $appStopTime =& $appointment->stopTime();

            if ( $appStartTime->isGreater( $firstInterval ) )
                $startTime = $midNight;

            while ( $appStartTime->isGreater( $startTime ) )
            {
                $startTime = $startTime->subtract( $interval );
            }

            if ( $lastInterval->isGreater( $appStopTime ) )
            {
                $stopTime = new eZDateTime( $date->year(), $date->month(), $date->day(), 23, 59 );
                $stopTime->setTimeStamp( $stopTime->timestamp() );
            }

            while ( $stopTime->isGreater( $appStopTime ) )
            {
                $stopTime = $stopTime->add( $interval );
            }
        }
    }

    for ( $i = 0; $i < count( $appointments ); $i++ )
    {
        $eZDateTimeObject = new eZDateTime();
        $eZDateTimeObject->setTimeStamp( $appointments[$i]->Date );

        if ( $appointments[$i]->allDay() )
        {
            $dateTime = new eZDateTime( $date->year(), $date->month(), $date->day() );
            $dateTime->setSecondsElapsed( $startTime->secondsElapsed() );
            // $appointments[$i]->setDateTime( $dateTime );

            // $appointments[$i]->setDuration( $stopTime->Time->timestamp() - $startTime->secondsElapsed() );
            // $appointments[$i]->store();
        }
    }

    // places appointments into columns, creates extra columns as necessary
    $numRows = 0;
    $numCols = 1;
    $tableCellsId = array();       // appointmend id for a cell
    $tableCellsRowSpan = array();  // rowspan for a cell
    $colTaken = array();           // number of non free rows in the current column, after the last appointment. 0 means col free.
    $emptyRows = array();          // number of empty rows in the current column, after the last appointment
    $appointmentDone = array();    // true when the appointment has been inserted into the table
    $tmpTime = new eZTime();
    $tmpTime->setSecondsElapsed( $startTime->secondsElapsed() );

    while ( $tmpTime->isGreater( $stopTime ) )
    {
        $numRows++;
        //$tableCellsId[$numRows - 1] = array();
        //$tableCellsRowSpan[$numRows - 1] = array();
        //$colTaken[$numCols] = 0;
        // marks cells as taken, -1
        for ( $col = 0; $col < $numCols; $col++ )
        {
            if ( isset( $colTaken[$col] ) && $colTaken[$col] > 0 )
            {
                $tableCellsId[$numRows - 1][$col] = -1;
            }
        }

        foreach ( $appointments as $appointment )
        {
            $appointmentDone[ $appointment->id() ] = false;
            // avoid wrapping around midnight
            $nextInterval = $tmpTime->add( $interval );
            if ( $nextInterval->isGreater( $tmpTime ) )
                $nextInterval = new eZTime( 23, 59 );

            // if this appointment should be inserted into the table now
            if ( isset( $appointmentDone[$appointment->id()] ) && $appointmentDone[$appointment->id()] == false &&
                 intersects( $appointment, $tmpTime, $nextInterval ) == true )
            {
                $foundFreeColumn = false;
                $col = 0;
                while ( $foundFreeColumn == false )
                {
                    //$tableCellsId[$numRows-1][$col] = 0;

                    // the column is free, insert appointment here
                    if ( $foundFreeColumn == false && !isset( $tableCellsId[$numRows-1][$col] ) ) //&& $tableCellsId[$numRows-1][$col] == 0 )
                    {
                        $tableCellsId[$numRows-1][$col] = $appointment->id();
                        $tableCellsRowSpan[$numRows-1][$col] = appointmentRowSpan( $appointment, $tmpTime, $interval );
                        $colTaken[$col] = $tableCellsRowSpan[$numRows-1][$col];
                        $appointmentDone[$appointment->id()] = true;
                        $foundFreeColumn = true;

                        // if we created a new column, mark leading empty spaces
                        // this was determined not to be needed at this point, so it's left here in case we messed up.

                        /* if ( $col >= $numCols )
                            $emptyRows[$col] = $numRows - 1;

                        if ( isset( $emptyRows[ $col ] ) && $emptyRows[ $col ] > 0 )
                        {
                            $tableCellsId[$numRows - 1 - $emptyRows[$col]][$col] = -2;
                            $tableCellsRowSpan[$numRows - 1 - $emptyRows[$col]][$col] = $emptyRows[$col];
                            $emptyRows[$col] = 0;
                        } */

                    }
                    elseif( $appointment->AllDay() && isset( $appointmentDone[$appointment->id() ] )
                                                    && $appointmentDone[ $appointment->id() ] == false
                    )
                    {
                        if ( !isset( $tableCellsId[$numRows-1][$col] ) ) //&& $tableCellsId[$numRows-1][$col] == 0 )
                        {
                            $tableCellsId[$numRows - 1][$col] = $appointment->id();
                            $tableCellsRowSpan[$numRows - 1][$col] = appointmentRowSpan($appointment, $tmpTime, $interval);
                            $colTaken[$col] = $tableCellsRowSpan[$numRows - 1][$col];
                            $appointmentDone[$appointment->id()] = true;
                            $foundFreeColumn = true;

                            // if we created a new column, mark leading empty spaces
                            if ($col >= $numCols)
                                $emptyRows[$col] = $numRows - 1;

                            if (isset($emptyRows[$col]) && $emptyRows[$col] > 0) {
                                $tableCellsId[$numRows - 1 - $emptyRows[$col]][$col] = -2;
                                $tableCellsRowSpan[$numRows - 1 - $emptyRows[$col]][$col] = $emptyRows[$col];
                                $emptyRows[$col] = 0;
                            }
                        }
                    }
                    else
                    {
                        $foundFreeColumn = true;
                    }

                    // the column was not free, try the next one
                    $col++;
                    if ( $col > $numCols )
                        $numCols++;
                }
            }
        }

        // decrease/increase counts as we move down
        for ( $col = 0; $col < $numCols; $col++ )
        {
            if ( isset( $colTaken[$col] ) && $colTaken[$col] > 0 )
            {
                $colTaken[$col]--;
            }

            if ( isset( $tableCellsId[ $numRows - 1 ][$col] ) && $tableCellsId[$numRows - 1][$col] == 0 )
            {
                $emptyRows[$col]++;
            }
        }

        if ( $tmpTime > $tmpTime->add( $interval ) )
            $tmpTime = new eZTime( 23, 59 );
        else
            $tmpTime = $tmpTime->add( $interval );
    }

    // mark remaining empty spaces as empty, -2
    for ( $col = 0; $col < $numCols; $col++ )
    {
        if ( isset( $emptyRows[$col] ) && $emptyRows[$col] > 0 )
        {
            $tableCellsId[$numRows - $emptyRows[$col]][$col] = -2;
            $tableCellsRowSpan[$numRows - $emptyRows[$col]][$col] = $emptyRows[$col];
        }
    }


//debug contents table
// print( "Rows: " . $numRows . "   Cols: " . $numCols . "<br />" );
// print( "<table border=\"1\">" );
// for ( $row=0; $row<$numRows; $row++ )
// {
//     print( "<tr>" );
//     for ( $col=0; $col<$numCols; $col++ )
//     {
//         if( isset( $tableCellsId[$row][$col] ) && isset( $tableCellsRowSpan[$row][$col] ) )
//         print( "<td>" . $tableCellsId[$row][$col] . " / " . $tableCellsRowSpan[$row][$col] . "</td>" );
//     }
//     print( "</tr>" );
// }
// print( "</table>" );

    // prints out the time table
    $emptyDone = false;
    $now = new eZTime();
    $nowSet = false;
    $row = 0;
    $tmpTime = new eZTime();
    $tmpTime->setSecondsElapsed( $startTime->secondsElapsed() );

    while ( $tmpTime->isGreater( $stopTime ) )
    {
        $t->set_var( "short_time", $Locale->format( $tmpTime, true ) );
        $t->set_var( "start_time", addZero( $tmpTime->hour() ) . addZero( $tmpTime->minute() ) );

        $drawnColumn = array();

        $t->set_var( "public_appointment", "" );
        $t->set_var( "private_appointment", "" );
        $t->set_var( "no_appointment", "" );
        $t->set_var( "delete_check", "" );

        // rarely needed now but helpful in data processing review
        // print_r( $tableCellsId );

        for ( $col = 0; $col < $numCols; $col++ )
        {
            if( isset( $tableCellsId[$row][$col] ) )
                $appointmentId = $tableCellsId[$row][$col];
            else
                $appointmentId = -2;

            // an appointment
            if ( $appointmentId > 0 )
            {
                $appointment = new eZAppointment( $appointmentId );

                // a private appointment
                if ( $appointment->isPrivate() && $appointment->userID() != $userID )
                {
                    $t->set_var( "td_class", "bglight" );
                    $t->set_var( "rowspan_value", $tableCellsRowSpan[$row][$col] );

                    $t->parse( "private_appointment", "private_appointment_tpl", true );
                } 
                else // a public appointment
                {

                    $t->set_var( "td_class", "bglight" );
                    $t->set_var( "rowspan_value", $tableCellsRowSpan[$row][$col] );
                    $t->set_var( "rowspan_markup_value", 'rowspan="'. $tableCellsRowSpan[$row][$col] . '"' );
                    // $t->set_var( "rowspan_markup_value", '' );

                    $t->set_var( "appointment_id", $appointment->id() );
                    $t->set_var( "appointment_name", $appointment->name() );
                    $t->set_var( "appointment_description", $appointment->description() );
                    $t->set_var( "edit_button", "Edit" );

                    $t->parse( "delete_check", "delete_check_tpl" );
                    $t->parse( "public_appointment", "public_appointment_tpl", true );
                }
            }
            else if ( $appointmentId == -2 ) // an empty space
            {
                $t->set_var("td_class", "bgdark");
                if ( isset( $tableCellsRowSpan[$row][$col] ) )
                {
                    $t->set_var("rowspan_value", $tableCellsRowSpan[$row][$col]);
                    $t->set_var("rowspan_markup_value", '');
                } else {
                    $t->set_var("rowspan_value", '');
                    $t->set_var("rowspan_markup_value", '');
                }
                $t->parse( "no_appointment", "no_appointment_tpl", true );
            }
        }

        $t->set_var( "td_class", "" );

// Mark current time with bgcurrent. Does not currently go well together with caching.
//        if ( $date->equals( $today ) && $nowSet == false &&
//        $tmpTime->isGreater( $now, true ) && $now->isGreater( $tmpTime->add( $interval ) ) )
//        {
//            $t->set_var( "td_class", "bgcurrent" );
//            $nowSet = true;
//        }

        if ( $tmpTime > $tmpTime->add( $interval ) )
            $tmpTime = new eZTime( 23, 59 );
        else
            $tmpTime = $tmpTime->add( $interval );
        $row++;

        $t->parse( "time_table", "time_table_tpl", true );
    }


    // User list
    $user_array =& eZUser::getAll();

    foreach ( $user_array as $userItem )
    {
        $t->set_var( "user_id", $userItem->id() );
        $t->set_var( "user_firstname", $userItem->firstName() );
        $t->set_var( "user_lastname", $userItem->lastName() );

        if ( $tmpUser->id() == $userItem->id() )
        {
            $t->set_var( "user_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "user_is_selected", "" );
        }

        $t->parse( "user_item", "user_item_tpl", true );
    }


    // previous day link
    $date->setYear( $Year );
    $date->setMonth( $Month );

    $date->setDay( $Day - 1 );
    if ( $date->day() < 1 )
    {
        $date->setMonth( $Month - 1 );
        if ( $date->month() < 1 )
        {
            $date->setMonth( 12 );
            $date->setYear( $Year - 1 );
        }
        $date->setDay( $date->daysInMonth() );
    }
    $t->set_var( "pd_year_number", $date->year() );
    $t->set_var( "pd_month_number", $date->month() );
    $t->set_var( "pd_day_number", $date->day() );

    // next day link
    $date->setYear( $Year );
    $date->setMonth( $Month );

    $date->setDay( $Day + 1 );
    if ( $date->day() > $date->daysInMonth() )
    {
        $date->setDay( 1 );
        $date->setMonth( $Month + 1 );
        if ( $date->month() > 12 )
        {
            $date->setMonth( 1 );
            $date->setYear( $Year + 1 );
        }
    }
    $t->set_var( "nd_year_number", $date->year() );
    $t->set_var( "nd_month_number", $date->month() );
    $t->set_var( "nd_day_number", $date->day() );

    // previous month link
    $date->setYear( $Year );
    $date->setDay( $Day );

    $date->setMonth( $Month - 1 );
    if ( $date->month() < 1 )
    {
        $date->setMonth( 12 );
        $date->setYear( $Year - 1 );
    }
    if ( $date->day() > $date->daysInMonth() )
        $date->setDay( $date->daysInMonth() );
    $t->set_var( "pm_year_number", $date->year() );
    $t->set_var( "pm_month_number", $date->month() );
    $t->set_var( "pm_day_number", $date->day() );

    // next month link
    $date->setYear( $Year );
    $date->setDay( $Day );

    $date->setMonth( $Month + 1 );
    if ( $date->month() > 12 )
    {
        $date->setMonth( 1 );
        $date->setYear( $Year + 1 );
    }
    if ( $date->day() > $date->daysInMonth() )
        $date->setDay( $date->daysInMonth() );
    $t->set_var( "nm_year_number", $date->year() );
    $t->set_var( "nm_month_number", $date->month() );
    $t->set_var( "nm_day_number", $date->day() );


    // parse month table
    $date->setYear( $Year );
    $date->setMonth( $Month );
    $date->setDay( $Day );

    $t->set_var( "month_number", $date->month() );
    $t->set_var( "month_name", $Locale->monthName( $date->monthName(), false ) );

    $t->set_var( "week", "" );
    for ( $week = 0; $week < 6; $week++ )
    { 
        $t->set_var( "day", "" );
        $t->set_var( "empty_day", "" );

        for ( $day = 1; $day <= 7; $day++ )
        {
            $date->setDay( 1 );
            $firstDay = $date->dayOfWeek( $Locale->mondayFirst() );

            $currentDay = $day + ( $week * 7 ) - $firstDay + 1;

            if ( ( ( $day + ( $week * 7 ) )  >= $firstDay ) &&
                 ( $currentDay <= $date->daysInMonth() ) )
            {
                $date->setDay( $currentDay );

                $t->set_var( "td_class", "bglight" );
//                if ( $date->equals( $today ) )
//                    $t->set_var( "td_class", "bgcurrent" );

                $t->set_var( "day_number", $currentDay );
                $t->parse( "day", "day_tpl", true );
            }
            else
            {
                $t->set_var( "td_class", "bglight" );                
                $t->parse( "day", "empty_day_tpl", true );
            }
        }
        $t->parse( "week", "week_tpl", true );

        if ( $currentDay >= $date->daysInMonth() )
        {
            $week = 6;
        }
    }

    $t->storeCache( "output", "day_view_page_tpl", true );
}


// returns the number of rows an appointment covers.
function appointmentRowSpan( &$appointment, &$startTime, &$interval )
{
    $ret = 1;
    $tmpTime = new eZTime();
    $tmpTime->setSecondsElapsed( $startTime->secondsElapsed() );
    $aStop =& $appointment->stopTime();


    while ( $tmpTime->isGreater( $aStop ) )
    {
        if ( $tmpTime > $tmpTime->add( $interval ) )
        {
            $tmpTime = new eZTime( 23, 59 );
        }
        else
        {
            $tmpTime = $tmpTime->add( $interval->timestamp() );
        }
        $ret++;
    }

    return $ret;
}


// checks if an appointment intersects with a given time interval
function intersects( &$app, &$startTime, &$stopTime )
{
    $ret = false;
    $appStartTime =& $app->startTime();
    $appStopTime =& $app->stopTime();

    // appstart is between start and stop
    if ( $startTime->isGreater( $appStartTime, true ) == true &&
         $appStartTime->isGreater( $stopTime ) == true )
    {
        $ret = true;
    }
    // appstop is between start and stop
    else if ( $startTime->isGreater( $appStopTime ) == true &&
              $appStopTime->isGreater( $stopTime, true ) == true )
    {
        $ret = true;
    }
    // appstart is before start, and appstop is after stop
    else if ( $appStartTime->isGreater( $startTime ) == true &&
              $stopTime->isGreater( $appStopTime ) == true )
    {
        $ret = true;
    }

    return $ret;
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

?>