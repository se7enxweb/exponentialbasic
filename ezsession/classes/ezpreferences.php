<?php
//
// $Id: ezpreferences.php 6912 2001-09-04 10:48:49Z fh $
//
// Definition of eZPreferences class
//
// Created on: <18-Jan-2001 13:05:06 bf>
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

//!! eZSession
//! eZPreferences handles preferences variables.
/*!
  If you call preferences when there is no user logged in, it allways returns 0;

  \code
  // include the code
  include_once( "ezsession/classes/ezpreferences.php" );

  // set the preferences
  $preferences = new eZPreferences();
  $preferences->setVariable( "Color", "Blue" );

  // get the preferences
  $preferences = new eZPreferences();
  $color =& $preferences->variable( "Color" );

  if ( $color )
  {
     print( "the user prefers a color: $color" );
  }
  else
  {
     print( "the user has no preferences" );
  }
  \endcode

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );

class eZPreferences
{
    /*!
      Creates a new eZPreferences object.

      It will automatically fetch the currently logged in user.
    */
    function __construct( )
    {
    }


    /*!
      Returns the value of a preferences variable as an array.

      If the variable does not exist 0 (false) is returned.
    */
    function variableArray( $name, $group = false )
    {
        $val =& $this->variable( $name, $group );
        if ( $val != "" )
        {
            $val =& explode( ";", $val );
        }
        else
            $val = array();
        return $val;
    }

    /*!
      Returns the value of a preferences variable.

      If the variable does not exist 0 (false) is returned.
    */
    static public function variable( $name, $group = false )
    {
        $ret = false;
        $user =& eZUser::currentUser();
        if( !$user )
            return 0;

        if ( is_a( $user, "eZUser" ) )
        {
            $db =& eZDB::globalDatabase();
            $userID = $user->id();

            if ( !is_bool( $group ) )
                $group_sql = "GroupName='$group'";
            else
                $group_sql = "GroupName=''";
            $db->array_query( $value_array, "SELECT Value FROM eZSession_Preferences
                                                    WHERE UserID='$userID' AND Name='$name'
                                                    AND $group_sql" );

            if ( count( $value_array ) == 1 )
            {
                $ret = $value_array[0][$db->fieldName("Value")];
            }
        }
        return $ret;
    }

	/*!
      Adds or updates a variable to the preferences.

      Returns false if unsuccessful.
    */
    function setVariable( $name, $value, $group = false )
    {
        $ret = false;
        $user =& eZUser::currentUser();
        if( !$user )
            return 0;

        if ( is_a( $user, "eZUser" ) )
        {
            if ( is_array( $value ) )
            {
                $value =& implode( ";", $value );
            }
            $db =& eZDB::globalDatabase();

            $dbError = false;
            $db->begin( );

            $userID = $user->id();

            $name = addslashes( $name );
            $value = addslashes( $value );
            if ( !is_bool( $group ) )
                $group_sql = "GroupName='$group'";
            else
                $group_sql = "GroupName=''";
            $db->array_query( $value_array, "SELECT ID FROM eZSession_Preferences
                                                    WHERE UserID='$userID' AND Name='$name'
                                                    AND $group_sql" );
            if ( count( $value_array ) == 1 )
            {
                $valueID = $value_array[0][$db->fieldName("ID")];
                $db->query( "UPDATE eZSession_Preferences SET
		                         Value='$value' WHERE ID='$valueID'
                                 " );
                $ret = true;
            }
            else
            {
                if ( is_bool( $group ) )
                    $group = "";
                else
                    $group = "'$group'";

                $db->lock( "eZSession_Preferences" );

                $nextID = $db->nextID( "eZSession_Preferences", "ID" );


                $res = $db->query( "INSERT INTO eZSession_Preferences
                             ( ID, UserID, Name, Value, GroupName )
                             VALUES
                             ( '$nextID', '$userID', '$name', '$value', '$group' )" );

                $db->unlock();

                if ( $res != false )
                    $ret = true;
                else
                    $dbError = true;
            }

            if ( $dbError == true )
                $db->rollback( );
            else
                $db->commit();
        }

        return $ret;
    }
}

?>