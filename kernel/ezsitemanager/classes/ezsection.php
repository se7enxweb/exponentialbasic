<?php
//
// $Id: ezsection.php 8954 2002-01-16 10:37:22Z kaid $
//
// ezsection class
//
// Created on: <10-May-2001 15:13:08 ce>
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

//!! eZSection
//! eZSections handles sections..
/*!

  Example code:
  \code
  \endcode

*/

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );

// include_once( "ezsitemanager/classes/ezsectionfrontpage.php" );

class eZSection
{

    /*!
      Constructs a new eZSection object.

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
      Stores a eZSection object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin( );

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $siteDesign = $db->escapeString( $this->SiteDesign );
        $templateStyle = $db->escapeString( $this->TemplateStyle );
        $secLanguage = $db->escapeString( $this->SecLanguage );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZSiteManager_Section" );

            $nextID = $db->nextID( "eZSiteManager_Section", "ID" );

            $timeStamp = (new eZDateTime())->timeStamp( true );

            $res = $db->query( "INSERT INTO eZSiteManager_Section
                                     ( ID,  Name, Created, Description, TemplateStyle, SiteDesign, Language )
                                     VALUES
                                     ( '$nextID',
                                       '$name',
                                       '$timeStamp',
                                       '$description',
                                       '$templateStyle',
                                       '$siteDesign',
				                       '$secLanguage' )" );

			$this->ID = $nextID;
        }
        else
        {
            $query = "UPDATE eZSiteManager_Section SET
		                             Name='$name',
		                             SiteDesign='$siteDesign',
		                             TemplateStyle='$templateStyle',
                                     Description='$description',
				                     Language='$secLanguage'
                                     WHERE ID='$this->ID'";

            $res = $db->query( $query );
        }

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZSection object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZSiteManager_Section WHERE ID='$this->ID'" );
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
            $query = "SELECT * FROM eZSiteManager_Section WHERE ID='$id'";
            $db->array_query( $section_array, $query );
            if ( count( $section_array ) > 1 )
            {
                die( "Error: Section's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $section_array ) == 1 )
            {
                $this->ID = $section_array[0][$db->fieldName("ID")];
                $this->Name = $section_array[0][$db->fieldName("Name")];
                $this->SiteDesign = $section_array[0][$db->fieldName("SiteDesign")];
//				if ( !isset( $section_array[0][$db->fieldName("TemplateStyle")] ) )
//					$section_array[0][$db->fieldName("TemplateStyle")] = "";
                $this->TemplateStyle = $section_array[0][$db->fieldName("TemplateStyle")];
				if ( !isset( $section_array[0][$db->fieldName("Description")] ) )
					$section_array[0][$db->fieldName("Description")] = "";
                $this->Description = $section_array[0][$db->fieldName("Description")];
                $this->Created = $section_array[0][$db->fieldName("Created")];
				if ( !isset( $section_array[0][$db->fieldName("Language")] ) )
					$section_array[0][$db->fieldName("Language")] = "";
                $this->SecLanguage = $section_array[0][$db->fieldName("Language")];
                $ret = true;
            }
        }

        return $ret;
    }

    /*!
      Static
      Returns all the categories found in the database.

      The categories are returned as an array of eZSection objects.
    */
    static public function getAll( $offset=0, $limit=40)
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $section_array = array();

        $db->array_query( $section_array, "SELECT ID, Created
                                           FROM eZSiteManager_Section
                                           ORDER BY Created ASC",
        array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i=0; $i < count($section_array); $i++ )
        {
            $return_array[$i] = new eZSection( $section_array[$i][$db->fieldName("ID")]  );
        }

