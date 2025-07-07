<?php
// 
// $Id: templatelist.php 6209 2001-07-19 12:36:31Z jakobn $
//
// Created on: <18-Apr-2001 16:36:22 fh>
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

// include_once( "ezbulkmail/classes/ezbulkmailtemplate.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

if( isset( $New ) )
{
    eZHTTPTool::header( "Location: /bulkmail/templateedit/0" );
    exit();
}

if( isset( $Delete ) && count( $TemplateArrayID ) > 0 )
{
    foreach( $TemplateArrayID as $templateID )
        eZBulkMailTemplate::delete( $templateID );
}

$t = new eZTemplate( "kernel/ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "kernel/ezbulkmail/admin/intl", $Language, "templatelist.php" );

$t->set_file( array(
    "template_list_tpl" => "templatelist.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $siteDesign );

$t->set_block( "template_list_tpl", "template_tpl", "template" );
$t->set_block( "template_tpl", "template_item_tpl", "template_item" );
$t->set_var( "template_item", "" );
$t->set_var( "template", "" );


$templates = eZBulkMailTemplate::getAll();
$i = 0;
foreach( $templates as $templateitem )
{
    $t->set_var( "template_name", $templateitem->name() );
    $t->set_var( "template_description", $templateitem->description() );
    $t->set_var( "template_id", $templateitem->id() );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
    $t->parse( "template_item", "template_item_tpl", true );
    $i++;
}
if( $i > 0 )
    $t->parse( "template", "template_tpl" );

$t->pparse( "output", "template_list_tpl" );

?>