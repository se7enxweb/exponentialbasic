<?php
//
// $Id: unhandledbugs.php 7696 2001-10-08 14:39:09Z fh $
//
// Created on: <27-Nov-2000 22:18:56 bf>
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

include_once( "ezbug/classes/ezbugcategory.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezbug/classes/ezbug.php" );

include_once( "ezuser/classes/ezuser.php" );

include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& INIFile::globalINI();
$Language= $ini->read_var("eZBugMain","Language");
$t = new eZTemplate( "ezbug/user/" . $ini->read_var( "eZBugMain", "TemplateDir" ),
                     "ezbug/user/intl", $Language, "unhandledbugs.php" );
$errorIni = new INIFIle( "ezbug/user/intl/" . $Language . "/unhandledbugs.php.ini", false );

$t->set_file( array(
    "unhandled_bugs_tpl" => "unhandledbugs.tpl"
    ) );

$t->setAllStrings();

$t->set_block( "unhandled_bugs_tpl", "bug_tpl", "bug" );

$t->set_var( "site_style", $GlobalSiteDesign );

$bug = new eZBug();

$unhandleBugs =& $bug->getUnhandled();

$t->set_var( "bug", "" );

$i=0;
foreach ( $unhandleBugs as $bug )
{
    if( eZObjectPermission::hasPermission( $bug->module( true ), "bug_module", 'w' ) )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "bug_id",  $bug->id(), "&nbsp;" );
        $t->set_var( "bug_name",  $bug->name(), "&nbsp;" );

        $module = $bug->module();
        if ( $module )
            $t->set_var( "bug_module_name", $module->name(), "&nbsp;" );
        else
            $t->set_var( "bug_module_name", "" );

        $owner = $bug->user();

        if( is_a( $owner, "eZUser") )
        {
            $t->set_var( "bug_submitter", $owner->name(), "&nbsp;" );
        }
        elseif ( $bug->userEmail() != false )
        {
            $t->set_var( "bug_submitter", $bug->userEmail() );
        }
        else
        {
            $errorMsg = $errorIni->read_var( "strings", "unknown" );
            $t->set_var( "bug_submitter", $errorMsg );
        }

        $t->parse( "bug", "bug_tpl", true );
        $i++;
    }
}

$t->pparse( "output", "unhandled_bugs_tpl" );

?>