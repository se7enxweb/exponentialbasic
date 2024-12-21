<?php
// 
// $Id: menubox.php 6931 2001-09-04 13:20:16Z fh $
//
// Created on: <23-Mar-2001 10:57:04 fh>
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

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZMailMain", "Language" );

    
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdb.php" );
// include_once( "ezmail/classes/ezmailfolder.php" );
// include_once( "ezsession/classes/ezpreferences.php" );

$user =& eZUser::currentUser();
if( $user )
{
    $t = new eZTemplate( "kernel/ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                         "kernel/ezmail/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "mail_folder_tpl", "mail_folder" );
    $t->set_block( "menu_box_tpl", "mail_check_tpl", "mail_check" );
    $t->set_var( "mail_check", "" );
    
    // auto check mail if enabled.
    if( eZPreferences::variable( "eZMail_AutoCheckMail" ) == "true" )
    {
        // include_once( "ezmail/classes/ezmailaccount.php" );
        $accounts = eZMailAccount::getByUser( $user->id() );
        foreach( $accounts as $account )
        {
            if( $account->isActive() )
                $account->checkMail();
        }
    }
    else
    {
        $t->parse( "mail_check", "mail_check_tpl", false );
    }

    $showUnread = eZPreferences::variable( "eZMail_ShowUnread" ) == "true" ? true : false;
    foreach( array( 'INBOX', 'SENT', 'DRAFTS', 'TRASH' ) as $specialfolder )
    {
        $folderItem = eZMailFolder::getSpecialFolder( $specialfolder );

	if( $folderItem )
	{
	    $t->set_var( "folder_id", $folderItem->id() );
            $t->set_var( "folder_name", htmlspecialchars( $folderItem->name() ) );
            $t->set_var( "indent", "");
      	    $t->set_var( "unread", "" );
	    
            if( $showUnread )
            {
		$num = $folderItem->count( true );
                if( $num > 0 )
                    $t->set_var( "unread", "($num)" );
            }
            $t->parse( "mail_folder", "mail_folder_tpl", true );
	}
	else
	{
	    $t->set_var('mail_folder','');
	}
    }
    
    $userFolders = eZMailFolder::getTree( 0, -1);
    foreach( $userFolders as $folderItem )
    {
        $t->set_var( "folder_id", $folderItem[0] );
        $t->set_var( "folder_name", htmlspecialchars( $folderItem[1] ) );
        $t->set_var( "indent", str_repeat( "&nbsp;", 2 * $folderItem[2] ) );
        $t->set_var( "unread", "" );
        if( $showUnread )
        {
            $num = $folderItem->count( true );
            if( $num > 0 )
                $t->set_var( "unread", "($num)" );
        }
        $t->parse( "mail_folder", "mail_folder_tpl", true );
    }
    
    $t->set_var( "sitedesign", $GlobalSiteDesign );

    $t->pparse( "output", "menu_box_tpl" );
}

?>
