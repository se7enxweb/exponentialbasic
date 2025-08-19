<?php
// 
// $Id: ezonlinetype.php 6482 2001-08-17 12:29:18Z jhe $
//
// Definition of eZOnline class
//
// Created on: <09-Nov-2000 18:44:38 ce>
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

//
//!! eZAddress
//! eZOnlineType handles online types.
/*!

  Example code:
  \code
  $onlinetype = new eZOnlineType();
  $onlinetype->setName( "/a/path/here" );
  $onlinetype->setURLPrefix( "http://" ) // Sets the url prefix to be http
  $onlinetype->store(); // Store or updates to the database.
  \code
  \sa eZOnlineType eZCompany eZPerson eZOnline eZPhone eZOnline
*/

//  // include_once( "ezaddress/classes/ezperson.php" );
//  // include_once( "ezaddress/classes/ezcompany.php" );

class eZOnlineType
{
    /*!
      Constructs a new eZAddressType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id= -1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }
    
    /*!
      Stores a eZOnline object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZAddress_OnlineType" );
            $nextID = $db->nextID( "eZAddress_OnlineType", "ID" );

            $db->query_single( $qry, "SELECT ListOrder FROM eZAddress_OnlineType ORDER BY ListOrder DESC", array( "Limit" => "1" ) );
            $listorder = $qry[$db->fieldName("ListOrder")] + 1;
            $this->ListOrder = $listorder;

            $result = $db->query( "INSERT INTO eZAddress_OnlineType
                      ( ID, Name, ListOrder, URLPrefix, PrefixLink, PrefixVisual )
                      VALUES ( '$nextID',
                               '$name',
                               '$this->ListOrder',
                               '$this->URLPrefix',
                               '$this->PrefixLink',
                               '$this->PrefixVisual') " );

			$this->ID = $nextID;

        }
        else
        {
            $result = $db->query( "UPDATE eZAddress_OnlineType SET
                                     Name='$name',
                                     ListOrder='$this->ListOrder',
                                     URLPrefix='$this->URLPrefix',
                                     PrefixLink='$this->PrefixLink',
                                     PrefixVisual='$this->PrefixVisual'
                                     WHERE ID='$this->ID'" );
        }

        $db->unlock();
    
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();

        return $dbError;
    }

    /*
      Deletes from the database where id = $this->ID,
     */
    function delete( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;

        $db->begin( );
        $result = $db->query( "UPDATE eZAddress_OnlineType SET Removed=1 WHERE ID='$id'" );
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }
    
    /*
      Fetches out a online type where id = $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $online_type_array, "SELECT * FROM eZAddress_OnlineType WHERE ID='$id'",
                              0, 1 );
            if ( count( $online_type_array ) == 1 )
            {
                $this->fill( $online_type_array[0] );
            }
            else
            {
                $this->ID = "";
            }
        }
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$online_type_array )
    {
        $db =& eZDB::globalDatabase();
                
        $this->ID = $online_type_array[$db->fieldName( "ID" )];
        $this->Name = $online_type_array[$db->fieldName( "Name" )];
        $this->ListOrder = $online_type_array[$db->fieldName( "ListOrder" )];
        $this->URLPrefix = $online_type_array[$db->fieldName( "URLPrefix" )];
        $this->PrefixLink = $online_type_array[$db->fieldName( "PrefixLink" )];
        $this->PrefixVisual = $online_type_array[$db->fieldName( "PrefixVisual" )];
    }

    /*
      Fetches out all the online types that is stored in the database.
    */
    static public function &getAll( $as_object = true )
    {
               
        $db =& eZDB::globalDatabase();
        $online_type_array = 0;

        $online_type_array = array();
        $return_array = array();

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $online_type_array, "SELECT $select FROM eZAddress_OnlineType
                                               WHERE Removed=0
                                               ORDER BY ListOrder" );

        if ( $as_object )
        {
            foreach ( $online_type_array as $onlineTypeItem )
            {
                $return_array[] = new eZOnlineType( $onlineTypeItem );
            }
        }
        else
        {
            foreach ( $online_type_array as $onlineTypeItem )
            {
                $return_array[] = $onlineTypeItem[ $db->fieldName( "ID" ) ];
            }
        }
    
        return $return_array;
    }

