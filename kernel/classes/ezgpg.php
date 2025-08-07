<?php
//
// $Id: ezgpg.php,v 1.3 2001/04/09 14:37:39 chrism Exp $
//
// Definition of eZGPG class
//
// Chris Mason <chris@net.ai>
// Created on: <09-Apr-2001 16:36:08 bf>
// Updated 6/24/2001 to improve command and remove
// the need to write a file
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
//! GPG Encrytion class
/*!

*/


class eZGPG
{
    /*!
      \static
      Encrypt function
    */
    function __construct( $plaintxt, $keyname, $wwwuser)
    {
      
      putenv($home);
      $boundary = md5( uniqid( time() ) );

      $this->keyname=$keyname;
      if ( sizeof( $this->keyname ) == 0 )
            echo "WARNING: No Keys Specified";

      $this->pcmd = "echo '$plaintxt' | ";
      $this->pcmd .= $this->pathtogpg.$this->encryptcommand;
      $this->pcmd.= " -a -q --always-trust --no-tty -e --homedir '" . $this->gnuhome .  "' -u $wwwuser -r $keyname";
      $this->pcmd.= " -o/var/www/" . $boundary;

      system( $this->pcmd );

      print( $this->pcmd );
      exit();
//      $pp = popen( $this->pcmd, "w" );
      //     fwrite( $pp, $this->body );
      //  pclose( $pp );


      $fp = eZPBFile::fopen( "/var/www/" . $boundary, r );
      $this->body = fread( $fp, eZPBFile::filesize( "/var/www/" . $boundary ) );
      fclose( $fp );

      eZPBFile::unlink( "/var/www/" . $boundary );
    }

    /*!
      \static
      Encrypt function
    */
    function __constructStock( $plaintxt, $keyname, $wwwuser)
    {
      $this->keyname=$keyname;
      if ( sizeof( $this->keyname ) == 0 )
            $this->body = "WARNING: No Keys Specified";

      $this->messagetext = escapeshellarg( $plaintxt );

      $this->pcmd = "echo '" . $this->messagetext . "' | ";
      $this->pcmd .= $this->pathtogpg.$this->encryptcommand;
      $this->pcmd.= "  -u ". $wwwuser . " --homedir '" . $this->home ."' -r '". $this->keyname . "' ";

      //clear return array and execute encrypt command
      unset( $ret );
      exec($this->pcmd, $ret);

      print( "<pre>" );
      print_r( $ret);
      print( "</pre><br />" );



      //loop return array for encrypted text
      foreach( $ret as $key=>$value )
      {
         $this->body .= $value;
         $this->body .="\n";
      }

   }

   function getbody()
   {
      return $this->body;
   }

   var $messagetext;
   var $body;
   var $keyname = array();
   var $ret = array();
   var $pathtogpg = "/usr/bin/";
   var $pp;
   var $fp;
   var $pcmd;
   var $encryptcommand = "gpg --encrypt --batch --no-secmem-warning -a -q --no-tty";
   var $signcommand = "gpg --sign --batch";
   //var $home = "/var/www/.gnupg";
   var $home="HOME=/var/www/";
   var $gnuhome="/var/www/.gnupg";
}

?>