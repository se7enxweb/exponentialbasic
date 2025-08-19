<?php
// 
// $Id: companystats.php 9529 2002-05-14 11:17:05Z jhe $
//
// Created on: <20-Mar-2001 18:21:41 amos>
//
// This source file is part of Exponential Basic, publishing software.
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

// include_once( "classes/INIFile.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZContactMain", "Language" );

// include_once( "classes/ezlocale.php" );
$locale = new eZLocale( $Language );

// include_once( "classes/eztemplate.php" );
// include_once( "ezcontact/classes/ezcompany.php" );

$t = new eZTemplate( "kernel/ezcontact/admin/" . $ini->variable( "eZContactMain", "AdminTemplateDir" ),
                     "kernel/ezcontact/admin/intl/", $Language, "companystats.php" );
$t->setAllStrings();

$t->set_file( "company_stats_tpl", "companystats.tpl" );

$t->set_block( "company_stats_tpl", "month_tpl", "month" );
$t->set_block( "month_tpl", "month_link_tpl", "month_link" );
$t->set_block( "month_tpl", "month_link_end_tpl", "month_link_end" );
$t->set_block( "month_tpl", "percent_marker_tpl", "percent_marker" );
$t->set_block( "month_tpl", "no_percent_marker_tpl", "no_percent_marker" );

$t->set_block( "company_stats_tpl", "date_nav_tpl", "date_nav" );
$t->set_block( "date_nav_tpl", "year_previous_tpl", "year_previous" );
$t->set_block( "date_nav_tpl", "year_previous_inactive_tpl", "year_previous_inactive" );
$t->set_block( "date_nav_tpl", "year_next_tpl", "year_next" );
$t->set_block( "date_nav_tpl", "year_next_inactive_tpl", "year_next_inactive" );

if ( !is_numeric( $Year )  )
{
    $cur_date = new eZDate();
    $Year = $cur_date->year();
}
if ( !is_numeric( $Month ) )
{
    $cur_date = new eZDate();
    $Month = $cur_date->month();
}
if ( !is_numeric( $Day ) )
{
    $cur_date = new eZDate();
    $Day = $cur_date->day();
}

$company = new eZCompany( $CompanyID );
$t->set_var( "company_name", $company->name() );
$t->set_var( "company_id", $company->id() );
$categories =& $company->categories( false, false, 1 );
$t->set_var( "category_id", "" );
if ( count( $categories ) > 0 )
    $t->set_var( "category_id", $categories[0] );

$date = new eZDate( $Year, $Month, 1 );

$t->set_var( "month", "" );
$counts =& $company->yearViewCounts( $Year );
if ( count( $counts ) > 0 )
{
    $maxCount = 0;
    $totalCount = 0;
    // find the largest hit value
    foreach ( $counts as $month )
    {
        $maxCount = max( $maxCount, $month["count"] );
        $totalCount += $month["count"];
    }

    $cur_date = new eZDate();
    foreach ( $counts as $month )
    {
        $count = $month["count"];
        $t->set_var( "page_view_count", $count );
        $t->set_var( "current_month", $month["month"] );

        $pageViewPercent = 0;
        if ( $totalCount > 0 )
            $pageViewPercent = round( ( $count / $totalCount ) * 100 );
        $newMax = $totalCount - $maxCount;

        $normalizedPercent = 0;
        if ( $maxCount > 0 )
            $normalizedPercent = round( ( $count / $maxCount ) * 100 );

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

        $t->set_var( "month_named", $locale->monthName( $month["month"], false ) );

        $t->set_var( "this_month", $month["month"] );

        $next_date = new eZDate( $Year, $month["month"], 1 );
        if ( !$cur_date->isGreater( $next_date ) )
        {
            $t->parse( "month_link", "month_link_tpl" );
            $t->parse( "month_link_end", "month_link_end_tpl" );
        }

        $t->parse( "month", "month_tpl", true );
    }
    $t->set_var( "total_page_views", $totalCount );
    $t->set_var( "pages_pr_month", 1 );
}

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

$t->parse( "date_nav", "date_nav_tpl" );

$t->pparse( "output", "company_stats_tpl" );

?>