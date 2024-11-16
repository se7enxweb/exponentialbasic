<?php
//
// $Id: loginheader.php 9331 2002-03-04 13:15:22Z ce $
//
// Created on: <23-Jan-2001 16:06:07 bf>
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
include_once( "classes/ezpublish.php" );

$ini =& INIFile::globalINI();
$Language =& $ini->read_var( "eZUserMain", "Language" );
$Locale = new eZLocale( $Language );
$iso = $Locale->languageISO();


$t = new eZTemplate( "design/admin/templates/" . $SiteStyle,
                     "ezuser/admin/intl/", $Language, "menubox.php" );
$t->set_file( array(
    "header_tpl" => "loginheader.tpl"
    ) );

$SiteURL =& $ini->read_var( "site", "SiteURL" );

$t->set_var( "site_url", $SiteURL );
$t->set_var( "site_style", $SiteStyle );

$moduleName = "user";
$t->set_var( "module_name", $moduleName );

$t->set_var( "charset", $iso );

$t->setAllStrings();
$t->set_var( "ezpublish_version", eZPublish::version() );

$t->pparse( "output", "header_tpl" );


?>
