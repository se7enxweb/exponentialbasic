<?php
// 
// $Id: imageview.php 7670 2001-10-05 09:50:30Z br $
//
// Created on: <26-Oct-2000 19:40:18 bf>
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

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagevariation.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );

$ShowOriginal = $ini->read_var( "eZImageCatalogueMain", "ShowOriginal" );


$t = new eZTemplate( "kernel/ezimagecatalogue/admin/" . $ini->read_var( "eZImageCatalogueMain", "AdminTemplateDir" ),
                     "kernel/ezimagecatalogue/admin/intl/", $Language, "imageview.php" );

$t->set_file( "image_view_tpl", "imageview.tpl" );

$t->setAllStrings();

$user =& eZUser::currentUser();

$image = new eZImage( $ImageID );
     
$parent_category = $image->categories();

//if ( eZObjectPermission::hasPermission( $image->id(), "imagecatalogue_image", "r", $user ) == false )
//{
//    eZHTTPTool::header( "Location: /error/403/" );
//    exit();
//}

if ( $ShowOriginal != "enabled" && !isset( $VariationID ) )
{
    $variation =& $image->requestImageVariation( $ini->read_var( "eZImageCatalogueMain", "ImageViewWidth" ),
    $ini->read_var( "eZImageCatalogueMain", "ImageViewHeight" ) );
}
else if ( isset( $VariationID ) )
{
    $variation = new eZImageVariation( $VariationID );
    if ( $variation->imageID() != $ImageID )
    {
        
        $variation =& $image->requestImageVariation( $ini->read_var( "eZImageCatalogueMain", "ImageViewWidth" ),
        $ini->read_var( "eZImageCatalogueMain", "ImageViewHeight" ) );
    }
}

$t->set_var( "image_uri", "/" . $variation->imagePath() );
$t->set_var( "image_width", $variation->width() );
$t->set_var( "image_height", $variation->height() );
$t->set_var( "image_caption", $image->caption() );
$t->set_var( "image_name", $image->name() );
$t->set_var( "image_description", $image->description() );

$t->set_var( "referer_url", $RefererURL );

$t->pparse( "output", "image_view_tpl" );


?>
