<?php
// 
// $Id: ezlog.php 6304 2001-07-29 23:31:17Z kaid $
//
// Definition of eZLog class
//
// Created on: <15-Oct-2000 13:28:44 bf>
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
//! The eZLog class handles streaming of information to a log file.
/*!
  The eZLog class enables simple logging of information to a log file.
  It enables you to have your error/warning messages in one place, so you
  can analyze later.

  The log messages are divided into there types: notice, warning and error.

  For simple messages you should use the static functions, if you're going
  to write alot of messages sequentially you should use the create object methods.
  Then you don't have the overhead of opening and closing the file each time.

  Example
  \code
  // include the class
  include_once( "kernel/classes/ezlog.php" );

  // Create a new log object.
  // Since we do not provide an argument the LogFile is read
  // from site.ini
  $log = new eZLog();

  // write out some notice, warning and error information.
  $log->notice( "This is a notice." );
  $log->warning( "This is a warning." );
  $log->error( "This is an error." );

  // Close the log. Since php lacs destructors we must clean up manually.
  $log->close();

  // A convenient function for simple use
  eZLog::write( "A notice" );
  eZLog::write( "A warning", "warning" );
  eZLog::write( "An error", "error" );

  // And three direct calls, you decide which is simpler to use
  eZLog::writeNotice( "A notice" );
  eZLog::writeWarning( "A warning" );
  eZLog::writeError( "An error" );

  
  \endcode
  
*/

class eZLog
{
    /*!
      Creates a new eZLog object. Opens the log file in append mode. If
      no valid filename is given as argument the log file is read from
      the site.ini file.
    */
    function __construct( $fileName="" )
    {
        if ( file_exists( $fileName ) )
        {
            $this->LogFile = eZPBFile::fopen( $fileName, "a" );
        }
        else
        {
            //include_once( "kernel/classes/INIFile.php" );
            $ini =& eZINI::instance( 'site.ini' );

            $fileName = $ini->variable( "site", "LogFileName" );
	    $logDir = $ini->variable( "site", "LogDir" );

	    // build timestamp for today's date 	    
	    //include_once( "kernel/classes/ezdate.php" );

	    $today = new eZDate();
	    $year = eZDateTime::addZero( $today->year() );
    	    $month = eZDateTime::addZero( $today->month() );
            $day = eZDateTime::addZero( $today->day() );

	    // append timestamp to log file name
	    $timestamp = $year ."_". $month ."_". $day;

            $fileName = $logDir . $fileName ."_". $timestamp .".log";

	    if ( file_exists( $fileName ) )
            {	
	       $this->LogFile = eZPBFile::fopen( $fileName, "a" );
            } 
	    else
            {
               $this->LogFile = eZPBFile::fopen( $fileName, "a+" );
            }
    
            if ( !$this->LogFile )
            {
                print( "Log file ( $fileName )  could not be opened." );
            }
        }
    }

    /*!
      Closes the log file.
    */
    function close()
    {
        fclose( $this->LogFile );
    }

    /*!
      Writes out a notice to the log file.
    */
    function notice( $notice )
    {
        $time = date("%b %d %Y %H:%M:%S", strtotime( "now" ) );        
        $notice = "[ " . $time . " ] [notice] " . $notice . "\n"; 
        fwrite( $this->LogFile, $notice );
    }

    /*!
      Writes out a warning to the log file.
    */
    function warning( $warning )
    {
        $time = date("%b %d %Y %H:%M:%S", strtotime( "now" ) );        
        $warning = "[ " . $time . " ] [warning] " . $warning . "\n"; 
        fwrite( $this->LogFile, $warning );
    }


    /*!
      Writes out an error to the log file.
    */
    function error( $error )
    {
        $time = date("%b %d %Y %H:%M:%S", strtotime( "now" ) );        
        $error = "[ " . $time . " ] [error] " . $error . "\n"; 
        fwrite( $this->LogFile, $error );
    }

    /*!
      \static
      A simple static function for writing notices to the log file.
     */
    static public function writeNotice( $notice )
    {
        $log = new eZLog();
        $log->notice( $notice );
        $log->close();
    }

    /*!
      \static
      A simple static function for writing warnings to the log file.
     */
    static public function writeWarning( $warning )
    {
        $log = new eZLog();
        $log->warning( $warning );
        $log->close();
    }

    /*!
      \static
      A simple static function for writing errors to the log file.
     */
    function writeError( $error )
    {
        $log = new eZLog();
        $log->error( $error );
        $log->close();
    }

    /*!
      \static
      A simple function for writing notice, warning of error messages to the log
      file.

      This function defaults to notice if no type argument is given.
    */
    function write( $message, $type="" )
    {
        switch ( $type )
        {
            case "warning" :
            {
                $log = new eZLog();
                $log->warning( $message );
                $log->close();
            }
            break;

            case "error" :
            {
                $log = new eZLog();
                $log->error( $message );
                $log->close();
            }
            break;

            case "notice" :
            {
                $log = new eZLog();
                $log->notice( $message );
                $log->close();
            }
            break;

            default :
            {
                $log = new eZLog();
                $log->notice( $message );
                $log->close();
            }
            break;            
        }
    }
    
    var $LogFile;
}

?>