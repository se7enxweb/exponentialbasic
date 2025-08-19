<?php
// 
// $Id: ezquery.php 8166 2001-11-01 15:32:45Z bf $
//
// Definition of eZQuery class
//
// Created on: <15-Sep-2000 14:40:06 bf>
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

//!! eZLink
//! The eZQuery class builds SQL queries.
/*!
  En klasse som h�ndterer SQL queries. Lager query setninger fra
  tekststrenger.
  
  Example code
  \code
  // create a new query and search in the columns Topic and Body
  $query = new eZQuery( array( "Topic", "Body" ), $query );

  // create a select 
  $query_str = "SELECT ID FROM MyTable WHERE (" .
             $query->buildQuery()  .
             ") ORDER BY SomeColumn LIMIT $offset, $limit";

  // do the query
  $db->array_query( $message_array, $query_str );
  \endcode

  \sa eZDB
  
*/

class eZQuery
{
    /*!
      Initializes the query with the fields and the query text.
      The query text can be supplied as a string or as an array.
      If the query text is a string it will be split using spaces as a delimiter.
      If $single_string is true and $queryText is a string the string will not be split up.
      $fields is an array of strings which is used for matching against, it can also be a single
      string in which case it is converted to an array with one element.
    */
    function __construct( $fields, $queryText, $single_string = false )
    {
        if ( !is_array( $fields ) )
            $fields = array( $fields );
        $this->Fields = $fields;
        $this->QueryText = $queryText;
        $this->SingleString = $single_string;
        $this->Literal = false;
        $this->PartialCompare = false;
        $this->StopWordColumn = "";
        $this->StopPercent = "0.5";
    }

    /*!
      Builds the WHERE clause of an SQL sentence and returns it.
    */
    function buildQuery( )
    {
        $QueryText = $this->QueryText;
        if ( is_array( $QueryText ) )
        {
            $queryArray = $QueryText;
            if ( ( isset( $queryArray[0] ) and is_array( $queryArray[0] ) ) or
                 ( isset( $queryArray[1] ) and is_array( $queryArray[1] ) ) or
                 ( isset( $queryArray[2] ) and is_array( $queryArray[2] ) ) )
            {
                $normalArray = $queryArray[0];
                $addArray = array();
                $subArray = array();
                if ( isset( $queryArray[1] ) and is_array( $queryArray[1] ) )
                    $addArray = $queryArray[1];
                if ( isset( $queryArray[2] ) and is_array( $queryArray[2] ) )
                    $subArray = $queryArray[2];
            }
        }
        else if ( $this->SingleString )
        {
            $queryArray = array( $QueryText );
        }
        else
        {
            $QueryText = trim( $QueryText );
            $QueryText = preg_replace( '/\\\\"/', '"', $QueryText );
            preg_match_all( "/((\"[^\"]+\")|([^ ]+))/", $QueryText, $m );
            $queryArray = array();
            foreach( $m[0] as $match )
            {
                $queryArray[] = $match;
            }
        }
        if ( count( $queryArray ) == 0 )
            $queryArray[] = "";

        if ( $this->Literal )
        {
            $normalArray = $queryArray;
            $addArray = array();
            $subArray = array();
        }
        else if ( !isset( $normalArray ) and !isset( $addArray ) and !isset( $subArray ) )
        {
            $normalArray = array();
            $addArray = array();
            $subArray = array();
            foreach( $queryArray as $queryItem )
            {
                switch ( $queryItem[0] )
                {
                    case '-':
                    {
                        $subArray[] = substr( $queryItem, 1 );
                        break;
                    }
                    case '+':
                    {
                        $addArray[] = substr( $queryItem, 1 );
                        break;
                    }
                    default:
                    {
                        $normalArray[] = $queryItem;
                    }
                }
            }
        }

        $like = $this->PartialCompare ? "LIKE" : "=";
        $not_like = $this->PartialCompare ? "LIKE" : "!=";

        $arrs = array( array( "array" => "normalArray", "item_delim" => "OR",
                              "delim" => $this->PartialCompare ? "AND" : "OR", "compare" => $like ),
                       array( "array" => "addArray", "item_delim" => "OR",
                              "delim" => "AND", "compare" => $like ),
                       array( "array" => "subArray", "item_delim" => "AND",
                              "delim" => "AND", "compare" => $not_like ) );

        $partial_sign = $this->PartialCompare ? "%" : "";

        $total_query = "";
        foreach( $arrs as $arr )
        {
            $queryArray = ${$arr["array"]};
            $item_delim = $arr["item_delim"];
            $delim = $arr["delim"];
            $compare = $arr["compare"];
            $query = "";
            for ( $i=0; $i<count($queryArray); $i++ )
            {
                $queryVal = $queryArray[$i];
                if ( strlen( $queryVal ) > 1 and $queryVal[0] == '"' and $queryVal[strlen($queryVal)-1] == '"' )
                {
                    $queryVal = substr( $queryVal, 1, strlen( $queryVal ) - 2 );
                }

                $subquery = "";
                for ( $j=0; $j<count($this->Fields); $j++ )
                {
                    $queryItem = $queryVal;

                    $queryItem = $this->Fields[$j] ." $compare '$partial_sign" . $queryItem . "$partial_sign' ";

                    if ( $j > 0 )
                        $queryItem = $item_delim . " " . $queryItem . " ";

                    if ( $this->StopWordColumn != "" )                        
                        $subquery .= "(". $queryItem . " AND $this->StopWordColumn < '$this->StopPercent' )";
                    else
                        $subquery .= $queryItem;
                        
                }
                $query .= "( $subquery )";

                if ( count( $queryArray) != ($i+1) )
                    $query .= " $delim ";
            }
            if ( count( $queryArray ) )
            {
                if ( !empty( $total_query ) )
                {
                    $total_query = "$total_query $delim ( $query )";
                }
                else
                {
                    $total_query = "( $query )";
                }
            }
        }
        return $total_query;
    }

    /*!
      Returns whether the query is literal or not.
      If it is literal all query items will match as they are.
    */
    function isLiteral()
    {
        return $this->Literal;
    }

    /*!
      Returns whether the query will do a partial compare or not.
    */
    function partialCompare()
    {
        return $this->PartialCompare;
    }

    /*!
      Sets whether the query is literal or not.
    */
    function setIsLiteral( $literal )
    {
        $this->Literal = $literal;
    }

    /*!
      Sets whether the query does a partial compare or not.
    */
    function setPartialCompare( $partial )
    {
        $this->PartialCompare = $partial;
    }

    /*!
      Sets the Stop Word frequency colums; E.g:
      eZArticle_Word.Frequency
    */
    function setStopWordColumn( $value )
    {
        $this->StopWordColumn = $value;
    }

    /*!
      Sets the Stop Word percent  E.g:
      0.5 == 50%
    */
    function setStopWordPercent( $value )
    {
        $this->StopPercent = $value;
    }
    
    var $Fields;
    var $QueryText;
    var $IsLiteral;
    var $PartialCompare;
    
    var $StopWordColumn;
    var $StopPercent;

    var $SingleString;
    var $Literal;
}

?>