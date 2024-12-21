<?php
// 
// $Id: ezsimplegenerator.php 6206 2001-07-19 12:19:22Z jakobn $
//
// Definition of eZSimpleGenerator class
//
// Created on: <18-Oct-2000 15:55:16 bf>
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

//!! eZArticle
//! eZSimpleGenerator generates  XML contents for articles.
/*!
  This class will generate a simple XML article. This class is ment
  as an example of how to write your own special generator.

*/

class eZSimpleGenerator
{
    /*!
      Creates a new eZSimpleGenerator object.
    */
    function __construct( &$contents )
    {
        $this->Contents = $contents;
    }

    /*!
      Generates valid XML data to use for storage.
    */
    function &generateXML()
    {
        // add the XML header.
        
        $newContents = "<?xml version=\"1.0\"?>";
        
        //add the generator, this is used for rendering.
        $newContents .= "<article><generator>simple</generator>\n";

        //add the contents
        $newContents .= "<intro>" . strip_tags( $this->Contents[0] ). "</intro>";

        $newContents .= "<body>" . strip_tags( $this->Contents[1] ) . "</body></article>";

        return $newContents;
    }

    /*!
      Returns the page count.
    */
    function pageCount()
    {
        return 1;
    }

    var $Contents;
}

?>