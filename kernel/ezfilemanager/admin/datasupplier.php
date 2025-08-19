<?php
//
// $Id: datasupplier.php 6214 2001-07-19 13:01:02Z jakobn $
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
    case "browse":
    {
        $FolderID = $url_array[3];
        include( "kernel/ezfilemanager/admin/browse.php" );
    }
    break;

    case "unassigned":
    {
        include( "kernel/ezfilemanager/admin/unassigned.php" );
    }
    break;

    default:
        include( "kernel/ezfilemanager/user/datasupplier.php" );
}

?>