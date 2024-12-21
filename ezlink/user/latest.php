<?php
//
// $Id: latest.php 8205 2001-11-01 18:09:20Z bf $
//
// Created on: <26-Oct-2000 14:50:13 ce>
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

// include_once( "classes/INIFile.php" );
$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZLinkMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

// include_once( "classes/eztemplate.php" );
// include_once( "ezlink/classes/ezlinkcategory.php" );
// include_once( "ezlink/classes/ezlink.php" );
// include_once( "ezlink/classes/ezhit.php" );


$t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
"ezlink/user/intl", $Language, "linklist.php" );
$t->setAllStrings();

$t->set_file( array(
    "last_links" => "latest.tpl"
    ) );

$t->set_block( "last_links", "link_list_tpl", "link_item" );

$link = new eZLink();

$link_array = $link->getLastTenDate( 10, 0 );

if ( count( $link_array ) == 0 )
{
    
    $t->set_var( "link_list", "<p>Ingen linker ble funnet.</p>" );
}
else
{
    $i=0;
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
        $category =& $linkItem->categoryDefinition();
        $t->set_var( "link_categoryid", $category->id() );
        $t->set_var( "link_keywords", $linkItem->keywords() );
        $t->set_var( "link_created", $linkItem->created() );
        $t->set_var( "link_modified", $linkItem->modified() );
        $t->set_var( "link_accepted", $linkItem->accepted() );
        $t->set_var( "link_url", $linkItem->url() );

        $hit = new eZHit();
        $hits = $hit->getLinkHits( $link_array[ $i ][ "ID" ] );

        $t->set_var( "link_hits", $hits );

        $t->set_var( "document_root", $DOC_ROOT );

        $t->parse( "link_item", "link_list_tpl", true );
        $i++;
    }
}

$t->pparse( "output", "last_links" );

?>
