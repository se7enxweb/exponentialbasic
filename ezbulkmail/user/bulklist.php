<?php
// 
// $Id: bulklist.php 6417 2001-08-13 12:31:09Z ce $
//
// Created on: <07-Aug-2001 15:45:54 ce>
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

// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
// include_once( "ezbulkmail/classes/ezbulkmail.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/ezlist.php" );

if( isset( $Ok ) || isset( $New ) )
{
    eZHTTPTool::header( "Location: /bulkmail/subscriptionlist/" );
    exit();
}

$Language = $ini->read_var( "eZBulkMailMain", "Language" );
$t = new eZTemplate( "ezbulkmail/user/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                     "ezbulkmail/user/intl", $Language, "bulklist.php" );

$iniLanguage = new INIFile( "ezbulkmail/user/intl/" . $Language . "/bulklist.php.ini", false );

$locale = new eZLocale( $Language ); 
$t->set_file( array(
    "bulk_list_tpl" => "bulklist.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $SiteDesign );

$t->set_block( "bulk_list_tpl", "bulkmail_tpl", "bulkmail" );
$t->set_block( "bulkmail_tpl", "bulkmail_item_tpl", "bulkmail_item" );
$t->set_block( "bulk_list_tpl", "no_bulkmail_tpl", "no_bulkmail" );
$t->set_var( "bulkmail", "" );
$t->set_var( "bulkmail_item", "" );



if( is_numeric( $CategoryID ) && $CategoryID > 0 )
{
    $category = new eZBulkMailCategory( $CategoryID );
    $t->set_var( "current_category_name", $category->name() );
    $t->set_var( "current_category_id", $category->id() );
    $mail = $category->mail($Offset, 20, false );
    $mailCount = $category->mailCount();
    $i = 0;
    foreach( $mail as $mailItem )
    {
        $t->set_var( "bulkmail_id", $mailItem->id() );
        $t->set_var( "bulkmail_subject", $mailItem->subject() );

        if( !$mailItem->isDraft() )
        {
            $t->set_var( "sent_date", $locale->format( $mailItem->date() ) );
        }
        else
        {
            $t->set_var( "sent_date", $iniLanguage->read_var( "strings", "not_sent" ) );
        }
        ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
        $t->parse( "bulkmail_item", "bulkmail_item_tpl", true );
        $i++;
    }
    if( $i > 0 )
    {
        $t->set_var( "no_bulkmail", "" );
        $t->parse( "bulkmail", "bulkmail_tpl" );
    }
    else
    {
        $t->parse( "no_bulkmail", "no_bulkmail_tpl" );
    }

}
eZList::drawNavigator( $t, $mailCount, 20, $Offset, "bulk_list_tpl" );
$t->pparse( "output", "bulk_list_tpl" );
?>