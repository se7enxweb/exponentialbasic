<?php
//
// $Id: cron.php 9901 2004-07-08 16:15:25Z br $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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


include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );

$db =& eZDB::globalDatabase();

$db->begin();

$timestamp = eZDateTime::timeStamp( true );
$debug = false;
$limit = 10000;

set_time_limit( 86400 );

// BrowserType archive

$newelements = array();
$oldelements = array();
$res = array();
if ( $debug == true )
    print( "\nStarting query eZStats_PageView (BrowserType archive):" );

unset( $newelements );
flush();
$offset = 0;
$newelements = array( "dummy" );
while ( count( $newelements ) > 0 )
{
    $db->array_query( $newelements, "SELECT COUNT(*), BrowserTypeID FROM eZStats_PageView WHERE Date < " . $timestamp . " GROUP BY BrowserTypeID", array( "Limit" => $limit, "Offset" => $offset ) );
    $offset += $limit;
    if ( $debug == true )
        print( "." );

    foreach ( $newelements as $element )
    {
        $browser = array();
        $db->array_query( $browser, "SELECT BrowserType FROM eZStats_BrowserType WHERE ID='" . $element[$db->fieldName("BrowserTypeID")] . "'" );
        $browsername = $db->escapeString( $browser[0][$db->fieldName("BrowserType")] );
        $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_BrowserType WHERE Browser='" . $browsername . "'");

        if ( count( $oldelements ) == 0 )
        {
            $db->lock( "eZStats_Archive_BrowserType" );
            $nextid = $db->nextID( "eZStats_Archive_BrowserType", "ID" );
            $res[] = $db->query( "INSERT INTO eZStats_Archive_BrowserType (ID, Browser, Count) VALUES ('$nextid', '$browsername', '$element[0]')" );
            $db->unlock();
        }
        else
        {
            $count = $oldelements[0][$db->fieldName("Count")] + $element[0];
            $res[] = $db->query( "UPDATE eZStats_Archive_BrowserType SET Count='$count' WHERE Browser='$browsername'" );
        }
    }
}
// RequestPage archive

if ( $debug == true )
    print( "\nStarting query eZStats_PageView (RequestPage archive):" );
unset( $newelements );
flush();
$offset = 0;
$newelements = array( "dummy" );
while ( count( $newelements ) > 0 )
{
    $db->array_query( $newelements, "SELECT Date, RequestPageID FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date", array( "Limit" => $limit, "Offset" => $offset ) );
    $offset += $limit;

    if ( $debug == true )
        print( "." );
    foreach ( $newelements as $element )
    {
        $request = array();
        $db->array_query( $request, "SELECT URI FROM eZStats_RequestPage WHERE ID=" . $element[$db->fieldName("RequestPageID")] );
        $requestname = $db->escapeString( $request[0][$db->fieldName("URI")] );
        $date = new eZDateTime();
        $date->setTimeStamp( $element[$db->fieldName("Date")] );
        $month = new eZDateTime( $date->year(), $date->month(), 1, 0, 0, 0 );
        $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_RequestedPage WHERE URI='$requestname' AND Month='" . $month->timeStamp() . "'" );
        if ( count( $oldelements ) == 0 )
        {
            $db->lock( "eZStats_Archive_RequestedPage" );
            $nextid = $db->nextID( "eZStats_Archive_RequestedPage", "ID" );
            $res[] = $db->query( "INSERT INTO eZStats_Archive_RequestedPage (ID, URI, Month, Count) VALUES ('$nextid', '$requestname', '" . $month->timeStamp() . "', '1')" );
            $db->unlock();
        }
        else
        {
            $count = $oldelements[0][$db->fieldName("Count")] + 1;
            $res[] = $db->query( "UPDATE eZStats_Archive_RequestedPage SET Count='$count' WHERE URI='$requestname' AND Month='" . $month->timeStamp() . "'" );
        }
    }
}
// ReferURL archive
if ( $debug == true )
    print( "\nStart query eZStats_PageView (ReferURL archive):" );

