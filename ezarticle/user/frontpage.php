<?php
//
// $Id: frontpage.php 9900 2004-07-08 13:48:56Z br $
//
// Created on: <30-May-2001 14:06:59 bf>
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
// include_once( "classes/ezlist.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

// include_once( "ezad/classes/ezadcategory.php" );
// include_once( "ezad/classes/ezad.php" );

//$CategoryID = $url_array[3];
global $CategoryID;

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$ImageDir = $ini->read_var( "eZArticleMain", "ImageDir" );
$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );
$DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );
$GrayScaleImageList = $ini->read_var( "eZArticleMain", "GrayScaleImageList" );


$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "frontpage.php" );

$t->setAllStrings();

$t->set_file( "article_list_page_tpl", "frontpage.tpl" );

$t->set_block( "article_list_page_tpl", "header_item_tpl", "header_item" );


// one column article
$t->set_block( "article_list_page_tpl", "element_list_tpl", "element_list" );
$t->set_block( "article_list_page_tpl", "one_column_article_tpl", "one_column_article" );
$t->set_block( "one_column_article_tpl", "one_column_article_image_tpl", "one_column_article_image" );
$t->set_block( "one_column_article_tpl", "one_column_read_more_tpl", "one_column_read_more" );

// one column product
$t->set_block( "article_list_page_tpl", "one_column_product_tpl", "one_column_product" );
$t->set_block( "one_column_product_tpl", "product_image_tpl", "product_image" );
$t->set_block( "one_column_product_tpl", "price_tpl", "price" );

// two column product
$t->set_block( "article_list_page_tpl", "two_column_product_tpl", "two_column_product" );
$t->set_block( "two_column_product_tpl", "left_product_tpl", "left_product" );
$t->set_block( "left_product_tpl", "left_product_image_tpl", "left_product_image" );
$t->set_block( "left_product_tpl", "left_price_tpl", "left_price" );

$t->set_block( "two_column_product_tpl", "right_product_tpl", "right_product" );
$t->set_block( "right_product_tpl", "right_product_image_tpl", "right_product_image" );
$t->set_block( "right_product_tpl", "right_price_tpl", "right_price" );

// two column article
$t->set_block( "article_list_page_tpl", "two_column_article_tpl", "two_column_article" );
$t->set_block( "two_column_article_tpl", "left_article_tpl", "left_article" );
$t->set_block( "left_article_tpl", "left_article_image_tpl", "left_article_image" );
$t->set_block( "left_article_tpl", "left_read_more_tpl", "left_read_more" );

$t->set_block( "two_column_article_tpl", "right_article_tpl", "right_article" );
$t->set_block( "right_article_tpl", "right_article_image_tpl", "right_article_image" );
$t->set_block( "right_article_tpl", "right_read_more_tpl", "right_read_more" );

// short single article
$t->set_block( "article_list_page_tpl", "one_short_article_tpl", "one_short_article" );
$t->set_block( "one_short_article_tpl", "short_read_more_tpl", "short_read_more" );

// banner ad
$t->set_block( "article_list_page_tpl", "ad_column_tpl", "ad_column" );
$t->set_block( "ad_column_tpl", "standard_ad_tpl", "standard_ad" );
$t->set_block( "ad_column_tpl", "html_ad_tpl", "html_ad" );

$t->set_var( "element_list", "" );

$t->set_var( "one_column_article", "" );
$t->set_var( "two_column_article", "" );
$t->set_var( "two_column_product", "" );
$t->set_var( "one_short_article", "" );
$t->set_var( "ad_column", "" );
$t->set_var( "one_column_product", "" );


// image dir
$t->set_var( "image_dir", $ImageDir );

$articleCount = 0;
$productCount = 0;
$adCount = 0;

// section
$t->set_var( "section_id", $GlobalSectionID );

$rows =& $sectionObject->frontPageRows();

$page_element = array();
$offsetArticleArray = array();
$offsetProductArray = array();

$tempArticle = new eZArticle();
$articleList = array();

