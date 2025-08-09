<?php
//
// $Id: ezbug.php 6739 2001-08-29 10:37:23Z jhe $
//
// Definition of eZBug class
//
// Created on: <27-Nov-2000 19:43:24 bf>
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

//!! eZBug
//! eZBug handles bug repports.
/*!
  The eZBug class handles bug reports. Each bug report can be a member
  of one or more modules. The modules are handled by the eZBugModule
  class.

  Each bug report is assigned to a bug category. The categories are handled
  by the eZBugCategory class.

  A bug which gets reported is assigned to unhandled bugs list. Handled bugs
  is stored in the archive. A handled bug is assigned a priority and a status.

  The priorities are handled by the eZBugPriority class. Priorities can be e.g. urgent,
  medium and low.

  The statuses are handled by the eZBugStatus class. Statuses ca be e.g. started, done
  and will not be fixed.

  Example:
  \code
  // include the class
  // include_once( "ezbug/classes/ezbug.php" );

  // create a new eZBug object.
  $bug = new eZBug();

  // set the object properties and save it to the database.
  $bug->setUser( eZUser::currentUser() );
  $bug->setName( "Empty search result" );
  $bug->setDescription( "The product search does not return anything." );
  $bug->setIsHandled( false );
  $bug->store();
  \endcode
  \sa eZBug eZBugCategory eZBugModule
*/

/*!TODO
*/

// include_once( "classes/ezdb.php" );
// include_once( "ezmail/classes/ezmail.php" );

// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezbug/classes/ezbugpriority.php" );
// include_once( "ezbug/classes/ezbugstatus.php" );
// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezfilemanager/classes/ezvirtualfile.php" );

class eZBug
{
    /*!
      Constructs a new eZBug object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1)
    {
        $this->IsHandled = 0;
        $this->IsClosed = 0;
        $this->IsPrivate = 0;
        $this->PriorityID = 0;
        $this->StatusID = 0;
        $this->OwnerID = 0;
        $this->UserID = 0;
        $this->ID = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZBug object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $useremail = $db->escapeString( $this->UserEmail );
        $version = $db->escapeString( $this->Version );

        $db->begin();

        $timeStamp = (new eZDateTime())->timeStamp( true );

        if ( !isset( $this->ID ) || $this->ID == 0 || $this->ID == null)
        {
            $db->lock( "eZBug_Bug" );
			$this->ID = $db->nextID( "eZBug_Bug", "ID" );

            $res = $db->query( "INSERT INTO eZBug_Bug
                                            (ID,
                                             Name,
                                             Description,
                                             IsHandled,
                                             IsClosed,
                                             PriorityID,
                                             StatusID,
                                             UserEmail,
                                             Created,
                                             UserID,
                                             OwnerID,
                                             Version,
                                             IsPrivate)
                                        VALUES
                                            ('$this->ID',
                                             '$name',
                                             '$description',
                                             '$this->IsHandled',
                                             '$this->IsClosed',
                                             '$this->PriorityID',
                                             '$this->StatusID',
                                             '$useremail',
                                             '$timeStamp',
                                             '$this->UserID',
                                             '$this->OwnerID',
                                             '$version',
                                             '$this->IsPrivate')" );
            $db->unlock();

        }
        else
        {
            $res = $db->query( "UPDATE eZBug_Bug SET
		                        Name='$name',
                                Description='$description',
                                IsHandled='$this->IsHandled',
                                IsClosed='$this->IsClosed',
                                PriorityID='$this->PriorityID',
                                StatusID='$this->StatusID',
                                UserEmail='$useremail',
                                UserID='$this->UserID',
                                OwnerID='$this->OwnerID',
                                Version='$version',
                                IsPrivate='$this->IsPrivate'
                                WHERE ID='$this->ID'" );
        }

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZBugGroup object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->begin();
            $res[] = $db->query( "DELETE FROM eZBug_BugModuleLink WHERE BugID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZBug_BugCategoryLink WHERE BugID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZBug_Log WHERE BugID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZBug_Bug WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $module_array, "SELECT * FROM eZBug_Bug WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: Bugs with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $module_array ) == 1 )
            {
                $this->ID =& $module_array[0][$db->fieldName( "ID" )];
                $this->Name =& $module_array[0][$db->fieldName( "Name" )];
                $this->Description =& $module_array[0][$db->fieldName( "Description" )];
                $this->UserID =& $module_array[0][$db->fieldName( "UserID" )];
                $this->UserEmail =& $module_array[0][$db->fieldName( "UserEmail" )];
                $this->Created =& $module_array[0][$db->fieldName( "Created" )];
                $this->IsHandled =& $module_array[0][$db->fieldName( "IsHandled" )];
                $this->IsClosed =& $module_array[0][$db->fieldName( "IsClosed" )];
                $this->PriorityID =& $module_array[0][$db->fieldName( "PriorityID" )];
                $this->StatusID =& $module_array[0][$db->fieldName( "StatusID" )];
                $this->OwnerID =& $module_array[0][$db->fieldName( "OwnerID" )];
                $this->IsPrivate =& $module_array[0][$db->fieldName( "IsPrivate" )];
                $this->Version =& $module_array[0][$db->fieldName( "Version" )];
            }
        }
    }

    /*!
      Returns all the bugs found in the database.

      The bugs are returned as an array of eZBug objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        $return_array = array();
        $module_array = array();

        $db->array_query( $module_array, "SELECT ID FROM eZBug_Bug ORDER BY Name" );

        for ( $i = 0; $i < count( $module_array ); $i++ )
        {
            $return_array[$i] = new eZBug( $module_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Returns all the unhandled bugs found in the database.

      The bugs are returned as an array of eZBug objects.
    */
    function &getUnhandled()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $module_array = array();

