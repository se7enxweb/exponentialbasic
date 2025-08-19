<?
// 
// $Id: ezxmlrpcerrorhandler.php 6023 2001-07-05 09:57:04Z matta $
//
// Definition of eZXMLRPC Error Handler
//
// Matt Allen <ma@investigationmarketplace.com>
// Created on: <05-Jul-2001 19:27:00 ma>
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
   
//	Therse are the available Error Levels
	
//    1		E_ERROR				fatal run-time errors                                           
//    2		E_WARNING 			run-time warnings (non fatal errors)                                                        
//    4		E_PARSE 			compile-time parse errors                                  
//    8		E_NOTICE 			run-time notices (less serious than warnings)                                            
//    16		E_CORE_ERROR 		fatal errors that occur during PHP's initial startup
//    32		E_CORE_WARNING 		warnings (non fatal errors) that occur during PHP's initial startup 
//    64		E_COMPILE_ERROR 	fatal compile-time errors
//    128		E_COMPILE_WARNING 	compile-time warnings (non fatal errors)
//   256		E_USER_ERROR 		user-generated error message
//  512		E_USER_WARNING 		user-generated warning message
//  1024	E_USER_NOTICE 		user-generated notice message



function ezxmlrpcErrorHandler ($errno, $errstr, $errfile, $errline) 
{
	$ret = new eZXMLRPCResponse();
	switch($errno) {
		case 1:
		case 2:
		case 4:
		case 16:
		case 256:
		case 512:
		case 1024:
			$ret->setError("666","PHP Error [$errno] $errstr in $errfile on $errline");
		break;
		default:
			//$ret->setError("6661","PHP Error [$errno] $errstr in $errfile on line $errline");
			unset( $ret );
			return true;
		break;
	}
    $payload =& $ret->payload();
    Header( "Server: eZ xmlrpc server" );
    Header( "Content-type: text/xml" );
    Header( "Content-Length: " . strlen( $payload ) );
    print( $payload );	
	die();
}
?>