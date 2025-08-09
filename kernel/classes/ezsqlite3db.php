<?php
//
// $Id: ezsqlite3db.php 9473 2025-08-08 20:20:00Z gb $
//
// Definition of eZSQLite3DB class
//
// Created on: <08-Aug-2025 16:09:31 gb>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2025 7x and eZ Systems.  All rights reserved.
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
//! The eZSQLite3DB class provides database functions.
/*!
  eZSQLite3DB implementes SQLite specific database code.

*/

class eZSQLite3DB
{
    function __construct( $databaseFileName  )
    {
        $this->DB = $db;
        $this->Type = 'sqlite';
        $this->DatabaseFileName = $databaseFileName;

        $ini =& eZINI::instance( 'site.ini' );
        // $databaseFilePath =& $ini->variable( "site", "DabaseSQLiteFile" );

        $this->Database = $this->connect( $this->DatabaseFileName );
        $numAttempts = 1;
        while ( $this->Database == false && $numAttempts < 5 )
        {
            sleep(5);
            $this->Database = $this->connect( $this->DatabaseFileName );
            $numAttempts++;
        }

        if ( $this->Database == false )
        {
            // No reason to continue as nothing will work.
            print( "<H1>Couldn't connect to database</H1>Please try again later or inform the system administrator." );
            exit;
        }

        // WAL mode has better control over concurrency.
        // Source: https://www.sqlite.org/wal.html
        $ret = $this->query( 'PRAGMA journal_mode = wal;' );

        if ( !$ret )
        {
            // No reason to continue as nothing will work.
            print( "<H1>SQLite Error</H1><br />" . mysqli_errno( $this->Database ) . ": " . mysqli_error( $this->Database )."<br /><hr />Please inform the system administrator." );
            exit;
        }
    }

    /*!
     \private
     Opens a new connection to a SQLite database and returns the connection
    */
    private function connect( $fileName )
    {
/*
        print( $sql . PHP_EOL . PHP_EOL );

        $backtrace = debug_backtrace();
        $cleanedBackTrace = array();
        foreach ( $backtrace as $call )
        {
            $item = '';
            if ( isset( $call['class'] ) )
            {
                $item .= $call['class'];
            }

            if ( isset( $call['type'] ) )
            {
                $item .= $call['type'];
            }

            $item .= $call['function'] . " in file " . $call['file'] . " line " . $call['line'];

            //$item .= var_export( $call['args'], true );
            $cleanedBacktrace[] = $item;
        }

        print( implode( PHP_EOL, $cleanedBacktrace ) . PHP_EOL . PHP_EOL );
*/
        $connection = false;
        $error = 0;

        $maxAttempts = $this->connectRetryCount() + 1;
        $waitTime = $this->connectRetryWaitTime();
        $numAttempts = 1;

        $ini = eZINI::instance( 'site.ini' );
        $directoryPath = $ini->variable( 'site', 'DatabaseSQLitePath' );
        $directoryPathFileName = $directoryPath . $fileName;

        while ( ( $connection == false || $error !== 0 ) && $numAttempts <= $maxAttempts )
        {
            $fullPath = $fileName == ':memory:' ? $fileName : $directoryPathFileName;
            // var_dump($databaseFileName);
            if( !file_exists( $directoryPath ) )
                mkdir( $directoryPath, 0775 );

            // var_dump( $fullPath ); echo '<hr>'; // die();

            if( !file_exists( $fullPath ) )
                $fh = fopen($fullPath, 'w') or eZDebug::writeError( "Connection error: Couldn't create database file. Please try again later or inform the system administrator.", "eZSQLite3DB" );
            
            // echo $fullPath; echo "<hr>";
            $connection = new SQLite3( $fullPath );
            eZDebug::writeDebug( $connection );

            if ( $connection )
            {
                $error = $connection->lastErrorCode();
                eZDebug::writeDebug( gettype( $error ) . '(' . ( $error ? 'true' : 'false' ) . ')' );
            }
            $numAttempts++;
        }

        // var_dump( $error ); echo '<hr>';

        if ( $error !== 0 )
        {
            $this->ErrorNumber = $error;
            $this->ErrorMessage = $connection->lastErrorMsg();
            eZDebug::writeError( "Connection error: Couldn't connect to database. Please try again later or inform the system administrator.", "eZSQLite3DB" );
            $this->IsConnected = false;
        }
        else
        {
            $connection->createFunction( 'md5', array( $this, 'md5UDF' ) );
            $this->IsConnected = true;
        }

        return $connection;
    }

    /*!
      Returns the driver type.
    */
    function isA()
    {
        return "sqlite";
    }

