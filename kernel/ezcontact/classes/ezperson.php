<?php
//
// $Id: ezperson.php 8022 2001-10-23 13:31:24Z jhe $
//
// Definition of eZPerson class
//
// Created on: <09-Nov-2000 14:52:40 ce>
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

//!! eZContact
//! eZPerson handles a persons belonging in contacts information.
/*!
 This class handles persons in the eZ contact database.
*/

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezdb.php" );
// include_once( "classes/ezquery.php" );
// include_once( "ezaddress/classes/ezaddress.php" );
// include_once( "ezaddress/classes/ezphone.php" );
// include_once( "ezaddress/classes/ezonline.php" );

class eZPerson
{
    /*!
      Constructs a new eZPerson object.

      If $id is set, the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        $this->ContactType = 0;
        if ( !empty( $id ) )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a person to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $birth = "NULL";
        if ( isset( $this->BirthDate ) and $this->BirthDate != "" )
            $birth = "$this->BirthDate";
        $firstname = $db->escapeString( $this->FirstName );
        $lastname = $db->escapeString( $this->LastName );
        $comment = $db->escapeString( $this->Comment );
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZContact_Person" );
            $this->ID = $db->nextID( "eZContact_Person", "ID" );
            $res[] = $db->query( "INSERT INTO eZContact_Person
                                  (ID,
                                   FirstName,
                                   LastName,
                                   Comment,
                                   BirthDate,
                                   ContactTypeID)
                                  VALUES
                                  ('$this->ID',
                                   '$firstname',
                                   '$lastname',
                                   '$comment',
                                   '$birth',
                                   '$this->ContactType')" );
            $db->unlock();
            $firstname = strtolower( $firstname );
            $lastname = strtolower( $lastname );
            $res[] = $db->query( "INSERT INTO eZContact_PersonIndex
                                  (PersonID, Value, Type)
                                  VALUES
                                  ('$this->ID', '$firstname', '0')" );
            $res[] = $db->query( "INSERT INTO eZContact_PersonIndex
                                  (PersonID, Value, Type)
                                  VALUES
                                  ('$this->ID', '$lastname', '0')" );
        }
        else
        {
            $queryText = "UPDATE eZContact_Person SET
                                          FirstName='$firstname',
                                          LastName='$lastname',
	                                      Comment='$comment',
	                                      BirthDate=$birth,
                                          ContactTypeID='$this->ContactType'
                                          WHERE ID='$this->ID'";

            $res[] = $db->query( $queryText );
            /*
            $firstname = strtolower( $firstname );
            $lastname = strtolower( $lastname );
            $queryText = "UPDATE eZContact_PersonIndex SET
                                          Value='$firstname'
                                          WHERE PersonID='$this->ID' AND Type='0'";
            var_dump($queryText);
            $res[] = $db->query( $queryText );
            $res[] = $db->query( "UPDATE eZContact_PersonIndex SET
                                          Value='$lastname'
                                          WHERE PersonID='$this->ID' AND Type='0'" );
            */
        }
        eZDB::finish( $res, $db );
    }


    /*!
      Deletes an eZPerson from the database.
    */
    static public function delete( $id = false )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !$id )
            $id = $this->ID;

        if ( isset( $id ) && is_numeric( $id ) )
        {
            // Delete project state
            eZPerson::setProjectState( false, $id );

            // Delete real world addresses

            $db->array_query( $address_array, "SELECT eZContact_PersonAddressDict.AddressID AS 'DID'
                                               FROM eZAddress_Address, eZContact_PersonAddressDict
                                               WHERE eZAddress_Address.ID=eZContact_PersonAddressDict.AddressID
                                                     AND eZContact_PersonAddressDict.PersonID='$id' " );

            foreach ( $address_array as $addressItem )
            {
                $addressDictID = $addressItem[ $db->fieldName( "DID" ) ];
                $res[] = $db->query( "DELETE FROM eZAddress_Address WHERE ID='$addressDictID'" );
            }
            $res[] = $db->query( "DELETE FROM eZContact_PersonAddressDict WHERE PersonID='$id'" );

            // Delete phone numbers.

            $db->array_query( $phone_array, "SELECT eZContact_PersonPhoneDict.PhoneID AS 'DID'
                                     FROM eZAddress_Phone, eZContact_PersonPhoneDict
                                     WHERE eZAddress_Phone.ID=eZContact_PersonPhoneDict.PhoneID
                                       AND eZContact_PersonPhoneDict.PersonID='$id' " );

            foreach ( $phone_array as $phoneItem )
            {
                $phoneDictID = $phoneItem["DID"];
                $res[] = $db->query( "DELETE FROM eZAddress_Phone WHERE ID='$phoneDictID'" );
            }
            $res[] = $db->query( "DELETE FROM eZContact_PersonPhoneDict WHERE PersonID='$id'" );

            // Delete online address.

            $db->array_query( $online_array, "SELECT eZContact_PersonOnlineDict.OnlineID AS 'DID'
                                     FROM eZAddress_Online, eZContact_PersonOnlineDict
                                     WHERE eZAddress_Online.ID=eZContact_PersonOnlineDict.OnlineID
                                       AND eZContact_PersonOnlineDict.PersonID='$id' " );

            foreach ( $online_array as $onlineItem )
            {
                $onlineDictID = $onlineItem[ $db->fieldName( "DID" ) ];
                $res[] = $db->query( "DELETE FROM eZAddress_Online WHERE ID='$onlineDictID'" );
            }
            $res[] = $db->query( "DELETE FROM eZContact_PersonOnlineDict WHERE PersonID='$id'" );
            $res[] = $db->query( "DELETE FROM eZContact_CompanyPersonDict WHERE PersonID='$id'" );
            $res[] = $db->query( "DELETE FROM eZContact_Person WHERE ID='$id'" );
            $res[] = $db->query( "DELETE FROM eZContact_PersonIndex WHERE PersonID='$id'" );
            $db->array_query( $res_array, "SELECT ID FROM eZTrade_Order WHERE PersonID='$id'" );
            // include_once( "eztrade/classes/ezorder.php" );
            foreach ( $res_array as $order )
            {
                $orderObject = new eZOrder( $order[$db->fieldName( "ID" )] );
                $orderObject->delete();
            }
        }
        eZDB::finish( $res, $db );
        return true;
    }


    /*
      Henter ut person med ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $person_array, "SELECT * FROM eZContact_Person WHERE ID='$id'" );
            if ( count( $person_array ) > 1 )
            {
                die( "Feil: Flere personer med samme ID funnet i database, dette skal ikke v�re mulig. " );
            }
            else if ( count( $person_array ) == 1 )
            {
                $this->ID = $person_array[ 0 ][ $db->fieldName( "ID" ) ];
                $this->FirstName = $person_array[ 0 ][ $db->fieldName( "FirstName" ) ];
                $this->LastName = $person_array[ 0 ][ $db->fieldName( "LastName" ) ];
                $this->ContactType = $person_array[ 0 ][ $db->fieldName( "ContactTypeID" ) ];
                $this->BirthDate = $person_array[ 0 ][ $db->fieldName( "BirthDate" ) ];
                $this->Comment = $person_array[ 0 ][ $db->fieldName( "Comment" ) ];
            }
            if ( $this->BirthDate == "NULL" )
                unset( $this->BirthDate );
        }
    }

    /*
        Fetches the person with the USER ID == $id
     */
    function getByUserID( $id )
    {
        $db =& eZDB::globalDatabase();

        $query = "SELECT PersonID FROM eZContact_UserPersonDict WHERE UserID='$id'";

        $return_item = 0;

        $db->array_query( $person_array, $query );
        foreach ( $person_array as $personItem )
        {
            $return_item = new eZPerson( $personItem[ $db->fieldName( "PersonID" ) ], false );
        }

        return $return_item;
    }

    function getAllCount( $search_types = "", $cond = "all" )
    {
        $db =& eZDB::globalDatabase();

        if ( empty( $search_types ) )
        {
            switch ( $cond )
            {
                case "standalone":
                {
                    $qry = "SELECT Person.ID
                            FROM eZContact_Person AS Person LEFT JOIN eZContact_CompanyPersonDict AS Dict
                            ON Person.ID=Dict.PersonID WHERE Dict.CompanyID IS NULL
                            GROUP BY Person.ID";
                    $db->array_query( $person_array, $qry );
                    return count( $person_array );
                    break;
                }
                case "connected":
                {
                    $qry = "SELECT Person.ID
                            FROM eZContact_Person AS Person LEFT JOIN eZContact_CompanyPersonDict AS Dict
                            ON Person.ID=Dict.PersonID WHERE Dict.CompanyID IS NOT NULL
                            GROUP BY Person.ID";
                    $db->array_query( $person_array, $qry );
                    return count( $person_array );
                    break;
                }
                case "all":
                default:
                {
                    $qry = "SELECT count( ID ) AS Count FROM eZContact_Person";
                    $db->query_single( $persons, $qry );
                    return $persons[ $db->fieldName( "Count" ) ];
                    break;
                }
            }
        }
        else
        {
            switch ( $cond )
            {
                case "standalone":
                {
                    $cond_text = "Dict.CompanyID IS NULL AND";
                    break;
                }
                case "connected":
                {
                    $cond_text = "Dict.CompanyID IS NOT NULL AND";
                    break;
                }
                case "all":
                default:
                {
                    $cond_text = "";
                }
            }
            $query = new eZQuery( array( "A.FirstName", "A.LastName",
                                         "C.Number",
                                         "E.Street1", "E.Street2", "E.Place", "E.Zip",
                                         "Online.Url" ), $search_types );
            $qry = "SELECT A.ID AS ID FROM
                    eZContact_Person AS A LEFT JOIN eZContact_PersonPhoneDict as B
                    ON A.ID=B.PersonID    LEFT JOIN eZAddress_Phone as C
		            ON B.PhoneID=C.ID     LEFT JOIN eZContact_PersonAddressDict AS D
		            ON A.ID=D.PersonID    LEFT JOIN eZAddress_Address AS E
		            ON D.AddressID=E.ID   LEFT JOIN eZContact_PersonOnlineDict AS F
		            ON A.ID=F.PersonID    LEFT JOIN eZAddress_Online AS Online
		            ON F.OnlineID=Online.ID LEFT JOIN eZContact_CompanyPersonDict AS Dict
                    ON A.ID=Dict.PersonID
                    WHERE $cond_text (" .
                    $query->buildQuery() .
                    ")
                    GROUP BY A.ID
                    ORDER BY A.LastName, A.FirstName";
            $db->array_query( $persons, $qry );
            return count( $persons );
        }
    }

    /*!
      Fetches all persons in the database.
    */
    function getAll( $search_types = "", $limit_index = 0, $limit = 1, $cond = "all")
    {
        $db =& eZDB::globalDatabase();
        $person_array = 0;

        if ( $limit >= 0 )
        {
            $limit_array = array( "Offset" => $limit_index, "Limit" => $limit );
        }

        if ( empty( $search_types ) )
        {
            switch ( $cond )
            {
                case "standalone":
                {
                    $qry = "SELECT Person.ID
                            FROM eZContact_Person AS Person LEFT JOIN eZContact_CompanyPersonDict AS Dict
                            ON Person.ID=Dict.PersonID WHERE Dict.CompanyID IS NULL
                            GROUP BY Person.ID";
                    break;
                }
                case "connected":
                {
                    $qry = "SELECT Person.ID
                            FROM eZContact_Person AS Person LEFT JOIN eZContact_CompanyPersonDict AS Dict
                            ON Person.ID=Dict.PersonID WHERE Dict.CompanyID IS NOT NULL
                            GROUP BY Person.ID";
                    break;
                }
                case "all":
                default:
                {
                    $qry = "SELECT ID FROM eZContact_Person ORDER BY LastName, FirstName";
                    break;
                }
            }
            $db->array_query( $person_array, $qry, $limit_array );
        }
        else
        {
            switch ( $cond )
            {
                case "standalone":
                {
                    $cond_text = "Dict.CompanyID IS NULL AND";
                    break;
                }
                case "connected":
                {
                    $cond_text = "Dict.CompanyID IS NOT NULL AND";
                    break;
                }
                case "all":
                default:
                {
                    $cond_text = "";
                }
            }
            $query = new eZQuery( array( "A.FirstName", "A.LastName",
                                         "C.Number",
                                         "E.Street1", "E.Street2", "E.Place", "E.Zip",
                                         "Online.Url" ), $search_types );
            $qry = "SELECT A.ID AS ID FROM
                    eZContact_Person AS A LEFT JOIN eZContact_PersonPhoneDict as B
                    ON A.ID=B.PersonID    LEFT JOIN eZAddress_Phone as C
		            ON B.PhoneID=C.ID     LEFT JOIN eZContact_PersonAddressDict AS D
		            ON A.ID=D.PersonID    LEFT JOIN eZAddress_Address AS E
		            ON D.AddressID=E.ID   LEFT JOIN eZContact_PersonOnlineDict AS F
		            ON A.ID=F.PersonID    LEFT JOIN eZAddress_Online AS Online
		            ON F.OnlineID=Online.ID LEFT JOIN eZContact_CompanyPersonDict AS Dict
                    ON A.ID=Dict.PersonID
                    WHERE $cond_text (" .
                    $query->buildQuery() .
                    ")
                    GROUP BY A.ID
                    ORDER BY A.LastName, A.FirstName";
            $db->array_query( $person_array, $qry, $limit_array );
        }

        $return_array = array();
        foreach ( $person_array as $personItem )
        {
            $return_array[] = new eZPerson( $personItem[$db->fieldName( "ID" )] );
        }
        return $return_array;
    }

    /*!
      Fetches all persons whith first name or last name equal to the query string.
    */
    static public function search( $query )
    {
        $db =& eZDB::globalDatabase();
        $person_array = 0;
        $return_array = array();

        $query = $db->escapeString( strtolower( $query ) );

        $queryString = "SELECT PersonID FROM eZContact_PersonIndex
                        WHERE Value LIKE '%$query%'
                        GROUP BY PersonID;";

        $db->array_query( $person_array, $queryString );

        foreach ( $person_array as $personItem )
        {
            $return_array[] = new eZPerson( $personItem[$db->fieldName( "PersonID" )] );
        }
        return $return_array;
    }

    /*!
      Returns the address that belong to this eZPerson object.
    */
    function addresses()
    {
        $return_array = array();
        $db =& eZDB::globalDatabase();

        $PersonID = $this->ID;


        $db->array_query( $address_array, "SELECT PAD.AddressID
                                           FROM eZContact_PersonAddressDict AS PAD,
                                                eZAddress_Address AS A,
                                                eZAddress_AddressType AS AT
                                           WHERE PAD.AddressID = A.ID
                                                 AND A.AddressTypeID = AT.ID
                                                 AND PAD.PersonID='$PersonID'
                                                 AND AT.Removed=0" );

        foreach ( $address_array as $addressItem )
        {
            $return_array[] = new eZAddress( $addressItem[ $db->fieldName( "AddressID" ) ] );
        }

        return $return_array;
    }

    /*!
      Adds an address to the current Person.
    */
    function addAddress( $address )
    {
        $ret = false;

        $db =& eZDB::globalDatabase();
        if ( is_a( $address, "eZAddress" ) )
        {
            $addressID = $address->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonAddressDict WHERE AddressID='$addressID'";

            $db->array_query( $address_array, $checkQuery );

            $count = count( $address_array );

            if ( $count == 0 )
            {
                $db->begin();
                $res[] = $db->query( "INSERT INTO eZContact_PersonAddressDict
                                      (PersonID, AddressID)
                                      VALUES
                                      ('$this->ID', '$addressID')" );
                eZDB::finish( $res, $db );
            }
            $ret = true;
        }
        return $ret;
    }

    /*!
      Remove all addresses to the current Person.
    */
    function removeAddresses()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $address_array, "SELECT AddressID FROM eZContact_PersonAddressDict
                                           WHERE PersonID='$this->ID'" );
        foreach ( $address_array as $address )
        {
            $id = $address[ $db->fieldName( "AddressID" ) ];
            eZAddress::delete( $id );
        }
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_PersonAddressDict WHERE PersonID='$this->ID'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the phones that belong to this eZPerson object.
    */
    function phones( $personID = false )
    {
        if ( !is_numeric( $personID ) )
            $personID = $this->ID;

        $return_array = array();
        $db =& eZDB::globalDatabase();

        $PersonID = $this->ID;

        $db->array_query( $phone_array, "SELECT PPD.PhoneID
                                         FROM eZContact_PersonPhoneDict AS PPD, eZAddress_Phone AS P,
                                              eZAddress_PhoneType AS PT
                                         WHERE PPD.PhoneID = P.ID AND P.PhoneTypeID = PT.ID
                                               AND PersonID='$PersonID' AND PT.Removed=0" );

        foreach ( $phone_array as $phoneItem )
        {
            $return_array[] = new eZPhone( $phoneItem[$db->fieldName( "PhoneID" )] );
        }

        return $return_array;
    }

    /*!
      Adds an phone to the current Person.
    */
    function addPhone( $phone )
    {
        $ret = false;

        $db =& eZDB::globalDatabase();
        if ( is_a( $phone, "eZPhone" ) )
        {
            $phoneID = $phone->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonPhoneDict WHERE PhoneID='$phoneID'";

            $db->array_query( $phone_array, $checkQuery );

            $count = count( $phone_array );
            if ( $count == 0 )
            {
                $phoneno = strtolower( $phone->number() );
                $db->begin();
                $res[] = $db->query( "INSERT INTO eZContact_PersonPhoneDict
                                      (PersonID, PhoneID)
                                      VALUES
                                      ('$this->ID', '$phoneID')" );
                $res[] = $db->query( "INSERT INTO eZContact_PersonIndex
                                      (PersonID, Value, Type)
                                      VALUES
                                      ('$this->ID', '$phoneno', '1')" );
                eZDB::finish( $res, $db );
            }

            $ret = true;
        }
        return $ret;
    }

    /*!
      Remove all phones to the current Person.
    */
    function removePhones()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $phone_array, "SELECT PhoneID FROM
                                         eZContact_PersonPhoneDict WHERE PersonID='$this->ID'" );
        foreach ( $phone_array as $phone )
        {
            $id = $phone[ $db->fieldName( "PhoneID" ) ];
            eZPhone::delete( $id );
        }
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_PersonPhoneDict WHERE PersonID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZContact_PersonIndex WHERE PersonID='$this->ID' AND Type='1'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the onlines that belong to this eZPerson object.
    */
    function onlines()
    {
        $return_array = array();
        $db =& eZDB::globalDatabase();

        $PersonID = $this->ID;

        $db->array_query( $online_array, "SELECT POD.OnlineID
                                          FROM eZContact_PersonOnlineDict AS POD, eZAddress_Online AS O,
                                               eZAddress_OnlineType AS OT
                                          WHERE POD.OnlineID = O.ID AND O.OnlineTypeID = OT.ID
                                                AND PersonID='$PersonID' AND OT.Removed=0" );

        foreach ( $online_array as $onlineItem )
        {
            $return_array[] = new eZOnline( $onlineItem[$db->fieldName( "OnlineID" )] );
        }

        return $return_array;
    }

    /*!
      Remove all onlines to the current Person.
    */
    function removeOnlines()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $online_array, "SELECT OnlineID FROM eZContact_PersonOnlineDict
                                          WHERE PersonID='$this->ID'" );
        foreach ( $online_array as $online )
        {
            $id = $online[$db->fieldName( "OnlineID" )];
            eZOnline::delete( $id );
        }
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_PersonOnlineDict WHERE PersonID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZContact_PersonIndex WHERE PersonID='$this->ID' AND Type='2'" );
        eZDB::finish( $res, $db );

    }

    /*!
      Returns the email address of the person, returns false if none exists.
    */
    function emailAddress()
    {
        $onlines = $this->onlines();
        if ( count( $onlines ) >= 1 )
        {
            $found_mail = false;
            foreach ( $onlines as $online )
            {
                if ( $online->urlType() == "mailto" )
                {
                    return $online->url();
                }
            }
        }
        return false;
    }

    /*!
      Returns the work phone of the person, returns false if none exists.
    */
    function workPhone()
    {
        $phones = $this->phones( 0 );
        if ( count( $phones ) >= 1 )
        {
            foreach ( $phones as $phone )
            {
                /* FIXME please, don't use contants!!! */
                if ( $phone->phoneTypeID() == 4 ) // 4 = Work phone
                {
                    return $phone->number();
                }
            }
        }
        return false;
    }

    /*!
      Returns the fax phone of the person, returns false if none exists.
    */
    function faxPhone()
    {
        $phones = $this->phones( 0 );
        if ( count( $phones ) >= 1 )
        {
            foreach ( $phones as $phone )
            {
                /* FIXME please, don't use contants!!! */
                if ( $phone->phoneTypeID() == 8 ) // 4 = Fax
                {
                    return $phone->number();
                }
            }
        }
        return false;
    }

    /*!
      Returns the title of the user related to a given company ($companyID)
      \obsolete
    */
    function hasTitle( $companyID )
    {
        $db =& eZDB::globalDatabase();
        $checkQuery = "SELECT Title FROM eZContact_CompanyPersonDict
                                    WHERE CompanyID='$companyID' and PersonID='$this->ID'";

        $title_array = array();

        $db->array_query( $title_array, $checkQuery );

        return count( $title_array ) > 0;
    }

    /*!
      Returns the title of the user related to a given company ($companyID)
      \obsolete
    */
    function title( $companyID )
    {
        $ret = false;

        $db =& eZDB::globalDatabase();
        $checkQuery = "SELECT Title FROM eZContact_CompanyPersonDict
                                    WHERE CompanyID='$companyID' and PersonID='$this->ID'";

        $title_array = array();

        $db->array_query( $title_array, $checkQuery );

        $title = false;

        if ( count( $title_array ) == 1 )
        {
            $title = $title_array[0][$db->fieldName( "Title" )];
        }
        else
        {
            die( "eZPerson::title(): Found " . count( $title_array ) . " titles, expected 1" );
        }
        return $title;
    }

    /*!
      Adds an online to the current Person.
    */
    function addOnline( $online )
    {
        $ret = false;

        $db =& eZDB::globalDatabase();

        if ( is_a( $online, "eZOnline" ) )
        {
            $onlineID = $online->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonOnlineDict WHERE OnlineID='$onlineID'";

            $db->array_query( $online_array, $checkQuery );
            $count = count( $online_array );

            if ( $count == 0 )
            {
                $url = strtolower( $online->url() );
                $db->begin();
                $res[] = $db->query( "INSERT INTO eZContact_PersonOnlineDict
                                      (PersonID, OnlineID)
                                      VALUES
                                      ('$this->ID', '$onlineID')" );
                $res[] = $db->query( "INSERT INTO eZContact_PersonIndex
                                      (PersonID, Value, Type)
                                      VALUES
                                      ('$this->ID', '$url', '2')" );
                eZDB::finish( $res, $db );
            }

            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the user that belong to this eZPerson object.
    */
    function user()
    {
        $return_array = array();
        $db =& eZDB::globalDatabase();

        $db->array_query( $user_array, "SELECT UserID
                                            FROM eZContact_UserPersonDict
                                            WHERE PersonID='$this->ID'" );

        foreach ( $user_array as $userItem )
        {
            $return_array[] = new eZUser( $userItem[ $db->fieldName( "UserID" ) ] );
        }

        return $return_array;
    }

    /*!
      Adds a user to the current Person.
    */
    function addUser( $user )
    {
        $ret = false;

        $db =& eZDB::globalDatabase();

        if ( is_a( $user, "eZUser" ) )
        {
            $userID = $user->id();

            $checkQuery = "SELECT PersonID FROM eZContact_UserPersonDict WHERE UserID=$userID";
            $db->array_query( $user_array, $checkQuery );

            $count = count( $user_array );

            if ( $count == 0 )
            {
                $db->begin();
                $res[] = $db->query( "INSERT INTO eZContact_UserPersonDict
                                      (PersonID, UserID)
                                      VALUES
                                      ('$this->ID', '$userID')" );
                eZDB::finish( $res, $db );
            }
            $ret = true;
        }
        return $ret;
    }

    /*!
        Set the first name of this object to $value.
     */
    function setFirstName( $value )
    {
        $this->FirstName = $value;
    }

    /*!
        Set the last name of this object to $value.
     */
    function setLastName( $value )
    {
        $this->LastName = $value;
    }

    /*!
        Set the comment of this object to $value.
     */
    function setComment( $value )
    {
        $this->Comment = $value;
    }

    /*!
        Set the contact for this object to $value.
     */
    function setContact( $value )
    {
        $this->ContactType = $value;
    }

    /*!
        Set the birth day of this object to $value.
    */
    function setBirthDay( $value )
    {
        $this->BirthDate = $value;
    }

    /*!
        Sets the person to have no birthday
    */
    function setNoBirthDay()
    {
        unset( $this->BirthDate );
    }

    /*!
      Returns the ID of the person.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the first name and the last name of the person.
    */
    function name()
    {
        return $this->firstName() . " " . $this->lastName();
    }

    /*!
      Returns the first name of the person.
    */
    function firstName()
    {
        return $this->FirstName;
    }

    /*!
      Returns the last name of the person.
    */
    function lastName()
    {
        return $this->LastName;
    }

    /*!
      Returns the first name and last name of the person concated together.
    */
    function fullName()
    {
        return $this->FirstName . " " . $this->LastName;
    }

    /*!
      Returns the comment for this person.
    */
    function comment()
    {
        return $this->Comment;
    }

    /*!
      Returns the contact for this person.
    */
    function contact()
    {
        return $this->ContactType;
    }

    /*!
      Returns the project state of this person.
    */
    function projectState()
    {
        $ret = "";

        $db =& eZDB::globalDatabase();

        $checkQuery = "SELECT ProjectID FROM eZContact_PersonProjectDict WHERE PersonID='$this->ID'";
        $db->array_query( $array, $checkQuery, 0, 1 );

        if ( count( $array ) == 1 )
        {
            $ret = $array[0][ $db->fieldName( "ProjectID" ) ];
        }

        return $ret;
    }

    /*!
      Returns the project state of this person.
    */
    static public function setProjectState( $value, $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZContact_PersonProjectDict WHERE PersonID='$id'" );

        if ( is_numeric( $value )  )
        {
            if ( $value > 0 )
            {
                $checkQuery = "INSERT INTO eZContact_PersonProjectDict
                               (PersonID, ProjectID)
                               VALUES
                               ('$id', '$value')";
                $res[] = $db->query( $checkQuery );
            }
        }
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the birthday of this person.
    */
    function birthDate()
    {
        return $this->BirthDate;
    }

    /*!
      Returns true if a birthday is present.
    */
    function hasBirthDate()
    {
        return isset( $this->BirthDate );
    }

    function removeCompanies( $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $res[] = $db->query( "DELETE FROM eZContact_CompanyPersonDict
                              WHERE PersonID='$id'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Returns an array of companies this person is related to.
    */
    static public function companies( $id = false, $as_object = true )
    {
        if ( !$id )
            $id = 0;

        $db =& eZDB::globalDatabase();
        $db->array_query( $arr,
                          "SELECT CPD.CompanyID
                           FROM eZContact_CompanyPersonDict AS CPD, eZContact_Company AS C
                           WHERE CPD.PersonID='$id' AND CPD.CompanyID=C.ID
                           ORDER BY C.Name" );
        $ret = array();
        if ( $as_object )
        {
            foreach ( $arr as $row )
            {
                $ret[] = new eZCompany( $row[ $db->fieldName( "CompanyID" ) ] );
            }
        }
        else
        {
            foreach ( $arr as $row )
            {
                $ret[] = $row[ $db->fieldName( "CompanyID" ) ];
            }
        }
        return $ret;
    }

    /*!
      Sets the logo image for the company.

      The argument must be a eZImage object.
    */
    function setImage( &$image, $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        if ( is_a( $image, "eZImage" ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();

            $imageID =& $image->id();

            $db->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZContact_PersonImageDefinition
                                           WHERE PersonID='$id'" );

            if ( $res_array[0][ $db->fieldName( "Number" ) ] == "1" )
            {
                $res[] = $db->query( "UPDATE eZContact_PersonImageDefinition
                                      SET
                                      LogoImageID='$imageID'
                                      WHERE
                                      PersonID='$id'" );
            }
            else
            {
                $res[] = $db->query( "INSERT INTO eZContact_PersonImageDefinition
                                      (PersonID, LogoImageID)
                                      VALUES
                                      ('$id', '$imageID')" );
            }
            eZDB::finish( $res, $db );
        }
    }

    /*!
      Returns the logo image of the company as a eZImage object.
    */
    static public function image( $id = false )
    {
        if ( !$id )
            $id = 0;

        $ret = false;
        $db =& eZDB::globalDatabase();

        $db->array_query( $res_array, "SELECT * FROM eZContact_PersonImageDefinition
                                       WHERE PersonID='$id'" );

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
      Deletes the image for the company.
    */
    function removeImage( $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "UPDATE eZContact_PersonImageDefinition
                              SET PersonImageID='0' WHERE PersonID='$id'" );
        eZDB::finish( $res, $db );
    }


    var $ID;
    var $FirstName;
    var $LastName;
    var $BirthDate;
    var $ContactType;
    var $Comment;
};

?>