<?php
//
// $Id: ezurltranslator.php 9232 2002-02-15 18:51:33Z master $
//
// Definition of eZURLTranslator class
//
// Created on: <22-Apr-2001 18:38:38 bf>
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

//!! eZURLTranslator
//! The eZURLTranslator class provides URL translation functions.
/*!

*/

// include_once( "classes/ezdb.php" );

class eZURLTranslator
{
    /*!

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
      \static
    */
    static public function translate( $url )
    {
        $ret = false;

	// Hack to delete a trailing slash
	$url = preg_replace("'/$'", "", $url);

        $db =& eZDB::globalDatabase();

        $db->array_query( $url_array,
            "SELECT Dest FROM eZURLTranslator_URL
             WHERE Source='$url'" );

	$url = preg_replace("'/$'", "", $url);

        if ( count( $url_array ) > 0 )
        {
            $ret = $url_array[0][$db->fieldName("Dest")];
        }
        return $ret;
    }

    /*!
      Stores/updates the URL translation.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZURLTranslator_URL" );

            $nextID = $db->nextID( "eZURLTranslator_URL", "ID" );
            $timeStamp =& eZDateTime::timeStamp( true );

            $res = $db->query( "INSERT INTO eZURLTranslator_URL
                         ( ID, Source, Dest, Created ) VALUES
                         ( '$nextID',
                           '$this->Source',
		                   '$this->Dest',
		                   '$timeStamp' )
                          " );

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZURLTranslator_URL SET
		                 Source='$this->Source',
		                 Dest='$this->Dest'
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
      Fetches the URL translation from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != -1  )
        {
            $db->array_query( $url_array, "SELECT * FROM eZURLTranslator_URL WHERE ID='$id'" );

            if ( count( $url_array ) > 1 )
            {
                die( "Error: Url translations's with the same ID was found in the database. This shouldn't happen." );
            }
            else if ( count( $url_array ) == 1 )
            {
                $this->ID =& $url_array[0][$db->fieldName("ID")];
                $this->Source =& $url_array[0][$db->fieldName("Source")];
                $this->Dest =& $url_array[0][$db->fieldName("Dest")];
            }
        }
    }

    // EP --------------------------------------------------------------------------
    /*!
      Fetches the URL translation from the database using destination as a parameter.
    */
    function getbydest( $dest = '' )
    {
	$db =& eZDB::globalDatabase();

	if ( $dest != '' )
	{
	    $db->array_query( $url_array, "SELECT * FROM eZURLTranslator_URL WHERE Dest='$dest'" );

	    $this->ID =& $url_array[0][$db->fieldName( "ID" )];
	    $this->Source =& $url_array[0][$db->fieldName( "Source" )];
	    $this->Dest =& $url_array[0][$db->fieldName( "Dest" )];
	}
    }

    /*!
      Retrieves all the URL translations from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $url_array = array();

        $db->array_query( $url_array, "SELECT ID, Created FROM eZURLTranslator_URL ORDER BY Created" );

        for ( $i=0; $i<count($url_array); $i++ )
        {
            $return_array[$i] = new eZURLTranslator( $url_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Deletes a URL translation from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $res = $db->query( "DELETE FROM eZURLTranslator_URL WHERE ID='$this->ID'" );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the source of the url translation.
    */
    function source()
    {
        return $this->Source;
    }

    /*!
      Returns the destination of the url translation.
    */
    function dest()
    {
        return $this->Dest;
    }


    /*!
      Sets the source of the URL translation.
    */
    function setSource( $value )
    {
        $this->Source = $value;
    }

    /*!
      Sets the destination of the URL translation.
    */
    function setDest( $value )
    {
        $this->Dest = $value;
    }

    var $ID;
    var $Source;
    var $Dest;

}

?>
