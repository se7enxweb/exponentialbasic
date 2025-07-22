<?php
// 
// $Id: ezimagefile.php 6304 2001-07-29 23:31:17Z kaid $
//
// Definition of eZImageFile class
//
// Created on: <21-Sep-2000 11:22:21 bf>
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
//! The eZImageFile class handles fileuploads, and image specific functions like scale.
/*!
  <b>NOTE:</b> this class requires the ImageMagic convert program. You can
  get it from: http://www.wizards.dupont.com/cristy/ImageMagick.html
  
  Example:
  \code
  You need a HTML file like:
    <form method="post" action="script_url" enctype="multipart/form-data">
       <input type="hidden" name="max_file_size" value="3000000">

    Image:<br>
       <input name="userfile" type="file" /><br>
       <input type="submit" value="OK" />
    </form>

    And in the script_url you put code like:
  
    $file = new eZImageFile();

    // note: userfile is not a variable it's a "text" string. The value
    // must be the same as the one used in the input.
    if ( $file->getFile( "userfile" ) )
    {
        print( $file->name() . " uploaded successfully" );

        // copy and scale the uploaded file
        $file->scaleCopy( "tmp/" . $file->name(), 120, 200 );
    }
    else
    {
        print( "Error uploading file." );
    }
  
  \endcode
*/

//include_once( "kernel/classes/ezfile.php" );
    

class eZImageFile extends eZFile
{
    /*!
      Constructs a new eZImageFile object.
    */
    function __construct()
    {

    }

