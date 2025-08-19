<?php
//
// $Id: topic.php 6553 2001-08-22 13:13:53Z jb $
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

// include_once( "ezarticle/classes/eztopic.php" );

if( $Command == "data" ) // return all the data in the category
{
    $topic = new eZTopic();
    if ( $topic->get( $ID ) )
    {
        $ReturnData = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $topic->name() ),
                                                 "Description" => new eZXMLRPCArray( $topic->description() ) ) );
    }
    else
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
}
else if( $Command == "delete" )
{
    $topic = new eZTopic();
    if ( $topic->get( $ID ) )
    {
        $topic->delete();
        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "topic", $ID ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 ) );
        $Command = "update";
    }
    else
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
}
else if( $Command == "storedata" )
{
    if ( isset( $Data["Name"] ) && isset( $Data["Description"] ) )
    {
        $topic = new eZTopic();
        if ( $ID != 0 )
            $topic->get( $ID );
        $topic->setName( $Data["Name"]->value() );
        $topic->setDescription( $Data["Description"]->value() );
        $topic->store();

        $ID = $topic->id();
        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "topic", $ID ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                             ) );
        $Command = "update";
    }
    else
        $Error = createErrorMessage( EZERROR_BAD_REQUEST_DATA );
}
?>