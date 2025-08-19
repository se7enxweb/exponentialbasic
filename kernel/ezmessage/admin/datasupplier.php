<?php
//
// $Id: datasupplier.php 6222 2001-07-20 11:19:37Z jakobn $
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


switch( $url_array[2] )
{
    case "view" :
    {
        $MessageID = $url_array[3];
        include( "kernel/ezmessage/admin/messageview.php" );
    }
    break;    

    case "list" :
    {
        include( "kernel/ezmessage/admin/messagelist.php" );
    }
    break;    

    case "edit" :
    {
       	include( "kernel/ezmessage/admin/messageedit.php" );
    }
    break;
    
    case "popup" :
    {
       	include( "kernel/ezmessage/admin/reciverpopup.php" );
    }
    break; 
    
    case "send" :
    {
       	include( "kernel/ezmessage/admin/messagesend.php" );
    }
    break;
    
    default :
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;    
}

?>