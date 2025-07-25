<?
//
// Definition of eZGroupEventType class
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
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

//!! eZEventCalendar
//! eZGroupEventType handles appointment types.
/*!
  
*/

/*!TODO
  Implement activeAppointments();
*/

//include_once( "classes/ezdb.php" );

class eZGroupEventType
{
    /*!
      Constructs a new eZGroupEventType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZGroupEventType object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZGroupEventCalendar_EventType SET
                             Name='$this->Name',
                                 Description='$this->Description',
                                 ParentID='$this->ParentID'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZGroupEventCalendar_EventType SET
                             Name='$this->Name',
                                 Description='$this->Description',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZAppointmentGroup object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZGroupEventCalendar_EventType WHERE ID='$this->ID'" );            
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $EventType_array, "SELECT * FROM eZGroupEventCalendar_EventType WHERE ID='$id'" );
            if ( count( $EventType_array ) > 1 )
            {
                die( "Error: EventType's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $EventType_array ) == 1 )
            {
                $this->ID = $EventType_array[0][ "ID" ];
                $this->Name = $EventType_array[0][ "Name" ];
                $this->Description = $EventType_array[0][ "Description" ];
                $this->ParentID = $EventType_array[0][ "ParentID" ];
                $this->ExcludeFromSearch = $EventType_array[0][ "ExcludeFromSearch" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZGroupEventType objects.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $EventType_array = array();
        
        $this->Database->array_query( $EventType_array, "SELECT ID FROM eZGroupEventCalendar_EventType ORDER BY Name" );
        
        for ( $i=0; $i<count($EventType_array); $i++ )
        {
            $return_array[$i] = new eZGroupEventType( $EventType_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the types with the EventType given as parameter as parent.

      If $showAll is set to true every EventType is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZGroupEventType objects.      
    */
    function &getByParent( $parent, $showAll=false, $sortby="name" )
    {
        if ( is_a( $parent, "eZGroupEventType" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $eventtype_array = array();

            $parentID = $parent->id();


            if ( $showAll == true )
            {
                $this->Database->array_query( $eventtype_array, "SELECT ID, Name FROM eZGroupEventCalendar_EventType
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );
            }
            else
            {
                $this->Database->array_query( $eventtype_array, "SELECT ID, Name FROM eZGroupEventCalendar_EventType
                                          WHERE ParentID='$parentID' AND ExcludeFromSearch='false'
                                          ORDER BY Name" );
            }

            for ( $i=0; $i<count($eventtype_array); $i++ )
            {
                $return_array[$i] = new eZGroupEventType( $eventtype_array[$i]["ID"], 0 );
            }

            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function path( $EventTypeID=0 )
    {
        if ( $EventTypeID == 0 )
        {
            $EventTypeID = $this->ID;
        }
            
        $EventType = new eZGroupEventType( $EventTypeID );

        $path = array();

        $parent = $EventType->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
//              array_push( $path, $EventType->name() );
        }

        if ( $EventTypeID != 0 )
            array_push( $path, array( $EventType->id(), $EventType->name() ) );                                
        
        return $path;
    }

    /*!
      Returns the categories sorted as a tree.
    */
    function &getTree( $parentID=0, $level=0 )
    {

        $EventType = new eZGroupEventType( $parentID );

        $EventTypeList =& $EventType->getByParent( $EventType, true );
        
        $tree = array();
        $level++;
        foreach ( $EventTypeList as $EventType )
        {
            array_push( $tree, array( $return_array[] = new eZGroupEventType( $EventType->id() ), $level ) );

            if ( $EventType != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $EventType->id(), $level ) );
            }
        }

        return $tree;
    }
    
    /*!
      Returns the object ID to the EventType. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns the name of the EventType.
    */
    function name( $htmlchars=true )
    {
        if ( $htmlchars == true && !is_null( $this->Name ) )
        {           
            return htmlspecialchars( $this->Name );
        }
        else
        {
            return $this->Name;
        }
    }

    /*!
      Returns the group description.
    */
    function description( $htmlchars=true )
    {
        if ( $htmlchars == true )
        {           
            return htmlspecialchars( $this->Description );
        }
        else
        {
            return $this->Description;
        }
    }
    
    /*!
      Returns the parentID, which is 0 if there is no parent.
    */
    function parentID()
    {
        return $this->ParentID;
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->ParentID != 0 )
       {
           return new eZGroupEventType( $this->ParentID );
       }
       else
       {
           return 0;           
       }
    }

    /*!
      Sets the name of the EventType.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the EventType.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the parent EventType.
    */
    function setParent( $value )
    {
		if ( get_class( $value ) == "ezgroupeventtype" )
		{
            $this->ParentID = $value->id();
        }
     }

    /*!
      Private function.      
      Open the database for read and write. Gets all the database information from site.ini.      
    */    
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $Name;
    var $ParentID;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
    var $ExcludeFromSearch;
}

?>