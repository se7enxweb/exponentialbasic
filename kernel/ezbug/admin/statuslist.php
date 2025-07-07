<?php
//
// $Id: statuslist.php 8504 2001-11-19 09:46:46Z jhe $
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

/*
  Viser liste over prioriteringer
*/
// include_once( "classes/INIFile.php" );
$ini = INIFile::globalINI();
$Language = $ini->read_var( "eZBugMain", "Language" );
$LanguageIni = new INIFIle( "kernel/ezbug/admin/intl/" . $Language . "/statuslist.php.ini", false );


// include_once( "classes/eztemplate.php" );

// include_once( "ezbug/classes/ezbugstatus.php" );

$t = new eZTemplate( "kernel/ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "kernel/ezbug/admin/intl", $Language, "statuslist.php" );
$t->setAllStrings();

$t->set_file( array(
    "status_page" =>  "statuslist.tpl"
    ) );

$t->set_block( "status_page", "status_item_tpl", "status_item" );

//$t->set_var( "site_style", $SiteDesign );

if( isset( $Ok ) || isset( $AddStatus ) )
{
    $i = 0;
    if( count( $StatusID ) > 0 )
    {
        foreach( $StatusID as $itemID )
        {
            $status = new eZBugStatus( $itemID );
            $status->setName( $StatusName[$i] );
            $status->store();
            $i++;
        }
    }
}

if( isset( $AddStatus ) )
{
    $newItem = new eZBugStatus();
    $newName = $LanguageIni->read_var( "strings", "newstatus" );
    $newItem->setName($newName);
    $newItem->store();
}

if( isset( $DeleteStatus ) )
{
    if( count( $StatusArrayID ) > 0 )
    {
        foreach( $StatusArrayID as $deleteItemID )
        {
            $item = new eZBugStatus( $StatusID[ $deleteItemID ] );
            $item->delete();
        }
    }

}


$status = new eZBugStatus();
$statusList = $status->getAll();

$i=0;
foreach( $statusList as $statusItem )
{
    if ( ( $i %2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
        
    $t->set_var( "status_id", $statusItem->id() );
    $t->set_var( "status_name", $statusItem->name() );
    $t->set_var( "index_nr", $i );
    
    $t->parse( "status_item", "status_item_tpl", true );
    $i++;
} 

$t->pparse( "output", "status_page" );

?>