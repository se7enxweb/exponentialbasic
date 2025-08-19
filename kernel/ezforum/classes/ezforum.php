<?php
//
// $Id: ezforum.php 9733 2002-10-02 14:50:11Z bf $
//
// Created on: <11-Sep-2000 22:10:06 bf>
//
// This source file is part of Exponential Basic, publishing software.
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
//! The eZForum class handles forum''s in the database.
/*!

  \sa eZForumMessage \eZForumCategory
*/

/*!TODO
*/

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezquery.php" );
// include_once( "ezforum/classes/ezforummessage.php" );

// include_once( "ezuser/classes/ezusergroup.php" );

class eZForum
{
    /*!
      Constructs a new eZForum object.
    */
    function __construct( $id = "" )
    {
        $this->IsModerated = 0;
        $this->IsAnonymous = 0;
        $this->IsPrivate = 0;
        $this->ModeratorID = 0;
        $this->GroupID = 0;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZForum object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZForum_Forum" );
            $nextID = $db->nextID( "eZForum_Forum", "ID" );

            $res = $db->query( "INSERT INTO eZForum_Forum
                         ( ID,
                           Name,
                           Description,
                           IsModerated,
                           IsAnonymous,
                           ModeratorID,
                           GroupID,
                           IsPrivate )
                         VALUES
                         ( '$nextID',
                           '$name',
                           '$description',
                           '$this->IsModerated',
                           '$this->IsAnonymous',
                           '$this->ModeratorID',
                           '$this->GroupID',
                           '$this->IsPrivate' )" );

            $this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZForum_Forum SET
                                         Name='$name',
                                         Description='$description',
                                         IsModerated='$this->IsModerated',
                                         IsAnonymous='$this->IsAnonymous',
                                         ModeratorID='$this->ModeratorID',
                                         GroupID='$this->GroupID',
                                         IsPrivate='$this->IsPrivate'
                                         WHERE ID='$this->ID'" );
        }

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZForumCategory object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        // delete messages
        $db->query( "DELETE FROM eZForum_Message WHERE ForumID='$this->ID'" );

        // delete category assignments
        $db->query( "DELETE FROM eZForum_ForumCategoryLink WHERE ForumID='$this->ID'" );

        // delete the forum
        $db->query( "DELETE FROM eZForum_Forum WHERE ID='$this->ID'" );

        return true;
    }

