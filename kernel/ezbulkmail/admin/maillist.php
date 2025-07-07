<?php
// 
// $Id: maillist.php 6209 2001-07-19 12:36:31Z jakobn $
//
// Created on: <18-Apr-2001 10:26:26 fh>
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

// include_once( "ezbulkmail/classes/ezbulkmail.php" );
// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

if( isset( $New ) )
{
    eZHTTPTool::header( "Location: /bulkmail/mailedit/" );
    exit();
}

if( isset( $Delete ) )
{
    foreach( $MailArrayID as $mailID )
        eZBulkMail::delete( $mailID );
}

$t = new eZTemplate( "kernel/ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "kernel/ezbulkmail/admin/intl", $Language, "maillist.php" );

$t->set_file( array(
    "bulkmail_list_tpl" => "maillist.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $SiteDesign );

$t->set_block( "bulkmail_list_tpl", "bulkmail_tpl", "bulkmail" );
$t->set_block( "bulkmail_tpl", "bulkmail_item_tpl", "bulkmail_item" );
$t->set_var( "bulkmail", "" );

/** List all the avaliable categories **/
$mail = eZBulkMail::getAll( true );
$i = 0;
foreach( $mail as $mailItem )
{
    $t->set_var( "bulkmail_subject", $mailItem->subject() );
    $categories = $mailItem->categories();
    if( count( $categories ) > 0 )
    {
        $t->set_var( "bulkmail_category", $categories[0]->name() );
    }

    $t->set_var( "bulkmail_id", $mailItem->id() );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
    $t->parse( "bulkmail_item", "bulkmail_item_tpl", true );
    $i++;
}
if( $i > 0 )
    $t->parse( "bulkmail", "bulkmail_tpl" );

$t->pparse( "output", "bulkmail_list_tpl" );

?>