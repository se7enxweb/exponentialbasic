<?php
// 
// $Id: ezcheckoutsupplier.php 7552 2001-09-28 09:19:50Z ce $
//
// Definition of eZCheckoutSupplier class
//
// Created on: <02-Feb-2001 15:22:14 bf>
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

//!! eZTrade
//! eZCheckoutSupplier handles checkout instances for the current checkout method.
/*!
  
*/

class eZCheckoutSupplier
{
    function __construct()
    {
        $this->PaymentMethods = array( array( "ID" => 1, "Text" => "VISA" ),
                                       array( "ID" => 2, "Text" => "Mastercard" ),
                                       array( "ID" => 3, "Text" => "Invoice" ),
                                       array( "ID" => 4, "Text" => "Voucher" )
                                       );
    }

    /*!
      Returns the payment types supported by this checkout method.

      The types are returned as an
      array(
           array( "ID" => $id, "Text" => $text ),
           array( "ID" => $id, "Text" => $text )
           );
    */
    function &paymentMethods( )
    {
        return $this->PaymentMethods;
    }

    /*!
      Returns the payment type with the given id.
    */
    function &paymentName( $id )
    {
        $ret = "unknown";
        foreach ( $this->PaymentMethods as $paymentMethod )
        {
            if ( $paymentMethod["ID"] == $id )
            {
                $ret = $paymentMethod["Text"];
            }
            if ( $id == "voucher_done" )
                $ret = $this->PaymentMethods[3]["Text"];
        }

        return $ret;
    }
    
    /*!
      Returns the file to include for the given payment type.
    */
    function &paymentFile( $id )
    {
        $ret = "eztrade/user/visa.php";
        switch( $id )
        {
            case 1 :
            {
                $ret = "eztrade/user/visa.php";
            }
            break;
            case 2 :
            {
                $ret = "eztrade/user/mastercard.php";
            }
            break;
            case 3 :
            {
                $ret = "eztrade/user/invoice.php";
            }
            break;
            case 4 :
            {
                $ret = "eztrade/user/voucher.php";
            }
            break;
        }
        return $ret;
    }

    var $PaymentMethods;
}

?>