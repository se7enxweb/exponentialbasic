<?php
//
// $Id: menubox.php 9305 2002-02-27 15:06:55Z master $
//
//
//
// Created on: <17-Oct-2000 12:16:07 bf>
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
// include_once( "classes/ezcachefile.php" );

$ini =& INIFile::globalINI();

// include_once( "ezuser/classes/ezobjectpermission.php" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

$PageCaching = $ini->read_var( "eZArticleMain", "PageCaching" );

if ( !(function_exists('createArticleMenu') ) )
{
    function createArticleMenu( $menuCacheFile=false )
        {
            global $ini;
            global $Language;
            global $GenerateStaticPage;
            global $GlobalSiteDesign;
            global $GlobalSectionID;
            global $CategoryID;
			global $url_array;

            // include_once( "classes/eztemplate.php" );

            // include_once( "ezarticle/classes/ezarticlecategory.php" );
            // include_once( "ezarticle/classes/ezarticle.php" );

            $t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                                 "ezarticle/user/intl", $Language, "menubox.php" );

            $t->setAllStrings();

            $t->set_file( array(
                "menu_box_tpl" => "menubox.tpl"
                ) );

            $t->set_block( "menu_box_tpl", "article_category_tpl", "article_category" );
            $t->set_block( "menu_box_tpl", "submit_article_tpl", "submit_article" );

            $t->set_var( "submit_article", "" );

            $t->set_var( "sitedesign", $GlobalSiteDesign );

            $t->set_var( "section_id", $GlobalSectionID );

            // List all categories
            if ( !isset( $CategoryID  ) )
                $CategoryID = 0;
                
            $target = '';

            $articleCategory = new eZArticleCategory( $CategoryID );

            $articleCategory_array = $articleCategory->getByParent( $articleCategory );

            $t->set_var( "top_title", $articleCategory->name() );

            $i = 0;
            if ( is_array($articleCategory_array))
            {
                foreach( $articleCategory_array as $categoryItem )
                {
                    if( eZObjectPermission::hasPermission( $categoryItem->id(), "article_category", 'r' ) )
                    {
                        $t->set_var( "articlecategory_id", $categoryItem->id()  );
                        $t->set_var( "articlecategory_title", $categoryItem->name() );

                                            if (
                            (( $url_array[2] == "archive" ) && ( $url_array[3] == $categoryItem->id() ))
                            || (( $url_array[2] == "articleview" ) && ( $url_array[5] == $categoryItem->id() ))
                            )
                                            {
                                                $t->set_var( "mark", "menumark" );
                                            }
                                            else
                                            {
                                                $t->set_var( "mark", "" );
                                            }

                        $t->parse( "article_category", "article_category_tpl", true );
                        $i++;
                    }
                }
            }

            if ( $i == 0 )
                $t->set_var( "article_category", "" );


            // user-submitted articles
            // include_once( "ezuser/classes/ezuser.php" );

            if ( eZUser::currentUser() != false &&
                 $ini->read_var( "eZArticleMain", "UserSubmitArticles" ) == "enabled" )
            {
                $t->parse( "submit_article", "submit_article_tpl", true );
            }

            if ( is_a( $menuCacheFile, "eZCacheFile" ) )
            {
                $output = $t->parse( $target, "menu_box_tpl" );
                $menuCacheFile->store( $output );
                print( $output );
            }
            else
            {
                $t->pparse( "output", "menu_box_tpl" );
            }

        }
}

// do the caching
if ( $PageCaching == "enabled" )
{
    $user =& eZUser::currentUser();
    $groupstr = "";
    if( is_a( $user, "eZUser" ) )
    {
        $groupIDArray = $user->groups( false );
        sort( $groupIDArray );
        $first = true;
        foreach( $groupIDArray as $groupID )
        {
            $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
            $first = false;
        }
    }

    unset( $menuCacheFile );
    $menuCacheFile = new eZCacheFile( "ezarticle/cache",
                                      array( "menubox",
                                             $groupstr,
                                             $GlobalSiteDesign,
                                             $CategoryID
                                             ),
                                      "cache", "," );

    if ( $menuCacheFile->exists() )
    {
        print( $menuCacheFile->contents() );
    }
    else
    {
        createArticleMenu( $menuCacheFile );
    }
}
else
{
    createArticleMenu();
}




?>
