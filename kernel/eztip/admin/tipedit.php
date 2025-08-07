<?php
// 
// $Id: tipedit.php,v 1.23.2.2 2002/02/27 16:53:26 master Exp $
//
// Created on: <16-Nov-2000 13:02:32 bf>
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
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezimagefile.php" );
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "classes/ezdatetime.php" );

// include_once( "eztip/classes/eztip.php" );
// include_once( "eztip/classes/eztipcategory.php" );

if ( isset ( $DeleteTips ) )
{
    $Action = "DeleteTips";
}

if ( isSet ( $Preview ) )
{
    if ( is_numeric ( $TipID ) && ( $TipID != 0 ) )
    {
        $Action = "Update";
    }
    else
    {
        $Action = "Insert";
    }
}

if( isset( $TipURL ) )
{
    // If TipURL is set, we trim it to remove any leading or trailing spaces.
    // This is to ensure that the URL is clean and does not contain unnecessary whitespace.}
    $tipUrl = trim( $TipURL );
}
else
{
    $tipUrl = "";
}

// Get images from the image browse function.
if ( ( isSet ( $AddImages ) ) and ( is_numeric( $TipID ) ) and ( is_numeric ( $TipID ) ) )
{
    $image = new eZImage( $ImageID );
    $tip = new eZTip( $TipID );
    $tip->setImage( $image );
    $tip->store();
    $Action = "Edit";
}

if ( $Action == "Insert" )
{

    $category = new eZTipCategory( $CategoryID );
    
    $tip = new eZTip( );

    $tip->setName( $TipTitle );
    $tip->setDescription( $TipDescription );
    
    if ( $IsActive == "on" )
    {
        $tip->setIsActive( true );
    }
    else
    {
        $tip->setIsActive( false );
    }
    
    if ( $UseHTML == "1" )
    {
        $tip->setUseHTML( true );
    }
    else
    {
        $tip->setUseHTML( false );
    }

    $tip->setHTMLBanner( $HTMLBanner );    
    
    $real_url = $tipUrl;
    
    $tip->setURL( $real_url );
    
    $file = new eZImageFile();

    if ( $file->getUploadedFile( "TipImage" ) )
    { 
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();
        
        $tip->setImage( $image );

        eZLog::writeNotice( "Picture added to ad: $TipID  from IP: $REMOTE_ADDR" );
    }

//      $dateTime = new eZDateTime( 2000, 11, 13, 14, 0, 15 );
//      $tip->setOriginalPublishingDate( $dateTime );

	//Webscope edit
	//if ( !isSet ( $Cancel ) )
	//{
    $tip->store();
    $category->addTip( $tip );
	//}
	
    if ( isSet ( $Browse ) )
    {
        $tipID = $tip->id();
        
        $session =& eZSession::globalSession();
        $session->setVariable( "SelectImages", "single" );
        $session->setVariable( "ImageListReturnTo", "/tip/tip/edit/$tipID/" );
        $session->setVariable( "NameInBrowse", $tip->name() );
        eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
        exit();
    }
    if ( isset( $Preview ) )
    {
        $Action = "Edit";
        $TipID = $tip->id();
    }
    else
    {        
        eZHTTPTool::header( "Location: /tip/archive/$CategoryID/" );
        exit();
    }
    
}

if ( $Action == "Update" )
{
    $category = new eZTipCategory( $CategoryID );
    
    $tip = new eZTip( $TipID );

    $tip->setName( $TipTitle );
    $tip->setDescription( $TipDescription );


  
    if ( $IsActive == "on" )
    {
        $tip->setIsActive( true );
    }
    else
    {
        $tip->setIsActive( false );
    }

    if ( $UseHTML == "1" )
    {
        $tip->setUseHTML( true );
    }
    else
    {
        $tip->setUseHTML( false );
    }

    $tip->setHTMLBanner( $HTMLBanner );    
    
//    if ( !preg_match( "/^([a-z]+:\/\/)/", $tipUrl ) )
//    {
//        if( !preg_match( "/^(ftp\.)/", $tipUrl ) )
//            $real_url = "http://" . $tipUrl;
//        else
//            $real_url = "ftp://" . $tipUrl;
//    }
//    else
//    {
//        $real_url = $tipUrl;
//    }

    $real_url = $tipUrl;

    $tip->setURL( $real_url );

//      $dateTime = new eZDateTime( 2000, 11, 13, 14, 0, 15 );
//      $tip->setOriginalPublishingDate( $dateTime );

    $file = new eZImageFile();

    if ( $file->getUploadedFile( "TipImage" ) )
    { 
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );

        $image->store();

        $tip->setImage( $image );

        eZLog::writeNotice( "Picture added to ad: $TipID  from IP: $REMOTE_ADDR" );
    }
	if (!isSet($Cancel))
	{
    $tip->store();
	}

    $tip->removeFromCategories();
    $category->addTip( $tip );

    if ( isSet ( $Browse ) )
    {
        $tipID = $tip->id();
        
        $session =& eZSession::globalSession();
        $session->setVariable( "SelectImages", "single" );
        $session->setVariable( "ImageListReturnTo", "/tip/tip/edit/$tipID/" );
        $session->setVariable( "NameInBrowse", $tip->name() );
        eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
        exit();
    }
    if ( isset( $Preview ) )
    {
        $Action = "Edit";        
    }
    else
    {        
        eZHTTPTool::header( "Location: /tip/archive/$CategoryID/" );
        exit();
    }
}

