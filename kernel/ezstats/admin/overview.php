<?php
// 
// $Id: overview.php 9837 2003-06-05 08:54:02Z br $
//
// Created on: <05-Jan-2001 11:23:51 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/ezmenubox.php" );

$ini =& eZINI::instance( 'site.ini' );
$SiteDesign =& $ini->variable( "site", "SiteStyle" );

$Language = $ini->variable( "eZStatsMain", "Language" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdate.php" );

// include_once( "ezstats/classes/ezpageview.php" );
// include_once( "ezstats/classes/ezpageviewquery.php" );

$t = new eZTemplate( "kernel/ezstats/admin/" . $ini->variable( "eZStatsMain", "AdminTemplateDir" ),
                     "kernel/ezstats/admin/intl", $Language, "overview.php" );

$t->setAllStrings();

$t->set_file( array(
    "overview_tpl" => "overview.tpl"
    ) );

$query = new eZPageViewQuery();

$t->set_var( "total_page_views", $query->totalPageViews() );

$today = new eZDate();
$pagesToday = $query->totalPageViewsDay( $today );
$pagesThisMonth = $query->totalPageViewsMonth( $today );

$t->set_var( "total_pages_today", $pagesToday );

$t->set_var( "total_pages_this_month", $pagesThisMonth );

$t->pparse( "output", "overview_tpl" );

$menuItems = array(
	   array( "/stats/productreport/", "{intl-product_report}" ), 
	   array( "/stats/entryexitreport/", "{intl-entry_exit_report}" )
    );

eZMenuBox::createBox( "eZStats", "kernel/ezstats", "admin",
                      $SiteDesign, $menuItems, true, "menuitems.tpl", "kernel/ezstats/admin/overview.php", true );

?>