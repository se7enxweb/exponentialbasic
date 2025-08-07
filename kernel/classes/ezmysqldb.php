<?php
//
// $Id: ezmysqldb.php 9473 2002-04-25 09:11:58Z bf $
//
// Definition of eZMySQLDB class
//
// Created on: <19-Jun-2001 16:09:31 bf>
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
//! The eZMySQLDB class provides database functions.
/*!
  eZMySQLDB implementes MySQL specific database code.

*/

class eZMySQLDB
{
    function __construct( $server, $db, $user, $password  )
    {
        $this->DB = $db;
        $this->Server = $server;
        $this->User = $user;
        $this->Password = $password;

        $ini =& eZINI::instance( 'site.ini' );

        $socketPath =& $ini->variable( "site", "MySQLSocket" );

        if ( trim( $socketPath != "" ) && $socketPath != "disabled" )
        {
            ini_set( "mysql.default_socket", $socketPath );
        }

        $this->Database = mysqli_connect( $server, $user, $password );
        $numAttempts = 1;
        while ( $this->Database == false && $numAttempts < 5 )
        {
            sleep(5);
            $this->Database = mysqli_connect( $server, $user, $password );
            $numAttempts++;
        }

        if ( $this->Database == false )
        {
            // No reason to continue as nothing will work.
            print( "<H1>Couldn't connect to database</H1>Please try again later or inform the system administrator." );
            exit;
        }

        $ret = mysqli_select_db( $this->Database, $db );

        if ( !$ret )
        {
            // No reason to continue as nothing will work.
            print( "<H1>MySQL Error</H1><br />" . mysqli_errno( $this->Database ) . ": " . mysqli_error( $this->Database )."<br /><hr />Please inform the system administrator." );
            exit;
        }
    }

    /*!
      Returns the driver type.
    */
    function isA()
    {
        return "mysql";
    }

    /*!
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function &query( $sql, $print=false, $debug=true )
    {
        if ( $debug )
        {
            // include_once( "kernel/classes/ezbenchmark.php" );
            // echo "Executing SQL: $sql<br>";
            $bench = new eZBenchmark();
            $bench->start();
            $result =& mysqli_query( $this->Database, $sql );

            $bench->stop();
            if ( $bench->elapsed() > 0.01 )
            {
                $GLOBALS["DDD"] .= $sql . "<br>";
                $GLOBALS["DDD"] .= $bench->printResults( true ) . "<br>";
            }

        }
        else
        {
            $result =& mysqli_query( $this->Database, $sql );
        }

//          eZLog::writeNotice( $sql );

        $errorMsg = mysqli_error( $this->Database );
        $errorNum = mysqli_errno( $this->Database );

        if ( $print )
        {
            if ( $debug )
            {
                print( $sql . "<br>");
            }
        }

        if ( $result )
        {
            return $result;
        }
        else
        {
            $this->unlock();
            $this->Error = "<code>" . htmlentities( $sql ) . "</code><br>\n<b>" . htmlentities(mysqli_error( $this->Database)) . "</b>\n" ;
            if ( $debug )
            {
                print( "<b>MySQL Query Error</b>: " . htmlentities( $sql ) . "<br><b> Error number:</b>" . $errorNum . "<br><b> Error message:</b> ". $errorMsg ."<br>" );
            }
            return false;
        }
        mysqli_free_result( $result );
    }

    /*!
      Executes a SELECT query that returns multiple rows and puts the results into the passed
      array as an indexed associative array.  The array is cleared first.  The results start with
      the array start at 0, and the number of results can be found with the count() function.
      The minimum and maximum expected rows can be set by supplying $min and $max,
      default is to allow zero or more rows.
      If a string is supplied to $column it is used for extracting a specific column from the
      query into the resulting array, this is useful when you just want one column from
      the query and don't want to iterate over it afterwards to extract the column.
    */
    function array_query( &$array, $sql, $min = 0, $max = -1, $column = false )
    {
        $array = array();
        return $this->array_query_append( $array, $sql, $min, $max, $column );
    }

    /*!
      Same as array_query() but expects to recieve 1 row only (no array), no more no less.
      $column is the same as in array_query().
    */
    function query_single( &$row, $sql, $column = false )
    {
        $array = array();
        $ret = $this->array_query_append( $array, $sql, 1, 1, $column );
        if ( isset( $array[0] ) )
            $row = $array[0];
        else
            $row = "";
        return $ret;
    }

