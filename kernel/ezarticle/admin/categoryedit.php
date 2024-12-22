<?php
//
// $Id: categoryedit.php 9801 2003-04-10 08:14:20Z br $
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

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezcachefile.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

// include_once( "ezarticle/classes/ezarticlegenerator.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );

$error = false;
$permissionError = false;

if ( isset( $Action ) && $Action == "new" )
{
    $Name = '';
    $Description = '';
    $parentID = '';
    $CategoryID = '';
    $sectionID = '';
    $ListLimit = '';
    $editorGroupID = '';
}

if ( ( isset( $Action ) && $Action == "insert" ) || ( isset( $Action ) && $Action == "update" ) )
{
    if ( ( $ParentID == 0 ) && ( eZPermission::checkPermission( $user, "eZArticle", "WriteToRoot" ) == false ) )
    {
        $permissionError = true;
        $error = true;
    }
}

// Get images from the image browse function.
if ( ( isset( $AddImages ) ) and ( is_numeric( $CategoryID ) ) and ( is_numeric( $ImageID ) ) )
{
    $image = new eZImage( $ImageID );
    $category = new eZArticleCategory( $CategoryID );
    $category->setImage( $image );
    $category->store();
    $Action = "edit";
}

// Direct actions
if ( $Action == "insert" && !$error )
{
    // clear the menu cache
    $files = eZCacheFile::files( "ezarticle/cache/",
                                 array( "menubox", NULL, NULL, NULL ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }


    $category = new eZArticleCategory();
    $category->setName( $Name );

    $parentCategory = new eZArticleCategory();
    if ( $parentCategory->get( $ParentID ) == true )
    {
        $category->setParent( $parentCategory );
    }

    //EP: CategoryDescriptionXML=enabled, description go in XML -------------------
    if ( $ini->read_var( "eZArticleMain", "CategoryDescriptionXML" ) == "enabled" )
    {

        $generator = new eZArticleGenerator();
        $desc1 = array( $Description, "" );
        $desc2 = $generator->generateXML( $desc1 );
        $category->setDescription( $desc2 );
    }
    else
    {
	$category->setDescription( $Description );
    }
    //EP --------------------------------------------------------------------------

    $category->setSectionID( $SectionID );
    $category->setEditorGroup( $EditorGroupID );
    $category->setListLimit( $ListLimit );
    $category->setSortMode( $SortMode );

    if ( $ExcludeFromSearch == "on" )
    {
        $category->setExcludeFromSearch( true );
    }
    else
    {
        $category->setExcludeFromSearch( false );
    }

    $file = new eZImageFile();
    if ( $file->getUploadedFile( "ImageFile" ) )
    {
        $image = new eZImage();
        $image->setName( "Image" );
        $image->setImage( $file );

        $image->store();
        $category->setImage( $image );
    }

    $category->setOwner( eZUser::currentUser() );

    $category->store();
    $categoryID = $category->id();

    if ( isset( $BulkMailID ) && $BulkMailID != -1 )
        $category->setBulkMailCategory( $BulkMailID );
    else
        $category->setBulkMailCategory( false );

    if ( $DeleteImage == "on" )
        $category->setImage( 0 );

    /* write access select */
    if ( isset( $WriteGroupArray ) )
    {
        if ( $WriteGroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $categoryID, "article_category", 'w' );
        }
        else
        {
            eZObjectPermission::removePermissions( $categoryID, "article_category", 'w' );
            foreach ( $WriteGroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $categoryID, "article_category", 'w' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $categoryID, "article_category", 'w' );
    }

    /* read access thingy */
    if ( isset( $GroupArray ) )
    {
        if ( $GroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $categoryID, "article_category", 'r' );
        }
        else // some groups are selected.
        {
            eZObjectPermission::removePermissions( $categoryID, "article_category", 'r' );
            foreach ( $GroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $categoryID, "article_category", 'r' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $categoryID, "article_category", 'r' );
    }

    $files =& eZCacheFile::files( "ezarticle/cache/",
                                  array( "articlelist", $ParentID, NULL ),
                                  "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }

    if ( isset( $Browse ) )
    {
        $session = eZSession::globalSession();
        $session->setVariable( "SelectImages", "single" );
        $session->setVariable( "ImageListReturnTo", "/article/categoryedit/edit/$categoryID/" );
        $session->setVariable( "NameInBrowse", $category->name() );
        eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
        exit();
    }

    eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
    exit();
}

if ( isset( $Action ) && $Action == "update" && !$error )
{
    // clear the menu cache
    $files = eZCacheFile::files( "ezarticle/cache/",
                                 array( NULL, NULL, NULL, NULL ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }

    $category = new eZArticleCategory();
    $category->get( $CategoryID );
    $category->setName( $Name );

    $parentCategory = new eZArticleCategory();
    if ( $parentCategory->get( $ParentID ) == true )
    {
        $category->setParent( $parentCategory );
    }
    else if ( $ParentID == 0 )
    {
        $category->setParent( 0 );
    }

    //EP: CategoryDescriptionXML=enabled, description go in XML -------------------
    if ( $ini->read_var( "eZArticleMain", "CategoryDescriptionXML" ) == "enabled" )
    {

//    $category->setDescription( htmlspecialchars ($Description) );
//    $category->setDescription( $Description );

        $generator = new eZArticleGenerator();
        $desc1 = array( $Description, "" );
        $desc2 = $generator->generateXML( $desc1 );

        $category->setDescription( $desc2 );
    }
    else
    {
        $category->setDescription( $Description );
    }
    //EP --------------------------------------------------------------------------

    $category->setSectionID( $SectionID );
    $category->setEditorGroup( $EditorGroupID );
    $category->setListLimit( $ListLimit );

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

    if ( $ExcludeFromSearch == "on" )
    {
        $category->setExcludeFromSearch( true );
    }
    else
    {
        $category->setExcludeFromSearch( false );
    }


//    $ownerGroup = new eZUserGroup( $OwnerID );
//    if ( isset( $Recursive ) )
//        $category->setOwnerGroup( $ownerGroup, true );
//    else
//        $category->setOwnerGroup( $ownerGroup, false );

    $categoryID = $category->id();
    /* write access select */
    eZObjectPermission::removePermissions( $categoryID, "article_category", 'w' ); //not really necessary..
    if ( isset( $WriteGroupArray ) )
    {
        if ( $WriteGroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $categoryID, "article_category", 'w' );
        }
        else
        {
            foreach ( $WriteGroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $categoryID, "article_category", 'w' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $categoryID, "article_category", 'w' );
    }

    /* read access thingy */
    eZObjectPermission::removePermissions( $categoryID, "article_category", 'r' );
    if ( isset( $GroupArray ) )
    {
        if ( $GroupArray[0] == 0 )
        {
            eZObjectPermission::setPermission( -1, $categoryID, "article_category", 'r' );
        }
        else // some groups are selected.
        {
            foreach ( $GroupArray as $groupID )
            {
                eZObjectPermission::setPermission( $groupID, $categoryID, "article_category", 'r' );
            }
        }
    }
    else
    {
        eZObjectPermission::removePermissions( $categoryID, "article_category", 'r' );
    }

    $category->store();

    if ( isset( $BulkMailID ) && $BulkMailID != -1 )
        $category->setBulkMailCategory( $BulkMailID );
    else
        $category->setBulkMailCategory( false );


    $categoryID = $category->id();
    $files =& eZCacheFile::files( "ezarticle/cache/",
                                  array( "articlelist", array( $CategoryID, $ParentID ), NULL ),
                                  "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }

    if ( isset( $Browse ) )
    {
        $session = eZSession::globalSession();
        $session->setVariable( "SelectImages", "single" );
        $session->setVariable( "ImageListReturnTo", "/article/categoryedit/edit/$categoryID/" );
        eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
        exit();
    }

    eZHTTPTool::header( "Location: /article/archive/$ParentID/" );
    exit();
}

if ( $Action == "delete" )
{
    // clear the menu cache
    $files = eZCacheFile::files( "ezarticle/cache/",
                                 array( "menubox", NULL, NULL, NULL ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }

    $category = new eZArticleCategory();
    $category->get( $CategoryID );

    $files =& eZCacheFile::files( "ezarticle/cache/",
                                  array( "articlelist",
                                         array( $CategoryID, $category->parent( false ) ), NULL ),
                                  "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }

    $category->delete();

    eZHTTPTool::header( "Location: /article/archive/" );
    exit();
}

$t = new eZTemplate( "kernel/ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "kernel/ezarticle/admin/intl/", $Language, "categoryedit.php" );

$t->setAllStrings();

$t->set_file( "category_edit_tpl", "categoryedit.tpl" );


$t->set_block( "category_edit_tpl", "value_tpl", "value" );
$t->set_block( "category_edit_tpl", "category_owner_tpl", "category_owner" );
$t->set_block( "category_edit_tpl", "group_item_tpl", "group_item" );
$t->set_block( "category_edit_tpl", "editor_group_item_tpl", "editor_group_item" );
$t->set_block( "category_edit_tpl", "bulkmail_category_item_tpl", "bulkmail_category_item" );
$t->set_block( "category_edit_tpl", "section_item_tpl", "section_item" );
$t->set_block( "category_edit_tpl", "error_permission_tpl", "error_permission" );

$t->set_block( "category_edit_tpl", "image_item_tpl", "image_item" );

$category = new eZArticleCategory();

$categoryArray = $category->getAll( );

$t->set_var( "description_value", isset( $Description ) ? $Description : false );

$t->set_var( "name_value", isset( $Name ) ? $Name : false );
$t->set_var( "list_limit_value", isset( $ListLimit ) ? $ListLimit : false );
$t->set_var( "action_value", "insert" );
$t->set_var( "exclude_checked", "" );
$t->set_var( "all_selected", "selected" );
$t->set_var( "all_write_selected", "selected" );
$t->set_var( "bulkmail_category_item", "" );
$t->set_var( "no_bulkmail_selected", "selected" );

$t->set_var( "image_item", "" );
$t->set_var( "category_id", "" );

$writeGroupsID = array();
$readGroupsID = array();

if ( $permissionError )
    $t->parse( "error_permission", "error_permission_tpl" );
else
    $t->set_var( "error_permission", "" );

$t->set_var( "1_selected", "" );
$t->set_var( "2_selected", "" );
$t->set_var( "3_selected", "" );
$t->set_var( "4_selected", "" );
$t->set_var( "5_selected", "" );

// edit
if ( $Action == "edit" )
{
    $category = new eZArticleCategory();
    $category->get( $CategoryID );

    $t->set_var( "name_value", $category->name() );
    $t->set_var( "list_limit_value", $category->listLimit() ? $category->listLimit() : "" );

    //EP: CategoryDescriptionXML=enabled, description go in XML -------------------------
    if ( $ini->read_var( "eZArticleMain", "CategoryDescriptionXML" ) == "enabled" )
    {
        $generator = new eZArticleGenerator();
        $desc1 = $generator->decodeXML( $category->description( false ) );
        $t->set_var( "description_value", $desc1[0]  );
    }
    else
    {
        $t->set_var( "description_value", $category->description() );
    }
    //EP --------------------------------------------------------------------------------

    $t->set_var( "action_value", "update" );
    $t->set_var( "category_id", $category->id() );
    $parent = $category->parent();

    $sectionID = $category->sectionID();

    // set the current sortmode to selected
    $t->set_var( $category->sortMode( true ) . "_selected", "selected" );

    $image =& $category->image();
    if ( is_a( $image, "eZImage" ) && $image->id() != 0 )
    {
        $imageWidth =& $ini->read_var( "eZArticleMain", "CategoryImageWidth" );
        $imageHeight =& $ini->read_var( "eZArticleMain", "CategoryImageHeight" );

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

    if ( is_object( $parent ) )
    {
        $parentID = $parent->id();
    }

    if ( $category->excludeFromSearch() == true )
    {
        $t->set_var( "exclude_checked", "checked" );
    }

    $writeGroupsID = eZObjectPermission::getGroups( $CategoryID, "article_category", 'w' , false );
    $readGroupsID = eZObjectPermission::getGroups( $CategoryID, "article_category", 'r', false );

    $editorGroupID = $category->editorGroup( false );

    if ( $writeGroupsID[0] != -1 )
        $t->set_var( "all_write_selected", "" );
    if ( $readGroupsID[0] != -1 )
        $t->set_var( "all_selected", "" );

    // check bulkmail dep
    $bulkMailCategoryID = $category->bulkMailCategory( false );
    if ( $category == false )
        $t->set_var( "no_bulkmail_selected", "selected" );
    else
        $t->set_var( "no_bulkmail_selected", "" );

}

$category = new eZArticleCategory();

$tree = $category->getTree();

foreach ( $tree as $item )
{
    if ( eZObjectPermission::hasPermission( $item[0]->id(), "article_category", 'w' ) && $CategoryID != $item[0]->id() )
    {
        $t->set_var( "option_value", $item[0]->id() );
        $t->set_var( "option_name", $item[0]->name() );

        if ( $item[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $item[1] ) );
        else
            $t->set_var( "option_level", "" );

        if ( isset( $parentID ) && $item[0]->id() == $parentID )
        {
            $t->set_var( "selected", "selected" );
            $selected = true;
        }
        else
        {
            $t->set_var( "selected", "" );
        }

        $t->parse( "value", "value_tpl", true );
    }
}

// group selector
$group = new eZUserGroup();
$groupList = $group->getAll();

$t->set_var( "selected", "" );
foreach ( $groupList as $groupItem )
{
    /* for the group owner selector */
    $t->set_var( "module_owner_id", $groupItem->id() );
    $t->set_var( "module_owner_name", $groupItem->name() );

    if ( in_array( $groupItem->id(), $writeGroupsID ) )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );

    $t->parse( "category_owner", "category_owner_tpl", true );

    /* for the read access groups selector */
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_id", $groupItem->id() );

    if ( in_array( $groupItem->id() , $readGroupsID ) )
        $t->set_var( "selected", "selected" );
    else
        $t->set_var( "selected", "" );

    $t->parse( "group_item", "group_item_tpl", true );

    /* for the editor groups selector */
    $t->set_var( "editor_group_name", $groupItem->name() );
    $t->set_var( "editor_group_id", $groupItem->id() );

    if ( $editorGroupID != 0 )
    {
        if ( $groupItem->id() == $editorGroupID )
            $t->set_var( "editor_selected", "selected" );
        else
            $t->set_var( "editor_selected", "" );
    }
    else
        $t->set_var( "no_selected", "selected" );

    $t->parse( "editor_group_item", "editor_group_item_tpl", true );
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

// bulkmail selector
$categories = eZBulkMailCategory::getAll();
foreach ( $categories as $categoryItem )
{
    $t->set_var( "bulkmail_category_id", $categoryItem->id() );
    $t->set_var( "bulkmail_category_name", $categoryItem->name() );

    if ( isset( $bulkMailCategoryID ) && $bulkMailCategoryID == $categoryItem->id() )
        $t->set_var( "bulkmail_selected", "selected" );
    else
        $t->set_var( "bulkmail_selected", "" );

    $t->parse( "bulkmail_category_item", "bulkmail_category_item_tpl", true );
}

$t->pparse( "output", "category_edit_tpl" );
?>