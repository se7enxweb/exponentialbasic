<?php
//
// $Id: section.php 6229 2001-07-20 11:26:45Z jakobn $
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

include_once( "ezsitemanager/classes/ezsection.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );

if( $Command == "list" ) // Return a list of users and their ID's 
{
    $sectionList = eZSection::getAll();
    $sections = array();

    foreach( $sectionList as $section )
    {
        $sections[] = new eZXMLRPCStruct(
            array( "URL" => createURLStruct( "ezsitemanager", "section", $section->id() ),
                   "Name" => new eZXMLRPCString( $section->name( false ) )
                   )
            );
    }
    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => new eZXMLRPCArray(),
                                         "Elements" => $sections,
                                         "Path" => new eZXMLRPCArray() ) ); // array starting with top level catalogue, ending with parent.

}

?>
