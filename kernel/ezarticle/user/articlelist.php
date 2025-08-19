<?php
//
// $Id: articlelist.php 9880 2003-07-24 11:07:55Z br $
//
// Created on: <18-Oct-2000 14:41:37 bf>
//
// This source file is part of Exponential Basic, publishing software.
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
// include_once( "classes/eztexttool.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

$GlobalSectionID = eZArticleCategory::sectionIDStatic( $CategoryID );

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZArticleMain", "Language" );
$ImageDir = $ini->variable( "eZArticleMain", "ImageDir" );
$CapitalizeHeadlines = $ini->variable( "eZArticleMain", "CapitalizeHeadlines" );
$DefaultLinkText =  $ini->variable( "eZArticleMain", "DefaultLinkText" );
$UserListLimit = $ini->variable( "eZArticleMain", "UserListLimit" );
$GrayScaleImageList = $ini->variable( "eZArticleMain", "GrayScaleImageList" );
$ForceCategoryDefinition = $ini->variable( "eZArticleMain", "ForceCategoryDefinition" );
$NoArticleHeader = false;
$TemplateDir = $ini->variable( "eZArticleMain", "TemplateDir" );

if ( isset( $CategoryID ) )
{
    $GlobalSectionID = eZArticleCategory::sectionIDStatic( $CategoryID );
}

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$templateDirTmp = $sectionObject->templateStyle();
if ( !is_null( $templateDirTmp) && trim( $templateDirTmp ) != "" )
{
    $TemplateDir = preg_replace( "/(.+)\/.+(\/?)/", "/\\1/$templateDirTmp\\2", $TemplateDir );
}


$t = new eZTemplate( "kernel/ezarticle/user/" . $ini->variable( "eZArticleMain", "TemplateDir" ),
                     "kernel/ezarticle/user/intl/", $Language, "articlelist.php" );

$t->setAllStrings();

// override template for the current category
$override = "_override_$CategoryID";
// override template for current section
// category override will be prefered
$sectionOverride = "_sectionoverride_$GlobalSectionID";


if ( file_exists( "kernel/ezarticle/user/$TemplateDir/articlelist" . $override . ".tpl" ) )
{
    $t->set_file( "article_list_page_tpl", "articlelist" . $override  . ".tpl"  );
}
else
{
    if ( file_exists( "kernel/ezarticle/user/$TemplateDir/articlelist" . $sectionOverride  . ".tpl" ) )
    {
        $t->set_file( "article_list_page_tpl", "articlelist" . $sectionOverride  . ".tpl"  );
    }
    else
    {
        $t->set_file( "article_list_page_tpl", "articlelist.tpl"  );
    }
}

$t->set_block( "article_list_page_tpl", "header_item_tpl", "header_item" );

// headline
$t->set_block( "header_item_tpl", "latest_headline_tpl", "latest_headline" );
$t->set_block( "header_item_tpl", "category_headline_item_tpl", "category_headline_item" );

// path
$t->set_block( "article_list_page_tpl", "path_item_tpl", "path_item" );

// article
$t->set_block( "article_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

// image
$t->set_block( "article_list_page_tpl", "current_image_item_tpl", "current_image_item" );

$t->set_block( "category_item_tpl", "image_item_tpl", "image_item" );
$t->set_block( "category_item_tpl", "no_image_tpl", "no_image" );

// product
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );

$t->set_block( "article_item_tpl", "article_date_tpl", "article_date" );
$t->set_block( "article_item_tpl", "headline_with_link_tpl", "headline_with_link" );
$t->set_block( "article_item_tpl", "headline_without_link_tpl", "headline_without_link" );

$t->set_block( "article_item_tpl", "article_image_tpl", "article_image" );
$t->set_block( "article_item_tpl", "read_more_tpl", "read_more" );
$t->set_block( "article_item_tpl", "article_topic_tpl", "article_topic" );
$t->set_block( "article_item_tpl", "message_count_tpl", "message_count" );

// prev/next
$t->set_block( "article_list_page_tpl", "previous_tpl", "previous" );
$t->set_block( "article_list_page_tpl", "next_tpl", "next" );

// print headline
if ( isset( $CategoryID ) && $CategoryID == 0 )
{
    $t->parse( "latest_headline", "latest_headline_tpl" );
    $t->set_var( "category_headline_item", "" );
}
else
{
    $t->parse( "category_headline_item", "category_headline_tpl" );
    $t->set_var( "latest_headline", "" );
    $t->set_var( "latest_headline_item", "" );
}

// read user override variables for image size
$ThumbnailImageWidth = $ini->variable( "eZArticleMain", "ThumbnailImageWidth" );
$ThumbnailImageHeight = $ini->variable( "eZArticleMain", "ThumbnailImageHeight" );

