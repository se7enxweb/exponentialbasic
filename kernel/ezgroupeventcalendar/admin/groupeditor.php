<?php
// 
// $Id: groupeditor.php,v 1.1 2001/04/24 09:39:43 bf Exp $
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

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

// include_once( "ezgroupeventcalendar/classes/ezgroupeditor.php" );
// include_once( "ezgroupeventcalendar/classes/ezgroupnoshow.php" );


if ( isset( $DeleteEditor ) )
{
    $editor = new eZGroupEditor();
	if( $RemoveMemberIdArray )
	{
		foreach ( $RemoveMemberIdArray as $id )
		{
			$editor = new eZGroupEditor( $id );
			$editor->delete();
		}
	}
}
	

if ( isset( $NewEditor ) and isset( $id ))
{
    
    $editor = new eZGroupEditor( $id );

	$group = new eZUserGroup( $GroupID);
    $editor->setGroup( $group );

	$editor->store();
}

if ( isset( $Store ) )
{
    $i=0;
    foreach ( $IDArray as $id )
    {
        $editor = new eZGroupEditor( $id );

		$user = new eZUser( $MemberID[$i]);
        $editor->setUser( $user );

		$group = new eZUserGroup( $GroupID);
		$editor->setGroup( $group );

        $editor->store();

        $i++;
    }
}


$t = new eZTemplate( "kernel/ezgroupeventcalendar/admin/" . $ini->variable( "eZGroupEventCalendarMain", "AdminTemplateDir" ),
                     "kernel/ezgroupeventcalendar/admin/intl", $Language, "groupeditor.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "group_editor_tpl", "groupeditor.tpl" );

$t->setAllStrings();

//Display block
$t->set_block( "group_editor_tpl", "group_list_tpl", "group_list" );
$t->set_block( "group_list_tpl", "group_item_tpl", "group_item" );
$t->set_block( "group_item_tpl", "editor_list_tpl", "editor_list" );
$t->set_block( "editor_list_tpl", "editor_tpl", "editor" );
$t->set_block( "group_item_tpl", "no_editor_tpl", "no_editor" );

//Edit block
$t->set_block( "group_editor_tpl", "group_edit_tpl", "group_edit" );
$t->set_block( "group_edit_tpl", "none_selected_tpl", "none_selected" );
$t->set_block( "group_edit_tpl", "editor_name_list_tpl", "editor_name_list" );
$t->set_block( "editor_name_list_tpl", "editor_name_item_tpl", "editor_name_item" );
$t->set_block( "editor_name_item_tpl", "editor_name_tpl", "editor_name" );



if( isset( $Action ) && $Action == "Display" )
{
	$t->set_var( "group_edit", "" );

	//Groups that shouldn't be displayed
	$noShowGroup = new eZGroupNoShow();

	$groups  = new eZUserGroup();
	$groupsList = $groups->getAll();

	foreach ( $groupsList as $group )
	{
		$editors     = new eZGroupEditor();
		$editorsList = $editors->getByGroup( $group->id() );

		if( $noShowGroup->groupEntry( $group->id() ) == false )
		{
			$t->set_var( "editor", "" );
			$t->set_var( "editor_list", "" );
			$t->set_var( "no_editor", "" );
			if( $editorsList )
			{
				foreach( $editorsList as $editor )
				{
					if( $user = new eZUser( $editor->userID() ) )
					{
						$t->set_var( "group_editor_id", $user->id() );
						$t->set_var( "group_editor_fname", $user->firstName() );
						$t->set_var( "group_editor_lname", $user->lastName() );
						if( $editor->userID() != 0 )
							$t->parse( "editor", "editor_tpl", true );
					}
					else
					{
						$editor->delete( $editor->id() );
					}
				}
			
			
				$t->parse( "editor_list", "editor_list_tpl", true );
			}
			else
			{
				$t->set_var( "editor", "" );
				$t->set_var( "editor_list", "" );
				$t->parse( "no_editor", "no_editor_tpl", true );
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
	}
	$t->parse( "group_list", "group_list_tpl", true );

	$groupsList = $editors->listGroups();
	
	if( $groupsList != false )
	{
		foreach( $groupsList as $groups )
		{
			$group = new eZUserGroup( $groups->groupID() );
			if( !$group->name() )
			{
				print( "we have found a group that doesn't exist anymore<br>\n" );
				$editors->removeDeletedGroup( $groups->groupID() );
			}
		}
	}
}

if( isset( $Action ) && $Action == "Edit" )
{
	$t->set_var( "group_list", "" );
	$t->set_var( "editor_name_list", "" );
	$t->set_var( "none_selected", "" );

	$editors     = new eZGroupEditor();
	$editorsList = $editors->getByGroup( $GroupID );

	$group = new eZUserGroup($GroupID);
	$t->set_var( "group_name", $group->name() );
	$t->set_var( "group_id", $group->id() );

	if( $editorsList )
	{
		foreach( $editorsList as $editor )
		{
			$groupsList = $group->users( $editor->groupID(), "name", false, false );

			$t->set_var( "editor_name", "" );
			foreach ( $groupsList as $user )
			{
				$t->set_var( "user_is_selected", $user->id() == $editor->userID() ? "selected" : "" );
				$t->set_var( "group_editor_user_fname", $user->firstName() );
				$t->set_var( "group_editor_user_lname", $user->lastName() );
				$t->set_var( "group_editor_user_id", $user->id() );
				$t->parse( "editor_name", "editor_name_tpl", true );
			}

			$t->set_var( "editor_id", $editor->id() );

			if ( ( $i % 2 ) == 0 )
			{
				$td_class = bglight;
			}
			else
			{
				$td_class = bgdark;
			}
			
			$t->set_var( "bgcolor", $td_class );
			$t->parse( "editor_name_item", "editor_name_item_tpl", true );

			$i++;
		}
		$t->parse( "editor_name_list", "editor_name_list_tpl", true );
	}
	else
		$t->parse( "none_selected", "none_selected_tpl" );


	$t->parse( "group_edit", "group_edit_tpl", true );
}

$t->pparse( "output", "group_editor_tpl" );

?>