if ( is_array ( $rows ) and count ( $rows ) > 0 )
{
    foreach ( $rows as $row )
    {
        $value = $row->settingByID( $row->settingID() );
        if (!isset($offsetArticleArray[$row->categoryID()])) 
        {
        	$offsetArticleArray[$row->categoryID()] = 0;
        }
        if ( $value == "2column" )
        {
            $category = new eZArticleCategory( $row->categoryID() );
            if ( $category->id() == "0" )
            {
                $articleList = @array_merge( $articleList, $tempArticle->articles( "time", false, $offsetArticleArray[$row->categoryID()], 2 ) );
            }
            else
            {
                $articleList = @array_merge( $articleList, eZArticleCategory::articles( $category->sortMode(), false, true, $offsetArticleArray[$row->categoryID()], 2, $row->categoryID() ) );
            }

            $offsetArticleArray[$row->categoryID()] = $offsetArticleArray[$row->categoryID()] + 2;
        }
        if ( $value == "1column" || $value == "1short" )
        {
            $category = new eZArticleCategory( $row->categoryID() );
            if ( $category->id() == "0" )
            {
                $articleList = @array_merge( $articleList, $tempArticle->articles( "time", false, $offsetArticleArray[$row->categoryID()], 1 ) );
            }
            else
            {
                $articleList = @array_merge( $articleList, eZArticleCategory::articles( $category->sortMode(), false, true, $offsetArticleArray[$row->categoryID()], 1, $row->categoryID() ) );
            }
            $offsetArticleArray[$row->categoryID()] = $offsetArticleArray[$row->categoryID()] + 1;
        }
        if ( $value == "ad"  )
        {
            $category = new eZAdCategory( $row->categoryID() );
            $adList =& array_merge( $adList, $category->ads( "count", false, $offsetAdArray[$row->categoryID()], 1 ) );
            $offsetAdArray[$row->categoryID()] = $offsetAdArray[$row->categoryID()] + 1;
        }
        if ( $value == "1columnProduct" )
        {
            $category = new eZProductCategory( $row->categoryID() );
            if (!isset($offsetProductArray[$row->categoryID()])) 
            {
            	$offsetProductArray[$row->categoryID()] = 0;
            }
            if (isset($productList))
            {
            	$productList = array_merge( $productList, eZProductCategory::products( $category->sortMode(), false, $offsetProductArray[$row->categoryID()], 1, false, $row->categoryID() ) );
            }
            else
            {
            	$productList = eZProductCategory::products( $category->sortMode(), false, $offsetProductArray[$row->categoryID()], 1, false, $row->categoryID() );
            }
            $offsetProductArray[$row->categoryID()] = $offsetProductArray[$row->categoryID()] + 1;
        }
        if ( $value == "2columnProduct" )
        {
            $category = new eZProductCategory( $row->categoryID() );
            $productList =& array_merge( $productList, eZProductCategory::products( $category->sortMode(), false, $offsetProductArray[$row->categoryID()], 2, false, $row->categoryID() ) );
            $offsetProductArray[$row->categoryID()] = $offsetProductArray[$row->categoryID()] + 2;
        }
        $page_elements[] = $value;
    }
}

$user =& eZUser::currentUser();

//$sectionObject->setOverrideVariables();

if ( $adCount > 0 )
{
    // include_once( "ezad/classes/ezadcategory.php" );
    // include_once( "ezad/classes/ezad.php" );

    $adCategory = new eZAdCategory( $FrontPageAdCategory );

    $adList =& $adCategory->ads( "count", false, 0, $adCount );
}

$t->set_var( "category_current_id", $CategoryID);

$locale = new eZLocale( $Language );
$i=0;

$articleOffset = 0;
$productOffset = 0;
$adOffset = 0;
$pageContents = "";
$counter = -1;
// render the page elements
if ( count( $page_elements ) > 0 )
foreach ( $page_elements as $element )
{
    $counter++;

    switch ( $element )
    {
        case "1column":
        {
            $article =& $articleList[$articleOffset];

            if ( is_a( $article, "eZArticle" ) )
                $pageContents .= renderFrontpageArticle( $t, $locale, $article );

            $articleOffset++;
        }break;

        case "2column":
        {
            $article1 =& $articleList[$articleOffset];
            $articleOffset++;
            $article2 =& $articleList[$articleOffset];

            if ( is_a( $article1, "eZArticle" ) && is_a( $article2, "eZArticle" ) )
                $pageContents .= renderFrontpageArticleDouble( $t, $locale, $article1, $article2 );

            $articleOffset++;
        }break;

        case "1short":
        {
            $article =& $articleList[$articleOffset];

            if ( is_a( $article, "eZArticle" ) )
                $pageContents .= renderShortSingleArticle( $t, $locale, $article );

            $articleOffset++;
        }break;

        case "ad":
        {
            $ad =& $adList[$adOffset];
            if ( is_a( $ad, "eZAd" ) )
                $pageContents .= renderAd( $t, $locale, $ad );

            $adOffset++;
        }break;

        case "1columnProduct":
        {
            $product =& $productList[$productOffset];
            if ( is_a( $product, "eZProduct" ) )
                $pageContents .= renderFrontpageProduct( $t, $locale, $product );

            $productOffset++;
        }break;

        case "2columnProduct":
        {
            $product1 =& $productList[$productOffset];
            $productOffset++;
            $product2 =& $productList[$productOffset];

            if ( is_a( $product1, "eZProduct" ) && is_a( $product2, "eZProduct" ) )
                $pageContents .= renderFrontpageProductDouble( $t, $locale, $product1, $product2 );

            $productOffset++;
        }break;


    }
}
$t->set_var( "element_list", $pageContents );


