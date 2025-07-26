<?php

    Class eZQuestionChoice
    {
        function __construct( $id = "-1" )
        {
            if ( $id != -1 )
            {
                $this->ID = $id;
                $this->get( $this->ID );
            }
        }

        function get( $id = "-1" )
        {
            $SqlQuery = "SELECT
                           ID,
                           QuestionID,
                           Content,
                           Value
                         FROM
                           eZSurvey_QuestionChoice
                         WHERE
                           ID = '$id'";

            $db =& eZDB::globalDatabase();

            $db->array_query( $object_array, $SqlQuery );
            if ( count( $object_array ) > 1 )
            {
                die( "Error: More than one object with the same ID found." );
            }
            elseif ( count( $object_array ) == 1 )
            {
                $this->ID = $object_array[ 0 ][ "ID" ];
                $this->QuestionID = $object_array[ 0 ][ "QuestionID" ];
                $this->Content = $object_array[ 0 ][ "Content" ];
                $this->Value = $object_array[ 0 ][ "Value" ];

                return $this;
            }
        }

        function store()
        {
            $ret = false;
            $db =& eZDB::globalDatabase();
            $db->begin();

            if ( !isSet( $this->ID) )
            {
                // create
                $db->lock( "eZSurvey_QuestionChoice" );
                $this->ID = $db->nextID( "eZSurvey_QuestionChoice", "ID" );
                $res[] = $db->query( "INSERT INTO eZSurvey_QuestionChoice
                                   (ID, QuestionID, Content, Value)
                                   VALUES (
                                    '$this->ID',
                                    '$this->QuestionID',
                                    '$this->Content',
                                    '$this->Value'
                                   )");

                $db->unlock();
            }
            else
            {
                // update
                $db->lock( "eZSurvey_QuestionChoice" );
                $res[] = $db->query( "UPDATE eZSurvey_QuestionChoice set
                                      ID = '$this->ID',
                                      QuestionID = '$this->QuestionID',
                                      Content = '$this->Content',
                                      Value = '$this->Value'
                                      WHERE ID = '$this->ID'");

                $db->unlock();
            }
            eZDB::finish( $res, $db );
            return true;
        }

        function delete()
        {
            $db =& eZDB::globalDatabase();
            $db->begin();

            $res[] = $db->query( "DELETE FROM eZSurvey_QuestionChoice WHERE ID='$this->ID'" );

            eZDB::finish( $res, $db );
        }

        function isOther()
        {
            if ( strtolower($this->Content) == "!other" )
                return true;
            else
                return false;
        }
        
        // get functions
        function id() { return $this->ID; }
        function questionID() { return $this->QuestionID; }
        function content() { return $this->Content; }
        function value() { return $this->Value; }

        // set functions
        function setID( $value ) { $this->ID = $value; }
        function setQuestionID( $value ) { $this->QuestionID = $value; }
        function setContent( $value ) { $this->Content = $value; }
        function setValue( $value ) { $this->Value = $value; }

        // internal variables
        var $ID;
        var $QuestionID;
        var $Content;
        var $Value;
    }
    
?>
