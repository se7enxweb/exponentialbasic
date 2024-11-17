<?php
//
// $Id: ezxmlrpcarray.php 6261 2001-07-25 10:20:25Z jb $
//
// Definition of eZXMLRPCArray class
//
// B�rd Farstad <bf@ez.no>
// Created on: <18-Dec-2000 14:13:24 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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


//!! eZXMLRPC
//! eZXMLRPCArray hadles encoding and decoding of an XML-RPC array datatype.
/*!

*/

class eZXMLRPCArray
{
    /*!
      Creates a new eZXMLRPCArray object.

      The default value is true.
    */
    function __construct( $array=array(), $type = false, $rec = false )
    {
        $this->Array = $array;
        $this->Type = $type;
        $this->Recursive = $rec;
    }

    /*!
      Returns true if type information is sent recursively to objects.
    */
    function isRecursive()
    {
        return $this->Recursive;
    }

    /*!
      Returns the type which all items are sent as, if false items are probed for type.
    */
    function type()
    {
        return $this->Type;
    }

    /*!
      Set recursive behaviour on or off.
    */
    function setIsRecursive( $rec )
    {
        $this->Recursive = $rec;
    }

    /*!
      Sets type of all items, false means probe for type.
    */
    function setType( $type )
    {
        $this->Type = $type;
    }

    /*!
      This function will encode the sting into a valid XML-RPC value.
    */
    function &serialize( )
    {
        $ret = $this->serializeArray( $this->Array, $this->Type, $this->Recursive );
        return $ret;
    }

    /*!
      Returns the array value.
    */
    function value($arg = -1)
    {
		if ($arg == -1)
        {
	        return $this->Array;
		}
        else
        {
	        return $this->Array[$arg]->Value();
		}
    }

    /*!
      Returns the contents as a regular array.
    */
    function &toArray()
    {
        $arr = array();
        foreach( $this->Array as $item )
        {
            $arr[] = $item->value();
        }
        return $arr;
    }

    /*!
      Decodes the a value.
    */
    function decode( $value )
    {

    }

    /*!
      \private
      \static
    */
    static public function serializeArray( $array, $type = false, $rec = false )
    {
        $ret = "<value><array><data>";
        foreach ( $array as $value )
        {
            $val_type = gettype($value);
            if ( !is_bool( $type ) )
                $val_type = $type;
            switch( $val_type  )
            {
                case "integer":
                {
                    $ret .= "<value><int>$value</int></value>";
                }
                break;

                case "array":
                {
                    if ( $rec )
                        $ret .= eZXMLRPCArray::serializeArray( $value, $type, $rec );
                    else
                        $ret .= eZXMLRPCArray::serializeArray( $value );
                }
                break;

                case "object":
                {
                    if ( substr( strtolower( get_class( $value ), 0, 8 ) ) == "ezxmlrpc" )
                    {
                        if ( is_a( $value, "eZXMLRPCStruct") and
                             $rec )
                        {
                            $value->setIsRecursive( $rec );
                            $value->setType( $type );
                        }
                        $ret .= $value->serialize( $value );
                    }
                }
                break;

                default:
                {
                    $ret .= "<value><string>$value</string></value>";
                }
                break;
            }
        }

        $ret .= "</data></array></value>";
        return $ret;
    }

    // The array value
    var $Array;
    var $Type;
    var $Recursive;
}

?>