function &renderFrontpageArticle( &$t, &$locale, &$article )
{
    global $ini, $counter, $rows, $GrayScaleImageList;

    $DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );

    $aid = $article->id();

    $CategoryID = $rows[$counter]->CategoryID;

    if ( $CategoryID == 0 )
    {
        $category =& $article->categoryDefinition();
        $CategoryID = $category->id();
    }

    $t->set_var( "category_id", $CategoryID );

    $t->set_var( "article_id", $article->id() );
    $t->set_var( "article_name", $article->name() );

    $t->set_var( "author_text", $article->authorText() );

    $categoryDef =& $article->categoryDefinition();

    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );

    // preview image
    $thumbnailImage =& $article->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZArticleMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZArticleMain", "ThumbnailImageHeight" ), $convertToGray );

        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "one_column_article_image", "one_column_article_image_tpl" );
    }
    else
    {
        $t->set_var( "one_column_article_image", "" );
    }

    $published = $article->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "article_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "article_timevalue", $locale->format( $publishedTimeValue ) );


    $t->set_var( "article_published", $locale->format( $published ) );


    $renderer = new eZArticleRenderer( $article );
    $t->set_var( "article_intro", $renderer->renderIntro(  ) );


    if ( $article->linkText() != "" )
    {
        $t->set_var( "article_link_text", $article->linkText() );
    }
    else
    {
        $t->set_var( "article_link_text", $DefaultLinkText );
    }

    // check if the article contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "one_column_read_more", "" );
    }
    else
    {
        $t->parse( "one_column_read_more", "one_column_read_more_tpl" );
    }


    return $t->parse( "output", "one_column_article_tpl" );
}

function &renderFrontpageArticleDouble( &$t, &$locale, &$article1, &$article2 )
{
    global $ini, $counter, $rows, $GrayScaleImageList;
    $aid = $article1->id();

    $DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );

    $CategoryID = $rows[$counter]->CategoryID;

    if ( $CategoryID == 0 )
    {
	$category =& $article1->categoryDefinition();
	$CategoryID = $category->id();
    }

    $t->set_var( "category_id", $CategoryID );

    $t->set_var( "article_id", $article1->id() );
    $t->set_var( "article_name", $article1->name() );
    $t->set_var( "author_text", $article1->authorText() );

    $categoryDef =& $article1->categoryDefinition();
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );

    // preview image
    $thumbnailImage =& $article1->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZArticleMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZArticleMain", "ThumbnailImageHeight" ), $convertToGray );

        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "left_article_image", "left_article_image_tpl" );
    }
    else
    {
        $t->set_var( "left_article_image", "" );
    }

    $published = $article1->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "article_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "article_timevalue", $locale->format( $publishedTimeValue ) );


    $t->set_var( "article_published", $locale->format( $published ) );


    $renderer = new eZArticleRenderer( $article1 );
    $t->set_var( "article_intro", $renderer->renderIntro(  ) );


    if ( $article1->linkText() != "" )
    {
        $t->set_var( "article_link_text", $article1->linkText() );
    }
    else
    {
        $t->set_var( "article_link_text", $DefaultLinkText );
    }

    // check if the article contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "left_read_more", "" );
    }
    else
    {
        $t->parse( "left_read_more", "left_read_more_tpl" );
    }

    $t->parse( "left_article", "left_article_tpl"  );
    $aid = $article2->id();

