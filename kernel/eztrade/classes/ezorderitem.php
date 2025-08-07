<?php
//
// $Id: ezorderitem.php 7299 2001-09-17 11:34:36Z pkej $
//
// Definition of eZOrderItem class
//
// Created on: <29-Sep-2000 10:27:55 bf>
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
//! eZOrderItem handles order items.
/*!

    All prices saved with this object is without VAT. All prices are for _one_
    product only. Remember to multiply correctly with the count.

  \sa eZOrder eZOrderOptionValue

*/

/*!TODO

*/

// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezorderoptionvalue.php" );
// include_once( "eztrade/classes/ezproduct.php" );

class eZOrderItem
{
    /*!
      Constructs a new eZOrderItem object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        $this->ExpiryDate = 0;
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a order to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        if ( !isset( $this->ID ) )
        {

            $db->lock( "eZTrade_OrderItem" );
            $nextID = $db->nextID( "eZTrade_OrderItem", "ID");
            $ret[] = $db->query( "INSERT INTO eZTrade_OrderItem
                                  ( ID,
	                                OrderID,
		                            Count,
		                            Price,
		                            VAT,
		                            ProductID,
                                    ExpiryDate )
                                  VALUES
                                  ( '$nextID',
		                            '$this->OrderID',
		                            '$this->Count',
		                            '$this->Price',
		                            '$this->VAT',
		                            '$this->ProductID',
                                    '$this->ExpiryDate' )" );
            $db->unlock();
			$this->ID = $nextID;

        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_OrderItem SET
		                         OrderID='$this->OrderID',
		                         Count='$this->Count',
		                         Price='$this->Price',
		                         ProductID='$this->ProductID',
		                         VAT='$this->VAT',
                                 ExpiryDate='$this->ExpiryDate'
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
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_OrderItem WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $cart_array ) == 1 )
            {
                $this->ID =& $cart_array[0][$db->fieldName("ID")];
                $this->OrderID =& $cart_array[0][$db->fieldName("OrderID")];
                $this->Count =& $cart_array[0][$db->fieldName("Count")];
                $this->Price =& $cart_array[0][$db->fieldName("Price")];
                $this->ProductID =& $cart_array[0][$db->fieldName("ProductID")];
                $this->ExpiryDate =& $cart_array[0][$db->fieldName("ExpiryDate")];
                $this->VAT =& $cart_array[0][ "VAT" ];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZOrderItem object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $ret[] = $db->query( "DELETE FROM eZTrade_OrderOptionValue WHERE OrderItemID='$this->ID'" );
        $ret[] = $db->query( "DELETE FROM eZTrade_OrderItem WHERE ID='$this->ID'" );

        eZDB::finish( $ret, $db );
        return true;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the count.
    */
    function count( )
    {
        return $this->Count;
    }

    /*!
      Returns the price of the order item.
    */
    function price( )
    {
        return $this->Price;
    }

    /*!
      Returns the product.
    */
    function product()
    {
        $ret = new eZProduct( $this->ProductID );

        return $ret;
    }

    /*!
      Returns the price included with VAT of the order item.
    */
    function priceIncVAT( )
    {
       return $this->Price + $this->VAT;
    }

    /*!
      Returns the VAT of the order item.
    */
    function VAT( )
    {
       return $this->VAT;
    }

    /*!
      returns a timestamp of the date when this product expires
    */
    function expiryDate()
    {
        return $this->ExpiryDate;
    }

    /*!
      Returns all the option values.
     */
    function optionValues()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();

        $db->array_query( $res_array, "SELECT ID FROM eZTrade_OrderOptionValue
                                       WHERE
                                       OrderItemID='$this->ID'
                                   " );

        foreach ( $res_array as $item )
        {
            $return_array[] = new eZOrderOptionValue( $item[$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      Sets the order.
    */
    function setOrder( $order )
    {
        if ( is_a( $order, "eZOrder" ) )
        {
            $this->OrderID = $order->id();
        }
    }

    /*!
      Sets the product.
    */
    function setProduct( $product )
    {
        if ( is_a( $product, "eZProduct" ) )
        {
            $this->ProductID = $product->id();
        }
    }

    /*!
      Sets the number of products.
    */
    function setCount( $value )
    {
        $this->Count = $value;
        setType( $this->Count, "integer" );
    }

    /*!
      Sets the price of one product.
    */
    function setPrice( $value )
    {
        $this->Price = $value;
        setType( $this->Price, "double" );
    }

    /*!
      Sets the VAT of one product.
    */
    function setVAT( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->VAT = $value;
       setType( $this->VAT, "double" );
    }


    /*!
      Sets the date when this product expires
    */
    function setExpiryDate( $date )
    {
        if ( is_a( $date, "eZDate" ) || is_a( $date, "eZDateTime" ) )
            $timestamp = $date->timeStamp();
        else if ( is_numeric( $date ) )
            $timestamp = $date;
        else
            return false;

        $this->ExpiryDate = $timestamp;
    }

    /*!
      Returns the order
    */
    function order()
    {
        return new eZOrder( $this->OrderID );
    }

    /*!
      Returns the correct price of the product based on the logged in user, and the
      VAT status and use.
    */
    function correctPrice( $calcCount=true, $withOptions=true, $calcVAT=true )
    {
        if ( $calcVAT == true )
        {
            $price =  $this->Price + $this->VAT;
        }
        else
        {
            $price = $this->Price;
        }

        if ( $calcCount == true )
        {
            $price = $price * $this->Count;
        }

        return $price;
    }

    /*!
      Returns the correct localized price of the product.
    */
    function localePrice( $calcCount=true, $withOptions=true, $calcVAT=true )
    {
        $ini =& eZINI::instance( 'site.ini' );
        $Language = $ini->variable( "eZTradeMain", "Language" );
        $locale = new eZLocale( $Language );
        $currency = new eZCurrency();

        $price = $this->correctPrice( $calcCount, $withOptions, $calcVAT );

        $currency->setValue( $price );
        return $locale->format( $currency );
    }



    var $ID;
    var $OrderID;
    var $Count;
    var $Price;
    var $ProductID;
    var $ExpiryDate;
    var $VAT;

}

?>