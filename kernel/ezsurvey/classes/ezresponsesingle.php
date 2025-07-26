<?php

    Class eZResponseSingle
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
                           ResponseID,
                           QuestionID,
                           ChoiceID,
                           Other
                         FROM
                           eZSurvey_ResponseSingle
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
                $this->ResponseID = $object_array[ 0 ][ "ResponseID" ];
                $this->QuestionID = $object_array[ 0 ][ "QuestionID" ];
                $this->ChoiceID = $object_array[ 0 ][ "ChoiceID" ];
                $this->Other = $object_array[ 0 ][ "Other" ];

                return $this;
            }
        }

        function getAll( $OrderBy = "ID", $ResponseID = "", $QuestionID = "", $LimitStart = "None", $LimitBy = "None" )
        {
            $db =& eZDB::globalDatabase();
    
            // order by
            switch ( strtolower( $OrderBy ) )
            {
                case "choiceid":
                    $OrderBy = "ORDER BY ChoiceID";
                    break;
                default:
                    $OrderBy = "ORDER BY ID";
                    break;
            }
    
            // limit
            if ( is_numeric( $LimitStart ) )
            {
                $LimitArray = array( "Offset" => $LimitStart );
    
                if ( is_numeric( $LimitBy ) )
                {
                    $LimitArray =& array_merge( $LimitArray, array( "Limit" => $LimitBy ) );
                }
            }
            else
            {
                $LimitArray = array();
            }
            
            // condi��es
            if ( $ResponseID != "" ) { $SqlCond .= " AND ResponseID = '$ResponseID'"; }
            if ( $QuestionID != "" ) { $SqlCond .= " AND QuestionID = '$QuestionID'"; }
            
            $SQLQuery = "SELECT
                            ID as A
                        FROM
                            eZSurvey_ResponseSingle";
                            
            if ( $SqlCond != "" )
            {
                $SQLQuery .= " WHERE " . substr( $SqlCond, 4, strlen($SqlCond) );
            }
            
            $response_array = array();
            $return_array = array();
            
            $db->array_query( $response_array, $SQLQuery );
    
            foreach ( $response_array as $responseItem )
            {
                $return_array[] = new eZResponseSingle( $responseItem[ $db->fieldName( "A" ) ] );
            }
            return $return_array;
        }
        
        function store()
        {
            $ret = false;
            $db =& eZDB::globalDatabase();
            $db->begin();

            if ( !isSet( $this->ID) )
            {
                // create
                $db->lock( "eZSurvey_ResponseSingle" );
                $this->ID = $db->nextID( "eZSurvey_ResponseSingle", "ID" );
                $res[] = $db->query( "INSERT INTO eZSurvey_ResponseSingle
                                   (ID, ResponseID, QuestionID, ChoiceID, Other)
                                   VALUES (
                                    '$this->ID',
                                    '$this->ResponseID',
                                    '$this->QuestionID',
                                    '$this->ChoiceID',
                                    '$this->Other'
                                   )");

                $db->unlock();
            }
            else
            {
                // update
                $db->lock( "eZSurvey_ResponseSingle" );
                $res[] = $db->query( "UPDATE eZSurvey_ResponseSingle set
                                      ID = '$this->ID',
                                      ResponseID = '$this->ResponseID',
                                      QuestionID = '$this->QuestionID',
                                      ChoiceID = '$this->ChoiceID',
                                      Other = '$this->Other'
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

            $res[] = $db->query( "DELETE FROM eZSurvey_ResponseSingle WHERE ID='$this->ID'" );

            eZDB::finish( $res, $db );
        }

        function deleteResponse( $ResponseID, $QuestionID )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            
            $res[] = $db->query( "DELETE FROM eZSurvey_ResponseSingle WHERE ResponseID = '$ResponseID' AND QuestionID = '$QuestionID'" );
            
            eZDB::finish( $res, $db );
        }
        
        function deleteByQuestion( $QuestionID )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            
            $res[] = $db->query( "DELETE FROM eZSurvey_ResponseSingle WHERE QuestionID = '$QuestionID'" );
            
            eZDB::finish( $res, $db );
        }
        
        // get functions
        function id() { return $this->ID; }
        function responseID() { return $this->ResponseID; }
        function questionID() { return $this->QuestionID; }
        function choiceID() { return $this->ChoiceID; }
        function other() { return $this->Other; }

        // set functions
        function setID( $value ) { $this->ID = $value; }
        function setResponseID( $value ) { $this->ResponseID = $value; }
        function setQuestionID( $value ) { $this->QuestionID = $value; }
        function setChoiceID( $value ) { $this->ChoiceID = $value; }
        function setOther( $value ) { $this->Other = $value; }

        // internal variables
        var $ID;
        var $ResponseID;
        var $QuestionID;
        var $ChoiceID;
        var $Other;
    }

?>
