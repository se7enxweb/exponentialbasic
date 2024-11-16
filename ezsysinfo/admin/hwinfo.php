<?php
// 
// $Id: hwinfo.php 6231 2001-07-20 11:30:53Z jakobn $
//
// Created on: <22-Apr-2001 13:32:08 bf>
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


include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezsysinfo/classes/ezsysinfo.php" );

$ini =& INIFile::globalINI();
$Language =& $ini->read_var( "eZSysinfoMain", "Language" );

$t = new eZTemplate( "ezsysinfo/admin/" . $ini->read_var( "eZSysinfoMain", "AdminTemplateDir" ),
                     "ezsysinfo/admin/intl/", $Language, "hwinfo.php" );


$t->set_file( "hw_info_tpl", "hwinfo.tpl" );

//$t->set_block( "hw_info_tpl", "hw_interface_tpl", "hw_interface" );

$t->setAllStrings();

$sys = eZSysinfo::cpu();

$t->set_var( "cpu_num", $sys['cpus'] );
$t->set_var( "cpu_model", $sys['model'] );
$t->set_var( "cpu_speed", $sys['mhz'] );
$t->set_var( "cpu_cache", $sys['cache'] );
$t->set_var( "cpu_bogomips", $sys['bogomips'] );

$ar_buf = eZSysinfo::pcibus();

$pci_bus = "";
if ( count( $ar_buf ) )
{
    for ( $i = 0; $i < sizeof($ar_buf); $i++ )
    {
        $pci_bus .= $ar_buf[$i] . '<br />';
    }
}

$t->set_var( "pci_bus", $pci_bus );

$ar_buf = eZSysinfo::idebus(); 

$ide_bus = "";
ksort( $ar_buf );
if ( count( $ar_buf ) )
{
    while ( list($key, $value) = each( $ar_buf ) )
    {
        $ide_bus .= $key . ": " . $ar_buf[$key]["model"];
        if ( isset( $ar_buf[$key]["capacity"] ) )
        {
            $ide_bus .= " (Capacity: " . sprintf("%.2f", $ar_buf[$key]["capacity"] / (1024 * 1024 * 2) ) . " GB )";
        }
        $ide_bus .= '<br>';
    }
}

$t->set_var( "ide_bus", $ide_bus );

$ar_buf = eZSysinfo::scsibus(); 

$scsi_bus = "";
if ( count( $ar_buf ) )
{
    for ( $i = 0; $i < sizeof($ar_buf); $i++ )
    {
        $scsi_bus .= $ar_buf[$i] . '<br>';
    }
}

$t->set_var( "scsi_bus", $scsi_bus );

$t->pparse( "output", "hw_info_tpl" );

?>