$thumbnailImageWidthOverride =& $t->get_user_variable( "article_list_page_tpl",  "ThumbnailImageWidth" );
if ( $thumbnailImageWidthOverride )
{
    $ThumbnailImageWidth = $thumbnailImageWidthOverride;
}

$thumbnailImageHeightOverride =& $t->get_user_variable( "article_list_page_tpl",  "ThumbnailImageHeight" );
if ( $thumbnailImageHeightOverride )
{
    $ThumbnailImageHeight = $thumbnailImageHeightOverride;
}


// image dir
$t->set_var( "image_dir", $ImageDir );

// makes the section ID available in articleview template
$t->set_var( "section_id", $GlobalSectionID );

$category = new eZArticleCategory( $CategoryID );

$t->set_var( "current_category_name", $category->name() );

//EP: CategoryDescriptionXML=enabled, description go in XML -------------------
if ( $ini->variable( "eZArticleMain", "CategoryDescriptionXML" ) == "enabled" )
{
    if ($CategoryID)
    {
	// include_once( "ezarticle/classes/ezarticlerenderer.php" );

        $article = new eZArticle();
	    $article->setContents ($category->description(false));

        $renderer = new eZArticleRenderer( $article );

	    $t->set_var( "current_category_description", $renderer->renderIntro() );
    }
    else
    {
	$t->set_var( "current_category_description", "" );
    }
}
else
{
    $t->set_var( "current_category_description", eZTextTool::nl2br( $category->description() ) );
}
//EP ---------------------------------------------------------------------------

if ( isset( $NoArticleHeader ) and $NoArticleHeader == true )
{
    $t->set_var( "header_item", "" );
}
else
{
    $t->parse( "header_item", "header_item_tpl" );
}

$SiteTitleAppend = "";

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    if ( $CapitalizeHeadlines == "enabled" )
    {
        // include_once( "classes/eztexttool.php" );
        $t->set_var( "category_name", eZTextTool::capitalize(  $path[1] ) );
    }
    else
    {
        $t->set_var( "category_name", $path[1] );
    }

    $SiteTitleAppend .= $path[1] . " - ";

    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList = $category->getByParent( $category );

$user =& eZUser::currentUser();

// current category image
$image =& $category->image();

$t->set_var( "current_image_item", "" );

if ( ( is_a( $image, "eZImage" ) ) && ( $image->id() != 0 ) )
{
    $imageWidth =& $ini->variable( "eZArticleMain", "CategoryImageWidth" );
    $imageHeight =& $ini->variable( "eZArticleMain", "CategoryImageHeight" );

    $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

    $imageURL = "/" . $variation->imagePath();
    $imageWidth =& $variation->width();
    $imageHeight =& $variation->height();
    $imageCaption =& $image->caption();
    $imageDescription =& $image->description();

    $photographer =& $image->photographer();

    $t->set_var( "current_image_width", $imageWidth );
    $t->set_var( "current_image_height", $imageHeight );
    $t->set_var( "current_image_url", $imageURL );
    $t->set_var( "current_image_caption", $imageCaption );
    $t->set_var( "current_image_description", $imageDescription );
    $t->set_var( "current_image_photographer", $photographer->name() );

    $t->parse( "current_image_item", "current_image_item_tpl" );
}
else
{
    $t->set_var( "current_image_item", "" );
}

// categories
$i = 0;
$t->set_var( "category_list", "" );
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_name", $categoryItem->name() );

    $parent = $categoryItem->parent();
    $image =& $categoryItem->image();

    $t->set_var( "image_item", "" );

    if ( ( is_a( $image, "eZImage" ) ) && ( $image->id() != 0 ) )
    {
        $imageWidth =& $ini->variable( "eZArticleMain", "CategoryImageWidth" );
        $imageHeight =& $ini->variable( "eZArticleMain", "CategoryImageHeight" );

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


    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_alt", "1" );
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_alt", "2" );
        $t->set_var( "td_class", "bgdark" );
    }

    //EP: CategoryDescriptionXML=enabled, description go in XML -------------------
    if ( $ini->variable( "eZArticleMain", "CategoryDescriptionXML" ) == "enabled" )
    {
	// include_once( "ezarticle/classes/ezarticlerenderer.php" );

        $article = new eZArticle();
	    $article->setContents ($categoryItem->description(false));

        $renderer = new eZArticleRenderer( $article );

        $t->set_var( "category_description", $renderer->renderIntro() );
    }
    else
    {
	    $t->set_var( "category_description", $categoryItem->description() );
    }

    //EP ---------------------------------------------------------------------------

    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0 )
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// set the offset/limit
if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

if ( ( $category->listLimit() > 0 ) && $Offset == 0 )
    $Limit = $category->listLimit();
else
    $Limit = $UserListLimit;

