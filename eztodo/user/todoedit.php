<?php
//
// $Id: todoedit.php 9750 2003-01-03 16:12:28Z br $
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

include_once( "classes/ezhttptool.php" );

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
        $ret = "0". $ret;
    }
    return $ret;
}

if ( isSet( $Delete ) )
{
    $Action = "delete";
}
if ( isSet( $List ) )
{
    eZHTTPTool::header( "Location: /todo" );
    exit();
}
if ( isSet( $Edit ) )
{
    $Action = "edit";
}

if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: /todo" );
    exit();
}

include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTodoMain", "Language" );
$NotDoneID = $ini->read_var( "eZTodoMain", "NotDoneID" );

$iniLanguage = new INIFile( "eztodo/user/intl/" . $Language . "/todoedit.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );
include_once( "eztodo/classes/ezstatus.php" );
include_once( "eztodo/classes/eztodolog.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezusergroup.php" );

$locale = new eZLocale( $Language );

$user =& eZUser::currentUser();
$redirect = true;

if ( isSet( $AddLog ) )
{
    $log = new eZTodoLog();
    $log->setLog( $Log );
    $log->store();
    $todo = new eZTodo( $TodoID );
    $todo->addLog( $log );

    $Action = "update";
    $redirect = false;
}

if ( !$user )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

$CategoryID = eZHTTPTool::getVar( "CategoryID", true );
$PriorityID = eZHTTPTool::getVar( "PriorityID", true );
$UserID = eZHTTPTool::getVar( "UserID", true );
$Name = eZHTTPTool::getVar( "Name", true );
$Description = eZHTTPTool::getVar( "Description", true );
$StatusID = eZHTTPTool::getVar( "StatusID", true );

$t = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "eztodo/user/intl", $Language, "todoedit.php" );
$t->setAllStrings();

$t->set_file( "todo_edit_page", "todoedit.tpl" );

$t->set_block( "todo_edit_page", "category_select_tpl", "category_select" );
$t->set_block( "todo_edit_page", "priority_select_tpl", "priority_select" );
$t->set_block( "todo_edit_page", "status_select_tpl", "status_select" );
$t->set_block( "todo_edit_page", "user_item_tpl", "user_item" );
$t->set_block( "todo_edit_page", "send_mail_tpl", "send_mail" );
$t->set_block( "todo_edit_page", "day_item_tpl", "day_item" );

$t->set_block( "todo_edit_page", "list_logs_tpl", "list_logs" );
$t->set_block( "list_logs_tpl", "log_item_tpl", "log_item" );

$t->set_block( "todo_edit_page", "errors_tpl", "errors" );
$t->set_var( "errors", "&nbsp;" );

$t->set_var( "name", $Name );
$t->set_var( "description", $Description );
$t->set_var( "list_logs", "" );
$t->set_var( "send_mail", "" );
$t->set_var( "log_item", "" );

$error = false;
$nameCheck = true;
$permissionCheck = true;
$descriptionCheck = false;
$userCheck = true;

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

$t->set_block( "errors_tpl", "error_permission_tpl", "error_permission" );
$t->set_var( "error_permission", "&nbsp;" );

$t->set_block( "errors_tpl", "error_user_tpl", "error_user" );
$t->set_var( "error_user", "&nbsp;" );


if ( ( $userCheck ) && ( $Action == "update" ) || ( $Action == "updateStatus" ) )
{
    $todo = new eZTodo( $TodoID );

    if ( ( $todo->userID() == $user->id() ) || ( $todo->ownerID() == $user->id() ) ||
         ( eZPermission::checkPermission( $user, "eZTodo", "EditOthers" ) == true ) )
    {
    }
    else
    {
        $t->parse( "error_user", "error_user_tpl" );
        $error = true;
    }
}


