<?php
//
// $Id: subscriptionlist.php 8249 2001-11-02 08:23:41Z ce $
//
// Created on: <18-Apr-2001 13:36:21 fh>
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

// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
// include_once( "ezbulkmail/classes/ezbulkmailsubscriptionaddress.php" );
// include_once( "ezbulkmail/classes/ezbulkmailusersubscripter.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

$Language = $ini->read_var( "eZBulkMailMain", "Language" );
$TemplateDir = $ini->read_var( "eZBulkMailMain", "TemplateDir" );

if ( $ini->read_var( "eZBulkMailMain", "UseEZUser" ) == "enabled" )
{
    $user = eZUser::currentUser();
    $subscriptionaddress = new eZBulkMailUserSubscripter( $user );
    if( !is_object ( $user ) )
    {
        eZHTTPTool::header( "Location: /user/login/?RedirectURL=/bulkmail/subscriptionlist/" );
        exit();
    }
}
else
{
    $subscriptionaddress = eZBulkMailSubscriptionAddress::getByEmail( $session->variable( "BulkMailAddress" ) );
    if( is_object( $subscriptionaddress ) == false )
    {
        eZHTTPTool::header( "Location: /bulkmail/login" );
        exit();
    }

}



if( isset ( $Ok ) )
{
    $subscriptionaddress->unsubscribe( true );

    foreach( $CategoryArrayID as $categoryID )
    {
        $subscriptionaddress->subscribe( $categoryID );
    }

    for( $i=0;$i<count($CategoryAll);$i++ )
    {
        $subscriptionaddress->addDelay( $CategoryAll[$i], $SendDelay[$i] );
    }

    /** TODO: Create a confirmation dialog to send the user to... let him either edit or do nothing...**/
    eZHTTPTool::header( "Location: /bulkmail/subscriptionlist/" );
    exit();
}

$t = new eZTemplate( "ezbulkmail/user/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                     "ezbulkmail/user/intl", $Language, "subscriptionlist.php" );

$t->set_file( array(
    "subscription_list_tpl" => "subscriptionlist.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $SiteDesign );

$t->set_block( "subscription_list_tpl", "category_tpl", "category" );
$t->set_block( "category_tpl", "category_item_tpl", "category_item" );
$t->set_block( "subscription_list_tpl", "no_categories_tpl", "no_categories" );
$t->set_var( "category", "" );
$t->set_var( "category_item", "" );
$t->set_var( "email_value", "" );
$t->set_var( "current_email", "" );

// List all the avaliable categories if there is a valid current address
$haystack = $subscriptionaddress->subscriptions( false );

$categories = eZBulkMailCategory::getAll( false );
foreach ( $categories as $categoryitem )
{
    $t->set_var( "category_name", $categoryitem->name() );
    $t->set_var( "category_description", $categoryitem->description() );
    $t->set_var( "category_id", $categoryitem->id() );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    if( isset ( $haystack ) && in_array ( $categoryitem->id(), $haystack ) )
        $t->set_var( "is_checked", "checked" );
    else
        $t->set_var( "is_checked", "" );

    $setting = $categoryitem->settings( $subscriptionaddress );

    $t->set_var( "delay_0", "" );
    $t->set_var( "delay_1", "" );
    $t->set_var( "delay_2", "" );
    $t->set_var( "delay_3", "" );

    if ( is_a( $setting, "eZBulkMailCategorySettings" ) || is_a( $setting, "eZBulkMailUserCategorySettings" ) )
    {
        $delay = $setting->delay();
        if ( $delay == 1 )
            $t->set_var( "delay_1", "selected" );
        if ( $delay == 2 )
            $t->set_var( "delay_2", "selected" );
        if ( $delay == 3 )
            $t->set_var( "delay_3", "selected" );
        if ( $delay == 4 )
            $t->set_var( "delay_4", "selected" );
    }
    else
        $t->set_var( "delay_0", "selected" );


    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}
if ( $i > 0 )
{
    $t->set_var( "no_categories", "" );
    $t->parse( "category", "category_tpl" );
}
else
{
    $t->parse( "no_categories", "no_categories_tpl" );
}

$t->pparse( "output", "subscription_list_tpl" );
?>