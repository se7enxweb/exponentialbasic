<?php
//
// $Id: dayview.php,v 1.38 2001/03/12 13:55:47 fh Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// This source file is part of Exponential Basic, publishing software.
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
// debug stuff
// Initialise the HTML4 Table rendering (see Var_Dump/Renderer/HTML4_Table.php)
/*
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );
include_once( "classes/ezlocale.php" );

include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );

include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupnoshow.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeditor.php" );
*/

$ini = &eZINI::instance( 'site.ini' );
$SiteDesign = $ini->variable("site", "SiteDesign");

$Language = $ini->variable("eZGroupEventCalendarMain", "Language");
$StartTimeStr = $ini->variable("eZGroupEventCalendarMain", "DayStartTime");
$StopTimeStr = $ini->variable("eZGroupEventCalendarMain", "DayStopTime");

$TemplateDir = $ini->variable( "eZGroupEventCalendarMain", "TemplateDir" );
$GlobalSectionID = $ini->variable( "eZGroupEventCalendarMain", "DefaultSection" );

//$IntervalStr = $ini->variable( "eZGroupEventCalendarMain", "DayInterval" );
$IntervalStr = "00:15"; // for future refactoring, this doesn't need to be preg'ed
$Locale = new eZLocale($Language);
$curDate = new eZDate();
//die('day ' .$curDate->year().$curDate->month().$curDate->day());
//Var_Dump::display($curDate);
$curYear = $curDate->year();
$curMonth = $curDate->month();
$curDay = $curDate->day();

$user = eZUser::currentUser();

if( $user )
{
    $userID = $user->id();
}
elseif (!$user)
{
    $userID = 0;
}

$session = &eZSession::globalSession();
$session->fetch();

$URL = explode("/", $_SERVER["REQUEST_URI"]);

if (count($URL) <= 5) {
    if (isset($URL[6]) && is_numeric($URL[6])) {
        $groupID = $URL[6];
    } else {
        $groupID = 0;
    }
} else {
    $readGroup = new eZUserGroup($session->variable("ShowOtherCalendarGroups"));
    $groupID = $readGroup->id();
}

if (!isset($GetByGroupID)) {
    $GetByGroupID = $groupID;
}

if ($GetByGroupID == false) {
    $GetByGroupID = $groupID;
}

if (!isset($GetByUserID)) {
    $GetByUserID = $userID;
}

if (!isset($Type)) {
    $Type = 0;
}

$type = new eZGroupEventType($Type);

if (
    $session->variable("ShowOtherCalendarGroups") == false ||
    isset($GetByGroup)
) {
    $session->setVariable("ShowOtherCalendarGroups", $GetByGroupID);
}
$tmpGroup = new eZUserGroup($session->variable("ShowOtherCalendarGroups"));

//Get the TYPE session data
if (
    $session->variable("ShowOtherCalendarTypes") == false ||
    isset($GetByTypeID)
) {
    if (!isset($GetByTypeID)) {
        $GetByTypeID = 0;
    }

    $session->setVariable("ShowOtherCalendarTypes", $GetByTypeID);
}
$type = new eZGroupEventType($session->variable("ShowOtherCalendarTypes"));

if (!isset($GetByTypeID)) {
    $GetByTypeID = $type->id();
}

$date = new eZDate();

if ($Year != "" && $Month != "" && $Day != "") {
    $date->setYear($Year);
    $date->setMonth($Month);
    $date->setDay($Day);
} else {
    $Year = $date->year();
    $Month = $date->month();
    $Day = $date->day();
}

$session->setVariable("Year", $Year);
$session->setVariable("Month", $Month);
$session->setVariable("Day", $Day);

$zMonth = addZero($Month);
$zDay = addZero($Day);
$isMyCalendar = $user && $userID == $GetByUserID ? "-private" : "";


// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$templateDirTmp = $sectionObject->templateStyle();

if ( $templateDirTmp != null && trim( $templateDirTmp ) != "" )
{
    $TemplateDir = "kernel/ezgroupeventcalendar/user/" . preg_replace( "/(.+)\/.+(\/?)/", "/\\1/$templateDirTmp\\2", $TemplateDir );
}
else
{
    $TemplateDir = "kernel/ezgroupeventcalendar/user/" . $ini->variable( "eZGroupEventCalendarMain", "TemplateDir" );
}

