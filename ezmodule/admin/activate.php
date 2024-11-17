<?php
// 
// $Id: activate.php 6223 2001-07-20 11:20:31Z jakobn $
//
// Created on: <11-Apr-2001 15:07:58 amos>
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

include_once( "classes/INIFile.php" );
include_once( "ezsession/classes/ezpreferences.php" );
include_once( "ezmodule/classes/ezmodulehandler.php" );

$ini =& INIFile::globalINI();
$preferences = new eZPreferences();
$single_module = eZModuleHandler::useSingleModule();

if ( $single_module )
{
    $modules = array( $ModuleName );
    eZModuleHandler::setOpen( $ModuleName );
}
else
{
    switch( $ModuleName )
    {
        case "all":
        {
            $modules =& eZModuleHandler::all();
            break;
        }
        case "none":
        {
            $modules = array();
            break;
        }
        default:
        {
            $modules =& eZModuleHandler::active();
            if ( $Activate )
            {
                $modules =& eZModuleHandler::append( $modules, $ModuleName );
            }
            else
            {
                $modules =& eZModuleHandler::remove( $modules, $ModuleName );
            }
        }
    }
}
eZModuleHandler::setActive( $modules );

$uri =& $_REQUEST["RefURL"];

// set the first menu item active
unset( $menuItems );
include( strtolower($ModuleName) ."/admin/menubox.php" );
$uri = $menuItems[0][0];
unset( $menuItems );

eZHTTPTool::header( "Location: $uri" );
exit();


