<?php
//
// $Id: findwishlist.php 6233 2001-07-20 11:42:02Z jakobn $
//
// Created on: <15-Jan-2001 16:46:13 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "ezuser/classes/ezuser.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZTradeMain", "Language" );

// include_once( "eztrade/classes/ezwishlist.php" );
// include_once( "ezsession/classes/ezsession.php" );
// include_once( "ezmail/classes/ezmail.php" );



$t = new eZTemplate( "kernel/eztrade/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "findwishlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "findwishlist_page_tpl" => "findwishlist.tpl"
    ) );

$t->set_block( "findwishlist_page_tpl", "wishlist_tpl", "wishlist" );

$wishlist = new eZWishList();

if( isset( $SearchText ) )
    $wishLists = $wishlist->search( $SearchText );
else
    $wishLists = array();

if( isset( $SearchText ) )
    $t->set_var( "search_text", $SearchText );
else
    $t->set_var( "search_text", '' );

$t->set_var( "wishlist", "" );

$i=0;
foreach ( $wishLists as $wishlist )
{
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    
    $user = $wishlist->user();
    
    $t->set_var( "user_id", $user->id() );
    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );

    $t->parse( "wishlist", "wishlist_tpl", true );
    $i++;
}

    

$t->pparse( "output", "findwishlist_page_tpl" );

?>

