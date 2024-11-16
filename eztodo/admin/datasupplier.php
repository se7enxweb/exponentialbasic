<?php
// 
// $Id: datasupplier.php 6968 2001-09-05 11:53:39Z jhe $
//
// Created on: <18-Oct-2000 15:04:39 bf>
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

//print $REQUEST_URI;
$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "categorytypelist":
    {
        include( "eztodo/admin/categorytypelist.php" );        
    }
    break;
    
    case "categorytypeedit" :
    {
        switch ( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
            
            case "insert":
            {
                $CategoryID = $url_array[4];
                $Action = "insert";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
            
            case "edit":
            {
                $CategoryID = $url_array[4];
                $Action = "edit";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
            
            case "update":
            {
                $CategoryID = $url_array[4];
                $Action = "update";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
            
            case "delete":
            {
                $CategoryID = $url_array[4];
                $Action = "delete";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
        }
    }
    break;
        
    case "prioritytypelist":
    {
        include( "eztodo/admin/prioritytypelist.php" );
    }
    break;

    case "prioritytypeedit" :
    {
        switch ( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;

            case "insert":
            {
                $PriorityID = $url_array[4];
                $Action = "insert";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;

            case "edit":
            {
                $PriorityID = $url_array[4];
                $Action = "edit";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;

            case "update":
            {
                $PriorityID = $url_array[4];
                $Action = "update";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;

            case "delete":
            {
                $PriorityID = $url_array[4];
                $Action = "delete";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;
        }
    }
    break;

    case "statustypelist":
    {
        include( "eztodo/admin/statustypelist.php" );        
    }
    break;
    
    case "statustypeedit" :
    {
        switch ( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
            
            case "insert":
            {
                $CategoryID = $url_array[4];
                $Action = "insert";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
            
            case "edit":
            {
                $CategoryID = $url_array[4];
                $Action = "edit";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
            
            case "update":
            {
                $CategoryID = $url_array[4];
                $Action = "update";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
            
            case "delete":
            {
                $CategoryID = $url_array[4];
                $Action = "delete";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
        }
    }
    break;

    default:
    {
        print( "<h1>Sorry, Your todo page could not be found. </h1>" );
    }
    break;
}

?>
