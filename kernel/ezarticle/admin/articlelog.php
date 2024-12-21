<?php
// 
// $Id: articlelog.php 7882 2001-10-16 11:33:52Z ce $
//
// Created on: <05-Jun-2001 14:38:04 bf>
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

// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );


$t = new eZTemplate( "kernel/ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "kernel/ezarticle/admin/intl", $Language, "articlelog.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "log_page_tpl", "articlelog.tpl" );

$t->setAllStrings();

$t->set_block( "log_page_tpl", "log_list_tpl", "log_list" );
$t->set_block( "log_list_tpl", "log_item_tpl", "log_item" );

$article = new eZArticle( $ArticleID );

$t->set_var( "article_id", $ArticleID );

$logArray = $article->logMessages();


foreach ( $logArray as $message )
{
    $dateTime = new eZDateTime();    
    $dateTime->timeStamp( $message["Created"] );
    $t->set_var( "log_date", $locale->format( $dateTime ) );

    $user = new eZUser( $message["UserID"] );
    $t->set_var( "log_user", $user->firstName() . " " . $user->lastName() );     

    $t->set_var( "log_message", $message["Message"] );

    $t->parse( "log_item", "log_item_tpl", true );
}
if ( count( $logArray ) > 0 )
    $t->parse( "log_list", "log_list_tpl" );
else
    $t->set_var( "log_list", "" );


$t->pparse( "output", "log_page_tpl" );

?>
