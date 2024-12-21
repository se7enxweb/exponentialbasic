<?php
//
// $Id: datasupplier.php 8882 2002-01-04 14:48:21Z kaid $
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

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZLinkMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "gotolink" :
    {
        $Action = $url_array[3];
        $LinkID = $url_array[4];
        include( "kernel/ezlink/user/gotolink.php" );
    }
    break;

    case "latest":
    {
        include( "kernel/ezlink/user/latest.php" );
    }
    break;
    
    case "search" :
    {
        if ( isset( $url_array[3] ) and $url_array[3] == "parent" )
        {
            $QueryString = urldecode( $url_array[4] );
            $Offset = $url_array[5];
        }
        include( "kernel/ezlink/user/search.php" );
    }
    break;

    case "success" :
        include( "kernel/ezlink/user/success.php" );
        break;

    case "category" :
    case "group" :
    {
        if ( isset( $url_array[4] ) and $url_array[4] == "parent" )
            $Offset = $url_array[5];
        $LinkCategoryID = $url_array[3];
        include( "kernel/ezlink/user/linkcategorylist.php" );
    }
    break;

    case "linklist" :
    {
        $LinkCategoryID = $url_array[3];
        include( "kernel/ezlink/user/onepagelinklist.php" );
    }
    break;

    case "suggestlink" :
    {
        if ( isset( $url_array[3] ) and $url_array[3] == "insert" )
        {
            $Action = "insert";
            include( "kernel/ezlink/user/suggestlink.php" );
        }
        else
        {
            $LinkCategoryID = $url_array[3];
            include( "kernel/ezlink/user/suggestlink.php" );
        }
    }
    break;

    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
