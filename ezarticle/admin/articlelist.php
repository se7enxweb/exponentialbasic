<?php
// 
// $Id: articlelist.php 9465 2002-04-24 07:38:20Z jhe $
//
// Created on: <18-Oct-2000 14:41:37 bf>
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

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticletool.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$Locale = new eZLocale( $Language );
$AdminListLimit = $ini->read_var( "eZArticleMain", "AdminListLimit" );

$session =& eZSession::globalSession();

if ( isSet( $GoTo ) && is_Numeric( $GoToCategoryID ) )
{
    eZHTTPTool::header( "Location: /article/archive/$GoToCategoryID" );
    exit();
}

if ( isSet( $StoreSelection ) )
{
    switch ( $ArticleSelection )
    {
        case "Published" :
        {
            $session->setVariable( "MixUnpublished", "Published" ); 
        }
        break;

        case "Unpublished" :
        {
            $session->setVariable( "MixUnpublished", "Unpublished" ); 
        }
        break;
        
        case "All" :
        default  :
        {
            $session->setVariable( "MixUnpublished", "All" ); 
        }        
    }
}

$articleMix =& $session->variable( "MixUnpublished" );

$ArticleSelection =& $articleMix;

if ( $articleMix == "" )
{
    $articleMix = "All";
}

if ( isset( $CopyCategories ) )
{
    if ( count( $CategoryArrayID ) != 0 )
    {
        foreach ( $CategoryArrayID as $tCategoryID )
        {
            // copy category
            $tmpCategory = new eZArticleCategory( $tCategoryID );

            $newCategory = new eZArticleCategory();
            $newCategory->setName( "Copy of " . $tmpCategory->name() );            
            $newCategory->setDescription( $tmpCategory->description(false) );
            $newCategory->setParent( $tmpCategory->parent( false ) );
            $newCategory->setOwner( eZUser::currentUser() );

            $newCategory->store();

            // write access
            eZObjectPermission::setPermission( -1, $newCategory->id(), "article_category", 'w' );

            // read access 
            eZObjectPermission::setPermission( -1, $newCategory->id(), "article_category", 'r' );
            

            $tmpCategory->copyTree( $tCategoryID, $newCategory );
        }
        eZHTTPTool::header( "Location: /article/archive/" );
        exit();
    }    
}




if ( isSet( $DeleteArticles ) )
{
    if ( count( $ArticleArrayID ) != 0 )
    {
        foreach ( $ArticleArrayID as $TArticleID )
        {
            if ( eZObjectPermission::hasPermission( $TArticleID, "article_article", 'w' ) ||
                 eZArticle::isAuthor( eZUser::currentUser(), $TArticleID ) )
            {
                $article = new eZArticle( $TArticleID );

                // get the category to redirect to
                $articleID = $article->id();

                $categoryArray =& $article->categories();
                $categoryIDArray = array();
                foreach ( $categoryArray as $cat )
                {
                    $categoryIDArray[] = $cat->id();
                }
                $categoryID = $article->categoryDefinition();
                $categoryID = $categoryID->id();

                // clear the cache files.
                deleteCache( $TArticleID, $categoryID, $categoryIDArray );
                $article->delete();
            }
        }
        eZHTTPTool::header( "Location: /article/archive/$CurrentCategoryID" );
        exit();
    }
}

if ( isset( $DeleteCategories ) )
{
    if ( count( $CategoryArrayID ) != 0 )
    {
        /** Delete menubox cache **/
        $files =& eZCacheFile::files( "ezarticle/cache/",
                                 array( "menubox", NULL ),
                                 "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }

        $categories = array();
        foreach ( $CategoryArrayID as $ID )
        {
            $categories[] = $ID;
            $category = new eZArticleCategory( $ID );
            $categories[] = $category->parent( false );
            if ( eZObjectPermission::hasPermission( $ID , "article_category", 'w' ) ||
                 eZArticleCategory::isOwner( eZUser::currentUser(), $ID ) )
                $category->delete();
        }
        $categories = array_unique( $categories );
        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( "articlelist",
                                             $categories, NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
    }

    eZHTTPTool::header( "Location: /article/archive/" );
    exit();
}


$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "articlelist.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_list_page_tpl" => "articlelist.tpl"
    ) );

