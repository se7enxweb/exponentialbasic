<?php
// 
// $Id: ezsectionfrontpage.php 7874 2001-10-16 10:32:43Z ce $
//
// ezsectionfrontpage class
//
// Created on: <02-Oct-2001 12:38:11 ce>
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

//!! ezsectionfrontpage
//! ezsectionfrontpages handles sections..
/*!

  Example code:
  \code
  \endcode

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
	      
class eZSectionFrontPage
{

    /*!
      Constructs a new ezsectionfrontpage object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }
    
    /*!
      Stores a ezsectionfrontpage object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );
        
        if ( !isset( $this->ID ) )
        {
            $db->array_query( $attribute_array, "SELECT Placement FROM eZSiteManager_SectionFrontPageRow" );

            if ( count ( $attribute_array ) > 0 )
            {
                $place = max( $attribute_array );
                $place = $place[$db->fieldName( "Placement" )];
                $place++;
            }

            $db->lock( "eZSiteManager_SectionFrontPageRow" );

            $nextID = $db->nextID( "eZSiteManager_SectionFrontPageRow", "ID" );
            
            $timeStamp = eZDateTime::timeStamp( true );

            $res = $db->query( "INSERT INTO eZSiteManager_SectionFrontPageRow
                                     ( ID, SettingID, CategoryID, Placement  )
                                     VALUES
                                     ( '$nextID',
                                       '$this->SettingID',
                                       '$this->CategoryID',
                                       '$place'
				        )" );

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZSiteManager_SectionFrontPageRow SET
		                             SettingID='$this->SettingID',
		                             CategoryID='$this->CategoryID'
                                     WHERE ID='$this->ID'" );
        }

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a ezsectionfrontpage object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZSiteManager_SectionFrontPageRowLink WHERE FrontPageID='$catID'" );
        $db->query( "DELETE FROM eZSiteManager_SectionFrontPageRow WHERE ID='$catID'" );
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $section_array, "SELECT * FROM eZSiteManager_SectionFrontPageRow WHERE ID='$id'" );
            if ( count( $section_array ) > 1 )
            {
                die( "Error: Section's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $section_array ) == 1 )
            {
                $this->ID =& $section_array[0][$db->fieldName("ID")];
                $this->CategoryID =& $section_array[0][$db->fieldName("CategoryID")];
                $this->SettingID =& $section_array[0][$db->fieldName("SettingID")];
                $this->Placement =& $section_array[0][$db->fieldName( "Placement" )];
                $ret = true;
            }
        }

        return $ret;
    }

    function setSettingID( $value )
    {
        $this->SettingID = $value;
    }

    function setCategoryID( $value )
    {
        $this->CategoryID = $value;
    }

    function categoryID(  )
    {
        return $this->CategoryID;
    }

    function settingID(  )
    {
        return $this->SettingID;
    }

    function id(  )
    {
        return $this->ID;
    }

    function &settingByRowID( $id )
    {
        $db =& eZDB::globalDatabase();
                
        $db->query_single( $setting, "SELECT Name FROM
                                           eZSiteManager_SectionFrontPageSetting,
                                           eZSiteManager_SectionFrontPageRow WHERE
                                           eZSiteManager_SectionFrontPageRow.ID='$id' AND
                                           SettingID=eZSiteManager_SectionFrontPageSetting.ID" );

        return $setting[$db->fieldName( "Name" )];
    }
    
    static public function &settingNames()
    {
        $db =& eZDB::globalDatabase();
        $ret = array();
        $db->array_query( $section_array, "SELECT ID, Name
                                           FROM eZSiteManager_SectionFrontPageSetting ORDER BY ID" );

        foreach ( $section_array as $name )
        {
            $ret[] = array( "Name" => $name[$db->fieldName( "Name" )], "ID" => $name[$db->fieldName( "ID" )] );
        }
        return $ret;
    }

    function &settingByID( $id )
    {
        $db =& eZDB::globalDatabase();
                
        $db->query_single( $setting, "SELECT Name
                                           FROM eZSiteManager_SectionFrontPageSetting WHERE ID='$id'" );

        return $setting[$db->fieldName( "Name" )];
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZSiteManager_SectionFrontPageRow
                                  WHERE Placement<'$this->Placement' ORDER BY Placement DESC", array( "Limit" => 1, "Offset" => 0 ) );
        $listorder = $qry[$db->fieldName( "Placement" )];
        $listid = $qry[$db->fieldName( "ID" )];

        $res[] = $db->query( "UPDATE eZSiteManager_SectionFrontPageRow SET Placement='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZSiteManager_SectionFrontPageRow SET Placement='$this->Placement' WHERE ID='$listid'" );

        eZDB::finish( $res, $db );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $db->query_single( $qry, "SELECT ID, Placement FROM eZSiteManager_SectionFrontPageRow
                                  WHERE Placement>'$this->Placement' ORDER BY Placement ASC", array( "Limit" => 1, "Offset" => 0 ) );
        $listorder = $qry[$db->fieldName( "Placement" )];
        $listid = $qry[$db->fieldName( "ID" )];
        $res[] = $db->query( "UPDATE eZSiteManager_SectionFrontPageRow SET Placement='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZSiteManager_SectionFrontPageRow SET Placement='$this->Placement' WHERE ID='$listid'" );

        eZDB::finish( $res, $db );
    }


    var $ID;
    var $SettingID;
    var $CategoryID;
    var $Placement;
}

?>