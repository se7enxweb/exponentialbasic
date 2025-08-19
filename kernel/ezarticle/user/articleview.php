<?php
//
// $Id: articleview.php 9879 2003-07-24 08:47:34Z br $
//
// Created on: <18-Oct-2000 16:34:51 bf>
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

// include_once( "classes/ezhttptool.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZArticleMain", "Language" );
$ForceCategoryDefinition = $ini->variable( "eZArticleMain", "ForceCategoryDefinition" );
$TemplateDir = $ini->variable( "eZArticleMain", "TemplateDir" );
$CapitalizeHeadlines = $ini->variable( "eZArticleMain", "CapitalizeHeadlines" );
$ListImageWidth = $ini->variable( "eZArticleMain", "ListImageWidth" );
$ListImageHeight = $ini->variable( "eZArticleMain", "ListImageHeight" );

if ( !is_numeric( $ArticleID ) )
{
    eZHTTPTool::header( "Location: /error/404" );
    exit();
}

if ( !is_numeric( $PageNumber) )
    $PageNumber = "";

if ( !is_numeric( $CategoryID ) )
    $CategoryID = eZArticle::categoryDefinitionStatic( $ArticleID );

if ( $ForceCategoryDefinition == "enabled" )
{
    $CategoryID = eZArticle::categoryDefinitionStatic( $ArticleID );
}

$GlobalSectionID = eZArticleCategory::sectionIDStatic( $CategoryID );

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$templateDirTmp = $sectionObject->templateStyle();
if ( $templateDirTmp != null && trim( $templateDirTmp ) != "" )
{
    $TemplateDir = preg_replace( "/(.+)\/.+(\/?)/", "/\\1/$templateDirTmp\\2", $TemplateDir );
}

$t = new eZTemplate( "kernel/ezarticle/user/" . $TemplateDir,
                     "kernel/ezarticle/user/intl/", $Language, "articleview.php" );

$t->setAllStrings();

$StaticPage = false;
if ( $url_array[2] == "static" || $url_array[2] == "articlestatic"  )
{
    $StaticPage = true;
}


// override template for the current category
$override = "_override_$CategoryID";
// override template for current section
// category override will be prefered
$sectionOverride = "_sectionoverride_$GlobalSectionID";

if ( $StaticPage == true )
{
    if ( file_exists( "kernel/ezarticle/user/$TemplateDir/articlestatic" . $override  . ".tpl" ) )
        $t->set_file( "article_view_page_tpl", "articlestatic" . $override  . ".tpl"  );
    else
        $t->set_file( "article_view_page_tpl", "articlestatic.tpl"  );
}
else
{
    if ( isset( $PrintableVersion ) and $PrintableVersion == "enabled" )
    {
            $t->set_file( "article_view_page_tpl", "articleprint.tpl"  );
    }
    else
    {
        // category override
        if ( file_exists( "kernel/ezarticle/user/$TemplateDir/articleview" . $override  . ".tpl" ) )
        {
            $t->set_file( "article_view_page_tpl", "articleview" . $override  . ".tpl"  );
        }
        else
        {
            // section override
            if ( file_exists( "kernel/ezarticle/user/$TemplateDir/articleview" . $sectionOverride  . ".tpl" ) )
            {
                $t->set_file( "article_view_page_tpl", "articleview" . $sectionOverride  . ".tpl"  );
            }
            else
            {
                $t->set_file( "article_view_page_tpl", "articleview.tpl"  );
            }
        }
    }
}

