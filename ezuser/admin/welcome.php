<?php
// 
// $Id: welcome.php 8869 2002-01-04 09:15:05Z bf $
//
// Created on: <13-Nov-2000 10:57:15 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezsession/classes/ezsession.php" );


// Template
$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/intl", $Language, "welcome.php" );
$t->setAllStrings();



$t->set_file( array(
    "welcome_tpl" => "welcome.tpl"
    ) );

$t->set_block( "welcome_tpl", "error_tpl", "error" );

$t->set_block( "error_tpl", "convert_error_tpl", "convert_error" );

$t->set_var( "error", "" );
$t->set_var( "convert_error", "" );

$user =& eZUser::currentUser();

if ( $user )
{
    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
}

if ( $ini->read_var( "site", "CheckDependence" ) == "enabled" )
{
	$error = false;
    $image_prog = "convert";    
    if ( $ini->has_var( "classes", "ImageConversionProgram" ) )
        $image_prog = $ini->read_var( "classes", "ImageConversionProgram" );
    
    $check = system( "$image_prog > /dev/null", $ret );

    if ( $ret == "127" )
    {
        $t->set_var( "convert_location", "http://www.imagemagick.org/www/archives.html" );
        $t->parse( "convert_error", "convert_error_tpl" );
        $error = true;
    }


    if ( $error )
    {
        $t->parse( "error", "error_tpl" );
    }
}

$t->pparse( "output", "welcome_tpl" );

