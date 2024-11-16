<?php
//
// $id$
// Bjørn Reiten <br@ez.no>
// Created on: <15-Jun-2001 15:24:47 br>
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


include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );

class eZExample
{
    /*!
      Constructs a new eZExample object
     */
    function eZExample( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Fetches the Text from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        if ( $id != -1  )
        {
            $db->array_query( $text_array, "SELECT * FROM eZExample_Test WHERE ID='$id'" );
            
            if ( count( $text_array ) > 1 )
            {
                die( "Error: Text with the same ID was found in the database. This shouldn't happen." );
            }
            else if ( count( $text_array ) == 1 )
            {
                $this->ID =& $text_array[0][$db->fieldName("ID")];
                $this->Text =& $text_array[0][$db->fieldName("Text")];
                $this->Created =& $text_array[0][$db->fieldName("Created")];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Retrieves all Text fields from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $url_array = array();
        
        $db->array_query( $url_array, "SELECT ID FROM eZExample_Test ORDER BY Created DESC"  );
        
        for ( $i=0; $i<count($url_array); $i++ )
        {
            $return_array[$i] = new eZExample( $url_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;
    }


    /*!
      Stores/updates the Text.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        
        $text = $db->escapeString( $this->Text );
        $timeStamp =& eZDateTime::timeStamp( true );

        $db->begin();
        $db->lock( "eZExample_Test" );
        
        $nextID = $db->nextID( "eZExample_Test", "ID" );

        if ( !isset( $this->ID ) )
        {
            $ret = $db->query( "INSERT INTO eZExample_Test
                       ( ID,
                         Text, 
                         Created )
                       VALUES
                       ( '$nextID',
		                 '$text',
                         '$timeStamp')
                       " );
			$this->ID = $nextID;
        }
        else
        {
            $ret = $db->query( "UPDATE eZExample_Test SET
		                 Text='$text'
                         WHERE ID='$this->ID'" );
        }
        $db->unlock();

        if ( $ret == false )
            $db->rollback();
        else
            $db->commit();

        return $ret;
    }

    
    /*!
      Deletes a Text from the database.
    */
    function delete( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( is_numeric( $id ) )
        {
             $this->ID = $id;
        }
        
        $db->begin();
        $ret = $db->query( "DELETE FROM eZExample_Test WHERE ID='$this->ID'" );

        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit( );

        return $ret;
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the Text From the database.
    */
    function text()
    {
        return $this->Text;
    }

    /*!
      Sets the text in the database.
    */
    function setText( $value )
    {
        $this->Text = $value;
    }

    var $ID;
    var $Text;
    var $Created;
}
?>
