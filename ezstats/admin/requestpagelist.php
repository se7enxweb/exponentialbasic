<?php
// 
// $Id: requestpagelist.php 7434 2001-09-24 17:42:25Z br $
//
// Created on: <07-Jan-2001 16:25:31 bf>
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
                     "ezstats/admin/intl", $Language, "requestpagelist.php" );

$t->setAllStrings();

$t->set_file( "request_page_tpl", "requestpagelist.tpl" );

$t->set_block( "request_page_tpl", "request_list_tpl", "request_list" );
$t->set_block( "request_list_tpl", "request_tpl", "request" );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

$latest =& eZPageViewQuery::topRequests( $ViewLimit, $Offset );
$ItemCount = eZPageViewQuery::topRequestsCount();

$t->set_var( "item_start", $Offset + 1 );
$t->set_var( "item_end", $Offset + $ViewLimit );
$t->set_var( "item_count", $ItemCount );
$t->set_var( "item_limit", $ViewLimit );
$t->set_var( "exclude_domain", $ExcludeDomain );

$headers = getallheaders();
$request_domain = $headers["Host"];

$request_domain = preg_replace( "#^admin.#", "", $request_domain );


if ( count( $latest ) > 0 )
{
    $i = 0;
    foreach ( $latest as $request )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "bg_color", "bglight" );
        else
            $t->set_var( "bg_color", "bgdark" );

        $t->set_var( "request_domain", $request_domain );
        $t->set_var( "request_uri", $request["URI"] );
        $t->set_var( "page_view_count", $request["Count"] );

        $t->parse( "request", "request_tpl", true );
        $i++;
    }
    eZList::drawNavigator( $t, $ItemCount, $ViewLimit, $Offset, "request_list_tpl" );
    $t->parse( "request_list", "request_list_tpl" );
}
else
{
    $t->set_var( "request_list", "" );
}


$t->pparse( "output", "request_page_tpl" );


?>
