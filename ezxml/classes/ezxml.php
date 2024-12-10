<?php
//
// $Id: ezxml.php 8931 2002-01-11 18:58:09Z kaid $
//
// Definition of eZXML class
//
// B�rd Farstad <bf@ez.no>
// Created on: <16-Nov-2001 11:26:01 bf>
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
//! eZXML handles parsing of well formed XML documents.
/*!
  eZXML will create a DOM tree from well formed XML documents.


  \code

  \endcode
  
*/

include_once( "ezxml/classes/ezdomnode.php" );
include_once( "ezxml/classes/ezdomdocument.php" );

class eZXML
{
    /*!
      Constructor, should not be used all functions are static.
    */
    function __construct( )
    {
        print( "Use the static functions: eZXML::domTree()" );
    }

    /*!
      \static
      Will return an DOM object tree from the well formed XML.

      $params["TrimWhiteSpace"] = false/true : if the XML parser should ignore whitespace between tags.      
    */
    static public function domTree( $xmlDoc, $params=array() )  
    {
        $TagStack = array();

        // get document version
        // stip the !doctype (xdutoit modification)
        $xmlDoc =& preg_replace( "%<\!DOCTYPE.*?>%is", "", $xmlDoc );

        // strip header
        $xmlDoc =& preg_replace( "#<\?.*?\?>#", "", $xmlDoc );

        // strip comments
        $xmlDoc =& eZXML::stripComments( $xmlDoc );


        // libxml compatible object creation
        $domDocument = new eZDOMDocument();
        $domDocument->version = "1.0";

        $domDocument->root =& $domDocument->children;
        $currentNode =& $domDocument;

        $pos = 0;
        $endTagPos = 0;
        while ( $pos < strlen( $xmlDoc ) )
        {
            $char = $xmlDoc[$pos];
            if ( $char == "<" )
            {
                // find tag name
                $endTagPos = strpos( $xmlDoc, ">", $pos );

                // tag name with attributes
                $tagName = substr( $xmlDoc, $pos + 1, $endTagPos - ( $pos + 1 ) );

                // check if it's an endtag </tagname>
                if ( $tagName[0] == "/" )
                {
                    $lastNodeArray = array_pop( $TagStack );
                    $lastTag = $lastNodeArray["TagName"];

                    $lastNode =& $lastNodeArray["ParentNodeObject"];

                    unset( $currentNode );
                    $currentNode =& $lastNode;
                    
                    $tagName = substr( $tagName, 1, strlen( $tagName ) );

                    // strip out namespace; nameSpace:Name
                    $colonPos = strpos( $tagName, ":" );

                    if ( $colonPos > 0 )
                        $tagName = substr( $tagName, $colonPos + 1, strlen( $tagName ) );                    
                    
                    //gb: not understanding what this was original intended on doing so we disable it to proceed.
                    if ( $lastTag != $tagName )
                    {
                        //print( "Error parsing XML, unmatched tags $tagName vs $lastTag" );
                        //return false;
                    }
                    else
                    {
                        //    print( "endtag name: $tagName ending: $lastTag <br> " );
                    }
                }
                else
                {
                    $firstSpaceEnd = strpos( $tagName, " " );
                    $firstNewlineEnd = strpos( $tagName, "\n" );

                    if ( $firstNewlineEnd != false )
                    {
                        if ( $firstSpaceEnd != false )
                        {
                            $tagNameEnd = min( $firstSpaceEnd, $firstNewlineEnd );
                        }
                        else
                        {
                            
                            $tagNameEnd = $firstNewlineEnd;
                        }
                    }
                    else
                    {
                        if ( $firstSpaceEnd != false )
                        {
                            $tagNameEnd = $firstSpaceEnd;
                        }
                        else
                        {
                            $tagNameEnd = 0;
                        }
                    }
                    
                    if ( $tagNameEnd > 0 )
                    {
                        $justName = substr( $tagName, 0, $tagNameEnd );
                    }
                    else
                        $justName = $tagName;


                    // strip out namespace; nameSpace:Name
                    $colonPos = strpos( $justName, ":" );

                    if ( $colonPos > 0 )
                        $justName = substr( $justName, $colonPos + 1, strlen( $justName ) );
                    
                    
                    // remove trailing / from the name if exists
                    if ( $justName[strlen($justName) - 1]  == "/" )
                    {
                        $justName = substr( $justName, 0, strlen( $justName ) - 1 );
                    }


                    // check for CDATA
                    $cdataSection = "";
                    $isCDATASection = false;
                    $cdataPos = strpos( $xmlDoc, "<![CDATA[", $pos );
                    if ( $cdataPos == $pos && $pos > 0)
                    {
                        $isCDATASection = true;
                        $endTagPos = strpos( $xmlDoc, "]]>", $cdataPos );
                        $cdataSection =& substr( $xmlDoc, $cdataPos + 9, $endTagPos - ( $cdataPos + 9 ) );

                        // new CDATA node
                        unset( $subNode );
                        $subNode = new eZDOMNode();
                        $subNode->name = "cdata-section";
                        $subNode->content = $cdataSection;
                        $subNode->type = 4;
                        
                        $currentNode->children[] =& $subNode;

                        $pos = $endTagPos; 
                        $endTagPos += 2;
                        
                    }
                    else
                    {                    
                        // normal start tag
                        unset( $subNode );
                        $subNode = new eZDOMNode();
                        $subNode->name = $justName;
                        $subNode->type = 1;
                        
                        $currentNode->children[] =& $subNode;
                    }

                    // find attributes
                    if ( $tagNameEnd > 0 )
                    {
                        $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );

                        // attributes
                        unset( $attr );
                        $attr =& eZXML::parseAttributes( $attributePart );

                        if ( $attr != false )
                            $subNode->attributes =& $attr;
                    }

                    // check it it's a oneliner: <tagname /> or a cdata section
                    if ( $isCDATASection == false )
                    if ( $tagName[strlen($tagName) - 1]  != "/" )
                    {
                        array_push( $TagStack,
                        array( "TagName" => $justName, "ParentNodeObject" => &$currentNode ) );

                        unset( $currentNode );
                        $currentNode =& $subNode;
                    }
                }
            }

            $pos = strpos( $xmlDoc, "<", $pos + 1 );
           
            if ( $pos == false )
            {
                // end of document
                $pos = strlen( $xmlDoc );
            }
            else
            {
                // content tag
                $tagContent = substr( $xmlDoc, $endTagPos + 1, $pos - ( $endTagPos + 1 ) );

				if ( !isset( $params["TrimWhiteSpace"] ) )
					$params["TrimWhiteSpace"] = false;

                if ( ( ( $params["TrimWhiteSpace"] == true ) and ( trim( $tagContent ) != "" ) ) or ( $params["TrimWhiteSpace"] == false ) )
                {
                    unset( $subNode );
                    $subNode = new eZDOMNode();
                    $subNode->name = "text";
                    $subNode->type = 3;

                    // convert special chars
                    $tagContent =& str_replace("&gt;", ">", $tagContent );
                    $tagContent =& str_replace("&lt;", "<", $tagContent );
                    $tagContent =& str_replace("&apos;", "'", $tagContent );
                    $tagContent =& str_replace("&quot;", '"', $tagContent );
                    $tagContent =& str_replace("&amp;", "&", $tagContent );
                    
                    $subNode->content = $tagContent;
                    
                    $currentNode->children[] =& $subNode;
                }
            }
        }

