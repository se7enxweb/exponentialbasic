<?php
// 
// $Id: footer.php 7590 2001-09-29 12:35:15Z kaid $
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

//include_once( "kernel/classes/INIFile.php" );
$ini =& eZINI::instance('site.ini');

$Language = $ini->variable( "eZArticleMain", "Language" );

// include_once( "kernel/classes/template.inc" );
// $t = new Template( $siteDir . "design/admin/templates/" . $SiteDesign );
// $t->set_var( "www_dir", $GlobalSiteIni->WWWDir );
// $t->set_var( "index", $GlobalSiteIni->Index );

//include_once( "kernel/classes/eztemplate.php" );
$t = new eZTemplate( "design/admin/templates/" . $siteDesign,
                     "design/admin/intl/", $Language, "footer.php" );

$t->set_file( array(
    "footer_tpl" => "footer.tpl"
    ) );

$t->set_var( "site_style", $siteDesign );
$t->set_var( "module_dir", $moduleName );

$t->pparse( "output", "footer_tpl" );