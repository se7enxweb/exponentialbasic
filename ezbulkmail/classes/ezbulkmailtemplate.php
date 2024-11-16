<?php
// 
// $Id: ezbulkmailtemplate.php 8528 2001-11-19 14:01:43Z jhe $
//
// eZBulkMailTemplate class
//
// Created on: <17-Apr-2001 14:07:32 fh>
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

//!! eZBulkMail
//! eZBulkMailTemplate documentation.
/*!

  Example code:
  \code
  \endcode

*/
	      
class eZBulkMailTemplate
{
    /*!
    */
    function eZBulkMailTemplate( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZBulkMail object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $header = $db->escapeString( $this->Header );
        $footer = $db->escapeString( $this->Footer );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZBulkMail_Template" );
            $nextID = $db->nextID( "eZBulkMail_Template", "ID" );
            $result = $db->query( "INSERT INTO eZBulkMail_Template
                                  ( ID, Header, Name, Footer, Description )
                                  VALUES
                                  ( '$nextID',
                                    '$header',
                                    '$name',
                                    '$footer',
                                    '$description' )
                                 " );
			$this->ID = $nextID;
        }
        else
        {
            $result = $db->query( "UPDATE eZBulkMail_Template SET
                                 Header='$header',
                                 Name='$name',
                                 Footer='$footer',
                                 Description='$description'
                                 WHERE ID='$this->ID'" );
        }

        $db->unlock();
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes a eZBulkMail object from the database.

    */
    function delete( $id = -1)
    {
        $db =& eZDB::globalDatabase();

        if ( $id == -1 )
            $id = $this->ID;

        $db->begin();
        $result = $db->query( "DELETE FROM eZBulkMail_Template WHERE ID='$id'" );
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $template_array, "SELECT * FROM eZBulkMail_Template WHERE ID='$id'" );
            if ( count( $template_array ) > 1 )
            {
                die( "Error: Templates with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $template_array ) == 1 )
            {
                $this->ID = $template_array[0][$db->fieldName( "ID" )];
                $this->Name = $template_array[0][$db->fieldName( "Name" )];
                $this->Header = $template_array[0][$db->fieldName( "Header" )];
                $this->Footer = $template_array[0][$db->fieldName( "Footer" )];
                $this->Description = $template_array[0][$db->fieldName( "Description")];
            }
        }
    }

    /*!
      Returns all the templates found in the database.

      The categories are returned as an array of eZBulkMailTemplate objects.
    */
    function getAll()
    {
        $db =& eZDB::globaldatabase();
        $return_array = array();
        $template_array = array();
        
        $db->array_query( $template_array, "SELECT ID, Name FROM eZBulkMail_Template ORDER BY Name" );
        for ( $i = 0; $i < count( $template_array ); $i++ )
        {
            $return_array[$i] = new eZBulkMailTemplate( $template_array[$i][$db->fieldName( "ID" )] );
        }
        
        return $return_array;
    }

    /*!
      Returns the header
     */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the header
     */
    function header()
    {
        return $this->Header;
    }

        /*!
      Returns the footer
     */
    function footer()
    {
        return $this->Footer;
    }

    /*!
      Returns the name of this template
     */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the description of this template
     */
    function description()
    {
        return $this->Description;
    }

    
    /*!
      Sets the header.
     */
    function setHeader( $value )
    {
        $this->Header = $value;
    }

    /*!
      Sets the footer
    */
    function setFooter( $value )
    {
        $this->Footer = $value;
    }

    /*!
      Sets the name
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

        /*!
      Sets the name
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    
    /*!
      Adds the footer and the header to the given message and returns the result.
     */
    function buildMessage( $message )
    {
        return $this->Header . $message . $this->Footer;
    }
    
    var $Header;
    var $Footer;
    var $Name;
    var $Description;
}

?>
