<?php
//
// $Id: ezuser.php 9798 2003-04-09 13:09:07Z jhe $
//
// Definition of eZUser class
//
// Created on: <26-Sep-2000 18:47:27 bf>
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

//!! eZUser
//! eZUser handles users.
/*!

  Example code:
  \code
  // create a new user and set some variables.
  $user = new eZUser();
  $user->setLogin( "bf" );
  $user->setPassword( "secret" );
  $user->setEmail( "bf@ez.no" );
  $user->setFirstName( "B�rd" );
  $user->setLastName( "Farstad" );
  $user->setSimultaneousLogins( "10" );

  // check if a user with that username exists
  if ( !$user->exists( $user->login() ) )
  {
      // Store the user to the database.
      echo "Username is not used, creating user.<br>";
      $user->store();
  }

  // validate a userlogin and password.
  $user = $user->validateUser( "bf", "secret" );

  if ( $user )
  {
      print( "Password and username are ok!<br>" );
      print( $user->firstName() );
      print( $user->lastName() );
  }

  \endcode

  \sa eZUserGroup eZPermission eZModule eZForgot
*/

// include_once( "classes/ezdb.php" );

// include_once( "ezaddress/classes/ezaddress.php" );
// include_once( "classes/ezdatetime.php" );

// include_once( "ezsession/classes/ezsession.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

