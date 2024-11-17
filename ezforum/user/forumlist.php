<?php
//
// $Id: forumlist.php 7782 2001-10-11 11:06:20Z jhe $
//
// Created on: <11-Sep-2000 22:10:06 bf>
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

$ini =& INIFile::globalINI();

include_once( "classes/eztemplate.php" );

include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir"),
                     "ezforum/user/intl", $Language, "forumlist.php" );

$t->setAllStrings();

$t->set_file( "forumlist", "forumlist.tpl" );

$t->set_block( "forumlist", "view_forums_tpl", "view_forums" );
$t->set_block( "view_forums_tpl", "forum_item_tpl", "forum_item" );

$category = new eZForumCategory( $CategoryID );

// sections
include_once( "ezsitemanager/classes/ezsection.php" );

$GlobalSectionID = eZForumCategory::sectionIDStatic( $CategoryID );

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();


$t->set_var( "category_id", $category->id( ) );
$t->set_var( "category_name", $category->name( ) );
$t->set_var( "category_description", $category->name( ) );

$forumList =& $category->forums( );

if ( !$forumList )
{
    $languageIni = new INIFile( "ezforum/user/intl/" . $Language . "/categorylist.php.ini", false );
    $noitem =  $languageIni->read_var( "strings", "noitem" );

    $t->set_var( "forum_item", $noitem );
}

$i=0;
$j=0; // The number of viewable forums for this session.
foreach ( $forumList as $forum )
{
    $t->set_var( "forum_id", $forum->id() );

    $t->set_var( "name", $forum->name() );
    $t->set_var( "description", $forum->description() );

    $t->set_var( "threads", $forum->messageCount( false, false ) );
    $t->set_var( "messages", $forum->messageCount( false, true ) );


    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight"  );
    else
        $t->set_var( "td_class", "bgdark"  );

    $group =& $forum->group();

    if ( is_a( $group, "eZUserGroup" ) )
    {
        $user =& eZUser::currentUser();
        if ( is_a( $user, "eZUser" ) )
        {
            $groupList =& $user->groups();

            foreach ( $groupList as $userGroup )
            {
                if ( $userGroup->id() == $group->id() )
                {
                    $t->parse( "forum_item", "forum_item_tpl", true );
                    $j++;
                    break;
                }
            }
        }
    }
    else
    {
        $t->parse( "forum_item", "forum_item_tpl", true );
        $j++;
    }

    $i++;
}

if ( $j == 0 && $i > 0 )
{
    $t->set_var( "view_forums", $t->Ini->read_var( "strings", "no_forums_for_you" ) );
}
else if ( $j == 0 && $i == 0 )
{
    $t->set_var( "view_forums", $t->Ini->read_var( "strings", "no_forums" ) );
}
else
{
    $t->parse( "view_forums", "view_forums_tpl" );
}

$t->set_var( "category_id", $CategoryID );

$t->pparse( "output", "forumlist" );

?>