//    if ( $CategoryID == 0 )
//    {
//        $category =& $article2->categoryDefinition();
//        $CategoryID = $category->id();
//    }
//    $category =& $article2->categoryDefinition();
//    $CategoryID = $category->id();

    $t->set_var( "category_id", $CategoryID );

    $t->set_var( "article_id", $article2->id() );
    $t->set_var( "article_name", $article2->name() );
    $t->set_var( "author_text", $article2->authorText() );

    $categoryDef =& $article2->categoryDefinition();
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );

    // preview image
    $thumbnailImage =& $article2->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZArticleMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZArticleMain", "ThumbnailImageHeight" ), $convertToGray );

        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "right_article_image", "right_article_image_tpl" );
    }
    else
    {
        $t->set_var( "right_article_image", "" );
    }

    $published = $article2->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "article_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "article_timevalue", $locale->format( $publishedTimeValue ) );


    $t->set_var( "article_published", $locale->format( $published ) );


    $renderer = new eZArticleRenderer( $article2 );
    $t->set_var( "article_intro", $renderer->renderIntro(  ) );


    if ( $article2->linkText() != "" )
    {
        $t->set_var( "article_link_text", $article2->linkText() );
    }
    else
    {
        $t->set_var( "article_link_text", $DefaultLinkText );
    }

    // check if the article contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "right_read_more", "" );
    }
    else
    {
        $t->parse( "right_read_more", "right_read_more_tpl" );
    }

    $t->parse( "right_article", "right_article_tpl"  );

    return $t->parse( "output", "two_column_article_tpl" );
}

function &renderShortSingleArticle( &$t, &$locale, &$article )
{
    global $ini, $counter, $rows, $GrayScaleImageList;

    $aid = $article->id();

    $DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );

    $CategoryID = $rows[$counter]->CategoryID;

    if ( $CategoryID == 0 )
    {
	$category =& $article->categoryDefinition();
	$CategoryID = $category->id();
    }

    $t->set_var( "category_id", $CategoryID );

    $t->set_var( "article_id", $article->id() );
    $t->set_var( "article_name", $article->name() );

    $t->set_var( "author_text", $article->authorText() );

    $categoryDef =& $article->categoryDefinition();

    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );

    $published = $article->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "article_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "article_timevalue", $locale->format( $publishedTimeValue ) );

    $t->set_var( "article_published", $locale->format( $published ) );


    if ( $article->linkText() != "" )
    {
        $t->set_var( "article_link_text", $article->linkText() );
    }
    else
    {
        $t->set_var( "article_link_text", $DefaultLinkText );
    }


    return $t->parse( "output", "one_short_article_tpl" );
}

function &renderAd( &$t, &$locale, &$ad )
{
    global $ini;

    if ( $ad->useHTML() )
    {
        $t->set_var( "standard_ad", "" );

        $t->set_var( "html_ad_contents", $ad->htmlBanner() );
        $t->parse( "html_ad", "html_ad_tpl" );
    }
    else
    {
        $t->set_var( "html_ad", "" );
        $image =& $ad->image();

        if ( $image )
        {
            $imgSRC =& $image->filePath();
            $imgWidth =& $image->width();
            $imgHeight =& $image->height();
        }

        $t->set_var( "ad_id", $ad->id() );
        $t->set_var( "image_src", $imgSRC );
        $t->set_var( "image_width", $imgWidth );
        $t->set_var( "image_height", $imgHeight );
        $t->parse( "standard_ad", "standard_ad_tpl" );
    }

    $ad->addPageView();

    return $t->parse( "output", "ad_column_tpl" );
}


function &renderFrontpageProduct( &$t, &$locale, &$product )
{
    global $ini;
    $i=0;
    $pid = $product->id();

    $ThumbnailImageWidth = $ini->read_var( "eZTradeMain", "ThumbnailImageWidth" );
    $ThumbnailImageHeight = $ini->read_var( "eZTradeMain", "ThumbnailImageHeight" );

    $PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" );

    // preview image
    $thumbnailImage = $product->thumbnailImage();
    $t->set_var( "product_id", $product->id() );

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

    $t->set_var( "product_name", $product->name() );

    $t->set_var( "product_intro_text", eZTextTool::nl2br( $product->brief() ) );

    $categoryDefinition = $product->categoryDefinition();
    $t->set_var( "category_id", $categoryDefinition->id() );

    if ( $product->showPrice() == true and $product->hasPrice() )
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
            if ( is_a( $option, "eZOption" ) )
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

    return $t->parse( "output", "one_column_product_tpl" );
}