    /*!
      Returns true if the uploaded file is an image (As perceived by Apache).
      Warning!! Do not use this to figure out if a file is an true image,
      only to see if Apache can serve it directly.
    */
    function isImage()
    {
        if ( preg_match( "/image/", $this->FileType ) )
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
      Returns a information structure of the image file.
      The structure is an associative array with the following contents:
      "suffix" - The suffix of the image file, for instance: png, jpg or gif
      "dot-suffix" - The suffix of the image file with a dot, is empty if not supported.
      "image-type" - The image type, for instance: image/jpeg, image/png or image/gif
      "support" - True if the image type is supported.
    */
    static public function &information( $file, $use_default = false )
    {
        $ret = array();
        if ( preg_match( '/\.([a-zA-Z]+)$/', $file, $regs ) )
        {
            // We got a suffix, make it lowercase and store it
            $suffix = strtolower( $regs[1] );
        }
        else
        {
            // If we don't have a suffix, we assume it's not supported
            $suffix = "";
        }
        // List of supported suffixes
        $suffix_list = array( "jpg" => array( ".jpg", "image/jpeg" ),
                              "jpeg" => array( ".jpg", "image/jpeg" ),
                              "gif" => array( ".gif", "image/gif" ),
                              "png" => array( ".png", "image/png" ) );

        if( isset( $suffix_list[$suffix] ) )
        {
            $postfix = $suffix_list[$suffix];
            $ret["suffix"] = $suffix;
            $ret["dot-suffix"] = $postfix[0];
            $ret["image-type"] = $postfix[1];
            $ret["supported"] = is_array( $postfix );
        }
        else {
            // If we don't have a suffix, we assume it's not supported
            $ret["suffix"] = $suffix;
            $ret["dot-suffix"] = "";
            $ret["image-type"] = "";
            $ret["supported"] = false;
        }
        if ( isset( $ret["supported"] ) and !$ret["supported"] and $use_default )
            return eZImageFile::defaultInformation();
        return $ret;
    }

    /*!
      \static
      Returns the default image info structure, which uses the jpeg format.
      This is used for returning a standard info structure if the file format is unsupported.
    */
    static public function &defaultInformation()
    {
        $ret = array( "suffix" => "jpg",
                      "dot-suffix" => ".jpg",
                      "image-type" => "image/jpeg",
                      "supported" => false );
        return $ret;
    }
    /*!
      Makes a copy of the image if and scales the copy according
      to the parameters $width and $height. If $aspectScale is true
      the scaling is done with ascpect ratio if not it uses "free form"
      scaling.

      Aspect scaling is the default.
    */
    function scaleCopy( $dest, $width, $height, $convertToGray = false, $aspectScale=true )
    {
        $ret = false;
        $lock_file = $dest . ".lock";
        if ( eZFile::file_exists( $lock_file ) )
        {
            // If image file is locked we need to wait until it's finished
            $i = 0;
            while( eZFile::file_exists( $lock_file ) and $i < 5*5 ) // Wait max 5 seconds
            {
                usleep( 200000 ); // Sleep 1/5 of a second
                clearstatcache();
                $i++;
            }
            return "locked";
        }
        touch( $lock_file );
        $ini =& INIFile::globalINI();
        $image_prog = "convert";
        if ( $ini->has_var( "classes", "ImageConversionProgram" ) )
            $image_prog = $ini->read_var( "classes", "ImageConversionProgram" );
        $grayCode = "";
        if ( $convertToGray == true )
            $grayCode = " -colorspace GRAY ";
        $execstr = "$image_prog  -colorspace Transparent $grayCode -geometry \"$width" . "x" . "$height" . ">\" "  . $this->TmpFileName . " " . $dest;
        // print( "<br><b>$execstr</b><br>" );

        $err = system( $execstr, $ret_code );

        if ( $ret_code == 0 )
        {
            @eZFile::chmod( $dest, 0644 );
            $ret = true;
        }
        else
        {
            $ret = false;
        }

	// Watermark Code added by Brian Ducharme - www.brianducharme.com 
	// - modified for image watermarks by Dylan McDiarmid
		$watermarkCodeTL = "";
		$watermarkCodeBR = "";
		$watermark_enabled = "false";
		$watermark_width = "200";
		$watermark_height = "200";
		$watermark_position = "south";
		// $watermark_image = "ttwater.gif";

	    if ( $ini->has_var( "watermark", "watermarkEnabled" ) )
             $watermark_enabled = $ini->read_var( "watermark", "watermarkEnabled" );
	    if ( $ini->has_var( "watermark", "watermarkImage" ) )
             $watermark_image = $ini->read_var( "watermark", "watermarkImage" );
	    if ( $ini->has_var( "watermark", "watermarkImageBr" ) )
             $watermark_image_br = $ini->read_var( "watermark", "watermarkImageBr" );
	    if ( $ini->has_var( "watermark", "watermarkImageBrSmall" ) )
             $watermark_image_br_small = $ini->read_var( "watermark", "watermarkImageBrSmall" );

		if ( $watermark_enabled == "true" )
		if ( $ini->has_var( "watermark", "minWidth" ) )
			$watermark_width = $ini->read_var( "watermark", "minWidth" );
		if ( $ini->has_var( "watermark", "minHeight" ) )
			$watermark_height = $ini->read_var( "watermark", "minHeight" );
		if ( $ini->has_var( "watermark", "position" ) )
			$watermark_position = $ini->read_var( "watermark", "position" );
		{
			
		// static for now while we get the scaling thing figured out
		$actualsize = getimagesize($dest);
		$actualheight = $actualsize[1];
		$actualwidth = $actualsize[0];
		if (($width >= 400 | $height >= 400) && ($actualheight >= 400 | $actualwidth >= 400)) { 
                  $watermark_image_br = $watermark_image_br;
		  // $watermark_image_br = 'design/tt/images/watermarks/ttwater-br.png';
		 // $watermark_image_br = 'design/tt/images/watermarks/ttwater-br-outline.png';
		} else {
		  $watermark_image_br = $watermark_image_br_small;
		 // $watermark_image_br = 'design/tt/images/watermarks/ttwater-br-small-outline.png';
		}
		// some lame error handling
		if (!is_file($watermark_image_br)) {
		  // ezlog: new eZLog
		  eZLog::writeNotice( "User Site: Could not locate watermark image file." );
		  echo ('Could not locate watermark image file.');
		  print ('Could not locate watermark image file.');
		}

		if ( $width >= $watermark_width | $height >= $watermark_height ):
		  //	$watermarkCodeTL = "/usr/bin/composite -gravity northwest $watermark_image_tl $dest $dest";
		  
		  $watermarkCodeBR = "/usr/bin/composite -gravity southeast $watermark_image_br $dest $dest";

		//	$watermarkCode = '';			
		endif;				


		//$watermarkCode = " composite -compose atop -gravity $watermark_position $watermark_text " .  $this->TmpFileName;
		//$watermarkCode = " -font helvetica -draw 'gravity $watermark_position fill white  text 0,20  \"$watermark_text\" fill black text 1,21  \"$watermark_text\"' ";
		}

		//	print('watermark is ' . $watermarkCode . "\n");
		// $execstr = "$image_prog  -colorspace Transparent $grayCode -geometry \"$width" . "x" . "$height" . ">\"  $watermarkCode "  . $this->TmpFileName . " " . $dest;
		// die('exec' . $execstr);
		
		// End of Watermark Code
		
		if ($watermarkCodeBR != "") {
		  $err_water = system( $watermarkCodeBR, $ret_code_water );
		  if ( $ret_code_water == 0 )
		    {
		      @eZFile::chmod( $dest, 0644 );
		      $re2 = true;
		    }
		  else
		    {
		      $ret2 = false;
		    }
        }

        // Check for animated gif/png
        if ( eZFile::file_exists( "$dest" . ".0" ) )
        {
            // TODO: not sure
	        // copy( $this->TmpFileName, $dest );
	        eZFile::copy( $dest );
            @eZFile::chmod( $dest, 0644 );
            $i = 0;
            while( eZFile::file_exists( "$dest.$i" ) )
            {
                eZFile::unlink( "$dest.$i" );
                $i++;
            }
            $ret = true;
        }

        eZFile::unlink( $lock_file );

	 if ((isset($ret2) && $ret2 == false) || $ret == false ) return false;
	
        return true;
    }

    /*!
      Makes a copy of the image if and converts it to the correct image type.
    */
    function convertCopy( $dest )
    {
        $ret = false;
        $ini =& INIFile::globalINI();
        $image_prog = "convert";
        if ( $ini->has_var( "classes", "ImageConversionProgram" ) )
            $image_prog = $ini->read_var( "classes", "ImageConversionProgram" );
        $execstr = "$image_prog -colorspace Transparent -quality 95 " . $this->TmpFileName . " " . $dest;
        // print( "<br><b>$execstr</b><br>" );

        $err = system( $execstr, $ret_code );
        $ret = true;
        if ( $ret_code != 0 )
        {
            $ret = false;
        }
        else
            @eZFile::chmod( $dest, 0644 );
        
        return $ret;
    }
    
}

?>