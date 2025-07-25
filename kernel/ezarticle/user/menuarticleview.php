<?php
// 
// $Id: menuarticleview.php 7204 2001-09-14 08:29:00Z ce $
//
// Created on: <18-Oct-2000 16:34:51 bf>
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

// include_once( "classes/ezhttptool.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );
// include_once( "ezmail/classes/ezmail.php" );

if ( !isset( $CategoryID ) )
    $CategoryID = eZArticle::categoryDefinitionStatic( $ArticleID );

$GlobalSectionID = eZArticleCategory::sectionIDStatic( $CategoryID );


$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$ForceCategoryDefinition = $ini->read_var( "eZArticleMain", "ForceCategoryDefinition" );
$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );
$TemplateDir = $ini->read_var( "eZArticleMain", "TemplateDir" );

if ( $ForceCategoryDefinition == "enabled" )
{
    $CategoryID = eZArticle::categoryDefinitionStatic( $ArticleID );
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

if ( $StaticPage == true )
{
    if ( eZFile::file_exists( "kernel/ezarticle/user/$TemplateDir/articlestatic" . $override  . ".tpl" ) )
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
        if ( eZFile::file_exists( "kernel/ezarticle/user/$TemplateDir/articleview" . $override  . ".tpl" ) )
            $t->set_file( "article_view_page_tpl", "articleview" . $override  . ".tpl"  );
        else
            $t->set_file( "article_view_page_tpl", "menuarticleview.tpl"  );
    }
}

// path
$t->set_block( "article_view_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "article_view_page_tpl", "article_url_item_tpl", "article_url_item" );

$t->set_block( "article_view_page_tpl", "article_header_tpl", "article_header" );
$t->set_block( "article_view_page_tpl", "article_intro_tpl", "article_intro" );

$t->set_block( "article_view_page_tpl", "attached_file_list_tpl", "attached_file_list" );
$t->set_block( "attached_file_list_tpl", "attached_file_tpl", "attached_file" );

$t->set_block( "article_view_page_tpl", "image_list_tpl", "image_list" );
$t->set_block( "image_list_tpl", "image_tpl", "image" );

$t->set_block( "article_view_page_tpl", "page_link_tpl", "page_link" );
$t->set_block( "article_view_page_tpl", "current_page_link_tpl", "current_page_link" );
$t->set_block( "article_view_page_tpl", "next_page_link_tpl", "next_page_link" );
$t->set_block( "article_view_page_tpl", "prev_page_link_tpl", "prev_page_link" );
$t->set_block( "article_view_page_tpl", "numbered_page_link_tpl", "numbered_page_link" );
$t->set_block( "article_view_page_tpl", "print_page_link_tpl", "print_page_link" );

$t->set_block( "article_view_page_tpl", "mail_to_tpl", "mail_to" );
$t->set_block( "article_view_page_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "type_item_tpl", "attribute_item_tpl", "attribute_item" );


if ( $StaticRendering == true )
{
    $t->set_var( "article_header", "" );
}
else
{
    $t->parse( "article_header", "article_header_tpl" );
}

$SiteURL = $ini->read_var( "site", "SiteURL" );

$t->set_var( "article_url", $SiteURL . $REQUEST_URI );
$t->set_var( "article_url_item", "" );
if ( isset( $PrintableVersion ) and $PrintableVersion == "enabled" )
    $t->parse( "article_url_item", "article_url_item_tpl" );


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
    

    // override the article tags:
    $template = "menu";
    $renderer = new eZArticleRenderer( $article, $template );

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

    if ( $PageNumber == 1 )
           $t->parse( "article_intro", "article_intro_tpl" );
    else
        $t->set_var( "article_intro", "" );
        
    $t->set_var( "article_body", $articleContents[1] );

    $t->set_var( "link_text", $article->linkText() );

    $t->set_var( "article_id", $article->id() );

    $locale = new eZLocale();
    $published = $article->published();

    $t->set_var( "article_created", $locale->format( $published ) );

    // image list

    $usedImages = $renderer->usedImageList();

    $images =& $article->images();

    
    {
        $imageNumber = 1;
        $i=0;
        foreach ( $images as $image )
        {
            if ( is_array( $usedImages ) && !in_array(  $imageNumber, $usedImages ) )
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

                $variation =& $image->requestImageVariation( 150, 150 );

                $t->set_var( "image_url", "/" .$variation->imagePath() );
                $t->set_var( "image_width", $variation->width() );
                $t->set_var( "image_height",$variation->height() );

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
        $t->set_var( "original_file_name", $file->originalFileName() );
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
if ( ( $PrintableVersion != "enabled" ) && ( $StaticRendering != true )  )
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


// set variables for meta information
$SiteTitleAppend = $article->name();
$SiteDescriptionOverride = str_replace( "\"", "", strip_tags( $articleContents[0] ) );

if ( isset( $GenerateStaticPage ) && $GenerateStaticPage == "true" )
{
    $fp = eZFile::fopen( $cachedFile, "w+");

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";    
    $output .= "?>\n";

    $output .= $t->parse( "output", "article_view_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "article_view_page_tpl" );
}

?>