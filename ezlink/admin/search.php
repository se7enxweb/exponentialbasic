<?php
// 
// $Id: search.php 8751 2001-12-13 10:40:19Z kaid $
//
// Created on: <15-Sep-2000 14:40:06 bf>
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

/*!
  listlink.php viser alle kategorier
*/

// include_once( "classes/INIFile.php" );

$ini = INIFile::globalINI();
$Language = $ini->read_var( "eZLinkMain", "Language" );
$AdminLimit = $ini->read_var( "eZLinkMain", "AdminSearchLimit" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlist.php" );

// include_once( "ezlink/classes/ezlinkcategory.php" );
// include_once( "ezlink/classes/ezlink.php"  );
// include_once( "ezlink/classes/ezhit.php" );

//  // include_once( "classes/ezquery.php" );

// setter template filer
$t = new eZTemplate( "ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
                     "ezlink/admin/intl", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "search_list" => "searchlist.tpl"
    ) );

$t->set_block( "search_list", "search_item_tpl", "search_item" );

$t->set_block( "search_list", "empty_result_tpl", "empty_result" );
$t->set_block( "search_list", "search_result_tpl", "search_result" );

if ( !isset( $Offset ) )
    $Offset = 0;

$link = new eZLink();

$t->set_var( "search_result", "" );

$t->set_var( "query_string", "" );

if ( $QueryString != "" )
{
    $t->set_var( "query_string", urlencode( $QueryString ) );

    $link_array = $link->getQuery( $QueryString, $AdminLimit, $Offset );    
    $total_count = $link->getQueryCount( $QueryString );

    $t->set_var( "empty_result", "" );
    $i=0;
    if ( $link_array )
    {
        foreach( $link_array as $linkItem )
        {
            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "bg_color", "#f0f0f0" );
            }
            else
            {
                $t->set_var( "bg_color", "#dcdcdc" );
            }  

            $t->set_var( "link_id", $linkItem->id() );
            $t->set_var( "link_title", $linkItem->name() );
            $t->set_var( "link_description", $linkItem->description() );
            $t->set_var( "link_categoryid", $linkItem->categoryDefinition( false ) );
            //$t->set_var( "link_categoryid", $linkItem->linkcategoryid() );
            $t->set_var( "link_keywords", $linkItem->keywords() );
            $t->set_var( "link_created", $linkItem->created() );
            $t->set_var( "link_modified", $linkItem->modified() );
            $t->set_var( "link_accepted", $linkItem->accepted() );
            $t->set_var( "link_url", $linkItem->url() );
                
            $hit = new eZHit();
            $hits = $hit->getLinkHits( $linkItem->id() );

            $t->set_var( "link_hits", $hits );
                
            $t->set_var( "empty_result", "" );

            $t->parse( "search_item", "search_item_tpl", true );
            $i++;
        }
    }
    else
    {
        $t->set_var( "search_item", "" );
        $t->parse( "empty_result", "empty_result_tpl" );
    }
}
else
{
    $total_count = 0;
    $t->set_var( "search_item", "" );
    $t->parse( "empty_result", "empty_result_tpl" );
}
eZList::drawNavigator( $t, $total_count, $AdminLimit, $Offset, "search_list" );

$t->set_var( "link_start", $Offset + 1 );
$t->set_var( "link_end", min( $Offset + $AdminLimit, $total_count ) );
$t->set_var( "link_total", $total_count );


$t->set_var( "hit_count", $total_count );

$t->set_var( "linkcategory_id", isset( $LGID ) ? $LGID : 0 );


$t->pparse( "output", "search_list" );
?>