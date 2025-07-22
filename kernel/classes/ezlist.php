<?php
//
// $Id: ezlist.php 7213 2001-09-14 11:00:57Z br $
//
// Definition of eZList class
//
// Created on: <06-Feb-2001 15:31:37 amos>
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

//!! eZCommon
//! The class eZList helps in handling listings of various types
/*!


  To create a naviator bare at the bottom of your list you can do:
  \code
  $t = new eZTemplate( ... );
  $ItemCount = Items::totalCount();
  $Max = 10;
  $Index = 0;
  eZList::drawNavigator( $t, $ItemCount, $Max, $Index, "my_template" );
  \endcode
*/

/*!TODO
  Document drawList() properly.
*/

//include_once( "kernel/classes/INIFile.php" );
//include_once( "kernel/classes/eztexttool.php" );

class eZList
{

    /*!
      This code can be reused for simple type lists. It requires an object with the following functions:
      name(): Used for reading the name of the type.
      id(): Used for retrieving the id of the type in the database

      The object list must be initialized in the $item_type_array variable.
      Also these following variables must be set properly.
      $language_file: The file used for reading language translations, for example: consultationtype.php
      $page_path: The base name of the url, for example: /address/consultationtype
      You can also enable item placement by setting the $move_item variable to true, to make sure an
      item can be moved it must implement the moveUp() and moveDown() functions.
      If the $SortPage variable is set all items will have hyperlinked names linked to the variable content.
      If $Searchable is set to true a search button is added.
      If $template_array, $variable_array and $block_array is set they are used for extending the
      list with extra information.
    */
    static public function drawList( $params, $no_print = false )
    {
        if ( !is_array( $params ) )
        {
            print( "<br /><b>eZList::drawList() requires $" .
                   "params to be an array</b><br />" );
            return false;
        }

        if ( isset( $params["ini"] ) )
            $ini =& $params["ini"];
        else
            $ini =& INIFile::globalINI();
        if ( !is_a( $ini, "INIFile" ) )
            $ini =& INIFile::globalINI();

        $module = $params["module"];
        $module_main = $params["module_main"];
        $place = $params["place"];
        if ( $place == "" )
            $place = "admin";
        $template_dir = $params["templatedir"];
        if ( $template_dir == "" )
            $template_dir = "AdminTemplateDir";
        $language_file = $params["language_file"];

        $Language = $ini->read_var( $module_main, "Language" );
        // $DOC_ROOT = $ini->read_var( $module_main, "DocumentRoot" );

        // include_once( "kernel/classes/eztemplate.php" );

	if ( !isset( $params["typelist"] ) )
            $template_dir = "kernel/classes/" . $ini->read_var( "classes", $template_dir );
        else
            $template_dir = "kernel/$module/$place/" . $ini->read_var( $module_main, $template_dir );


        if ( !isset( $params["template"] ) or !is_a( $params["template"], "eZTemplate" ) )
            $t = new eZTemplate( $template_dir,
                                 "kernel/$module/$place/intl", $Language, $language_file );
        else
            $t =& $params["template"];

        $item_error = true;

        $HTTP_REFERER = $params["HTTP_REFERER"];
        $BackUrl = $params["back_url"];
        $page_path = $params["page_path"];
        $search_encoded = $params['search_encoded'];

        if( $HTTP_REFERER == "" )
        {
            if( $BackUrl == "" )
            {
                $back_command = "$page_path/list";
            }
            else
            {
                $back_command = $BackUrl;
            }
        }
        else
        {
            $back_command = $HTTP_REFERER;
        }

        if ( !isset( $params["typelist"] ) )
            $typelist = "typelist.tpl";
        else
            $typelist = $params["typelist"];

        if ( isset( $params["template_array"] ) )
            $template_array = $params["template_array"];
        if ( isset( $params["variable_array"] ) )
             $variable_array = $params["variable_array"];
        if ( isset( $params["block_array"] ) )
            $block_array = $params["block_array"];

        if ( isset( $template_array ) and isset( $variable_array ) and
             is_array( $template_array ) and is_array( $variable_array ) )
        {
            $standard_array = array( "list_page" => $typelist );
            $t->set_file( array_merge( $standard_array, $template_array ) );
            $t->set_file_block( $template_array );
            if ( isset( $block_array ) and is_array( $block_array ) )
                $t->set_block( $block_array );
            $t->parse( $variable_array );
        }
        else
        {
            $t->set_var( "extra_type_header", "" );
            $t->set_var( "extra_type_item", "" );
            $t->set_file( "list_page", $typelist );
        }

        $t->set_block( "list_page", "list_item_tpl", "list_item" );
        $t->set_block( "list_item_tpl", "line_item_tpl", "line_item" );
        $t->set_block( "list_page", "no_line_item_tpl", "no_line_item" );
        $t->set_block( "list_page", "search_item_tpl", "search_item" );

        $t->set_block( "list_item_tpl", "name_header_item_tpl", "name_header_item" );
        $t->set_block( "list_item_tpl", "custom_header_item_tpl", "custom_header_item" );
        $t->set_block( "list_item_tpl", "header_move_down_tpl", "header_move_down" );
        $t->set_block( "list_item_tpl", "header_separator_tpl", "header_separator" );
        $t->set_block( "list_item_tpl", "header_move_up_tpl", "header_move_up" );
        $t->set_block( "list_item_tpl", "delete_header_item_tpl", "delete_header_item" );

        $t->set_block( "line_item_tpl", "type_item_tpl", "type_item" );
        $t->set_block( "type_item_tpl", "item_plain_tpl", "item_plain" );
        $t->set_block( "type_item_tpl", "item_linked_tpl", "item_linked" );
        $t->set_block( "line_item_tpl", "item_move_up_tpl", "item_move_up" );
        $t->set_block( "line_item_tpl", "item_separator_tpl", "item_separator" );
        $t->set_block( "line_item_tpl", "item_move_down_tpl", "item_move_down" );
        $t->set_block( "line_item_tpl", "no_item_move_up_tpl", "no_item_move_up" );
        $t->set_block( "line_item_tpl", "no_item_separator_tpl", "no_item_separator" );
        $t->set_block( "line_item_tpl", "no_item_move_down_tpl", "no_item_move_down" );
        $t->set_block( "line_item_tpl", "delete_box_item_tpl", "delete_box_item" );

        $t->set_block( "list_page", "delete_button_item_tpl", "delete_button_item" );

        $t->set_var( "no_line_item", "" );
        $t->set_var( "line_item", "" );
        $t->set_var( "list_item", "" );
        $t->set_var( "search_item", "" );

        $ItemID = $params["item_id"];
        $ItemName = $params["item_name"];
        $Action = $params["action"];
        $ListType = $params["list_type"];
        $SearchText = $params["search_text"];
        if ( isset( $params["searchable"] ) )
            $Searchable = $params["searchable"];
        $item_type_array =& $params["item_type_array"];

        if ( isset( $params["form_command"] ) )
            $item_form_command = $params["form_command"];
        else
            $itme_form_command = "$page_path/new";

        $t->set_var( "item_up_command", "$page_path/up" );
        $t->set_var( "item_down_command", "$page_path/down" );
        $t->set_var( "item_edit_command", "$page_path/edit" );
        $t->set_var( "item_delete_command", "$page_path/delete" );
        $t->set_var( "item_view_command", "$page_path/view" );
        $t->set_var( "item_list_command", "$page_path/list" );
        $t->set_var( "item_new_command", "$page_path/new" );
        $t->set_var( "item_form_command", $item_form_command );
        $t->set_var( "item_id", $ItemID );
        $t->set_var( "item_name", $ItemName );
        $t->set_var( "back_url", $back_command );
        $t->set_var( "item_back_command", $back_command );

        $t->set_var( "action", $Action );
        $t->set_var( "type", $ListType );

        $SearchText = stripslashes( $SearchText );
        $t->set_var( "search_form_text", $SearchText );
        $t->set_var( "search_text", $search_encoded );

        if ( isset( $Searchable ) )
            $t->parse( "search_item", "search_item_tpl" );

        $count = count( $item_type_array );

        if ( isset( $params["func_call"] ) )
            $func_call =& $params["func_call"];
        if ( isset( $params["custom_func_call"] ) )
            $custom_func_call =& $params["custom_func_call"];
        if ( isset( $params["item_url"] ) )
            $ItemUrl = $params["item_url"];
        if ( isset( $params["move_item" ] ) )
             $move_item = $params["move_item"];

        $t->set_var( "name_header_item", "" );
        $t->set_var( "custom_header_item", "" );
        if ( isset( $params["header_names"] ) )
        {
            foreach( $params["header_names"] as $header )
            {
                $t->set_var( "custom_header", $header );
                $t->parse( "custom_header_item", "custom_header_item_tpl", true );
            }
        }
        else
        {
            $t->parse( "name_header_item", "name_header_item_tpl" );
        }

        $t->set_var( "delete_header_item", "" );
        $t->set_var( "delete_box_item", "" );
        $t->set_var( "delete_button_item", "" );
        if ( !isset( $params["no_delete"] ) )
        {
            $t->parse( "delete_header_item", "delete_header_item_tpl" );
            $t->parse( "delete_button_item", "delete_button_item_tpl" );
        }

        $i = 0;
        foreach( $item_type_array as $item )
        {
            $t->set_var( "item_move_up", "" );
            $t->set_var( "no_item_move_up", "" );
            $t->set_var( "item_move_down", "" );
            $t->set_var( "no_item_move_down", "" );
            $t->set_var( "item_separator", "" );
            $t->set_var( "no_item_separator", "" );

            $t->set_var( "type_item", "" );
            $t->set_var( "item_plain", "" );
            $t->set_var( "item_linked", "" );

            $t->set_var( "bg_color", ( $i %2 ) == 0 ? "bglight" : "bgdark" );

            if ( isset( $func_call ) and is_array( $func_call ) )
            {
                reset( $func_call );
                foreach( $func_call as $key => $val )
                {
                    $t->set_var( $key, eZTextTool::htmlspecialchars( $item->$val() ) );
                }
            }
            else
            {
                $t->set_var( "item_id", $item->id() );
                $t->set_var( "item_name", eZTextTool::htmlspecialchars( $item->name() ) );
            }

            if ( isset( $ItemUrl ) )
            {
                $t->set_var( "item_url_command", $ItemUrl );
                $t->parse( "item_linked", "item_linked_tpl" );
            }
            else
            {
                $t->parse( "item_plain", "item_plain_tpl" );
            }
            $t->parse( "type_item", "type_item_tpl", true );

            if ( isset( $custom_func_call ) and is_array( $custom_func_call ) )
            {
                $t->set_var( "item_plain", "" );
                $t->set_var( "item_linked", "" );
                reset( $custom_func_call );
                foreach( $custom_func_call as $key => $val )
                {
                    $t->set_var( "item_name", eZTextTool::htmlspecialchars( $item->$val() ) );
                    $t->parse( "item_plain", "item_plain_tpl" );
                    $t->parse( "type_item", "type_item_tpl", true );
                }
            }

            if ( $i > 0 && isset( $move_item ) )
            {
                $t->parse( "item_move_up", "item_move_up_tpl" );
            }
            else
            {
                $t->parse( "no_item_move_up", "no_item_move_up_tpl" );
            }

            if ( $i > 0 && $i < $count - 1 && isset( $move_item ) )
            {
                $t->parse( "item_separator", "item_separator_tpl" );
            }
            else
            {
                $t->parse( "no_item_separator", "no_item_separator_tpl" );
            }

            if ( $i < $count - 1 && isset( $move_item ) )
            {
                $t->parse( "item_move_down", "item_move_down_tpl" );
            }
            else
            {
                $t->parse( "no_item_move_down", "no_item_move_down_tpl" );
            }
            if ( !isset( $params["no_delete"] ) )
                $t->parse( "delete_box_item", "delete_box_item_tpl" );

            $t->parse( "line_item", "line_item_tpl", true );

            $i++;
        }

        if( $count < 1 )
        {
            $t->parse( "no_line_item", "no_line_item_tpl" );
        }
        else
        {
            $t->parse( "list_item", "list_item_tpl" );
        }

        $t->setAllStrings();

        $Max = $params["max"];
        $Offset = $params["offset"];
        $total_types = $params["total_types"];

        if ( is_numeric( $total_types ) and $total_types >= 0 )
            eZList::drawNavigator( $t, $total_types, $Max, $Offset, "list_page" );

        if ( !$no_print )
            $t->pparse( "output", "list_page" );
        else
            return $t->parse( "output", "list_page" );
    }

