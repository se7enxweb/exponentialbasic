<?php
//
// $Id: ezmailfolder.php 7641 2001-10-03 08:44:34Z fh $
//
// eZMailFolder class
//
// Created on: <20-Mar-2001 18:29:11 fh>
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

//!! eZMail
//! eZMailFolder documentation.
/*!

  Example code:
  \code
  \endcode

*/
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/INIFile.php" );

/* DEFINES */
define( "USER", 0 );
define( "INBOX", 1 );
define( "DRAFTS", 2 );
define( "SENT", 3 );
define( "TRASH", 4 );

class eZMailFolder
{
    /*!
      constructor
    */
    function __construct( $id="", $fetch=true )
    {
        // default value
        $this->IsPublished = "false";

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Deletes a eZMailFolder object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id == -1 )
            $id = $this->ID;

        $db->query( "DELETE FROM eZMail_Folder WHERE ID='$id'" );

        $mail = eZMailFolder::mail( "subject", 0, 10000, $id );
        foreach ( $mail as $mailItem )
            $mailItem->delete();
    }

    /*!
      Stores a mail to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        $db->begin();
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZMail_Folder" );
            $nextID = $db->nextID( "eZMail_Folder", "ID" );

            $result = $db->query( "INSERT INTO eZMail_Folder (ID, UserID, ParentID, Name, FolderType )
                                 VALUES (
                                 '$nextID',
		                         '$this->UserID',
		                         '$this->ParentID',
                                 '$name',
                                 '$this->FolderType' )
                                 " );
            $db->unlock();
			$this->ID = $nextID;

        }
        else
        {
            $result = $db->query( "UPDATE eZMail_Folder SET
		                         UserID='$this->UserID',
		                         ParentID='$this->ParentID',
                                 Name='$name',
                                 FolderType='$this->FolderType'
                                 WHERE ID='$this->ID'
                                 " );
        }

        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $account_array, "SELECT * FROM eZMail_Folder WHERE ID='$id'" );
            if ( count( $account_array ) > 1 )
            {
                die( "Error: Mail folders with the same ID was found in the database. This should not happen." );
            }
            else if ( count( $account_array ) == 1 )
            {

                $this->ID = $account_array[0][ $db->fieldName("ID") ];
                $this->UserID = $account_array[0][ $db->fieldName("UserID") ];
                $this->ParentID = $account_array[0][ $db->fieldName("ParentID") ];
                $this->Name = $account_array[0][ $db->fieldName("Name") ];
                $this->FolderType = $account_array[0][ $db->fieldName("FolderType") ];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

   /*!
     Returns the ID of the owner user
    */
    function userID()
    {
        return $this->UserID;
    }

    /*!
      Sets the user that owns this object
    */
    function setUser( $value )
    {
        if ( is_a( $value, "eZUser" ) )
            $value = $value->id();

        $this->UserID = $value;
    }

  /*!
    Returns the name of the folder
  */
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the name of the folder.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Returns the ID of the parent folder.
    */
    function parentID()
    {
        return $this->ParentID;
    }

    /*!
      Sets the parent folder.
    */
    function setParent( $value )
    {
        $this->ParentID = $value;
    }

    /*!
      Returns the type of this folder. Valid types are:
      0 - Normal user created folder
      1 - Inbox
      2 - Outbox
      3 - Sent mail
      4 - Drafts
      5 - Trash
    */
    function folderType()
    {
        return $this->FolderType;
    }

    /*!
      Sets the type of this folder. Valid types are:
      0 - Normal user created folder
      1 - Inbox
      2 - Outbox
      3 - Sent mail
      4 - Drafts
      5 - Trash
    */
    function setFolderType( $value )
    {
        $this->FolderType = $value;
    }

    /*!
      Adds a mail to this folder. Returns true if successfull.
    */
    function addMail( $mail, $removeFromOld = true )
    {
       if ( is_a( $mail, "eZMail" ) )
           $mail = $mail->id();

       $db =& eZDB::globalDatabase();
       $db->begin();
       if ( $removeFromOld == true )
           $results[] = $db->query( "DELETE FROM eZMail_MailFolderLink WHERE MailID='$mail'" );

       $query = "INSERT INTO eZMail_MailFolderLink ( FolderID, MailID ) VALUES
                       ( '$this->ID', '$mail' )";

       $results[] = $db->query( $query );
       $res = in_array( false, $results ) ? false : true;

        if ( $res == false )
        {
            $db->rollback( );
            return false;
        }
        else
            $db->commit();

        return true;
    }

    /*!
      Removes a mail from this folder
     */
    function removeMail( $mail )
    {
       if ( is_a( $mail, "eZMail" ) )
       {
           $mailID = $mail->id();
            $query = "DELETE FROM eZMail_MailFolderLink
                       WHERE FolderID='$this->ID' AND MailID='$mailID'";

           $db =& eZDB::globalDatabase();
           $db->query( $query );
       }
    }

    /*!
      Returns all folders with the folder given as parent.

      The folder are returned as an array of eZMailFolder objects.
    */
    function getByParent( $parent )
    {
      if ( is_a( $parent, "eZMailFolder" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $folder_array = array();

            $parentID = $parent->id();

            $db->array_query( $category_array, "SELECT ID, Name FROM eZMail_Folder
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );

            for ( $i=0; $i < count($category_array); $i++ )
            {
                $return_array[$i] = new eZMailFolder( $folder_array[$i][$db->fieldName("ID")], 0 );
            }

            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      \static
      Returns all folders that belongs to this
      user as an array of eZMailFolders.
     */
    static public function getByUser( $user = false, $withSpecialFolders=false, $parentFolder = -1 )
    {
        if ( !is_a( $user, "eZUser" ) )
            $user =& eZUser::currentUser();

        $noSpecial = "";
        if ( $withSpecialFolders == false )
        {
            $noSpecial = "AND FolderType='0'";
        }
        $parentFolderSQL = "";
        if ( $parentFolder != -1 )
            $parentFolderSQL = "AND ParentID='$parentFolder'";

        $return_array = array();
        $res = array();
        $userid = $user->id();
        $database =& eZDB::globalDatabase();
        $query = "SELECT ID FROM eZMail_Folder WHERE UserID='$userid' $noSpecial $parentFolderSQL";
        $database->array_query( $res, $query );

        for ( $i=0; $i < count($res); $i++ )
        {
            $return_array[$i] = new eZMailFolder( $res[$i][$database->fieldName("ID")] );
        }

        return $return_array;
    }

    /*
      Creates a tree of the folders for the current user.
     */
    static public function &getTree( $parentID=0, $level=0 )
    {
        $folderList = eZMailFolder::getByUser( false, false, $parentID );

        $tree = array();
        $level++;
        foreach ( $folderList as $folder )
        {
//            array_push( $tree, array( new eZMailFolder( $folder->id() ), $level ) );
            array_push( $tree, array( $folder->id(), $folder->name(),  $level ) );

            if ( $folder != 0 )
            {
                $tree = array_merge( $tree, eZMailFolder::getTree( $folder->id(), $level ) );
            }
        }
        return $tree;
    }


    /*!
      Returns all the mail in the folder. $sortmode can be one of the following:
      subject, sender, date, subjectdec, senderdesc, datedesc.
      $offset and $limit sets how many mail to return in one bunch and where in the list to start.
      Static if the folderID is supplied.
     */
    function &mail( $sortmode="subject_asc", $offset=0, $limit=50, $folderID = -1 )
    {
        if ( $folderID == -1 )
            $folderID = $this->ID;

        $orderBySQL = "Mail.UDate ASC";
        switch( $sortmode )
        {
            case "subject_asc" : $orderBySQL = "Mail.Subject ASC"; break;
            case "subject_desc" : $orderBySQL = "Mail.Subject DESC"; break;
            case "date_asc" : $orderBySQL = "Mail.UDate ASC"; break;
            case "date_desc" : $orderBySQL = "Mail.UDate DESC"; break;
            case "from_asc" : $orderBySQL = "Mail.FromField ASC"; break;
            case "from_desc" : $orderBySQL = "Mail.FromField DESC"; break;
            case "size_asc" : $orderBySQL = "Mail.Size ASC"; break;
            case "size_desc" : $orderBySQL = "Mail.Size DESC"; break;
        }

        $db =& eZDB::globalDatabase();
        $query = "SELECT Mail.ID FROM eZMail_Mail AS Mail, eZMail_MailFolderLink AS Link
                  WHERE Mail.ID=Link.MailID AND Link.FolderID='$folderID'
                  ORDER BY $orderBySQL";

        $mail_array = array();
        $return_array = array();
        $db->array_query( $mail_array, $query, array( "Limit" => $limit, "Offset" => $offset ) );
        for ( $i=0; $i < count($mail_array); $i++ )
        {
            $return_array[$i] = new eZMail( $mail_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      Returns the number of mail in this folder
     */
    function mailCount()
    {
        $db =& eZDB::globalDatabase();
        $query = "SELECT Count( Mail.ID ) as Count FROM eZMail_Mail AS Mail, eZMail_MailFolderLink AS Link
                  WHERE Mail.ID=Link.MailID AND Link.FolderID='$this->ID'";
        $db->query_single( $result, $query );
        return $result[$db->fieldName("Count")];
    }

    /*!
      Deletes all mail in the folder
     */
    function deleteAll()
    {
        $db =& eZDB::globalDatabase();
        $query = "SELECT Mail.ID FROM eZMail_Mail AS Mail, eZMail_MailFolderLink AS Link
                  WHERE Mail.ID=Link.MailID AND Link.FolderID='$this->ID'";

        $db->array_query( $mail_array, $query );
        for ( $i=0; $i < count($mail_array); $i++ )
            eZMail::delete( $mail_array[$i][$db->fieldName("ID")] );
    }

    /*!
      Returns the number for mail in the folder. If $unreadOnly is set to true the function returns the number of unread mails.
      If you specify the folderID this function can be used as an static function.
     */
    function count( $unreadOnly = false, $folderID =-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $folderID == -1 )
            $folderID = $this->ID;

        $unreadSQL = "";
        if ( $unreadOnly == true )
            $unreadSQL = "AND Mail.Status='0'";

        $db->query_single( $res, "SELECT count( Mail.ID ) as Count from eZMail_Mail as Mail,
                                                eZMail_MailFolderLink as Link
                                                WHERE Mail.ID=Link.MailID AND Link.FolderID='$folderID' $unreadSQL" );
        return $res[$db->fieldName("Count")];
    }

    /*
      \static

      Returns the requested special folder of the current user or the user specified. Valid folders are:
      INBOX
      SENT
      DRAFTS
      TRASH
      If the folder does not exist it will be created. If the creation should fail the function returns false.
     */
    static public function getSpecialFolder( $specialType, $user=false )
    {
        if ( !is_a( $user, "eZUser" ) )
            $user =& eZUser::currentUser();

        $userid = $user->id();

        if ( $userid == 0 )
            return false;

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT ID FROM eZMail_Folder WHERE FolderType='$specialType' AND UserID='$userid'" );

//        echo $res[$db->fieldName( "ID" )];
        if ( $res && $res[$db->fieldName( "ID" )] != "" )
            return new eZMailFolder(  $res[$db->fieldName( "ID" )] );

        $ini =& eZINI::instance( 'site.ini' );
        $Language = $ini->variable( "eZMailMain", "Language" );
        $folderNameIni = new eZINI( "kernel/ezmail/user/intl/" . $Language . "/folderlist.php.ini" );
        switch( $specialType )
        {
            case 1 :
                $folderName = $folderNameIni->variable( "strings", "inbox" );
                break;
            case 2 :
                $folderName =  $folderNameIni->variable( "strings", "sent" );
                break;
            case 3 :
                $folderName =  $folderNameIni->variable( "strings", "drafts" );
                break;
            case 4 :
                $folderName =  $folderNameIni->variable( "strings", "trash" );
                break;
            default:
                return false;
                break;
        }

        $db->begin();
        $db->lock( "eZMail_Folder" );
        $nextID = $db->nextID( "eZMail_Folder", "ID" );

        $result = $db->query( "INSERT INTO eZMail_Folder (ID, FolderType, UserID, ParentID, Name )
                   VALUES (
                          '$nextID',
                          '$specialType',
                          '$userid',
                          '0',
                          '$folderName' )
                     " );

        $db->unlock();
        if ( $result == false )
        {
            $db->rollback( );
            return false;
        }
        else
            $db->commit();

        return new eZMailFolder( $nextID );
    }

    /*!
      Returns true if the given folder is a child (doesn't have to be first level) of this folder.
      If the second parameter is set to true, the function also checks if the folder given is itself.
     */
    function isChild( $folderID, $check_for_self = false )
    {
        $return_value = false;
        $db =& eZDB::globalDatabase();

        if ( is_a( $folderID, "eZMailFolder" ) )
            $folderID = $folderID->id();

        if ( $check_for_self == true && $folderID == $this->ID )
            return true;

        while ( $folderID != 0 )
        {
            $db->query_single( $result, "SELECT ParentID FROM eZMail_Folder WHERE ID='$folderID'" );
            $folderID = $result[$db->fieldName("ParentID")];
            if ( $folderID == $this->ID )
                return true;
        }
        return $return_value;
    }

    /*!
      \static

      Returns true if the given mail belongs to the given user.
     */
    static public function isOwner( $user, $folderID )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZMail_Folder WHERE ID='$folderID'" );
        if ( $res[$db->fieldName( "UserID" )] == $user )
            return true;

        return false;
    }


    var $ID;
    var $UserID;
    var $ParentID;
    var $Name;
    var $FolderType=0;
    var $IsPublished;
}

?>