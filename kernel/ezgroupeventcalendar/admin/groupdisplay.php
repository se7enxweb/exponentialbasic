<?
// 
// $Id: groupdisplay.php,v 1.1 2001/04/24 09:39:43 bf Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

// include_once( "ezuser/classes/ezusergroup.php" );

// include_once( "ezgroupeventcalendar/classes/ezgroupnoshow.php" );

if ( isset( $NoDisplayIDArray ) && isset( $Store ) )
{
	$nodisplay = new eZGroupNoShow();

	// first clear the table
	$nodisplay->dumpTable();

	//second reload the table
    foreach ( $NoDisplayIDArray as $id )
    {
		$group = new eZUserGroup( $id );
        $nodisplay = new eZGroupNoShow();
        $nodisplay->setGroup( $group );
        $nodisplay->store();
    }
}
elseif( !isset( $NoDisplayIDArray ) && isset( $Store ) )
{
	$nodisplay = new eZGroupNoShow();

	// first clear the table
	$nodisplay->dumpTable();
}

$t = new eZTemplate( "kernel/ezgroupeventcalendar/admin/" . $ini->variable( "eZGroupEventCalendarMain", "AdminTemplateDir" ),
                     "kernel/ezgroupeventcalendar/admin/intl", $Language, "groupdisplay.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "group_display_tpl", "groupdisplay.tpl" );

$t->setAllStrings();

$t->set_block( "group_display_tpl", "group_list_tpl", "group_list" );
$t->set_block( "group_list_tpl", "group_item_tpl", "group_item" );

$groups         = new eZUserGroup();
$groups_no_show = new eZGroupNoShow();

$groups_list         = $groups->getAll();
$groups_no_show_list = $groups_no_show->getAll(); 


foreach ( $groups_list as $group )
{
	/*
	foreach ( $groups_no_show_list as $group_no_show )
	{
		$t->set_var( "group_is_checked", $group->id() == $group_no_show->groupID() ? "checked" : "" );
	}
	*/

	if( $groups_no_show->groupEntry( $group->id() ) == true )
	{
		$t->set_var( "group_is_checked", "checked" );
	}
	else
	{
		$t->set_var( "group_is_checked", "" );
	}

	if ( ( $i % 2 ) == 0 )
	{
		$td_class = "bglight";
	}
	else
	{
		$td_class = "bgdark";
	}

	$t->set_var( "bgcolor", $td_class ); 
	$t->set_var( "group_id", $group->id() );
	$t->set_var( "group_name", $group->name() );
	$t->parse( "group_item", "group_item_tpl", true );

	$i++;
}
$t->parse( "group_list", "group_list_tpl", true );


$t->pparse( "output", "group_display_tpl" );
?>
