<?php
//
// $Id: mailedit.php 9363 2002-03-23 13:24:54Z fh $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezsession/classes/ezpreferences.php" );

if ( $MailID != 0 && !eZMail::isOwner( eZUser::currentUser(), $MailID ) )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

if ( isSet( $Cancel ) )
{
    if ( $MailID != 0 )
    {
        $mail = new eZMail( $MailID );
        $folderID = $mail->folder( false );
    }
    else
    {
        $inbox = eZMailFolder::getSpecialFolder( INBOX );
        $folderID = $inbox->id();
    }
    eZHTTPTool::header( "Location: /mail/folder/$folderID" );
    exit();
}

if ( isSet( $ToButton ) )
{
    eZHTTPTool::header( "Location: /contact/person/list" );
    exit();
}

if ( isSet( $AddAttachment ) )
{
    $MailID = save_mail();
    eZHTTPTool::header( "Location: /mail/fileedit/$MailID" );
    exit();
}

if ( isSet( $DeleteAttachments ) && count( $AttachmentArrayID ) > 0 )
{
    foreach ( $AttachmentArrayID as $attachmentID )
    {
        $mail = new eZMail( $MailID );
        $file = new eZVirtualFile( $attachmentID );
        $mail->deleteFile( $file );
    }
}

if ( isSet( $Save ) )
{
    $MailID = save_mail();
    if ( isSet( $IDList ) )
    {
        $id_array = split( ";", $IDList );
        foreach ( $id_array as $idItem )
        {
            eZMail::addContact( $MailID, $idItem, $CompanyList );
        }
    }
    $mail = new eZMail( $MailID );
    $mail->setStatus( READ, true );

    $drafts = eZMailFolder::getSpecialFolder( DRAFTS );
    $drafts->addMail( $mail );
}

if ( isSet( $Preview ) )
{
    $MailID = save_mail();
    eZHTTPTool::header( "Location: /mail/view/$MailID" );
    exit();
}

if ( isSet( $Send ) )
{
    $MailID = save_mail();
    if ( isSet( $IDList ) )
    {
        $id_array = split( ";", $IDList );
        foreach ( $id_array as $idItem )
        {
            if ( is_numeric( $idItem ) )
                eZMail::addContact( $MailID, $idItem, $CompanyList );
        }
    }
    // give error message if no valid users where supplied...
    $mail = new eZMail( $MailID );
    if ( $mail->to() == "" && $mail->bcc() == "" && $mail->cc() == "" )
    {
        $error = "no_address";
    }
    else
    {
        $mail->setStatus( MAIL_SENT, true );
        $mail->send();

        $sent = eZMailFolder::getSpecialFolder( SENT );
        $sent->addMail( $mail );
    
        $sentid = $sent->id();
        eZHTTPTool::header( "Location: /mail/folder/$sentid" );
        exit();
    }
}

if ( isSet( $CcButton ) )
    $showcc = true;
if ( isSet( $BccButton ) )
    $showbcc = true;

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "mailedit.php" );

$languageIni = new INIFile( "ezmail/user/intl/" . $Language . "/mailedit.php.ini", false );
$t->setAllStrings();

$t->set_file( "mail_edit_page_tpl", "mailedit.tpl" );

$t->set_block( "mail_edit_page_tpl", "error_message_tpl", "error_message" );
$t->set_block( "mail_edit_page_tpl", "attachment_delete_tpl", "attachment_delete" );
$t->set_block( "mail_edit_page_tpl", "inserted_attachments_tpl", "inserted_attachments" );
$t->set_block( "mail_edit_page_tpl", "bcc_single_tpl", "bcc_single" );
$t->set_block( "mail_edit_page_tpl", "cc_single_tpl", "cc_single" );
$t->set_block( "inserted_attachments_tpl", "attachment_tpl", "attachment" );
$t->set_var( "inserted_attachments", "" );
$t->set_var( "attachment_delete", "" );

$t->set_var( "error_message", "" );
$t->set_var( "site_style", $SiteStyle );

$to_string = "";
$id_string = "";
$company_list = false;
for ( $i = 0; $i < count( $toArray["Email"] ); $i++ )
{
    $to_string .= $toArray["Email"][$i];
    $id_string .= $toArray["ID"][$i];
    if ( ( $i + 1 ) < count( $toArray["Email"] ) )
    {
        $to_string .= "; ";
        $id_string .= ";";
    }
    else
    {
        $company_list = $toArray["CompanyEdit"];
    }
}

$t->set_var( "to_value", $to_string );
$t->set_var( "id_value", $id_string );
$t->set_var( "company_value", $company_list );
$t->set_var( "from_value", "" );
$t->set_var( "cc_value", "" );
$t->set_var( "bcc_value", "" );
$t->set_var( "subject_value", "" );
$t->set_var( "mail_body", "" );
$t->set_var( "current_mail_id", "" );
$t->set_var( "cc_single", "" );
$t->set_var( "bcc_single", "" );

