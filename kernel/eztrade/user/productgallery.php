<?php
// 
// $Id: productgallery.php,v 1.1.2.1 2002/05/14 13:02:54 kracker Exp $
//
// Created on: <24-Feb-2002 17:53:20 ghb>
//
// This source file is part of XP publish, based on eZ publish.
//
// Copyright (C) 2001-2002 Brookins Consulting.  All rights reserved.
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
include_once( "classes/eztexttool.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezlist.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

// sections
include_once( "ezsitemanager/classes/ezsection.php" );


if ( $CategoryID != 0 )
{
    $GlobalSectionID = eZProductCategory::sectionIDStatic( $CategoryID );
}

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();


$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$Limit = $ini->read_var( "eZTradeMain", "ProductGalleryLimit" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$RequireUserLogin = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "true";
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;

$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );

$ThumbnailImageWidth = $ini->read_var( "eZTradeMain", "ThumbnailImageWidth" );
$ThumbnailImageHeight = $ini->read_var( "eZTradeMain", "ThumbnailImageHeight" );


$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "productgallery.php" );

$t->set_file( "product_gallery_page_tpl", "productgallery.tpl" );


$t->set_block( "product_gallery_page_tpl", "product_catalog_number_tpl", "product_catalog_number" );

//$t->set_block( "product_tpl", "product_catalog_number_tpl", "product_catalog_number" );

$t->set_block( "product_gallery_page_tpl", "price_tpl", "price" );
$t->set_block( "product_gallery_page_tpl", "add_to_cart_tpl", "add_to_cart" );

$t->set_block( "product_gallery_page_tpl", "path_tpl", "path" );
$t->set_block( "product_gallery_page_tpl", "product_gallery_tpl", "product_gallery" );

$t->set_block( "product_gallery_tpl", "product_tpl", "product" );
$t->set_block( "product_tpl", "product_image_tpl", "product_image" );

$t->set_block( "product_tpl", "product_group_begin_tpl", "product_group_begin" );
$t->set_block( "product_tpl", "product_group_end_tpl", "product_group_end" );

$t->set_block( "product_gallery_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

if ( !isSet( $ModuleName ) )
    $ModuleName = "trade";
if ( !isSet( $ModuleList ) )
    $ModuleList = "productgallery";
if ( !isSet( $ModuleView ) )
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

    $SiteTitleAppend .= $path[1] . " - ";
}

$categoryList =& $category->getByParent( $category );

// categories
$i = 0;

foreach ( $categoryList as $categoryItem )
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

if ( count( $categoryList ) == 0 )
{
    $t->set_var( "category_list", "" );
}
else
{
    $t->parse( "category_list", "category_list_tpl" );
}

if ( !isSet( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 10;
if ( !isSet( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

// products
//$category->setSortMode(2);
$category->setSortMode(5);
$TotalTypes =& $category->productCount( $category->sortMode(), false );
//$productgallery =& $category->activeProducts( $category->sortMode(), $Offset, $Limit );
$productgallery =& $category->activeProducts( "alphanumeric_asc", $Offset, $Limit );

$locale = new eZLocale( $Language );
$i = 0;
$groupi = 0;

foreach ( $productgallery as $product )
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

    $SiteDescriptionOverride .= $product->name() . " ";

    $t->set_var( "product_name", $product->name() );
	
	$t->set_var( "product_number", "" );

	if ( $product->productNumber() != "" )
	{
	    $t->set_var( "product_number", $product->productNumber() );
	    // $t->parse( "product_number", "product_number_item_tpl" );
	}

	$t->set_var( "catalog_number", "" );
        $t->set_var( "product_catalog_number", "" );

        if ( $product->catalogNumber() != "" )
	  {
            $t->set_var( "catalog_number", $product->catalogNumber() );   
	    $t->parse( "product_catalog_number", "product_catalog_number_tpl" );
	  }

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

    $galleryarraysize =  sizeof($productgallery) - 1;
    $groupi_size = $groupi; // - 1;
    $groupitemnumber = 3;
    $groupitempreceeding = $groupitemnumber - 1;

    if ( $i == 0 )
    {
        $t->parse( "product_group_begin", "product_group_begin_tpl" );
        $t->set_var( "product_group_end", "" );
    } 
    elseif ( $i == $galleryarraysize ) {
        $t->parse( "product_group_end", "product_group_end_tpl" );
        $t->set_var( "product_group_begin", "" );
    }
    elseif ( $groupi_size == $groupitempreceeding ) {
        $t->parse( "product_group_end", "product_group_end_tpl" );
        $t->set_var( "product_group_begin", "" );
    }
    elseif ( $groupi_size == $groupitemnumber ) {
        $groupi = 0;
        $t->parse( "product_group_begin", "product_group_begin_tpl" );
        $t->set_var( "product_group_end", "" );
    }
    else
    {
	/* Debug */ 
         /*
        $t->set_var( "product_group_begin", "$i / $groupi<br />" );
        $t->set_var( "product_group_end", "$i / $groupi<br />" );
	*/
	$t->set_var( "product_group_begin", "" );
        $t->set_var( "product_group_end", "" );
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
    $groupi++;
}

if ( count( $productgallery ) > 0 )
{
    $t->parse( "product_gallery", "product_gallery_tpl" );
}
else
{
    $t->set_var( "product_gallery", "" );
}



eZList::drawNavigator( $t, $TotalTypes, $Limit, $Offset, "product_gallery_page_tpl" );

if ( $GenerateStaticPage == "true" )
{
    if ( $user )
        $CategoryArray =& $user->groups( false );
    $cache = new eZCacheFile( "kernel/eztrade/cache/", array( "productgallery", $CategoryArray, $Offset, $PriceGroup ),
                              "cache", "," );

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";    
    $output .= "?>\n";
    
    $output = $t->parse( "output", "product_gallery_page_tpl" );
    print( $output );
    $CacheFile->store( $output );
}
else
{
    $t->pparse( "output", "product_gallery_page_tpl" );
}

?>