    /*!
      Differs from the above function only by not creating av empty array,
      but simply appends to the array passed as an argument.
     */
    function array_query_append( &$array, $sql, $min = 0, $max = -1, $column = false )
    {
        $limit = -1;
        $offset = 0;
        // check for array parameters
        if ( is_array( $min ) )
        {
            $params = $min;

            if ( isset( $params["Limit"] ) and is_numeric( $params["Limit"] ) )
            {
                $limit = $params["Limit"];
            }

            if ( isset( $params["Offset"] ) and is_numeric( $params["Offset"] ) )
            {
                $offset = $params["Offset"];
            }
        }

        if ( $limit != -1 )
        {
            $sql .= " LIMIT $offset, $limit ";
        }
        $result =& $this->query( $sql );

        if ( $result == false )
        {
//            print( $this->Error );
            eZLog::writeWarning( $this->Error );
            return false;
        }

        $offset = count( $array );
//          if ( count( $result ) > 0 )

        if ( mysqli_num_rows( $result ) > 0 )
        {
            if ( !is_string( $column ) )
            {
                for($i = 0; $i < mysqli_num_rows($result); $i++)
                {
                    $array[$i + $offset] =& mysqli_fetch_array($result);
                }
            }
            else
            {
                for($i = 0; $i < mysqli_num_rows($result); $i++)
                {
                    $tmp_row =& mysqli_fetch_array($result);
                    $array[$i + $offset] =& $tmp_row[$column];
                }
            }
        }

        if ( count( $array ) < $min )
        {
            $this->Error = "<code>" . htmlentities( $sql ) . "</code><br>\n<b>" .
                                      htmlentities( "Received " . count( $array ) . " rows, minimum is " . (int) $min ) . "</b>\n" ;
        }
        if ( $max >= 0 )
        {
            if ( count( $array ) > $max )
            {
                $this->Error = "<code>" . htmlentities( $sql ) . "</code><br>\n<b>" .
                                          htmlentities( "Received " . count( $array ) . " rows, maximum is $max" ) . "</b>\n" ;
            }
        }
    }


    function dateToNative( &$date )
    {
        $ret = false;
        if ( is_a( $date, "eZDate" ) )
        {
            $ret = $date->year() . "-" . eZDate::addZero( $date->month() ) . "-" . eZDate::addZero( $date->day() );
        }
        else
            print( "Wrong date type, must be an eZDate object." );

        return $ret;
    }

    /*!
      Locks a table
    */
    function lock( $table )
    {
        $this->query( "LOCK TABLES $table WRITE" );
    }

    /*!
      Releases table locks.
    */
    function unlock()
    {
        $this->query( "UNLOCK TABLES" );
    }

    /*!
      Starts a new transaction.
    */
    function begin()
    {
        $this->query( "BEGIN WORK" );
    }

    /*!
      Commits the transaction.
    */
    function commit()
    {
        $this->query( "COMMIT" );
    }

    /*!
      Cancels the transaction.
    */
    function rollback()
    {
        $this->query( "ROLLBACK" );
    }

    /*!
      Returns the next value which can be used as a unique index value.

      Remeber to lock the table before using this function and inserting the value.
    */
    function nextID( $table, $field="ID" )
    {
        $result = mysqli_query( $this->Database, "SELECT $field FROM $table Order BY $field DESC LIMIT 1" );

        $id = 1;
        if ( $result )
        {
            if ( !mysqli_num_rows( $result ) == 0 )
            {
                $array = mysqli_fetch_row( $result );
                $id = $array[0];
                $id++;
            }
            else
                $id = 1;
        }
        return $id;
    }

    /*!
      Will escape a string so it's ready to be inserted in the database.
    */
    function escapeString( $str )
    {
        if( is_null( $str ) )
            return false;
        return mysqli_real_escape_string( $this->Database, $str );
    }

    /*!
      \static
      Will just return the field name.
    */
    function fieldName( $str )
    {
        return $str;
    }

    /*!
      Will close the database connection.
    */
    function close()
    {
        mysqli_close( $this->Database );
    }

    /*
      will be removed
     */
    function insertID()
    {
//        print( "insertid is obsolete" );
        return mysqli_insert_id( $this->Database );
    }

    function printConnection()
    {
        print "Server: " . $this->Server . "<br>\n";
        print "Database: " . $this->DB . "<br>\n";
        print "Username: " . $this->User . "<br>\n";
        print "Password: " . $this->Password . "<br>\n";
    }

    function counter()
    {
        return $this->Counter;
    }


    var $Server;
    var $DB;
    var $User;
    var $Password;
    var $Database;
    var $Error;
    var $Counter;

}

?>