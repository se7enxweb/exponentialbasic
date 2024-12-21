<?php
// 
// $Id: masssubscribe.php 8088 2001-10-29 19:10:11Z fh $
//
// Created on: <14-May-2001 15:02:02 ce>
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
// include_once( "ezmail/classes/ezmail.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBulkMailMain", "Language" );

$t = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "ezbulkmail/admin/intl/", $Language, "masssubscribe.php" );

$t->setAllStrings();

$t->set_file( array(
    "mass_subscribe_page" => "masssubscribe.tpl"
    ) );

$t->set_block( "mass_subscribe_page", "new_email_list_tpl", "new_email_list" );
$t->set_block( "new_email_list_tpl", "new_email_item_tpl", "new_email_item" );

$t->set_block( "mass_subscribe_page", "category_item_tpl", "category_item" );

$t->set_block( "mass_subscribe_page", "email_exists_list_tpl", "email_exists_list" );
$t->set_block( "email_exists_list_tpl", "email_exists_item_tpl", "email_exists_item" );

$t->set_block( "mass_subscribe_page", "not_valid_list_tpl", "not_valid_list" );
$t->set_block( "not_valid_list_tpl", "not_valid_item_tpl", "not_valid_item" );

$t->set_var( "new_email_list", "" );
$t->set_var( "email_exists_list", "" );
$t->set_var( "not_valid_list", "" );

$t->set_var( "addresses", isset( $Addresses ) ? $Addresses : false );

if ( isSet ( $OK ) && ( count ( $CategoryArrayID ) > 0 ) )
{
    unset ( $addresses );
    $addresses = explode( "\n", $Addresses );

    if ( count ( $addresses ) > 0 )
    {
        $mailTemplate = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                                        "ezbulkmail/admin/intl", $Language, "sendmail.php" );
        
        $languageIni = new INIFile( "ezbulkmail/admin/intl/$Language/sendmail.php.ini" );
        $mailTemplate->setAllStrings();
        $mailTemplate->set_file( "send_mail_tpl", "sendmail.tpl" );
        $mailTemplate->set_block( "send_mail_tpl", "mail_password_tpl", "mail_password" );
        $mailTemplate->set_var( "mail_password", "" );
        
        foreach ( $addresses as $address )
        {
            $email = trim( $address );
            if ( eZMail::validate( $email ) )
            {
                $bulkMail = eZBulkMailSubscriptionAddress::getByEmail( $email );

                $sendPassword = false;
                if ( $bulkMail->addressExists ( $email ) == false )
                {
                    $password = substr( md5( microtime() ), 0, 4 );
                    $bulkMail->setEncryptetPassword( $password );
                    $bulkMail->store();
                    $sendPassword = true;
                }

               
                foreach( $CategoryArrayID as $CategoryID )
                {
                    if ( $bulkMail->subscribe( $CategoryID ) )
                    {
                        $category = new eZBulkMailCategory( $CategoryID );
                        $new[] = array( $email, $category->name() );
                        if ( $SendMail == "on" )
                        {
                            $mailTemplate->set_var( "category_name", $category->name() );

                            if ( $sendPassword )
                            {
                                $mailTemplate->set_var( "password", $password );
                                $mailTemplate->parse( "mail_password", "mail_password_tpl" );
                            }
                            
                            $mail = new eZMail();
                            $mail->setSubject( $languageIni->read_var( "strings", "mail_subject" ) );
                            $mail->setTo( $email );
                            $mail->setBody( $mailTemplate->parse( "dummy", "send_mail_tpl" ) );
                            $mail->setFrom( $GlobalSiteIni->read_var( "eZBulkMailMain", "BulkmailSenderAddress" ) );
                           
                            $mail->send();
                        }
                    }
                    else
                        $exists[] = $email;
                }
            }
            else
                $notValid[] = $email;
        }
    }

    if ( count ( $new ) > 0 )
    {
        foreach ( $new as $email )
        {
            $t->set_var( "new_email", $email[0] );
            $t->set_var( "new_category", $email[1] );
            $t->parse( "new_email_item", "new_email_item_tpl", true );
        }
        $t->parse( "new_email_list", "new_email_list_tpl", true );
    }

    if ( count ( $exists ) > 0 )
    {
        foreach ( $exists as $email )
        {
            $t->set_var( "email_exists", $email );
            $t->parse( "email_exists_item", "email_exists_item_tpl", true );
        }
        $t->parse( "email_exists_list", "email_exists_list_tpl", true );
    }
    
    if ( count ( $notValid ) > 0 )
    {
        foreach ( $notValid as $email )
        {
            $t->set_var( "not_valid", $email );
            $t->parse( "not_valid_item", "not_valid_item_tpl", true );
        }
        $t->parse( "not_valid_list", "not_valid_list_tpl", true );
    }
}

$categoryList =& eZBulkMailCategory::getAll();

if ( count ( $categoryList ) > 0 )
{
    foreach( $categoryList as $category )
    {
        $t->set_var( "category_id", $category->id() );
        $t->set_var( "category_name", $category->name() );
        $t->parse( "category_item", "category_item_tpl", true );
    }
}
    
$t->pparse( "output", "mass_subscribe_page" );

?>