// path
$t->set_block( "article_view_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "article_view_page_tpl", "article_url_item_tpl", "article_url_item" );

$t->set_block( "article_view_page_tpl", "article_header_tpl", "article_header" );
$t->set_block( "article_view_page_tpl", "article_topic_tpl", "article_topic" );
$t->set_block( "article_view_page_tpl", "article_intro_tpl", "article_intro" );

$t->set_block( "article_view_page_tpl", "attached_file_list_tpl", "attached_file_list" );
$t->set_block( "attached_file_list_tpl", "attached_file_tpl", "attached_file" );

$t->set_block( "article_view_page_tpl", "image_list_tpl", "image_list" );
$t->set_block( "image_list_tpl", "image_tpl", "image" );

// current category image
$t->set_block( "article_view_page_tpl", "current_category_image_item_tpl", "current_category_image_item" );

$t->set_block( "article_view_page_tpl", "page_link_tpl", "page_link" );
$t->set_block( "article_view_page_tpl", "current_page_link_tpl", "current_page_link" );
$t->set_block( "article_view_page_tpl", "next_page_link_tpl", "next_page_link" );
$t->set_block( "article_view_page_tpl", "prev_page_link_tpl", "prev_page_link" );
$t->set_block( "article_view_page_tpl", "numbered_page_link_tpl", "numbered_page_link" );
$t->set_block( "article_view_page_tpl", "print_page_link_tpl", "print_page_link" );
$t->set_block( "article_view_page_tpl", "section_item_tpl", "section_item" );

$t->set_block( "article_view_page_tpl", "mail_to_tpl", "mail_to" );
$t->set_block( "article_view_page_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "type_item_tpl", "attribute_item_tpl", "attribute_item" );
$t->set_block( "section_item_tpl", "link_item_tpl", "link_item" );

// read user override variables for image size
$ListImageWidth = $ini->variable( "eZArticleMain", "ListImageWidth" );
$ListImageHeight = $ini->variable( "eZArticleMain", "ListImageHeight" );

// Make the manual keywords available in the articleview template
$ManualKeywords =& $article->manualKeywords();
$t->set_var( "article_keywords", $ManualKeywords );


$listImageWidthOverride =& $t->get_user_variable( "article_view_page_tpl",  "ListImageWidth" );
if ( $listImageWidthOverride )
{
    $ListImageWidth = $listImageWidthOverride;
}

$listImageHeightOverride =& $t->get_user_variable( "article_view_page_tpl",  "ListImageHeight" );
if ( $listImageHeightOverride )
{
    $ListImageHeight = $listImageHeightOverride;
}


$SiteURL = $ini->variable( "site", "UserSiteURL" );

$t->set_var( "article_url", $SiteURL . $_SERVER['REQUEST_URI'] );
$t->set_var( "article_url_item", "" );
if ( isset( $PrintableVersion ) and $PrintableVersion == "enabled" )
    $t->parse( "article_url_item", "article_url_item_tpl" );


// makes the section ID available in articleview template
$t->set_var( "section_id", $GlobalSectionID );

$article = new eZArticle(  );

// check if the article exists
if ( $article->get( $ArticleID ) )
{
    if ( $article->isPublished() )
    {
        // published article.
    }
    else
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }

    $categories =& $article->categories( false );

    // path
    if ( !in_array( $CategoryID, $categories ) )
    {
        $category = $article->categoryDefinition();
    }
    else
    {
        $category = new eZArticleCategory( $CategoryID );
    }

    // current category image
    $image =& $category->image();

    $t->set_var( "current_category_image_item", "" );

    if ( is_a( $image, "eZImage" ) && ( $image->id() != 0 ) )
    {
        $imageWidth =& $ini->variable( "eZArticleMain", "CategoryImageWidth" );
        $imageHeight =& $ini->variable( "eZArticleMain", "CategoryImageHeight" );

        $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

        $imageURL = "/" . $variation->imagePath();
        $imageWidth =& $variation->width();
        $imageHeight =& $variation->height();
        $imageCaption =& $image->caption();

        $t->set_var( "current_category_image_width", $imageWidth );
        $t->set_var( "current_category_image_height", $imageHeight );
        $t->set_var( "current_category_image_url", $imageURL );
        $t->set_var( "current_category_image_caption", $imageCaption );
        $t->parse( "current_category_image_item", "current_category_image_item_tpl" );
    }
    else
    {
        $t->set_var( "current_category_image_item", "" );
    }

    $pathArray =& $category->path();

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

        $t->parse( "path_item", "path_item_tpl", true );
    }

// link list
$module_link = new eZModuleLink( "eZArticle", "Article", $article->id() );
$sections =& $module_link->sections();
$t->set_var( "section_item", "" );
foreach ( $sections as $section )
{
    $t->set_var( "link_item", "" );
    $t->set_var( "section_name", $section->name() );
    $t->set_var( "section_id", $section->id() );
    $links =& $section->links();
    $i = 0;
    foreach ( $links as $link )
    {
        $t->set_var( "td_class", ($i % 2) == 0 ? "bglight" : "bgdark" );
        $t->set_var( "link_name", $link->name() );
        $t->set_var( "link_url", $link->url() );
        $t->set_var( "link_id", $link->id() );
        $t->parse( "link_item", "link_item_tpl", true );
        ++$i;
    }
    $t->parse( "section_item", "section_item_tpl", true );
}



    $renderer = new eZArticleRenderer( $article );

    if ( $CapitalizeHeadlines == "enabled" )
    {
        // include_once( "classes/eztexttool.php" );
        $t->set_var( "article_name", eZTextTool::capitalize(  $article->name() ) );
    }
    else
    {
        $t->set_var( "article_name", $article->name() );
    }

    if ( eZMail::validate( $article->authorEmail() ) && $article->authorEmail() )
    {
        $t->set_var( "author_email", $article->authorEmail() );
    }
    else
    {
        $author = $article->author();
        $t->set_var( "author_email", $author->email() );
    }

    $t->set_var( "author_text", $article->authorText() );
    $t->set_var( "author_id", $article->contentsWriter( false ) );

    // check for topic
    $topic =& $article->topic();

    if ( is_a( $topic, "eZTopic" ) && $topic->name() != "" )
    {
        $t->set_var( "topic_id", $topic->id() );
        $t->set_var( "topic_name", $topic->name() );
        $t->parse( "article_topic", "article_topic_tpl" );
    }
    else
    {
        $t->set_var( "article_topic", "" );
    }

    // check if author is "" or starts with -
    $authorText = trim( $article->authorText() );
    if ( $authorText == "" ||
         $authorText[0] == "-"
         )
    {
        $ShowHeader = "hide";
    }

    $categoryDef =& $article->categoryDefinition();

    $t->set_var( "category_definition_name", $categoryDef->name() );

    $pageCount = $article->pageCount();
    if ( $PageNumber > $pageCount )
        $PageNumber = $pageCount;

    if ( $PageNumber == -1 )
        $articleContents = $renderer->renderPage( -1 );
    else
        $articleContents = $renderer->renderPage( $PageNumber -1 );

    $t->set_var( "article_intro", $articleContents[0] );

    if ( ( $PageNumber == 1 ) || (( isset( $PrintableVersion ) and $PrintableVersion == "enabled" )))
           $t->parse( "article_intro", "article_intro_tpl" );
    else
        $t->set_var( "article_intro", "" );

    if ( $articleContents[0] != '' )
    {    
      $t->set_var( "article_intro", $articleContents[0] );
      $t->parse( "article_intro", "article_intro_tpl" );
    } else {
       $t->set_var( "article_intro", "" );
    }


    $t->set_var( "article_body", $articleContents[1] );

    $t->set_var( "link_text", $article->linkText() );

    $t->set_var( "article_id", $article->id() );

    $locale = new eZLocale( $Language );
    $published = $article->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "article_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "article_timevalue", $locale->format( $publishedTimeValue ) );

    $t->set_var( "article_created", $locale->format( $published ) );

    // image list

    $usedImages = $renderer->usedImageList();
    $images =& $article->images();
    $imageNumber = 0;
    {
        $i=0;
        foreach ( $images as $imageArray )
        {
            $image = $imageArray["Image"];
            $placement = $imageArray["Placement"];

            $showImage = true;

            if ( is_array( $usedImages ) == true )
            {
                if ( in_array( $placement, $usedImages ) )
                {
                    $showImage = false;
                }
            }

            if (  $showImage  )
            {
                if ( ( $i % 2 ) == 0 )
                {
                    $t->set_var( "td_class", "bglight" );
                }
                else
                {
                    $t->set_var( "td_class", "bgdark" );
                }

                if ( $image->caption() == "" )
                    $t->set_var( "image_caption", "&nbsp;" );
                else
                    $t->set_var( "image_caption", $image->caption() );


                $t->set_var( "image_id", $image->id() );
                $t->set_var( "article_id", $ArticleID );

                $variation =& $image->requestImageVariation( $ListImageWidth, $ListImageHeight );
                if( is_object( $variation ) )
                {
                    $t->set_var( "image_url", "/" .$variation->imagePath() );
                    $t->set_var( "image_width", $variation->width() );
                    $t->set_var( "image_height",$variation->height() );
                }

                $img_id = $image->id();
                $t->set_var( "alt_image_url", "/imagecatalogue/imageview/$img_id/?RefererURL=".$_SERVER["REQUEST_URI"] );

                $t->parse( "image", "image_tpl", true );
                $i++;
            }
            $imageNumber++;
        }

        $t->parse( "image_list", "image_list_tpl", true );
    }
    if ( $i == 0 )
        $t->set_var( "image_list", "" );



}
else
{
    eZHTTPTool::header( "Location: /error/404" );
    exit();
}



