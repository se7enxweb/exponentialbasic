<?php
// 
// $Id: ezuritool.php 6200 2001-07-19 11:33:57Z jakobn $
//
// Definition of eZURITool class
//
// Created on: <16-Jan-2001 10:59:46 amos>
//
// This source file is part of eZ publish, publishing software.
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

//!! eZCommon
//! The eZURITool has several static functions for handling URIs.
/*!
  A class with static functions for handling URIs.

  Example code
  \code
  $uri_array = eZURITool::split( $URI );

  // Decodes a part of the URI to a normal text
  // For instance the string "Test+text" would become "Test text"
  $text_part = eZURITool::decode( $uri_array[3] );

  // Add some text and encode it back
  $text_part .= " more text";
  $uri_array[3] = eZURITool::encode( $text_part );

  $URI = eZURITool::merge( $uri_array );
  \endcode

*/

class eZURITool
{
    /*!
      \static
      Splits the URI into an array and returns the array.
      \sa merge
    */
    static public function &split( &$uri )
    {
        $uri_array = explode( "/", $uri );
        return $uri_array;
    }

    /*!
      \static
      Merges an split URI array into a full URI and returns it.
      \sa split
    */
    static public function &merge( &$uri_array )
    {
        $uri = implode( "/", $uri_array );
        return $uri;
    }

    /*!
      \static
      Decodes an encoded string into a normal string used in PHP code.
      \sa encode
    */

    static public function &decode( &$text )
    {
        $text = preg_replace( "/%([0-9a-fA-F]{2})/", "".chr(hexdec('\\1'))."", $text );
        return $text;
    }

    /*!
      \static
      Encodes a normal string into a string usable in URIs, these strings does not contain spaces.
      \sa decode
    */

    static public function &encode( &$text )
    {
        $text = preg_replace( '/\\\\"/', '"', $text );
        $text = preg_replace( "/([ \"'&%])/", "%".bin2hex('\\1')."", $text );
        return $text;
    }
}
?>