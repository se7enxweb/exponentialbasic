 <?php
// 
// $Id: hotdealsgallery.php,v 1.1.2.1 2002/05/14 13:02:54 kracker Exp $
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$hotDealColumns  = $ini->read_var( "eZTradeMain", "HotDealColumns" );
$hotDealImageWidth  = $ini->read_var( "eZTradeMain", "HotDealImageWidth" );
$hotDealImageHeight  = $ini->read_var( "eZTradeMain", "HotDealImageHeight" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

$user =& eZUser::currentUser();

$RequireUser = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "enabled" ? true : false;
$ShowPrice = $RequireUser ? get_class( $user ) == "ezuser" : true;

$PriceGroup = 0;
if ( get_class( $user ) == "ezuser" )
{
    $PriceGroup = eZPriceGroup::correctPriceGroup( $user->groups( false ) );
}
if ( !$ShowPrice )
    $PriceGroup = -1;

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "hotdealsgallery.php" );

if ( isset( $HotDealsPage ) )
{
    $t->set_file( "product_list_page_tpl", "hotdealspage.tpl" );
}
else
{
    if ( !isset( $HotDealsTemplate ) )
        $HotDealsTemplate = "hotdealsgallery.tpl";
    $t->set_file( "product_list_page_tpl", $HotDealsTemplate );
}

$t->set_block( "product_list_page_tpl", "header_tpl", "header" );
$t->set_block( "product_list_page_tpl", "product_list_tpl", "product_list" );
$t->set_block( "product_list_tpl", "product_tpl", "product" );
$t->set_block( "product_tpl", "product_image_tpl", "product_image" );
$t->set_block( "product_tpl", "price_tpl", "price" );
$t->set_block( "product_tpl", "product_catalog_number_tpl", "product_catalog_number" );

$t->set_block( "product_tpl", "add_to_cart_tpl", "add_to_cart" );


if ( !isset( $ModuleName ) )
    $ModuleName = "trade";
if ( !isset( $ModuleView ) )
    $ModuleView = "productview";
if ( !isSet( $ModuleList ) )
    $ModuleList = "productgallary";

$t->set_var( "module", $ModuleName );
$t->set_var( "module_view", $ModuleView );
$t->set_var( "module_list", $ModuleList );

$t->setAllStrings();

$product = new eZProduct(  );

if ( !isset( $MaxHotDeals ) )
    $MaxHotDeals = false;
if ( isset( $HotDealColumns ) )
    $hotDealColumns = $HotDealColumns;
$t->set_var( "hotdeal_columns", $hotDealColumns );

// products
$productList =& $product->hotDealProducts( $MaxHotDeals );

$locale = new eZLocale( $Language );
$i=0;
$trEnded = false;

$groupi = 0;

foreach ( $productList as $product )
{
    $gallaryarraysize =  sizeof($productgallary) - 1;
    $groupi_size = $groupi; // - 1;
    $groupitemnumber = 3;
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
            $t->set_var( "begin_tr", "<tr><td colspan='$hotDealColumns'>&nbsp;</td></tr><tr>" );
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
    $t->set_var( "product_intro_text", $product->brief() );
    $t->set_var( "product_number", "" );

    if ( $product->productNumber() != "" )
    {
        $t->set_var( "product_number", $product->productNumber() );
    }

    $t->set_var( "product_catalog_number", "" );
    $t->set_var( "catalog_number", "" );

    if ( $product->catalogNumber() != "" )
      {
        $t->set_var( "catalog_number", $product->catalogNumber() );
	$t->parse( "product_catalog_number", "product_catalog_number_tpl" );

      }


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
	        $t->set_var( "thumbnail_image_caption", $image->caption() );
            }
            else
            {
                $t->set_var( "thumbnail_image_uri", "/" . $thumbnail->imagePath() );
                $t->set_var( "thumbnail_image_width", $thumbnail->width() );
                $t->set_var( "thumbnail_image_height", $thumbnail->height() );
                $t->set_var( "thumbnail_image_caption", $image->caption() );
            }
            $t->parse( "product_image", "product_image_tpl" );            
        }
        else
        {
            $t->set_var( "product_image", "" );
        }


    }
    else
    {
        $t->set_var( "product_image", "" );
    }
    
    if ( ( !$RequireUserLogin or get_class( $user ) == "ezuser"  ) and
             $ShowPrice and $product->showPrice() == true )
    {
        $t->set_var( "product_price", $product->localePrice( $PricesIncludeVAT ) );
        $priceRange = $product->correctPriceRange( $PricesIncludeVAT );
        
        if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) )
         and !($product->correctPrice( $PricesIncludeVAT ) > 0) )
        {
            $t->set_var( "product_price", "" );
        }

        $t->parse( "price", "price_tpl" );
    }
    else
    {
        $t->set_var( "price", "" );
    }
    
    $defCat = $product->categoryDefinition();
    if ( $defCat )
    {
        $t->set_var( "category_id", $defCat->id() );
    }

    $t->set_var( "action_url", "cart/add" );
    $t->parse( "add_to_cart", "add_to_cart_tpl" );

    $t->parse( "product", "product_tpl", true );
    $i++;
    $groupi++;
}

if ( count( $productList ) > 0 )
{
    $t->parse( "product_list", "product_list_tpl" );
}
else
{
    $t->set_var( "product_list", "" );
}



if ( $GenerateStaticPage == "true" )
{
    include_once( "classes/ezcachefile.php" );
    $CacheFile = new eZCacheFile( "eztrade/cache/",
                                  array( "hotdealsgallery", $PriceGroup ),
                                  "cache", "," );
    $output = $t->parse( $target, "product_list_page_tpl" );
    // print the output the first time while printing the cache file.
    print( $output );
    $CacheFile->store( $output );
}
else
{
    $t->pparse( "output", "product_list_page_tpl" );
}


?>
