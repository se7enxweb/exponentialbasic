<?php
//
// $Id: mediaedit.php 9823 2003-06-02 08:00:11Z jhe $
//
// Created on: <24-Jul-2001 13:35:07 ce>
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
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezuser/classes/ezauthor.php" );

// include_once( "ezmediacatalogue/classes/ezmedia.php" );
// include_once( "ezmediacatalogue/classes/ezmediacategory.php" );
// include_once( "ezmediacatalogue/classes/ezmediatype.php" );

$user =& eZUser::currentUser();

$CurrentCategoryID = eZHTTPTool::getVar( "CategoryID" );
$CategoryID = eZHTTPTool::getVar( "CategoryID" );

if ( !$user )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

if ( isset( $NewCategory ) )
{
    eZHTTPTool::header( "Location: /mediacatalogue/category/new/$CurrentCategoryID/" );
    exit();
}

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /mediacatalogue/media/list/" . $CurrentCategoryID . "/" );
    exit();
}

if ( isset( $DeleteMedia ) )
{
    $Action = "DeleteMedia";
}

if ( isset( $DeleteCategories ) )
{
    $Action = "DeleteCategories";
}

if ( isset( $Action ) && $Action == "New" )
{
    $Name = false;
    $Description = false;
    $Caption = false;
    $mediaType = false;
    $readGroupArrayID[0] = -1;
    $writeGroupArrayID[0] = -1;
    $uploadGroupArrayID[0] = -1;
}

if ( isset( $Action ) && $Action == "Edit" )
{
    $Name = false;
    $Description = false;
    $Caption = false;
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );

// // include_once( "classes/ezfile.php" );
// include_once( "classes/ezmediafile.php" );

// include_once( "ezmediacatalogue/classes/ezmedia.php" );
// include_once( "ezmediacatalogue/classes/ezmediacategory.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZMediaCatalogueMain", "Language" );

$t = new eZTemplate( "kernel/ezmediacatalogue/admin/" . $ini->variable( "eZMediaCatalogueMain", "TemplateDir" ),
                     "kernel/ezmediacatalogue/admin/intl/", $Language, "mediaedit.php" );

$t->setAllStrings();

$t->set_file( "media_edit_page", "mediaedit.tpl" );

$t->set_block( "media_edit_page", "value_tpl", "value" );
$t->set_block( "media_edit_page", "multiple_value_tpl", "multiple_value" );
$t->set_block( "media_edit_page", "errors_tpl", "errors" );

$t->set_block( "media_edit_page", "write_group_item_tpl", "write_group_item" );
$t->set_block( "media_edit_page", "read_group_item_tpl", "read_group_item" );

$t->set_block( "media_edit_page", "photographer_item_tpl", "photographer_item" );

$t->set_block( "media_edit_page", "type_tpl", "type" );

$t->set_block( "media_edit_page", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );


$t->set_var( "errors", "&nbsp;" );

$t->set_var( "name_value", "$Name" );
$t->set_var( "media_description", "$Description" );
$t->set_var( "caption_value", "$Caption" );

$error = false;
$nameCheck = true;
$captionCheck = false;
$descriptionCheck = false;
$fileCheck = true;
$permissionCheck = false;

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_caption_tpl", "error_caption" );
$t->set_var( "error_caption", "&nbsp;" );

