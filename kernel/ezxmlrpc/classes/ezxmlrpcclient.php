<?php
//
// $Id: ezxmlrpcclient.php 6972 2001-09-05 12:21:54Z ce $
//
// Definition of eZXMLRPCClient class
//
// B�rd Farstad <bf@ez.no>
// Created on: <16-Dec-2000 10:37:45 bf>
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
//! eZXMLRPCClient is a class which creates and handles an XML-RPC client.
/*!
  \code
    // include_once( "ezxmlrpc/classes/ezxmlrpcclient.php" );
    // include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

    // include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );

    // create a new client
    $client = new eZXMLRPCClient( "php.ez.no", "/ezxmlrpc/server.php" );

    $call = new eZXMLRPCCall( );
    $call->setMethodName( "myFunc" );

    // send the request
    $response = $client->send( $call );

    // print out the results
    $result = $response->result();
    print( "The server returned: " . $result->value() . "<br>" );
  \endcode
  \sa eZXMLRPCServer
*/

// include_once( "ezxmlrpc/classes/ezxmlrpcresponse.php" );

class eZXMLRPCClient
{
    /*!
      Will create a new XML RPC client which will communicate
      with the server given as argument.

      You can specify a port for communication, the default port is
      80.
    */
    function __construct( $server, $path, $port=80  )
    {
        $this->Server = $server;
        $this->Path = $path;
        $this->Port = $port;
        $this->Debug = false;
    }

    /*!
      Turns on or off debug.
    */
    function setDebug( $debug )
    {
        if ( $debug == true )
            $this->Debug = true;
        else
            $this->Debug = false;

    }

    /*!
      Returns the debug value.
    */
    function debug()
    {
        return $this->Debug;
    }

    /*!
      Sets the timeout in seconds for the connect call.
      The default is no timeout.
    */
    function setTimeOut( $timeout )
    {
        $this->TimeOut = $timeout;
    }

    /*!
      Returns the timeout value.
    */
    function timeOut()
    {
        return $this->TimeOut;
    }

    /*!
      Returns the error string.
    */
    function errorString( )
    {
        return $this->ErrorString;
    }

    /*!
      Will connect to the server and return the response as
      a eZXMLRPCResponse object.

      If an error occured false (0) is returned.
    */
    function &send( &$call, $useSSL = false )
    {
        $rawResponse = 0;
        if (!$useSSL || !in_array("curl",get_loaded_extensions()))
        {
            if ( is_a( $call, "eZXMLRPCCall" ) )
            {
                if ( $Timeout != 0 )
                {
                    $fp = fsockopen( $this->Server,
                    $this->Port,
                    &$this->errorNumber,
                    &$this->errorString,
                    $this->TimeOut );
                }
                else
                {
                    $fp = fsockopen( $this->Server,
                    $this->Port,
                    &$this->errorNumber,
                    &$this->errorString );
                }

                $payload =& $call->payload();

                // send the XML-RPC call
                if ( $fp != 0 )
                {
                    $authentification = "";
                    if ( ( $this->login() != "" ) )
                    {
                        $authentification = "Authorization: Basic " . base64_encode( $this->login() . ":" . $this->password() ) . "\r\n" ;
                    }

                    $HTTPCall = "POST " . $this->Path . " HTTP/1.0\r\n" .
                         "User-Agent: eZ xmlrpc client\r\n" .
                         "Host: " . $this->Server . "\r\n" .
                         $authentification .
                         "Content-Type: text/xml\r\n" .
                         "Content-Length: " . strlen( $payload ) . "\r\n\r\n" .
                         $payload;

                    if ( !fputs( $fp, $HTTPCall, strlen( $HTTPCall ) ) )
                    {
                        $this->ErrorString = "<b>Error:</b> could not send the XML-RPC call. Could not write to the socket.";
                        return 0;
                    }
                }

                $rawResponse = "";
                unSet( $rawResponse );

                // fetch the XML-RPC response
                while( $data=fread( $fp, 32768 ) )
                {
                    $rawResponse .= $data;
                }

                print( $rawResponse );
                if ( $this->Debug == true )
                {
                    print( "<pre>" );
                    print( nl2br ( htmlspecialchars( $rawResponse )  ) );
                    print( "</pre>" );
                }

                // close the socket
                fclose( $fp );


            }
        }
        else
        {
            // Call was made with useSSL == true
            // to use this functionality, you must have cURL (curl.haxx.se) installed and compiled into PHP with --with-ssl enabled.
            if ( is_a( $call, "eZXMLRPCCall" ) )
            {
                $URL = "https://".$this->Server.":".$this->Port.$this->Path;
                $ch = curl_init ($URL);
                if ( $Timeout != 0 )
                {
                    curl_setopt ($ch, CURLOPT_TIMEOUT, $this->TimeOut);
                }
                $payload =& $call->payload();
                // send the XML-RPC call
                if ( $ch != 0 )
                {
                    curl_setopt ($ch, CURLOPT_RETURNTRANSFER,1);
                    $HTTPCall = "POST " . $this->Path . " HTTP/1.0\r\n" .
                         "User-Agent: eZ xmlrpc client\r\n" .
                         "Host: " . $this->Server . "\r\n" .
                         "Content-Type: text/xml\r\n" .
                         "Content-Length: " . strlen( $payload )."\r\n";
                    if ($this->Username != "")
                    {
                        $HTTPCall .= "Authorization: Basic " .	base64_encode($this->Username . ":" . $this->Password) . "\r\n";
                    }
                    $HTTPCall .= "\r\n" . $payload;
                    curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, $HTTPCall);
                    unSet( $rawResponse );
                    $rawResponse = curl_exec ($ch);
                    if ( !$rawResponse )
                    {
                        $this->ErrorString = "<b>Error:</b> could not send the XML-RPC call. Could not write to the socket.";
                        return 0;
                    }
                }
                curl_close($ch);
            }
        }

        $response = new eZXMLRPCResponse();
        $response->decodeStream( $rawResponse );

        return $response;
    }

    /*!
      Set the login.
     */
    function setLogin( $value )
    {

        $this->Login = $value;
    }

    /*!
      Set the username.
     */
    function setPassword( $value )
    {
        $this->Password = $value;
    }

    /*!
      Returns the login.
    */
    function login()
    {
        return $this->Login;
    }

    /*!
      Returns the password.
    */
    function password()
    {
        return $this->Password;
    }

    /// The name or IP of the server to communicate with
    var $Server;

    /// The path to the XML-RPC server
    var $Path;

    /// The port of the server to communicate with.
    var $Port;

    /// How long to wait for the call.
    var $TimeOut=0;

    /// The username to use for authentification
    var $Login;

    /// The password to use for authentification
    var $Password;

    /// The error string
    var $ErrorString;

    /// The error number
    var $ErrorNumber;

    /// true if debug output should be shown
    var $Debug;
}


?>