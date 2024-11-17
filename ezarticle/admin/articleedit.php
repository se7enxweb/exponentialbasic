<?php
//
// $Id: articleedit.php 9526 2002-05-14 06:52:21Z bf $
//
// Created on: <18-Oct-2000 15:04:39 bf>
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
include_once( "classes/ezcachefile.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezuser/classes/ezauthor.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/eztopic.php" );
include_once( "ezarticle/classes/ezarticlegenerator.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );

include_once( "ezbulkmail/classes/ezbulkmail.php" );
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

include_once( "ezarticle/classes/ezarticletool.php" );

include_once( "ezxml/classes/ezxml.php" );

$ini =& INIFile::globalINI();

$CategoryID = isset($_POST["CategoryID"]) && $_POST["CategoryID"];

// article published from preview
if ( isset( $PublishArticle ) )
{
    $article = new eZArticle();

    if ( $article->get( $ArticleID ) )
    {
        $article->setIsPublished( true );
        $article->store();
    }

    $category =& $article->categoryDefinition( );

    if ( $category )
    {
        $categoryID = $category->id();
    }

    eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
    exit();
}

if ( $Action == "Cancel" )
{
    $article = new eZArticle( $ArticleID );

    $category = $article->categoryDefinition( );

    if ( $category )
    {
        $categoryID = $category->id();
    }

    eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
    exit();
}

// update an existing article in the database
if ( $Action == "Update" || ( $Action == "Insert" ) )
{
    $sendNotification = false;
    $article = new eZArticle( );

    if ( ( $Action == "Insert" ) or ( $article->get( $ArticleID ) == true ) )
    {
        if ( $Action == "Insert" )
        {
            $article = new eZArticle( );
            $user =& eZUser::currentUser();
            $article->setAuthor( $user );
        }

        $article->setName( $_POST['Name'] );

        if ( trim( $_POST['NewAuthorName'] ) != "" )
        {
            $author = new eZAuthor();
            $author->setName( $NewAuthorName );
            $author->setEmail( $NewAuthorEmail );
            $author->store();
            $article->setContentsWriter( $author );
        }
        else
        {
            $author = new eZAuthor( $ContentsWriterID );
            $article->setContentsWriter( $author );
        }

        $topic = new eZTopic( $TopicID );
        $article->setTopic( $topic );

        $generator = new eZArticleGenerator();

        $contents = $generator->generateXML( $Contents );

        $article->setContents( $contents );
        $article->setPageCount( $generator->pageCount() );


        $article->setAuthorText( $AuthorText );
        $article->setAuthorEmail( $AuthorEmail );
        $article->setLinkText( $LinkText );

        if ( trim( $LogMessage ) != "" )
            $article->addLog( $LogMessage );

        if ( $Discuss == "on" )
            $article->setDiscuss( true );
        else
            $article->setDiscuss( false );

        // check if the contents is parseable
        if ( eZXML::domTree( $contents ) )
        {
            // to get ID
            $article->store();

            // add to categories
            $category = new eZArticleCategory( $CategoryID );
            $article->setCategoryDefinition( $category );

            $iniVar = $ini->read_var( "eZArticleMain", "LowerCaseManualKeywords" );

            if ( $iniVar == "enabled" )
                $toLower = true;
            else
                $toLower = false;

            $article->setManualKeywords( $Keywords, $toLower );

            $categoryArray =& $article->categories();

            // Calculate new and unused categories
            $old_maincategory = $article->categoryDefinition();
            $old_categories =& array_unique( array_merge( $old_maincategory->id(),
                                                          $article->categories( false ) ) );

            $new_categories = array_unique( array_merge( $CategoryID, $CategoryArray ) );

            $remove_categories = array_diff( $old_categories, $new_categories );
            $add_categories = array_diff( $new_categories, $old_categories );

            $categoryIDArray = array();

            foreach ( $categoryArray as $cat )
            {
                $categoryIDArray[] = $cat->id();
            }

            // clear the cache files.
            eZArticleTool::deleteCache( $ArticleID, $CategoryID, $old_categories );

            foreach ( $remove_categories as $categoryItem )
            {
                eZArticleCategory::removeArticle( $article, $categoryItem );
            }

            // add to categories


            $category = new eZArticleCategory( $CategoryID );
            $category->addArticle( $article );
            $article->setCategoryDefinition( $category );

            foreach ( $add_categories as $categoryItem )
            {
                eZArticleCategory::addArticle( $article, $categoryItem );
            }

            //EP: URL translation inside articles -------------------------

            if ( $UrltranslatorEnabled )
            {
                $tmpCategory = $article->categoryDefinition();
                $url1 = "/article/articleview/" . $article->id() . "/1/" . $tmpCategory->id();

                include_once( "ezurltranslator/classes/ezurltranslator.php" );
                $urltranslator = new eZURLTranslator();
                $urltranslator->getbydest ( $url1 );

                if ( $Urltranslator )
                {
                    $urltranslator->setSource( $Urltranslator );
                    $urltranslator->setDest( $url1 );
                    $urltranslator->store();
                }
                else
                {
                    $urltranslator->delete();
                }
            }

            //EP -------------------------------------------------------------

            // Time publishing
            if ( checkdate( (int)$StartMonth, (int)$StartDay, (int)$StartYear ) )
            {
                $startDate = new eZDateTime( $StartYear,  $StartMonth, $StartDay, $StartHour, $StartMinute, 0 );
                $article->setStartDate( $startDate );
            }

            if ( checkdate( (int)$StopMonth, (int)$StopDay, (int)$StopYear ) )
            {
                $stopDate = new eZDateTime( $StopYear, $StopMonth, $StopDay, $StopHour, $StopMinute, 0 );
                $article->setStopDate( $stopDate );
            }

            eZObjectPermission::removePermissions( $article->id(), "article_article", 'w' );
            if ( isset( $WriteGroupArray ) )
            {
                if ( $WriteGroupArray[0] == 0 )
                {
                    eZObjectPermission::setPermission( -1, $article->id(), "article_article", 'w' );
                }
                else
                {
                    foreach ( $WriteGroupArray as $groupID )
                    {
                        eZObjectPermission::setPermission( $groupID, $article->id(), "article_article", 'w' );
                    }
                }
            }
            else
            {
                eZObjectPermission::removePermissions( $article->id(), "article_article", 'w' );
            }

            /* read access thingy */
            eZObjectPermission::removePermissions( $article->id(), "article_article", 'r' );
            if ( isset( $GroupArray ) )
            {
                if ( $GroupArray[0] == 0 )
                {
                    eZObjectPermission::setPermission( -1, $article->id(), "article_article", 'r' );
                }
                else // some groups are selected.
                {
                    foreach ( $GroupArray as $groupID )
                    {
                        eZObjectPermission::setPermission( $groupID, $article->id(), "article_article", 'r' );
                    }
                }
            }
            else
            {
                eZObjectPermission::removePermissions( $article->id(), "article_article", 'r' );
            }

                        // add check for publishing rights here
            if ( $IsPublished == "on" )
            {
                // check if the article is published now
                if ( $article->isPublished() == false )
                {
                    $sendNotification = true;
                }

                $article->setIsPublished( true );
            }
            else
            {
                $article->setIsPublished( false );
            }


            // generate keywords
            $contents = strip_tags( $contents );
            $contents = preg_replace( "#\n#", "", $contents );
            $contents_array =& split( " ", $contents );
            $contents_array = array_unique( $contents_array );

            $keywords = "";
            foreach ( $contents_array as $word )
            {
                $keywords .= $word . " ";
            }

            $article->setKeywords( $keywords );

            $article->store();
            $ArticleID = $article->id();

            if ( $sendNotification )
                eZArticleTool::notificationMessage( $article );

            // Go to insert item..
            if ( isset( $AddItem ) )
            {
                switch ( $ItemToAdd )
                {
                    case "Image":
                    {
                        // add images
                        eZHTTPTool::header( "Location: /article/articleedit/imagelist/$ArticleID/" );
                        exit();
                    }
                    break;

                    case "Media":
                    {
                        // add media
                        eZHTTPTool::header( "Location: /article/articleedit/medialist/$ArticleID/" );
                        exit();
                    }
                    break;

                    case "File":
                    {
                        // add files
                        eZHTTPTool::header( "Location: /article/articleedit/filelist/$ArticleID/" );
                        exit();
                    }
                    break;

                    case "Attribute":
                    {
                        // add attributes
                        eZHTTPTool::header( "Location: /article/articleedit/attributelist/$ArticleID/" );
                        exit();
                    }
                    break;

                    case "Form":
                    {
                        // add form
                        eZHTTPTool::header( "Location: /article/articleedit/formlist/$ArticleID/" );
                        exit();
                    }
                    break;
                }
            }

            // preview
            if ( isset( $Preview ) )
            {
                eZHTTPTool::header( "Location: /article/articlepreview/$ArticleID/" );
                exit();
            }

            // log history
            if ( isset( $Log ) )
            {
                eZHTTPTool::header( "Location: /article/articlelog/$ArticleID/" );
                exit();
            }

            // get the category to redirect to
            $category = $article->categoryDefinition( );
            $categoryID = $category->id();

            if ( $article->isPublished() )
            {
                eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
            }
            else
            {
                eZHTTPTool::header( "Location: /article/unpublished/$categoryID/" );
            }
            exit();
        }
        else
        {
            $invalidContents = $contents;

            if ( $Action == "Insert" )
                $Action = "New";
            else
                $Action = "Edit";

            $ErrorParsing = true;
        }
    }
}

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "articleedit.php" );