    /*!
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function &query( $sql, $print=false, $debug=false )
    {
        if ( $debug == true )
        {
            echo "Executing SQL: $sql<hr>";
            
            // include_once( "kernel/classes/ezbenchmark.php" );
            
            $bench = new eZBenchmark();
            $bench->start();
            
            $result = $this->Database->exec( $sql );
            $bench->stop();
            if ( $bench->elapsed() > 0.01 )
            {
                $GLOBALS["DDD"] .= $sql . "<br>";
                $GLOBALS["DDD"] .= $bench->printResults( true ) . "<br>";
            }

        }
        else
        {
            $result = $this->Database->exec( $sql );
        }

//          eZPBLog::writeNotice( $sql );

        if ( $result === false )
        {
            $errorNum = $this->Database->lastErrorCode();
            $errorMsg = $this->Database->lastErrorMsg();
        }
        else
        {
            $errorNum = 0;
            $errorMsg = '';
        }

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
        $results = $this->array_query_append( $array, $sql, $min, $max, $column );
        return $results;
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
        $retArray = array();
        // echo $sql . "<hr><hr>";
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
        $results = $this->Database->query( $sql );

        if ( $results == false )
        {
            $this->Error = $this->Database->lastErrorCode();
            print( $this->Error );
            // eZPBLog::writeWarning( $this->Error );
            // return false;
        }

        $offset = count( $array );
        if ( $results || is_array( $results ) && count( $results ) > 0 )
        {
            $i = 0;
            while ( $row = $results->fetchArray( SQLITE3_ASSOC ) )
            {
                // eZDebug::accumulatorStart( 'sqlite3_loop', 'sqlite3_total', 'Looping result' );

                // SQLite sometimes gives back column names prefixed with the table name
                // we need to transform the row so that there are no table names
                $transformedRow = array();
                foreach ( $row as $identifier => $value )
                {
                    if ( strpos( $identifier, '.' ) !== false )
                    {
                        $parts = explode( '.', $identifier );
                        $newIdentifier = array_pop( $parts );
                    }
                    else
                    {
                        $newIdentifier = $identifier;
                    }

                    $transformedRow[$newIdentifier] = $value;
                }

                $array[$i + $offset] = is_string( $column ) ? $transformedRow[$column] : $transformedRow;
                $i++;
                // eZDebug::accumulatorStop( 'sqlite3_loop' );
            }
            // $results->finalize();

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

        // var_dump($array); echo "<HR>";echo "<HR>";
        //die('this solution suffers from the same problem.');
        return $array;
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
        //$this->query( "BEGIN IMMEDIATE TRANSACTION;" );
    }

    /*!
      Releases table locks.
    */
    function unlock()
    {
        //$this->query( "UNLOCK TABLES" );
    }

    /*!
      Starts a new transaction.
    */
    function begin()
    {
        $this->query( "BEGIN TRANSACTION;" );
    }

    /*!
      Commits the transaction.
    */
    function commit()
    {
        $this->query( "COMMIT;" );
    }

    /*!
      Cancels the transaction.
    */
    function rollback()
    {
        $this->query( "ROLLBACK;" );
    }

    /*!
      Returns the next value which can be used as a unique index value.

      Remeber to lock the table before using this function and inserting the value.
    */
    function nextID( $table, $field="ID" )
    {
        $result = $this->query( "SELECT $field FROM $table Order BY $field DESC LIMIT 1" );

        $id = 1;
        if ( $result )
        {
            if ( !$this->Database->changes() == 0 )
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
        return $this->Database->escapeString( $str );
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
        $this->Database->close();
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
        print "DatabaseFile: " . $this->Server . "<br>\n";
        print "Database: " . $this->DB . "<br>\n";
        print "Username: " . $this->User . "<br>\n";
        print "Password: " . $this->Password . "<br>\n";
    }

    function counter()
    {
        return $this->Counter;
    }

    function md5( $str )
    {
        return " MD5( $str ) ";
    }

    function md5UDF( $str )
    {
        return md5( $str );
    }

    /**
     * Returns the number of times the db handler should try to reconnect if it fails.
     *
     * @return int
     */
    function connectRetryCount()
    {
        return $this->ConnectRetries;
    }

    /**
     * Returns the number of seconds the db handler should wait before rereconnecting.
     *
     * @return int
     */
    function connectRetryWaitTime()
    {
        return 3;
    }

    var $Server;
    var $DB;
    var $User;
    var $Password;
    var $Database;
    var $Error;
    var $Counter;
    var $DatabaseFileName;
    var $DatabaseSQLitePath;
    var $IsConnected;
    var $ConnectRetries;

}

?>