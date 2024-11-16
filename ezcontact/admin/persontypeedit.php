<?php
//
// $Id: persontypeedit.php 9529 2002-05-14 11:17:05Z jhe $
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
  Redigerer person typer.
*/

include_once( "classes/INIFile.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "common/ezphputils.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );

require( "ezuser/admin/admincheck.php" );

// Legge til
if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminAdd" ) )
    {
        $type = new eZPersonType();
        $type->setName( $PersonTypeName );
        $type->setDescription( $PersonTypeDescription );
        $type->store();

        eZHTTPTool::header( "Location: /contact/persontypelist/" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

// Oppdatere
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminModify" ) )
    {
        $type = new eZPersonType();
        $type->get( $PID );
        print ( "$PID ..." );

        $type->setName( $PersonTypeName );
        $type->setDescription( $PersonTypeDescription );
        $type->update();

        eZHTTPTool::header( "Location: /contact/persontypelist/" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

// Slette
if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminDelete" ) )
    {
        $type = new eZPersonType();
        $type->get( $PID );
        $type->delete( );
        eZHTTPTool::header( "Location: /contact/persontypelist/" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "persontypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "persontype_edit_page" => "persontypeedit.tpl"
    ) );    

$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "persontype_id", "" );
$t->set_var( "head_line", "Legg til ny persontype" );

// Editere
if ( $Action == "edit" )
{
    $type = new eZPersonType();
    $type->get( $PID );
  
    $PersonTypeName = $type->name();
    $PersonTypeDescription = $type->description();

    $t->set_var( "submit_text", "Lagre endringer" );
    $t->set_var( "action_value", "update" );
    $t->set_var( "persontype_id", $PID );
    $t->set_var( "head_line", "Rediger persontype" );

}

// Sette tempalte variabler
$t->set_var( "document_root", $DOC_ROOT );
$t->set_var( "persontype_name", $PersonTypeName );
$t->set_var( "description", $PersonTypeDescription );

$t->pparse( "output", "persontype_edit_page" );
?>
