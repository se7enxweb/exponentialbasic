<?
// 
// $Id: formlist.php 6206 2001-07-19 12:19:22Z jakobn $
//
// Created on: <15-Jun-2001 15:02:54 pkej>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezform/classes/ezform.php" );
// include_once( "ezarticle/classes/ezarticleform.php" );

$ActionValue = "list";
$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZFormMain", "Language" );

$article = new eZArticle( $ArticleID );
$selectedForm = eZArticleForm::articleHasForm( $article );

if( isset( $OK ) )
{
    if( $selectedFormID > 0 )
    {
        $form = new eZForm( $selectedFormID );
        $article->deleteForms();
        $article->addForm( $form );
        $article->store();
        $selectedForm =& $form;
    }
    
    eZHTTPTool::header( "Location: /article/articleedit/edit/$ArticleID/" );
    exit();
}

$t = new eZTemplate( "kernel/ezarticle/admin/" . $ini->variable( "eZArticleMain", "AdminTemplateDir" ),
                     "kernel/ezarticle/admin/intl/", $Language, "formlist.php" );
$t->setAllStrings();

$t->set_file( array(
    "form_list_page_tpl" => "formlist.tpl"
    ) );

$t->set_block( "form_list_page_tpl", "no_forms_item_tpl", "no_forms_item" );
$t->set_block( "form_list_page_tpl", "form_list_tpl", "form_list" );
$t->set_block( "form_list_tpl", "form_item_tpl", "form_item" );

$t->set_var( "form_item", "" );
$t->set_var( "form_list", "" );
$t->set_var( "no_forms_item", "" );

$totalCount =& eZForm::count();
$forms =& eZForm::getAll();

if( count( $forms ) == 0 )
{
    $t->parse( "no_forms_item", "no_forms_item_tpl" );
}
else
{
    $i = 0;
    foreach( $forms as $form )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        if( $selectedForm->id() == $form->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
        
        $t->set_var( "form_id", $form->id() );
        $t->set_var( "form_name", $form->name() );
        $t->set_var( "form_receiver", $form->receiver() );
        $t->parse( "form_item", "form_item_tpl", true );
        
        $i++;
    }
    
    
    $t->parse( "form_list", "form_list_tpl" );
}

$t->set_var( "article_id", $ArticleID );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteDesign );
$t->set_var( "selectedFormID", $selectedFormID );

$t->pparse( "output", "form_list_page_tpl" );

?>