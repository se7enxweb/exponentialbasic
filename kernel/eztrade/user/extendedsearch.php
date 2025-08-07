<?php
//
// $Id: extendedsearch.php 6484 2001-08-17 13:36:01Z jhe $
//
// Created on: <10-Oct-2000 17:49:05 bf>
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
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/ezlist.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$RequireUserLogin = $ini->variable( "eZTradeMain", "RequireUserLogin" ) == "true";
$ExtendedSearchCategories = $ini->read_array( "eZTradeMain", "ExtendedSearchCategories" );
$MaxSearchForProducts = $ini->variable( "eZTradeMain", "MaxSearchForProducts" );

$SmallImageWidth = $ini->variable( "eZTradeMain", "SmallImageWidth" );
$SmallImageHeight = $ini->variable( "eZTradeMain", "SmallImageHeight" );

// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezcartoptionvalue.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezsession/classes/ezsession.php" );
// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "classes/ezlist.php" );

if ( isset ( $SearchButton ) )
{
    $Action = "SearchButton";
}

$user =& eZUser::currentUser();

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ) ,
                     "kernel/eztrade/user/intl/", $Language, "extendedsearch.php" );

$t->setAllStrings();

$t->set_file(  "extended_search_tpl", "extendedsearch.tpl" );


$t->set_block( "extended_search_tpl", "extended_tpl", "product" );
$t->set_block( "extended_search_tpl", "product_search_list_tpl", "product_search_list" );
$t->set_block( "extended_search_tpl", "error_max_search_for_products_tpl", "error_max_search_for_products" );
$t->set_block( "extended_search_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_block( "extended_search_tpl", "empty_search_tpl", "empty_search" );

$t->set_block( "product_search_list_tpl", "image_tpl", "image" );
$t->set_block( "product_search_list_tpl", "price_tpl", "price" );

$t->set_var( "price_lower", "" );
$t->set_var( "price_higher", "" );
$t->set_var( "text", "" );
$t->set_var( "error_max_search_for_products", "" );

$priceRange = explode( "-", $PriceRange );
//  $PriceLower = !is_numeric( $priceRange[0] ) ? 0 : $priceRange[0];
//  $PriceHigher = !is_numeric( $priceRange[1] ) ? 500 : $priceRange[1];
$PriceLower = $priceRange[0];
$PriceHigher = $priceRange[1];

// products
$product = new eZProduct();

if ( $Action == "SearchButton" )
{
    if ( $Limit == "" )
        $Limit = 10;
    if ( $Offset == "" )
        $Offset = 0;

    $mainCategoryArray = explode( "-", $MainCategories );
    if ( $Next || $Prev )
    {
        $lists = explode( ":", $CategoryArray );
        $cats = array();
        foreach( $lists as $list )
        {
            $cats[] = explode( "-", $list );
        }
        $mains = explode( "-", $MainCategories );

        $catIDArray = array();
        reset( $mains );
        list($key,$main) = each( $mains );
        foreach ( $cats as $cat )
        {
            $cat_array = array();
            $cat_array["id"] = $main;
            $cat_array["categories"] = $cat;
            $catIDArray[] = $cat_array;
            list($key,$main) = each( $mains );
        }

        $productList =& $product->extendedSearch( $PriceLower, $PriceHigher, $Text, $Offset, $Limit, $catIDArray );
        $totalCount = $product->extendedSearchCount( $PriceLower, $PriceHigher, $Text, $catIDArray );
    }
    else
    {
        if ( $Limit == "" )
            $Limit = 10;
        if ( $Offset == "" )
            $Offset = 0;

        $catIDArray = array();

        reset( $CategoryArrayID );
        while ( list($main,$cats ) = each($CategoryArrayID) )
        {
            $cat_array = array();
            $cat_array["id"] = $main;
            $categories = array();
            foreach( $cats as $cat )
            {
                $tree =& eZProductCategory::getTree( $cat == 0 ? $main : $cat );
                foreach( $tree as $category_item )
                {
                    $categories[] = $category_item[0]->id();
                }
                if ( $cat != 0 )
                    $categories[] = $cat;
            }
            $cat_array["categories"] = $categories;
            $catIDArray[] = $cat_array;
        }

        $productList =& $product->extendedSearch( $PriceLower, $PriceHigher, $Text, $Offset, $Limit, $catIDArray );
        $totalCount = $product->extendedSearchCount( $PriceLower, $PriceHigher, $Text, $catIDArray );
    }

    $t->set_var( "price_lower", $PriceLower );
    $t->set_var( "price_higher", $PriceHigher );
    $t->set_var( "text", $Text );

    $t->set_var( "url_text", urlencode( $Text == "" ? " " : $Text ) );
    $t->set_var( "url_range", urlencode( $PriceRange ) );

    $urlCategory = "";
    $i = 0;
    foreach( $catIDArray as $catID )
    {
        $categories =& $catID["categories"];
        $cat_list = "";
        $j = 0;
        foreach( $categories as $category )
        {
            if ( $j > 0 )
                $cat_list .= "-";
            $cat_list .= $category;
            ++$j;
        }
        if ( $i > 0 )
            $urlCategory .= ":";
        $urlCategory .= $cat_list;
        ++$i;
    }

    $t->set_var( "url_category", urlencode( $urlCategory ) );

    $mainCategory = "";
    $i = 0;
    foreach( $catIDArray as $catID )
    {
        if ( $i > 0 )
            $mainCategory .= "-";
        $mainCategory .= $catID["id"];
        ++$i;
    }
    $t->set_var( "url_main_categories", $mainCategory );
}

if ( ( $MaxSearchForProducts != 0 ) && ( $MaxSearchForProducts < $totalCount ) )
{
    $t->parse( "error_max_search_for_products", "error_max_search_for_products_tpl" );
    $t->set_var( "product_search_list", "" );
    $productList = array();
    $totalCount = 0;
}

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "product", "" );
$t->set_var( "empty_search", "" );
if ( count ( $productList ) > 0 )
{
    foreach ( $productList as $product )
    {
        // preview image
        $thumbnailImage = $product->thumbnailImage();
        if ( $thumbnailImage )
        {
            $variation =& $thumbnailImage->requestImageVariation( $SmallImageWidth, $SmallImageHeight );

            $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
            $t->set_var( "thumbnail_image_width", $variation->width() );
            $t->set_var( "thumbnail_image_height", $variation->height() );
            $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

            $t->parse( "image", "image_tpl" );
        }
        else
        {
            $t->set_var( "image", "" );
        }

        $t->set_var( "product_name", $product->name() );

        if ( ( !$RequireUserLogin or is_a( $user, "eZUser" ) ) and
             $ShowPrice and $product->showPrice() == true and $product->hasPrice() )
        {
            $found_price = false;
            if ( $ShowPriceGroups and $PriceGroup > 0 )
            {
                $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup );
                if ( $price )
                {
                    $found_price = true;
                    $price = new eZCurrency( $price );
                }
            }
            if ( !$found_price )
            {
                $price = new eZCurrency( $product->price() );
            }
            $t->set_var( "product_price", $locale->format( $price ) );
            $t->parse( "price", "price_tpl" );
        }
        else
        {
            $t->set_var( "price", "" );
        }

        $t->set_var( "product_intro_text", $product->brief() );
        $t->set_var( "product_id", $product->id() );

        $defCat = $product->categoryDefinition();
        $t->set_var( "category_id", $defCat->id() );

        $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );

        $t->parse( "product_search_list", "product_search_list_tpl", true );
        $i++;
    }
}
else
{
    $t->set_var( "product_search_list", "" );
    if ( $Action == "SearchButton" )
        $t->parse( "empty_search", "empty_search_tpl" );
}

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "extended_search_tpl" );

