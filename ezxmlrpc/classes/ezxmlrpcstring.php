<?php
// 
// $Id: ezxmlrpcstring.php 2835 2001-01-25 09:23:53Z bf $
//
// Definition of eZXMLRPCString class
//
// Bård Farstad <bf@ez.no>
// Created on: <16-Dec-2000 12:04:35 bf>
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
//! eZXMLRPCString hadles encoding and decoding of an  XML-RPC string.
/*!
  
*/

class eZXMLRPCString
{
    /*!
      Creates a new eZXMLRPCString object.
    */
    function eZXMLRPCString( $value="" )
    {
        $this->Value = $value;
    }

    /*!
      This function will encode the sting into a valid XML-RPC value.
    */
    function &serialize( )
    {
        $ret = "<value>";
        $ret .= "<string>";
        $ret .= $this->encode( $this->Value );
        $ret .= "</string>";
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
      Returns an encoded string. Where <, > and & are converted to &lt;, &gt; and &amp;
    */
    function &encode( $string )
    {
        $string =& ereg_replace ( "&", "&amp;", $string );
        $string =& ereg_replace ( "<", "&lt;", $string );
        $string =& ereg_replace ( ">", "&gt;", $string );
        
        return $string;
    }

    /*!
      Returns a string which is decoded. Opposite of encode().
    */
    function decode( $string )
    {
        $string =& ereg_replace ( "&amp;", "&", $string );
        $string =& ereg_replace ( "&lt;", "<", $string );
        $string =& ereg_replace ( "&gt;", ">", $string );

        $this->Value =& $string;                
    }
    
    // The string value
    var $Value;
}

?>
