<?php
// 
// $Id: mailtofriend.php 9839 2003-06-05 09:57:30Z br $
//
// Created on: <18-Jun-2001 16:37:47 br>
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

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZArticleMain", "Language" );
$Sender = $ini->variable( "ezArticleMain", "MailToFriendSender" );

// sections
// include_once( "ezsitemanager/classes/ezsection.php" );

$CategoryID = $url_array[5];

if ( !strstr( $HTTP_REFERER, $ini->variable( "eZArticleMain", "FromURL" ) ) )
{
  die( "SendToFriend is not configured correctly. Please check your site.ini file" );
} 

// tempo fix for admin users - maybe in the future must be changed
if ( ($CategoryID != 0) )
{
    $GlobalSectionID = eZArticleCategory::sectionIDstatic ( $CategoryID );
}
	
// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$tpl = new eZTemplate( "kernel/ezarticle/user/" . $ini->variable( "eZArticleMain", "TemplateDir" ),
                       "kernel/ezarticle/user/" . "intl", $Language, "mailtofriend.php" );

$tpl->set_file( "mailtofriend_tpl" ,"mailtofriend.tpl" );
$tpl->setAllStrings();

$tpl->set_block( "mailtofriend_tpl", "first_page_tpl", "first_page" );

// Error messages.

$tpl->set_block( "first_page_tpl", "err_msg_tpl", "err_msg" );
$tpl->set_block( "first_page_tpl", "err_real_name_tpl", "err_real_name" );
$tpl->set_block( "first_page_tpl", "err_send_to_tpl", "err_send_to" );
$tpl->set_block( "first_page_tpl", "err_from_tpl", "err_from" );

// The success message.

$tpl->set_block( "mailtofriend_tpl", "success_tpl", "success" );
$tpl->set_block( "success_tpl", "user_comment_tpl", "user_comment" );

$tpl->set_var( "section_id", $GlobalSectionID );
//$tpl->set_var( "category_id", $CategoryID );

// Own eZTemplate object for create the mail.message to send.

$sendmail_tpl = new eZTemplate( "kernel/ezarticle/user/" . $ini->variable( "eZArticleMain", "TemplateDir" ),
                       "kernel/ezarticle/user/" . "intl", $Language, "sendmailtofriend.php" );
$sendmail_tpl->setAllStrings();
$sendmail_tpl->set_file( "sendmailtofriend_tpl", "sendmailtofriend.tpl" );

// Set subject

$sendmail_tpl->set_block( "sendmailtofriend_tpl", "mail_subject_tpl", "mail_subject" );

// Build up the mail.

$sendmail_tpl->set_block( "sendmailtofriend_tpl", "mail_body_tpl", "mail_body" );
$sendmail_tpl->set_block( "mail_body_tpl", "mail_comment_tpl", "mail_comment" );
$sendmail_tpl->set_block( "mail_body_tpl", "article_url_tpl", "article_url");

// Set all variables to "". Then we don't get any {err_msg} to output if the variable is emty.

$tpl->set_var( "first_page", "" );
$tpl->set_var( "err_msg", "" );
$tpl->set_var( "err_real_name", "" );
$tpl->set_var( "err_send_to", "" );
$tpl->set_var( "err_from", "" );
$tpl->set_var( "success", "" );
$tpl->set_var( "user_comment", "" );

$tpl->set_var( "site_url", $GLOBALS["HTTP_HOST"] );
$tpl->set_var( "art_id", $ArticleID);

$sendmail_tpl->set_var( "mail_subject", "" );
$sendmail_tpl->set_var( "mail_body", "" );
$sendmail_tpl->set_var( "article_url", "" );
$sendmail_tpl->set_var( "mail_comment", "" );

// check wich button is pressed and error check the input fields.

if ( isset( $Submit ) || isset( $RealName ) ||  isset( $SendTo  ) || isset( $From ) )
{
    $errorArr = array();
//    if ( (  trim( $RealName ) == "" ) )
//        $errorArr["real_name"] = true;
    
    if (! ( eZMail::validate( $SendTo ) ) )
        $errorArr["send_to"] = true;

    if (! ( eZMail::validate( $From ) ) )
        $errorArr["from"] = true;

    if( !isset( $Textarea ) )
        $Textarea = false;

    if( !isset( $RealName ) )
        $RealName = false;

    if ( $errorArr )
        errorMsg( $ArticleID, $CategoryID, $tpl, $RealName, $SendTo, $From, $Textarea, $errorArr );
    else
        sendmail( $ArticleID, $CategoryID, $tpl, $sendmail_tpl, $RealName, $SendTo, $From, $Textarea, $Sender );
} else
{
    printForm( $ArticleID, $CategoryID, $tpl );
}

/*!
  build up the mail to send.
 */
