<?php
// 
// $Id: imagemap.php 7845 2001-10-15 11:04:57Z jhe $
//
// Created on: <12-Jun-2001 14:47:19 jhe>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezarticle/classes/ezimagemap.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZArticleMain", "Language" );

$map = new eZImageMap( $ImageID );

switch ( $Action )
{
    case "Edit" :
    {
        $t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                             "ezarticle/admin/intl/", $Language, "imagemap.php" );
        
        $t->setAllStrings();
        
        $t->set_file( "image_map_tpl", "imagemap.tpl" );
        $t->set_block( "image_map_tpl", "element_tpl", "element" );
        
        $article = new eZArticle( $ArticleID );
        $image = new eZImage( $ImageID );
        
        $t->set_var( "article_name", $article->name() );
        
        $t->set_var( "image_id", $ImageID );
        $t->set_var( "article_id", $ArticleID );
        $t->set_var( "image", $image->filePath( true ) );
        
        $elements = $map->elements();
        $t->set_var( "element", "" );
        
        for ( $i = 0; $i < count( $elements ); $i++ )
        {
            $t->set_var( "element_id", $i );
            $t->set_var( "value", $elements[$i] );
            
            $t->parse( "element", "element_tpl", true );
        }
        $t->pparse( "output", "image_map_tpl" );
    }
	break;
    
    case "Store" :
    {
        $map->store( $Values );
        eZHTTPTool::header( "Location: /article/articleedit/imagelist/" . $ArticleID );
    }
	break;
}

?>
