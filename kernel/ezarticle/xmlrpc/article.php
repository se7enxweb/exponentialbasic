<?php
//
// $Id: article.php 9771 2003-02-11 13:04:22Z jb $
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

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticleattribute.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticletool.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );
// include_once( "ezform/classes/ezform.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

if( $Command == "info" )
{
    $article = new eZArticle();
    if ( !$article->get( $ID ) )
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
    else
    {
        $ret = array( "Name" => new eZXMLRPCString( $article->name( false ) ) );
        $ReturnData = new eZXMLRPCStruct( $ret );
    }
}
else if ( $Command == "permission" )
{
    $CategoryID = eZArticle::categoryDefinitionStatic( $ID );
    $read = eZObjectPermission::hasPermissionWithDefinition( $ID, "article_article", "r", $User, $CategoryID );
    $edit = eZObjectPermission::hasPermissionWithDefinition( $ID, "article_article", 'w', $User, $CategoryID );

    $ret = array( "Read" => new eZXMLRPCBool( $read ),
                  "Edit" => new eZXMLRPCBool( $edit ) );
    $ReturnData = new eZXMLRPCStruct( $ret );
}
else if( $Command == "data" ) // return all the data in the category
{
    $article = new eZArticle();
    if ( !$article->get( $ID ) )
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
    else
    {
        $writeGroups = eZObjectPermission::getGroups( $ID, "article_article", 'w', false );
        $readGroups = eZObjectPermission::getGroups( $ID, "article_article", 'r', false );
        $contentsWriter =& $article->contentsWriter( true );

        $type_arr = array();
        $types =& $article->types();
        foreach( $types as $type )
        {
            $attributes =& $type->attributes();
            if ( count( $attributes ) > 0 )
            {
                $attr_arr = array();
                foreach( $attributes as $attrib )
                {
                    $attr_arr[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $attrib->id() ),
                                                             "Name" => new eZXMLRPCString( $attrib->name() ),
                                                             "Content" => new eZXMLRPCString( $attrib->value( $article ) ) ) );
                }
                $type_arr[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $type->id() ),
                                                         "Name" => new eZXMLRPCString( $type->name() ),
                                                         "Attributes" => new eZXMLRPCArray( $attr_arr ) ) );
            }
        }
        $images = $article->images( false );
        $img = array();
        foreach( $images as $image )
        {
            $img[] = new eZXMLRPCStruct( array( "Image" => new eZXMLRPCInt( $image["Image"] ),
                                                "Placement" => new eZXMLRPCInt( $image["Placement"] ) ) );
        }

        $cat_def_id = $article->categoryDefinition( false );
        $section_id = eZArticleCategory::sectionIDStatic( $cat_def_id );
        $section_lang = false;
        if ( $section_id != 0 )
        {
            $section = new eZSection( $section_id );
            $section_lang = $section->language();
        }
        $cats = $article->categories( false );
        $cats = array_diff( $cats, array( $cat_def_id ) );

        $ret = array( "Location" => createURLStruct( "ezarticle", "article", $article->id() ),
                      "AuthorID" => new eZXMLRPCInt( $article->author( false ) ),
                      "Name" => new eZXMLRPCString( $article->name( false ) ), // title
                      "Contents" => new eZXMLRPCString( $article->contents( false ) ),
                      "ContentsWriterID" => new eZXMLRPCInt( $contentsWriter->id() ),
                      "LinkText" => new eZXMLRPCString( $article->linkText( false ) ),
                      "ManualKeyWords" => new eZXMLRPCString( $article->manualKeywords() ),
                      "Category" => new eZXMLRPCInt( $cat_def_id ),
                      "Categories" => new eZXMLRPCArray( $cats, "integer" ),
                      "Discuss" => new eZXMLRPCBool( $article->discuss() ),
                      "IsPublished" => new eZXMLRPCBool( $article->isPublished() ),
                      "PageCount" => new eZXMLRPCInt( $article->pageCount() ),
                      "Thumbnail" => new eZXMLRPCInt( $article->thumbnailImage( false ) ),
                      "Images" => new eZXMLRPCArray( $img ),
                      "Files" => new eZXMLRPCArray( $article->files( false ), "integer" ),
                      "Forms" => new eZXMLRPCArray( $article->forms( false ), "integer" ),
                      "ReadGroups" => new eZXMLRPCArray( $readGroups, "integer" ),
                      "WriteGroups" => new eZXMLRPCArray( $writeGroups, "integer" ),
                      "Types" => new eZXMLRPCArray( $type_arr ),
                      "Topic" => new eZXMLRPCInt( $article->topic( false ) )
//                                             "PublishedDate" => new eZXMLRPCStruct(),
                      );
        if ( $section_lang != false )
        {
            $charsetLocale = new eZLocale( $section_lang );
            $section_charset = $charsetLocale->languageISO();
            $ret["Section"] = new eZXMLRPCStruct( array( "Language" => $section_lang,
                                                         "Charset" => $section_charset ) );
        }
        $start_date = $article->startDate( false );
        if ( !is_bool( $start_date ) )
            $ret["StartDate"] = createDateTimeStruct( $article->startDate() );
        $stop_date =& $article->stopDate( false );
        if ( !is_bool( $stop_date ) )
            $ret["StopDate"] = createDateTimeStruct( $article->stopDate() );
        $published =& $article->published();
        if ( $published->isValid() )
            $ret["PublishedDate"] = createDateTimeStruct( $published );
        $created =& $article->created();
        if ( $created->isValid() )
            $ret["CreatedDate"] = createDateTimeStruct( $created );
        $modified =& $article->modified();
        if ( $modified->isValid() )
            $ret["ModifiedDate"] = createDateTimeStruct( $modified );
        $ReturnData = new eZXMLRPCStruct( $ret );
    }
}
else if( $Command == "storedata" )
{
    $article = new eZArticle();
    if( $ID != 0 )
        $article->get( $ID );

    $article->setAuthor( $Data["AuthorID"]->value() );
    $article->setName( $Data["Name"]->value() ); // title
    $article->setContents( $Data["Contents"]->value() );
    $article->setContentsWriter( $Data["ContentsWriterID"]->value() );
    $article->setLinkText( $Data["LinkText"]->value() );
    $article->setDiscuss( $Data["Discuss"]->value() );
    $article->setTopic( $Data["Topic"]->value() );

    if ( isset( $Data["StartDate"] ) )
    {
        $startDate = createDateTime( $Data["StartDate"]->value() );
        $article->setStartDate( $startDate );
    }
    if ( isset( $Data["StopDate"] ) )
    {
        $stopDate = createDateTime( $Data["StopDate"]->value() );
        $article->setStopDate( $stopDate );
    }

    $article->store();
    $ID = $article->id();

    // Must have an article ID in order to set keywords
    $article->setManualKeywords( $Data["ManualKeyWords"]->value() );

    if ( isset( $Data["LogMessage"] ) )
    {
        $article->addLog( $Data["LogMessage"]->value(), $User );
    }

    $old_cat_def_id = $article->categoryDefinition( false );
    $old_cats = $article->categories( false );

    if ( isset( $Data["Category"] ) )
    {
        $cat = new eZArticleCategory( $Data["Category"]->value() );
        $article->setCategoryDefinition( $cat );
    }

    $add_locs = array();
    $cur_locs = array();
    $old_locs = array();
    if ( isset( $Data["Category" ] ) && isset( $Data["Categories"] ) )
    {
        $cat = $Data["Category"]->value();
        $cat_arr =& $Data["Categories"]->value();
        $cats = array( $cat );
        foreach( $cat_arr as $cat )
        {
            $cats[] = $cat->value();
        }
        $cats = array_unique( $cats );
        $remove_categories = array_diff( $old_cats, $cats );
        $add_categories = array_diff( $cats, $old_cats );
        $cur_categories = array_intersect( $old_cats, $cats );
    }

    if ( isset( $Data["Categories"] ) )
    {
        foreach( $remove_categories as $cat )
        {
            eZArticleCategory::removeArticle( $article, $cat );
        }
        foreach( $add_categories as $cat )
        {
            eZArticleCategory::addArticle( $article, $cat );
        }
    }

    if ( isset( $Data["Category" ] ) && isset( $Data["Categories"] ) )
    {
        $add_locs =& createURLArray( $add_categories, "ezarticle", "category" );
        $cur_locs =& createURLArray( $cur_categories, "ezarticle", "category" );
        $old_locs =& createURLArray( $remove_categories, "ezarticle", "category" );
    }

    // images
    $images = $Data["Images"]->value();
    $new_images = array();
    foreach( $images as $img )
    {
        $image = $img->value();
        $id = $image["Image"]->value();
        $ix = $image["Placement"]->value();
        $new_images[$ix] = $id;
    }
    $images = $article->images( false );
    $old_images = array();
    foreach( $images as $image )
    {
        $id = $image["Image"];
        $ix = $image["Placement"];
        $old_images[$ix] = $id;
    }
    $del_images = array_diff( $old_images, $new_images );
    $added_images = array_diff( $new_images, $old_images );
    $changed_images = array_intersect( $new_images, $old_images );

    foreach( $del_images as $image )
    {
        $article->deleteImage( $image );
    }
    while( list($add_ix, $add_id) = each($added_images) )
    {
        $article->addImage( $add_id, $add_ix );
    }

    if ( $Data["Thumbnail"]->value() > 0 )
    {
        $thumbImage = new eZImage( $Data["Thumbnail"]->value() );
    }
    else
        $thumbImage = false;
    $article->setThumbnailImage( $thumbImage );

    // files
    $files = $Data["Files"]->value();
    $new_files = array();
    foreach( $files as $fl )
    {
        $new_files[] = $fl->value();
    }
    $files =& $article->files( false );
    $old_files = array_diff( $files, $new_files );
    $added_files = array_diff( $new_files, $files );
    $changed_files = array_intersect( $new_files, $files );
    foreach( $old_files as $file )
        $article->deleteFile( $file );
    foreach( $added_files as $file )
        $article->addFile( $file );

    // permissions....
    eZObjectPermission::removePermissions( $ID, "article_article", 'r' );
    $readGroups = $Data["ReadGroups"]->value();
    foreach( $readGroups as $readGroup )
        eZObjectPermission::setPermission( $readGroup->value(), $ID, "article_article", 'r' );

    eZObjectPermission::removePermissions( $ID, "article_article", 'w' );
    $writeGroups = $Data["WriteGroups"]->value();
    foreach( $writeGroups as $writeGroup )
        eZObjectPermission::setPermission( $writeGroup->value(), $ID, "article_article", 'w' );

    // types
    $types = $Data["Types"]->value();
    $old_types = $article->types( false );
    $new_types = array();
    foreach ( $types as $type )
    {
        $type = $type->value();
        $typeID = $type["ID"]->value();
        $new_types[] = $typeID;
        $attrs = $type["Attributes"]->value();
        $attr_map = array();
        foreach( $attrs as $attr )
        {
            $attr = $attr->value();
            $id = $attr["ID"]->value();
            $name = $attr["Name"]->value();
            $content = $attr["Content"]->value();
            $attr_map[$id] = array( "ID" => $id,
                                    "Name" => $name,
                                    "Content" => $content );
        }
        $articleType = new eZArticleType( $typeID );
        $attributes = $articleType->attributes();

        foreach( $attributes as $attribute )
        {
            $id = $attribute->id();
            if ( isset( $attr_map[$id] ) )
            {
                $attribute->setName( $attr_map[$id]["Name"] );
                $attribute->store();
                $attribute->setValue( $article, $attr_map[$id]["Content"] );
            }
        }
    }
    $removed_types = array_diff( $old_types, $new_types );
    foreach( $removed_types as $typeID )
    {
        $type = new eZArticleType();
        if ( $type->get( $typeID ) )
        {
            $article->deleteAttributesByType( $type );
        }
    }

    // forms
    $article->deleteForms();
    $forms = $Data["Forms"]->value();
    foreach( $forms as $form )
    {
        $form = new eZForm( $form->value() );
        $article->addForm( $form );
    }

    $waspublished = true;
    if ( !$article->isPublished() )
    {
        $waspublished = false;
    }

    // Set is published and store again
    $article->setIsPublished( $Data["IsPublished"]->value(), $User );
    $article->store();

    if ( $article->isPublished() && !$waspublished )
    {
        eZArticleTool::notificationMessage( $article );
    }

    // categories
    $category = new eZArticleCategory( eZArticle::categoryDefinitionStatic( $ID ) );
    $par =& createPath( $category, "ezarticle", "category" );

    $category = $article->categoryDefinition( );
    $CategoryID = $category->id();
    $CategoryArray =& $article->categories( false );
    eZArticleTool::deleteCache( $ID, $CategoryID, $CategoryArray );

    $cat_def_id = $article->categoryDefinition( false );
    $section_id = eZArticleCategory::sectionIDStatic( $cat_def_id );
    $section_lang = false;
    if ( $section_id != 0 )
    {
        $section = new eZSection( $section_id );
        $section_lang = $section->language();
    }

    $ret_arr = array( "Location" => createURLStruct( "ezarticle", "article", $ID ),
                      "Name" => new eZXMLRPCString( $article->name( false ) ),
                      "Path" => new eZXMLRPCArray( $par ),
                      "NewLocations" => $add_locs,
                      "ChangedLocations" => $cur_locs,
                      "RemovedLocations" => $old_locs,
                      "UpdateType" => new eZXMLRPCString( $Command )
                      );
    if ( $section_lang != false )
    {
        $charsetLocale = new eZLocale( $section_lang );
        $section_charset = $charsetLocale->languageISO();
        $ret_arr["Section"] = new eZXMLRPCStruct( array( "Language" => $section_lang,
                                                         "Charset" => $section_charset ) );
    }
    $ReturnData = new eZXMLRPCStruct( $ret_arr );
    $Command = "update";

}
else if( $Command == "delete" )
{
    $category = eZArticle::categoryDefinitionStatic( $ID );
    $category = new eZArticleCategory( $category );
    $path =& $category->path();
    if ( $category->id() != 0 )
    {
        $par[] = createURLStruct( "ezarticle", "category", 0 );
    }
    else
    {
        $par[] = createURLStruct( "ezarticle", "" );
    }
    foreach( $path as $item )
    {
        if ( $item[0] != $category->id() )
            $par[] = createURLStruct( "ezarticle", "category", $item[0] );
    }

    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "article", $ID ),
                                             "Path" => new eZXMLRPCArray( $par ),
                                             "UpdateType" => new eZXMLRPCString( $Command )
                                             )
                                      );
    $Command = "update";

    $article = new eZArticle( $ID );
    $category = $article->categoryDefinition( );
    $CategoryID = $category->id();
    $CategoryArray =& $article->categories( false );
    eZArticleTool::deleteCache( $ID, $CategoryID, $CategoryArray );

    $article->delete();
}
else if ( $Command == "search" )
{
    $texts = $Data["Keywords"]->value();
    $text = "";
    foreach( $texts as $txt )
    {
        if ( $text == "" )
            $text = $text . $txt->value();
        else
            $text = $text . " " . $txt->value();
    }
    $elements = array();
    $article = new eZArticle();
    $params = array();
    if ( isset( $Data["Parameters"] ) )
    {
        $par = $Data["Parameters"]->value();
        if ( isset( $par["FromDate"] ) )
            $params["FromDate"] = createDateTime( $par["FromDate"]->value() );
        if ( isset( $par["ToDate"] ) )
            $params["ToDate"] = createDateTime( $par["ToDate"]->value() );
        if ( isset( $par["Categories"] ) )
            $params["Categories"] = $par["Categories"]->toArray();
        if ( isset( $par["Type"] ) )
            $params["Type"] = $par["Type"]->value();
        if ( isset( $par["AuthorID"] ) )
            $params["AuthorID"] = $par["AuthorID"]->value();
        if ( isset( $par["PhotographerID"] ) )
            $params["PhotographerID"] = $par["PhotographerID"]->value();
    }
    $search_count = 0;
    $result =& $article->search( $text, "alpha", true, 0, -1, $params, $search_count );
    foreach( $result as $item )
    {
        $cat =& $item->categoryDefinition();
        $itemid = $item->id();
        $catid = $cat->id();
        $elements[] = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $item->name( false ) ),
                                                 "CategoryName" => new eZXMLRPCString( $cat->name( false ) ),
                                                 "Location" => createURLStruct( "ezarticle", "article", $item->id() ),
                                                 "CategoryLocation" => createURLStruct( "ezarticle", "category", $cat->id() ),
                                                 "WebURL" => new eZXMLRPCString( rewriteWebURL( "/article/articleview/$itemid/1/$catid/" ) ),
                                                 "CategoryWebURL" => new eZXMLRPCString( rewriteWebURL( "/article/archive/$catid/" ) )
                                                 ) );
    }
    $ret = array( "Elements" => new eZXMLRPCArray( $elements ) );
    handleSearchData( $ret );
    $ReturnData = new eZXMLRPCStruct( $ret );
}
else if ( $Command == "weburl" )
{
    $path = "/article/articleview/" .
         $Data["ArticleID"]->value() . "/" .
         $Data["ArticlePage"]->value() . "/" .
         $Data["ArticleCategory"]->value() . "/";
    $ReturnData = new eZXMLRPCStruct(
        array( "WebURL" => new eZXMLRPCString( rewriteWebURL( $path ) ) )
        );
}

?>
