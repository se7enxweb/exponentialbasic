<?
//
// Definition of eZGroupEventCategory class
//
// Graham Brookins <info@brookinsconsulting.com>
// Created on: <3-Oct-2001 14:36:00>
// By: Adam Fallert <FallertA@umsystem.edu>
// 
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
//! eZGroupEventCategory handles appointment categorys.
/*!
  
*/

/*!TODO
  Implement activeAppointments();
*/

// include_once( "classes/ezdb.php" );

class eZGroupEventCategory
{
    /*!
      Constructs a new eZGroupEventCategory object.

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
      Stores a eZGroupEventCategory object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZGroupEventCalendar_EventCategory SET
                             Name='$this->Name',
                                 Description='$this->Description',
                                 ParentID='$this->ParentID'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZGroupEventCalendar_EventCategory SET
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
            $this->Database->query( "DELETE FROM eZGroupEventCalendar_EventCategory WHERE ID='$this->ID'" );            
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
            $this->Database->array_query( $EventCategory_array, "SELECT * FROM eZGroupEventCalendar_EventCategory WHERE ID='$id'" );
            if ( count( $EventCategory_array ) > 1 )
            {
                die( "Error: EventCategory's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $EventCategory_array ) == 1 )
            {
                $this->ID = $EventCategory_array[0][ "ID" ];
                $this->Name = $EventCategory_array[0][ "Name" ];
                $this->Description = $EventCategory_array[0][ "Description" ];
                $this->ParentID = $EventCategory_array[0][ "ParentID" ];
                $this->ExcludeFromSearch = $EventCategory_array[0][ "ExcludeFromSearch" ];
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

      The categories are returned as an array of eZGroupEventCategory objects.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $EventCategory_array = array();
        
        $this->Database->array_query( $EventCategory_array, "SELECT ID FROM eZGroupEventCalendar_EventCategory ORDER BY Name" );
        
        for ( $i=0; $i<count($EventCategory_array); $i++ )
        {
            $return_array[$i] = new eZGroupEventCategory( $EventCategory_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categorys with the EventCategory given as parameter as parent.

      If $showAll is set to true every EventCategory is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZGroupEventCategory objects.      
    */
    function &getByParent( $parent, $showAll=false, $sortby="name" )
    {
        if ( is_a( $parent, "eZGroupEventCategory" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $eventcategory_array = array();

            $parentID = $parent->id();


            if ( $showAll == true )
            {
                $this->Database->array_query( $eventcategory_array, "SELECT ID, Name FROM eZGroupEventCalendar_EventCategory
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );
            }
            else
            {
                $this->Database->array_query( $eventcategory_array, "SELECT ID, Name FROM eZGroupEventCalendar_EventCategory
                                          WHERE ParentID='$parentID' AND ExcludeFromSearch='1'
                                          ORDER BY Name" );
            }

            for ( $i=0; $i<count($eventcategory_array); $i++ )
            {
                $return_array[$i] = new eZGroupEventCategory( $eventcategory_array[$i]["ID"], 0 );
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
    function path( $EventCategoryID=0 )
    {
        if ( $EventCategoryID == 0 )
        {
            $EventCategoryID = $this->ID;
        }
            
        $EventCategory = new eZGroupEventCategory( $EventCategoryID );

        $path = array();

        $parent = $EventCategory->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
//              array_push( $path, $EventCategory->name() );
        }

        if ( $EventCategoryID != 0 )
            array_push( $path, array( $EventCategory->id(), $EventCategory->name() ) );                                
        
        return $path;
    }

    /*!
      Returns the categories sorted as a tree.
    */
    function &getTree( $parentID=0, $level=0 )
    {

        $EventCategory = new eZGroupEventCategory( $parentID );

        $EventCategoryList =& $EventCategory->getByParent( $EventCategory, true );

        $tree = array();
        $level++;
        foreach ( $EventCategoryList as $EventCategory )
        {
            array_push( $tree, array( $return_array[] = new eZGroupEventCategory( $EventCategory->id() ), $level ) );

            if ( $EventCategory != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $EventCategory->id(), $level ) );
            }
        }

        return $tree;
    }
    
    /*!
      Returns the object ID to the EventCategory. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns the name of the EventCategory.
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
           return new eZGroupEventCategory( $this->ParentID );
       }
       else
       {
           return 0;           
       }
    }

    /*!
      Sets the name of the EventCategory.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the EventCategory.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the parent EventCategory.
    */
    function setParent( $value )
    {
		if ( get_class( $value ) == "ezgroupeventcategory" )
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
    var $ExcludeFromSearch; // 0 = false, 1 = true, default is false
}

?>

