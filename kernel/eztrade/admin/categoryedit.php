<?php
//
// $Id: categoryedit.php 9895 2004-04-06 11:08:44Z br $
//
// Created on: <18-Sep-2000 14:46:19 bf>
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

// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /trade/categorylist/" );
    exit();
}

if ( isset ( $DeleteCategories ) )
{
    $Action = "DeleteCategories";
}
// include_once( "ezsitemanager/classes/ezsection.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

// include_once( "eztrade/classes/ezproductcategory.php" );

if ( isset( $Action ) && $Action == "New" )
{
    $CategoryID = false;
    $sectionID = false;
}

// Get images from the image browse function.
if ( ( isSet ( $AddImages ) ) and ( is_numeric( $CategoryID ) ) and ( is_numeric ( $ImageID ) ) )
{
    $image = new eZImage( $ImageID );
    $category = new eZProductCategory( $CategoryID );
    $category->setImage( $image );
    $category->store();
    $Action = "Edit";
}

// Direct actions
if ( isset( $Action ) && $Action == "Insert" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $ParentID );

    $category = new eZProductCategory();
    $category->setName( $Name );
    $category->setParent( $parentCategory );
    $category->setDescription( $Description );
    $category->setSectionID( $SectionID );
    $category->setSortMode( $SortMode );

    $file = new eZImageFile();
    if ( $file->getUploadedFile( "ImageFile" ) )
    {
        $image = new eZImage( );
        $image->setName( "Image" );
        $image->setImage( $file );

        $image->store();
        $category->setImage( $image );
    }

    $category->store();

    $categoryID = $category->id();

    /* write access select */
    if( isset( $WriteGroupArray ) )
    {
        if( $WriteGroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $categoryID, "trade_category", 'w' );
        }
        else
        {
            eZObjectPermission::removePermissions( $categoryID, "trade_category", 'w' );
            foreach( $WriteGroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $categoryID, "trade_category", 'w' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $categoryID, "trade_category", 'w' );
    }

    /* read access thingy */
    if ( isset( $ReadGroupArray ) )
    {
        if( $ReadGroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $categoryID, "trade_category", 'r' );
        }
        else // some groups are selected.
        {
            eZObjectPermission::removePermissions( $categoryID, "trade_category", 'r' );
            foreach ( $ReadGroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $categoryID, "trade_category", 'r' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $categoryID, "trade_category", 'r' );
    }

    // include_once( "classes/ezcachefile.php" );
    $files = eZCacheFile::files( "eztrade/cache/", array( "productlist",
                                                          array( $ParentID, $category->id() ),
                                                          NULL, NULL ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    if ( isSet ( $Browse ) )
    {
        $categoryID = $category->id();
        $session = eZSession::globalSession();
        $session->setVariable( "SelectImages", "single" );
        $session->setVariable( "ImageListReturnTo", "/trade/categoryedit/edit/$categoryID/" );
        $session->setVariable( "NameInBrowse", $category->name() );
        eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
        exit();
    }

    eZHTTPTool::header( "Location: /trade/categorylist/" );
    exit();
}

if ( isset( $Action ) && $Action == "Update" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $ParentID );

    $category = new eZProductCategory();
    $category->get( $CategoryID );
    $category->setName( $Name );
    $category->setParent( $parentCategory );
    $category->setDescription( $Description );
    $category->setSectionID( $SectionID );
    $category->setSortMode( $SortMode );

    $file = new eZImageFile();
    if ( $file->getUploadedFile( "ImageFile" ) )
    {
        $image = new eZImage( );
        $image->setName( "Image" );
        $image->setImage( $file );

        $image->store();
        $category->setImage( $image );
    }

    if ( $DeleteImage == "on" )
        $category->setImage( 0 );

    $category->store();

    $categoryID = $category->id();

    /* write access select */
    if( isset( $WriteGroupArray ) )
    {
        if( $WriteGroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $categoryID, "trade_category", 'w' );
        }
        else
        {
            eZObjectPermission::removePermissions( $categoryID, "trade_category", 'w' );
            foreach( $WriteGroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $categoryID, "trade_category", 'w' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $categoryID, "trade_category", 'w' );
    }

    /* read access thingy */
    if ( isset( $ReadGroupArray ) )
    {
        if( $ReadGroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $categoryID, "trade_category", 'r' );
        }
        else // some groups are selected.
        {
            eZObjectPermission::removePermissions( $categoryID, "trade_category", 'r' );
            foreach ( $ReadGroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $categoryID, "trade_category", 'r' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $categoryID, "trade_category", 'r' );
    }


    // include_once( "classes/ezcachefile.php" );
    $files = eZCacheFile::files( "eztrade/cache/", array( "productlist",
                                                          array( $ParentID, $CategoryID ), NULL, NULL ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    if ( isSet ( $Browse ) )
    {
        $categoryID = $category->id();
        $session = eZSession::globalSession();
        $session->setVariable( "SelectImages", "single" );
        $session->setVariable( "ImageListReturnTo", "/trade/categoryedit/edit/$categoryID/" );
        $session->setVariable( "NameInBrowse", $category->name() );
        eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
        exit();
    }

    eZHTTPTool::header( "Location: /trade/categorylist/" );
    exit();
}

if ( isset( $Action ) && $Action == "Delete" )
{
    $category = new eZProductCategory();
    $category->get( $CategoryID );

    if ( eZFile::file_exists( "ezarticle/cache/menubox.cache" ) )
        eZFile::unlink( "ezarticle/cache/menubox.cache" );

    // include_once( "classes/ezcachefile.php" );

    $parent = $category->parent();
    if ( is_a( $parent, "eZProductCategory" ) )
        $parent = $parent->id();
    $files = eZCacheFile::files( "eztrade/cache/",
                                 array( "productlist",
                                        array( $category->id(), $parent ),
                                        NULL, NULL ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    $category->delete();

    eZHTTPTool::header( "Location: /trade/categorylist/" );
    exit();
}

if ( isset( $Action ) && $Action == "DeleteCategories" )
{
    if ( count ( $CategoryArrayID ) != 0 )
    {
        if ( eZFile::file_exists( "ezarticle/cache/menubox.cache" ) )
            eZFile::unlink( "ezarticle/cache/menubox.cache" );

        // include_once( "classes/ezcachefile.php" );
        foreach( $CategoryArrayID as $ID )
        {
            $category = new eZProductCategory();
            $category->get( $ID );

            $parent = $category->parent();
            if ( is_a( $parent, "eZProductCategory" ) )
                $parent = $parent->id();
            $files = eZCacheFile::files( "eztrade/cache/",
                                         array( "productlist",
                                                array( $category->id(), $parent ),
                                                NULL, NULL ),
                                         "cache", "," );
            foreach( $files as $file )
            {
                $file->delete();
            }

            $category->delete();
        }
    }

    eZHTTPTool::header( "Location: /trade/categorylist/" );
    exit();
}

if ( isset( $Action ) && $Action == "CopyCategoires" )
{
    if ( count ( $CategoryArrayID ) > 0 )
    {
        foreach( $CategoryArrayID as $ID )
        {
        }
    }
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "categoryedit.php" );

$t->setAllStrings();

$t->set_file( array( "category_edit_tpl" => "categoryedit.tpl" ) );


$t->set_block( "category_edit_tpl", "value_tpl", "value" );
$t->set_block( "category_edit_tpl", "image_item_tpl", "image_item" );

$t->set_block( "category_edit_tpl", "read_group_item_tpl", "read_group_item" );
$t->set_block( "category_edit_tpl", "write_group_item_tpl", "write_group_item" );
$t->set_block( "category_edit_tpl", "section_item_tpl", "section_item" );


$headline = new INIFIle( "kernel/eztrade/admin/intl/" . $Language . "/categoryedit.php.ini", false );
$t->set_var( "head_line", $headline->read_var( "strings", "head_line_insert" ) );

$category = new eZProductCategory();

$categoryArray = $category->getTree( );

$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "action_value", "insert" );

$t->set_var( "1_selected", "" );
$t->set_var( "2_selected", "" );
$t->set_var( "3_selected", "" );
$t->set_var( "4_selected", "" );
$t->set_var( "image_item", "" );

$writeGroupsID = array();
$readGroupsID = array();

// edit
if ( isset( $Action ) && $Action == "Edit" )
{
    $category = new eZProductCategory();
    $category->get( $CategoryID );

    $t->set_var( "name_value", $category->name() );
    $t->set_var( "description_value", $category->description() );
    $t->set_var( "action_value", "update" );
    $t->set_var( "category_id", $category->id() );

    $parent = $category->parent();
    switch ( $category->sortMode() )
    {
        case "time" :
        {
            $t->set_var( "1_selected", "selected" );
        }
        break;

        case "alpha" :
        {
            $t->set_var( "2_selected", "selected" );
        }
        break;

        case "alphadesc" :
        {
            $t->set_var( "3_selected", "selected" );
        }
        break;

        case "absolute_placement" :
        {
            $t->set_var( "4_selected", "selected" );
        }
        break;
    }

    $sectionID = $category->sectionID();

    $image =& $category->image( true );
    if ( is_a( $image, "eZImage" ) && $image->id() != 0 )
    {
        $imageWidth =& $ini->read_var( "eZTradeMain", "CategoryImageWidth" );
        $imageHeight =& $ini->read_var( "eZTradeMain", "CategoryImageHeight" );

        $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

        $imageURL = "/" . $variation->imagePath();
        $imageWidth = $variation->width();
        $imageHeight = $variation->height();
        $imageCaption = $image->caption();

        $t->set_var( "image_width", $imageWidth );
        $t->set_var( "image_height", $imageHeight );
        $t->set_var( "image_url", $imageURL );
        $t->set_var( "image_caption", $imageCaption );
        $t->parse( "image_item", "image_item_tpl" );
    }
    else
    {

    }

    $writeGroupsID = eZObjectPermission::getGroups( $CategoryID, "trade_category", 'w' , false );
    $readGroupsID = eZObjectPermission::getGroups( $CategoryID, "trade_category", 'r', false );

    $headline = new INIFIle( "kernel/eztrade/admin/intl/" . $Language . "/categoryedit.php.ini", false );
    $t->set_var( "head_line", $headline->read_var( "strings", "head_line_edit" ) );
}

foreach ( $categoryArray as $catItem )
{
    if ( $CategoryID != $catItem[0]->id() )
    {
        $t->set_var( "option_value", $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );

        if ( $Action == "Edit" )
        {
		    if ( $parent )
			{
            	if ( $catItem[0]->id() == $parent->id() )
            	{
                	$t->set_var( "selected", "selected" );
            	}
            	else
            	{
               		$t->set_var( "selected", "" );
            	}
			}

        }
        else
        {
            $t->set_var( "selected", "" );
        }

        if ( $catItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->parse( "value", "value_tpl", true );
    }
}

$sectionList =& eZSection::getAll();

if ( count( $sectionList ) > 0 )
{
    foreach ( $sectionList as $section )
    {
        $t->set_var( "section_id", $section->id() );
        $t->set_var( "section_name", $section->name() );

        if ( $sectionID == $section->id() )
            $t->set_var( "section_is_selected", "selected" );
        else
            $t->set_var( "section_is_selected", "" );

        $t->parse( "section_item", "section_item_tpl", true );
    }
}
else
    $t->set_var( "section_item", "" );


// group selector
$group = new eZUserGroup();
$groupList = $group->getAll();

$t->set_var( "selected", "" );

$writeAllGroupFound = false;
if ( in_array( "-1", $readGroupsID ) )
{
    $t->set_var( "all_selected", "selected=\"selected\"" );
    $writeAllGroupFound = true;
}
else
{
    $t->set_var( "all_selected", "" );
}

$readAllGroupFound = false;
if ( in_array( "-1", $writeGroupsID ) )
{
    $t->set_var( "all_write_selected", "selected=\"selected\"" );
    $writeAllGroupFound = true;
}
else
{
    $t->set_var( "all_write_selected", "" );
}



foreach( $groupList as $groupItem )
{
    /* for the group owner selector */
    $t->set_var( "read_id", $groupItem->id() );
    $t->set_var( "read_name", $groupItem->name() );

    if( in_array( $groupItem->id() , $readGroupsID ) && $readAllGroupFound == false )
        $t->set_var( "selected", "selected=\"selected\"" );
    else
        $t->set_var( "selected", "" );

    $t->parse( "read_group_item", "read_group_item_tpl", true );

    /* for the read access groups selector */
    $t->set_var( "write_id", $groupItem->id() );
    $t->set_var( "write_name", $groupItem->name() );

    if( in_array( $groupItem->id(), $writeGroupsID )  && $writeAllGroupFound == false )
        $t->set_var( "is_selected", "selected=\"selected\"" );
    else
        $t->set_var( "is_selected", "" );

    $t->parse( "write_group_item", "write_group_item_tpl", true );
}


$t->pparse( "output", "category_edit_tpl" );

?>