class eZUser
{
    /*!
      Constructs a new eZUser object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        $this->InfoSubscription = 0;
        $this->SimultaneousLogins = 0;
        $this->GroupString = false;
        $this->HasRoot = -1;
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
      Stores or updates a eZUser object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );

        $email = $db->escapeString( $this->Email );
        $firstname = $db->escapeString( $this->FirstName );
        $lastname = $db->escapeString( $this->LastName );
        $signature = $db->escapeString( $this->Signature );
        $login = $db->escapeString( $this->Login );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZUser_User" );

            $nextID = $db->nextID( "eZUser_User", "ID" );

            // backwards compatible passwords
            if ( $db->isA() == "mysql" )
            {
                $db->query( "INSERT INTO eZUser_User SET
                                 ID='$nextID',
                                 Login='$login',
                                 Password=PASSWORD('$this->Password'),
                                 Email='$email',
                                 InfoSubscription='$this->InfoSubscription',
                                 FirstName='$firstname',
                                 LastName='$lastname',
                                 Signature='$signature',
                                 CookieLogin='$this->CookieLogin',
                                 SimultaneousLogins='$this->SimultaneousLogins'" );
                $this->ID = $nextID;
            }
            else
            {
                $password = md5( $this->Password );

                $db->query( "INSERT INTO eZUser_User
                ( ID, Login, Password, Email, InfoSubscription, FirstName, LastName, Signature, CookieLogin, SimultaneousLogins )
                VALUES
                ( '$nextID',
                  '$login',
                  '$password',
                  '$email',
                  '$this->InfoSubscription',
                  '$firstname',
                  '$lastname',
                  '$signature',
                  '$this->CookieLogin',
                  '$this->SimultaneousLogins')" );

                $this->ID = $nextID;
            }

        }
        else
        {
            $db->query( "UPDATE eZUser_User SET
                         Login='$login',
                                 Email='$email',
                                 InfoSubscription='$this->InfoSubscription',
                                 FirstName='$firstname',
                                 Signature='$signature',
                                 LastName='$lastname',
                                 CookieLogin='$this->CookieLogin',
                                 SimultaneousLogins='$this->SimultaneousLogins'
                                 WHERE ID='$this->ID'" );

            // update password if set.
            if ( isset( $this->Password ) )
            {
                // backwards compatible passwords
                if ( $db->isA() == "mysql" )
                {
                    $db->query( "UPDATE eZUser_User SET
                                 Password=PASSWORD('$this->Password')
                                 WHERE ID='$this->ID'" );
                }
                else
                {
                    $password = md5( $this->Password );

                    $db->query( "UPDATE eZUser_User SET
                                 Password='$password'
                                 WHERE ID='$this->ID'" );
                }
            }
        }

        $db->unlock();

        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZUser object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZUser_UserGroupLink WHERE UserID='$this->ID'" );
            $db->query( "DELETE FROM eZUser_UserAddressLink WHERE UserID='$this->ID'" );
            $db->query( "DELETE FROM eZUser_UserShippingLink WHERE UserID='$this->ID'" );

            $db->query( "DELETE FROM eZUser_User WHERE ID='$this->ID'" );
        }

        return true;
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
            $db->array_query( $user_array, "SELECT * FROM eZUser_User WHERE ID='$id'", 0, 1 );

            if ( count( $user_array ) == 1 )
            {
                $this->fill( $user_array[0] );
                $ret = true;
            }
            elseif ( count( $user_array ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$user_array )
    {
        $db =& eZDB::globalDatabase();

        $this->ID =& $user_array[$db->fieldName("ID")];
        $this->Login =& $user_array[$db->fieldName("Login")];
        $this->Email =& $user_array[$db->fieldName("Email")];
        $this->InfoSubscription =& $user_array[$db->fieldName("InfoSubscription")];
        $this->FirstName =& $user_array[$db->fieldName("FirstName")];
        $this->LastName =& $user_array[$db->fieldName("LastName")];
        $this->Signature =& $user_array[$db->fieldName("Signature")];
        $this->CookieLogin =& $user_array[$db->fieldName("CookieLogin")];
        $this->SimultaneousLogins =& $user_array[$db->fieldName("SimultaneousLogins")];
    }

    /*!
      Returns the number of rows a getAll with the same search param would give.
    */
    static public function &getAllCount( $search = false )
    {
        $db =& eZDB::globalDatabase();

        $query = new eZQuery( array( "FirstName", "LastName",
                                     "Login", "Email" ), $search );

        if ( $search != false )
            $db->query_single( $user_array, "SELECT count( ID ) AS Count FROM eZUser_User
                                         WHERE " . $query->buildQuery() );
        else
            $db->query_single( $user_array, "SELECT count( ID ) AS Count FROM eZUser_User" );

        return $user_array[$db->fieldName("Count")];
    }

    /*!
      Fetches the user id from the database. And returns a array of eZUser objects.
    */
    static public function &getAll( $order="Login", $as_object = true, $search = false, $max = -1, $index = 0 )
    {
        $db =& eZDB::globalDatabase();

        switch ( $order )
        {
            case "name" :
            {
                $orderBy = "LastName, FirstName";
            }
            break;

            case "lastname" :
            {
                $orderBy = "LastName";
            }
            break;

            case "firstname" :
            {
                $orderBy = "FirstName";
            }
            break;

            case "lastname" :
            {
                $orderBy = "LastName";
            }
            break;

            case "email" :
            {
                $orderBy = "Email";
            }
            break;

            default :
                $orderBy = "Login";
            break;
        }

        $return_array = array();
        $user_array = array();

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        if ( $search )
        {
            $db->array_query( $user_array, "SELECT $select FROM eZUser_User
                                            WHERE FirstName LIKE '%$search%' OR
                                            LastName LIKE '%$search%' OR
                                            Login LIKE '%$search%' OR
                                            Email LIKE '%$search%'
                                            ORDER By $orderBy",
                              array( "Limit" => $max,
                                     "Offset" => $index ) );
        }
        else
        {
            $db->array_query( $user_array, "SELECT $select FROM eZUser_User
                                            ORDER By $orderBy",
                              array( "Limit" => $max,
                                     "Offset" => $index ) );
        }

        if ( $as_object )
        {
            foreach ( $user_array as $user )
            {
                $return_array[] = new eZUser( $user );
            }
        }
        else
        {
            foreach ( $user_array as $user )
            {
                $return_array[] = $user[ $db->fieldName( "ID" ) ];
            }
        }
        return $return_array;
    }

    /*!
      Returns the eZUser object where login = $login.

      False (0) is returned if the users isn't validated.
    */
    static public function getUser( $login )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'" );
        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0][$db->fieldName("ID")] );
        }
        return $ret;
    }

    /*!
      Returns the correct eZUser object if the user is validated.

      False (0) is returned if the user isn't validated.
    */
    function validateUser( $login, $password )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $login = $db->escapeString( $login );
        $password = $db->escapeString( $password );


        if ( $db->isA() == "mysql" )
        {
	    $sqlQuery = "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'
                                                    AND Password=CONCAT('*', UPPER(SHA1(UNHEX(SHA1('$password')))));";
            $db->array_query( $user_array, $sqlQuery );
	}
        else
        {
            $password = md5( $password );

            $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'
                                                    AND Password='$password'" );
        }

        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0][$db->fieldName("ID")] );
            $GLOBALS["eZCurrentUserObject"] =& $ret;
        }
        return $ret;
    }

    /*!
      \static
      Returns the eZUser object if a user with that login exits.

      False (0) is returned if not.
    */
    static public function exists( $login )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'" );

        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0][$db->fieldName("ID")] );
        }

        return $ret;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        if ( isset( $this->ID ) )
            return $this->ID;
    }

    /*!
      Returns the signature.
    */
    function signature()
    {
        return $this->Signature;
    }

    /*!
      Returns the users login.
    */
    function login( $html = true )
    {
        if ( $this->Login != null && $html )
            return htmlspecialchars( $this->Login );
        else
            return $this->Login;
    }


    /*!
      Returns the users InfoSubscription.
    */
    function infoSubscription()
    {
       $ret = false;

       if ( $this->InfoSubscription == 1 )
       {
           $ret = true;
       }
       return $ret;
    }


    /*!
      Returns the users e-mail address.
    */
    function email()
    {
       return $this->Email;
    }

    /*!
      Returns the users name and email where the email is surrounded by <>.
    */
    function namedEmail()
    {
        return $this->name() . " <" . $this->email() . ">";
    }

    /*!
      Returns the users first and last name with a space in between.
    */
    function name()
    {
        return $this->firstName() . " " . $this->lastName();
    }

    /*!
      Returns the users first name.
    */
    function firstName( $html = true )
    {
        if ( isset( $this->FirstName ) && $html )
            return htmlspecialchars( $this->FirstName );
        else
            return $this->FirstName;
    }

    /*!
      Returns the users last name.
    */
    function lastName( $html = true  )
    {
        if ( isset( $this->LastName ) && $html )
            return htmlspecialchars( $this->LastName );
        else
            return $this->LastName;
    }

    /*!
      Returns the auto cookie login value.
    */
    function cookieLogin()
    {
        $ret = false;
        if ( $this->CookieLogin == 1 )
            $ret = true;
        return $ret;
    }


    /*!
      Returns the number og simultaneous logins allowed on this account.
    */
    function simultaneousLogins()
    {
        return htmlspecialchars( $this->SimultaneousLogins );
    }

    /*!
      Sets the signature.
    */
    function setSignature( $value )
    {
       $this->Signature = $value;
    }

    /*!
      Sets the login.
    */
    function setLogin( $value )
    {
       $this->Login = $value;
    }


    /*!
      Sets the password.
    */
    function setPassword( $value )
    {
       $this->Password = $value;
    }

    /*!
      Sets the email address to the user.
    */
    function setEmail( $value )
    {
        $this->Email = $value;
    }

    /*!
      Sets the infoSubscription to the user.

      This value indicates if the user wants to receive updates
      from the site. true and false are valid arguments.
    */
    function setInfoSubscription( $value )
    {
        if ( $value == true )
        {
            $this->InfoSubscription = 1;
        }
        else
        {
            $this->InfoSubscription = 0;
        }
    }

    /*!
      Sets the users first name.
    */
    function setFirstName( $value )
    {
        $this->FirstName = $value;
    }

    /*!
      Sets the users last name.
    */
    function setLastName( $value )
    {
        $this->LastName = $value;
    }

    /*!
      Sets the number of simultaneous connections on this account.
    */
    function setSimultaneousLogins ( $value )
    {
        $this->SimultaneousLogins = $value;

        setType( $this->SimultaneousLogins, "integer" );
    }

    /*!
      Sets the auto cookie login value on this account.
    */
    function setCookieLogin ( $value )
    {
        if ( $value == true )
            $this->CookieLogin = 1;
        else
            $this->CookieLogin = 0;
    }

    function setGroupDefinition( $group )
    {
        if ( is_a( $group, "eZUserGroup" ) )
            $group = $group->ID();

        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZUser_UserGroupDefinition WHERE UserID='" . $this->ID . "'" );
        $db->lock( "eZUser_UserGroupDefinition" );
        $nextID = $db->nextID( "eZUser_UserGroupDefinition", "ID" );
        $res[] = $db->query( "INSERT INTO eZUser_UserGroupDefinition
                              (ID, UserID, GroupID)
                              VALUES
                              ('$nextID', '$this->ID', '$group')" );
        $db->unlock();
        eZDB::finish( $res, $db );
    }

    /*!
      \static
      Logs in the user given argument. The $user argument must be a eZUser object.

      Returns false if unsuccess ful, true if successful.
    */
    static public function loginUser( &$user )
    {
        $ret = false;

        if ( is_a( $user, "eZUser" ) )
        {
            $session =& eZSession::globalSession();

            if ( !$session->fetch() )
            {
                $session->store();
            }

            $session->refresh();
//            $session->refresh();

            $session->setVariable( "AuthenticatedUser", $user->id() );

            $GLOBALS["eZCurrentUserObject"] =& $user;

            $ret = true;
        }
        return $ret;
    }

    /*!
      \static
      Auto login in a user with a cookie. The function check the database for the same hash, if the hash is found the user is logged in.
     */
    static public function autoCookieLogin( $hash )
    {
        $db =& eZDB::globalDatabase();

        if ( $hash )
        {
            $db->array_query( $userArray, "SELECT UserID FROM eZUser_Cookie WHERE Hash='$hash'" );
            if ( count ( $userArray ) == 1 )
            {
                $user = new eZUser( $userArray[0][$db->fieldName("UserID")] );
                if ( $user )
                {
                    eZUser::loginUser( $user );
                    return true;
                }
            }
        }
        return false;
    }

    static public function clearAutoCookieLogin()
    {
        $user =& eZUser::currentUser();
        $db =& eZDB::globalDatabase();

        setCookie( "eZUser_AutoCookieLogin", "", 0, "/",  "", 0 );
        setCookie( "eZUser_AutoCookieLogin" );

        if ( $user )
        {
            $userID = $user->id();
            $db->query( "DELETE FROM eZUser_Cookie WHERE UserID='$userID'" );
        }
    }

    /*!
      \static
      Logs out a user.
    */
    static public function logout()
    {
        $session =& eZSession::globalSession();
        if ( $session->fetch() )
        {
            $session->setVariable( "AuthenticatedUser", "" );
        }
    }



    /*!
      \static
      Returns the current logged on user as a eZUser object. If the user's session timeout
      has timedout the user is logged out.

      If the session timeout is set to 0 it is disabled.

      False is returned if unsuccessful.
    */
    static public function &currentUser()
    {
        $user =& $GLOBALS["eZCurrentUserObject"];

        if ( ( is_a( $user, "eZUser" ) ) and ( is_numeric( $user->id() ) ) )
        {
            return $user;
        }


        $session =& eZSession::globalSession();

        $returnValue = false;

        if ( $session->fetch( false ) )
        {
            $user = new eZUser( $session->variable("AuthenticatedUser" ) );

//            $val =& $session->variable( "AuthenticatedUser" );
//            $user = new eZUser( $val );

//              print( $session->variable( "AuthenticatedUser" ) );

            $idle = $session->idle();
            $idle = $idle / 60;

            if ( ( $idle > $user->timeoutValue() ) && ( $user->timeoutValue() != 0 ) )
            {
                $user->logout();
                $user = false;
            }
            else
            {
                if ( ( $user->id() != 0 ) && ( $user->id() != "" ) )
                {
                    $session->refresh( );
                    $returnValue = $user;
                }
            }
        }

        return $returnValue;
    }


    /*!
      \static
      Returns the currently logged in users with sessions. It is returned as an
      array of eZUser and eZSession objects.
    */
    static public function currentUsers()
    {
        $globalSession =& eZSession::globalSession();

        $ret = array();

        $sessionIDArray =& $globalSession->getByVariable( "AuthenticatedUser" );

        foreach ( $sessionIDArray as $sessionID )
        {
            $session = new eZSession( $sessionID );
            $user = new eZUser( $session->variable( "AuthenticatedUser" ) );

            $idle = $session->idle();
            $idle = $idle / 60;

            if ( ( $idle > $user->timeoutValue() ) && ( $user->timeoutValue() != 0 ) )
            {
                $session->delete( );
            }
            else
            {
                if ( ( $user->id() != 0 ) && ( $user->id() != "" ) )
                {
                    $ret[] = array( $user, $session );
                }
            }
        }

        return $ret;
    }

    /*!
      Returns the current number of logged in users on one userid.
    */
    function getLogins( $userId )
    {
        $userSessionList =& eZUser::currentUsers();
        $logins = 0;
        foreach ( $userSessionList as $userSessionItem )
        {
            if ( $userSessionItem[0]->id() == $userId )
                $logins++;
        }
        return $logins;
    }

    /*!
      Returns the member group id's of the user as a string sorted by id.

      e.g. 1-2-3-4-6
    */
    function groupString()
    {
        $groupStr = "";
        if ( $this->GroupString == false )
        {
            $groupIDArray =& $this->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach ( $groupIDArray as $groupID )
            {
                $first ? $groupStr .= "$groupID" : $groupStr .= "-$groupID";
                $first = false;
            }
            $this->GroupString = $groupStr;
        }

        return $this->GroupString;
    }

    /*!
      Returns the user groups the current user is a member of.
      The result is returned as an array of eZUserGroup objects if $IDOnly = false. If not only an array with the ID's is returned.
    */
    function groups( $as_object = true )
    {
        $ret = array();
        $db =& eZDB::globalDatabase();

        $db->array_query( $user_group_array, "SELECT * FROM eZUser_UserGroupLink
                                                    WHERE UserID='$this->ID'" );

        foreach ( $user_group_array as $group )
        {
            !$as_object ? $ret[] = $group[$db->fieldName( "GroupID" )] : $ret[] = new eZUserGroup( $group[$db->fieldName( "GroupID" )] );
        }

        return $ret;
    }

    function groupDefinition( $as_object = false )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $groups, "SELECT * FROM eZUser_UserGroupDefinition WHERE UserID='" . $this->ID . "'" );

        if ( count( $groups ) > 1 )
            die( "Error in database" );
        else if ( count( $groups ) == 0 )
            return false;
        else if ( $as_object )
            return new eZUserGroup( $groups[0][$db->fieldName( "GroupID" )] );
        else
            return $groups[0][$db->fieldName( "GroupID" )];
    }

    /*!
      Static function..check if the user given has root access
     */
    function hasRootAccess()
    {
        if ( $this->HasRoot < 0 )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $result, "SELECT count( * ) as Count FROM eZUser_UserGroupLink, eZUser_Group
                                                    WHERE eZUser_UserGroupLink.UserID='$this->ID'
                                                    AND eZUser_Group.ID=eZUser_UserGroupLink.GroupID
                                                    AND eZUser_Group.IsRoot='1'" );
            if ( $result[$db->fieldName( "Count" )] > 0 )
                $this->HasRoot = true;
            else
                $this->HasRoot = false;
        }
        return $this->HasRoot;
    }


    /*!
      Removes the user from every user group.
    */
    function removeGroups()
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZUser_UserGroupLink
                                WHERE UserID='$this->ID'" );
    }

    /*!
      Adds an address to the current User.
    */
    function addAddress( $address )
    {
        $db =& eZDB::globalDatabase();
        if ( is_a( $address, "eZAddress" ) )
        {
            $addressID = $address->id();
            $db->begin( );
            $db->lock( "eZUser_UserAddressLink" );

            $nextID = $db->nextID( "eZUser_UserAddressLink", "ID" );

            $res = $db->query( "INSERT INTO eZUser_UserAddressLink
                                ( ID, UserID, AddressID )
                                VALUES
                                ( '$nextID', '$this->ID', '$addressID' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback();
            else
                $db->commit();
        }
    }

    /*!
      Remove address from a user. The function also remove the address from the database.
    */
    function removeAddress( $address )
    {
        $db =& eZDB::globalDatabase();
        if ( is_a( $address, "eZAddress" ) )
        {
            $addressID = $address->id();
            $db->begin( );

            $res = $db->query( "DELETE FROM eZUser_UserAddressLink
                                WHERE AddressID='$addressID'" );

            if ( $res == false )
                $db->rollback();
            else
                $db->commit();

            eZAddress::delete( $addressID );
//              $db->query( "DELETE FROM eZContact_Address
//                                  WHERE ID='$addressID'" );
        }
    }

    /*!
      Remove all addresses from a user. The function also remove the addresses from the database.
      If the $id is supplied it is used for looking up addresses.
    */
    function removeAddresses( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;

        $addresses = $this->addresses( $id, false );
        foreach ( $addresses as $address )
        {
            eZAddress::delete( $address );
        }
        $db->query( "DELETE FROM eZUser_UserAddressLink
                     WHERE UserID='$id'" );
    }

    /*!
      Returns the addresses a user has. It is returned as an array of eZAddress objects.
      If the $id is supplied it is used for looking up addresses.
    */
    function addresses( $id = false, $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        if ( !$id )
            $id = $this->ID;

        $db->array_query( $address_array, "SELECT AddressID FROM eZUser_UserAddressLink
                                WHERE UserID='$id' ORDER BY AddressID" );

        $ret = array();
        if ( $as_object )
        {
            foreach ( $address_array as $address )
            {
                $ret[] = new eZAddress( $address[$db->fieldName( "AddressID" )] );
            }
        }
        else
        {
            foreach ( $address_array as $address )
            {
                $ret[] = $address[$db->fieldName( "AddressID" )];
            }
        }

        return $ret;
    }

    /*!
      Returns the main address the user has. It is returned as an eZAddress objects.
      If the $id is supplied it is used for looking up addresses.
    */
    function mainAddress( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $id = $this->ID;

        $db->array_query( $address_array, "SELECT AddressID FROM eZAddress_AddressDefinition
                                WHERE UserID='$id' ORDER BY AddressID" );

        if (count($address_array)) 
        {
	        if ( $as_object )
	        {
	            if ( $address_array[0][$db->fieldName( "AddressID" )] != 0 )
	                $ret = new eZAddress( $address_array[0][$db->fieldName( "AddressID" )] );
	        }
	        else
	        {
	            $ret = $address_array[0][$db->fieldName( "AddressID" )];
	        }
        	return $ret;
        }
        return null;
    }

    function trustees( $id = -1, $as_object = false )
    {
        $db =& eZDB::globalDatabase();
        if ( is_a( $id, "eZUser" ) )
            $id = $id->ID();
        if ( $id < 0 )
            $id = $this->ID;
        $select = $as_object ? "*" : "UserID";
        $db->array_query( $trusteeArray, "SELECT $select FROM eZUser_Trustees WHERE OwnerID='$id'" );
        $ret = array();
        if ( $as_object )
        {
            foreach ( $trusteeArray as $trustee )
            {
                $ret[] = new eZUser( $trustee[ $db->fieldName( "UserID" ) ] );
            }
        }
        else
        {
            foreach ( $trusteeArray as $trustee )
            {
                $ret[] = $trustee[ $db->fieldName( "UserID" ) ];
            }
        }
        return $ret;
    }

    function getByTrustee( $id = -1, $as_object = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !is_integer( $id ) && get_class( $id ) )
            $id = $id->ID();
        if ( $id < 0 )
            $id = $this->ID;
        $select = $as_object ? "*" : "OwnerID";
        $db->array_query( $trusteeArray, "SELECT $select FROM eZUser_Trustees WHERE UserID='$id'" );
        $ret = array();
        if ( $as_object )
        {
            foreach ( $trusteeArray as $trustee )
            {
                $ret[] = new eZUser( $trustee[ $db->fieldName( "OwnerID" ) ] );
            }
        }
        else
        {
            foreach ( $trusteeArray as $trustee )
            {
                $ret[] = $trustee[ $db->fieldName( "OwnerID" ) ];
            }
        }
        return $ret;
    }

    function addTrustee( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $user = $user->ID();
        }
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZUser_Trustees" );
        $nextID = $db->nextID( "eZUser_Trustees", "ID" );
        $res[] = $db->query( "INSERT INTO eZUser_Trustees (ID, OwnerID, UserID) VALUES
                              ('$nextID', '" . $this->ID() . "', '$user')" );
        $db->unlock();
        eZDB::finish( $res, $db );
    }

    function removeTrustee( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $user = $user->ID();
        }
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZUser_Trustees WHERE OwnerID='" .
                             $this->ID() . "' AND UserID='$user'" );
        eZDB::finish( $res, $db );
    }

    function setCookieValues()
    {
        $user =& eZUser::currentUser();
        $db =& eZDB::globalDatabase();

        if ( $user )
        {
            $db->begin( );

            $db->lock( "eZUser_Cookie" );
            $nextID = $db->nextID( "eZUser_Cookie", "ID" );

            $userID = $user->id();
            $hash = md5( microTime() );

            $db->query( "DELETE FROM eZUser_Cookie WHERE UserID='$userID'" );
            $res = $db->query( "INSERT INTO eZUser_Cookie ( ID, Hash, UserID )
                                 VALUES ( '$nextID',
                               Hash='$hash',
                               UserID='$userID')" );

            setCookie( "eZUser_AutoCookieLogin", $hash, time()+1209600, "/",  "", 0 );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();

        }
    }

    /*!
      Returns the timeout for the user. If the user is not a member of any user groups the timeout
      is set to 30. If the user is a member of several user groups, the fastest timeout is returned.
    */
    function timeoutValue()
    {
        if ( isset( $this->StoredTimeout ) && is_numeric( $this->StoredTimeout ) )
            return $this->StoredTimeout;

        $ret = 30;

        $db =& eZDB::globalDatabase();
        $db->array_query( $timeout_array, "SELECT eZUser_Group.SessionTimeout
                                                      FROM eZUser_User, eZUser_UserGroupLink, eZUser_Group
                                                      WHERE eZUser_User.ID=eZUser_UserGroupLink.UserID
                                                      AND eZUser_Group.ID=eZUser_UserGroupLink.GroupID
                                                      AND eZUser_User.ID='$this->ID'
                                                      ORDER BY eZUser_Group.SessionTimeout ASC",
                          array( "Limit" => 1 ) );

       if ( count( $timeout_array ) == 1 )
       {
           $ret = $timeout_array[0][$db->fieldName("SessionTimeout")];
       }
       $this->StoredTimeout = $ret;
       return $ret;
    }

    /*!
      Searches for users matching the queryText and returns them as an array of eZUser objects.
     */
    function search( $queryText, $order = false, $LastName="", $FirstName="", $EMail="", $Login="", $match="AND" ) // gwf
    {
        $db =& eZDB::globalDatabase();

        switch ( $order )
        {
            case "name" : $orderBy = "LastName, FirstName"; break;
            case "lastname" : $orderBy = "LastName"; break;
            case "firstname" : $orderBy = "FirstName"; break;
            case "lastname" : $orderBy = "LastName"; break;
            case "email" : $orderBy = "Email"; break;
            default : $orderBy = "Login"; break;
        }

        if ($match =="") $match = "AND";

        $query_ext = "";
        $i=0;
        if ($LastName != "")
        {
        	$i = 1;
        	$query_ext = " LastName LIKE '%$LastName%' ";
        }
        if ($FirstName !="")
        {
        	if ($i == 1) $query_ext .= " $match ";
        	$query_ext .= " FirstName LIKE '%$FirstName%' ";
        	$i = 1;
        }
        if ($EMail !="")
        {
        	if ($i == 1) $query_ext .= " $match ";
        	$query_ext .= " Email LIKE '%$EMail%' ";
        	$i = 1;
        }

        if ($Login !="")
        {
        	if ($i == 1) $query_ext .= " $match ";
        	$query_ext .= " Login LIKE '%$Login%' ";
        	$i = 1;
        }

        if ($i == 0 and $query_ext == "")
        	$query_ext = " Login LIKE '%$queryText%' OR Email LIKE '%$queryText%'
                  			OR FirstName LIKE '%$queryText%' OR LastName LIKE '%$queryText%' ";

        $return_array = array();
        $user_array = array();
        $query = "SELECT * FROM eZUser_User WHERE $query_ext ORDER BY $orderBy";

        $db->array_query( $user_array, $query );
        for ( $i = 0; $i < count( $user_array ); $i++ )
        {
            $return_array[$i] = new eZUser( $user_array[$i][$db->fieldName("ID")] );
        }
        return $return_array;
    }

    var $ID;
    var $Login;
    var $Password;
    var $Email;
    var $FirstName;
    var $LastName;
    var $InfoSubscription;
    var $Signature;
    var $CookieLogin = 0;
    var $SimultaneousLogins;
    var $StoredTimeout;
    var $HasRoot;

    /// string with the member groups, used for storing permissions in cache files
    var $GroupString;
}

?>