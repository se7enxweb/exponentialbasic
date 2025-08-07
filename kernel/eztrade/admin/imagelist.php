<?php
// 
// $Id: imagelist.php 6746 2001-08-29 14:31:58Z bf $
//
// Created on: <21-Sep-2000 10:32:19 bf>
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
// include_once( "classes/ezcurrency.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTradeMain", "Language" );

// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezproduct.php" );


$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->variable( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "imagelist.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_list_page_tpl" => "imagelist.tpl"
    ) );

$t->set_block( "image_list_page_tpl", "no_images_tpl", "no_images" );
$t->set_block( "image_list_page_tpl", "image_list_tpl", "image_list" );
$t->set_block( "image_list_tpl", "image_tpl", "image" );

$t->set_var( "site_style", $SiteDesign );

$product = new eZProduct( $ProductID );

$session =& eZSession::globalSession();
$session->setVariable( "ImageListReturnTo", $REQUEST_URI );
$session->setVariable( "SelectImages", "multi" );
$session->setVariable( "NameInBrowse", $product->name() );

$thumbnail = $product->thumbnailImage();
$main = $product->mainImage();

$t->set_var( "product_name", $product->name() );

if ( isset ( $AddImages ) )
{
    if ( count ( $ImageArrayID ) > 0 )
    {
        foreach( $ImageArrayID as $imageID )
        {
            $image = new eZImage( $imageID );
            $product->addImage( $image );
        }
    }
    
}

$images = $product->images();
if ( count( $images ) == 0 )
{
    $t->set_var( "image_list", "" );
    $t->parse( "no_images", "no_images_tpl", true );
}
else
{
    $t->set_var( "no_images", "" );

    $i=0;
    $t->set_var( "image", "" );
    foreach ( $images as $imageArray )
    {
        $image = $imageArray["Image"];
        $placement = $imageArray["Placement"];
        
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "main_image_checked", "" );
        if ( $main != 0 )
        {
            if ( $main->id() == $image->id() )
            {
                $t->set_var( "main_image_checked", "checked" );
            }
        }

        $t->set_var( "thumbnail_image_checked", "" );
        if ( $thumbnail != 0 )
        {
            if ( $thumbnail->id() == $image->id() )
            {
                $t->set_var( "thumbnail_image_checked", "checked" );
            }
        }

        $t->set_var( "image_number", $placement );

        $t->set_var( "image_name", $image->caption() );
        $t->set_var( "image_id", $image->id() );
        $t->set_var( "product_id", $ProductID );

        $variation = $image->requestImageVariation( 150, 150 );

        $t->set_var( "image_url", "/" .$variation->imagePath() );
        $t->set_var( "image_width", $variation->width() );
        $t->set_var( "image_height",$variation->height() );

        $t->parse( "image", "image_tpl", true );

        $i++;
    }

    $t->parse( "image_list", "image_list_tpl", true );
}


$t->set_var( "product_id", $product->id() );

$t->pparse( "output", "image_list_page_tpl" );

?>