<?php
// 
// $Id: pageviewlist.php 8239 2001-11-02 06:46:24Z br $
//
// Created on: <06-Jan-2001 17:11:01 bf>
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
include_once( "classes/ezlocale.php" );

include_once( "ezstats/classes/ezpageview.php" );
include_once( "ezstats/classes/ezpageviewquery.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "pageviewlist.php" );

$t->setAllStrings();

$t->set_file( "page_view_page_tpl", "pageviewlist.tpl" );

$t->set_block( "page_view_page_tpl", "page_view_list_tpl", "page_view_list" );
$t->set_block( "page_view_list_tpl", "page_view_tpl", "page_view" );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

$latest =& eZPageViewQuery::latest( $ViewLimit, $Offset );

$ItemCount = eZPageViewQuery::latestCount();
$ItemCount = count( $latest );

$t->set_var( "item_start", $Offset + 1 );
$t->set_var( "item_end", $Offset + $ViewLimit );
$t->set_var( "item_count", $ItemCount );
$t->set_var( "item_limit", $ViewLimit );

$locale = new eZLocale( $Language );

if ( count( $latest ) > 0 )
{
    $i = 0;
    foreach ( $latest as $pageview )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "bg_color", "bglight" );
        else
            $t->set_var( "bg_color", "bgdark" );
        
        $dateTime = $pageview->dateTime();
        $time =& $dateTime->time();        
        $t->set_var( "request_time", $locale->format( $dateTime ) );
        $t->set_var( "remote_ip", $pageview->remoteIP() );
        $t->set_var( "remote_host_name", $pageview->remoteHostName() );
        $t->set_var( "request_page", $pageview->requestPage() );
        
        $t->parse( "page_view", "page_view_tpl", true );
        $i++;
    }
    eZList::drawNavigator( $t, $ItemCount, $ViewLimit, $Offset, "page_view_list_tpl" );

    $t->parse( "page_view_list", "page_view_list_tpl" );
}
else
{
    $t->set_var( "page_view_list", "" );
}

$t->pparse( "output", "page_view_page_tpl" );


?>