//$t = new eZTemplate( "kernel/ezgroupeventcalendar/user/" . $ini->variable( "eZGroupEventCalendarMain", "TemplateDir" ),
$t = new eZTemplate(  $TemplateDir,
    "kernel/ezgroupeventcalendar/user/intl",
    $Language,
    "dayview.php",
    "default",
    "kernel/ezgroupeventcalendar" . "/user",
    "$Year-$zMonth-$zDay-$GetByUserID" . $isMyCalendar
);

$t->set_file("day_view_page_tpl", "dayview.tpl");

$user_groupID = "-1";
$groupsList = "-1";

// groups not to include
$noShowGroup = new eZGroupNoShow();

$rootAccess = false;

// Determin the users group membership and access
if ($user) {
    if (
        $user->hasRootAccess() == true ||
        eZPermission::checkPermission(
            $user,
            "eZGroupEventCalendar",
            "WriteToRoot"
        )
    ) {
        $groups = new eZUserGroup();
        $groupsList = $groups->getAll(true);
        $rootAccess = true;
    } else {
        $groupsList = $user->groups();
    }

    $permission = new eZGroupEditor();

    // Determin if the user get the time stamps as "new event" links or not
    if (
        $permission->hasEditPermission($user->id(), $tmpGroup->id()) == true ||
        $rootAccess == true
    ) {
        $editor = true;
    } elseif ($permission->getByGroup($tmpGroup->id()) == false) {
        foreach ($groupsList as $groups) {
            if (
                $permission->groupHasEditor($groups->id()) == false &&
                $noShowGroup->groupEntry($groups->id()) == false
            ) {
                if ($tmpGroup->id() != 0 && $groups->id() == $tmpGroup->id()) {
                    $editor = true;
                    break;
                } elseif ($tmpGroup->id() == 0) {
                    $editor = true;
                    break;
                }
            }
        }
    }
}

//if ( $t->hasCache() && !isSet( $GetByGroup ) )
//{
// print( "cached and $GetByGroup<br />" );
// print( $t->cache() );
//}
//else
//{
//    print( "not cached<br />" );
$t->setAllStrings();

$t->set_block("day_view_page_tpl", "group_item_tpl", "group_item");
$t->set_block("day_view_page_tpl", "type_item_tpl", "type_item");
$t->set_block("day_view_page_tpl", "group_print_tpl", "group_print");
$t->set_block("day_view_page_tpl", "time_display_tpl", "time_display");
$t->set_block("day_view_page_tpl", "time_table_tpl", "time_table");
$t->set_block("day_view_page_tpl", "valid_editor_tpl", "valid_editor");
$t->set_block("time_table_tpl", "no_event_tpl", "no_event");
$t->set_block("time_table_tpl", "private_event_tpl", "private_event");
$t->set_block("time_table_tpl", "public_event_tpl", "public_event");
$t->set_block("time_display_tpl", "new_event_link_tpl", "new_event_link");
$t->set_block("time_display_tpl", "no_new_event_link_tpl", "no_new_event_link");
$t->set_block("public_event_tpl", "delete_check_tpl", "delete_check");
$t->set_block("public_event_tpl", "no_delete_check_tpl", "no_delete_check");
$t->set_block("day_view_page_tpl", "all_day_event_tpl", "all_day_event");
$t->set_block(
    "all_day_event_tpl",
    "all_day_delete_check_tpl",
    "all_day_delete_check"
);
$t->set_block("day_view_page_tpl", "week_tpl", "week");
$t->set_block("week_tpl", "day_tpl", "day");
$t->set_block("week_tpl", "empty_day_tpl", "empty_day");
$t->set_block("day_view_page_tpl", "day_links_tpl", "day_links");
$t->set_block("time_table_tpl", "fifteen_event_tpl", "fifteen_event");
$t->set_block(
    "fifteen_event_tpl",
    "fifteen_delete_check_tpl",
    "fifteen_delete_check"
);
$t->set_block(
    "fifteen_event_tpl",
    "fifteen_no_delete_check_tpl",
    "fifteen_no_delete_check"
);

$t->set_var("sitedesign", $SiteDesign);
$t->set_var("month_number", $Month);
$t->set_var("year_number", $Year);
$t->set_var("day_number", $Day);
$t->set_var("long_date", $Locale->format($date, false));
$t->set_var("year_cur", $curYear);
$t->set_var("month_cur", $curMonth);
$t->set_var("day_cur", $curDay);

if ($tmpGroup->id() != 0) {
    $t->set_var("group_print_name", $tmpGroup->name());
    $t->set_var("group_print_id", $tmpGroup->id());
    $t->parse("group_print", "group_print_tpl", true);
} else {
    $t->set_var("group_print_id", $tmpGroup->id());
    $t->set_var("group_print", "");
}

