<?php
//
// $Id: questionedit.php 8687 2001-12-06 10:19:29Z jhe $
//
// Created on: <22-May-2001 16:17:22 ce>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezquiz/classes/ezquizquestion.php" );

$errorMessages = array();
if ( isSet( $NewAlternative ) )
{
    $question = new eZQuizQuestion( $QuestionID );
    $alternative = new eZQuizAlternative();
    $alternative->setQuestion( $question );
    $alternative->store();
    $Action = "Update";
}
else
{
    $question = new eZQuizQuestion( $QuestionID );
}


if ( isSet( $OK ) )
{
    $question = new eZQuizQuestion( $QuestionID );

    if ( $question->countAlternatives() == false )
    {
        $errorMessages[] = "error_add_alternative";
    }

    if ( count( $errorMessages ) > 0 )
    {
        unset( $OK );
    }
    $Action = "Update";
}

if ( isSet( $Cancel ) )
{
    $question = new eZQuizQuestion( $QuestionID);
    $game =& $question->game();
    $gameID = $game->id();
    eZHTTPTool::header( "Location: /quiz/game/edit/$gameID/" );
    exit();
}

if ( isSet( $Delete ) )
{
    if ( count( $AlternativeDeleteArray ) > 0 )
    {
        foreach ( $AlternativeDeleteArray as $AltID )
        {
            eZQuizAlternative::delete( $AltID );
        }
    }
    $Action = "";
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZQuizMain", "Language" );

$t = new eZTemplate( "ezquiz/admin/" . $ini->read_var( "eZQuizMain", "AdminTemplateDir" ),
                     "ezquiz/admin/" . "/intl", $Language, "questionedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "question_edit_page" => "questionedit.tpl"
      ) );

$t->set_block( "question_edit_page", "alternative_list_tpl", "alternative_list" );
$t->set_block( "alternative_list_tpl", "alternative_item_tpl", "alternative_item" );
$t->set_block( "question_edit_page", "error_list_tpl", "error_list" );
$t->set_block( "error_list_tpl", "error_item_tpl", "error_item" );

$t->set_var( "question_name", isset( $Name ) ? $Name : false );
$t->set_var( "question_description", isset( $Description ) ? $Description : false );

if ( isset( $Action ) && $Action == "Update" )
{
    if ( is_numeric( $QuestionID ) )
        $question = new eZQuizQuestion( $QuestionID );
    else
        $question = new eZQuizQuestion();

    if ( empty( $Name ) )
    {
        $errorMessages[] = "error_missing_question_name";
        unset( $OK );
    }
    else
    {
        $question->setName( $Name );
    }
    $question->store();
    $alternativeNameError = false;
    if ( isset( $AlternativeArrayID ) && count( $AlternativeArrayID ) > 0 )
    {
        for ( $i = 0; $i < count( $AlternativeArrayID ); $i++ )
        {
            $alternative = new eZQuizAlternative( $AlternativeArrayID[$i] );
            if ( empty( $AlternativeArrayName[$i] ) )
            {
                if ( $alternativeNameError == false )
                {
                    $errorMessages[] = "error_missing_answer_name";
                    unset( $OK );
                    $alternativeNameError = true;
                }
            }
            else
            {
                $alternative->setName( $AlternativeArrayName[$i] );
            }

            if ( isset( $IsCorrect ) && $IsCorrect == $AlternativeArrayID[$i] )
                $alternative->setIsCorrect( true );
            else
                $alternative->setIsCorrect( false );
            $alternative->store();
        }
        unset( $alternative );
    }

    if ( $question->countCorrectAlternatives() == false && !isset( $NewAlternative ) )
    {
        $errorMessages[] = "error_no_correct_alternative";
        unset( $OK );
    }


    if ( isSet( $OK ) )
    {
        $game =& $question->game();
        $gameID = $game->id();
        eZHTTPTool::header( "Location: /quiz/game/edit/$gameID" );
        exit();
    }
}

if ( isset( $Action ) && $Action == "Delete" )
{
    if ( count( $AlternativeArrayID ) > 0 )
    {
        foreach ( $AlternativeArrayID as $AlternativeID )
        {
            $alternative = new eZQuizAlternative( $AlternativeID );
            $alternative->delete();
        }
    }
    eZHTTPTool::header( "Location: /quiz/game/question/edit/$GameID" );
    exit();
}

if ( is_numeric( $QuestionID ) )
{
    if ( !is_a( $question, "eZQuizQuestion" ) )
        $question = new eZQuizQuestion( $QuestionID );
    $t->set_var( "question_id", $question->id() );
    $t->set_var( "question_name", $question->name() );

    $alternativeList =& $question->alternatives();
}

if ( count( $alternativeList ) > 0 )
{
    foreach ( $alternativeList as $alternative )
    {
        $t->set_var( "alternative_id", $alternative->id() );
        $t->set_var( "alternative_name", $alternative->name() );

        if ( $alternative->isCorrect() == $alternative->id() )
            $t->set_var( "is_selected", "checked" );
        else
            $t->set_var( "is_selected", "" );

        $t->parse( "alternative_item", "alternative_item_tpl", true );
    }
    $t->parse( "alternative_list", "alternative_list_tpl", true );
}
else
{
    $t->set_var( "alternative_list", "" );
}

if ( count( $errorMessages ) > 0 )
{
    foreach ( $errorMessages as $errorMessage )
    {
        $errorMessage =& $t->Ini->read_var( "strings", $errorMessage );
        $t->set_var( "error_message", $errorMessage );
        $t->parse( "error_item", "error_item_tpl", true );
    }

    $t->set_var( "question_name", $Name );
    $t->set_var( "question_id", $QuestionID );

    $t->parse( "error_list", "error_list_tpl" );
}
else
{
    $t->set_var( "error_list", "" );
}

$t->pparse( "output", "question_edit_page" );

?>