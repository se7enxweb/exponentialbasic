<?php
//
// $Id: datasupplier.php 9344 2002-03-06 08:56:33Z jhe $
//
// Created on: <28-May-2001 11:24:41 pkej>
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
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$PageCaching = $ini->read_var( "eZQuizMain", "PageCaching" );
$GlobalSectionID = $ini->read_var( "eZQuizMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "game":
    {
        $Action = $url_array[3];

        switch ( $Action )
        {
            case "future":
            case "past":
            case "list":
            {
                $Offset = $url_array[4];

                if  ( !is_numeric( $Offset ) )
                {
                    $Offset = 0;
                }

                include( "ezquiz/user/quizlist.php" );
            }
            break;

            case "score":
            case "scores":
            {
                $Offset = $url_array[5];

                if ( !is_numeric( $Offset ) )
                {
                    $Offset = 0;
                }

                $GameID = $url_array[4];

                if( !is_numeric( $GameID ) )
                {
                    eZHTTPTool::header( "Location: /quiz/game/list" );
                }

                include( "ezquiz/user/quizscores.php" );
            }
            break;


            case "view":
            case "play":
            {
                $GameID = $url_array[4];

                $user =& eZUser::currentUser();

                if ( !is_a( $user, "eZUser" ) )
                {
                   eZHTTPTool::header( "Location: /user/login?RedirectURL=" . urlencode( "/quiz/game/play/$GameID" ) );
                }
                else
                {
                    // include_once( "classes/ezlocale.php" );
                    // include_once( "classes/ezdate.php" );
                    // include_once( "ezquiz/classes/ezquizgame.php" );

                    $game = new eZQuizGame( $GameID );
                    $gameStop = $game->stopDate();
                    $gameStart = $game->startDate();
                    $today = new eZDate();

                    $locale = new eZLocale( $Language );

                    if ( $gameStart->isGreater( $today, true ) )
                    {
                        if ( $today->isGreater( $gameStop, true ) )
                        {
                            $QuestionNum = $url_array[5];
                            if ( !is_numeric( $QuestionNum ) )
                            {
                                $QuestionNum = 0;
                            }

                            include( "ezquiz/user/quizplay.php" );
                        }
                        else
                        {
                            $error = "closed";
                            include( "ezquiz/user/quizplay.php" );
                        }
                    }
                    else
                    {
                        $error = "unopened";
                        include( "ezquiz/user/quizplay.php" );
                    }
                }
            }
            break;
        }
        break;
    }

    case "my":
    {
        $Action = $url_array[3];

        $user =&  eZUser::currentUser();

        if( !is_a( $user, "eZUser" ) )
        {
        }


        switch ( $Action )
        {
            case "open":
            case "closed":
            {
                $Offset = $url_array[4];

                if  ( !is_numeric( $Offset ) )
                {
                    $Offset = 0;
                }

                include( "ezquiz/user/quizlist.php" );
            }
            break;

            case "score":
            case "scores":
            {
                $Offset = $url_array[4];

                if ( !is_numeric( $Offset ) )
                {
                    $Offset = 0;
                }

                include( "ezquiz/user/quizmyscores.php" );
            }
            break;
        }
        break;
    }
}

?>
