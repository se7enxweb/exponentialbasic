<?php
//
// $Id: ezoptionvalue.php 9276 2002-02-26 14:41:12Z ce $
//
// Definition of eZOptionValue class
//
// Created on: <12-Sep-2000 15:52:19 bf>
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
//! Handles product option values.
/*!

  Example:
  \code
  // Create a new eZOptionValue object and store it to the database
  $value = new eZOptionValue();
  $value->setName( "Red" );
  $value->store();

  // Fetch a value from the database, and print out the contents.
  $value->get( 2 );

  print( $value->name() );

  \endcode
  \sa eZProductCategory eZOption
*/

// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezoption.php" );
// include_once( "eztrade/classes/ezpricegroup.php" );

class eZOptionValue
{
    /*!
      Constructs a new eZOptionValue object.
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
      Stores a eZOptionValue object to the database.
    */
    function store()
    {
        $ini = eZINI::instance( "site.ini" );
        $db =& eZDB::globalDatabase();
        $db->begin();
        $qry_array = array();
        $ret = array();

        $this->OptionID = $db->escapeString( $this->OptionID );

        $price = $this->Price == "" ? "NULL" : "'$this->Price'";

        $ini->variable( "site", "DebugOutput" ) == "enabled" ? true : false;

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_OptionValue" );
            $db->array_query( $qry_array,
                              "SELECT Placement FROM eZTrade_OptionValue
                               WHERE OptionID='$this->OptionID' ORDER BY Placement DESC LIMIT 1" );
            $placement = count( $qry_array ) == 0 ? 1 : $qry_array[0]["Placement"] + 1;
            $nextID = $db->nextID( "eZTrade_OptionValue", "ID" );
            $ret[] = $db->query( "INSERT INTO eZTrade_OptionValue
                               ( ID,
		                         Price,
                                 Placement,
                                 RemoteID,
                                 OptionID )
                               VALUES
                               ( '$nextID',
	                          $price,
                                 '$placement',
                                 '$this->RemoteID',
                                 '$this->OptionID' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_OptionValue SET
		                         Price=$price,
                                 RemoteID='$this->RemoteID',
                                 OptionID='$this->OptionID'
                                 WHERE ID='$this->ID'" );
        }

        eZDB::finish( $ret, $db );

        return true;
    }

    /*!
      Fetches the option object values from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "-1" )
        {
            $db->array_query( $optionValue_array, "SELECT * FROM eZTrade_OptionValue WHERE ID='$id'" );
            if ( count( $optionValue_array ) > 1 )
            {
                die( "Error: OptionValue's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $optionValue_array ) == 1 )
            {
                $this->ID =& $optionValue_array[0][$db->fieldName("ID")];
                $this->Price =& $optionValue_array[0][$db->fieldName("Price")];
                if ( $this->Price == "NULL" )
                    $this->Price = false;
                $this->OptionID =& $optionValue_array[0][$db->fieldName("OptionID")];
                $this->RemoteID =& $optionValue_array[0][$db->fieldName("RemoteID")];
            }
        }
    }

    /*!
      Returns every optionValue stored in the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        $return_array = array();
        $optionValue_array = array();

        $db->array_query( $optionValue_array, "SELECT ID FROM eZTrade_OptionValue
                                                           ORDER BY Placement ASC" );

        for ( $i=0; $i < count($optionValue_array); $i++ )
        {
            $return_array[$i] = new eZOptionValue( $optionValue_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Returns every optionValue connected to a certain Option.

      The values are sorted by name. Returns 0 if no values are found.
    */
    function &getByOption( &$value, $as_object = true )
    {
        if ( is_a( $value, "eZOption" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $optionValue_array = array();

            $id = $value->id();

            $db->array_query( $optionValue_array,
            "SELECT ID FROM eZTrade_OptionValue WHERE OptionID='$id' ORDER BY Placement ASC" );

            for ( $i=0; $i < count($optionValue_array); $i++ )
            {
                $return_array[$i] = $as_object ? new eZOptionValue( $optionValue_array[$i][$db->fieldName("ID")], true ) : $optionValue_array[$i][$db->fieldName("ID")];
            }
            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Sets the total quantity of the option value.
    */
    function setTotalQuantity( $quantity )
    {
        $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $ret = array();
        $db->begin();

        $db->array_query( $qry_array,
                          "SELECT Q.ID
                           FROM eZTrade_Quantity AS Q, eZTrade_ValueQuantityDict AS VQD
                           WHERE Q.ID=VQD.QuantityID AND ValueID='$id'" );
        $ret[] = $db->query( "DELETE FROM eZTrade_ValueQuantityDict WHERE ValueID='$id'" );
        foreach( $qry_array as $row )
        {
            $q_id = $row[$db->fieldName("ID")];
            $ret[] = $db->query( "DELETE FROM eZTrade_Quantity WHERE ID='$q_id'" );
        }

        if ( is_bool( $quantity ) and !$quantity )
            return;

        $db->lock( "eZTrade_Quantity" );

        $nextID = $db->nextID( "eZTrade_Quantity", "ID" );

        $ret[] = $db->query( "INSERT INTO eZTrade_Quantity
                                      ( ID,
                                        Quantity )
                                      VALUES
                                      ('$nextID',
                                       '$quantity')" );

        $q_id = $nextID;

        $db->lock( "eZTrade_ValueQuantityDict" );
        $ret[] = $db->query( "INSERT INTO eZTrade_ValueQuantityDict
                                      ( ValueID,
                                        QuantityID )
                                      VALUES
                                      ('$id',
                                       '$q_id' )" );

        $db->unlock();
        eZDB::finish( $ret, $db );
    }

    /*!
      \static
      Returns the total quantity of this value.
    */
    function totalQuantity( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array,
                          "SELECT Q.Quantity
                           FROM eZTrade_Quantity AS Q, eZTrade_ValueQuantityDict AS VQD
                           WHERE Q.ID=VQD.QuantityID AND ValueID='$id'" );
        $quantity = 0;
        if ( count( $qry_array ) > 0 )
        {
            foreach( $qry_array as $row )
            {
                if ( $row[$db->fieldName("Quantity")] == "NULL" )
                    return false;
                $quantity += $row[$db->fieldName("Quantity")];
            }
        }
        else
            return false;
        return $quantity;
    }

    /*!
      \static
      Returns true if the option value has some sort of quantity which can be bought.
    */
    function hasQuantity( $require = true, $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $quantity = eZOptionValue::totalQuantity( $id );
        if ( (is_bool($quantity) and !$quantity) or
             !$require or ( $require and $quantity > 0 ) )
            return true;
        return false;
    }

    /*!
      Returns all descriptions connected to this option value.
      It is returned as an array with strings.
    */
    function &descriptions( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;
        $db->array_query( $qry_array,
                          "SELECT eZTrade_OptionValueContent.Value FROM eZTrade_OptionValueContent
                           WHERE ValueID='$id' GROUP By eZTrade_OptionValueContent.Value, eZTrade_OptionValueContent.Placement ORDER BY Placement ASC" );
        $ret = array();
        foreach( $qry_array as $row )
        {
            $ret[] = $row[$db->fieldName("Value")];
        }
        return $ret;
    }

    /*!
      Removes all descriptions for this option value.
    */
    function removeDescriptions( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;
        $db->begin();
        $ret[] = $db->query( "DELETE FROM eZTrade_OptionValueContent WHERE ValueID='$id'" );
        eZDB::finish( $ret, $db );
    }

    /*!
      Adds a new description to this option value.
    */
    function addDescription( $description, $id = false )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        if ( !$id )
            $id = $this->ID;
        if ( !is_array( $description ) )
            $description = array( $description );
        $db->array_query( $qry_array,
                          "SELECT Placement FROM eZTrade_OptionValueContent
                           WHERE ValueID='$id' ORDER BY Placement DESC LIMIT 1", 0, 1 );
        $placement = count( $qry_array ) == 0 ? 1 : $qry_array[0][$db->fieldName("Placement")] + 1;
        $db->lock( "eZTrade_OptionValueContent" );
        foreach( $description as $desc )
        {
            $desc = $db->escapeString( $desc );
            $nextID = $db->nextID( "eZTrade_OptionValueContent", "ID" );
            $ret[] = $db->query( "INSERT INTO eZTrade_OptionValueContent
                                  ( ID,
                                    Value,
                                    ValueID,
                                    Placement )
                                  VALUES
                                  ( '$nextID',
                                    '$desc',
                                    '$id',
                                    '$placement')" );
            $placement++;
        }

        $db->unlock();
        eZDB::finish( $ret, $db );
    }

    /*!
      Deletes a option from the database.
    */
    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $ret = array();
        $db =& eZDB::globalDatabase();
        $db->begin();

        $ret[] = $db->query( "DELETE FROM eZTrade_OptionValue
                                                      WHERE ID='$id'" );
        $ret[] = $db->query( "DELETE FROM eZTrade_ProductPriceLink
                                                      WHERE ValueID='$id'" );
        $ret[] = $db->query( "DELETE FROM eZTrade_OptionValueContent
                                                      WHERE ValueID='$id'" );
        eZDB::finish( $ret, $db );
    }

    /*!
      Check if there are a value where RemoteID == $id. Return the value if true.
    */
    function getByRemoteID( $id )
    {
        $db =& eZDB::globalDatabase();

        $value = false;

        $db->array_query( $res, "SELECT ID FROM
                                            eZTrade_OptionValue
                                            WHERE RemoteID='$id'" );
        if ( count( $res ) == 1 )
        {
            $value = new eZOptionValue( $res[0][$db->fieldName("ID")] );
        }

        return $value;
    }


    /*!
      Returns the id of the optionvalue.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the price of the option value.
    */
    function price()
    {
        return $this->Price;
    }

    /*!
      Returns the correct localized price of the product.
    */
    function &localePrice( $calcVAT, $inProduct )
    {
        $ini =& eZINI::instance( 'site.ini' );
        $inLanguage = $ini->variable( "eZTradeMain", "Language" );

        $locale = new eZLocale( $inLanguage );
        $currency = new eZCurrency();

        $price = $this->correctPrice( $calcVAT, $inProduct );

        $currency->setValue( $price );
        return $locale->format( $currency );
    }

    /*!
      Returns the correct price of the option value.
    */
    function correctPrice( $calcVAT, $inProduct, $withPriceGroups=true )
    {
        $inUser =& eZUser::currentUser();
        $vat = $inProduct->vatPercentage();
        $productHasVAT = $inProduct->includesVAT();

        if ( is_a( $inUser,  "eZUser" ) )
        {
            if ( $withPriceGroups == true )
                $groups = eZPriceGroup::priceGroups( $inUser, false );
            else
                $groups = array();

            $price = eZPriceGroup::correctPrice( $inProduct->id(), $groups, $this->OptionID, $this->ID );
        }

        if ( empty( $price ) )
        {
            $price = $this->Price;
        }

        if ( $calcVAT == true )
        {
            if ( $productHasVAT == false )
            {
                $price = ( $price * $vat / 100 ) + $price;
            }
        }
        else
        {
            if ( $productHasVAT == true )
            {
                $price = $price - ( $price / ( $vat + 100  ) ) * $vat;
            }

        }
        return $price;
    }

    /*!
      Returns the correct localized savings of the product.
    */
    function &localeSavings( $calcVAT, $inProduct )
    {
        $ini =& eZINI::instance( 'site.ini' );
        $inLanguage = $ini->variable( "eZTradeMain", "Language" );

        $locale = new eZLocale( $inLanguage );
        $currency = new eZCurrency();

        $price = $this->correctSavings( $calcVAT, $inProduct );

        $currency->setValue( $price );
        return $locale->format( $currency );
    }


    /*!
      Returns the savings of the option value.
    */
    function correctSavings( $calcVAT, $inProduct )
    {
        $inUser =& eZUser::currentUser();
        $vat = $inProduct->vatPercentage();
        $productHasVAT = $inProduct->includesVAT();

        if ( is_a( $inUser,  "eZUser" ) )
        {
            $groups = eZPriceGroup::priceGroups( $inUser, false );

            $price = eZPriceGroup::correctPrice( $inProduct->id(), $groups, $this->OptionID, $this->ID );
        }

        if ( empty( $price ) )
        {
            $price = $this->Price;
        }

        if ( isset( $maxPrice ) && $maxPrice <= $price )
        {
            $maxPrice = $this->Price;
        }

        if (  isset( $maxPrice ) && $maxPrice > $price )
        {
            $savings = $maxPrice - $price;
        }
        else
        {
            $savings = 0;
        }

        if ( $calcVAT == true )
        {
            if ( $productHasVAT == false )
            {
                $savings = ( $savings * $vat / 100 ) + $savings;
            }
        }
        else
        {
            if ( $productHasVAT == true )
            {
                $savings = $savings - ( $savings / ( $vat + 100  ) ) * $vat;
            }

        }
        return $savings;
    }

    /*!
      Returns the remoteID of the option value.
    */
    function remoteID()
    {
        return $this->RemoteID;
    }

    /*!
      Returns the option connected to the value.
    */
    function option()
    {
        return new eZOption( $this->OptionID );
    }

    /*!
      Sets the price of the option value.
    */
    function setPrice( $value )
    {
        $this->Price = $value;
    }

    /*!
      Sets the remoteID of the option value.
    */
    function setRemoteID( $value )
    {
        $this->RemoteID = $value;
    }

    /*!

    */
    function setOptionID( $value )
    {
       $this->OptionID = $value;
       setType( $this->OptionID, "integer" );
    }

    var $ID;
    var $Price;
    var $RemoteID;
    var $OptionID;

}

?>