unset( $newelements );
flush();
$offset = 0;
$newelements = array( "dummy" );
while ( count( $newelements ) > 0 )
{

    $db->array_query( $newelements, "SELECT Date, RefererURLID FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date", array( "Limit" => $limit, "Offset" => $offset ) );
    $offset += $limit;
    if ( $debug == true )
        print( "." );

    foreach ( $newelements as $element )
    {
        $refer = array();
        $db->array_query( $refer, "SELECT URI, Domain FROM eZStats_RefererURL WHERE ID=" . $element[$db->fieldName("RefererURLID")] );

        $refername = $db->escapeString( $refer[0][$db->fieldName("URI")] );
        $domain = $db->escapeString( $refer[0][$db->fieldName("Domain")] );
    
        $date = new eZDateTime();
        $date->setTimeStamp( $element[$db->fieldName("Date")] );
        $month = new eZDateTime( $date->year(), $date->month(), 1, 0, 0, 0  );
        $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_RefererURL WHERE URI='$refername' AND Domain='$domain' AND Month='". $month->timeStamp() . "'" );

        if ( count( $oldelements ) == 0 )
        {
            $db->lock( "eZStats_Archive_RefererURL" );
            $nextid = $db->nextID( "eZStats_Archive_RefererURL", "ID" );
            $res[] = $db->query( "INSERT INTO eZStats_Archive_RefererURL (ID, URI, Domain, Month, Count, Language ) VALUES " .
                                 "('$nextid', '$refername', '$domain', '" . $month->timeStamp() . "', '1', '' )" );
            $db->unlock();
        }
        else
        {
            $count = $oldelements[0][$db->fieldName("Count")] + 1;
            $res[] = $db->query( "UPDATE eZStats_Archive_RefererURL SET Count='$count' WHERE URI='$refername' AND Domain='$domain' AND Month='" . $month->timeStamp() . "'" );
        }
    }
}
// RemoteHost archive
if ( $debug == true )
    print( "\nStart query eZStats_PageView (RemoteHost archive):" );

unset( $newelements );
flush();
$offset = 0;

$newelements = array( "dummy" );
while ( count( $newelements ) > 0 )
{

    $db->array_query( $newelements, "SELECT RemoteHostID FROM eZStats_PageView WHERE Date < " . $timestamp, array( "Limit" => $limit, "Offset" => $offset ) );
    $offset += $limit;
    if ( $debug == true )
        print( "." );

    foreach ( $newelements as $element )
    {
        $remote = array();
        $db->array_query( $remote, "SELECT IP, HostName FROM eZStats_RemoteHost WHERE ID=" . $element[$db->fieldName("RemoteHostID")] );

        $remotename = $db->escapeString( $remote[0][$db->fieldName("IP")] );
        $hostname = $db->escapeString( $remote[0][$db->fieldName("HostName")] );

        $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_RemoteHost WHERE IP='$remotename' AND HostName='$hostname'" );
    
        if ( count( $oldelements ) == 0 )
        {
            $remote_host_id = $element[$db->fieldName( "RemoteHostID" )];
            $db->array_query( $remote_host, "SELECT * FROM eZStats_RemoteHost WHERE ID='$remote_host_id'" );

            $remoteHostIP = $db->escapeString( $remote_host[0][$db->fieldName( "IP" )] );
            $remoteHostName = $db->escapeString( $remote_host[0][$db->fieldName( "HostName" )] );
        
            $db->lock( "eZStats_Archive_RemoteHost" );
            $nextid = $db->nextID( "eZStats_Archive_RemoteHost", "ID" );
        
            $res[] = $db->query( "INSERT INTO eZStats_Archive_RemoteHost (ID, IP, HostName, Count) VALUES ('$nextid', '" .
                                  $remoteHostIP . "', '" . 
                                  $remoteHostName . "', '1')" );
            $db->unlock();
        }
        else
        {
            $count = $oldelements[0][$db->fieldName("Count")] + 1;
            $res[] = $db->query( "UPDATE eZStats_Archive_RemoteHost SET Count='$count' WHERE IP='$remotename' AND HostName='$hostname'" );
        }
    }
}
// Users archive
if ( $debug == true )
    print( "\nStart query eZStats_PageView (Users archive):" );

unset( $newelements );
flush();
$offset = 0;

