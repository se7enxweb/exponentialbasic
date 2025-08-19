<?php
// 
// $Id: ezimagevariationgroup.php 9854 2003-06-12 14:33:53Z br $
//
// Definition of eZImageVariationGroup class
//
// Created on: <21-Sep-2000 17:28:47 bf>
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
//!! eZImageCatalogue
//! The eZImageVariationGroup class hadles images variation groups.
/*!

  \sa eZImage eZImageVariation
*/

// include_once( "classes/ezdb.php" );

class eZImageVariationGroup
{
    /*!
      Constructs a new eZImageVariationGroup object.
    */
    function __construct( $id="" )
    {
        $this->ImageVariationGroup = 0;
        $this ->ID = 0;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZImageVariationGroup object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $db->lock( "eZImageCatalogue_ImageVariationGroup" );

        $this->ID = $db->nextID( "eZImageCatalogue_ImageVariationGroup", "ID" );
        
        $res = $db->query( "INSERT INTO eZImageCatalogue_ImageVariationGroup 
                                 ( ID, Width, Height ) VALUES ( '$this->ID', '$this->Width', '$this->Height' )" );

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $image_variation_array, "SELECT * FROM eZImageCatalogue_ImageVariationGroup WHERE ID='$id'" );
            if ( count( $image_variation_array ) > 1 )
            {
                die( "Error: ImageVariations's with the same ID was found in the database. This should not happen." );
            }
            else if( count( $image_variation_array ) == 1 )
            {
                $this->ID = $image_variation_array[0][$db->fieldName("ID")];
                $this->Width = $image_variation_array[0][$db->fieldName("Width")];
                $this->Height = $image_variation_array[0][$db->fieldName("Height")];
            }
        }
    }

    /*!
      Returns the ID the the group if there exists a image group with the requested size, false if not.
    */
    function groupExists( $width, $height )
    {
        $db =& eZDB::globalDatabase();
        
        $ret = false;
        
        $query = ( "SELECT * FROM eZImageCatalogue_ImageVariationGroup WHERE Width='$width' AND Height='$height'" );

        $db->array_query( $group_array, $query, array( "Limit" => 1, "Offset" => 0 ) );
                                                     
        if ( count( $group_array ) == 1 )
        {
            $ret = $group_array[0][$db->fieldName("ID")];
        }

        return $ret;
    }

    /*!
      Returns the id of the image group.
    */
    function id()
    {
       return $this->ID;
    }
    
    /*!
      Returns the width of the image group.
    */
    function width()
    {
       return $this->Width;
    }

    /*!
      Returns the height of the image group.
    */
    function height()
    {
       return $this->Height;
    }

    /*!
      Sets the width of the image group.
    */
    function setWidth( $value )
    {
       $this->Width = $value;
    }

    /*!
      Sets the height of the image group.
    */
    function setHeight( $value )
    {
       $this->Height = $value;
    }

    var $ID;
    var $Width;
    var $Height;
}

?>