if ( $StaticRendering == true  || (isset($ShowHeader) && $ShowHeader == "hide" ))
{
    $t->set_var( "article_header", "" );
}
else
{
    $t->parse( "article_header", "article_header_tpl" );
}


// set the variables in the mail_to form
if ( !isset( $SendTo ) )
    $SendTo = "";
$t->set_var( "send_to", $SendTo );
if ( !isset( $From ) )
    $From = "";
$t->set_var( "from", $From );

$types = $article->types();

$typeCount = count( $types );

$t->set_var( "attribute_item", "" );
$t->set_var( "type_item", "" );
$t->set_var( "attribute_list", "" );

if( $typeCount > 0 )
{
    foreach( $types as $type )
    {
        $attributes = array();
        $attributes = $type->attributes();
        $attributeCount = count( $attributes );

        if( $attributeCount > 0 )
        {
            $t->set_var( "type_id", $type->id() );
            $t->set_var( "type_name", $type->name() );
            $t->set_var( "attribute_item", "" );
            foreach( $attributes as $attribute )
            {
                $t->set_var( "attribute_id", $attribute->id() );
                $t->set_var( "attribute_name", $attribute->name() );
                $t->set_var( "attribute_value", nl2br( $attribute->value( $article ) ) );
                $t->parse( "attribute_item", "attribute_item_tpl", true );
            }
            $t->parse( "type_item", "type_item_tpl", true );
        }
    }

    $t->parse( "attribute_list", "attribute_list_tpl" );
}



