<?php
//
// $Id: type.php 6206 2001-07-19 12:19:22Z jakobn $
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

include_once( "ezarticle/classes/ezarticletype.php" );
include_once( "ezarticle/classes/ezarticleattribute.php" );

if( $Command == "data" ) // return all the data in the category
{
    $type = new eZArticleType();
    if ( $type->get( $ID ) )
    {
        $attrs = $type->attributes();
        $attr_arr = array();
        foreach( $attrs as $attr )
        {
            $attr_arr[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $attr->id() ),
                                                     "Name" => new eZXMLRPCString( $attr->name() ) ) );
        }
        $ReturnData = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $type->name() ),
                                                 "Attributes" => new eZXMLRPCArray( $attr_arr ) ) );
    }
    else
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
}
else if( $Command == "delete" )
{
    $type = new eZArticleType();
    if ( $type->get( $ID ) )
    {
        $type->delete();
        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "article", $ID ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 ) );
        $Command = "update";
    }
    else
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
}
?>
