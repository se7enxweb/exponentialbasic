<?php
// 
// $Id: imagevariationadmin.php,v 1.3.2.2 2002/04/24 07:38:20 jhe Exp $
//
// Created on: <05-Jul-2001 14:40:06 bf>
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
// include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZSiteManagerMain", "Language" );
$Limit = $ini->read_var( "eZSiteManagerMain", "AdminListLimit" );

//include_once( "ezsitemanager/classes/ezsection.php" );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "imagevariationadmin.php" );
$t->setAllStrings();

$t->set_file( "variation_admin_tpl", "imagevariationadmin.tpl" );
$t->set_block( "variation_admin_tpl", "variation_results_tpl", "variation_results" ); 

$t->set_var( "variation_results", "" );
if ( isset( $ClearVariations ) )
{    
    // save the buffer contents
    $buffer =& ob_get_contents();
    ob_end_clean();

    // fetch the system printout
    ob_start();
    if ( trim( $GLOBALS["WINDIR"] ) != "" )
        system( $siteDir . "./bin/shell/clearvariations.bat" );
    else
        system( $siteDir . "./bin/shell/clearvariations.sh" );
    $ret = ob_get_contents();
    ob_end_clean();

    // fill the buffer with the old values
    ob_start();
    print( $buffer );

    $t->set_var( "variation_return", $ret );

    $t->parse( "variation_results", "variation_results_tpl" );
}

$t->pparse( "output", "variation_admin_tpl" );

?>