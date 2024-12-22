<?php
//
// $Id: ezforummessage.php 9145 2002-02-05 10:39:07Z jhe $
//
// Definition of eZForumMessage class
//
// Created on: <11-Sep-2000 22:10:06 bf>
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

//!! eZForum
//! The eZForumMessage handles a forum message in the database.
/*!
  Handles messages in forums..
*/

/*!TODO
*/

// include_once( "classes/ezdatetime.php" );
// include_once( "ezuser/classes/ezuser.php" );

class eZForumMessage
{
    /*!
      Constructs a new eZForumMessage object.
    */
    function __construct( $id = "" )
    {
        $this->IsApproved = true;
        $this->IsTemporary = false;

        $this->ParentID = 0;

        if ( $id != "" )
        {
            $this->get( $id );
        }
    }

    /*!
      Stores a eZForumMessage object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin( );
        if ( !isset( $this->ID ) )
        {
            if ( $this->ParentID == 0 )
            { // new node

                $db->lock( "eZForum_Message" );
                $nextID = $db->nextID( "eZForum_Message", "ID" );

                $timeStamp = new eZDateTime();
                $timeStamp = $timeStamp->timeStamp( true );

                // find the biggest treeID
                $db->array_query( $result, "SELECT TreeID FROM eZForum_Message ORDER BY TreeID DESC", array( "Limit" => 1 ) );

                $this->Depth = 0;
                if ( count( $result ) > 0 )
                {
                    $this->TreeID = $result[0][$db->fieldName( "TreeID" )] + 1;
                }
                else
                {
                    $this->TreeID = 0;
                }

                // get the biggest thread ID
                $db->array_query( $result,
                "SELECT ThreadID, TreeID FROM eZForum_Message WHERE Parent='0' ORDER BY TreeID DESC",
                array( "Limit" => 1 ) );

                if ( count( $result ) > 0 )
                {
                    $this->ThreadID = $result[0][$db->fieldName( "ThreadID" )] + 1;
                }
                else
                {
                    $this->ThreadID = 0;
                }
                $topic = $db->escapeString( $this->Topic );
                $body = $db->escapeString( $this->Body );

                $res = $db->query( "INSERT INTO eZForum_Message
		                         ( ID,
                                   ForumID,
                                   Topic,
                                   Body,
                                   UserID,
                                   Parent,
                                   TreeID,
                                   ThreadID,
                                   Depth,
                                   EmailNotice,
                                   IsApproved,
                                   IsTemporary,
                                   PostingTime,
                                   UserName )
                                 VALUES
                                 ( '$nextID',
                                   '$this->ForumID',
                                   '$topic',
                                   '$body',
                                   '$this->UserID',
                                   '$this->ParentID',
                                   '$this->TreeID',
                                   '$this->ThreadID',
                                   '$this->Depth',
                                   '$this->EmailNotice',
                                   '$this->IsApproved',
                                   '$this->IsTemporary',
                                   '$timeStamp',
                                   '$this->UserName' )
                                 " );

				$this->ID = $nextID;
            }
            else
            { // child node

                // find the TreeID, ThreadID and Depth of the parent
                $db->array_query( $result, "SELECT TreeID, ThreadID, Depth FROM eZForum_Message
                                                        WHERE ID='$this->ParentID'
                                                        ORDER BY TreeID DESC", array( "Limit" => 1 ) );

                if ( count( $result ) == 1 )
                {
                    $db->lock( "eZForum_Message" );
                    $nextID = $db->nextID( "eZForum_Message", "ID" );

                    $timeStamp = new eZDateTime();
                    $timeStamp = $timeStamp->timeStamp( true );

                    $parentID = $result[0][$db->fieldName( "TreeID" )];
                    $this->TreeID =  $parentID;

                    $this->ThreadID = $result[0][$db->fieldName( "ThreadID" )];

                    $d = $result[0][$db->fieldName( "Depth" )];
                    setType( $d, "integer" );

                    $this->Depth = $d + 1;

                    // update the whole tree''s ThreeID.
                    $db->query( "UPDATE eZForum_Message SET TreeID=( TreeID + 1 ), PostingTime=PostingTime WHERE TreeID >= $parentID" );

                    $bodySlash = $db->escapeString( $this->Body );
                    $topicSlash = $db->escapeString( $this->Topic );

                    $res = $db->query( "INSERT INTO eZForum_Message
		                         ( ID,
                                   ForumID,
                                   Topic,
                                   Body,
                                   UserID,
                                   Parent,
                                   TreeID,
                                   ThreadID,
                                   Depth,
                                   EmailNotice,
                                   IsApproved,
                                   IsTemporary,
                                   PostingTime,
                                   UserName )
                                 VALUES
                                 ( '$nextID',
                                   '$this->ForumID',
                                   '$topicSlash',
                                   '$bodySlash',
                                   '$this->UserID',
                                   '$this->ParentID',
                                   '$this->TreeID',
                                   '$this->ThreadID',
                                   '$this->Depth',
                                   '$this->EmailNotice',
                                   '$this->IsApproved',
                                   '$this->IsTemporary',
                                   '$timeStamp',
                                   '$this->UserName' )
                                 " );
					$this->ID = $nextID;
                }
                else
                {
                    print( "<b>ERROR:</b> eZForumMessage::store() parent not found in database.<br /> \n" );
                }
            }
        }
        else
        {
            $bodySlash = $db->escapeString( $this->Body );
            $topicSlash = $db->escapeString( $this->Topic );

            $res = $db->query( "UPDATE eZForum_Message SET
		                         ForumID='$this->ForumID',
		                         Topic='$topicSlash',
		                         Body='$bodySlash',
		                         UserID='$this->UserID',
		                         Parent='$this->ParentID',
		                         EmailNotice='$this->EmailNotice',
		                         IsApproved='$this->IsApproved',
		                         IsTemporary='$this->IsTemporary',
                                 PostingTime=PostingTime,
                                 UserName='$this->UserName'
                                 WHERE ID='$this->ID'
                                 " );
        }

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
        {
            $db->commit();
            $this->createIndex();
        }


        return true;
    }

    /*!
      Deletes a eZForumCategory object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZForum_Message WHERE ID='$this->ID'" );
        $db->query( "DELETE FROM eZForum_MessageWordLink WHERE MessageID='$this->ID'" );
        return true;
    }


    /*!
      Clones this eZForumMessage object.
    */
    function &cloneObject()
    {
        $tmp = new eZForumMessage( $this->ID );
        unset( $tmp->ID );
        return $tmp;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = "" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        if ( $id != "" )
        {
            $timeStamp = new eZDateTime();
            $timeStamp = $timeStamp->timeStamp( true );

            $db->array_query( $message_array, "SELECT *,
                             ( $timeStamp  - PostingTime ) AS Age
                              FROM eZForum_Message WHERE ID='$id'" );
            if ( count( $message_array ) > 1 )
            {
                die( "Error: Message's with the same ID was found in the database. This shouldn't happen." );
            }
            else if ( count( $message_array ) == 1 )
            {
                $this->ID =& $message_array[0][$db->fieldName( "ID" )];
                $this->ForumID =& $message_array[0][$db->fieldName( "ForumID" )];
                $this->Topic =& $message_array[0][$db->fieldName( "Topic" )];
                $this->Body =& $message_array[0][$db->fieldName( "Body" )];
                $this->UserID =& $message_array[0][$db->fieldName( "UserID" )];
                $this->ParentID =& $message_array[0][$db->fieldName( "Parent" )];
                $this->PostingTime =& $message_array[0][$db->fieldName( "PostingTime" )];
                $this->EmailNotice =& $message_array[0][$db->fieldName( "EmailNotice" )];

                $this->IsApproved =& $message_array[0][$db->fieldName( "IsApproved" )];
                $this->IsTemporary =& $message_array[0][$db->fieldName( "IsTemporary" )];

                $this->ThreadID =& $message_array[0][$db->fieldName( "ThreadID" )];
                $this->TreeID =& $message_array[0][$db->fieldName( "TreeID" )];
                $this->Depth =& $message_array[0][$db->fieldName( "Depth" )];

                $this->Age =& $message_array[0][$db->fieldName( "Age" )];
                $this->UserName =& $message_array[0][$db->fieldName( "UserName" )];

                $ret = true;
            }
            else if ( count( $message_array ) == 0 )
            {
                $this->ID = 0;
                $ret = false;
            }
        }
        return $ret;
    }

    /*!
      Returns every message as an array of eZForumCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        $ret = array();

        $db->array_query( $message_array, "SELECT ID FROM
                                           eZForum_Message" );

        $ret = array();

        foreach ( $message_array as $message )
        {
            $ret[] = new eZForumMessage( $message[$db->fieldName( "ID" )] );
        }

        return $ret;
    }


    /*!
      \private
      will index the article keywords (fetched from Contents) and name for fulltext search.
    */
    function createIndex()
    {
        // generate keywords
        $tmpContents = $this->Body;

        $contents = strtolower( strip_tags( $tmpContents ) ) . " " . $this->Topic;
        $contents = str_replace ("\n", "", $contents );
        $contents = str_replace ("\r", "", $contents );
        $contents = str_replace ("(", " ", $contents );
        $contents = str_replace (")", " ", $contents );
        $contents = str_replace (",", " ", $contents );
        $contents = str_replace (".", " ", $contents );
        $contents = str_replace ("/", " ", $contents );
        $contents = str_replace ("-", " ", $contents );
        $contents = str_replace ("_", " ", $contents );
        $contents = str_replace ("\"", " ", $contents );
        $contents = str_replace ("'", " ", $contents );
        $contents = str_replace (":", " ", $contents );
        $contents = str_replace ("?", " ", $contents );
        $contents = str_replace ("!", " ", $contents );
        $contents = str_replace ("\"", " ", $contents );
        $contents = str_replace ("|", " ", $contents );
        $contents = str_replace ("qdom", " ", $contents );
        $contents = str_replace ("tech", " ", $contents );

        // strip &quot; combinations
        $contents = preg_replace("(&.+?;)", " ", $contents );

        // strip multiple whitespaces
        $contents = preg_replace("(\s+)", " ", $contents );

        $contents_array =& preg_split( "/ /", $contents );

        $totalWordCount = count( $contents_array );
        $wordCount = array_count_values( $contents_array );

        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            if ( strlen( $word ) >= 2 )
            {
                $keywords .= $word . " ";
            }
        }

        $this->Keywords = $keywords;

        $db =& eZDB::globalDatabase();
        $ret = array();

        $ret[] = $db->query( "DELETE FROM  eZForum_MessageWordLink WHERE MessageID='$this->ID'" );

        // get total number of messages
        $db->array_query( $message_array, "SELECT COUNT(*) AS Count FROM eZForum_Message" );
        $messageCount = $message_array[0][$db->fieldName( "Count" )];

        foreach ( $contents_array as $word )
        {
            if ( strlen( $word ) >= 2 )
            {
                $indexWord = $word;

                $indexWord = $db->escapeString( $indexWord );

                $db->begin( );

                // find the frequency
                $count = $wordCount[$indexWord];

                $freq = ( $count / $totalWordCount );

                $query = "SELECT ID FROM eZForum_Word
                      WHERE Word='$indexWord'";

                $db->array_query( $word_array, $query );


                if ( count( $word_array ) == 1 )
                {
                    // word exists create reference
                    $wordID = $word_array[0][$db->fieldName("ID")];

                    // number of links to this word
                    $db->array_query( $message_array, "SELECT COUNT(*) AS Count FROM eZForum_MessageWordLink WHERE WordID='$wordID'" );
                    $wordUsageCount = $message_array[0][$db->fieldName( "Count" )];

                    if ( $messageCount != 0 )
                        $wordFreq = ( $wordUsageCount + 1 )  / $messageCount;

                    // update word frequency
                    $ret[] = $db->query( "UPDATE  eZForum_Word SET Frequency='$wordFreq' WHERE ID='$wordID'" );


                    $ret[] = $db->query( "INSERT INTO eZForum_MessageWordLink ( MessageID, WordID, Frequency ) VALUES
                                      ( '$this->ID',
                                        '$wordID',
                                        '$freq' )" );
                }
                else
                {
                    // lock the table
                    $db->lock( "eZForum_Word" );

                    if ( $messageCount != 0 )
                        $wordFreq = 1 / $messageCount;

                    // new word, create word
                    $nextID = $db->nextID( "eZForum_Word", "ID" );
                    $ret[] = $db->query( "INSERT INTO eZForum_Word ( ID, Word, Frequency ) VALUES
                                      ( '$nextID',
                                        '$indexWord',
                                        '$wordFreq' )" );
                    $db->unlock();

                    $ret[] = $db->query( "INSERT INTO eZForum_MessageWordLink ( MessageID, WordID, Frequency ) VALUES
                                      ( '$this->ID',
                                        '$nextID',
                                        '$freq' )" );

                }
            }
        }
        eZDB::finish( $ret, $db );
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the forum id.
    */
    function forumID()
    {
        return $this->ForumID;
    }

    /*!

    */
    function setForumID( $newForumID )
    {
        $this->ForumID = $newForumID;
    }

    /*!
      Sets the message to be approved or not.
    */
    function setIsApproved( $value )
    {
       if ( $value == true )
           $this->IsApproved = 1;
       else
           $this->IsApproved = 0;
    }


    /*!
      Sets the message to be temporary or not.
    */
    function setIsTemporary( $value )
    {
       if ( $value == true )
           $this->IsTemporary = 1;
       else
           $this->IsTemporary = 0;
    }


    /*!
      Returns true if the message is approved.
    */
    function isApproved()
    {
       if ( $this->IsApproved == 1 )
           return true;
       else
           return false;
    }

    /*!
      Returns true if the message is a temporary item.
    */
    function isTemporary()
    {
       if ( $this->IsTemporary == 1 )
           return true;
       else
           return false;
    }

    /*!
      Returns the parent message.

      If the message is a top level message false is returned.
    */
    function &parent()
    {
       $ret = false;

       if ( $this->ParentID != 0 )
           $ret = new eZForumMessage( $this->ParentID );

       return $ret;
    }

    /*!

    */
    function setParent( $newParent )
    {
        $this->ParentID = $newParent;
    }

    /*!
      Returns the topic of the message.
    */
    function &topic( $htmlchars = true )
    {
       if ( !is_null( $this->Topic ) && $htmlchars == true )
       {
            return htmlspecialchars( $this->Topic );
       }
       else
       {
            return $this->Topic;
       }
    }

    /*!
      Same as topic()
    */
    function name()
    {
        return $this->topic();
    }

    /*!
      Sets the message topic.
    */
    function setTopic( &$newTopic )
    {
        $this->Topic = $newTopic;
    }


    /*!
      Returns the body of the forum message.
    */
    function &body()
    {
        return $this->Body;
    }

    /*!
      Sets the body contents.
    */
    function setBody( &$newBody )
    {
        $this->Body = $newBody;
    }

    /*!
      Returns the user id.
    */
    function userID()
    {
        return $this->UserID;
    }

    function userName()
    {

        if ( isset( $this->UserName ) )
            return $this->UserName;
        else
            return false;
    }

    /*!
      Returns the number of seconds since the message was posted.
    */
    function age()
    {
        return $this->Age;
    }

    /*!
      Returns the user as a eZUser object.
    */
    function &user()
    {
       $owner = new eZUser( $this->UserID );

       return $owner;
    }

    function setUserName( $username )
    {
        $this->UserName = $username;
    }

    /*!
      Sets the user id.
    */
    function setUserID( $newUserID )
    {
        $this->UserID = $newUserID;
    }

    /*!
      Returns true if the poster should receive email notice.
    */
    function emailNotice()
    {
       $ret = false;
       if ( $this->EmailNotice == 1 )
           $ret = true;

       return $ret;
    }

    /*!
      Set to true if the po
    */
    function setEmailNotice( $newEmailNotice )
    {
        if ( $newEmailNotice == true )
            $this->EmailNotice = 1;
        else
            $this->EmailNotice = 0;
    }

    /*!
      Returns the depth of the message.
    */
    function depth()
    {
       return $this->Depth;
    }

    /*!
      Enabled email notice.
    */
    function enableEmailNotice()
    {
        $this->setEmailNotice( 1 );
    }

    /*!
      Disables email notice.
    */
    function disableEmailNotice()
    {
        $this->setEmailNotice( 0 );
    }


    /*!
      Returns the postimg time as a eZTimeDate object.
    */
    function &postingTime()
    {
       $dateTime = new eZDateTime();

       $dateTime->setTimeStamp( $this->PostingTime );

       return $dateTime;
    }

    /*!
      Returns the threadID. Each new posting to a forum creates
      a new thread. Every reply to that message belongs to the
      same thread.
    */
    function threadID()
    {
       return $this->ThreadID;
    }

    /*!
      Returns the treeID. The tree id is an integer which
      indicates the position of the message in the forum.
      Higher number is newer/higher up in the tree. 0 is the
      first message.
    */
    function treeID()
    {
       return $this->TreeID;
    }

    /*!
      Returns the number of messages in the given thread.
    */
    static public function threadMessageCount( $threadID )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $message_array,
                          "SELECT COUNT(*) AS Count
                           FROM eZForum_Message
                           WHERE ThreadID='$threadID'
                           AND IsTemporary='0'
                           AND IsApproved='1'" );

        return $message_array[0][$db->fieldName( "Count" )];
    }


    /*!
      Returns the number of messages.
    */
    static public function countMessages( $ID )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $message_array,
                          "SELECT COUNT(ID) AS Messages
                           FROM eZForum_Message
                           WHERE ForumID='$ID'
                           AND Parent IS NULL AND IsTemporary='0'" );

        return $message_array[0][$db->fieldName( "Messages" )];
    }

    /*!

     */
    static public function countReplies( $ID )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $message_array,
        "SELECT COUNT(ID) AS Replies FROM eZForum_Message WHERE Parent='$ID' AND IsTemporary='0'" );

        return $message_array[0][$db->fieldName( "Replies" )];
    }

