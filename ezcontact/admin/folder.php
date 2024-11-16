<?php
// 
// $Id: folder.php 7816 2001-10-12 12:27:35Z jhe $
//
// Created on: <14-Sep-2001 14:39:54 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

include_once( "ezfilemanager/classes/ezvirtualfolder.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );

$top = eZVirtualFolder::getIDByParent( "Contact" );
if ( !$top )
{
    $contact = new eZVirtualFolder();
    $contact->setName( "Contact" );
    $contact->setParent( 0 );
    $contact->store();
    $top = $contact->ID();
}

if ( $CompanyEdit )
{
    $parent = eZVirtualFolder::getIDByParent( "Company", $top );
    if ( !$parent )
    {
        $companyFolder = new eZVirtualFolder();
        $companyFolder->setName( "Company" );
        $companyFolder->setParent( $top );
        $companyFolder->store();
        $parent = $companyFolder->ID();
    }
    $element = new eZCompany( $item_id );
}
else
{
    $parent = eZVirtualFolder::getIDByParent( "Person", $top );
    if ( !$parent )
    {
        $personFolder = new eZVirtualFolder();
        $personFolder->setName( "Person" );
        $personFolder->setParent( $top );
        $personFolder->store();
        $parent = $personFolder->ID();
    }
    $element = new eZPerson( $item_id );
}
$id = eZVirtualFolder::getIDByParent( $element->name(), $parent );
if ( !$id )
{
    $newFolder = new eZVirtualFolder();
    $newFolder->setName( $element->name() );
    $newFolder->setParent( $parent );
    $newFolder->store();
    eZObjectPermission::setPermission( -1, $newFolder, "filemanager_folder", "r" );
    eZObjectPermission::setPermission( -1, $newFolder, "filemanager_folder", "w" );
    eZObjectPermission::setPermission( -1, $newFolder, "filemanager_folder", "u" );
    $id = $newFolder->ID();
}

include_once( "classes/ezhttptool.php" );
eZHTTPTool::header( "Location: /filemanager/list/$id/" );
exit;

?>