/*    if( $editor == true && $tmpTime->minute() == '00' )
	{
		$t->parse( "new_event_link", "new_event_link_tpl" );
		$t->set_var( "no_new_event_link", "" );
	}
	else
	{
		$t->parse( "no_new_event_link", "no_new_event_link_tpl" );
		$t->set_var( "new_event_link", "" );
	}
	else
	{
		$t->set_var( "no_new_event_link", "" );
		$t->set_var( "new_event_link", "" );
	}*/

$today = new eZDate();
$tmpDate = new eZDate($date->year(), $date->month(), $date->day());
$tmpGroupEvent = new eZGroupEvent();

// Fetch all the events for day
if ($tmpGroup->id() == 0 && $type->id() == 0) {
    $events = &$tmpGroupEvent->getAllByDate($tmpDate, true);
}
// Fetch all appointments by type
elseif ($tmpGroup->id() == 0 && $type->id() != 0) {
    $events = &$tmpGroupEvent->getByGroupType($tmpDate, $tmpGroup, $type, true);
}
// Fetch all appointments by Group
else {
    $events = &$tmpGroupEvent->getByDate($tmpDate, $tmpGroup, true);
}

// set start/stop and interval times
$startTime = new eZTime();
$stopTime = new eZTime();
$interval = new eZTime();

if (
    preg_match(
        "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#",
        $StartTimeStr,
        $startArray
    )
) {
    $hour = $startArray[2];
    $startTime->setHour($hour);

    $min = $startArray[3];
    $startTime->setMinute($min);

    $startTime->setSecond(0);
    //  Var_Dump::display($startArray);
}

if (
    preg_match(
        "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#",
        $StopTimeStr,
        $stopArray
    )
) {
    $hour = $stopArray[2];
    $stopTime->setHour($hour);

    $min = $stopArray[3];
    $stopTime->setMinute($min);

    $stopTime->setSecond(0);
}

if (
    preg_match(
        "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#",
        $IntervalStr,
        $intervalArray
    )
) {
    $hour = $intervalArray[2];
    $interval->setHour($hour);

    $min = $intervalArray[3];
    $interval->setMinute($min);

    $interval->setSecond(0);
}
$dayArray = [
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday",
];

foreach ($dayArray as $dayname) {
    $shortDayName = strtolower(substr($dayname, 0, 3));
    $dayDistance = getDayDiff($date->dayName(false), $shortDayName);
    $compDate = $date;
    $compDate->move(0, 0, $dayDistance);
    $t->set_var("top_day_number", $compDate->day());
    $t->set_var("top_month_number", $compDate->month());
    $t->set_var("top_year_number", $compDate->year());
    $t->set_var("day_name", $dayname);
    if ($date->dayName(true) == $shortDayName) {
        $t->set_var("class_name", "gcalDayViewTopBarSelect");
    } else {
        $t->set_var("class_name", "gcalDayViewTopBar");
    }
    $t->parse("day_links", "day_links_tpl", true);
}
$t->set_var("the_month", $date->month());
$t->set_var("the_day", $date->day());
$t->set_var("the_year", $date->year());

// increase schedule span to fit early/late events
$midNight = new eZTime();
$midNight->setSecondsElapsed(0);
$lastInterval = $midNight->subtract($interval);
$firstInterval = $midNight->add($interval);
for ($i = 0; $i < sizeof($events); $i++) {
    $appStartTime = &$events[$i]->startTime();
    $appStopTime = &$events[$i]->stopTime();
    $appStartStr = $appStartTime->hour() . addZero($appStartTime->minute());
    $appStopStr = $appStopTime->hour() . addZero($appStopTime->minute());
    $startStr = $startTime->hour() . addZero($startTime->minute());
    $stopStr = $stopTime->hour() . addZero($stopTime->minute());
    // adding all_day_event filtering
    if (
        ($appStartTime == $startTime && $appStopTime == $stopTime) ||
        ($appStartStr <= $startStr && $appStopStr >= $stopStr)
    ) {
        $allDayEvents[] = $events[$i];
        $events[$i]->setNoDisplay(true);
        continue;
    }
    if ($appStartTime->isGreater($firstInterval)) {
        $startTime = $midNight;
    }

    while ($appStartTime->isGreater($startTime)) {
        $startTime = $startTime->subtract($interval);
    }

    if ($lastInterval->isGreater($appStopTime)) {
        $stopTime = new eZTime(23, 59);
    }

    while ($stopTime->isGreater($appStopTime)) {
        $stopTime = $stopTime->add($interval);
    }
}

