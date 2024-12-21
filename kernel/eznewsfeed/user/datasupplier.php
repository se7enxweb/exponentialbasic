<?php
//
// $Id: datasupplier.php 6304 2001-07-29 23:31:17Z kaid $
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
$GlobalSectionID = $ini->read_var( "site", "DefaultSection" );

$PageCaching = $ini->read_var( "eZNewsfeedMain", "PageCaching" );

switch ( $url_array[2] )
{
    case "latest":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        if ( $PageCaching == "enabled" )
        {
            $CategoryID = $url_array[3];
            $cachedFile = "eznewsfeed/cache/latestnews," . $CategoryID . ".cache";

            if ( eZFile::file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "kernel/eznewsfeed/user/newslist.php" );
            }            
        }
        else
        {
            $GenerateStaticPage = "false";
            include( "kernel/eznewsfeed/user/newslist.php" );
        }
    }
    break;

    case "allcategories" :
    {
        $GenerateStaticPage = "false";
        include( "kernel/eznewsfeed/user/allcategories.php" );
    }
    break;

    case "search":
    {
        include( "kernel/eznewsfeed/user/search.php" );
    }
    break;
}

?>
