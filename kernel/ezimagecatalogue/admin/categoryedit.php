<?php
// 
// $Id: categoryedit.php 9274 2002-02-26 13:58:27Z br $
//
// Created on: <08-Jan-2001 11:13:29 ce>
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
// include_once( "classes/ezhttptool.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );

// include_once( "ezsitemanager/classes/ezsection.php" );

if (isset( $Action ) && $Action == "New" )
{
    $Name = false;
    $Description = false;
    $sectionID = false;
}

// Insert the category values when editing.
if ( isset( $Action ) && $Action == "Edit" )
{
    $category = new eZImageCategory( $CategoryID );

    $Name = $category->name();
    $Description = $category->description();

    $parent = $category->parent();
    if ( $parent )
        $CurrentCategoryID = $parent->id();
    else
        $CurrentCategoryID = $CategoryID;
}

if ( isset ( $Cancel ) )
{
    eZHTTPTool::header( "Location: /imagecatalogue/image/list/" );
    exit();
}

$user =& eZUser::currentUser();

if ( !$user )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZImageCatalogueMain", "Language" );

$t = new eZTemplate( "kernel/ezimagecatalogue/admin/" . $ini->variable( "eZImageCatalogueMain", "AdminTemplateDir" ),
                     "kernel/ezimagecatalogue/admin/intl/", $Language, "categoryedit.php" );

$t->set_file( "category_edit_tpl", "categoryedit.tpl" );

$t->setAllStrings();

$t->set_block( "category_edit_tpl", "value_tpl", "value" );
$t->set_block( "category_edit_tpl", "errors_tpl", "errors" );

$t->set_block( "category_edit_tpl", "section_item_tpl", "section_item" );
$t->set_block( "category_edit_tpl", "write_group_item_tpl", "write_group_item" );
$t->set_block( "category_edit_tpl", "read_group_item_tpl", "read_group_item" );
$t->set_block( "category_edit_tpl", "upload_group_item_tpl", "upload_group_item" );

$t->set_var( "errors", "" );
$t->set_var( "category_name", "$Name" );
$t->set_var( "category_description", "$Description" );

