<?php
// 
// $Id: pollist.php 6304 2001-07-29 23:31:17Z kaid $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezcachefile.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZPollMain", "Language" );
$errorIni = new eZINI( "kernel/ezpoll/admin/intl/" . $Language . "/pollist.php.ini", false );

// include_once( "ezpoll/classes/ezpoll.php" );

require( "kernel/ezuser/admin/admincheck.php" );

// Language
$LangaugeIni = new eZINI( "kernel/ezpoll/admin/" . "intl/" . $Language . "/pollist.php.ini", false );
$yes = $LangaugeIni->variable( "strings", "yes" );
$no = $LangaugeIni->variable( "strings", "no" );
$closed = $LangaugeIni->variable( "strings", "closed" );
$notClosed = $LangaugeIni->variable( "strings", "not_closed" );

if ( isset( $Action ) && $Action == "StoreMainPoll" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "kernel/ezpoll/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
    
    
    $mainPoll = new eZPoll( $MainPollID );
    if ( $mainPoll->isClosed() )
    {
        $errorMsg = $errorIni->variable( "strings", "poll_closed" );
    }
    else if ( !$mainPoll->isEnabled() )
    {
        $errorMsg = $errorIni->variable( "strings", "poll_not_enabled" );
    }
    else
    {
        $mainPoll->setMainPoll( $mainPoll );
    }
}

if ( isset( $Action ) && $Action == "Delete" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezpoll/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
    
    if( count( $PollArrayID ) > 0 )
    {
        foreach( $PollArrayID as $doomedPoll )
        {
            $poll = new eZPoll( $doomedPoll );
            $poll->delete();
        }
    }
    // clear the menu cache
    if ( file_exists("kernel/ezpoll/cache/menubox.cache" )  )
        eZPBFile::unlink( "kernel/ezpoll/cache/menubox.cache" );
}

$t = new eZTemplate( "kernel/ezpoll/admin/" . $ini->variable( "eZPollMain", "AdminTemplateDir" ),
                     "kernel/ezpoll/admin/intl/", $Language, "pollist.php" );

$t->setAllStrings();

$t->set_file( array(
    "poll_list_page" => "pollist.tpl"
    ) );

$t->set_block( "poll_list_page", "poll_item_tpl", "poll_item" );

$t->set_var( "site_style", $SiteDesign );

$nopolls = "";

$poll = new eZPoll();

$pollList = $poll->getAll( );

if ( !$pollList )
{
    $languageIni = new eZINI( "kernel/ezpoll/" . "/admin/" . "intl/" . $Language . "/pollist.php.ini", false );
    $nopolls =  $languageIni->variable( "strings", "nopolls" );
    $t->set_var( "poll_item", "" );
}

$mainPoll = $poll->mainPoll();

if ( $mainPoll )
{
    $mainPollID = $mainPoll->id();
}

$i=0;
foreach( $pollList as $pollItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
        
    if ( $pollItem->isEnabled() == "true" )
        $t->set_var( "poll_is_enabled", $yes );
    else
        $t->set_var( "poll_is_enabled", $no );

//      if ( $pollItem->anonymous() == "true" )
//          $t->set_var( "anonymous", "Ja" );
//      else
//          $t->set_var( "anonymous", "Nei" );

    
    if ( $pollItem->id() == $mainPollID )
        $t->set_var( "is_checked", "checked" );
    else
        $t->set_var( "is_checked", "" );        

    if ( $pollItem->isClosed() == "true" )
    {
        $t->set_var( "poll_is_closed", $closed );
    }
    else
    {
        $t->set_var( "poll_is_closed", $notClosed );
    }
    $t->set_var( "poll_id", $pollItem->id() );
    $t->set_var( "poll_name", $pollItem->name() );
    $t->set_var( "poll_description", $pollItem->description() );

    $t->parse( "poll_item", "poll_item_tpl", true );
    $i++;
}

$t->set_var( "error_msg", isset( $errorMsg ) ? $errorMsg : false );
$t->set_var( "nopolls", $nopolls );

$t->pparse( "output", "poll_list_page" );

?>