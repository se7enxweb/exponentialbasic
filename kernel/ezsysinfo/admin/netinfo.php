<?php
// 
// $Id: netinfo.php 6231 2001-07-20 11:30:53Z jakobn $
//
// Created on: <21-Apr-2001 12:11:59 bf>
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
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezsysinfo/classes/ezsysinfo.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language =& $ini->variable( "eZSysinfoMain", "Language" );

$t = new eZTemplate( "kernel/ezsysinfo/admin/" . $ini->variable( "eZSysinfoMain", "AdminTemplateDir" ),
                     "kernel/ezsysinfo/admin/intl/", $Language, "netinfo.php" );


$t->set_file( "net_info_tpl", "netinfo.tpl" );

$t->set_block( "net_info_tpl", "net_interface_tpl", "net_interface" );

$t->setAllStrings();

$net = eZSysinfo::netdevs();

$t->set_var( "net_interface", "" );
foreach ($net as $dev => $stats)
{
    $t->set_var( "device", $dev );
    $t->set_var( "received", eZSysinfo::format_bytesize( $stats['rx_bytes'] / 1024 )  );
    $t->set_var( "sent", eZSysinfo::format_bytesize( $stats['tx_bytes'] / 1024 ) );
    $t->set_var( "error", $stats['errs'] . '/' . $stats['drop'] );
    

    $t->parse( "net_interface", "net_interface_tpl", true );
}

$t->pparse( "output", "net_info_tpl" );
    
?>