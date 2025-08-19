<?php
//
// $Id: image.php 9691 2002-08-06 15:15:45Z gl $
//
// Created on: <14-Jun-2001 13:18:27 amos>
//
// This source file is part of Exponential Basic, publishing software.
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

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagevariation.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

if( $Command == "info" )
{
    $image = new eZImage();
    if ( !$image->get( $ID ) )
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
    else
    {
        $ret = array( "Name" => new eZXMLRPCString( $image->name( false ) ) );
        $ReturnData = new eZXMLRPCStruct( $ret );
    }
}
else if ( $Command == "permission" )
{
    $img = new eZImage( $ID );
    $cat = $img->categoryDefinition();
    $CategoryID = $cat->id();
    $read_c = eZObjectPermission::hasPermission( $CategoryID, "imagecatalogue_category", "r", $User );
    $edit_c = eZObjectPermission::hasPermission( $CategoryID, "imagecatalogue_category", 'w', $User );
    $read = ( eZObjectPermission::hasPermission( $ID, "imagecatalogue_image", "r", $User ) and
              $read_c );
    $edit = ( eZObjectPermission::hasPermission( $ID, "imagecatalogue_image", 'w', $User ) and
              $edit_c );
//     $read = eZObjectPermission::hasPermissionWithDefinition( $ID, "imagecatalogue_image", "r", $User, $CategoryID );
//     $edit = eZObjectPermission::hasPermissionWithDefinition( $ID, "imagecatalogue_image", 'w', $User, $CategoryID );

    $ret = array( "Read" => new eZXMLRPCBool( $read ),
                  "Edit" => new eZXMLRPCBool( $edit ) );
    $ReturnData = new eZXMLRPCStruct( $ret );
}
else if( $Command == "data" ) // Dump image info!
{
    unset( $width );
    unset( $height );
    if ( is_object( $Data["Size"] ) )
    {
        $size = $Data["Size"]->value();
        if ( is_object( $size["Width"] ) and is_object( $size["Height"] ) )
        {
            $width = $size["Width"]->value();
            $height = $size["Height"]->value();
        }
    }

    if ( isset( $width ) and isset( $height ) )
    {
        $writeGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_image", 'w', false );
        $readGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_image", 'r', false );

        $rgp = array();
        $wgp = array();
        foreach( $readGroups as $group )
            $rgp[] = new eZXMLRPCInt( $group );
        foreach( $writeGroups as $group )
            $wgp[] = new eZXMLRPCInt( $group );

        $image = new eZImage( $ID );
        $variation = $image->requestImageVariation( $width, $height, false, true );
        if ( !is_bool( $variation ) )
        {
            $size = 0;
            if ( $image->fileExists( true ) )
            {
                $imagePath =& $image->filePath( true );
                $size = eZPBFile::filesize( $imagePath );
                $user = $image->user();
                $user_id = is_a( $user, "eZUser" ) ? $user->id() : 0;
                $cat_def = $image->categoryDefinition();
                $cat_def_id = is_a( $cat_def, "eZImageCategory" ) ? $cat_def->id() : 0;
                $cats = $image->categories();
                $cats = array_diff( $cats, array( $cat_def_id ) );

                $section_id = 0;
                $cat_def_id = 0;
                $cat_def = $image->categoryDefinition();
                if ( is_object( $cat_def ) )
                {
                    $cat_def_id = $cat_def->id();
                    $section_id = eZImageCategory::sectionIDStatic( $cat_def_id );
                }
                $section_lang = false;
                if ( $section_id != 0 )
                {
                    $section = new eZSection( $section_id );
                    $section_lang = $section->language();
                }

                $photoid = $image->photographer( false );
                $ret = array(
                    "Name" => new eZXMLRPCString( $image->name( false ) ),
                    "Caption" => new eZXMLRPCString( $image->caption( false ) ),
                    "Description" => new eZXMLRPCString( $image->description( false ) ),
                    "FileName" => new eZXMLRPCString( $image->fileName() ),
                    "OriginalFileName" => new eZXMLRPCString( $image->originalFileName() ),
                    "FileSize" => new eZXMLRPCInt( $size ),
                    "UserID" => new eZXMLRPCInt( $user_id ),
                    "PhotographerID" => new eZXMLRPCInt( $photoid > 0 ? $photoid : 0 ),
                    "ReadGroups" => new eZXMLRPCArray( $rgp ),
                    "WriteGroups" => new eZXMLRPCArray( $wgp ),
                    "Categories" => new eZXMLRPCArray( $cats, "integer" ),
                    "Category" => new eZXMLRPCInt( $cat_def_id ),
                    "WebURL" => new eZXMLRPCString( rewriteHTTPURL( "/" . $variation->imagePath() ) ),
                    "Size" => createSizeStruct( $variation->width(), $variation->height() ),
                    "RequestSize" => createSizeStruct( $width, $height )
                    );
                if ( $section_lang != false )
                {
                    $charsetLocale = new eZLocale( $section_lang );
                    $section_charset = $charsetLocale->languageISO();
                    $ret["Section"] = new eZXMLRPCStruct( array( "Language" => $section_lang,
                                                                 "Charset" => $section_charset ) );
                }
                $ReturnData = new eZXMLRPCStruct( $ret );
            }
            else
                $Error = createErrorMessage( EZERROR_CUSTOM, "Image $ID does not exist on disk",
                                             EZIMAGECATALOGUE_NONEXISTING_IMAGE );
        }
        else
            $Error = createErrorMessage( EZERROR_CUSTOM, "Couldn't convert image $ID",
                                         EZIMAGECATALOGUE_CONVERT_ERROR );
    }
    else
    {
        $Error = createErrorMessage( EZERROR_CUSTOM, "Missing width and height in image request",
                                     EZIMAGECATALOGUE_SIZE_MISSING );
    }
}
else if ( $Command == "storedata" )
{
    if ( isset( $Data["Title"] ) and isset( $Data["Caption"] ) and isset( $Data["Description"] ) and isset( $Data["PhotographerID"] ) )
    {
        $title = $Data["Title"]->value();
        $caption = $Data["Caption"]->value();
        $description = $Data["Description"]->value();
        $photographer = $Data["PhotographerID"]->value();
        $readgroups = $Data["ReadGroups"]->value();
        $writegroups = $Data["WriteGroups"]->value();
        $category_id = $Data["Category"]->value();
        $categories = $Data["Categories"]->value();

        $image = new eZImage();
        if ( $ID != 0 )
        {
            if ( !$image->get( $ID ) )
                $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
        }
        if ( !$Error )
        {
            $image->setName( $title );
            $image->setCaption( $caption );
            $image->setDescription( $description );
            $image->setPhotographer( $photographer > 0 ? $photographer : 0 );
            if ( isset( $Data["Image"] ) and isset( $Data["ImageFileName"] ) )
            {
                $image_data = $Data["Image"]->value();
                $orig_file = $Data["ImageFileName"]->value();
                $image_file = new eZPBImageFile();
                $image_file->dumpDataToFile( $image_data, $orig_file );
                if ( !$image->setImage( $image_file ) )
                {
                    $Error = createErrorMessage( EZERROR_CUSTOM, "Failed to set image sent by client to image $ID",
                                                 EZIMAGECATALOGUE_BAD_IMAGE );
                }
            }
            if ( !$Error )
            {
                $image->store();
                $ID = $image->id();

                $old_category = $image->categoryDefinition();

                $category = new eZImageCategory( $category_id );
                $image->setCategoryDefinition( $category );

                // categories...
                $old_categories =& $image->categories();
                if ( is_bool( $old_categories ) )
                    $old_categories = array();
                $new_categories = array();
                foreach( $categories as $cat )
                {
                    $new_categories[] = $cat->value();
                }
                $new_cats = $new_categories;
                if ( $category_id > 0 )
                    $new_categories = array_unique( array_merge( $new_categories, $category_id ) );
                $remove_categories = array_diff( $old_categories, $new_categories );
                $add_categories = array_diff( $new_categories, $old_categories );
                $cur_categories = array_intersect( $old_categories, $new_categories );

                foreach ( $remove_categories as $categoryItem )
                {
                    eZImageCategory::removeImage( $image, $categoryItem );
                }
                foreach ( $add_categories as $categoryItem )
                {
                    eZImageCategory::addImage( $image, $categoryItem );
                }

                // permissions...
                eZObjectPermission::removePermissions( $ID, "imagecatalogue_image", 'r' );
                foreach( $readgroups as $readgroup )
                    eZObjectPermission::setPermission( $readgroup->value(), $ID, "imagecatalogue_image", 'r' );

                eZObjectPermission::removePermissions( $ID, "imagecatalogue_image", 'w' );
                foreach( $writegroups as $writegroup )
                    eZObjectPermission::setPermission( $writegroup->value(), $ID, "imagecatalogue_image", 'w' );

                $ID = $image->id();

                $par = array();

                $par =& createPath( $category, "ezimagecatalogue", "category" );

                $add_locs =& createURLArray( $add_categories, "ezimagecatalogue", "category" );
                $cur_locs =& createURLArray( $cur_categories, "ezimagecatalogue", "category" );
                $old_locs =& createURLArray( $remove_categories, "ezimagecatalogue", "category" );

                $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezimagecatalogue", "image", $ID ),
                                                         "Name" => new eZXMLRPCString( $image->name( false ) ),
                                                         "Path" => new eZXMLRPCArray( $par ),
                                                         "NewLocations" => $add_locs,
                                                         "ChangedLocations" => $cur_locs,
                                                         "RemovedLocations" => $old_locs,
                                                         "UpdateType" => new eZXMLRPCString( $Command )
                                                         )
                                                  );
                $Command = "update";
            }
        }
    }
    else
    {
        $Error = createErrorMessage( EZERROR_BAD_REQUEST_DATA );
    }
}
else if ( $Command == "search" )
{
    $keywords = $Data["Keywords"]->value();
    $texts = array();
    foreach( $keywords as $keyword )
    {
        $texts[] = $keyword->value();
    }
    $elements = array();
    $result =& eZImage::search( $texts, true );
    foreach( $result as $item )
    {
        $cat =& $item->categoryDefinition();
        if ( is_a( $cat, "eZImageCategory" ) )
        {
            $cats =& $item->categories();
            if ( is_array( $cats ) and count( $cats ) > 0 )
            {
                $cat = new eZImageCategory( $cats[0] );
            }
            else
                continue;
        }
        $itemid = $item->id();
        $catid = $cat->id();
        $elements[] = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $item->name( false ) ),
                                                 "CategoryName" => new eZXMLRPCString( $cat->name( false ) ),
                                                 "Location" => createURLStruct( "ezimagecatalogue", "image", $item->id() ),
                                                 "CategoryLocation" => createURLStruct( "ezimagecatalogue", "category", $cat->id() ),
                                                 "HasPreview" => new eZXMLRPCBool( true ),
                                                 "WebURL" => new eZXMLRPCString( rewriteWebURL( "/imagecatalogue/imageview/$itemid/" ) ),
                                                 "CategoryWebURL" => new eZXMLRPCString( rewriteWebURL( "/imagecatalogue/image/list/$catid/" ) )
                                                 ) );
    }
    $ret = array( "Elements" => new eZXMLRPCArray( $elements ) );
    handleSearchData( $ret );
    $ReturnData = new eZXMLRPCStruct( $ret );
}
else if( $Command == "delete" )
{
    $image = new eZImage();
    if ( $image->get( $ID ) )
    {
        $category = $image->categoryDefinition();
        $par =& createPath( $category, "ezimagecatalogue", "category" );

        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezimagecatalogue", "image", $ID ),
                                                 "Path" => new eZXMLRPCArray( $par ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 )
                                          );
        $Command = "update";
        $image->delete();
    }
    else
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
}

?>