// files
$files = $article->files();

if ( count( $files ) > 0 )
{
    $i=0;
    foreach ( $files as $file )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "file_id", $file->id() );
        // $t->set_var( "original_file_name", $file->originalFileName() );
        $t->set_var( "original_file_name", $file->fileName() );
        $t->set_var( "file_name", $file->name() );
        $t->set_var( "file_url", $file->name() );
        $t->set_var( "file_description", $file->description() );

        $size = $file->siFileSize();
        $t->set_var( "file_size", $size["size-string"] );
        $t->set_var( "file_unit", $size["unit"] );


        $i++;
        $t->parse( "attached_file", "attached_file_tpl", true );
    }

    $t->parse( "attached_file_list", "attached_file_list_tpl" );
}
else
{
    $t->set_var( "attached_file_list", "" );
}


$t->set_var( "current_page_link", "" );

// page links
if ( $pageCount > 1 && $PageNumber != -1 && ( $PrintableVersion != "enabled" ) )
{
    for ( $i=0; $i<$pageCount; $i++ )
    {
        $t->set_var( "article_id", $article->id() );
        $t->set_var( "page_number", $i+1 );
        $t->set_var( "category_id", $CategoryID );

        if ( ( $i + 1 )  == $PageNumber )
        {
            $t->parse( "page_link", "current_page_link_tpl", true );
        }
        else
        {
            $t->parse( "page_link", "page_link_tpl", true );
        }
    }
}
else
{
    $t->set_var( "page_link", "" );

}

