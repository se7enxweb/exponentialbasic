<?php
// 
// $Id: topiclist.php 6206 2001-07-19 12:19:22Z jakobn $
//
// Created on: <01-Jun-2001 11:58:53 bf>
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

// include_once( "ezarticle/classes/eztopic.php" );

if ( isset( $NewTopic ) )
{
    $i=0;
    if ( count( $IDArray ) > 0 )
    {
        foreach ( $IDArray as $id )
        {
            $topic = new eZTopic( $id );
            $topic->setDescription( $Description[$i] );
            $topic->setName( $Name[$i] );
            $topic->store();

            $i++;
        }
    }
    $topic = new eZTopic( );
    $topic->store();    
}

if ( isset( $DeleteTopic ) )
{
    foreach ( $DeleteIDArray as $id )
    {
        $topic = new eZTopic( $id );
        $topic->delete();
    }
}

if ( isset( $Store ) )
{
    $i=0;
    if ( count( $IDArray ) > 0 )
    {
        foreach ( $IDArray as $id )
        {
            $topic = new eZTopic( $id );
            $topic->setDescription( $Description[$i] );
            $topic->setName( $Name[$i] );
            $topic->store();

            $i++;
        }
    }
}

$t = new eZTemplate( "kernel/ezarticle/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "kernel/ezarticle/admin/intl", $Language, "topiclist.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "topic_page_tpl", "topiclist.tpl" );

$t->setAllStrings();

$t->set_block( "topic_page_tpl", "topic_list_tpl", "topic_list" );
$t->set_block( "topic_list_tpl", "topic_item_tpl", "topic_item" );

$topic = new eZTopic( );

$topicArray = $topic->getAll();

$t->set_var( "topic_item", "" );
$i=0;
foreach ( $topicArray as $topic )
{
    $t->set_var( "id", $topic->id() );
    $t->set_var( "topic_name", $topic->name() );
    $t->set_var( "topic_description", $topic->description() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    $t->parse( "topic_item", "topic_item_tpl", true );
    $i++;
	
}
$t->parse( "topic_list", "topic_list_tpl" );

$t->pparse( "output", "topic_page_tpl" );

?>
