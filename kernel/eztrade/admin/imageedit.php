<?php
// 
// $Id: imageedit.php 6233 2001-07-20 11:42:02Z jakobn $
//
// Created on: <21-Sep-2000 10:32:36 bf>
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
// include_once( "classes/ezlog.php" );

// include_once( "classes/ezimagefile.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezproduct.php" );

if ( $Action == "Insert" )
{
    $file = new eZImageFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $product = new eZProduct( $ProductID );
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();
        
        $product->addImage( $image );

        if ( count( $product->images() ) == 1 )
        {
            $product->setThumbnailImage( $image );
            $product->setMainImage( $image );
        }

        eZLog::writeNotice( "Picture added to product: $ProductID  from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
    exit();
}

if ( $Action == "Update" )
{
    $file = new eZImageFile();
    
    if ( $file->getUploadedFile( "userfile" ) )
    {
        $product = new eZProduct( $ProductID );

        $oldImage = new eZImage( $ImageID );
        $product->deleteImage( $oldImage );
        
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();
        
        $product->addImage( $image );
    }
    else
    {
        $image = new eZImage( $ImageID );
        $image->setName( $Name );
        $image->setCaption( $Caption );
        $image->store();
    }
    
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $product = new eZProduct( $ProductID );

    if ( count ( $ImageArrayID ) != 0 )
    {
        foreach( $ImageArrayID as $ImageID )
        {
            $image = new eZImage( $ImageID );
            $product->deleteImage( $image );
        }
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
    exit();    
}

// store the image definition
if ( $Action == "StoreDef" )
{
    $product = new eZProduct( $ProductID );

    // Unset main page image radiobutton
    if ( isset( $NoMainImage ) )
    {
        $product->setMainImage( false );
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
        exit();
    }

    // Unset mini page image radiobutton
    if ( isset( $NoMiniImage ) )
    {
        $product->setThumbnailImage( false );
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
        exit();
    }

    if ( isset( $ThumbnailImageID ) &&  ( $ThumbnailImageID != 0 ) &&  ( $ThumbnailImageID != "" ) )
    {
        $thumbnail = new eZImage( $ThumbnailImageID );
        $product->setThumbnailImage( $thumbnail );
    }

    if ( isset( $MainImageID ) &&  ( $MainImageID != 0 ) &&  ( $MainImageID != "" ) )
    {
        $main = new eZImage( $MainImageID );
        $product->setMainImage( $main );
    }
    
    if ( isset( $NewImage ) )
    {
        print( "new image" );
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /trade/productedit/imageedit/new/$ProductID/" );
        exit();
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/productedit/edit/" . $ProductID . "/" );
    exit();
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "imageedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_edit_page" => "imageedit.tpl",
    ) );


$t->set_block( "image_edit_page", "image_tpl", "image" );

//default values
$t->set_var( "name_value", "" );
$t->set_var( "caption_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
$t->set_var( "image", "" );

if ( $Action == "Edit" )
{
    $product = new eZProduct( $ProductID );
    $image = new eZImage( $ImageID );

    $t->set_var( "image_id", $image->id() );
    $t->set_var( "name_value", $image->name() );
    $t->set_var( "caption_value", $image->caption() );
    $t->set_var( "action_value", "Update" );


    $t->set_var( "image_alt", $image->caption() );

    $variation = $image->requestImageVariation( 150, 150 );
    
    $t->set_var( "image_src", "/" .$variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    $t->set_var( "image_file_name", $image->originalFileName() );
    $t->parse( "image", "image_tpl" );
}

$product = new eZProduct( $ProductID );
    
$t->set_var( "product_name", $product->name() );
$t->set_var( "product_id", $product->id() );

$t->pparse( "output", "image_edit_page" );

?>