$t->setAllStrings();

$t->set_file( "article_edit_page_tpl",  "articleedit.tpl"  );

$t->set_block( "article_edit_page_tpl", "topic_item_tpl", "topic_item" );
$t->set_block( "article_edit_page_tpl", "value_tpl", "value" );
$t->set_block( "article_edit_page_tpl", "multiple_value_tpl", "multiple_value" );
$t->set_block( "article_edit_page_tpl", "category_owner_tpl", "category_owner" );
$t->set_block( "article_edit_page_tpl", "group_item_tpl", "group_item" );
$t->set_block( "article_edit_page_tpl", "publish_dates_tpl", "publish_dates" );
$t->set_block( "article_edit_page_tpl", "article_pending_tpl", "article_pending" );
$t->set_block( "article_edit_page_tpl", "author_pending_information_tpl", "author_pending_information" );
$t->set_block( "article_edit_page_tpl", "author_item_tpl", "author_item" );

$t->set_block( "publish_dates_tpl", "published_tpl", "published" );
$t->set_block( "publish_dates_tpl", "un_published_tpl", "un_published" );

$t->set_block( "article_edit_page_tpl", "error_message_tpl", "error_message" );
$t->set_block( "article_edit_page_tpl", "urltranslator_tpl", "urltranslator" );

