<?php
// 
// $Id: browserlist.php 8239 2001-11-02 06:46:24Z br $
//
// Created on: <07-Jan-2001 16:13:21 bf>
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
include_once( "classes/ezlist.php" );

include_once( "ezstats/classes/ezpageview.php" );
include_once( "ezstats/classes/ezpageviewquery.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "browserlist.php" );

$t->setAllStrings();

$t->set_file( "browser_page_tpl", "browserlist.tpl" );

$t->set_block( "browser_page_tpl", "browser_list_tpl", "browser_list" );
$t->set_block( "browser_list_tpl", "browser_tpl", "browser" );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

$latest =& eZPageViewQuery::topBrowsers( $ViewLimit, $Offset );
// $ItemCount = eZPageViewQuery::topBrowsersCount();
$ItemCount = count( $latest );
$total_count = eZPageViewQuery::latestCount();
$total_count = eZPageViewQuery::sumBrowserTypeCount();

$t->set_var( "item_start", $Offset + 1 );
$t->set_var( "item_end", $Offset + $ViewLimit );
$t->set_var( "item_count", $ItemCount );
$t->set_var( "item_limit", $ViewLimit );

if ( count( $latest ) > 0 )
{
    $i = 0;
    foreach ( $latest as $browser )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "bg_color", "bglight" );
        else
            $t->set_var( "bg_color", "bgdark" );

        $t->set_var( "browser_name", $browser["Name"] );
        $t->set_var( "page_view_count", $browser["Count"] );
        $t->set_var( "page_view_percent", round( $browser["Count"]*100/$total_count ) );
        $t->parse( "browser", "browser_tpl", true );
        $i++;
    }

    eZList::drawNavigator( $t, $ItemCount, $ViewLimit, $Offset, "browser_list_tpl" );
    $t->parse( "browser_list", "browser_list_tpl" );
}
else
{
    $t->set_var( "browser_list", "" );
}

$t->set_var( "view_mode", $ViewMode );
$t->set_var( "view_limit", $ViewLimit );


$t->pparse( "output", "browser_page_tpl" );


?>
