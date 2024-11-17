<?php
// 
// $Id: refererlist.php 9639 2002-06-19 15:02:28Z br $
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
                     "ezstats/admin/intl", $Language, "refererlist.php" );

$t->setAllStrings();

$t->set_file( "referer_page_tpl", "refererlist.tpl" );

$t->set_block( "referer_page_tpl", "referer_list_tpl", "referer_list" );
$t->set_block( "referer_list_tpl", "referer_tpl", "referer" );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

if ( !isset( $ViewLimit ) or !is_numeric( $ViewLimit ) )
    $ViewLimit = 20;

$latest =& eZPageViewQuery::topReferers( $ViewLimit, $ExcludeDomain, $Offset );
$ItemCount = eZPageViewQuery::topReferersCount( $ExcludeDomain );

$t->set_var( "item_start", $Offset + 1 );
$t->set_var( "item_end", $Offset + $ViewLimit );
$t->set_var( "item_count", $ItemCount );
$t->set_var( "item_limit", $ViewLimit );
$t->set_var( "exclude_domain", trim( $ExcludeDomain ) );

if ( count( $latest ) > 0 )
{
    $i = 0;
    foreach ( $latest as $referer )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "bg_color", "bglight" );
        else
            $t->set_var( "bg_color", "bgdark" );

        $t->set_var( "referer_domain", $referer["Domain"] );
        $t->set_var( "referer_uri", $referer["URI"] );
        $t->set_var( "page_view_count", $referer["Count"] );

        $t->parse( "referer", "referer_tpl", true );
        $i++;
    }

    eZList::drawNavigator( $t, $ItemCount, $ViewLimit, $Offset, "referer_list_tpl" );
    $t->parse( "referer_list", "referer_list_tpl" );
}
else
{
    $t->set_var( "referer_list", "" );
}

$t->set_var( "view_mode", $ViewMode );
$t->set_var( "view_limit", $ViewLimit );


$t->pparse( "output", "referer_page_tpl" );

?>
