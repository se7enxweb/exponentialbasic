<?php

    // include_once( "classes/ezdb.php" );
    // include_once( "ezsurvey/classes/ezquestion.php" );

    class eZSurvey
    {
        // constructor
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
                           UserID,
                           Public,
                           Status,
                           Title,
                           SubTitle,
                           EMail,
                           Info,
                           ThanksPage,
                           ThankHead,
                           ThankBody,
                           Changed,
                           SectionID
                        FROM
                           eZSurvey_Survey
                        WHERE
                           ID = '$id'";
                           
            $db =& eZDB::globalDatabase();
            
            $db->array_query( $survey_array, $SqlQuery );
            if ( count( $survey_array ) > 1 )
            {
                die( "Error: More than one survey with the same ID found." );
            }
            elseif ( count( $survey_array ) == 1 )
            {
                $this->ID = $survey_array[ 0 ][ "ID" ];
                $this->UserID = $survey_array[ 0 ][ "UserID" ];
                $this->Public = $survey_array[ 0 ][ "Public" ];
                $this->Status = $survey_array[ 0 ][ "Status" ];
                $this->Title = $survey_array[ 0 ][ "Title" ];
                $this->SubTitle = $survey_array[ 0 ][ "SubTitle" ];
                $this->EMail = $survey_array[ 0 ][ "EMail" ];
                $this->Info = $survey_array[ 0 ][ "Info" ];
                $this->ThanksPage = $survey_array[ 0 ][ "ThanksPage" ];
                $this->ThankHead = $survey_array[ 0 ][ "ThankHead" ];
                $this->ThankBody = $survey_array[ 0 ][ "ThankBody" ];
                $this->Changed = $survey_array[ 0 ][ "Changed" ];
                $this->SectionID = $survey_array[ 0 ][ "SectionID" ];
                
                return $this;
            }
        
        }
        
        public static function getAll( $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
        {
            $db =& eZDB::globalDatabase();
    
            switch ( strtolower( $OrderBy ) )
            {
                default:
                    $OrderBy = "ORDER BY ID";
                    break;
            }
    
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
    
            $survey_array = array();
            $return_array = array();
    
            $SQLQuery = "SELECT ID as A FROM eZSurvey_Survey";
    
            $SqlCond = "";
            
            // TODO: acrescentar condi��es
            
            if ($SqlCond != "")
            {
                $SQLQuery .= " WHERE $SqlCond";
            }
    
            $SQLQuery .= " $OrderBy";
    
            $db->array_query( $survey_array, $SQLQuery, $LimitArray );
            
            foreach ( $survey_array as $surveyItem )
            {
                $return_array[] = new eZSurvey( $surveyItem[ "A" ] );
            }
            return $return_array;
        }
        
        function store()
        {
            $ret = false;
            $db =& eZDB::globalDatabase();
            $db->begin();
            
            if ( !isSet( $this->ID ) )
            {
                // create survey
                $db->lock( "eZSurvey_Survey" );
                $this->ID = $db->nextID( "eZSurvey_Survey", "ID" );
                $res[] = $db->query( "INSERT INTO eZSurvey_Survey
                                  (ID, UserID, Public, Status, Title, SubTitle, EMail, Info, ThanksPage, ThankHead, ThankBody, Changed, SectionID )
                                  VALUES
                                  ('$this->ID',
                                   '$this->UserID',
                                   '$this->Public',
                                   'EDIT',
                                   '$this->Title',
                                   '$this->SubTitle',
                                   '$this->EMail',
                                   '$this->Info',
                                   '$this->ThanksPage',
                                   '$this->ThankHead',
                                   '$this->ThankBody',
                                   '" . time() . "',
                                   '$this->SectionID'
                                   )");
                
                $db->unlock();
            }
            else
            {
                // Update survey
                $db->lock( "eZSurvey_Survey" );
                $query = "UPDATE eZSurvey_Survey set UserID='$this->UserID',
                                  Public='$this->Public',
                                  Status='$this->Status',
                                  Title='$this->Title',
                                  SubTitle='$this->SubTitle',
                                  EMail='$this->EMail',
                                  Info='$this->Info',
                                  ThanksPage='$this->ThanksPage',
                                  ThankHead='$this->ThankHead',
                                  ThankBody='$this->ThankBody',
                                  Changed='" . time() . "',
                                  SectionID='$this->SectionID'
                              WHERE ID='$this->ID'";

                $res[] = $db->query( $query );
                 
                 
                 $db->unlock();
            }
            $ret = $res;
            eZDB::finish( $res, $db );
            return true;
        }

        function delete()
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            
            $question_array = $this->surveyQuestions();
            foreach ( $question_array as $questionItem )
            {
                $questionItem->delete();
            }
            
            $res[] = $db->query( "DELETE FROM eZSurvey_Survey WHERE ID='$this->ID'" );
            
            eZDB::finish( $res, $db );
        }
        
        function copySurvey( $newTitle )
        {
            $newSurvey = new eZSurvey();
            
            $newSurvey->setPublic( $this->Public );
            $newSurvey->setStatus( "EDIT" );
            $newSurvey->setTitle( $newTitle );
            $newSurvey->setSubTitle( $this->SubTitle );
            $newSurvey->setEMail( $this->EMail );
            $newSurvey->setInfo( $this->Info );
            $newSurvey->setThanksPage( $this->ThanksPage );
            $newSurvey->setThankHead( $this->ThankHead );
            $newSurvey->setThankBody( $this->ThankBody );
            
            $newSurvey->store();
            
            foreach( $this->surveyQuestions() as $questionItem )
            {
                $questionItem->copyQuestion( $newSurvey->id() );
            }
            
            return $newSurvey;
        }
        
        function surveyQuestions( $OrderBy = "Position", $Page = 0 )
        {
            $page_array = array();
            $SqlCond = "";

            $SqlQuery = "SELECT Position
                        FROM
                           eZSurvey_Question
                        WHERE
                           SurveyID = '$this->ID'
                           and QuestionTypeID = 99
                        ORDER BY
                           Position";
                           
            $db =& eZDB::globalDatabase();
            
            $db->array_query( $page_array, $SqlQuery );
            
            $start = "";
            $end = "";
            
            if ( $Page > 1 )
            {
                $start = $page_array[$Page-2][ "Position" ];
            }
            if ( isset( $page_array[$Page-1] ) && $Page <= count($page_array) )
            {
                $end = $page_array[$Page-1][ "Position" ];
            }
            
            if ($start != "")
            {
                $SqlCond .= " AND Position > '$start'";
            }
            if ($end != "")
            {
                $SqlCond .= " AND Position < '$end'";
            }
            
            $SqlQuery = "SELECT ID
                        FROM
                           eZSurvey_Question
                        WHERE
                           SurveyID = '$this->ID'";
                           
            $SqlQuery .= "$SqlCond ORDER BY $OrderBy";
            
            $db->array_query( $question_array, $SqlQuery );
            
            $return_array = array();
            
            foreach( $question_array as $questionItem )
            {
                $return_array[] = new eZQuestion( $questionItem[ "ID" ] );
            }
            
            return $return_array;
        }
        
        public static function numberOfSurveys()
        {
            $db =& eZDB::globalDatabase();
            
            $db->array_query( $count, "SELECT count(*) as A FROM eZSurvey_Survey" );
            
            return $count[ 0 ][ "A" ];
        }
        
        function numberOfQuestions()
        {
            $db =& eZDB::globalDatabase();
            
            $db->array_query( $count, "SELECT count(*) as A FROM eZSurvey_Question WHERE SurveyID = " . $this->ID );
            
            return $count[ 0 ][ "A" ];
        }
        
        function numberOfPages()
        {
            $db =& eZDB::globalDatabase();
            
            $db->array_query( $count, "SELECT count(*) as A FROM eZSurvey_Question WHERE SurveyID = '$this->ID' AND QuestionTypeID = 99" );
            
            return ($count[ 0 ][ "A" ]+1);
        }
        
        public function statusOptions()
        {
            switch( $this->Status )
            {
                case "EDIT":
                {
                    return Array( "EDIT", "ACTIVE" );
                }
                break;
                
                case "ACTIVE":
                {
                    return Array( "ACTIVE", "EDIT", "DONE" );
                }
                break;
                
                case "DONE":
                {
                    return Array( "DONE" );
                }
                break;
                
                default:
                {
                    return Array( "EDIT" );
                }
            }
        }
        
        function hasCompleted( $userID )
        {
            $db =& eZDB::globalDatabase();
            
            $db->array_query( $count, "SELECT Complete FROM eZSurvey_Response WHERE SurveyID = '$this->ID' AND UserID = '$userID' AND Complete = 'Y'" );
            
            $ret = false;
            
            if ( count($count) > 0 )
            {
                $ret = true;
            }
            
            return $ret;
        }
        
        public static function existSurvey( $id )
        {
            $db =& eZDB::globalDatabase();
            $SQLQuery ="SELECT ID FROM eZSurvey_Survey where ID = '$id'";
            $db->array_query( $survey_array, $SQLQuery );
            
            if (count ($survey_array))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        
        // get functions
        function id() { return $this->ID; }
        function userId() { return $this->UserID; }
        function public() { return $this->Public; }
        function status() { return $this->Status; }
        function title() { return $this->Title; }
        function subTitle() { return $this->SubTitle; }
        function email() { return $this->EMail; }
        function info() { return $this->Info; }
        function thanksPage() { return $this->ThanksPage; }
        function thankHead() { return $this->ThankHead; }
        function thankBody() { return $this->ThankBody; }
        function changed() { return $this->Changed; }
        function sectionID() { return $this->SectionID; }
        
        // set functions
        function setUserId( $value ) { $this->UserID = $value; }
        function setPublic( $value ) { $this->Public = $value; }
        function setStatus( $value ) { $this->Status = $value; }
        function setTitle( $value ) { $this->Title = $value; }
        function setSubTitle( $value ) { $this->SubTitle = $value; }
        function setEMail( $value ) { $this->EMail = $value; }
        function setInfo( $value ) { $this->Info = $value; }
        function setThanksPage( $value ) { $this->ThanksPage = $value; }
        function setThankHead( $value ) { $this->ThankHead = $value; }
        function setThankBody( $value ) { $this->ThankBody =$value; }
        function setChanged( $value ) { $this->Changed =$value; }
        function setSectionID( $value ) { $this->SectionID =$value; }
        
        var $ID;
        var $UserID;
        var string $Public;
        var $Status;
        var $Title;
        var $SubTitle;
        var $EMail;
        var $Info;
        var $ThanksPage;
        var $ThankHead;
        var $ThankBody;
        var $Changed;
        var $SectionID;
    }
    
?>
