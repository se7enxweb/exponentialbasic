<?php
//
// $Id: ezmailaccount.php 6657 2001-08-27 10:54:12Z jhe $
//
// eZMailAccount class
//
// Created on: <19-Mar-2001 17:58:38 fh>
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
//! eZMailAccount documentation.
/*!

  Example code:
  \code
  \endcode

*/

/* ATTENTION:
   Lots of this code is build upon imap specific features. If you want to understand how this stuff works
   you can find info here www.faqs.org about the protocols:
    RFC821: Simple Mail Transfer Protocol (SMTP).
    RFC822: Standard for ARPA internet text messages.
    RFC2060: Internet Message Access Protocol (IMAP) Version 4rev1.
    RFC1939: Post Office Protocol Version 3 (POP3).
    RFC977: Network News Transfer Protocol (NNTP).
    RFC2076: Common Internet Message Headers.
    RFC2045 , RFC2046 , RFC2047 , RFC2048 & RFC2049: Multipurpose Internet Mail Extensions (MIME).

    To understand the return types from the imap functions take a look at the mail.h file in the mime c-klient library
    found here: ftp://ftp.cac.washington.edu/imap
 */

include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfunctions.php" );
include_once( "ezmail/classes/ezmailfilterrule.php" );
include_once( "classes/ezhttptool.php" );

class eZMailAccount
{
    /*!
      constructor
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
      Deletes a eZMailAccount object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id == -1 )
        {
            $db->query( "DELETE FROM eZMail_Account WHERE ID='$this->ID'" );
        }
        else
        {
            $db->query( "DELETE FROM eZMail_Account WHERE ID='$id'" );
        }
        return true;
    }

    /*!
      Stores a mail to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $name = $db->escapeString( $this->Name );
        $loginname = $db->escapeString( $this->LoginName );
        $password = $db->escapeString( $this->Password );
        $server = $db->escapeString( $this->Server );
        $db->begin();
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZMail_Account" );
            $nextID = $db->nextID( "eZMail_Account", "ID" );
            $result = $db->query( "INSERT INTO eZMail_Account ( ID, UserID, Name, LoginName, Password,
                                 Server, DeleteFromServer, IsActive, ServerType, ServerPort )
                                 VALUES (
                                 '$nextID',
		                         '$this->UserID',
                                 '$name',
                                 '$loginname',
                                 '$password',
                                 '$server',
                                 '$this->DeleteFromServer',
                                 '$this->IsActive',
                                 '$this->ServerType',
                                 '$this->ServerPort' )
                                 " );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $result = $db->query( "UPDATE eZMail_Account SET
		                         UserID='$this->UserID',
                                 Name='$name',
                                 LoginName='$loginname',
                                 Password='$password',
                                 Server='$server',
                                 DeleteFromServer='$this->DeleteFromServer',
                                 IsActive='$this->IsActive',
                                 ServerType='$this->ServerType',
                                 ServerPort='$this->ServerPort'
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
            $db->array_query( $account_array, "SELECT * FROM eZMail_Account WHERE ID='$id'" );
            if ( count( $account_array ) > 1 )
            {
                die( "Error: Mail accounts with the same ID was found in the database. This should not happen." );
            }
            else if ( count( $account_array ) == 1 )
            {

                $this->ID =& $account_array[0][$db->fieldName( "ID" )];
                $this->UserID =& $account_array[0][$db->fieldName( "UserID" )];
                $this->Name =& $account_array[0][$db->fieldName( "Name" )];
                $this->LoginName =& $account_array[0][$db->fieldName( "LoginName" )];
                $this->Password =& $account_array[0][$db->fieldName( "Password" )];
                $this->Server =& $account_array[0][$db->fieldName( "Server" )];
                $this->DeleteFromServer =& $account_array[0][$db->fieldName( "DeleteFromServer" )];
                $this->IsActive =& $account_array[0][$db->fieldName( "IsActive" )];
                $this->ServerType =& $account_array[0][$db->fieldName( "ServerType" )];
                $this->ServerPort =& $account_array[0][$db->fieldName( "ServerPort" )];

                $ret = true;
            }
        }
        return $ret;
    }

    /*
      Returns the ID of this object.
     */
    function id()
    {
        return $this->ID;
    }

