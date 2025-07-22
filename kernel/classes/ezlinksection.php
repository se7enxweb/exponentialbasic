<?php
// 
// $Id: ezlinksection.php 7848 2001-10-15 11:32:18Z ce $
//
// Definition of eZLinkSection class
//
// Created on: <16-Mar-2001 19:25:51 amos>
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

//!! eZCommon
//! The class eZLinkSection handles link sections.
/*!
  Link sections are connected to a specific item in a module
  and contains zero or more links.

  To link a linksection to an item you need to use the eZModuleLink class.

  \sa eZModuleLink
*/

// include_once( "kernel/classes/ezlinkitem.php" );

class eZLinkSection
{
    /*!
      Initializes the link section with an $id and $module name.
      Set the id to false to create a new section.
    */
    function __construct( $id, $module )
    {
        $this->Module = $module;
        $this->ID = $id;
        if ( is_numeric( $id ) )
            $this->get( $id );
    }

    /*!
      Fetches the link section from the database with the given id.
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_LinkSection";
        $db->query_single( $row, "SELECT Name FROM $table_name WHERE ID='$id'" );
        $this->fill( $row );
    }

    /*!
      Stores the link section in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $table_name = $this->Module . "_LinkSection";
        if ( is_numeric( $this->ID ) and $this->ID > 0 )
        {
            $qry_text = "UPDATE $table_name";
            $qry_where = "WHERE ID='$this->ID'";
            $res = $db->query( "$qry_text
                     SET Name='$this->Name' $qry_where" );
            
        }
        else
        {
            $db->lock( $table_name );
            $nextID = $db->nextID( $table_name, "ID" );            

            $qry_text = "INSERT INTO $table_name";
            $res = $db->query( "$qry_text
                     (ID, Name) VALUES ( '$nextID', '$this->Name' )" );
            $db->unlock();
            $this->ID = $nextID;
        }
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes the linksections and all links connected to it from the database.
    */
    function delete( $id = false, $module = false )
    {
        if ( !$id )
            $id = $this->ID;
        if ( !$module )
            $module = $this->Module;
        $db =& eZDB::globalDatabase();
        $db->begin();
        $table_name = $module . "_Link";
        $res[] = $db->query( "DELETE FROM $table_name WHERE SectionID='$id'" );
        $table_name = $module . "_LinkSection";
        $res[] = $db->query( "DELETE FROM $table_name WHERE ID='$id'" );
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();            
    }

    /*!
      Fills in database information in the object.
    */
    function fill( $row )
    {
        $db =& eZDB::globalDatabase();
        $this->Name = $row[$db->fieldName( "Name" )];
    }

    /*!
      Returns an array of links connected to this section.
    */
    function &links()
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_Link";
        $db->array_query( $qry_array, "SELECT ID, Name, URL, Placement, ModuleType FROM $table_name
                                       WHERE SectionID='$this->ID' ORDER BY Placement ASC" );
        $ret = array();
        foreach( $qry_array as $row )
        {
            $ret[] = new eZLinkItem( $row, $this->Module );
        }
        return $ret;
    }

    /*!
      Moves the linksection up one place in the section list.
      If the top is reached it is wrapped to the bottom.
    */
    function moveUp( $objectid, $id, $module, $type )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $link_table_name = $module . "_$type" . "SectionDict";
        $type_column = $type . "ID";
        $db->query_single( $placement, "SELECT Placement FROM $link_table_name
                                        WHERE SectionID='$id'", "Placement" );
        $db->array_query( $items, "SELECT SectionID, Placement
                                   FROM $link_table_name
                                   WHERE $type_column='$objectid' AND Placement<'$placement'
                                   ORDER BY Placement DESC", array( "Limit" => 1 ) );
        if ( count( $items ) == 0 )
        {
            $db->array_query( $items, "SELECT SectionID, Placement
                                       FROM $link_table_name
                                       WHERE $type_column='$objectid' AND SectionID!='$id'
                                       ORDER BY Placement DESC", array( "Limit" => 1 ) );
            $swap_placement = $items[0][$db->fieldName( "Placement" )] + 1;
            $res[] = $db->query( "UPDATE $link_table_name
                         SET Placement=$swap_placement WHERE SectionID=$id" );
        }
        else
        {
            $swap_id = $items[0][$db->fieldName( "SectionID" )];
            $swap_placement = $items[0][$db->fieldName( "Placement" )];
            $res[] = $db->query( "UPDATE $link_table_name
                         SET Placement=$swap_placement WHERE SectionID=$id" );
            $res[] = $db->query( "UPDATE $link_table_name
                         SET Placement=$placement WHERE SectionID=$swap_id" );
        }
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();            
    }

    /*!
      Moves the linksection down one place in the section list.
      If the bottom is reached it is wrapped to the top.
    */
    function moveDown( $objectid, $id, $module, $type )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $link_table_name = $module . "_$type" . "SectionDict";
        $type_column = $type . "ID";
        $db->query_single( $placement, "SELECT Placement FROM $link_table_name
                                        WHERE SectionID='$id'", "Placement" );
        $db->array_query( $items, "SELECT SectionID, Placement
                                   FROM $link_table_name
                                   WHERE $type_column='$objectid' AND Placement>'$placement'
                                   ORDER BY Placement ASC", array( "Limit" => 1 ) );
        if ( count( $items ) == 0 )
        {
            $db->array_query( $items, "SELECT SectionID, Placement
                                       FROM $link_table_name
                                       WHERE $type_column='$objectid' AND SectionID!='$id'
                                       ORDER BY Placement ASC", array( "Limit" => 1 ) );
            $swap_placement = $items[0][$db->fieldName( "Placement" )] - 1;
            $res[] = $db->query( "UPDATE $link_table_name
                         SET Placement=$swap_placement WHERE SectionID=$id" );
        }
        else
        {
            $swap_id = $items[0][$db->fieldName( "SectionID" )];
            $swap_placement = $items[0][$db->fieldName( "Placement" )];
            $res[] = $db->query( "UPDATE $link_table_name
                         SET Placement=$swap_placement WHERE SectionID=$id" );
            $res[] = $db->query( "UPDATE $link_table_name
                         SET Placement=$placement WHERE SectionID=$swap_id" );
        }
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();            
    }

    /*!
      Sets the name of the section.
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Returns the id of the section.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the section.
    */
    function name()
    {
        return $this->Name;
    }

    var $Module;
    var $ID;
    var $Name;
}

?>