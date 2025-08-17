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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "eztrade/classes/ezpricegroup.php" );


$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZTradeMain", "Language" );
$hotDealColumns  = $ini->variable( "eZTradeMain", "HotDealColumns" );
$hotDealImageWidth  = $ini->variable( "eZTradeMain", "HotDealImageWidth" );
$hotDealImageHeight  = $ini->variable( "eZTradeMain", "HotDealImageHeight" );
$ShowPriceGroups = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$PricesIncludeVAT = $ini->variable( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
$RequireUserLogin = $ini->variable( "eZTradeMain", "RequireUserLogin" ) == "enabled" ? true : false;

$ThumbnailImageWidth = $ini->variable( "eZTradeMain", "ThumbnailImageWidth" );
$ThumbnailImageHeight = $ini->variable( "eZTradeMain", "ThumbnailImageHeight" );

$ShowPrice = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true";

$SimpleOptionHeaders = $ini->variable( "eZTradeMain", "SimpleOptionHeaders" ) == "true";
$ShowOptionQuantity = $ini->variable( "eZTradeMain", "ShowOptionQuantity" ) == "true";
$RequireQuantity = $ini->variable( "eZTradeMain", "RequireQuantity" ) == "true" ;

$ShowPrice = $RequireUser ? get_class( $user ) == "ezuser" : true;
$GenerateStaticPage = true;
$PriceGroup = 0;

$user =& eZUser::currentUser();

if ( !isset( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 10;
if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

if ( is_object( $user ) && get_class( $user ) == "ezuser" )
{
    $PriceGroup = eZPriceGroup::correctPriceGroup( $user->groups( false ) );
}
if ( !$ShowPrice )
    $PriceGroup = -1;

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "hotdealsgallery.php" );

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

$t->set_block( "product_list_page_tpl", "price_tpl", "price" );
$t->set_block( "product_list_page_tpl", "path_tpl", "path" );
$t->set_block( "product_list_page_tpl", "product_list_tpl", "product_list" );

$t->set_block( "product_list_tpl", "product_tpl", "product" );
$t->set_block( "product_tpl", "product_image_tpl", "product_image" );

$t->set_block( "product_tpl", "option_tpl", "option" );
$t->set_block( "option_tpl", "value_price_header_tpl", "value_price_header" );
$t->set_block( "option_tpl", "value_tpl", "value" );

$t->set_block( "value_price_header_tpl", "value_description_header_tpl", "value_description_header" );
$t->set_block( "value_price_header_tpl", "value_price_header_item_tpl", "value_price_header_item" );
$t->set_block( "value_price_header_tpl", "value_currency_header_item_tpl", "value_currency_header_item" );

$t->set_block( "value_tpl", "value_description_tpl", "value_description" );
$t->set_block( "value_tpl", "value_price_item_tpl", "value_price_item" );
$t->set_block( "value_tpl", "value_availability_item_tpl", "value_availability_item" );
$t->set_block( "value_tpl", "value_price_currency_list_tpl", "value_price_currency_list" );

$t->set_block( "value_price_currency_list_tpl", "value_price_currency_item_tpl", "value_price_currency_item" );

$t->set_block( "product_tpl", "add_to_cart_tpl", "add_to_cart" );


if ( !isset( $ModuleName ) )
    $ModuleName = "trade";
if ( !isset( $ModuleView ) )
    $ModuleView = "productview";
if ( !isset( $ModuleList ) )
    $ModuleList = "hotdealsgallery";

$t->set_var( "module", $ModuleName );
$t->set_var( "module_view", $ModuleView );
$t->set_var( "module_list", $ModuleList );

$t->setAllStrings();

$product = new eZProduct();

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
    $gallaryarraysize =  sizeof($productList) - 1;
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

    if ( eZObjectPermission::hasPermission( $product->id(), "trade_product", "r", $user ) )
    {
        $t->set_var( "product_id", $product->id() );
        $t->set_var( "category_id", $product->categories()[0]->id() );

        // preview image
        $thumbnailImage = $product->thumbnailImage();

        if ( $thumbnailImage )
        {
            $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight );

            $t->set_var( "product_image_uri", "/" . $variation->imagePath() );
            $t->set_var( "product_image_width", $variation->width() );
            $t->set_var( "product_image_height", $variation->height() );
            $t->set_var( "product_image_caption", $thumbnailImage->caption() );

            $t->parse( "product_image", "product_image_tpl" );
        }
        else
        {
            $t->set_var( "product_image", "" );    
        }

        $options = $product->options();
        $t->set_var( "option", "" );

        $t->set_var( "value_price_header", "" );
        if ( $ShowPrice and $product->showPrice() == true  )
            $t->parse( "value_price_header", "value_price_header_tpl" );

        // show alternative currencies
        $currency = new eZProductCurrency( );
        $currencies =& $currency->getAll();
        $t->set_var( "currency_count", count( $currencies ) );
        $t->set_var( "value_price_header_item", "" );
        $t->set_var( "value_currency_header_item", "" );
        if ( !$RequireUserLogin or is_a( $user, "eZUser" ) )
        {
            $t->parse( "value_price_header_item", "value_price_header_item_tpl" );
            if ( count( $currencies ) > 0 )
                $t->parse( "value_currency_header_item", "value_currency_header_item_tpl" );
        }

        $can_checkout = true;

        $currency_locale = new eZLocale( $Language );
        foreach ( $options as $option )
        {
            $values = $option->values();

            $t->set_var( "value", "" );
            $i = 0;
            $headers = $option->descriptionHeaders();
            $t->set_var( "value_description_header", "" );
            if ( $SimpleOptionHeaders )
            {
                $t->set_var( "description_header", $headers[0] );
                $t->parse( "value_description_header", "value_description_header_tpl" );
            }
            else
            {
                foreach ( $headers as $header )
                {
                    $t->set_var( "description_header", $header );
                    $t->parse( "value_description_header", "value_description_header_tpl", true );
                }
            }

            foreach ( $values as $value )
            {
                $value_quantity = $value->totalQuantity();
                if ( $ShowOptionQuantity or ( is_bool( $value_quantity ) and !$value_quantity ) or
                    !$RequireQuantity or ( $RequireQuantity and $value_quantity > 0 ) )
                {
                    if ( !$value->hasQuantity( $RequireQuantity ) )
                        $can_checkout = false;
                    $t->set_var( "value_td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
                    $id = $value->id();

                    $descriptions = $value->descriptions();
                    $t->set_var( "value_description", "" );
                    if ( $SimpleOptionHeaders )
                    {
                        $t->set_var( "value_id", $value->id() );

                        $t->set_var( "value_name", $descriptions[0] );
                        $t->parse( "value_description", "value_description_tpl" );
                    }
                    else
                    {
                        foreach ( $descriptions as $description )
                        {
                            $t->set_var( "value_name", $description );
                            $t->parse( "value_description", "value_description_tpl", true );
                        }
                    }

                    $t->set_var( "value_price", "" );
                    $t->set_var( "value_price_item", "" );
                    $t->set_var( "value_price_currency_list", "" );
                    if ( $ShowPrice and $product->showPrice() == true  )
                    {
                        $price = new eZCurrency( $value->correctPrice( $PricesIncludeVAT, $product ) );
                        if ( $value->price() != 0 )
                            $t->set_var( "value_price", "+".$value->localePrice( $PricesIncludeVAT, $product ) );
                        else
                            $t->set_var( "value_price", "" );

                        $t->parse( "value_price_item", "value_price_item_tpl" );

                        $t->set_var( "value_price_currency_item", "" );
                        foreach ( $currencies as $currency )
                        {
                            $altPrice = $price;
                            $altPrice->setValue( $price->value() * $currency->value() );

                            $currency_locale->setSymbol( $currency->sign() );
                            $currency_locale->setPrefixSymbol( $currency->prefixSign() );

                            $t->set_var( "alt_value_price", $currency_locale->format( $altPrice ) );
                            $t->parse( "value_price_currency_item", "value_price_currency_item_tpl", true );
                        }

                        $t->set_var( "value_price_currency_list", "" );
                        if ( count( $currencies ) > 0 )
                            $t->parse( "value_price_currency_list", "value_price_currency_list_tpl" );
                    }

                    $t->set_var( "value_availability_item", "" );
                    if ( !( is_bool( $value_quantity ) and !$value_quantity ) )
                    {
                        $named_quantity = $value_quantity;
                        if ( $ShowNamedQuantity )
                            $named_quantity = eZProduct::namedQuantity( $value_quantity );
                        $t->set_var( "value_availability", $named_quantity );
                        $t->parse( "value_availability_item", "value_availability_item_tpl" );
                    }

                    $t->parse( "value", "value_tpl", true );
                    $i++;
                }
            }

            if ( $i > 0 )
            {
                $t->set_var( "option_name", $option->name() );
                $t->set_var( "option_description", $option->description() );
                $t->set_var( "option_id", $option->id() );
                // $t->set_var( "product_id", isset( $ProductID ) ? $ProductID : false );

                $t->parse( "option", "option_tpl", true );
            }
        }

        if( isset( $SiteDescriptionOverride ) )
        $SiteDescriptionOverride .= $product->name() . " ";
        // $SiteDescriptionOverride = addslashes( $SiteDescriptionOverride );
        $t->set_var( "product_name", $product->name() );

        $t->set_var( "product_intro_text", eZTextTool::nl2br( $product->brief() ) );

        if ( $ShowPrice and $product->showPrice() == true and $product->hasPrice() )
        {
            $t->set_var( "product_price", $product->localePrice( $PricesIncludeVAT ) );
            $priceRange = $product->correctPriceRange( $PricesIncludeVAT );

            if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) ) and !($product->correctPrice( $PricesIncludeVAT ) > 0) )
            {
                $t->set_var( "product_price", "" );
            }
            $t->parse( "price", "price_tpl" );
        }
        elseif( $product->showPrice() == false )
        {
            $t->set_var( "product_price", "" );
            $t->parse( "price", "price_tpl" );
        }
        else
        {
            $priceArray = "";
            $options =& $product->options();
            if ( count( $options ) == 1 )
            {
                $option = $options[0];
                if ( get_class( $option ) == "ezoption" )
                {
                    $optionValues =& $option->values();
                    if ( count( $optionValues ) > 1 )
                    {
                        $i=0;
                        foreach ( $optionValues as $optionValue )
                        {
                            $priceArray[$i] = $optionValue->localePrice( $PricesIncludeVAT, $product );
                            $i++;
                        }
                        $high = max( $priceArray );
                        $low = min( $priceArray );
                        
                        $t->set_var( "product_price", $low . " - " . $high );
                        
                        $t->parse( "price", "price_tpl" );
                    }
                }
            }
            else
                $t->set_var( "price", "" );
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "action_url", "cart/add" );
        $t->parse( "add_to_cart", "add_to_cart_tpl" );
        $t->parse( "product", "product_tpl", true );
        $i++;
    }
}

$TotalTypes = count( $productList );
eZList::drawNavigator( $t, $TotalTypes, $Limit, $Offset, "product_list_tpl" );


if ( count( $productList ) > 0 )
{
    $t->pparse( "product_list", "product_list_tpl" );
}
else
{
    $t->set_var( "product_list", "" );
}

/*
foreach ( $productList as $product )
{
    
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "category_id", $product->categories()[0]->id() );
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
    // include_once( "classes/ezcachefile.php" );
    $CacheFile = new eZCacheFile( "kernel/eztrade/cache/",
                                  array( "hotdealsgallery", $PriceGroup ),
                                  "cache", "," );
    $output = $t->parse( "output", "product_list_page_tpl" );
    // print the output the first time while printing the cache file.
    print( $output );
    $CacheFile->store( $output );
}
else
{
    $t->pparse( "output", "product_list_page_tpl" );
}
*/
?>