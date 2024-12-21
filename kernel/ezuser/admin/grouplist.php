<?php
// 
// $Id: grouplist.php 6235 2001-07-20 11:45:40Z jakobn $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

require( "kernel/ezuser/admin/admincheck.php" );

$t = new eZTemplate( "kernel/ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "kernel/ezuser/admin/" . "/intl", $Language, "grouplist.php" );
$t->setAllStrings();

$t->set_file( array(
    "group_list_page" => "grouplist.tpl"
    ) );

$t->set_block( "group_list_page", "group_item_tpl", "group_item" );

$t->set_var( "site_style", $SiteDesign );

$group = new eZUserGroup();

$groupList = $group->getAll();

$i=0;
foreach( $groupList as $groupItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    

    $t->set_var( "group_id", $groupItem->id() );
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_description", $groupItem->description() );

    $t->parse( "group_item", "group_item_tpl", true );
    $i++;
}

$t->pparse( "output", "group_list_page" );

?>