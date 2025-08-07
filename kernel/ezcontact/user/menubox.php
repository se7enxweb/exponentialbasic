<?php
//
// $Id: menubox.php 7760 2001-10-10 13:19:26Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezpermission.php" );

$user =& eZUser::currentUser();
if ( is_a( $user, "eZUser" ) and
     ( eZPermission::checkPermission( $user, "eZContact", "CompanyList" ) or
       eZPermission::checkPermission( $user, "eZContact", "PersonList" ) or
       eZPermission::checkPermission( $user, "eZContact", "Consultation" ) ) )
{
    // include_once( "classes/INIFile.php" );

    $Language = $ini->variable( "eZContactMain", "Language" );

    // include_once( "classes/eztemplate.php" );

    $t = new eZTemplate( "kernel/ezcontact/user/" . $ini->variable( "eZContactMain", "TemplateDir" ),
                         "kernel/ezcontact/user/intl", $Language, "menubox.php" );
    $t->setAllStrings();
    $t->set_file( "menu_box_tpl", "menubox.tpl" );
    $t->set_block( "menu_box_tpl", "company_item_tpl", "company_item" );
    $t->set_block( "menu_box_tpl", "person_item_tpl", "person_item" );
    $t->set_block( "menu_box_tpl", "company_new_tpl", "company_new" );
    $t->set_block( "menu_box_tpl", "person_new_tpl", "person_new" );
    $t->set_block( "menu_box_tpl", "consultation_item_tpl", "consultation_item" );

    $t->set_var( "company_item", "" );
    $t->set_var( "person_item", "" );
    $t->set_var( "company_new", "" );
    $t->set_var( "person_new", "" );
    $t->set_var( "consultation_item", "" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CompanyList" ) )
        $t->parse( "company_item", "company_item_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "PersonList" ) )
        $t->parse( "person_item", "person_item_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CompanyAdd" ) )
        $t->parse( "company_new", "company_new_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "PersonAdd" ) )
        $t->parse( "person_new", "person_new_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "Consultation" ) )
        $t->parse( "consultation_item", "consultation_item_tpl" );

	$t->set_var( "sitedesign", $GlobalSiteDesign );

    $t->pparse( "output", "menu_box_tpl" );
}

?>