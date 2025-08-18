<?php
//
// $Id: ezpricegroup.php 9210 2002-02-13 09:14:20Z br $
//
// Definition of eZPriceGroup class
//
// Created on: <23-Feb-2001 12:57:01 amos>
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
//! The class eZPriceGroup handles price groups and prices for products and options
/*!

*/

// include_once( "classes/ezdb.php" );

class eZPriceGroup
{
    function __construct( $id = false )
    {
        if ( is_array( $id ) )
            $this->fill( $id );
        else if ( $id )
            $this->get( $id );
    }

    function get( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->query_single( $array, "SELECT ID, Name, Description, Placement FROM eZTrade_PriceGroup
                                    WHERE ID='$id'" );
        $this->fill( $array );
    }

    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );
        $this->Description = $db->escapeString( $this->Description );

        if ( !isset( $this->Placement ) or !is_numeric( $this->Placement ) )
        {
            $db->query_single( $place, "SELECT max( Placement ) AS Placement
                                        FROM eZTrade_PriceGroup" );
            $this->Placement = $place[$db->fieldName("Placement")] == "NULL" ? 1 : $place[$db->fieldName("Placement")] + 1;
        }

        if ( is_numeric( $this->ID) )
        {
            $ret[] = $db->query( "UPDATE eZTrade_PriceGroup SET
                     Name='$this->Name',
                     Description='$this->Description',
                     Placement='$this->Placement'
                     WHERE
                     ID='$this->ID'" );
        }
        else
        {
            $db->lock( "eZTrade_PriceGroup" );
            $nextID = $db->nextID( "eZTrade_PriceGroup", "ID" );
            $ret[] = $db->query( "INSERT INTO eZTrade_PriceGroup
                   ( ID,
                     Name,
                     Description,
                     Placement )
                   VALUES
                   ( '$nextID',
                     '$this->Name',
                     '$this->Description',
                     '$this->Placement' )" );
            $db->unlock();
            $this->ID = $nextID;
        }

        eZDB::finish( $ret, $db );
    }

    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        $ret[] = $db->query( "DELETE FROM eZTrade_ProductPriceLink WHERE PriceID='$id'" );
        $ret[] = $db->query( "DELETE FROM eZTrade_GroupPriceLink WHERE PriceID='$id'" );
        $ret[] = $db->query( "DELETE FROM eZTrade_PriceGroup WHERE ID='$id'" );
        eZDB::finish( $ret, $db );
    }

    function fill( $array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $array[$db->fieldName("ID")];
        $this->Name = $array[$db->fieldName("Name")];
        $this->Description = $array[$db->fieldName("Description")];
        $this->Placement = $array[$db->fieldName("Placement")];
    }

    /*!
      Returns all product groups from db.
    */
    static public function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $array, "SELECT ID FROM eZTrade_PriceGroup
                                   ORDER BY Placement" );
        $ret = array();
        foreach( $array as $row )
        {
            $ret[] = $as_object ? new eZPriceGroup( $row[$db->fieldName("ID")] ) : $row[$db->fieldName("ID")];
        }
        return $ret;
    }

