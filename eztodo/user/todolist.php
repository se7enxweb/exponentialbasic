<?php
//
// $Id: todolist.php 8503 2001-11-19 09:37:02Z jhe $
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
// include_once( "classes/ezhttptool.php" );

$ini = INIFile::globalINI();
$Language = $ini->read_var( "eZTodoMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTodoMain", "DocumentRoot" );

$iniLanguage = new INIFile( "eztodo/user/intl/$Language/todolist.php.ini", false );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezpermission.php" );

// include_once( "eztodo/classes/eztodo.php" );
// include_once( "eztodo/classes/ezcategory.php" );
// include_once( "eztodo/classes/ezpriority.php" );
// include_once( "eztodo/classes/ezstatus.php" );


if ( isSet( $New ) )
{
    eZHTTPTool::header( "Location: /todo/todoedit/new" );
    exit();
}

$user =& eZUser::currentUser();

if ( $user == false )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

if ( isSet( $Delete ) )
{
    if ( count( $DeleteArrayID ) > 0 )
    {
        foreach ( $DeleteArrayID as $todoid )
        {
            $todo = new eZTodo( $todoid );
            $due = $todo->due();
            if ( $due )
                deleteCache( "default", $Language, $due->year(), addZero( $due->month() ) , addZero( $due->day() ), $user->id() );
            $todo->delete();
        }
    }
}

$t = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "eztodo/user/intl/", $Language, "todolist.php" );
$t->setAllStrings();
$t->set_file( "todo_list_page", "todolist.tpl" );

$CategoryID = eZHTTPTool::getVar( "CategoryTodoID" );
//$Show = eZHTTPTool::getVar( "Show" );
$ShowButton = eZHTTPTool::getVar( "ShowButton" );
$StatusID = eZHTTPTool::getVar( "StatusTodoID" );

if ( isSet( $Show ) )
{
    $GetByUserID = eZHTTPTool::getVar( "GetByUserID" );
    $session->setVariable( "TodoUser", $GetByUserID );
}
else
{
    $GetByUserID = $session->variable( "TodoUser" );
    if ( !$GetByUserID )
    {
        $GetByUserID = $user->id();
        $session->setVariable( "TodoUser", $GetByUserID );
    }
}

$t->set_block( "todo_list_page", "todo_item_tpl", "todo_item" );
$t->set_block( "todo_list_page", "user_item_tpl", "user_item" );
$t->set_block( "todo_list_page", "no_found_tpl", "no_found" );
$t->set_block( "todo_list_page", "category_item_tpl", "category_item" );
$t->set_block( "todo_list_page", "status_item_tpl", "status_item" );

$t->set_block( "todo_item_tpl", "todo_is_public_tpl", "todo_is_public" );
$t->set_block( "todo_item_tpl", "todo_is_not_public_tpl", "todo_is_not_public" );


if ( isSet( $ShowButton ) )
{
    $session->setVariable( "TodoCategory", $CategoryID );
    $session->setVariable( "TodoStatus", $StatusID );
}

$showCategory = $session->variable( "TodoCategory" );
$showTodo = $session->variable( "TodoStatus" );

$todo = new eZTodo();

// Check if the user want its own todos or the public todos. This needs the "view-others-todo permission".
$currentUserID = $user->id();
if ( eZPermission::checkPermission( $user, "eZTodo", "ViewOtherUsers" ) )
{
    if ( $GetByUserID != "" )
    {
        if ( $GetByUserID == $currentUserID )
        {
            $todo_array = $todo->getByUserID( $currentUserID, $showTodo, $showCategory );
        }
        else
        {
            $todo_array = $todo->getByOthers( $GetByUserID, $showTodo, $showCategory );
        }
    }
    else
    {
        $todo_array = $todo->getByUserID( $currentUserID, $showTodo, $showCategory );
    }
}
else
{
    $todo_array = $todo->getByUserID( $currentUserID, $showTodo, $showCategory );
}

$showID = $session->variable( "ShowTodoID" );

// User selector.
$userList = $user->getAll();

