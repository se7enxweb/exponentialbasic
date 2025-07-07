<?php
//
// $Id: modulelist.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

/*
  Viser liste over prioriteringer
*/
// include_once( "classes/INIFile.php" );
$ini = INIFile::globalINI();
$Language = $ini->read_var( "eZBugMain", "Language" );

// include_once( "classes/eztemplate.php" );

// include_once( "ezbug/classes/ezbugmodule.php" );

$t = new eZTemplate( "kernel/ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "kernel/ezbug/admin/intl", $Language, "modulelist.php" );
$t->setAllStrings();

$t->set_file( array(
    "module_page" =>  "modulelist.tpl"
    ) );

$t->set_block( "module_page", "module_item_tpl", "module_item" );
$t->set_block( "module_page", "path_item_tpl", "path_item" );

$t->set_var( "site_style", $SiteDesign );

$module = new eZBugModule( $ParentID );
$t->set_var( "this_id", $ParentID );


if( isset( $DeleteModules ) ) // delete selected modules
{
    if( count( $ModuleArrayID ) > 0 )
    {
        foreach( $ModuleArrayID as $itemID )
        {
            $delModule = new eZBugModule( $itemID );
            $delModule->delete();
        }
    }
}

// path
$pathArray = $module->path();

// print( count( $pathArray ) );
$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "module_id", $path[0] );

    $t->set_var( "module_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$moduleList = $module->getByParent( $module );

if ( count( $moduleList ) == 0 )
{
    $t->set_var( "module_item", "ingen moduler funnet" );
}
else
{
    $i=0;
    foreach( $moduleList as $moduleItem )
    {
        if ( ( $i %2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        $t->set_var( "module_id", $moduleItem->id() );
        $t->set_var( "module_name", $moduleItem->name() );
        
        $i++;
        $t->parse( "module_item", "module_item_tpl", true );
    }
} 

$t->pparse( "output", "module_page" );

?>