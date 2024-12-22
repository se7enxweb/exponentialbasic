<?php
// 
// $Id: ezpageview.php 8665 2001-12-02 12:48:02Z kaid $
//
// Definition of eZPageView class
//
// Created on: <04-Jan-2001 18:00:08 bf>
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

//!! eZStats
//! The eZPageView handled user page views on the site.
/*!
  This class hadles collecting of statistical information. It does
  not contain any query functions due to speed considerations.

  The class eZPageViewQuery handles the queries on the gathered information.
  
  \sa eZPageViewQuery
*/

/*!TODO
 */

// include_once( "classes/ezdb.php" );
// include_once( "ezuser/classes/ezuser.php" );

class eZPageView
{
    /*!
      Constructs a new eZPageView object.
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
      Stores a eZPageView object to the database.

      This function will also automatically fetch the user information and set the values
      before storing them to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        
        if ( !isset( $this->ID ) )
        {
            // parse information which is not relevant reported by browsers like konqueror
            $userAgent = preg_replace( "#(.*)\);.*#", "\\1)", $_SERVER["HTTP_USER_AGENT"] );

            // check if the browser type is already stored in the database, if it it just
            // create a reference to it.

            // lock
            $db->begin();
            $db->lock( "eZStats_BrowserType" );
            $nextID = $db->nextID( "eZStats_BrowserType", "ID" );
            $result = false;
            
            $userAgent = $db->escapeString( $userAgent );
            $db->array_query( $browser_type_array,
            "SELECT ID FROM eZStats_BrowserType
             WHERE BrowserType='$userAgent'" );

            if ( count( $browser_type_array ) == 0 )
            {
                $result = $db->query( "INSERT INTO eZStats_BrowserType
                                    ( ID, BrowserType )
                                    VALUES ( '$nextID', '$userAgent' )" );
                
				$this->BrowserTypeID = $nextID;
            }
            else
            {
                $this->BrowserTypeID = $browser_type_array[0][$db->fieldName( "ID" )];
            }
            $db->unlock();
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
            
            // check if the remote host is already stored in the database, if it it just
            // create a reference to it.
            
            $remoteIP = $_SERVER["REMOTE_ADDR"];

            $db->begin();
            $db->lock( "eZStats_RemoteHost" );
            $nextID = $db->nextID( "eZStats_RemoteHost", "ID" );
            $result = false;
            
            $db->array_query( $remote_host_array,
            "SELECT ID FROM eZStats_RemoteHost
             WHERE IP='$remoteIP'" );
            
            if ( count( $remote_host_array ) == 0 )
            {
                $remoteHostName =& gethostbyaddr( $remoteIP );

                $result = $db->query( "INSERT INTO eZStats_RemoteHost
                                    ( ID, IP, HostName )
                                    VALUES ( '$nextID',
                                             '$remoteIP',
                                             '$remoteHostName' )
                                    " );

				$this->RemoteHostID = $nextID;
            }
            else
            {
                $this->RemoteHostID = $remote_host_array[0][$db->fieldName( "ID" )];
            }
            $db->unlock();
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
            
            // check if the referer url is already stored in the database, if it it just
            // create a reference to it.

            $refererDomain = "";
            $refererURI = "";
            
            if ( isset( $_SERVER["HTTP_REFERER"] ) and preg_match( "#(htt.*?://)(.*?)(/.*)#", $_SERVER["HTTP_REFERER"], $valueArray ) )
            {
                // we don't need to store the http:// or the https://
                // $valueArray[1];

                // store the referer parts
                $refererDomain =& $valueArray[2];
                $refererURI =& $valueArray[3];
            }

            $db->begin();
            $db->lock( "eZStats_RefererURL" );
            $nextID = $db->nextID( "eZStats_RefererURL", "ID" );
            $result = false;

            $refererURI = $db->escapeString( $refererURI );
            
            $db->array_query( $referer_url_array,
            "SELECT ID FROM eZStats_RefererURL
             WHERE Domain='$refererDomain' AND URI='$refererURI'" );
            
            if ( count( $referer_url_array ) == 0 )
            {
                $result = $db->query( "INSERT INTO eZStats_RefererURL
                          ( ID, Domain, URI )
                          VALUES ( '$nextID',
                                   '$refererDomain',
                                    '$refererURI' )
                          " );                
				$this->RefererURLID = $nextID;
            }
            else
            {
                $this->RefererURLID = $referer_url_array[0][$db->fieldName( "ID" )];
            }
            $db->unlock();
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();

            // check if the requested page is already stored. If so store
            // the id.
            $requestURI = $_SERVER["REQUEST_URI"];
            $requestURI = preg_replace( "#/stats/store(.*?)1x1.gif$#", "\\1", $requestURI );
            $requestURI = preg_replace( "#/rx.*?-(.*)$#", "\\1", $requestURI );
            // Remove url parameters
            // preg_match( "([^?]+)", $requestURI, $regs);
            // $requestURI =& $regs[1];
            $requestURI = explode( '/stats/store', $requestURI  );
            $requestURI = $requestURI[1];

            $db->begin();
            $db->lock( "eZStats_RequestPage" );
            $nextID = $db->nextID( "eZStats_RequestPage", "ID" );
            $result = false;

            $db->array_query( $request_page_array,
            "SELECT ID FROM eZStats_RequestPage
             WHERE URI='$requestURI'" );
            
            if ( count( $request_page_array ) == 0 )
            {
                $result = $db->query( "INSERT INTO eZStats_RequestPage
                          ( ID, URI )
                          VALUES ( '$nextID',
                                   '$requestURI' )
                          " );
                
				$this->RequestPageID = $nextID;
            }
            else
            {
                $this->RequestPageID = $request_page_array[0][$db->fieldName( "ID" )];
            }
            $db->unlock();
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();

            $user =& eZUser::currentUser();
            if ( $user )
            {                
                $this->UserID = $user->id();
            }
            else
            {
                $this->UserID = 0;
            }
            
            $db->begin();
            $db->lock( "eZStats_PageView" );
            $nextID = $db->nextID( "eZStats_PageView", "ID" );
            $result = false;
            $now = new eZDateTime();
            $now = $now->timeStamp( true );
            $date = new eZDate();
            $date = $date->timestamp( true );
            $time = new eZTime();
            $time = $time->timeStamp( true );

            $result = $db->query( "INSERT INTO eZStats_PageView
                                ( ID, UserID, BrowserTypeID, RemoteHostID, RefererURLID, RequestPageID, Date, DateValue, TimeValue )
                                VALUES ( '$nextID',
                                         '$this->UserID',
                                         '$this->BrowserTypeID',
                                         '$this->RemoteHostID',
                                         '$this->RefererURLID',
                                         '$this->RequestPageID',
                                         '$now',
                                         '$date',
                                         '$time' )
                                " );
            $db->unlock();
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
            
			$this->ID = $nextID;    
        }
        else
        {
            $db->begin();
            $db->lock( "eZStats_PageView" );
            $result = $db->query( "UPDATE eZStats_PageView SET
                                 UserID='$this->UserID',
                                 BrowserTypeID='$this->BrowserTypeID',
                                 RemoteHostID='$this->RemoteHostID',
                                 RefererURLID='$this->RefererURLID'
                                 WHERE ID='$this->ID'
                                 " );
            $db->unlock();
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
        }
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
            $db->array_query( $pageview_array, "SELECT * FROM eZStats_PageView WHERE ID='$id'" );
            if ( count( $pageview_array ) > 1 )
            {
                die( "Error: Pageview's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $pageview_array ) == 1 )
            {
                $this->ID =& $pageview_array[0][$db->fieldName( "ID" )];
                $this->UserID =& $pageview_array[0][$db->fieldName( "UserID" )];
                $this->Date =& $pageview_array[0][$db->fieldName( "Date" )];
                $this->DateValue =& $pageview_array[0][$db->fieldName( "DateValue" )];
                $this->TimeValue =& $pageview_array[0][$db->fieldName( "TimeValue" )];
                $this->BrowserTypeID =& $pageview_array[0][$db->fieldName( "Date" )];
                $this->RemoteHostID =& $pageview_array[0][$db->fieldName( "RemoteHostID" )];
                $this->RefererURLID =& $pageview_array[0][$db->fieldName( "RefererURLID" )];
                $this->RequestPageID =& $pageview_array[0][$db->fieldName( "RequestPageID" )];

                // fetch the remote IP and domain
                $db->array_query( $pageview_array,
                "SELECT IP, HostName FROM eZStats_RemoteHost WHERE ID='$this->RemoteHostID'" );

                $this->RemoteIP = $pageview_array[0][$db->fieldName( "IP" )];

                $this->RemoteHostName = $pageview_array[0][$db->fieldName( "HostName" )];

                // check if the domain name is fetched, if not try to fetch it 
                // and store the result in the table.
                if ( $this->RemoteHostName = "NULL" )
                {
                    $db->begin();
                    $db->lock( "eZStats_RemoteHost" );
                    $this->RemoteHostName =& gethostbyaddr( $this->RemoteIP );

                    $result = $db->query( "UPDATE eZStats_RemoteHost SET HostName='$this->RemoteHostName' WHERE ID='$this->RemoteHostID'" );

                    $db->unlock();
                    if ( $result == false )
                        $db->rollback( );
                    else
                        $db->commit();
                }

                // fetch the requested page
                $db->array_query( $pageview_array,
                "SELECT URI FROM eZStats_RequestPage WHERE ID='$this->RequestPageID'" );

                $this->RequestPage = $pageview_array[0][$db->fieldName( "URI" )];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Returns the id of the virtual file.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the user who got the page. False if the user was not logged in.
    */
    function user()
    {
        $ret = false;
        if ( $this->UserID != 0 )
        {
            // include_once( "ezuser/classes/ezuser.php" );
            $ret = new eZUser( $this->UserID );
        }
        return $ret;
    }

