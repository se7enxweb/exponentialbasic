<?php

//     include_once( "classes/ezdb.php" );
// //    include_once( "classes/eznovissequence.php" );
//     include_once( "ezsurvey/classes/ezquestionchoice.php" );
//     include_once( "ezsurvey/classes/ezresponsequestion.php" );
//     include_once( "ezsurvey/classes/ezresponse.php" );
    
    class eZQuestion
    {
        // constructor
        function __construct( $id = "-1" )
        {
            $this->SurveyID = 0;
            $this->QuestionTypeID = 0;
            $this->ResultID = 0;
            $this->Length = 0;
            $this->Position = 0;
            $this->Content = 0;
            $this->Initial = '';
            $this->Required = 'N';
            $this->Public = 'Y';

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
                           QuestionTypeID,
                           ResultID,
                           Length,
                           Position,
                           Content,
                           Initial,
                           Required,
                           Public
                        FROM
                           eZSurvey_Question
                        WHERE
                           ID = '$id'";

            $db =& eZDB::globalDatabase();

            $db->array_query( $question_array, $SqlQuery );
            if ( count( $question_array ) > 1 )
            {
                die( "Error: More than one question with the same ID found." );
            }
            elseif ( count( $question_array ) == 1 )
            {
                $this->ID = $question_array[ 0 ][ "ID" ];
                $this->SurveyID = $question_array[ 0 ][ "SurveyID" ];
                $this->QuestionTypeID = $question_array[ 0 ][ "QuestionTypeID" ];
                $this->ResultID = $question_array[ 0 ][ "ResultID" ];
                $this->Length = $question_array[ 0 ][ "Length" ];
                $this->Position = $question_array[ 0 ][ "Position" ];
                $this->Content = $question_array[ 0 ][ "Content" ];
                $this->Initial = $question_array[ 0 ][ "Initial" ];
                $this->Required  = $question_array[ 0 ][ "Required" ];
                $this->Public  = $question_array[ 0 ][ "Public" ];

                // question type
                $db->array_query( $questionType_array, "SELECT ResponseTable, HasChoices FROM eZSurvey_QuestionType WHERE ID = '$this->QuestionTypeID'" );

                if ( count($questionType_array) > 0 )
                {
                    $this->HasChoices = $questionType_array[ 0 ][ "HasChoices" ];
                    $this->ResponseTable = $questionType_array[ 0 ][ "ResponseTable" ];
                }
                else
                {
                    $this->HasChoices = 'N';
                }

                return $this;
            }

        }

        public static function getAll( $OrderBy = "ID", $SurveyID = "", $LimitStart = "None", $LimitBy = "None" )
        {
            $db =& eZDB::globalDatabase();

            switch ( strtolower( $OrderBy ) )
            {
                case "name":
                    $OrderBy = "ORDER BY Name";
                    break;

                case "position desc":
                    $OrderBy = "ORDER BY Position DESC";
                    break;

                case "position":
                    $OrderBy = "ORDER BY Position";
                    break;

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

            $question_array = array();
            $return_array = array();

            $SQLQuery = "SELECT ID as A FROM eZSurvey_Question";

            $SqlCond = "";

            if ($SurveyID != "")
            {
                $SqlCond .= " SurveyID = '$SurveyID'";
            }

            if ($SqlCond != "")
            {
                $SQLQuery .= " WHERE $SqlCond";
            }

            $SQLQuery .= " $OrderBy";

            $db->array_query( $question_array, $SQLQuery, $LimitArray );

            foreach ( $question_array as $questionItem )
            {
                $return_array[] = new eZQuestion( $questionItem[ "A" ] );
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
                // create question
                $db->lock( "eZSurvey_Question" );
                $this->ID = $db->nextID( "eZSurvey_Question", "ID" );
                $res[] = $db->query( "INSERT INTO eZSurvey_Question
                                  (ID, SurveyID, QuestionTypeID, ResultID, Length, Position, Content, Initial, Required, Public )
                                  VALUES
                                  ('$this->ID',
                                   '$this->SurveyID',
                                   '$this->QuestionTypeID',
                                   '$this->ResultID',
                                   '$this->Length',
                                   '$this->Position',
                                   '$this->Content',
                                   '$this->Initial',
                                   '$this->Required',
                                   '$this->Public'
                                   )");

                $db->unlock();
            }
            else
            {
                // update question
                $db->lock( "eZSurvey_Question" );
                $res[] = $db->query( "UPDATE eZSurvey_Question 
                                  SET SurveyID='$this->SurveyID',
                                  QuestionTypeID='$this->QuestionTypeID',
                                  ResultID='$this->ResultID',
                                  Length='$this->Length',
                                  Position='$this->Position',
                                  Content='$this->Content',
                                  Initial='$this->Initial',
                                  Required='$this->Required',
                                  Public='$this->Public'
                              WHERE ID='$this->ID'" );


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

            eZResponseQuestion::deleteByQuestion( $this->ID );
            
            $questionChoice_array = $this->questionChoices();
            foreach ( $questionChoice_array as $questionChoiceItem )
            {
                $questionChoiceItem->delete();
            }

            $res[] = $db->query( "DELETE FROM eZSurvey_Question WHERE ID='$this->ID'" );

            eZDB::finish( $res, $db );
        }

        function copyQuestion( $surveyID )
        {
            $newQuestion = new eZQuestion();
            
            $newQuestion->setSurveyID( $surveyID );
            $newQuestion->setQuestionTypeID( $this->QuestionTypeID );
            $newQuestion->setResultID( $this->ResultID );
            $newQuestion->setLength( $this->Length );
            $newQuestion->setContent( $this->Content );
            $newQuestion->setInitial( $this->Initial );
            $newQuestion->setRequired( $this->Required );
            $newQuestion->setPublic( $this->Public );
            $newQuestion->setPosition( $this->Position );
            
            $newQuestion->store();
            
            return $newQuestion;
        }
        
        public static function questionTypes()
        {
            $SqlQuery = "SELECT ID, Type FROM eZSurvey_QuestionType";

            $db =& eZDB::globalDatabase();

            $db->array_query( $type_array, $SqlQuery );

            return $type_array;
        }

        function questionChoices( $OrderBy = "ID" )
        {
            $SqlQuery = "SELECT ID
                        FROM
                           eZSurvey_QuestionChoice
                        WHERE
                           QuestionID = '$this->ID'
                        ORDER BY
                           $OrderBy";

            $db =& eZDB::globalDatabase();

            $db->array_query( $questionChoice_array, $SqlQuery );

            $return_array = array();

            foreach( $questionChoice_array as $questionItem )
            {
                $return_array[] = new eZQuestionChoice( $questionItem[ "ID" ] );
            }

            return $return_array;
        }
        
        function numberOfQuestionsChoices()
        {
            $db =& eZDB::globalDatabase();

            $db->array_query( $count, "SELECT count(*) as A FROM eZSurvey_QuestionChoice WHERE QuestionID = " . $this->ID );

            return $count[ 0 ][ "A" ];
        }
        
        function questionNumber()
        {
            $db =& eZDB::globalDatabase();

            $SqlQuery = "SELECT count(*) as A FROM eZSurvey_Question
                        WHERE SurveyID = '" . $this->SurveyID . "'
                        AND Position <= '" . $this->Position . "'
                        AND QuestionTypeID != '" . $this->TYPE_PAGEBREAK . "'
                        AND QuestionTypeID != '" . $this->TYPE_SECTIONTEXT . "'";
             
            $db->array_query( $count,  $SqlQuery );

            return $count[ 0 ][ "A" ];
        }

        function questionUp()
        {
            $SqlQuery = "SELECT
                           ID
                         FROM
                           eZSurvey_Question
                         WHERE
                           SurveyID = '$this->SurveyID'
                           and Position < '$this->Position'
                         ORDER BY
                           Position DESC";

            $db =& eZDB::globalDatabase();

            $db->array_query( $question_array, $SqlQuery, Array( "Offset" => 0, "Limit" => 1 ) );

            if ( count($question_array) > 0 )
            {
                $ret = new eZQuestion( $question_array[0][ "ID" ] );
            }
            else
            {
                // retorna a �ltima pergunta se n�o existir nenhuma acima desta
                $question_array = $this->getAll( "Position DESC", $this->SurveyID, "0", "1" );
                $ret = new eZQuestion( $question_array[0]->id() );
            }

            return $ret;
        }

        function moveUp()
        {
            $question = $this->questionUp();

            //echo "up: " . $question->content();

            $temp = $this->Position;
            $this->Position = $question->position();
            $question->setPosition( $temp );

            $question->store();
            $this->store();
        }

        function questionDown()
        {
            $SqlQuery = "SELECT
                           ID
                         FROM
                           eZSurvey_Question
                         WHERE
                           SurveyID = '$this->SurveyID'
                           and Position > '$this->Position'
                         ORDER BY
                           Position";

            $db =& eZDB::globalDatabase();

            $db->array_query( $question_array, $SqlQuery, Array( "Offset" => 0, "Limit" => 1 ) );

            if ( count($question_array) > 0 )
            {
                $ret = new eZQuestion( $question_array[0][ "ID" ] );
            }
            else
            {
                // retorna a primeira pergunta se n�o existir nenhuma abaixo desta
                $question_array = $this->getAll( "Position", $this->SurveyID, "0", "1" );
                $ret = new eZQuestion( $question_array[0]->id() );
            }

            return $ret;
        }

        function moveDown()
        {
            $question = $this->questionDown();

            //echo "up: " . $question->content();

            $temp = $this->Position;
            $this->Position = $question->position();
            $question->setPosition( $temp );

            $question->store();
            $this->store();
        }

        function isRequired()
        {
            if ( $this->Required == 'Y' )
                return true;
            else
                return false;
        }

        function isQuestion()
        {
            switch ( $this->QuestionTypeID )
            {
                case $this->TYPE_SECTIONTEXT:
                case $this->TYPE_PAGEBREAK:
                {
                    $res = false;
                }
                break;
                
                default:
                {
                    $res = true;
                }
            }
            
            return $res;
        }
        
        function hasChoices()
        {
            if ( $this->HasChoices == 'Y' )
                return true;
            else
                return false;
        }

        function hasDefault()
        {
            if ( $this->QuestionTypeID == 0 // no type
                 || $this->QuestionTypeID == $this->TYPE_RATE
                 || $this->QuestionTypeID == $this->TYPE_DATE
                 || $this->QuestionTypeID == $this->TYPE_PAGEBREAK
                 || $this->QuestionTypeID == $this->TYPE_SECTIONTEXT )
                return false;
            else
                return true;
        }
        
        function hasSize()
        {
            switch ( $this->QuestionTypeID )
            {
                case $this->TYPE_TEXTBOX:
                case $this->TYPE_TEXTAREA:
                case $this->TYPE_RADIO:
                case $this->TYPE_CHECKBOX:
                case $this->TYPE_DATE:
                case $this->TYPE_NUMERIC:
                {
                    $res = true;
                }
                break;
                
                default:
                {
                    $res = false;
                }
            }
            
            return $res;
        }
        
        function hasName()
        {
            switch ( $this->QuestionTypeID )
            {
                case $this->TYPE_PAGEBREAK:
                {
                    $res = false;
                }
                break;
                
                default:
                {
                    $res = true;
                }
            }
            
            return $res;
        }
        
        function hasRequired()
        {
            switch ( $this->QuestionTypeID )
            {
                case $this->TYPE_PAGEBREAK:
                case $this->TYPE_SECTIONTEXT:
                {
                    $res = false;
                }
                break;
                
                default:
                {
                    $res = true;
                }
            }
            
            return $res;
        }

        
        function hasStats()
        {
            switch ( $this->QuestionTypeID )
            {
                case $this->TYPE_TEXTBOX:
                case $this->TYPE_TEXTAREA:
                case $this->TYPE_DATE:
                case $this->TYPE_PAGEBREAK:
                case $this->TYPE_SECTIONTEXT:
                {
                    $res = false;
                }
                break;
                
                default:
                {
                    $res = true;
                }
            }
            
            return $res;
        }

        function stats()
        {
            $db =& eZDB::globalDatabase();
            
            $questionChoices = $this->questionChoices();
            $numberResponses = eZResponse::numberOfResponses( $this->SurveyID );
            
            switch ( $this->QuestionTypeID )
            {
                case $this->TYPE_YESNO:
                {
                    // Yes
                    $SqlQuery = "SELECT ChoiceID, count(*) as A from eZSurvey_ResponseBool WHERE QuestionID = '$this->ID' AND ChoiceID = 'Y' GROUP BY ChoiceID";
                    $db->array_query( $question_array, $SqlQuery );
                    
                    $return_array[0][] = $question_array[ 0 ][ "ChoiceID" ];
                    $return_array[1][] = "Yes";
                    $return_array[2][] = $question_array[ 0 ][ "A" ];
                    if ( $numberResponses > 0 )
                        $return_array[3][] = ($question_array[ 0 ][ "A" ] / $numberResponses) * 100;
                    else
                        $return_array[3][] = 0;
                    
                    // No
                    $SqlQuery = "SELECT ChoiceID, count(*) as A from eZSurvey_ResponseBool WHERE QuestionID = '$this->ID' AND ChoiceID = 'N' GROUP BY ChoiceID";
                    $db->array_query( $question_array, $SqlQuery );
                    
                    $return_array[0][] = $question_array[ 0 ][ "ChoiceID" ];
                    $return_array[1][] = "No";
                    $return_array[2][] = $question_array[ 0 ][ "A" ];
                    if ( $numberResponses > 0 )
                        $return_array[3][] = ($question_array[ 0 ][ "A" ] / $numberResponses) * 100;
                    else
                        $return_array[3][] = 0;
                }
                break;
                
                case $this->TYPE_RADIO:
                case $this->TYPE_DROPDOWN:
                {
                    foreach ( $questionChoices as $choiceItem )
                    {
                        $db->array_query( $question_array, "SELECT count(*) as A from eZSurvey_ResponseSingle WHERE QuestionID = '$this->ID' and ChoiceID = '" . $choiceItem->id() . "'" );
                        
                        $return_array[0][] = $choiceItem->id();
                        
                        if ( $choiceItem->isOther() )
                            $return_array[1][] = "Other";
                        else
                            $return_array[1][] = $choiceItem->content();
                            
                        $return_array[2][] = $question_array[0][ "A" ];
                        if ( $numberResponses > 0 )
                            $return_array[3][] = ($question_array[ 0 ][ "A" ] / $numberResponses) * 100;
                        else
                            $return_array[3][] = 0;
                    }
                }
                break;
                
                case $this->TYPE_CHECKBOX:
                {
                    foreach ( $questionChoices as $choiceItem )
                    {
                        $db->array_query( $question_array, "SELECT count(*) as A from eZSurvey_ResponseMultiple WHERE QuestionID = '$this->ID' and ChoiceID = '" . $choiceItem->id() . "'" );
                        
                        $return_array[0][] = $choiceItem->id();
                        
                        if ( $choiceItem->isOther() )
                            $return_array[1][] = "Other";
                        else
                            $return_array[1][] = $choiceItem->content();
                            
                        $return_array[2][] = $question_array[0][ "A" ];
                        if ( $numberResponses > 0 )
                            $return_array[3][] = ($question_array[ 0 ][ "A" ] / $numberResponses) * 100;
                        else
                            $return_array[3][] = 0;
                    }
                }
                break;
                
                case $this->TYPE_RATE:
                {
                    foreach ( $questionChoices as $choiceItem )
                    {
                        $db->array_query( $question_array, "SELECT ChoiceID, AVG(Rank)+1 as A from eZSurvey_ResponseRank WHERE QuestionID = '$this->ID' AND ChoiceID = '" . $choiceItem->id() . "' GROUP BY ChoiceID" );
                        
                        $return_array[0][] = $choiceItem->id();
                        $return_array[1][] = $choiceItem->content();
                        $return_array[2][] = $question_array[0][ "A" ];
                        $return_array[3][] = ($question_array[ 0 ][ "A" ] / 5) * 100;
                    }
                }
                break;
                
                case $this->TYPE_NUMERIC:
                {
                    $SqlQuery = "SELECT ChoiceID, AVG(Response) as A from eZSurvey_ResponseText WHERE QuestionID = '$this->ID' GROUP BY ChoiceID ORDER BY ChoiceID";
                    $db->array_query( $question_array, $SqlQuery );
                    
                    $return_array[0][] = $question_array[0][ "ChoiceID" ];
                    $return_array[1][] = "";
                    $return_array[2][] = $question_array[0][ "A" ];
                }
                break;
            }
            
            return $return_array;
        }

        // get functions
        function id() { return $this->ID; }
        function surveyId() { return $this->SurveyID; }
        function questionTypeID() { return $this->QuestionTypeID; }
        function resultID() { return $this->ResultID; }
        function length() { return $this->Length; }
        function position() { return $this->Position; }
        function content() { return $this->Content; }
        function initial() { return $this->Initial; }
        function required() { return $this->Required; }
        function public() { return $this->Public; }

        function responseTable() { return $this->ResponseTable; }
        
        // set functions
        function setSurveyID( $value ) { $this->SurveyID = $value; }
        function setQuestionTypeID( $value ) { $this->QuestionTypeID = $value; }
        function setResultID( $value ) { $this->ResultID = $value; }
        function setLength( $value ) { $this->Length = $value; }
        //function setPosition( $value ) { $this->Position = $value; }
        function setContent( $value ) { $this->Content = $value; }
        function setInitial( $value ) { $this->Initial = $value; }
        function setRequired( $value ) { $this->Required = $value; }
        function setPublic( $value ) { $this->Public = $value; }

        function setPosition( $value = "" )
        {
            if ( $value != "" )
            {
                $this->Position = $value;
            }
            else
            {
                //$this->Position = eZNovisSequence::GetNextValue( "QuestionPosition" );
                $db =& eZDB::globalDatabase();
                $db->lock( "eZSurvey_Question" );
                $this->Position = $db->nextID( "eZSurvey_Question", "Position" );
                $db->unlock();
            }
        }

        var $ID;
        var $SurveyId;
        var $SurveyID;
        var $QuestionTypeID;
        var $ResultID;
        var $Length;
        var $Position;
        var $Content;
        var $Initial;
        var $Required;
        var $Public;

        // question type
        var $HasChoices;
        var $ResponseTable;

        // Types
        var $TYPE_YESNO =       "1";
        var $TYPE_TEXTBOX =     "2";
        var $TYPE_TEXTAREA =    "3";
        var $TYPE_RADIO =       "4";
        var $TYPE_CHECKBOX =    "5";
        var $TYPE_DROPDOWN =    "6";
        var $TYPE_RATE =        "8";
        var $TYPE_DATE =        "9";
        var $TYPE_NUMERIC =     "10";
        var $TYPE_PAGEBREAK =   "99";
        var $TYPE_SECTIONTEXT = "100";
        
        var $TABLE_BOOL =       "eZResponseBool";
        var $TABLE_TEXT =       "eZResponseText";
        var $TABLE_SINGLE =     "eZResponseSingle";
        var $TABLE_MULTIPLE =   "eZResponseMultiple";
        var $TABLE_RANK =       "eZResponseRank";
        var $TABLE_DATE =       "eZResponseDate";
    }

?>
