<?php
// 
// $Id: categoryedit.php 6417 2001-08-13 12:31:09Z ce $
//
// Created on: <18-Apr-2001 11:15:33 fh>
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

// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

if( isset( $Cancel ) ) // cancel pressed, redirect to categorylist page...
{
    eZHTTPTool::header( "Location: /bulkmail/subscriptionlist/" );
    exit();
}

if( isset( $Ok ) ) // cancel pressed, redirect to categorylist page...
{
    if( $CategoryID == 0 )
    {
        $category = new eZBulkMailCategory();
    }
    else
    {
        $category = new eZBulkMailCategory( $CategoryID );
    }
    eZHTTPTool::header( "Location: /bulkmail/categorylist/$id" );
    exit();
}


$t = new eZTemplate( "kernel/ezbulkmail/user/" . $ini->variable( "eZBulkMailMain", "TemplateDir" ),
                     "kernel/ezbulkmail/user/intl", $Language, "categoryedit.php" );
$t->set_file( array(
    "category_edit_tpl" => "categoryedit.tpl"
    ) );

$t->setAllStrings();

$t->pparse( "output", "category_edit_tpl" );

?>
