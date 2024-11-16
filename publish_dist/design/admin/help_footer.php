<?php
// 
// $Id: help_footer.php 7941 2001-10-17 12:50:54Z bf $
//
// Created on: <20-Apr-2001 15:16:45 bf>
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

$Language = $ini->read_var( "eZArticleMain", "Language" );

include_once( "classes/template.inc" );

$t = new Template( "admin/templates/" . $SiteStyle );

$t->set_file( "help_footer_tpl", "help_footer.tpl" );

$t->set_var( "site_style", $SiteStyle );
$t->set_var( "module_dir", $moduleName );


$t->pparse( "output", "help_footer_tpl" );
    

?>

