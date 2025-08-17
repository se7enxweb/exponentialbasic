<?php
// 
// $Id: eztemplate.php 9474 2002-04-25 09:28:35Z jhe $
//
// Definition of eZTemplate class
//
// Based upon template.inc by Kristian Koehntopp @ NetUSE GmbH
//
// Created on: <11-Sep-2000 22:10:06 bf>
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
//! The eZTemplate class provides template functions. In regard to locale information.
/*!
  This class provides functions for using templates with internationalized language.
  Template variables which start with intl- are looked up in the language file and
  replaced with text in the described language.

  Handling templates is done by setting the template file,
  setting up blocks and filling in template variables.

  Setting up the template file is done by
  \code
  $t->set_file( "template_tpl", "template.tpl" );
  \endcode
  or by using several files
  \code
  $t->set_file( array( "template_tpl" => "template.tpl",
                       "second_tpl" => "second.tpl" ) );
  \endcode

  If you want to fill in an internationalized language you do
  \code
  $t->setAllStrings()
  \endcode
  all strings are then taken from the language file and supplied as template variables.

  Setting up blocks is done by filling in template blocks in the template file
  \code
  <!-- BEGIN my_block_tpl -->
  <b>{my_variable}</b>
  <!-- END my_block_tpl -->
  \endcode
  and defining them in the template object.
  \code
  $t->set_block( "template_tpl", "my_block_tpl", "my_block" );
  \endcode

  Filling in template variables is either done by setting them directly
  \code
  $t->set_var( "my_variable", "some text" );
  \endcode
  or by parsing a predefined block
  \code
  $t->parse( "my_block", "my_block_tpl" );
  \encode
  alternativley parsing a predefined block several times
  \code
  foreach( $list as $item )
  {
      $t->set_var( "item", $item );
      $t->parse( "my_block", "my_block_tpl", true );
  }
  \encode

  The class can also carry out caching of template content.
  Example:
  \code
  $t->set_file( "template_tpl", "template.tpl" );
  if ( $t->hasCache() )
  {
      print $t->cache();
      return;
  }

  // Do lot's of template stuff

  // store the cache and print it
  $t->storeCache( "output", "template_tpl" );
  \endcode

*/

// include_once( "kernel/classes/INIFile.php" );
// include_once( "kernel/classes/ezlog.php" );

class eZTemplate
{
    var $language;
    var $phpFile;
    var $intlDir;

