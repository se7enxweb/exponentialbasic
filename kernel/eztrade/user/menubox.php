<?php
// 
// $Id: menubox.php 6363 2001-08-02 13:09:42Z jhe $
//
// Created on: <18-April-2001 13:00:00 th>
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

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTradeMain", "Language" );
$CategoryListProductImages = $ini->variable( "eZTradeMain", "CategoryListProductImages" ) == "enabled" ? true : false;

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdb.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl", $Language, "menubox.php" );

$t->setAllStrings();

$t->set_file( "category_list_page_tpl", "menubox.tpl" );
$t->set_block( "category_list_page_tpl", "category_tpl", "category" );
$t->set_block( "category_list_page_tpl", "category_list_tpl", "category_list" );

$t->set_block( "category_list_tpl",'category_image_hot_deals_tpl', "category_image_hot_deals" );
$t->set_block( "category_list_tpl", "category_image_list_tpl", "category_image_list" );
$t->set_block( "category_image_list_tpl", "category_image_tpl", "category_image" );

$t->set_var( "sitedesign", $GlobalSiteDesign );

$category = new eZProductCategory(  );
$category->get( $CategoryID );

$categoryList = $category->getByParent( $category );

if ( $CategoryListProductImages == true )
{
    foreach ( $categoryList as $category )
    {
        $t_category_name = $category->id().".gif";
        $t->set_var( "category_id", $category->id() );
        $t->set_var( "category_image_name", $t_category_name );

        if ( $CategoryListProductImages == true ) {
            $t->parse( "category_image_hot_deals", "category_image_hot_deals_tpl", true);
            $t->parse( "category_image_list", "category_image_list_tpl", true);
            $t->parse( "category_image", "category_image_tpl", true);

            $t->set_var( "category", "");
            $t->set_var( "category_hot_deals","");
        }
        else 
        {
            $t->set_var( "category_image_list", "");
            $t->set_var( "category_image_hot_deals", "");
        }
    }

    $t->parse( "category_image_list", "category_image_list_tpl", true);
}
else
{
        $t->parse( "category_image_hot_deals", "category_image_hot_deals_tpl", true);
        $t->set_var( "category_image", "");
        $t->set_var( "category_image_list", "");
}

foreach ( $categoryList as $category )
{
    $t->set_var( "id", $category->id() );
    $t->set_var( "name", $category->name() );
    $t->parse( "category", "category_tpl", true );
}
//$t->set_var( "category", "" );

$t->pparse( "output", "category_list_tpl" );
		
?>