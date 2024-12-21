<?php
//
// $Id: menubox.php 8689 2001-12-06 10:22:35Z jhe $
//
// Created on: <17-Oct-2000 12:16:07 bf>
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
// include_once( "classes/ezcachefile.php" );
// include_once( "classes/ezlocale.php" );

$Language = $ini->read_var( "eZQuizMain", "Language" );
$PageCaching = $ini->read_var( "eZQuizMain", "PageCaching" );

createQuizMenu();

function createQuizMenu()
{
    global $ini;
    global $Language;
    global $quizMenuCachedFile;
    global $GenerateStaticPage;
	global $GlobalSiteDesign;

    // include_once( "classes/eztemplate.php" );
    // include_once( "ezquiz/classes/ezquizgame.php" );
    // include_once( "ezquiz/classes/ezquizscore.php" );

    $t = new eZTemplate( "kernel/ezquiz/user/" . $ini->read_var( "eZQuizMain", "TemplateDir" ),
                         "kernel/ezquiz/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "current_game_item_tpl", "current_game_item" );
    $t->set_block( "menu_box_tpl", "next_game_item_tpl", "next_game_item" );
    $t->set_block( "menu_box_tpl", "no_game_item_tpl", "no_game_item" );
    $t->set_block( "menu_box_tpl", "quiz_menu_item_tpl", "quiz_menu_item" );
    $t->set_block( "menu_box_tpl", "my_quiz_item_tpl", "my_quiz_item" );

    $t->set_var( "current_game_item", "" );
    $t->set_var( "next_game_item", "" );
    $t->set_var( "quiz_menu_item", "" );
    $t->set_var( "my_quiz_item", "" );
    $t->set_var( "no_game_item", "" );

    $t->set_var( "sitedesign", $GlobalSiteDesign );

    $userMenuUp = false;

    $game = new eZQuizGame();

    if ( eZUser::currentUser() != false )
    {

        $t->parse( "my_quiz_item", "my_quiz_item_tpl" );
        $userMenuUp = true;
        $user =& eZUser::currentUser();
    }

    if ( true )
    {
        $t->parse( "quiz_menu_item", "quiz_menu_item_tpl" );
    }

    $games = $game->openGames( 0, 1 );
    $count = count( $games );

    if ( $count > 0 )
    {
        $game = $games[0];
        $finished = false;

        if ( $userMenuUp )
        {
            $score = new eZQuizScore();
            $score->getUserGame( $user, $game );

            if ( $score->isFinishedGame() )
            {
                $finished = true;
            }
        }

        if ( $finished == false )
        {
            $t->set_var( "game_id", $game->id() );
            $t->set_var( "game_name", $game->name() );
            $t->parse( "current_game_item", "current_game_item_tpl" );
        }


    }

    if ( $count == 0 || $finished )
    {
        $games = $game->opensNext( 0, 1 );
        $count = count( $games );

        if ( $count >= 1 )
        {
            $game = $games[0];
            $t->set_var( "game_id", $game->id() );
            $t->set_var( "game_name", $game->name() );

            $start = $game->startDate();

            $locale = new eZLocale( $Language );

            if ( $start->day() != 0  )
            {
                $t->set_var( "game_start_date", $locale->format( $start, true ) );
            }
            $t->parse( "next_game_item", "next_game_item_tpl" );
        }
        else
        {
            $t->parse( "no_game_item", "no_game_item_tpl" );
        }
    }

    if ( isset( $menuCacheFile ) and is_a( $menuCacheFile, "eZCacheFile" ) )
    {
        $output = $t->parse( $target, "menu_box_tpl" );
        $menuCacheFile->store( $output );
        print( $output );
    }
    else
    {
		$t->pparse( "output", "menu_box_tpl" );
    }
}

?>