$Locale = new eZLocale( $Language );
if ( isset($_REQUEST['ErrorParsing']) )
{
    $t->set_var( "article_invalid_contents", $invalidContents );
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}

$t->set_var( "article_is_published", "" );

foreach($_REQUEST as $key => $value)
{
	$$key = $value;
}
$t->set_var( "article_id", "" );
$t->set_var( "article_name", stripslashes( $Name ) );

//EP: URL translation : new article -------------------------------------------

if ( $ini->read_var( "eZArticleMain", "AdminURLTranslator" ) == "enabled" )
{
    $t->set_var( "article_url", "" );
    $t->set_var( "article_urltranslator", "" );
    $t->parse( "urltranslator", "urltranslator_tpl" );
}
else
{
    $t->set_var( "article_url", "" );
    $t->set_var( "intl-article_nourl", "" );
    $t->set_var( "article_urltranslator", "" );
    $t->set_var( "urltranslator", "" );
}
//EP --------------------------------------------------------------------------

$t->set_var( "article_keywords", stripslashes( $Keywords ) );
$t->set_var( "article_contents_0", stripslashes( $Contents[0] ) );
$t->set_var( "article_contents_1", stripslashes( $Contents[1] ) );
$t->set_var( "article_contents_2", stripslashes( $Contents[2] ) );
$t->set_var( "article_contents_3", stripslashes( $Contents[3] ) );
$t->set_var( "author_text", stripslashes( $AuthorText ) );
$t->set_var( "author_email", stripslashes( $AuthorEmail ) );
$t->set_var( "link_text", stripslashes( $LinkText ) );

$t->set_var( "start_day", stripslashes( $StartDay ) );
$t->set_var( "start_month", stripslashes( $StartMonth ) );
$t->set_var( "start_year", stripslashes( $StartYear ) );
$t->set_var( "start_hour", stripslashes( $StartHour ) );
$t->set_var( "start_minute", stripslashes( $StartMinute ) );
$t->set_var( "stop_day", stripslashes( $StopDay ) );
$t->set_var( "stop_month", stripslashes( $StopMonth ) );
$t->set_var( "stop_year", stripslashes( $StopYear ) );
$t->set_var( "stop_hour", stripslashes( $StopHour ) );
$t->set_var( "stop_minute", stripslashes( $StopMinute ) );

$t->set_var( "action_value", "insert" );
$t->set_var( "all_selected", "selected" );
$t->set_var( "all_write_selected", "selected" );
$writeGroupsID = array();
$readGroupsID = array();

