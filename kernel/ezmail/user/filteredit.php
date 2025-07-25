<?php
//
// $Id: filteredit.php 6221 2001-07-20 11:18:28Z jakobn $
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
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezmail/classes/ezmailfilterrule.php" );
// include_once( "ezmail/classes/ezmailfolder.php" );

if( isset( $Ok ) )
{
    if( $FilterID == 0 )
    {
        $filter = new eZMailFilterRule();
        $filter->setOwner( eZUser::currentUser() );
        $filter->setIsActive( true );
    }
    else
    {
        $filter = new eZMailFilterRUle( $FilterID );
    }

    $filter->setHeaderType( $HeaderSelect );
    $filter->setCheckType( $CheckSelect );
    $filter->setFolderID( $FolderSelectID );
    $filter->setMatch( $Match );
    $filter->store();
    eZHTTPTool::header( "Location: /mail/config/" );
    exit();
}

if( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /mail/config/" );
    exit();
}


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "kernel/ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "kernel/ezmail/user/intl/", $Language, "filteredit.php" );
$t->setAllStrings();

$t->set_file( array(
    "filter_edit_page_tpl" => "filteredit.tpl"
    ) );

$t->set_block( "filter_edit_page_tpl", "header_item_tpl", "header_item" );
$t->set_block( "filter_edit_page_tpl", "check_item_tpl", "check_item" );
$t->set_block( "filter_edit_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_var( "check_item", "" );
$t->set_var( "header_item", "" );
$t->set_var( "match_value", "" );
$t->set_var( "folder_item", "" );
$t->set_var( "current_filter_id", "" );

if( $FilterID != 0 ) // someone set us up the bomb
{
    $t->set_var( "current_filter_id", $FilterID );
    $filter = new eZMailFilterRule( $FilterID );
    $t->set_var( "match_value", htmlspecialchars( $filter->match() ) );
    $headerid = $filter->headerType();
    $checkid = $filter->checkType();
    $folderid = $filter->folderID();
}

$localINI = new INIFile( "kernel/ezmail/user/intl/" . $Language . "/filteredit.php.ini" );
foreach( array( 0, 1, 2, 3, 4, 5, 6, 7 ) as $headerID )
{
    $headerName = "";
    switch( $headerID )
    {
        case 0: $headerName = $localINI->read_var( "strings", "message"); break;
        case 1: $headerName = $localINI->read_var( "strings", "body"); break;
        case 2: $headerName = $localINI->read_var( "strings", "any_header"); break;
        case 3: $headerName = $localINI->read_var( "strings", "tocc"); break;
        case 4: $headerName = $localINI->read_var( "strings", "subject"); break;
        case 5: $headerName = $localINI->read_var( "strings", "from"); break;
        case 6: $headerName = $localINI->read_var( "strings", "to"); break;
        case 7: $headerName = $localINI->read_var( "strings", "cc"); break;
    }
    $t->set_var( "header_id", $headerID );
    $t->set_var( "header_name", $headerName  );
    if( isset( $headerid ) && $headerid == $headerID )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );

    $t->parse( "header_item", "header_item_tpl", true );
}

foreach( array( 0, 1, 2, 3, 4  ) as $checkID )
{
    $checkName = "";
    switch( $checkID )
    {
        case 0: $checkName = $localINI->read_var( "strings", "equals"); break;
        case 1: $checkName = $localINI->read_var( "strings", "nequals"); break;
        case 2: $checkName = $localINI->read_var( "strings", "contains"); break;
        case 3: $checkName = $localINI->read_var( "strings", "ncontains"); break;
        case 4: $checkName = $localINI->read_var( "strings", "regexp"); break;
    }
    $t->set_var( "check_id", $checkID );
    $t->set_var( "check_name", $checkName  );
    if( isset( $checkid ) && $checkid == $checkID )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );
    $t->parse( "check_item", "check_item_tpl", true );
} 

$folders = eZMailFolder::getByUser();
$folders[] = eZMailFolder::getSpecialFolder( TRASH );
foreach( $folders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    if( isset( $folderid ) && $folderid == $folderItem->id() )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );
    $t->parse( "folder_item", "folder_item_tpl", true );
}

$t->pparse( "output", "filter_edit_page_tpl" );

?>