        $db->array_query( $module_array, "SELECT ID FROM eZBug_Bug
                                          WHERE IsHandled=0
                                          ORDER BY Created" );

        for ( $i = 0; $i < count( $module_array ); $i++ )
        {
            $return_array[$i] = new eZBug( $module_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }


    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the bug.
    */
    function name( $html = true )
    {
       if ( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Returns the email address to the reporter.
    */
    function userEmail( $html = true )
    {
       if ( $html )
           return htmlspecialchars( $this->UserEmail );
       else
           return $this->UserEmail;

    }

    /*!
      Returns the group description.
    */
    function description( $html = true )
    {
       if ( $html )
           return htmlspecialchars( $this->Description );
       else
           return $this->Description;
    }

    /*!
      Returns the creation time of the bug.

      The time is returned as a eZDateTime object.
    */
    function &created()
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->Created );

       return $dateTime;
    }


    /*!
      Returns true if the bug is handled false if not.
    */
    function isHandled()
    {
       $ret = false;
       if ( $this->IsHandled == 1 )
       {
           $ret = true;
       }
       return $ret;
    }

    /*!
      Returns true if the bug is closed false if not.
    */
    function isClosed()
    {
       $ret = false;
       if ( $this->IsClosed == 1 )
       {
           $ret = true;
       }
       return $ret;
    }

    /*!
      This function returns true if the bug is private, false if not. A private bug can only be seen by priveliged
      users and admins.
     */
    function isPrivate()
    {
        $ret = false;
        if ( $this->IsPrivate == 1 )
        {
            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the user as an eZUser object.

      Returns 0 if the user was not set.
    */
    function user()
    {
       $ret = false;
       $user = new eZUser();

       if ( $user->get( $this->UserID ) )
           $ret = $user;

       return $ret;
    }

        /*!
      Returns the bug owner as an eZUser object.

      Returns 0 if the user was not set.
    */
    function owner()
    {
       $ret = false;
       $user = new eZUser();

       if ( $user->get( $this->OwnerID ) )
           $ret = $user;

       return $ret;
    }

    /*!
      Sets the name of the module.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the email address to the user.

      False is returned if the e-mail address is not valid.
    */
    function setUserEmail( $mail )
    {
       $ret = false;

       if ( eZMail::validate( $mail ) )
       {
           $this->UserEmail = $mail;
           $ret = true;
       }
       return $ret;
    }

    /*!
      Sets the description of the module.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
     Sets the bug to handled or not.
    */
    function setIsHandled( $value )
    {
       if ( $value == true )
       {
           $this->IsHandled = 1;
       }
       else
       {
           $this->IsHandled = 0;
       }
    }

    /*!
     Sets the bug to closed or not.
    */
    function setIsCLosed( $value )
    {
       if ( $value == true )
       {
           $this->IsClosed = 1;
       }
       else
       {
           $this->IsClosed = 0;
       }
    }

    /*!
      Sets the user whom reported the bug.
    */
    function setUser( $user )
    {
       if ( is_a( $user, "eZUser" ) )
       {
           $this->UserID = $user->id();
       }
    }

    /*!
      Sets the priority assigned to the bug.
    */
    function setPriority( $pri )
    {
       if ( is_a( $pri, "eZBugPriority" ) )
       {
           $this->PriorityID = $pri->id();
       }
    }

    /*!
      Sets the status assigned to the bug.
    */
    function setStatus( $status )
    {
       if ( is_a( $status, "eZBugStatus" ) )
       {
           $this->StatusID = $status->id();
       }
    }

    /*!
      Sets the owner of the bug.
      If $user is not of type eZUser the owner is set to NULL.
     */
    function setOwner( $user )
    {
       if ( is_a( $user, "eZUser" ) )
       {
           $this->OwnerID = $user->id();
       }
       else
       {
           $this->OwnerID = 0;
       }
    }

    /*!
      Sets the bug to be private if the parameter is true. A private bug can only be seen by priveliged users
      and admins.
     */
    function setIsPrivate( $priv )
    {
        if ( $priv )
        {
            $this->IsPrivate = 1;
        }
        else
        {
            $this->IsPrivate = 0;
        }
    }

    /*!
      Sets the version number
     */
    function setVersion( $value )
    {
        $this->Version = $value;
    }

    /*!
      Returns the version number
     */
    function version( $asHTML = true )
    {
        if ( $asHTML )
            return htmlspecialchars( $this->Version );
        return $this->Version;
    }

   /*!
      Returns the priority assigned to the bug as an
      eZBugPriority object.

      If no priority is assigned 0 / false is returned.
    */
    function priority()
    {
        $ret = false;

        if ( $this->PriorityID != 0 )
        {
            $ret = new eZBugPriority( $this->PriorityID );
        }

        return $ret;
    }

    /*!
      Returns the status assigned to the bug as an
      eZBugStatus object.

      If no staus is assigned 0 / false is returned.
    */
    function status()
    {
        $ret = false;
        if ( $this->StatusID != 0 )
        {
            $ret = new eZBugStatus( $this->StatusID );
        }

        return $ret;
    }

    /*!
      Returns the module which the bug is a part of.

      If the bug is not assigned to any module false is returned.
    */
    function module( $IDOnly = false )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $module_array, "SELECT ModuleID
                                          FROM eZBug_BugModuleLink
                                          WHERE BugID='$this->ID'" );

        $ret = false;
        if ( count( $module_array ) == 1 )
        {
            $ret = $IDOnly ? $module_array[0][$db->fieldName( "ModuleID" )] :
                new eZBugModule( $module_array[0][$db->fieldName( "ModuleID" )] );
        }
        return $ret;
    }

    /*!
      Returns the category which the bug is a part of.

      If the bug is not assigned to any category false is returned.
    */
    function category()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $category_array, "SELECT CategoryID
                                            FROM eZBug_BugCategoryLink
                                            WHERE BugID='$this->ID'" );
        $ret = false;
        if ( count( $category_array ) == 1 )
        {
            $ret = new eZBugCategory( $category_array[0][$db->fieldName( "CategoryID" )] );
        }

        return $ret;
    }

    /*!
      Removes the category assignments.
    */
    function removeFromCategories()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res = $db->query( "SELECT Count(*) FROM eZBug_BugCategoryLink
                            WHERE BugID='$this->ID'" );

        if ( $res != false )
        {
            $query = "DELETE FROM eZBug_BugCategoryLink
                            WHERE BugID='$this->ID'";

            $res = $db->query( $query );
        }

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
    }

    /*!
      Removes the module assignments.
    */
    function removeFromModules()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res = $db->query( "DELETE
                            FROM eZBug_BugModuleLink
                            WHERE BugID='$this->ID'" );

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
    }

    /*!
      Searches the bug database and returns the result as an array
      of eZBug objects.

      Default limit is set to 25.
    */
    function search( $query, $offset=0, $limit=25 )
    {
        $db =& eZDB::globalDatabase();

        $link_array = array();
        $return_array = array();

        $query = new eZQuery( array( "Name", "Description" ), $query );

        $query_str =  "SELECT ID FROM eZBug_Bug WHERE (" .
             $query->buildQuery()  .
             ") ORDER BY Name LIMIT $offset, $limit";

        $db->array_query( $bug_array, $query_str );
        $ret = array();

        foreach( $bug_array as $bugItem )
        {
            $ret[] = new eZBug( $bugItem[$db->fieldName( "ID" )] );
        }
        return $ret;
    }

    /*!
      Returns the search count.
    */
    function searchCount( $query )
    {
        $db =& eZDB::globalDatabase();

        $query = new eZQuery( array( "Name", "Description" ), $query );

        $query_str =  "SELECT COUNT(ID) as Count FROM eZBug_Bug WHERE (" .
             $query->buildQuery()  .
             ") ";

        $db->array_query( $bug_array, $query_str );

        return $bug_array[0][ $db->fieldName( "Count" ) ];
    }

    /*!
      Connects the image $image of type eZImage with this bug.
     */
    function addImage( $image )
    {
        if ( is_a( $image, "eZImage" ) )
        {
            $imageID = $image->id();
            $created = (new eZDateTime())->timeStamp( true );
            $db =& eZDB::globalDatabase();
            $db->begin();
            $db->lock( "eZBug_BugImageLink" );
            $nextID = $db->nextID( "eZBug_BugImageLink", "ID" );
            $res = $db->query( "INSERT INTO eZBug_BugImageLink
                                (ID, BugID, ImageID, Created)
                                VALUES ('$nextID','$this->ID','$imageID', '$created')" );
            $db->unlock();

            if ( $res == false )
                $db->rollback();
            else
                $db->commit();
        }
    }

    /*!
      Deletes an eZImage screenshot from the bug.
     */
    function deleteImage( $image )
    {
        if ( is_a( $image, "eZImage" ) )
        {
            $db =& eZDB::globalDatabase();
            $imageID = $image->id();
            $image->delete();
            $db->begin();
            $res = $db->query( "DELETE FROM eZBug_BugImageLink WHERE BugID='$this->ID' AND ImageID='$imageID'" );
            if ( $res == false )
                $db->rollback();
            else
                $db->commit();
        }
    }

    /*!
      Returns all images set to this bug as a array of eZImage objects.
     */
    function images()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $image_array = array();

        $db->array_query( $image_array, "SELECT ImageID FROM eZBug_BugImageLink WHERE BugID='$this->ID' ORDER BY Created" );

        for ( $i = 0; $i < count( $image_array ); $i++ )
        {
            $return_array[$i] = new eZImage( $image_array[$i][$db->fieldName( "ImageID" )], false );
        }
        return $return_array;
    }

    /*!
      Adds an file to the bug.
    */
    function addFile( $file )
    {
        if ( is_a( $file, "eZVirtualFile" ) )
        {
            $db =& eZDB::globalDatabase();

            $fileID = $file->id();
            $db->begin();
            $db->lock( "eZBug_BugFileLink" );
            $nextID = $db->nextID( "eZBug_BugFileLink", "ID" );
            $res = $db->query( "INSERT INTO eZBug_BugFileLink
                                (ID, BugID, FileID)
                                VALUES
                                ('$nextID','$this->ID','$fileID')" );
            $db->unlock();

            if ( $res == false )
                $db->rollback();
            else
                $db->commit();
        }
    }

    /*!
      Deletes an file from the article.
    */
    function deleteFile( $file )
    {
        if ( is_a( $file, "eZVirtualFile" ) )
        {
            $db =& eZDB::globalDatabase();

            $fileID = $file->id();
            $file->delete();
            $db->begin();
            $res = $db->query( "DELETE FROM eZBug_BugFileLink WHERE BugID='$this->ID' AND FileID='$fileID'" );

            if ( $res == false )
                $db->rollback();
            else
                $db->commit();
        }
    }

    /*!
      Returns every file to a article as a array of eZFile objects.
    */
    function files()
    {
        $db =& eZDB::globalDatabase();
        $return_array = array();
        $file_array = array();

        $db->array_query( $file_array, "SELECT FileID FROM eZBug_BugFileLink WHERE BugID='$this->ID' ORDER BY Created" );

       for ( $i = 0; $i < count( $file_array ); $i++ )
       {
           $return_array[$i] = new eZVirtualFile( $file_array[$i][$db->fieldName( "FileID" )], false );
       }
       return $return_array;
    }

    /*!
      \static
      Checks if a bug exists
    */
    function bugExists( $id )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT ID FROM eZBug_Bug WHERE ID='$id'" );
        return ( count( $res ) == 1 );
    }

    var $ID;
    var $Name;
    var $Description;
    var $IsHandled;
    var $IsClosed;
    var $Created;
    var $UserID;
    var $UserEmail;
    var $PriorityID;
    var $StatusID;
    var $OwnerID;
    var $IsPrivate=0;
    var $Version;

}

?>