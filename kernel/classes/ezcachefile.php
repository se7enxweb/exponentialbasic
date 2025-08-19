<?php
// 
// $Id: ezcachefile.php 9802 2003-04-10 08:37:08Z br $
//
// Definition of eZCacheFile class
//
// Created on: <07-Feb-2001 13:56:26 amos>
//
// This source file is part of Exponential Basic, publishing software.
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

// include_once( "kernel/classes/ezdatetime.php" );

//!! eZCommon
//! The class eZCacheFile manages cache files in an easy way
/*!
  Example:
  \code
  $file = new eZCacheFile( "kernel/ezarticle/cache", array( "articleview", 1, 2 ), "cache", "," );
  if ( !$file->isUpToDate() )
  {
      $file->delete();
      $file->store( $content );
  }
  else
      print $file->contents();
      

*/

class eZCacheFile
{
    function __construct( $root, $component, $suffix = "cache", $separator = "-" )
    {
        if ( strlen( $root ) > 1 and $root[strlen($root) - 1] != "/" )
            $root .= "/";
        $this->Root = $root;
        if ( !is_array( $component ) )
            $component = array( $component );

	//EP -- additional caching parameters -------------------
//	$component[] = $GLOBALS["GlobalSectionID"] ;
	//$component[] = $GLOBALS["HTTP_HOST"]; // future - multihost configuration
	//-------------------------------------------------------

        $this->Components = $component;
        $this->Suffix = $suffix;
        $this->Separator = $separator;
    }

    /*!
      Returns the filename of the cache file. If $with_root is true the root dir is prepended.
    */
    function &filename( $with_root = false )
    {
        if ( empty( $this->Filename ) )
        {            
            //$part = (string) implode( $this->Separator[0], $this->Components );
	    $flatComponents = array_map(function ($item) {
	        return is_array($item) ? json_encode($item) : (string)$item;
		}, $this->Components);

	    $part = implode($this->Separator[0], $flatComponents);

            $this->Filename = $part . "." . $this->Suffix;
        }
        if ( $with_root and empty( $this->AbsFilename ) )
        {
            $this->AbsFilename = $this->Root . $this->Filename;
        }
	
	
        if ( $with_root )
            return $this->AbsFilename;
        else
            return $this->Filename;
    }

    /*!
      Returns true if the file exists.
      \sa isUpToDate()
    */
    function exists()
    {
        $file =& $this->filename( true );
        return file_exists( $file );
    }

    /*!
      Removes the file from the filesystem.
    */
    function delete()
    {
//        print( $this->filename( true ) . "<br>" );
        if ( $this->exists() )
            eZPBFile::unlink( $this->filename( true ) );
    }

    /*!
      Returns true if the file exists and has modification time higher than $modtime.
      \sa exists()
    */
    function isUpToDate( $modtime )
    {
        if ( $this->exists() )
        {
            $mod = eZPBFile::filemtime( $this->filename( true ) );
            return $modtime <= $mod;
        }
        return false;
    }

    /*!
      Returns an eZDateTime object describing when the file was last modified.
    */
    function &lastModified()
    {
        if ( $this->exists() )
        {
            $mod = eZPBFile::filemtime( $this->filename( true ) );
            $datetime = new eZDateTime();

            $datetime->setYear( date( "Y", $mod ) );
            $datetime->setMonth( date( "m", $mod ) );
            $datetime->setDay( date( "d", $mod ) );
            $datetime->setHour( date( "H", $mod ) );
            $datetime->setMinute( date( "i", $mod ) );
            $datetime->setSecond( date( "s", $mod ) );

            return $datetime;
        }
        return false;
    }

    /*!
      Returns the content of the file if it exists and can be read.
    */
    function &contents()
    {
        if ( !$this->exists() )
            print( "<br><b>Cache: File \"" . $this->filename( true ) . "\" does not exist</b><br>" );
        else
        {
            $file = eZPBFile::fopen( $this->filename( true ), "r" );
            if ( $file )
            {
		        $fileSize = eZPBFile::filesize( $this->filename( true ) );
		        if( $fileSize != 0 )
                    $content =& fread( $file, $fileSize );
                fclose( $file );
            }
            else
            {
                print( "<br><b>Cache: Cannot read contents of file \"" . $this->filename( true ) . "\"</b><br>" );
            }
        }
        return $content;
    }

    /*!
      Stores the content to the cache file and returns it.
    */
    function store( $content )
    {
        $file = eZPBFile::fopen( $this->filename( true ), "w" );
        if ( $file )
        {
            fwrite( $file, $content );
            fclose( $file );
        }
        else
        {
            print( "<br><b>Cache: Cannot write contents to file \"" . $this->filename( true ) . "\"</b><br>" );
        }
        return $content;
    }

    /*!
      \static
      Returns a list of eZCacheFile objects matching the $components in directory $root.
      $suffix is suffix for all cache files and $separator is the separator for the $components.

      The $components is expected to be an array, if an entry in the array is NULL a wildcard
      is inserted letting it be possible to find several files of a similar $component structure.
      If an entry is an array the subcomponent can contain either entries found in the array.
      If $as_object is false the returned array contains the actual files without path, otherwise
      eZCacheFile objects are returned.

      Example:
      \code
      // Matching for a specific file
      // This will match "articleview,1,2.cache".
      $files = eZCacheFile::files( "ezarticle/cache", array( "articleview", "1", "2" ), "cache", "," );

      // Matching for files with a "articleview" and "2" in the components.
      // For instance "articleview,1,2.cache" matches.
      $files = eZCacheFile::files( "ezarticle/cache", array( "articleview", NULL, "2" ), "cache", "," );

      // Matching for files with a "articleview" and "2" in the components,
      // but only with "1" and "2" in component #2
      // For instance "articleview,1,2.cache" and "articleview,2,2.cache" matches.
      $files = eZCacheFile::files( "ezarticle/cache", array( "articleview", array( "1", 2" ), "2" ), "cache", "," );
    */
    static public function files( $root, $components, $suffix = "cache", $separator = "-", $as_object = true )
    {
        if ( strlen( $root ) > 1 and $root[strlen($root) - 1] != "/" )
            $root .= "/";
        if ( !is_array( $components ) )
            $components = array( $components );
        $reg = "";
        $i = 0;
        foreach( $components as $comp )
        {
            if ( $i > 0 )
                $reg .= $separator;
            if ( isset( $comp ) )
            {
                if ( is_array( $comp ) )
                {
                    $cond = "";
                    $j = 0;
                    foreach( $comp as $choices )
                    {
                        if ( $j > 0 )
                            $cond .= "|";
                        $cond .= $choices;
                        ++$j;
                    }
                    $cond = "($cond)";
                    $reg .= $cond;
                }
                else
                {
                    $reg .= $comp;
                }
            }
            else
            {
                $reg .= "[^" . $separator . "]*";
            }
            ++$i;
        }
        $reg = "/$reg/";
        $dir = eZPBFile::dir( $root );
        $ret = array();
        while ( $entry = $dir->read() )
        {
            if ( $entry != "." && $entry != ".." )
            {
                if ( preg_match( $reg, $entry ) )
                {
                    $parts = explode( ".", $entry );
                    if ( $as_object )
                        $ret[] = new eZCacheFile( $root, explode( $separator, $parts[0] ),
                                                  $parts[1], $separator );
                    else
                        $ret[] = $entry;
                }
            }
        }

        $dir->close();
        return $ret;
    }

    var $Root;
    var $Components;
    var $Suffix;
    var $Separator;
    var $Filename;
    var $AbsFilename;
}

?>