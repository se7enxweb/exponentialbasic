<?php
// 
// $Id: sitemap.php 9693 2002-08-07 09:37:31Z br $
//
// Created on: <06-Jun-2001 17:05:38 bf>
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
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "classes/ezcachefile.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlegenerator.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );

$Language = $ini->variable( "eZArticleMain", "Language" );

// sections
// include_once( "ezsitemanager/classes/ezsection.php" );

if ( isset( $CategoryID ) )
{
    $GlobalSectionID = eZArticleCategory::sectionIDstatic ( $CategoryID );
}
    
// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$t = new eZTemplate( "kernel/ezarticle/user/" . $ini->variable( "eZArticleMain", "TemplateDir" ),
                     "kernel/ezarticle/user/intl/", $Language, "sitemap.php" );

$t->setAllStrings();

$t->set_file( "article_sitemap_page_tpl", "sitemap.tpl" );

$t->set_block( "article_sitemap_page_tpl", "category_value_tpl", "category_value" );
$t->set_block( "article_sitemap_page_tpl", "article_value_tpl", "article_value" );
$t->set_block( "article_sitemap_page_tpl", "value_tpl", "value" );

$tree = new eZArticleCategory();
$treeArray = $tree->getTree( $CategoryID );
$user =& eZUser::currentUser();

$t->set_var( "category_value", "" );
$t->set_var( "article_value", "" );
$t->set_var( "value", "" );

if ( $CategoryID != 0 && is_numeric( $CategoryID ) )
{
    $frontCategory = new eZArticleCategory( $CategoryID );
    if ( eZObjectPermission::hasPermission( $frontCategory->id(), "article_category", 'r', $user ) == true  ||
         eZArticleCategory::isOwner( eZUser::currentUser(), $frontCategory->id() ) )
    {    
        $articleFrontList =& $frontCategory->articles( 1, false, true, 0, 50, $frontCategory->id() );

        $itemCount++;

        foreach ( $articleFrontList as $article )
        {

            if ( ( $itemCount % 2 ) == 0 )
            {
                $t->set_var( "td_alt", "1" );
                $t->set_var( "td_class", "bglight" );
            }
            else
            {
                $t->set_var( "td_alt", "2" );
                $t->set_var( "td_class", "bgdark" );
            }

            $t->set_var( "option_level", $option_level );
                
            $t->set_var( "option_value", $article->id() );
            $t->set_var( "option_name", $article->name() );
            $t->set_var( "category_id", $frontCategory->id() );
            $t->parse( "value", "article_value_tpl", true );
            $itemCount++;
        }
        unset( $articleFrontList );
    }
}

$itemCount = 0;
foreach ( $treeArray as $catItem )
{
    if ( eZObjectPermission::hasPermission( $catItem[0]->id(), "article_category", 'r', $user ) == true  ||
         eZArticleCategory::isOwner( eZUser::currentUser(), $catItem[0]->id() ) )
    {    

        $category = new eZArticleCategory( $catItem[0]->id() );
        if ( $category->excludeFromSearch() == false )
        {
            $placement = $catItem[1] - 1;
            $option_level = str_repeat( "&nbsp;&nbsp;&nbsp;&nbsp;", $placement );

            $t->set_var( "option_value", $catItem[0]->id() );
            $t->set_var( "option_name", $catItem[0]->name() );
            $t->set_var( "option_level", $option_level );

            if ( ( $itemCount % 2 ) == 0 )
            {
                $t->set_var( "td_alt", "1" );
                $t->set_var( "td_class", "bglight" );
            }
            else
            {
                $t->set_var( "td_alt", "2" );
                $t->set_var( "td_class", "bgdark" );
            }
            
            $t->parse( "value", "category_value_tpl", true );    
            
            $articleList =& $category->articles( 1, false, true, 0, 50, $catItem[0]->id() );
            $itemCount++;

            foreach ( $articleList as $article )
            {

                if ( ( $itemCount % 2 ) == 0 )
                {
                    $t->set_var( "td_alt", "1" );
                    $t->set_var( "td_class", "bglight" );
                }
                else
                {
                    $t->set_var( "td_alt", "2" );
                    $t->set_var( "td_class", "bgdark" );
                }

                $t->set_var( "option_level", "&nbsp;&nbsp;&nbsp;&nbsp;" . $option_level );
                
                $t->set_var( "option_value", $article->id() );
                $t->set_var( "option_name", $article->name() );
                $t->set_var( "category_id", $category->id() );
                $t->parse( "value", "article_value_tpl", true );
                $itemCount++;
            }
        }
        unset ($articleList);
        unset ($category);

    }
}


$t->pparse( "output", "article_sitemap_page_tpl" );

?>