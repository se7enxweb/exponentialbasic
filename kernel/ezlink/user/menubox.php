<?php
//
// $Id: menubox.php 8886 2002-01-04 14:51:51Z kaid $
//
// Created on: <17-Oct-2000 12:16:07 bf>
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
// include_once( "classes/ezcachefile.php" );
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZLinkMain", "Language" );

$PageCaching = $ini->read_var( "eZLinkMain", "PageCaching");

unset( $menuCachedFile );
// do the caching
if ( $PageCaching == "enabled" )
{
    $menuCacheFile = new eZCacheFile( "ezlink/cache",
                                      array( "menubox", $GlobalSiteDesign ),
                                      "cache", "," );

    if ( $menuCacheFile->exists() )
    {
        print( $menuCacheFile->contents() );
    }
    else
    {
        createLinkMenu( $menuCacheFile );
    }
}
else
{
    createLinkMenu();
}

function createLinkMenu( $menuCacheFile=false )
{
    global $ini;
    global $Language;
    global $menuCachedFile;
    global $GlobalSiteDesign;

    // include_once( "classes/eztemplate.php" );

    // include_once( "ezlink/classes/ezlinkcategory.php" );
    // include_once( "ezlink/classes/ezlink.php" );
    // include_once( "ezlink/classes/ezhit.php" );

    $t = new eZTemplate( "kernel/ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
                         "kernel/ezlink/user/intl", $Language, "categorylist.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "link_category_tpl", "link_category" );
    $t->set_block( "menu_box_tpl", "no_link_category_tpl", "no_link_category" );

    $t->set_var( "link_category", "" );
    $t->set_var( "no_link_category", "" );

// List all categories
    $linkCategory = new eZLinkCategory();

    $linkCategory_array = $linkCategory->getByParent( 0 );

    if ( count( $linkCategory_array ) == 0 )
    {
        $t->set_var( "category_list", "" );
        $t->parse( "no_link_category", "no_link_category_tpl" );
    }
    else
    {
        foreach( $linkCategory_array as $categoryItem )
        {
            $link_category_id = $categoryItem->id();

            $t->set_var( "linkcategory_id", $link_category_id );
            $t->set_var( "linkcategory_name", $categoryItem->name() );

            $t->parse( "link_category", "link_category_tpl", true );
        }
    }
    if ( !isset( $LGID ) )
        $LGID = "";
    $t->set_var( "linkcategory_id", $LGID );

    $t->set_var( "sitedesign", $GlobalSiteDesign );

    if ( is_a( $menuCacheFile, "eZCacheFile" ) )
    {
        $output = $t->parse( $target, "menu_box_tpl" );
        $menuCacheFile->store( $output );
        print( $output );
    }
    else
    {
        $t->pparse( "output", "menu_box_tpl" );
    }
}

?>
