<?php
// 
// $Id: linkcategorylist.php 7517 2001-09-27 12:49:48Z th $
//
// Created on: <26-Oct-2000 15:02:09 ce>
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

include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/ezlist.php" );


include_once( "ezlink/classes/ezlinkcategory.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );
include_once( "ezlink/classes/ezlinktype.php" );
include_once( "ezsitemanager/classes/ezsection.php" );

if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $LinkCategoryID ) )
    $LinkCategoryID = false;

// List all the categories
$linkCategory = new eZLinkCategory();
$linkCategory->get( $LinkCategoryID );

// section

if ( is_numeric( $linkCategory->sectionID() ) )
{
    $GlobalSectionID = $linkCategory->sectionID();
    // init the section
    $sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
    $sectionObject->setOverrideVariables();
}



$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZLinkMain", "Language" );
$UserLimit = $ini->read_var( "eZLinkMain", "UserLinkLimit" );
$languageIni = new INIFile( "ezlink/user/intl/". $Language . "/linkcategorylist.php.ini", false );


$t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
                     "ezlink/user/intl", $Language, "linkcategorylist.php" );

$t->setAllStrings();

$t->set_file( "link_page_tpl", "linkcategorylist.tpl" );

$t->set_block( "link_page_tpl", "headline_tpl", "headline_item" );
$t->set_block( "link_page_tpl", "category_name_tpl", "category_name_item" );

$t->set_block( "link_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_block( "category_item_tpl", "image_item_tpl", "image_item" );
$t->set_block( "category_item_tpl", "no_image_tpl", "no_image" );

$t->set_block( "link_page_tpl", "link_list_tpl", "link_list" );
$t->set_block( "link_list_tpl", "link_item_tpl", "link_item" );

$t->set_block( "link_item_tpl", "link_image_item_tpl", "link_image_item" );
$t->set_block( "link_item_tpl", "attribute_list_tpl", "attribute_list" );

$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );
$t->set_block( "attribute_list_tpl", "attribute_value_tpl", "attribute_value" );
$t->set_block( "attribute_list_tpl", "attribute_header_tpl", "attribute_header" );

$t->set_block( "link_page_tpl", "path_item_tpl", "path_item" );


$t->set_var( "attribute_header", "" );
$t->set_var( "attribute_value", "" );

// Path
$pathArray = $linkCategory->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );
    $t->set_var( "category_name", $path[1] );
    $t->parse( "path_item", "path_item_tpl", true );
}

if ( ! $path[1] )
{
    $t->parse( "headline_item", "headline_tpl" );
    $t->set_var( "category_name_item", "" );
}	 
else
{
    $t->parse( "category_name_item", "category_name_tpl" );
    $t->set_var( "headline_item", "" );
}	 

$linkCategory_array = $linkCategory->getByParent( $LinkCategoryID );

if ( empty( $linkCategory_array ) or count( $linkCategory_array ) == 0 )
{
    $t->set_var( "categories", "" );
    $t->set_var( "category_list", "" );
}
else
{
    $i = 0;
    foreach( $linkCategory_array as $categoryItem )
    {
        $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark"  );

        $link_category_id = $categoryItem->id();
        $t->set_var( "linkcategory_id", $link_category_id );
        $t->set_var( "linkcategory_name", $categoryItem->name() );
        $t->set_var( "linkcategory_description", $categoryItem->description() );
        $t->set_var( "linkcategory_parent", $categoryItem->parent() );

        $image =& $categoryItem->image();

        $t->set_var( "image_item" , "" );
        
        if ( $image )
        {
            $imageWidth =& $ini->read_var( "eZLinkMain", "CategoryImageWidth" );
            $imageHeight =& $ini->read_var( "eZLinkMain", "CategoryImageHeight" );

            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );
            
            $imageURL = "/" . $variation->imagePath();
            $imageWidth = $variation->width();
            $imageHeight = $variation->height();
            $imageCaption = $image->caption();

            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            $t->set_var( "no_image", "" );
            $t->parse( "image_item", "image_item_tpl" );
        }
        else
        {
            $t->parse( "no_image", "no_image_tpl" );
            $t->set_var( "image_item", "" );
        }

        $categories = $languageIni->read_var( "strings", "categories" );

        $t->set_var( "categories", $categories );
        
        $i++;
        $t->parse( "category_item", "category_item_tpl", true );
    }
    $t->parse( "category_list", "category_list_tpl", true );
}


