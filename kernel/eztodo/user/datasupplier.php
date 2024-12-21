<?php
//
// $Id: datasupplier.php 6232 2001-07-20 11:36:07Z jakobn $
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
$GlobalSectionID = $ini->read_var( "eZTodoMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "" :
        include( "kernel/eztodo/user/todolist.php" );        
        break;

    case "todolist" :
        include( "kernel/eztodo/user/todolist.php" );        
        break;

    case "todoedit" :
    {
        switch ( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "kernel/eztodo/user/todoedit.php" );
            }
            break;

            case "insert":
            {
                $Action = "insert";
                include( "kernel/eztodo/user/todoedit.php" );
            }
            break;
            
            case "edit":
            {
                $Action = "edit";
                $TodoID = $url_array[4];
                include( "kernel/eztodo/user/todoedit.php" );
            }
            break;

            case "update":
            {
                $Action = "update";
                $TodoID = $url_array[4];
                include( "kernel/eztodo/user/todoedit.php" );
            }
            break;
            case "delete":
            {
                $Action = "delete";
                $TodoID = $url_array[4];
                include( "kernel/eztodo/user/todoedit.php" );
            }
            break;

        }
    }
    break;

    case "todoview":
    {
        $TodoID = $url_array[3];
        include( "kernel/eztodo/user/todoview.php" );
    }
    break;
            
    
    case "todoinfo" :
        include( "kernel/eztodo/user/todoinfo.php" );
        break;

    default:
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>