<?php
//
// $Id: eztodo.php 7992 2001-10-22 09:22:31Z  $
//
// Definition of eZTodo class
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

//!! eZTodo
//! The eZTodo handles the todo informasjon.
/*!
  Handles the todo informasjon stored in the database.
*/

// include_once( "eztodo/classes/eztodolog.php" );
// include_once( "classes/ezdatetime.php" );

class eZTodo
{
    //! eZTodo
    /*!
      eZtodo Constructor.
    */
    function __construct( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores the todo object to the database.
      Returnes the ID to the eZCompany object if the store is a success.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        if ( is_a( $this->Due, "eZDateTime" ) )
            $due = $this->Due->timeStamp();
        else
            $due = "";

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTodo_Todo" );
			$this->ID = $db->nextID( "eZTodo_Todo", "ID" );
            $timestamp = eZDateTime::timeStamp( true );
            $res[] = $db->query( "INSERT INTO eZTodo_Todo
                                  (ID,
                                   Name,
                                   Description,
                                   Category,
                                   Priority,
                                   Due,
                                   UserID,
                                   OwnerID,
                                   Status,
                                   Date,
                                   IsPublic)
                                  VALUES
                                  ('$this->ID',
                                   '$name',
                                   '$description',
                                   '$this->Category',
                                   '$this->Priority',
                                   '$due',
                                   '$this->UserID',
                                   '$this->OwnerID',
                                   '$this->Status',
                                   '$timestamp',
                                   '$this->IsPublic')" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTodo_Todo SET
                                              Name='$name',
                                              Description='$description',
                                              Category='$this->Category',
                                              Priority='$this->Priority',
                                              Due='$due',
                                              UserID='$this->UserID',
                                              OwnerID='$this->OwnerID',
                                              Status='$this->Status',
                                              IsPublic='$this->IsPublic',
                                              Date=Date
                                              WHERE ID='$this->ID' ");
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes the todo object in the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZTodo_Todo WHERE ID='$this->ID'" );
        eZDB::finish( $res, $db );

        return true;
    }

    /*!
      Gets the todo object from the database, where ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $todo_array, "SELECT * FROM eZTodo_Todo WHERE ID='$id'" );
            if ( count( $todo_array ) > 1 )
            {
                die( "Error: Todo's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $todo_array ) == 1 )
            {
                $this->ID = $todo_array[0][$db->fieldName( "ID" )];
                $this->Name = $todo_array[0][$db->fieldName( "Name" )];
                $this->Description = $todo_array[0][$db->fieldName( "Description" )];
                $this->Category = $todo_array[0][$db->fieldName( "Category" )];
                $this->Priority = $todo_array[0][$db->fieldName( "Priority" )];
                $this->Due = $todo_array[0][$db->fieldName( "Due" )];
                $this->Date = $todo_array[0][$db->fieldName( "Date" )];
                $this->UserID = $todo_array[0][$db->fieldName( "UserID" )];
                $this->OwnerID = $todo_array[0][$db->fieldName( "OwnerID" )];
                $this->Status = $todo_array[0][$db->fieldName( "Status" )];
                $this->IsPublic = $todo_array[0][$db->fieldName( "IsPublic" )];

                $ret = true;
            }
        }

        return $ret;
    }

    /*!
      Gets all the todo informasjon from the database.
      Returns the array in $todo_array ordered by name.
    */
    static public function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $todo_array = array();

        $db->array_query( $todo_array, "SELECT ID FROM eZTodo_Todo ORDER BY Priority" );

        for ( $i = 0; $i < count( $todo_array ); $i++ )
        {
            $return_array[$i] = new eZTodo( $todo_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Gets all the todo infomasjon from a owner, where ID == $id.
      Return the array in $todo_array ordered by name.

    */
    static public function getByUserID( $id, $statusID = 0, $categoryID = 0 )
    {
        $db =& eZDB::globalDatabase();
        $todo_array = 0;

        $return_array = array();
        $todo_array = array();
        $showStatus = false;
        $showCategory = false;

        if ( $statusID != 0 )
        {
            $showStatus = "AND Status='$statusID'";
        }

        if ( $categoryID != 0 )
        {
            $showCategory = "AND Category='$categoryID'";
        }

        $sql = "SELECT ID FROM eZTodo_Todo WHERE UserID='$id' $showStatus $showCategory";

        $db->array_query( $todo_array, $sql );

        for ( $i = 0; $i < count( $todo_array ); $i++ )
        {
            $return_array[$i] = new eZTodo( $todo_array[$i][$db->fieldName( "ID" )], 0 );
        }
        return $return_array;
    }

    /*!
      Gets all the todo infomasjon from a owner, where ID == $id.
      Return the array in $todo_array ordered by name.

    */
    function getByOthers( $id )
    {
        $db =& eZDB::globalDatabase();
        $todo_array = 0;

        $return_array = array();
        $todo_array = array();

        $db->array_query( $todo_array, "SELECT ID FROM eZTodo_Todo WHERE ( UserID='$id' or OwnerID='$id' ) AND IsPublic='1' ORDER BY Priority");

        for ( $i = 0; $i < count( $todo_array ); $i++ )
        {
            $return_array[$i] = new eZTodo( $todo_array[$i][$db->fieldName( "ID" )], 0 );
        }
        return $return_array;
    }

    /*!
      Tilte of the todo.
      Returns the name of the todo as a string.
    */
    function name()
    {
       return htmlspecialchars( $this->Name );
    }

    /*!
      Gets all the todo infomasjon from a owner, where ID == $id.
      Return the array in $todo_array ordered by name.

    */
    function getByLimit( $id, $limit = 5, $status = 0, $except = 0 )
    {
        $db =& eZDB::globalDatabase();
        $todo_array = 0;

        $return_array = array();
        $todo_array = array();

        if ( $except == "0" )
        {
    	    $db->array_query( $todo_array, "SELECT ID FROM eZTodo_Todo WHERE UserID='$id' AND Status='$status' ORDER BY Priority", array( "Limit" => $limit, "Offset" => 0 ) );
        }
        else
        {
    	    $db->array_query( $todo_array, "SELECT ID FROM eZTodo_Todo WHERE UserID='$id' AND Status!='$status' ORDER BY Priority", array( "Limit" => $limit, "Offset" => 0 ) );
        }

        for ( $i = 0; $i < count( $todo_array ); $i++ )
        {
            $return_array[$i] = new eZTodo( $todo_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      \static
      Gets all todos for one spesific date
    */
    static public function &getByDate( $user, $date )
    {
        if ( is_a( $date, "eZDate" ) )
        {
            $currentUser =& eZUser::currentUser();
            if ( is_numeric( $user ) )
                $userid = $user;
            else
                $userid = $user->id();
            if ( $currentUser && $currentUser->id() != $userid )
                $public_string = "AND IsPublic='1'";
            else
                $public_string = "";
            $return_array = array();
            $enddate = new eZDate();
            $enddate->setTimeStamp( $date->timeStamp() + 86400 );
            $db =& eZDB::globalDatabase();
    	    $db->array_query( $todo_array, "SELECT ID FROM eZTodo_Todo WHERE Due>='" . $date->timeStamp() . "' AND Due<'" . $enddate->timeStamp() . "' AND UserID='" . $userid . "' $public_string ORDER BY Priority" );
            for ( $i = 0; $i < count( $todo_array ); $i++ )
            {
                $return_array[$i] = new eZTodo( $todo_array[$i][$db->fieldName( "ID" )], 0 );
            }
            return $return_array;
        }
    }

    /*!
      Sets the name of the todo.
      The new name of the todo is passed as a paramenter ( $value ).
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Description of the todo.
      Returns the description of the todo as a string.
    */
    function description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Sets the description of the todo.
      The new description of the todo is passed as a paramenter ( $value ).
     */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Tilte of the category.
      Returns the category of the todo as a string.
    */
    function categoryID()
    {
        return $this->Category;
    }

    /*!
      Sets the category of the todo.
      The new category of the todo is passed as a paramenter ( $value ).
     */
    function setCategoryID( $value )
    {
        $this->Category = $value;
    }

    /*!
      Priority of the todo.
      Returns the priority of the todo as a string.
    */
    function priorityID()
    {
        return $this->Priority;
    }

    /*!
      Sets the priority of the todo.
      The new priority of the todo is passed as a paramenter ( $value ).
     */
    function setPriorityID( $value )
    {
        $this->Priority = $value;
    }

    /*!
      Tilte of the status.
      Returns the status of the todo as a string.
    */
    function statusID()
    {
        return $this->Status;
    }

    /*!
      Sets the status of the todo.
      The new status of the todo is passed as a paramenter ( $value ).
     */
    function setStatusID( $value )
    {
        $this->Status = $value;
    }

    /*!
      Due of the todo.
      Returns the due of the todo as a string.
    */
    function due()
    {
        if ( $this->Due == 0 )
            return false;
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Due );

        return $dateTime;
    }

    /*!
      Sets the due of the todo.
      The new due of the todo is passed as a paramenter ( $value ).
     */
    function setDue( $value )
    {
        $this->Due = $value;
    }

    /*!
      Date of the todo.
      Returns the due of the todo as a string.
    */
    function date()
    {
        $dateTime = new eZDateTime( );
        $dateTime->setTimeStamp( $this->Date );

        return $dateTime;
    }

    /*!
      Sets the date of the todo.
      The new date of the todo is passed as a paramenter ( $value ).
     */
    function setDate( $value )
    {
        $this->Date = $value;
    }

    /*!
      UserID of the todo.
    */
    function userID()
    {
        return $this->UserID;
    }

    /*!
      Sets the user of the todo.
      The new user of the todo is passed as a paramenter ( $value ).
     */
    function setUserID( $value )
    {
        $this->UserID = $value;
    }

    /*!
      OwnerID of the todo.
    */
    function ownerID()
    {
        return $this->OwnerID;
    }

    /*!
      Sets the owner of the todo.
      The new owner of the todo is passed as a paramenter ( $value ).
     */
    function setOwnerID( $value )
    {
        $this->OwnerID = $value;
    }

    /*!
      Status of the todo.
      Returns the status of the todo as a string.
    */
    function status()
    {
        return $this->Status;
    }

    /*!
      Sets the status of the todo.
      The new status of the todo is passed as a paramenter ( $value ).
     */
    function setStatus( $value )
    {
        $this->Status = $value;
    }


    /*!
      Permission of the todo.
      Returns the permission of the todo as a string.
    */
    function isPublic()
    {
        if ( $this->IsPublic == 1 )
            return true;
        else
            return false;
    }

    /*!
      Sets the permission of the todo.
      The new permission of the todo is passed as a paramenter ( $value ).
     */
    function setIsPublic( $value )
    {
        if ( $value == true )
            $this->IsPublic = 1;
        else
            $this->IsPublic = 0;
    }

    /*!
      Returns the logs over this todo.

      The logs is returned as array of eZTodoLog objects.
    */
    function logs()
    {
        $db =& eZDB::globalDatabase();
        $returnArray = array();

        $db->array_query( $logsArray, "SELECT LogID from eZTodo_TodoLogLink where TodoID='$this->ID'" );

        if ( count( $logsArray ) > 0 )
        {
            foreach ( $logsArray as $log )
            {
                $returnArray[] = new eZTodoLog( $log[$db->fieldName( "LogID" )] );
            }
        }
        return $returnArray;
    }

    /*!
      Adds an log to the todo.
    */
    function addLog( &$value )
    {
        if ( is_a( $value, "eZTodoLog" ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();

            $logID = $value->id();
            $db->lock( "eZTodo_TodoLogLink" );
            $nextID = $db->nextID( "eZTodo_TodoLogLink", "ID" );
            $res[] = $db->query( "INSERT INTO eZTodo_TodoLogLink (ID, TodoID, LogID)
                                  VALUES ('$nextID', '$this->ID', '$logID')" );
            $db->unlock();
            eZDB::finish( $res, $db );
        }
    }

    /*!
      Id of the todo.
      Returns the id of the todo as an int.
    */
    function id()
    {
        return $this->ID;
    }

    var $OwnerID;
    var $UserID;
    var $IsPublic;
    var $Description;
    var $Due;
    var $Date;
    var $Name;
    var $CategoryID;
    var $PriorityID;
    var $ID;
    var $Status;
    var $Category;
    var $Priority;
}

?>