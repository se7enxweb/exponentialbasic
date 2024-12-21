<?php
// $Id: todomenulist.php 6968 2001-09-05 11:53:39Z jhe $
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

// include_once( "classes/INIFile.php" );

//$ini = new INIFIle( "site.ini");
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTodoMain", "Language" );
$NotDoneID = $ini->read_var( "eZTodoMain", "NotDoneID" );

$iniLanguage = new INIFile( "kernel/eztodo/user/intl/$Language/todolist.php.ini", false );

// include_once( "classes/eztemplate.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "eztodo/classes/eztodo.php" );

$user =& eZUser::currentUser();

$t = new eZTemplate( "kernel/eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "kernel/eztodo/user/intl/", $Language, "todomenulist.php" );
$t->setAllStrings();
$t->set_file( "todo_list_page", "todomenulist.tpl" );

$t->set_block( "todo_list_page", "todo_item_tpl", "todo_item" );
$t->set_block( "todo_list_page", "no_item_tpl", "no_item" );

$todo = new eZTodo();
$todo_array = array();

if ( $user )
{
    $todo_array = $todo->getByLimit( $user->id(), 5, $NotDoneID, 0 );
}

$i=0;
if ( count( $todo_array ) > 0 ) {
	foreach ( $todo_array as $todoItem )
	{
	    if ( ( $i %2 ) == 0 )
	        $t->set_var( "td_class", "bgdark" );
	    else
	        $t->set_var( "td_class", "bglight" );
	    
	    $t->set_var( "todo_id", $todoItem->id() );
	    $t->set_var( "todo_name", $todoItem->name() );
	
	    $t->set_var( "no_item", "" );
	
	    $t->parse( "todo_item", "todo_item_tpl", true );
	    $i++;
	}
} 
else
{
    $t->set_var( "todo_item", "" );
    $t->parse( "no_item", "no_item_tpl" );
}

$t->pparse( "output", "todo_list_page" );

?>