if (isset($allDayEvents)) {
    foreach ($allDayEvents as $adEvent) {
        $t->set_var("all_day_name", stripslashes($adEvent->name()));
        $t->set_var("all_day_overlib_name", addslashes($adEvent->name()));
        $adStartTime = $adEvent->startTime();
        $adStart =
            addZero($adStartTime->hour()) .
            ":" .
            addZero($adStartTime->minute());
        $adStopTime = $adEvent->stopTime();
        $adStop =
            addZero($adStopTime->hour()) . ":" . addZero($adStopTime->minute());

        $t->set_var("all_day_id", $adEvent->id());
        $t->set_var("all_day_start", $adStart);
        $t->set_var("all_day_stop", $adStop);
        $t->set_var("all_day_desc", addslashes($adEvent->description()));
        $t->set_var(
            "all_day_location",
            $adEvent->location() ? $adEvent->location() : ""
        );

        $event_editor = false;
        $groupMember = false;
        if ($rootAccess == true) {
            $groupMember = true;
        } elseif ($groupsList != "-1" && $rootAccess == false) {
            foreach ($groupsList as $groups) {
                //If the user has a matching group set their group id to the matching group else there group id will be -1.
                if ($adEvent->groupID() == $groups->id()) {
                    $user_groupID = $groups->id();
                    $groupMember = true;
                    break;
                }
            }
        }

        // Determine editing permissions for mixed group dayviews
        if ($groupMember == true && $editor == true) {
            $event_editor = true;
        }
        if ($event_editor) {
            $t->parse("all_day_delete_check", "all_day_delete_check_tpl");
        } else {
            $t->set_var("all_day_delete_check", "");
        }

        $t->parse("all_day_event", "all_day_event_tpl", true);
    }
} else {
    $t->set_var("all_day_name", "");
    $t->set_var("all_day_id", "");
    $t->set_var("all_day_start", "");
    $t->set_var("all_day_stop", "");
    $t->set_var("all_day_desc", "");
    $t->set_var("all_day_location", "");
    $t->set_var("all_day_type", "");
    $t->set_var("all_day_priority", "");
    $t->set_var("all_day_status", "");
    $t->set_var("all_day_event", "");
}
for ($i = $startTime->hour(); $i <= $stopTime->hour(); $i++) {
    $t->set_var("short_time", $i . ":00");
    $dispHour = addZero($i);
    $t->set_var("display_start_time", $dispHour . "00");

    if (isset($editor) && $editor == true) {
        $t->parse("new_event_link", "new_event_link_tpl");
        $t->set_var("no_new_event_link", "");
    } else {
        $t->parse("no_new_event_link", "no_new_event_link_tpl");
        $t->set_var("new_event_link", "");
    }

    if (!isset($eventGroup) || (isset($eventGroup) && $eventGroup->id() != 0)) {
        $t->parse("time_display", "time_display_tpl", true);
    }
}

