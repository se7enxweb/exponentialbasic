<?php
//
// $Id: search.php 9824 2003-06-02 08:00:43Z jhe $
//
// Created on: <08-Jun-2001 13:10:36 bf>
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

// include_once( "classes/ezhttptool.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZSearchMain", "Language" );
$SearchModules = $ini->read_var( "eZSearchMain", "SearchModules" );

$moduleArray = explode( ";", $SearchModules );

// init the section
if ( isset ($SectionIDOverride) )
{
    // include_once( "ezsitemanager/classes/ezsection.php" );

    $sectionObject =& eZSection::globalSectionObject( $SectionIDOverride );
    $sectionObject->setOverrideVariables();
}

$t = new eZTemplate( "kernel/ezsearch/user/" . $ini->read_var( "eZSearchMain", "TemplateDir" ),
                     "kernel/ezsearch/user/intl/", $Language, "search.php" );

$t->set_file( "search_tpl", "search.tpl" );

$t->set_block( "search_tpl", "search_type_tpl", "search_type" );
$t->set_block( "search_type_tpl", "search_sub_module_tpl", "search_sub_module" );
$t->set_block( "search_sub_module_tpl", "search_sub_module_name_tpl", "search_sub_module_name" );
$t->set_block( "search_sub_module_tpl", "search_item_tpl", "search_item" );

$t->setAllStrings();

$Limit = 20;
$Offset = 0;

$tmpSearchText = str_replace( "<", "&lt;", $SearchText );
$tmpSearchText = str_replace( ">", "&gt;", $tmpSearchText );
$t->set_var( "search_text", $tmpSearchText );
$t->set_var( "search_type", '' );

foreach ( $moduleArray as $module )
{
    $module = strtolower( $module );
    unset( $SearchResult );
    if ( eZFile::file_exists( "kernel/$module/user/searchsupplier.php" ) )
    {
        include( "kernel/$module/user/searchsupplier.php" );

        $t->set_var( "search_item", "" );
        $t->set_var( "module_name", $ModuleName );
        $i = 0;
        if ( isset( $SearchResult[0] ) && !is_array( $SearchResult[0] ) )
        {
            $SearchResult = array( $SearchResult );
        }

        $t->set_var( "search_sub_module", "" );
        foreach ( $SearchResult as $Result )
        {
            if ( !is_null( $Result ) && 
                 !is_int( $Result ) &&
                 !is_string( $Result ) && count( $Result ) > 0 &&
                 ( isset( $Result["Result"] ) && !is_null( $Result["Result"] ) ) && 
                 !is_int( $Result["Result"] ) && count( $Result["Result"] ) > 0 )
            {
                $ResultArrayDetailViewPath = $Result["DetailViewPath"];
                $ResultArray = $Result["Result"];

                foreach ( $ResultArray as $res )
                {
                    if ( ( $i % 2 ) == 0 )
                        $t->set_var( "td_class", "bglight" );
                    else
                        $t->set_var( "td_class", "bgdark" );
                    if ( isset( $Result["SuffixVariable"] ) && $Result["SuffixVariable"] )
	                    $t->set_var( "search_link", $Result["DetailViewPath"] . $res->id() . $Result["SuffixVariable"]);
					else
	                    $t->set_var( "search_link", $Result["DetailViewPath"] . $res->id() );
                    $t->set_var( "search_name", $res->name() );
                    $t->set_var( "icon_src", $Result["IconPath"] );            
                    $t->parse( "search_item", "search_item_tpl", true );
                    $i++;
                }
            }

            $t->set_var( "search_more_link", isset( $Result["DetailedSearchPath"] ) ? $Result["DetailedSearchPath"] . "?" .
            
            $Result["DetailedSearchVariable"] . "=". urlencode( $SearchText ) : false );

            $t->set_var( "search_count", isset( $Result["SearchCount"] ) && $Result["SearchCount"] ? $Result["SearchCount"] : count( is_array( $Result ) && !empty( $Result["Result"] ) ? $Result["Result"] : array() ) );

            if ( isset( $Result["SubModuleName"] ) )
            {
                $t->set_var( "sub_module_name", $Result["SubModuleName"] );
                $t->parse( "search_sub_module_name", "search_sub_module_name_tpl" );
            }
            else
            {
                $t->set_var( "search_sub_module_name", "" );
                }
                     
            $t->parse( "search_sub_module", "search_sub_module_tpl", true );
            $t->set_var( "search_item", "" );
        }
        $t->parse( "search_type", "search_type_tpl", true );
    }
}

$t->pparse( "output", "search_tpl" );

?>