<?php
// 
// $Id: storestats.php 8189 2001-11-01 17:44:30Z bf $
//
// Created on: <26-Apr-2001 10:39:18 bf>
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

ob_start( );

// get right path 
chdir( "../../" );

$REQUEST_URI = preg_replace( "#/stats/store(.*?)1x1.gif$#", "\\1", $REQUEST_URI );
//$REQUEST_URI = preg_replace( "#/stats/store(.*?)1x1.png$#", "\\1", $REQUEST_URI );

$REQUEST_URI = preg_replace( "#/rx.*?-(.*)$#", "\\1", $REQUEST_URI );
//$REQUEST_URI = preg_replace( "#/rx.*?-(.*)$#", "\\1", $REQUEST_URI );

// do the statistics
include_once( "ezstats/classes/ezpageview.php" );

// create a global page view object for statistics
// and store the stats
$GlobalPageView = new eZPageView();
$GlobalPageView->store();

ob_end_clean();

//  # the file may be a local file with full path.
// $filePath = "images/1x1.gif";
// $filePath = "design/base/images/design/1x1.png";

$filePath = "design/base/images/design/1x1.gif";
$fileSize = eZFile::filesize( $filePath );
$fp = eZFile::fopen( $filePath, "r" );
$content =& fread( $fp, $fileSize );

$originalFileName = "1x1.gif";
// $originalFileName = "1x1.png";

//Header("Content-type: image/png");
Header("Content-type: image/gif"); 
Header("Content-length: $fileSize"); 
Header("Content-disposition: attachment; filename=\"$originalFileName\"");

echo($content);
exit();
?>
