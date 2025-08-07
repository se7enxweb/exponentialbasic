<?php
//
// $Id: datasupplier.php 6211 2001-07-19 12:45:08Z jakobn $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

////////////////////////////////////////////
// Set Redirect (Keep Site Errors Clean by Redirecting Away from It After 7 Seconds
$MetaRedirectLocation = "http://". $ini->variable( "site", "UserSiteURL" ) ."/";
$MetaRedirectTimer = "5";


switch( $url_array[2] )
{
    case "403" :
    {
        include( "kernel/ezerror/admin/403.php" );
    }
    break;
    
    case "404" :
    {
        include( "kernel/ezerror/admin/404.php" );
    }
    break;

    case "error" :
    {
        include( "kernel/ezerror/admin/error.php" );
    }
    break;
}

?>