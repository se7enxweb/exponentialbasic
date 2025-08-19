<?php
// 
// $Id: eztipclick.php,v 1.9 2001/08/22 18:02:03 br Exp $
//
// Definition of eZTipClick class
//
// Created on: <25-Nov-2000 16:30:05 bf>
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

//!! eZTip
//! eZTipClick handles banner ad clicks.
/*!

  \sa eZTipCategory  eZTip
*/

/*!TODO

*/

// include_once( "classes/ezdb.php" );


class eZTipClick
{
    /*!
      Constructs a new eZTipClick object.

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
            $db->lock( "eZTip_Click" );
            $nextID = $db->nextID( "eZTip_Click", "ID" );
            $res[] = $db->query( "INSERT INTO eZTip_Click
                               ( ID,
		                         PageViewID,
		                         TipID )
                               VALUES
                               ( '$nextID',
		                         '$this->PageViewID',
		                         '$this->TipID' )" );

			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTip_Click SET
		                         PageViewID='$this->PageViewID',
		                         TipID='$this->TipID'
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
            $db->array_query( $tip_array, "SELECT * FROM eZTip_Tip WHERE ID='$id'" );
            if ( count( $tip_array ) > 1 )
            {
                die( "Error: Tip's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $tip_array ) == 1 )
            {
                $this->ID =& $tip_array[0][$db->fieldName( "ID" )];
                $this->PageViewID =& $tip_array[0][$db->fieldName( "PageViewID" )];
                $this->ClickPrice =& $tip_array[0][$db->fieldName( "ClickPrice" )];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZTip object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( isset( $this->ID ) )
        {
            $res[] = $db->query( "DELETE FROM eZTip_Click WHERE ID='$this->ID'" );
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
      Sets the ad ID if a valid eZTip object is given as argument.
    */
    function setTip( $tip )
    {
       if ( get_class( $tip ) == "eztip" )
       {
           $this->TipID = $tip->id();
       }
    }

    var $ID;
    var $TipID;
    var $PageViewID;
    var $ClickTime;

}

?>