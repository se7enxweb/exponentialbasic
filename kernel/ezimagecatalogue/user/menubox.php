<?php
// 
// $Id: menubox.php 7462 2001-09-25 17:37:52Z fh $
//
// Created on: <16-Jan-2001 13:23:02 ce>
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

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZImageCatalogueMain", "Language" );

    
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdb.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

$t = new eZTemplate( "kernel/ezimagecatalogue/user/" . $ini->variable( "eZImageCatalogueMain", "TemplateDir" ),
                     "kernel/ezimagecatalogue/user/intl", $Language, "menubox.php" );

$t->setAllStrings();

$t->set_file( array(
    "menu_box_tpl" => "menubox.tpl"
    ) );

////////////////
// BEGIN random image block added by admin [at] riderhaus [dot] com

$RandImageCategory =& $ini->variable( "eZImageCatalogueMain", "RandImageCategory" );
$width =& $ini->variable( "eZImageCatalogueMain", "ThumbnailViewWidth" );
$height =& $ini->variable( "eZImageCatalogueMain", "ThumbnailViewHight" );
$randimage = eZImage::randomImage( $RandImageCategory );
$variation =& $randimage->requestImageVariation( $width, $height );
$t->set_var( "image_src", "/" . $variation->imagePath() );
$t->set_var( "image_file_name", $randimage->name() );
$t->set_var( "image_category", $RandImageCategory );
$t->set_var( "image_caption", $randimage->caption() );
$t->set_var( "image_width", $variation->width()  );
$t->set_var( "image_height", $variation->height() );

// END random image block added by admin [at] riderhaus [dot] com
////////////////


$t->set_block( "menu_box_tpl", "user_login_tpl", "user_login" );

if ( $user && ( eZObjectPermission::getObjects( "imagecatalogue_category", 'w', true ) > 0 ||
                eZObjectPermission::getObjects( "imagecatalogue_category", 'u', true ) > 0 ||
                eZPermission::checkPermission( $user, "eZImageCatalogue", "WriteToRoot" ) ) )
{
    $t->parse( "user_login", "user_login_tpl" );
}
else
{
    $t->set_var( "user_login", "" );
}

$t->set_var( "sitedesign", $GlobalSiteDesign );  
    
$t->pparse( "output", "menu_box_tpl" );

?>