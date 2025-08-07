<?php
// 
// $Id: authorlist.php 9564 2002-05-23 09:12:28Z jhe $
//
// Created on: <16-Feb-2001 14:54:04 amos>
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

// include_once( "ezarticle/classes/ezarticle.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZArticleMain", "Language" );
$Limit = $ini->variable( "eZArticleMain", "AuthorLimit" );

$t = new eZTemplate( "kernel/ezarticle/user/" . $ini->variable( "eZArticleMain", "TemplateDir" ),
                     "kernel/ezarticle/user/intl/", $Language, "authorlist.php" );

$t->setAllStrings();

$t->set_file( "author_list_tpl", "authorlist.tpl" );
$t->set_block( "author_list_tpl", "author_item_tpl", "author_item" );

if ( !isset( $Offset ) )
    $Offset = 0;
if ( !isset( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 5;
if ( !isset( $SortOrder ) )
    $SortOrder = "name";

$authors =& eZArticle::authorList( $Offset, $Limit, $SortOrder );
$db =& eZDB::globalDatabase();

$t->set_var( "author_item", "" );
$i = 0;
foreach ( $authors as $author )
{
    $articleCount = eZArticle::authorArticleCount( $author[$db->fieldName( "ContentsWriterID" )] );
    if ( $articleCount > 0 )
    {
        $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
        $t->set_var( "author_id", $author[$db->fieldName( "ContentsWriterID" )] );
        $t->set_var( "author_name", $author[$db->fieldName( "ContentsWriter" )] );
    
        $t->set_var( "article_count", $articleCount );
        $t->parse( "author_item", "author_item_tpl", true );
        $i++;
    }
}

$t->pparse( "output", "author_list_tpl" );

?>