<?php
//
// $Id: prioritylist.php 8504 2001-11-19 09:46:46Z jhe $
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

/*
  Shows a list of priorities, and lets the user edit and add new priorities.
*/
// include_once( "classes/INIFile.php" );
$ini = INIFile::globalINI();
$Language = $ini->read_var( "eZBugMain", "Language" );
$LanguageIni = new INIFIle( "kernel/ezbug/admin/intl/" . $Language . "/prioritylist.php.ini", false );

// include_once( "classes/eztemplate.php" );

// include_once( "ezbug/classes/ezbugpriority.php" );

$t = new eZTemplate( "kernel/ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "kernel/ezbug/admin/intl", $Language, "prioritylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "priority_page" =>  "prioritylist.tpl"
    ) );

$t->set_block( "priority_page", "priority_item_tpl", "priority_item" );

$t->set_var( "site_style", $SiteDesign );


if( isset( $DeletePriorities ) )
{
    if( count( $PriorityArrayID ) > 0 )
    {
        foreach( $PriorityArrayID as $deleteItemID )
        {
            $item = new eZBugPriority( $PriorityID[ $deleteItemID ] );
            $item->delete();
        }
    }
}


if( isset( $Ok ) || isset( $AddPriority ) )
{
    $i = 0;
    if( count( $PriorityID ) > 0 )
    {
        foreach( $PriorityID as $itemID )
        {
            $priority = new eZBugPriority( $itemID );
            $priority->setName( $PriorityName[$i] );
            $priority->store();
            $i++;
        }
    }
    
//    $priority = new eZBugPriority( $PriorityID );
//    $priority->setName( $Name );
//    $priority->store();
}

if( isset( $AddPriority ) )
{
    $newItem = new eZBugPriority();
    $newName = $LanguageIni->read_var( "strings", "new_priority" );
    $newItem->setName($newName);
    $newItem->store();
}


$priority = new eZBugPriority();
$priorityList = $priority->getAll();

$i=0;
foreach( $priorityList as $priorityItem )
{
    if ( ( $i %2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
        
    $t->set_var( "priority_id", $priorityItem->id() );
    $t->set_var( "priority_name", $priorityItem->name() );
    $t->set_var( "index_nr", $i );
    
    $t->parse( "priority_item", "priority_item_tpl", true );
    $i++;
} 

$t->pparse( "output", "priority_page" );
?>