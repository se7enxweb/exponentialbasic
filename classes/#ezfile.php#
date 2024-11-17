<?php
// $Id: ezfile.php 9329 2002-03-04 12:55:56Z ce $
//
// Definition of eZCompany class
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
//! The eZFile class handles fileuploads, and other file functions.
/*!
  Example:
  \code
    $file = new eZFile();

    if ( $file->getFile( "userfile" ) )
    {
        print( $file->name() . " uploaded successfully" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }
  \endcode

*/
include_once( "classes/INIFile.php" );

class eZFile
{

    /*!
      Fetches the uploaded file information.

      The $name_var variable is refering to the html <input .. variable>

      See the example for more details.
    */
    function getUploadedFile( $name_var )
    {
        global $HTTP_POST_FILES;

        $name_var = $HTTP_POST_FILES[ $name_var ];
        $ret = true;

        $this->FileName = $name_var['name'];
        $this->FileType = $name_var['type'];
        $this->FileSize = $name_var['size'];
        $this->TmpFileName = $name_var['tmp_name'];

        if ( ( $this->FileSize == "0" ) || ( $this->FileSize == "" ) || ( $this->FileName == "" ) )
        {
            $ret = false;
        }

        return $ret;
    }

    /*!
      Dumps the data to a temporary file. Sets the variables in this file.
     */
    function dumpDataToFile( $data, $fileName )
    {
        $this->FileName = $fileName;
        $ini =& INIFile::globalINI();
        $tmpDir = $ini->read_var( "site", "SiteTmpDir" );
        $tmpfileName = tempnam( $tmpDir, "att" );
        $this->TmpFileName = $tmpfileName;
        $fh = fopen( $tmpfileName, 'wb' );
        fwrite( $fh, $data );
        fclose( $fh );
    }

    /*!

    */
    function getFile( $fileName )
    {
        $this->FileName = $fileName;
        $this->FileSize = eZFile::filesize( $fileName );
        $this->TmpFileName = $fileName;

        $ret = true;

        if ( ( $this->FileSize == "0" ) || ( $this->FileSize == "" ) )
        {
            $ret = false;
        }
        return $ret;
    }

    /*!
      Moves the uploaded file to the desired directory.

      Returns true if successful.
    */
    function move( $dest )
    {
        return move_uploaded_file( $this->TmpFileName, $dest );
    }

   /*!
      Copies the uploaded file to the desired directory.

      Returns true if successful.
    */
    function copy( $dest )
    {
		global $GlobalSiteIni;
    	if ( $dest != "" && $GlobalSiteIni)
        {
            $dest = $GlobalSiteIni->SiteDir . $dest;
        }

        $ret = true;

        if ( !copy( $this->TmpFileName, $dest ) )
        {
            $ret = false;
        }
        else
            chmod( $dest, 0644 );

        return $ret;
    }

    /*!
      Returns the original file name.
    */
    function name()
    {
        return $this->FileName;
    }

    /*!
      Returns the file type.
    */
    function type()
    {
        return $this->FileType;
    }

    /*!
      Returns the file size.
    */
    function size()
    {
        return $this->FileSize;
    }

    /*!
      \static
      Returns the size of the file in a shortened form useful for printing to the user,
      the returned value is an array with the filesize, the size as a shortened string
      and the unit. The keys used for fetching the various items in the array are:
      "size" - The full file size
      "size-string" - The shortened file size as a string
      "unit" - The unit for the shortened size, either B, KB, MB or GB
    */

    public static function &siFileSize( $size )
    {
        $units = array( "GB" => 10737741824,
                        "MB" => 1048576,
                        "KB" => 1024,
                        "B" => 0 );
        $decimals = 0;
        $shortsize = $size;
	foreach( $units as $unit_key => $val )
	{
            if ( $size >= $val )
            {
                $unit = $unit_key;
                if ( $val > 0 )
                {
                    $decimals = 2;
                    $shortsize = $size / $val;
                }
                break;
            }
        }
        $shortsize = number_format( ( $shortsize ), $decimals);
        $size = array( "size" => $size,
                       "size-string" => $shortsize,
                       "unit" => $unit );
        return $size;
    }

    /*!
      Returns the temporary file name.
    */
    public function tmpName()
    {
        return $this->TmpFileName;
    }

