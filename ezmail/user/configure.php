<?php
//
// $Id: configure.php 6931 2001-09-04 13:20:16Z fh $
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
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezsession/classes/ezpreferences.php" );

include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "ezmail/classes/ezmailfilterrule.php" );

$user =& eZUser::currentUser();

if( isset( $Ok ) || isset( $NewAccount ) || isset( $NewFilter ) ||
    isset( $DeleteAccounts ) )
{
    // this strange way of enabling accounts makes sure that one user doesn't tamper
    // with the accounts of someone else.
    $accounts = eZMailAccount::getByUser( eZUser::currentUser() );
    foreach( $accounts as $account )
    {
        if( count( $AccountActiveArrayID ) > 0 &&
            in_array( $account->id(), $AccountActiveArrayID ) )
            $account->setIsActive( true );
        else
            $account->setIsActive( false );

        $account->store();
    }
    // save options
    if( $user )
    {
        if( isset( $OnDelete ) && $OnDelete == "trash" )
            eZPreferences::setVariable( "eZMail_OnDel", "trash" );
        else
            eZPreferences::setVariable( "eZMail_OnDel", "del" );

        if( isset( $Signature ) )
            eZPreferences::setVariable( "eZMail_Signature", $Signature );

        if( isset( $AutoSignature ) )
            eZPreferences::setVariable( "eZMail_AutoSignature", "true" );
        else
            eZPreferences::setVariable( "eZMail_AutoSignature", "false" );

        if( isset( $ShowUnread ) )
            eZPreferences::setVariable( "eZMail_ShowUnread", "true" );
        else
            eZPreferences::setVariable( "eZMail_ShowUnread", "false" );

        if( isset( $AutoCheckMail ) )
            eZPreferences::setVariable( "eZMail_AutoCheckMail", "true" );
        else
            eZPreferences::setVariable( "eZMail_AutoCheckMail", "false" );
    }
}

if( isset( $NewAccount ) )
{
    eZHTTPTool::header( "Location: /mail/accountedit" );
    exit();
}

if( isset( $NewFilter) )
{
    eZHTTPTool::header( "Location: /mail/filteredit" );
    exit();
}

if( isset( $DeleteAccounts ) && count( $AccountArrayID ) > 0  )
{
    foreach( $AccountArrayID as $accountID )
    {
        eZMailAccount::delete( $accountID );
    }
}
if( isset( $DeleteAccounts ) && count( $FilterArrayID ) > 0 ) 
{
    foreach( $FilterArrayID as $filterID )
    {
        eZMailFilterRule::delete( $filterID );
    }
}


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "configure.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_configure_page_tpl" => "configure.tpl"
    ) );

$t->set_var( "site_style", $siteDesign );
$t->set_block( "mail_configure_page_tpl", "account_item_tpl", "account_item" );
$t->set_block( "mail_configure_page_tpl", "filter_item_tpl", "filter_item" );
$t->set_var( "account_item", "" );
$t->set_var( "filter_item" ,"" );

// init options
$t->set_var( "trash_checked", "checked" );
$t->set_var( "delete_checked", "" );
$t->set_var( "signature", "" );
if( $user )
{
    $del_var =& eZPreferences::variable( "eZMail_OnDel" );
    if( $del_var == "del" )
    {
        $t->set_var( "trash_checked", "" );
        $t->set_var( "delete_checked", "checked" );
    }
    $signature =& eZPreferences::variable( "eZMail_Signature" );
    if( $signature )
        $t->set_var( "signature", htmlspecialchars( $signature ) );
    
    $t->set_var( "signature_checked", "" );
    $auto_signature =& eZPreferences::variable( "eZMail_AutoSignature" );
    if( $auto_signature && $auto_signature == "true" )
        $t->set_var( "signature_checked", "checked" );

    $t->set_var( "show_unread_checked", "" );
    $show_unread = eZPreferences::variable( "eZMail_ShowUnread" );
    if( $show_unread == "true" )
        $t->set_var( "show_unread_checked", "checked" );

    $t->set_var( "check_mail_checked", "" );
    $auto_check_mail = eZPreferences::variable( "eZMail_AutoCheckMail" );
    if( $auto_check_mail == "true" )
        $t->set_var( "check_mail_checked", "checked" );
}

$accounts = eZMailAccount::getByUser( $user->id() );
foreach( $accounts as $account )
{
    $t->set_var( "account_id", $account->id() );
    $t->set_var( "account_name", htmlspecialchars( $account->name() ) );
    $t->set_var( "account_type", $account->serverType() );
    $t->set_var( "account_folder", "" );
    $account->isActive() ? $t->set_var( "account_active_checked", "checked" ) : $t->set_var( "account_active_checked", "" );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "account_item", "account_item_tpl", true );
}

$filters = eZMailFilterRule::getByUser( $user->id() );
foreach( $filters as $filter )
{
    $t->set_var( "filter_id", $filter->id() );
    $t->set_var( "filter_name", htmlspecialchars( buildFilterName( $filter, $Language) ) );
    
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "filter_item", "filter_item_tpl", true );
}

$t->pparse( "output", "mail_configure_page_tpl" );

function buildFilterName( &$filter, $Language )
{
    $localINI = new INIFile( "ezmail/user/intl/" . $Language . "/configure.php.ini" );
    $if = $localINI->read_var( "strings", "if" );
    $move = $localINI->read_var( "strings", "move" );
    $headerName = "";
    switch( $filter->headerType() )
    {
        case FILTER_MESSAGE: $headerName = $localINI->read_var( "strings", "message"); break;
        case FILTER_BODY: $headerName = $localINI->read_var( "strings", "body"); break;
        case FILTER_ANY: $headerName = $localINI->read_var( "strings", "any_header"); break;
        case FILTER_TOCC: $headerName = $localINI->read_var( "strings", "tocc"); break;
        case FILTER_SUBJECT: $headerName = $localINI->read_var( "strings", "subject"); break;
        case FILTER_FROM: $headerName = $localINI->read_var( "strings", "from"); break;
        case FILTER_TO: $headerName = $localINI->read_var( "strings", "to"); break;
        case FILTER_CC: $headerName = $localINI->read_var( "strings", "cc"); break;
    }

    $checkName = "";
    switch( $filter->checkType() )
    {
        case FILTER_EQUALS: $checkName = $localINI->read_var( "strings", "equals"); break;
        case FILTER_NEQUALS: $checkName = $localINI->read_var( "strings", "nequals"); break;
        case FILTER_CONTAINS: $checkName = $localINI->read_var( "strings", "contains"); break;
        case FILTER_NCONTAINS: $checkName = $localINI->read_var( "strings", "ncontains"); break;
        case FILTER_REGEXP: $checkName = $localINI->read_var( "strings", "regexp"); break;
    }
    $folder = new eZMailFolder( $filter->folderID() );

    return $if ." " . $headerName ." " . $checkName . " " . " \"". $filter->match() . "\" " . $move . " \"" . $folder->name() . "\"";
}

?>