// New mail, lets insert some default values 
if ( $MailID == 0 )
{
    $auto_signature =& eZPreferences::variable( "eZMail_AutoSignature" );
    $signature =& eZPreferences::variable( "eZMail_Signature" );
    if( $auto_signature && $auto_signature == "true" && $signature != "" )
    {
        $comp_sign = "--\n$signature";
        $t->set_var( "mail_body", htmlspecialchars( $comp_sign  ) );
    }
}
$user =& eZUser::currentUser();
$t->set_var( "from_value", $user->email() );

// We are editing an allready existant mail... lets insert it's values 
if ( $MailID != 0 && eZMail::isOwner( $user, $MailID ) ) // load values from disk!, check that this is really current users mail
{
    $t->set_var( "current_mail_id", $MailID );
    
    $mail = new eZMail( $MailID );
    $t->set_var( "to_value", htmlspecialchars( $mail->to() ) );

    if ( $mail->from() != "" )
        $t->set_var( "from_value", htmlspecialchars( $mail->from() ) );
    $t->set_var( "subject_value", htmlspecialchars( $mail->subject() ) );

    if( isset( $Signature ) )
    {
        $signature =& eZPreferences::variable( "eZMail_Signature" );
        $mail_body = $mail->body();
        $comp_sign = "$mail_body\n\n--\n$signature";
        $t->set_var( "mail_body", htmlspecialchars( $comp_sign  ) );
    }
    else
    {
        $t->set_var( "mail_body", htmlspecialchars( $mail->body() ) );
    }
    
    if ( $mail->cc() != ""  )
    {
        $showcc = true;
        $t->set_var( "cc_value", htmlspecialchars( $mail->cc() ) );
    }

    if ( $mail->bcc() != "" )
    {
        $showbcc = true;
        $t->set_var( "bcc_value", htmlspecialchars( $mail->bcc() ) );
    }

    $files = $mail->files();
    $i = 0;
    foreach ( $files as $file )
    {
        $t->set_var( "file_name", htmlspecialchars( $file->originalFileName() ) );
        $t->set_var( "file_id", $file->id() );

        $size = $file->siFileSize();
        $t->set_var( "file_size", $size["size-string"] . $size["unit"] );

        ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
        
        $t->parse( "attachment", "attachment_tpl", true );
        $i++;
    }
    if ( $i > 0 )
    {
        $t->parse( "attachment_delete", "attachment_delete_tpl" );
        $t->parse( "inserted_attachments", "inserted_attachments_tpl", false );
    }
}
else if ( $MailID == 0 && ( $showcc || $showbcc || $Signature ) ) //mail not saved, but there is data
{
    $t->set_var( "to_value", htmlspecialchars( $To ) );
    $t->set_var( "id_value", $IDList );
    $t->set_var( "company_value", $CompanyList );
    $t->set_var( "from_value", htmlspecialchars( $From ) );
    $t->set_var( "cc_value", htmlspecialchars( $Cc ) );
    $t->set_var( "bcc_value", htmlspecialchars( $Bcc ) );
    $t->set_var( "subject_value",  htmlspecialchars( $Subject ) );
    if( $Signature )
    {
        $signature =& eZPreferences::variable( "eZMail_Signature" );
        if( $signature != "" )
        {
            $comp_sign = "$MailBody\n\n--\n$signature";
            $t->set_var( "mail_body", htmlspecialchars( $comp_sign  ) );
        }
    }
    else
    {
        $t->set_var( "mail_body", htmlspecialchars( $MailBody ) );
    }
    if ( $Cc != "" )
        $showcc = true;
    if ( $Bcc != "" )
        $showbcc = true;
}

// check if we have any errors... if yes. show them to the user
if ( isSet( $error ) )
{
    $t->set_var( "mail_error_message", $languageIni->read_var( "strings", "address_error" ) );
    $t->parse( "error_message", "error_message_tpl", true );
}

if ( isSet( $showcc ) )
        $t->parse( "cc_single", "cc_single_tpl", false );
if ( isSet( $showbcc ) )
        $t->parse( "bcc_single", "bcc_single_tpl", false );

$t->pparse( "output", "mail_edit_page_tpl" );

/*********************** FUNCTIONS ***************************************/

/*
  Saves the mail and returns the ID of the saved mail.
 */
function save_mail()
{
    global $To, $From, $Cc, $Bcc, $Subject, $MailBody, $MailID; // instead of passing them as arguments..

    if ( $MailID == 0 )
    {
        $mail = new eZMail();
        $mail->setOwner( eZUser::currentUser() );
    }
    else
    {
        $mail = new eZMail( $MailID );
    }
    $mail->setTo( $To );
    $mail->setFrom( $From  ); // from NAME
    $mail->setCc( $Cc );
    $mail->setBcc( $Bcc );
//    $mail->setReferences( );
//    $mail->setReplyTo( $ );
    $mail->setSubject( $Subject );
    $mail->setBodyText( $MailBody );
    $mail->calculateSize();
    
    $mail->store();
    $folder = eZMailFolder::getSpecialFolder( DRAFTS );
    $folder->addMail( $mail );

    return $mail->id();
}

?>
