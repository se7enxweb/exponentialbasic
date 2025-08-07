<?php
// 
// $Id: tiplist_tpl.php,v 1.21.2.3 2002/010/08 11:56:16 master Exp $
//
// Created on: <25-Nov-2000 15:44:37 bf>
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

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/eztemplate.php" );

include_once( "eztip/classes/eztip.php" );
include_once( "eztip/classes/eztipcategory.php" );
include_once( "ezsitemanager/classes/ezsection.php" );
// site information
include_once( "classes/INIFile.php" );

$ini =& eZINI::instance( 'site.ini' );
$GlobalSiteIni =& $ini;
$category = new eZTipCategory( $CategoryID );

// fetch the user if any
$user =& eZUser::currentUser();
if ( !isset( $Limit ) )
    $Limit = 1;
if ( !isset( $tipLocationID ) )
    $tipLocationID = 0;
if ( !isset( $showTitle ) )
    $showTitle = 0;
if ( !isset( $Offset ) )
    $Offset = 0;
	
// tips
$tiplist_tpl =& $category->tips( "count", false, $Offset, $Limit, $tipLocationID );

/* for debugging
echo "<pre>";
print_r($tiplist_tpl);
echo "<pre>";
exit ();
*/

if (count($tiplist_tpl) != 0) {

$Language = $ini->variable( "eZTipMain", "Language" );
unset($t);
$t = new eZTemplate( "eztip/user/" . $ini->variable( "eZTipMain", "TemplateDir" ), "eztip/user/intl", $Language, "tiplist.php" );

$t->setAllStrings();
$t->set_file( array(
    "tiplist_tpl" => "tiplist.tpl"
    ) );

$t->set_block( "tiplist_tpl", "tip_image_tpl", "tip_image" );
$t->set_block( "tiplist_tpl", "tip_html_tpl", "tip_html" );
$t->set_block( "tiplist_tpl", "tip_link_tpl", "tip_link" );
$t->set_block( "tiplist_tpl", "tip_title_tpl", "tip_title" );

if ($showTitle <> 1)
	$t->set_var("tip_title", "");

$t->set_var( "site-design", $siteDesign );

	foreach ( $tiplist_tpl as $tip )
	{
	    $tipID = $tip->id();
	
	    $image =& $tip->image();
	
	    // ad image
	    if ( $image )
	    {
            $variation =& $image->requestImageVariation( 145 , 200 );
	        $imgSRC =& $variation->imagePath( true ) ;
	        $imgWidth =& $variation->width();
	        $imgHeight =& $variation->height();
	        $imgName =& $image->name();
			$t->set_var("tip_title", "");

	    }
	
	    $tip->addPageView();
	
	    if ( $tip->useHTML() )
	    {
		    if ($showTitle == 1)
			{
			$tipCat = $tip->categories();
			$t->set_var("title_text", $tipCat[0]->Name );
 			$t->parse( "tip_title", "tip_title_tpl" );
			}
			
			$t->set_var("tip_image", "");
		    $t->set_var("html", $tip->htmlBanner());
		    $t->set_var("tip_id", $tipID);
			if ( trim($tip->URL()) != "" )
			{
				//EP: check if it is external or internal banner --------------------
				if ( preg_match( "/^([a-z]+:\/\/)/", $tip->URL() ) )
				{
          		    $t->set_var("tip_target", "_blank");
				}
				else
				{
          		    $t->set_var("tip_target", "_self");
				}
				//EP ----------------------------------------------------------------
    		$t->parse( "tip_link", "tip_link_tpl" );
			} else {
		    $t->set_var("tip_link", "");
            }		
		$t->parse( "tip_html", "tip_html_tpl" );
	    }
	    else
	    {
		    $t->set_var("tip_id", $tipID);
		    $t->set_var("image_width", $imgWidth);
		    $t->set_var("image_height", $imgHeight);
		    $t->set_var("tip_name", $imgName);
		    $t->set_var("image_src", $imgSRC);
		    $t->set_var("tip_html", "");
		    $t->set_var("tip_link", "");

		if ( trim($tip->URL()) != "" )
		{
		    $t->set_var("link", "/tip/goto/$tipID/" );
		
			if ( preg_match( "/^([a-z]+:\/\/)/", $tip->URL() ) )
			{
		    $t->set_var("link_start", '<a target="_blank" href="/tip/goto/' . $tipID . '/">' );
		    $t->set_var("link_end", "</a>" );
			}
			else
			{
		    $t->set_var("link_start", '<a target="_self" href="/tip/goto/' . $tipID . '/">' );
		    $t->set_var("link_end", "</a>" );
			}
		} else {
		    $t->set_var("link_start", "" );
		    $t->set_var("link_end", "" );
		}
		$t->parse( "tip_image", "tip_image_tpl" );
		}
	}

$output = $t->parse( "output", "tiplist_tpl" );
print $output;
unset($showTitle);
}
?>