if ( $Action == "Delete" )
{
    $tip = new eZTip( $TipID );
    $tip->delete();

    eZHTTPTool::header( "Location: /tip/archive/$CategoryID/" );
    exit();    
}

if ( $Action == "DeleteTips" )
{
    if ( count ( $TipArrayID ) != 0 )
    {
        foreach( $TipArrayID as $TipID )
        {
            $tip = new eZTip( $TipID );
            $cat = $tip->categories();
            $CategoryID = $cat[0]->id();
            $tip->delete();
        }
    }

    eZHTTPTool::header( "Location: /tip/archive/$CategoryID/" );
    exit();
}


$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTipMain", "Language" );
$ImageDir = $ini->variable( "eZTipMain", "ImageDir" );

$t = new eZTemplate( "kernel/eztip/admin/" . $ini->variable( "eZTipMain", "AdminTemplateDir" ),
                     "kernel/eztip/admin/intl/", $Language, "tipedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "tip_edit_page_tpl" => "tipedit.tpl"
    ) );

$t->set_block( "tip_edit_page_tpl", "value_tpl", "value" );
$t->set_block( "tip_edit_page_tpl", "image_tpl", "image" );


$t->set_var( "action_value", "insert" );

$t->set_var( "tip_title_value", "" );
$t->set_var( "tip_date_value", "" );
$t->set_var( "tip_description_value", "" );
$t->set_var( "tip_url_value", "" );
$t->set_var( "tip_click_price_value", "" );
$t->set_var( "tip_view_price_value", "" );
$t->set_var( "tip_id", "" );
$t->set_var( "image", "" );
$t->set_var( "html_banner", "" );
$t->set_var( "use_html", "" );

if ( $Action == "Edit" )
{
    $tip = new eZTip( $TipID );

    $t->set_var( "tip_title_value", $tip->name() );
    $t->set_var( "tip_description_value", $tip->description() );
    $t->set_var( "tip_url_value", $tip->url() );
    $t->set_var( "tip_id", $tip->id() );
    $t->set_var( "action_value", "update" );

    $t->set_var( "tip_click_price_value", $tip->clickPrice() );
    $t->set_var( "tip_view_price_value", $tip->viewPrice() );

    $t->set_var( "html_banner", $tip->htmlBanner() );

    
    if ( $tip->isActive() == true )
    {
        $t->set_var( "tip_is_active", "checked" );
    }
    else
    {
        $t->set_var( "tip_is_active", "" );
    }

    if ( $tip->useHTML() == true )
    {
        $t->set_var( "use_html", "checked" );
        $t->set_var( "use_image", "" );
    }
    else
    {
        $t->set_var( "use_html", "" );
        $t->set_var( "use_image", "checked" );
    }
    
    $image = $tip->image();

    
    if ( $image )
    {
        $t->set_var( "image_src",  $image->filePath() );
        $t->set_var( "image_width", $image->width() );
        $t->set_var( "image_height", $image->height() );
        $t->set_var( "image_file_name", $image->originalFileName() );
        $t->parse( "image", "image_tpl" );
    }
    else
    {
        $t->set_var( "image", "" );
    }
        
    
    
    $cats = $tip->categories();
    $defCat = $cats[0];
}


// category select
$category = new eZTipCategory();
$categoryArray = $category->getTree();

foreach ( $categoryArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        if ( $defCat->id() == $catItem[0]->id() )
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

    if ( $catItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
    else
        $t->set_var( "option_level", "" );
    
    $t->set_var( "option_value", $catItem[0]->id() );
    $t->set_var( "option_name", $catItem[0]->name() );

    $t->parse( "value", "value_tpl", true );    
}


$t->pparse( "output", "tip_edit_page_tpl" );

?>