    /*!
      Returns all user groups which are connected to a specific price group.
    */
    function &userGroups( $id = false, $as_object = true )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $array, "SELECT GroupID AS ID FROM eZTrade_GroupPriceLink
                                   WHERE PriceID='$id'" );
        $ret = array();
        foreach( $array as $row )
        {
            $ret[] = $as_object ? new eZUserGroup( $row[$db->fieldName("ID")] ) : $row[$db->fieldName("ID")];
        }
        return $ret;
    }

    /*!
      Returns all price groups which are connected to a user through the user's user groups.
    */
    static public function &priceGroups( $inUser, $as_object = true )
    {
        $group_string = false;
        if ( is_a( $inUser, "eZUser" ) )
        {
            $user =& $inUser;
        }
        else if ( is_numeric( $inUser ) )
        {
            $user = new eZUser( $inUser );
        }

        $groups = $user->groups( false );
        $i = 0;
        foreach( $groups as $group )
        {
            if ( $i == 0 )
            {
                $group_string = "GroupID=$group";
            }
            else
            {
                $group_string = $group_string . " OR GroupID=$group";
            }
            $i++;
        }

        $ret = array();
        if ( $group_string != "" )
        {
            $db =& eZDB::globalDatabase();
            $db->array_query( $array, "SELECT PriceID AS ID FROM eZTrade_GroupPriceLink
                                   WHERE $group_string" );
            foreach( $array as $row )
            {
                $ret[] = $as_object ? new eZPriceGroup( $row[$db->fieldName("ID")] ) : $row[$db->fieldName("ID")];
            }
        }
        return $ret;
    }

    /*!
      Adds a user group to a price group.
    */
    function addUserGroup( $group_id, $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        if ( is_a( $group_id, "eZUserGroup" ) )
            $group_id = $group_id->id();

        $db =& eZDB::globalDatabase();
        $db->begin();
        $ret[] = $db->query( "INSERT INTO eZTrade_GroupPriceLink
                   ( GroupID,
                     PriceID )
                   VALUES
                   ( '$group_id',
                     '$id' )" );
        eZDB::finish( $ret, $db );
    }

    /*!
      Removes all user groups from a price group.
    */
    function removeUserGroups( $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        $ret[] = $db->query( "DELETE FROM eZTrade_GroupPriceLink WHERE PriceID='$id'" );
        eZDB::finish( $ret, $db );
    }

    /*!
      Returns the price group which the user group is connected to or false if none.
    */
    static public function correctPriceGroup( $group_id, $debug=false )
    {
        $db =& eZDB::globalDatabase();
        if ( is_array( $group_id ) )
        {
            $first = true;
            $group_text = '';
            foreach( $group_id as $group )
            {
                $first ? $group_text = "GroupID='$group'" : $group_text .= "OR GroupID='$group'";
                $first = false;
            }
            if ( $group_text )
                $group_text = " AND ( $group_text )";
        }
        else
        {
            $group_text = "AND GroupID='$group_id'";
        }

        $db->array_query( $array, "SELECT PriceID
                                   FROM eZTrade_GroupPriceLink, eZTrade_PriceGroup
                                   WHERE PriceID=ID $group_text
                                   ORDER BY Placement", array( "Limit" => 1, "Offset" => 0 ) );

        if ( count( $array ) == 1 )
            return $array[0][$db->fieldName("PriceID")];
        return false;
    }

    /*
        Returns the lowest price for an option.
     */
    static public function lowestPrice( $productid, $priceid, $optionid )
    {
        $db =& eZDB::globalDatabase();

        $ini =& eZINI::instance( 'site.ini' );
        $ret = false;
        $ShowPriceGroups = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true" ? true : false;

        if ( $ShowPriceGroups == true )
        {
            if ( is_array( $priceid ) )
            {
                $first = true;
                foreach( $priceid as $group )
                {
                    $first ? $group_text = "PriceID='$group'" : $group_text .= "OR PriceID='$group'";
                    $first = false;
                }

                if ( isset($group_text) )
                    $group_text = " AND ( $group_text )";
                else
                    $group_text = "";
            }
            else
            {
                $group_text = "AND PriceID='$priceid'";
            }

            $db->array_query( $array, "SELECT Price, PriceID FROM eZTrade_ProductPriceLink
                                       WHERE ProductID='$productid' $group_text
                                         AND OptionID='$optionid' ORDER BY Price" );

            if ( count( $array ) > 0 )
            {
                for($i=0;$i < count( $array ); $i++ )
                {
                    $priceID = $array[$i][$db->fieldName("PriceID")];
                    if ( in_array( $priceID , $priceid ) )
                    {
                        $ret = $array[$i][$db->fieldName("Price")];
                        break;
                    }
                }
            }

            if ( $ret == false )
            {
                $db->array_query( $array, "SELECT Price FROM eZTrade_OptionValue
                                           WHERE OptionID='$optionid' ORDER BY Price" );

                if ( count( $array ) > 0 )
                    $ret = $array[0][$db->fieldName("Price")];
            }
        }
        else
        {
            $db->array_query( $array, "SELECT Price FROM eZTrade_OptionValue
                                       WHERE OptionID='$optionid' ORDER BY Price" );
            if ( count( $array ) > 0 )
                $ret = $array[0][$db->fieldName("Price")];
        }

        return $ret;
    }

    /*
        Returns the highest price for an option.
     */
    static public function highestPrice( $productid, $priceid, $optionid )
    {
        $db =& eZDB::globalDatabase();

        $ini =& eZINI::instance( 'site.ini' );
        $ret = false;
        $ShowPriceGroups = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true" ? true : false;

        if ( $ShowPriceGroups == true )
        {
            if ( is_array( $priceid ) )
            {
                $first = true;
                foreach( $priceid as $group )
                {
                    $first ? $group_text = "PriceID='$group'" : $group_text .= "OR PriceID='$group'";
                    $first = false;
                }
                if ( isset($group_text) )
                    $group_text = " AND ( $group_text )";
                else
                    $group_text = "";
            }
            else
            {
                $group_text = "AND PriceID='$priceid'";
            }

            $db->array_query( $array, "SELECT Price, PriceID FROM eZTrade_ProductPriceLink
                                       WHERE ProductID='$productid' $group_text
                                         AND OptionID='$optionid' ORDER BY Price DESC" );

            if ( count( $array ) > 0 )
            {
                for($i=0;$i < count( $array ); $i++ )
                {
                    $priceID = $array[$i][$db->fieldName("PriceID")];

                    if ( in_array( $priceID , $priceid ) )
                    {
                        $ret = $array[$i][$db->fieldName("Price")];
                        break;
                    }
                }
            }

            if ( $ret == false )
            {
                $db->array_query( $array, "SELECT Price FROM eZTrade_OptionValue
                                           WHERE OptionID='$optionid' ORDER BY Price DESC" );

                if ( count( $array ) > 0 )
                {
                    $ret = $array[0][$db->fieldName("Price")];
                }

            }
        }
        else
        {
            $db->array_query( $array, "SELECT Price FROM eZTrade_OptionValue
                                       WHERE OptionID='$optionid' ORDER BY Price DESC" );

            if ( count( $array ) > 0 )
                $ret = $array[0][$db->fieldName("Price")];
        }
        return $ret;
    }

    /*!
      Returns the price of a product according to it's price group, option and value type.
    */
    static public function correctPrice( $productid, $priceid, $optionid = 0, $valueid = 0 )
    {
        $db =& eZDB::globalDatabase();

        $ini =& eZINI::instance( 'site.ini' );
        $ShowPriceGroups = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true" ? true : false;
        $group_text = false;
        if ( $ShowPriceGroups == true )
        {
            if ( is_array( $priceid ) )
            {
                $first = true;
                foreach( $priceid as $group )
                {
                    $first ? $group_text = "PriceID='$group'" : $group_text .= "OR PriceID='$group'";
                    $first = false;
                }
                if ( isset( $group_text ) && $group_text != "" )
                    $group_text = " AND ( $group_text )";
                else
                    $group_text = "";

            }
            else
            {
                $group_text = "AND PriceID='$priceid'";
            }

	        $array = array();

            // don't give better price unless you're a part of a price group
            if ( $group_text != "" )
            {
                $db->array_query( $array, "SELECT Price FROM eZTrade_ProductPriceLink
                                       WHERE ProductID='$productid' $group_text
                                         AND OptionID='$optionid' AND ValueID='$valueid' ORDER BY Price" );

            }
            if ( isset( $array ) && count( $array ) == 1 )
                return $array[0][$db->fieldName("Price")];
        }
        return false;
    }

    /*!
      Returns all prices for a specific product with a specific option and value id.
    */
    static public function prices( $productid, $optionid = 0, $valueid = 0 )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $array, "SELECT PriceID, Price FROM eZTrade_ProductPriceLink
                                   WHERE ProductID='$productid' AND OptionID='$optionid'
                                         AND ValueID='$valueid'" );
        return $array;
    }

    /*!
      Adds a price to a product with the specific option and value id.
    */
    function addPrice( $productid, $priceid, $price, $optionid = 0, $valueid = 0 )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $query = "INSERT INTO eZTrade_ProductPriceLink
                     ( PriceID,
                       ProductID,
                       Price,
                       OptionID,
                       ValueID )
                     VALUES
                     ( '$priceid',
                       '$productid',
                       '$price',
                       '$optionid',
                       '$valueid' )";

        $ret[] = $db->query( $query );
        eZDB::finish( $ret, $db );
    }

    /*!
      Removes all prices from a product with the specific option and value id.
    */
    static public function removePrices( $productid, $optionid = 0, $valueid = 0, $priceid='' )
    {
        $db = eZDB::globalDatabase();
        $db->begin();
        if ( $optionid >= 0 )
            $option_text = "AND OptionID='$optionid'";
        if ( $valueid >= 0 )
            $value_text = "AND ValueID='$valueid'";
		if ( $priceid != '' )
			$priceid_text = "AND PriceID='$priceid'";
		else
			$priceid_text = '';
        $ret[] = $db->query( "DELETE FROM eZTrade_ProductPriceLink
                     WHERE ProductID='$productid' $option_text $value_text $priceid_text" );
        eZDB::finish( $ret, $db );
    }

    /*!
      Returns the id of the group.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the group.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the description of the group.
    */
    function description()
    {
        return $this->Description;
    }

    /*!
      Returns the placement of the group in the list.
    */
    function placement()
    {
        return $this->Placement;
    }

    /*!
      Sets the name of the group.
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Sets the description of the group.
    */
    function setDescription( $desc )
    {
        $this->Description = $desc;
    }

    /*!
      Sets the placement of the group in the list.
    */
    function setPlacement( $place )
    {
        $this->Placement = $place;
    }

    var $ID;
    var $Name;
    var $Description;
    var $Placement;
}

?>