<?php
//
// $Id: ezdb.php 8707 2001-12-10 06:48:59Z jhe $
//
// Definition of eZDB class
//
// Created on: <14-Jul-2000 13:01:15 bf>
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

//!! eZCommon
//! The eZDB class provides database functions.
/*!
  The eZDB class hanles database connections and is a wrapper
  to query functions for the selected database.

  \code
  // fetch the database for the current connection.
  $db =& eZDB::globalDatabase();

  // Create a table
  $db->query( "DROP TABLE TableA" );

  // start a new transaction
  $db->begin( );

  // lock the table
  $db->lock( "TableA" );

  // get the next ID
  $id = $db->nextID( "TableA", "ID" );

  $string =& $db->escapeString( "This is a text string" );

  $count = 100+$i;
  $dateTime = new eZDateTime( 1977, 9, 2, 14, 30, 42 );
  $timeStamp = $dateTime->timeStamp();


  $res1 = $db->query( "INSERT INTO TableA ( ID, Count, Number, Name, DateTime )
                             VALUES ( '$id',
                                      '$count',
                                      '3.14',
                                      '$string',
                                      '$timeStamp' )" );
  $db->unlock();

  if ( $res == false )
     $db->rollback( );
  else
     $db->commit( );


  // fetch some data
  $db->array_query( $array,  "SELECT * FROM TableA ORDER BY ID DESC", array( "Limit" => 2, "Offset" => 2 ) );


  $locale = new eZLocale( "no_NO" );
  foreach ( $array as $row )
  {
    $timeStamp = $row[$db->fieldName("DateTime")];
    $dateTime = new eZDateTime( );

    $dateTime->setTimeStamp( $timeStamp );

     print( $locale->format( $dateTime ).", " .
           "ID: " . $row[$db->fieldName("ID")] . " " .
           $row[$db->fieldName("Count")] . " " .
           nl2br( $row[$db->fieldName("Tekst")] ). " " .
           $row[$db->fieldName("Name")] . " " .
           $row[$db->fieldName("Description")] . " " .
           $row[$db->fieldName("Number")] . " " .
           $row[$db->fieldName("Date")] . "<br/>" );
  }


  // close the database connection
  $db->close();

  // if you need implementation spesific code
  // you can use the isA function.
  // Normally not neded
  if ( $db->isA() == "informix" )
  {
     // Special code for informix.
  }

  \endcode
*/


include_once( "classes/ezlog.php" );
include_once( "classes/INIFile.php" );

class eZDB
{
    /*!
      Constructs a new eZDB object, connects to the database and
      selects the desired table.

      The eZDB constructor takes a .ini file as an argument.
      The second argument defines under what category in the .ini
      file the database information is located.
    */
    function __construct( $iniFile, $category )
    {
        print( "This object should not be created use eZDB::globalDatabase();" );
    }


    /*!
      \static
      Returns a reference to the global database object, if it doesn't exists it is initialized.
      This is safe to call without an object since it does not access member variables.
    */
    static public function &globalDatabase(  )
    {
        $impl =& $GLOBALS["eZDB"];
	if( !$impl === null )
	{
            $class = strtolower( get_class( $impl ) );
	}
	else
	{
	    $class = false;
	}
        if ( $impl == null || !preg_match( "/ez.+?db/", $class ) )
        {
            $ini =& INIFile::globalINI();

            $server =& $ini->read_var( "site", "Server" );
            $db =& $ini->read_var( "site", "Database" );
            $user =& $ini->read_var( "site", "User" );
            $password =& $ini->read_var( "site", "Password" );
            $databaseImplementation =& $ini->read_var( "site", "DatabaseImplementation" );

            switch ( $databaseImplementation )
            {
                case "mysql" :
                {
                    include_once( "classes/ezmysqldb.php" );

                    $impl = new eZMySQLDB( $server, $db, $user, $password );
                }
                break;

                case "postgresql" :
                {
                    include_once( "classes/ezpostgresqldb.php" );

                    $impl = new eZPostgreSQLDB( $server, $db, $user, $password );
                }
                break;

                case "informix" :
                {
                    include_once( "classes/ezinformixdb.php" );
                    $impl = new eZInformixDB( $server, $db, $user, $password );
                }
                break;

                default :
                {
                    print( "Database error: have no support for $databaseImplementation" );
                }
                break;
            }
        }


        return $impl;
    }

    /*!
      \static
      This function rollbacks if the array supplied contains any false values, else it commits.
     */
    static public function finish( &$resultArray, &$db )
    {
        $db->unlock();
        if ( !is_array( $resultArray ) )
            $resultArray = array( $resultArray );

        if ( in_array( false, $resultArray ) )
            $db->rollback();
        else
            $db->commit();
    }

    /// the server to connect to
    var $Server;
    /// the database to use
    var $DB;
    /// the username to use
    var $User;
    /// the password to use
    var $Password;

    // the last error message
    var $Error;
}

