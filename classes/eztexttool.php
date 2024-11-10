<?php
// 
// $Id: eztexttool.php 9704 2002-08-15 10:27:46Z gl $
//
// Definition of eZTextTool class
//
// Created on: <16-Oct-2000 11:06:56 bf>
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
//! The eZTextTool class provides text utility functions
/*!
  This class consists of static functions for formatting of text.
  Theese functions is made as an extention to PHP ie functions you would
  use all the time, but isn't a part of php.
  
  Example of usage:
  \code
  // create a string with newlines
  $text = "This is
  a
  text
  to break
  up";

  // convert the newlines to xhtml breaks
  $text = eZTextTool::nl2br( $text );

  //print out the result
  print( $text );
  \endcode
  
*/

class eZTextTool
{
    /*!
      \static
      This function converts all newlines \n into breaks.

      The breaks are inserted before every newline.

      The default is to use xhtml breaks, html breaks is used if the
      $xhtml variable is set to false.
    */
    static public function nl2br( $string, $xhtml=true )
    {
        if ( !is_bool( $xhtml ) )
        {
            return str_replace( "\n", "$xhtml \n", $string );
        }
        
        return nl2br($string, $xhtml);
    }    

    /*!
      \static
      This function will add a > at the beginning of each line.
    */
    static public function addPre( $string, $char=">" )
    {
        $string =& wordwrap( $string, 60, "\n" );
        return preg_replace( "#^#m", "$char ", $string );
    }

    /*!
      \static
      This function will convert text into capitilzed text.

      Numbers will not be capitalized. E.g.

      "a text string" will be converted to "A  T E X T  S T R I N G"
      Where the first letter in each word will get the css style;
      span with class="$bigClass" given as argument.
      
    */
    static public function capitalize( $string, $bigClass="h1bigger" )
    {
        $string = strtoupper( $string );
        
        for ( $i=0; $i<strlen( $string ); $i++)
        {
            $string2 .= $string[$i] . " ";
        }
        
        $string = trim( $string2 );
        
        $string = str_replace ("�", "�", $string );        
        $string = str_replace ("�", "�", $string );
        $string = str_replace ("�", "�", $string );

        $string = preg_replace( "#(  |^)([a-zA-Z������] )#", "\\1<span class=\"$bigClass\">\\2</span>", $string );
        
//        $string = preg_replace( "#(  |^)([^ ])#", "\\1<span class=\"$bigClass\">\\2</span>", $string );
        
        $string = str_replace ( "  ", "&nbsp;&nbsp;", $string );
        
        return  $string;
    }
    
    /*!
      \static
      This function will return the text "true" if the input value is true and
      the text "false" if false.
     */

    static public function boolText( $value )
    {
    	return $value?'true':'false';
    }

    /*!
      Performs a normal htmlspecialchars with a striplashes afterwards,
      this is needed to avoid " and \ being slashed on web pages.
    */
    static public function htmlspecialchars( $string )
    {
        return stripslashes( htmlspecialchars( $string ) );
    }

    /*!
      Fixup error made by htmlspecialchars, will convert for instance
      &amp;#8364; back to &#8364; (euro symbol)
    */
    static public function fixhtmlentities( $string )
    {
        $string = preg_replace( "/&amp;#([0-9]+);/", "&#\\1;", $string );
        return $string;
    }

    /*!
      This function will split a string into lines of no more than a
      given number of characters, but wont split a word. Useful in
      e-mails. Each new line will be ended with a "\n".
      
      You can also add a padding length, if you wish.
     */
    static public function lineSplit( $in, $len = 0, $size = 72 )
    {
        $tmp = "";
        $pad = str_pad( $tmp, $len, " ", STR_PAD_LEFT );
        $temptext = ""; 
        $temparray = explode( " ", $in ); 
        $i = 0; 
        while( $i <= count( $temparray ) )
        { 
            while( ( strlen( $pad . $temptext . " " . $temparray[$i] ) < $size )
                && ($i <= count( $temparray ) ) )
            { 
                $temptext = $temptext." ".$temparray[$i]; 
                $i++;
            } 
            $out = $out."\n" . $pad . $temptext; 
            $temptext = $temparray[$i]; 
            $i++; 
        } 
        return $out;
    }

    /*!
      \static
      Parses an XML message into a tree array,
      the XML needs to be passed trough qdom_tree or xml_tree before being passed to this function.
      This function is useful for small xml files with 1-3 levels.
      The resulting array tree makes it easy to grab variables from the XML tree.
      Each node gets a separate array with each attribute as a key/value pair,
      each child of that node is a key with the node name and the value is the new tree.
      If $inline_children is set to true then no "children" elements are created,
      the subnodes will instead be placed directly in the current node.
      Example: <top attrib="test"><one src="test again" /></top>
      Result: Array
      (
        [top] => Array
        (
          [attrib] => test
          [children] => Array
          (
            [one] => Array
            (
              [src] => test again
            )
          )
        )
      )
      You can then access the "src" attribute of the node "one" by doing,
      $src = $tree["top"]["children"]["one"]["src"];
      which is quite easier than traversing an xml tree manually.
    */
    static public function parseXML( &$xml, $inline_children = false )
    {
        $msg = array();
        eZTextTool::parseXMLPart( $xml, $msg, $inline_children );
        return $msg;
    }

    /*!
      \static
      \private
      Helper function for parseXML.
    */
    static public function parseXMLPart( &$xml, &$msg, $inline_children )
    {
        foreach( $xml->children as $child )
        {
            $part = array();
            if ( isset( $child->attributes ) )
            {
                foreach( $child->attributes as $attr )
                {
                    if ( $attr->type == 2 )
                    {
                        $part[$attr->name] =& $attr->content;
                    }
                }
            }
            if ( isset( $child->children ) )
            {
                if ( $inline_children )
                {
                    eZTextTool::parseXMLPart( $child, $part, $inline_children );
                }
                else
                {
                    $children = array();
                    eZTextTool::parseXMLPart( $child, $children, $inline_children );
                    $part["children"] =& $children;
                }
            }
            $msg[$child->name] = $part;
        }
    }
}