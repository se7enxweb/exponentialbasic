<?php
//
// $Id: ezadclick.php 6555 2001-08-22 18:02:03Z br $
//
// Definition of eZAdClick class
//
// Created on: <25-Nov-2000 16:30:05 bf>
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

//!! eZAd
//! eZAdClick handles banner ad clicks.
/*!

  \sa eZAdCategory  eZAd
*/

/*!TODO

*/

// include_once( "classes/ezdb.php" );


class eZAdClick
{
    /*!
      Constructs a new eZAdClick object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        if ( $id != "" )
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
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZAd_Click" );
            $nextID = $db->nextID( "eZAd_Click", "ID" );
            $res[] = $db->query( "INSERT INTO eZAd_Click
                               ( ID,
		                         PageViewID,
		                         AdID,
                                 ClickPrice )
                               VALUES
                               ( '$nextID',
		                         '$this->PageViewID',
		                         '$this->AdID',
                                 '$this->ClickPrice' )" );

			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZAd_Click SET
		                         PageViewID='$this->PageViewID',
		                         AdID='$this->AdID',
                                 ClickPrice='$this->ClickPrice'
                                 WHERE ID='$this->ID'
                                 " );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $ad_array, "SELECT * FROM eZAd_Ad WHERE ID='$id'" );
            if ( count( $ad_array ) > 1 )
            {
                die( "Error: Ad's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $ad_array ) == 1 )
            {
                $this->ID =& $ad_array[0][$db->fieldName( "ID" )];
                $this->PageViewID =& $ad_array[0][$db->fieldName( "PageViewID" )];
                $this->ClickPrice =& $ad_array[0][$db->fieldName( "ClickPrice" )];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZAd object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( isset( $this->ID ) )
        {
            $res[] = $db->query( "DELETE FROM eZAd_Click WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );

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
      Returns the click price.
    */
    function &price()
    {
       return $this->Price;
    }

    /*!
      Returns the click date and time as a eZDateTime object.
    */
    function &clickTime()
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->ClickTime );

       return $dateTime;
    }

    /*!
      Sets the click IP.
    */
    function setPageViewID( $value )
    {
       $this->PageViewID = $value;
    }

    /*!
      Sets the click price.
    */
    function setPrice( $value )
    {
       $this->ClickPrice = $value;
    }

    /*!
      Sets the ad ID if a valid eZAd object is given as argument.
    */
    function setAd( $ad )
    {
       if ( is_a( $ad, "eZAd" ) )
       {
           $this->AdID = $ad->id();
       }
    }

    var $ID;
    var $AdID;
    var $PageViewID;
    var $ClickTime;
    var $ViewPrice;

}

?>