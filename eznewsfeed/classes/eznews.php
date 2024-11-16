<?php
//
// $Id: eznews.php 6224 2001-07-20 11:21:41Z jakobn $
//
// Definition of eZNews class
//
// Created on: <13-Nov-2000 12:51:40 bf>
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

//!! eZNewsFeed
//! eZNews handles news items in the database.
/*!
  Example code:
  \code
  $news = new eZNews( );

  $news->setName( "eZ publish released" );
  $news->setIntro( "Is publish is a ....." );
  $news->setIsPublished( true );

  $news->setKeywords( "PHP GPL Object Oriented" );
  $news->setOrigin( "ez.no" );
  $news->setURL( "http://ez.no" );

  $dateTime = new eZDateTime( 2000, 11, 13, 14, 0, 15 );
  print( $dateTime->timeStamp() );

  $news->setOriginalPublishingDate( $dateTime );

  $news->store();
  \endcode
  \sa eZNewsCategory
*/

/*!TODO

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZNews
{
    /*!
      Constructs a new eZNews object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZNews( $id=""  )
    {
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a news to the database. This function will not store two similar news items
      to the database. If a news with the same title, description and url are already
      stored the news is skipped.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $timeStamp = eZDateTime::timeStamp( true );

        $name = $db->escapeString( $this->Name );
        $intro = $db->escapeString( $this->Intro );
        $url = $db->escapeString( $this->URL );
        $keywords = $db->escapeString( $this->KeyWords );
        $origin = $db->escapeString( $this->Origin );

        if ( !isset( $this->ID ) )
        {
            // check if the news is already stored.
            $db->array_query( $ret, "SELECT ID FROM eZNewsFeed_News WHERE
		                         Name='$name' AND
                                 Intro='$intro' AND
                                 URL='$url'
                                 " );

            if ( count( $ret ) == 0 )
            {

                $db->lock( "eZNewsFeed_News" );
                $nextID = $db->nextID( "eZNewsFeed_News", "ID" );

                $ret[] = $db->query( "INSERT INTO eZNewsFeed_News
                               ( ID,
                                 Name,
                                 Intro,
                                 IsPublished,
                                 PublishingDate,
                                 OriginalPublishingDate,
                                 KeyWords,
                                 Origin,
                                 URL )
                               VALUES
                               ( '$nextID',
                                 '$name',
                                 '$intro',
                                 '$this->IsPublished',
                                 '$timeStamp',
                                 '$this->OriginalPublishingDate',
                                 '$keywords',
                                 '$this->Origin',
                                 '$url' )" );

				$this->ID = $nextID;

            }
            else
            {
                $ret[] = false;
            }
        }
        else
        {
            $db->array_query( $res, "SELECT ID FROM eZNewsFeed_News WHERE IsPublished='0' AND ID='$this->ID'" );
            if ( ( count( $res ) > 0 ) && ( $this->IsPublished == "1" ) )
            {
                $db->query( "UPDATE eZNewsFeed_News SET
		                         Name='$name',
                                 Intro='$intro',
                                 IsPublished='1',
                                 PublishingDate='$timeStamp',
                                 OriginalPublishingDate='$this->OriginalPublishingDate',
                                 KeyWords='$keywords',
                                 Origin='$origin',
                                 URL='$url'
                                 WHERE ID='$this->ID'
                                 " );
            }
            else
            {
                $db->query( "UPDATE eZNewsFeed_News SET
		                         Name='$name',
                                 Intro='$intro',
                                 IsPublished='$this->IsPublished',
                                 PublishingDate=PublishingDate,
                                 OriginalPublishingDate='$this->OriginalPublishingDate',
                                 KeyWords='$keywords',
                                 Origin='$origin',
                                 URL='$url'
                                 WHERE ID='$this->ID'
                                 " );
            }

            $ret[] = true;
        }

        eZDB::finish( $ret, $db );

        return !in_array( false, $ret );
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
            $db->array_query( $news_array, "SELECT * FROM eZNewsFeed_News WHERE ID='$id'" );
            if ( count( $news_array ) > 1 )
            {
                die( "Error: News's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $news_array ) == 1 )
            {
                $this->ID =& $news_array[0][$db->fieldName("ID")];
                $this->Name =& $news_array[0][$db->fieldName("Name")];
                $this->Intro =& $news_array[0][$db->fieldName("Intro")];
                $this->IsPublished =& $news_array[0][$db->fieldName("IsPublished")];
                $this->PublishingDate =& $news_array[0][$db->fieldName("PublishingDate")];
                $this->OriginalPublishingDate =& $news_array[0][$db->fieldName("OriginalPublishingDate")];
                $this->Origin =& $news_array[0][$db->fieldName("Origin")];
                $this->KeyWords =& $news_array[0][$db->fieldName("KeyWords")];
                $this->URL =& $news_array[0][$db->fieldName("URL")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZNews object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->begin();

            $res[] = $db->query( "DELETE FROM eZNewsFeed_NewsCategoryLink WHERE NewsID='$this->ID'" );

            $res[] = $db->query( "DELETE FROM eZNewsFeed_News WHERE ID='$this->ID'" );

            if ( in_array( false, $res ) )
                $db->rollback( );
            else
                $db->commit();
        }

        return in_array( false, $res );
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the news name / title.
    */
    function &name()
    {
       return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the news contents.
    */
    function &intro()
    {
       return $this->Intro;
    }

    /*!
      Returns the url to the news.
    */
    function &url()
    {
       return $this->URL;
    }

    /*!
      Returns the publishing time of the news.

      The time is returned as a eZDateTime object.
    */
    function &publishingDate()
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->PublishingDate );

       return $dateTime;
    }

    /*!
      Returns the original publishing time of the news.

      The time is returned as a eZDateTime object.
    */
    function &originalPublishingDate()
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->OriginalPublishingDate );

       return $dateTime;
    }

    /*!
      Returns the origin of the news.
    */
    function origin( )
    {
       return $this->Origin;
    }

    /*!
      Returns the keywords of an news.
    */
    function keywords( )
    {
       return $this->KeyWords;
    }

    /*!
      Returns true if the news is published false if not.
    */
    function isPublished()
    {
        $ret = false;
        if ( $this->IsPublished == 1 )
        {
            $ret = true;
        }
        return $ret;
    }


    /*!
      Sets the news name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the intro of the news.
    */
    function setIntro( $value )
    {
        $this->Intro = $value;
    }

    /*!
      Sets the url to the news.
    */
    function setURL( $value )
    {
        $this->URL = $value;
    }

    /*!
      Sets the origin of the news.
    */
    function setOrigin( $value )
    {
        $this->Origin = $value;
    }


    /*!
      Sets the keywords of the article.
    */
    function setKeyWords( $value )
    {
        $this->KeyWords = $value;
    }

    /*!
      Sets the original publishing date.

      It takes an eZDateTime object as argument.
    */
    function setOriginalPublishingDate( $time)
    {
        if ( is_a( $time, "eZDateTime" ) )
        {
            $this->OriginalPublishingDate = $time->timeStamp();

        }
    }

    /*!
     Sets the news to published or not.
    */
    function setIsPublished( $value )
    {
       if ( $value == true )
       {
           $this->IsPublished = 1;
       }
       else
       {
           $this->IsPublished = 0;
       }
    }


    /*!
      Returns the categrories an news is assigned to.

      The categories are returned as an array of eZNewsCategory objects.
    */
    function categories()
    {
        $db =& eZDB::globalDatabase();

        $ret = array();
        $db->array_query( $category_array, "SELECT * FROM eZNewsFeed_NewsCategoryLink WHERE NewsID='$this->ID'" );

        foreach ( $category_array as $category )
        {
            $ret[] = new eZNewsCategory( $category[$db->fieldName("CategoryID")] );
        }

        return $ret;
    }

    /*!
      Removes every category assignments from the current news.
    */
    function removeFromCategories()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();
        $ret[] = $db->query( "DELETE FROM eZNewsFeed_NewsCategoryLink WHERE NewsID='$this->ID'" );
        eZDB::finish( $ret, $db );

    }


    /*!
      Returns true if the product is assigned to the category given
      as argument. False if not.
     */
    function existsInCategory( $category )
    {
       $ret = false;
       if ( is_a( $category, "eZNewsCategory" ) )
       {
           $db =& eZDB::globalDatabase();
           $catID = $category->id();

           $db->array_query( $ret_array, "SELECT ID FROM eZNewsFeed_NewsCategoryLink
                                    WHERE NewsID='$this->ID' AND CategoryID='$catID'" );

           if ( count( $ret_array ) == 1 )
           {
               $ret = true;
           }
       }
       return $ret;
    }

    /*!
      Does a search in the news archive and returns the result as an array
      of eZNews objects.
    */
    function &search( $queryText, $fetchNonPublished=false, $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();

        if ( $fetchNonPublished == true )
        {
            $fetchText = "eZNewsFeed_News.IsPublished = '1' AND";
        }
        else
        {
            $fetchText = "";
        }

       $return_array = array();
       $news_array = array();

       $db->array_query( $news_array,
                    "SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name
                    FROM eZNewsFeed_News
                    WHERE
                    (
                    eZNewsFeed_News.Name LIKE '%$queryText%' OR
                    eZNewsFeed_News.Intro LIKE '%$queryText%'
                    )
                    ORDER BY PublishingDate", array( "Limit" => $limit, "Offset" => $offset ) );

       for ( $i=0; $i<count($news_array); $i++ )
       {
           $return_array[$i] = new eZNews( $news_array[$i][$db->fieldName("NewsID")], false );
       }

       return $return_array;
    }

    /*!
      Does a search in the news archive and returns the number of hits.
    */
    function &searchCount( $queryText, $fetchNonPublished=false )
    {
       $db =& eZDB::globalDatabase();

       if ( $fetchNonPublished == true )
       {
           $fetchText = "eZNewsFeed_News.IsPublished = '1' AND";
       }
       else
       {
           $fetchText = "";
       }

       $return_array = array();
       $news_array = array();

       $db->array_query( $news_array,
                    "SELECT count( eZNewsFeed_News.ID ) AS Count
                    FROM eZNewsFeed_News
                    WHERE
                    (
                    eZNewsFeed_News.Name LIKE '%$queryText%' OR
                    eZNewsFeed_News.Intro LIKE '%$queryText%'
                    )
                    " );


       return $news_array[0][$db->fieldName("Count")];
    }

    /*!
      Returns every news in every category sorted by time.
    */
    function newsList( $sortMode=time,
                       $fetchNonPublished=false,
                       $offset=0,
                       $limit=25 )
    {
       $db =& eZDB::globalDatabase();

       $OrderBy = "eZNewsFeed_News.PublishingDate DESC";
       switch( $sortMode )
       {
           case "alpha" :
           {
               $OrderBy = "eZNewsFeed_News.Name DESC";
           }
           break;
       }


       $return_array = array();
       $news_array = array();

       if ( $fetchNonPublished == true )
       {
           $db->array_query( $news_array, "
                    SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name
                    FROM eZNewsFeed_News
                    GROUP BY eZNewsFeed_News.ID ORDER BY $OrderBy", array( "Limit" => $limit, "Offset" => $offset ) );
       }
       else
       {
           $db->array_query( $news_array, "
                    SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name
                    FROM eZNewsFeed_News
                    WHERE
                    eZNewsFeed_NewsCategoryLink.NewsID = eZNewsFeed_News.ID
                    AND
                    eZNewsFeed_News.IsPublished = '1'
                    GROUP BY eZNewsFeed_News.ID ORDER BY $OrderBy
                    ", array( "Limit" => $limit, "Offset" => $offset ) );
       }

       for ( $i=0; $i<count($news_array); $i++ )
       {
           $return_array[$i] = new eZNews( $news_array[$i][$db->fieldName("NewsID")], false );
       }

       return $return_array;



    }

    var $ID;
    var $Name;
    var $Intro;
    var $PublishingDate;
    var $OriginalPublishingDate;
    var $IsPublished;
    var $KeyWords;
    var $Origin;
    var $URL;
}


?>
