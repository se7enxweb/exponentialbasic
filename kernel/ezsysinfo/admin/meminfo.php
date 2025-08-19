<?php
// 
// $Id: meminfo.php 6231 2001-07-20 11:30:53Z jakobn $
//
// Created on: <22-Apr-2001 13:32:08 bf>
//
// This source file is part of Exponential Basic, publishing software.
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
                     "kernel/ezsysinfo/admin/intl/", $Language, "meminfo.php" );


$t->set_file( "mem_info_tpl", "meminfo.tpl" );

//$t->set_block( "hw_info_tpl", "hw_interface_tpl", "hw_interface" );

$t->setAllStrings();

$sys = eZSysinfo::cpu();
$scsi_bus = eZSysinfo::scsibus();

$t->set_var( "scsi_bus", $scsi_bus );

$mem = eZSysinfo::meminfo();

$t->set_var( "physical_free", eZSysinfo::format_bytesize(@$mem['ram']['t_free']) );
$t->set_var( "physical_used", eZSysinfo::format_bytesize(@$mem['ram']['t_used']) );
$t->set_var( "physical_total", eZSysinfo::format_bytesize(@$mem['ram']['total']) );

$t->set_var( "physical_percent", @$mem['ram']['percent'] );
$t->set_var( "physical_invert_percent", 100 - @$mem['ram']['percent'] );


$t->set_var( "swap_free", eZSysinfo::format_bytesize(@$mem['swap']['free']) );
$t->set_var( "swap_used", eZSysinfo::format_bytesize(@$mem['swap']['used']) );
$t->set_var( "swap_total", eZSysinfo::format_bytesize(@$mem['swap']['total']) );

$t->set_var( "swap_percent", @$mem['swap']['percent'] );
$t->set_var( "swap_invert_percent", 100 - @$mem['swap']['percent'] );



$t->pparse( "output", "mem_info_tpl" );

?>