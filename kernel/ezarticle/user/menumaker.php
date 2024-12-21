<?php
// 
// $Id: menumaker.php 8283 2001-11-05 16:44:16Z th $
//
// Definition of ||| class
//
// Bjørn Reiten <br@ez.no>
// Created on: <29-Aug-2001 16:47:13 br>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! |||
//! 
/*!
 
  Example code:
  \code
  \endcode

*/

function menuMaker()
{
    global $CategoryID;
    global $GlobalSiteDesign;
    // include_once( "classes/INIFile.php" );
    // include_once( "classes/ezcachefile.php" );
    // include_once( "classes/eztemplate.php" );
    
    // include_once( "ezarticle/classes/ezarticlecategory.php" );
    // include_once( "ezarticle/classes/ezarticle.php" );
    // include_once( "ezuser/classes/ezobjectpermission.php" );
    
    $ini =& INIFile::globalINI();
    
    $Language = $ini->read_var( "eZArticleMain", "Language" );
    $t = new eZTemplate( "kernel/ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                         "kernel/ezarticle/user/intl", $Language, "menumaker.php" );
    
    $t->setAllStrings();
    
    $t->set_file( array(
        "menu_maker_tpl" => "menumaker.tpl"
        ) );
    
    $t->set_block( "menu_maker_tpl", "menu_box_tpl", "menu_box" );
    $t->set_block( "menu_box_tpl", "menu_header_tpl", "menu_header" );
    $t->set_block( "menu_box_tpl", "menu_article_tpl", "menu_article" );
    $t->set_block( "menu_box_tpl", "menu_category_tpl", "menu_category" );

    $t->set_var( "menu_box", "" );
    $t->set_var( "menu_header", "" );
    $t->set_var( "menu_category", "" );
    
    if ( !isset( $CategoryID  ) )
    {
        $category_id = 0;
    }
    else
    {
        $category_id = $CategoryID;
    }
    
    $articleCategory = new eZArticleCategory( $category_id );
    $articleCategory_array = $articleCategory->getByParent( $articleCategory );
    $article_art_array =& $articleCategory->articles( "absolute_placement", false, true, 0, 50 );
    $i = 0;

    if ( count( $article_art_array ) > 0 )
    {
        foreach( $article_art_array as $article_item )
        {
            $t->set_var( "sitedesign", $GlobalSiteDesign );
            $t->set_var( "article_link_text", $article_item->name() );
            $t->set_var( "article_id", $article_item->id() );
            $t->parse( "menu_article", "menu_article_tpl", true );
        }
    }
    if( count( $article_cat_array ) > 0 || count( $article_art_array ) > 0 )
    {
        $t->set_var( "current_category_name", $articleCategory->name() );
        $t->parse( "menu_box", "menu_box_tpl", true );
    }
   
    foreach( $articleCategory_array as $categoryItem )
    {
        $t->set_var( "menu_header", "" );
        $t->set_var( "menu_article", "" );
        $t->set_var( "menu_category", "" );
        

        $article_cat_array = $categoryItem->getByParent( $categoryItem );
        $article_art_array =& $categoryItem->articles( "absolute_placement", false, true, 0, 50 );
        
        
        if ( count( $article_cat_array ) > 0 )
        {
            foreach( $article_cat_array as $article_item )
            {
                $t->set_var( "sitedesign", $GlobalSiteDesign );
                $t->set_var( "category_link_text", $article_item->name() );
                $t->set_var( "category_id", $article_item->id() );
                $t->parse( "menu_category", "menu_category_tpl", true );
            }
        }
        
        if ( count( $article_art_array ) > 0 )
        {
            foreach( $article_art_array as $article_item )
            {
                $t->set_var( "sitedesign", $GlobalSiteDesign );
                $t->set_var( "article_link_text", $article_item->name() );
                $t->set_var( "article_id", $article_item->id() );
                $t->parse( "menu_article", "menu_article_tpl", true );
            }
        }
        
        if( count( $article_cat_array ) > 0 || count( $article_art_array ) > 0 )
        {
            $t->set_var( "current_category_name", $categoryItem->name() );
            $t->parse( "menu_header", "menu_header_tpl" );
            $t->parse( "menu_box", "menu_box_tpl", true );
        }
    }
    $t->pparse( "output", "menu_maker_tpl" );

}
?>