    /*!
      Constructs a new eZTemplate object.
    */
    function __construct( $templateDir = "", $intlDir = "", $language = "",
                         $phpFile = "", $style = false, $module_dir = false,
                         $state = false, $mod_time = false )
    {
        // check for PHP version, if > 4.0.5 use new and improved str_replace
        // instead of preg_replace. Enables usage of $ in template variables.
        $versionArray = explode( ".", phpversion() );
        
        $major = $versionArray[0]; 
        $minor = $versionArray[1]; 
        $release = $versionArray[2];

        if ( $major >= 4 && ( ( $minor == 0 && $release >= 5 ) || ( $minor > 0 ) ) )
        {
            $this->ReplaceFunc = "str_replace";
        }
        else
        {
            $this->ReplaceFunc = "preg_replace";
        }            
        
        // section override variables
        if ( isset( $GLOBALS["eZLanguageOverride"] ) and $GLOBALS["eZLanguageOverride"] != "" )
            $language = $GLOBALS["eZLanguageOverride"];

        if ( isset( $GLOBALS["eZTemplateOverride"] ) and $GLOBALS["eZTemplateOverride"] != "" )
        {
            // override template dir
            $templateDir = preg_replace( "#^(.*?)/(.*?)/(.*?)/(.*?)/$#", "\\1/\\2/\\3/" . $GLOBALS["eZTemplateOverride"] . "/", $templateDir );
        }
        
        $this->intlDir =& $intlDir;
        $this->language =& $language;
        $this->phpFile =& $phpFile;
        $this->Style = $style;
        $this->ModuleDir = $module_dir;
        $this->TextStrings = array();
        $this->State = $state;
        $this->ExternModTime = $mod_time;

        $this->set_root( $templateDir );
        $this->set_unknowns( "remove" );
        $this->debugINI = eZINI::instance( 'site.ini' );

        if ( is_array( $phpFile ) and is_array( $intlDir ) )
        {
            $this->languageFile = array();
            $this->Ini = eZINI::instance( 'site.ini' );

            foreach ( $phpFile as $php_file )
            {
                $lang_file = $intlDir[1] . "/" . $language . "/" . $php_file . ".ini";
                $this->languageFile[] = $lang_file;

                if ( file_exists( $lang_file ) )
                {
                    $this->Ini->instance( $lang_file, false );
                    $this->TextStrings = array_merge( $this->TextStrings, $this->Ini->groups( "strings" ) );
                }
                else
                {
                    if ( $this->debugINI->variable( "site", "DebugOutput" ) == "enabled" )
                    {
                        print( "<br><b>Error: language file, $lang_file, could not be found.</b><br>" );
                    }
                }
                
            }
            if ( $this->debugINI->variable( "site", "DebugOutput" ) == "enabled" )
            {
                if ( count( $this->languageFile ) == 0 )
                {
                    print( "<br><b>Error: no language files found.</b><br>" );
                }
            }
        }

        if ( !is_array( $phpFile ) and !is_array( $intlDir ) )
        {
            $this->languageFile = $intlDir . "/" . $language . "/" . $phpFile . ".ini";
            $this->Ini = eZINI::instance( 'site.ini' );

            // 7x: Add a setting for this debug output statement to be encouraged durring debugging.
            // Add a setting to enable this statement.
            if( $this->debugINI->variable( "site", "DebugOutput" ) == "enabled" && $GLOBALS["DEBUG_EZTEMPLATE"] == true )
            {
                echo "<hr>"; echo $this->languageFile; echo "<hr>";
            }
            
            if ( file_exists( $this->languageFile ) )
            {
                $this->Ini = eZINI::instance( $this->languageFile, false );
                $this->TextStrings = $this->Ini->groups( "strings" );
            }
            else
            {
                if ( $this->debugINI->variable( "site", "DebugOutput" ) == "enabled" )
                {
                    debug_print_backtrace();
                    print( "<br><b>Error: language file, $this->languageFile, could not be found.</b><br>" );
                }
            }
        }
        else
        {
            if ( $this->debugINI->variable( "site", "DebugOutput" ) == "enabled" )
            {                    
                print( "<br><b>Error: $" . "intlDir and $" . "phpFile must either be arrays or strings.</b><br>" );
            }
        }

        if ( empty( $this->Style ) || empty( $this->ModuleDir ) )
        {
            $this->CacheSuffix = "";
            $this->CacheDir = "";
        }
        else
        {
            $this->CacheDir = $module_dir . "/cache";
            if ( !empty( $state ) )
                $state = "-" . $state;
            $this->CacheSuffix = $style ."-" . $language . $state . ".cache";
        }
    }

    /*!
      Sets all internationalisations.
    */
    function setAllStrings()
    {
        if ( isset( $this->TextStrings ) and is_array( $this->TextStrings ) && isset( $this->TextStrings["strings"] ) )
        {
            reset( $this->TextStrings );
            foreach ($this->TextStrings["strings"] as $i => $tmp)
            {
                $tmp_key = "intl-" . strtolower($i);
                $this->set_var_internal( $tmp_key, $tmp );
                // Debug pro tools
                // echo '<hr>';var_dump($tmp_key); echo '<hr>';
            }
        }
    }

    /*!
      Returns the name of the cache file with path.
    */
    function &cacheFile()
    {
        $CacheFile = $this->CacheDir . "/" . $this->files[0] . "-" . $this->CacheSuffix;
        return $CacheFile;
    }

    /*!
      Returns the name of the cache file.
    */
    function &cacheFileName()
    {
        $CacheFile = $this->files[0] . "-" . $this->CacheSuffix;
        return $CacheFile;
    }

    /*!
      Returns the path to the cache file.
    */
    function &cachePath()
    {
        $CachePath = $this->CacheDir;
        return $CachePath;
    }

    /*!
      Clears all template variables except the ones mentioned in the $except variable.
    */

    function clearVars( $except = array() )
    {
        $tmpkeys = array();
        $tmpvals = array();
        foreach ( $except as $key )
        {
            if ( isset( $varkeys[$key] ) && isset( $varvals[$key] ) )
            {
                $tmpkeys[$key] =& $varkeys[$key];
                $tmpvals[$key] =& $varvals[$key];
            }
        }
        $varkeys = array();
        $varvals = array();
        $varkeys = $tmpkeys;
        $varvals = $tmpvals;
    }

