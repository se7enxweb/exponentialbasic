<?php
// 
// $Id: admincheck.php 6484 2001-08-17 13:36:01Z jhe $
//
// Created on: <26-Oct-2000 15:11:17 ce>
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

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );

//  $user = new eZUser();
//  $user = $user->currentUser();

$user =& eZUser::currentUser();

if ( $user == false )
{
    eZHTTPTool::header( "Location: /user/login" );
    exit();
}

if ( !eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ) )
{
    eZUser::logout( $user );
    eZHTTPTool::header( "Location: /user/login" );
    exit();
}


?>

