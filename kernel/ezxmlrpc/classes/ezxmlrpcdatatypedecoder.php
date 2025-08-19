<?php
// 
// $Id: ezxmlrpcdatatypedecoder.php 8492 2001-11-16 16:21:00Z bf $
//
// Definition of eZXMLRPCDataTypeDecoder class
//
// B�rd Farstad <bf@ez.no>
// Created on: <01-Jan-2001 14:33:43 bf>
//
// This source file is part of Exponential Basic, publishing software.
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
//! eZXMLRPCDataTypeDecoder handles decoding of XML-RPC data types.
/*!
  
*/

class eZXMLRPCDataTypeDecoder
{
    /*!
      Creates a new eZXMLRPCDataTypeDecoder object.
    */
    function __construct(  )
    {
    }

    /*!
      \private
      Decodes the datatypes from the XML-RPC stream and returns the appropriate value as
      an eZXMLRPC datatype object.
     */
    function &decodeDataTypes( $value )
    {
        $result = 0;
        // check the type
        foreach ( $value->children as $type )
        {
            switch ( $type->name )
            {
                // if no type is specified make it a string
                case "#text" :
                case "text" :
                case "string" :
                {                                    
                    $result =& $this->decodeString( $type );
                }
                break;

                case "i4" :
                case "int" :
                {
                    $result =& $this->decodeInt( $type );
                }
                break;

                case "double" : 
                {
                    $result =& $this->decodeDouble( $type );
                }
                break;
                                
                case "boolean" :
                {
                    $result =& $this->decodeBoolean( $type );
                }
                break;

                case "base64" :
                {
                    $result =& $this->decodeBase64( $type );
                }
                break;

                case "dateTime.iso8601" :
                {
                    $result =& $this->decodeDateTime( $type );
                }
                break;

                case "array" :
                {
                    $result =& $this->decodeArray( $type );
                }
                break;

                case "struct" :
                {
                    $result =& $this->decodeStruct( $type );
                }
                break;
            }
        }

        
        return $result;
    }

    /*!
      \private
    */
    function &decodeString( $type )
    {
        $result = 0;
        if ( count( $type->children ) > 0 )
        {
            foreach ( $type->children as $content )
            {
                if ( $content->name == "#text" || $content->name == "text" )
                {
                    $result = new eZXMLRPCString( $content->content );
                }
            }
        }
        else
        {
            $result = new eZXMLRPCString( $type->content );
        }

        return $result;
    }
    
    /*!
      \private
    */
    function &decodeInt( $type )
    {
        $result = 0;
        foreach ( $type->children as $content )
        {
            if ( $content->name == "#text" || $content->name == "text" )
            {
                $result = new eZXMLRPCInt( $content->content );
            }
        }
        return $result;        
    }

    /*!
      \private
    */
    function &decodeDouble( $type )
    {
        $result = 0;
        foreach ( $type->children as $content )
        {
            if ( $content->name == "#text" || $content->name == "text" )
            {
                $result = new eZXMLRPCDouble( $content->content );
            }
        }
        return $result;
    }

    /*!
      \private      
    */
    function decodeBoolean( $type )
    {
        $result = 0;
        foreach ( $type->children as $content )
        {
            if ( $content->name == "#text" || $content->name == "text" )
            {
                $bool = new eZXMLRPCBool( );
                $bool->decode( $content->content );
                $result =  $bool;
            }
        }
        return $result;        
    }

    /*!
      \private
     */
    function decodeBase64( $type )
    {
        $result = 0;
        foreach ( $type->children as $content )
        {
            if ( $content->name == "#text" || $content->name == "text" )
            {
                $bin = new eZXMLRPCBase64( );
                $bin->decode( $content->content );
                $result = $bin;
            }
        }
        return $result;        
    }

    /*!
      \private
    */
    function decodeDateTime( $type )
    {
        $result = 0;
        foreach ( $type->children as $content )
        {
            if ( $content->name == "#text" || $content->name == "text" )
            {
                $date = new eZXMLRPCDateTime( );
                $date->decode( $content->content );
                $result = $date;
            }
        }
        return $result;
    }

    /*!
      \private
    */
    function decodeArray( $type )
    {
        $array = array();
        if ( count( $type->children )  > 0)
        foreach ( $type->children as $data )
        {
            if ( $data->name == "data" )
            {
                if ( isset( $data->children ) and count( $data->children ) > 0 )
                {
                    foreach ( $data->children as $dataValue )
                    {
                        if ( $dataValue->name == "value" )
                        {
                            $array[] = $this->decodeDataTypes( $dataValue );
                        }
                        if ( $dataValue->name == "array" )
                        {
                            $array[] = $this->decodeDataTypes( $dataValue );
                        }
                    }
                }
            }
        }
        return new eZXMLRPCArray( $array );
    }


    /*!
      \private
    */
    function decodeStruct( $type )
    {
        $array = array();
        if ( count( $type->children ) > 0 )
        foreach ( $type->children as $member )
        {
            if ( $member->name == "member" )
            {
                unset( $memberName );
                unset( $memberData );
                
                foreach ( $member->children as $memberValue )
                {
                    if ( $memberValue->name == "name" )
                    {
                        foreach ( $memberValue->children as $content )
                        {
                            if ( $content->name == "#text" || $content->name == "text" )
                            {
                                $memberName = $content->content;
                            }
                        }
                    }

                    if ( $memberValue->name == "value" )
                    {
                        $memberData = $this->decodeDataTypes( $memberValue );
                    }

//                      if ( $dataValue->name == "array" )
//                      {
//                          $array[] = $this->decodeDataTypes( $dataValue );
//                      }
                    
                }

                $array = array_merge( $array, array( $memberName => $memberData ) );
            }
        }
        return new eZXMLRPCStruct( $array );
    }

}

?>