  /*!
    Returns the ID of the account owner
    */
    function userID()
    {
        return $this->UserID;
    }

    /*!
      Sets the account owner
    */
    function setUserID( $value )
    {
        $this->UserID = $value;
    }

    /*!
      Sets the account owner with a user.
     */
    function setUser( $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $this->UserID = $user->id();
    }

  /*!
    Returns the name of the account.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the name of the account.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

  /*!
    Returns the login name for the account.
    */
    function loginName()
    {
        return $this->LoginName;
    }

    /*!
      Sets the login name for the account.
    */
    function setLoginName( $value )
    {
        $this->LoginName = $value;
    }


  /*!
    Returns the password for the account.
   */
    function password()
    {
        return $this->Password;
    }

    /*!
    Sets the password for the account.
    TODO: encrypt it.
    */
    function setPassword( $value )
    {
        $this->Password = $value;
    }

  /*!
    Returns the server for the account.
    */
    function server()
    {
        return $this->Server;
    }

    /*!
      Sets the server for this account.
    */
    function setServer( $value )
    {
        $this->Server = $value;
    }

    /*!
      Returns the server port for this account.
     */
    function serverPort()
    {
        return $this->ServerPort;
    }

    /*!
      Sets the server port for this account.
     */
    function setServerPort( $value )
    {
        $this->ServerPort = $value;
    }

  /*!
    Returns 1 if mail gets deleted from server after download. 0 If not.
    */
    function deleteFromServer()
    {
        return $this->DeleteFromServer;
    }

    /*!
      1- Mail gets deleted from server after download
      0-Leaves the mail alone.
    */
    function setDeleteFromServer( $value )
    {
        $this->DeleteFromServer = $value;
    }

    /*!
      Returns 1 if the account is active. Inactive accounts should not be checked.
    */
    function isActive()
    {
        return $this->IsActive;
    }

    /*!
      Sets the account active. Inactive accounts will not be checked.
   */
    function setIsActive( $value )
    {
        $this->IsActive = $value;
    }

    /*!
      Returns the server type..Not used at the moment.
    */
    function serverType()
    {
        return $this->ServerType;
    }

  /*!
    Sets the server type. Not used at the moment.
    */
    function setServerType( $value )
    {
        $this->ServerType = $value;
    }

    /*!
      \static

      Returns true if the given account belongs to the given user.
     */
    function isOwner( $user, $accountID )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZMail_Account WHERE ID='$accountID'" );
        if ( $res[$db->fieldName("UserID")] == $user )
            return true;

