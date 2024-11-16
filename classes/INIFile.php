<?php
//
// $Id: INIFile.php 9625 2002-06-11 08:36:33Z jhe $
//
// Implements a simple INI-file parser
//
// Based upon class.INIfile.php by Mircho Mirev <mircho@macropoint.com>
//
// Created on: <09-Jun-2001 07:18:20 bf>
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
//! The INIFile class provides .ini file functions.
/*!
  The INI file class supports comments which starts with a # and stops at the end of the line,
  this means that one cannot use these characters in groups, keys or values.

  The INI file can also read MS-DOS text files,
  which has an extra carriage return to signal an end of line.

  \code
  include_once( "classes/INIFile.php" );
  $ini = new INIFile( "site.ini" );

  $PageCaching = $ini->read_var( "eZArticleMain", "PageCaching" );

  $arrayTest = $ini->read_array( "site", "ArrayTest" );

  foreach ( $arrayTest as $test )
  {
    print( "test: ->$test<-<br>" );
  }
  \endcode
*/

class INIFile
{

    /*!
      Constructs a new INIFile object.
    */
    function __construct( $inifilename = "", $write = false )
    {
        include_once( "classes/ezfile.php" );

        $cachedFile = "classes/cache/" . md5( eZFile::realpath( $inifilename ) ) . ".php";

        // check for modifications
        $cacheTime = eZFile::filemtime( $cachedFile );
        $origTime = eZFile::filemtime( $inifilename );
        $overrideTime = eZFile::filemtime( "bin/ini/override/" . basename($inifilename) );
        $appendTime = eZFile::filemtime( "bin/ini/override/" . basename($inifilename) . ".append" );

        $loadCache = false;
        if ( eZFile::file_exists( $cachedFile ) )
        {
            $loadCache = true;
            if ( $cacheTime < $origTime )
                $loadCache = false;
            if ( eZFile::file_exists( "bin/ini/override/" . basename($inifilename) ) and $cacheTime < $overrideTime )
                $loadCache = false;
            if ( eZFile::file_exists( "bin/ini/override/" . basename($inifilename) . ".append" ) and $cacheTime < $appendTime )
                $loadCache = false;
        }

        if ( $loadCache )
        {
            include( $cachedFile );
        }
        else
	    {
            $this->load_data( $inifilename, $write );
            // save the data to a cached file
            $buffer = "";
            $i = 0;
            reset( $this->GROUPS );

            foreach( $this->GROUPS as $groupKey => $groupVal )
            {
                reset( $groupVal );
		
                foreach( $groupVal as $key => $val )
                {
                    $tmpVal = str_replace( "\"", "\\\"", $val );

                    $buffer .= "\$Array_" . $i . "[\"$key\"] = \"$tmpVal\";\n";
                }

                $buffer .= "\$this->GROUPS[\"$groupKey\"] =& \$Array_" . $i . ";\n";
                $i++;
            }
            $buffer = "<?php\n" . $buffer . "\n?>";

            $fp = eZFile::fopen( $cachedFile, "w+" );
            fwrite ( $fp, $buffer );
            fclose( $fp );
        }

    }

    function load_data( $inifilename = "", $write = true, $useoverride = true )
    {
        $this->WRITE_ACCESS = $write;
        if ( !empty( $inifilename ) )
        {
            if ( !eZFile::file_exists( $inifilename ) )
            {
                if ( eZFile::file_exists( $inifilename . ".php") )
                {
                    $this->parse( $inifilename . ".php" );
                }
                else
                {
                    $this->error( "The file \"" . $inifilename . "\" or \"" . $inifilename . ".php\" does not exist!" );
                }
            }
            else
	    	{
				if ( $inifilename == "bin/ini/site.ini" ) 
				{
		 	  		$filesiteini = "site.ini";

		   			if ( eZFile::file_exists( "bin/ini/override/$filesiteini" ) ) 
		   			{
		       			if ( eZFile::file_exists( "bin/ini/override/$filesiteini" . ".php") ) 
		       			{
			   				$this->parse( "bin/ini/override/$filesiteini" . ".php" );
		       			}
			       		else 
			       		{
				 			$this->parse( "bin/ini/override/$filesiteini" );
				 			$this->error( "The file \"" . "bin/ini/override/$filesiteini" . "\" or \"" . "bin/ini/override/$filesiteini" . ".php\" does not exist!" );
			       		}
			   		} 
			   		else 
			   		{
			     		if ( eZFile::file_exists( "bin/ini/$filesiteini" ) ) 
			     		{
			       			if ( eZFile::file_exists( "bin/ini/$filesiteini" . ".php") )
			       			{
				 				$this->parse( "bin/ini/$filesiteini" . ".php" );
			       			}
			       			else
			       			{
				 				$this->parse( "bin/ini/$filesiteini" );
				 				$this->error( "The file \"" . "bin/ini/$filesiteini" . "\" or \"" . "bin/ini/$filesiteini" . ".php\" does not exist!" );
			       			}
			     		}
			   		}
				} 
				else 
				{
			  		$this->parse( $inifilename );
				}
        	}
    	}
    	if ( $useoverride ) 
    	{
    		$this->load_override_data( "bin/ini/override/" . basename($inifilename) );
    	}
    }
    
