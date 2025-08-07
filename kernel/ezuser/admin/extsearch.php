<?php
//
// $Id: extsearch.php 9460 2002-04-24 07:23:43Z bf $
//
// Created on: <24-Apr-2002 09:02:33 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZUserMain", "Language" );

require( "kernel/ezuser/admin/admincheck.php" );

$t = new eZTemplate( "kernel/ezuser/admin/" . $ini->variable( "eZUserMain", "AdminTemplateDir" ),
                     "kernel/ezuser/admin/" . "/intl", $Language, "extsearch.php" );
$t->setAllStrings();

$t->set_file( "extended_search", "extsearch.tpl" );

$t->pparse( "output", "extended_search" );

?>