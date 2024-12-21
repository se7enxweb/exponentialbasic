<?php
//
// $Id: ezdomdocument.php 8837 2001-12-21 15:37:48Z bf $
//
// Definition of eZDOMDocument class
//
// Created on: <16-Nov-2001 12:18:23 bf>
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

//!! eZXML
//! eZDOMNode handles DOM nodes in DOM documents
/*!

*/

class eZDOMDocument
{
    /*!
    */
    function __construct( )
    {
//        $this->children = array();
    }

    /*!
      Returns a XML string of the DOM document
    */
    function &toString()
    {
        $ret = "<?xml version=\"1.0\"?>";

        if ( count( $this->children ) > 0 )
        foreach ( $this->children as $child )
        {
            $ret .= $child->toString();
        }

        return $ret;        
    }


    /// XML version
    var $version;
    
    var $standalone;
    var $type;
    var $children;
    var $root;
}

?>