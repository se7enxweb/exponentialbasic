<?php
// 
// $Id: ezmediafile.php 8138 2001-11-01 08:31:40Z ce $
//
// Definition of eZMediaFile class
//
// Created on: <24-Jul-2001 12:44:06 ce>
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
//! The eZMediaFile class handles fileuploads, and media specific functions like scale.
/*!
  <b>NOTE:</b> this class requires the MediaMagic convert program. You can
  get it from: http://www.wizards.dupont.com/cristy/MediaMagick.html
  
  Example:
  \code
  You need a HTML file like:
    <form method="post" action="script_url" enctype="multipart/form-data">
       <input type="hidden" name="max_file_size" value="3000000">

    Media:<br>
       <input name="userfile" type="file" /><br>
       <input type="submit" value="OK" />
    </form>

    And in the script_url you put code like:
  
    $file = new eZMediaFile();

    // note: userfile is not a variable it's a "text" string. The value
    // must be the same as the one used in the input.
    if ( $file->getFile( "userfile" ) )
    {
        print( $file->name() . " uploaded successfully" );
    }
    else
    {
        print( "Error uploading file." );
    }
  
  \endcode
*/

include_once( "kernel/classes/ezfile.php" );
    

class eZMediaFile extends eZFile
{
    /*!
      Constructs a new eZMediaFile object.
    */
    function __construct()
    {

    }

    /*!
      Returns true if the uploaded file is an media (As perceived by Apache).
      Warning!! Do not use this to figure out if a file is an true media,
      only to see if Apache can serve it directly.
    */
    function isMedia()
    {
        if ( ereg( "media", $this->FileType ) )
        {
            $ret = true;
        }
        else
        {
            $ret = false;
        }

        return $ret;
    }

    /*!
      \static
      Returns a information structure of the media file.
      The structure is an associative array with the following contents:
      "suffix" - The suffix of the media file, for instance: mpeg, avi or rm
      "dot-suffix" - The suffix of the media file with a dot, is empty if not supported.
      "media-type" - The media type, for instance: media/jpeg, media/png or media/gif
      "support" - True if the media type is supported.
    */
    function &information( $file, $use_default = false )
    {
        $ret = array();
        $suffix = "";
        if ( ereg( "\\.([a-zA-Z]+)$", $file, $regs ) )
        {
            // We got a suffix, make it lowercase and store it
            $suffix = strtolower( $regs[1] );
        }

        // List of supported suffixes
        $suffix_list = array( "mpg" => array( ".mpg", "video/mpeg" ),
                              "mpeg" => array( ".mpeg", "video/mpeg" ),
                              "avi" => array( ".avi", "video/avi" ),
                              "mov" => array( ".mov", "video/mov" ),
                              "swf" => array( ".swf", "application/x-shockwave-flash-" ),
                              "wmv" => array( ".wmv", "application/x-mplayer2" ),
                              "asf" => array( ".asf", "application/x-mplayer2" ),
                              "rm" => array( ".rm", "video/realaudio" ) );
        
        $postfix = $suffix_list[$suffix];
        $ret["suffix"] = $suffix;
        $ret["dot-suffix"] = $postfix[0];
        $ret["media-type"] = $postfix[1];
        $ret["supported"] = is_array( $postfix );
        if ( !$ret["supported"] and $use_default )
            return eZMediaFile::defaultInformation();
        return $ret;
    }

    /*!
      \static
      Returns the default media info structure, which uses the mpeg format.
      This is used for returning a standard info structure if the file format is unsupported.
    */
    function &defaultInformation()
    {
        $ret = array( "suffix" => "mpg",
                      "dot-suffix" => ".mpg",
                      "media-type" => "media/mpeg",
                      "supported" => false );
        return $ret;
    }
}
?>