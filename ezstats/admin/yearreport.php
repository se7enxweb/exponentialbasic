<?php
// 
// $Id: yearreport.php 6230 2001-07-20 11:28:54Z jakobn $
//
// Created on: <07-Jan-2001 14:47:04 bf>
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

include_once( "classes/ezlocale.php" );
$locale = new eZLocale( $Language );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdate.php" );

include_once( "ezstats/classes/ezpageview.php" );
include_once( "ezstats/classes/ezpageviewquery.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "yearreport.php" );

$t->setAllStrings();

$t->set_file( "year_report_tpl", "yearreport.tpl" );

$t->set_block( "year_report_tpl", "result_list_tpl", "result_list" );
$t->set_block( "result_list_tpl", "month_tpl", "month" );

$t->set_block( "month_tpl", "month_link_tpl", "month_link" );
$t->set_block( "month_tpl", "month_link_end_tpl", "month_link_end" );

$t->set_block( "month_tpl", "percent_marker_tpl", "percent_marker" );
$t->set_block( "month_tpl", "no_percent_marker_tpl", "no_percent_marker" );

$t->set_block( "result_list_tpl", "year_tpl", "year" );
$t->set_block( "year_tpl", "year_previous_tpl", "year_previous" );
$t->set_block( "year_tpl", "year_previous_inactive_tpl", "year_previous_inactive" );
$t->set_block( "year_tpl", "year_next_tpl", "year_next" );
$t->set_block( "year_tpl", "year_next_inactive_tpl", "year_next_inactive" );

if ( !is_numeric( $Year ) )
{
    $cur_date = new eZDate();
    $Year = $cur_date->year();
    $Month = $cur_date->month();
}

$yearReport =& eZPageViewQuery::yearStats( $Year );

$months = array( 1 => "jan",
                 2 => "feb",
                 3 => "mar",
                 4 => "apr",
                 5 => "may",
                 6 => "jun",
                 7 => "jul",
                 8 => "aug",
                 9 => "sep",
                 10 => "oct",
                 11 => "nov",
                 12 => "dec" );

if ( count( $yearReport ) > 0 )
{
    $maxCount = 0;
    // find the largest hit value
    foreach ( $yearReport["Months"] as $month )
    {
        $count = $month["Count"];

        if ( $count > $maxCount )
            $maxCount = $count;
    }

    $cur_date = new eZDate();
    $i=1;
    foreach ( $yearReport["Months"] as $month )
    {
        $count = $month["Count"];
        $totalCount = $yearReport["TotalPages"];
        
        $t->set_var( "page_view_count", $count );
        $t->set_var( "current_month", $i );

        if ( $totalCount > 0 )
        {
            $pageViewPercent = ( $count / $totalCount ) * 100;
            $pageViewPercent = round($pageViewPercent);
        }
        else
        {
            $pageViewPercent = 0;
        }

        $newMax = $totalCount - $maxCount;

        if ( $maxCount > 0 )
        {
            $normalizedPercent = ( $count / $maxCount ) * 100;
            $normalizedPercent = round($normalizedPercent);
        }
        else
        {
            $normalizedPercent = 0;
        }

        $t->set_var( "page_view_percent", $normalizedPercent );
        $t->set_var( "page_view_inverted_percent", 100 - $normalizedPercent );

        $t->set_var( "percent_count", $pageViewPercent );

        $t->set_var( "percent_marker", "" );
        $t->set_var( "no_percent_marker", "" );

        if ( $count == 0 )
            $t->parse( "no_percent_marker", "no_percent_marker_tpl" );
        else
            $t->parse( "percent_marker", "percent_marker_tpl" );

        $t->set_var( "month_link", "" );
        $t->set_var( "month_link_end", "" );

        $t->set_var( "month_named", $locale->monthName( $months[$i], false ) );

        $t->set_var( "this_month", $i );

        $next_date = new eZDate( $Year, $i, 1 );
        if ( !$cur_date->isGreater( $next_date ) )
        {
            $t->parse( "month_link", "month_link_tpl" );
            $t->parse( "month_link_end", "month_link_end_tpl" );
        }

        $t->parse( "month", "month_tpl", true );
        $i++;
    }
    $t->set_var( "total_page_views", $yearReport["TotalPages"] );
    $t->set_var( "pages_pr_month", $yearReport["PagesPrMonth"] );

    $t->parse( "result_list", "result_list_tpl" );
}
else
{
    $t->set_var( "result_list", "" );
}

$t->set_var( "this_month", $Month );
$t->set_var( "this_year", $Year );

$NextYear = $Year + 1;
$PrevYear = $Year - 1;
$t->set_var( "next_year", $NextYear );
$t->set_var( "previous_year", $PrevYear );

$t->set_var( "next_year", $NextYear );
$t->set_var( "previous_year", $PrevYear );
$t->set_var( "next_year", $NextYear );
$t->set_var( "previous_year", $PrevYear );

$t->set_var( "year_next_inactive", "" );
$t->set_var( "year_next", "" );
$t->set_var( "year_previous", "" );
$t->set_var( "year_previous_inactive", "" );

$cur_date = new eZDate();
$next_date = new eZDate( $NextYear, 1, 1 );

if ( $cur_date->isGreater( $next_date ) )
    $t->parse( "year_next_inactive", "year_next_inactive_tpl" );
else
    $t->parse( "year_next", "year_next_tpl" );

$t->parse( "year_previous", "year_previous_tpl" );

$t->parse( "year", "year_tpl" );


$t->pparse( "output", "year_report_tpl" );


?>
