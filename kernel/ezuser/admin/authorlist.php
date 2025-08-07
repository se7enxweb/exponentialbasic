<?php
// 
// $Id: authorlist.php 8273 2001-11-05 09:09:11Z jhe $
//
// Created on: <31-May-2001 13:27:04 bf>
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

// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

// include_once( "ezuser/classes/ezauthor.php" );

if ( isset( $NewAuthor ) )
{
    $author = new eZAuthor();
    $author->store();    
}

if ( isset( $DeleteAuthor ) )
{
    if ( count( $DeleteIDArray ) > 0 )
    {
        foreach ( $DeleteIDArray as $id )
        {
            eZAuthor::delete( $id );
        }
    }
}


if ( ( isset( $Store ) ) || ( isset( $NewAuthor ) ) ||( isset( $DeleteAuthor ) ) )
{
    $i = 0;

    if ( count( $IDArray )  > 0 )
    {
        foreach ( $IDArray as $id )
        {
            $author = new eZAuthor( $id );
            $author->setEMail( $EMail[$i] );
            $author->setName( $Name[$i] );
            $author->store();
            
            $i++;
        }
    }
}

$t = new eZTemplate( "kernel/ezuser/admin/" . $ini->variable( "eZUserMain", "AdminTemplateDir" ),
                     "kernel/ezuser/admin/intl", $Language, "authorlist.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "author_page_tpl", "authorlist.tpl" );

$t->setAllStrings();

$t->set_block( "author_page_tpl", "author_list_tpl", "author_list" );
$t->set_block( "author_list_tpl", "author_item_tpl", "author_item" );

$t->set_var( "author_item", "" );

$author = new eZAuthor();

$authorArray = $author->getAll();

$i = 0;

if ( count( $authorArray ) > 0 )
{
    foreach ( $authorArray as $author )
    {
        $t->set_var( "id", $author->id() );
        $t->set_var( "author_name", $author->name() );
        $t->set_var( "author_email", $author->email() );
        
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $i++;
        
        $t->parse( "author_item", "author_item_tpl", true );
	}
}

$t->parse( "author_list", "author_list_tpl" );

$t->pparse( "output", "author_page_tpl" );

?>