        return $return_array;
    }

    /*!
      Returns the total count.
    */
    static public function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZSiteManager_Section" );
        $ret = $result[$db->fieldName("Count")];
        return $ret;
    }


    /*!
      Returns the object ID to the section. This is the unique ID stored in the database.
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
	if( isset( $this->Name ) )
            return htmlspecialchars( $this->Name );

	return $this->Name;
    }

    /*!
      \static
      Returns the SiteDesign of the section.

      If $sectionID is a number, the function will return the sitedesign for that section ID.
    */
    public function siteDesign( $sectionID=false )
    {
        if ( $sectionID != false )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $siteDesign, "SELECT SiteDesign FROM eZSiteManager_Section WHERE ID='$sectionID'" );
	    if( is_array( $siteDesign ) && !empty( $siteDesign ) )
	    {
                return $siteDesign[$db->fieldName("SiteDesign")];
	    }
        }
        else {
            if( isset( $this->SiteDesign ) )
                return htmlspecialchars( $this->SiteDesign );
        }

	    return false;
    }

    static public function siteDesignStatic( $sectionID=false )
    {
        if ( $sectionID != false )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $siteDesign, "SELECT SiteDesign FROM eZSiteManager_Section WHERE ID='$sectionID'" );
            return $siteDesign[$db->fieldName("SiteDesign")];
        }

        return false;
    }


    /*!
      \static
      Returns the template style for this section.
    */
    function templateStyle( $sectionID=false )
    {
        if ( $sectionID != false )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $templateStyle, "SELECT TemplateStyle FROM eZSiteManager_Section WHERE ID='$sectionID'" );
            return $templateStyle[$db->fieldName("TemplateStyle")];
        }
        else {
            if( isset( $this->TemplateStyle ) )
                return htmlspecialchars( $this->TemplateStyle );
        }
    }

    static public function templateStyleStatic( $sectionID=false )
    {
        if ( $sectionID != false )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $templateStyle, "SELECT TemplateStyle FROM eZSiteManager_Section WHERE ID='$sectionID'" );
            return $templateStyle[$db->fieldName("TemplateStyle")];
        }

        return false;
    }
    /*!
     \static
      Returns the language for this section.
    */
    static public function language( $sectionID=false )
    {
        if ( is_numeric ( $sectionID ) )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $templateStyle, "SELECT Language FROM eZSiteManager_Section WHERE ID='$sectionID'" );
            return $templateStyle[$db->fieldName("Language")];
        }
        // else
        //     return htmlspecialchars( $this->SecLanguage );
    }

    /*!
      Returns the section description.
    */
    function description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Sets the name of the section.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the SiteDesign of the section.
    */
    function setSiteDesign( $value )
    {
        $this->SiteDesign = $value;
    }

    /*!
      Sets the TemplateStyle of the section.
    */
    function setTemplateStyle( $value )
    {
        $this->TemplateStyle = $value;
    }

    /*!
      Sets the Language of the section.
    */
    function setLanguage( $value )
    {
        $this->SecLanguage = $value;
    }

    /*!
      Sets the description of the section.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      \static
      Will return the global section object for the given section ID.
     */
    static public function &globalSectionObject( $sectionID )
    {
        $objName = "eZSectionObject_$sectionID";
	    global $GLOBALS;
	
        if ( !isset( $GLOBALS[$objName] ) or !is_a( $GLOBALS[$objName], "eZSection" ) )
        {
            $GLOBALS[$objName] = new eZSection( $sectionID );
        }

        return $GLOBALS[$objName];
    }

    /*!
      Will set override variables for this section.

      Language and templatestyle variables will be overrided.
     */
    function setOverrideVariables()
    {
	global $GLOBALS;
        $ini =& eZINI::instance( 'site.ini' );
	
        // set the sitedesign from the section
        if ( $ini->variable( "site", "Sections" ) == "enabled" )
        {
            if ( !is_null( $this->TemplateStyle ) && trim( $this->TemplateStyle ) != "" )
            {
                $GLOBALS["eZTemplateOverride"] = trim( $this->TemplateStyle );
            }

            if (  !is_null( $this->SecLanguage ) && trim( $this->SecLanguage ) != "" )
            {
                $GLOBALS["eZLanguageOverride"] = trim( $this->SecLanguage );
            }
        }
    }

    static public function &settingNames()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $section_array, "SELECT ID, Name
                                           FROM eZSiteManager_SectionFrontPageSetting" );

        return $section_array;
    }

    function &frontPageRows()
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();

        $db->array_query( $rows, "SELECT eZSiteManager_SectionFrontPageRow.ID as ID FROM eZSiteManager_SectionFrontPageRowLink, eZSiteManager_SectionFrontPageRow
                                           WHERE eZSiteManager_SectionFrontPageRowLink.SectionID='$this->ID' AND eZSiteManager_SectionFrontPageRowLink.FrontPageID = eZSiteManager_SectionFrontPageRow.ID ORDER BY eZSiteManager_SectionFrontPageRow.Placement" );

        foreach( $rows as $row )
        {
            $returnArray[] = new eZSectionFrontPage( $row[$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    function addFrontPageRow( $rowID )
    {
        if ( is_a( $rowID, "eZSectionFrontPage" ) )
            $rowID = $rowID->id();
        $db =& eZDB::globalDatabase();
        $db->begin( );

        $db->lock( "eZSiteManager_SectionFrontPageRowLink" );

        $nextID = $db->nextID( "eZSiteManager_SectionFrontPageRowLink", "ID" );

        $timeStamp = (new eZDateTime())->timeStamp( true );

        $res[] = $db->query( "INSERT INTO eZSiteManager_SectionFrontPageRowLink
                                     ( ID,  SectionID, FrontPageID )
                                     VALUES
                                     ( '$nextID',
                                       '$this->ID',
                                       '$rowID'
                                        )" );

        $db->unlock();
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();
    }

    var $ID;
    var $Name;
    var $SiteDesign;
    var $TemplateStyle;
    var $Description;
    var $Created;
    var $SecLanguage;

}

?>