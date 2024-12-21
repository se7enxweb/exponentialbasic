<?php
// 
// $Id: sysinfo.php 6231 2001-07-20 11:30:53Z jakobn $
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

$ini =& INIFile::globalINI();
$Language =& $ini->read_var( "eZSysinfoMain", "Language" );

$t = new eZTemplate( "ezsysinfo/admin/" . $ini->read_var( "eZSysinfoMain", "AdminTemplateDir" ),
                     "ezsysinfo/admin/intl/", $Language, "sysinfo.php" );


$t->set_file( "sys_info_tpl", "sysinfo.tpl" );

$t->setAllStrings();

$t->set_var( "host_name", eZSysinfo::chostname() );
$t->set_var( "host_ip", eZSysinfo::ip_addr() );
$t->set_var( "kernel", eZSysinfo::kernel() );
$t->set_var( "uptime", eZSysinfo::uptime() );
$t->set_var( "users", eZSysinfo::users() );

$loadAvg = eZSysinfo::loadavg();

$t->set_var( "load_1", $loadAvg[0] );
$t->set_var( "load_2", $loadAvg[1] );
$t->set_var( "load_3", $loadAvg[2] );



$t->pparse( "output", "sys_info_tpl" );
    


?>


<?php //include('includes/table_hardware.php'); ?>

<?php //include('includes/table_memory.php'); ?>

<?php //include('includes/table_filesystems.php'); ?>