    /*!
      Sets the name of the object.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the name of the object.
    */
    function setURLPrefix( $value )
    {
        $this->URLPrefix = $value;
    }

    /*!
      Sets whether the prefix must always be applied for links.
    */
    function setPrefixLink( $value )
    {
        if ( $value )
            $this->PrefixLink = 1;
        else
            $this->PrefixLink = 0;
    }

    /*!
      Sets whether the prefix must always be applied for visuals (the visual part of a link).
    */
    function setPrefixVisual( $value )
    {
        if ( $value )
            $this->PrefixVisual = 1;
        else
            $this->PrefixVisual = 0;
    }

    /*!
      Returns the name of the object.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the URL prefix of the object.
    */
    function urlPrefix()
    {
        return $this->URLPrefix;
    }

    /*!
      Returns true if the prefix must always be applied for links.
    */
    function prefixLink()
    {
        return $this->PrefixLink == 1;
    }

    /*!
      Returns true if the prefix must always be applied for visuals (the visual part of a link).
    */
    function prefixVisual()
    {
        return $this->PrefixVisual == 1;
    }

    /*!
      Returns the id of the object.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns the number of external items using this item.
    */

    function &count()
    {
        $db =& eZDB::globalDatabase();
//          $db->array_query( $person_qry,  "SELECT count( Pe.ID ) as Count
//                                           FROM eZContact_Person AS Pe, eZContact_PersonOnlineDict AS POD,
//                                                eZAddress_Online AS Online, eZAddress_OnlineType AS OT
//                                           WHERE Pe.ID = POD.PersonID AND POD.OnlineID = Online.ID AND Online.OnlineTypeID = OT.ID AND OnlineTypeID='$this->ID'" );
//          $db->array_query( $company_qry, "SELECT count( Co.ID ) as Count
//                                           FROM eZContact_Company AS Co, eZContact_CompanyOnlineDict AS COD,
//                                                eZAddress_Online AS Online, eZAddress_OnlineType AS OT
//                                           WHERE Co.ID = COD.CompanyID AND COD.OnlineID = Online.ID AND Online.OnlineTypeID = OT.ID AND OnlineTypeID='$this->ID'" );
        $db->array_query( $qry, "SELECT count( Online.ID ) as Count
                                 FROM eZAddress_Online AS Online, eZAddress_OnlineType AS OT
                                 WHERE Online.OnlineTypeID = OT.ID AND OnlineTypeID='$this->ID'" );
        $cnt = 0;
//          if ( count( $company_qry ) > 0 )
//              $cnt += $company_qry[0]["Count"];
//          if ( count( $person_qry ) > 0 )
//              $cnt += $person_qry[0]["Count"];
        if ( count( $qry ) > 0 )
            $cnt += $qry[0][ $db->fieldName( "Count" ) ];
        return $cnt;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */

    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_OnlineType
                                  WHERE Removed=0 AND ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC", array( "Limit" => "1" ) );
        $listorder = $qry[$db->fieldName( "ListOrder" )];
        $listid = $qry[$db->fieldName( "ID" )];

        $db->begin();
        $res[] = $db->query( "UPDATE eZAddress_OnlineType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZAddress_OnlineType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_OnlineType
                                  WHERE Removed=0 AND ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC", array( "Limit" => "1" ) );
        $listorder = $qry[$db->fieldName( "ListOrder" )];
        $listid = $qry[$db->fieldName( "ID" )];

        $db->begin();
        $res[] = $db->query( "UPDATE eZAddress_OnlineType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZAddress_OnlineType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $res, $db );
    }

    var $ID;
    var $Name;
    var $ListOrder;
    var $URLPrefix;
    var $PrefixLink;
    var $PrefixVisual;
}

?>