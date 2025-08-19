<?
// 
// $Id: menubox.php,v 1.2 2001/04/11 14:18:40 th Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// This source file is part of Exponential Basic, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
// include_once( "classes/ezdb.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZGroupEventCalendarMain", "Language" );

$t = new eZTemplate( "kernel/ezgroupeventcalendar/user/" . $ini->variable( "eZGroupEventCalendarMain", "TemplateDir" ),
                     "kernel/ezgroupeventcalendar/user/intl", $Language, "menubox.php" );

$t->setAllStrings();

$t->set_file( array(
    "menu_box_tpl" => "menubox.tpl"
    ) );

$t->set_var( "sitedesign", $GlobalSiteDesign );

$t->pparse( "output", "menu_box_tpl" );
		
?>