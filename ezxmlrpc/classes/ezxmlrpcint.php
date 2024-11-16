<?php
// 
// $Id: ezxmlrpcint.php 3596 2001-02-21 09:32:51Z ce $
//
// Definition of eZXMLRPCInt class
//
// B�rd Farstad <bf@ez.no>
// Created on: <16-Dec-2000 12:08:19 bf>
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
//! eZXMLRPCInt hadles encoding and decoding of an XML-RPC integer value.
/*!
  This class handles both int and i4 as a four-byte signed integer.  
*/

class eZXMLRPCInt
{
    /*!
      Creates a new eZXMLRPCInt object.
    */
    function __construct( $value=0 )
    {
        if ( !is_numeric( $value ) )
            $value = 0;
        
        $this->Value = $value;

    }

    /*!
      This function will encode the int into a valid XML-RPC value.
    */
    function &serialize( )
    {
        $ret = "<value>";
        $ret .= "<int>";
        $ret .= $this->Value;
        $ret .= "</int>";
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

    // The string value
    var $Value;
}

?>