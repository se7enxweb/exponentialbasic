<?php
//
// $Id: ezorderstatus.php 6394 2001-08-08 12:34:57Z jhe $
//
// Definition of eZOrderStatus class
//
// Created on: <02-Oct-2000 15:06:32 bf>
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
//!! eZTrade
//! eZOrderStatus handles order status.
/*!

  \sa eZOrder
*/

/*!TODO
  Add documentation.
*/

include_once( "classes/ezdb.php" );

include_once( "classes/ezdatetime.php" );

class eZOrderStatus
{
    /*!
      Constructs a new eZOrder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZOrderStatus( $id="" )
    {

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a order status  to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Comment = $db->escapeString( $this->Comment );
        $timestamp = eZDateTime::timeStamp( true );
        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZTrade_OrderStatus" );
            $nextID = $db->nextID( "eZTrade_OrderStatus", "ID" );
            $ret[] = $db->query( "INSERT INTO eZTrade_OrderStatus
                                 (ID,
                                  StatusID,
                                  Altered,
		                          AdminID,
		                          Comment,
		                          OrderID)
                                 VALUES
                                 ('$nextID',
		                          '$this->StatusID',
                                  '$timestamp',
		                          '$this->AdminID',
		                          '$this->Comment',
		                          '$this->OrderID')" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_OrderStatus SET
		                          StatusID='$this->StatusID',
                                  Altered='$timestamp',
		                          AdminID='$this->AdminID',
		                          Comment='$this->Comment',
		                          OrderID='$this->OrderID'
                                  WHERE ID='$this->ID'
                                 " );
        }
        eZDB::finish( $ret, $db );
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
            $db->array_query( $order_array, "SELECT * FROM eZTrade_OrderStatus WHERE ID='$id'" );
            if ( count( $order_array ) > 1 )
            {
                die( "Error: Order's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $order_array ) == 1 )
            {
                $this->ID =& $order_array[0][$db->fieldName("ID")];
                $this->StatusID =& $order_array[0][$db->fieldName("StatusID")];
                $this->Altered =& $order_array[0][$db->fieldName("Altered")];
                $this->AdminID =& $order_array[0][$db->fieldName("AdminID")];
                $this->OrderID =& $order_array[0][$db->fieldName("OrderID")];
                $this->Comment =& $order_array[0][$db->fieldName("Comment")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the altered timestamp as a eZDateTime object.
    */
    function altered( )
    {
       $dateTime = new eZDateTime();

       $dateTime->setTimeStamp( $this->Altered );

       return $dateTime;
    }

    /*!
      Returns the status type as a eZOrderStatusType object.
    */
    function type( )
    {
       $ret = new eZOrderStatusType( $this->StatusID );

       return $ret;
    }

    /*!
      Returns the comment.
    */
    function comment()
    {
       return $this->Comment;
    }

    /*!
      Returns the admin user.
    */
    function admin()
    {
        $ret = new eZUser( $this->AdminID );
        return $ret;
    }


    /*!
      Sets status type.
    */
    function setType( $type )
    {
       if ( is_a( $type, "eZOrderStatusType" ) )
       {
           $this->StatusID = $type->id();
       }
    }

    /*!
      Sets the admin.
    */
    function setAdmin( $user )
    {
       if ( is_a( $user, "eZUser" ) )
       {
           $this->AdminID = $user->id();
       }
    }

    /*!
      Sets the order ID.
    */
    function setOrderID( $order )
    {
       $this->OrderID = $order;
    }

    /*!
      Sets the comment.
    */
    function setComment( $value )
    {
       $this->Comment = $value;
    }

    var $ID;
    var $StatusID;
    var $Altered;
    var $AdminID;
    var $OrderID;
    var $Comment;

}

?>
