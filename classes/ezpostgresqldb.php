<?php
// 
// $Id: ezpostgresqldb.php 9173 2002-02-07 11:23:42Z bf $
//
// Definition of eZPostgreSQLLDB class
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
//! The eZPostgreSQLDB class provides database functions.
/*!
  eZPostgreSQLDB implementes PostgreSQLDB specific database code.   
*/

class eZPostgreSQLDB
{
    function __construct( $server, $db, $user, $password  )
    {
        if ( $GLOBALS["DEBUG"] == true)
        {
            $this->Database = pg_pconnect( "host=$server dbname=$db user=$user password=$password" );
            if ( !$this->Database )
                print( "PostgreSQL error: could not connect to database." );
        }
        else
        {
            $this->Database = @pg_pconnect( "host=$server dbname=$db user=$user password=$password" );
        }
    }

    /*!
      Returns the driver type.
    */
    function isA()
    {
        return "postgresql";
    }

    function &query( $sql )
    {
        if ( $this->Database )
        {
            $result = @pg_exec( $this->Database, $sql );
            
            if ( !$result )
            {
                if ( $GLOBALS["DEBUG"] == true )
                {
                    print( "PostgreSQL error: error executing query: $sql ".
                           pg_errormessage ( $this->Database ) );
                }
            }
        }

        return $result;
    }

    function array_query( &$array, $sql, $min = 0, $max = -1, $column = false )
    {
        $array = array();
        return $this->array_query_append( $array, $sql, $min, $max, $column );
    }
    
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
            $sql .= " LIMIT $limit, $offset";
        }
        
        $result =& $this->query( $sql );

        if ( $result == false )
        {
            if ( $GLOBALS["DEBUG"] == true )
            {
                print( $this->Error );
            }
            eZLog::writeWarning( $this->Error );
            return false;
        }

        $offset = count( $array );

        if ( pg_numrows( $result ) > 0 )
        { 
            if ( !is_string( $column ) )
            {
                for($i = 0; $i < pg_numrows($result); $i++)
                {
                    $array[$i + $offset] =& pg_fetch_array ( $result, $i );
                }
            }
            else
            {
                for($i = 0; $i < pg_numrows($result); $i++)
                {
                    $tmp_row =& pg_fetch_array ( $result, $i );
                    $array[$i + $offset] =& $tmp_row[$column];
                }
            }
        }
    }
    
    /*!
      Same as array_query() but expects to recieve 1 row only (no array), no more no less.
      $column is the same as in array_query().
    */
    function query_single( &$row, $sql, $column = false )
    {
        $array = array();
        $ret = $this->array_query_append( $array, $sql, 1, 1, $column );
        $row = $array[0];
        return $ret;
    }
    

    /*!
      Locks a table
    */
    function lock( $table )
    {
        $this->query( "LOCK TABLE $table" );
    }

    /*!
      Releases table locks. Not needed for PostgreSQL.
    */
    function unlock()
    {
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
        $this->query( "COMMIT WORK" );
    }

    /*!
      Cancels the transaction.
    */
    function rollback()
    {
        $this->query( "ROLLBACK WORK" );
    }
    
    /*!
      Returns the next value which can be used as a unique index value.

      Remeber to lock the table before using this function and inserting the value.
    */
    function nextID( $table, $field="ID" )
    {
        $result = @pg_exec( $this->Database, "SELECT $field FROM $table Order BY $field DESC LIMIT 1" );

        $id = 1;
        if ( $result )
        {
            if ( !pg_numrows( $result ) == 0 )
            {                
                $array = pg_fetch_row( $result, 0 );
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
    function &escapeString( $str )
    {
        $str = str_replace ("'", "\'", $str );
        $str = str_replace ("\"", "\\\"", $str );
        return $str;
    }
    
    /*!
      \static
      Will convert the field name to lower case.
    */      
    function &fieldName( $str )
    {
        return strToLower( $str );
    }

    /*!
      Closes the database connection.
    */
    function close()
    {
        @pg_close();
    }
    
    /// database connection
    var $Database;
}



?>