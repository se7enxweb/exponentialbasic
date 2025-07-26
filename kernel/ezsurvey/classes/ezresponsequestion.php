<?php

    // include_once( "ezsurvey/classes/ezquestion.php" );
    // include_once( "ezsurvey/classes/ezquestionchoice.php" );
    
    // // response types classes
    // include_once( "ezsurvey/classes/ezresponsebool.php" );
    // include_once( "ezsurvey/classes/ezresponsedate.php" );
    // include_once( "ezsurvey/classes/ezresponsemultiple.php" );
    // include_once( "ezsurvey/classes/ezresponserank.php" );
    // include_once( "ezsurvey/classes/ezresponsesingle.php" );
    // include_once( "ezsurvey/classes/ezresponsetext.php" );
    
    class eZResponseQuestion
    {
        function __construct( $response = "", $question = "" )
        {
            if ( $response != "" && $question != "" )
            {
                $this->get( $response, $question );
            }
        }
        
        function get( $responseID, $questionID )
        {
            $this->ResponseID = $responseID;
            $this->QuestionID = $questionID;
         
            $question = new eZQuestion( $this->QuestionID );
            
            switch( $question->responseTable() )
            {
                case $question->TABLE_BOOL:
                {
                    $response = new eZResponseBool();
                    $response_array = $response->getAll( "ID", $this->ResponseID, $this->QuestionID );
                    if ( count($response_array) == 1 )
                    {
                        $ChoiceID = $response_array[0]->choiceID();
                    }
                }
                break;
                
                case $question->TABLE_TEXT:
                {
                    $response = new eZResponseText();
                    $response_array = $response->getAll( "ID", $this->ResponseID, $this->QuestionID );
                    if ( count($response_array) == 1 )
                    {
                        $ChoiceID = $response_array[0]->response();
                    }
                }
                break;
                
                case $question->TABLE_SINGLE:
                {
                    $response = new eZResponseSingle();
                    $response_array = $response->getAll( "ID", $this->ResponseID, $this->QuestionID );
                    
                    if ( count($response_array) == 1 )
                    {
                        $ChoiceID = $response_array[0]->choiceID();
                        $Other = $response_array[0]->other();
                    }
                }
                break;
                
                case $question->TABLE_MULTIPLE:
                {
                    $response = new eZResponseMultiple();
                    $response_array = $response->getAll( "ID", $this->ResponseID, $this->QuestionID );
                    
                    foreach ( $response_array as $responseItem )
                    {
                        $ChoiceID[] = $responseItem->choiceID();
                        $Other[] = $responseItem->other();
                    }
                }
                break;
                
                case $question->TABLE_RANK:
                {
                    $response = new eZResponseRank();
                    $response_array = $response->getAll( "ID", $this->ResponseID, $this->QuestionID );
                    
                    foreach ( $response_array as $responseItem )
                    {
                        $ChoiceID[] = $responseItem->choiceID();
                        $Other[] = $responseItem->rank();
                    }
                }
                break;
                
                case $question->TABLE_DATE:
                {
                    $response = new eZResponseDate();
                    $response_array = $response->getAll( "ID", $this->ResponseID, $this->QuestionID );
                    if ( count($response_array) == 1 )
                    {
                        $ChoiceID = $response_array[0]->response();
                    }
                }
                break;
            }
            
            $this->ChoiceID = $ChoiceID;
            $this->Other = $Other;
            
            return $this;
        }

        function getResponsesByQuestion( $OrderBy = "ID", $QuestionID = "", $LimitStart = "None", $LimitBy = "None" )
        {
            $db =& eZDB::globalDatabase();

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

            $question = new eZQuestion( $QuestionID );
            
            switch( $question->responseTable() )
            {
                case $question->TABLE_BOOL:
                {
                    $response = new eZResponseBool();
                }
                break;
                
                case $question->TABLE_TEXT:
                {
                    $response = new eZResponseText();
                }
                break;
                
                case $question->TABLE_SINGLE:
                {
                    $response = new eZResponseSingle();
                }
                break;
                
                case $question->TABLE_SINGLE:
                {
                    $response = new eZResponseMultiple();
                }
                break;
                
                case $question->TABLE_RANK:
                {
                    $response = new eZResponseRank();
                }
                break;
                
                case $question->TABLE_DATE:
                {
                    $response = new eZResponseDate();
                }
                break;
            }
            
            $question_array = Array();
            $resturn_array = Array();
            
            $question_array = $response->getAll( $OrderBy, "", $QuestionID );
            foreach ( $question_array as $questionItem )
            {
                $return_array = new eZResponseQuestion( $response->ResponseID(), $QuestionID );
            }
            
            return $return_array;
        }
        
        function store()
        {
            $question = new eZQuestion( $this->QuestionID );
            
            $this->delete();
            
            switch( $question->responseTable() )
            {
                case $question->TABLE_BOOL:
                {
                    $response = new eZResponseBool();
                    
                    $response->setResponseID( $this->ResponseID );
                    $response->setQuestionID( $this->QuestionID );
                    $response->setChoiceID( $this->ChoiceID );
                    
                    $response->store();
                }
                break;
                
                case $question->TABLE_TEXT:
                {
                    $response = new eZResponseText();
                    
                    $response->setResponseID( $this->ResponseID );
                    $response->setQuestionID( $this->QuestionID );
                    $response->setResponse( $this->ChoiceID );
                    
                    $response->store();
                }
                break;
                
                case $question->TABLE_SINGLE:
                {
                    $response = new eZResponseSingle();
                    
                    $response->setResponseID( $this->ResponseID );
                    $response->setQuestionID( $this->QuestionID );
                    $response->setChoiceID( $this->ChoiceID );
                    $response->setOther( $this->Other );
                    
                    $response->store();
                }
                break;
                
                case $question->TABLE_MULTIPLE:
                {
                    if ( is_array( $this->ChoiceID ) )
                    {
                        $i = 0;
                        foreach ( $this->ChoiceID as $choiceItem )
                        {
                            $response = new eZResponseMultiple();
                            
                            $response->setResponseID( $this->ResponseID );
                            $response->setQuestionID( $this->QuestionID );
                            $response->setChoiceID( $choiceItem );
                            $response->setOther( $this->Other[$i++] );
                            
                            $response->store();
                        }
                    }
                }
                break;
                
                case $question->TABLE_RANK:
                {
                    $i = 0;
                    foreach ( $this->ChoiceID as $choiceItem )
                    {
                        $response = new eZResponseRank();
                        
                        $response->setResponseID( $this->ResponseID );
                        $response->setQuestionID( $this->QuestionID );
                        $response->setChoiceID( $choiceItem );
                        $response->setRank( $this->Other[$i++] );
                        
                        $response->store();
                    }
                }
                break;
                
                case $question->TABLE_DATE:
                {
                    $response = new eZResponseDate();
                    
                    $response->setResponseID( $this->ResponseID );
                    $response->setQuestionID( $this->QuestionID );
                    $response->setResponse( $this->ChoiceID );
                    
                    $response->store();
                }
                break;
            }
        }
     
        function delete()
        {
            $question = new eZQuestion( $this->QuestionID );
            
            switch( $question->responseTable() )
            {
                case $question->TABLE_BOOL:
                {
                    $response = new eZResponseBool();                    
                }
                break;
                
                case $question->TABLE_TEXT:
                {
                    $response = new eZResponseText();
                }
                break;
                
                case $question->TABLE_SINGLE:
                {
                    $response = new eZResponseSingle();
                }
                break;
                
                case $question->TABLE_MULTIPLE:
                {
                    $response = new eZResponseMultiple();
                }
                break;
                
                case $question->TABLE_RANK:
                {
                    $response = new eZResponseRank();
                }
                break;
                
                case $question->TABLE_DATE:
                {
                    $response = new eZResponseDate();
                }
                break;
            }
            
            $response->deleteResponse( $this->ResponseID, $this->QuestionID );
        }
        
        public static function deleteByQuestion( $QuestionID )
        {
            $question = new eZQuestion( $QuestionID );
            
            $delete = true;
            
            switch( $question->responseTable() )
            {
                case $question->TABLE_BOOL:
                {
                    $response = new eZResponseBool();
                }
                break;
                
                case $question->TABLE_TEXT:
                {
                    $response = new eZResponseText();
                }
                break;
                
                case $question->TABLE_SINGLE:
                {
                    $response = new eZResponseSingle();
                }
                break;
                
                case $question->TABLE_MULTIPLE:
                {
                    $response = new eZResponseMultiple();
                }
                break;
                
                case $question->TABLE_RANK:
                {
                    $response = new eZResponseRank();
                }
                break;
                
                case $question->TABLE_DATE:
                {
                    $response = new eZResponseDate();
                }
                break;
                
                default:
                {
                    $delete = false;
                }
            }

            if ( $delete )
            {
                $response->deleteByQuestion( $QuestionID );
            }
        }
        
        function report()
        {
            $question = new eZQuestion( $this->QuestionID );
            
            // n�o h� report para estes
            if ( $question->questionTypeID() == $question->TYPE_PAGEBREAK
                 || $question->questionTypeID() == $question->TYPE_SECTIONTEXT )
            {
                return;
            }
            
            $ret = $question->content() . "\n";
            
            if ( is_array( $this->ChoiceID ) )
            {
                $i = 0;
                
                foreach ( $this->ChoiceID as $choiceItem ) 
                {
                    $choice = new eZQuestionChoice( $choiceItem );
                    $other = $this->Other[$i++];
                    $ret .= "- " . $choice->content();
                    if ( $other != "" )
                    {
                        if ( $question->questionTypeID() == $question->TYPE_RATE )
                        {
                            $other++;
                        }
                        $ret .= ": $other";
                    }
                    $ret .= "\n";
                }
            }
            else
            {
                if ( $question->hasChoices() )
                {
                    $choice = new eZQuestionChoice( $this->ChoiceID );
                    $ret .= "- " . $choice->content();
                }
                else
                {
                    $ret .= "- $this->ChoiceID";
                }
                
                if ( $this->Other != "" )
                {
                    $ret .= ": $this->Order";
                }
                $ret .= "\n";
            }
            
            return $ret;
        }
        
        // get functions
        function responseID() { return $this->ResponseID; }
        function questionID() { return $this->QuestionID; }
        function choiceID() { return $this->ChoiceID; }
        function other() { return $this->Other; }
        
        // set functions
        function setResponseID( $value ) { $this->ResponseID = $value; }
        function setQuestionID( $value ) { $this->QuestionID = $value; }
        function setChoiceID( $value ) { $this->ChoiceID = $value; }
        function setOther( $value ) { $this->Other = $value; }
        
        var $ResponseID;
        var $QuestionID;
        var $ChoiceID;
        var $Other;
    }
