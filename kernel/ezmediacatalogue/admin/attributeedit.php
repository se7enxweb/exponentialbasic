<?php
// 
// $Id: attributeedit.php 6248 2001-07-24 15:42:35Z ce $
//
// Created on: <29-Jun-2001 13:57:58 bf>
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

// include_once( "classes/ezhttptool.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /mediacatalogue/mediaedit/edit/$MediaID/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZMediaMain", "Language" );

// include_once( "ezmedia/classes/ezmediacategory.php" );
// include_once( "ezmedia/classes/ezmedia.php" );

// include_once( "ezmedia/classes/ezmediatype.php" );
// include_once( "ezmedia/classes/ezmediaattribute.php" );

$media = new eZMedia( $MediaID );

if ( $Action == "Update" )
{
    if ( $TypeID == -1 )
    {
        $media->removeType();
    }
    else
    {
        $media->setType( new eZMediaType( $TypeID ) );

        $i = 0;
        if ( count( $AttributeValue ) > 0 )
        {
            foreach ( $AttributeValue as $attribute )
            {
                $att = new eZMediaAttribute( $AttributeID[$i] );
                
                $att->setValue( $media, $attribute );

                $i++;
            }
        }
    }
    
    if ( isset( $OK ) )
    {
        eZHTTPTool::header( "Location: /media/mediaedit/edit/$MediaID/" );
        exit();
    }
}

$t = new eZTemplate( "kernel/ezmedia/admin/" . $ini->variable( "eZMediaMain", "AdminTemplateDir" ),
                     "kernel/ezmedia/admin/intl/", $Language, "attributeedit.php" );

$t->setAllStrings();

$t->set_file( "attribute_edit_page", "attributeedit.tpl" );

$t->set_block( "attribute_edit_page", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );

$t->set_block( "attribute_edit_page", "type_tpl", "type" );


//default values
    
if ( $Action == "Edit" )
{    
    
}

$type = new eZMediaType( );
$types = $type->getAll();

$type = $media->type();


foreach ( $types as $typeItem )
{
    if ( $type )
    {
        if ( $type->id() == $typeItem->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
    }
    
    $t->set_var( "type_id", $typeItem->id( ) );
    $t->set_var( "type_name", $typeItem->name( ) );
    
    $t->parse( "type", "type_tpl", true );
}


if ( $type )    
{
    $attributes = $type->attributes();

    foreach ( $attributes as $attribute )
    {
        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );
        $t->set_var( "attribute_value", $attribute->value( $media ) );
        
        $t->parse( "attribute", "attribute_tpl", true );
    }
}

if ( count( $attributes ) > 0 )
{
    $t->parse( "attribute_list", "attribute_list_tpl" );
}
else
{
    $t->set_var( "attribute_list", "" );
}

$t->set_var( "media_name", $media->title() );
$t->set_var( "media_id", $MediaID );

$t->pparse( "output", "attribute_edit_page" );

?>