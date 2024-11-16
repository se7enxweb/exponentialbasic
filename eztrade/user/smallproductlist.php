<?php
// 
// $Id: smallproductlist.php 9895 2004-04-06 11:08:44Z br $
//
// Created on: <04-Oct-2001 12:20:03 ce>
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

$ini =& INIFile::globalINI();
$PageCaching =& $ini->read_var( "eZTradeMain", "PageCaching");

$PureStatic = "false";

unset( $CacheFile );

$GenerateStaticPage = "false";
if ( $PageCaching == "enabled" )
{
    include_once( "classes/ezcachefile.php" );
    $CacheFile = new eZCacheFile( "eztrade/cache/",
                                  array( "smallproductlist", $CategoryID, $GlobalSiteDesign ), 
                                  "cache", "," );
    if ( $CacheFile->exists() )
    {
        include( $CacheFile->filename( true ) );
        $PureStatic = "true";
    }
    else
    {
        $GenerateStaticPage = "true";
    }
}

if ( $PureStatic != "true" )
{
    include_once( "classes/INIFile.php" );
    include_once( "classes/eztemplate.php" );
    include_once( "classes/ezlocale.php" );
    include_once( "classes/ezcurrency.php" );

    include_once( "ezuser/classes/ezuser.php" );
    include_once( "ezuser/classes/ezpermission.php" );
    include_once( "ezuser/classes/ezobjectpermission.php" );
    
    $ini =& INIFile::globalINI();
    $Language = $ini->read_var( "eZTradeMain", "Language" );

    include_once( "eztrade/classes/ezproduct.php" );
    include_once( "eztrade/classes/ezproductcategory.php" );

    $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                         "eztrade/user/intl/", $Language, "smallproductlist.php" );

    $t->set_file( "product_list_page_tpl", "smallproductlist.tpl" );

    $t->set_block( "product_list_page_tpl", "product_list_tpl", "product_list" );
    $t->set_block( "product_list_tpl", "product_tpl", "product" );


    $t->setAllStrings();

    $category = new eZProductCategory( $CategoryID );

    $productList =& $category->activeProducts( $category->sortMode(), 0, $Limit, $CategoryID );
    $t->set_var( "sitedesign", $GlobalSiteDesign );
    $t->set_var( "category_id", $CategoryID );
    $t->set_var( "category_name", $category->name() );
    $user =& eZUser::currentUser();

    // categories
    $i=0;
    foreach ( $productList as $productItem )
    {
//        if ( eZObjectPermission::hasPermission( $productItem->id(), "trade_product", "r", $user ) )
        {
            $t->set_var( "product_id", $productItem->id() );


            $t->set_var( "product_name", $productItem->name() );

            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "td_class", "bglight" );
            }
            else
            {
                $t->set_var( "td_class", "bgdark" );
            }

            $t->parse( "product", "product_tpl", true );
            $i++;
        }
    }

    if ( count( $i ) == 0 )
    {
        $t->set_var( "product_list", "" );
    }
    else
    {
        $t->parse( "product_list", "product_list_tpl" );
    }

    if ( $GenerateStaticPage == "true" )
    {
        $output = $t->parse( $target, "product_list_page_tpl" );
        // print the output the first time while printing the cache file.
        print( $output );
        $CacheFile->store( $output );
    }
    else
    {
        $t->pparse( "output", "product_list_page_tpl" );
    }
}
?>
