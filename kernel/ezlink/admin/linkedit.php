<?php
//
// $Id: linkedit.php 9200 2002-02-12 12:06:00Z br $
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

/*
  linkedit.php - edit a link.
*/

// include_once( "classes/INIFile.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->variable( "eZLinkMain", "Language" );
$error = new eZINI( "kernel/ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
$error_msg = false;
// include_once( "classes/eztemplate.php" );

// include_once( "ezlink/classes/ezlinkcategory.php" );
// include_once( "ezlink/classes/ezlink.php" );
// include_once( "ezlink/classes/ezhit.php" );

// include_once( "ezlink/classes/ezlinktype.php" );
// include_once( "ezlink/classes/ezlinkattribute.php" );

include_once( "kernel/ezlink/classes/ezmeta.php" );
require( "kernel/ezuser/admin/admincheck.php" );


if ( isset( $Accepted ) && $Accepted == "1" || !isset( $Accepted ) )
{
    $yes_selected = "selected";
    $no_selected = "";
}
else
{
    $yes_selected = "";
    $no_selected = "selected";
}

if ( isset( $DeleteLinks ) )
{
    $Action = "DeleteLinks";
}

if ( isset( $Delete ) )
{
    $Action = "delete";
}

if( isset( $Update ) )
{
    $tname = $Name;
    $turl = $Url;
    $tkeywords = $Keywords;
    $tdescription = $Description;
}

if ( isset( $Back ) )
{
    if ( $LinkID != "" )
    {
        $link = new eZLink( $LinkID );
        $catDef = $link->categoryDefinition();
        $LinkCategoryID = $catDef->id();
    }
    else
    {
        $LinkCategoryID = 0;
    }

    eZHTTPTool::header( "Location: /link/category/$LinkCategoryID/" );
    exit();
}

if ( isset( $CategoryArray ) )
    $LinkCategoryIDArray = $CategoryArray;
else
    $LinkCategoryIDArray = array();

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkAdd" ) )
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }

    $action_value = "new";

    $LinkID = 0;
    $error_msg = false;
    $tdescription = false;
    $tkeywords = false;
    $tname = false;
    $turl = false;
    $linkType = false;
    $tLinkCategoryID = false;

    if ( isset( $OK ) || isset( $Browse ) )
    {
        $Action = "insert";
    }
}

if ( $Action == "edit" )
{
    $action_value = "edit";
    if( isset( $OK ) )
    {
        $Action = "update";
    }
}

// Get images from the image browse function.
if ( ( isset( $AddImages ) ) and ( is_numeric( $LinkID ) ) and ( is_numeric( $LinkID ) ) )
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
            // Change this to use an external message
            $error_msg = "The site does not exists";
        }
        /* else if( count( $metaList ) == 0 )
        {
            $inierror = new eZINI( "kernel/ezlink/user/" . "/intl/" . $Language . "/suggestlink.php.ini", false );
            $terror_msg = $inierror->variable( "strings", "nometa" );
        } */
        if ( $metaList["description"] )
            $tdescription = $metaList["description"];
        else
            $tdescription = "";

        if ( isset( $metaList["keywords"] ) && $metaList["keywords"] )
            $tkeywords = $metaList["keywords"];
        else
            $tkeywords = "";

        if ( $metaList["title"] )
            $tname = $metaList["title"];
        else if ( $metaList["abstract"] )
            $tname = $metaList["abstract"];
        else
            $tname = "";

        $turl = $Url;
    }
    else
    {
        $tname = $Name;
        $turl = $Url;
        $tkeywords = $Keywords;
        $tdescription = $Description;
    }

    $action_value = $Action;
    if ( $Action != "edit" )
        $Action = "";

}

