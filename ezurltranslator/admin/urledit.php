<?php
// 
// $Id: urledit.php 7910 2001-10-16 17:22:36Z fh $
//
// Created on: <24-Apr-2001 11:09:30 bf>
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

include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

include_once( "ezurltranslator/classes/ezurltranslator.php" );

if ( isset( $DeleteURL ) && count( $DeleteIDArray ) > 0 )
{
    $url = new eZURLTranslator( );
    foreach ( $DeleteIDArray as $id )
    {
        $url = new eZURLTranslator( $id );
        $url->delete();
    }
}


if ( isset( $NewURL ) )
{
    
    $url = new eZURLTranslator( );
    $url->store();
}

if ( isset( $Store ) )
{
    $i=0;
    foreach ( $IDArray as $id )
    {
        $url = new eZURLTranslator( $id );
        $url->setSource( $SourceURL[$i] );
        $url->setDest( $DestURL[$i] );
        $url->store();

        $i++;
    }

}

$Language = $ini->read_var( "eZURLTranslatorMain", "Language" );

$t = new eZTemplate( "ezurltranslator/admin/" . $ini->read_var( "eZURLTranslatorMain", "AdminTemplateDir" ),
                     "ezurltranslator/admin/intl", $Language, "urledit.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "url_edit_tpl", "urledit.tpl" );

$t->setAllStrings();

$t->set_block( "url_edit_tpl", "url_list_tpl", "url_list" );
$t->set_block( "url_list_tpl", "url_item_tpl", "url_item" );
$t->set_var( "url_item", "" );

$url = new eZURLTranslator( );

$urlArray = $url->getAll();

foreach ( $urlArray as $url )
{
    $t->set_var( "id", $url->id() );
    $t->set_var( "source_url", $url->source() );
    $t->set_var( "dest_url", $url->dest() );

    $t->parse( "url_item", "url_item_tpl", true );
}
$t->parse( "url_list", "url_list_tpl" );


$t->pparse( "output", "url_edit_tpl" );
?>
