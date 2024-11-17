<? 
// 
// $Id: ezinformixdb.php 6200 2001-07-19 11:33:57Z jakobn $
//
// Definition of eZInformixDB class
//
// Created on: <29-Jun-2001 13:17:19 bf>
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
//! The eZInfomixDB class provides the informix database implementation.
/*!
  See the eZDB class for description on usage.

  \sa eZDB
*/

class eZInformixDB
{
    /*!
      Constructor
    */
    function __construct( $server, $db, $user, $password  )
    {
        putenv('INFORMIXSERVER=localhost');
        putenv('INFORMIXDIR=/opt/informix');
        ifx_textasvarchar(1);
        $BlobIDArray = false;        
        $this->Database = ifx_pconnect( "$db@$server", "$user", "$password" );
    }
    
    /*!
      Returns the driver type.
    */
    function isA()
    {
        return "informix";
    }

    /*!
      Sets the informix blob ID array.

      NOTE: this function does only work with informix.      
    */
    function setBlobArray( $array )
    {
        $this->BlobIDArray = $array;
    }     

    /*!
      Executes an informix query
    */
    function query( $sql, $print=false )
    {
        if ( is_array( $this->BlobIDArray ) )
        {
            $result = ifx_query( $sql, $this->Database, $this->BlobIDArray );
            $this->BlobIDArray = false;
        }
        else
            $result = ifx_query( $sql, $this->Database );

        if ( $print )
        {
            print( $sql . "<br>");
        }
        
        if ( $result )
        {
            return $result;
        }
        else
        {
            $this->Error = "<code>" . htmlentities( $sql ) . "</code>". ifx_error() . " " . ifx_errormsg() . "<br>\n" ;

            if ( $GLOBALS["DEBUG"] )
            {
                print( $this->Error );
//                exit();
            }
            return false;
        }
    }


    /*!
      
    */
    function array_query( &$ret_array, $query, $min=0, $max=-1 )
    {
        $limit = -1;
        $offset = 0;
        // check for array parameters
        if ( is_array( $min ) )
        {
            $params = $min;
            
            if ( is_numeric( $params["Limit"] ) )
            {
                $limit = $params["Limit"];
            }

            if ( is_numeric( $params["Offset"] ) )
            {
                $offset = $params["Offset"];
            }

        }
        
        $ret_array = array();
        if ( $offset != 0 )
            $res_id = ifx_prepare( $query, $this->Database, IFX_SCROLL );
        else
            $res_id = ifx_prepare( $query, $this->Database );

        if ( !$res_id )
        {
            print( "Informix error:" . ifx_errormsg( ) );
        }

        $rowcount = ifx_affected_rows( $res_id );

        /*
        if ( $rowcount > 5000 )
        {
            printf ("Too many rows in result set (%d)\n<br>", $rowcount );
            die( "Please restrict your query<br>\n" );
        }
        */

        if ( !ifx_do( $res_id ) )
        {
        }


        if ( $offset != 0 )
        {
            $row = ifx_fetch_row( $res_id, (int)$offset + 1 );
        }
        else
            $row = ifx_fetch_row( $res_id, "NEXT" );

        $i=0;
        while ( is_array( $row )  )
        {
            $ret_array[] = $row;
            $row = ifx_fetch_row( $res_id, "NEXT" );
            
            $i++;
            if ( $limit != -1 )
            {
                if ( $i >= $limit )
                    break;
            }
        }
        ifx_free_result( $res_id );
        
        return $ret_array;
    }

    /*!
      Same as array_query() but expects to recieve 1 row only (no array), no more no less.
      $column is the same as in array_query().
    */
    function query_single( &$row, $sql, $column = false )
    {
        $ret = $this->array_query( $array, $sql, 1, 1, $column );
        $row = $array[0];
        return $ret;
    }


    /*!
      Locks a table
    */
    function lock( $table )
    {
        $this->query( "LOCK TABLE $table IN SHARE MODE" );
    }

    /*!
      Releases table locks.
    */
    function unlock()
    {
        // no unlock code necessary?
//        $this->query( "" );
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
        $this->array_query( $res_array, "SELECT $field FROM $table Order BY $field DESC" );

        $id = 1;
        if ( $res_array )
        {
            if ( !count( $res_array ) == 0 )
            {                
                $id = $res_array[0][$this->fieldName( "id" )];
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
        $str = str_replace ("'", "''", $str );        
        $str = str_replace ("\r", "", $str );        
        return $str;
    }
    
    /*!
      \static
      Will just return the field name.
    */      
    function &fieldName( $str )
    {
        return strToLower( $str );
    }

    /*!
      Will close the database connection.
    */
    function close()
    {
        ifx_close( $this->Database );
    }

    /// database connection
    var $Database;

    /// variable for  blob ID array
    var $BlobIDArray;    
    
}

?>