<?php
//
// $Id: ezxmlrpccall.php 8714 2001-12-10 12:29:48Z bf $
//
// Definition of eZXMLRPCCall class
//
// B�rd Farstad <bf@ez.no>
// Created on: <16-Dec-2000 11:15:16 bf>
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
//! eZXMLRPCCall hadles a XML-RPC server call.
/*!

*/

// include_once( "ezxmlrpc/classes/ezxmlrpcdatatypedecoder.php" );
// include_once( "ezxml/classes/ezxml.php" );

class eZXMLRPCCall
{
    /*!
      Creates a new eZXMLRPCCall object.
    */
    function __construct(  )
    {
        $this->clearParameters( );
    }

    /*!
      Sets the method name.
    */
    function setMethodName( $name )
    {
        $this->MethodName = $name;
    }

    /*!
      Returns the method name.
    */
    function methodName( )
    {
        return $this->MethodName;
    }

    /*!
      Adds a new parameter to the parameter list.

      The parameters can be of the types: eZXMLRPCString, eZXMLRPCInt ...

      If the value is a normal PHP type it will be decoded as a eZXMLRPCString.

      This function returns false if the parameter was not successful added to the
      parameter list.
    */
    function addParameter( $value )
    {
        $ret = false;
        switch ( strtolower( get_class( $value ) ) )
        {
            case "ezxmlrpcstring" :
            {
                $this->ParameterList[] = $value;
                $ret = true;
            }
            break;

            case "ezxmlrpcint" :
            {
                $this->ParameterList[] = $value;
                $ret = true;
            }
            break;

            case "ezxmlrpcdouble" :
            {
                $this->ParameterList[] = $value;
                $ret = true;
            }
            break;

            case "ezxmlrpcarray" :
            {
                $this->ParameterList[] = $value;
                $ret = true;
            }
            break;

            case "ezxmlrpcbase64" :
            {
               $this->ParameterList[] = $value;
               $ret = true;
            }
            break;

            case "ezxmlrpcboolean" :
            {
               $this->ParameterList[] = $value;
               $ret = true;
            }
            break;

            case "ezxmlrpcstruct" :
            {
               $this->ParameterList[] = $value;
               $ret = true;
            }
            break;

            default :
            {
                if ( $value != "Object" )
                {
                    // include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );

                    $string = new eZXMLRPCString( $value );
                    $this->ParameterList[] =& $string;

                    $ret = true;
                }
            }
        }

        return $ret;
    }

    /*!
      Returns the parameter list.
    */
    function parameterList()
    {
        return $this->ParameterList;
    }

    /*!
      Clears the parameter list.
    */
    function clearParameters( )
    {
        $this->ParameterList = array();
    }

    /*!
      Returns the call payload. This is the requst encoded
      as an XML-RPC call.
    */
    function &payload( )
    {
        $parameters = "";
        if ( count( $this->ParameterList ) > 0 )
        {
            $parameters = "<params>\n";

            foreach ( $this->ParameterList as $parameter )
            {
                $parameters .= "<param>\n" .
                     $parameter->serialize() .
                     "</param>\n";
            }

            $parameters .= "</params>";
        }

        $payload = "<?xml version=\"1.0\"?>\n" .
             "<methodCall>\n" .
             "<methodName>" . $this->MethodName . "</methodName>\n" .
             $parameters .
             "</methodCall>\n";

        return $payload;
    }

    /*!
      Decodes the XML-RPC stream.
    */
    function decodeStream( $rawResponse )
    {
        // create a new decoder object
        $decoder = new eZXMLRPCDataTypeDecoder( );

//        $rawResponse = eZXMLRPCResponse::stripHTTPHeader( $rawResponse );

//        eZPBLog::writeNotice( "empty?: " . $rawResponse . "<-" );
//        $domTree =& qdom_tree( $rawResponse );
//        $domTree =& xmltree( $rawResponse );

        $domTree =& eZXML::domTree( $rawResponse, array( "TrimWhiteSpace" => true ) );

//        print_r( $domTree );

        foreach ( $domTree->children as $call )
        {
            if ( $call->name == "methodCall" )
            {
                foreach ( $call->children as $callItem )
                {
                    // method name
                    if ( $callItem->name == "methodName" )
                    {
                        foreach ( $callItem->children as $value )
                        {
                            if ( $value->name == "#text" || $value->name == "text" )
                            {
                                $this->MethodName = $value->content;
                            }
                        }
                    }

                    // parameters
                    if ( $callItem->name == "params" && is_array( $callItem->children ) )
                    {
                        foreach ( $callItem->children as $param )
                        {
                            if  ( $param->name == "param" )
                            {
                                foreach ( $param->children as $value )
                                {
                                    if ( $value->name == "value" )
                                    {
                                        $this->ParameterList[] = $decoder->decodeDataTypes( $value );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    /// The name of the method to call
    var $MethodName;

    /// The parameters to send with the method.
    var $ParameterList;

}

?>