$newelements = array( "dummy" );
while ( count( $newelements ) > 0 )
{
    $db->array_query( $newelements, "SELECT Date, UserID FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date", array( "Limit" => $limit, "Offset" => $offset ) );
    $offset += $limit;

    if ( $debug == true )
        print( "." );
    foreach ( $newelements as $element )
    {
        $userID = $element[$db->fieldName("UserID")];
        $date = new eZDateTime();
        $date->setTimeStamp( $element[$db->fieldName("Date")] );
        $month = new eZDateTime( $date->year(), $date->month(), 1, 0, 0, 0  );
        $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_Users WHERE UserID='$userID' AND Month='". $month->timeStamp() . "'" );
    
        if ( count( $oldelements ) == 0 )
        {
            $db->lock( "eZStats_Archive_Users" );
            $nextid = $db->nextID( "eZStats_Archive_Users", "ID" );
            $res[] = $db->query( "INSERT INTO eZStats_Archive_Users (ID, UserID, Month, Count) VALUES ('$nextid', '$userID', '" . $month->timeStamp() . "', '1')" );
            $db->unlock();
        }
        else
        {
            $count = $oldelements[0][$db->fieldName("Count")] + 1;
            $res[] = $db->query( "UPDATE eZStats_Archive_Users SET Count='$count' WHERE UserID='$userID' AND Month='" . $month->timeStamp() . "'" );
        }
    }
}
/* UniqueVisits archive

$db->query( $newelements, "SELECT Date, RemoteHostID FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date" );


foreach ( $newelements as $element )
{
    $date = new eZDateTime();
    $date->setTimeStamp( $element[$db->fieldName("Date")] );
    $day = new eZDateTime( $date->year(), $date->month(), $date->day() );
    $db->query( $oldelements, "SELECT * FROM eZStats_Archive_UniqueVisits WHERE Day='" . $day->timeStamp() . "'" );
    
    if ( count( $oldelements ) == 0 )
    {
        $db->lock( "eZStats_Archive_UniqueVisits" );
        $nextid = $db->nextID( "eZStats_Archive_UniqueVisits", "ID" );
        $res[] = $db->query( "INSERT INTO eZStats_Archive_UniqueVisits (ID, Day, Count) VALUES ('$nextid', '$day->timeStamp()', '1')" );
        $db->unlock();
    }
    else
    {
        $count = $oldelements[0][$db->fieldName("Count")] + 1;
        $res[] = $db->query( "UPDATE eZStats_Archive_UniqueVisits SET Count='$count' WHERE Day='" . $day->timeStamp() . "'" );
    }
}
*/

// PageView archive
if ( $debug == true )
    print( "\nStart query eZStats_PageView (PageView archive):" );
unset( $newelements );
flush();
$offset = 0;

$newelements = array( "dummy" );
while ( count( $newelements ) > 0 )
{

    $db->array_query( $newelements, "SELECT Date FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date", array( "Limit" => $limit, "Offset" => $offset ) );
    $offset += $limit;
    if ( $debug == true )
        print( "." );

    foreach ( $newelements as $element )
    {
        $date = new eZDateTime();
        $date->setTimeStamp( $element[$db->fieldName("Date")] );
        $hour = new eZDateTime( $date->year(), $date->month(), $date->day(), $date->hour(), 0, 0 );
        $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_PageView WHERE Hour='" . $hour->timeStamp() . "'" );
    
        if ( count( $oldelements ) == 0 )
        {
            $db->lock( "eZStats_Archive_PageView" );
            $nextid = $db->nextID( "eZStats_Archive_PageView", "ID" );
            $res[] = $db->query( "INSERT INTO eZStats_Archive_PageView (ID, Hour, Count) VALUES ('$nextid', '" . $hour->timeStamp() . "', '1')" );
            $db->unlock();
        }
        else
        {
            $count = $oldelements[0][$db->fieldName("Count")] + 1;
            $res[] = $db->query( "UPDATE eZStats_Archive_PageView SET Count='$count' WHERE Hour='" . $hour->timeStamp() . "'" );
        }
    }
}
$res[] = $db->query( "DELETE FROM eZStats_PageView WHERE Date < " . $timestamp );

if ( in_array( false, $res ) )
    $db->rollback();
else
$db->commit();


?>