if ( $Action == "insert" || $Action == "update" )
{
    if ( $nameCheck )
    {
        if ( empty( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }
    if ( $descriptionCheck )
    {
        if ( empty( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }
    if ( $user->id() != $UserID )
    {
        if ( eZPermission::checkPermission( $user, "eZTodo", "AddOthers" ) == false )
        {
            $t->parse( "error_permission", "error_permission_tpl" );
            $error = true;
        }
    }
    if ( $DeadlineYear > 0 )
    {
        $Due = new eZDateTime( $DeadlineYear, $DeadlineMonth, $DeadlineDay );
    }
    else
    {
        $Due = "";
    }
}

if ( $error )
{
    $t->parse( "errors", "errors_tpl" );
}

// Save a todo in the database.
if ( $Action == "insert" && $error == false )
{
    $todo = new eZTodo();
    $todo->setName( $Name );
    $todo->setDescription( $Description );
    $todo->setCategoryID( $CategoryID );
    $todo->setPriorityID( $PriorityID );
    $todo->setDue( $Due );
    $todo->setUserID( $UserID );
    $todo->setOwnerID( $user->id() );
    $todo->setStatusID( $StatusID );
    $date = new eZDateTime();

    if ( $IsPublic == "on" )
    {
        $todo->setIsPublic( true );
    }
    else
    {
        $todo->setIsPublic( false );
    }

    $todo->store();
    deleteCache( "default", $Language, $Due->year(), addZero( $Due->month() ) , addZero( $Due->day() ), $UserID );
    if ( $SendMail == "on" )
    {
        $mailTemplate = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                                        "eztodo/user/intl", $Language, "sendmail.php" );

        $mailTemplate->setAllStrings();
        $mailTemplate->set_file( "send_mail_tpl", "sendmail.tpl" );

        $mailTemplate->set_block( "send_mail_tpl", "todo_is_public_tpl", "todo_is_public" );
        $mailTemplate->set_block( "send_mail_tpl", "todo_is_not_public_tpl", "todo_is_not_public" );

        $category = new eZCategory( $CategoryID );
        $priority = new eZPriority( $PriorityID );
        $status = new eZStatus ( $StatusID );
        $owner = new eZUser( $user->id() );
        $user = new eZUser( $UserID );

        if ( $todo->IsPublic() )
        {
            $mailTemplate->set_var( "todo_is_not_public", "" );
            $mailTemplate->parse( "todo_is_public", "todo_is_public_tpl" );
        }
        else
        {
            $mailTemplate->set_var( "todo_is_public", "" );
            $mailTemplate->parse( "todo_is_not_public", "todo_is_not_public_tpl" );
        }

        $mailTemplate->set_var( "todo_name", $Name );
        $mailTemplate->set_var( "todo_category", $category->name() );
        $mailTemplate->set_var( "todo_priority", $priority->name() );
        $mailTemplate->set_var( "todo_status", $status->name() );
        $mailTemplate->set_var( "todo_owner", $owner->firstName() . " " . $owner->lastName() );
        $mailTemplate->set_var( "todo_description", $Description );

        $mail = new eZMail();
        $mail->setSubject( "Todo: " . $Name );
        $mail->setFrom( $owner->email() );
        $mail->setTo( $user->email() );
        $mail->setBody( $mailTemplate->parse( "dummy", "send_mail_tpl" ) );

        $mail->send();
    }

    eZHTTPTool::header( "Location: /todo/todolist" );
    exit();
}


// Update a todo in the database.
if ( $Action == "update" && $error == false )
{
    $userID = $user->ID();
    $todo = new eZTodo();
    $todo->get( $TodoID );
    $oldDue = $todo->due();

    if ( $oldDue )
    {
        deleteCache( "default", $Language, $oldDue->year(), addZero( $oldDue->month() ) , addZero( $oldDue->day() ), $userID );
    }
    deleteCache( "default", $Language, $DeadlineYear, addZero( $DeadlineMonth ), addZero( $DeadlineDay ), $userID );

    $oldstatus = $todo->statusID();

    $todo->setName( $Name );
    $todo->setDescription( $Description );
    $todo->setCategoryID( $CategoryID );
    $todo->setPriorityID( $PriorityID );
    $todo->setDue( $Due );
    $todo->setUserID( $userID );
    $todo->setStatusID( $StatusID );

    if ( $IsPublic == "on" )
    {
        $todo->setIsPublic( true );
    }
    else
    {
        $todo->setIsPublic( false );
    }
    $todo->store();

    if ( ( $MailLog ) && ( is_a( $log, "eZTodoLog" ) ) )
    {
        $mailTemplate = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                                        "eztodo/user/intl", $Language, "maillog.php" );

        $mailTemplate->setAllStrings();

        $mailTemplate->set_file( "send_mail_tpl", "maillog.tpl" );

        $mailTemplate->set_block( "send_mail_tpl", "todo_is_public_tpl", "todo_is_public" );
        $mailTemplate->set_block( "send_mail_tpl", "todo_is_not_public_tpl", "todo_is_not_public" );

        $category = new eZCategory( $CategoryID );
        $priority = new eZPriority( $PriorityID );
        $status = new ezStatus( $StatusID );
        $owner = new eZUser( $user->id() );
        $user = new eZUser( $UserID );

        if ( $todo->IsPublic() )
        {
            $mailTemplate->set_var( "todo_is_not_public", "" );
            $mailTemplate->parse( "todo_is_public", "todo_is_public_tpl" );
        }
        else
        {
            $mailTemplate->set_var( "todo_is_public", "" );
            $mailTemplate->parse( "todo_is_not_public", "todo_is_not_public_tpl" );
        }

        $locale = new eZLocale( $Language );
        $mailTemplate->set_var( "todo_name", $Name );
        $mailTemplate->set_var( "todo_category", $category->name() );
        $mailTemplate->set_var( "todo_priority", $priority->name() );
        $mailTemplate->set_var( "todo_status", $status->name() );
        $mailTemplate->set_var( "todo_owner", $owner->firstName() . " " . $owner->lastName() );
        $mailTemplate->set_var( "todo_description", $Description );

        $mailTemplate->set_var( "time", $locale->format( $log->created() ) );
        $mailTemplate->set_var( "log", $log->log() );

        $mail = new eZMail();
        $mail->setSubject( "Todo log: " . $Name );
        $mail->setFrom( $owner->email() );
        $mail->setTo( $user->email() );
        $mail->setBody( $mailTemplate->parse( "dummy", "send_mail_tpl" ) );

        $mail->send();

    }

    if ( $redirect )
    {
        eZHTTPTool::header( "Location: /todo/todolist/" );
        exit();
    }
    else
    {
        $Action = "edit";
    }
}

// Delete a todo in the database.
if ( $Action == "delete" )
{
    $todo = new eZTodo();
    $todo->get( $TodoID );
    if ( $todo->userID == $user->id() || $todo->ownerID == $user->id() )
        $todo->delete();
    eZHTTPTool::header( "Location: /todo/todolist/" );
    exit();
}

if ( $Action == "new" )
{
    $Deadline = new eZDateTime();
    $DeadlineDay = $Deadline->day();
    $DeadlineMonth = $Deadline->month();
    $DeadlineYear = $Deadline->year();
    $action_value = "insert";
    $name = "";
    $description = "";
    $year = "";
    $mnd = "";
    $day = "";
    $hour = "";
    $min = "";
    $t->set_var( "text", "" );
    $t->parse( "send_mail", "send_mail_tpl" );
}

if ( $error )
{
    $priorityID = $PriorityID;
    $t->set_var( "todo_is_public", $IsPublic == "on" ? "checked" : "" );
    $t->set_var( "send_mail_checkbox", $SendMail == "on" ? "checked" : "" );
    $t->parse( "send_mail", "send_mail_tpl" );
}

// default user
$OwnerID = $user->id();

$datetime = new eZDateTime();

if ( $Action == "new" || $error )
{
    $t->set_var( "current_date", $locale->format( $datetime ) );
    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
    $t->set_var( "todo_id", "" );
    $t->set_var( "action_value", "insert" );

    for ( $i = 1; $i <= 31; $i++ )
    {
        $t->set_var( "day_id", $i );
        $t->set_var( "day_value", $i );
        $t->set_var( "selected", "" );
        if ( ( $DeadlineDay == "" and $i == 1 ) or $DeadlineDay == $i )
            $t->set_var( "selected", "selected" );
        $t->parse( "day_item", "day_item_tpl", true );
    }

    $month_array = array( 1 => "select_january",
                          2 => "select_february",
                          3 => "select_march",
                          4 => "select_april",
                          5 => "select_may",
                          6 => "select_june",
                          7 => "select_july",
                          8 => "select_august",
                          9 => "select_september",
                          10 => "select_october",
                          11 => "select_november",
                          12 => "select_december" );

    for ( $i = 1; $i <= count( $month_array ); $i++ )
    {
        if ( $i == $DeadlineMonth )
            $t->set_var( $month_array[$i], "selected" );
        else
            $t->set_var( $month_array[$i], "" );
    }

    if ( $DeadlineYear > 0 )
        $t->set_var( "deadlineyear", $DeadlineYear );
    else
        $t->set_var( "deadlineyear", "" );

    $t->set_var( "comment", $Comment );

    $userID = $user->id();
}

// Edit a todo.
if ( $Action == "edit" )
{
    // Return the current time

    $todo = new eZTodo( $TodoID );

    if ( $todo->status() == true )
    {
        $t->set_var( "status", "checked" );
    }
    else
    {
        $t->set_var( "status", "" );
    }

    if ( $todo->IsPublic() )
    {
        $t->set_var( "todo_is_public", "checked" );
    }
    else
    {
        $t->set_var( "todo_is_public", "" );
    }

    $t->set_var( "todo_id", $todo->id() );
    $t->set_var( "name", $todo->name() );
    $t->set_var( "description", $todo->description() );

    $categoryID = $todo->categoryID();
    $priorityID = $todo->priorityID();
    $userID = $todo->userID();
    $ownerID = $todo->ownerID();
    $StatusID = $todo->statusID();

    $duestamp = $todo->due();
    if ( $duestamp )
    {
        $DeadlineDay = $duestamp->day();
        $DeadlineMonth = $duestamp->month();
        $DeadlineYear = $duestamp->year();
    }
    else
    {
        $DeadlineDay = "";
        $DeadlineMonth = "";
        $DeadlineYear = "";
    }
    // Get the owner
    $owner = new eZUser( $todo->ownerID() );
    $t->set_var( "first_name", $owner->firstName() );
    $t->set_var( "last_name", $owner->lastName() );

    $logs = $todo->logs();

    if ( count( $logs ) > 0 )
    {
        foreach ( $logs as $log )
        {
            $t->set_var( "log_view", $log->log() );
            $t->set_var( "log_created", $locale->format( $log->created() ) );

            $t->parse( "log_item", "log_item_tpl", true );
        }
    }
    $t->parse( "list_logs", "list_logs_tpl" );

    for ( $i = 1; $i <= 31; $i++ )
    {
        $t->set_var( "day_id", $i );
        $t->set_var( "day_value", $i );
        $t->set_var( "selected", "" );
        if ( ( $DeadlineDay == "" and $i == 1 ) or $DeadlineDay == $i )
            $t->set_var( "selected", "selected" );
        $t->parse( "day_item", "day_item_tpl", true );
    }

    $month_array = array( 1 => "select_january",
                          2 => "select_february",
                          3 => "select_march",
                          4 => "select_april",
                          5 => "select_may",
                          6 => "select_june",
                          7 => "select_july",
                          8 => "select_august",
                          9 => "select_september",
                          10 => "select_october",
                          11 => "select_november",
                          12 => "select_december" );

    foreach ( $month_array as $month )
    {
        $t->set_var( $month, "" );
    }

    $var_name =& $month_array[$DeadlineMonth];
    if ( $var_name == "" )
        $var_name =& $month_array[1];

    $t->set_var( $var_name, "selected" );

    $t->set_var( "deadlineyear", $DeadlineYear );

    $t->set_var( "comment", $Comment );

    $headline = "Rediger todo";
    $submit_description = "Rediger";

    $t->set_var( "action_value", "update" );
}

// Category selector.
$category = new eZCategory();
$category_array = $category->getAll();

for ( $i = 0; $i < count( $category_array ); $i++ )
{
    $t->set_var( "category_id", $category_array[$i]->id() );
    $t->set_var( "category_name", $category_array[$i]->name() );

    if ( $categoryID == $category_array[$i]->id() )
    {
         $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }
    $t->parse( "category_select", "category_select_tpl", true );
}

// Priority selector.
$priority = new eZPriority();
$priority_array = $priority->getAll();

for ( $i = 0; $i < count( $priority_array ); $i++ )
{
    $t->set_var( "priority_id", $priority_array[$i]->id() );
    $t->set_var( "priority_name", $priority_array[$i]->name() );

    if ( $priorityID == $priority_array[$i]->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "priority_select", "priority_select_tpl", true );
}

// Status selector
$status = new eZStatus();
$status_array = $status->getAll();

if ( $Action == "new")
    $StatusID = $NotDoneID;

for ( $i = 0; $i < count( $status_array ); $i++ )
{
    $t->set_var( "status_id", $status_array[$i]->id() );
    $t->set_var( "status_name", $status_array[$i]->name() );

    if ( $StatusID == $status_array[$i]->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "status_select", "status_select_tpl", true );
}


// User selector.

$user =& eZUser::currentUser();
$user_array = $user->getAll();

foreach ( $user_array as $userItem )
{
    $t->set_var( "user_id", $userItem->id() );
    $t->set_var( "user_firstname", $userItem->firstName() );
    $t->set_var( "user_lastname", $userItem->lastName() );

    // User select
    if ( $UserID == $userItem->id() )
    {
        $t->set_var( "user_is_selected", "selected" );
    }
    else
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

    $t->parse( "user_item", "user_item_tpl", true );
}

// Template variables.

$t->pparse( "output", "todo_edit_page" );

?>