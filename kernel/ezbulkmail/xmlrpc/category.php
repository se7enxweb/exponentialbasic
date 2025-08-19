<?php
//
// $Id: category.php 6209 2001-07-19 12:36:31Z jakobn $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
if( $Command == "list" ) // Return a list of all bulkmail categories..
{
    $categoryList = eZBulkMailCategory::getAll( );
    $categories = array();
    foreach( $categoryList as $category )
    {
        $categories[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezbulkmail", "category",  $category->id() ),
                                              "Name" => new eZXMLRPCString( $category->name( false ) )
                                              )
                                       );
    }
    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => new eZXMLRPCArray(),
                                             "Elements" => $categories,
                                             "Path" => new eZXMLRPCArray() ) ); // array starting with top level catalogue, ending with parent.

}

?>