// path
$t->set_block( "article_list_page_tpl", "path_item_tpl", "path_item" );

// category selector

$t->set_block( "article_list_page_tpl", "category_tree_id_tpl", "category_tree_id" );

// category
$t->set_block( "article_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );
$t->set_block( "category_item_tpl", "category_edit_tpl", "category_edit" );

// article
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );

$t->set_block( "article_item_tpl", "article_is_published_tpl", "article_is_published" );
$t->set_block( "article_item_tpl", "article_not_published_tpl", "article_not_published" );

// move up / down
$t->set_block( "article_list_tpl", "absolute_placement_header_tpl", "absolute_placement_header" );
$t->set_block( "article_item_tpl", "absolute_placement_item_tpl", "absolute_placement_item" );
$t->set_block( "article_item_tpl", "article_edit_tpl", "article_edit" );


$t->set_var( "site_style", $SiteDesign );

$category = new eZArticleCategory( $CategoryID );

/** move article categories up/down **/
if ( isset( $MoveCategoryUp ) || isset( $MoveCategoryDown ) )
{
    if ( is_numeric( $MoveCategoryUp ) )
    {
        $mvcategory = new eZArticleCategory( $MoveCategoryUp );
        $mvcategory->moveCategoryUp();
    }

    if ( is_numeric( $MoveCategoryDown ) )
    {
        $mvcategory = new eZArticleCategory( $MoveCategoryDown );
        $mvcategory->moveCategoryDown();
    }

    /** Clear cache when moving stuff arround **/
    $files =& eZCacheFile::files( "ezarticle/cache/",
                                 array( "menubox", NULL ),
                                 "cache", "," );
    
    foreach ( $files as $file )
    {
        $file->delete();
    }
    $files =& eZCacheFile::files( "ezarticle/cache/",
                                 array( "articlelist", $CategoryID, NULL, NULL ), "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }

}
// move articles up / down
if ( $category->sortMode() == "absolute_placement" )
{
    if ( is_numeric( $MoveUp ) )
    {
        $category->moveUp( $MoveUp );
    }

    if ( is_numeric( $MoveDown ) )
    {
        $category->moveDown( $MoveDown );
    }
}

$t->set_var( "current_category_id", $category->id() );

//EP: CategoryDescriptionXML=enabled, description go in XML -------------------
if ( $ini->read_var( "eZArticleMain", "CategoryDescriptionXML" ) == "enabled" )
{
    if ( $CategoryID )
    {
        include_once( "ezarticle/classes/ezarticlerenderer.php" );
    
        $article = new eZArticle();
        $article->setContents( $category->description( false ) );
	    
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
    $t->set_var( "current_category_description", $category->description() );    
}	
//EP --------------------------------------------------------------------------

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );
    $t->set_var( "category_name", $path[1] );
    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList =& $category->getByParent( $category, true, "placement" );

// category "tree" selector
$tree = new eZArticleCategory();
$treeArray =& $tree->getTree();

foreach ( $treeArray as $catItem )
{
    $t->set_var( "category_id", $catItem[0]->id() );
    $t->set_var( "category_name", $catItem[0]->name() );

    if ( $catItem[1] > 1 )
        $t->set_var( "category_level", str_repeat( "&nbsp;&nbsp;", $catItem[1] ) );
    else
        $t->set_var( "category_level", "" );

    $t->set_var( "selected", $catItem[0]->id() == $CategoryID ? "selected" : "" );
    
    $t->parse( "category_tree_id", "category_tree_id_tpl", true );    
}


// categories
$i = 0;
$t->set_var( "category_list", "" );
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_name", $categoryItem->name() );

    $parent = $categoryItem->parent();

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    //EP: CategoryDescriptionXML=enabled, description go in XML -------------------
    if ( $ini->read_var( "eZArticleMain", "CategoryDescriptionXML" ) == "enabled" )
    {
        include_once( "ezarticle/classes/ezarticlerenderer.php" );
       
        $article = new eZArticle ();
        $article->setContents ($categoryItem->description(false));
	       
        $renderer = new eZArticleRenderer( $article );
		   
        $t->set_var( "category_description", $renderer->renderIntro() );
    }
    else
    {
        $t->set_var( "category_description", $categoryItem->description() );
    }       
    //EP --------------------------------------------------------------------------

    if ( eZObjectPermission::hasPermission( $categoryItem->id(), "article_category", 'w' ) ||
         eZArticleCategory::isOwner( eZUser::currentUser(), $categoryItem->id() ) )
        $t->parse( "category_edit", "category_edit_tpl", false );
    else
        $t->set_var( "category_edit", "" );
        
    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

$t->set_var( "archive_id", $CategoryID );

if ( $i > 0 )
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// set the offset/limit
if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $Limit ) )
    $Limit = $AdminListLimit;

