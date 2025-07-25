<?php
//
// $Id: linkcategorylist.php 7341 2001-09-19 11:35:52Z bf $
//
// Created on: <26-Oct-2000 14:55:24 ce>
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
// include_once( "classes/ezlist.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZLinkMain", "Language" );
$AdminLimit = $ini->read_var( "eZLinkMain", "AdminLinkLimit" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );
$languageIni = new INIFile( "kernel/ezlink/admin/intl/" . $Language . "/linkcategorylist.php.ini", false );

// include_once( "ezlink/classes/ezlinkcategory.php" );
// include_once( "ezlink/classes/ezlink.php" );
// include_once( "ezlink/classes/ezhit.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );

require( "kernel/ezuser/admin/admincheck.php" );

$t = new eZTemplate( "kernel/ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
"kernel/ezlink/admin/intl/", $Language, "linkcategorylist.php" );
$t->setAllStrings();

$t->set_file( "link_page_tpl", "linkcategorylist.tpl" );

$t->set_block( "link_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_block( "category_item_tpl", "image_item_tpl", "image_item" );
$t->set_block( "category_item_tpl", "no_image_tpl", "no_image" );

$t->set_block( "link_page_tpl", "link_list_tpl", "link_list" );
$t->set_block( "link_list_tpl", "link_item_tpl", "link_item" );

$t->set_block( "link_item_tpl", "image_item_tpl", "image_item" );
$t->set_block( "link_item_tpl", "no_image_tpl", "no_image" );


$t->set_block( "link_page_tpl", "path_item_tpl", "path_item" );

$t->set_var( "site_style", $SiteDesign );

if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $total_sub_links ) )
    $total_sub_links = 0;

if ( !isset( $new_sub_links ) )
    $new_sub_links = 0;

if ( !isset( $links ) )
    $links = 0;

if ( !isset( $categories ) )
    $categories = false;

// List all the categoires
$linkCategory = new eZLinkCategory();


$linkCategory->get( $LinkCategoryID );

// path
$pathArray = $linkCategory->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );
    $t->set_var( "category_name", $path[1] );
    $t->parse( "path_item", "path_item_tpl", true );
}

$linkCategoryList =& $linkCategory->getByParent( $LinkCategoryID );

if ( $LinkCategoryID == "incoming" )
{
    $linkCategoryList = array();
}

if ( count( $linkCategoryList ) == 0 )
{
    $t->set_var( "categories", "" );
    $t->set_var( "category_list", "" );
}
else
{
    $i=0;
    foreach( $linkCategoryList as $linkCategoryItem )
    {
        if ( ( ( $i ) % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "bglight" );
        }
        else
        {
            $t->set_var( "bg_color", "bgdark" );
        }

        $link_category_id = $linkCategoryItem->id();
        $t->set_var( "linkcategory_id", $link_category_id );
        $t->set_var( "linkcategory_name", $linkCategoryItem->name() );
        $t->set_var( "category_description", $linkCategoryItem->description() );
        $t->set_var( "linkcategory_parent", $linkCategoryItem->parent() );

        $t->set_var( "total_links", $total_sub_links );
        $t->set_var( "new_links", $new_sub_links );

        $t->set_var( "document_root", $DOC_ROOT );

        $image =& $linkCategoryItem->image();

        $t->set_var( "image_item", "" );

        if ( is_a( $image, "eZImage" ) )
        {
            $imageWidth =& $ini->read_var( "eZLinkMain", "CategoryImageWidth" );
            $imageHeight =& $ini->read_var( "eZLinkMain", "CategoryImageHeight" );

            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

            $imageURL = "/" . $variation->imagePath();
            $imageWidth =& $variation->width();
            $imageHeight =& $variation->height();
            $imageCaption =& $image->caption();

            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            $t->set_var( "no_image", "" );
            $t->parse( "image_item", "image_item_tpl" );
        }
        else
        {
            $t->parse( "no_image", "no_image_tpl" );
            $t->set_var( "image_item", "" );
        }

        $categories = $languageIni->read_var( "strings", "categories" );

        $t->parse( "category_item", "category_item_tpl", true );
        $i++;
    }
    $t->parse( "category_list", "category_list_tpl", true );
}

// List all the links in category
$link = new eZLinkCategory();
if ( $LinkCategoryID == "incoming" )
{
    $linkList =& $link->links( $Offset, $AdminLimit, true );
    $linkCount =& $link->linkCount( true );
}
else
{
    $linkList =& $linkCategory->links( $Offset, $AdminLimit );
    $linkCount =& $linkCategory->linkCount();
}

if ( !$linkList )
{
    $t->set_var( "links", "" );
    $t->set_var( "link_list", "" );
}
else
{
    $i=0;
    foreach( $linkList as $linkItem )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "link_id", $linkItem->id() );
        $t->set_var( "link_name", $linkItem->name() );
        $t->set_var( "link_description", $linkItem->description() );
        $t->set_var( "link_keywords", $linkItem->keywords() );
        $t->set_var( "link_created", $linkItem->created() );
        $t->set_var( "link_modified", $linkItem->modified() );
        $t->set_var( "link_accepted", $linkItem->id() );
        $t->set_var( "link_url", $linkItem->url() );

        $image =& $linkItem->image();

        $t->set_var( "image_item", "" );

        if ( $image )
        {
            $imageWidth =& $ini->read_var( "eZLinkMain", "LinkImageWidth" );
            $imageHeight =& $ini->read_var( "eZLinkMain", "LinkImageHeight" );

            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

            $imageURL = "/" . $variation->imagePath();
            $imageWidth =& $variation->width();
            $imageHeight =& $variation->height();
            $imageCaption =& $image->caption();

            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            $t->set_var( "no_image", "" );
            $t->parse( "image_item", "image_item_tpl" );
        }
        else
        {
            $t->parse( "no_image", "no_image_tpl" );
        }


        $hit = new eZHit();
        $hits = $hit->getLinkHits( $linkItem->id() );

        $t->set_var( "link_hits", $hits );

        $links = $languageIni->read_var( "strings", "links" );

        $t->parse( "link_item", "link_item_tpl", true );
        $i++;
    }
    $t->parse( "link_list", "link_list_tpl", true );
}

eZList::drawNavigator( $t, $linkCount, $AdminLimit, $Offset, "link_page_tpl" );

$t->set_var( "categories", $categories );
$t->set_var( "links", $links );
$t->set_var( "document_root", $DOC_ROOT );

$t->set_var( "link_start", $Offset + 1 );
$t->set_var( "link_end", min( $Offset + $AdminLimit, $linkCount ) );
$t->set_var( "link_total", $linkCount );

$t->pparse( "output", "link_page_tpl" );

?>