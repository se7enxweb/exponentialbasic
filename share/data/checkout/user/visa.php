<?
// 
// $Id: visa.php,v 1.4 2001/03/01 14:06:26 jb Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <08-Feb-2001 14:11:48 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//include_once( "classes/INIFile.php" );
//include_once( "classes/eztemplate.php" );
//include_once( "classes/ezlocale.php" );
//include_once( "classes/ezcurrency.php" );
//include_once( "classes/ezhttptool.php" );
//include_once( "classes/ezcctool.php" );

$ini =& eZINI::instance('site.ini');

$Language = $ini->variable( "eZTradeMain", "Language" );

if ( isset( $Action ) && $Action == "Verify" )
{
    // Add VISA transaction code here..

    // set to true if successful
    $PaymentSuccess = "true";    
}

$t = new eZTemplate( "kernel/classes/checkout/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                     "kernel/classes/checkout/user/intl/", $Language, "visa.php" );

$t->set_file( "visa_tpl", "visa.tpl" );

$t->setAllStrings();

$t->set_var( "order_id", $OrderID );
$t->set_var( "payment_type", $PaymentType );

$t->pparse( "output", "visa_tpl" );
?>