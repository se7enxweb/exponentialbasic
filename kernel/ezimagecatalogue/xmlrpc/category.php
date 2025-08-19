<?php
//
// $Id: category.php 9520 2002-05-08 13:05:20Z jb $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// eZ article complete data
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

if( $Command == "info" )
{
    $category = new eZImageCategory();
    if ( !$category->get( $ID ) )
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
    else
    {
        $ret = array( "Name" => new eZXMLRPCString( $category->name( false ) ) );
        $ReturnData = new eZXMLRPCStruct( $ret );
    }
}
else if ( $Command == "permission" )
{
    $read = eZObjectPermission::hasPermission( $ID, "imagecatalogue_category", "r", $User );
    $edit = eZObjectPermission::hasPermission( $ID, "imagecatalogue_category", 'w', $User );

    $ret = array( "Read" => new eZXMLRPCBool( $read ),
                  "Edit" => new eZXMLRPCBool( $edit ) );
    $ReturnData = new eZXMLRPCStruct( $ret );
}
else if( $Command == "data" ) // Dump category info!
{
//      usleep( 5000000 );
    $writeGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_category", 'w', false );
    $readGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_category", 'r', false );
    $uploadGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_category", 'u', false );

    $rgp = array();
    $wgp = array();
    $ugp = array();

    foreach( $readGroups as $group )
        $rgp[] = new eZXMLRPCInt( $group );
    foreach( $writeGroups as $group )
        $wgp[] = new eZXMLRPCInt( $group );
    foreach( $uploadGroups as $group )
        $ugp[] = new eZXMLRPCInt( $group );

    $category = new eZImageCategory( $ID );
    $cat_def_id = $category->parent( false );
    $section_id = eZImageCategory::sectionIDStatic( $cat_def_id );
    $section_lang = false;
    if ( $section_id != 0 )
    {
        $section = new eZSection( $section_id );
        $section_lang = $section->language();
    }

    $ret = array( "Location" => createURLStruct( "ezimagecatalogue", "category", $category->id() ),
                  "Name" => new eZXMLRPCString( $category->name( false ) ),
                  "ParentID" => new eZXMLRPCInt( $category->parent( false ) ),
                  "Description" => new eZXMLRPCString( $category->description( false ) ),
                  "ReadGroups" => new eZXMLRPCArray( $rgp ),
                  "WriteGroups" => new eZXMLRPCArray( $wgp ),
                  "UploadGroups" => new eZXMLRPCArray( $ugp )
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
else if( $Command == "storedata" ) // save the category data!
{
    if( $ID == 0 )
    {
        $category = new eZImageCategory();
    }
    else
    {
        $category = new eZImageCategory( $ID );
        $category->setUser( $User );
    }
    $category->setName( $Data["Name"]->value() );
    $category->setDescription( $Data["Description"]->value() );

    $parent = new eZImageCategory();
    $parentid = $Data["ParentID"]->value();
    if ( $parentid == 0 or $parent->get( $parentid ) )
    {
        $old_category = $category->parent();
        $old_category_id = is_object( $old_category ) ? $old_category->id() : 0;
        $category->setParent( $parent );
        $category->store();
        $ID = $category->id();

        eZObjectPermission::removePermissions( $ID, "imagecatalogue_category", 'r' );
        $readGroups = $Data["ReadGroups"]->value();
        foreach( $readGroups as $readGroup )
            eZObjectPermission::setPermission( $readGroup->value(), $ID, "imagecatalogue_category", 'r' );

        eZObjectPermission::removePermissions( $ID, "imagecatalogue_category", 'w' );
        foreach( $Data["WriteGroups"]->value() as $writeGroup )
            eZObjectPermission::setPermission( $writeGroup->value(), $ID, "imagecatalogue_category", 'w' );

        eZObjectPermission::removePermissions( $ID, "imagecatalogue_category", 'u' );
        foreach( $Data["UploadGroups"]->value() as $uploadGroup )
            eZObjectPermission::setPermission( $uploadGroup->value(), $ID, "imagecatalogue_category", 'u' );

        $par =& createPath( $category, "ezimagecatalogue", "category", false );

        $add_categories = array();
        $cur_categories = array();
        $remove_categories = array();
        $add_categories = array_diff( array( $parent->id() ), array( $old_category_id ) );
        $remove_categories = array_diff( array( $old_category_id ), array( $parent_id ) );
        $cur_categories = array_intersect( array( $parent->id() ), array( $old_category_id ) );

        $add_locs =& createURLArray( $add_categories, "ezimagecatalogue", "category" );
        $cur_locs =& createURLArray( $cur_categories, "ezimagecatalogue", "category" );
        $old_locs =& createURLArray( $remove_categories, "ezimagecatalogue", "category" );

        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezimagecatalogue", "category", $ID ),
                                                 "Name" => new eZXMLRPCString( $category->name( false ) ),
                                                 "Path" => new eZXMLRPCArray( $par ),
                                                 "NewLocations" => $add_locs,
                                                 "ChangedLocations" => $cur_locs,
                                                 "RemovedLocations" => $old_locs,
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 )
                                          );
        $Command = "update";
    }
    else
    {
        $Error = createErrorMessage( EZERROR_CUSTOM, "Parent ($parentid) of $Module:/$RequestType/$ID is not an existing parent",
                                     EZIMAGECATALOGUE_NONEXISTING_PARENT );
    }
}
else if( $Command == "delete" )
{
    $category = new eZImageCategory();
    if ( $category->get( $ID ) )
    {
        $par =& createPath( $category, "ezimagecatalogue", "category", false );

        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezimagecatalogue", "category", $ID ),
                                                 "Path" => new eZXMLRPCArray( $par ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 )
                                          );
        $Command = "update";
        $category->delete( $ID );
    }
    else
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
}

?>