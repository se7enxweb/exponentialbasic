<?php
//
// $Id: ezbulkmailusercategorysettings.php 7122 2001-09-10 11:38:19Z ce $
//
// Definition of eZBulkMailCategorySettings class
//
// Created on: <17-Apr-2001 11:17:57 fh>
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

//!! eZBulkMail
//! eZBulkMailCategorySettings
/*!
  Example code:
  \code
*/

/*!TODO
*/

// include_once( "classes/ezdb.php" );
// include_once( "ezbulkmail/classes/ezbulkmail.php" );
// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

class eZBulkMailUserCategorySettings
{
    /*!
    */
    function __construct( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZBulkMailCategorySettings object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZBulkMail_UserCategorySettings" );
            $nextID = $db->nextID( "eZBulkMail_UserCategorySettings", "ID" );

            $result = $db->query( "INSERT INTO eZBulkMail_UserCategorySettings
                                ( ID, CategoryID, UserID, Delay )
                                VALUES
                                ( '$nextID',
                                  '$this->CategoryID',
                                  '$this->UserID',
                                  '$this->Delay'
                                ) " );
			      $this->ID = $nextID;
        }
        else
        {
            $result = $db->query( "UPDATE eZBulkMail_UserCategorySettings SET
		                         CategoryID='$this->CategoryID',
                                 UserID='$this->UserID',
                                 Delay='$this->Delay'
                                 WHERE ID='$this->ID'" );
        }

        $db->unlock();
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes a eZBulkMailCategorySettings object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if( $id == -1 )
            $id = $this->ID;

        $result = $db->query( "DELETE FROM eZBulkMail_UserCategorySettings WHERE ID='$id'" );

        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
     }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZBulkMail_UserCategorySettings WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][$db->fieldName( "ID" )];
                $this->CategoryID = $category_array[0][$db->fieldName( "CategoryID" )];
                $this->UserID = $category_array[0][$db->fieldName( "UserID" )];
                $this->Delay = $category_array[0][$db->fieldName( "Delay" )];
            }
        }
    }

    /*!
      Returns the ID of this object.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the category of this object.
    */
    function category()
    {
        return new eZBulkMailCategorySettings( $this->CategoryID );
    }

    /*!
      Returns the address of this object.
    */
    function subscriptionAddress()
    {
        return new eZBulkMailUserSubscripter( $this->UserID );
    }

    /*!
      1 = hour
      2 = day
      3 = week
      4 = month
    */
    function delay()
    {
        return $this->Delay;
    }

    /*!
      Sets the category of this setting.
    */
    function setCategory( $value )
    {
        if ( is_a ( $value, "eZBulkMailCategory" ) )
            $this->CategoryID = $value->id();
        elseif ( is_numeric( $value ) )
            $this->CategoryID = $value;
    }

    /*!
      Sets the address of this setting.
    */
    function setAddress( $value )
    {
        if ( is_a( $value, "eZBulkMailUserSubscripter" ) )
            $this->UserID = $value->id();
        elseif ( is_numeric( $value ) )
            $this->UserID = $value;
    }

    /*!
      1 = hour
      2 = day
      3 = week
      4 = monthgorysettings Object ( [ID] => 1 [CategoryID] => 1 [UserID] => 1 [Delay] => 1 )

Subscripti
    */
    function setDelay( $value )
    {
        $this->Delay = $value;
    }

    var $ID;
    var $CategoryID;
    var $UserID;
    var $Delay;
}

?>