    /*!
      Removes all assignments from forum to categories.
    */
    function removeFromForums()
    {
        $db =& eZDB::globalDatabase();

        // delete category assignments
        $db->query( "DELETE FROM eZForum_ForumCategoryLink WHERE ForumID='$this->ID'" );
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = "" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $category_array = array();

        if ( $id != "" )
        {
            $db->array_query( $forum_array, "SELECT * FROM eZForum_Forum WHERE ID='$id'" );
            if ( count( $forum_array ) > 1 )
            {
                die( "Error: Forum's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $forum_array ) == 1 )
            {
                $this->ID =& $forum_array[0][$db->fieldName( "ID" )];
                $this->Name =& $forum_array[0][$db->fieldName( "Name" )];
                $this->Description =& $forum_array[0][$db->fieldName( "Description" )];
                $this->IsModerated =& $forum_array[0][$db->fieldName( "IsModerated" )];
                $this->IsAnonymous =& $forum_array[0][$db->fieldName( "IsAnonymous" )];
                $this->ModeratorID =& $forum_array[0][$db->fieldName( "ModeratorID" )];
                $this->GroupID =& $forum_array[0][$db->fieldName( "GroupID" )];
                $this->IsPrivate =& $forum_array[0][$db->fieldName( "IsPrivate" )];

                $ret = true;
            }
            else if ( count( $category_array ) == 0 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }


    /*!
      Returns every forum.
    */
    function getAll()
    {

    }

    /*!
      Returns the messages in a forum.
    */
    function &messages()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $message_array, "SELECT ID, PostingTime FROM
                                           eZForum_Message
                                           WHERE ForumID='$this->ID' AND IsTemporary='0'
                                           ORDER BY PostingTime DESC" );

        $ret = array();

        foreach ( $message_array as $message )
        {
            $ret[] = new eZForumMessage( $message[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    /*!
      Returns the messages in every forum matching the query string.
    */
    function &search( $queryText, $offset, $limit, &$SearchTotalCount, $params = array() )
    {
        $db =& eZDB::globalDatabase();
        $return_array = array();
        $queryText = $db->escapeString( $queryText );

        $query = new eZQuery( "eZForum_Word.Word", $queryText );
        $query->setIsLiteral( true );
        $query->setStopWordColumn(  "eZForum_Word.Frequency" );
        $searchSQL = $query->buildQuery();

        $groupString = "eZForum_Forum.GroupID=0";

        $user =& eZUser::currentUser();
        if ( $user )
        {
            $groups =& $user->groups( false );
            $i = 0;

            foreach ( $groups as $userGroup )
            {
                $groupString .= " OR ";

                $groupString .= "eZForum_Forum.GroupID='" . $userGroup . "'";
                $i++;
            }
        }

        // special search for MySQL, mimic subselects ;)
        if ( $db->isA() == "mysql" )
        {
            $queryArray = explode( " ", trim( $queryText ) );

            $db->query( "CREATE TEMPORARY TABLE eZForum_SearchTemp( MessageID int )" );

            $count = 1;
            foreach ( $queryArray as $queryWord )
            {
                $queryWord = trim( $queryWord );

                $searchSQL = " ( eZForum_Word.Word = '$queryWord' AND eZForum_Word.Frequency < '0.9' ) ";

                $queryString = "INSERT INTO eZForum_SearchTemp ( MessageID )
                                SELECT DISTINCT eZForum_Message.ID AS MessageID
                                FROM eZForum_Message,
                                     eZForum_MessageWordLink,
                                     eZForum_Word,
                                     eZForum_Forum
                                WHERE $searchSQL AND
                                ( eZForum_Message.ID=eZForum_MessageWordLink.MessageID
                                AND eZForum_MessageWordLink.WordID=eZForum_Word.ID ) AND
                                eZForum_Message.ForumID=eZForum_Forum.ID AND
                                ($groupString)
                                ORDER BY eZForum_MessageWordLink.Frequency";

                $db->query( $queryString );
                // check if this is a stop word
                $queryString = "SELECT Frequency FROM eZForum_Word WHERE Word='$queryWord'";

                $db->query_single( $WordFreq, $queryString, array( "LIMIT" => 1 ) );

                if ( isset( $WordFreq["Frequency"] ) && $WordFreq["Frequency"] <= 0.7 )
                    $count += 1;
            }
            $count -= 1;

            $queryString = "SELECT MessageID, Count(*) AS Count FROM eZForum_SearchTemp GROUP BY MessageID HAVING Count='$count'";

            $db->array_query( $message_array, $queryString );
            $SearchTotalCount = count( $message_array );
            $message_array =& array_slice( $message_array, $offset, $limit );

            $db->query( "DROP TABLE eZForum_SearchTemp" );
        }
        else
        {
            $queryString = "SELECT DISTINCT eZForum_Message.ID AS MessageID
                            FROM eZForum_Message,
                            eZForum_MessageWordLink,
                            eZForum_Word,
                            eZForum_Forum
                            WHERE
                            $searchSQL
                            AND
                            ( eZForum_Message.ID=eZForum_MessageWordLink.MessageID
                            AND eZForum_MessageWordLink.WordID=eZForum_Word.ID )
                            eZForum_Message.ForumID=eZForum_Forum.ID AND
                            ($groupString)
                            ORDER BY eZForum_MessageWordLink.Frequency";

            $db->array_query( $message_array, $queryString );
            $SearchTotalCount = count( $message_array );
            $message_array =& array_slice( $message_array, $offset, $limit );
        }

        for ( $i = 0; $i < count( $message_array ); $i++ )
        {
            $return_array[$i] = new eZForumMessage( $message_array[$i][$db->fieldName( "MessageID" )], false );
        }

        return $return_array;
    }


    /*!
      Returns the total count of a query.
    */
    function &getQueryCount( $queryString )
    {
        $db =& eZDB::globalDatabase();
        $queryString = $db->escapeString( $queryString );
        $message_array = 0;

        $query = new eZQuery( array( "Topic", "Body" ), $queryString );
        $query->setPartialCompare( true );

        $query_str = "SELECT count(*) AS Count FROM eZForum_Message WHERE (" . $query->buildQuery() . ")";

        $db->array_query( $message_array, $query_str );

        $ret = 0;
        if ( count( $message_array ) == 1 )
            $ret = $message_array[0][$db->fieldName( "Count" )];
        settype( $ret, "integer" );
        return $ret;
    }


    /*!
      Returns all the messages and submessages as a tree as an array.

      Default limit is set to 30.
    */
    function &messageTree( $offset = 0, $limit = 30, $showUnApproved = false )
    {
        $db =& eZDB::globalDatabase();

        $approvedCode = "";
        if ( $showUnApproved == false )
        {
            $approvedCode = " AND IsApproved=1 ";
        }

        $db->array_query( $message_array, "SELECT ID, TreeID FROM
                                           eZForum_Message
                                           WHERE ForumID='$this->ID' $approvedCode
                                           AND IsTemporary='0' ORDER BY TreeID
                                           DESC",
                          array( "Limit" => $limit, "Offset" => $offset ) );

        $ret = array();

        foreach ( $message_array as $message )
        {
            $ret[] = new eZForumMessage( $message[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    /*!
      Returns all the messages and submessages as a tree as an array.

      Default limit is set to 30.
    */
    function messageTreeArray( $offset = 0, $limit = 30, $showUnApproved = false, $showReplies = true )
    {
        $db =& eZDB::globalDatabase();

        $approvedCode = "";
        if ( $showUnApproved == false )
        {
            $approvedCode = " AND IsApproved=1 ";
        }

        $timeStamp =& (new eZDateTime())->timeStamp( true );
        if ( $showReplies )
        {
            $db->array_query( $message_array, "SELECT ID, Topic, UserID, PostingTime, Depth, UserName,
                                          ( $timeStamp  - PostingTime ) AS Age, TreeID, Body
                                          FROM
                                          eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND IsTemporary='0'
                                          $approvedCode
                                          ORDER BY TreeID
                                          DESC",
                              array( "Limit" => $limit, "Offset" => $offset ) );
        }
        else
        {
            $db->array_query( $message_array, "SELECT ID, Topic, UserID, PostingTime, Depth, UserName,
                                          ( $timeStamp  -  PostingTime ) AS Age, TreeID, ThreadID, Body
                                          FROM eZForum_Message
                                          WHERE ForumID='$this->ID' AND Depth='0'
                                          AND IsTemporary='0'
                                          $approvedCode
                                          ORDER BY TreeID DESC ",
                              array( "Limit" => $limit, "Offset" => $offset ) );
        }
        return $message_array;
    }

    /*!
      Returns all the messages and submessages of a thread as a tree.

      Default limit is set to 100
    */
    function messageThreadTree( $threadID, $showUnApprived = false, $offset = 0, $limit = 100 )
    {
       $db =& eZDB::globalDatabase();

       if ( !isset( $showUnApproved ) || !$showUnApproved )
           $showUnApproved = " AND IsApproved='1' ";
       else
           $showUnApproved = " AND IsApproved='0' ";

       $db->array_query( $message_array, "SELECT ID, TreeID FROM eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND ThreadID='$threadID'
                                          AND IsTemporary='0'
                                          $showUnApproved
                                          ORDER BY TreeID DESC",
       array( "Limit" => $limit, "Offset" => $offset ) );

       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message[$db->fieldName("ID")] );
       }

       return $ret;
    }


    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns every category which the forum is a part of.
    */
    function categories( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $forum_array, "SELECT CategoryID FROM
                                         eZForum_ForumCategoryLink
                                         WHERE ForumID='$this->ID'" );

        $ret = array();

        foreach ( $forum_array as $forum )
        {
            if ( $as_object )
                $ret[] = new eZForumCategory( $forum[$db->fieldName( "CategoryID" )] );
            else
                $ret[] = $forum[$db->fieldName( "CategoryID" )];
        }

        return $ret;
    }


    /*!
      Returns the name of the forum.
    */
    function &name( $html = true )
    {
        if( !is_null( $this->Name ) )
        {
            if ( $html )
            {
                return htmlspecialchars( stripslashes( $this->Name ) );
            }
            else
            {
                return stripslashes( $this->Name );
            }
        }
        else
        {
            return null;
        }
    }

    /*!

    */
    function setName( $newName )
    {
        $this->Name = $newName;
    }

    /*!

    */
    function &description()
    {
		$description = stripslashes( $this->Description );
        return htmlspecialchars( $description );
    }

    /*!

    */
    function setDescription( $newDescription )
    {
        $this->Description = $newDescription;
    }

    /*!
      Returns true if the forum is moderated, false if not.
    */
    function isModerated()
    {
        $ret = false;
        if ( $this->IsModerated == 1 )
            $ret = true;

        return $ret;
    }

    /*!
      Returns true if the forum allows anonymous posting.
    */
    function isAnonymous()
    {
        $ret = false;
        if ( $this->IsAnonymous == 1 )
            $ret = true;

        return $ret;
    }

    /*!
      Returns the forum moderator as a eZUser object.
    */
    function &moderator()
    {
        $group = false;

        if ( $this->ModeratorID > 0 )
        {
            $group = new eZUserGroup( $this->ModeratorID );
        }

        return $group;
    }


    /*!
      Returns the forum moderator as a eZUser object.
    */
    function &group()
    {
        $group = false;

        if ( $this->GroupID > 0 )
        {
            $group = new eZUserGroup( $this->GroupID );
        }

        return $group;
    }

    /*!
      Sets the forum to be moderated or not.
    */
    function setIsModerated( $value )
    {
        if ( $value )
        {
            $this->IsModerated = 1;
        }
        else
        {
            $this->IsModerated = 0;
        }
    }

    /*!
      Sets the forum to allow anonymous posting, or not.
    */
    function setIsAnonymous( $value )
    {
        if ( $value )
            $this->IsAnonymous = 1;
        else
            $this->IsAnonymous = 0;
    }

    /*!
      Sets the forum moderator.
    */
    function setModerator( $group )
    {
        if ( is_a( $group, "eZUserGroup" ) )
        {
            $this->ModeratorID = $group->id();
        }
        else if ( is_numeric( $group ) )
        {
            $this->ModeratorID = $group;
        }
    }

    /*!
      Sets the forum readers.
    */
    function setGroup( $group )
    {
        if ( is_a( $group, "eZUserGroup" ) )
        {
            $this->GroupID = $group->id();
        }
        else if ( is_numeric( $group ) )
        {
            $this->GroupID = $group;
        }
    }


    /*!

    */
    function isPrivate()
    {
        return $this->IsPrivate;
    }

    /*!

    */
    function setPrivate( $newPrivate )
    {
        $this->IsPrivate = $newPrivate;
    }

    /*!
      Returns the number of threads in the forum.
    */
    function threadCount( $countUnapproved = false )
    {
        $db =& eZDB::globalDatabase();

        $unapprovedSQL = "";
        if ( $countUnapproved == false )
            $unapprovedSQL = "AND IsApproved='1'";

        $db->array_query( $message_array, "SELECT Count(ID) AS Count FROM eZForum_Message
                                           WHERE ForumID='$this->ID' AND Depth='0'
                                           AND IsTemporary='0' $unapprovedSQL " );

        $ret = $message_array[0][$db->fieldName( "Count" )];

        setType( $ret, "integer" );

        return $ret;
    }

    /*!
      Returns the number of messages in the forum.
    */
    function messageCount( $countUnapproved = false, $showReplies = false )
    {
        $db =& eZDB::globalDatabase();

        if ( $countUnapproved == false )
            $unapprovedSQL = "AND IsApproved='1'";
        else
            $unapprovedSQL = "";

        if ( $showReplies )
        {
            $db->array_query( $message_array, "SELECT ID FROM eZForum_Message
                                               WHERE ForumID='$this->ID'
                                               AND IsTemporary='0'
                                               $unapprovedSQL
                                              " );
       }
       else
       {
           $db->array_query( $message_array, "SELECT ID FROM eZForum_Message
                                              WHERE ForumID='$this->ID' AND Depth='0'
                                              AND IsTemporary='0' $unapprovedSQL" );
       }

        $ret = count( $message_array );

        return $ret;
    }


    var $ID;
    var $Name;
    var $Description;
    var $IsModerated;
    var $IsAnonymous;
    var $IsPrivate;
    var $ModeratorID;
    var $GroupID;
}

?>