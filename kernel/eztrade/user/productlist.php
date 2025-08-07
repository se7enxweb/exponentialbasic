<?php
// 
// $Id: productlist.php 9741 2002-11-21 08:42:39Z vl $
//
// Created on: <23-Sep-2000 14:46:20 bf>
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
// include_once( "classes/eztexttool.php" );
// include_once( "classes/ezcachefile.php" );
// include_once( "classes/ezlist.php" );

// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezpricegroup.php" );

// sections
// include_once( "ezsitemanager/classes/ezsection.php" );


if ( $CategoryID != 0 )
{
    $GlobalSectionID = eZProductCategory::sectionIDStatic( $CategoryID );
    $CategoryArray = $CategoryID;
}

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();


$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZTradeMain", "Language" );
$Limit = $ini->variable( "eZTradeMain", "ProductLimit" );
$ShowPriceGroups = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$RequireUserLogin = $ini->variable( "eZTradeMain", "RequireUserLogin" ) == "true";
$PricesIncludeVAT = $ini->variable( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;

$CapitalizeHeadlines = $ini->variable( "eZArticleMain", "CapitalizeHeadlines" );

$ThumbnailImageWidth = $ini->variable( "eZTradeMain", "ThumbnailImageWidth" );
$ThumbnailImageHeight = $ini->variable( "eZTradeMain", "ThumbnailImageHeight" );

$ShowPrice = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true";

$SimpleOptionHeaders = $ini->variable( "eZTradeMain", "SimpleOptionHeaders" ) == "true";
$ShowOptionQuantity = $ini->variable( "eZTradeMain", "ShowOptionQuantity" ) == "true";
$RequireQuantity = $ini->variable( "eZTradeMain", "RequireQuantity" ) == "true" ;


$t = new eZTemplate( "kernel/eztrade/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "productlist.php" );

$t->set_file( "product_list_page_tpl", "productlist.tpl" );

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

$t->set_block( "product_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

$t->set_var( "product", "" );
$t->set_var( "module", "" );

if ( !isSet( $ModuleName ) )
    $ModuleName = "trade";
if ( !isset( $ModuleList ) )
    $ModuleList = "productlist";
if ( !isset( $ModuleView ) )
    $ModuleView = "productview";

// makes the section ID available in articleview template
$t->set_var( "section_id", $GlobalSectionID );

$t->set_var( "module", $ModuleName );
$t->set_var( "module_list", $ModuleList );
$t->set_var( "module_view", $ModuleView );

$t->setAllStrings();

$category = new eZProductCategory();
$category->get( $CategoryID );

// path
$pathArray = $category->path();

$t->set_var( "path", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );
    $t->set_var( "category_name", $path[1] );
    $t->parse( "path", "path_tpl", true );

    if( isset( $SiteTitleAppend ) )
    $SiteTitleAppend .= $path[1] . " - ";
}

$categoryList =& $category->getByParent( $category );

$user =& eZUser::currentUser();

// categories
$i = 0;
foreach ( $categoryList as $categoryItem )
{
  if ( eZObjectPermission::hasPermission( $categoryItem->id(), "trade_category", "r", $user ) )
  {
        $t->set_var( "category_id", $categoryItem->id() );
        $t->set_var( "category_name", $categoryItem->name() );
        $t->set_var( "category_description", $categoryItem->description() );

        $parent = $categoryItem->parent();
        
        if ( $categoryItem->parent() != 0 )
        {
            $parent = $categoryItem->parent();
            $t->set_var( "category_parent", $parent->name() );
        }
        else
        {
            $t->set_var( "category_parent", "&nbsp;" );
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        $t->parse( "category", "category_tpl", true );
        $i++;
	}
}

if ( count( $categoryList ) == 0 )
{
    $t->set_var( "category_list", "" );
}
else
{
    $t->parse( "category_list", "category_list_tpl" );
}

if ( !isset( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 10;
if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

// products
$TotalTypes =& $category->productCount( $category->sortMode(), false );
$productList =& $category->activeProducts( $category->sortMode(), $Offset, $Limit, $category->id() );

$locale = new eZLocale( $Language );
$i = 0;

foreach ( $productList as $product )
{
  if ( eZObjectPermission::hasPermission( $category->id(), "trade_category", "r", $user ) )
{
    $t->set_var( "product_id", $product->id() );

    // preview image
    $thumbnailImage = $product->thumbnailImage();
    
    if ( $thumbnailImage )
    {
        $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

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
    
    $t->set_var( "category_id", $category->id() );

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

if ( count( $productList ) > 0 )
{
    $t->parse( "product_list", "product_list_tpl" );
}
else
{
    $t->set_var( "product_list", "" );
}



eZList::drawNavigator( $t, $TotalTypes, $Limit, $Offset, "product_list_page_tpl" );

if ( isset( $GenerateStaticPage ) && $GenerateStaticPage == "true" )
{
    // if ( $user ) {
    //     $CategoryArray =& $user->groups( false );
    // } 
    // else
    // {
    //     $CategoryArray = '';
    // }
  
    if ( !$PriceGroup ) 
      $PriceGroup = 0;
    
    $cache = new eZCacheFile( "kernel/eztrade/cache/", array( "productlist", $CategoryID, $Offset, $PriceGroup ),
                              "cache", "," );
      $cacheFileName = $cache->filename( true );
    // add PHP code in the cache file to store variables
    /*
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";    
    $output .= "?>\n";
    */
    
    $output = $t->parse( "output", "product_list_page_tpl" );
    print( $output ."<br /><br />" );
    $cache->store( $output );
}
else
{
    $t->pparse( "output", "product_list_page_tpl" );
}

?>