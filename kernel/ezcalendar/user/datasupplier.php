<?php
//
// $Id: datasupplier.php 6974 2001-09-05 12:51:13Z jhe $
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
$GlobalSectionID = $ini->read_var( "eZCalendarMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "yearview" :
    {
        $Year = $url_array[3];

        include( "kernel/ezcalendar/user/yearview.php" );
    }
    break;

    case "monthview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "kernel/ezcalendar/user/monthview.php" );
    }
    break;

    case "dayview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        $Day = $url_array[5];

        include( "kernel/ezcalendar/user/dayview.php" );
    }
    break;
    
    case "appointmentedit" :
    {
        switch ( $url_array[3] )
        {
            case "new" :
            {
                $Action = "New";
                $Year = $url_array[4];
                $Month = $url_array[5];
                $Day = $url_array[6];
                $StartTime = $url_array[7];
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $AppointmentID = $url_array[4];
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $AppointmentID = $url_array[4];
            }
            break;

            case "insert" :
            {
                $Action = "Insert";
                $AppointmentID = 0;
            }
            break;

            default :
            {
                $Action = $url_array[3];
            }
        }
        if ( isset( $ChangeView ) )
            $Action = "New";
        include( "kernel/ezcalendar/user/appointmentedit.php" );
    }
    break;

    case "appointmentview" :
    {
        $AppointmentID = $url_array[3];
        include( "kernel/ezcalendar/user/appointmentview.php" );
    }
    break;

    case "trustees":
    {
        switch ( $url_array[3] )
        {
            case "edit":
            {
                $Action = "edit";
                include( "kernel/ezcalendar/user/trustees.php" );
                break;
            }
            default:
            {
                include( "kernel/ezcalendar/user/trustees.php" );
            }
            break;
        }
    }
    break;
}

?>