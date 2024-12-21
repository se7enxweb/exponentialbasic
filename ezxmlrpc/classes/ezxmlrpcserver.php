<?php
//
// $Id: ezxmlrpcserver.php 9718 2002-08-28 09:07:16Z jb $
//
// Definition of eZXMLRPCServer class
//
// B�rd Farstad <bf@ez.no>
// Created on: <16-Dec-2000 10:37:45 bf>
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
//! eZXMLRPCServer is a class which creates and handles an XML-RPC server.
/*!
  Sample code for a minimal eZ XML-RPC server:
  \code
  ob_start();

  // include the server
  // include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );

  // include the datatype(s) we need
  // include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );

  $server = new eZXMLRPCServer( );

  $server->registerFunction( "myFunc" );

  // process the server requests
  $server->processRequest();

  function myFunc( )
  {
      $tmp = new eZXMLRPCString( "This command was run by xml rpc" );
      return $tmp;
  }

  ob_end_flush();
  \endcode
  \sa eZXMLRPCClient
*/

/*!TODO
  Implement extensive checks and error messages.

*/

// eZXMLRPC error messages
define( "EZXMLRPC_NO_DOM_PARSER", -1 );

// include_once( "ezxmlrpc/classes/ezxmlrpcfunction.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcresponse.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

// datatypes
// include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcbase64.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcdatetime.php" );

class eZXMLRPCServer
{
    /*!

    */
    function __construct(  )
    {
        global $HTTP_RAW_POST_DATA;
        $this->RawPostData = $HTTP_RAW_POST_DATA;
    }

    /*!
      Processes the XML-RPC request and prints out the
      propper response.
    */
    function processRequest()
    {
        global $HTTP_SERVER_VARS;

        if ( $HTTP_SERVER_VARS["REQUEST_METHOD"] != "POST" )
        {
            print( "Error: this web page does only understand POST methods" );
            exit();
        }

        $call = new eZXMLRPCCall( );
        $call->decodeStream( $this->RawPostData );

        $functionWasFound = false;
        $equalParameterCount = true;
        foreach ( $this->FunctionList as $function )
        {
            if ( $function->name() == $call->methodName() )
            {
                $func = $function->name();

                if ( function_exists( $func ) )
                {
                    $functionWasFound = true;

                    if ( count( $call->parameterList() ) ==
                         count( $function->parameters() ) )
                    {
                        $result = $func( $call->parameterList() );
                    }
                    else
                    {
                        $equalParameterCount = false;
                    }
                }
                else
                {
                    print( "Error: could not find function" );
                }
            }
        }

        if ( is_a( $result, "eZXMLRPCResponse" ) )
        {
            $response =& $result;
        }
        else
        {
            // do the server response
            $response = new eZXMLRPCResponse( );

            if ( $functionWasFound == false )
            {
                $response->setError( 1, "Requested function not found." );
            }

            if ( $equalParameterCount == false )
            {
                $response->setError( 2, "Wrong parameter count for requested function." );
            }

            $response->setResult( $result );
        }

        $payload =& $response->payload();

        Header("Server: eZ xmlrpc server" );
        Header("Content-type: text/xml" );
        Header( "Content-Length: " . strlen( $payload ) );

        ob_end_clean();

        print( $payload );
    }

    /*!
      Registers a new function on the server.

      Returns false if the function could not be registered.
    */
    function registerFunction( $name, $params=array() )
    {
        $func = new eZXMLRPCFunction( $name );

        foreach ( $params as $param )
        {
            $func->addParameter( $param );
        }

        $this->FunctionList[] = $func;
    }


    /// contains the raw HTTP post data
    var $RawPostData;

    /// Contains a list over registered functions
    var $FunctionList;

    /// The last method name request
//      var $MethodName;

//      /// The last method request parameter list
//      var $ParameterList;
}


?>