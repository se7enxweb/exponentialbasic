<?php
//
// $Id: author.php 6552 2001-08-22 13:13:15Z jb $
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

// include_once( "ezuser/classes/ezauthor.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );

if( $Command == "list" ) // Return a list of authors and their ID's 
{
    $authorList = eZAuthor::getAll();
    $authors = array();
    foreach( $authorList as $author )
    {
        $authors[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezuser", "author", $author->id() ),
                                                "Name" => new eZXMLRPCString( $author->name( false ) )
                                                )
                                         );
    }
    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => array(),
                                             "Elements" => $authors ) );
}
else if( $Command == "storedata" )
{
    if ( isset( $Data["Name"] ) && isset( $Data["Email"] ) )
    {
        $author = new eZAuthor();
        if ( $ID != 0 )
            $author->get( $ID );
        $author->setName( $Data["Name"]->value() );
        $author->setEmail( $Data["Email"]->value() );
        $author->store();

        $ID = $author->id();
        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezuser", "author", $ID ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                             ) );
        $Command = "update";
    }
    else
        $Error = createErrorMessage( EZERROR_BAD_REQUEST_DATA );
}

?>