if ( $Action == "New" )
{
    $user =& eZUser::currentUser();
    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName() );
    $article = new eZArticle();
}

$t->set_var( "author_pending_information", "" );
$t->set_var( "publish_dates", "" );
$t->set_var( "article_pending", "" );
if ( $Action == "Edit" )
{
    $article = new eZArticle();

    if ( !$article->get( $ArticleID ) )
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }

    $definition =& $article->categoryDefinition();


    $t->set_var( "article_id", $ArticleID );

    $pending = false;
    if ( $article->isPublished() )
    {
        if ( $article->isPublished() == 2 )
        {
            $pending = true;
            $t->parse( "article_pending", "article_pending_tpl" );
        }
        else
            $t->set_var( "article_is_published", "checked" );
    }
    else
    {
        $t->set_var( "article_is_published", "" );
    }

    if ( $article->discuss() )
    {
        $t->set_var( "discuss_article", "checked" );
    }
    else
    {
        $t->set_var( "discuss_article", "" );
    }

    $startDate =& $article->startDate();
    $stopDate =& $article->stopDate();

    if ( $article->startDate( false ) != 0 )
    {
        $t->set_var( "start_day", "" );
        $t->set_var( "start_month", "" );
        $t->set_var( "start_year", "" );
        $t->set_var( "start_hour", "" );
        $t->set_var( "start_minute", "" );
        if ( is_a( $startDate, "eZDateTime" ) )
        {
            $t->set_var( "start_day", $startDate->addZero( $startDate->day() ) );
            $t->set_var( "start_month", $startDate->addZero( $startDate->month() ) );
            $t->set_var( "start_year", $startDate->addZero( $startDate->year() ) );
            $t->set_var( "start_hour", $startDate->addZero( $startDate->hour() ) );
            $t->set_var( "start_minute", $startDate->addZero( $startDate->minute() ) );
        }
    }

    if ( $article->stopDate( false ) != 0 )
    {
        $t->set_var( "stop_day", "" );
        $t->set_var( "stop_month", "" );
        $t->set_var( "stop_year", "" );
        $t->set_var( "stop_hour", "" );
        $t->set_var( "stop_minute", "" );

        if ( is_a( $stopDate, "eZDateTime" ) )
        {
            $t->set_var( "stop_day", $startDate->addZero( $stopDate->day() ) );
            $t->set_var( "stop_month", $startDate->addZero( $stopDate->month() ) );
            $t->set_var( "stop_year", $startDate->addZero( $stopDate->year() ) );
            $t->set_var( "stop_hour", $startDate->addZero( $stopDate->hour() ) );
            $t->set_var( "stop_minute", $startDate->addZero( $stopDate->minute() ) );
        }
    }

    if ( !isset( $_REQUEST['Name'] ) )
        $t->set_var( "article_name", $article->name() );

    $generator = new eZArticleGenerator();

    $contentsArray = $generator->decodeXML( $article->contents() );

    $i = 0;
    foreach ( $contentsArray as $content )
    {
        if ( !isset( $Contents[$i] ) )
        {
            $t->set_var( "article_contents_$i", htmlspecialchars( $content ) );
        }
        $i++;
    }

    //EP: URL translation: article edit get translation

    if ( $ini->read_var( "eZArticleMain", "AdminURLTranslator" ) == "enabled" )
    {

        $category = $article->categoryDefinition();
        $url1 = "/article/articleview/" . $article->id() . "/1/" . $category->id();
        $t->set_var( "article_url", $url1 );
	$t->set_var( "intl-article_nourl", "" );

        include_once( "ezurltranslator/classes/ezurltranslator.php" );
        $urltranslator = new eZURLTranslator();
        $urltranslator->getbydest ( $url1 );
        $t->set_var( "article_urltranslator", $urltranslator->source() );

        $t->parse( "urltranslator", "urltranslator_tpl" );
    }

    $t->set_var( "article_keywords", $article->manualKeywords() );

    $t->set_var( "author_text", $article->authorText() );
    $t->set_var( "author_email", $article->authorEmail() );

    if ( $pending )
    {
        $t->parse( "author_pending_information", "author_pending_information_tpl" );
    }
    else
    {
        $t->set_var( "author_pending_information", "" );
    }

    $t->set_var( "link_text", $article->linkText() );

    $t->set_var( "action_value", "update" );

    $author = $article->contentsWriter();
    $ContentsWriterID = $author->id();

    $topic = $article->topic();
    $TopicID = $topic->id();

    $writeGroupsID = eZObjectPermission::getGroups( $ArticleID, "article_article", 'w' , false );
    $readGroupsID = eZObjectPermission::getGroups( $ArticleID, "article_article", 'r', false );

    if ( count($writeGroupsID) && $writeGroupsID[0] != -1 )
        $t->set_var( "all_write_selected", "" );
    if ( count($readGroupsID) && $readGroupsID[0] != -1 )
        $t->set_var( "all_selected", "" );

    // dates
    $published =& $article->published();
    $created =& $article->created();
    $modified =& $article->modified();
    $t->set_var( "published_date", $Locale->format( $published ) );
    $t->set_var( "created_date", $Locale->format( $created ) );
    $t->set_var( "modified_date", $Locale->format( $modified ) );

    if ( $article->isPublished() == true )
    {
        $t->parse( "published", "published_tpl" );
        $t->set_var( "un_published", "" );
    }
    else
    {
        $t->parse( "un_published", "un_published_tpl" );
        $t->set_var( "published", "" );
    }

    $t->parse( "publish_dates", "publish_dates_tpl" );
}


