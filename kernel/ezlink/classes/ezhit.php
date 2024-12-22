<?php
// 
// $Id: ezhit.php 9860 2003-07-10 08:17:50Z br $
//
// Definition of eZHit class
//
// Created on: <15-Sep-2000 14:40:06 bf>
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

//!! eZLink
//! The eZHit class handles URL hits. 
/*!
  The eZHit class stores hits and information about hits to the database.

   \sa eZLink eZLinkgroup eZQuery
*/

// include_once( "classes/ezdb.php" );

class eZHit
{
    /*!
      Constructor
    */
    function __construct( $id=-1 )
    {
        $this->Time = 0;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Store to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        // lock the table
        $db->lock( "eZLink_Hit" );

        $nextID = $db->nextID( "eZLink_Hit", "ID" );

        $res[] = $db->query( "INSERT INTO eZLink_Hit
                                        ( RemoteIP, ID, Link, Time)
                                        VALUES
                                        ('$this->RemoteIP','$nextID','$this->Link','$this->Time')" );
        $db->unlock();

        eZDB::finish( $res, $db );
    }

    /*!
      Update to the database
    */
    function update()
    {
        $db =& eZDB::globalDatabase();
        
        $db->query( "UPDATE eZLink_Hit SET
				RemoteIP='$this->RemoteIP',
                Link='$this->Link',
                WHERE ID='$this->ID'" );
    }

    /*!
      Delete from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZLink_Hit WHERE ID='$ID'" );
    }

    /*!
      Get out the count for one link.
     */

    function &getLinkHits( $id )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $hit_array, "SELECT COUNT(ID) as Count FROM eZLink_Hit WHERE Link='$id'" );        
        $count = $hit_array[$db->fieldName( "Count" )];

        return $count;
    }
    
    /*!
      Get out the count for one link.
     */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $hit_array, "SELECT * FROM eZLink_Hit WHERE ID='$id'" );

        return count( $hit_array );
    }

    /*!
      Set the linkid.
    */
    function setLink( $value )
    {
        $this->Link = ( $value );
    }

    /*!
      Set the remote ip.
    */
    function setRemoteIP( $value )
    {
        $this->RemoteIP = ( $value );
    }

    /*!
      Set the time.
    */
    function setTime( $timestsamp )
    {
        $this->Time = $timestsamp;
        return $this->Time;
    }

    /*!
      Return the id of the hit.
    */
    function id()
    {
        return $this->ID;
    }

    
    /*!
      Return linkid.
    */
    function link()
    {
        return $this->Link;
    }

    /*!
      Return the time.
    */
    function time()
    {
        return $this->Time;
    }

    /*!
      Return the ip to the link hit.
    */
    function remoteIP( )
    {
        return $this->RemoteIP;
    }
    
      
    var $ID;
    var $Link;
    var $Time;
    var $RemoteIP;    
}

?>