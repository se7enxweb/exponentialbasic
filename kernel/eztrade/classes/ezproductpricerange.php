<?php
// 
// $Id: ezproductpricerange.php 7848 2001-10-15 11:32:18Z ce $
//
// eZProductPricerange class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <07-Sep-2001 12:38:17 ce>
//
// This source file is part of Exponential Basic, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

//!! ezquizgame
//! ezquizgame documentation.
/*!

  Example code:
  \code
  $pricerange = new eZProductPricerange(); // Create a new eZProductPricerange object.
  $product = new eZProduct( 4 ); // Get a product with id 4
  $pricerange->setProduct( $product ); // Sets the product object.
  $pricerange->setPrice( 100 ); // Set the price.
  $pricerange->store(); // Stores the object to the database.
  \endcode

*/

// include_once( "classes/ezdate.php" );
// include_once( "eztrade/classes/ezorder.php" );
	      
class eZProductPriceRange
{

    /*!
      Constructs a new eZProductPriceRange object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
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
      Stores a eZProductPriceRange object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_ProductPriceRange" );
            $nextID = $db->nextID( "eZTrade_ProductPriceRange", "ID" );            
            $timeStamp =& (new eZDateTime())->timeStamp( true );
            $password = md5( $this->Password );

            $res = $db->query( "INSERT INTO eZTrade_ProductPriceRange
                      ( ID, Max, Min, ProductID )
                      VALUES
                      ( '$nextID',
                        '$this->Max',
                        '$this->Min',
                        '$this->ProductID'
                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZTrade_ProductPriceRange SET
                                     Min='$this->Min',
                                     Max='$this->Max',
                                     ProductID='$this->ProductID'
                                     WHERE ID='$this->ID'" );
        }
        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZProductPriceRange object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $res = $db->query( "DELETE FROM eZTrade_ProductPriceRange WHERE ID='$this->ID'" );
    
        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $productArray, "SELECT * FROM eZTrade_ProductPriceRange WHERE ID='$id'",
                              0, 1 );
            if( count( $productArray ) == 1 )
            {
                $this->fill( $productArray[0] );
                $ret = true;
            }
            elseif( count( $productArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$productArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $productArray[$db->fieldName( "ID" )];
        $this->Min = $productArray[$db->fieldName( "Min" )];
        $this->Max = $productArray[$db->fieldName( "Max" )];
        $this->ProductID = $productArray[$db->fieldName( "ProductID" )];
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZProductPriceRange objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $productArray = array();

        if ( $limit == false )
        {
            $db->array_query( $productArray, "SELECT ID
                                           FROM eZTrade_ProductPriceRange
                                           " );

        }
        else
        {
            $db->array_query( $productArray, "SELECT ID
                                           FROM eZTrade_ProductPriceRange
                                           ", array( "Limit" => $limit, "Offset" => $offset ) );
        }

        for ( $i=0; $i < count($productArray); $i++ )
        {
            $returnArray[$i] = new eZProductPriceRange( $productArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZTrade_ProductPriceRange" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the minimin price of the prince range.
    */
    function &min()
    {
        return $this->Min;
    }

    /*!
      Returns the maximum price of the prince range.
    */
    function &max()
    {
        return $this->Max;
    }

    /*!
      Sets the minimin price of the prince range.
    */
    function setMin( $value )
    {
        $this->Min = $value;
    }

    /*!
      Returns the maximum price of the prince range.
    */
    function setMax( $value )
    {
        $this->Max = $value;
    }

    /*!
      Sets the Product for this object.
    */
    function setProduct( $value )
    {
        if ( get_class ( $value ) )
        {
            $this->ProductID = $value->id();
        }
        else if ( is_numeric ( $value ) )
        {
            $this->ProductID = $value;
        }
    }

    /*!
      Returns the product of this object.
    */
    function product( $asObjcet=true )
    {
        if ( $asObject )
        {
            $ret = new eZProduct( $this->ProductID );
        }
        else
        {
            $ret = $this->ProductID;
        }
        return $ret;
    }

    var $ID;
    var $Min;
    var $Max;
    var $ProductID;
}

?>