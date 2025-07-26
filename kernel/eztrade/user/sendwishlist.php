<?php
//
// $Id: sendwishlist.php 8850 2002-01-02 21:42:12Z kaid $
//
// Created on: <15-Jan-2001 14:17:36 bf>
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
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );

// include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();
$wwwDir = $ini->WWWDir;
$indexFile = $ini->Index;

$Language = $ini->read_var( "eZTradeMain", "Language" );

// include_once( "eztrade/classes/ezwishlist.php" );
// include_once( "ezsession/classes/ezsession.php" );
// include_once( "ezmail/classes/ezmail.php" );


$wishlist = new eZWishList();
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

$user =& eZUser::currentUser();

if ( !$user )
{
     eZHTTPTool::header( "Location: /trade/customerlogin/?RedirectURL=/trade/wishlist/" );
    exit();
}

$wishlist = $wishlist->getByUser( $user );

if ( !$wishlist )
{
    print( "creating a wishlist" );
    $wishlist = new eZWishlist();
    $wishlist->setUser( $user );

    $wishlist->store();
     eZHTTPTool::header( "Location: /trade/wishlist/" );
    exit();    
}

if ( isset( $Action ) && $Action == "SendWishlist" )
{
    $SendToArray = explode( ",", $SendTo );

    $correctEmails = true;
    foreach ( $SendToArray as $toAddress )
    {
        if ( !eZMail::validate( trim( $toAddress ) ) )
        {
            $correctEmails = false;
        }
    }
    

    if ( $correctEmails )
    {
        $mail = new eZMail();
        $mailTemplate = new eZTemplate( "kernel/eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                                        "kernel/eztrade/user/intl/", $Language, "wishlistmail.php" );

        $mailTemplate->setAllStrings();
        
        $mailTemplate->set_file( array(
            "wishlist_mail_tpl" => "wishlistmail.tpl"
            ) );

        $mailTemplate->set_block( "wishlist_mail_tpl", "subject_tpl", "subject" );
        $mailTemplate->set_block( "wishlist_mail_tpl", "product_tpl", "product" );

        $headersInfo = getallheaders();

        $mail->setFrom( $user->email() );
        
        $mailTemplate->set_var( "first_name", $user->firstName() );

        $mailTemplate->set_var( "host_name", $headersInfo["Host"].$indexFile );
        $mailTemplate->set_var( "user_id", $user->id() );

        $subject = $mailTemplate->parse( "dummy", "subject_tpl" );
        $mailTemplate->set_var( "subject", "" );

        $mailTemplate->set_var( "message", $Message );


        $items = $wishlist->items();

        foreach ( $items as $item )
        {
            $mailTemplate->set_var( "host_name", $headersInfo["Host"].$indexFile );
            
            $product =& $item->product();
            $mailTemplate->set_var( "product_id", $product->id() );
            $mailTemplate->set_var( "product_name", $product->name() );
            $mailTemplate->parse( "product", "product_tpl", true );            
        }

        $mail->setSubject( $subject );
        $mail->setBody( $mailTemplate->parse( "dummy", "wishlist_mail_tpl" ) );


        // send to multiplce recipients
        foreach ( $SendToArray as $toAddress )
        {
            $mail->setTo( trim( $toAddress ) );            
            $mail->send();
        }

         eZHTTPTool::header( "Location: /trade/sendwishlist/success/" );
        exit();        
    }
    else
    {
        $EmailError = true;
        print( "Email error" );
        
    }
    
}


$t = new eZTemplate( "kernel/eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "sendwishlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "sendwishlist_page_tpl" => "sendwishlist.tpl"
    ) );

$t->set_block( "sendwishlist_page_tpl", "wishlist_sendt_tpl", "wishlist_sendt" );
$t->set_block( "sendwishlist_page_tpl", "wishlist_empty_tpl", "wishlist_empty" );
$t->set_block( "sendwishlist_page_tpl", "wishlist_private_tpl", "wishlist_private" );
$t->set_block( "sendwishlist_page_tpl", "send_wishlist_tpl", "send_wishlist" );

if ( count( $wishlist->items() ) == 0 )
{
    $t->parse( "wishlist_empty", "wishlist_empty_tpl" );

    $t->set_var( "send_wishlist", "" );
    $t->set_var( "wishlist_sendt", "" );
    $t->set_var( "wishlist_private", "" );
}
else if ( $wishlist->isPublic() == false )
{
    $t->parse( "wishlist_private", "wishlist_private_tpl" );
    
    $t->set_var( "wishlist_empty", "" );
    $t->set_var( "send_wishlist", "" );
    $t->set_var( "wishlist_sendt", "" );

}
else
{
    $t->set_var( "wishlist_private", "" );    
    $t->set_var( "wishlist_empty", "" );

    if ( $url_array[3] == "success" )
    {
        $t->parse( "wishlist_sendt", "wishlist_sendt_tpl" );
        $t->set_var( "send_wishlist", "" );
    }
    else
    {
        $t->set_var( "wishlist_sendt", "" );
        $t->parse( "send_wishlist", "send_wishlist_tpl" );
    }
}
    
$locale = new eZLocale( $Language );


$t->pparse( "output", "sendwishlist_page_tpl" );

?>