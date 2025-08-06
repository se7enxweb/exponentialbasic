<?php
//
// $Id: ezarticle.php 9874 2003-07-22 11:14:49Z br $
//
// Definition of eZArticle class
//
// Created on: <18-Oct-2000 13:50:24 bf>
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
//! eZArticle handles articles.
/*!

  Example code:
  \code
  $category = new eZArticleCategory();
  $category->setName( "Programming" );
  $category->setDescription( "Lots of programming articles" );

  $category->store();

  $article = new eZArticle( );
  $article->setName( "C++" );
  $article->setContents( "An article about the fine art of C++ .... .... ... ... .... ... " );
  $article->setAuthorText( "B�rd Farstad" );
  $article->setLinkText( "Read the article" );

  $article->store();

  $category->addArticle( $article );
  \endcode

  \sa eZArticleCategory

*/

/*!TODO
  Add delayed fetching of the article contents.

*/

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/eztexttool.php" );
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezauthor.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezfilemanager/classes/ezvirtualfile.php" );

// include_once( "ezmediacatalogue/classes/ezmedia.php" );

// include_once( "ezforum/classes/ezforum.php" );
// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticleattribute.php" );
// include_once( "ezarticle/classes/ezarticletype.php" );

class eZArticle
{
    /*!
      Constructs a new eZArticle object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        // default value
        $this->IsPublished = "0";
        $this->StartDate = 0;
        $this->StopDate = 0;
        //$this->Discuss = 0;
        $this->TopicID = 0;

        $this->PublishedOverride = 0;

        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a product to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $contents = $db->escapeString( $this->Contents );
        $authortext = $db->escapeString( $this->AuthorText );
        $authoremail = $db->escapeString( $this->AuthorEmail );
        $linktext = $db->escapeString( $this->LinkText );
        $keywords = $db->escapeString( $this->Keywords );
        $importID = $db->escapeString( $this->ImportID );

        if ( is_object( $this->StartDate ) and $this->StartDate->isValid() )
            $startDate = $this->StartDate->timeStamp();
        else
            $startDate = $this->StartDate;

        if ( is_object( $this->StopDate ) and $this->StopDate->isValid() )
            $stopDate = $this->StopDate->timeStamp();
        else
            $stopDate = $this->StopDate;

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZArticle_Article" );

            $nextID = $db->nextID( "eZArticle_Article", "ID" );

            $timeStamp =& (new eZDateTime())->timeStamp( true );

            if ( $this->PublishedOverride != 0 )
                $published = $this->PublishedOverride;
            else
                $published = $timeStamp;


            // fix for informix blob field
            $contentsStr = "'$contents'";

            if ( $db->isA() == "informix" )
            {
                $textid = ifx_create_blob( 0, 0, $this->Contents );

//                $textid = ifx_create_char( $contents );
                $blobIDArray[] = $textid;
                $contentsStr = "?";
                $db->setBlobArray( $blobIDArray );

            }

            $ret = $db->query( "INSERT INTO eZArticle_Article
		                        ( ID,
                                 Name,
                                 Contents,
                                 AuthorID,
                                 LinkText,
                                 PageCount,
                                 IsPublished,
                                 Keywords,
                                 Discuss,
                                 ContentsWriterID,
                                 TopicID,
                                 StartDate,
                                 StopDate,
                                 Modified,
                                 Published,
                                 Created,
                                 ImportID )
                                 VALUES
                                 ( '$nextID',
		                           '$name',
                                    $contentsStr,
                                   '$this->AuthorID',
                                   '$linktext',
                                   '$this->PageCount',
                                   '$this->IsPublished',
                                   '$keywords',
                                   '$this->Discuss',
                                   '$this->ContentsWriterID',
                                   '$this->TopicID',
                                   '$startDate',
                                   '$stopDate',
                                   '$timeStamp',
                                   '$published',
                                   '$timeStamp',
                                   '$importID' )
                                 " );

			$this->ID = $nextID;
        }
        else
        {

            // fix for informix blob field
            $contentsStr = "Contents='$contents',";

            if ( $db->isA() == "informix" )
            {
                ifx_textasvarchar(0);
                $db->array_query( $res, "SELECT ID, Contents FROM eZArticle_Article WHERE ID='$this->ID'" );


                $bid = $res[0][$db->fieldName("Contents")];
                // fetch the blob id
                $res = ifx_update_blob( $bid, $this->Contents );

                if ( !$res  )
                {
                    print( "Error updating informix text blob" );
                    die();
                }

                $blobIDArray[] = $bid;
                $db->setBlobArray( $blobIDArray );

                $contentsStr = "Contents=?,";
                ifx_textasvarchar(1);
            }


            $db->array_query( $res, "SELECT ID FROM eZArticle_Article WHERE IsPublished='0' AND ID='$this->ID'" );

            $timeStamp =& (new eZDateTime())->timeStamp( true );

            if ( $this->PublishedOverride != 0 )
                $published = $this->PublishedOverride;
            else
                $published = $timeStamp;

            if ( ( count( $res ) > 0 ) && ( $this->IsPublished == "1" ) )
            {
                $queryText = "UPDATE eZArticle_Article SET
		                         Name='$name',
                                 $contentsStr
                                 LinkText='$linktext',
                                 PageCount='$this->PageCount',
                                 AuthorID='$this->AuthorID',
                                 IsPublished='$this->IsPublished',
                                 Keywords='$keywords',
                                 Discuss='$this->Discuss',
                                 ContentsWriterID='$this->ContentsWriterID',
                                 TopicID='$this->TopicID',
                                 StartDate='$startDate',
                                 StopDate='$stopDate',
                                 Published='$published',
                                 Modified='$timeStamp',
                                 ImportID='$importID'
                                 WHERE ID='$this->ID'
                                 ";

                $ret = $db->query( $queryText );
            }
            else
            {
                if ( $this->PublishedOverride != 0 )
                {
                    $published = $this->PublishedOverride;
                }
                else
                {
                    if( $this->Published != '' )
                        $published = $this->Published;
                    else
                        $published = $timeStamp;
                }
                $queryText = "UPDATE eZArticle_Article SET
		                         Name='$name',
                                 $contentsStr
                                 LinkText='$linktext',
                                 PageCount='$this->PageCount',
                                 AuthorID='$this->AuthorID',
                                 IsPublished='$this->IsPublished',
                                 Keywords='$keywords',
                                 Discuss='$this->Discuss',
                                 ContentsWriterID='$this->ContentsWriterID',
                                 TopicID='$this->TopicID',
                                 Published='$published',
                                 StartDate='$startDate',
                                 StopDate='$stopDate',
                                 Modified='$timeStamp',
                                 ImportID='$importID'
                                 WHERE ID='$this->ID'
                                 ";

                $ret = $db->query( $queryText );
            }
        }

        $db->unlock();

        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();

        // index this article
        $this->createIndex();

        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $article_array, "SELECT * FROM eZArticle_Article WHERE ID='$id'" );
            if ( count( $article_array ) > 1 )
            {
                die( "Error: Article's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $article_array ) == 1 )
            {
                $this->fill( $article_array[0] );
                $ret = true;
            }
        }
        return $ret;
    }

    function fill( $article_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $article_array[$db->fieldName("ID")];
        $this->Name =& $article_array[$db->fieldName("Name")];
        $this->Contents =& $article_array[$db->fieldName("Contents")];
        $this->AuthorText =& $article_array[$db->fieldName("AuthorText")];
        $this->AuthorEmail =& $article_array[$db->fieldName("AuthorEmail")];
        $this->AuthorID =& $article_array[$db->fieldName("AuthorID")];
        $this->LinkText =& $article_array[$db->fieldName("LinkText")];
        $this->Modified =& $article_array[$db->fieldName("Modified")];
        $this->Created =& $article_array[$db->fieldName("Created")];
        $this->Published =& $article_array[$db->fieldName("Published")];
        $this->PageCount =& $article_array[$db->fieldName("PageCount")];
        $this->IsPublished =& $article_array[$db->fieldName("IsPublished")];
        $this->Keywords =& $article_array[$db->fieldName("Keywords")];
        $this->Discuss =& $article_array[$db->fieldName("Discuss")];
        $this->ContentsWriterID =& $article_array[$db->fieldName("ContentsWriterID")];
        $this->TopicID =& $article_array[$db->fieldName("TopicID")];
        $this->StartDate =& $article_array[$db->fieldName("StartDate")];
        $this->StopDate =& $article_array[$db->fieldName("StopDate")];
        $this->ImportID =& $article_array[$db->fieldName("ImportID")];
    }

    /*!
        \static
        Returns the one, and only if one exists, article with the name

        Returns an object of eZArticle.
     */
    static public function &getByName( $name )
    {
        $db =& eZDB::globalDatabase();

        $topic = new eZArticle();

        $name = $db->escapeString( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZArticle_Article WHERE Name='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic = new eZArticle( $author_array[0] );
            }
        }

