<?php
//
// $Id: success.php 6220 2001-07-20 11:15:21Z jakobn $
//
// Created on: <14-Sep-2000 19:37:17 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZLinkMain", "Language" );
$DOC_ROOT = $ini->variable( "eZLinkMain", "DocumentRoot" );

// include_once( "ezlink/classes/ezlinkgroup.php" );
// include_once( "ezlink/classes/ezlink.php" );
// include_once( "ezlink/classes/ezhit.php" );


$t = new eZTemplate( "kernel/ezlink/user/" . $ini->variable( "eZLinkMain", "TemplateDir" ),
"kernel/ezlink/user/intl", $Language, "success.php" );
$t->setAllStrings();

$t->set_file( array(
    "success" => "success.tpl"
    ));

$t->pparse( "output", "success" );

?>