        return $domDocument;
    }

    /*!
      \static
      \private
    */
    static public function stripComments( &$str )
    {
        $str =& preg_replace( "#<\!--.*?-->#s", "", $str );
        return $str;
    }

    /*!
      \static
      \private
      Parses the attributes. Returns false if no attributes in the supplied string is found.
    */
    static public function &parseAttributes( $attributeString )
    {
        $ret = array();
        
        preg_match_all( "/([a-zA-Z:]+=\".*?\")/i",  $attributeString, $attributeArray );

        foreach ( $attributeArray[0] as $attributePart )
        {
            $attributePart = $attributePart;

            if ( trim( $attributePart ) != "" && trim( $attributePart ) != "/" )
            {
                $attributeTmpArray = explode( "=\"", $attributePart );

                $attributeName = $attributeTmpArray[0];

                // strip out namespace; nameSpace:Name
                $colonPos = strpos( $attributeName, ":" );
                                
                if ( $colonPos > 0 )
                    $attributeName = substr( $attributeName, $colonPos + 1, strlen( $attributeName ) );                    

                $attributeValue = $attributeTmpArray[1];

                // remove " from value part
                $attributeValue = substr( $attributeValue, 0, strlen( $attributeValue ) - 1);

                unset( $attrNode );
                $attrNode = new eZDOMNode();
                $attrNode->name = $attributeName;
                $attrNode->type = 2;
                $attrNode->content = $attributeValue;

                unset( $nodeValue );
                $nodeValue = new eZDOMNode();
                $nodeValue->name = "text";
                $nodeValue->type = 3;
                $nodeValue->content = $attributeValue;
                                
                $attrNode->children[] =& $nodeValue;

                $ret[] =& $attrNode;

            }
        }
        return $ret;         
    }
 
}

?>