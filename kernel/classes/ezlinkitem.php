<?php
// 
// $Id: ezlinkitem.php 9873 2003-07-22 10:57:19Z vl $
//
// Definition of eZLinkItem class
//
// Created on: <19-Mar-2001 13:15:16 amos>
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

//!! eZCommon
//! The class eZLinkItem handles link items contained in a section.
/*!
  Contains information on the name, the url and the type of link.

  \sa eZLinkSection, eZModuleLink
*/

class eZLinkItem
{
    /*!
      Initializes the link item with an id and a module name.
    */
    function __construct( $id, $module )
    {
        $this->Module = $module;
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( is_numeric( $id ) )
        {
            $this->get( $id );
        }
    }

    /*!
      Fetches information from the database using the id.
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_Link";
        $db->query_single( $row, "SELECT ID, Name, URL, Placement, ModuleType FROM $table_name WHERE ID='$id'" );
        $this->fill( $row );
    }

    /*!
      Stores the information in the database.
    */
    function store()
    {
        $table_name = $this->Module . "_Link";
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        if ( is_numeric( $this->ID ) and $this->ID > 0 )
        {
            $qry_text = "UPDATE $table_name";
            $qry_where = "WHERE ID='$this->ID'";

            $db->query( "$qry_text
                     SET SectionID='$this->Section',
                         Name='$name',
                         URL='$this->URL',
                         Placement='$this->Placement',
                         ModuleType='$this->ModuleType' $qry_where" );
        }
        else
        {
            $qry_text = "INSERT INTO $table_name";
            $db->array_query( $qry_array, "SELECT Placement FROM $table_name
                                           WHERE SectionID='$this->Section'
                                           ORDER BY Placement", array( "Limit" => 1 ) );
            $this->Placement = count( $qry_array ) == 1 ? $qry_array[0][$db->fieldName( "Placement" )] + 1 : 1;

            $db->lock( $table_name );
            $nextID = $db->nextID( $table_name, "ID" );            
                        
            $res = $db->query( "$qry_text
                     ( ID, SectionID, Name, URL, Placement, ModuleType )
                     VALUES( '$nextID', '$this->Section', '$name', '$this->URL', '$this->Placement', '$this->ModuleType' )" );
            $this->ID = $nextID;
            $db->unlock();
        }

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes the object from the database.
    */
    function delete( $id = false, $module = false )
    {
        if ( !$id )
            $id = $this->ID;
        if ( !$module )
            $module = $this->Module;
        $table_name = $module . "_Link";
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res = $db->query( "DELETE FROM $table_name WHERE ID='$id'" );
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Fills in database information to the object.
    */
    function fill( $row )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $row[$db->fieldName( "ID" )];
        $this->Name = $row[$db->fieldName( "Name" )];
        $this->URL = $row[$db->fieldName( "URL" )];
        $this->Placement = $row[$db->fieldName( "Placement" )];
        $this->ModuleType = $row[$db->fieldName( "ModuleType" )];
    }

    /*!
      Sets the section this link item is connected to.
    */
    function setSection( $section )
    {
        $this->Section = $section;
    }

    /*!
      Sets the name of the link item.
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Sets the url of the link item.
    */
    function setURL( $url )
    {
        $this->URL = $url;
    }

    /*!
      Returns the id of the link item in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the section id.
    */
    function section()
    {
        return $this->Section;
    }

    /*!
      Returns the name of the item.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the url of the item.
    */
    function url()
    {
        return $this->URL;
    }

    /*!
      Returns the type id of the link item if $as_name is set to false,
      otherwise the module and type name is returned in an array.
    */
    function type( $as_name = false )
    {
        if ( !$as_name )
        {
            return $this->ModuleType;
        }
        else
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $row, "SELECT Module, Type FROM eZModule_LinkModuleType
                                      WHERE ID='$this->ModuleType'" );
            return $row;
        }
    }

    /*!
      Sets the type of the link item.
      It makes sure that only one entry is created for each unique module/type pair
      and sets the id of that type in this object.
    */
    function setType( $module, $type )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $rows, "SELECT ID FROM eZModule_LinkModuleType
                                  WHERE Module='$module' AND Type='$type'", 0, 1, "ID" );
        if ( count( $rows ) == 0 )
        {
            $db->begin();
            $db->lock( "eZModule_LinkModuleType" );
            $nextID = $db->nextID( "eZModule_LinkModuleType", "ID" );            

            $res = $db->query( "INSERT INTO eZModule_LinkModuleType ( ID, Module, Type ) VALUES ( '$nextID', '$module', '$type' )" );
            $this->ModuleType = $nextID;
            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
        else
        {
            $this->ModuleType = $rows[0];
        }
    }

    /*!
      Moves the item up with wrapping.
    */
    function moveUp( $sectionid, $id, $module )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $table_name = $module . "_Link";
        $db->query_single( $placement, "SELECT Placement FROM $table_name
                                        WHERE ID='$id'", "Placement" );
        $db->array_query( $items, "SELECT ID, Placement
                                   FROM $table_name
                                   WHERE SectionID='$sectionid' AND Placement<'$placement'
                                   ORDER BY Placement DESC", array( "Limit" => 1 ) );
        if ( count( $items ) == 0 )
        {
            $db->array_query( $items, "SELECT Placement
                                       FROM $table_name
                                       WHERE SectionID='$sectionid' AND ID!='$id'
                                       ORDER BY Placement DESC", array( "Limit" => 1 ) );
            $swap_placement = $items[0][$db->fieldName( "Placement" )] + 1;
            $res[] = $db->query( "UPDATE $table_name
                         SET Placement=$swap_placement WHERE ID=$id" );
        }
        else
        {
            $swap_id = $items[0][$db->fieldName("ID")];
            $swap_placement = $items[0][$db->fieldName("Placement")];
            $res[] = $db->query( "UPDATE $table_name
                         SET Placement=$swap_placement WHERE ID=$id" );
            $res[] = $db->query( "UPDATE $table_name
                         SET Placement=$placement WHERE ID=$swap_id" );
        }
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Moves the item down with wrapping.
    */
    function moveDown( $sectionid, $id, $module )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $table_name = $module . "_Link";
        $db->query_single( $placement, "SELECT Placement FROM $table_name
                                        WHERE ID='$id'", "Placement" );
        $db->array_query( $items, "SELECT ID, Placement
                                   FROM $table_name
                                   WHERE SectionID='$sectionid' AND Placement>'$placement'
                                   ORDER BY Placement ASC", array( "Limit" => 1 ) );
        if ( count( $items ) == 0 )
        {
            $db->array_query( $items, "SELECT Placement
                                       FROM $table_name
                                       WHERE SectionID='$sectionid' AND ID!='$id'
                                       ORDER BY Placement ASC", array( "Limit" => 1 ) );
            $swap_placement = $items[0][$db->fieldName( "Placement" )] - 1;
            $res[] = $db->query( "UPDATE $table_name
                         SET Placement=$swap_placement WHERE ID=$id" );
        }
        else
        {
            $swap_id = $items[0][$db->fieldName( "ID" )];
            $swap_placement = $items[0][$db->fieldName("Placement" )];
            $res[] = $db->query( "UPDATE $table_name
                         SET Placement=$swap_placement WHERE ID=$id" );
            $res[] = $db->query( "UPDATE $table_name
                         SET Placement=$placement WHERE ID=$swap_id" );
        }
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();
    }

    var $ID;
    var $Section;
    var $Name;
    var $URL;
    var $Placement;
    var $Module;
    var $ModuleType;
}

?>