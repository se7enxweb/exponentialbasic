<?php
//
// $Id: unassignedlist.php 9520 2002-05-08 13:05:20Z jb $
//
// Created on: <26-Oct-2000 19:40:18 bf>
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

// include_once( "ezimagecatalogue/classes/ezimage.php" );

if ( $Command == "list" )
{
    $offset = 0;
    $max = -1;
    $total = 0;

    if ( is_object( $Data["Part"] ) )
    {
        $Part = $Data["Part"]->value();
        $offset = $Part["Offset"]->value();
        $max = $Part["Max"]->value();
    }

//      $category = new eZImageCategory( $ID );

    $loc_max = $max;
    $loc_offset = $offset;

    $art = array();
    $imageCount = eZImage::countUnassigned();
    $total += $imageCount;
    if ( $loc_max > 0 and $loc_offset >= 0 )
    {
        $imageList =& eZImage::getUnassigned( $loc_offset, $loc_max );
//          $imageList =& eZImage::getUnassigned();
        foreach( $imageList as $imageItem )
        {
            $cols = array( "Caption" => new eZXMLRPCString( $imageItem->caption( false ) ),
                           "Description" => new eZXMLRPCString( $imageItem->description( false ) ),
                           "FileName" => new eZXMLRPCString( $imageItem->originalFileName() )
                           );
            $art[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezimagecatalogue",
                                                                          "image",
                                                                          $imageItem->id() ),
                                                "Name" => new eZXMLRPCString( $imageItem->name( false ) ),
                                                "Thumbnail" => new eZXMLRPCBool( true )
                                                )
                                         );
        }
    }
//      if ( $offset > 0 )
//          usleep( 5000000 );

    $cat = array();
    $par = array();
    if ( $offset == 0 )
    {
        $par[] = createURLStruct( "ezimagecatalogue", "" );
    }

    $part_arr = array( "Offset" => new eZXMLRPCInt( $offset ),
                       "Total" => new eZXMLRPCInt( $total ) );
    if ( $offset == 0 )
    {
        $part_arr["Begin"] = new eZXMLRPCBool( true );
    }
    if ( $total == $offset + count( $art ) )
    {
        $part_arr["End"] = new eZXMLRPCBool( true );
    }
    $part = new eZXMLRPCStruct( $part_arr );

    if ( $offset == 0 )
        $cols = new eZXMLRPCStruct( array( "Caption" => new eZXMLRPCString( "text" ),
                                           "Description" => new eZXMLRPCString( "text" ),
                                           "FileName" => new eZXMLRPCString( "text" )
                                           ) );

    $ret = array( "Catalogues" => $cat,
                  "Elements" => $art,
                  "Path" => $par,
                  "Part" => $part );
    if ( $offset == 0 )
        $ret["Columns"] = $cols;

    $ReturnData = new eZXMLRPCStruct( $ret );
}

?>
