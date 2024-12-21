<?php
// 
// $Id: templateedit.php 8526 2001-11-19 12:48:51Z jhe $
//
// Created on: <18-Apr-2001 17:15:33 fh>
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

if ( isset( $Cancel ) ) // cancel pressed, redirect to templatelist page...
{
    eZHTTPTool::header( "Location: /bulkmail/templatelist/" );
    exit();
}

if ( isset( $Ok ) ) // cancel pressed, redirect to templatelist page...
{
    if ( $TemplateID == 0 )
        $template = new eZBulkMailTemplate();
    else
        $template = new eZBulkMailTemplate( $TemplateID );

    $template->setName( $Name );
    $template->setDescription( $Description );
    $template->setHeader( $Header );
    $template->setFooter( $Footer );
    
    $template->store();
    eZHTTPTool::header( "Location: /bulkmail/templatelist/" );
    exit();
}


$t = new eZTemplate( "kernel/ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "kernel/ezbulkmail/admin/intl", $Language, "templateedit.php" );
$t->set_file( "template_edit_tpl", "templateedit.tpl" );

$t->setAllStrings();
$t->set_var( "site_style", $SiteDesign );

$t->set_var( "template_name", "" );
$t->set_var( "description", "" );
$t->set_var( "template_id", $TemplateID );
$t->set_var( "template_footer", "" );
$t->set_var( "template_header", "" );
if ( $TemplateID != 0  )
{
    $template = new eZBulkMailTemplate( $TemplateID );
    if ( is_object( $template ) )
    {
        $t->set_var( "template_name", $template->name() );
        $t->set_var( "description", $template->description() );
        $t->set_var( "template_header",  $template->header() );
        $t->set_var( "template_footer", $template->footer() );
    }
}

$t->pparse( "output", "template_edit_tpl" );

?>