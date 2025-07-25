<?
include_once( "classes/ezdb.php" );


class eZMessageDefinition
{
    /*!
      Constructs a new eZMessageDefinition object.
    */
    function eZMessageDefinition( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZMessageDefinition object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZMessage_MessageDefinition" );
            $nextID = $db->nextID( "eZMessage_MessageDefinition", "ID" );
            $res[] = $db->query( "INSERT INTO eZMessage_MessageDefinition
                       ( ID,
                         MessageID,
                         FromUserID,
                         ToUserID )
                       VALUES
                       ( '$nextID',
                         '$this->MessageID',
                         '$this->FromUserID',
                         '$this->ToUserID' )" );
			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZMessage_MessageDefinition SET
            		 MessageID='$this->MessageID',
                         FromUserID='$this->FromUserID',
                         ToUserID='$this->ToUserID'
                         WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZMessageDefinition object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( isset( $this->ID ) )
        {
            $res[] = $db->query( "DELETE FROM eZMessage_MessageDefinition WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    
    function &getMessageID( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $message_array = array();

	$query ="SELECT * FROM eZMessage_MessageDefinition WHERE MessageID='$id'";

        $db->array_query( $message_array, $query );
        foreach ( $message_array as $message )
        {
            $return_array[] = new eZMessageDefinition( $message[0] );
        }
        return $return_array;
    }
    
    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $message_array, "SELECT * FROM eZMessage_MessageDefinition WHERE ID='$id'" );
            if( count( $message_array ) == 1 )
            {
                $this->ID =& $message_array[0][$db->fieldName( "ID" )];
                $this->MessageID =& $message_array[0][$db->fieldName( "MessageID" )];
                $this->FromUserID =& $message_array[0][$db->fieldName( "FromUserID" )];
                $this->ToUserID =& $message_array[0][$db->fieldName( "ToUserID" )];
                $ret = true;
            }
            elseif( count( $message_array ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }


    /*!
      Fetches the user id from the database. And returns a array of eZMessageDefinition objects.
    */
    function &getAll(  )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $message_array = array();


        $db->array_query( $message_array, "SELECT ID FROM eZMessage_MessageDefinition
                                        ORDER By Created" );

        foreach ( $message_array as $message )
        {
            $return_array[] = new eZMessageDefinition( $message[0] );
        }
        return $return_array;
    }

    /*!
      Fetches the messages for a user.
    */
    function &messagesToUser( $user )
    {
        $userID = $user->id();
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $message_array = array();


        $db->array_query( $message_array, "SELECT ID FROM eZMessage_MessageDefinition
                                        WHERE ToUserID='$userID'
                                        ORDER By Created DESC" );

        foreach ( $message_array as $message )
        {
            $return_array[] = new eZMessageDefinition( $message[0] );
        }
        return $return_array;
    }
    
    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Sets the MessageID.
    */
    function setMessageID( $value )
    {
        $this->MessageID = $value;
    }

    /*!
      Returns the MessageID.
    */
    function MessageID()
    {
        return $this->MessageID;
    }
    
    /*!
      Sets the use which the message is from.
    */
    function setFromUserID( $user )
    {
        if ( get_class( $user ) == "ezuser" )
        {
            $this->FromUserID = $user->id();
        }
    }

    /*!
      Returns the from user as an eZUser object.
    */
    function fromUserID()
    {
        return new eZUser( $this->FromUserID );
    }
     

    /*!
      Returns the to user as an eZUser object.
    */
    function &toUserID()
    {
        $ret = new eZUser( $this->ToUserID );
        return $ret;
    }

    /*!
      Sets the use which the message is to.
    */
    function setToUserID( $user )
    {
        if ( get_class( $user ) == "ezuser" )
        {
            $this->ToUserID = $user->id();
        }
    }

    var $ID;
    var $MessageID;
    var $FromUserID;
    var $ToUserID;
}

?>