        return false;
    }

    /*!
      \static

      Returns all mail accounts for a selected user as an array of eZMailAccount objects.
     */
    function getByUser( $user )
    {
        if ( is_a( $user, "eZUser" ) )
            $user = $user->id();

        $db =& eZDB::globalDatabase();

        $return_array = array();
        $account_array = array();

        $db->array_query( $account_array, "SELECT ID FROM eZMail_Account WHERE UserID='$user'" );

        for ( $i = 0; $i < count( $account_array ); $i++ )
        {
            $return_array[$i] = new eZMailAccount( $account_array[$i][$db->fieldName( "ID" )] );
        }

        return $return_array;
    }

    /*!
      Checks if there is any new mail in this accounts. Downloads according to the setup, and filters it
      to the correct folder if filters are enabled. Currently this function only works for pop3.
     */
    function checkMail()
    {
        $user =& eZUser::currentUser();
        $server = "{" . $this->Server . "/pop3:" .$this->ServerPort ."}";
        $mbox = imap_open( $server, $this->LoginName, $this->Password, OP_HALFOPEN);
        if ( $mbox == false )
        {
            $errorMsg = rawurlencode( imap_last_error() );
            eZHTTPTool::header( "Location: /error/error?Info=$errorMsg" );
            exit();
        }

        //debug!!!!
        //        $struct = imap_fetchstructure( $mbox, 1 );
        //        echo "<pre>"; print_r( $struct ); echo "</pre>";
        //        exit;

        // get the inbox... we will be adding mail to this.
        $inbox = eZMailFolder::getSpecialFolder( INBOX );
        $filters = new eZMailFilter();

        $num = imap_num_msg( $mbox );         // fetch numbers of all new mails
        for ( $i = 1; $i <= $num; $i++ )  // go through each mail in inbox
        {
            $headerinfo = imap_header( $mbox, $i );           // fetch mail headers
            if ( !eZMail::isDownloaded( $headerinfo->message_id, $user->id() ) )             // check if allready downloaded
            {
                $mail = new eZMail();
                $mail->setOwner( $user );
                $mail->setStatus( UNREAD );
                $mail->setUDate( $headerinfo->udate );

                getHeaders( $mail, $mbox, $i ); // fetch header information
                $mail->store(); // to get ID

                set_time_limit( 20 );
                $mailstructure = imap_fetchstructure( $mbox, $i );
                disectThisPart( $mailstructure, "1", $mbox, $i, $mail );
                $mail->setSize( $mailstructure->bytes );

                $mail->store();
                $mail->markAsDownloaded();
                $inbox->addMail( $mail ); // safety for now while we debug the filters
                $filters->runFilters( $mail );

                if ( $this->DeleteFromServer == true )
                    imap_delete( $mbox, $i );
            }
        }

//        $headers = imap_headers( $mbox );
//        print("<pre>"); print_r( $headers ); print("</pre>" );
        imap_close( $mbox, CL_EXPUNGE );
    }


    /*!
      \Static
      Gets all new mails, removes from server
     */
    function getNewMail( $LoginName, $Password, $ServerName, $ServerPort = 110 )
    {
        $server = "{" . $ServerName . "/pop3:" .$ServerPort ."}";
        $mbox = imap_open( $server, $LoginName, $Password, OP_HALFOPEN );
        if ( $mbox == false )
        {
            $errorMsg = rawurlencode( imap_last_error() );
            eZHTTPTool::header( "Location: /error/error?Info=$errorMsg" );
            exit();
        }
        $ret_array = array();
        // get the inbox... we will be adding mail to this.
//        $inbox = eZMailFolder::getSpecialFolder( INBOX );
//        $filters = new eZMailFilter();

        // fetch numbers of all new mails
        $num = imap_num_msg( $mbox );

        // go through each mail in inbox
        for ( $i = 1; $i <= $num; $i++ )
        {
            // fetch mail headers
            $headerinfo = imap_header( $mbox, $i );

            $mail = new eZMail();
            $mail->setOwner( $user );
            $mail->setStatus( UNREAD );
            $mail->setUDate( $headerinfo->udate );

            getHeaders( $mail, $mbox, $i ); // fetch header information
            $mail->store(); // to get ID

            $mailstructure = imap_fetchstructure( $mbox, $i );
            disectThisPart( $mailstructure, "1", $mbox, $i, $mail );
            $mail->setSize( $mailstructure->bytes );

            $mail->store();
            $mail->markAsDownloaded();
            $ret_array[] = $mail;
            imap_delete( $mbox, $i );
        }

//        $headers = imap_headers( $mbox );
//        print("<pre>"); print_r( $headers ); print("</pre>" );
        imap_close( $mbox, CL_EXPUNGE );
        return $ret_array;
    }


    var $ID;
    var $UserID;
    var $Name;
    var $LoginName;
    var $Password;
    var $Server;
    var $ServerPort;
    var $DeleteFromServer;
    var $IsActive;
    var $ServerType;
}

?>