<?php
//
// $Id: datasupplier.php 7663 2001-10-04 14:31:14Z bf $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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


$ini =& eZINI::instance( 'site.ini' );
if ( isset( $GlobalSectionIDOverride ) )
{
    $GlobalSectionID = $GlobalSectionIDOverride;
}
else
{
    $GlobalSectionID = $ini->variable( "eZUserMain", "DefaultSection" );
}

switch ( $url_array[2] )
{
   case "step1" :
    {
        $Action = $url_array[3];
        include( "kernel/ezuser/user/step1.php" );
    }
    break;

   case "step2" :
    {
        $Action = $url_array[3];
        include( "kernel/ezuser/user/step2.php" );
    }
    break;

   case "confirmation" :
   case "account" :
    {
        $Action = $url_array[3];
        include( "kernel/ezuser/user/account_static.php" );
	
    }
    break;

    // ##BREAK

    case "login" :
    {
        $Action = $url_array[3];
        include( "kernel/ezuser/user/login.php" );
    }
    break;

    case "loginmain" :
    {
        $Action = $url_array[3];
        include( "kernel/ezuser/user/loginmain.php" );
    }
    break;

    case "norights":
    {
        include( "kernel/ezuser/user/norights.php" );        
    }
    break;

    case "new":
      if ( $url_array[2] == "new" ){
	$Action = "New";
      }
    case "edit":

      if ( $url_array[3] == "new" || $url_array[3] == "edit")   // && $url_array[2] == "edit")
        {
          $Action = "Edit";
          $url_array[3] = "edit";
        }
      if ( $url_array[2] == "edit" ); // new" || $url_array[2] == "edit")   // && $url_array[2] == "edit")
        {
	  $Action = "Edit";
	  $url_array[3] = "edit";
        }
      //      die( " $url_array[2] - $url_array[3] ");
    case "user" :
    {   
        if ( $url_array[3] == "new" || $url_array[2] == "new")   // && $url_array[2] == "new")
        {
            $Action = "New";
        }
        if ( $url_array[3] == "edit" ) // || $url_array[2] == "edit" )
        {
            if ( $url_array[5] == "MissingAddress" )
                $MissingAddress = true;
            else
                $MissingAddress = false;
            if ( $url_array[5] == "MissingCountry" )
                $MissingCountry = true;
            else
                $MissingCountry = false;

	    /*
	    if ( $url_array[4] != '' ){
               $UserID = $url_array[4];
               $Action = "Edit";
	    } else {
	      $UserID = 0;
	      $Action = "Edit";
	    }
	    */
        }
        if ( $url_array[3] == "update" )
        {
            $UserID = $url_array[4];
            $Action = "Update";
        }

        if ( $url_array[3] == "" )
            $Action = "New";

        if ( $url_array[3] == "insert" )
            $Action = "Insert";

	//	die( " $Action : $url_array[2] - $url_array[3] ");

	$OverrideUserWithAddress = "";

	if( $ini->variable( "eZUserMain", "OverrideUserWithAddress" ) != "disabled" ){ 
	  $OverrideUserWithAddress = $ini->variable( "eZUserMain", "OverrideUserWithAddress" );
	}

        if ( empty( $OverrideUserWithAddress ) )
        {
            include( "kernel/ezuser/user/useredit.php" );
        }
        else
        {
            if ( is_file( $OverrideUserWithAddress ) )
            {
                include( $OverrideUserWithAddress );
            }
            else
            {   
  	        // patric's : header("Location: /index.php/user/step1/");
                include( "kernel/ezuser/user/useredit.php" );
            }
        }
    }
    break;
    case "withaddress" : 
    case "userwithaddress" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
            header("Location: /user/new/");
        }
        if ( $url_array[3] == "edit" )
        {
            if ( $url_array[5] == "MissingAddress" )
                $MissingAddress = true;
            else
                $MissingAddress = false;
            if ( $url_array[5] == "MissingCountry" )
                $MissingCountry = true;
            else
                $MissingCountry = false;

            $UserID = $url_array[4];
            $Action = "Edit";
			//$RedirectURL = "/BypassRedirect/Edit/";
			//$RedirectURL = $RedirectURL;
			//$RedirectURL = $session->variable( "RedirectURL" );
			if ( !isset( $RedirectURL ) or !$RedirectURL )
		        $RedirectURL = $session->variable( "RedirectURL" );
        }
        if ( $url_array[3] == "update" )
        {
            $UserID = $url_array[4];
            $Action = "Update";
        }

        if ( $url_array[3] == "insert" )
            $Action = "Insert";

        $OverrideUserWithAddress = $ini->variable( "eZUserMain", "OverrideUserWithAddress" );

        if ( empty( $OverrideUserWithAddress ) )
        {
            include( "kernel/ezuser/user/userwithaddress.php" );
        }
        else
        {
            if ( is_file( $OverrideUserWithAddress ) )
            {
                include( $OverrideUserWithAddress );
            }
            else
            {
                include( "kernel/ezuser/user/userwithaddress.php" );
            }
        }

    }
    break;

    case "forgot" :
    {
        $Action = $url_array[3];
        $Hash = $url_array[4];
        include( "kernel/ezuser/user/forgot.php" );
    }
    break;

    case "address" :
    {
        if ( $url_array[3] == "new" )
            $Action = "New";
        if ( $url_array[3] == "insert" )
            $Action = "Insert";

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "Update";
        }
        
        include( "kernel/ezuser/user/addressedit.php" );
    }
    break;

    case "logout" :
    {
        $Action = $url_array[2];
        include( "kernel/ezuser/user/login.php" );
    }
    break;

    case "unsuccessfull":
    {
        $unsuccessfull = true;
        include( "kernel/ezuser/user/forgotmessage.php" );
    }
    break;

    case "successfull":
    {
        $successfull = true;
        include( "kernel/ezuser/user/forgotmessage.php" );
    }
    break;

    case "missingemail":
    {
        $successfull = true;
        include( "kernel/ezuser/user/missingemailmessage.php" );
    }
    break;

    case "generated":
    {
        $generated = true;
        include( "kernel/ezuser/user/forgotmessage.php" );
    }
    break;

    case "passwordchange" :
    {
        $ini =& eZINI::instance( 'site.ini' );
        $DemoSite = $ini->variable( "site", "DemoSite" );

        if ( $DemoSite == "enabled" ) {
            print("<div align='center'>This is a demosite only. You are not allowed to change the admin password!</div>\n");
        }else{
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/passwordchange.php" );
        }

    }
    break;

    default :
    {
        include( "kernel/ezuser/user/login.php" );
    }
    break;


}

?>