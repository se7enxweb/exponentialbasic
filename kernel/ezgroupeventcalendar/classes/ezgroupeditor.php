<?
//
// Definition of eZGroupEditor class
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

//include_once( "classes/ezdb.php" );

class eZGroupEditor
{
    /*!
      Constructs a new eZGroupEditor object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZGroupEditor object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZGroupEventCalendar_GroupEditor SET
			                        UserID = '$this->UserID',
									GroupID = '$this->GroupID'" );
            
            $this->ID = $this->Database->insertID();
        }
        else
        {
            $this->Database->query( "UPDATE eZGroupEventCalendar_GroupEditor SET
			                        UserID = '$this->UserID',
									GroupID = '$this->GroupID'
									WHERE ID='$this->ID'" );
        }
        
        return true;
    }

	/*!
      Deletes a eZGroupEditor object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZGroupEventCalendar_GroupEditor WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $group_array, "SELECT * FROM eZGroupEventCalendar_GroupEditor WHERE ID='$id'" );

            $this->ID      =& $group_array[0][ "ID" ];
			$this->UserID  =& $group_array[0][ "UserID" ];
            $this->GroupID =& $group_array[0][ "GroupID" ];

            $this->State_ = "Coherent";

        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

	/*!
	  Returns all the entries for a specific group
	*/
	function getByGroup($groupID)
	{
		$this->dbInit();

        $return_array = array();
        $event_array = array();

		$this->Database->array_query( $group_array, "SELECT ID FROM eZGroupEventCalendar_GroupEditor WHERE GroupID='$groupID'" );

		if( count( $group_array ) > 0 )
		{
			for( $i=0; $i < count( $group_array ); $i++ )
			{
				$return_array[$i] = new eZGroupEditor( $group_array[$i]["ID"] );
			}
		}

		if( count( $return_array ) > 0 )
			return $return_array;
		else
			return false;
	}

	/*!
	  Returns all the enties in the database as an array of objects
    */
	function getAll()
	{
		$this->dbInit();

        $return_array = array();
        $event_array = array();

		$this->Database->array_query( $group_array, "SELECT * FROM eZGroupEventCalendar_GroupEditor" );

		if( count( $group_array ) >= 0 )
		{
			for( $i=0; $i < count ( $group_array ); $i++ )
			{
				$return_array[$i] = new eZGroupEditor( $group_array[$i]["ID"] );
			}
		}

		return $return_array;
	}

	function hasEditPermission( $user, $group )
	{
		$this->dbInit();

		$this->Database->array_query( $group_array, "SELECT GroupID FROM eZGroupEventCalendar_GroupEditor WHERE UserID='$user'" );

		if( count( $group_array ) > 0 && $group == 0 )
		{
			return true;
		}
		elseif( count( $group_array ) > 0 && $group != 0 )
		{
			for( $i=0; $i < count( $group_array ); $i++ )
			{
				if( $group_array[$i][ "GroupID" ] == $group )
					return true;
			}
		}
		else
			return false;
	}


	/*!
	  Returns true if the group has specified editors
	  False if not
	*/
	function groupHasEditor( $group )
	{
		$this->dbInit();

		$this->Database->array_query( $group_array, "SELECT * FROM eZGroupEventCalendar_GroupEditor WHERE GroupID='$group'" );

		$ret = false;

		if( count( $group_array ) > 0 && $group != 0 )
			$ret = true;

		return $ret;
	}

    /*!
      Returns the object ID to the event. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the user ID of the group not to show.
    */
    function userID()
    {
        return $this->UserID;
    }

    /*!
      Returns the group ID of the group not to show.
    */
    function groupID()
    {
        return $this->GroupID;
    }

    /*!
      Sets the user fields.
    */
    function setUser( $user )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $user ) == "ezuser" )
       {
           $this->UserID = $user->id();
       }
    }
	
	/*!
      Sets the Group field.
    */
    function setGroup( $group )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $group ) == "ezusergroup" )
       {
           $this->GroupID = $group->id();
       }
    }

	function listGroups()
	{
		$this->dbInit();
		$return_array = array();

		$this->Database->array_query( $group_array, "SELECT ID FROM eZGroupEventCalendar_GroupEditor Group By GroupID" );
		
		if( count( $group_array ) >= 0 )
		{
			for( $i=0; $i < count ( $group_array ); $i++ )
			{
				$return_array[$i] = new eZGroupEditor( $group_array[$i]["ID"] );
			}
			return $return_array;
		}
		else
			return false;
	}


	/*! 
	  Removes any groups that have been deleted from the system
	*/
	function removeDeletedGroup( $groupID='-1' )
	{
		$this->dbInit();

		$this->Database->array_query( $group_array, "SELECT ID FROM eZGroupEventCalendar_GroupEditor WHERE GroupID='$groupID'" );

		if( count( $group_array ) >= 0 )
		{
			for( $i=0; $i < count ( $group_array ); $i++ )
			{
				$deleteGroup = new eZGroupEditor( $group_array[$i]["ID"] );
				$deleteGroup->delete( );
			}
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

    var $IsConnected;
    var $ID;
    var $UserID;
    var $GroupID;
    var $State_;
    var $Database;
}

?>