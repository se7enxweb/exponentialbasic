<?php
// 
// $Id: menubox.php 6232 2001-07-20 11:36:07Z jakobn $
//
// Created on: <16-Jan-2001 13:23:02 ce>
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

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZTodoMain", "Language" );

    
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdb.php" );

$t = new eZTemplate( "kernel/eztodo/user/" . $ini->variable( "eZTodoMain", "TemplateDir" ),
                     "kernel/eztodo/user/intl", $Language, "menubox.php" );

$t->setAllStrings();

$t->set_file( array(
    "menu_box_tpl" => "menubox.tpl"
    ) );

$t->set_var( "sitedesign", $GlobalSiteDesign );    

$t->pparse( "output", "menu_box_tpl" );

?>