$category = new eZProductCategory();

//$categoryList =& $category->getTree();
$categoryList = array();
foreach( $ExtendedSearchCategories as $category )
{
    $cat = new eZProductCategory( $category );
    $cats = $cat->getByParent( $cat );
    $categoryList[] = array( "categories" => $cats,
                             "name" => $cat->name(),
                             "id" => $cat->id() );
}

$subCategories = "";
$t->set_var( "category_list", "" );
$t->set_var( "is_all_selected", "" );
if ( is_array( $CategoryArrayID ) )
{
    reset( $CategoryArrayID );
    list($key,$categoryArray) = each( $CategoryArrayID );
}
foreach( $categoryList as $categoryItem )
{
    $t->set_var( "category_main_name", $categoryItem["name"] );
    $t->set_var( "category_main_id", $categoryItem["id"] );
    $cats =& $categoryItem["categories"];
    $t->set_var( "category_item", "" );
    foreach( $cats as $cat )
    {
        $t->set_var( "category_name", $cat->name()  );
        $t->set_var( "category_id", $cat->id() );
        $t->set_var( "option_level", "" );

        $t->set_var( "is_selected", "" );
        if ( is_array ( $categoryArray ) )
        {
            foreach ( $categoryArray as $categoryID )
            {
                if ( $categoryID == $cat->id() )
                    $t->set_var( "is_selected", "selected" );
            }
        }

        $t->parse( "category_item", "category_item_tpl", true );
        $subCategories = "";
    }
    $t->parse( "category_list", "category_list_tpl", true );
    if ( is_array( $CategoryArrayID ) )
    {
        list($key,$categoryArray) = each( $CategoryArrayID );
    }
}

$t->set_var( "url_query_string", $Query );
$t->set_var( "query_string", $Query );

$t->set_var( "query", $Query );
$t->set_var( "limit", $Limit );
$prevOffs = $Offset - $Limit;
$nextOffs = $Offset + $Limit;

$t->set_var( "prev_offset", $prevOffs );
$t->set_var( "next_offset", $nextOffs );

$t->pparse( "output", "extended_search_tpl" );
?>