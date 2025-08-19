<?php
//
// $Id: ezcompany.php 9529 2002-05-14 11:17:05Z jhe $
//
// Definition of eZProduct class
//
// Created on: <09-Nov-2000 14:52:40 ce>
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

//!! eZContact
//! eZCompany handles company information.
/*!

  Example code:
  \code
  $company = new eZCompany();
  $company->setName( "Company name" );
  $company->store();

  \endcode

  \sa eZPerson eZAddress
*/

//require "ezphputils.php";

// include_once( "ezaddress/classes/ezaddress.php" );
// include_once( "ezcontact/classes/ezcompanytype.php" );
// include_once( "ezaddress/classes/ezphone.php" );
// include_once( "classes/ezimagefile.php" );
// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "classes/ezdate.php" );

class eZCompany
{
    /*!
      Constructs a new eZCompany object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id= -1 )
    {
        $this->CreatorID = 0;

        if ( is_numeric( $id ) and $id > -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a company to the database
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $comment = $db->escapeString( $this->Comment );
        $type = $this->ContactType == "ezperson" ? 2 : 1;

        if ( !isset( $this->ID ) or !is_numeric( $this->ID ) )
        {
            $db->lock( "eZContact_Company" );
            $this->ID = $db->nextID( "eZContact_Company", "ID" );
            $res[] = $db->query( "INSERT INTO eZContact_Company
                                  (ID,
                                   Name,
                                   Comment,
                                   CompanyNo,
                                   ContactID,
                                   ContactType,
                                   CreatorID)
                                  VALUES
                                  ('$this->ID',
                                   '$name',
                                   '$comment',
                                   '$this->CompanyNo',
                                   '$this->ContactID',
                                   '$type',
                                   '$this->CreatorID')" );
            $db->unlock();
            $name = strtolower( $name );
            $res[] = $db->query( "INSERT INTO eZContact_CompanyIndex
                                  (CompanyID, Value, Type)
                                  VALUES
                                  ('$this->ID', '$name', '0')" );
        }
        else
        {
            $res[] = $db->query( "UPDATE eZContact_Company SET
                                  Name='$name',
                                  Comment='$comment',
                                  CompanyNo='$this->CompanyNo',
                                  ContactID='$this->ContactID',
                                  ContactType='$type',
                                  CreatorID='$this->CreatorID'
                                  WHERE ID='$this->ID'" );
            $name = strtolower( $name );
            $res[] = $db->query( "UPDATE eZContact_CompanyIndex SET
                                  Value='$name'
                                  WHERE CompanyID='$this->ID' AND Type='0'" );
        }
        eZDB::finish( $res, $db );

        return true;
    }

    /*!
      Deletes a eZCompany object  from the database.
    */
    function delete( $id = false )
    {
        $db =& eZDB::globalDatabase();

        if ( !$id )
            $id = $this->ID;

        $db->begin();
        if ( isset( $id ) && is_numeric( $id ) )
        {
            // Delete real world addresses

            $db->array_query( $address_array, "SELECT eZContact_CompanyAddressDict.AddressID AS DID
                                               FROM eZAddress_Address, eZContact_CompanyAddressDict
                                               WHERE eZAddress_Address.ID=eZContact_CompanyAddressDict.AddressID
                                               AND eZContact_CompanyAddressDict.CompanyID='$id' " );

            foreach ( $address_array as $addressItem )
            {
                $addressDictID = $addressItem[ $db->fieldName( "DID" ) ];
                eZAddress::delete( $addressDictID );
            }

            $res[] = $db->query( "DELETE FROM eZContact_CompanyAddressDict WHERE CompanyID='$id'" );

            // Delete phone numbers.

            $db->array_query( $phone_array, "SELECT eZContact_CompanyPhoneDict.PhoneID AS DID
                                     FROM eZAddress_Phone, eZContact_CompanyPhoneDict
                                     WHERE eZAddress_Phone.ID=eZContact_CompanyPhoneDict.PhoneID
                                     AND eZContact_CompanyPhoneDict.CompanyID='$id' " );

            foreach ( $phone_array as $phoneItem )
            {
                $phoneDictID = $phoneItem[ $db->fieldName( "DID" ) ];
                eZPhone::delete( $phoneDictID );
            }
            $res[] = $db->query( "DELETE FROM eZContact_CompanyPhoneDict WHERE CompanyID='$id'" );

            // Delete online address.

            $db->array_query( $online_array, "SELECT eZContact_CompanyOnlineDict.OnlineID AS DID
                                       FROM eZAddress_Online, eZContact_CompanyOnlineDict
                                       WHERE eZAddress_Online.ID=eZContact_CompanyOnlineDict.OnlineID
                                       AND eZContact_CompanyOnlineDict.CompanyID='$id' " );

            foreach ( $online_array as $onlineItem )
            {
                $onlineDictID = $onlineItem[ $db->fieldName( "DID" ) ];
                eZPhone::delete( $onlineDictID );
            }

            $res[] = $db->query( "DELETE FROM eZContact_CompanyOnlineDict WHERE CompanyID='$id'" );
            $res[] = $db->query( "DELETE FROM eZContact_CompanyTypeDict WHERE CompanyID='$id'" );
            $res[] = $db->query( "DELETE FROM eZContact_Company WHERE ID='$id'" );
            $res[] = $db->query( "DELETE FROM eZContact_CompanyPersonDict WHERE CompanyID='$id'" );
            $res[] = $db->query( "DELETE FROM eZContact_CompanyIndex WHERE CompanyID='$id'" );
            $db->array_query( $res_array, "SELECT ID FROM eZTrade_Order WHERE CompanyID='$id'" );
            // include_once( "eztrade/classes/ezorder.php" );
            foreach ( $res_array as $order )
            {
                $orderObject = new eZOrder( $order[$db->fieldName( "ID" )] );
                $orderObject->delete();
            }
        }
        eZCompany::removePersons( $id );
        eZDB::finish( $res, $db );

        return true;
    }


    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $company_array, "SELECT * FROM eZContact_Company WHERE ID='$id'" );
            if ( count( $company_array ) > 1 )
            {
                die( "Error: More than one company with the same id was found. " );
            }
            elseif ( count( $company_array ) == 1 )
            {
                $this->ID = $company_array[0][$db->fieldName( "ID" )];
                $this->Name = $company_array[0][$db->fieldName( "Name" )];
                $this->Comment = $company_array[0][$db->fieldName( "Comment" )];
                $this->CreatorID = $company_array[0][$db->fieldName( "CreatorID" )];
                $this->CompanyNo = $company_array[0][$db->fieldName( "CompanyNo" )];
                $this->ContactID = $company_array[0][$db->fieldName( "ContactID" )];
                $type = $company_array[0][$db->fieldName( "ContactType" )];
                $this->ContactType = $type == 2 ? "ezperson" : "ezuser";
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      \static
      Returns true if the company exists.
    */
    static public function exists( $id )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $db->array_query( $qry_array, "SELECT ID FROM eZContact_Company WHERE ID='$id'", 0, 1 );
        if ( count( $qry_array ) == 1 )
            $ret = true;
        return $ret;
    }

    /*!
      Returns all companies found in the database.

      The companies are returned as an array of eZCompany objects.
    */
    function &getAll( )
    {
        $db =& eZDB::globalDatabase();

        $company_array = array();
        $return_array = array();

        $db->array_query( $company_array, "SELECT ID FROM eZContact_Company ORDER BY Name" );

        foreach ( $company_array as $companyItem )
        {
            $return_array[] = new eZCompany( $companyItem[$db->fieldName( "ID" )] );
        }
        return $return_array;
    }

    /*!
      Returns all the companies found in the database in a specific category.

      The companies are returned as an array of eZCompany objects.
    */
    function &getByCategory( $categoryID, $offset = 0, $limit = -1, $order = "name" )
    {
        $db =& eZDB::globalDatabase();

        $company_array = array();
        $return_array = array();
        $limit_array = array();

        if ( $limit > 0 )
        {
            $limit_array = array( "Limit" => $limit, "Offset" => $offset );
        }

        $dir = "ASC";
        if ( $order[0] == "-" )
        {
            $order = substr( $order, 1 );
            $dir = "DESC";
        }
        else if ( $order[0] == "+" )
        {
            $order = substr( $order, 1 );
            $dir = "ASC";
        }
        switch ( $order )
        {
            default:
                print( "<br /><b>Unknown order type in eZCompany::getByCategory(), got \"$order\"</b><br />" );
            case "name":
            {
                $order_text = "Name";
                break;
            }
            case "id":
            {
                $order_text = "ID";
                break;
            }
        }

        $db->array_query( $company_array, "SELECT CompanyID FROM eZContact_CompanyTypeDict, eZContact_Company
                                           WHERE eZContact_CompanyTypeDict.CompanyTypeID='$categoryID'
                                           AND eZContact_Company.ID = eZContact_CompanyTypeDict.CompanyID
                                           ORDER BY eZContact_Company.$order_text $dir", $limit_array );

        foreach ( $company_array as $companyItem )
        {
            $return_array[] = new eZCompany( $companyItem[$db->fieldName( "CompanyID" )] );
        }

        return $return_array;
    }

    /*!
      Returns all the companies found in the database in a specific category.

      The companies are returned as an array of eZCompany objects.
    */
    function countByCategory( $categoryID )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $company_array,
                           "SELECT count( CompanyID ) as Count
                            FROM eZContact_CompanyTypeDict, eZContact_Company
                            WHERE eZContact_CompanyTypeDict.CompanyTypeID='$categoryID'
                            AND eZContact_Company.ID = eZContact_CompanyTypeDict.CompanyID" );

        return $company_array[$db->fieldName( "Count" )];
    }

    /*!
      Search the company database in a single category, using query as the search string in company name.
    */
    function &searchByCategory( $categoryID, $query )
    {
        $db =& eZDB::globalDatabase();

        $query = $db->escapeString( $query );

        $company_array = array();
        $return_array = array();
        if ( !empty( $query ) )
        {
            $db->array_query( $company_array, "
                SELECT
                    Comp.ID
                FROM
                    eZContact_CompanyTypeDict as Dict,
                    eZContact_Company as Comp
                WHERE
                    Comp.Name LIKE '%$query%'
                AND
                    Dict.CompanyTypeID = '$categoryID'
                AND
                    Comp.ID = Dict.CompanyID
                ORDER BY Name" );

            foreach ( $company_array as $companyItem )
            {
                $return_array[] = new eZCompany( $companyItem[$db->fieldName( "ID" )] );
            }
        }
        return $return_array;
    }


    /*!
      Henter ut alle firma i databasen som inneholder s�kestrengen.
    */
    public static function &search( $query )
    {
        $db =& eZDB::globalDatabase();

        $query = $db->escapeString( strtolower( $query ) );

        $company_array = array();
        $return_array = array();

        $queryString = "SELECT CompanyID FROM eZContact_CompanyIndex
                        WHERE (Value LIKE '%$query%')
                        GROUP BY CompanyID";

        $db->array_query( $company_array, $queryString );

        foreach ( $company_array as $companyItem )
        {
            $return_array[] = new eZCompany( $companyItem[$db->fieldName( "CompanyID" )] );
        }
        return $return_array;
    }


    /*!
      Removes the company from every user category.
    */
    function removeCategories()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZContact_CompanyTypeDict
                                WHERE CompanyID='$this->ID'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the categories that belong to this eZCompany object.
    */
    function categories( $companyID = false, $as_object = true, $limit = -1 )
    {
        if ( !$companyID )
            $companyID = $this->ID;

        $return_array = array();
        $db =& eZDB::globalDatabase();

        if ( $limit != -1 )
        {
            $limit_array = array( "Limit" => $limit );
        }

        $db->array_query( $categories_array, "SELECT CompanyTypeID
                                              FROM eZContact_CompanyTypeDict
                                              WHERE CompanyID='$companyID'",
                                              $limit );

        foreach ( $categories_array as $categoriesItem )
        {
            if ( $as_object )
                $return_array[] = new eZCompanyType( $categoriesItem[$db->fieldName( "CompanyTypeID" )] );
            else
                $return_array[] =& $categoriesItem[$db->fieldName( "CompanyTypeID" )];
        }
        return $return_array;
    }


    /*!
      Returns the address that belong to this eZCompany object.
    */
    function &addresses( $companyID = false )
    {
        if ( !$companyID )
            $companyID = $this->ID;

        $return_array = array();
        $db =& eZDB::globalDatabase();

        $db->array_query( $address_array, "SELECT CAD.AddressID
                                           FROM eZContact_CompanyAddressDict AS CAD, eZAddress_Address AS A,
                                           eZAddress_AddressType as AT
                                           WHERE CAD.AddressID = A.ID AND A.AddressTypeID = AT.ID
                                                 AND CAD.CompanyID='$companyID' AND AT.Removed=0" );

        foreach ( $address_array as $addressItem )
        {
            $return_array[] = new eZAddress( $addressItem[$db->fieldName( "AddressID" )] );
        }

        return $return_array;
    }

    /*!
      Adds an address to the current Company.
    */
    function addAddress( &$address )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();
        if ( is_a( $address, "eZAddress" ) )
        {
            $addressID = $address->id();
            $db->begin();

            $res[] = $db->query( "INSERT INTO eZContact_CompanyAddressDict
                                  (CompanyID, AddressID)
                                  VALUES
                                  ('$this->ID', '$addressID')" );
            eZDB::finish( $res, $db );

            $ret = true;
        }
        return $ret;
    }

    /*!
      Delete all address and the relation to the eZContact_Company
    */
    function removeAddresses()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $address_array, "SELECT AddressID FROM eZContact_CompanyAddressDict
                                           WHERE CompanyID='$this->ID' " );

        foreach ( $address_array as $addressItem )
        {
            $addressID =& $addressItem[$db->fieldName( "AddressID" )];
            eZAddress::delete( $addressID );
        }
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_CompanyAddressDict WHERE CompanyID='$this->ID'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the phones that belong to this eZCompany object.
    */
    function &phones( $companyID = false )
    {
        if ( !$companyID )
            $companyID = $this->ID;

        $return_array = array();
        $db =& eZDB::globalDatabase();

        $db->array_query( $phone_array, "SELECT CPD.PhoneID
                                         FROM eZContact_CompanyPhoneDict AS CPD, eZAddress_Phone AS P,
                                              eZAddress_PhoneType AS PT
                                         WHERE CPD.PhoneID = P.ID AND P.PhoneTypeID = PT.ID
                                               AND CPD.CompanyID='$companyID' AND PT.Removed=0" );

        foreach ( $phone_array as $phoneItem )
        {
            $return_array[] = new eZPhone( $phoneItem[$db->fieldName( "PhoneID" )] );
        }

        return $return_array;
    }

    /*!
      Adds an phone to the current Company.
    */
    function addPhone( &$phone )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();
        $db->begin();
        if ( is_a( $phone, "eZPhone" ) )
        {
            $phoneID =& $phone->id();
            $phoneno = strtolower( $phone->number() );

            $res[] = $db->query( "INSERT INTO eZContact_CompanyPhoneDict
                                  (CompanyID, PhoneID)
                                  VALUES
                                  ('$this->ID', '$phoneID')" );

            $res[] = $db->query( "INSERT INTO eZContact_CompanyIndex
                                  (CompanyID, Value, Type)
                                  VALUES
                                  ('$this->ID', '$phoneno', '1')" );
            $ret = true;
        }
        eZDB::finish( $res, $db );
        return $ret;
    }

    /*!
      Delete all phones and the relation to the eZContact_Company
    */
    function removePhones()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->array_query( $phone_array, "SELECT PhoneID FROM
                                         eZContact_CompanyPhoneDict WHERE CompanyID='$this->ID' " );

        foreach ( $phone_array as $phoneItem )
        {
            $phoneID =& $phoneItem[$db->fieldName( "PhoneID" )];
            eZPhone::delete( $phoneID );
        }
        $res[] = $db->query( "DELETE FROM eZContact_CompanyPhoneDict WHERE CompanyID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZContact_CompanyIndex WHERE CompanyID='$this->ID' AND Type='1'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the onlines that belong to this eZCompany object.
    */
    function &onlines( $onlineID = false )
    {
        if ( !$onlineID )
            $onlineID = $this->ID;

        $return_array = array();
        $db =& eZDB::globalDatabase();

        $db->array_query( $online_array, "SELECT COD.OnlineID
                                          FROM eZContact_CompanyOnlineDict AS COD, eZAddress_Online AS O,
                                               eZAddress_OnlineType AS OT
                                          WHERE COD.OnlineID = O.ID AND O.OnlineTypeID = OT.ID
                                                AND COD.CompanyID='$this->ID' AND OT.Removed=0" );

        foreach ( $online_array as $onlineItem )
        {
            $return_array[] = new eZOnline( $onlineItem[$db->fieldName( "OnlineID" )] );
        }

        return $return_array;
    }

    /*!
      Adds an online to the current Company.
    */
    function addOnline( &$online )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( is_a( $online, "eZOnline" ) )
        {
            $onlineID =& $online->id();
            $url = strtolower( $online->url() );
            $res[] = $db->query( "INSERT INTO eZContact_CompanyOnlineDict
                                  (CompanyID, OnlineID)
                                  VALUES
                                  ('$this->ID', '$onlineID')" );
            $res[] = $db->query( "INSERT INTO eZContact_CompanyIndex
                                  (CompanyID, Value, Type)
                                  VALUES
                                  ('$this->ID', '$url', '2')" );
            $ret = true;
        }
        eZDB::finish( $res, $db );
        return $ret;
    }

    /*!
      Delete all onlines and the relation to the eZContact_Company
    */
    function removeOnlines()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->array_query( $online_array, "SELECT OnlineID FROM eZContact_CompanyOnlineDict
                                          WHERE CompanyID='$this->ID' " );

        foreach ( $online_array as $onlineItem )
        {
            $onlineID =& $onlineItem[$db->fieldName( "OnlineID" )];
            eZOnline::delete( $onlineID );
        }

        $res[] = $db->query( "DELETE FROM eZContact_CompanyOnlineDict WHERE CompanyID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZContact_CompanyIndex WHERE CompanyID='$this->ID' AND Type='2'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Adds a image to the current
     */
    function addImage( &$image )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();
        $db->begin();
        if ( is_a( $image, "eZImage" ) )
        {
            $imageID =& $image->id();

            $res[] = $db->query( "INSERT INTO eZContact_CompanyImageDict
                                  (CompanyID, ImageID)
                                  VALUES
                                  ('$this->ID', '$imageID')" );
        }
        eZDB::finish( $res, $db );
    }

    /*!
      Returns every image to a product as a array of eZImage objects.
    */
    function &images()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $image_array = array();

        $db->array_query( $image_array, "SELECT ImageID FROM eZContact_CompanyImageDict WHERE CompanyID='$this->ID'" );

        for ( $i = 0; $i < count( $image_array ); $i++ )
        {
            $return_array[$i] = new eZImage( $image_array[$i][$db->fieldName( "ImageID" )], false );
        }

        return $return_array;
    }

    /*!
      Delete all images for this company.
    */
    function removeImages()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_CompanyImageDefinition WHERE CompanyID='$this->ID'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the logo image of the company as a eZImage object.
    */
   static public function logoImage( $id = false )
    {
        if ( !$id )
            $id = 0;

        $ret = false;
        $db =& eZDB::globalDatabase();

        $db->array_query( $res_array, "SELECT * FROM eZContact_CompanyImageDefinition
                                       WHERE CompanyID='$id'" );

        if ( count( $res_array ) == 1 )
        {
            if ( $res_array[0][$db->fieldName( "LogoImageID" )] != "NULL"
            and $res_array[0][$db->fieldName( "LogoImageID" )] != "0" )
            {
                $ret = new eZImage( $res_array[0][$db->fieldName( "LogoImageID" )], false );
            }
        }
        return $ret;
    }

    /*!
      Sets the logo image for the company.

      The argument must be a eZImage object.
    */
    function setLogoImage( &$image, $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        if ( is_a( $image, "eZImage" ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();

            $imageID =& $image->id();

            $db->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZContact_CompanyImageDefinition
                                           WHERE CompanyID='$id'" );

            if ( $res_array[0][ $db->fieldName( "Number" ) ] == "1" )
            {
                $res[] = $db->query( "UPDATE eZContact_CompanyImageDefinition
                                      SET
                                      LogoImageID='$imageID'
                                      WHERE
                                      CompanyID='$id'" );
            }
            else
            {
                $res[] = $db->query( "INSERT INTO eZContact_CompanyImageDefinition
                                      (CompanyID, LogoImageID)
                                      VALUES
                                      ('$id', '$imageID')" );
            }
            eZDB::finish( $res, $db );
        }
    }

    /*!
      Deletes the image for the company.
    */
    function deleteImage( $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "UPDATE eZContact_CompanyImageDefinition
                              SET CompanyImageID='0' WHERE CompanyID='$id'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Deletes the logo for the company.
    */
    function deleteLogo( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "UPDATE eZContact_CompanyImageDefinition
                              SET LogoImageID='0' WHERE CompanyID='$id'" );
        eZDB::finish( $res, $db );
    }


    /*!
      Sets the company image for the company.

      The argument must be a eZImage object.
    */
    function setCompanyImage( &$image, $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        if ( is_a( $image, "eZImage" ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();

            $imageID =& $image->id();

            $db->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZContact_CompanyImageDefinition
                                           WHERE CompanyID='$id'" );

            if ( $res_array[0][$db->fieldName( "Number" )] == "1" )
            {
                $res[] = $db->query( "UPDATE eZContact_CompanyImageDefinition
                                      SET
                                      CompanyImageID='$imageID'
                                      WHERE
                                      CompanyID='$id'" );
            }
            else
            {
                $res[] = $db->query( "INSERT INTO eZContact_CompanyImageDefinition
                                      (CompanyID, CompanyImageID)
                                      VALUES
                                      ('$id', '$imageID')" );
            }
            eZDB::finish( $res, $db );
        }
    }


    /*!
      Returns the image of the company as a eZImage object.
    */
    static public function companyImage( $id = false )
    {
        if ( !$id )
            $id = 0;
        $ret = false;
        $db =& eZDB::globalDatabase();

        $db->array_query( $res_array, "SELECT * FROM eZContact_CompanyImageDefinition
                                       WHERE CompanyID='$id'" );

        if ( count( $res_array ) == 1 )
        {
            if ( $res_array[0][$db->fieldName( "CompanyImageID" )] != "NULL"
            and $res_array[0][$db->fieldName( "CompanyImageID" )] != "0" )
            {
                $ret = new eZImage( $res_array[0][$db->fieldName( "CompanyImageID" )], false );
            }
        }

        return $ret;
    }


    /*!
      Sets the name of the company.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the comment of the company.
    */
    function setComment( $value )
    {
        $this->Comment = $value;
    }

    /*!
      Sets the creatorID of the company.
    */
    function setCreatorID( &$user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $userID =& $user->id();
            $this->CreatorID = $userID;
        }
    }

    /*!
      Sets the contact type of the company.
    */
    function setCompanyNo( $value )
    {
        $this->CompanyNo = $value;
    }

    /*!
      Returnerer ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returnerer firmanavn.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returnerer ID til eier av firma ( brukeren som opprettet det ).
    */
    function creatorID()
    {
        return $this->CreatorID;
    }

    /*!
      Returnerer kommentar.
    */
    function comment()
    {
        return $this->Comment;
    }

    /*!
      Returns Company no.
    */
    function companyNo()
    {
        return $this->CompanyNo;
    }


    /*!
        Set the contact for this object to $value.
     */
    function setContact( $value )
    {
        $this->ContactID = $value;
        $this->ContactType = "ezuser";
    }

    /*!
      Returns the contact for this company.
    */
    function contact()
    {
        return $this->ContactID;
        $this->ContactType = "ezuser";
    }

    /*!
        Set the person contact for this object to $value.
     */
    function setPersonContact( $value )
    {
        $this->ContactID = $value;
        $this->ContactType = "ezperson";
    }

    /*!
      Returns the contact for this company.
    */
    function contactType()
    {
        return $this->ContactType;
    }

    /*
      Henter ut alle firma i databasen hvor en eller flere tilh�rende personer
      inneholder s�kestrengen.
    */
    function &searchByPerson( $query )
    {
        $db =& eZDB::globalDatabase();
        $query = $db->escapeString( $query );
        $company_array = array();
        $return_array = array();

        $db->array_query( $company_array,
                          "SELECT eZContact_Company.ID as ID
                           FROM eZContact_Company, eZContact_Person
                           WHERE ((eZContact_Person.FirstName LIKE '%$query%' OR eZContact_Person.LastName LIKE '%$query%')
                           AND eZContact_Company.ID=eZContact_Person.Company) GROUP BY eZContact_Company.ID ORDER BY eZContact_Company.ID" );

        foreach ( $company_array as $companyItem )
        {
            $return_array[] = new eZCompany( $companyItem[$db->fieldName( "ID" )] );
        }
        return $return_array;
    }

    /*!
      Returns the project state of this company.
    */
    function &projectState()
    {
        $ret = "";

        $db =& eZDB::globalDatabase();

        $checkQuery = "SELECT ProjectID FROM eZContact_CompanyProjectDict WHERE CompanyID='$this->ID'";
        $db->array_query( $array, $checkQuery, 0, 1 );

        if ( count( $array ) == 1 )
        {
            $ret =& $array[0][$db->fieldName( "ProjectID" )];
        }

        return $ret;
    }

    /*!
      Returns the project state of this company.
    */
    function setProjectState( $value )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_CompanyProjectDict WHERE CompanyID='$this->ID'" );

        if ( is_numeric( $value )  )
        {
            if ( $value > 0 )
            {
                $checkQuery = "INSERT INTO eZContact_CompanyProjectDict
                               (CompanyID, ProjectID)
                               VALUES
                               ('$this->ID', '$value')";
                $res[] = $db->query( $checkQuery );
            }
        }
        eZDB::finish( $res, $db );
    }

    /*!
      Makes the person a part of the company.
    */
    function removePersons( $companyid = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$companyid )
            $companyid = $this->ID;

        $res[] = $db->query( "DELETE FROM eZContact_CompanyPersonDict
                              WHERE CompanyID='$companyid'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Makes the person a part of the company.
    */
    function addPerson( $personid, $companyid = false )
    {
        $db =& eZDB::globalDatabase();
        if ( is_a( $personid, "eZPerson" ) )
            $personid = $personid->id();
        if ( !$companyid )
            $companyid = $this->ID;

        $db->begin();

        $res[] = $db->query( "DELETE FROM eZContact_CompanyPersonDict
                              WHERE CompanyID='$companyid' AND PersonID='$personid'" );
        $res[] = $db->query( "INSERT INTO eZContact_CompanyPersonDict
                              (PersonID, CompanyID)
                              VALUES
                              ('$personid', '$companyid')" );
        eZDB::finish( $res, $db );

    }

    /*!
      Returns the number of persons related to this company
    */
    function personCount( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->query_single( $arr, "SELECT count( PersonID ) AS Count
                                  FROM eZContact_CompanyPersonDict
                                  WHERE CompanyID='$id'" );
        return $arr[$db->fieldName( "Count" )];
    }

    /*!
      Returns an array of persons related to this company
    */
    static public function persons( $id = false, $as_object = true, $limit = -1, $offset = 0 )
    {
        if ( !$id )
            $id = $this->ID;
        if ( $limit >= 0 )
        {
            $limit_array = array( "Limit" => $limit, "Offset" => $offset );
        }
        else
            $limit_array = false;
        $db =& eZDB::globalDatabase();
        $db->array_query( $arr, "SELECT CPD.PersonID
                                 FROM eZContact_CompanyPersonDict AS CPD, eZContact_Person AS P
                                 WHERE CPD.CompanyID='$id' AND CPD.PersonID=P.ID
                                 ORDER BY P.LastName, P.FirstName",  $limit_array );
        $ret = array();
        if ( $as_object )
        {
            foreach ( $arr as $row )
            {
                $ret[] = new eZPerson( $row[ $db->fieldName( "PersonID" ) ] );
            }
        }
        else
        {
            foreach ( $arr as $row )
            {
                $ret[] = $row[ $db->fieldName( "PersonID" ) ];
            }
        }
        return $ret;
    }

    /*!
      Adds another view hit for this company for this day.
    */
    function addViewHit( $company_id = false )
    {
        if ( !$company_id )
            $company_id = $this->ID;
        $db =& eZDB::globalDatabase();
        $timestamp = (new eZDateTime())->timeStamp( true );
        $db->array_query( $qry_array, "SELECT ID FROM eZContact_CompanyView
                                       WHERE Date='$timestamp' AND
                                             CompanyID='$company_id'", 0, 1 );
        if ( count( $qry_array ) == 1 )
        {
            $id = $qry_array[0][$db->fieldName( "ID" )];
            $res[] = $db->query( "UPDATE eZContact_CompanyView
                                  SET Count=Count+1, Date='$timestamp'
                                  WHERE ID='$id'" );
        }
        else
        {
            $db->lock( "eZContact_CompanyView" );
            $nextID = $db->nextID( "eZContact_CompanyView", "ID" );
            $res[] = $db->query( "INSERT INTO eZContact_CompanyView
                                  (ID, CompanyID, Count, Date)
                                  VALUES
                                  ('$nextID', '$company_id', '1', '$timestamp')" );
            $db->unlock();
        }
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the total number of views for this company.
    */
    function totalViewCount()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $row, "SELECT sum( Count ) AS Count FROM eZContact_CompanyView
                                  WHERE CompanyID='$this->ID'" );
        return $row[$db->fieldName( "Count" )];
    }

    /*!
      Returns array of months in a year, where each month contains
      the total view count for that month.
      Each month is an array with these keys:
      count: The total count for the month
      month: The month number (starting at 1)
    */
    function &yearViewCounts( $year = false )
    {
        $ret = array();
        if ( is_bool( $year ) )
        {
            $date = new eZDate();
            $year = $date->year();
        }
        for ( $i = 0; $i < 12; $i++ )
        {
            $month_num = $i + 1;
            $date = new eZDate( $year, $month_num, 1 );
            $count = $this->viewCount( $date, "month" );
            $month = array( "count" => $count,
                            "month" => $month_num );
            $ret[] = $month;
        }
        return $ret;
    }

    /*!
      Returns the number of views for this company on a given date or date range,
      default is this day. Date ranges are either "day", "month" or "year"
    */
    function viewCount( $date = false, $type = "day" )
    {
        if ( is_bool( $date ) )
            $date = new eZDate();
        $year = $date->year();
        $month = $date->month();
        if ( $month < 10 )
            $month = "0" . $month;
        $day = $date->day();
        if ( $day < 10 )
            $day = "0" . $day;
        switch ( $type )
        {
            case "year":
            {
                $date = "$year-%";
                break;
            }
            case "month":
            {
                $date = $year . "-" . $month . "-%";
                break;
            }
            default:
            case "day":
            {
                $date = "$year-$month-$day";
                break;
            }
        }
        $db =& eZDB::globalDatabase();
        $db->query_single( $row, "SELECT sum( Count ) as Count, count( Count ) as Num
                                  FROM eZContact_CompanyView
                                  WHERE CompanyID='$this->ID' AND
                                        Date LIKE '$date'" );
        if ( $row[$db->fieldName( "Num" )] == 0 )
            return false;
        return $row[$db->fieldName( "Count" )];
    }

    var $ID;
    var $CreatorID;
    var $Name;
    var $Comment;
    var $Online;
    var $ContactID;
    var $PersonContactID;
    var $CompanyNo;
    var $ContactType;
}

?>