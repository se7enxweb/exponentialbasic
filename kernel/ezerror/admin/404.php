<?php
// 
// $Id: 404.php 6211 2001-07-19 12:45:08Z jakobn $
//
// Created on: <23-Feb-2001 16:53:09 fh>
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
$ini = & eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZErrorMain", "Language" );

$t = new eZTemplate( "kernel/ezerror/admin/" . $ini->variable( "eZErrorMain", "AdminTemplateDir" ),
                     "kernel/ezerror/admin/intl/", $Language, "404.php" );

$t->setAllStrings();
$t->set_file( array( "error_page" => "404.tpl" ) );
$t->set_block( "error_page", "additional_information_tpl", "additional_information" );

if( isset( $Info ) && !empty( $Info ) )
{
    $t->set_var( "additional_info", stripslashes( $Info ) );
    $t->parse( "additional_information", "additional_information_tpl", true );
}
else
{
    $t->set_var( "additional_information", "" );
}      

$t->pparse( "output", "error_page" );

?>