// places events into columns, creates extra columns as necessary
$numRows = 0;
$numCols = 1;
$tableCellsId = []; // appointmend id for a cell
$tableCellsRowSpan = []; // rowspan for a cell
$colTaken = []; // number of non free rows in the current column, after the last event. 0 means col free.
$emptyRows = []; // number of empty rows in the current column, after the last event
$eventDone = []; // true when the event has been inserted into the table
$tmpTime = new eZTime();
$tmpTime->setSecondsElapsed($startTime->secondsElapsed());
while ($tmpTime->isGreater($stopTime) == true) {
    $numRows++;
    $tableCellsId[$numRows - 1] = [];
    $tableCellsRowSpan[$numRows - 1] = [];
    //echo "$numRows rows<br />";

    // marks cells as taken, -1
    for ($col = 0; $col < $numCols; $col++) {
        if (isset($colTaken[$col]) && $colTaken[$col] > 0) {
            $tableCellsId[$numRows - 1][$col] = -1;
        }
    }

    foreach ($events as $event) {
        if ($event->NoDisplay()) {
            continue;
        } // don't show all day events
        // avoid wrapping around midnight
        $nextInterval = $tmpTime->add($interval);
        if ($nextInterval->isGreater($tmpTime)) {
            $nextInterval = new eZTime(23, 59);
        }

        // if this event should be inserted into the table now
        if (
            !isset($eventDone[$event->id()]) &&
            intersects($event, $tmpTime, $nextInterval) == true
        ) {
            $foundFreeColumn = false;
            $col = 0;
            while ($foundFreeColumn == false) {
                // the column is free, insert event here
                if (!isset($tableCellsId[$numRows - 1][$col])) {
                    $tableCellsId[$numRows - 1][$col] = $event->id();
                    $tableCellsRowSpan[$numRows - 1][$col] = eventRowSpan(
                        $event,
                        $tmpTime,
                        $interval
                    );
                    $colTaken[$col] = $tableCellsRowSpan[$numRows - 1][$col];
                    $eventDone[$event->id()] = true;
                    $foundFreeColumn = true;

                    // if we created a new column, mark leading empty spaces
                    if ($col >= $numCols) {
                        $emptyRows[$col] = $numRows - 1;
                    }

                    if (isset($emptyRows[$col]) && $emptyRows[$col] > 0) {
                        // var_dump($emptyRows[$col]);
                        $tableCellsId[$numRows - 1 - $emptyRows[$col]][
                            $col
                        ] = -2;
                        $tableCellsRowSpan[$numRows - 1 - $emptyRows[$col]][
                            $col
                        ] = $emptyRows[$col];
                        $emptyRows[$col] = 0;
                    }
                }

                // the column was not free, try the next one
                $col++;
                if ($col > $numCols) {
                    $numCols++;
                }
            }
        }
    }

    // decrease/increase counts as we move down
    for ($col = 0; $col < $numCols; $col++) {
        if (isset($colTaken[$col]) && $colTaken[$col] > 0) {
            $colTaken[$col]--;
        }

        if (
            isset($tableCellsId[$numRows - 1][$col]) &&
            $tableCellsId[$numRows - 1][$col] == 0
        ) {
            $emptyRows[$col]++;
        }
    }

    if ($tmpTime > $tmpTime->add($interval)) {
        $tmpTime = new eZTime(23, 59);
    } else {
        $tmpTime = $tmpTime->add($interval);
    }
}

// mark remaining empty spaces as empty, -2
for ($col = 0; $col < $numCols; $col++) {
    if (isset($emptyRows[$col]) && $emptyRows[$col] > 0) {
        $tableCellsId[$numRows - $emptyRows[$col]][$col] = -2;
        $tableCellsRowSpan[$numRows - $emptyRows[$col]][$col] =
            $emptyRows[$col];
    }
}