$t->set_block( "errors_tpl", "error_file_upload_tpl", "error_file_upload" );
$t->set_var( "error_file_upload", "&nbsp" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

$t->set_block( "errors_tpl", "error_read_everybody_permission_tpl", "error_read_everybody_permission" );
$t->set_var( "error_read_everybody_permission", "&nbsp;" );

$t->set_block( "errors_tpl", "error_write_everybody_permission_tpl", "error_write_everybody_permission" );
$t->set_var( "error_write_everybody_permission", "&nbsp;" );

// Check for errors when inserting or updating.
if ( isset( $Action ) && $Action == "Insert" || isset( $Action ) && $Action == "Update" )
{
    if ( $nameCheck )
    {
        if ( empty( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }

    if ( $captionCheck )
    {
        if ( empty( $Caption ) )
        {
            $t->parse( "error_caption", "error_caption_tpl" );
            $error = true;
        }
    }

    if ( $descriptionCheck )
    {
        if ( empty( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }

    if ( $permissionCheck )
    {
        if ( empty( $ReadGroupArrayID ) )
        {
            $t->parse( "error_read_everybody_permission", "error_read_everybody_permission_tpl" );
            $error = true;
        }
        if ( empty( $WriteGroupArrayID ) )
        {
            $t->parse( "error_write_everybody_permission", "error_write_everybody_permission_tpl" );
            $error = true;
        }

    }


    if ( $fileCheck )
    {
        $file = new eZMediaFile();
        if ( $file->getUploadedFile( "userfile" ) )
        {
            $mediaTest = new eZMedia();
            if ( $mediaTest->checkMedia( $file ) && $mediaTest->setMedia( $file ) )
            {
                $fileOK = true;
            }
            else
            {
                $error = true;
                $t->parse( "error_file_upload", "error_file_upload_tpl" );
            }
        }
        else
        {
            if ( $Action == "Insert" )
            {
                $error = true;
                $t->parse( "error_file_upload", "error_file_upload_tpl" );
            }
        }
    }

    if ( $error )
    {
        $t->parse( "errors", "errors_tpl" );
        if ( count( $WriteGroupArrayID ) != 0 )
        {
            foreach ( $WriteGroupArrayID as $unf )
            {
                if ( $unf == 0 )
                    $writeGroupArrayID[] = -1;
                else
                    $writeGroupArrayID[] = $unf;
            }
        }

        if ( count( $ReadGroupArrayID ) != 0 )
        {
            foreach ( $ReadGroupArrayID as $unf )
            {
                if ( $unf == 0 )
                    $readGroupArrayID[] = -1;
                else
                    $readGroupArrayID[] = $unf;
            }
        }
    }
}

// Insert if error == false
if ( ( isset( $Action ) && $Action == "Insert" || isset( $Action ) && $Action == "Update" ) && $error == false )
{
    $media = new eZMedia( $MediaID );
    $media->setName( $Name );
    $media->setCaption( $Caption );
    $media->setDescription( $Description );
    $media->setUser( $user );

    if ( $fileOK )
    {
        $media->setMedia( $file );
    }

    if ( trim( $NewCreatorName ) != "" &&
         trim( $NewCreatorEmail ) != "" )
    {
        $author = new eZAuthor();
        $author->setName( $NewCreatorName );
        $author->setEmail( $NewCreatorEmail );
        $author->store();
        $media->setPhotographer( $author );
    }
    else
    {
        $media->setPhotographer( $PhotoID );
    }

    $media->store();

    if ( $TypeID == -1 )
    {
        $media->removeType();
    }
    else
    {
        $media->removeType();

        $media->setType( new eZMediaType( $TypeID ) );

        $i = 0;
        if ( count( $AttributeValue ) > 0 )
        {
            foreach ( $AttributeValue as $attribute )
            {
                $att = new eZMediaAttribute( $AttributeID[$i] );

                $att->setValue( $media, $attribute );

                $i++;
            }
        }
    }

    if ( count( $ReadGroupArrayID ) > 0 )
    {
        foreach ( $ReadGroupArrayID as $Read )
        {
            if ( $Read == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $Read );

            eZObjectPermission::setPermission( $group, $media->id(), "mediacatalogue_media", "r" );
        }
    }

    if ( count( $WriteGroupArrayID ) > 0 )
    {
        foreach ( $WriteGroupArrayID as $Write )
        {
            if ( $Write == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $Write );

            eZObjectPermission::setPermission( $group, $media->id(), "mediacatalogue_media", "w" );
        }
    }

    if ( isset( $Action ) && $Action == "Insert" )
    {
        $category = new eZMediaCategory( $CategoryID );

        $media->setCategoryDefinition( $category );

        $categories = array_unique( array_merge( $CategoryArray, $CategoryID ) );

        foreach ( $categories as $categoryItem )
        {
            eZMediaCategory::addMedia( $media, $categoryItem );
        }
        eZPBLog::writeNotice( "Video added to catalogue: $media->name() from IP: $REMOTE_ADDR" );
    }
    elseif ( isset( $Action ) && $Action == "Update" )
    {
        $categoryArray = $media->categories();
        // Calculate new and unused categories
        $old_maincategory = $media->categoryDefinition();

        if ( $old_maincategory > -1 )
            $old_categories =& array_unique( array_merge( $old_maincategory->id(),
                                                          $media->categories( false ) ) );

        $new_categories = array_unique( array_merge( $CategoryID, $CategoryArray ) );

        $category = new eZMediaCategory( $CategoryID );
        $media->setCategoryDefinition( $category );

        $remove_categories = array_diff( $old_categories, $new_categories );
        $add_categories = array_diff( $new_categories, $old_categories );

        foreach ( $remove_categories as $categoryItem )
        {
            eZMediaCategory::removeMedia( $media, $categoryItem );
        }
        foreach ( $add_categories as $categoryItem )
        {
            eZMediaCategory::addMedia( $media, $categoryItem );
        }
    }

    if ( !isset( $Update ) )
    {
        eZHTTPTool::header( "Location: /mediacatalogue/media/list/" . $CategoryID . "/" );
        exit();
    }
    else
    {
        //$Action = "Edit";
        $MediaID = $media->id();
    }
}

// Delete an media
if ( isset( $Action ) && $Action == "DeleteMedia" )
{
    if ( count( $MediaArrayID ) != 0 )
    {
        foreach ( $MediaArrayID as $MediaID )
        {
            $media = new eZMedia( $MediaID );
            $media->delete();
        }
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /mediacatalogue/media/list/" . $CurrentCategoryID . "/" );
    exit();
}

// Delete a category
if ( isset( $Action ) && $Action == "DeleteCategories" )
{
    if ( count( $CategoryArrayID ) > 0 )
    {
        foreach ( $CategoryArrayID as $categoryID )
        {
            $category = new eZMediaCategory( $categoryID );
            $category->delete();
        }
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /mediacatalogue/media/list/" . $CurrentCategoryID . "/" );
    exit();
}

// Set the default values to null
if ( isset( $Action ) && $Action == "New" || $error )
{
    $t->set_var( "action_value", "Insert" );
    $t->set_var( "media", "" );
    $t->set_var( "media_id", "" );

// author select

    $author = new eZAuthor();
    $authorArray = $author->getAll();
    foreach ( $authorArray as $author )
    {
        $t->set_var( "photo_id", $author->id() );
        $t->set_var( "photo_name", $author->name() );
        $t->parse( "photographer_item", "photographer_item_tpl", true );
    }

}

// Sets the values to the current media
if ( isset( $Action ) && $Action == "Edit" )
{
    $media = new eZMedia( $MediaID );

    $t->set_var( "media_id", $media->id() );
    $t->set_var( "name_value", $media->name() );
    $t->set_var( "caption_value", $media->caption() );
    $t->set_var( "media_description", $media->description() );
    $t->set_var( "action_value", "update" );

    $t->set_var( "media_alt", $media->caption() );

    $photographer = $media->photographer();
    $PhotographerID = $photographer->id();

// author select

    $author = new eZAuthor();
    $authorArray = $author->getAll();
    foreach ( $authorArray as $author )
    {
        if ( $PhotographerID == $author->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
        $t->set_var( "photo_id", $author->id() );
        $t->set_var( "photo_name", $author->name() );
        $t->parse( "photographer_item", "photographer_item_tpl", true );
    }

    $mediaType = $media->type();

    $objectPermission = new eZObjectPermission();

    $readGroupArrayID =& $objectPermission->getGroups( $media->id(), "mediacatalogue_media", "r", false );
    $writeGroupArrayID =& $objectPermission->getGroups( $media->id(), "mediacatalogue_media", "w", false );
}


$category = new eZMediaCategory() ;
$categoryList =& $category->getTree( );

$tree = new eZMediaCategory();
$treeArray =& $tree->getTree();
$user =& eZUser::currentUser();

$catCount = count( $treeArray );
$t->set_var( "num_select_categories", min( $catCount, 10 ) );

foreach ( $treeArray as $catItem )
{
    if ( eZObjectPermission::hasPermission( $catItem[0]->id(), "media_category", 'w', $user ) == true  ||
         eZMediaCategory::isOwner( eZUser::currentUser(), $catItem[0]->id() ) )
    {
        if ( $Action == "Edit" )
        {
            $defCat = $media->categoryDefinition();

            if ( is_a( $defCat, "eZMediaCategory" ) )
            {
                if ( $media->existsInCategory( $catItem[0] ) &&
                ( $defCat->id() != $catItem[0]->id() ) )
                {
                    $t->set_var( "multiple_selected", "selected" );
                }
                else
                {
                    $t->set_var( "multiple_selected", "" );
                }
            }
            else
            {
                $t->set_var( "multiple_selected", "" );
            }

            if ( is_a( $defCat, "eZMediaCategory" ) )
            {
                if ( $defCat->id() == $catItem[0]->id() )
                {
                    $t->set_var( "selected", "selected" );
                }
                else
                {
                    $t->set_var( "selected", "" );
                }
            }
            else
            {
                $t->set_var( "selected", "" );
            }
        }
        else
        {
            if ( $CategoryID == $catItem[0]->id() )
                $t->set_var( "selected", "selected" );
            else
                $t->set_var( "selected", "" );

            $t->set_var( "multiple_selected", "" );
        }


        $t->set_var( "option_value", $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );

        if ( $catItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
        else
            $t->set_var( "option_level", "" );


        $t->parse( "value", "value_tpl", true );
        $t->parse( "multiple_value", "multiple_value_tpl", true );
    }
}

// Print out all the types.
$type = new eZMediaType();
$types = $type->getAll();

if ( isset( $TypeID ) )
    $mediaType = new eZMediaType( $TypeID );

foreach ( $types as $typeItem )
{
    if ( is_a( $mediaType, "eZMediaType" ) )
    {
        if ( $mediaType->id() == $typeItem->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->set_var( "type_id", $typeItem->id( ) );
    $t->set_var( "type_name", $typeItem->name( ) );

    $t->parse( "type", "type_tpl", true );
}

if ( is_a( $mediaType, "eZMediaType") )
{
    $attributes = $mediaType->attributes();

    foreach ( $attributes as $attribute )
    {
        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );

        if ( !$attribute->value( $media ) )
            $t->set_var( "attribute_value", $attribute->defaultValue() );
        else
            $t->set_var( "attribute_value", $attribute->value( $media ) );

        $t->parse( "attribute", "attribute_tpl", true );
    }
}

if ( isset( $attributes ) && count( $attributes ) > 0 || !isset( $type ) )
{
    $t->parse( "attribute_list", "attribute_list_tpl" );
}
else
{
    $t->set_var( "attribute_list", "" );
}

// Print out all the groups.
$groups =& eZUserGroup::getAll();
foreach ( $groups as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", $group->name() );

    $t->set_var( "is_write_selected1", "" );
    $t->set_var( "is_read_selected1", "" );

    if ( $readGroupArrayID )
    {
        foreach ( $readGroupArrayID as $readGroup )
        {
            if ( $readGroup == $group->id() )
            {
                $t->set_var( "is_read_selected1", "selected" );
            }
            elseif ( $readGroup == -1 )
            {
                $t->set_var( "read_everybody", "selected" );
            }
            else
            {
                $t->set_var( "is_read_selected", "" );
            }
        }
    }

    if ( isset( $Action ) && $Action == "new" )
        $t->set_var( "read_everybody", "selected" );
    $t->parse( "read_group_item", "read_group_item_tpl", true );

    if ( $writeGroupArrayID )
    {
        foreach ( $writeGroupArrayID as $writeGroup )
        {
            if ( $writeGroup == $group->id() )
            {
                $t->set_var( "is_write_selected1", "selected" );
            }
            elseif ( $writeGroup == -1 )
            {
                $t->set_var( "write_everybody", "selected" );
            }
            else
            {
                $t->set_var( "is_write_selected", "" );
            }
        }
    }

    $t->parse( "write_group_item", "write_group_item_tpl", true );
}

$t->pparse( "output", "media_edit_page" );

?>