    /*!
      Returns true if the template file has a cache file which can be read,
      if the cache file does not exist or is too old, false is returned.
    */
    function hasCache()
    {
        if ( empty( $this->CacheSuffix ) )
            return false;
        if ( empty( $this->files[0] ) )
            return false;
        $CacheFile =& $this->cacheFile();
        if ( file_exists( $CacheFile ) )
        {
            $template_m = eZPBFile::filemtime( $this->filename( $this->files[0] ) );
            if ( is_array( $this->languageFile ) )
            {
                $lang_m = 0;
                foreach ( $this->languageFile as $lang )
                {
                    $modtime = eZPBFile::filemtime( $lang );
                    if ( $modtime > $lang_m )
                        $lang_m = $modtime;
                }
            }
            else
                $lang_m = eZPBFile::filemtime( $this->languageFile );
            $cache_m = eZPBFile::filemtime( $CacheFile );
            if ( $template_m <= $cache_m && $lang_m <= $cache_m )
            {
                if ( isset($this->ExternModtime) or $this->ExternModTime <= $cache_m )
                    return true;
            }
        }
        return false;
    }

    /*!
      If the hasCache() returns true the cache content can be retrieved with this function,
      otherwise false is returned.
    */

    function &cache()
    {
        if ( empty( $this->CacheSuffix ) )
            return false;
        if ( empty( $this->files[0] ) )
            return false;
        $CacheFile =& $this->cacheFile();
        if ( file_exists( $CacheFile ) )
        {
            $fd = eZPBFile::fopen( $CacheFile, "r" );
            $str =& fread( $fd, eZPBFile::filesize( $CacheFile ) );
            fclose( $fd );
            return $str;
        }
        return false;
    }

    /*!
      Instead of using pparse for printing out the template,
      one can use this to store the template content to a cache file and eventually print it.
      If $print is true the template content is printed.
    */
    function &storeCache( $target, $handle, $print = true )
    {
        if ( empty( $this->CacheSuffix ) )
            return false;
        $str =& $this->parse( $target, $handle );
        $CacheFile =& $this->cacheFile();
        if ( !file_exists( $this->CacheDir ) )
        {
            if ( $this->debugINI->variable( "site", "DebugOutput" ) == "enabled" )
            {                    
                print( "<br /><b>TemplateCache: directory $this->CacheDir does not exist, cannot create cache file</b><br />" );
            }
        }
        else
        {
            $fd = eZPBFile::fopen( $CacheFile, "w" );
            fwrite( $fd, $str );
            fclose( $fd );
        }
        if ( $print )
        {
            print $str;
        }
        return $str;
    }

    /*!
      Clears the cache file if it exists.
    */
    function clearCache()
    {
        if ( empty( $this->CacheSuffix ) )
            return false;
        $CacheFile =& $this->cacheFile();
        if ( !file_exists( $this->CacheDir ) )
        {
            if ( $this->debugINI->variable( "site", "DebugOutput" ) == "enabled" )
            {
                print( "<br /><b>TemplateCache: directory $this->CacheDir does not exist, cannot delete cache file</b><br />" );
            }
        }
        else
        {
            if ( file_exists( $this->CacheFile ) )
                return eZPBFile::unlink( $CacheFile );
            else
                return true;
        }
        return false;
    }

    /*!
      Returns a reference to the ini file object.
    */
    function &ini()
    {
        return $this->Ini;
    }

    /*!
     Sets the template directory.
    */  
    function set_root($root)
    {
        if ( $root != "" )
        {
			global $GlobalSiteIni;
            $root = $GlobalSiteIni->SiteDir . $root;
        }

        if ( !is_dir( $root ) )
        {
            $this->halt( "set_root: $root is not a directory." );
            return false;
        }
    
        $this->root = $root;
        return true;
    }

    /*!
     Sets what to do with uknown templates variables
    */
    function set_unknowns( $unknowns = "keep" )
    {
        $this->unknowns = $unknowns;
    }