// Update a link.
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkModify" ) )
    {
        if ( $Name != "" &&
             $LinkCategoryID != "" &&
             $Accepted != "" &&
             $Url != "" )
        {
            $link = new eZLink( $LinkID );

            $link->setName( $Name );
            $link->setDescription( $Description );
            $link->setKeyWords( $Keywords );
            $link->setUrl( $Url );

            if( !isset( $_REQUEST[ 'CategoryArray']) )
                $CategoryArray = array();
            // Calculate new and unused categories

            $old_maincategory = $link->categoryDefinition();
            $old_categories =& array_unique( array_merge( array( $old_maincategory->id() ),
                                                          $link->categories( false ) ) );
            $new_categories = array_unique( array_merge( array( $LinkCategoryID ), $CategoryArray ) );
            $remove_categories = array_diff( $old_categories, $new_categories );
            $add_categories = array_diff( $new_categories, $old_categories );

            foreach ( $remove_categories as $categoryItem )
            {
                eZLinkCategory::removeLink( $link, $categoryItem );
            }

            // add to categories
            $category = new eZLinkCategory( $LinkCategoryID );
            $link->setCategoryDefinition( $category );

            foreach ( $add_categories as $categoryItem )
            {
                eZLinkCategory::addLink( $link, $categoryItem );
            }

            if ( $Accepted == "1" )
                $link->setAccepted( true );
            else
                $link->setAccepted( false );

            $link->setUrl( $Url );

            $file = new eZImageFile();
            if ( $file->getUploadedFile( "ImageFile" ) )
            {
                $image = new eZImage();
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
            if ( isset ( $Browse ) )
            {
                $linkID = $link->id();
                $session =& eZSession::globalSession();
                $session->setVariable( "SelectImages", "single" );
                $session->setVariable( "ImageListReturnTo", "/link/linkedit/edit/$linkID/" );
                $session->setVariable( "NameInBrowse", $link->name() );
                eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
                exit();
            }

            if ( isset( $Attributes ) )
            {
                $linkID = $link->id();
                eZHTTPTool::header( "Location: /link/linkedit/attributeedit/$linkID/" );
                exit();
            }

            eZHTTPTool::header( "Location: /link/category/$LinkCategoryID" );
            exit();
        }
        else
        {
            $error_msg = $error->variable( "strings", "error_missingdata" );
            $action_value = "edit";

            $tname = $Name;
            $turl = $Url;
            $tkeywords = $Keywords;
            $tdescription = $Description;
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
        exit();
    }
}

// Delete a link.
if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkDelete" ) )
    {
        $deletelink = new eZLink();
        $deletelink->get( $LinkID );
        $deletelink->delete();

        if ( $deletelink->accepted() == false )
        {
            eZHTTPTool::header( "Location: /link/category/incoming" );
            exit();
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

if ( $Action == "DeleteLinks" )
{
    if ( count ( $LinkArrayID ) != 0 )
    {
        foreach( $LinkArrayID as $LinkID )
        {
            $deletelink = new eZLink();
            $deletelink->get( $LinkID );
            $deletelink->delete();

        }
        if ( $deletelink )
        {
            if ( $deletelink->accepted() == false )
            {
                eZHTTPTool::header( "Location: /link/category/incoming" );
                exit();
            }
        }
        eZHTTPTool::header( "Location: /link/category/$LinkCategoryID" );
        exit();
    }
}

// Insert a link.
if ( $Action == "insert" )
{

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkAdd") )
    {
        if ( isset( $Name ) && $Name != "" &&
        isset( $LinkCategoryID ) && $LinkCategoryID != "" &&
        isset( $Accepted ) && $Accepted != "" &&
        isset( $Url ) && $Url != "" )
        {
            $link = new eZLink();

            $link->setName( $Name );
            $link->setDescription( $Description );
            $link->setKeyWords( $Keywords );
            if ( $Accepted == "1" )
                $link->setAccepted( true );
            else
                $link->setAccepted( false );

            $link->setUrl( $Url );

            $tname = $Name;
            $turl = $Url;
            if ( isset( $GetSite) && !$GetSite )
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
            if ( count( $CategoryArray ) > 0 )
            {
                foreach ( $CategoryArray as $categoryItem )
                {
                    if ( $categoryItem != $cat->id() )
                    {
                        $cat = new eZLinkCategory( $categoryItem );
                        $cat->addLink( $link );
                    }
                }
            }
            $linkID = $link->id();

            if ( isset( $Browse ) )
            {
                $linkID = $link->id();
                $session = eZSession::globalSession();
                $session->setVariable( "SelectImages", "single" );
                $session->setVariable( "ImageListReturnTo", "/link/linkedit/edit/$linkID/" );
                $session->setVariable( "NameInBrowse", $link->name() );
                eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
                exit();
            }

            if ( isset( $Attributes ) )
            {
                $linkID = $link->id();
                eZHTTPTool::header( "Location: /link/linkedit/attributeedit/$linkID/" );
                exit();
            }

            eZHTTPTool::header( "Location: /link/category/$LinkCategoryID" );
            exit();
        }
        else if ( !isset( $Update ) && !isset( $GetSite ) )
        {
            $error_msg = $error->variable( "strings", "error_missingdata" );
            $action_value = "new";

            $tname = $Name;
            $turl = $Url;
            $tkeywords = $Keywords;
            $tdescription = $Description;
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

// set the template files.

$t = new eZTemplate( "kernel/ezlink/admin/" . $ini->variable( "eZLinkMain", "AdminTemplateDir" ),
"kernel/ezlink/admin/" . "/intl", $Language, "linkedit.php" );
$t->setAllStrings();

$t->set_file( "link_edit", "linkedit.tpl" );

$t->set_block( "link_edit", "link_category_tpl", "link_category" );

$t->set_block( "link_edit", "image_item_tpl", "image_item" );
$t->set_block( "link_edit", "no_image_item_tpl", "no_image_item" );

$t->set_block( "link_edit", "multiple_category_tpl", "multiple_category" );

$t->set_block( "link_edit", "type_tpl", "type" );

$t->set_block( "link_edit", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );


$languageIni = new eZINI( "kernel/ezlink/admin/intl/" . $Language . "/linkedit.php.ini", false );
$headline = $languageIni->variable( "strings", "headline_insert" );

$linkselect = new eZLinkCategory();

$linkCategoryList =& $linkselect->getTree();

// Template variables.

// $action_value = "update";


$t->set_var( "image_item", "" );
$t->set_var( "no_image_item", "" );

// set accepted link as default.
// $yes_selected = "selected";
// $no_selected = "";

// editere
if ( $Action == "edit" )
{

    $languageIni = new eZINI( "kernel/ezlink/admin/intl/" . $Language . "/linkedit.php.ini", false );
    $headline =  $languageIni->variable( "strings", "headline_edit" );

    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkModify" ) )
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
    else
    {
        if ( !isset( $editLink ) )
        {
            $editLink = new eZLink();
            $editLink->get( $LinkID );
        }

        $name = $editLink->Name;

        $cateDef = $editLink->categoryDefinition();
        $LinkCategoryID = $cateDef->id();
        $LinkCategoryIDArray = $editLink->categories( false );

        $name = $editLink->name();
        $description = $editLink->description();
        $keywords = $editLink->keyWords();
        $accepted = $editLink->accepted();
        $url = $editLink->url();

        $action_value = "edit";

        if ( !isset( $Update ) )
        {
            $tname = $editLink->name();
            $tdescription = $editLink->description();
            $tkeywords = $editLink->keywords();
            $turl = $editLink->url();
        }

        $linkType = $editLink->type();

        $image = $editLink->image();

        if ( $image )
        {
            $imageWidth =& $ini->variable( "eZLinkMain", "CategoryImageWidth" );
            $imageHeight =& $ini->variable( "eZLinkMain", "CategoryImageHeight" );

            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

            $imageURL = "/" . $variation->imagePath();
            $imageWidth = $variation->width();
            $imageHeight = $variation->height();
            $imageCaption = $image->caption();

            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            $t->set_var( "no_image", "" );
            $t->parse( "image_item", "image_item_tpl" );

            $t->set_var( "no_image_item", "" );
        }
        else
        {
            $t->parse( "no_image_item", "no_image_item_tpl" );
            $t->set_var( "image_item", "" );
        }


        if ( $editLink->accepted() == true )
        {
            $yes_selected = "selected";
            $no_selected = "";
        }
        else
        {
            $yes_selected = "";
            $no_selected = "selected";
        }

        if ( isset( $Browse ) )
        {
            $linkID = $editLink->id();
            $session = eZSession::globalSession();
            $session->setVariable( "SelectImages", "single" );
            $session->setVariable( "ImageListReturnTo", "/link/linkedit/edit/$linkID/" );
            $session->setVariable( "NameInBrowse", $editLink->name() );
            eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
            exit();
        }
    }
}

if ( $Action == "AttributeList" )
{
    $tname = $Name;
    $tkeywords = $Keywords;
    $tdescription = $Description;
    $turl = $Url;

    $action_value = "update";

    $t->parse( "no_image_item", "no_image_item_tpl" );
    $t->set_var( "image_item", "" );

    if ( $Accepted == true )
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

$t->set_var( "link_category", "" );
$t->set_var( "multiple_category", "" );

foreach( $linkCategoryList as $linkCategoryItem )
{
    $t->set_var("link_category_id", $linkCategoryItem[0]->id() );
    $t->set_var("link_category_name", $linkCategoryItem[0]->name() );

    if ( isset( $LinkCategoryID ) && (int) $LinkCategoryID == $linkCategoryItem[0]->id() )
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

    $link_select_dict[ $linkCategoryItem[0]->id() ] = $i;
    if ( in_array( $linkCategoryItem[0]->id(), $LinkCategoryIDArray )
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

if ( isset( $TypeID ) )
    $linkType = new eZLinkType( $TypeID );

$t->set_var( "type", "" );

foreach ( $types as $typeItem )
{
    if ( is_a( $linkType, "eZLinkType"  ) )
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


$i = 0;
if ( is_a( $linkType, "eZLinkType") )
{
    $attributes = $linkType->attributes();
    foreach ( $attributes as $attribute )
    {

        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );

        if ( isset( $AttributeValue[$i] ) && $attribute->id() == $AttributeID[$i] )
            $t->set_var( "attribute_value", $AttributeValue[$i] );
        else
            $t->set_var( "attribute_value", $attribute->value( $editLink ) );

        $t->parse( "attribute", "attribute_tpl", true );
        $i++;
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