function &renderFrontpageProductDouble( &$t, &$locale, &$product1, &$product2 )
{
    global $ini;
    $pid = $product1->id();

    $PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" );

    $ThumbnailImageWidth = $ini->read_var( "eZTradeMain", "ThumbnailImageWidth" );
    $ThumbnailImageHeight = $ini->read_var( "eZTradeMain", "ThumbnailImageHeight" );

    // preview image
    $thumbnailImage = $product1->thumbnailImage();

    if ( $thumbnailImage )
    {
        $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight );

        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "left_product_image", "left_product_image_tpl" );
    }
    else
    {
        $t->set_var( "left_product_image", "" );
    }

    $t->set_var( "product_name", $product1->name() );
    $t->set_var( "product_id", $product1->id() );

    $t->set_var( "product_intro_text", eZTextTool::nl2br( $product1->brief() ) );

    $categoryDefinition = $product1->categoryDefinition();
    $t->set_var( "category_id", $categoryDefinition->id() );

    if ( $product1->showPrice() == true and $product1->hasPrice() )
    {
        $t->set_var( "product_price", $product1->localePrice( $PricesIncludeVAT ) );
        $priceRange = $product1->correctPriceRange( $PricesIncludeVAT );

        if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) ) and !($product1->correctPrice( $PricesIncludeVAT ) > 0) )
        {
            $t->set_var( "product_price", "" );
        }
        $t->parse( "left_price", "left_price_tpl" );
    }
    elseif( $product1->showPrice() == false )
    {
        $t->set_var( "product_price", "" );
        $t->parse( "left_price", "left_price_tpl" );
    }
    else
    {
        $priceArray = "";
        $options =& $product1->options();
        if ( count( $options ) == 1 )
        {
            $option = $options[0];
            if ( is_a( $option, "eZOption" ) )
            {
                $optionValues =& $option->values();
                if ( count( $optionValues ) > 1 )
                {
                    $i=0;
                    foreach ( $optionValues as $optionValue )
                    {
                        $priceArray[$i] = $optionValue->localePrice( $PricesIncludeVAT, $product1 );
                        $i++;
                    }
                    $high = max( $priceArray );
                    $low = min( $priceArray );

                    $t->set_var( "product_price", $low . " - " . $high );

                    $t->parse( "left_price", "left_price_tpl" );
                }
            }
        }
        else
            $t->set_var( "left_price", "" );
    }


    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "left_product", "left_product_tpl"  );

    $pid = $product2->id();

    // preview image
    $thumbnailImage = $product2->thumbnailImage();

    $t->set_var( "product_id", $product2->id() );

    if ( $thumbnailImage )
    {
        $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight );

        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "right_product_image", "right_product_image_tpl" );
    }
    else
    {
        $t->set_var( "right_product_image", "" );
    }

    $t->set_var( "product_name", $product2->name() );


    $categoryDefinition = $product2->categoryDefinition();
    $t->set_var( "category_id", $categoryDefinition->id() );

    $t->set_var( "product_intro_text", eZTextTool::nl2br( $product2->brief() ) );

    if ( $product2->showPrice() == true and $product2->hasPrice() )
    {
        $t->set_var( "product_price", $product2->localePrice( $PricesIncludeVAT ) );
        $priceRange = $product2->correctPriceRange( $PricesIncludeVAT );

        if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) ) and !($product2->correctPrice( $PricesIncludeVAT ) > 0) )
        {
            $t->set_var( "product_price", "" );
        }
        $t->parse( "right_price", "right_price_tpl" );
    }
    elseif( $product2->showPrice() == false )
    {
        $t->set_var( "product_price", "" );
        $t->parse( "right_price", "right_price_tpl" );
    }
    else
    {
        $priceArray = "";
        $options =& $product2->options();
        if ( count( $options ) == 1 )
        {
            $option = $options[0];
            if ( is_a( $option, "eZOption" ) )
            {
                $optionValues =& $option->values();
                if ( count( $optionValues ) > 1 )
                {
                    $i=0;
                    foreach ( $optionValues as $optionValue )
                    {
                        $priceArray[$i] = $optionValue->localePrice( $PricesIncludeVAT, $product2 );
                        $i++;
                    }
                    $high = max( $priceArray );
                    $low = min( $priceArray );

                    $t->set_var( "product_price", $low . " - " . $high );

                    $t->parse( "right_price", "right_price_tpl" );
                }
            }
        }
        else
            $t->set_var( "right_price", "" );
    }


    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "right_product", "right_product_tpl"  );

    return $t->parse( "output", "two_column_product_tpl" );
}



$t->pparse( "output", "article_list_page_tpl" );

// set variables for meta information
// $SiteTitleAppend = $category->name();

if ( isset( $GenerateStaticPage ) && $GenerateStaticPage == "true" )
{
    $fp = eZFile::fopen( $cachedFile, "w+");

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";
    $output .= "\$eZLanguageOverride=\"$eZLanguageOverride\";\n";
    $output .= "?>\n";

    $output .= ob_get_contents();

    // print the output the first time while printing the cache file.
    fwrite ( $fp, $output );
    fclose( $fp );
}

?>

