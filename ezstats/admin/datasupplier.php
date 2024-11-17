<?php
//
// $Id: datasupplier.php 6484 2001-08-17 13:36:01Z jhe $
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

include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );
$user =& eZUser::currentUser();
if ( eZPermission::checkPermission( $user, "eZStats", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

switch ( $url_array[2] )
{
    case "overview" :
    {
        include( "ezstats/admin/overview.php" );
    }
    break;

    case "pageviewlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];

        include( "ezstats/admin/pageviewlist.php" );
    }
    break;

    case "visitorlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];

        include( "ezstats/admin/visitorlist.php" );
    }
    break;

    case "refererlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];
        if ( !isset( $ExcludeDomain ) )
            $ExcludeDomain = $url_array[6];

        include( "ezstats/admin/refererlist.php" );
    }
    break;

    case "browserlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];

        include( "ezstats/admin/browserlist.php" );
    }
    break;

    case "requestpagelist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];

        include( "ezstats/admin/requestpagelist.php" );
    }
    break;
    
    case "yearreport" :
    {
        $Year = $url_array[3];

        include( "ezstats/admin/yearreport.php" );
    }
    break;

    case "monthreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "ezstats/admin/monthreport.php" );
    }
    break;

    case "dayreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        $Day = $url_array[5];

        include( "ezstats/admin/dayreport.php" );
    }
    break;

    case "productreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        
        include( "ezstats/admin/productreport.php" );
    }
    break;
    
    case "entryexitreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        
        include( "ezstats/admin/entryexitpages.php" );
    }
    break;
    
}

?>
