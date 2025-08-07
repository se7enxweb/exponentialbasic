<?php

    // include_once( "ezmail/classes/ezmail.php" );
    // include_once( "ezbulkmail/classes/ezbulkmailtemplate.php" );
    
    class eZResponse
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
                           SurveyID,
                           Submitted,
                           Complete,
                           UserID
                         FROM
                           eZSurvey_Response
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
                $this->SurveyID = $object_array[ 0 ][ "SurveyID" ];
                $this->Submitted = $object_array[ 0 ][ "Submitted" ];
                $this->Complete = $object_array[ 0 ][ "Complete" ];
                $this->UserID = $object_array[ 0 ][ "UserID" ];

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
                $db->lock( "eZSurvey_Response" );
                $this->ID = $db->nextID( "eZSurvey_Response", "ID" );
                $res[] = $db->query( "INSERT INTO eZSurvey_Response
                                   (ID, SurveyID, Submitted, Complete, UserID)
                                   VALUES (
                                    '$this->ID',
                                    '$this->SurveyID',
                                    '" . time() . "',
                                    '$this->Complete',
                                    '$this->UserID'
                                   )");

                $db->unlock();
            }
            else
            {
                // update
                $db->lock( "eZSurvey_Response" );
                $res[] = $db->query( "UPDATE eZSurvey_Response set
                                      ID = '$this->ID',
                                      SurveyID = '$this->SurveyID',
                                      Submitted = '" . time() . "',
                                      Complete = '$this->Complete',
                                      UserID = '$this->UserID'
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

            $res[] = $db->query( "DELETE FROM eZSurvey_Response WHERE ID='$this->ID'" );
            
            eZDB::finish( $res, $db );
        }
        
        public static function numberOfResponses( $surveyId = "" )
        {
            $db =& eZDB::globalDatabase();
            
            // if ( $surveyId == "" )
            // {
            //     $surveyId = $this->SurveyID;
            // }
            
            $SqlQuery = "SELECT count(*) as A from eZSurvey_Response WHERE SurveyID = '$surveyId'";
            $db->array_query( $question_array, $SqlQuery );
            
            return $question_array[ 0 ][ "A" ];
        }

        function sendMail()
        {
            $survey = new eZSurvey( $this->SurveyID );
            $ini =& eZINI::instance( 'site.ini' );
            
            if ( trim($survey->email()) != "" )
            {
                $question_array =& $survey->surveyQuestions();
                
                foreach ( $question_array as $questionItem )
                {
                    $questionResponse = new eZResponseQuestion( $this->ID, $questionItem->id() );
                    $body .= $questionResponse->report() . "\n";
                }
                
                $TemplateID = $ini->variable( "eZSurveyMain", "ReportTemplateID" );
                $mailTemplate = new eZBulkMailTemplate($TemplateID);
                
                $subjectMail = str_replace ( "%SURVEY%", $survey->title(), $mailTemplate->name() );
                $bodyMail = str_replace ( "%REPORT%", $body, $mailTemplate->header() );
                
                $mail = new eZMail();
                $mail->setTo( $survey->email() );
                $mail->setSubject( $subjectMail );
                $mail->setFrom( "nospam@novis.pt"  );
                $mail->setBody( $body );
                
                $mail->store();
                $mail->send();
            }
        }
        
        // get functions
        function id() { return $this->ID; }
        function surveyID() { return $this->SurveyID; }
        function submitted() { return $this->Submitted; }
        function complete() { return $this->Complete; }
        function userID() { return $this->UserID; }

        // set functions
        function setID( $value ) { $this->ID = $value; }
        function setSurveyID( $value ) { $this->SurveyID = $value; }
        function setSubmitted( $value ) { $this->Submitted = $value; }
        function setComplete( $value ) { $this->Complete = $value; }
        function setUserID( $value ) { $this->UserID = $value; }

        // internal variables
        var $ID;
        var $SurveyID;
        var $Submitted;
        var $Complete;
        var $UserID;
    }

?>