$t->set_var( "total_pages", $pageCount );
$t->set_var( "current_page", $PageNumber );

// non-printable version link
if ( ( $PageNumber == -1 ) && ( $PrintableVersion == "enabled" ) )
{
    $t->parse( "numbered_page_link", "numbered_page_link_tpl" );
}
else
{
    $t->set_var( "numbered_page_link", "" );
}

// printable version link
if ( ( !isset( $PrintableVersion ) or $PrintableVersion != "enabled" ) && ( !isset( $StaticRendering ) or $StaticRendering != true )  )
{
    $t->parse( "print_page_link", "print_page_link_tpl" );
}
else
{
    $t->set_var( "print_page_link", "" );
}

// previous page link
if ( ( $PageNumber > 1 ) && ( $PrintableVersion != "enabled" ) )
{
    $t->set_var( "prev_page_number", $PageNumber - 1 );
    $t->parse( "prev_page_link", "prev_page_link_tpl" );
}
else
{
    $t->set_var( "prev_page_link", "" );
}

// next page link
if ( $PageNumber < $pageCount && $PageNumber != -1 && ( $PrintableVersion != "enabled" ) )
{
    $t->set_var( "next_page_number", $PageNumber + 1 );
    $t->parse( "next_page_link", "next_page_link_tpl" );
}
else
{
    $t->set_var( "next_page_link", "" );
}

// PBo Mod
// If the current logged in user matches the author, show an edit link or button 
// as defined in the corresponding template block article_edit_tpl
$currentuser =& eZUser::currentUser(); 
$t->set_block( "article_view_page_tpl", "article_edit_tpl", "article_edit" ); 
$editOwnArticle=$ini->variable( "eZArticleMain", "UserEditOwnArticle" );

//if ($currentuser->id() == $article->author(false) && $editOwnArticle == "enabled")
if ( eZArticle::isAuthor($currentuser, $article->id()) && $editOwnArticle == "enabled")
{
    $t->set_var( "edit_article_id", $article->id() );
    $t->parse ( "article_edit", "article_edit_tpl" );
   
}
else
{
    $t->set_var( "article_edit", "", "" );

  
}
//End PBo mod

// set variables for meta information
$SiteTitleAppend = $article->name();
$SiteDescriptionOverride = str_replace( "\"", "", strip_tags( $articleContents[0] ) );
$SiteKeywordsOverride = str_replace( "\"", "", strip_tags( $article->keywords() ) );

$SiteKeywordsOverride  = str_replace( "qdom", "", $SiteKeywordsOverride );

if ( isset( $GenerateStaticPage ) && $GenerateStaticPage == "true" )
{
    $fp = eZPBFile::fopen( $cachedFile, "w+");

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$ManualKeywords=\"$ManualKeywords\";\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";
    $output .= "\$SiteKeywordsOverride=\"$SiteKeywordsOverride\";\n";
    // $output .= "\$eZLanguageOverride=\"$eZLanguageOverride\";\n";
    $output .= "?>\n";

    $printOut = $t->parse(  "output", "article_view_page_tpl" );

    // print the output the first time while printing the cache file.
    print( $printOut );

    $output .= $printOut;

    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "article_view_page_tpl" );
}

?>