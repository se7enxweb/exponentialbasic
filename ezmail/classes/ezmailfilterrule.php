<?php
//
// $Id: ezmailfilterrule.php 6657 2001-08-27 10:54:12Z jhe $
//
// eZMailFilterRule class
//
// Created on: <29-Mar-2001 14:20:04 fh>
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
//! eZMailFilterRule documentation.
/*!
  To check mail use the eZMailFilter class.
  Interaction with this class should only be nescasarry if you want to change a filter rule.
  Example code:
  \code
  \endcode

*/

include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "ezmail/classes/ezmail.php" );

/* DEFINES */
define( "FILTER_MESSAGE", 0 );
define( "FILTER_BODY", 1 );
define( "FILTER_ANY", 2 );
define( "FILTER_TOCC", 3 );
define( "FILTER_SUBJECT", 4 );
define( "FILTER_FROM", 5 );
define( "FILTER_TO", 6 );
define( "FILTER_CC", 7 );

define( "FILTER_EQUALS", 0 );
define( "FILTER_NEQUALS", 1 );
define( "FILTER_CONTAINS", 2 );
define( "FILTER_NCONTAINS", 3 );
define( "FILTER_REGEXP", 4 );

class eZMailFilterRule
{
    /*!
      Constructs a new eZMail object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Deletes a eZMail object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        if( $id == -1 )
            $id = $this->ID;

        $db->query( "DELETE FROM eZMail_FilterRule WHERE ID='$id'" );
        return true;
    }

    /*!
      Stores a mail to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $match = $db->escapeString( $this->Match );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZMail_FilterRule" );
            $nextID = $db->nextID( "eZMail_FilterRule", "ID" );
            $result = $db->query( "INSERT INTO eZMail_FilterRule ( ID, UserID, HeaderType, CheckType,
                                    MatchValue, FolderID, IsActive )
                                 VALUES (
                                 '$nextID',
		                         '$this->UserID',
                                 '$this->HeaderType',
                                 '$this->CheckType',
                                 '$match',
                                 '$this->FolderID',
                                 '$this->IsActive' )
                                 " );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $result = $db->query( "UPDATE eZMail_FilterRule SET
		                         UserID='$this->UserID',
                                 HeaderType='$this->HeaderType',
                                 CheckType='$this->CheckType',
                                 MatchValue='$match',
                                 FolderID='$this->FolderID',
                                 IsActive='$this->IsActive'
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
        $ret = false;
        if ( $id != "" )
        {
            $db =& eZDB::globalDatabase();
            $db->array_query( $mail_array, "SELECT * FROM eZMail_FilterRule WHERE ID='$id'" );
            if ( count( $mail_array ) > 1 )
            {
                die( "Error: Mail filters with the same ID where found in the database. This should not happen." );
            }
            else if( count( $mail_array ) == 1 )
            {
                $this->ID =& $mail_array[0][ $db->fieldName("ID") ];
                $this->UserID =& $mail_array[0][ $db->fieldName("UserID") ];
                $this->HeaderType =& $mail_array[0][ $db->fieldName("HeaderType") ];
                $this->CheckType =& $mail_array[0][ $db->fieldName("CheckType") ];
                $this->Match =& $mail_array[0][ $db->fieldName("MatchValue") ];
                $this->FolderID =& $mail_array[0][ $db->fieldName("FolderID") ];
                $this->IsActive =& $mail_array[0][ $db->fieldName("IsActive") ];

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
      Returns the userID of the user that owns this object
    */
    function owner()
    {
        return $this->UserID;;
    }

    /*!
      Sets the owner of this mail
    */
    function setOwner( $newOwner )
    {
        if ( is_a( $newOwner, "eZUser" ) )
            $this->UserID = $newOwner->id();
        else
            $this->UserID = $newOwner;
    }

    /*!
      Returns the header type to check for.
     */
    function headerType()
    {
        return $this->HeaderType;
    }

    /*!
      Sets the header type to check for.
     */
    function setHeaderType( $value )
    {
        $this->HeaderType = $value;
    }

    /*!
      Returns the checktype.
     */
    function checkType()
    {
        return $this->CheckType;
    }

    /*!
      Set the check type.
     */
    function setCheckType( $value )
    {
        $this->CheckType = $value;
    }

    /*!
      Returns the string that is matched.
     */
    function match()
    {
        return $this->Match;
    }

    /*!
      Set the string that the filter is matching.
     */
    function setMatch( $value )
    {
        $this->Match = $value;
    }

    /*!
    Returns 1 if the filter is active.
    */
    function isActive()
    {

        return $this->IsActive;
    }

  /*!
    Sets the account active.
   */
    function setIsActive( $value )
    {
        $this->IsActive = $value;
    }

    /*!
      Returns the folder that this
     */
    function folderID()
    {
        return $this->FolderID;
    }

    /*!
      Sets the folderID of the folder that this filter filters mail into.
     */
    function setFolderID( $value )
    {
        $this->FolderID = $value;
    }