    function load_override_data( $inifilename = "" )
    {
        $appendfilename = $inifilename . ".append";
        if ( !empty( $inifilename ) and eZFile::file_exists( $inifilename ) )
        {
            $this->parse( $inifilename, false );
        }
        else if ( !empty( $appendfilename ) and eZFile::file_exists( $appendfilename ) )
        {
            $this->parse( $appendfilename, true );
        }
    }

    static public function file_exists( $inifilename )
    {
        return ( eZFile::file_exists( "override/" . $inifilename . ".append" ) or
                 eZFile::file_exists( "override/" . $inifilename ) or
                 eZFile::file_exists( $inifilename ) );
    }

    /*!
      Parses the ini file.
    */
    function parse( $inifilename, $append = false )
    {
        $this->INI_FILE_NAME = $inifilename;

        $fp = eZFile::fopen( $inifilename, $this->WRITE_ACCESS ? "r+" : "r" );

        if ( !isset( $this->CURRENT_GROUP ) or !$append )
             $this->CURRENT_GROUP = false;
        if ( !isset( $this->GROUPS ) or !$append )
             $this->GROUPS = array();

        $contents = fread( $fp, eZFile::filesize( $inifilename ) );

        // Remove trailing empty lines in the file
        // This should be the code used, but for some reason it will not work
        //$contents = eregi_replace( "\n*$", "", $contents );

        // So instead we'll do a hack until the reason for the error is found
        $contents .= "\n";

        $ini_data = preg_split( "/\n/",$contents );

        foreach( $ini_data as $key => $data )
        {
            // Remove MS-DOS Carriage return from end of line
            $data = preg_replace( "/\r*$/", "", $data );
            $this->parse_data( $data );
        }

        fclose( $fp );
    }

    /*!
      Parses the variable.
    */
    function parse_data( $data )
    {
        // Remove comments from line
        if ( preg_match( "/([^#]*)#.*/", $data, $m ) )
        {
            $data = $m[1];
        }

        if( preg_match( "/^\[([[:alnum:]]+)\]/", $data, $out ) )
        {
            $this->CURRENT_GROUP = strtolower( $out[1] );
        }
        else
        {
            $split_data = preg_split( "/=/", $data );

            if ( !isset( $split_data[1] ) )
                $split_data[1] = "";
            $this->GROUPS[ $this->CURRENT_GROUP ][ $split_data[0] ] = $split_data[1];
        }
    }

    /*!
      Saves the ini file.
    */
    function save_data()
    {
        $fp = eZFile::fopen( $this->INI_FILE_NAME, "w" );

        if ( empty( $fp ) )
        {
            $this->Error( "Cannot create file " . $this->INI_FILE_NAME );
            return false;
        }

        $groups = $this->read_groups();
        $group_cnt = count( $groups );

        for( $i = 0; $i < $group_cnt; $i++ )
        {
            $group_name = $groups[ $i ];
            if ( $i == 0 )
            {
                $res = sprintf( "[%s]\n", $group_name );
            }
            else
            {
                $res = sprintf( "\n[%s]\n",$group_name );
            }
            fwrite( $fp, $res );

            $group = $this->read_group( $group_name );

	    foreach( $group as $key => $data )
            {
                $res = "$key=$data\n";
                fwrite( $fp, $res );
            }
        }

        fclose( $fp );
    }

    /*!
      Returns the number of groups.
    */
    function get_group_count()
    {
        return count($this->GROUPS);
    }

    /*!
      Returns an array with the names of all the groups.
    */
    function read_groups()
    {
        $groups = array();
        for ( reset( $this->GROUPS ); $key = key( $this->GROUPS ); next( $this->GROUPS ) )
            $groups[] = $key;
        return $groups;
    }

