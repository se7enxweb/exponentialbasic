<?php
// 
// $Id: attributeedit.php 6220 2001-07-20 11:15:21Z jakobn $
//
// Created on: <29-Jun-2001 13:57:58 bf>
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

// include_once( "classes/ezhttptool.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /link/linkedit/edit/$LinkID/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZLinkMain", "Language" );

// include_once( "ezlink/classes/ezlinkcategory.php" );
// include_once( "ezlink/classes/ezlink.php" );

// include_once( "ezlink/classes/ezlinktype.php" );
// include_once( "ezlink/classes/ezlinkattribute.php" );

$link = new eZLink( $LinkID );

if ( $Action == "Update" )
{
    if ( $TypeID == -1 )
    {
        $link->removeType();
    }
    else
    {
        $link->setType( new eZLinkType( $TypeID ) );

        $i = 0;
        if ( count( $AttributeValue ) > 0 )
        {
            foreach ( $AttributeValue as $attribute )
            {
                $att = new eZLinkAttribute( $AttributeID[$i] );
                
                $att->setValue( $link, $attribute );

                $i++;
            }
        }
    }
    
    if ( isset( $OK ) )
    {
        eZHTTPTool::header( "Location: /link/linkedit/edit/$LinkID/" );
        exit();
    }
}

$t = new eZTemplate( "kernel/ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
                     "kernel/ezlink/admin/intl/", $Language, "attributeedit.php" );

$t->setAllStrings();

$t->set_file( "attribute_edit_page", "attributeedit.tpl" );

$t->set_block( "attribute_edit_page", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );

$t->set_block( "attribute_edit_page", "type_tpl", "type" );


//default values
    
if ( $Action == "Edit" )
{    
    
}

$type = new eZLinkType( );
$types = $type->getAll();

$type = $link->type();


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
        $t->set_var( "attribute_value", $attribute->value( $link ) );
        
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

$t->set_var( "link_name", $link->title() );
$t->set_var( "link_id", $LinkID );

$t->pparse( "output", "attribute_edit_page" );

?>