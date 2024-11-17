<?php
// 
// $Id: sqlquery.php 7785 2001-10-11 11:39:51Z bf $
//
// Created on: <26-Sep-2001 19:23:56 bf>
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

// TODO
//
//  after testing it a bit; here is the features I think we should implement:
//+ display number of rows in result
//- use template on the table code
//+ use intl strings on the error messages
//+ save queries, mabye save it in the user preferences ( use the 
//  eZPrefrerences class )
//- Be able to reuse saved queries ( for quicker handling of multiple queries, 
//  mabye a drop down selection of your saved queries )
//- export the result to a comma separated file to open in excel etc.
//- show errors in a friendly way ( both mysql and postgresql ) not a high pri.
//

include_once( "classes/ezdb.php" );

$Language = $ini->read_var( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "ezsitemanager/admin/intl", $Language, "sqlquery.php" );

$t->set_file( "sql_query_tpl", "sqlquery.tpl" );

$t->setAllStrings();


// include the code
include_once( "ezsession/classes/ezpreferences.php" );
$preferences = new eZPreferences();
    	  
// get the preferences
$QueryPref =& $preferences->variableArray( "QueryPref" );

if ( isset( $Run2 ) )
{
    $QueryText = $QueryText2;    
}

if ( $QueryPref )
{
    $QueryText2 = "<select name=\"QueryText2\">";

    while ( list( $key, $val ) = each ( $QueryPref ) )
    {
	$QueryText2 .= "<option>$val</option>";
    }
    
    $QueryText2 .= "</select>";
    
}


$db =& eZDB::globalDatabase();

$QueryRes   = '';
$QueryError = '';

if ( !isset( $QueryText ) )
{
    $QueryError = 'No query specified';        
}
else
{
    $QueryText = trim( $QueryText );
    $QueryArray = explode( " ", $QueryText );

    if ( $QueryArray[0] == "insert" ||
         $QueryArray[0] == "update" ||
         $QueryArray[0] == "create" ||
    $QueryArray[0] == "alter" )
    {
        $db->query( $QueryText );
    }
    else
    {
        $db->array_query( $return_array, $QueryText );
    }

    if ( $return_array )
    {
        $QueryRes .= "<table width=\"100%\" border=\"1\" >";

        $QueryRes .= "<tr>";

        // print the column names
        reset( $return_array[0] );
        while ( list( $key, $val ) = each ( $return_array[0] ) )
        {
            $res = each ( $return_array[0] );
            $QueryRes .= "<th>" . $res[0] . "</th>";
        }    
        
        $QueryRes .= "</tr>";
	
        for ( $i = 0; $i < count( $return_array ); $i++ )
        {
            
            $QueryRes .= "<tr>";
	    
            for ( $j = 0; $j <  count( $return_array[$i]) / 2 ; $j++ )
            {
                $QueryRes .= "<td>" . htmlspecialchars( $return_array[$i][$j] ) . "</td>";
            }
            $QueryRes .= "</tr>";
        }	
        $QueryRes .= "</table>";


	// set the preferences

	if ($QueryText <> $QueryPref[count ($QueryPref) - 1 ] )
	{
    	    $QueryPref = array_merge ( $QueryPref, array ( $QueryText) );
	    $preferences->setVariable( "QueryPref", $QueryPref );
	}
	else
	{
	    $preferences->setVariable( "QueryPref", $QueryPref );	
	}
	
    }
    else
    {
        $QueryError = "SQL error occured";
    }

    

}

if( isset( $QueryText ) )
{

    $t->set_var( "query_text",   $QueryText  );
}
else
{

    $t->set_var( "query_text", '' );
}

if( isset( $QueryText2 ) )
{
    $t->set_var( "query_text2",  $QueryText2 );
}
else
{
    $t->set_var( "query_text2", '' );
}

if( isset( $return_array ) )
{
    $t->set_var( "query_rows",   count ($return_array));
}
else
{
    $t->set_var( "query_rows", 0 );
}


$t->set_var( "query_result", $QueryRes   );

if( isset( $return_array ) )
{
    $t->set_var( "query_rows",   count ($return_array));
}
else
{
    $t->set_var( "query_rows", 0 );
}

$t->set_var( "error", 	     $QueryError );

$t->pparse( "output", "sql_query_tpl" );

?>