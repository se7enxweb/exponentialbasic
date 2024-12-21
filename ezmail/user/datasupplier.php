<?php
//
// $Id: datasupplier.php 8870 2002-01-04 09:26:57Z jhe $
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

// include_once( "classes/ezhttptool.php" );
// include_once( "ezmail/classes/ezmailaccount.php" );
// include_once( "ezmail/classes/ezmailfolder.php" );
// include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZMailMain", "DefaultSection" );
$SiteDesign = $ini->read_var( "site", "SiteDesign" );

switch ( $url_array[2] )
{
    case "foldersort" : // change the sort mode of the folder list
    {
        $FolderID = $url_array[3];
        $SortMethod = $url_array[4];
        $Offset = 0;
        include( "ezmail/user/maillist.php" );
    }
    break;

    case "folder" :
    {
        $FolderID = $url_array[3];
        $Offset = $url_array[4];
        if ( $Offset == "" )
            $Offset = 0;
//        if ( $FolderID == "" )
//            $FolderID = get INBOX.
        
        include( "ezmail/user/maillist.php" );
    }
    break;

    case "view" :
    {
        $MailID = $url_array[3];
        include( "ezmail/user/mailview.php" );
    }
    break;

    case "folderedit" :
    {
        $FolderID = $url_array[3];
        if ( $FolderID == "" )
            $FolderID = 0;
        include( "ezmail/user/folderedit.php" );
    }
    break;

    case "folderlist" :
    {
        include( "ezmail/user/folderlist.php" );
    }
    break;
    
    case "mailedit" :
    {
        $MailID = $url_array[3];
        if ( $MailID == "" )
            $MailID = 0;
        include( "ezmail/user/mailedit.php" );
    }
    break;

    case "fileedit" :
    {
        $MailID = $url_array[3];
        if ( $MailID == "" )
            $MailID = 0;
        include( "ezmail/user/fileedit.php" );
    }
    break;
    
    case "config" :
    {
        include( "ezmail/user/configure.php" );
    }
    break;

    case "accountedit" :
    {
        $AccountID = $url_array[3];
        if ( $AccountID == "" )
            $AccountID = 0;
        include( "ezmail/user/accountedit.php" );
    }
    break;

    case "check" : // check the mail for this user!
    {

        $user =& eZUser::currentUser();
        $accounts = eZMailAccount::getByUser( $user->id() );

        foreach ( $accounts as $account )
        {
            if ( $account->isActive() )
                $account->checkMail();
        }

        eZHTTPTool::header( "Location: /mail/folderlist/" );
        exit();
//        $server = "{" . "zap.ez.no" . "/pop3:" . "110" ."}";
//        $mbox = imap_open( $server, "larson", "AcRXYJJA", OP_HALFOPEN)
//             or die("can't connect: ".imap_last_error());

//        $structure = imap_fetchstructure( $mbox, 1 );
//        echo "<pre>"; print_r( $structure ); echo "</pre>";
//        print( imap_fetchbody( $mbox, 1, 2 ) ); 
//        imap_close( $mbox );
    }
    break;

    case "filteredit" :
    {
        $FilterID = $url_array[3];
        if ( $FilterID == "" )
            $FilterID = 0;
        include( "ezmail/user/filteredit.php" );
    }
    break;

    case "search" :
    {
        include( "ezmail/user/search.php" );
    }
    break;

    case "link" :
    {
        $MailID = $url_array[3];
        include( "ezmail/user/link.php" );
    }
    break;
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }
    break;
}

?>