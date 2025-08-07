<?php
// 
// $Id: tiplist.php,v 1.16 2001/07/19 11:56:33 jakobn Exp $
//
// Created on: <22-Nov-2000 21:08:34 bf>
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
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "eztip/classes/eztip.php" );
// include_once( "eztip/classes/eztipcategory.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTipMain", "Language" );

$t = new eZTemplate( "kernel/eztip/admin/" . $ini->variable( "eZTipMain", "AdminTemplateDir" ),
                     "kernel/eztip/admin/intl/", $Language, "tiplist.php" );

$t->setAllStrings();

$t->set_file( array(
    "tip_list_page_tpl" => "tiplist.tpl"
    ) );


// path
$t->set_block( "tip_list_page_tpl", "path_item_tpl", "path_item" );

// category
$t->set_block( "tip_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

// ad
$t->set_block( "tip_list_page_tpl", "tip_list_tpl", "tip_list" );
$t->set_block( "tip_list_tpl", "tip_item_tpl", "tip_item" );
$t->set_block( "tip_item_tpl", "tip_is_active_tpl", "tip_is_active" );
$t->set_block( "tip_item_tpl", "tip_not_active_tpl", "tip_not_active" );
$t->set_block( "tip_item_tpl", "image_item_tpl", "image_item" );
$t->set_block( "tip_item_tpl", "html_item_tpl", "html_item" );
$t->set_block( "tip_item_tpl", "no_image_tpl", "no_image" );

$t->set_var( "site_style", $SiteDesign );

if ( !isset( $CategoryID ) || !is_numeric( $CategoryID ) )
    $CategoryID = 0;

$category = new eZTipCategory( $CategoryID );

$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList = $category->getByParent( $category, true );

// categories
$i=0;
$t->set_var( "category_list", "" );

foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );

    $t->set_var( "category_name", $categoryItem->name() );

    $parent = $categoryItem->parent();
    

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    $t->set_var( "category_description", $categoryItem->description() );

    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0 )    
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// tips
$tipList =& $category->tiplist( "time", true );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "tip_list", "" );
foreach ( $tipList as $tip )
{
    if ( $tip->name() == "" )
        $t->set_var( "tip_name", "&nbsp;" );
    else
        $t->set_var( "tip_name", $tip->name() );

    $t->set_var( "tip_id", $tip->id() );

    if ( $tip->isActive() == true )
    {
        $t->parse( "tip_is_active", "tip_is_active_tpl" );
        $t->set_var( "tip_not_active", "" );        
    }
    else
    {
        $t->set_var( "tip_is_active", "" );
        $t->parse( "tip_not_active", "tip_not_active_tpl" );
    }

    $image = $tip->image();

    if ( $tip->useHTML() )
    {
        $t->set_var( "image_item", "" );
        $t->set_var( "no_image", "" );

        $t->set_var( "html_banner", $tip->htmlBanner() );
        $t->parse( "html_item", "html_item_tpl" );
    }
    else
    {
        if ( get_class ( $image ) == "ezimage" )
        {
            $imageURL = $image->filePath();
            
            $t->set_var( "image_width", $image->width() );
            $t->set_var( "image_height", $image->height() );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $image->caption() );
            $t->parse( "image_item", "image_item_tpl" );
            $t->set_var( "no_image", "" );
            $t->set_var( "html_item", "" );
        }
        else
        {
            $t->set_var( "html_item", "" );
            $t->set_var( "image_item", "" );
            $t->parse( "no_image", "no_image_tpl" );
        }
    }
    

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "tip_item", "tip_item_tpl", true );
    $i++;
}

if ( count( $tipList ) > 0 )    
    $t->parse( "tip_list", "tip_list_tpl" );
else
    $t->set_var( "tip_list", "" );

$t->pparse( "output", "tip_list_page_tpl" );

?>