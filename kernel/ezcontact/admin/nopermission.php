<?php
// 
// $Id: nopermission.php 6970 2001-09-05 11:57:07Z jhe $
//
// Created on: <19-Feb-2001 11:11:28 amos>
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

// include_once( "classes/eztemplate.php" );

$ini =& $GlobalSiteIni;
$Language = $ini->read_var( "eZContactMain", "Language" );
$t = new eZTemplate( "kernel/ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "kernel/ezcontact/admin/intl", $Language, "nopermission.php" );
$t->setAllStrings();

$t->set_file( "permission_page", "nopermission.tpl" );

$t->set_block( "permission_page", "permission_tpl", "permission" );

$t->set_block( "permission_tpl", "permission_login_item_tpl", "permission_login_item" );
$t->set_block( "permission_tpl", "permission_company_new_item_tpl", "permission_company_new_item" );
$t->set_block( "permission_tpl", "permission_company_edit_item_tpl", "permission_company_edit_item" );
$t->set_block( "permission_tpl", "permission_company_delete_item_tpl", "permission_company_delete_item" );
$t->set_block( "permission_tpl", "permission_company_list_item_tpl", "permission_company_list_item" );
$t->set_block( "permission_tpl", "permission_company_view_item_tpl", "permission_company_view_item" );
$t->set_block( "permission_tpl", "permission_person_new_item_tpl", "permission_person_new_item" );
$t->set_block( "permission_tpl", "permission_person_edit_item_tpl", "permission_person_edit_item" );
$t->set_block( "permission_tpl", "permission_person_delete_item_tpl", "permission_person_delete_item" );
$t->set_block( "permission_tpl", "permission_person_list_item_tpl", "permission_person_list_item" );
$t->set_block( "permission_tpl", "permission_person_view_item_tpl", "permission_person_view_item" );
$t->set_block( "permission_tpl", "permission_category_new_item_tpl", "permission_category_new_item" );
$t->set_block( "permission_tpl", "permission_category_edit_item_tpl", "permission_category_edit_item" );
$t->set_block( "permission_tpl", "permission_category_delete_item_tpl", "permission_category_delete_item" );
$t->set_block( "permission_tpl", "permission_type_admin_tpl", "permission_type_admin" );
$t->set_block( "permission_tpl", "permission_consultation_tpl", "permission_consultation" );

$t->set_var( "permission", "" );
$t->set_var( "permission_login_item", "" );
$t->set_var( "permission_company_new_item", "" );
$t->set_var( "permission_company_edit_item", "" );
$t->set_var( "permission_company_delete_item", "" );
$t->set_var( "permission_company_list_item", "" );
$t->set_var( "permission_company_view_item", "" );
$t->set_var( "permission_person_new_item", "" );
$t->set_var( "permission_person_edit_item", "" );
$t->set_var( "permission_person_delete_item", "" );
$t->set_var( "permission_person_list_item", "" );
$t->set_var( "permission_person_view_item", "" );
$t->set_var( "permission_category_new_item", "" );
$t->set_var( "permission_category_edit_item", "" );
$t->set_var( "permission_category_delete_item", "" );
$t->set_var( "permission_type_admin", "" );
$t->set_var( "permission_consultation", "" );

switch ( $Type )
{
    case "login":
    {
        $t->parse( "permission_login_item", "permission_login_item_tpl" );
        break;
    }
    case "type":
    {
        $t->parse( "permission_type_admin", "permission_type_admin_tpl" );
        break;
    }
    case "consultation":
    {
        $t->parse( "permission_consultation", "permission_consultation_tpl" );
        break;
    }
    case "company":
    case "person":
    {
        switch ( $Action )
        {
            case "new":
            case "edit":
            case "delete":
            case "list":
            case "view":
            {
                $t->parse( "permission_" . $Type . "_" . $Action . "_item",
                           "permission_" . $Type . "_" . $Action . "_item_tpl" );
                break;
            }
        }
        break;
    }
    case "category":
    {
        switch ( $Action )
        {
            case "new":
            case "edit":
            case "delete":
            {
                $t->parse( "permission_" . $Type . "_" . $Action . "_item",
                           "permission_" . $Type . "_" . $Action . "_item_tpl" );
                break;
            }
        }
        break;
    }
}

$t->parse( "permission", "permission_tpl" );

$t->pparse( "output", "permission_page" );

?>