function sendmail ( $article_id, $CategoryID, $tpl, $sendmail_tpl, $real_name, $to_name, $from_name, $text=false, $Sender=false )
{
    $article = getArticle( $article_id );
    $renderer = new eZArticleRenderer( $article );
    $name = $article->name( );
    $intro = strip_tags( $renderer->renderIntro( ) );
    $text = trim( $text );
    
    $site_url = $_SERVER["HTTP_HOST"];
    // $server_name = $_SERVER["SERVER_NAME"];
    // $server_name = $GLOBALS["HTTP_HOST"];

    $ini =& eZINI::instance( 'site.ini' );
	$server_name = $ini->variable( "site", "SiteURL" );
    
// Build up the mail to send from the template.
    
    $sendmail_tpl->set_var( "server_name", $server_name );
    $mail_subject = $sendmail_tpl->parse( "mail_subject", "mail_subject_tpl" );
    
    $sendmail_tpl->set_var( "server_name", $server_name );
    $sendmail_tpl->set_var( "from_name", $from_name );

    if ( $text != "" )
    {
        $sendmail_tpl->set_var( "comment", $text );
        $sendmail_tpl->parse( "mail_comment", "mail_comment_tpl" );
    }
    $sendmail_tpl->set_var( "name", $name );
    $sendmail_tpl->set_var( "intro", $intro );
    $mail_body = $sendmail_tpl->parse( "mail_body", "mail_body_tpl" );

    $sendmail_tpl->set_var( "real_name", $real_name );
    $sendmail_tpl->set_var( "site_url", $site_url );
    $sendmail_tpl->set_var( "art_id", $article_id );
    $sendmail_tpl->set_var( "category_id", $CategoryID );
    $mail_body .= $sendmail_tpl->parse( "article_url", "article_url_tpl" );

// Send the mail.
    
    $mail = new eZMail();
    $mail->setFromName( $real_name );
    $mail->setTo( $to_name );
    $mail->setFrom( $Sender );
    $mail->setSubject( $mail_subject );
    $mail->setBodyText( $mail_body );
    $mail->send();
    
// print a successfull message to the webpage
    $tpl->set_var( "to_name", htmlspecialchars( $to_name ) );
    $tpl->set_var( "server_name", $server_name );
    $tpl->set_var( "from_name", htmlspecialchars( $from_name ) );
    if ( $text != "" )
    {
        $tpl->set_var( "user_comment", htmlspecialchars( $text ) );
        $tpl->parse( "user_comment", "user_comment_tpl" );
    }
    $tpl->set_var( "header_text", $name );
    $tpl->set_var( "intro_text", $intro );
    $tpl->set_var( "site_url", $site_url );
    $tpl->set_var( "art_id", $article->id() );
    $tpl->set_var( "category_id", $CategoryID );    

//    $category = $article->categoryDefinition();
//    if ( $category )
//    {
//        $tpl->set_var( "category_name", $category->name() );
//        $tpl->set_var( "category_id", $category->id() );
//    }
    $tpl->parse( "success", "success_tpl" );
    $tpl->pparse( "output", "mailtofriend_tpl" );
}



/*!
  Print an error msg if wrong input from the form.
 */
function errorMsg ( $article_id, $CategoryID, $tpl, $real_name, $send_to, $from, $textarea, $error )
{
    $tpl->parse( "err_msg", "err_msg_tpl" );

    if ( $error["real_name"] == true)
    {
        $tpl->parse( "err_real_name", "err_real_name_tpl" );
    }
    if ( $error["send_to"] == true )
    {
        $tpl->parse( "err_send_to", "err_send_to_tpl" );
    }
    if ( $error["from"] == true )
    {
        $tpl->parse( "err_from", "err_from_tpl" );
    }
    
    printForm( $article_id, $CategoryID, $tpl,  $real_name, $send_to, $from, $textarea );
}

/*!
  Return an article with the given number.
*/
function getArticle ( $ArticleID )
{
    $article = new eZArticle();
    if ( $article->get( $ArticleID ) )
    {
        return $article;
    } else
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }
}

/*!
  print the first page.
 */
function printForm ( $ArticleID, $CategoryID, $tpl, $real_name="", $send_to="", $from="", $textarea="" )
{

    $article = getArticle( $ArticleID );
    $renderer = new eZArticleRenderer( $article );
    
    $name = $article->name( );
    $intro = strip_tags( $renderer->renderIntro( ) );

//    $category = $article->categoryDefinition();
//    if ( $category )
//    {
//        $tpl->set_var( "category_name", $category->name() );
//	$tpl->set_var( "category_id", $category->id() );
//    }
    
    $tpl->set_var( "category_id", $CategoryID );
    
    $tpl->set_var( "Topic", $name );
    $tpl->set_var( "Intro", $intro );
    $tpl->set_var( "real_name", htmlspecialchars( $real_name ) );
    $tpl->set_var( "send_to", htmlspecialchars( $send_to ) );
    $tpl->set_var( "from", htmlspecialchars( $from ) );
    $tpl->set_var( "textarea", htmlspecialchars( $textarea ) );
    $tpl->parse( "first_page", "first_page_tpl" );
    $tpl->pparse( "output", "mailtofriend_tpl" );
}

?>