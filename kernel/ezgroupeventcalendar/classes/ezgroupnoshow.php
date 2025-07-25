<?
//
// Definition of eZGroupNoShow class
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
//include_once( "ezuser/classes/ezusergroup.php" );

class eZGroupNoShow
{
    /*!
      Constructs a new eZGroupNoShow object.

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
      Stores a eZGroupNoShow object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZGroupEventCalendar_GroupNoShow SET
									GroupID = '$this->GroupID'" );
            
            $this->ID = $this->Database->insertID();
        }
        else
        {
            $this->Database->query( "UPDATE eZGroupEventCalendar_GroupNoShow SET
									GroupID = '$this->GroupID'
									WHERE ID='$this->ID'" );
        }
        
        return true;
    }

	/*!
      Deletes a eZGroupNoShow object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZGroupEventCalendar_GroupNoShow WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $group_array, "SELECT * FROM eZGroupEventCalendar_GroupNoShow WHERE ID='$id'" );

            $this->ID =& $group_array[0][ "ID" ];
            $this->GroupID =& $group_array[0][ "GroupID" ];

            $this->State_ = "Coherent";

        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

	function getAll()
	{
		$this->dbInit();

        $return_array = array();
        $group_array = array();

		$this->Database->array_query( $group_array, "SELECT * FROM eZGroupEventCalendar_GroupNoShow" );

		if( count( $group_array ) >= 0 )
		{
			for( $i=0; $i < count ( $group_array ); $i++ )
			{
				$return_array[$i] = new eZGroupNoShow( $group_array[$i]["ID"] );
			}
		}

		return $return_array;
	}

	/*!
	  Returns true if an entry exists in the database false if not
	*/
	function groupEntry( $groupID )
	{
		$this->dbInit();

		$event_array = array();

		$this->Database->array_query( $group_array, "SELECT ID FROM eZGroupEventCalendar_GroupNoShow WHERE GroupID='$groupID'" );

		if( count( $group_array ) > 0 )
			return true;
		else
			return false;
	}

	/*!
	  Clears the entire table
	*/
	function dumpTable()
	{
		$groups = $this->getAll();
		foreach( $groups as $group )
		{
			$delete_group = new eZGroupNoShow( $group->id() );
			$delete_group->delete();
		}
	}

    /*!
      Returns the object ID to the event. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the group ID of the group not to show.
    */
    function groupID()
    {
        return $this->GroupID;
    }

    /*!
      Sets the Group not to show.
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

    var $Database;
    var $IsConnected;
    var $ID;
    var $GroupID;
    var $State_;
}

?>