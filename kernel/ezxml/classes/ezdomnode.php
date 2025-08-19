<?php
//
// $Id: ezdomnode.php 8837 2001-12-21 15:37:48Z bf $
//
// Definition of eZDOMNode class
//
// Created on: <16-Nov-2001 12:11:43 bf>
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

//!! eZXML
//! eZDOMNode handles DOM nodes in DOM documents
/*!

*/

class eZDOMNode
{
    /*!
    */
    function __construct( )
    {
    }

    /*!
      Returns a XML string of the DOM Node and subnodes
    */
    function &toString()
    {
        $ret = "";
        switch ( $this->name )
        {
            case "text" :
            {
                $tagContent = $this->content;
                
                $tagContent =& str_replace( "&", "&amp;", $tagContent );
                $tagContent =& str_replace( ">", "&gt;", $tagContent );
                $tagContent =& str_replace( "<", "&lt;", $tagContent );
                $tagContent =& str_replace( "'", "&apos;", $tagContent );
                $tagContent =& str_replace( '"', "&quot;", $tagContent );
                
                $ret =& $tagContent;
            }break;

            case "cdata-section" :
            {
                $ret = "<![CDATA[";
                $ret .= $this->content;
                $ret .= "]]>";
            }break;

            default :
            {
                $isOneLiner = false;
                // check if it's a oneliner
                if ( count( $this->children ) == 0 and ( $this->content == "" ) )
                    $isOneLiner = true;
                    
                
                $attrStr = "";
                // generate attributes string
                if ( count( $this->attributes ) > 0 )
                foreach ( $this->attributes as $attr )
                {
                    $attrStr .= " " . $attr->name . "=\"" . $attr->content . "\" ";
                    
                }

                if ( $isOneLiner )
                    $oneLinerEnd = " /";
                else
                    $oneLinerEnd = "";
                    
                $ret = "<" . $this->name . $attrStr . $oneLinerEnd . ">";

                if ( count( $this->children ) > 0 )
                foreach ( $this->children as $child )
                {
                    $ret .= $child->toString();
                }                

                if ( !$isOneLiner )
                    $ret .= "</" . $this->name . ">";

            }break;

        }        


        return $ret;        
    }
    

    /// Name of the node
    var $name;

    /// Type of the DOM node
    var $type;

    /// Content of the node
    var $content;

    /// Subnodes
    var $children;

    /// Attributes
    var $attributes;
}

?>