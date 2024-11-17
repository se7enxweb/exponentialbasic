<?php
// 
// $Id: ezxmlrpcbase64.php 2835 2001-01-25 09:23:53Z bf $
//
// Definition of eZXMLRPCBase64 class
//
// B�rd Farstad <bf@ez.no>
// Created on: <17-Dec-2000 15:32:50 bf>
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
//! eZXMLRPCBase64 hadles encoding and decoding of an XML-RPC base64 encoded binary.
/*!

 */

class eZXMLRPCBase64
{
    /*!
      Creates a new eZXMLRPCBase64 object.
    */
    function __construct( $value=0 )
    {
        if ( !isset( $value ) )
             $value = 0;
        $this->Value = $value;
    }

    /*!
      This function will encode the base64 into a valid XML-RPC value.
    */
    function &serialize( )
    {        
        $value =& base64_encode( $this->Value );
        
        $ret = "<value>";
        $ret .= "<base64>";
        $ret .= $value;
        $ret .= "</base64>";
        $ret .= "</value>";

        return $ret;             
    }
    
    /*!
      Returns the string value.
    */
    function value()
    {
        return $this->Value;
    }

    /*!
      Decodes the base64 encoded string and sets the internal value.
    */
    function decode( $value )
    {
        $this->Value =& base64_decode( $value );
    }
    
    // The string value
    var $Value;
}

?>