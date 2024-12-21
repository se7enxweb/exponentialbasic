<?php
//
// $Id: suggestlink.php 9517 2002-05-08 11:07:34Z vl $
//
// Created on: <26-Oct-2000 14:58:57 ce>
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
// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "classes/eztexttool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZLinkMain", "Language" );
$error = new INIFile( "kernel/ezlink/user/intl/" . $Language . "/suggestlink.php.ini", false );

// include_once( "classes/eztemplate.php" );

include( "ezlink/classes/ezlinkcategory.php" );
include( "ezlink/classes/ezlink.php" );
include( "ezlink/classes/ezhit.php" );

// include_once( "ezlink/classes/ezlinktype.php" );
// include_once( "ezlink/classes/ezlinkattribute.php" );
// include_once( "ezlink/classes/ezmeta.php" );

//require( "kernel/ezuser/admin/admincheck.php" );

if ( isSet( $DeleteLinks ) )
{
    $Action = "DeleteLinks";
}

if ( isSet( $Delete ) )
{
    $Action = "delete";
}

if ( isSet( $Back ) )
{
    if ( $LinkID != "" )
    {
        $link = new eZLink();
        $link->get( $LinkID );
        $catDef = $link->categoryDefinition();
        $LinkCategoryID = $catDef->id();

        eZHTTPTool::header( "Location: /link/category/$LinkCategoryID/" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: " . $_SERVER['HTTP_REFERER'] );
        exit();
    }
}

$Accepted = $ini->read_var( "eZLinkMain", "AcceptSuggestedLinks" );

// Get images from the image browse function.
if ( ( isSet( $AddImages ) ) and ( is_numeric( $LinkID ) ) and ( is_numeric( $LinkID ) ) )
{
    $image = new eZImage( $ImageID );
    $link = new eZLink( $LinkID );
    $link->setImage( $image );
    $link->update();
    $Action = "edit";
}

if ( isset( $GetSite ) && $GetSite )
{
    if ( $Url )
    {
        if ( !preg_match( "%^([a-z]+://)%", $Url ) )
            $real_url = "http://" . $Url;
        else
            $real_url = $Url;

        $metaList = fetchURLInfo( $real_url );

        if ( $metaList == false )
        {
            $error_msg = $error->read_var( "strings", "error_nosite" );
        }
        else if ( count( $metaList ) == 0 )
        {
            $error_msg = $error->read_var( "strings", "error_nometa" );
        }

        if ( $metaList["description"] )
            $tdescription = $metaList["description"];
        else
            $tdescription = $description;

        if ( $metaList["keywords"] )
            $tkeywords = $metaList["keywords"];
        else
            $tkeywords = $keywords;

        if ( $metaList["title"] )
            $tname = $metaList["title"];
        else if ( $metaList["abstract"] )
            $tname = $metaList["abstract"];
        else
            $tname = $Name;
//          $tdescription = $metaList["description"];
//          $tkeywords = $metaList["keywords"];
//          $tname = $Name;

        $turl = eZTextTool::htmlspecialchars( $Url );
    }

    $action_value = "insert";
    $Action = "new";

}

// Update a link.
if ( isset( $Action ) && $Action == "update" )
{
    if ( $Name != "" && $LinkCategoryID != "" && $Url != "" )
    {
        $link = new eZLink();
        $link->get( $LinkID );

        $link->setName( $Name );
        $link->setDescription( $Description );
        $link->setKeyWords( $Keywords );
        $link->setUrl( $Url );

        $link->setCategoryDefinition( $LinkCategoryID );

        // Calculate new and unused categories

        $old_maincategory = $link->categoryDefinition();
        $old_categories =& array_unique( array_merge( $old_maincategory->id(),
                                         $link->categories( false ) ) );

        $new_categories = array_unique( array_merge( $CategoryID, $CategoryArray ) );

        $remove_categories = array_diff( $old_categories, $new_categories );
        $add_categories = array_diff( $new_categories, $old_categories );

        $categoryIDArray = array();

        foreach ( $categoryArray as $cat )
        {
            $categoryIDArray[] = $cat->id();
        }

        foreach ( $remove_categories as $categoryItem )
        {
            eZLinkCategory::removeLink( $link, $categoryItem );
        }

        // add to categories
        $category = new eZLinkCategory( $LinkCategoryID );
        $link->setCategoryDefinition( $category );
        $category->addLink( $link );

        foreach ( $add_categories as $categoryItem )
        {
            eZLinkCategory::addLink( $link, $categoryItem );
        }

        if ( $Accepted == "1" && eZPermission::checkPermission( $user, "eZLink", "LinkModify" ) )
            $link->setAccepted( true );
        else
            $link->setAccepted( false );

        $link->setUrl( $Url );

        $file = new eZImageFile();
        if ( $file->getUploadedFile( "ImageFile" ) )
        {
     	    $image = new eZImage( );
            $image->setName( "LinkImage" );
            $image->setImage( $file );

            $image->store();

            $link->setImage( $image );
        }

        if ( $TypeID == -1 )
        {
            $link->removeType();
        }
        else
        {
            $link->removeType();

            $link->setType( new eZLinkType( $TypeID ) );

            $i = 0;
            if ( count( $AttributeValue ) > 0 )
            {
                foreach ( $AttributeValue as $attribute )
                {
                    $att = new eZLinkAttribute( $AttributeID[$i] );

                    $att->setValue( $link, $attribute );

                    $i++;
                }
            }
        }

        $link->update();

        if ( $DeleteImage )
        {
            $link->deleteImage();
        }

        eZHTTPTool::header( "Location: /link/category/$LinkCategoryID" );
        exit();
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_missingdata" );
    }
}
//else
//{
//    eZHTTPTool::header( "Location: /link/norights" );
//    exit();
//}


// Insert a link.
if ( isset( $Action ) && $Action == "insert" )
{
    if ( $Name != "" && $LinkCategoryID != "" && $Url != "" )
    {
        $link = new eZLink();

        $link->setName( $Name );
        $link->setDescription( $Description );
        $link->setKeyWords( $Keywords );
        if ( $Accepted == "1" && eZPermission::checkPermission( $user, "eZLink", "LinkAdd" ) )
            $link->setAccepted( true );
        else
            $link->setAccepted( false );

        $link->setUrl( $Url );

        $tname = $Name;
        $turl = $Url;
        if ( !$GetSite )
        {
            $tkeywords = $Keywords;
            $tdescription = $Description;
        }

        $file = new eZImageFile();
        if ( $file->getUploadedFile( "ImageFile" ) )
        {
            $image = new eZImage( );
            $image->setName( "LinkImage" );
            $image->setImage( $file );

            $image->store();

            $link->setImage( $image );
        }

        $link->store();

        if ( $TypeID == -1 )
        {
            $link->removeType();
        }
        else
        {
            $link->setType( new eZLinkType( $TypeID ) );

            $i = 0;
            if ( count( $AttributeValue ) > 0 )
            {
                foreach ( $AttributeValue as $attribute )
                {
                    $att = new eZLinkAttribute( $AttributeID[$i] );
                    $att->setValue( $link, $attribute );
                    $i++;
                }
            }
        }

        // Add to categories.
        $cat = new eZLinkCategory( $LinkCategoryID );
        $cat->addLink( $link );
        $link->setCategoryDefinition( $cat );
        if ( isset( $CategoryArray ) && count( $CategoryArray ) > 0 )
        {
            foreach ( $CategoryArray as $categoryItem )
            {
                if ( $categoryItem != $cat->id() )
                    eZLinkCategory::addLink( $link, $categoryItem );
            }
        }

        $linkID = $link->id();

        eZHTTPTool::header( "Location: /link/category/$LinkCategoryID" );
        exit();
    }
    else if ( !isSet( $Update ) )
    {
        $error_msg = $error->read_var( "strings", "error_missingdata" );
    }
}
else
{
//    eZHTTPTool::header( "Location: /link/norights" );
    $LinkID = false;
    $error_msg = false;
    $tdescription = false;
    $tkeywords = false;
    $turl = false;
    $tname = false;
    $linkType = false;
}

// set the template files.

$t = new eZTemplate( "kernel/ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
                     "kernel/ezlink/user/" . "/intl", $Language, "suggestlink.php" );
$t->setAllStrings();

$t->set_file( "link_edit", "suggestlink.tpl" );

$t->set_block( "link_edit", "link_category_tpl", "link_category" );

$t->set_block( "link_edit", "accept_link_tpl", "accept_link" );
$t->set_block( "link_edit", "image_item_tpl", "image_item" );
$t->set_block( "link_edit", "multiple_category_tpl", "multiple_category" );

$t->set_block( "link_edit", "type_tpl", "type" );

$t->set_block( "link_edit", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );


$languageIni = new INIFile( "kernel/ezlink/user/intl/" . $Language . "/suggestlink.php.ini", false );
$headline = $languageIni->read_var( "strings", "headline_insert" );

$linkselect = new eZLinkCategory();

$linkCategoryList =& $linkselect->getTree();

// Template variables.
$action_value = "update";

//if ( !eZPermission::checkPermission( $user, "eZLink", "LinkAdd" ) )
//{
//    eZHTTPTool::header( "Location: /link/norights" );
//}

$action_value = "insert";

$t->set_var( "image_item", "" );
// set accepted link as default.
$yes_selected = "selected";
$no_selected = "";

if ( $_SERVER['HTTP_REFERER'] != "" )
{
    $t->set_var( "referer_url", $_SERVER['HTTP_REFERER'] );
}
else
{
    $refererURL = $_SERVER['HTTP_REFERER'];
    if ( $refererURL != "" )
    {
        $t->set_var( "referer_url", $refererURL );
    }
    else
    {
        $t->set_var( "referer_url", "/" );
    }
}


if ( isset( $Action ) && $Action == "AttributeList" )
{
    $tname = $Name;
    $tkeywords = $Keywords;
    $tdescription = $Description;
    $turl = $Url;

    $action_value = "update";

    $t->set_var( "image_item", "" );


    if ( $Accepted )
    {
        $yes_selected = "selected";
        $no_selected = "";
    }
    else
    {
        $yes_selected = "";
        $no_selected = "selected";
    }

    $action_value = $url_array[3];
}

// Selector
$link_select_dict = "";
$catCount = count( $linkCategoryList );
$t->set_var( "num_select_categories", min( $catCount, 10 ) );
$i = 0;
foreach ( $linkCategoryList as $linkCategoryItem )
{
    $t->set_var( "link_category_id", $linkCategoryItem[0]->id() );
    $t->set_var( "link_category_name", $linkCategoryItem[0]->name() );

    if ( $LinkCategoryID == $linkCategoryItem[0]->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    if ( $linkCategoryItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $linkCategoryItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    $link_select_dict[$linkCategoryItem[0]->id()] = $i;

    if ( isset( $LinkCategoryIDArray ) && is_array( $LinkCategoryIDArray ) and in_array( $linkCategoryItem[0]->id(), $LinkCategoryIDArray )
         and ( $LinkCategoryID != $linkCategoryItem[0]->id() ) )
    {
        $t->set_var( "multiple_selected", "selected" );
        $i++;
    }
    else
    {
        $t->set_var( "multiple_selected", "" );
    }

    $t->parse( "link_category", "link_category_tpl", true );
    $t->parse( "multiple_category", "multiple_category_tpl", true );
}


$type = new eZLinkType();
$types = $type->getAll();

if ( isSet( $TypeID ) )
    $linkType = new eZLinkType( $TypeID );

foreach ( $types as $typeItem )
{
    if ( is_a( $linkType, "eZLinkType" )  )
    {
        if ( $linkType->id() == $typeItem->id() )
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


if ( is_a( $linkType, "eZLinkType" ) )
{
    $attributes = $linkType->attributes();

    foreach ( $attributes as $attribute )
    {
        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );

        $t->set_var( "attribute_value", $attribute->value( $editLink ) );

        $t->parse( "attribute", "attribute_tpl", true );
    }
}

if ( isset( $attributes ) && count( $attributes ) > 0 || !isSet( $type ) )
{
    $t->parse( "attribute_list", "attribute_list_tpl" );
}
else
{
    $t->set_var( "attribute_list", "" );
}


$t->set_var( "yes_selected", $yes_selected );
$t->set_var( "no_selected", $no_selected );

$t->set_var( "action_value", $action_value );

$t->set_var( "name", $tname );
$t->set_var( "url", $turl );
$t->set_var( "keywords", $tkeywords );
$t->set_var( "description", $tdescription );
// $t->set_var( "accepted", $taccepted );

$t->set_var( "headline", $headline );

$t->set_var( "error_msg", $error_msg );

$t->set_var( "link_id", $LinkID );
$t->pparse( "output", "link_edit" );

?>