// author select

$author = new eZAuthor();
$authorArray = $author->getAll();
foreach ( $authorArray as $author )
{
    if ( $ContentsWriterID == $author->id() )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }
    $t->set_var( "author_id", $author->id() );
    $t->set_var( "author_name", $author->name() );
    $t->parse( "author_item", "author_item_tpl", true );
}

// topic select

$topic = new eZTopic();
$topicArray = $topic->getAll();
foreach ( $topicArray as $topic )
{
    if ( $TopicID == $topic->id() )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }
    $t->set_var( "topic_id", $topic->id() );
    $t->set_var( "topic_name", $topic->name() );
    $t->parse( "topic_item", "topic_item_tpl", true );
}

// category select
$category = new eZArticleCategory();
$categoryArray = $category->getAll( );


$tree = new eZArticleCategory();
$treeArray =& $tree->getTree();
$user =& eZUser::currentUser();

$catCount = count( $treeArray );
$t->set_var( "num_select_categories", min( $catCount, 10 ) );

foreach ( $treeArray as $catItem )
{
    if ( eZObjectPermission::hasPermission( $catItem[0]->id(), "article_category", 'w', $user ) == true ||
         eZArticleCategory::isOwner( eZUser::currentUser(), $catItem[0]->id() ) )
    {
        if ( $Action == "Edit" )
        {
            $defCat = $article->categoryDefinition( );

            if ( is_a( $defCat, "eZArticleCategory" ) )
            {
                if ( $article->existsInCategory( $catItem[0] ) && $defCat->id() != $catItem[0]->id() )
                {
                    $t->set_var( "multiple_selected", "selected" );
                }
                else
                {
                    $t->set_var( "multiple_selected", "" );
                }
            }
            else
            {
                $t->set_var( "selected", "" );
            }

            if ( is_a( $defCat, "eZArticleCategory" ) )
            {
                if ( $defCat->id() == $catItem[0]->id() )
                {
                    $t->set_var( "selected", "selected" );
                }
                else
                {
                    $t->set_var( "selected", "" );
                }
            }
            else
            {
                $t->set_var( "selected", "" );
            }
        }
        else
        {
            $t->set_var( "selected", "" );
            $t->set_var( "multiple_selected", "" );
        }

        $t->set_var( "option_value", $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );

        if ( $catItem[1] > 1 )
            $t->set_var( "option_level", str_repeat( "&nbsp;&nbsp;", $catItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->parse( "value", "value_tpl", true );
        $t->parse( "multiple_value", "multiple_value_tpl", true );
    }
}

// group selector
$group = new eZUserGroup();
$groupList = $group->getAll();

$t->set_var( "selected", "" );
foreach ( $groupList as $groupItem )
{
    //for the group owner selector */
    $t->set_var( "module_owner_id", $groupItem->id() );
    $t->set_var( "module_owner_name", $groupItem->name() );

    if ( in_array( $groupItem->id(), $writeGroupsID ) )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );

    $t->parse( "category_owner", "category_owner_tpl", true );

    // for the read access groups selector */
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_id", $groupItem->id() );
    if ( in_array( $groupItem->id(), $readGroupsID ) )
        $t->set_var( "selected", "selected" );
    else
        $t->set_var( "selected", "" );
    $t->parse( "group_item", "group_item_tpl", true );
}

$t->pparse( "output", "article_edit_page_tpl" );

?>
