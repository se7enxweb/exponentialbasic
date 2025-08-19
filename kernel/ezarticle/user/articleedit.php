<?php
// 
// $Id: articleedit.php 9457 2002-04-23 15:32:42Z bf $
//
// Created on: <18-Oct-2000 15:04:39 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcachefile.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezarticle/classes/ezarticletool.php" );
// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlegenerator.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezuser/classes/ezauthor.php" );
// include_once( "ezxml/classes/ezxml.php" );

// include_once( "ezbulkmail/classes/ezbulkmail.php" );
// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

$ini =& eZINI::instance( 'site.ini' );

$PublishNoticeReceiver = $ini->variable( "eZArticleMain", "PublishNoticeReceiver" );
$PublishNoticeSender = $ini->variable( "eZArticleMain", "PublishNoticeSender" );

$session =& eZSession::globalSession();

// insert a new article in the database
if ( ( $Action == "Insert" ) || ( $Action == "Update" ) )
{
    $user =& eZUser::currentUser();
        
    $article = new eZArticle( $ArticleID );
    $article->setName( $Name );
    
    $article->setAuthor( $user );

    $generator = new eZArticleGenerator();

    $contents = $generator->generateXML( $Contents );
    $article->setContents( $contents );

    $article->setPageCount( $generator->pageCount() );


    // check if author exists in the database, else create
    $author = new eZAuthor();
    if ( !$author->getByName( trim( $AuthorText ) ) )
    {
        $author = new eZAuthor( );
        $author->setName( $AuthorText );
        $author->store();
        
        $article->setContentsWriter( $author );
    }
    else
    {
        $article->setContentsWriter( $author );
    }
    
    $article->setLinkText( $LinkText );
    $article->setStartDate( (new eZDateTime())->timeStamp(true) );
    $article->setStopDate( (new eZDateTime())->timeStamp(true) );
    $article->store(); // to get ID

    // remove from category if update
    if ( $Action == "Update" )
        $article->removeFromCategories();
    
    // add to categories    
    $category = new eZArticleCategory( $CategoryIDSelect );
    $category->addArticle( $article );

    $article->setCategoryDefinition( $category );
    
// Which group should a user-published article be set to?
    eZObjectPermission::setPermission( -1, $article->id(), "article_article", 'w' );
    eZObjectPermission::setPermission( -1, $article->id(), "article_article", 'r' );

    // user-submitted articles are never directly published


    // check if the contents is parseable
    if ( eZXML::domTree( $contents ) )
    {
        // generate keywords
        $contents = strip_tags( $contents );
        $contents = preg_replace( "#\n#", "", $contents );
        $contents_array =& preg_split( "/ /", $contents );
        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            
            $keywords .= $word . " ";
        }

        $article->setKeywords( $keywords );
        $article->store();
    
        // Go to insert item..
        if ( isset( $AddItem ) )
        {
            switch( $ItemToAdd )
            {
                case "Image":
                {
                    $session->setVariable( "ArticleEditID", $article->id() );
                    $articleID = $article->id();
                    // add images
                    eZHTTPTool::header( "Location: /article/articleedit/imagelist/$articleID/" );
                    exit();
                }
                break;
                case "File":
                {
                    $session->setVariable( "ArticleEditID", $article->id() );
                    $articleID = $article->id();
                    // add files
                    eZHTTPTool::header( "Location: /article/articleedit/filelist/$articleID/" );
                    exit();
                }
                break;
            }
        }

        if ( $ini->variable( "eZArticleMain", "CanUserPublish" ) == "enabled" )
        {
            $article->setIsPublished( true );

            eZArticleTool::deleteCache( $articleID, $CategoryIDSelect, array( $CategoryIDSelect ) );
            eZArticleTool::notificationMessage( $article );
        }
        else
        {
            $article->setIsPublished( false );
        }

        $article->store();
        
        $session->setVariable( "ArticleEditID", "" );
        eZHTTPTool::header( "Location: /article/archive/$CategoryIDSelect/" );
        exit();
    }
    else
    {
        $Action = "New";
        $ErrorParsing = true;
    }
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


$Language = $ini->variable( "eZArticleMain", "Language" );

