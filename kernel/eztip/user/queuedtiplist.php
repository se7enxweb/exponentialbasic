<?php
// 
// $Id: queuedtiplist.php,v 1.1 2001/10/02 15:08:09 br Exp $
//
// Created on: <02-Oct-2001 17:24:33 br>
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


// NOTE: this page does not use templates due to speed 
// and because we cannot cache the contents of this page.

include_once( "ezuser/classes/ezuser.php" );

include_once( "eztip/classes/eztip.php" );
include_once( "eztip/classes/eztipcategory.php" );

$category = new eZTipCategory( $CategoryID );

// fetch the user if any
$user =& eZUser::currentUser();

// if adList not have been set before.
if ( !$queuedTipListCheck )
{
    if ( !isset( $Limit ) )
        $Limit = 1;
    if ( !isset( $Offset ) )
        $Offset = 0;
    
    // tips
    $tipList =& $category->tips( "count", false, $Offset, $Limit );
    $queuedTipListCheck = 1;
}

$tip = array_shift( $tipList );

if ( $tip )
{
    $tipID = $tip->id();

    $image =& $tip->image();
    
    // ad image
    if ( $image )
    {
        $imgSRC =& $image->filePath();
        $imgWidth =& $image->width();
        $imgHeight =& $image->height();
    }
    
    $tip->addPageView();
    
    if ( $tip->useHTML() )
    {
        print( $tip->htmlBanner() );
    }
    else
    {
        print( "<a target=\"_blank\" href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/tip/goto/$tipID/\"><img src=\"".$GlobalSiteIni->WWWDir."$imgSRC\" width=\"$imgWidth\" height=\"$imgHeight\" border=\"0\" alt=\"\" /></a><br />" );
    }
}


?>