    /*!
      Returns the remote ip address.
    */
    function remoteIP()
    {
        return $this->RemoteIP;
    }

    /*!
      Returns the time as a eZ time object.
    */
    function &dateTime()
    {
        $time = new eZDateTime();
        $time->setTimeStamp( $this->Date );
       
        return $time;
    }
    
    /*!
      Returns the remote host name.
    */
    function remoteHostName()
    {
        return $this->RemoteHostName;
    }
    
    /*!
      Returns the requested page
    */
    function requestPage()
    {
        return $this->RequestPage;
    }
    

    /*!
      Returns the requested page by request page id.
    */
    function requestPageByID( $id )
    {
        $db =& eZDB::globalDatabase();
        
        // fetch the requested page
        $db->array_query( $pageview_array,
        "SELECT URI FROM eZStats_RequestPage WHERE ID='$id'" );
        
        return $pageview_array[0][$db->fieldName( "URI" )];
    }

    /*!
      Returns the hostname of the requested ip address.
    */
    static public function getHostByAddr( $remoteIP )
    {
        $remoteHostName =& gethostbyaddr( $remoteIP );
        return $remoteHostName;
    }


    var $ID;
    var $UserID;
    var $Date;
    var $DateValue;
    var $TimeValue;
    var $BrowserTypeID;
    var $RemoteHostID;
    var $RefererID;
    var $RequestPageID;

    var $BrowserType;
    var $RemoteIP;
    var $RemoteHostName;
    var $RefererURL;
    var $RefererDomain;
    var $RequestPage;
    var $RefererURLID;
}

?>