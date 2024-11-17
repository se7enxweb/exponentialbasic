<?php
// 
// $Id: page5.php 6212 2001-07-19 12:48:35Z jakobn $
//
// Created on: <22-Jun-2001 13:25:39 br>
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

// include the class files.

include_once( "classes/eztemplate.php" );
include_once( "ezexample/classes/ezexample.php" );

$textfield = new eZExample( );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZExampleMain", "Language" );
$tpl = new eZTemplate( "ezexample/admin/" . $ini->read_var( "eZExampleMain", "AdminTemplateDir" ),
                       "ezexample/admin/" . "intl", $Language, "page4.php" );
$tpl->setAllStrings();

// check which button is pressed.

if ( isset( $New ) )
{
    $exmpl = new eZExample( );
    $exmpl->setText( "" );
    $exmpl->store();
    updateValues( $IDArray, $TextArray );
}

if ( isset( $Update ) )
{
    updateValues( $IDArray, $TextArray );
}

if ( isset( $Delete ) )
{
    foreach( $DeleteArrayID as $id )
    {
        eZExample::delete( $id );
    }
}

// get all fields from the database.

$textfieldArray =& $textfield->getAll();

// parse the template.

$tpl->set_file( "page4_tpl", "page4.tpl" );
$tpl->set_block( "page4_tpl", "row_tpl", "row" );
$tpl->set_var( "row", "" );

for( $i=0; $i< count($textfieldArray); $i++ )
{
    $tpl->set_var( "row_text", $textfieldArray[$i]->Text() );
    $tpl->set_var( "row_id", $textfieldArray[$i]->id() );
    $tpl->parse( "row", "row_tpl", true );
}

$tpl->pparse( "output", "page4_tpl" );

/*!
  Update all fields in the database.
*/
function updateValues( $idArray, $textArray )
{
    $i=0;
    if ( count( $idArray ) > 0 )
        foreach( $idArray as $id )
        {
            $exmpl = new eZExample( );
            if ( $exmpl->get( $id ) )
            {
                $exmpl->setText( $textArray[ $i ] );
                $exmpl->store();
            }
            $i++;
        }
}
?>
