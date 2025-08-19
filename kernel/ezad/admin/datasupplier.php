<?php
//
// $Id: datasupplier.php 6203 2001-07-19 11:56:33Z jakobn $
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


switch ( $url_array[2] )
{
    case "archive" :
    {
        $CategoryID = $url_array[3];

        include( "kernel/ezad/admin/adlist.php" );
    }
    break;

    case "statistics" :
    {
        $AdID = $url_array[3];
        
        include( "kernel/ezad/admin/adstatistics.php" );        
    }
    break;

    case "ad" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = "Insert";
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
        }

        if ( $url_array[3] == "update" )
        {
            $Action = "Update";
        }

        if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
        }
        
        if( empty( $AdID ) )
        {
            $AdID = $url_array[4];
        }
        include( "kernel/ezad/admin/adedit.php" );
    }
    break;
    
    case "category" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = "Insert";
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
        }

        if ( $url_array[3] == "update" )
        {
            $Action = "Update";
            $CategoryID = $url_array[4];
        }

        if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
        }
        if( empty( $CategoryID ) )
        {
            $CategoryID = $url_array[4];
        }
        include( "kernel/ezad/admin/categoryedit.php" );
    }
    break;

}

?>