foreach ( $userList as $userItem )
{
    if ( !isSet( $GetByUserID ) )
    {
        $GetByUserID = $currentUserID;
    }
    $t->set_var( "user_id", $userItem->id() );
    $t->set_var( "user_firstname", $userItem->firstName() );
    $t->set_var( "user_lastname", $userItem->lastName() );

    if ( $GetByUserID == $user->id() )
    {
        if ( $user->id() == $userItem->id() )
        {
            $t->set_var( "user_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "user_is_selected", "" );
        }
    }
    else
    {
        if ( $GetByUserID == $userItem->id() )
        {
            $t->set_var( "user_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "user_is_selected", "" );
        }
    }
    $t->parse( "user_item", "user_item_tpl", true );
}
    
// Todo list
if ( count( $todo_array ) == 0 )
{
    $t->set_var( "todo_item", "" );
    $t->parse( "no_found", "no_found_tpl" );
}

$locale = new eZLocale( $Language );

$i = 0;
foreach ( $todo_array as $todoItem )
{
    if ( $i % 2 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
    
    $t->set_var( "todo_id", $todoItem->id() );
    $t->set_var( "todo_name", $todoItem->name() );
    $t->set_var( "todo_description", $todoItem->description() );
    $cat = new eZCategory( $todoItem->categoryID() );
    $t->set_var( "todo_category_id", $cat->name() );
    $pri = new eZPriority( $todoItem->priorityID() );
    $t->set_var( "todo_priority_id", $pri->name() );
    $t->set_var( "todo_userid", $todoItem->userID() );
    $stat = new eZStatus( $todoItem->statusID() );
    $t->set_var( "todo_status", $stat->name() );
    
    if ( $todoItem->isPublic() )
    {
        $t->set_var( "todo_is_not_public", "" );
        $t->parse( "todo_is_public", "todo_is_public_tpl" );
    }
    else
    {
        $t->parse( "todo_is_not_public", "todo_is_not_public_tpl" );
        $t->set_var( "todo_is_public", "" );
    }
    
    $t->set_var( "todo_date", $locale->format( $todoItem->date() ) );
    $t->set_var( "no_found", "" );

    $t->parse( "todo_item", "todo_item_tpl", true );
    $i++;
}

$category = new eZCategory();
$categoryList =& $category->getAll();
$t->set_var( "category_selected", $showCategory ? "" : "selected" );

foreach ( $categoryList as $category )
{
    $t->set_var( "category_name", $category->name() );
    $t->set_var( "category_id", $category->id() );

    if ( $category->id() == $showCategory )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "category_item", "category_item_tpl", true );
}

$status = new eZStatus();
$statusList =& $status->getAll();
$t->set_var( "all_selected", $showTodo ? "" : "selected" );

foreach ( $statusList as $status )
{
    $t->set_var( "status_name", $status->name() );
    $t->set_var( "status_id", $status->id() );

    if ( $status->id() == $showTodo )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "status_item", "status_item_tpl", true );
}

$t->pparse( "output", "todo_list_page" );


// deletes the dayview cache file for a given day
function deleteCache( $SiteDesign, $language, $year, $month, $day, $userID )
{
    @eZFile::unlink( "ezcalendar/user/cache/dayview.tpl-$SiteDesign-$language-$year-$month-$day-$userID.cache" );
    @eZFile::unlink( "ezcalendar/user/cache/monthview.tpl-$SiteDesign-$language-$year-$month-$userID.cache" );
    @eZFile::unlink( "ezcalendar/user/cache/dayview.tpl-$SiteDesign-$language-$year-$month-$day-$userID-private.cache" );
    @eZFile::unlink( "ezcalendar/user/cache/monthview.tpl-$SiteDesign-$language-$year-$month-$userID-private.cache" );
}

//Adds a "0" in front of the value if it's below 10.
function addZero( $value )
{
    settype( $value, "integer" );
    $ret = $value;
    if ( $ret < 10 )
    {
        $ret = "0" . $ret;
    }
    return $ret;
}

?>