switch ( $articleMix )
{
    case "Published" :
    {
        $t->set_var( "published_selected", "selected" );
        $t->set_var( "un_published_selected", "" );
        $t->set_var( "all_selected", "" );
    }
    break;

    case "Unpublished" :
    {
        $t->set_var( "published_selected", "" );
        $t->set_var( "un_published_selected", "selected" );
        $t->set_var( "all_selected", "" );
    }
    break;
        
    case "All" :
    default  :
    {
        $t->set_var( "published_selected", "" );
        $t->set_var( "un_published_selected", "" );
        $t->set_var( "all_selected", "selected" );
    }
}


// articles
if ( is_numeric( $CategoryID ) && ( $CategoryID > 0 ) )
{
    switch ( $ArticleSelection )
    {
       
        case "Published" :
        {
            $articleList =& $category->articles( $category->sortMode(), false, true, $Offset, $Limit );
            $articleCount = $category->articleCount( false, true  );        
        }
        break;

        case "Unpublished" :
        {
            $articleList =& $category->articles( $category->sortMode(), false, false, $Offset, $Limit );
            $articleCount = $category->articleCount( false, false  );
        }
        break;
        
        case "All" :
        default  :
        {
            $articleList =& $category->articles( $category->sortMode(), true, true, $Offset, $Limit );
            $articleCount = $category->articleCount( true, true  );        
        }
    }
}
else
{
    $articleList = array();
    $articleCount = 0;
}

$i = 0;
$t->set_var( "article_list", "" );

if ( $category->sortMode() == "absolute_placement" )
{
    $t->parse( "absolute_placement_header", "absolute_placement_header_tpl" );
}
else
{
    $t->set_var( "absolute_placement_header", "" );
}

$locale = new eZLocale( $Language );

foreach ( $articleList as $article )
{
    if ( eZObjectPermission::hasPermission( $article->id(), "article_article", 'r' ) ||
         eZArticle::isAuthor( eZUser::currentUser(), $article->id() ) )
    {
        if ( $article->name() == "" )
            $t->set_var( "article_name", "&nbsp;" );
        else
            $t->set_var( "article_name", $article->name() );

        $t->set_var( "article_id", $article->id() );

        if ( $article->isPublished() == true )
        {
            $t->parse( "article_is_published", "article_is_published_tpl" );
            $t->set_var( "article_not_published", "" );        
        }
        else
        {
            $t->set_var( "article_is_published", "" );
            $t->parse( "article_not_published", "article_not_published_tpl" );
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        if ( $category->sortMode() == "absolute_placement" )
        {
            $t->parse( "absolute_placement_item", "absolute_placement_item_tpl" );
        }
        else
        {
            $t->set_var( "absolute_placement_item", "" );
        }

        $published = $article->published();
        $t->set_var( "article_published_date", $locale->format( $published ) );

        if( eZObjectPermission::hasPermission( $article->id(), "article_article", 'w') ||
            eZArticle::isAuthor( eZUser::currentUser(), $article->id() ) )
            $t->parse( "article_edit", "article_edit_tpl", false );
        else
            $t->set_var( "article_edit", "" );


        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }
}
eZList::drawNavigator( $t, $articleCount, $AdminListLimit, $Offset, "article_list_page_tpl" );

// $i is from the last foreach loop
if ( $i > 0 )    
    $t->parse( "article_list", "article_list_tpl" );
else
    $t->set_var( "article_list", "" );


$t->pparse( "output", "article_list_page_tpl" );

/*!
  Delete cache.
*/
function deleteCache( $ArticleID, $CategoryID, $CategoryArray )
{    
    eZArticleTool::deleteCache( $ArticleID, $CategoryID, $CategoryArray );
}

?>