<?php
// 
// $Id: entryexitpages.php 6230 2001-07-20 11:28:54Z jakobn $
//
// Created on: <12-Jan-2001 16:31:41 bf>
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
$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZStatsMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdate.php" );

include_once( "ezstats/classes/ezpageview.php" );
include_once( "ezstats/classes/ezpageviewquery.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "entryexitpages.php" );

$t->setAllStrings();

$t->set_file( array(
    "entry_exit_report_tpl" => "entryexitpages.tpl"
    ) );

$t->set_block( "entry_exit_report_tpl", "exit_page_tpl", "exit_page" );
$t->set_block( "entry_exit_report_tpl", "entry_page_tpl", "entry_page" );

$t->set_block( "entry_exit_report_tpl", "month_tpl", "month" );
$t->set_block( "month_tpl", "month_previous_tpl", "month_previous" );
$t->set_block( "month_tpl", "month_previous_inactive_tpl", "month_previous_inactive" );
$t->set_block( "month_tpl", "month_next_tpl", "month_next" );
$t->set_block( "month_tpl", "month_next_inactive_tpl", "month_next_inactive" );

if ( !is_numeric( $Year ) || !is_numeric( $Month ) )
{
    $cur_date = new eZDate();
    $Year = $cur_date->year();
    $Month = $cur_date->month();
}

$query = new eZPageViewQuery();

// exit pages
$exitPages =& $query->topExitPage();

$exitPageArray = array();

foreach ( $exitPages as $page )
{
    $exitPageArray[$page]["Count"] += 1;
    $exitPageArray[$page]["PageID"] = $page;
}

arsort( $exitPageArray );

$pageView = new eZPageView();
$ExitPageLimit = 20;

$i=0;
foreach ( $exitPageArray as $exitPage )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "bg_color", "bglight" );
    else
        $t->set_var( "bg_color", "bgdark" );

    $t->set_var( "page_uri", $pageView->requestPageByID( $exitPage["PageID"] ) );
    $t->set_var( "exit_count", $exitPage["Count"] );

    $t->parse( "exit_page", "exit_page_tpl", true );
    
    $i++;
    if ( $i>=$ExitPageLimit )
        break;
}

// entry pages
$entryPages =& $query->topEntryPage();

$entryPageArray = array();

foreach ( $entryPages as $page )
{
    $entryPageArray[$page]["Count"] += 1;
    $entryPageArray[$page]["PageID"] = $page;
}

arsort( $entryPageArray );

$EntryPageLimit = 20;

$i=0;
foreach ( $entryPageArray as $entryPage )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "bg_color", "bglight" );
    else
        $t->set_var( "bg_color", "bgdark" );

    $t->set_var( "page_uri", $pageView->requestPageByID( $entryPage["PageID"] ) );
    $t->set_var( "entry_count", $entryPage["Count"] );

    $t->parse( "entry_page", "entry_page_tpl", true );
    
    $i++;
    if ( $i>=$EntryPageLimit )
        break;
}

$next_month = new eZDate( $Year, $Month, 1, 0, 1, 0 );
$prev_month = new eZDate( $Year, $Month, 1, 0, -1, 0 );

$t->set_var( "next_month", $next_month->month() );
$t->set_var( "previous_month", $prev_month->month() );
$t->set_var( "next_year", $next_month->year() );
$t->set_var( "previous_year", $prev_month->year() );

$t->set_var( "month_next_inactive", "" );
$t->set_var( "month_next", "" );
$t->set_var( "month_previous", "" );
$t->set_var( "month_previous_inactive", "" );

$cur_date = new eZDate();

if ( $cur_date->isGreater( $next_month ) )
    $t->parse( "month_next_inactive", "month_next_inactive_tpl" );
else
    $t->parse( "month_next", "month_next_tpl" );

$t->parse( "month_previous", "month_previous_tpl" );

$t->parse( "month", "month_tpl" );

$t->set_var( "this_month", $Month );
$t->set_var( "this_year", $Year );

$t->pparse( "output", "entry_exit_report_tpl" );


?>