    /*!
      Returns the first message in a thread as a eZForumMessage object.

      *warning* This function is recursive!
     */
    function threadTop( &$msg )
    {
        $db =& eZDB::globalDatabase();

        $ret = 0;

        if ( $msg->parent() != 0 )
        {
            $parent =& $msg->parent();
            $ret = $this->threadTop( $parent  );
        }
        else
        {
            $ret = $msg;
        }

        return $ret;
    }

    /*!
      Get all the messages which is not approved
    */
    function getAllUnApproved( $Offset=0, $Limit=10 )
    {
        $db =& eZDB::globalDatabase();

        $ret = array();

        $db->array_query( $message_array, "SELECT ID FROM eZForum_Message WHERE IsApproved='0' AND IsTemporary='0'",
                          array( "Limit" => $Limit, "Offset" => $Offset ) );
        $ret = array();

        foreach ( $message_array as $message )
        {
            $ret[] = new eZForumMessage( $message[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    /*!
      Returns the number of unapproved messages.
    */
    function unApprovedCount( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $message_array, "SELECT COUNT(ID) as Count FROM eZForum_Message WHERE IsApproved='0' AND IsTemporary='0'" );

        return $message_array[0][$db->fieldName( "Count" )];
    }

    /*!
      Get all the messages which is not approved
    */
    function getAllTemporary( )
    {
        $db =& eZDB::globalDatabase();

        $ret = array();

        $db->array_query( $message_array, "SELECT ID FROM eZForum_Message WHERE IsTemporary='1'" );
        $ret = array();

        foreach ( $message_array as $message )
        {
            $ret[] = new eZForumMessage( $message[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    /*!
      Returns the last n forum messages.

      The returned array has the following values
      array( "ID" => $id, "Topic" => $topic );
    */
    static public function &lastMessages( $limit, $user = false )
    {
        $db =& eZDB::globalDatabase();

        if ( !$user )
            $user =& eZUser::currentUser();

        $query_string = "AND ( f.GroupID='0' ";
        if ( $user )
        {
            $groups = $user->groups( false );
            foreach ( $groups as $group )
            {
                $query_string .= "OR f.GroupID='$group' ";
            }
        }
        $query_string .= ")";

        $ret = array();

        $db->array_query( $message_array, "SELECT m.ID as ID, m.UserID, m.ForumID, m.Topic, m.PostingTime, m.IsApproved,
                                           l.ForumID,
                                           f.ID as FID, f.GroupID
                                           FROM eZForum_Message as m, eZForum_ForumCategoryLink as l,
                                           eZForum_Forum as f
                                           WHERE IsTemporary='0' AND l.ForumID=m.ForumID
                                           AND m.IsApproved='1'
                                           AND f.ID=l.ForumID $query_string
                                           ORDER BY m.PostingTime DESC", array( "Limit" => $limit ) );

        return $message_array;
    }

    var $ID;
    var $ForumID;
    var $ParentID;
    var $Topic;
    var $Body;
    var $UserID;
    var $PostingTime;
    var $EmailNotice;
    var $IsApproved;
    var $IsTemporary;
    var $UserName;

    /// Number of seconds since the message was posted
    var $Age;

    /// indicates the position in the tree.
    var $TreeID;

    // contains the thread id
    var $ThreadID;

    // indicates the depth of the message in the tree
    var $Depth;

    var $Keywords;

}
?>