    /*!
      Sets the file(s) to be working on, its either set to one file with
      two parameters, set_file( $handle, $filename ), or by several file
      using an array, set_file( array( "file1_tpl" => "file1.tpl",
                                       "file2_tpl" => "file2.tpl" ) );
    */
    function set_file( $handle, $filename = "" )
    {
        if ( !is_array( $handle ) )
        {
            if ( $filename == "" )
	      { 
                // kracker : Added Support for template filename error tracking
                $phpFile = $this->phpFile;
                $this->halt( "set_file ($phpFile): For handle $handle filename is empty." ); 
                return false;
            }
            $this->file[$handle] = $this->filename( $filename );
            $this->files = array( $filename );
        }
        else
        {
            $this->files = array();
            reset( $handle );
	    foreach ($handle as $h => $f)
	    {
                $this->file[$h] = $this->filename( $f );
                $this->files[] = $f;
            }
        }

        // For non-virtualhost, non-rewrite setup
        global $GlobalSiteIni;
        $this->set_var( 'www_dir', $GlobalSiteIni->WWWDir );
        $this->set_var( 'index', $GlobalSiteIni->Index );
    }

    /*!
      Extract a template block and set it as a template variable,
      the content of the template variable is the same as the content of the block.
      If $parent is an array each entry is extracted and set as a block,
      each entry is assumed to contain a parent, a handle and a name.
    */
    function set_block( $parent, $handle = "", $name = "", $required = true )
    {
        if ( !is_array( $parent ) )
        {
            if ( !$this->loadfile( $parent ) )
            {
                if ( $required )
                {
                    	$phpFile = $this->phpFile;
						$this->halt("subst ($phpFile): unable to load $parent.");
                }
                return false;
            }
            if ( $name == "" )
                $name = $handle;

            $str =& $this->get_var( $parent );
            $reg = "/<!--\s+BEGIN $handle\s+-->(.*)\n\s*<!--\s+END $handle\s+-->/sm";
            preg_match( $reg, $str, $m );
            $str =& preg_replace( $reg, "{" . "$name}", $str );

            if( is_array( $m ) and isset( $m[1] ) )
            {
                $handleValue = $m[1];
            }
            else {
                $handleValue = "";
            //var_dump($m); echo '<hr>';
            }
            $this->set_var_internal( $handle, $handleValue );
            $this->set_var_internal( $parent, $str );
        }
        else
        {
            foreach ( $parent as $block )
            {
                $this->set_block( $block[0], $block[1], $block[2] );
            }
        }
        return true;
    }

    /*!
      Extract the contents of a file named with set_file and sets it as a template_variable.
    */
    function set_file_block( $parent )
    {
        if ( is_array( $parent ) )
        {
            reset( $parent );
            foreach ( $parent as $file => $val )
            {
                if ( !$this->loadfile( $file ) )
                {
                    $this->halt( "set_file_block: unable to load $parent." );
                    return false;
                }
            }
        }
        else
        {
            if ( !$this->loadfile( $parent ) )
            {
                $this->halt( "set_file_block: unable to load $parent." );
                return false;
            }
        }
        return true;
    }


    /*!
      Sets a template variable to contain a certain value.
      If $value is an empty string the contents will be replaced
      with the contents of $subsIfEmtpy (which also is empty as default)
    */
    function set_var( $varname, $value = "", $substIfEmpty = "" )
    {
        if ( is_string( $value ) and $value == "" )
            $value = $substIfEmpty;

        $this->set_var_internal( $varname, $value );
    }

    /*!
      \private
      Sets the template variable using references.
    */
    function set_var_internal( &$varname, $value )
    {
        if ( !is_array( $varname ) )
        {
            if ( $this->ReplaceFunc != "str_replace" )
            {
                $this->varkeys[$varname] =& preg_quote("/{".$varname."}/");
                $this->varvals[$varname] =& $value;
            }
            else
            {                
                $var = "{".$varname."}";
                $this->varkeys[$varname] =& $var;
                $this->varvals[$varname] =& $value;
            }
        }
        else
        {
            reset( $varname );
            foreach( $varname as $k => $v )
            {
                if ( $this->ReplaceFunc != "str_replace" )
                {                
                    $this->varkeys[$k] =& preg_quote( "/{".$k."}/" );
                    $this->varvals[$k] =& $v;
                }
                else
                {
                    $var = "{".$k."}";
                    $this->varkeys[$k] =& $var;
                    $this->varvals[$k] =& $v;
                }
            }
        }
    }