    /*!
      Checks if a group exists.
    */
    function group_exists( $group_name )
    {
        $group_name = strtolower( $group_name );
        $group =& $this->GROUPS[ $group_name ];
        if ( empty( $group ) ) return false;
        else return true;
    }

    /*!
      Returns an associative array of the variables in one group.
    */
    function read_group( $group_name )
    {
        $group_name = strtolower( $group_name );
        $group_array =& $this->GROUPS[ $group_name ];
        if(! empty( $group_array ) )
            return $group_array;
        else
        {
            $this->Error( "Group " . $group_name . " does not exist" );
            return false;
        }
    }

    /*!
      Adds a new group to the ini file.
    */
    function add_group( $group_name )
    {
        $group_name = strtolower( $group_name );
        $new_group = $this->GROUPS[ $group_name ];
        if ( empty( $new_group ) )
        {
            $this->GROUPS[ $group_name ] = array();
        }
        else
        {
            $this->Error( "Group " . $group_name . " exists" );
        }
    }

    /*!
      Clears a group.
    */
    function empty_group( $group_name )
    {
        $group_name = strtolower( $group_name );
        unset( $this->GROUPS[ $group_name ] );
        $this->GROUPS[ $group_name ] = array();
    }

    /*!
      Returns true if the group and variable exists.
    */
    function has_var( $group_name, $var_name )
    {
        $group_name = strtolower( $group_name );
        return isset( $this->GROUPS[ $group_name ] ) and isset( $this->GROUPS[ $group_name ][ $var_name ] );
    }

    /*!
      Reads a variable from a group.
    */
    function read_var( $group_name, $var_name )
    {
        $group_name = strtolower( $group_name );

	// EP: multilingual interface in administrator ---------------------------------------------------

//	if ( $var_name == "Language" and $GLOBALS["SCRIPT_NAME"] == "/index_admin.php" )
//	{
//	    global $Language;
//
//	    include_once( "ezsession/classes/ezsession.php" );
//            $session =& eZSession::globalSession();
//            $session->fetch();
//
//	    if ( isset ( $Language ))
//	    {
//		$session->setVariable( "AdminSiteLanguage", $Language );
//	    }
//
//            $Language =& $session->variable( "AdminSiteLanguage" );
//	}
//
//        if ( $Language <> "" )
//        {
//	    return $Language;
//        }

	// EP --------------------------------------------------------------------------------------------

        if ( !isset( $this->GROUPS[ $group_name ] ) or !isset( $this->GROUPS[ $group_name ][ $var_name ] ) )
        {
            $this->Error( $var_name . " does not exist in " . $group_name );
            return false;
        }
        return $this->GROUPS[ $group_name ][ $var_name ];
    }

    /*!
      Reads a variable from a group and returns the result as an
      array of strings.

      The variable is split on ; characters.
    */
    function read_array( $group_name, $var_name )
    {
        if ( $this->has_var( $group_name, $var_name ) )
        {
            $var_value =& $this->read_var( $group_name, $var_name );
            if ( $var_value != "" )
            {
                $var_array = explode( ";", $var_value );
            }
            else
            {
                $var_array = array();
            }
            return $var_array;
        }
        else
        {
            $this->Error( "array " . $var_name . " does not exist in " . $group_name );
            return false;
        }
    }

    /*!
      Sets a variable in a group.
    */
    function set_var( $group_name, $var_name, $var_value )
    {
        $group_name = strtolower( $group_name );
        $this->GROUPS[ $group_name ][ $var_name ] = $var_value;
    }

    /*!
      Prints the error message.
    */
    function error( $errmsg )
    {
        $this->ERROR = $errmsg;
        if ( $GLOBALS["DEBUG"] == true )
        {
            print( "Error:" . $this->ERROR . "<br>\n" );
        }
        return;
    }

    /*!
      \static
      Returns the global ini file for a given type. Normally the type is the site.ini INI object,
      loaded from the site.ini file. This can be overidden by supplying $type and $file.
      If the ini-file object does not exist it is created before returning.
    */
    static public function &globalINI( $type = "SiteIni", $file = "bin/ini/site.ini" )
    {
        $ini =& $GLOBALS["INI_$type"];

        if ( !is_a( $ini, "INIFile" ) )
        {
            $ini = new INIFile( $file );
        }

        return $ini;
    }

    var $INI_FILE_NAME = "";
    var $ERROR = "";
    var $GROUPS = array();
    var $CURRENT_GROUP = "";
    var $WRITE_ACCESS = "";
    var $Index = "";
    var $WWWDir = "";
    var $SiteDir = "";
}