<?php
// 
// $Id: mediaview.php 8187 2001-11-01 17:32:53Z ce $
//
// Created on: <24-Jul-2001 17:08:10 ce>
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
include_once( "classes/eztemplate.php" );

include_once( "ezmediacatalogue/classes/ezmedia.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZMediaCatalogueMain", "Language" );

$t = new eZTemplate( "ezmediacatalogue/admin/" . $ini->read_var( "eZMediaCatalogueMain", "TemplateDir" ),
                     "ezmediacatalogue/admin/intl/", $Language, "mediaview.php" );

$t->set_file( "media_view_tpl", "mediaview.tpl" );

$t->setAllStrings();

$user =& eZUser::currentUser();

$media = new eZMedia( $MediaID );

//if ( eZObjectPermission::hasPermission( $media->id(), "mediacatalogue_media", "r", $user ) == false )
//{
//    eZHTTPTool::header( "Location: /error/403/" );
//    exit();
//}

$t->set_var( "media_uri", $media->mediaPath( false ) );
$t->set_var( "media_caption", $media->caption() );
$t->set_var( "media_name", $media->name() );
$t->set_var( "media_description", $media->description() );

$attString =& $media->attributeString();

$t->set_var( "attributes", $attString );
$t->set_var( "referer_url", $RefererURL );

$t->pparse( "output", "media_view_tpl" );


?>