    /*!
      \private
      Retrieves a template variable and subsistutes all variable in that with
      all defined template variables and returns it.
    */
    function &subst( $handle )
    {
        if ( !$this->loadfile( $handle ) )
        {
            $this->halt( "subst: unable to load $handle." );
            return false;
        }
        $replacedString = array();
        $str = $this->get_var( $handle );
        $rFunc = $this->ReplaceFunc;

        if( is_string( $str ) )
        {
            // 7x - Note: Refactor this code
            //foreach( (array) $str as $string ) {
            //    $replacedString[] = $string;
            //}
            $str = @$rFunc( $this->varkeys, $this->varvals, $str );
        }
        else
        {
            $replacedString =& $rFunc( $this->varkeys, $this->varvals, $str );
        }
        
        return $str;
    }

    /*!
      Same as subst() but prints it.
    */
    function psubst( $handle )
    {
        print $this->subst( $handle );
    
        return false;
    }

    /*!
      Parses a the content of a block into a template variable and returns it.
      If $target is array treat each key/value as a target and handle.
      If $handle is array set $target to content of all $handle variables
    */
    function &parse( $target, $handle = "", $append = false )
    {
        if ( is_array( $target ) )
        {
            reset( $target );
            foreach ( $target as $targ => $hndl )
            {
                unset( $str );
                $str =& $this->subst( $hndl );
                if ( $append )
                {
                    $tmp = $this->get_var( $targ ) . $str;
                    $this->set_var_internal( $targ, $tmp );
                }
                else
                {
                    $this->set_var_internal( $targ, $str );
                }
            }
        }
        else if ( !is_array( $handle ) )
        {
            $str =& $this->subst( $handle );
            if ( $append )
            {
                $tmp = $this->get_var( $target ) . $str;
                $this->set_var_internal( $target, $tmp );
            }
            else
            {
                $this->set_var_internal( $target, $str );
            }
        }
        else
        {
            reset( $handle );
            foreach ( $handle as $i => $h )
            {
                $str =& $this->subst( $h );
                $this->set_var_internal( $target, $str );
            }
        }
        return $str;
    }

    /*!
      Same as parse() but prints it.
    */

    function pparse( $target, $handle, $append = false )
    {
        print $this->parse( $target, $handle, $append );
        return false;
    }
  
    /*!
      Returns an array of template variables.
    */
    function &get_vars()
    {
        reset( $this->varkeys );
        while ( list( $k, $v ) = each( $this->varkeys ) )
        {
            $result[$k] = $v;
        }
    
        return $result;
//          return $this->varkeys;
    }

    /*!
      Returns the content of a specific template variable.
    */
    function &get_var( $varname )
    {
        $err = false;
        if ( !is_array( $varname ) )
        {
            if ( isset( $this->varvals[$varname] ) )
            {
                return $this->varvals[$varname];
            }
            else
            {
                return $err;
            }
            // return $this->varkeys[$varname];
        }
        else
        {
            reset( $varname );
            while ( list( $k, $v ) = each( $varname ) )
            {
                $result[$k] =& $this->varvals[$k];
//                  $result[$k] =& $this->varkeys[$k];
            }

            return $result;
        }
    }

    /*!
      Returns the contents of a template defined variable.
      You can define variables in the template like this:
      <!-- VAR VariableName=value -->

      This is useful when using override templates and want to define some values
      just for this template. These variables can be read from the script and override
      the site.ini settings.

      Returns the value if found else false is returned.
    */
    function get_user_variable( $handle, $var )
    {
        $this->loadfile( $handle );
        preg_match( "#<!--\s+VAR\s+$var=(.*?)\s+-->#", $this->varvals[$handle], $matches );
        
        if ( isset( $matches[1] ) )
            return $matches[1];
        else
            return false;                  
    }
  
    /*!
     Returns an array of undefined template variables inside another template variable.
    */
    function get_undefined($handle)
    {
        if ( !$this->loadfile( $handle ) )
        {
            $this->halt( "get_undefined: unable to load $handle." );
            return false;
        }

        preg_match_all( "/\{([^}]+)\}/", $this->get_var( $handle ), $m );
        $m = $m[1];
        if ( !is_array( $m ) )
            return false;

        reset( $m );
        while ( list( $k, $v ) = each( $m ) )
        {
            if ( !isset( $this->varkeys[$v] ) )
                $result[$v] = $v;
        }
    
        if ( count( $result ) )
            return $result;
        else
            return false;
    }

