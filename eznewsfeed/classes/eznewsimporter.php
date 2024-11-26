<?php
//
// $Id: eznewsimporter.php 8080 2001-10-29 16:49:39Z bf $
//
// Definition of eZNewsImporter class
//
// Created on: <13-Nov-2000 16:56:48 bf>
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
//! eZNewsImporter handles importing of news bullets from other sites.
/*!
  Example code:
  \sa eZNewsCategory

*/

/*!TODO

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZNewsImporter
{
    /*!
      Create a new importer with the given decoder and site. Login and
      password are default not used.
    */
    function __construct( $decoder, $site, $category,  $login="", $password="", $autoPublish=false )
    {
        $this->Site = $site;
        $this->Decoder = $decoder;
        $this->Login = $login;
        $this->Password = $password;
        $this->AutoPublish = $autoPublish;

        if ( is_a( $category, "eZNewsCategory" ) )
        {
            $this->CategoryID = $category->id();
        }
    }

    /*
      \static
      This funcion returns an array of strings with the avaliable decoders.
      NOTE: When you create a new decoder you must add it to this function or the decoder will not be avaliable for users to select.
     */
    static public function listDecoders( )
    {
        $list = array( "rdf", "rss", "backslash" );
        return $list;
    }

    /*!
      Imports news from the given site.
    */
    function importNews( )
    {
        $category = new eZNewsCategory( $this->CategoryID );
        $this->Decoder = trim( $this->Decoder );
        switch ( $this->Decoder )
          {
            case "nyheter.no" :
            {
                include_once( "eznewsfeed/classes/eznyheternoimporter.php" );

                $importer = new eZNyheterNOImporter( $this->Site, $this->Login, $this->Password );

                $importer->news();
                $newsList =& $importer->news();
            }
            break;

            case "rdf" :
            {
                include_once( "eznewsfeed/classes/ezrdfimporter.php" );

                $importer = new eZRDFImporter( $this->Site, $this->Login, $this->Password );
                $newsList =& $importer->news();
            }
            break;

            case "rss" :
            {
                include_once( "eznewsfeed/classes/ezrssimporter.php" );

                $importer = new eZRSSImporter( $this->Site, $this->Login, $this->Password );
                $newsList =& $importer->news();
            }
            break;

            case "backslash" :
            {
                include_once( "eznewsfeed/classes/ezbackslashimporter.php" );

                $importer = new eZBackslashImporter( $this->Site, $this->Login, $this->Password );
                $newsList =& $importer->news();
            }
            break;
          }

          if( isset( $newsList ) )
          {
              foreach ( $newsList as $newsItem )
              {
                  if ( $newsItem->store() == true )
                  {
                      if ( $this->AutoPublish == true )
                      {
                          $newsItem->setIsPublished( 1 );
                      }
                      else
                      {
                          $newsItem->setIsPublished( 0 );
                      }
                      $newsItem->store();

                      $category->addNews( $newsItem );
                      print( "storing: -" .$newsItem->name() . "<br>");
                  }
                  else
                  {
                      print( "already stored: -" .$newsItem->name() . "<br>");
                  }
              }
          }
    }

    var $Decoder;
    var $Site;
    var $Login;
    var $Password;
    var $CategoryID;
    var $AutoPublish;
}

?>