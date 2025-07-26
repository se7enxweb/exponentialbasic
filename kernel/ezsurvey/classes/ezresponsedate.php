<?php

    class eZResponseDate
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
                           Response
                         FROM
                           eZSurvey_ResponseDate
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
                $this->Response = $object_array[ 0 ][ "Response" ];

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
                            eZSurvey_ResponseDate";
                            
            if ( $SqlCond != "" )
            {
                $SQLQuery .= " WHERE " . substr( $SqlCond, 4, strlen($SqlCond) );
            }
            
            $response_array = array();
            $return_array = array();
            
            $db->array_query( $response_array, $SQLQuery );
            
            foreach ( $response_array as $responseItem )
            {
                $return_array[] = new eZResponseDate( $responseItem[ $db->fieldName( "A" ) ] );
            }
            return $return_array;
        }
       
        function makeTimestamp( $date )
        {
            if ( $date != "" )
            {
                $data_array = explode( "-", $date );
                if ( count($data_array) == 3 )
                {
                    $timestamp = mktime( 0, 0, 0, $data_array[1], $data_array[0], $data_array[2] );
                }
            }
            else
            {
                $timestamp = "null";
            }
            
            return $timestamp;
        }
        
        function makeDate( $timestamp )
        {
            if ( $timestamp != "" )
            {
                $date = date( "d-m-Y", $timestamp );
            }
            
            return $date;
        }
        
        function store()
        {
            $ret = false;
            $db =& eZDB::globalDatabase();
            $db->begin();

            if ( !isSet( $this->ID) )
            {
                // create
                $db->lock( "eZSurvey_ResponseDate" );
                $this->ID = $db->nextID( "eZSurvey_ResponseDate", "ID" );
                $res[] = $db->query( "INSERT INTO eZSurvey_ResponseDate
                                   (ID, ResponseID, QuestionID, Response)
                                   VALUES (
                                    '$this->ID',
                                    '$this->ResponseID',
                                    '$this->QuestionID',
                                    '" . $this->Response . "'
                                   )");

                $db->unlock();
            }
            else
            {
                // update
                $db->lock( "eZSurvey_ResponseDate" );
                $res[] = $db->query( "UPDATE eZSurvey_ResponseDate set
                                      ID = '$this->ID',
                                      ResponseID = '$this->ResponseID',
                                      QuestionID = '$this->QuestionID',
                                      Response = '" . $this->Response . "'
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

            $res[] = $db->query( "DELETE FROM eZSurvey_ResponseDate WHERE ID='$this->ID'" );

            eZDB::finish( $res, $db );
        }

        function deleteResponse( $ResponseID, $QuestionID )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            
            $res[] = $db->query( "DELETE FROM eZSurvey_ResponseDate WHERE ResponseID = '$ResponseID' AND QuestionID = '$QuestionID'" );
            
            eZDB::finish( $res, $db );
        }
        
        function deleteByQuestion( $QuestionID )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            
            $res[] = $db->query( "DELETE FROM eZSurvey_ResponseDate WHERE QuestionID = '$QuestionID'" );
            
            eZDB::finish( $res, $db );
        }
        
        // get functions
        function id() { return $this->ID; }
        function responseID() { return $this->ResponseID; }
        function questionID() { return $this->QuestionID; }
        function response() { return $this->Response; }

        // set functions
        function setID( $value ) { $this->ID = $value; }
        function setResponseID( $value ) { $this->ResponseID = $value; }
        function setQuestionID( $value ) { $this->QuestionID = $value; }
        function setResponse( $value ) { $this->Response = $value; }

        // internal variables
        var $ID;
        var $ResponseID;
        var $QuestionID;
        var $Response;
    }

?>
