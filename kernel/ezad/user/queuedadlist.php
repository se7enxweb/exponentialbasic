<?php
// 
// $Id: queuedadlist.php 7633 2001-10-02 15:08:09Z br $
//
// Created on: <02-Oct-2001 17:24:33 br>
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


// NOTE: this page does not use templates due to speed 
// and because we cannot cache the contents of this page.

// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezad/classes/ezad.php" );
// include_once( "ezad/classes/ezadcategory.php" );

$category = new eZAdCategory( $CategoryID );

// fetch the user if any
$user =& eZUser::currentUser();

// if adList not have been set before.
if ( !$queuedAdListCheck )
{
    if ( !isset( $Limit ) )
        $Limit = 1;
    if ( !isset( $Offset ) )
        $Offset = 0;
    
    // ads
    $adList =& $category->ads( "count", false, $Offset, $Limit );
    $queuedAdListCheck = 1;
}

$ad = array_shift( $adList );

if ( $ad )
{
    $adID = $ad->id();

    $image =& $ad->image();
    
    // ad image
    if ( $image )
    {
        $imgSRC =& $image->filePath();
        $imgWidth =& $image->width();
        $imgHeight =& $image->height();
    }
    
    $ad->addPageView();
    
    if ( $ad->useHTML() )
    {
        print( $ad->htmlBanner() );
    }
    else
    {
        print( "<a target=\"_blank\" href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/ad/goto/$adID/\"><img src=\"".$GlobalSiteIni->WWWDir."$imgSRC\" width=\"$imgWidth\" height=\"$imgHeight\" border=\"0\" alt=\"\" /></a><br />" );
    }
}

?>