<?php
//
// $Id: sectionedit.php 9825 2003-06-02 10:02:22Z jhe $
//
// Created on: <10-May-2001 16:17:29 ce>
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
// include_once( "classes/ezhttptool.php" );

// include_once( "ezsitemanager/classes/ezsection.php" );
// include_once( "ezsitemanager/classes/ezsectionfrontpage.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );

// include_once( "ezad/classes/ezadcategory.php" );

$Action = "";

if ( isSet ( $OK ) )
{
    $Action = "Insert";
}
if ( isSet ( $Delete ) )
{
    $Action = "Delete";
}
if ( isSet ( $Cancel ) )
{
    eZHTTPTool::header( "Location: /sitemanager/section/list/" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZSiteManagerMain", "Language" );
$move_item = true;
$languageINI = new INIFIle( "kernel/ezsitemanager/admin/intl/" . $Language . "/sectionedit.php.ini", false );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "sectionedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "section_edit_page" => "sectionedit.tpl"
      ) );


$t->set_block( "section_edit_page", "setting_list_tpl", "setting_list" );
$t->set_block( "setting_list_tpl", "setting_item_tpl", "setting_item" );
$t->set_block( "setting_item_tpl", "settings_tpl", "settings" );


$t->set_block( "setting_item_tpl", "article_category_list_tpl", "article_category_list" );
$t->set_block( "article_category_list_tpl", "article_category_item_tpl", "article_category_item" );

$t->set_block( "setting_item_tpl", "product_category_list_tpl", "product_category_list" );
$t->set_block( "product_category_list_tpl", "product_category_item_tpl", "product_category_item" );


$t->set_block( "setting_item_tpl", "item_move_up_tpl", "item_move_up" );
$t->set_block( "setting_item_tpl", "item_separator_tpl", "item_separator" );
$t->set_block( "setting_item_tpl", "item_move_down_tpl", "item_move_down" );
$t->set_block( "setting_item_tpl", "no_item_move_up_tpl", "no_item_move_up" );
$t->set_block( "setting_item_tpl", "no_item_separator_tpl", "no_item_separator" );
$t->set_block( "setting_item_tpl", "no_item_move_down_tpl", "no_item_move_down" );

$warning = true;

if ( isSet ( $AddRow ) )
    $Action = "Update";

if ( isSet ( $OK ) )
    $Action = "Update";

if ( isSet ( $Store ) )
    $Action = "Update";

if ( isSet ( $DeleteRows ) )
{
    $Action = "Update";

    if ( count ( $RowDeleteArrayID ) > 0 )
    {
        foreach( $RowDeleteArrayID as $RowID )
            eZSectionFrontPage::delete( $RowID );
    }
}

if ( $Action == "up" )
{
    $row = new eZSectionFrontPage( $RowID );
    $row->moveUp();

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /sitemanager/section/edit/$SectionID" );
    exit();
}

if ( $Action == "down" )
{
    $row = new eZSectionFrontPage( $RowID );
    $row->moveDown();
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /sitemanager/section/edit/$SectionID" );
    exit();
}

if ( ( $Action == "Insert" ) || ( $Action == "Update" ) && ( $user ) )
{
    if ( $warning )
    {
        if ( eZFile::is_dir( "sitedesign/" . $Name ) == false );
        {
            $session =& eZSession::globalSession();
            $session->setVariable( "DirNotExists", "true" );
        }
    }

    if ( is_numeric( $SectionID ) )
        $section = new eZSection( $SectionID);
    else
        $section = new eZSection();

    $section->setName( $Name );
    $section->setSiteDesign( $SiteDesign );
    $section->setTemplateStyle( $TemplateStyle );
    $section->setLanguage( $SecLanguage );
    $section->setDescription( $Description );
    $section->store();

    if ( isSet ( $Store )  || isSet( $OK ) )
    {
        $i=0;
        foreach( $RowArrayID as $RowID )
        {
            $pageRow = new eZSectionFrontPage( $RowID );
            $pageRow->setCategoryID( $CategoryID[$i] );
            $pageRow->setSettingID( $SettingID[$i] );
            $pageRow->store();
            $i++;
        }
    }

    if ( isSet ( $AddRow ) )
    {
        $pageRow = new eZSectionFrontPage();
        $pageRow->store();
        $section->addFrontPageRow( $pageRow );

        eZHTTPTool::header( "Location: /sitemanager/section/edit/" . $section->id() );
        exit();
    }
    else if ( !$DeleteRows and !$Store )
    {
        eZHTTPTool::header( "Location: /sitemanager/section/list/" );
        exit();
    }
}


if ( $Action == "Delete" )
{
    if ( count ( $SectionArrayID ) > 0 )
    {
        foreach( $SectionArrayID as $SectionID )
        {
            $section = new eZSection( $SectionID );
            $section->delete();
        }
    }
    eZHTTPTool::header( "Location: /sitemanager/section/list/" );
    exit();
}

if ( is_numeric( $SectionID ) )
{
    $section = new eZSection( $SectionID );
    $t->set_var( "section_id", $section->id() );
    $t->set_var( "section_name", $section->name() );
    $t->set_var( "section_description", $section->description() );
    $t->set_var( "section_sitedesign", $section->siteDesign() );
    $t->set_var( "section_language", $section->language() );
    $t->set_var( "section_templatestyle", $section->templateStyle() );
}
else
{
    $t->set_var( "section_name", $Name );
    $t->set_var( "section_sitedesign", $SiteDesign );
    $t->set_var( "section_templatestyle", $TemplateStyle );
    $t->set_var( "section_description", $Description );
    $t->set_var( "section_language", $SecLanguage );
    $t->set_var( "setting_list", "" );
}