    /*!
      Does an operation on the $str depending on what setUnkowns is set too and returns it.
      If set to:
      "keep", no change to string
      "remove", removes all template variables
      "comment", comments out all template variables
      "nbsp", changes all template variables into a non-breaking space
      \sa set_unknowns
     */
    function &finish( $str )
    {
        switch ( $this->unknowns )
        {
            case "keep":
                break;

            case "remove":
                $str =& preg_replace( '/{[^ \t\r\n}]+}/', "", $str );
            break;

            case "comment":
                $str =& preg_replace( '/{([^ \t\r\n}]+)}/', "<!-- Template $handle: Variable \\1 undefined -->", $str );
            break;

            case "nbsp":
                $str =& preg_replace( '/{[^ \t\r\n}]+}/', "&nbsp;", $str );
        }
        return $str;
    }

    /*!
      Prints out the content of template variable after it has gone trough finish
      \sa finish(), get()
    */
    function p( $varname )
    {
        print $this->finish( $this->get_var( $varname ) );
    }

    /*!
      Returns the content of template variable after it has gone trough finish
      \sa finish(), p()
    */

    function get( $varname )
    {
        return $this->finish( $this->get_var( $varname ) );
    }
    
    /*!
      \private
      Returns a full filepath of the specified filename.
    */
    function filename( $filename )
    {
        $root = $this->root;
        if ( is_array( $filename ) )
        {
            $root = $filename[0];
            $filename = $filename[1];
        }
        if ( substr( $filename, 0, 1 ) != "/" )
        {
            $filename = $root."/".$filename;
        }

        if ( !file_exists( $filename ) )
            $this->halt( "filename: file $filename does not exist." );

        return $filename;
    }
  
    /*!
      Loads the template file and sets as a template variable.
    */
    function loadfile( $handle )
    {
        if ( isset( $this->varkeys[$handle] ) and !empty( $this->varvals[$handle] ) )
            return true;

        // if (isset($this->varkeys[$handle]) and !empty($this->varkeys[$handle]))
        // return true;
        // var_dump( $this->file );
        if ( !isset( $this->file[$handle] ) )
        {
            $this->halt( "loadfile: $handle is not a valid handle." );
            return false;
        }

        $file = $this->file[$handle];
        $filename = $this->file[$handle];

        if ( file_exists( $filename ) )
        {
            $fd = fopen( $filename, "r" );
            $str =& fread( $fd, filesize( $filename ) );
            fclose( $fd );
            if ( empty( $str ) )
            {
                $this->halt( "loadfile: While loading $handle, $filename does not exist or is empty." );
                return false;
            }
        }
        else
        {
            $this->halt( "loadfile: Cannot find template file \"$filename\"" );
            return false;
        }

        $this->set_var_internal( $handle, $str );
    
        return true;
    }

    /*!
      \private
      Prints out an error message and halts.
      Whether to print or halt is controlled by the halt_on_error variable.
    */
    function halt( $msg )
    {
        $this->last_error = $msg;

        if ( $this->halt_on_error != "no" )
            $this->haltmsg($msg);

        if ( $this->halt_on_error == "yes" )
            die( "<b>Halted.</b>" );

        return false;
    }

    /*!
      \private
      Prints out the halt message
    */
    function haltmsg( $msg )
    {
        $err_msg = "<b>Template Error:</b> $msg<br>\n";
        if ( $this->debugINI->variable( "site", "DebugOutput" ) == "enabled" )
        {
            print( $err_msg );
        }
        eZPBLog::writeNotice( $err_msg );
    }

    var $TextStrings;
    var $Ini;
    var $debugINI;
    var $Style;
    var $ModuleDir;
    var $State;
    var $languageFile;
    var $CacheSuffix;
    var $CacheDir;
    var $ExternModTime;

    var $files = array();

    /* $file[handle] = "filename"; */
    var $file  = array();

    /* relative filenames are relative to this pathname */
    var $root   = "";

    /* $varkeys[key] = "key"; $varvals[key] = "value"; */
    var $varkeys = array();
    var $varvals = array();

    /* "remove"  => remove undefined variables
     * "comment" => replace undefined variables with comments
     * "keep"    => keep undefined variables
     * "nbsp"    => replace all undefined variables with &nbsp; (very nice in tables with bg color)
     */
    var $unknowns = "remove";
  
    /* "yes" => halt, "report" => report error, continue, "no" => ignore error quietly */
    var $halt_on_error  = "report";
  
    /* last error message is retained here */
    var $last_error     = "";

    var $ReplaceFunc;
}

?>