$t->set_block( "errors_tpl", "error_write_permission", "error_write" );
$t->set_var( "error_write", "" );

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_parent_check_tpl", "error_parent_check" );
$t->set_var( "error_parent_check", "&nbsp;" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

$t->set_block( "errors_tpl", "error_read_everybody_permission_tpl", "error_read_everybody_permission" );
$t->set_var( "error_read_everybody_permission", "&nbsp;" );

$t->set_block( "errors_tpl", "error_write_everybody_permission_tpl", "error_write_everybody_permission" );
$t->set_var( "error_write_everybody_permission", "&nbsp;" );

$error = false;
$permissionCheck = true;
$nameCheck = true;
$descriptionCheck = true;

if ( isset( $Action ) && $Action == "Insert" || isset( $Action ) && $Action == "Update" )
{
    // Check if the user have write access to the category
    if ( $permissionCheck )
    {
        if ( $ParentID == 0 )
        {
            if ( eZPermission::checkPermission( $user, "eZImageCatalogue", "WriteToRoot"  ) == false )
                $error = true;
        }
        else
        {
            if ( eZObjectPermission::hasPermission( $ParentID, "imagecatalogue_category", "w", $user ) == false &&
                 eZObjectPermission::hasPermission( $ParentID, "imagecatalogue_category", 'u', $user ) == false
                 )
            {
                $error = true;
            }
            if ( $Action == "Update" && eZObjectPermission::hasPermission( $ParentID, "imagecatalogue_category", 'w', $user ) == false )
            {
                $error = true;
            }
            
        }
        if ( $error )
            $t->parse( "error_write", "error_write_permission" );
    }

    // Check if parent is the same as category.
    if ( isset( $Action ) && $Action == "Update" )
    {
        if ( $ParentID == $CategoryID )
        {
            $t->parse( "error_parent_check", "error_parent_check_tpl" );
            $error = true;
        }
    }

    // Check if name is empty.
    if ( $nameCheck )
    {
        if ( empty ( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }

    // Check if description is empty.
    if ( $descriptionCheck )
    {
        if ( empty ( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }

    // Check if there was any errors.
 
    if ( $error == true )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}

// Insert or update a category
if ( ( isset( $Action ) && $Action == "Insert" || isset( $Action ) && $Action == "Update" ) && $error == false )
{
    if ( isset( $Action ) && $Action == "Insert" )
    {
        $category = new eZImageCategory();
        $category->setUser( $user );
    }
    else
    {
        $category = new eZImageCategory( $CategoryID );
    }

    $category->setName( $Name );
    $category->setDescription( $Description );

    $category->setSectionID( $SectionID );
    
    $parent = new eZImageCategory( $ParentID );
    $category->setParent( $parent );

    $category->store();
    $CategoryID = $category->id();
    changePermissions( $CategoryID, $ReadGroupArrayID, 'r' );
    changePermissions( $CategoryID, $WriteGroupArrayID, 'w' );
    changePermissions( $CategoryID, $UploadGroupArrayID, 'u' );

    // check if user uploaded a dir and had upload permission only and is not owner.
    if ( $Action == "Insert" && eZObjectPermission::hasPermission( $ParentID, "imagecatalogue_category", 'w' ) == false &&
    $parent->user( false ) != $user->id() )
    {
        eZObjectPermission::removePermissions( $CategoryID, "imagecatalogue_category", "wru" ); // no permissions
        eZObjectPermission::setPermission( -1, $CategoryID, "imagecatalogue_category", 'r' );
        eZObjectPermission::setPermission( -1, $CategoryID, "imagecatalogue_category", 'u' );
        $category->setUser( $parent->user() );
        $category->store();
    }
    // if update and moving into a category that has upload permission and not owner of that category
    // TODO: this part has to be extended on imagefolder to support images in multiple categories..
    if ( isset( $Action ) && $Action == "Update" && eZObjectPermission::hasPermission( $ParentID, "imagecatalogue_category", 'w' ) == false
    && $parent->user( false ) != $user->id() )
    {
        // recursivly edit permissions on all file and folders...
        $categories = array();
        $categories[] = $category; // set permission on self.
        $images = array();
        getImagesAndCategories( $categories, $images, $category );

        // set permissions on all these files and dirs..
        foreach ( $categories as $categoryItem )
        {
            eZObjectPermission::removePermissions( $categoryItem->id(), "imagecatalogue_category", "wru" ); // no permissions
            eZObjectPermission::setPermission( -1, $categoryItem->id(), "imagecatalogue_category", 'r' );
            eZObjectPermission::setPermission( -1, $categoryItem->id(), "imagecatalogue_category", 'u' );
            $categoryItem->setUser( $parent->user() );
            $categoryItem->store();
        }
        foreach ( $images as $imageItem )
        {
            eZObjectPermission::removePermissions( $imageItem->id(), "imagecatalogue_image", "wr" ); // no read/write
            eZObjectPermission::setPermission( -1, $imageItem->id(), "imagecatalogue_image", 'r' );
            $imageItem->setUser( $parent->user() );
            $imageItem->store();
        }
    }
    
    eZHTTPTool::header( "Location: /imagecatalogue/image/list/$ParentID" );
    exit();
}

// Delete the selected categories.
if ( isset( $Action ) && $Action == "Delete" && $error == false )
{
    if ( count ( $CategoryArrayID ) > 0 )
    {
        foreach ( $CategoryArrayID as $CategoryID )
        {
            $category = new eZImageCategory( $CategoryID );
            $category->delete();
        }
    }
}
    
// Insert default values when creating a new category.
if (isset( $Action ) &&  $Action == "New" || $error )
{
    $t->set_var( "action_value", "insert" );
    $t->set_var( "category_id", "" );
    $readGroupArrayID[0] = -1;
    $writeGroupArrayID[0] = -1;
    $uploadGroupArrayID[0] = -1;
}

// Insert the category values when editing.
if ( isset( $Action ) && $Action == "Edit" )
{
    $category = new eZImageCategory( $CategoryID );

    $t->set_var( "category_name", $category->name() );
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_description", $category->description() );

    $parent =& $category->parent();
    $sectionID = $category->sectionID();
    if ( $parent )
        $CurrentCategoryID = $parent->id();

    $t->set_var( "action_value", "update" );

    $readGroupArrayID =& eZObjectPermission::getGroups( $category->id(), "imagecatalogue_category", "r", false );

    $writeGroupArrayID =& eZObjectPermission::getGroups( $category->id(), "imagecatalogue_category", "w", false );
    $uploadGroupArrayID =& eZObjectPermission::getGroups( $category->id(), "imagecatalogue_category", "u", false );
}

// Print the sections.

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




// Print out all the groups.
$groups = eZUserGroup::getAll();
foreach ( $groups as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", $group->name() );

    $t->set_var( "is_write_selected1", "" );
    $t->set_var( "is_read_selected1", "" );
    $t->set_var( "is_upload_selected1", "" );

    if ( in_array( $group->id(), $readGroupArrayID ) )
    {
        $t->set_var( "is_read_selected1", "selected" );
    }
    elseif ( in_array( -1, $readGroupArrayID ) )
    {
        $t->set_var( "read_everybody", "selected" );
    }
    else
    {
        $t->set_var( "is_read_selected1", "" );
    }
    $t->parse( "read_group_item", "read_group_item_tpl", true );

    if ( in_array( $group->id(), $writeGroupArrayID ) )
    {
        $t->set_var( "is_write_selected1", "selected" );
    }
    elseif ( in_array( -1, $writeGroupArrayID ) )
    {
        $t->set_var( "write_everybody", "selected" );
    }
    else
    {
        $t->set_var( "is_write_selected1", "" );
    }
    $t->parse( "write_group_item", "write_group_item_tpl", true );

    if ( in_array( $group->id(), $uploadGroupArrayID ) )
    {
        $t->set_var( "is_upload_selected1", "selected" );
    }
    elseif ( in_array( -1, $uploadGroupArrayID ) )
    {
        $t->set_var( "upload_everybody", "selected" );
    }
    else
    {
        $t->set_var( "is_upload_selected1", "" );
    }
    $t->parse( "upload_group_item", "upload_group_item_tpl", true );
}

$category = new eZImageCategory() ;

$categoryList =& $category->getTree( );

if ( count ( $categoryList ) == 0 )
{
    $t->set_var( "value", "" );
}

// Print out the categories.
foreach ( $categoryList as $categoryItem )
{
    if ( eZObjectPermission::hasPermission( $categoryItem[0]->id(), "imagecatalogue_category", 'w' ) ||
         eZObjectPermission::hasPermission( $categoryItem[0]->id(), "imagecatalogue_category", 'u' ) ||
         eZImageCategory::isOwner( eZUser::currentUser(), $categoryItem[0]->id() ) )
    {
        $t->set_var( "option_name", $categoryItem[0]->name() );
        $t->set_var( "option_value", $categoryItem[0]->id() );

        if ( $categoryItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $categoryItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->set_var( "is_selected", "" );

        if ( $CurrentCategoryID != 0 )
        {
            if ( $categoryItem[0]->id() == $CurrentCategoryID )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );
            }
        }
    
        $t->parse( "value", "value_tpl", true );
    }
}

$t->pparse( "output", "category_edit_tpl" );

/******* FUNCTIONS ****************************/
function changePermissions( $objectID, $groups , $permission )
{
    eZObjectPermission::removePermissions( $objectID, "imagecatalogue_category", $permission );
    if ( count( $groups ) > 0 )
    {
        foreach ( $groups as $groupItem )
        {
            if ( $groupItem == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $groupItem );
            
            eZObjectPermission::setPermission( $group, $objectID, "imagecatalogue_category", $permission );
        }
    }

}
// get all the images and categories of a category recursivly.
function getImagesAndCategories( &$folderArray, &$fileArray, $fromFolder )
{
    $result = eZImageCatalogue::getByParent( $fromFolder );
    $folderArray = array_merge( $result, $folderArray );
    $files = $fromFolder->images();
    $fileArray = array_merge( $files, $fileArray );
    
    foreach ( $result as $child )
    {
        getImagesAndCategories( $folderArray, $fileArray, $child );
    }
}

?>