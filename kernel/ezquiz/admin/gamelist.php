<?php
// 
// $Id: gamelist.php 6226 2001-07-20 11:24:09Z jakobn $
//
// Created on: <22-May-2001 14:47:05 ce>
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
// include_once( "classes/ezlist.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZQuizMain", "Language" );
$Limit = $ini->variable( "eZQuizMain", "AdminListLimit" );

// include_once( "ezquiz/classes/ezquizgame.php" );

$t = new eZTemplate( "kernel/ezquiz/admin/" . $ini->variable( "eZQuizMain", "AdminTemplateDir" ),
                     "kernel/ezquiz/admin/" . "/intl", $Language, "gamelist.php" );
$t->setAllStrings();

$t->set_file( array(
    "game_page" => "gamelist.tpl"
      ) );

$t->set_block( "game_page", "game_list_tpl", "game_list" );
$t->set_block( "game_list_tpl", "game_item_tpl", "game_item" );

if ( !isset( $Offset ) )
    $Offset = 0;

$t->set_var( "site_style", $SiteDesign );
$gameList =& eZQuizGame::getAll( $Offset, $Limit );
$totalCount =& eZQuizGame::count();

if ( count ( $gameList ) > 0 )
{
    $i=0;
    foreach( $gameList as $game )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "game_id", $game->id() );
        $t->set_var( "game_name", $game->name() );
        $t->set_var( "game_description", $game->description() );
        
        $t->parse( "game_item", "game_item_tpl", true );
        $i++;
    }
    $t->parse( "game_list", "game_list_tpl" );
}
else
$t->set_var( "game_list", "" );
eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "game_page" );

$t->set_var( "game_start", $Offset + 1 );
$t->set_var( "game_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "game_total", $totalCount );

$t->pparse( "output", "game_page" );

?>