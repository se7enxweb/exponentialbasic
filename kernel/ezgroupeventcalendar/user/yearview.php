<?php
// 
// $Id: yearview.php,v 1.16 2001/04/20 15:32:00 gl Exp $
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezdate.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/ezcachefile.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$SiteDesign = $ini->variable( "site", "SiteDesign" );
$Language = $ini->variable( "eZGroupEventCalendarMain", "Language" );

$TemplateDir = $ini->variable( "eZGroupEventCalendarMain", "TemplateDir" );
$GlobalSectionID = $ini->variable( "eZGroupEventCalendarMain", "DefaultSection" );

$Locale = new eZLocale( $Language );

$today = new eZDateTime();
if ( $Year == false )
    $Year = $today->year();

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

$t = new eZTemplate( $TemplateDir,
                     "kernel/ezgroupeventcalendar/user/intl", $Language, "yearview.php",
                     "default", "kernel/ezgroupeventcalendar" . "/user", $Year );

$t->set_file( "year_view_page_tpl", "yearview.tpl" );
$t->set_var("date_year", $today->year());
$t->set_var("date_month", $today->month());
$t->set_var("date_day", $today->day());
/*
$build = false;
if ( $t->hasCache() )
{
//    print( "cached<br />" );
    $file = new eZCacheFile( "ezgroupeventcalendar/user/cache", array( "yearview.tpl", "default", $Language, $Year ), "cache", "-" );
    $dt =& $file->lastModified();

    if ( $Year == $today->year() && $dt->day() != $today->day() )
    {
        $file->delete();
        $build = true;
    }
    else
    {
        print( $t->cache() );
    }
}
else
{
    $build = true;
} */
$build = true;
if ( $build == true )
{
//    print( "not cached<br />" );
    $t->setAllStrings();

    $t->set_block( "year_view_page_tpl", "month_tpl", "month" );
    $t->set_block( "month_tpl", "week_tpl", "week" );
    $t->set_block( "week_tpl", "day_tpl", "day" );
    $t->set_block( "week_tpl", "empty_day_tpl", "empty_day" );

	$t->set_var( "sitedesign", $SiteDesign );

    $session =& eZSession::globalSession();
    $session->fetch();

    $date = new eZDate();

    if ( $Year != "" )
    {
        $date->setYear( $Year );
    }
    else
    {
        $Year = $date->year();
    }

    $session->setVariable( "Year", $Year );

    $t->set_var( "year_number", $Year );
    $t->set_var( "prev_year_number", $Year - 1 );
    $t->set_var( "next_year_number", $Year + 1 );

    $i=0;
    for ( $month=1; $month<13; $month++ )
    {
        if ( ( $i % 3 ) == 0 )
        {
            $t->set_var( "begin_tr", "<tr>" );
            $t->set_var( "end_tr", "" );        
        }
        else if ( ( $i % 3 ) == 2 )
        {
            $t->set_var( "begin_tr", "" );
            $t->set_var( "end_tr", "</tr>" );
        }
        else
        {
            $t->set_var( "begin_tr", "" );
            $t->set_var( "end_tr", "" );        
        }

        $date->setMonth( $month );
        $t->set_var( "month_number", $month );
        $t->set_var( "month_name", $Locale->monthName( $date->month(), false ) );

        $t->set_var( "week", "" );
        for ( $week=0; $week<6; $week++ )
        {
            $t->set_var( "day", "" );
            $t->set_var( "empty_day", "" );

            for ( $day=1; $day<=7; $day++ )
            {
                $date->setDay( 1 );
                $firstDay = $date->dayOfWeek( $Locale->mondayFirst() );

                $currentDay = $day + ( $week * 7 ) - $firstDay + 1;

                if ( ( ( $day + ( $week * 7 ) )  >= $firstDay ) &&
                     ( $currentDay <= $date->daysInMonth() ) )
                {
                    $date->setDay( $currentDay );

                    $t->set_var( "td_class", "gcalYearViewReg" );
                    if ( $date->equals( $today ) )
                        $t->set_var( "td_class", "gcalYearViewCur" );

                    $t->set_var( "day_number", $currentDay );
                    $t->parse( "day", "day_tpl", true );
                }
                else
                {
                    $t->set_var( "td_class", "gcalYearViewOff" );
                    $t->parse( "day", "empty_day_tpl", true );
                }
            }
            $t->parse( "week", "week_tpl", true );

            if ( $currentDay >= $date->daysInMonth() )
            {
                $week = 6;
            }
        }
        $t->parse( "month", "month_tpl", true );


        $i++;
    }

    $t->storeCache( "output", "year_view_page_tpl", true );
}

?>