// init the section
if ( isset ($SectionIDOverride) )
{
    // include_once( "ezsitemanager/classes/ezsection.php" );
    
    $sectionObject =& eZSection::globalSectionObject( $SectionIDOverride );
    $sectionObject->setOverrideVariables();
}

$t = new eZTemplate( "kernel/ezarticle/user/" . $ini->variable( "eZArticleMain", "TemplateDir" ),
                     "kernel/ezarticle/user/intl/", $Language, "articleedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_edit_page_tpl" => "articleedit.tpl"
    ) );

$t->set_block( "article_edit_page_tpl", "value_tpl", "value" );
$t->set_block( "article_edit_page_tpl", "error_message_tpl", "error_message" );


if ( isset( $ErrorParsing ) && $ErrorParsing == true )
{
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}

if ( $Action == "New" )
{
    $user =& eZUser::currentUser();
    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName());
    $catDefID = false;
    $Name = false;
    $ContentsSet = array();
    $ContentsSet[0] = false;
    $ContentsSet[1] = false;
    $AuthorText = false;
    $LinkText = false;
}

if ( $Action == "Edit" )
{
    $AuthorText = false;
    $LinkText = false;
    $NameSet = false;
    $ContentsSet = array();
    $ContentsSet[0] = false;
    $ContentsSet[1] = false;
}

$t->set_var( "article_id", "" );
$t->set_var( "article_name", stripslashes( $Name ) );
$t->set_var( "article_contents_0", stripslashes( $ContentsSet[0] ) );
$t->set_var( "article_contents_1", stripslashes( $ContentsSet[1] ) );
$t->set_var( "author_text", stripslashes( $AuthorText ) );
$t->set_var( "link_text", stripslashes( $LinkText  ) );

$t->set_var( "action_value", "insert" );

if ( $Action == "New" )
{
    $user =& eZUser::currentUser();
    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName());    
}

//pBo modified this: assign the current articleID as suggested by Arne Schirmacher 
//$articleID = $session->variable( "ArticleEditID" );
$articleID = $ArticleID;
//end PBo mod

if ( $Action == "Edit" )
{
    $article = new eZArticle( $articleID );

    $generator = new eZArticleGenerator();
    
    $contentsArray = $generator->decodeXML( $article->contents() );

    $catDef =& $article->categoryDefinition();
    $catDefID = $catDef->id();

    $user =& eZUser::currentUser();

    //PBo modification
    //Check if the id of the author matches the current logged in user
    //This does refer to the userid who submitted the article, not the possibel "assigned author" 
    //as can be done through the admin interface
    //If there is no match, the scripts dies which is still al little harch
    
    $editOwnArticle=$ini->variable( "eZArticleMain", "UserEditOwnArticle" );
    if (!eZArticle::isAuthor($user, $article->id()) || $editOwnArticle != "enabled" )
    {
    	echo "You " . $user->id() . " are not the author" . $article->author(false) . " or user side editing is disabled , bye!<br />";
	die("THE END");
	
    }
    //End PBo mod

    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName());    

    $t->set_var( "article_name", $article->name() );

    $i=0;
    foreach ( $contentsArray as $content )
    {
        if ( !isset( $Contents[$i] ) )
        {
            $t->set_var( "article_contents_$i", htmlspecialchars( $content ) );
        }
        $i++;
    }
    $t->set_var( "article_keywords", $article->manualKeywords() );

    $t->set_var( "link_text", $article->linkText() );

    $t->set_var( "action_value", "update" );
    $t->set_var( "article_id", $articleID );
}


// category select
$tree = new eZArticleCategory();
$treeArray = $tree->getTree();

foreach ( $treeArray as $catItem )
{
    if( eZObjectPermission::hasPermission( $catItem[0]->id(), "article_category", 'w', eZUser::currentUser() ) == true )
    {
        $t->set_var( "selected", "" );

        if ( $catDefID == $catItem[0]->id() )
        {
            $t->set_var( "selected", "selected" );
        }

        $t->set_var( "option_value", $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );

        if ( $catItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->parse( "value", "value_tpl", true );
    }
}

if ( isset ($SectionIDOverride) ) $t->set_var( "section_id", $SectionIDOverride );

$t->pparse( "output", "article_edit_page_tpl" );

?>