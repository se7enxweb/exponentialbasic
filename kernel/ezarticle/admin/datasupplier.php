<?php
//
// $id: datasupplier.php 9560 2002-05-22 13:35:33Z bf $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "classes/ezdatetime.php" );

#echo " " . $url_array[2] . " " . $url_array[3] . " " . $url_array[4] . " " . $url_array[5];
#exit();

$user = eZUser::currentUser();
if ( eZPermission::checkPermission( $user, "eZArticle", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

// --- Explicit POST/GET extraction (replaces register_globals hack for this module) ---
$action              = eZHTTPTool::getVar( 'Action' );
$actionValue         = eZHTTPTool::getVar( 'ActionValue' );
$cancel              = eZHTTPTool::getVar( 'Cancel' );
$name                = eZHTTPTool::getVar( 'Name' );
$description         = eZHTTPTool::getVar( 'Description' );
$parentID            = eZHTTPTool::getVar( 'ParentID' );
$categoryID          = eZHTTPTool::getVar( 'CategoryID' );
$sectionID           = eZHTTPTool::getVar( 'SectionID' );
$articleID           = eZHTTPTool::getVar( 'ArticleID' );
$imageID             = eZHTTPTool::getVar( 'ImageID' );
$fileID              = eZHTTPTool::getVar( 'FileID' );
$mediaID             = eZHTTPTool::getVar( 'MediaID' );
$attributeID         = eZHTTPTool::getVar( 'AttributeID' );
$topicID             = eZHTTPTool::getVar( 'TopicID' );
$typeID              = eZHTTPTool::getVar( 'TypeID' );
$editorGroupID       = eZHTTPTool::getVar( 'EditorGroupID' );
$ownerID             = eZHTTPTool::getVar( 'OwnerID' );
$bulkMailID          = eZHTTPTool::getVar( 'BulkMailID' );
$listLimit           = eZHTTPTool::getVar( 'ListLimit' );
$sortMode            = eZHTTPTool::getVar( 'SortMode' );
$excludeFromSearch   = eZHTTPTool::getVar( 'ExcludeFromSearch' );
$deleteImage         = eZHTTPTool::getVar( 'DeleteImage' );
$deleteSelected      = eZHTTPTool::getVar( 'DeleteSelected' );
$deleteArticles      = eZHTTPTool::getVar( 'DeleteArticles' );
$deleteCategories    = eZHTTPTool::getVar( 'DeleteCategories' );
$deleteArrayID       = eZHTTPTool::getVar( 'DeleteArrayID' );
$deleteIDArray       = eZHTTPTool::getVar( 'DeleteIDArray' );
$deleteTopic         = eZHTTPTool::getVar( 'DeleteTopic' );
$deleteAttributes    = eZHTTPTool::getVar( 'DeleteAttributes' );
$articleArrayID      = eZHTTPTool::getVar( 'ArticleArrayID' );
$categoryArrayID     = eZHTTPTool::getVar( 'CategoryArrayID' );
$imageArrayID        = eZHTTPTool::getVar( 'ImageArrayID' );
$fileArrayID         = eZHTTPTool::getVar( 'FileArrayID' );
$mediaArrayID        = eZHTTPTool::getVar( 'MediaArrayID' );
$typeArrayID         = eZHTTPTool::getVar( 'TypeArrayID' );
$idArray             = eZHTTPTool::getVar( 'IDArray' );
$groupArray          = eZHTTPTool::getVar( 'GroupArray' );
$writeGroupArray     = eZHTTPTool::getVar( 'WriteGroupArray' );
$postedCategoryArray = eZHTTPTool::getVar( 'CategoryArray' );
$articleSelection    = eZHTTPTool::getVar( 'ArticleSelection' );
$postedContents      = eZHTTPTool::getVar( 'Contents' );
$postedKeywords      = eZHTTPTool::getVar( 'Keywords' );
$postedUrltranslator = eZHTTPTool::getVar( 'Urltranslator' );
$urltranslatorEnabled= eZHTTPTool::getVar( 'UrltranslatorEnabled' );
$authorText          = eZHTTPTool::getVar( 'AuthorText' );
$authorEmail         = eZHTTPTool::getVar( 'AuthorEmail' );
$newAuthorName       = eZHTTPTool::getVar( 'NewAuthorName' );
$newAuthorEmail      = eZHTTPTool::getVar( 'NewAuthorEmail' );
$newCreatorName      = eZHTTPTool::getVar( 'NewCreatorName' );
$newCreatorEmail     = eZHTTPTool::getVar( 'NewCreatorEmail' );
$newPhotographerName = eZHTTPTool::getVar( 'NewPhotographerName' );
$newPhotographerEmail= eZHTTPTool::getVar( 'NewPhotographerEmail' );
$photographerID      = eZHTTPTool::getVar( 'PhotographerID' );
$contentsWriterID    = eZHTTPTool::getVar( 'ContentsWriterID' );
$linkText            = eZHTTPTool::getVar( 'LinkText' );
$linkID              = eZHTTPTool::getVar( 'LinkID' );
$caption             = eZHTTPTool::getVar( 'Caption' );
$newImage            = eZHTTPTool::getVar( 'NewImage' );
$addImages           = eZHTTPTool::getVar( 'AddImages' );
$addFiles            = eZHTTPTool::getVar( 'AddFiles' );
$addMedia            = eZHTTPTool::getVar( 'AddMedia' );
$addItem             = eZHTTPTool::getVar( 'AddItem' );
$itemToAdd           = eZHTTPTool::getVar( 'ItemToAdd' );
$preview             = eZHTTPTool::getVar( 'Preview' );
$publishArticle      = eZHTTPTool::getVar( 'PublishArticle' );
$isPublished         = eZHTTPTool::getVar( 'IsPublished' );
$discuss             = eZHTTPTool::getVar( 'Discuss' );
$noFrontImage        = eZHTTPTool::getVar( 'NoFrontImage' );
$searchText          = eZHTTPTool::getVar( 'SearchText' );
$store               = eZHTTPTool::getVar( 'Store' );
$storeSelection      = eZHTTPTool::getVar( 'StoreSelection' );
$update              = eZHTTPTool::getVar( 'Update' );
$ok                  = eZHTTPTool::getVar( 'OK' ) ?? eZHTTPTool::getVar( 'Ok' );
$moveCategoryUp      = eZHTTPTool::getVar( 'MoveCategoryUp' );
$moveCategoryDown    = eZHTTPTool::getVar( 'MoveCategoryDown' );
$moveUp              = eZHTTPTool::getVar( 'MoveUp' );
$moveDown            = eZHTTPTool::getVar( 'MoveDown' );
$moveImageUp         = eZHTTPTool::getVar( 'MoveImageUp' );
$moveImageDown       = eZHTTPTool::getVar( 'MoveImageDown' );
$startStamp          = eZHTTPTool::getVar( 'StartStamp' );
$stopStamp           = eZHTTPTool::getVar( 'StopStamp' );
$startDay            = eZHTTPTool::getVar( 'StartDay' );
$startMonth          = eZHTTPTool::getVar( 'StartMonth' );
$startYear           = eZHTTPTool::getVar( 'StartYear' );
$startHour           = eZHTTPTool::getVar( 'StartHour' );
$startMinute         = eZHTTPTool::getVar( 'StartMinute' );
$stopDay             = eZHTTPTool::getVar( 'StopDay' );
$stopMonth           = eZHTTPTool::getVar( 'StopMonth' );
$stopYear            = eZHTTPTool::getVar( 'StopYear' );
$stopHour            = eZHTTPTool::getVar( 'StopHour' );
$stopMinute          = eZHTTPTool::getVar( 'StopMinute' );
$newTopic            = eZHTTPTool::getVar( 'NewTopic' );
$newType             = eZHTTPTool::getVar( 'NewType' );
$newAttribute        = eZHTTPTool::getVar( 'NewAttribute' );
$attributeName       = eZHTTPTool::getVar( 'AttributeName' );
$attributeValue      = eZHTTPTool::getVar( 'AttributeValue' );
$values              = eZHTTPTool::getVar( 'Values' );
$photoID             = eZHTTPTool::getVar( 'PhotoID' );
$siteDesign          = eZHTTPTool::getVar( 'SiteDesign' ) ?? $siteDesign;
$siteStyle           = eZHTTPTool::getVar( 'SiteStyle' ) ?? $siteDesign;
$preferencesSetting  = eZHTTPTool::getVar( 'PreferencesSetting' );
$recursive           = eZHTTPTool::getVar( 'Recursive' );
$copyCategories      = eZHTTPTool::getVar( 'CopyCategories' );
$currentCategoryID   = eZHTTPTool::getVar( 'CurrentCategoryID' );
$goTo                = eZHTTPTool::getVar( 'GoTo' );
$goToCategoryID      = eZHTTPTool::getVar( 'GoToCategoryID' );
$objectID            = eZHTTPTool::getVar( 'ObjectID' );
$objectType          = eZHTTPTool::getVar( 'ObjectType' );
$productID           = eZHTTPTool::getVar( 'ProductID' );
$vatType             = eZHTTPTool::getVar( 'VatType' );
$quantity            = eZHTTPTool::getVar( 'Quantity' );
$stock               = eZHTTPTool::getVar( 'Stock' );
$stockDay            = eZHTTPTool::getVar( 'StockDay' );
$stockMonth          = eZHTTPTool::getVar( 'StockMonth' );
$stockYear           = eZHTTPTool::getVar( 'StockYear' );
$pricesIncludeVAT    = eZHTTPTool::getVar( 'PricesIncludeVAT' );
$showQuantity        = eZHTTPTool::getVar( 'ShowQuantity' );
$shippingGroup       = eZHTTPTool::getVar( 'ShippingGroup' );
$clientModuleName    = eZHTTPTool::getVar( 'ClientModuleName' );
$clientModuleType    = eZHTTPTool::getVar( 'ClientModuleType' );
$detailView          = eZHTTPTool::getVar( 'DetailView' );
$normalView          = eZHTTPTool::getVar( 'NormalView' );
$showModuleLinker    = eZHTTPTool::getVar( 'ShowModuleLinker' );
$log                 = eZHTTPTool::getVar( 'Log' );
$logMessage          = eZHTTPTool::getVar( 'LogMessage' );
$funcs               = eZHTTPTool::getVar( 'Funcs' );
$browse              = eZHTTPTool::getVar( 'Browse' );
$boxType             = eZHTTPTool::getVar( 'BoxType' );
// URL-routing vars are set explicitly in the switch below and override extracted values
// -----------------------------------------------------------------------------------

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
        if ( !is_numeric( $categoryID=eZHTTPTool::getVar( "CategoryID", true ) ) )
        {
            $categoryID = $url_array[3];
            if  ( !isset( $categoryID ) || ( $categoryID == "" ) )
                $categoryID = 0;
        }
        
        if ( isset($url_array[4]) && $url_array[4] == "parent" )
            $offset = $url_array[5];

        if ( $categoryID == 0 || eZObjectPermission::hasPermission( $categoryID, "article_category", 'r' )  ||
             eZArticleCategory::isOwner( $user, $categoryID ) )
            include( "kernel/ezarticle/admin/articlelist.php" );
    }
    break;

    case "unpublished":
    {
        $categoryID = $url_array[3];
        if  ( !isset( $categoryID ) || ( $categoryID == "" ) )
            $categoryID = 0;

        if ( $url_array[4] == "parent" )
            $offset = $url_array[5];

        if ( $categoryID == 0 || eZObjectPermission::hasPermission( $categoryID, "article_category", 'r' ) ||
             eZArticleCategory::isOwner( $user, $categoryID) )
            include( "kernel/ezarticle/admin/unpublishedlist.php" );
    }
    break;

    case "pendinglist":
    {
        $categoryID = $url_array[3];
        if ( !isset( $categoryID ) || ( $categoryID == "" ) )
            $categoryID = 0;

        if ( $url_array[4] == "parent" )
            $offset = $url_array[5];

        if ( $categoryID == 0 || eZObjectPermission::hasPermission( $categoryID, "article_category", 'r' ) ||
             eZArticleCategory::isOwner( $user, $categoryID) )
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
            $offset = 0;
            if ( $url_array[3] == "parent" )
            {
                $searchText = urldecode( $url_array[4] );
                if ( $url_array[5] != urlencode( "+" ) )
                    $startStamp = urldecode( $url_array[5] );
                if ( $url_array[6] != urlencode( "+" ) )
                    $stopStamp = urldecode( $url_array[6] );
                if ( $url_array[7] != urlencode( "+" ) )
                    $categoryArray = explode( "-", urldecode( $url_array[7] ) );
                if ( $url_array[8] != urlencode( "+" ) )
                    $contentsWriterID = urldecode( $url_array[8] );
                if ( $url_array[9] != urlencode( "+" ) )
                    $photographerID = urldecode( $url_array[9] );
                
                $offset = $url_array[10];
            }
            include( "kernel/ezarticle/admin/search.php" );
        }
    }
    break;

    case "view":    
    case "articleview":
    case "articlepreview":
    {
        $articleID = $url_array[3];
        $pageNumber= $url_array[4];
        if ( !isset( $pageNumber ) || ( $pageNumber == "" ) )
            $pageNumber= 1;

        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'r' ) ||
             eZArticle::isAuthor( $user, $articleID ) )
            include( "kernel/ezarticle/admin/articlepreview.php" );
    }
    break;

    case "articlelog" :
    {
        $articleID = $url_array[3];
        if ( eZObjectPermission::hasPermission(  $articleID, "article_article", 'w' ) ||
             eZArticle::isAuthor( $user, $articleID ) )
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
                $action = "Insert";
                $articleID = $url_array[4];
                include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;
		
            case "new" :
            {
                $action = "New";
                include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;

            case "update" :
            {
                $action = "Update";
                $articleID = $url_array[4];

                if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;

            case "cancel" :
            {
                $action = "Cancel";
                $articleID = $url_array[4];

                if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;
                        
            case "edit" :
            {
                $action = "Edit";
                $articleID = $url_array[4];

                if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/articleedit.php" );
                else
                    print("Not allowed");
            }
            break;

            case "delete" :
            {
                $action = "Delete";
                $articleID = $url_array[4];

                if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user , $articleID ) )
                    include( "kernel/ezarticle/admin/articleedit.php" );
            }
            break;

            case "imagelist" :
            {
            	eZHTTPTool::extractVar(array('MoveImageUp','MoveImageDown','AddImages', 
            		'ImageArrayID' ));
                $articleID = $url_array[4];
                if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/imagelist.php" );
            }
            break;

            case "medialist" :
            {
            	eZHTTPTool::extractVar(array('MoveImageUp','MoveImageDown','AddMedia', 
            		'MediaArrayID' ));
            	$articleID = $url_array[4];
                if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/medialist.php" );
            }
            break;

            case "filelist" :
            {
            	eZHTTPTool::extractVar(array('MoveImageUp','MoveImageDown','AddFile', 
            		'FileArrayID' ));
            	$articleID = $url_array[4];
                if ( eZObjectPermission::hasPermission(  $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/filelist.php" );
            }
            break;

           case "link" :
            {
                $itemID = $url_array[5];
                include_once( "ezarticle/classes/ezarticle.php" );
                include_once( "ezarticle/classes/ezarticletool.php" );

                $iniGroup = "eZArticleMain";
                $defaultSectionsName = "ArticleLinkSections";
                $preferencesSetting = "ArticleLinkType";
                $clientModuleName = "eZArticle";
                $clientModuleType = "Article";
                $root = "/article/articleedit";
                $urls = array( "back" => "$root/edit/%s",
                               "linklist" => "$root/link/list/%s",
                               "linkmoveup" => "$root/link/moveup/link/%d/%d/%d",
                               "linkmovedown" => "$root/link/movedown/link/%d/%d/%d",
                               "sectionmoveup" => "$root/link/moveup/section/%d/%d",
                               "sectionmovedown" => "$root/link/movedown/section/%d/%d",
                               "linkselect" => "$root/link/select/%s/%s/%s/%s/%s/0/%s",
                               "linkselect_basic" => "$root/link/select/",
                               "linkselect_std" => "$root/link/select/%s/%s/%s/%s/%s",
                               "urledit" => "$root/link/select/%s/%s/%s/%s",
                               "linkedit" => "$root/link/select/%s/%s/%s/0/0/%s" );
                $funcs = array( "delete" => "deleteCacheHelper" );

                function deleteCacheHelper( $articleID )
                    {
                        eZArticleTool::deleteCache( $articleID, $categoryID, $categoryArray );
                    }  

                switch( $url_array[4] )
                {
                    case "list":
                    {
                        include( "classes/admin/linklist.php" );
                        break;
                    }
                    case "select":
                    {
                        if ( isset( $url_array[6] ) )
                            $moduleName = $url_array[6];
                        if ( isset( $url_array[7] ) )
                            $type = $url_array[7];
                        if ( isset( $url_array[8] ) )
                            $sectionID = $url_array[8];
                        if ( isset( $url_array[9] ) )
                            $category = $url_array[9];
                        if ( isset( $url_array[10] ) )
                            $offset = $url_array[10];
                        if ( isset( $url_array[11] ) )
                            $linkID = $url_array[11];

                        include( "classes/admin/linkselect.php" );
                        break;
                    }
                    case "moveup":
                        $moveUp = true;
                    case "movedown":
                    {
                        if ( isset( $url_array[5] ) )
                            $objectType = $url_array[5];
                        if ( isset( $url_array[6] ) )
                            $itemID = $url_array[6];
                        if ( isset( $url_array[7] ) )
                            $objectID = $url_array[7];
                        if ( isset( $url_array[8] ) )
                            $linkID = $url_array[8];
                        include( "classes/admin/linkmove.php" );
                        break;
                    }
                    default:
                    {
                        eZHTTPTool::header( "Location: /error/404" );
                        break;
                    }
                }
                break;
            }

            case "imagemap" :
            {
                switch ( $url_array[4] )
                {
                    case "edit" :
                    {
                        $articleID = $url_array[6];
                        $imageID = $url_array[5];
                        $action = "Edit";
                        if ( eZObjectPermission::hasPermission(  $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/imagemap.php" );
                    }
                    break;
                    
                    case "store" :
                    {
                        $articleID = $url_array[6];
                        $imageID = $url_array[5];
                        $action = "Store";
                        if ( eZObjectPermission::hasPermission(  $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/imagemap.php" );
                    }
                    break;
                }
            }
            break;
            
            case "attributelist" :
            {
                $articleID = $url_array[4];
                if ( eZObjectPermission::hasPermission(  $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/attributelist.php" );
            }
            break;

            case "attributeedit" :
            {
                $action = $url_array[4];
                if ( !isset( $typeID ) ) 
                    $typeID = $url_array[5];
                if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/attributeedit.php" );
            }
            break;

            
            case "formlist" :
            {
            	$articleID = $url_array[4];
                if( eZObjectPermission::hasPermission(  $articleID, "article_article", 'w' ) ||
                    eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/formlist.php" );
            }
            break;

            
            case "imageedit" :
            {
                if ( isset( $browse ) )
                {
                    // Compatibility aliases for ezimagecatalogue/browse.php (not yet refactored)
                    $CategoryID = $categoryID;
                    $SearchText = $searchText;
                    include ( "kernel/ezimagecatalogue/admin/browse.php" );
                    break;
                }
                switch ( $url_array[4] )
                {
                    case "new" :
                    {
                        $action = "New";
                        $articleID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/imageedit.php" );
                    }
                    break;

                    case "edit" :
                    {
                        $action = "Edit";
                        $articleID = $url_array[6];
                        $imageID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/imageedit.php" );
                    }
                    break;

                    case "storedef" :
                    {
                        $action = "StoreDef";
                        if ( isset( $deleteSelected ) )
                            $action = "Delete";
                        $articleID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/imageedit.php" );
                    }
                    break;

                    default :
                    {
                        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/imageedit.php" );
                    }
                }
            }
            break;

            case "mediaedit" :
            {
                if ( isset ( $browse ) )
                {
                    // Compatibility aliases for ezmediacatalogue/browse.php (not yet refactored)
                    $CategoryID = $categoryID;
                    include ( "kernel/ezmediacatalogue/admin/browse.php" );
                    break;
                }
                $articleID = $url_array[4];
                $mediaID = $url_array[5];
                if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                     eZArticle::isAuthor( $user, $articleID ) )
                    include( "kernel/ezarticle/admin/mediaedit.php" );
            }
            break;

            case "fileedit" :
            {
                if ( isset( $browse ) )
                {
                    // Compatibility aliases for ezfilemanager/browse.php (not yet refactored)
                    $FolderID = eZHTTPTool::getVar( 'FolderID' );
                    include( "kernel/ezfilemanager/admin/browse.php" );
                    break;
                }
                switch ( $url_array[4] )
                {
                    case "new" :
                    {
                        $action = "New";
                        $articleID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/fileedit.php" );
                    }
                    break;

                    case "edit" :
                    {
                        $action = "Edit";
                        $articleID = $url_array[6];
                        $fileID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/fileedit.php" );
                    }
                    break;

                    case "delete" :
                    {
                        $action = "Delete";
                        $articleID = $url_array[6];
                        $fileID = $url_array[5];
                        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
                            include( "kernel/ezarticle/admin/fileedit.php" );
                    }
                    break;
                    
                    default :
                    {
                        if ( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) ||
                             eZArticle::isAuthor( $user, $articleID ) )
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
            $action = "Cancel";
            $categoryID = $url_array[4];
            eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
            exit();
        }        

        if ( $url_array[3] == "insert" )
        {
            $action = "insert";
            include( "kernel/ezarticle/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "new" )
        {
            $action = "new";
            include( "kernel/ezarticle/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            $categoryID = $url_array[4];
            $action = "update";
            if ( eZObjectPermission::hasPermission( $categoryID, "article_category", 'w' ) ||
                 eZArticleCategory::isOwner( $user, $categoryID) )
                include( "kernel/ezarticle/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $categoryID = $url_array[4];
            $action = "delete";
            if ( eZObjectPermission::hasPermission( $categoryID, "article_category", 'w' )  ||
                 eZArticleCategory::isOwner( $user, $categoryID) )
                include( "kernel/ezarticle/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "edit" )
        {
            $categoryID = $url_array[4];
            $action = "edit";
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
                if ( !isset( $action ) )
                    $action = $url_array[3];
                if ( isset( $typeID ) && is_numeric( $typeID ) )
                {
                    $actionValue = "update";
                }
                else
                {
                    $typeID = $url_array[4];
                }
                
                if ( !isset( $attributeID ) )
                {
                    $attributeID = $url_array[5];
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

?>