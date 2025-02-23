<?php
//
// $Id: menubox.php 8162 2001-11-01 15:23:40Z bf $
//
// Created on: <11-Sep-2000 22:10:06 bf>
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

$Language = $ini->read_var( "eZForumMain", "Language" );

$PageCaching = $ini->read_var( "eZForumMain", "PageCaching" );

unset( $menuCachedFile );
// do the caching
if ( $PageCaching == "enabled" )
{
    $menuCacheFile = new eZCacheFile( "ezforum/cache",
                                      array( "menubox", $GlobalSiteDesign ),
                                      "cache", "," );

    if ( $menuCacheFile->exists() )
    {
        print( $menuCacheFile->contents() );
    }
    else
    {
        createPage( $menuCacheFile );
    }
}
else
{
    createPage();
}

function createPage( $menuCacheFile = false )
{
    global $ini;
    global $Language;
   	global $GlobalSiteDesign;

    // include_once( "classes/eztemplate.php" );
    // include_once( "classes/ezdb.php" );
    // include_once( "ezforum/classes/ezforumcategory.php" );

    $t = new eZTemplate( "kernel/ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                         "kernel/ezforum/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( "menu_box_tpl", "menubox.tpl" );

//      $t = new Template( "." );

//      $t->set_file( Array( "categorylist_tpl" => "kernel/ezforum/user/templates/standard/categorymenu.tpl" ) );

    $t->set_block( "menu_box_tpl", "category_tpl", "category" );

    $category = new eZForumCategory();
    $categories = $category->getAllCategories();

    if ( !$categories )
    {
        $Language = $ini->read_var( "eZForumMain", "Language" );
        $nofound = new INIFile( "kernel/ezforum/user/intl/" . $Language . "/categorylist.php.ini", false );
        $noitem =  $nofound->read_var( "strings", "noitem" );

        $t->set_var( "category", $noitem );
    }
    else
    {
        foreach ( $categories as $category )
        {
            $t->set_var( "id", $category->id() );
            $t->set_var( "name", $category->name() );

            $t->parse( "category", "category_tpl", true);
        }
    }

    $t->set_var( "sitedesign", $GlobalSiteDesign );

    if ( is_a( $menuCacheFile, "eZCacheFile" ) )
    {
        $output = $t->parse( "output", "menu_box_tpl" );
        // print the output the first time while printing the cache file.
        $menuCacheFile->store( $output );
        print( $output );
    }
    else
    {
		$t->pparse( "output", "menu_box_tpl" );
    }
}

?>