if ( $section )
    $rows = $section->frontPageRows();
$settingNames =& eZSectionFrontPage::settingNames();


if ( count ( $rows ) > 0 )
{
    $tree = new eZArticleCategory();
    $articleTreeArray =& $tree->getTree();

    $category = new eZProductCategory();
    $productCategoryArray =& $category->getTree( );

    $ad = new eZAdCategory();
    $adCategoryArray =& $ad->getTree();

    $i=0;
    $count = count( $rows );
    foreach ( $rows as $row )
    {
        $t->set_var( "item_move_up", "" );
        $t->set_var( "no_item_move_up", "" );
        $t->set_var( "item_move_down", "" );
        $t->set_var( "no_item_move_down", "" );
        $t->set_var( "item_separator", "" );
        $t->set_var( "no_item_separator", "" );

        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "row_id", $row->id() );
        $settingName = $row->settingByID( $row->settingID() );

        $t->set_var( "product_category_list", "" );
        $t->set_var( "article_category_list", "" );

        switch ( $settingName )
        {
            case "1column":
            case "2column":
            case "1short":
            {
                $t->set_var( "article_category_item", "" );
                if ( count( $articleTreeArray ) > 0 )
                {
                    foreach ( $articleTreeArray as $catItem )
                    {
                        $t->set_var( "option_value", $catItem[0]->id() );
                        $t->set_var( "option_name", $catItem[0]->name() );

                        if ( $catItem[0]->id() == $row->categoryID() )
                            $t->set_var( "selected", "selected" );
                        else
                            $t->set_var( "selected", "" );

                        if ( $catItem[1] > 1 )
                            $t->set_var( "option_level", str_repeat( "&nbsp;&nbsp;", $catItem[1] ) );
                        else
                            $t->set_var( "option_level", "" );

                        $t->parse( "article_category_item", "article_category_item_tpl", true );
                    }
                    $t->parse( "article_category_list", "article_category_list_tpl", true );
                }

            }
            break;

            case "1columnProduct":
            case "2columnProduct":
            {
                $t->set_var( "product_category_item", "" );
                if ( count ( $productCategoryArray ) > 0 )
                {
                    foreach ( $productCategoryArray as $catItem )
                    {
                        $t->set_var( "option_value", $catItem[0]->id() );
                        $t->set_var( "option_name", $catItem[0]->name() );

                        if ( $catItem[0]->id() == $row->categoryID() )
                            $t->set_var( "selected", "selected" );
                        else
                            $t->set_var( "selected", "" );

                        if ( $catItem[1] > 0 )
                            $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
                        else
                            $t->set_var( "option_level", "" );

                        $t->parse( "product_category_item", "product_category_item_tpl", true );
                    }
                    $t->parse( "product_category_list", "product_category_list_tpl" );
                }
            }
            break;

            case "ad":
            {
                $t->set_var( "product_category_item", "" );
                if ( count( $adCategoryArray ) > 0 )
                {
                    foreach ( $adCategoryArray as $catItem )
                    {
                        $t->set_var( "option_value", $catItem[0]->id() );
                        $t->set_var( "option_name", $catItem[0]->name() );

                        if ( $catItem[0]->id() == $row->categoryID() )
                            $t->set_var( "selected", "selected" );
                        else
                            $t->set_var( "selected", "" );

                        if ( $catItem[1] > 0 )
                            $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
                        else
                            $t->set_var( "option_level", "" );

                        $t->parse( "product_category_item", "product_category_item_tpl", true );
                    }
                    $t->parse( "product_category_list", "product_category_list_tpl" );
                }
            }
            break;

        }

        $t->set_var( "settings", "" );
        foreach ( $settingNames as $name )
        {
            $t->set_var( "setting_name", $languageINI->read_var( "strings", $name["Name"] ) );
            $t->set_var( "setting_id", $name["ID"] );

            if ( $row->settingID() == $name["ID"] )
                $t->set_var( "selected", "selected" );
            else
                $t->set_var( "selected", "" );

            $t->parse( "settings", "settings_tpl", true );
        }
        if ( $i > 0 && isset( $move_item ) )
        {
            $t->parse( "item_move_up", "item_move_up_tpl" );
        }
        else
        {
            $t->parse( "no_item_move_up", "no_item_move_up_tpl" );
        }

        if ( $i > 0 && $i < $count - 1 && isset( $move_item ) )
        {
            $t->parse( "item_separator", "item_separator_tpl" );
        }
        else
        {
            $t->parse( "no_item_separator", "no_item_separator_tpl" );
        }

        if ( $i < $count - 1 && isset( $move_item ) )
        {
            $t->parse( "item_move_down", "item_move_down_tpl" );
        }
        else
        {
            $t->parse( "no_item_move_down", "no_item_move_down_tpl" );
        }

		if ( ( $i % 2 ) == 0 )
	    {
	        $t->set_var( "td_class", "bglight" );
	    }
	    else
	    {
	        $t->set_var( "td_class", "bgdark" );
	    }
	    $t->set_var( "counter", $i );
        $t->parse( "setting_item", "setting_item_tpl", true );
        $i++;
    }
    $t->parse( "setting_list", "setting_list_tpl" );
}

$t->pparse( "output", "section_edit_page" );
?>