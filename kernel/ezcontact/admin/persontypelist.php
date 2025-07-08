<?php
//
// $Id: persontypelist.php 6237 2001-07-20 12:01:51Z jakobn $
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
  Viser liste over person typer.
*/
// include_once( "classes/INIFile.php" );

$ini =& $GlobalSiteIni;
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

// include_once( "classes/eztemplate.php" );
// include_once( "common/ezphputils.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );

// include_once( "ezcontact/classes/ezperson.php" );
// include_once( "ezcontact/classes/ezpersontype.php" );

require( "kernel/ezuser/admin/admincheck.php" );

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "persontypelist.php" );
$t->setAllStrings();

$t->set_file( array(
    "persontype_page" => "persontypelist.tpl",
    "persontype_item" => "persontypeitem.tpl"
    ) );    

$persontype = new eZPersonType();
$persontype_array = $persontype->getAll();

for ( $i=0; $i<count( $persontype_array ); $i++ )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "bg_color", "#eeeeee" );
    }
    else
    {
        $t->set_var( "bg_color", "#dddddd" );
    }

    $t->set_var( "persontype_id", $persontype_array[$i][ "ID" ] );
    $t->set_var( "persontype_name", $persontype_array[$i][ "Name" ] );
    $t->set_var( "description", $persontype_array[$i][ "Description" ] );
    $t->parse( "persontype_list", "persontype_item", true );
}

$t->set_var( "document_root", $DOC_ROOT );
$t->pparse( "output", "persontype_page" );

?>