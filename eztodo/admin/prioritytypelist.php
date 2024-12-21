<?php
// 
// $Id: prioritytypelist.php 6232 2001-07-20 11:36:07Z jakobn $
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

// include_once( "classes/eztemplate.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );

// include_once( "eztodo/classes/eztodo.php" );
// include_once( "eztodo/classes/ezpriority.php" );

// include_once( "classes/INIFile.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZTodoMain", "Language" );

 
$t = new eZTemplate( "eztodo/admin/" . $ini->read_var( "eZTodoMain", "AdminTemplateDir" ),
                     "eztodo/admin/intl", $Language, "prioritytypelist.php" );
$t->setAllStrings();

$t->set_file( array(
    "priority_type_page" =>  "prioritytypelist.tpl"
    ) );

$t->set_block( "priority_type_page", "priority_item_tpl", "priority_item" );

$t->set_var( "site_style", $SiteDesign );

$priority_type = new eZPriority();
$priority_type_array = $priority_type->getAll();

$i=0;
foreach( $priority_type_array as $priorityItem )
{
    if ( ( $i %2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
        
    $t->set_var( "priority_type_id", $priorityItem->id() );
    $t->set_var( "priority_type_name", $priorityItem->name() );

    $i++;
    $t->parse( "priority_item", "priority_item_tpl", true );
}
if ( count ( $priority_type_array ) == 0 )
{
    $t->set_var( "priority_item", "" );
}

$t->pparse( "output", "priority_type_page" );
?>