if ( $CategoryID == 0 )
{
    // do not set offset for the main page news
    // always sort by publishing date is the merged category
    $article = new eZArticle();
    $articleList =& $article->articles( "time", false, $Offset, $Limit );
    $articleCount = $article->articleCount( false );
}
else
{
    $articleList =& $category->articles( $category->sortMode(), false, true, $Offset, $Limit, $category->id() );
    $articleCount = $category->articleCount( false, true  );
}

$t->set_var( "category_current_id", $CategoryID );

$locale = new eZLocale( $Language );
$i = 0;
$t->set_var( "article_list", "" );

$SiteDescriptionOverride = "";
foreach ( $articleList as $article )
{
    $categoryDef =& $article->categoryDefinition();

    $t->set_var( "category_id", $CategoryID );

    if ( $ForceCategoryDefinition == "enabled" )
    {
        $t->set_var( "category_id", $categoryDef->id() );
    }
    else if ( $CategoryID == 0 )
    {
        $t->set_var( "category_id", $categoryDef->id() );
    }


    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );

    $t->set_var( "article_id", $article->id() );
    $t->set_var( "article_name", $article->name() );

    $SiteDescriptionOverride .= $article->name() . " ";

    $t->set_var( "author_text", $article->authorText() );

    // check for topic
    $topic =& $article->topic();

    if ( ( is_a( $topic, "eZTopic" ) ) && ( $topic->name() != "" ) )
    {
        $t->set_var( "topic_id", $topic->id() );
        $t->set_var( "topic_name", $topic->name() );
        $t->parse( "article_topic", "article_topic_tpl" );
    }
    else
    {
        $t->set_var( "article_topic", "" );
    }

    // preview image
    $thumbnailImage =& $article->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight, $convertToGray );
        if( is_object( $variation ) ) {
            $t->set_var("thumbnail_image_uri", "/" . $variation->imagePath());
            $t->set_var("thumbnail_image_width", $variation->width());
            $t->set_var("thumbnail_image_height", $variation->height());
            $t->set_var("thumbnail_image_caption", $thumbnailImage->caption());
        }

        $t->parse( "article_image", "article_image_tpl" );
    }
    else
    {
        $t->set_var( "article_image", "" );
    }


    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "tr_start", "<tr>" );
        $t->set_var( "tr_stop", "" );

        $t->set_var( "td_alt", "1" );
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "tr_start", "" );
        $t->set_var( "tr_stop", "</tr>" );


        $t->set_var( "td_alt", "2" );
        $t->set_var( "td_class", "bgdark" );
    }

    $published =& $article->published();

    $authorText = $article->authorText();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "article_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "article_timevalue", $locale->format( $publishedTimeValue ) );

	if ( $authorText == "" || $authorText[0] == "-" )
	{
		$t->set_var( "article_published", $locale->format( $published ) );
        $t->set_var( "article_date", "" );
	}
	else
    {
		$t->set_var( "article_published", $locale->format( $published ) );
        $t->parse( "article_date", "article_date_tpl" );
	}

    $renderer = new eZArticleRenderer( $article );

    $t->set_var( "article_intro", $renderer->renderIntro(  ) );
    $t->set_var( "article_intro", $renderer->renderIntro(  ) );

	$t->set_var( "messages", "" );
	if ( $article->forum() )
		{
		$forum = $article->forum();
		$MessageCount = $forum->messageCount( false, true );
		if ( $MessageCount > 0 )
			{
			$t->set_var( "messages", $MessageCount );
			$t->parse( "message_count", "message_count_tpl" );
			}
		else
			$t->set_var( "message_count", "" );
		}
	else
		$t->set_var( "message_count", "" );

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

    if ( trim( $contents[1] ) == "" && count( $article->attributes( false ) ) <= 0 )
    {
        $t->set_var( "read_more", "" );
        $t->parse( "headline_without_link", "headline_without_link_tpl" );
        $t->set_var( "headline_with_link", "" );
    }
    else
    {
        $t->parse( "read_more", "read_more_tpl" );
        $t->parse( "headline_with_link", "headline_with_link_tpl" );
        $t->set_var( "headline_without_link", "" );
    }


    $t->parse( "article_item", "article_item_tpl", true );
    $i++;
}

eZList::drawNavigator( $t, $articleCount, $UserListLimit, $Offset, "article_list_page_tpl" );

if ( count( $articleList ) > 0 )
    $t->parse( "article_list", "article_list_tpl" );
else
    $t->set_var( "article_list", "" );


if ( isset( $GenerateStaticPage ) and $GenerateStaticPage == "true" and $cachedFile != "" )
{
    $fp = eZPBFile::fopen( $cachedFile, "w+");

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";
    // $output .= "\$eZLanguageOverride=\"$eZLanguageOverride\";\n";
    $output .= "?>\n";

    $output .= $t->parse( "output", "article_list_page_tpl" );

    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "article_list_page_tpl" );
}

?>