// List all the links in the category
$links = $linkCategory->links( $Offset, $UserLimit );
$linkCount = $linkCategory->linkCount();
if ( count( $links ) == 0 )
{
    if ( $LinkCategoryID == 0 )
    {
        $t->set_var( "link_list", "" );
    }
    else
    {
        $t->set_var( "links", "" );
        $t->set_var( "link_list", "" );
    }
    
}
else
{
    $i = 0;
    foreach ( $links as $linkItem )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {            
            $t->set_var( "td_class", "bgdark" );
        }
        $t->set_var( "link_id", $linkItem->id() );
        $t->set_var( "link_name", $linkItem->name() );
        $t->set_var( "link_description", $linkItem->description() );
        $t->set_var( "link_keywords", $linkItem->keywords() );
        $t->set_var( "link_created", $linkItem->created() );
        $t->set_var( "link_modified", $linkItem->modified() );
        $t->set_var( "link_accepted", $linkItem->accepted() );
        $t->set_var( "link_url", $linkItem->url() );

        $image =& $linkItem->image();

        $t->set_var( "link_image_item", "" );
        
        if ( $image )
        {
            $imageWidth =& $ini->read_var( "eZLinkMain", "LinkImageWidth" );
            $imageHeight =& $ini->read_var( "eZLinkMain", "LinkImageHeight" );
            
            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );
            
            $imageURL = "/" . $variation->imagePath();
            $imageWidth = $variation->width();
            $imageHeight = $variation->height();
            $imageCaption = $image->caption();
            
            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            
            $t->parse( "link_image_item", "link_image_item_tpl" );
        }
        
        $hit = new eZHit();
        $hits = $hit->getLinkHits( $linkItem->id() );
        $t->set_var( "link_hits", $hits );

        $links = $languageIni->read_var( "strings", "links" );
        $t->set_var( "links", $links );
        $i++;
        $t->set_var( "attribute", "" );
        // attribute list
        $type = $linkItem->type();
        if ( $type )    
        {
            $attributes = $type->attributes();
            for( $i2 = 0; $i2 < count( $attributes ); $i2++ )
            {
                if ( ( $i2 % 2 ) == 0 )
                {
                    $t->set_var( "begin_tr", "<tr>" );
                    $t->set_var( "end_tr", "" );        
                }
                else
                {
                    $t->set_var( "begin_tr", "" );
                    $t->set_var( "end_tr", "</tr>" );
                }
                
                $value =& $attributes[$i2]->value( $linkItem );
                $t->set_var( "attribute_id", $attributes[$i2]->id( ) );
                $t->set_var( "attribute_name", $attributes[$i2]->name( ) );
                $t->set_var( "attribute_unit", $attributes[$i2]->unit( ) );
                $t->set_var( "attribute_value_var", $value );

                if ( ( is_numeric( $value ) and ( $value > 0 ) ) || ( !is_numeric( $value ) and $value != "" ) )
                {
                    $t->parse( "attribute", "attribute_value_tpl", true );
                }
            }
        }

        if ( isset( $attributes ) && count( $attributes ) > 0 and $type )
        {
            $t->parse( "attribute_list", "attribute_list_tpl" );
        }
        else
        {
            $t->set_var( "attribute_list", "" );
        }
        $t->parse( "link_item", "link_item_tpl", true );
    }
    $t->parse( "link_list", "link_list_tpl", true );
}

eZList::drawNavigator( $t, $linkCount, $UserLimit, $Offset, "link_page_tpl" );

$t->set_var( "link_start", $Offset + 1 );
$t->set_var( "link_end", min( $Offset + $UserLimit, $linkCount ) );
$t->set_var( "link_total", $linkCount );

$t->set_var( "linkcategory_id", $LinkCategoryID );
                       
$t->pparse( "output", "link_page_tpl" );    

?>