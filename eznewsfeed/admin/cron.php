<?php
// 
// $Id: cron.php 6224 2001-07-20 11:21:41Z jakobn $
//
// Created on: <13-Dec-2000 10:20:45 bf>
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


// this file is designed to run as a cron job

// include_once( "eznewsfeed/classes/eznews.php" );
// include_once( "eznewsfeed/classes/eznewscategory.php" );
// include_once( "eznewsfeed/classes/eznewsimporter.php" );
// include_once( "eznewsfeed/classes/ezsourcesite.php" );

// include_once( "classes/ezdatetime.php" );

// This will fetch the news from every source site
$Action = "ImportNews";
if ( $Action == "ImportNews" )
{
    $sourceSite = new eZSourceSite();
    
    $sourceSiteList = $sourceSite->getAll();
    
    foreach ( $sourceSiteList as $site )
    {
        print( "importing news from: ".  $site->decoder() );
        unset( $newsImporter );
        $newsImporter = new eZNewsImporter( $site->decoder(),
                                            $site->url(),
                                            $site->category(),
                                            $site->login(),
                                            $site->password(),
                                            $site->autoPublish() );
        $newsImporter->importNews();
    }    
}


?>
