<?php
//
// $Id: hotdealslist.php 7382 2001-09-21 14:28:49Z jhe $
//
// Created on: <12-Nov-2000 19:34:40 bf>
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

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$hotDealColumns  = $ini->read_var( "eZTradeMain", "HotDealColumns" );
$hotDealImageWidth  = $ini->read_var( "eZTradeMain", "HotDealImageWidth" );
$hotDealImageHeight  = $ini->read_var( "eZTradeMain", "HotDealImageHeight" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;

// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezproductcategory.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "eztrade/classes/ezpricegroup.php" );

$user =& eZUser::currentUser();

$RequireUser = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "enabled" ? true : false;
$ShowPrice = $RequireUser ? is_a( $user, "eZUser" ) : true;

$PriceGroup = 0;
if ( is_a( $user, "eZUser" ) )
{
    $PriceGroup = eZPriceGroup::correctPriceGroup( $user->groups( false ) );
}
if ( !$ShowPrice )
    $PriceGroup = -1;

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "hotdealslist.php" );

if ( isset( $HotDealsPage ) )
{
    $t->set_file( "hdl_product_list_page_tpl", "hotdealspage.tpl" );
}
else
{
    if ( !isset( $HotDealsTemplate ) )
        $HotDealsTemplate = "hotdealslist.tpl";
    $t->set_file( "hdl_product_list_page_tpl", $HotDealsTemplate );
}

$t->set_block( "hdl_product_list_page_tpl", "hdl_header_tpl", "header" );
$t->set_block( "hdl_product_list_page_tpl", "hdl_product_list_tpl", "hdl_product_list" );
$t->set_block( "hdl_product_list_tpl", "hdl_product_tpl", "hdl_product" );
$t->set_block( "hdl_product_tpl", "hdl_product_image_tpl", "hdl_product_image" );
$t->set_block( "hdl_product_tpl", "hdl_price_tpl", "hdl_price" );

$t->set_block( "product_gallary_page_tpl", "add_to_cart_tpl", "add_to_cart" );

if ( !isset( $ModuleName ) )
    $ModuleName = "trade";
if ( !isset( $ModuleView ) )
    $ModuleView = "productview";

$ModuleList = "productgallary";

$t->set_var( "module", $ModuleName );
$t->set_var( "module_view", $ModuleView );
$t->set_var( "module_list", $ModuleList );


$t->setAllStrings();

$product = new eZProduct(  );

$HotDealColumns = 2;

if ( !isset( $MaxHotDeals ) )
    $MaxHotDeals = false;
if ( isset( $HotDealColumns ) )
    $hotDealListColumns = $HotDealColumns;

$t->set_var( "hotdeal_columns", $hotDealListColumns );

// products
$productList =& $product->hotDealProducts( $MaxHotDeals );

$locale = new eZLocale( $Language );
$i=0;
$groupi = 0;
$hotDealListColumns = 1;
$trEnded = false;

foreach ( $productList as $product )
{
    $gallaryarraysize =  sizeof($productList) - 1;
    $groupi_size = $groupi; // - 1;
    $groupitemnumber = $hotDealListColumns;
    $groupitempreceeding = $groupitemnumber - 1;

        if ( $i == 0 )
	{
            $t->set_var( "begin_tr", "<tr>" );
            $t->set_var( "end_tr", "" );        
        }
        elseif ( $i == $gallaryarraysize )
        {
            $t->set_var( "begin_tr", "" );
            $t->set_var( "end_tr", "</tr>" );
        }
	elseif ( $groupi_size == $groupitempreceeding )
	{
            $t->set_var( "begin_tr", "" );
            $t->set_var( "end_tr", "</tr>" );
        }
	elseif ( $groupi_size == $groupitemnumber ) 
        {
            $t->set_var( "begin_tr", "<tr>" );
            $t->set_var( "end_tr", "" );
            $groupi = 0;
        }
	else 
	{
            $t->set_var( "begin_tr", "" );
            $t->set_var( "end_tr", "" );
	}

    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_intro_text", htmlspecialchars( $product->brief() ) );

    $image = $product->thumbnailImage();

    if  ( $image )
    {
        $thumbnail =& $image->requestImageVariation( $hotDealImageWidth, $hotDealImageHeight );
//        $thumbnail =& $image->requestImageVariation( 109, 109 );

        if ( $thumbnail )
        {
            if ( !isset( $HotDealsPage ) )
            {
                $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
                $t->set_var( "product_image_width", $thumbnail->width() );
                $t->set_var( "product_image_height", $thumbnail->height() );
                $t->set_var( "product_image_caption", $image->caption() );
            }
            else
            {
                $t->set_var( "thumbnail_image_uri", "/" . $thumbnail->imagePath() );
                $t->set_var( "thumbnail_image_width", $thumbnail->width() );
                $t->set_var( "thumbnail_image_height", $thumbnail->height() );
                $t->set_var( "thumbnail_image_caption", $image->caption() );
            }
            $t->parse( "hdl_product_image", "hdl_product_image_tpl" );
        }
        else
        {
            $t->set_var( "product_image", "" );
        }


    }
    else
    {
        $t->set_var( "hdl_product_image", "" );
    }

    if ( ( !$RequireUser || is_a( $user, "eZUser") ) &&
         ( $ShowPrice && $product->showPrice() ) )
    {
        $t->set_var( "product_price", $product->localePrice( $PricesIncludeVAT ) );
        $priceRange = $product->correctPriceRange( $PricesIncludeVAT );

        if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) )
         and !($product->correctPrice( $PricesIncludeVAT ) > 0) )
        {
            $t->set_var( "product_price", "" );
        }

        $t->parse( "hdl_price", "hdl_price_tpl" );
    }
    else
    {
        $t->set_var( "hdl_price", "" );
    }

    $defCat = $product->categoryDefinition();
    if ( $defCat )
    {
        $t->set_var( "category_id", $defCat->id() );
    }

    $t->set_var( "action_url", "cart/add" );
    $t->parse( "add_to_cart", "add_to_cart_tpl" );

    $t->parse( "hdl_product", "hdl_product_tpl", true );
    $i++;
}

if ( count( $productList ) > 0 )
{
    $t->parse( "hdl_product_list", "hdl_product_list_tpl" );
}
else
{
    $t->set_var( "hdl_product_list", "" );
}



if ( $GenerateStaticPage == "true" )
{
    // include_once( "classes/ezcachefile.php" );
    $CacheFile = new eZCacheFile( "kernel/eztrade/cache/",
                                  array( "hotdealslist", $PriceGroup ),
                                  "cache", "," );
    $output = $t->parse( "output", "product_list_page_tpl" );
    // print the output the first time while printing the cache file.
    print( $output );
    $CacheFile->store( $output );
}
else
{
    $t->pparse( "output", "hdl_product_list_page_tpl" );
}

?>