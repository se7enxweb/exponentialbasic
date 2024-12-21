<?php
//
// $Id: categoryedit.php 8123 2001-10-31 12:06:39Z br $
//
// Created on: <26-Oct-2000 14:57:28 ce>
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
$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZLinkMain", "Language" );
$error = new INIFIle( "kernel/ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/ezcachefile.php" );

// include_once( "ezlink/classes/ezlinkcategory.php" );
// include_once( "ezlink/classes/ezlink.php" );
// include_once( "ezlink/classes/ezhit.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );

// include_once( "ezsitemanager/classes/ezsection.php" );


require( "kernel/ezuser/admin/admincheck.php" );

if ( isSet ( $DeleteCategories ) )
{
    $Action = "DeleteCategories";
}

// Get images from the image browse function.
if ( ( isSet ( $AddImages ) ) and ( is_numeric( $LinkCategoryID ) ) and ( is_numeric ( $LinkCategoryID ) ) )
{
    $image = new eZImage( $ImageID );
    $category = new eZLinkCategory( $LinkCategoryID );
    $category->setImage( $image );
    $category->update();
    $Action = "edit";
}

// Insert a category.

if ( isset( $Action ) && $Action == "insert" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezlink/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkCategoryAdd" ) )
    {
        if ( $Name != "" &&
        $ParentCategory != "" )
        {
            $category = new eZLinkCategory();

            $category->setName( $Name );
            $category->setDescription( $Description );
            $category->setSectionID( $SectionID );
            $category->setParent( $ParentCategory );
            $ttile = "";

            $file = new eZImageFile();
            if ( $file->getUploadedFile( "ImageFile" ) )
            {
                $image = new eZImage( );
                $image->setName( "Image" );
                $image->setImage( $file );

                $image->store();

                $category->setImage( $image );
            }
            else
            {
                $category->setImage( 0 );
            }

            $category->store();

            if ( isSet ( $Browse ) )
            {
                $categoryID = $category->id();

                $session =& eZSession::globalSession();
                $session->setVariable( "SelectImages", "single" );
                $session->setVariable( "ImageListReturnTo", "/link/categoryedit/edit/$categoryID/" );
                $session->setVariable( "NameInBrowse", $category->name() );
                eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
                exit();
            }
            eZHTTPTool::header( "Location: /link/category/". $ParentCategory );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
        eZHTTPTool::header( "Location: /link/norights" );
        exit();
    }
}

// Delete a category.
if ( isset( $Action ) && $Action == "delete" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezlink/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkCategoryDelete" ) )
    {
        $category = new eZLinkCategory();
        $category->get( $LinkCategoryID );
        $category->delete();

        eZHTTPTool::header( "Location: /link/category/" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

if ( isset( $Action ) && $Action == "DeleteCategories" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezlink/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkCategoryDelete" ) )
    {
        if ( count ( $CategoryArrayID ) != 0 )
        {
            foreach( $CategoryArrayID as $CategoryID )
            {
                $category = new eZLinkCategory();
                $category->get( $CategoryID );
                $parentID = $category->parent();
                $category->delete();
            }
            eZHTTPTool::header( "Location: /link/category/$parentID" );
            exit();
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

// Update a category.
if ( isset( $Action ) && $Action == "update" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezlink/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkCategoryModify" ) )
    {
        if ( $Name != "" &&
        $ParentCategory != "" )
        {
            $category = new eZLinkCategory();
            $category->get( $LinkCategoryID );
            $category->setName( $Name );
            $category->setDescription( $Description );
            $category->setSectionID( $SectionID );
            $category->setParent( $ParentCategory );

            $file = new eZImageFile();
            if ( $file->getUploadedFile( "ImageFile" ) )
            {
                $image = new eZImage( );
                $image->setName( "Image" );
                $image->setImage( $file );

                $image->store();

                $category->setImage( $image );
            }

            $category->update();

            if ( $DeleteImage )
            {
                $category->deleteImage();
            }

            if ( isSet ( $Browse ) )
            {
                $categoryID = $category->id();
                $session = eZSession::globalSession();
                $session->setVariable( "SelectImages", "single" );
                $session->setVariable( "ImageListReturnTo", "/link/categoryedit/edit/$categoryID/" );
                $session->setVariable( "NameInBrowse", $category->name() );
                eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
                exit();
            }

            eZHTTPTool::header( "Location: /link/category/$ParentCategory" );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

$t = new eZTemplate( "kernel/ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
                     "kernel/ezlink/admin/" . "/intl/", $Language, "categoryedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "category_edit" => "categoryedit.tpl"
    ));

$languageIni = new INIFIle( "kernel/ezlink/admin/intl/" . $Language . "/categoryedit.php.ini", false );
$headline = $languageIni->read_var( "strings", "headline_insert" );

$t->set_block( "category_edit", "section_item_tpl", "section_item" );
$t->set_block( "category_edit", "parent_category_tpl", "parent_category" );
$t->set_block( "category_edit", "image_item_tpl", "image_item" );
$t->set_block( "category_edit", "no_image_item_tpl", "no_image_item" );
$t->set_var( "category_id", "" );

$categoryselect = new eZLinkCategory();
$categoryLinkList = $categoryselect->getTree( );

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkCategoryAdd" ) )
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }

    $t->set_var( "image_item", "" );
    $t->set_var( "no_image_item", "" );
    $t->set_var( "category_name", "" );
    $t->set_var( "category_description", "" );
    $t->set_var( "category_id", "" );

    $t->set_var( "action_value", "insert" );

    $error_msg = false;
    $sectionID = false;
    $parentID = false;
}

// Modifing a category.
if ( $Action == "edit" )
{
    $languageIni = new INIFIle( "kernel/ezlink/admin/intl/" . $Language . "/categoryedit.php.ini", false );
    $headline = $languageIni->read_var( "strings", "headline_edit" );

    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkCategoryModify" ) )
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
    else
    {
        $linkCategory = new eZLinkCategory();
        $linkCategory->get( $LinkCategoryID );

        $parentID = $linkCategory->parent();
        $sectionID = $linkCategory->sectionID();

        $t->set_var( "category_name", $linkCategory->name() );
        $t->set_var( "category_description", $linkCategory->description() );
        $t->set_var( "category_id", $linkCategory->id() );

        $image =& $linkCategory->image();

        if ( is_a( $image, "eZImage" ) && $image->id() != 0 )
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
            $t->set_var( "no_image_item", "" );
            $t->parse( "image_item", "image_item_tpl" );
        }
        else
        {
            $t->parse( "no_image_item", "no_image_item_tpl" );
            $t->set_var( "image_item", "" );
        }

        $t->set_var( "action_value", "update" );
    }

}

// Selecter
$category_select_dict = "";
foreach( $categoryLinkList as $i => $categoryLinkItem )
{
    $t->set_var( "categorylink_id", $categoryLinkItem[0]->id() );
    $t->set_var( "categorylink_name", $categoryLinkItem[0]->name() );
    $t->set_var( "categorylink_parent", $categoryLinkItem[0]->parent() );

    if ( is_numeric( $parentID ) )
    {
        if ( $parentID == $categoryLinkItem[0]->id() )
        {
            $t->set_var( "is_selected", "selected" );
        }
        else
        {
            $t->set_var( "is_selected", "" );
        }
    }

    if ( $categoryLinkItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $categoryLinkItem[1] ) );
    else
        $t->set_var( "option_level", "" );


    $category_select_dict[ $categoryLinkItem[0]->id() ] = $i;

    $t->parse( "parent_category", "parent_category_tpl", true );
}


// Get all sections

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



$t->set_var( "headline", $headline );

$t->set_var( "error_msg", $error_msg );

$t->pparse( "output", "category_edit" );
?>