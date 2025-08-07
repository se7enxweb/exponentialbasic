<?php
// 
// $Id: userlist.php 8088 2001-10-29 19:10:11Z fh $
//
// Created on: <28-Aug-2001 15:02:02 fh>
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

// include_once( "ezbulkmail/classes/ezbulkmailsubscriptionaddress.php" );
// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZBulkMailMain", "Language" );

$t = new eZTemplate( "kernel/ezbulkmail/admin/" . $ini->variable( "eZBulkMailMain", "AdminTemplateDir" ),
                     "kernel/ezbulkmail/admin/intl/", $Language, "userlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "user_list_page_tpl" => "userlist.tpl"
    ) );

$t->set_block( "user_list_page_tpl", "category_item_tpl", "category_item" );
$t->set_var( "category_item", "" );

$t->set_block( "user_list_page_tpl", "address_tpl", "address" );
$t->set_block( "address_tpl", "address_item_tpl", "address_item" );
$t->set_var( "address", "" );

$t->set_block( "user_list_page_tpl", "user_address_tpl", "user_address" );
$t->set_block( "user_address_tpl", "user_address_item_tpl", "user_address_item" );
$t->set_var( "user_address", "" );


$t->set_block( "user_list_page_tpl", "address_group_tpl", "address_group" );
$t->set_block( "address_group_tpl", "address_item_group_tpl", "address_item_group" );
$t->set_var( "address_group", "" );

$t->set_block( "user_list_page_tpl", "no_subscribers_tpl", "no_subscribers" );
$t->set_var( "no_subscribers", "" );

// some logic to set the CategoryID right. (we can get it both from url and from the list)
if( isset( $ListID ) && is_numeric( $ListID ) )
    $CategoryID = $ListID;

$categories = eZBulkMailCategory::getAll();
foreach( $categories as $category )
{
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );
    if( $CategoryID == $category->id() )
        $t->set_var( "category_selected", "selected" );
    else
        $t->set_var( "category_selected", "" );
        
    $t->parse( "category_item", "category_item_tpl", true );
}

if( $CategoryID != 0 )
{
    // list normal subscribers..
    $i = 0;
    $addresses = eZBulkMailCategory::subscribers( true, $CategoryID );
    $normal = 0;
    $normalUser = 0;
    if( count( $addresses ) > 0 )
    {
        foreach( $addresses as $address )
        {
            $t->set_var( "subscriber_address", $address->eMail() );
            ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
            $t->parse( "address_item", "address_item_tpl", true );
            $i++; $normal++;
        }
    }
    // list eZUser subscribers.
    $addresses = eZBulkMailCategory::subscribedUsers( $CategoryID );
    if( count( $addresses ) > 0 )
    {
        foreach( $addresses as $address )
        {
            $t->set_var( "subscriber_address", $address->eMail() );
            ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
            $t->parse( "user_address_item", "user_address_item_tpl", true );
            $i++; $normalUser++;
        }
    }

    // list users forced by group addition
    $groups = eZBulkMailCategory::groupSubscriptions( true, $CategoryID );
    foreach( $groups as $group )
        $subscribers = array_merge( $subscribers, $group->users() );

    $group = 0;
    if( count( $subscribers ) > 0 )
    {
        foreach( $subscribers as $subscriber )
        {
            $t->set_var( "subscriber_address_group", $subscriber->eMail() );
            ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
            $t->parse( "address_item_group", "address_item_group_tpl", true );
            $i++; $group++;
        }
    }

    if( $i > 0 )
    {
        if( $normal > 0 )
            $t->parse( "address", "address_tpl", false );
        if( $normalUser > 0 )
            $t->parse( "user_address", "user_address_tpl", false );
        if( $group > 0 )
            $t->parse( "address_group", "address_group_tpl", false );
    }
    else
    {
        $t->parse( "no_subscribers", "no_subscribers_tpl", false );
    }
}

$t->pparse( "output", "user_list_page_tpl" );

?>