<?php
// 
// $Id: importnews.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <16-Nov-2000 13:02:19 bf>
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

// include_once( "eznewsfeed/classes/eznews.php" );
// include_once( "eznewsfeed/classes/eznewscategory.php" );
// include_once( "eznewsfeed/classes/eznewsimporter.php" );
// include_once( "eznewsfeed/classes/ezsourcesite.php" );

// include_once( "classes/ezdatetime.php" );

// fetch one site
if ( isset( $Action ) && $Action == "Fetch" )
{
    $site = new eZSourceSite( $SourceSiteID );

    $newsImporter = new eZNewsImporter( $site->decoder(),
                                        $site->url(),
                                        $site->category(),
                                        $site->login(),
                                        $site->password(),
                                        $site->autoPublish() );
    $newsImporter->importNews();
}
// fetch every site
if ( isset( $Action ) && $Action == "ImportNews" && !isset( $Delete ) )
{
    $sourceSite = new eZSourceSite();
    
    $sourceSiteList = $sourceSite->getAll();
    
    foreach ( $sourceSiteList as $site )
    {
        if ( $site->IsActive() == 1 )
        {
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
}

// Delete selected sites.
if ( isset( $Delete ) )
{
    if ( isset( $DeleteArray ) && count( $DeleteArray ) > 0 )
    {
        foreach( $DeleteArray as $row )
        {
            eZSourceSite::delete( $row );
        }
    }
}

$ini = eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZNewsFeedMain", "Language" );

$t = new eZTemplate( "kernel/eznewsfeed/admin/" . $ini->variable( "eZNewsFeedMain", "AdminTemplateDir" ),
                     "kernel/eznewsfeed/admin/intl/", $Language, "importnews.php" );

$t->setAllStrings();

$t->set_file( array(
    "import_news_tpl" => "importnews.tpl"
    ) );

$t->set_block( "import_news_tpl", "source_site_list_tpl", "source_site_list" );
$t->set_block( "source_site_list_tpl", "source_site_tpl", "source_site" );
$t->set_var( "source_site", "" );

$t->set_var( "site_style", $SiteDesign );

$sourceSite = new eZSourceSite();

$sourceSiteList = $sourceSite->getAll();

$i=0;
foreach ( $sourceSiteList as $site )
{
    $t->set_var( "source_site_id", $site->id() );
    $t->set_var( "source_site_name", $site->name() );
    $t->set_var( "source_site_url", $site->url() );
    
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $i++;
    $t->parse( "source_site", "source_site_tpl", true );
}

$t->parse( "source_site_list", "source_site_list_tpl" );

$sourceSite = new eZSourceSite();

//  $newsImporter = new eZNewsImporter( "nyheter.no" );
//  $newsImporter->importNews();

//  $newsImporter = new eZNewsImporter( "freshmeat.net" );
//  $newsImporter->importNews();

$t->pparse( "output", "import_news_tpl" );

?>