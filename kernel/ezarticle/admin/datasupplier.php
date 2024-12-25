<?php
//
// $Id: datasupplier.php 9560 2002-05-22 13:35:33Z bf $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "classes/ezdatetime.php" );

#echo " " . $url_array[2] . " " . $url_array[3] . " " . $url_array[4] . " " . $url_array[5];
#exit();

$user =& eZUser::currentUser();
if ( eZPermission::checkPermission( $user, "eZArticle", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

switch ( $url_array[2] )
{
    case "export":
    {
        include( "kernel/ezarticle/admin/export.php" );
    }
    break;

    case "topiclist":
    {
        eZHTTPTool::extractVar(array('NewTopic','DeleteTopic','Store','IDArray','Description','Name'));
        include( "kernel/ezarticle/admin/topiclist.php" );
    }
    break;
    
    case "archive":
    {
        if ( !is_numeric( $CategoryID=eZHTTPTool::getVar( "CategoryID", true ) ) )
        {
            $CategoryID = $url_array[3];
            if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
                $CategoryID = 0;
        }
        
        if ( isset($url_array[4]) && $url_array[4] == "parent" )
            $Offset = $url_array[5];

        if ( $CategoryID == 0 || eZObjectPermission::hasPermission( $CategoryID, "article_category", 'r' )  ||
             eZArticleCategory::isOwner( $user, $CategoryID ) )
            include( "kernel/ezarticle/admin/articlelist.php" );
    }
    break;

    case "unpublished":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        if ( $url_array[4] == "parent" )
            $Offset = $url_array[5];

        if ( $CategoryID == 0 || eZObjectPermission::hasPermission( $CategoryID, "article_category", 'r' ) ||
             eZArticleCategory::isOwner( $user, $CategoryID) )
            include( "kernel/ezarticle/admin/unpublishedlist.php" );
    }
    break;

    case "pendinglist":
    {
        $CategoryID = $url_array[3];
        if ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        if ( $url_array[4] == "parent" )
            $Offset = $url_array[5];

        if ( $CategoryID == 0 || eZObjectPermission::hasPermission( $CategoryID, "article_category", 'r' ) ||
             eZArticleCategory::isOwner( $user, $CategoryID) )
            include( "kernel/ezarticle/admin/pendinglist.php" );
    }
    break;

    case "search" :
    {
        if ( $url_array[3] == "advanced" )
        {
            include( "kernel/ezarticle/admin/searchform.php" );
        }
        else
        {
            $Offset = 0;
            if ( $url_array[3] == "parent" )
            {
                $SearchText = urldecode( $url_array[4] );
                if ( $url_array[5] != urlencode( "+" ) )
                    $StartStamp = urldecode( $url_array[5] );
                if ( $url_array[6] != urlencode( "+" ) )
                    $StopStamp = urldecode( $url_array[6] );
                if ( $url_array[7] != urlencode( "+" ) )
                    $CategoryArray = explode( "-", urldecode( $url_array[7] ) );
                if ( $url_array[8] != urlencode( "+" ) )
                    $ContentsWriterID = urldecode( $url_array[8] );
                if ( $url_array[9] != urlencode( "+" ) )
                    $PhotographerID = urldecode( $url_array[9] );
                
                $Offset = $url_array[10];
            }
            include( "kernel/ezarticle/admin/search.php" );
        }
    }
    break;

    case "view":    
    case "articleview":
    case "articlepreview":
    {
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];
        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) )
            $PageNumber= 1;

        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'r' ) ||
             eZArticle::isAuthor( $user, $ArticleID ) )
            include( "kernel/ezarticle/admin/articlepreview.php" );
    }
    break;

    case "articlelog" :
    {
        $ArticleID = $url_array[3];
        if ( eZObjectPermission::hasPermission(  $ArticleID, "article_article", 'w' ) ||
             eZArticle::isAuthor( $user, $ArticleID ) )
            include( "kernel/ezarticle/admin/articlelog.php" );
    }
    break;
    
  // FIXME: test for writeable categories!!!    
    case "articleedit":
    {
        if ( eZObjectPermission::getObjects( "article_category", 'w', true ) < 1 )
        {
            $text = "You do not have write permission to any categories";
            $info = urlencode( $text );
            eZHTTPTool::header( "Location: /error/403?Info=$info" );
            exit();
        }

        eZHTTPTool::extractVar(array('Name','Keywords','Contents','AuthorText','AuthorEmail', 'LinkText', 
        	'StartDay', 'StartMonth', 'StartYear', 'StartHour', 'StartMinute',
        	'StopDay', 'StopMonth', 'StopYear', 'StopHour', 'StopMinute',
        //Actions
        	'Action', 'PublishArticle', 'AddItem', 'ItemToAdd', 'Preview', 'Log',  
        ));
        
        switch ( $url_array[3] )
        {
           
            case "insert" :
            {
                $Action = "Insert";
                $ArticleID = $url_array[4];
                include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;
		
            case "new" :
            {
                $Action = "New";
                include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $ArticleID = $url_array[4];

                if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;

            case "cancel" :
            {
                $Action = "Cancel";
                $ArticleID = $url_array[4];

                if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;
                        
            case "edit" :
            {
                $Action = "Edit";
                $ArticleID = $url_array[4];

                if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/articleedit.php" );
                else
                    print("Not allowed");
            }
            break;

            case "delete" :
            {
                $Action = "Delete";
                $ArticleID = $url_array[4];

                if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user , $ArticleID ) )
                    include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;

            case "imagelist" :
            {
            	eZHTTPTool::extractVar(array('MoveImageUp','MoveImageDown','AddImages', 
            		'ImageArrayID' ));
                $ArticleID = $url_array[4];
                if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/imagelist.php" );
            }
            break;

            case "medialist" :
            {
            	eZHTTPTool::extractVar(array('MoveImageUp','MoveImageDown','AddMedia', 
            		'MediaArrayID' ));
            	$ArticleID = $url_array[4];
                if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/medialist.php" );
            }
            break;

            case "filelist" :
            {
            	eZHTTPTool::extractVar(array('MoveImageUp','MoveImageDown','AddFile', 
            		'FileArrayID' ));
            	$ArticleID = $url_array[4];
                if ( eZObjectPermission::hasPermission(  $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/filelist.php" );
            }
            break;

            case "imagemap" :
            {
                switch ( $url_array[4] )
                {
                    case "edit" :
                    {
                        $ArticleID = $url_array[6];
                        $ImageID = $url_array[5];
                        $Action = "Edit";
                        if ( eZObjectPermission::hasPermission(  $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/imagemap.php" );
                    }
                    break;
                    
                    case "store" :
                    {
                        $ArticleID = $url_array[6];
                        $ImageID = $url_array[5];
                        $Action = "Store";
                        if ( eZObjectPermission::hasPermission(  $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/imagemap.php" );
                    }
                    break;
                }
            }
            break;
            
            case "attributelist" :
            {
                $ArticleID = $url_array[4];
                if ( eZObjectPermission::hasPermission(  $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/attributelist.php" );
            }
            break;

            case "attributeedit" :
            {
                $Action = $url_array[4];
                if ( !isset( $TypeID ) ) 
                    $TypeID = $url_array[5];
                if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/attributeedit.php" );
            }
            break;

            
            case "formlist" :
            {
            	$ArticleID = $url_array[4];
                if( eZObjectPermission::hasPermission(  $ArticleID, "article_article", 'w' ) ||
                    eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/formlist.php" );
            }
            break;

            
            case "imageedit" :
            {
                if ( isset( $Browse ) )
                {
                    include ( "kernel/ezimagecatalogue/admin/browse.php" );
                    break;
                }
                switch ( $url_array[4] )
                {
                    case "new" :
                    {
                        $Action = "New";
                        $ArticleID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/imageedit.php" );
                    }
                    break;

                    case "edit" :
                    {
                        $Action = "Edit";
                        $ArticleID = $url_array[6];
                        $ImageID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/imageedit.php" );
                    }
                    break;

                    case "storedef" :
                    {
                        $Action = "StoreDef";
                        if ( isset( $DeleteSelected ) )
                            $Action = "Delete";
                        $ArticleID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/imageedit.php" );
                    }
                    break;

                    default :
                    {
                        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/imageedit.php" );
                    }
                }
            }
            break;

            case "mediaedit" :
            {
                if ( isset ( $Browse ) )
                {
                    include ( "kernel/ezmediacatalogue/admin/browse.php" );
                    break;
                }
                $ArticleID = $url_array[4];
                $MediaID = $url_array[5];
                if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $ArticleID ) )
                    include( "kernel/ezarticle/admin/mediaedit.php" );
            }
            break;

            case "fileedit" :
            {
                if ( isset( $Browse ) )
                {
                    include( "kernel/ezfilemanager/admin/browse.php" );
                    break;
                }
                switch ( $url_array[4] )
                {
                    case "new" :
                    {
                        $Action = "New";
                        $ArticleID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/fileedit.php" );
                    }
                    break;

                    case "edit" :
                    {
                        $Action = "Edit";
                        $ArticleID = $url_array[6];
                        $FileID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/fileedit.php" );
                    }
                    break;

                    case "delete" :
                    {
                        $Action = "Delete";
                        $ArticleID = $url_array[6];
                        $FileID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/fileedit.php" );
                    }
                    break;
                    
                    default :
                    {
                        if ( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $ArticleID ) )
                            include( "kernel/ezarticle/admin/fileedit.php" );
                    }
                }
            }
            break;
        }
    }
    break;


    case "categoryedit":
    {
        // make switch
        if ( $url_array[3] == "cancel" )        
        {
            $Action = "Cancel";
            $CategoryID = $url_array[4];
            eZHTTPTool::header( "Location: /article/archive/$CategoryID/" );
            exit();
        }        

        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";
            include( "kernel/ezarticle/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
            include( "kernel/ezarticle/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            $CategoryID = $url_array[4];
            $Action = "update";
            if ( eZObjectPermission::hasPermission( $CategoryID, "article_category", 'w' ) ||
                 eZArticleCategory::isOwner( $user, $CategoryID) )
                include( "kernel/ezarticle/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $CategoryID = $url_array[4];
            $Action = "delete";
            if ( eZObjectPermission::hasPermission( $CategoryID, "article_category", 'w' )  ||
                 eZArticleCategory::isOwner( $user, $CategoryID) )
                include( "kernel/ezarticle/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "edit" )
        {
            $CategoryID = $url_array[4];
            $Action = "edit";
            include( "kernel/ezarticle/admin/categoryedit.php" );
        }

    }
    break;

    case "sitemap":
    {
        include( "kernel/ezarticle/admin/sitemap.php" );
    }
    break;    

    case "type":
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                    include( "kernel/ezarticle/admin/typelist.php" );
            }
            break;
            
            case "new":
            case "edit":
            case "insert":
            case "update":
            case "delete":
            case "up":
            case "down":
            {
                if ( !isset( $Action ) )
                    $Action = $url_array[3];
                if ( isset( $TypeID ) && is_numeric( $TypeID ) )
                {
                    $ActionValue = "update";
                }
                else
                {
                    $TypeID = $url_array[4];
                }
                
                if ( !isset( $TypeID ) && !is_array( $AttributeID ) )
                {
                    $AttributeID = $url_array[5];
                }
                include( "kernel/ezarticle/admin/typeedit.php" );
            }
            break;
        }
    }
    break;

    default :
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

// display a page with error msg