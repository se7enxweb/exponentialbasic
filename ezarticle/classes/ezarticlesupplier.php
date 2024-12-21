<?php
// 
// $Id: ezarticlesupplier.php 6206 2001-07-19 12:19:22Z jakobn $
//
// Definition of eZArticleSupplier class
//
// Created on: <04-May-2001 17:14:30 amos>
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

//!! eZArticle
//! The class eZArticleSupplier supples article categories and article for other modules.
/*!

*/

class eZArticleSupplier
{
    function __construct()
    {
    }

    /*!
      Returns an array of available types.
    */
    function &urlTypes()
    {
        return $this->UrlTypes;
    }

    /*!
      Returns the name of the module.
    */
    function moduleName()
    {
        return "eZArticle";
    }

    /*!
      Returns a list of categories and/or contacts.
    */
    function &urlList( $type, $category = 0, $offset = 0 )
    {
        $ini =& INIFile::globalINI();
        $ret = false;
        switch( $type )
        {
            case "article":
            {
                // include_once( "ezarticle/classes/ezarticle.php" );
                // include_once( "ezarticle/classes/ezarticlecategory.php" );
                $limit = $ini->read_var( "eZArticleMain", "AdminListLimit" );
                $cat = new eZArticleCategory( $category );
                $categories = $cat->getByParent( $cat, false, "name" );
                $articles = $cat->articles( "alpha", false, true, $offset, $limit );
                $num_articles = $cat->articleCount( false, true );
                $path = $cat->path( $category );
                $category_path = array();
                foreach( $path as $path_item )
                {
                    $category_path[] = array( "id" => $path_item[0],
                                              "name" => $path_item[1] );
                }
                $category_array = array();
                $category_url = "/article/archive";
                foreach( $categories as $category )
                {
                    $id = $category->id();
                    $url = "$category_url/$id";
                    $category_array[] = array( "name" => $category->name(),
                                               "id" => $id,
                                               "url" => $url );
                }
                $article_array = array();
                $article_url = "/article/view";
                foreach( $articles as $article )
                {
                    $id = $article->id();
                    $cat = $article->categoryDefinition();
                    $cat = $cat->id();
                    $url = "$article_url/$id/$cat/1";
                    $article_array[] = array( "name" => $article->name(),
                                              "id" => $id,
                                              "url" => $url );
                }
                $ret = array();
                $ret["path"] = $category_path;
                $ret["categories"] = $category_array;
                $ret["items"] = $article_array;
                $ret["item_total_count"] = $num_articles;
                $ret["max_items_shown"] = $limit;
                break;
            }
        }
        return $ret;
    }

    function &item( $type, $id, $is_category )
    {
        $ret = false;
        switch( $type )
        {
            case "article":
            {
                if ( $is_category )
                {
                    // include_once( "ezarticle/classes/ezarticlecategory.php" );
                    $category = new eZArticleCategory( $id );
                    $category_url = "/article/archive";
                    $url = "$category_url/$id";
                    $ret = array( "name" => $category->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
                else
                {
                    // include_once( "ezarticle/classes/ezarticle.php" );
                    $article = new eZArticle( $id );
                    $article_url = "/article/view";
                    $cat = $article->categoryDefinition();
                    $cat = $cat->id();
                    $url = "$article_url/$id/$cat/1";
                    $ret = array( "name" => $article->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
            }
        }
        return $ret;
    }

    var $UrlTypes = array( "article" => "{intl-article}" );
}

?>