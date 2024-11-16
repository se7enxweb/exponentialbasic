<?php
//
// $Id: todoview.php 8503 2001-11-19 09:37:02Z jhe $
//
// Definition of todo list.
//
// Created on: <04-Sep-2000 16:53:15 ce>
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

$ini = INIFile::globalINI();
$Language = $ini->read_var( "eZTodoMain", "Language" );

$lanugageIni = new INIFile( "eztodo/user/intl/" . $Language . "/todoview.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );
include_once( "eztodo/classes/ezstatus.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdatetime.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );

$locale = new eZLocale( $Language );

// Setup template.
$t = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "eztodo/user/intl", $Language, "todoview.php" );
$t->setAllStrings();

$t->set_file( "todo_edit_page", "todoview.tpl" );

$t->set_block( "todo_edit_page", "category_select_tpl", "category_select" );
$t->set_block( "todo_edit_page", "priority_select_tpl", "priority_select" );
$t->set_block( "todo_edit_page", "status_select_tpl", "status_select" );
$t->set_block( "todo_edit_page", "todo_is_public_tpl", "todo_is_public" );
$t->set_block( "todo_edit_page", "todo_is_private_tpl", "todo_is_private" );
//$t->set_block( "todo_edit_page", "mark_as_done", "mark_done" );
$t->set_block( "todo_edit_page", "user_item_tpl", "user_item" );
$t->set_block( "todo_edit_page", "deadline_tpl", "deadline" );

$t->set_block( "todo_edit_page", "list_logs_tpl", "list_logs" );
$t->set_block( "list_logs_tpl", "log_item_tpl", "log_item" );


$t->set_block( "todo_edit_page", "errors_tpl", "errors" );
$t->set_var( "errors", "&nbsp;" );
$t->set_var( "log_item", "&nbsp;" );
$t->set_var( "list_logs", "&nbsp;" );

$todo = new eZTodo();
$todo->get( $TodoID );

if ( $todo->IsPublic() )
{
    $t->set_var( "todo_is_private", "" );
    $t->parse( "todo_is_public", "todo_is_public_tpl" );
}
else
{
    $t->set_var( "todo_is_private", "" );
    $t->parse( "todo_is_public", "todo_is_private_tpl" );
}

$t->set_var( "todo_name", $todo->name() );
$t->set_var( "todo_description", $todo->description() );
$t->set_var( "todo_id", $todo->id() );

$logs = $todo->logs();

if ( count( $logs ) > 0 )
{
    foreach ( $logs as $log )
    {
        $t->set_var( "log_view", $log->log() );
        $t->set_var( "log_created", $locale->format( $log->created() ) );
        
        $t->parse( "log_item", "log_item_tpl", true );
    }
    $t->parse( "list_logs", "list_logs_tpl" );
}

$owner = new eZUser( $todo->ownerID() );
$t->set_var( "first_name", $owner->firstName() );
$t->set_var( "last_name", $owner->lastName() );

$category = new eZCategory();
$category_array =& $category->getAll();

for ( $i = 0; $i < count( $category_array ); $i++ )
{ 
    if ( $todo->categoryID() == $category_array[$i]->id() )
    {
        $t->set_var( "todo_category", $category_array[$i]->name() );
    }
    else
    {
        $t->set_var( "todo_category", "" );
    }
    $t->parse( "category_select", "category_select_tpl", true );
}

$priority = new eZPriority();
$priority_array =& $priority->getAll();

for ( $i = 0; $i < count( $priority_array ); $i++ )
{ 
    if ( $todo->priorityID() == $priority_array[$i]->id() )
    {
        $t->set_var( "todo_priority", $priority_array[$i]->name() );
    }
    else
    {
        $t->set_var( "todo_priority", "" );
    }
    $t->parse( "priority_select", "priority_select_tpl", true );
}

$status = new eZStatus();
$status_array =& $status->getAll();

for ( $i = 0; $i < count( $status_array ); $i++ )
{ 
    if ( $todo->statusID() == $status_array[$i]->id() )
    {
        $t->set_var( "todo_status", $status_array[$i]->name() );
    }
    else
    {
        $t->set_var( "todo_status", "" );
    }
    $t->parse( "status_select", "status_select_tpl", true );
}

$due = $todo->due();
if ( $due )
{
    $duedate = new eZDate();
    $duedate->setTimeStamp( $due->timeStamp() );
    $locale = new eZLocale( $Language );
    $t->set_var( "todo_deadline", $locale->format( $duedate ) );
    $t->parse( "deadline", "deadline_tpl" );
}
else
{
    $t->set_var( "deadline", "" );
}

$user = new eZUser( $todo->userID() );

$t->set_var( "user_id", $user->id() );
$t->set_var( "user_firstname", $user->firstName() );
$t->set_var( "user_lastname", $user->lastName() );

$t->pparse( "output", "todo_edit_page" );

?>
