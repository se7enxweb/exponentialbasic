<?php
//
// $Id: tag.php 7344 2001-09-19 11:49:28Z jb $
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

include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();

$tags = array();

$tagList =& $ini->read_array( "eZArticleMain", "CustomTags" );
foreach ( $tagList as $tag )
{
    $tags[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle",
                                                                   "tag",
                                                                   0 ),
                                         "Name" => new eZXMLRPCString( $tag ) ) );
}

$part_arr = array( "Offset" => new eZXMLRPCInt( 0 ),
                   "Total" => new eZXMLRPCInt( count( $tagList ) ),
                   "Begin" => new eZXMLRPCBool( true ),
                   "End" => new eZXMLRPCBool( true ) );
$part = new eZXMLRPCStruct( $part_arr );

$ReturnData = new eZXMLRPCStruct( array( "Catalogues" => array(),
                                         "Elements" => $tags,
                                         "Part" => $part ) );
?>
