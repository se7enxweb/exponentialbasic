<?php
//
// $Id: ezsourcesite.php 6484 2001-08-17 13:36:01Z jhe $
//
// Definition of eZSourceSite class
//
// Created on: <19-Nov-2000 11:26:48 bf>
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
//! eZSourceSite handles news feed source sites' import parameters.
/*!
  \sa eZNewsCategory eZNews
*/

/*!TODO

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZSourceSite
{
    /*!
      Constructs a new eZSourceSite object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        // default value
        $this->IsPublished = "0";

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores an object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $login = $db->escapeString( $this->Login );
        $password = $db->escapeString( $this->Password );
        $url = $db->escapeString( $this->URL );

        $ret = false;
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZNewsFeed_SourceSite" );
            $nextID = $db->nextID( "eZNewsFeed_SourceSite", "ID" );
            $ret[] = $db->query( "INSERT INTO eZNewsFeed_SourceSite
		                       ( ID,
                                 Name,
                                 URL,
                                 Login,
                                 Password,
                                 CategoryID,
                                 IsActive,
                                 Decoder,
                                 AutoPublish )
                               VALUES
                               ( '$nextID',
                                 '$name',
                                 '$url',
                                 '$login',
                                 '$password',
                                 '$this->CategoryID',
                                 '$this->IsActive',
                                 '$this->Decoder',
                                 '$this->AutoPublish' )" );

			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZNewsFeed_SourceSite SET
		                         Name='$name',
                                 URL='$url',
                                 Login='$login',
                                 Password='$password',
                                 CategoryID='$this->CategoryID',
                                 Decoder='$this->Decoder',
                                 IsActive='$this->IsActive',
                                 AutoPublish='$this->AutoPublish'
                                 WHERE ID='$this->ID'
                                 " );
        }
        eZDB::finish( $ret, $db );
        return in_array( false, $ret );
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
            $db->array_query( $news_array, "SELECT * FROM eZNewsFeed_SourceSite WHERE ID='$id'" );
            if ( count( $news_array ) > 1 )
            {
                die( "Error: News's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $news_array ) == 1 )
            {
                $this->ID =& $news_array[0][$db->fieldName("ID")];
                $this->Name =& $news_array[0][$db->fieldName("Name")];
                $this->URL =& $news_array[0][$db->fieldName("URL")];
                $this->Login =& $news_array[0][$db->fieldName("Login")];
                $this->Password =& $news_array[0][$db->fieldName("Password")];
                $this->CategoryID =& $news_array[0][$db->fieldName("CategoryID")];
                $this->Decoder =& $news_array[0][$db->fieldName("Decoder")];
                $this->IsActive =& $news_array[0][$db->fieldName("IsActive")];
                $this->AutoPublish =& $news_array[0][$db->fieldName("AutoPublish")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZSourceSite object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        if( $id == -1 )
            $id = $this->ID;

        $db->begin();

        $ret[] = $db->query( "DELETE FROM eZNewsFeed_SourceSite WHERE ID='$id'" );

        eZDB::finish( $ret, $db );

        return in_array( false, $ret );
    }

    /*!
      Returns every source site objects in the database.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $source_site_array = array();

        $db->array_query( $source_site_array, "SELECT ID FROM eZNewsFeed_SourceSite ORDER BY Name" );

        for ( $i=0; $i<count($source_site_array); $i++ )
        {
            $return_array[$i] = new eZSourceSite( $source_site_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;

    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the source site name.
    */
    function &name()
    {
      return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the url to the source site.
    */
    function &url()
    {
       return $this->URL;
    }

    /*!
      Returns the login to the source site.
    */
    function &login()
    {
       return $this->Login;
    }

    /*!
      Returns the password to the source site.
    */
    function &password()
    {
       return $this->Password;
    }

    /*!
      Returns the decoder to use on the source site.
    */
    function &decoder()
    {
       return $this->Decoder;
    }

    /*!
      Returns the category to import the news to.
    */
    function &category()
    {
       $ret = new eZNewsCategory( $this->CategoryID );

       return $ret;
    }

    /*!
      Returns the isActive, return true if succsessfull.
    */
    function &isActive()
    {
       $ret = false;
       if ( $this->IsActive == "1" )
           $ret = true;

       return $ret;
    }

    /*!
      Sets the source site name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the url of the source site.
    */
    function setURL( $value )
    {
       $this->URL = $value;
    }

    /*!
      Sets the login to the source site.
    */
    function setLogin( $value )
    {
       $this->Login = $value;
    }

    /*!
      Sets the password to the news site.
    */
    function setPassword( $value )
    {
       $this->Password = $value;
    }

    /*!
      Sets the category to import news to.
    */
    function setCategory( $category )
    {
       if ( is_a( $category, "eZNewsCategory" ) )
       {
           $this->CategoryID = $category->id();
       }
    }

    /*!
      Sets the decoder to use.
    */
    function setDecoder( $value )
    {
       $this->Decoder = $value;
    }

    /*!
      Sets the IsActive.
    */
    function setIsActive( $value )
    {
        if ( $value == true )
        {
            $this->IsActive = 1;
        }
        else
        {
            $this->IsActive = 0;
        }
    }

    /*!
      Sets the source to automatically publish new fetched news or not.
    */
    function setAutoPublish( $value )
    {
        if ( $value == true )
        {
            $this->AutoPublish = 1;
        }
        else
        {
            $this->AutoPublish = 0;
        }
    }

    /*!
      Returns true if the news from this site is automatically
      published.
    */
    function autoPublish()
    {
       if ( $this->AutoPublish == 1 )
           $ret = true;
       else
           $ret = false;

       return $ret;
    }


    var $ID;
    var $Name;
    var $URL;
    var $Login;
    var $Password;
    var $CategoryID;
    var $Decoder;
    var $IsActive;

    /// bool represented as an int. For automatically publishing of articles.
    var $AutoPublish;
    var $IsPublished;
}


?>