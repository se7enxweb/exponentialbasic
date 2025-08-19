<?php
// 
// $Id: sourcesiteedit.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <26-Nov-2000 17:55:31 bf>
//
// This source file is part of Exponential Basic, publishing software.
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
// include_once( "classes/ezhttptool.php" );

$ini = eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZNewsFeedMain", "Language" );
$ImageDir = $ini->variable( "eZNewsFeedMain", "ImageDir" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "eznewsfeed/classes/eznewsimporter.php" );
// include_once( "eznewsfeed/classes/ezsourcesite.php" );

// include_once( "eznewsfeed/classes/eznewscategory.php" );

if ( $Action == "Insert" )
{
    $sourcesite = new eZSourceSite();
    
    $sourcesite->setName( $SourceSiteName );
    $sourcesite->setURL( $SourceSiteURL );
    $sourcesite->setLogin( $SourceSiteLogin );
    $sourcesite->setPassword( $SourceSitePassword );
    $category = new eZNewsCategory( $CategoryID );
    $sourcesite->setCategory( $category );
    $sourcesite->setDecoder( $DecoderChoice );

    if ( $SourceSiteIsActive == "on" )
    {
        $sourcesite->setIsActive( true );
    }
    else
    {
        $sourcesite->setIsActive( false );
    }


    if ( $SourceSiteAutoPublish == "on" )
    {
        $sourcesite->setAutoPublish( true );
    }
    else
    {
        $sourcesite->setAutoPublish( false );
    }
    
    $sourcesite->store();

    eZHTTPTool::header( "Location: /newsfeed/importnews/" );
    exit();
}

if ( $Action == "Update" )
{
    $sourcesite = new eZSourceSite( $SourceSiteID );
    
    $sourcesite->setName( $SourceSiteName );
    $sourcesite->setURL( $SourceSiteURL );
    $sourcesite->setLogin( $SourceSiteLogin );
    $sourcesite->setPassword( $SourceSitePassword );
    $category = new eZNewsCategory( $CategoryID );
    $sourcesite->setCategory( $category );
    $sourcesite->setDecoder( $DecoderChoice );


    if ( $SourceSiteIsActive == "on" )
    {
        $sourcesite->setIsActive( true );
    }
    else
    {
        $sourcesite->setIsActive( false );
    }

    if ( $SourceSiteAutoPublish == "on" )
    {
        $sourcesite->setAutoPublish( true );
    }
    else
    {
        $sourcesite->setAutoPublish( false );
    }
    
    $sourcesite->store();

    eZHTTPTool::header( "Location: /newsfeed/importnews/" );
    exit();
}


$t = new eZTemplate( "kernel/eznewsfeed/admin/" . $ini->variable( "eZNewsFeedMain", "AdminTemplateDir" ),
                     "kernel/eznewsfeed/admin/intl/", $Language, "sourcesiteedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "news_edit_page_tpl" => "sourcesiteedit.tpl"
    ) );

$t->set_block( "news_edit_page_tpl", "value_tpl", "value" );
$t->set_block( "news_edit_page_tpl", "decoder_tpl", "decoder" );

if ( $Action == "New" )
{
    $t->set_var( "source_site_name_value", "" );
    $t->set_var( "source_site_id_value", "" );
    $t->set_var( "source_site_url_value", "" );
    $t->set_var( "source_site_login_value", "" );
    $t->set_var( "source_site_password_value", "" );
    $t->set_var( "action_value", "insert" );    
}



if ( $Action == "Edit" )
{
    $sourcesite = new eZSourceSite( $SourceSiteID );

    $t->set_var( "source_site_name_value", $sourcesite->name() );
    $t->set_var( "source_site_url_value", $sourcesite->url() );
    $t->set_var( "source_site_login_value", $sourcesite->login() );
    $t->set_var( "source_site_password_value", $sourcesite->password() );
    $t->set_var( "source_site_id", $sourcesite->id() );
    $decoderChoice = $sourcesite->decoder();
    $t->set_var( "action_value", "update" );

    $category = $sourcesite->category();
    $categoryID = $category->id();

    if ( $sourcesite->isActive() == true )
    {
        $t->set_var( "source_site_isactive_value", "checked" );
    }
    else
    {
        $t->set_var( "source_site_isactive_value", "" );
    }  

    if ( $sourcesite->autoPublish() == true )
    {
        $t->set_var( "source_site_auto_publish_value", "checked" );
    }
    else
    {
        $t->set_var( "source_site_auto_publish_value", "" );
    }  
}




// decoder select
$decoders = eZNewsImporter::listDecoders();
foreach( $decoders as $decoderItem )
{
    if( isset( $decoderChoice ) && trim( $decoderChoice ) == $decoderItem )
    {
        $t->set_var( "choice_selected", "selected" );
    }
    else
    {
        $t->set_var( "choice_selected", "" );
    }
    $t->set_var( "decoder_value", $decoderItem );
    $t->parse( "decoder", "decoder_tpl", true );    
}


// category select
$category = new eZNewsCategory();
$categoryArray = $category->getAll( );

foreach ( $categoryArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        if ( $categoryID == $catItem->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
    }    
    
    $t->set_var( "option_value", $catItem->id() );
    $t->set_var( "option_name", $catItem->name() );

    $t->parse( "value", "value_tpl", true );    
}

$t->pparse( "output", "news_edit_page_tpl" );

?>