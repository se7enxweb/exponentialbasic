<?php
//
// $Id: companytypeedit.php 9529 2002-05-14 11:17:05Z jhe $
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

/*
  Edit company types
*/

include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "classes/ezimagefile.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );

$user =& eZUser::currentUser();
if ( !is_a( $user, "eZUser" ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/login" );
    exit();
}

if ( $Action == "edit" || $Action == "update" )
{
    if ( !eZPermission::checkPermission( $user, "eZContact", "CategoryModify" ) )
    {
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/nopermission/category/edit" );
        exit();
    }
}
else if ( $Action == "new" || $Action ==  "insert" )
{
    if ( !eZPermission::checkPermission( $user, "eZContact", "CategoryAdd" ) )
    {
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/nopermission/category/new" );
        exit();
    }
}
else if ( $Action == "delete" )
{
    if ( !eZPermission::checkPermission( $user, "eZContact", "CategoryDelete" ) )
    {
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/nopermission/category/delete" );
        exit();
    }
}

if ( empty( $TypeID ) )
{
    $TypeID = 0;
}

$type = new eZCompanyType();
$type->get( $TypeID );

if ( $Action == "insert" || $Action == "update" )
{
    $type = new eZCompanyType();

    if ( !empty( $TypeID ) )
    {
        $type->get( $TypeID );
    }
    $type->setName( $TypeName );
    $type->setDescription( $TypeDescription );
    if ( !empty( $TypeID ) )
    {
        if ( $SelectParentID != $type->id() )
        {
            $tree =& $type->getTree( $type->id() );
            foreach ( $tree as $item )
            {
                if ( $item[0]->id() == $SelectParentID )
                {
                    $SelectParentID = 0;
                    break;
                }
            }
        }
        else
        {
            $SelectParentID = 0;
        }
    }
    $type->setParentID( $SelectParentID );

    $file = new eZImageFile();
    if ( $file->getUploadedFile( "ImageFile" ) )
    {
        $image = new eZImage( );
        $image->setName( "Image" );
        $image->setImage( $file );

        $image->store();

        $type->setImageID( $image->id() );
    }

    $type->store();
    $TypeID = $type->id();

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/company/list/$TypeID" );

}

if ( !$type->id() && $Action != "new"  )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /error.php?type=404&reason=missingpage&module=ezcontact&hint=/contact/company/list/0" );
    exit();
}


if ( $Action == "delete" )
{
    $type = new eZCompanyType();
    $type->get( $TypeID );
    $ParentID = $type->parentID();
    $type->delete( );

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/company/list/$ParentID" );
    exit();
}


$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl/", $Language, "companytype.php" );
$t->setAllStrings();

$t->set_file( "type_page", "companytypeedit.tpl" );

$t->set_block( "type_page", "current_type_tpl", "current_type" );
$t->set_block( "type_page", "path_tpl", "path" );
$t->set_block( "path_tpl", "path_item_tpl", "path_item" );
$t->set_block( "path_tpl", "current_path_item_tpl", "current_path_item" );
$t->set_block( "current_type_tpl", "parent_item_tpl", "parent_item" );
$t->set_block( "current_type_tpl", "image_item_tpl", "image_item" );
$t->set_block( "current_type_tpl", "no_image_item_tpl", "no_image_item" );

$t->set_var( "command_type", "company" );
$t->set_var( "page_args", $args );
$t->set_var( "no_image_item", "" );
$t->set_var( "image_item", "" );
$t->set_var( "path_item", "" );
$t->set_var( "current_path_item", "" );

if ( empty( $TypeID ) || $TypeID == 0 )
{
    $t->parse( "path", "path_tpl" );
}
else
{
    $paths = $type->path( $TypeID );
    $countingPaths = count( $path );

    $t->set_var( "path_item", "" );
    foreach ( $paths as $path )
    {
        $t->set_var( "parent_id", $path[0] );
        if ( $path[0] == $type->id() )
        {
            $t->parse( "current_path_item", "current_path_item_tpl" );
        }
        else
        {
            $t->set_var( "parent_name", $path[1] );
            $t->parse( "path_item", "path_item_tpl", true );
        }
    }

    $t->parse( "path", "path_tpl" );
}

if ( $Action == "edit" || $Action == "new" )
{
    if ( $Action == "edit" )
    {
        $t->set_var( "action_value", "update" );
    }
    else
    {
        $t->set_var( "action_value", "insert" );
    }

    $type = new eZCompanyType();
    $type->get( $TypeID );

    $id = $type->id();
    $name = $type->name();
    $desc = $type->description();
    $parentid = $type->parentID();
    if ( isSet( $NewParentID ) )
        $parentid = $NewParentID;

    $t->set_var( "current_id", $id );
    $t->set_var( "current_name", $name );
    $t->set_var( "current_description", $desc );
    $t->set_var( "parent_id", $parentid );

    $ImageID = $type->imageID();

    if ( is_numeric( $ImageID ) && $ImageID != 0 )
    {
        $imageWidth = $ini->read_var( "eZContactMain", "CategoryImageWidth" );
        $imageHeight = $ini->read_var( "eZContactMain", "CategoryImageHeight" );

        $image = new eZImage( $ImageID );

        $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

        $imageURL = "/" . $variation->imagePath();
        $imageWidth = $variation->width();
        $imageHeight = $variation->height();
        $imageCaption = $image->caption();

        $t->set_var( "image_width", $imageWidth );
        $t->set_var( "image_height", $imageHeight );
        $t->set_var( "image_url", $imageURL );
        $t->set_var( "image_caption", $imageCaption );
        $t->parse( "image_item", "image_item_tpl" );
    }
    else
    {
        $t->parse( "no_image_item", "no_image_item_tpl" );
    }


    $category = new eZCompanyType();

    $tree = $category->getTree();

    foreach ( $tree as $item )
    {
        $t->set_var( "select_parent_id", $item[0]->id() );
        $t->set_var( "select_parent_name", $item[0]->name() );

        if ( $item[1] > 0 )
            $t->set_var( "parent_level", str_repeat( "&nbsp;", $item[1] ) );
        else
            $t->set_var( "parent_level", "" );

        if ( $item[0]->id() == $parentid )
        {
            $t->set_var( "selected", "selected" );
            $selected = true;
        }
        else
        {
            $t->set_var( "selected", "" );
        }

        $t->parse( "parent_item", "parent_item_tpl", true );
    }

    if ( count( $tree ) == 0 )
    {
        $t->set_var( "parent_item", "" );
    }

    if ( $selected == false )
    {
        $t->set_var( "root_selected", "selected" );
    }
    else
    {
        $t->set_var( "root_selected", "" );
    }

    $t->parse( "current_type", "current_type_tpl" );
}

$t->pparse( "output", "type_page" );

?>
