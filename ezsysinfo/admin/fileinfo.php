<?php
// 
// $Id: fileinfo.php 6231 2001-07-20 11:30:53Z jakobn $
//
// Created on: <22-Apr-2001 14:16:46 bf>
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
$sum = array();

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezsysinfo/classes/ezsysinfo.php" );

$ini =& INIFile::globalINI();
$Language =& $ini->read_var( "eZSysinfoMain", "Language" );

$t = new eZTemplate( "ezsysinfo/admin/" . $ini->read_var( "eZSysinfoMain", "AdminTemplateDir" ),
                     "ezsysinfo/admin/intl/", $Language, "fileinfo.php" );


$t->set_file( "file_info_tpl", "fileinfo.tpl" );

$t->set_block( "file_info_tpl", "disk_tpl", "disk" );

$t->setAllStrings();

$fs = eZSysinfo::fsinfo();

for ( $i = 0; $i < sizeof($fs); $i++ )
{
    $sum['size'] = $fs[$i]['size'];
    $sum['used'] = $fs[$i]['used'];
    $sum['free'] = $fs[$i]['free'];

    $t->set_var( "mount_point", $fs[$i]['mount'] );
    $t->set_var( "fs_type", $fs[$i]['fstype'] );
    $t->set_var( "device", $fs[$i]['disk'] );    

    $t->set_var( "capacity_percent", $fs[$i]['percent'] );
    $t->set_var( "capacity_inverted_percent", 100 - (int) $fs[$i]['percent'] );

    $t->set_var( "free", eZSysinfo::format_bytesize( $fs[$i]['free'] ) );
    $t->set_var( "used", eZSysinfo::format_bytesize( $fs[$i]['used'] ) );
    $t->set_var( "total", eZSysinfo::format_bytesize( $fs[$i]['size'] ) );

    $t->parse( "disk", "disk_tpl", true );
}

$sum_percent = round( ($sum['used'] * 100) / $sum['size'] );

$t->set_var( "sum_capacity_percent", $sum_percent );
$t->set_var( "sum_capacity_inverted_percent", 100 - $sum_percent );

$t->set_var( "sum_free", eZSysinfo::format_bytesize( $sum['free'] ) );
$t->set_var( "sum_used", eZSysinfo::format_bytesize( $sum['used'] ) );
$t->set_var( "sum_total", eZSysinfo::format_bytesize( $sum['size'] ) );

$t->pparse( "output", "file_info_tpl" );

?>