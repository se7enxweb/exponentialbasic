<?php
//
// $Id: gotolink.php 6274 2001-07-25 11:06:15Z jhe $
//
// Created on: <26-Oct-2000 15:02:25 ce>
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

include_once( "classes/INIFile.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

if ( $Action == "addhit" )
{
    $hit = new eZHit();
    $hit->setLink( $LinkID );
    $hit->setRemoteIP( $REMOTE_ADDR );
    $hit->setTime( time() );
    $hit->store();
}
$link = new eZLink( $LinkID );
$location =& $link->url();

if ( !preg_match( "%^([a-z]+://)%", $location ) )
    $location = "http://" . $location;

Header( "Location: " . $location );
exit();

?>