<?php
//
// $Id: datasupplier.php 8485 2001-11-16 12:53:06Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "ezmail/classes/ezmail.php" );

$eZFormOperation = $url_array[2];
$eZFormName = $url_array[3];
$eZFormAction = $url_array[3];

function &errorPage( $PrimaryName, $PrimaryURL, $type )
{
//    $ini =& $GLOBALS["GlobalSiteIni"];
    $ini =& INIFile::globalINI();
    

    $t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                         "ezform/admin/intl", $ini->read_var( "eZFormMain", "Language" ), "errors.php" );

    $t->set_file( "page", "errormessage.tpl"  );
    $t->set_var( "primary_url", $PrimaryURL  );
    $t->set_var( "primary_url_name", $t->Ini->read_var( "strings", $PrimaryName  ) );

    $t->set_var( "error_header", $t->Ini->read_var( "strings", error_ . $type . _header ) );
    $t->set_var( "error_1", $t->Ini->read_var( "strings", error_ . $type . _1 ) );
    $t->set_var( "error_2", $t->Ini->read_var( "strings", error_ . $type . _2 ) );
    $t->set_var( "error_3", $t->Ini->read_var( "strings", error_ . $type . _3 ) );

    $t->setAllStrings();

    $error = $t->parse( "error", "page" );
    $Info =& stripslashes( $error );
    $error =& urlencode( $Info );
    return $error;
}

// $mailSendTo = "";
// $mailSendFrom = "";
// $mailSubject = "";
// $mailMessage = "";
// $redirectTo = "";

function formProcess( $value, $key )
{
    global $mailSendTo;
    global $mailSendFrom;
    global $mailSubject;
    global $mailMessage;
    global $redirectTo;
    
    switch ( $key )
    {
        case "submit":
        {
        }
        break;
        
        case "redirectTo":
        {
            $redirectTo = $value;
        }
        break;
        
        case "mailSendTo":
        {
            $mailSendTo = $value;
        }
        break;
        
        case "mailSendFrom":
        {
            $mailSendFrom = $value;
        }
        break;
        
        case "mailSubject":
        {
            $mailSubject = $value;
        }
        break;
        
        default:
            $mailMessage = $mailMessage . "$key:\n$value\n\n";
            break;
    }
}

switch ( $eZFormOperation )
{
    case "form":
    {
        $FormID = $url_array[4];
        $SectionIDOverride = $url_array[5];

        switch ( $eZFormAction )
        {
            case "view":
            case "process":
            {
                include( "ezform/user/formview.php" );
            }
            break;
            
            default:
            {
                eZHTTPTool::header( "Location: /error/404" );
            }
        }
        
        
    }
    break;
    
    case "simpleprocess":
    {
        if ( $HTTP_POST_VARS )
        {
            array_walk( $HTTP_POST_VARS, "formProcess" );
            
            $mail = new eZMail();
            $mail->setSubject( $mailSubject );
            $mail->setBody( $mailMessage );
            $mail->setFrom( $mailSendFrom );
            $mail->setTo( $mailSendTo );
            $mail->send();
        }
        
        if ( !empty( $redirectTo ) )
        {
            eZHTTPTool::header( "Location: $redirectTo" );
        }
        else
        {
            eZHTTPTool::header( "Location: /" );
        }
    }
    break;
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404" );
    }
    break;
}

?>