// prints out the time table
$emptyDone = false;
$now = new eZTime();
$nowSet = false;
$row = 0;
$tmpTime = new eZTime();
$tmpTime->setSecondsElapsed($startTime->secondsElapsed());
while ($tmpTime->isGreater($stopTime, false) == true) {
    // this if block is a way to get the 23rd hour displayed
    //     if ($tmpTime->hour() == 22 && $toggle23) $tmpTime = $tmpTime->add( $interval );
    //	$t->set_var( "short_time", $Locale->format( $tmpTime, true ) );
    $t->set_var(
        "start_time",
        addZero($tmpTime->hour()) . addZero($tmpTime->minute())
    );

    $drawnColumn = [];

    $t->set_var("fifteen_event", "");
    $t->set_var("public_event", "");
    $t->set_var("private_event", "");
    $t->set_var("no_event", "");
    $t->set_var("delete_check", "");

    for ($col = 0; $col < $numCols; $col++) {
        if (!isset($tableCellsId[$row][$col])) {
            $eventId = 0;
        } else {
            $eventId = $tableCellsId[$row][$col];
        }

        $event_editor = false;
        $groupMember = false;

        // an event
        if ($eventId > 0) {
            $event = new eZGroupEvent($eventId);

            if ($rootAccess == true) {
                $groupMember = true;
            } elseif ($groupsList != "-1" && $rootAccess == false) {
                foreach ($groupsList as $groups) {
                    //If the user has a matching group set their group id to the matching group else there group id will be -1.
                    if ($event->groupID() == $groups->id()) {
                        $user_groupID = $groups->id();
                        $groupMember = true;
                        break;
                    }
                }
            }

            // Determine editing permissions for mixed group dayviews
            if ($groupMember == true && $editor == true) {
                $event_editor = true;
            }

            $group = new eZUserGroup($event->groupID());
            $t->set_var("event_groupName", $group->name());

            // a private event
            if (
                $event->isPrivate() == true &&
                $groupMember != true &&
                !eZPermission::checkPermission(
                    $user,
                    "eZGroupEventCalendar",
                    "Read"
                )
            ) {
                $t->set_var("td_class", "bgdark");
                $t->set_var("rowspan_value", $tableCellsRowSpan[$row][$col]);

                $t->parse("private_event", "private_event_tpl", true);
            }
            // a public event or a private event that a non group member can read
            elseif (
                $event->isPrivate() == false ||
                ($event->isPrivate() == true &&
                    eZPermission::checkPermission(
                        $user,
                        "eZGroupEventCalendar",
                        "Read"
                    ))
            ) {
                // var_dump($tableCellsRowSpan[$row][$col]);
                $t->set_var("td_class", "bgdark");
                $t->set_var("rowspan_value", $tableCellsRowSpan[$row][$col]);
                $t->set_var("event_id", $event->id());
                $t->set_var("event_name", stripslashes($event->name()));
                $t->set_var("overlib_event_name", addslashes($event->name()));
                $t->set_var(
                    "event_description",
                    stripslashes($event->description(false))
                );
                $t->set_var(
                    "overlib_event_description",
                    addslashes($event->description(false))
                );
                $t->set_var("edit_button", "Edit");
                $eventDivHeight = getEventHeight($event);
                $t->set_var("event_div_height", $eventDivHeight);
                $permission = new eZGroupEditor();
                // This should not be but was needed to display the date correctly in the overlib ui.
                $evStop = $event->duration();
                $evStart = $event->dateTime();
                $evStartTime =
                    $evStart->hour() . ":" . addZero($evStart->minute());
                $evStopTime =
                    $evStop->hour() . ":" . addZero($evStop->minute());
                $t->set_var("event_start", $evStartTime);
                $t->set_var("event_stop", $evStopTime);
                $evStopStr = $evStop->hour() . addZero($evStop->minute());
                $evStartStr = $evStart->hour() . addZero($evStart->minute());
                // fix for 15 min stop times that end on another hour
                if (substr($evStopStr, 1, 2) == "00") {
                    $evStopStr = substr($evStopStr, 0, 1) - 1 . "60";
                }
                settype($evStopStr, "integer");
                settype($evStartStr, "integer");
                $res = $evStopStr - $evStartStr;
                if ($evStopStr - $evStartStr == 15) {
                    $t->set_var("public_event", "");

                    if ($event_editor == true) {
                        $t->parse(
                            "fifteen_delete_check",
                            "fifteen_delete_check_tpl"
                        );
                        $t->set_var("fifteen_no_delete_check", "");
                    } else {
                        $t->parse(
                            "fifteen_no_delete_check",
                            "fifteen_no_delete_check_tpl"
                        );
                        $t->set_var("fifteen_delete_check", "");
                    }
                    $t->parse("fifteen_event", "fifteen_event_tpl", true);
                } else {
                    if ($event_editor == true) {
                        $t->parse("delete_check", "delete_check_tpl");
                        $t->set_var("no_delete_check", "");
                    } else {
                        $t->parse("no_delete_check", "no_delete_check_tpl");
                        $t->set_var("delete_check", "");
                    }
                    $t->set_var("fifteen_event", "");

                    $t->parse("public_event", "public_event_tpl", true);
                }
            }
        }

        // an empty space
        elseif ($eventId == -2) {
            $t->set_var("td_class", "bgweekend");
            $t->set_var("rowspan_value", $tableCellsRowSpan[$row][$col]);

            //$t->parse("no_event", "no_event_tpl", true);
        }
    }

    $t->set_var("td_class", "");
    // Mark current time with bgcurrent. Does not currently go well together with caching.
    //        if ( $date->equals( $today ) && $nowSet == false &&
    //        $tmpTime->isGreater( $now, true ) && $now->isGreater( $tmpTime->add( $interval ) ) )
    //        {
    //            $t->set_var( "td_class", "bgcurrent" );
    //            $nowSet = true;
    //        }
    //        if ( !isset($toggle23) )
    //	    $toggle23 = false;
    //   if ( $tmpTime > $tmpTime->add( $interval ) )
    //         $tmpTime = new eZTime( 23, 59 );
    // this elseif block is a hack to get isGreater to display the 23rd hour
    //        elseif ($stopTime->hour() == 23 && $tmpTime->hour() == 22 && $toggle23 == false)
    //	    $toggle23 = true;
    //	else
    $tmpTime = $tmpTime->add($interval);

    $row++;

    if ($i % 2 == 0) {
        $t->set_var("td_class", "bglight");
    } else {
        $t->set_var("td_class", "bgdark");
    }
    $i++;
    if ($i > 600) {
        die('Error: The application has halted to prevent a server crash due to the complexity of the events on this day. 
    Contact the site administrator for more details.');
    }
    $t->parse("time_table", "time_table_tpl", true);
}

// group list
$group = new eZUserGroup();
$group_array = &$group->getAll();

foreach ($group_array as $groupItem) {
    if ($noShowGroup->groupEntry($groupItem->id()) == false) {
        $t->set_var("group_id", $groupItem->id());
        $t->set_var("group_name", $groupItem->Name());

        if ($tmpGroup->id() == $groupItem->id()) {
            $t->set_var("selected_group_id", $tmpGroup->id());
            $t->set_var("group_is_selected", "selected");
        } elseif ($tmpGroup->id() == 0) {
            $t->set_var("selected_group_id", 0);
        } else {
            $t->set_var("group_is_selected", "");
        }

        $t->parse("group_item", "group_item_tpl", true);
    }
}

//type list
$typeList = $type->getAll();

foreach ($typeList as $type) {
    $t->set_var("type_id", $type->id());
    $t->set_var("type_name", $type->name(true));

    if ($type->id() == $GetByTypeID) {
        $t->set_var("selected_type_id", $type->id());
        $t->set_var("type_is_selected", "selected");
    } else {
        $t->set_var("type_is_selected", "");
    }

    $t->parse("type_item", "type_item_tpl", true);
}

if (isset($editor) && $editor == true) {
    $t->parse("valid_editor", "valid_editor_tpl", true);
} else {
    $t->set_var("valid_editor", "");
}

// previous day link
$date->setYear($Year);
$date->setMonth($Month);
$dayAdjust = 0;
$date->setDay($Day - 7);
if ($date->day() < 1) {
    $dayAdjust = $date->day();
    $date->setMonth($Month - 1);
    if ($date->month() < 1) {
        $date->setMonth(12);
        $date->setYear($Year - 1);
    }
    $date->setDay($date->daysInMonth() + $dayAdjust);
}
$t->set_var("pd_year_number", $date->year());
$t->set_var("pd_month_number", $date->month());
$t->set_var("pd_day_number", $date->day());

// next day link
$date->setYear($Year);
$date->setMonth($Month);
$date->setDay($Day + 7);
if ($date->day() > $date->daysInMonth()) {
    $date->setDay($date->day() - $date->daysInMonth());
    $date->setMonth($Month + 1);
    if ($date->month() > 12) {
        $date->setMonth(1);
        $date->setYear($Year + 1);
    }
}
$t->set_var("nd_year_number", $date->year());
$t->set_var("nd_month_number", $date->month());
$t->set_var("nd_day_number", $date->day());

// previous month link
$date->setYear($Year);
$date->setDay($Day);

$date->setMonth($Month - 1);
if ($date->month() < 1) {
    $date->setMonth(12);
    $date->setYear($Year - 1);
}
if ($date->day() > $date->daysInMonth()) {
    $date->setDay($date->daysInMonth());
}
$t->set_var("pm_year_number", $date->year());
$t->set_var("pm_month_number", $date->month());
$t->set_var("pm_day_number", $date->day());

// next month link
$date->setYear($Year);
$date->setDay($Day);

$date->setMonth($Month + 1);
if ($date->month() > 12) {
    $date->setMonth(1);
    $date->setYear($Year + 1);
}
if ($date->day() > $date->daysInMonth()) {
    $date->setDay($date->daysInMonth());
}
$t->set_var("nm_year_number", $date->year());
$t->set_var("nm_month_number", $date->month());
$t->set_var("nm_day_number", $date->day());

// parse month table
$date->setYear($Year);
$date->setMonth($Month);
$date->setDay($Day);

$t->set_var("month_number", $date->month());
$t->set_var("month_name", $Locale->monthName($date->monthName(), false));

$t->set_var("week", "");
for ($week = 0; $week < 6; $week++) {
    $t->set_var("day", "");
    $t->set_var("empty_day", "");

    for ($day = 1; $day <= 7; $day++) {
        $date->setDay(1);
        $firstDay = $date->dayOfWeek($Locale->mondayFirst());

        $currentDay = $day + $week * 7 - $firstDay + 1;

        if (
            $day + $week * 7 >= $firstDay &&
            $currentDay <= $date->daysInMonth()
        ) {
            $date->setDay($currentDay);

            $t->set_var("td_class", "bglight");
            //                if ( $date->equals( $today ) )
            //                    $t->set_var( "td_class", "bgcurrent" );

            $t->set_var("day_number", $currentDay);
            $t->parse("day", "day_tpl", true);
        } else {
            $t->set_var("td_class", "bglight");
            $t->parse("day", "empty_day_tpl", true);
        }
    }
    $t->parse("week", "week_tpl", true);

    if ($currentDay >= $date->daysInMonth()) {
        $week = 6;
    }
}

$t->storeCache("output", "day_view_page_tpl", true);
//}

// returns the number of rows an event covers.
function eventRowSpan(&$event)
{
    //  &$startTime, &$interval )
    $ret = 0;
    $dur = $event->duration();
    $min = $dur->secondsElapsed() / 60;
    $ret = $min / 15;
    return $ret;
    /*    $ret = 0;
    $tmpTime = new eZTime();
    $tmpTime->setSecondsElapsed( $startTime->secondsElapsed() );
    $aStop =& $event->stopTime();

    while ( $tmpTime->isGreater( $aStop ) )
    {
        if ( $tmpTime > $tmpTime->add( $interval ) )
            $tmpTime = new eZTime( 23, 59 );
        else
            $tmpTime = $tmpTime->add( $interval );

        $ret++;
    }

    return $ret;*/
}

// checks if an event intersects with a given time interval
function intersects(&$app, &$startTime, &$stopTime)
{
    $ret = false;
    $appStartTime = &$app->startTime();
    $appStopTime = &$app->stopTime();

    // appstart is between start and stop
    if (
        $startTime->isGreater($appStartTime, true) == true &&
        $appStartTime->isGreater($stopTime) == true
    ) {
        $ret = true;
    }
    // appstop is between start and stop
    elseif (
        $startTime->isGreater($appStopTime) == true &&
        $appStopTime->isGreater($stopTime, true) == true
    ) {
        $ret = true;
    }
    // appstart is before start, and appstop is after stop
    elseif (
        $appStartTime->isGreater($startTime) == true &&
        $stopTime->isGreater($appStopTime) == true
    ) {
        $ret = true;
    }

    return $ret;
}

//Adds a "0" in front of the value if it's below 10.
function addZero($value)
{
    settype($value, "integer");
    $ret = $value;
    if ($ret < 10) {
        $ret = "0" . $ret;
    }
    return $ret;
}

/*!
Calculates and returns height of the event descrip div based on the 1px = 1 minute background image, and a 16 pix heading.
*/
function getEventHeight($event)
{
    $ret = 0;
    $dur = $event->duration();
    $min = $dur->secondsElapsed() / 60;
    $ret = $min;
    
    // $starttime = $event->startTime();
    // $stoptime = $event->stopTime();

    // if ($starttime->hour() == 0 && $stoptime->hour() == 23 && $starttime->minute() == '00' && $stoptime->minute() == '00')
    // $ret = $ret + 60;
    return $ret;
}

/*;
Returns the difference between 2 text days
*/
function getDayDiff($d1, $d2)
{
    switch ($d1) {
        case "mon":
            $d1 = 1;
            break;
        case "tue":
            $d1 = 2;
            break;
        case "wed":
            $d1 = 3;
            break;
        case "thu":
            $d1 = 4;
            break;
        case "fri":
            $d1 = 5;
            break;
        case "sat":
            $d1 = 6;
            break;
        case "sun":
            $d1 = 7;
            break;
    }
    switch ($d2) {
        case "mon":
            $d2 = 1;
            break;
        case "tue":
            $d2 = 2;
            break;
        case "wed":
            $d2 = 3;
            break;
        case "thu":
            $d2 = 4;
            break;
        case "fri":
            $d2 = 5;
            break;
        case "sat":
            $d2 = 6;
            break;
        case "sun":
            $d2 = 7;
            break;
    }
    $diff = $d2 - $d1;
    return $diff;
}
/*!
Returns true if the two events overlap (in time only)
*/

function EventTimeOverlaps($ev1, $ev2)
{
    if (!is_object($ev1)) {
        $ev1 = new eZGroupEvent($ev1);
        $ev1->get($ev1->id());
    }
    if (!is_object($ev2)) {
        $ev2 = new eZGroupEvent($ev2);
        $ev2->get($ev2->id());
    }
    $start1 = $ev1->startTime();
    $start2 = $ev2->startTime();
    $stop1 = $ev1->stopTime();
    $stop2 = $ev2->stopTime();
    $s1 = $start1->hour() . addZero($start1->minute());
    $s2 = $start2->hour() . addZero($start2->minute());
    $e1 = $stop1->hour() . addZero($stop1->minute());
    $e2 = $stop2->hour() . addZero($stop2->minute());
    if (($s1 > $s2 && $e2 > $s1) || ($s2 > $s1 && $e1 > $s2)) {
        return true;
    } else {
        return false;
    }
}

?>