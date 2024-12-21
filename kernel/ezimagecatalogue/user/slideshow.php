<?php
//
// $Id: slideshow.php 9345 2002-03-06 10:34:39Z jhe $
//
// Definition of eZArticle class
//
// Created on: <25-Jun-2001 11:50:32 jhe>
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
// include_once( "classes/eztemplate.php" );
// include_once( "ezimagecatalogue/classes/ezslideshow.php" );

// sections
// include_once( "ezsitemanager/classes/ezsection.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );

$SlideShowHeaderFooter = $ini->read_var( "eZImageCatalogueMain", "SlideShowHeaderFooter" );
$SlideShowOriginalImage = $ini->read_var( "eZImageCatalogueMain", "SlideShowOriginalImage" );

if ( $SlideShowHeaderFooter == "disabled" )
{
    $PrintableVersion = "enabled";
}

$t = new eZTemplate( "kernel/ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "kernel/ezimagecatalogue/user/intl/", $Language, "slideshow.php" );

$t->setAllStrings();

$t->set_file( "slideshow_tpl", "slideshow.tpl" );

$t->set_block( "slideshow_tpl", "image_tpl", "image" );
$t->set_block( "slideshow_tpl", "previous_tpl", "previous" );
$t->set_block( "slideshow_tpl", "next_tpl", "next" );


if ( $CategoryID == 0 )
    $GlobalSectionID = $ini->read_var( "eZImageCatalogueMain", "DefaultSection" );

if ( !$GlobalSectionID )
    $GlobalSectionID = $ini->read_var( "eZImageCatalogueMain", "DefaultSection" );

$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

if ( $Position == "" )
    $Position = 0;

$slideshow = new eZSlideshow( $CategoryID, eZUser::currentUser(), $Position );
$image = $slideshow->image();

if ( !$image )
{
    $t->set_var( "image", "" );
}
else
{
    if ( $SlideShowOriginalImage == "enabled" )
    {    
        $variation = $image;
    }
    else
    {        
        $variation =& $image->requestImageVariation( $ini->read_var( "eZImageCatalogueMain", "ImageViewWidth" ),
        $ini->read_var( "eZImageCatalogueMain", "ImageViewHeight" ) );
    }

    $t->set_var( "image_uri", "/" . $variation->imagePath( true ) );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    
    $t->parse( "image", "image_tpl" );
}

$current = $slideshow->currentPosition();
$t->set_var( "category", $CategoryID );

if ( $current > 0 )
{
    $t->set_var( "prev_image", $current - 1 );
    $t->parse( "previous", "previous_tpl" );
}
else
{
    $t->set_var( "prev_image", $current );
    $t->set_var( "previous", "" );
} 

if ( $current < ( $slideshow->size() - 1 ) )
{
    if ( is_numeric( $RefreshTimer ) )
    {
        $MetaRedirectLocation = "/imagecatalogue/slideshow/" . $CategoryID . "/" . ( $current + 1 ) . "/" . $RefreshTimer . "/";
        $MetaRedirectTimer = $RefreshTimer;
    }
    $t->set_var( "next_image", $current + 1 );
    $t->parse( "next", "next_tpl" );
}
else
{
    $t->set_var( "next_image", $current );
    $t->set_var( "next", "" );
}

$t->pparse( "output", "slideshow_tpl" );

?>
