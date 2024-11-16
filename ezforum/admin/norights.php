<?php
// $Id: norights.php 6218 2001-07-19 13:17:55Z jakobn $
//
// Created on: Created on: <14-Jul-2000 13:41:35 lw>
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

include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/ezdb.php" );
include_once( "classes/eztemplate.php" );

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
"ezforum/admin/" . "/intl", $Language, "noright.php" );
$t->setAllStrings();

$t->set_file( array( "norights" => "norights.tpl"
                     ) );

$t->pparse( "output", "norights" );
?>
