<?php
// 
// $Id: menuforumlist.php 7592 2001-09-30 11:48:33Z bf $
//
// Created on: <30-Sep-2001 14:38:03 bf>
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
include_once( "classes/eztexttool.php" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezlist.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "menuforumlist.php" );

$t->set_file( "menuforum_list_tpl", "menuforumlist.tpl"  );
$t->set_block( "menuforum_list_tpl", "forum_item_tpl", "forum_item" );
$t->setAllStrings();

$category = new eZForumCategory( $CategoryID );
$forumList = $category->forums();
$t->set_var( "forum_category_name", $category->name() );

$t->set_var( "forum_item", "" );

foreach ( $forumList as $forum )
{
    $t->set_var( "forum_id", $forum->id() );
    $t->set_var( "forum_name", $forum->name() );

    $t->parse( "forum_item", "forum_item_tpl", true );
}

$t->pparse( "output", "menuforum_list_tpl" );

?>