    /*!
      Sets the mime type of the file.
    */
    public function setType( $type )
    {
        $this->FileType = $type;
    }

    /*!
      Same as file_exists(), but prepends $siteDir if $filename not empty.
    */
    public static function file_exists( $filename )
    {
		global $GlobalSiteIni;
    	if ( $filename != "")
        {
        	if ($GlobalSiteIni)
        	{
    			$filename = $GlobalSiteIni->SiteDir . $filename;
        	}
            return file_exists( $filename );
        } else
        {
            return false;
        }
    }

    /*!
      Same as is_file(), but prepends $siteDir if $filename not empty.
    */
    public static function is_file( $filename )
    {
		global $GlobalSiteIni;
    	if ( $filename != "")
        {
        	if ($GlobalSiteIni)
        	{
    			$filename = $GlobalSiteIni->SiteDir . $filename;
        	}
            return is_file( $filename );
        } else
        {
            return false;
        }
    }
    
    /*!
      Same as filemtime(), but prepends $siteDir if $filename not empty.
    */
    public static function filemtime( $filename )
    {
		global $GlobalSiteIni;
    	if ( $filename != "" && $GlobalSiteIni)
        {
            $filename = $GlobalSiteIni->SiteDir . $filename;
        }
        if ( file_exists( $filename ) )
        {
            return filemtime( $filename );
        }
        else
        {
            return false;
        }
    }

    /*!
      Same as fopen(), but prepends $siteDir if $filename not empty.
    */
    public static function fopen( $filename, $options )
    {
		global $GlobalSiteIni;
    	if ( $filename != "" && $GlobalSiteIni)
        {
    		$filename = $GlobalSiteIni->SiteDir . $filename;
        }
        return fopen( $filename, $options );
    }

    /*!
      Same as filesize(), but prepends $siteDir if $filename not empty.
    */
    public static function filesize( $filename )
    {
		global $GlobalSiteIni;
    	if ( $filename != "" && $GlobalSiteIni)
        {
    		$filename = $GlobalSiteIni->SiteDir . $filename;
        }
        return filesize( $filename );
    }

    /*!
      Same as unlink(), but prepends $siteDir if $filename not empty.
    */
    public static function unlink( $filename )
    {
		global $GlobalSiteIni;
    	if ( $filename != "" && $GlobalSiteIni)
        {
    		$filename = $GlobalSiteIni->SiteDir . $filename;
        }
        return unlink( $filename );
    }

    /*!
      Same as chmod(), but prepends $siteDir if $dest not empty.
    */
    public static function chmod( $filename, $mode )
    {
		global $GlobalSiteIni;
    	if ( $filename != "" && $GlobalSiteIni)
        {
    		$filename = $GlobalSiteIni->SiteDir . $filename;
        }
        return chmod( $filename, $mode);
    }

    /*!
      Same as dir(), but prepends $siteDir if $dir not empty.
    */
    public static function dir( $dir, $add_sitedir = true )
    {
		global $GlobalSiteIni;
    	if ( $add_sitedir )
        {
            if ( $dir != "" && $GlobalSiteIni)
            {
                $dir = $GlobalSiteIni->SiteDir . $dir;
            }
        }
        return dir( $dir );
    }

    /*!
      Same as is_dir(), but prepends $siteDir if $dir not empty.
    */
    public static function is_dir( $dir )
    {
		global $GlobalSiteIni;
    	if ( $dir != "" && $GlobalSiteIni)
        {
            $dir = $GlobalSiteIni->SiteDir . $dir;
        }
        return is_dir( $dir );
    }

    /*!
      Same as realpath(), but prepends $siteDir if $filename not empty.
    */
    public static function realpath( $filename )
    {
		global $GlobalSiteIni;
    	if ( $filename != "" && $GlobalSiteIni)
        {
    		$filename = $GlobalSiteIni->SiteDir . $filename;
        }
        return realpath( $filename );
    }

    /*!
      Same as file(), but prepends $siteDir if $filename not empty.
    */
    public static function file( $filename )
    {
        if (file_exists( "sitedir.ini" ) &&  $filename != "" )
        {
            include( "sitedir.ini" );
            $filename = $siteDir . $filename;
        }
        return file( $filename );
    }

    var $FileName;
    var $TmpFileName;
    var $FileType;
    var $FileSize;
}