    /*!
      \static

      Returns all mail filters for a selected user as an array of eZMailFilterRules objects.
     */
    static public function getByUser( $user )
    {
        if( !is_string( $user ) && get_class( $user ) == "ezuser" )
            $user = $user->id();

        $database =& eZDB::globalDatabase();

        $return_array = array();
        $account_array = array();

        $database->array_query( $account_array, "SELECT ID FROM eZMail_FilterRule WHERE UserID='$user'" );

        for ( $i=0; $i < count($account_array); $i++ )
        {
            $return_array[$i] = new eZMailFilterRule( $account_array[$i][$database->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      Applies the filter rule to a mail. Returns true if the filter was successfull.

      define( "FILTER_EQUALS", 0 );
      define( "FILTER_NEQUALS", 1 );
      define( "FILTER_CONTAINS", 2 );
      define( "FILTER_NCONTAINS", 3 );
      define( "FILTER_REGEXP", 4 );
     */
    function applyFilter( &$mail )
    {
        // Get the array of elements to search in
        $searchArray =& $this->buildSearchArray( $mail );
        // Loop through the elements and run the required tests. Exit at once when we find a match.
        // This is not good coding practice, but it's the best way to do it here
        switch( $this->CheckType )
        {
            case FILTER_EQUALS :
            {
                foreach( $searchArray as $searchItem )
                {
                    if( $searchItem == $this->Match )
                    {
                        $this->doFilter( $mail );
                        return true;
                    }
                }
            }
            break;
            case FILTER_NEQALS :
            {
                foreach( $searchArray as $searchItem )
                {
                    if( $searchItem == $this->Match )
                        return false;
                }
                $this->doFilter( $mail );
                return true;
            }
            break;
            case FILTER_CONTAINS :
            {
                foreach( $searchArray as $searchItem )
                {
                    $pos = strpos( $searchItem, $this->Match );
                    if( $pos !== false )
                    {
                        $this->doFilter( $mail );
                        return true;
                    }
                }
            }
            break;
            case FILTER_NCONTAINS :
            {
                foreach( $searchArray as $searchItem )
                {
                    $pos = strpos( $searchItem, $this->Match );
                    if( $pos !== false )
                        return false;
                }
                $this->doFilter( $mail );
                return true;
            }
            break;
            case FILTER_REGEXP :
            {
                foreach( $searchArray as $searchItem )
                {
                    if( ereg( $this->Match, $searchItem ) )
                    {
                        $this->doFilter( $mail );
                        return true;
                    }
                }
            }
            break;
        }
        return false;
    }

    /*!
      \private

      Builds an array of data that is to be checked by the apply filter function.
      This function only uses referenced copying of data, to speed things up. (No actuall copy)
    */
    function &buildSearchArray( &$mail )
    {
        $searchArray = array();
        switch( $this->HeaderType )
        {
            case FILTER_MESSAGE :
            {
                $searchArray[] =& $mail->body();
                $searchArray[] =& $mail->to();
                $searchArray[] =& $mail->cc();
                $searchArray[] =& $mail->bcc();
                $searchArray[] =& $mail->from();
                $searchArray[] =& $mail->fromName();
                $searchArray[] =& $mail->subject();
            }
            break;
            case FILTER_BODY :
            {
                $searchArray[] =& $mail->body();
            }
            break;
            case FILTER_ANY :
            {
                $searchArray[] =& $mail->to();
                $searchArray[] =& $mail->cc();
                $searchArray[] =& $mail->bcc();
                $searchArray[] =& $mail->from();
                $searchArray[] =& $mail->fromName();
                $searchArray[] =& $mail->subject();
            }
            break;
            case FILTER_TOCC :
            {
                $searchArray[] =& $mail->to();
                $searchArray[] =& $mail->cc();
            }
            break;
            case FILTER_SUBJECT :
            {
                $searchArray[] =& $mail->subject();
            }
            break;
            case FILTER_FROM :
            {
                $searchArray[] =& $mail->from();
                $searchArray[] =& $mail->fromName();
            }
            break;
            case FILTER_TO :
            {
                $searchArray[] =& $mail->to();
            }
            break;
            case FILTER_CC :
            {
                $searchArray[] =& $mail->cc();
            }
            break;
        }
        return $searchArray;
    }

    /*!
      Moves the mail to the desired folder.
     */
    function doFilter( &$mail )
    {
        $folder = new eZMailFolder( $this->FolderID );

        if( is_a( $folder, "eZMailFolder" ) )
        {
            $folder->addMail( $mail );
        }
        else
        {
            $inbox = eZMailFolder::getSpecialFolder( INBOX );
            $inbox->addMail( $mail );
        }
    }

    var $ID;
    var $UserID;

    // the filter
    var $HeaderType;
    var $CheckType;
    var $Match;

    var $FolderID;
    var $IsActive;
}

//!! eZMail
//! eZMailFilter documentation.
/*!
  Does the filtering of a users mail. If no filters apply the mail is put into the inbox.
  Example code:
  \code
  $filter = new eZMailFilter();
  $filter->runFilters( $mail );
  \endcode

*/

class eZMailFilter
{
    function eZMailFilter( $userID = false )
    {
        if ( $userID == false )
        {
            $user =& eZUser::currentUser();
            $userID = $user->id();
        }
        $this->Filters = eZMailFilterRule::getByUser( $userID );
        $this->Inbox = eZMailFolder::getSpecialFolder( INBOX );
        $this->numFilters = count( $this->Filters );
    }

    function runFilters( &$mail )
    {
        $i = 0;
        $res = false;
        do
        {
//            echo "Running filter\n";
            if ( count( $this->Filters ) > 0 )
                $res = $this->Filters[$i]->applyFilter( $mail );
            $i++;
        }
        while ( $i < $this->$NumFilters && $res == false );

        if ( $res == false )
        {
//            echo "Didn't match\n";
            $this->Inbox->addMail( $mail );
        }
    }

    var $NumFilters;
    var $Filters;
    var $Inbox;
}

?>