    /*!
      Draws next/prev navigators by using a specified template.
      $total_types is the total number of types a list will give.
      $max_types is the number of types the list should show at a time.
      $index is the index of the type list.
      if $parent is set to a string that string is used as the parent for the template blocks,
      if false all blocks needs to be set by the client.
      $variables is an associative array with various template variables, if a template variable is not specified in the array the default is used.
      It currently supports:
          next: The block containing the next button
          next_inactive: The block containing the inactive next button
          next_index: The index variable for the next button
          previous: The block containing the previous button
          previous_inactive: The block containing the inactive previous button
          previous_index: The index variable for the previous button
          item: The block containing the hotlink items
          item_inactive: The block containing the inactive hotlink items
          item_index: The item index variable used with hotlinks
          item_list: The block containing the active and inactive hotlink items
          item_name: The item name variable used with hotlinks
          type_list: The block containing the whole navigator HTML
    */
    static public function drawNavigator( &$t, $total_types, $max_types, $index, $parent = false,
    	                     $variables = false )
    {
        if ( is_array( $variables ) )
        {
            $next = $variables["next"];
            $prev = $variables["previous"];
            $next_inactive = $variables["next_inactive"];
            $prev_inactive = $variables["previous_inactive"];
            $item = $variables["item"];
            $item_inactive = $variables["item_inactive"];
            $item_list = $variables["item_list"];
            $next_index = $variables["next_index"];
            $prev_index = $variables["previous_index"];
            $item_index = $variables["item_index"];
            $item_name = $variables["item_name"];
            $type_list = $variables["type_list"];
        }

        if ( empty( $next ) )
            $next = "type_list_next";
        if ( empty( $prev ) )
            $prev = "type_list_previous";
        if ( empty( $next_inactive ) )
            $next_inactive = "type_list_next_inactive";
        if ( empty( $prev_inactive ) )
            $prev_inactive = "type_list_previous_inactive";
        if ( empty( $item_list ) )
            $item_list = "type_list_item_list";
        if ( empty( $item ) )
            $item = "type_list_item";
        if ( empty( $item_inactive ) )
            $item_inactive = "type_list_inactive_item";
        if ( empty( $next_index ) )
            $next_index = "item_next_index";
        if ( empty( $prev_index ) )
            $prev_index = "item_previous_index";
        if ( empty( $item_index ) )
            $item_index = "item_index";
        if ( empty( $item_name ) )
            $item_name = "type_item_name";
        if ( empty( $type_list ) )
            $type_list = "type_list";

        if ( is_string( $parent ) )
            $t->set_block( $parent, $type_list . "_tpl", $type_list );

        if ( ( $total_types > $max_types || $index > 0 ) and is_string( $parent ) )
        {
            $t->set_block( $type_list . "_tpl", $prev . "_tpl", $prev );
            $t->set_block( $type_list . "_tpl", $item_list . "_tpl", $item_list );
            $t->set_block( $item_list . "_tpl", $item . "_tpl", $item );
            $t->set_block( $item_list . "_tpl", $item_inactive . "_tpl", $item_inactive );
            $t->set_block( $type_list . "_tpl", $next . "_tpl", $next );
            $t->set_block( $type_list . "_tpl", $prev_inactive . "_tpl", $prev_inactive );
            $t->set_block( $type_list . "_tpl", $next_inactive . "_tpl", $next_inactive );
        }

        if ( $total_types > $max_types || $index > 0 )
        {
            $t->set_var( $prev, "" );
            $t->set_var( $next, "" );
            $t->set_var( $prev_inactive, "" );
            $t->set_var( $next_inactive, "" );

            if ( $index > 0 )
            {
                $t->set_var( $prev_index, max( $index - $max_types, 0 ) );
                $t->parse( $prev, $prev . "_tpl" );
            }
            else
            {
                $t->parse( $prev_inactive, $prev_inactive . "_tpl" );
            }
            if ( $index + $max_types < $total_types )
            {
                $t->set_var( $next_index, $index + $max_types );
                $t->parse( $next, $next . "_tpl" );
            }
            else
            {
                $t->parse( $next_inactive, $next_inactive . "_tpl" );
            }
            $total = $total_types;
            $page = ($index / ($max_types * 10));
            settype( $page, "integer" );

            $i = ( ( $page * 10 ) + 1) - $max_types;
            settype( $i, "integer" );

            if($i < 0)
                $i=1;
            $item_count = 49;
            $max = $i + $item_count;

            if( ( $max * $max_types ) > $total_types )
            {
                $max = $i + ( ( $total_types - ( $i * $max_types ) ) / $max_types ) + 1;
                settype( $max, "integer" );
                if ( $max - $item_count <= 0 )
                {
                    $i = 1;
                }
                else
                {
                    $i = $max - $item_count;
                    if ( ( $i % 10 ) + 1 != 1 )
                    {
                        $i += 10 - ( $i % 10 ) + 1;
                    }
                }
            }

            while ( $total > 0 && $i <= $max )
            {
                $cur_i = $i;
                $t->set_var( $item, "" );
                $t->set_var( $item_inactive, "" );
                $cur_page = (($i-1) / (10));
                settype( $cur_page, "integer" );
                if ( $cur_page == $page )
                {
                    $t->set_var( $item_index, ($i - 1)*$max_types );
                    $t->set_var( $item_name, $i );
                    $i++;
                    $total = $total - $max_types;
                }
                else
                {
                    $i_start = $i;
                    $i_end = $i + 9;
                    $total_pages = (($total_types-1)/$max_types) + 1;
                    settype( $total_pages, "integer" );
                    if ( $i_end > $total_pages )
                    {
                        $i_end = $total_pages;
                    }
                    $t->set_var( $item_index, ($i - 1)*$max_types );
                    if ( $i_start != $i_end )
                        $t->set_var( $item_name, $i_start . "-" . $i_end );
                    else
                        $t->set_var( $item_name, $i_start );
                    $i += 10;
                    $total = $total - $max_types*10;
                }
                if ( ($cur_i - 1)*$max_types == $index )
                {
                    $t->parse( $item_inactive, $item_inactive . "_tpl" );
                }
                else
                {
                    $t->parse( $item, $item . "_tpl" );
                }
                $t->parse( $item_list, $item_list . "_tpl", true );
            }

            $t->parse( $type_list, $type_list . "_tpl" );
        }
        else
        {
            $t->set_var( $type_list, "" );
        }
    }
}

?>