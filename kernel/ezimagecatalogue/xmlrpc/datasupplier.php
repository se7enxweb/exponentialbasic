<?php
//
// $Id: datasupplier.php 9480 2002-04-25 13:30:52Z jb $
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

define( "EZIMAGECATALOGUE_NONEXISTING_IMAGE", 1 );
define( "EZIMAGECATALOGUE_CONVERT_ERROR", 2 );
define( "EZIMAGECATALOGUE_SIZE_MISSING", 3 );
define( "EZIMAGECATALOGUE_BAD_IMAGE", 4 );
define( "EZIMAGECATALOGUE_NONEXISTING_PARENT", 5 );

switch ( $RequestType )
{
    case "category" :
    {
        switch( $Command )
        {
            case "list":
            case "tree":
            case "search":
            {
                include( "ezimagecatalogue/xmlrpc/categorylist.php" );
                break;
            }
            case "data":
            case "storedata":
            case "delete":
            case "permission":
            case "info":
            {
                include( "ezimagecatalogue/xmlrpc/category.php" );
                break;
            }
            default:
                $Error = true;
        }
    } break;
    case "unassigned" :
    {
        switch( $Command )
        {
            case "list":
            {
                include( "ezimagecatalogue/xmlrpc/unassignedlist.php" );
                break;
            }
            default:
                $Error = true;
        }
    } break;

    case "image" :
    {
        switch( $Command )
        {
            case "data":
            case "storedata":
            case "delete":
            case "search":
            case "permission":
            case "info":
            {
                include( "ezimagecatalogue/xmlrpc/image.php" );
                break;
            }
            default:
                $Error = true;
        }
    }
    break;
        
    default :
    {
        switch( $Command )
        {
            case "search":
            {
                include( "ezimagecatalogue/xmlrpc/search.php" );
                break;
            }
            default:
                $Error = true;
        }
    } break;
}

?>