        return $topic;
    }
    /*!
        \static
        Returns the one, and only if one exists, article with the import id

        Returns an object of eZArticle.
     */
    static public function &getByImportID( $name )
    {
        $db =& eZDB::globalDatabase();

        $topic = new eZArticle();

        $name = $db->escapeString( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZArticle_Article WHERE ImportID='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic = new eZArticle( $author_array[0] );
            }
        }

        return $topic;
    }


    /*!
      Deletes a eZArticle object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $imageList =& $this->images();
            $fileList =& $this->files();
            foreach( $imageList as $image )
            {
//                print_r( $image );

//                $image->delete();
            }
            foreach( $fileList as $file )
            {
//                $file->delete();
            }
            $db->begin();

            $forum = $this->forum();
            $forum->delete();
            $res = array();

            $res[] = $db->query( "DELETE FROM eZArticle_ArticleCategoryLink WHERE ArticleID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZArticle_ArticleCategoryDefinition WHERE ArticleID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZArticle_ArticleImageDefinition WHERE ArticleID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZArticle_ArticlePermission WHERE ObjectID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZArticle_Article WHERE ID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZArticle_AttributeValue WHERE ArticleID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZArticle_ArticleForumLink WHERE ArticleID='$this->ID'" );

            if ( in_array( false, $res ) )
                $db->rollback( );
            else
                $db->commit();

        }

        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the article name / title.
    */
    function name( $asHTML = true )
    {
        if( $this->Name != '' && $asHTML == true )
        {
			$name = stripslashes($this->Name);
            return eZTextTool::fixhtmlentities( htmlspecialchars( $name ) );
        }
        return $this->Name;
    }

    /*!
      Returns the article import id.
    */
    function &importID( )
    {
        return $this->ImportID;
    }

    /*!
      Returns the article contents.

      The contents is internally stored as XML.
    */
    function &contents()
    {
        $db =& eZDB::globalDatabase();

        return $this->Contents;
    }

    /*!
      Returns the author text contents.
    */
    function &authorText( $asHTML = true )
    {
        $author = new eZAuthor( $this->ContentsWriterID );

        if( $asHTML == true )
            return eZTextTool::fixhtmlentities( htmlspecialchars( $author->name() ) );
        return $author->name();
    }

    /*!
      Returns the author email contents.
    */
    function &authorEmail( $asHTML = true )
    {
        $author = new eZAuthor( $this->ContentsWriterID );

        if( $asHTML == true )
            return htmlspecialchars( $author->email() );
        return $author->email();
    }


    /*!
      Returns the link text.
    */
    function &linkText( $asHTML = true )
    {
        if(  $asHTML )
            return eZTextTool::fixhtmlentities( htmlspecialchars( $this->LinkText ) );
        return $this->LinkText;
    }

    /*!
      Returns the author as a eZUser object.
    */
    function &author( $as_object = true )
    {
        if( $as_object )
            $author = new eZUser( $this->AuthorID );
        else
            $author = $this->AuthorID;
        return $author;
    }

    /*!
      Returns the number of pages in the article.
    */
    function &pageCount()
    {
        return substr_count( $this->Contents, "<page>" );
    }

    /*!
      Returns the creation time of the article.

      The time is returned as a eZDateTime object.
    */
    function &created()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Created );

        return $dateTime;
    }

    /*!
      Returns the modification time of the article.

      The time is returned as a eZDateTime object.
    */
    function &modified()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Modified );

        return $dateTime;
    }

    /*!
      Returns the keywords of an article.
    */
    function keywords( )
    {
        $this->Keywords = stripslashes($this->Keywords);
        return $this->Keywords;
    }

    /*!
      Returns the discuss value of an article.
    */
    function discuss( )
    {
        $ret = false;
        if ( $this->Discuss == 1 )
        {
            $ret = true;
        }
        return $ret;
    }


    /*!
      Returns the last time the article was published.

      The time is returned as a eZDateTime object.
    */
    function &published()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Published );

        return $dateTime;
    }

    /*!
      Returns true if the article is published false if not.
    */
    function isPublished()
    {
        $ret = false;
        if ( $this->IsPublished == "1" )
        {
            $ret = 1;
        }
        elseif ( $this->IsPublished == "2" )
        {
            $ret = 2;
        }
        return $ret;
    }

    /*!
      Returns the start date of the article.
    */
    function &startDate( $as_object=true )
    {
        if ( $as_object )
        {
            $ret = new eZDateTime();
            $ret->setTimeStamp( $this->StartDate );
            return $ret;
        }
        else
            return $this->StartDate;
    }

    /*!
      Returns the stop date of the article.
    */
    function &stopDate( $as_object=true )
    {
        if ( $as_object )
        {
            $ret = new eZDateTime();
            $ret->setTimeStamp( $this->StopDate );
            return $ret;
        }
        else
            return $this->StopDate;
    }

    /*!
      Sets the article name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the article import id.
    */
    function setImportID( $value )
    {
        $this->ImportID = $value;
    }

    /*!
      Sets the contents name.
    */
    function setContents( $value )
    {
        $this->Contents = $value;
    }

    /*!
      Sets the author text.
    */
    function setAuthorText( $value )
    {
        $this->AuthorText = $value;
    }

    /*!
      Sets the author email.
    */
    function setAuthorEmail( $value )
    {
        $this->AuthorEmail = $value;
    }

    /*!
      Sets the link text.
    */
    function setLinkText( $value )
    {
        $this->LinkText = $value;
    }

    /*!
      Sets the author of the article.
    */
    function setAuthor( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $this->AuthorID = $user->id();
        }
    }

    /*!
      Sets the number of pages in the article.
    */
    function setPageCount( $value )
    {
        $this->PageCount = $value;
    }

    /*!
      Sets the keywords to an article. Theese words are used in the search.
    */
    function setKeywords( $keywords )
    {
        // replace newlines with space
        $keywords = str_replace ("\n\r", " ", $keywords );
        $keywords = str_replace ("\r\n", " ", $keywords );
        $keywords = str_replace ("\n", " ", $keywords );
        $keywords = str_replace ("\r", " ", $keywords );
        $this->Keywords = $keywords;
    }

    /*!
      \private
      will index the article keywords (fetched from Contents) and name for fulltext search.
    */
    function createIndex()
    {
        // generate keywords
        $tmpContents = $this->Contents;

        $tmpContents = str_replace ("</intro>", " ", $tmpContents );
        $tmpContents = str_replace ("</page>", " ", $tmpContents );

        $contents = strtolower( strip_tags( $tmpContents ) ) . " " . $this->Name;
        $contents = str_replace ("\n", "", $contents );
        $contents = str_replace ("\r", "", $contents );
        $contents = str_replace ("(", " ", $contents );
        $contents = str_replace (")", " ", $contents );
        $contents = str_replace (",", " ", $contents );
        $contents = str_replace (".", " ", $contents );
        $contents = str_replace ("/", " ", $contents );
        $contents = str_replace ("-", " ", $contents );
        $contents = str_replace ("_", " ", $contents );
        $contents = str_replace ("\"", " ", $contents );
        $contents = str_replace ("'", " ", $contents );
        $contents = str_replace (":", " ", $contents );
        $contents = str_replace ("?", " ", $contents );
        $contents = str_replace ("!", " ", $contents );
        $contents = str_replace ("\"", " ", $contents );
        $contents = str_replace ("|", " ", $contents );
        $contents = str_replace ("qdom", " ", $contents );
        $contents = str_replace ("tech", " ", $contents );

        // strip &quot; combinations
        $contents = preg_replace("(&.+?;)", " ", $contents );

        // strip multiple whitespaces
        $contents = preg_replace("(\s+)", " ", $contents );

        $contents_array =& preg_split( "/ /", $contents );
        $contents_array =& array_merge( $contents_array, $this->manualKeywords( true ) );

        $totalWordCount = count( $contents_array );
        $wordCount = array_count_values( $contents_array );

        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            if ( strlen( $word ) >= 2 )
            {
                $keywords .= $word . " ";
            }
        }

        $this->Keywords = $keywords;

        $db =& eZDB::globalDatabase();
        $ret = array();

        $ret[] = $db->query( "DELETE FROM eZArticle_ArticleWordLink WHERE ArticleID='$this->ID'" );

        // get total number of articles
        $db->array_query( $article_array, "SELECT COUNT(*) AS Count FROM eZArticle_Article" );
        $articleCount = $article_array[0][$db->fieldName( "Count" )];

        $db->begin( );

        foreach ( $contents_array as $word )
        {
            if ( strlen( $word ) >= 2 )
            {
                $indexWord = $word;

                $indexWord = $db->escapeString( $indexWord );


                // find the frequency
                $count = $wordCount[$indexWord];

                $freq = ( $count / $totalWordCount );

                $query = "SELECT ID FROM eZArticle_Word
                      WHERE Word='$indexWord'";

                $db->array_query( $word_array, $query );


                if ( count( $word_array ) == 1 )
                {
                    // word exists create reference
                    $wordID = $word_array[0][$db->fieldName("ID")];

                    // number of links to this word
                    $db->array_query( $article_array, "SELECT COUNT(*) AS Count FROM eZArticle_ArticleWordLink WHERE WordID='$wordID'" );
                    $wordUsageCount = $article_array[0][$db->fieldName( "Count" )];

                    $wordFreq = ( $wordUsageCount + 1 )  / $articleCount;

                    // update word frequency
                    $ret[] = $db->query( "UPDATE  eZArticle_Word SET Frequency='$wordFreq' WHERE ID='$wordID'" );


                    $ret[] = $db->query( "INSERT INTO eZArticle_ArticleWordLink ( ArticleID, WordID, Frequency ) VALUES
                                      ( '$this->ID',
                                        '$wordID',
                                        '$freq' )" );
                }
                else
                {
                    // lock the table
                    $db->lock( "eZArticle_Word" );

                    $wordFreq = 1 / $articleCount;

                    // new word, create word
                    $nextID = $db->nextID( "eZArticle_Word", "ID" );
                    $ret[] = $db->query( "INSERT INTO eZArticle_Word ( ID, Word, Frequency ) VALUES
                                      ( '$nextID',
                                        '$indexWord',
                                        '$wordFreq' )" );
                    $db->unlock();

                    $ret[] = $db->query( "INSERT INTO eZArticle_ArticleWordLink ( ArticleID, WordID, Frequency ) VALUES
                                      ( '$this->ID',
                                        '$nextID',
                                        '$freq' )" );

                }
            }
        }
        eZDB::finish( $ret, $db );

    }

    /*!
      Sets the discuss value to an article.
    */
    function setDiscuss( $discuss )
    {
        if ( $discuss == true )
            $this->Discuss = 1;
        else
            $this->Discuss = 0;
    }

    /*!
      Sets the manual keywords to an article. Theese words are used in the search and for the keyword index.
    */
    function setManualKeywords( $keywords, $toLower = true )
    {
        if ( !is_array( $keywords ) )
        {
            $words = explode( ",", $keywords );
            $keywords = array();
            foreach( $words as $word )
            {
                if( $toLower )
                {
                    $keyword = strtolower( trim( $word ) );
                }
                else
                {
                    $keyword = trim( $word );
                }
                if ( $keyword != "" )
                    $keywords[] = $keyword;
            }
        }
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZArticle_ArticleKeyword WHERE ArticleID='$this->ID' AND Automatic='0'" );
        foreach( $keywords as $keyword )
        {
            $db->begin( );

            $keyword = $db->escapeString( $keyword );

            $db->lock( "eZArticle_ArticleKeyword" );

            $nextID = $db->nextID( "eZArticle_ArticleKeyword", "ID" );

            $res = $db->query( "INSERT INTO eZArticle_ArticleKeyword
                         ( ID,
                         ArticleID,
                         Keyword,
                         Automatic )
                         VALUES
                        ( '$nextID',
                          '$this->ID',
                          '$keyword',
                         '0' )" );


            $db->unlock();


            // Create first letter cache table
            $db->array_query( $letter_array, "SELECT SUBSTRING( Keyword from 1 for 1 ) AS Letter
                                               FROM eZArticle_ArticleKeyword GROUP BY Letter" );

            $db->lock( "eZArticle_ArticleKeywordFirstLetter" );

            $db->query( "DELETE FROM eZArticle_ArticleKeywordFirstLetter" );

            foreach ( $letter_array as $letter )
            {
                $nextID = $db->nextID( "eZArticle_ArticleKeywordFirstLetter", "ID" );

                $letter = strToLower( $letter[$db->fieldName("Letter")] );

                $res = $db->query( "INSERT INTO eZArticle_ArticleKeywordFirstLetter
                         ( ID,
                         Letter )
                         VALUES
                        ( '$nextID',
                          '$letter' )" );
            }

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      \private
      \static

      Returns an array of letters which is the unique first character of all keywords.
    */
    static public function &keywordFirstLetters( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $letter_array, "SELECT SUBSTRING( Keyword from 1 for 1 ) AS Letter
                                          FROM eZArticle_ArticleKeyword GROUP BY Letter ORDER BY Letter" );

        $retArray = array();
        foreach ( $letter_array as $letter )
        {
            $nextID = $db->nextID( "eZArticle_ArticleKeywordFirstLetter", "ID" );

            $letter = strToLower( $letter[$db->fieldName("Letter")] );
            $retArray[] = $letter;
        }

        return $retArray;
    }

    /*!
      Sets the start date for the article.
    */
    function setStartDate( &$date )
    {
        if ( is_a( $date, "eZDateTime" ) )
        {
            if ( !( $date->year() == "1970" && $date->month() == "1" && $date->day() == "1" ) )
            {
                $this->StartDate = $date;

                $now = (new eZDateTime())->timeStamp( true );
                $startDate = $date->timeStamp();

                if ( ( $startDate < $now ) and ( $date->year() != "1970" )  )
                {
                    $this->PublishedOverride = $startDate;
                    $this->IsPublished = 1;
                    $this->StartDate = 0;
                }
            }
        }
        else
            $this->StartDate = 0;

    }

    /*!
      Sets the start date for the article.
    */
    function setStopDate( &$date )
    {
        if ( is_a ( $date, "eZDateTime" ) )
            $this->StopDate = $date;
        else
            $this->StopDate = 0;
    }

    /*!
      Returns the manual keywords for an article.
      It is either returned as an array or as a comma separated string.
    */
    function &manualKeywords( $as_array = false )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query($keywords, "SELECT Keyword FROM eZArticle_ArticleKeyword
                                      WHERE ArticleID='$this->ID' AND Automatic='0'" );
        $ret = array();
        foreach( $keywords as $keyword )
        {
            $ret[] = $keyword[$db->fieldName("Keyword")];
        }
        if ( !$as_array )
            $ret = implode( ", ", $ret );
        return $ret;
    }

    /*!
      \static
      Returns an index of keywords found in all articles.
      It returns an array of unique keywords.
      If $firstLetter is set only the keywords with this first letter are returned.
    */
    static public function &manualKeywordIndex( $firstLetter = false )
    {
        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        if ( $user )
        {
            $groups =& $user->groups( false );

            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";

                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' ) AND Permission.ReadPermission='1') ) AND";

        $firstLetterSQL = "";
        if ( $firstLetter != false )
        {
            $firstLetterSQL = " HAVING Letter='$firstLetter' ";
        }
        $db =& eZDB::globalDatabase();
        $db->array_query( $keywords, "SELECT ArtKey.Keyword AS Keyword, SUBSTRING( ArtKey.Keyword from 1 for 1 ) AS Letter
                  FROM eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_Category AS Category,
                       eZArticle_Article AS Article LEFT JOIN eZArticle_ArticleKeyword AS ArtKey ON
                       Article.ID=ArtKey.ArticleID
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        AND Article.IsPublished = '1'
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ExcludeFromSearch = '0'
                        AND ArtKey.Keyword is not NULL
                                      GROUP BY Keyword $firstLetterSQL ORDER BY Keyword", 0, -1, "Keyword" );
        return $keywords;
    }

    /*!
      Sets the contents author.
    */
    function setContentsWriter( $author )
    {
        if ( is_numeric( $author ) )
            $this->ContentsWriterID = $author;
        else
            $this->ContentsWriterID = $author->id();
    }

    /*!
      Returns the contentswriter of the article.
    */
    function contentsWriter( $returnObject = true )
    {
        if ( $returnObject )
            return new eZAuthor( $this->ContentsWriterID );
        else
            return $this->ContentsWriterID;
    }


    /*!
      Sets the topic.
    */
    function setTopic( $topic )
    {
        $this->TopicID = is_numeric( $topic ) ? $topic : $topic->id();
    }

    /*!
      Returns the topic.

      If there is no topic selected for the article false is returned.
    */
    function topic( $as_object = true )
    {
        // include_once( "ezarticle/classes/eztopic.php" );
        return $as_object ? new eZTopic( $this->TopicID ) : $this->TopicID;
    }

    /*!
      \static
      Returns an array of articles which match short contents and the keywords.
    */
    static public function &searchByShortContent( $short_content, $keywords, $offset = 0, $max = -1, $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $content_sql = "";
        $keyword_sql = "";
        if ( count( $keywords ) > 0 )
        {
            foreach( $keywords as $keyword )
            {
                if ( $keyword_sql != "" )
                    $keyword_sql .= " OR ";
                $keyword_sql .= "ArtKey.Keyword='$keyword'";
            }
            if ( $content_sql != "" )
                $keyword_sql = "AND ( $keyword_sql )";
        }
        $conditions = "";
        if ( $content_sql != "" or $keyword_sql != "" )
        {
            $conditions = "AND $content_sql $keyword_sql";
        }
        $limit_sql = "";
        if ( !is_bool( $offset ) and $max > 0 )
        {
            $limit_sql = "LIMIT $offset, $max";
        }

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        if ( $user )
        {
            $groups = $user->groups( false );

            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";

                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' ) AND Permission.ReadPermission='1') ) AND";

        $select_sql = "";
        if ( is_bool( $offset ) )
        {
            $select_sql = "count( DISTINCT Article.ID ) as ArticleCount";
        }
        else
        {
            $select_sql = "DISTINCT Article.ID as ArticleID";
        }
        $sql = "SELECT $select_sql, Article.Name
                  FROM eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_Category AS Category,
                       eZArticle_Article AS Article LEFT JOIN eZArticle_ArticleKeyword AS ArtKey ON
                       Article.ID=ArtKey.ArticleID
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        AND Article.IsPublished = '1'
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ExcludeFromSearch = '0'
                        $conditions
                 ORDER BY Article.Name
                 $limit_sql";
        $db->array_query( $contents, $sql );
        if ( !is_bool( $offset ) )
        {
            $ret = array();
            foreach( $contents as $content )
            {
                $ret[] = $as_object ? new eZArticle( $content[$db->fieldName("ArticleID")] ) : $content[$db->fieldName("ArticleID")];
            }
        }
        else
            $ret = $contents[0][$db->fieldName("ArticleCount")];
        return $ret;
    }

    /*!
     Sets the article to published or not.
    */
    function setIsPublished( $value, $user = false, $as_script = false )
    {
        if ( !is_a( $user, "eZUser" ) )
            $user =& eZUser::currentUser();

        $category = $this->categoryDefinition();
        if ( is_a( $category, "eZArticleCategory" ) )
            $editorID = $category->editorGroup( false );

        if ( is_numeric ( $editorID ) && ( $editorID > 0 ) && $as_script == false )
        {
            $group = new eZUserGroup( $editorID );
            if ( $group->isMember( $user ) or ( $user->hasRootAccess() )  )
            {
                if ( $value == 0 )
                    $this->IsPublished = "0";
                else
                    $this->IsPublished = "1";
            }
            else
            {
                if ( $value == 0 )
                    $this->IsPublished = "0";
                else
                {
                    $this->IsPublished = "2";
                    $this->sendPendingMail( );
                }
            }
        }
        else if ( $value == true )
        {
            $this->IsPublished = "1";
        }
        else
        {
            $this->IsPublished = "0";
        }
    }


    /*!
      Returns the categories an article is assigned to.

      The categories are returned as an array of eZArticleCategory objects.
    */
    function categories( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $ret = array();
        $db->array_query( $category_array, "SELECT * FROM eZArticle_ArticleCategoryLink WHERE ArticleID='$this->ID'" );

        if ( $as_object )
        {
            foreach ( $category_array as $category )
            {
                $ret[] = new eZArticleCategory( $category[$db->fieldName("CategoryID")] );
            }
        }
        else
        {
            foreach ( $category_array as $category )
            {
                $ret[] = $category[$db->fieldName("CategoryID")];
            }
        }

        return $ret;
    }

    /*!
      Removes every category assignments from the current article.
    */
    function removeFromCategories()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZArticle_ArticleCategoryLink WHERE ArticleID='$this->ID'" );
    }

    /*!
      Adds an image to the article, unless the image is allready added for this article.
    */
    function addImage( $value, $placement = false )
    {
        $db =& eZDB::globalDatabase();

        if( is_a( $value, "eZImage" ) )
            $value = $value->id();

        $db->query_single( $res, "SELECT count( * ) as Count FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID' AND ImageID='$value'" );
        if( $res[$db->fieldName("Count")] == 0 )
        {
            $db->begin( );

            $db->lock( "eZArticle_ArticleImageLink" );

            if ( is_bool( $placement ) )
            {
                $db->array_query( $image_array, "SELECT ID, ImageID, Placement, Created FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID' ORDER BY Placement DESC" );

                if ( $image_array[0][$db->fieldName("Placement")] == "0" )
                {
                    $placement=1;
                    for ( $i=0; $i < count($image_array); $i++ )
                    {
                        $imageLinkID = $image_array[$i][$db->fieldName("ID")];
                        $db->query( "UPDATE eZArticle_ArticleImageLink SET Placement='$placement' WHERE ID='$imageLinkID'" );
                        $image_array[$i][$db->fieldName("Placement")] = $placement;
                        $placement++;
                    }
                }
                $placement = $image_array[0][$db->fieldName("Placement")] + 1;
            }

            $nextID = $db->nextID( "eZArticle_ArticleImageLink", "ID" );
            $timeStamp = (new eZDateTime())->timeStamp( true );

            $res = $db->query( "INSERT INTO eZArticle_ArticleImageLink
                         ( ID, ArticleID, ImageID, Created, Placement )
                         VALUES
                         ( '$nextID',  '$this->ID', '$value', '$timeStamp', '$placement' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Deletes an image from the article.

      NOTE: the image does not get deleted from the image catalogue.
    */
    function deleteImage( $value )
    {
        if ( is_a( $value, "eZImage" ) )
        {
            $imageID = $value->id();
        }
        else
            $imageID = $value;

        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZArticle_ArticleImageDefinition WHERE ArticleID='$this->ID' AND ThumbnailImageID='$imageID'" );

        $db->query( "DELETE FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID' AND ImageID='$imageID'" );
    }

    /*!
      Returns every image to a article as a array of eZImage objects.
    */
    function images( $asObject = true, $OrderBy ="Created" )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $image_array = array();

        $db->array_query( $image_array, "SELECT ID, ImageID, Placement, Created FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID' ORDER BY $OrderBy" );

        // convert the database if placement is not set
        if ( count( $image_array ) > 0 )
        {
            if ( $image_array[0][$db->fieldName("Placement")] == "0" )
            {
                $placement=1;
                for ( $i=0; $i < count($image_array); $i++ )
                {
                    $imageLinkID = $image_array[$i][$db->fieldName("ID")];
                    $db->query( "UPDATE eZArticle_ArticleImageLink SET Placement='$placement' WHERE ID='$imageLinkID'" );

                    $image_array[$i][$db->fieldName("Placement")] = $placement;
                    $placement++;
                }
            }
        }

        for ( $i=0; $i < count($image_array); $i++ )
        {
            $return_array[$i]["Image"] = $asObject ? new eZImage( $image_array[$i][$db->fieldName("ImageID")] ) : $image_array[$i][$db->fieldName("ImageID")];
            $return_array[$i]["Placement"] = $image_array[$i][$db->fieldName("Placement")];
        }

        return $return_array;
    }



    /*!
      Moves the image placement with the given ID up.
    */
    function moveImageUp( $id )
    {
        $db =& eZDB::globalDatabase();

	$db->query_single( $qry, "SELECT * FROM eZArticle_ArticleImageLink
				  WHERE ArticleID='$this->ID' AND ImageID='$id'" );

       if ( is_numeric( $qry[$db->fieldName("ID")] ) )
       {
           $linkID = $qry[$db->fieldName("ID")];

           $placement = $qry[$db->fieldName("Placement")];

           $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_ArticleImageLink
                                    WHERE Placement<'$placement' AND ArticleID='$this->ID'
                                    ORDER BY Placement DESC" );

           $newPlacement = $qry[$db->fieldName("Placement")];
           $listid = $qry[$db->fieldName("ID")];

           if ( $newPlacement == $placement )
           {
               $placement += 1;
           }

           if ( is_numeric( $listid ) )
           {
               $db->query( "UPDATE eZArticle_ArticleImageLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
               $db->query( "UPDATE eZArticle_ArticleImageLink SET Placement='$placement' WHERE ID='$listid'" );
           }
       }
    }

    /*!
      Moves the Image placement with the given ID down.
    */
    function moveImageDown( $id )
    {
       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZArticle_ArticleImageLink
                                  WHERE ArticleID='$this->ID' AND ImageID='$id'" );

       if ( is_numeric( $qry[$db->fieldName("ID")] ) )
       {
           $linkID = $qry[$db->fieldName("ID")];

           $placement = $qry[$db->fieldName("Placement")];

           $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_ArticleImageLink
                                    WHERE Placement>'$placement' AND ArticleID='$this->ID' ORDER BY Placement ASC" );

           $newPlacement = $qry[$db->fieldName("Placement")];
           $listid = $qry[$db->fieldName("ID")];

           if ( $newPlacement == $placement )
           {
               $newPlacement += 1;
           }

           if ( is_numeric( $listid ) )
           {
               $db->query( "UPDATE eZArticle_ArticleImageLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
               $db->query( "UPDATE eZArticle_ArticleImageLink SET Placement='$placement' WHERE ID='$listid'" );
           }
       }
    }

    /*!
      Sets the thumbnail image for the article.

      The argument must be an eZImage object, or false to unset the thumbnail image.
    */
    function setThumbnailImage( $image )
    {
        if ( is_a( $image, "eZImage" ) )
        {
            $db =& eZDB::globalDatabase();

            $imageID = $image->id();

            $db->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZArticle_ArticleImageDefinition
                                                       WHERE
                                                       ArticleID='$this->ID'" );

            if ( $res_array[0][$db->fieldName("Number")] == "1" )
            {
                $db->query( "UPDATE eZArticle_ArticleImageDefinition
                                         SET
                                         ThumbnailImageID='$imageID'
                                         WHERE
                                         ArticleID='$this->ID'" );
            }
            else
            {
                $db->begin( );


                $res = $db->query( "INSERT INTO eZArticle_ArticleImageDefinition
                                         ( ArticleID, ThumbnailImageID )
                                         VALUES
                                         ( '$this->ID', '$imageID' )" );
                $db->unlock();

                if ( $res == false )
                    $db->rollback( );
                else
                    $db->commit();

            }
        }
        else if ( $image == false )
        {
            $db =& eZDB::globalDatabase();

            $db->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZArticle_ArticleImageDefinition
                                                       WHERE
                                                       ArticleID='$this->ID'" );

            if ( $res_array[0][$db->fieldName("Number")] == "1" )
            {
                $db->query( "DELETE FROM eZArticle_ArticleImageDefinition
                                         WHERE
                                         ArticleID='$this->ID'" );
            }
        }
    }

    /*!
      Returns the thumbnail image of the article as a eZImage object.
    */
    function thumbnailImage( $as_object = true )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();

        $db->array_query( $res_array, "SELECT * FROM eZArticle_ArticleImageDefinition
                                     WHERE
                                     ArticleID='$this->ID'
                                   " );

        if ( count( $res_array ) == 1 )
        {
            $id = $res_array[0][$db->fieldName("ThumbnailImageID")];
            if ( $id != "NULL" )
            {
                $ret = $as_object ? new eZImage( $id ) : $id;
            }
        }

        return $ret;
    }

    /*!
      Adds an media to the article, unless the media is allready added for this article.
    */
    function addMedia( $value )
    {
        $db =& eZDB::globalDatabase();

        if( is_a( $value, "eZMedia" ) )
            $value = $value->id();

        $db->query_single( $res, "SELECT count( * ) as Count FROM eZArticle_ArticleMediaLink WHERE ArticleID='$this->ID' AND MediaID='$value'" );
        if( $res[$db->fieldName("Count")] == 0 )
        {
            $db->begin( );

            $db->lock( "eZArticle_ArticleMediaLink" );

            $nextID = $db->nextID( "eZArticle_ArticleMediaLink", "ID" );
            $timeStamp = (new eZDateTime())->timeStamp( true );

            $res = $db->query( "INSERT INTO eZArticle_ArticleMediaLink
                         ( ID, ArticleID, MediaID, Created )
                         VALUES
                         ( '$nextID',  '$this->ID', '$value', '$timeStamp' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Deletes an media from the article.

      NOTE: the media does not get deleted from the media catalogue.
    */
    function deleteMedia( $value )
    {
        if ( is_a( $value, "eZMedia" ) )
        {
            $mediaID = $value->id();
        }
        else
            $mediaID = $value;

        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZArticle_ArticleMediaDefinition WHERE ArticleID='$this->ID' AND ThumbnailMediaID='$mediaID'" );

        $db->query( "DELETE FROM eZArticle_ArticleMediaLink WHERE ArticleID='$this->ID' AND MediaID='$mediaID'" );
    }

    /*!
      Returns every media to a article as a array of eZMedia objects.
    */
    function media( $asObject = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $media_array = array();

        $db->array_query( $media_array, "SELECT MediaID, Created FROM eZArticle_ArticleMediaLink WHERE ArticleID='$this->ID' ORDER BY Created" );

        for ( $i=0; $i < count($media_array); $i++ )
        {
            $return_array[$i] = $asObject ? new eZMedia( $media_array[$i][$db->fieldName("MediaID")] ) : $media_array[$i][$db->fieldName("MediaID")];
        }

        return $return_array;
    }


    /*!
      Adds an file to the article.
      $value can either be a eZVirtualFile or an ID
    */
    function addFile( $value )
    {
        if ( is_a( $value, "eZVirtualFile" ) )
        {
            $fileID = $value->id();
        }
        else
            $fileID = $value;

        $db =& eZDB::globalDatabase();

        $db->begin( );

        $db->lock( "eZArticle_ArticleFileLink" );

        $nextID = $db->nextID( "eZArticle_ArticleFileLink", "ID" );

        $timeStamp = (new eZDateTime())->timeStamp( true );

        $res = $db->query( "INSERT INTO eZArticle_ArticleFileLink
                         ( ID, ArticleID, FileID, Created ) VALUES ( '$nextID', '$this->ID', '$fileID', '$timeStamp' )" );

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes an file from the article.
      $value can either be a eZVirtualFile or an ID

      NOTE: the file does not get deleted from the file catalogue.
    */
    function deleteFile( $value )
    {
        if ( is_a( $value, "eZVirtualFile" ) )
        {
            $fileID = $value->id();

        }
        else
            $fileID = $value;

        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZArticle_ArticleFileLink WHERE ArticleID='$this->ID' AND FileID='$fileID'" );
    }

    /*!
      Returns every file to a article as a array of eZFile objects.
    */
    function files( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $file_array = array();

        $db->array_query( $file_array, "SELECT FileID, Created FROM eZArticle_ArticleFileLink WHERE ArticleID='$this->ID' ORDER BY Created" );

        for ( $i=0; $i < count($file_array); $i++ )
        {
            $id = $file_array[$i][$db->fieldName("FileID")];
            $return_array[$i] = $as_object ? new eZVirtualFile( $id, false ) : $id;
        }

        return $return_array;
    }

    /*!
      Deletes an attribute from an article.
    */
    function deleteAttribute( $value )
    {
        if ( is_a( $value, "eZArticleAttribute" ) )
        {
            $db =& eZDB::globalDatabase();

            $attributeID = $value->id();

            $db->query( "DELETE FROM eZArticle_AttributeValue WHERE ArticleID='$this->ID' AND AttributeID='$attributeID'" );
        }
    }

    /*!
      Returns every attribute belonging to an article as an array of eZArticleAttribute objects.
    */
    function attributes( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $attribute_array = array();

        $db->array_query( $attribute_array, "SELECT Value.AttributeID, Attr.Placement FROM eZArticle_AttributeValue as Value, eZArticle_Attribute as Attr
                                             WHERE Attr.ID = Value.AttributeID AND Value.ArticleID='$this->ID' ORDER BY Attr.TypeID, Attr.Placement" );

        for ( $i=0; $i < count( $attribute_array ); $i++ )
        {
            $id = $attribute_array[$i][$db->fieldName("AttributeID")];
            $return_array[$i] = $as_object ? new eZArticleAttribute( $id, false ) : $id;
        }

        return $return_array;
    }


    /*!
      Deletes all attributes defined for this article which belongs to a certain type.
    */
    function deleteAttributesByType( $type )
    {
        $ret = false;

        if ( is_a( $type, "eZArticleType" ) )
        {
            $db =& eZDB::globalDatabase();

            $typeID = $type->id();

            $return_array = array();
            $attribute_array = array();

            $db->array_query( $attribute_array, "SELECT Value.ID FROM eZArticle_AttributeValue AS Value, eZArticle_Attribute AS Attr
                                                 WHERE Value.ArticleID='$this->ID' AND Value.AttributeID=Attr.ID AND Attr.TypeID='$typeID'" );

            for ( $i=0; $i < count( $attribute_array ); $i++ )
            {
               $valueID =  $attribute_array[$i][$db->fieldName("ID")];
               $db->query( "DELETE FROM eZArticle_AttributeValue WHERE ID='$valueID'" );
            }

            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns every attribute type belonging to an article as an array of eZArticleType objects.
    */
    function types( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $type_array = array();

        $db->array_query( $type_array, "SELECT Attr.TypeID AS TypeID FROM eZArticle_Attribute AS Attr, eZArticle_AttributeValue AS Value WHERE Value.ArticleID='$this->ID' AND Attr.ID = Value.AttributeID GROUP BY Attr.TypeID" );

        for ( $i=0; $i < count( $type_array ); $i++ )
        {
            $id = $type_array[$i][$db->fieldName("TypeID")];
            $return_array[$i] = $as_object ? new eZArticleType( $id, false ) : $id;
        }

        return $return_array;
    }

     /*!
      Returns true if the type given exists for this article.
    */
    function hasType( $type )
    {
        $ret = false;
        if ( is_a( $value, "eZArticleType" ) )
        {
            $typeID = $value->id();

            $db =& eZDB::globalDatabase();

            $return_array = array();
            $type_array = array();

            $db->array_query( $type_array, "SELECT Attr.TypeID AS TypeID FROM eZArticle_Attribute AS Attr, eZArticle_AttributeValue AS Value WHERE Value.ArticleID='$this->ID' AND Attr.ID = Value.AttributeID AND Attr.TypeID='$typeID'" );

            if( count( $type_array ) > 0 )
            {
                $ret = true;
            }
        }
        return $ret;
    }

   /*!
      Returns true if the article is assigned to the category given
      as argument. False if not.
     */
    function existsInCategory( $category )
    {
        $ret = false;
        if ( is_a( $category, "eZArticleCategory" ) )
        {
            $db =& eZDB::globalDatabase();
            $catID = $category->id();

            $db->array_query( $ret_array, "SELECT ID FROM eZArticle_ArticleCategoryLink
                                    WHERE ArticleID='$this->ID' AND CategoryID='$catID'" );

            if ( count( $ret_array ) == 1 )
            {
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Does a search in the article archive.
      queryText is the text to search for
      sortMode is the way the result is sorted.
      fetchnonPublished can be either true or false.
      offset, limit are self explanatory.

      params is an associative array that can contain the following items
      FromDate an eZDate object.
      ToDate an eZDate object.
      Categories an array of Category ID's
      Type
      AuthorID the ID of the author writing the article
      PhotographerID a photographer that has contributed to the article

      if SearchExcludedArticles is set to "true" articles which is set non searchable will also be searched.
      $SearchTotalCount will return the total number of items found in the search
    */
    function &search( &$queryText, $sortMode=time, $fetchPublished=false, $offset=0, $limit=10, $params = array(), &$SearchTotalCount = false )
    {
        $db =& eZDB::globalDatabase();

        $queryText = $db->escapeString( $queryText );

        // Build the ORDER BY
        $OrderBy = "eZArticle_ArticleWordLink.Frequency DESC";
        switch( $sortMode )
        {
            case "alpha" :
            {
                $OrderBy = "eZArticle_Article.Name DESC";
            }
            break;
        }

        if ( $fetchPublished == true )
        {
            $fetchText = "";
        }
        else
        {
            $fetchText = "AND eZArticle_Article.IsPublished = '1'";
        }

        $usePermission = true;

        $user =& eZUser::currentUser();

        // Build the permission
        $loggedInSQL = "";
        $groupSQL = "";
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $loggedInSQL = "eZArticle_Article.AuthorID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }

        $loggedInSQL = "( ( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) ) AND";

        if ( $usePermission )
            $permissionSQL = $loggedInSQL;
       else
           $permissionSQL = "";

        // stop word frequency
        $ini =& INIFile::globalINI();
        $StopWordFrequency = $ini->read_var( "eZArticleMain", "StopWordFrequency" );


        $query = new eZQuery( "eZArticle_Word.Word", $queryText );
        $query->setIsLiteral( true );
//		$query->setPartialCompare( true );
        $query->setStopWordColumn(  "eZArticle_Word.Frequency" );
        $query->setStopWordPercent( $StopWordFrequency );
        $searchSQL = $query->buildQuery();

        $authorSQL = "";
        $dateSQL = "";
        $catSQL = "";
        $typeTables = "";
        $typeSQL = "";
        $sectionsSQL = "";
        $photoSQL = "";
        $photoTables = "";

        if ( isset( $params["FromDate"] ) )
        {
            $fromdate = $params["FromDate"];
            if( is_a( $fromdate, "eZDateTime" ) )
                $fromdate = $fromdate->timeStamp();
            $dateSQL .= "AND eZArticle_Article.Published >= '$fromdate'";
        }
        if ( isset( $params["ToDate"] ) )
        {
            $todate = $params["ToDate"];
            if( is_a( $todate, "eZDateTime" ) )
                $todate = $todate->timeStamp();
            $dateSQL .= "AND eZArticle_Article.Published <= '$todate'";
        }
        if ( isset( $params["Categories"] ) )
        {
            $cats = $params["Categories"];
            $sql = "";
            $i = 0;
            foreach( $cats as $cat )
            {
                if ( $i > 0 )
                    $sql .= "OR ";
                $sql .= "Category.ID = '$cat' ";
                ++$i;
            }
            if ( count( $cats ) > 0 )
            {
                $catSQL = "AND ( $sql ) AND Category.ID=eZArticle_ArticleCategoryLink.CategoryID
                            AND eZArticle_Article.ID=eZArticle_ArticleCategoryLink.ArticleID";
            }
        }
        if ( isset( $params["Type"] ) )
        {
            $type = $params["Type"];
            $typeSQL = "AND eZArticle_Attribute.TypeID='$type'
                        AND eZArticle_Attribute.ID=eZArticle_AttributeValue.AttributeID
                        AND eZArticle_AttributeValue.ArticleID=eZArticle_Article.ID";
            $typeTables = "eZArticle_Attribute, eZArticle_AttributeValue, ";
        }
        if ( isset( $params["AuthorID"] ) )
        {
            $author = $params["AuthorID"];
            $authorSQL = "AND eZArticle_Article.ContentsWriterID='$author'";
        }
	if ( isset( $params["SectionsList"] ) )
	{
	    $sectionsList = $params["SectionsList"];
	    $sectionsArray = explode( ",", $sectionsList );
	    if ( is_numeric( $sectionsArray[0] ) )
	    {
		$sectionsSQL .= "AND ( Category.SectionID='$sectionsArray[0]'";
		for ( $i=1; $i<count( $sectionsArray ); $i++ )
	        {
		     $sectionsSQL .= " OR Category.SectionID='$sectionsArray[$i]'";
		}
		$sectionsSQL .= " ) ";
            }
        }
        if ( isset( $params["PhotographerID"] ) )
        {
            $photo = $params["PhotographerID"];
            $photoSQL = "AND eZImageCatalogue_Image.PhotographerID='$photo'
                         AND eZImageCatalogue_Image.ID=eZArticle_ArticleImageLink.ImageID
                         AND eZArticle_Article.ID=eZArticle_ArticleImageLink.ArticleID";
            $photoTables = "eZArticle_ArticleImageLink, eZImageCatalogue_Image,";
        }


        if ( isset($params["SearchExcludedArticles"]) &&  $params["SearchExcludedArticles"] == "true" )
            $excludeFromSearchSQL = " ";
        else
            $excludeFromSearchSQL = " AND Category.ExcludeFromSearch = '0' ";

        // special search for MySQL, mimic subselects ;)
        if ( $db->isA() == "mysql" )
        {
            $queryArray = explode( " ", trim( $queryText ) );

            $db->query( "CREATE TEMPORARY TABLE eZArticle_SearchTemp( ArticleID int )" );

            $count = 1;
            foreach ( $queryArray as $queryWord )
            {
                $queryWord = trim( $queryWord );

                $searchSQL = " ( eZArticle_Word.Word = '$queryWord' AND eZArticle_Word.Frequency < '$StopWordFrequency' ) ";
//                $searchSQL = " ( (eZArticle_Word.Word LIKE '$queryWord') AND eZArticle_Word.Frequency < '$StopWordFrequency' ) ";

                $queryString = "INSERT INTO eZArticle_SearchTemp ( ArticleID ) SELECT DISTINCT eZArticle_Article.ID AS ArticleID
                 FROM eZArticle_Article,
                      eZArticle_ArticleWordLink,
                      eZArticle_Word,
                      eZArticle_ArticleCategoryLink as Link,
                      $typeTables
                      $photoTables
                      eZArticle_ArticleCategoryDefinition as Definition,
                      eZArticle_ArticlePermission as Permission,
                      eZArticle_Category AS Category,
                      eZArticle_CategoryPermission as CategoryPermission

                 WHERE
                       $permissionSQL
                       $searchSQL
                       $dateSQL
                       $catSQL
                       $typeSQL
                       $authorSQL
                       $photoSQL
		       $sectionsSQL
                       AND
                       ( eZArticle_Article.ID=eZArticle_ArticleWordLink.ArticleID
                         AND Definition.ArticleID=eZArticle_Article.ID
                         AND Definition.CategoryID=Category.ID
                         $excludeFromSearchSQL
                         AND eZArticle_ArticleWordLink.WordID=eZArticle_Word.ID
                         AND Permission.ObjectID=eZArticle_Article.ID
                         AND CategoryPermission.ObjectID=Definition.CategoryID
                         $fetchText
                         AND Link.ArticleID=eZArticle_Article.ID
                        )
                       ORDER BY $OrderBy";

                $db->query( $queryString );

                // check if this is a stop word
                $queryString = "SELECT Frequency FROM eZArticle_Word WHERE Word='$queryWord'";

                $db->query_single( $WordFreq, $queryString, array( "LIMIT" => 1 ) );

                if ( is_array( $WordFreq ) && $WordFreq["Frequency"] <= $StopWordFrequency )
                    $count += 1;
            }
            $count -= 1;

            $queryString = "SELECT ArticleID, Count(*) AS Count FROM eZArticle_SearchTemp GROUP BY ArticleID HAVING Count>='$count'";

            $db->array_query( $article_array, $queryString );

//            $db->array_query( $article_array, $queryString, array( "Limit" => $limit, "Offset" => $offset ) );

            $db->query( "DROP  TABLE eZArticle_SearchTemp" );

            $SearchTotalCount = count( $article_array );
            if ( $limit >= 0 )
                $article_array =& array_slice( $article_array, $offset, $limit );
        }
        else
        {
            $queryString = "SELECT DISTINCT eZArticle_Article.ID AS ArticleID, eZArticle_Article.Published, eZArticle_Article.Name, eZArticle_ArticleWordLink.Frequency
                 FROM eZArticle_Article,
                      eZArticle_ArticleWordLink,
                      eZArticle_Word,
                      eZArticle_ArticleCategoryLink,
                      $catDefTable
                      $catTable
                      $typeTables
                      $photoTables
                      eZArticle_ArticlePermission
                 WHERE
                       $searchSQL
                       $dateSQL
                       $catSQL
                       $typeSQL
                       $authorSQL
                       $photoSQL
                       AND
                       ( eZArticle_Article.ID=eZArticle_ArticleWordLink.ArticleID
                         AND eZArticle_ArticleCategoryDefinition.ArticleID=eZArticle_Article.ID
                         AND eZArticle_ArticleCategoryDefinition.CategoryID=eZArticle_Category.ID
                         $excludeFromSearchSQL
                         AND eZArticle_ArticleWordLink.WordID=eZArticle_Word.ID
                         AND eZArticle_ArticlePermission.ObjectID=eZArticle_Article.ID
                         $fetchText
                         AND eZArticle_ArticleCategoryLink.ArticleID=eZArticle_Article.ID AND
                          ( $loggedInSQL ($groupSQL eZArticle_ArticlePermission.GroupID='-1')
                            AND eZArticle_ArticlePermission.ReadPermission='1'
                          )
                        )
                       ORDER BY $OrderBy";

            $db->array_query( $article_array, $queryString );

            $SearchTotalCount = count( $article_array );
            $article_array =& array_slice( $article_array, $offset, $limit );
        }

        for ( $i = 0; $i < count($article_array); $i++ )
        {
            $return_array[$i] = new eZArticle( $article_array[$i][$db->fieldName( "ArticleID" )], false );
        }

        return $return_array;
    }

    /*!
      Does a search for titles in the article archive.
      queryText is the text to search for
      fetchnonPublished can be either true or false.

      $SearchTotalCount will return the total number of items found in the search
    */
    function &searchTitle( &$queryText, $fetchPublished=false, &$SearchTotalCount = false, $categoryID = false )
    {
        $db =& eZDB::globalDatabase();

        $queryText = $db->escapeString( $queryText );

        // Build the ORDER BY
        $OrderBy = "eZArticle_Article.Published DESC";

        if ( $fetchPublished == true )
            $fetchText = "";
        else
            $fetchText = "AND eZArticle_Article.IsPublished = '1'";

        if ( $categoryID )
            $categoryText = "AND eZArticle_ArticleCategoryLink.CategoryID = '$categoryID'";
        else
            $categoryText = "";

        $usePermission = true;

        $user =& eZUser::currentUser();

        $query = "SELECT eZArticle_Article.ID AS ArticleID FROM eZArticle_Article, eZArticle_ArticleCategoryLink WHERE
                  eZArticle_Article.Name LIKE '%$queryText%' AND
                  eZArticle_Article.ID = eZArticle_ArticleCategoryLink.ArticleID
                  $fetchText
                  $categoryText GROUP BY eZArticle_Article.ID ORDER BY eZArticle_Article.Published";

        $db->array_query( $article_array, $query );
        $SearchTotalCount = count( $article_array );

        for ( $i = 0; $i < count( $article_array ); $i++ )
        {
            $return_array[$i] = new eZArticle( $article_array[$i][$db->fieldName( "ArticleID" )], false );
        }

        return $return_array;
    }

    /*!
      Returns the number of articles available, for the current user.
    */
    function articleCount( $fetchNonPublished=true, $excludeFromSearch=false )
    {
        $db =& eZDB::globalDatabase();

        $OrderBy = "Article.Published DESC";
        //switch( $sortMode )
        //{
        //    case "alpha" :
        //    {
        //        $OrderBy = "Article.Name DESC";
        //    }
        //    break;
        //}

        $ini =& INIFile::globalINI();
        $ExcludeCategories = "";
        if ( $ini->has_var( "eZArticleMain", "ExcludeCategories" ) )
        {
            $ExcludeCategories = $ini->read_var( "eZArticleMain", "ExcludeCategories" );
        }


        $return_array = array();
        $article_array = array();

        $usePermission = true;

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) ) ";

        if ( $usePermission )
            $permissionSQL = $loggedInSQL;
        else
            $permissionSQL = "";

       // fetch only published articles
       if ( $fetchNonPublished  == true )
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Article.IsPublished = '0' AND ";
           else
               $publishedSQL = " AND Article.IsPublished = '0' AND ";
       }
       // fetch only non-published articles
       else
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Article.IsPublished = '1' AND ";
           else
               $publishedSQL = " AND Article.IsPublished = '1' AND ";
       }

       // fetch only published articles
       if ( $fetchNonPublished  == "pending" )
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Article.IsPublished = '2' AND ";
           else
               $publishedSQL = " AND Article.IsPublished = '2' AND ";
       }

       if ( $excludeFromSearch )
           $excludeSQL = " AND Category.ExcludeFromSearch = '0'";
       else
           $excludeSQL = "";



       if ($ExcludeCategories && $ExcludeCategories<>"") $excludeSQL .= " AND Category.ID NOT IN (".$ExcludeCategories.")";


        $query = "SELECT COUNT( DISTINCT Article.ID ) as Count
                  FROM eZArticle_ArticleCategoryDefinition as Definition,
                       eZArticle_Article AS Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_CategoryPermission as CategoryPermission,
                       eZArticle_Category AS Category
                  WHERE $permissionSQL
                        $publishedSQL
                        Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ID=Link.CategoryID
                        AND Definition.ArticleID=Article.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                        $excludeSQL ";


        $db->array_query( $article_array, $query  );

        return  $article_array[0][$db->fieldName("Count")];
    }

    /*!
      Returns every article in every category sorted by time.
    */
    function &articles( $sortMode="time", $fetchNonPublished=true,
                        $offset=0, $limit=50 )
    {
        $db =& eZDB::globalDatabase();

        $OrderBy = "Article.Published DESC";
        $GroupBy = "Article.Published";
        switch( $sortMode )
        {
            case "alpha" :
            {
                $GroupBy = "Article.Name";
                $OrderBy = "Article.Name DESC";
            }
            break;
        }

        $ini =& INIFile::globalINI();
        $ExcludeCategories = "";
        if ( $ini->has_var( "eZArticleMain", "ExcludeCategories" ) )
        {
            $ExcludeCategories = $ini->read_var( "eZArticleMain", "ExcludeCategories" );
        }


        $return_array = array();
        $article_array = array();

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        $usePermission = true;
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) ) ";

        if ( $usePermission )
            $permissionSQL = $loggedInSQL;
       else
           $permissionSQL = "";

       $excludeSQL = " AND Category.ExcludeFromSearch = '0'";

       // fetch only published articles
       if ( $fetchNonPublished  == true )
       {
           $excludeSQL = "";
           if ( $permissionSQL == "" )
               $publishedSQL = " Article.IsPublished = '0' AND ";
           else
               $publishedSQL = " AND Article.IsPublished = '0' AND ";
       }
       // fetch only non-published articles
       else
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Article.IsPublished = '1' AND ";
           else
               $publishedSQL = " AND Article.IsPublished = '1' AND ";
       }

       // fetch only published articles
       if ( $fetchNonPublished  == "pending" )
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Article.IsPublished = '2' AND ";
           else
               $publishedSQL = " AND Article.IsPublished = '2' AND ";
       }

        if ( $ExcludeCategories && $ExcludeCategories <> "" )
            $excludeSQL .= " AND Category.ID NOT IN (".$ExcludeCategories.")";

        $query = "SELECT Article.ID as ArticleID
                  FROM eZArticle_ArticleCategoryDefinition as Definition,
                       eZArticle_Article AS Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_CategoryPermission as CategoryPermission,
                       eZArticle_Category AS Category
                  WHERE $permissionSQL
                        $publishedSQL
                        Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        $excludeSQL
                        AND Definition.ArticleID=Article.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                 GROUP BY Article.ID, Article.IsPublished, $GroupBy ORDER BY $OrderBy";


        $db->array_query( $article_array, $query, array( "Limit" => $limit, "Offset" => $offset )  );

        for ( $i=0; $i < count($article_array); $i++ )
        {
            $return_array[$i] = new eZArticle( $article_array[$i][$db->fieldName("ArticleID")], false );
        }

        return $return_array;
    }

    /*!
      Set's the articles defined category. This is the main category for the article.
      Additional categories can be added with eZArticleCategory::addArticle();
    */
    function setCategoryDefinition( $value )
    {
        if ( is_a( $value, "eZArticleCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $categoryID = $value->id();

            $db->begin( );

            $res[] = $db->query( "DELETE FROM eZArticle_ArticleCategoryDefinition
                                     WHERE ArticleID='$this->ID'" );


            $db->lock( "eZArticle_ArticleCategoryDefinition" );
            $nextID = $db->nextID( "eZArticle_ArticleCategoryDefinition", "ID" );

            $query = "INSERT INTO
                           eZArticle_ArticleCategoryDefinition
                           ( ID, CategoryID, ArticleID )
                      VALUES
                           ( '$nextID',
                             '$categoryID',
                             '$this->ID' )";

            $res[] = $db->query( $query );


            $db->unlock();

            if ( in_array( false, $res ) )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Returns the article's definition category.
    */
    function categoryDefinition( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT CategoryID FROM
                                            eZArticle_ArticleCategoryDefinition
                                            WHERE ArticleID='$this->ID'" );

        $category = false;
        if ( count( $res ) == 1 )
        {
            $id = $res[0][$db->fieldName("CategoryID")];
            $category = $as_object ? new eZArticleCategory( $id ) : $id;
        }
        else
        {
            print( "<br><b>Failed to get article category definition for ID $this->ID</b></br>" );
        }

        return $category;
    }

    /*!
      \static
      Returns the article definition id to the corresponding
      article id.

      false is returned if no article was found.
    */
    static public function categoryDefinitionStatic( $id )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT CategoryID FROM
                                            eZArticle_ArticleCategoryDefinition
                                            WHERE ArticleID='$id'" );

        if ( count( $res ) == 1 )
            return $res[0][$db->fieldName("CategoryID")];
        else
            return false;
    }

    /*!
      Returns the ID of a category
    */
    function GetCategory( $articleSectionID = 0 )
    {

        $db =& eZDB::globalDatabase();

        $categoryQueryWithSection="SELECT DISTINCT eZArticle_Category.ID AS CategoryID
            FROM
            eZArticle_Article, eZArticle_Category, eZArticle_ArticleCategoryLink
	    WHERE
	    eZArticle_Category.ID=eZArticle_ArticleCategoryLink.CategoryID
	    AND eZArticle_Article.ID=eZArticle_ArticleCategoryLink.ArticleID
	    AND eZArticle_Article.ID=$this->ID
	    AND eZArticle_Category.SectionID='$articleSectionID'";

	$categoryQueryWithoutSection="SELECT DISTINCT eZArticle_ArticleCategoryDefinition.CategoryID AS CategoryID
            FROM
            eZArticle_Article, eZArticle_ArticleCategoryDefinition
	    WHERE
	    eZArticle_Article.ID=eZArticle_ArticleCategoryDefinition.ArticleID
	    AND eZArticle_Article.ID=$this->ID";


	if ( $articleSectionID > 0 )
	{
	    $db->array_query( $category_array, $categoryQueryWithSection );
	    if ( count( $category_array ) == 0 )
	    {
	        $db->array_query( $category_array, $categoryQueryWithoutSection );
	    }
	}
	else
	{
	    $db->array_query( $category_array, $categoryQueryWithoutSection );
	}

	return $category_array[0][$db->fieldName("CategoryID")];
    }


    /*!
      Creates a discussion forum for the article.
    */
    function createForum()
    {

    }

    /*!
      Returns the forum for the article.
    */
    function forum( $as_object = true )
    {
	
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT ForumID FROM
                                            eZArticle_ArticleForumLink
                                            WHERE ArticleID='$this->ID'" );
        $forum = false;
        if ( count( $res ) == 1 )
        {
            if ( $as_object )
                $forum = new eZForum( $res[0][$db->fieldName( "ForumID" )] );
            else
                $forum = $res[0][$db->fieldName( "ForumID" )];
        }
        else
        {
            $forum = new eZForum();
            $forum->setName( $db->escapeString( $this->Name ) );

			$ini =& INIFile::globalINI();
        	$RequireUserLogin = $ini->read_var( "eZArticleMain", "RequireUserLogin" );
			if ($RequireUserLogin == "disabled")			
				$forum->setIsAnonymous('true');			
			
            $forum->store();

            $forumID = $forum->id();

            $db->begin( );

            $db->lock( "eZArticle_ArticleForumLink" );

            $nextID = $db->nextID( "eZArticle_ArticleForumLink", "ID" );

            $res = $db->query( "INSERT INTO eZArticle_ArticleForumLink
                                ( ID, ArticleID, ForumID )
                                VALUES
                                ( '$nextID', '$this->ID', '$forumID' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();


            if ( $as_object )
                $forum = new eZForum( $forumID );
            else
                $forum = $forumID;
        }
        return $forum;
    }


    /*!
      \Static
      Returns true if the given user is the author of the given object.
      $user is either a userID or an eZUser.
      $article is the articleID
     */
    public static function isAuthor( $user, $articleID )
    {
        if( !is_a( $user, "eZUser" ) )
            return false;

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT AuthorID from eZArticle_Article WHERE ID='$articleID'");
        $authorID = $res[$db->fieldName("AuthorID")];
        if(  $authorID == $user->id() )
            return true;

        return false;
    }

    /*!
      Returns the article which a forum is connected to.
     */
    public static function articleIDFromForum( $ForumID )
    {
        $db =& eZDB::globalDatabase();

        $ArticleID = 0;

        $db->array_query( $result, "SELECT ArticleID FROM
                                    eZArticle_ArticleForumLink
                                    WHERE ForumID='$ForumID' GROUP BY ArticleID" );

        if( count( $result ) > 0 )
        {
            $ArticleID = $result[0][$db->fieldName("ArticleID")];
        }

        return $ArticleID;
    }

    /*!
      Returns a list of authors and their article count.
    */
    static public function authorList( $offset = 0, $limit = -1, $sort = false )
    {
        $sort_text = false;

        if ( is_string( $sort ) )
        {
            switch( $sort )
            {
                case "count":
                {
                    $sort_text = "ORDER BY Count";
                    break;
                }
                default:
                case "name":
                {
                    $sort_text = "ORDER BY ContentsWriterID";
                    break;
                }
            }
        }

        $db =& eZDB::globalDatabase();

        $db->array_query( $qry_array, "SELECT count( eZArticle_Article.ID ) AS Count, eZUser_Author.Name AS ContentsWriter, eZUser_Author.ID AS ContentsWriterID
                                       FROM eZArticle_Article, eZArticle_ArticleCategoryDefinition, eZUser_Author
                                       WHERE IsPublished='1' AND eZArticle_Article.ID=ArticleID
                                       AND eZArticle_Article.ContentsWriterID=eZUser_Author.ID
                                       GROUP BY eZUser_Author.ID, eZUser_Author.Name $sort_text ",
        array( "Limit" => $limit, "Offset" => $offset ) );

        return $qry_array;
    }

    /*!
      Returns a all articles an author has written that currentuser is allowed to see.
    */
    static public function authorArticleList( $authorid, $offset = 0, $limit = -1, $sort = false )
    {
        $sort_text = '';
        if ( is_string( $sort ) )
        {
            switch( $sort )
            {
                case "author":
                {
                    $sort_text = "ORDER BY A.ContentsWriterID";
                    break;
                }
                case "name":
                {
                    $sort_text = "ORDER BY A.Name";
                    break;
                }
                case "category":
                {
                    $sort_text = "ORDER BY C.Name";
                    break;
                }
                default:
                case "published":
                {
                    $sort_text = "ORDER BY A.Published DESC";
                    break;
                }
            }
        }

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        $usePermission = true;
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( P.GroupID='$group' AND CP.GroupID='$group' ) OR
                              ( P.GroupID='$group' AND CP.GroupID='-1' ) OR
                              ( P.GroupID='-1' AND CP.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $currentUserSQL = "A.AuthorID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL P.GroupID='-1' AND CP.GroupID='-1' ) AND P.ReadPermission='1' AND CP.ReadPermission='1' ) ) ";

        $query = "SELECT A.ID, A.Name, Author.Name as AuthorName, A.Published, C.ID as CategoryID, C.Name as CategoryName
                     FROM
eZArticle_Article AS A,
eZArticle_Category as C,
eZArticle_ArticleCategoryDefinition as ACL,
eZArticle_ArticlePermission AS P,
eZArticle_CategoryPermission AS CP,
eZUser_Author as Author
                     WHERE $loggedInSQL AND A.ID=ACL.ArticleID AND C.ID=ACL.CategoryID AND A.ContentsWriterID=Author.ID AND
                     IsPublished='1' AND ContentsWriterID='$authorid' AND
                     CP.ObjectID=ACL.CategoryID AND
                     A.ID=P.ObjectID GROUP BY A.ID $sort_text ";

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, $query, array( "Limit" => $limit, "Offset" => $offset ) );

        return $qry_array;
    }

    /*!
      Returns the number of articles this author has written that the user is allowed to see.
    */
    static public function authorArticleCount( $authorid )
    {
        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        $usePermission = true;
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( P.GroupID='$group' AND CP.GroupID='$group' ) OR
                              ( P.GroupID='$group' AND CP.GroupID='-1' ) OR
                              ( P.GroupID='-1' AND CP.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $currentUserSQL = "A.AuthorID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL P.GroupID='-1' AND CP.GroupID='-1' ) AND P.ReadPermission='1' AND CP.ReadPermission='1' ) ) ";

        $query = "SELECT count( DISTINCT A.ID ) AS Count
                     FROM eZArticle_Article AS A,
                     eZArticle_ArticleCategoryDefinition AS Def,
                     eZArticle_ArticlePermission AS P,
                     eZArticle_CategoryPermission AS CP,
                     eZUser_Author as Author
                     WHERE
                     $loggedInSQL AND CP.ObjectID=Def.CategoryID AND A.ID=P.ObjectID AND  A.ID=Def.ArticleID AND
                     A.ContentsWriterID=Author.ID AND A.IsPublished='1' AND A.ContentsWriterID='$authorid'";

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, $query );

        return (int)$qry_array[0][$db->fieldName("Count")];
    }

    /*!
      Adds a log message to the article.
    */
    function addLog( $message, $user = false )
    {
        $db =& eZDB::globalDatabase();

        if ( !$user )
            $user =& eZUser::currentUser();
        $userID = $user->id();

        $db->begin( );

        $db->lock( "eZArticle_Log" );

        $nextID = $db->nextID( "eZArticle_Log", "ID" );

        $timeStamp =& (new eZDateTime())->timeStamp( true );

        $query = "INSERT INTO eZArticle_Log
                  ( ID,  ArticleID, Created, Message, UserID )
                  VALUES
                  ( '$nextID',
                    '$this->ID',
                    '$timeStamp',
                    '$message',
                    '$userID' )";

        $res = $db->query( $query );

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Adds a form to the article.
    */
    function deleteForms()
    {
        $db =& eZDB::globalDatabase();

        $ArticleID = $this->ID;

        $query = "DELETE FROM eZArticle_ArticleFormDict
                  WHERE ArticleID=$ArticleID
                  ";
        $db->query( $query );
    }


    /*!
      Adds a form to the article.
    */
    function addForm( $form )
    {
        $db =& eZDB::globalDatabase();

        if( is_a( $form, "eZForm" ) )
        {
            $ArticleID = $this->ID;
            $FormID = $form->id();

            $db->begin( );

            $db->lock( "eZArticle_ArticleFormDict" );

            $nextID = $db->nextID( "eZArticle_ArticleFormDict", "ID" );

            $query = "INSERT INTO eZArticle_ArticleFormDict
                      ( ID, ArticleID, FormID )
                      VALUES ( '$nextID', '$ArticleID', '$FormID' )
                      ";
            $res = $db->query( $query );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();

        }
    }

    /*!
      Returns an array of the forms for the current article.
    */
    function forms( $as_object = true)
    {
        $db =& eZDB::globalDatabase();

        // include_once( "ezform/classes/ezform.php" );

        $ArticleID = $this->ID;

        $return_array = array();

        $query = "SELECT FormID FROM eZArticle_ArticleFormDict
                      WHERE ArticleID=$ArticleID
                      ";

        $db->array_query( $ret_array, $query );
        $count = count( $ret_array );
        for( $i = 0; $i < $count; $i++ )
        {
            $id = $ret_array[$i][$db->fieldName("FormID")];
            $return_array[] = $as_object ? new eZForm( $id ) : $id;
        }
        return $return_array;
    }

    /*!
      Returns an array of the logg messages for the current article.

      The messages are returned as : array( date, message ).
    */
    function logMessages( )
    {
        $db =& eZDB::globalDatabase();
        $ret = array();

        $query = "SELECT * FROM  eZArticle_Log
                  WHERE ArticleID='$this->ID'
                  ORDER BY Created
                  ";


        $db->array_query( $ret_array, $query );
        foreach( $ret_array as $log )
        {
            $ret[] = array( "Created" => $log[$db->fieldName( "Created" )], "UserID" => $log[$db->fieldName( "UserID" )], "Message" => $log[$db->fieldName( "Message" )] );
        }
        return $ret;
    }

    /*!
      Returns all the articles in the database.

      The articles are returned as an array of eZArticle objects.
    */
    function &getAll( )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $articleArray = array();

        // $db->array_query( $articleArray, "SELECT * FROM eZArticle_Article" );

        $db->array_query( $articleArray, "SELECT ID
                                          FROM eZArticle_Article
                                          " );

        for ( $i=0; $i < count($articleArray); $i++ )
        {
            $returnArray[$i] = new eZArticle( $articleArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }

    /*!
      Returns all the articles that is valid now.

      The articles are returned as an array of eZArticle objects.
    */
    function &getAllValid( $isPublished=false )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $articleArray = array();

        if ( !$isPublished )
            $published = "  IsPublished='0' ";
        else
            $published = "  IsPublished='1' ";

        $now =& (new eZDateTime())->timeStamp( true );

        $db->array_query( $articleArray, "SELECT *
                                          FROM eZArticle_Article
                                          WHERE $published
                                          AND ( StartDate !='0' AND StartDate <= $now )
                                          AND ( StopDate ='0' OR StopDate >= $now )
                                          ORDER BY ID
                                          " );

        for ( $i=0; $i < count($articleArray); $i++ )
        {
            $returnArray[$i] = new eZArticle( $articleArray[$i] );
        }

        return $returnArray;
    }

    /*!
      \private
      Send mail to the editors that will tell them that a new article is ready to be published.
    */
    function sendPendingMail()
    {
        $ini =& INIFile::globalINI();
        $Language = $ini->read_var( "eZArticleMain", "Language" );
        $adminSiteURL = $ini->read_var( "site", "AdminSiteURL" );
        $index = $ini->Index;
        $wwwDir = $ini->WWWDir;
        global $HTTP_HOST;

        $definition = $this->categoryDefinition();

        $editorGroup = $definition->editorGroup();


        $mailTemplate = new eZTemplate( "kernel/ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                                        "kernel/ezarticle/admin/intl", $Language, "pendingmail.php" );

        $mailTemplate->set_file( "pending_mail_tpl", "pendingmail.tpl" );
        $mailTemplate->setAllStrings();


        $mail = new eZMail();

        $author = $this->contentsWriter();
        $authorEmail = $author->email();
        $name = $author->name();

        $mailTemplate->set_var( "author", $name . " " . $authorEmail );
        $mailTemplate->set_var( "url", "http://" . $HTTP_HOST . $wwwDir . $index .
                                "/article/articlepreview/" . $this->ID .  "/" );
        $mailBody = $mailTemplate->parse( "dummy", "pending_mail_tpl" );
        $mail->setBody( $mailBody );

        $mail->setFrom( $authorEmail );

        $users = $editorGroup->users();
        foreach ( $users as $userItem )
        {
            $mail->setTo( $userItem->email() );
            $mail->send();
        }
    }

    /*!
      Returns all the articles that is not valid now.

      The articles are returned as an array of eZArticle objects.
    */
    function &getAllUnValid( $isPublished=true )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $articleArray = array();

        if ( !$isPublished )
            $published = "  IsPublished='0' ";
        else
            $published = "  IsPublished='1' ";

        $now =& (new eZDateTime())->timeStamp( true );

        $db->array_query( $articleArray, "SELECT *
                                          FROM eZArticle_Article
                                          WHERE $published
                                          AND ( StopDate !='0')
                                          AND StopDate <= $now
                                          ORDER BY ID
                                          " );

        for ( $i=0; $i < count($articleArray); $i++ )
        {
            $returnArray[$i] = new eZArticle( $articleArray[$i] );
        }

        return $returnArray;
    }


    var $ID;
    var $AuthorID;
    var $ContentsWriterID;
    var $Name;
    var $Contents;
    var $LinkText;
    var $Modified;
    var $Created;
    var $Published;
    // override publising date
    var $PublishedOverride;
    var $Keywords;
    var $Discuss;
    var $TopicID;
    var $StartDate;
    var $StopDate;
    var $ImportID;

    // tell eZ publish to show the article to the public
    var $IsPublished;

    // variable for storing the number of pages in the article.
    var $PageCount;

    // php8
    var $AuthorText;
    var $AuthorEmail;

}

?>