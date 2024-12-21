<?php
//
// $Id: mailview.php 7828 2001-10-14 16:20:00Z fh $
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezmail/classes/ezmailfolder.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezsession/classes/ezpreferences.php" );

// Check if this really is your mail we are talking about here..
if ( !eZMail::isOwner( eZUser::currentUser(), $MailID ) )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

if ( isSet( $Cancel ) )
{
    $mail = new eZMail( $MailID );
    $folderID = $mail->folder( false );
    eZHTTPTool::header( "Location: /mail/folder/$folderID" );
    exit();
}

if ( isSet( $Reply ) )
{
    $mail = new eZMail( $MailID );
    $mail->setStatus( REPLIED, true );
    $reply = $mail->copyMail( "reply" );
    $replyid = $reply->id();
    $reply->setStatus( READ, true );
    
    $drafts = eZMailFolder::getSpecialFolder( DRAFTS );
    $drafts->addMail( $reply );
    
    eZHTTPTool::header( "Location: /mail/mailedit/$replyid" );
    exit();
}

if ( isSet( $ReplyAll ) )
{
    $mail = new eZMail( $MailID );
    $mail->setStatus( REPLIED, true );
    $reply = $mail->copyMail( "replyall" );
    $replyid = $reply->id();
    $reply->setStatus( READ, true );
    
    $drafts = eZMailFolder::getSpecialFolder( DRAFTS );
    $drafts->addMail( $reply );
    
    eZHTTPTool::header( "Location: /mail/mailedit/$replyid" );
    exit();
}

if ( isSet( $Forward ) )
{
    $mail = new eZMail( $MailID );
    $mail->setStatus( FORWARDED, true );
    $reply = $mail->copyMail( "forward" );
    $replyid = $reply->id();
    $reply->setStatus( READ, true );
    
    $drafts = eZMailFolder::getSpecialFolder( DRAFTS );
    $drafts->addMail( $reply );
    
    eZHTTPTool::header( "Location: /mail/mailedit/$replyid" );
    exit();
}

if ( isset( $Delete ) )
{
    $mail = new eZMail( $MailID );
    $folderID = $mail->folder( false );

    $del_var =& eZPreferences::variable( "eZMail_OnDel" );
    if( $del_var == "del" )
    {
        eZMail::delete( $MailID );
    }
    else // move to trash
    {
        $trash = eZMailFolder::getSpecialFolder( TRASH );
        $trash->addMail( $MailID );
    }
    eZHTTPTool::header( "Location: /mail/folder/$folderID" );
    exit();
}

if ( isSet( $Link ) )
{
    eZHTTPTool::header( "Location: /mail/link/$MailID" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "mailview.php" );
$t->setAllStrings();

$t->set_file( "mail_view_page_tpl", "mailview.tpl" );

$t->set_block( "mail_view_page_tpl", "cc_value_tpl", "cc_value" );
$t->set_block( "mail_view_page_tpl", "bcc_value_tpl", "bcc_value" );
$t->set_block( "mail_view_page_tpl", "inserted_attachments_tpl", "inserted_attachments" );
$t->set_block( "inserted_attachments_tpl", "attachment_tpl", "attachment" );
$t->set_var( "inserted_attachments", "" );
$t->set_var( "cc_value", "" );
$t->set_var( "bcc_value", "" );

$mail = new eZMail( $MailID );
if ( $mail->status() == UNREAD )
    $mail->setStatus( READ, true );
$t->set_var( "current_mail_id", $MailID );

$t->set_var( "to", htmlspecialchars( $mail->to() ) );
$t->set_var( "from", htmlspecialchars( $mail->from() ) );
$t->set_var( "subject", htmlspecialchars( $mail->subject() ) );

if ( $ini->read_var( "eZMailMain", "HTMLMail" ) == "enabled" )
    $t->set_var( "mail_body", nl2br( ( $mail->body() ) ) );
else
    $t->set_var( "mail_body", nl2br( htmlspecialchars( $mail->body() ) ) );

$t->set_var( "date", date("D M d H:i Y ", $mail->uDate() ) );

if ( $mail->cc() != "" )
{
    $t->set_var( "cc", htmlspecialchars( $mail->cc() ) );
    $t->parse( "cc_value", "cc_value_tpl", false );
}

if ( $mail->bcc() != "" )
{
    $t->set_var( "bcc", htmlspecialchars( $mail->bcc() ) );
    $t->parse( "bcc_value", "bcc_value_tpl", false );
}

$files = $mail->files();
$i = 0;
foreach ( $files as $file )
{
    $t->set_var( "file_name", "<a href=\"$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/filemanager/download/" . $file->id() . "/" . $file->originalFileName() . "/ \">" . htmlspecialchars( $file->originalFileName() ) . "</a>" );
    $t->set_var( "file_id", $file->id() );
    
    $size = $file->siFileSize();
    $t->set_var( "file_size", $size["size-string"] . $size["unit"] );
    
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
    $t->parse( "attachment", "attachment_tpl", true );
    $i++;
}

if ( $i > 0 )
    $t->parse( "inserted_attachments", "inserted_attachments_tpl", false );

$t->pparse( "output", "mail_view_page_tpl" );

?>
