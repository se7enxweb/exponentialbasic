<?php
// 
// $Id: votebox.php 6376 2001-08-03 12:49:23Z master $
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
// include_once( "classes/ezhttptool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZPollMain", "Language" );
$PageCaching = $ini->read_var( "eZPollMain", "PageCaching" );
$errorIni = new INIFIle( "kernel/ezpoll/user/intl/" . $Language . "/votebox.php.ini", false );

$noItem = $errorIni->read_var( "strings", "noitem" );

unset( $menuCachedFile );
// do the caching
if ( $PageCaching == "enabled" )
{
    $menuCachedFile = "kernel/ezpoll/cache/menubox,". $GlobalSiteDesign .".cache";
    
    if ( eZFile::file_exists( $menuCachedFile ) )
    {
        include( $menuCachedFile );
    }
    else
    {
        createPollMenu( true );
    }            
}
else
{
    createPollMenu();
}

function createPollMenu( $generateStaticPage = false )
{
    global $ini;
    global $menuCachedFile;
    global $noItem;
    global $GlobalSiteDesign;
    global $PollID;

    $Language = $ini->read_var( "eZPollMain", "Language" );
    
    // include_once( "ezpoll/classes/ezpoll.php" );
    // include_once( "ezpoll/classes/ezpollchoice.php" );

    $t = new eZTemplate( "kernel/ezpoll/user/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                         "kernel/ezpoll/user/intl/", $Language, "votebox.php" );

    $t->setAllStrings();

    $poll = new eZPoll( $PollID );
    $poll = $poll->mainPoll();

    $t->set_file( array(
        "vote_box" => "votebox.tpl"
        ) );
    
    $t->set_block( "vote_box", "vote_item_tpl", "vote_item" );
    $t->set_block( "vote_box", "novote_item_tpl", "novote_item" );

    $t->set_var( "vote_item", "" );
    $t->set_var( "novote_item", "" );
    $t->set_var( "head_line", "" );
    $t->set_var( "sitedesign", $GlobalSiteDesign );
    
    if ( $poll )
    {
	if (! $PollID)
	{
	    $PollID = $poll->id();
	}
        $poll = new eZPoll( $PollID );


        if ( $poll->isClosed() )
        {
            eZHTTPTool::header( "Location: /poll/result/$PollID" );
            exit();
        }

        $choice = new eZPollChoice();

        $choiceList = $choice->getAll( $PollID );

        if ( !$choiceList )
        {
            $t->set_var( "vote_item", "" );
            $t->set_var( "novote_item", $noItem );
            $t->parse( "novote_item", "novote_item_tpl" );
        }
        else
        {
            foreach( $choiceList as $choiceItem )
            {
                $t->set_var( "choice_name", $choiceItem->name() );
                $t->set_var( "choice_id", $choiceItem->id() );

                $t->set_var( "novote_item", "" );
                $t->parse( "vote_item", "vote_item_tpl", true );
            }
        }

        $poll = new eZPoll();
        $poll->get( $PollID );
        $t->set_var( "head_line", $poll->name() );
        $t->set_var( "poll_id", $PollID );

    }
    if ( $generateStaticPage == true )
    {
        $fp = eZFile::fopen( $menuCachedFile, "w+");

        $output = $t->parse( "output", "vote_box" );
        // print the output the first time while printing the cache file.
    
        print( $output );
        fwrite ( $fp, $output );
        fclose( $fp );
    }
    else
    {
		$t->pparse( "output", "vote_box" );
    }

}
?>
