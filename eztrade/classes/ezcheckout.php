<?php
// 
// $Id: ezcheckout.php 6304 2001-07-29 23:31:17Z kaid $
//
// Definition of eZCheckout class
//
// Created on: <02-Feb-2001 15:00:11 bf>
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
//! eZCheckout handles user chekcouts and payment methods.
/*!
  This class handles the chechout and payment methods. This is the
  default implementation which is meant to be used as a skeleton for
  your own specific implementation.

  To create your own checkout routine simply create a folder named checkout/
  in the eZ publish root and copy the ezcheckoutsupplier.php to the classes/
  folder of that directory. The reason for the checkout folder for custom checkout
  code is compatibility with upgrades of the software and some CVS issues.

  You will also find an example implementation of a custom checkout routine
  in the file checkout.tar.gz which is found in the custom folder of your
  eZ publish installation.
  
*/

class eZCheckout
{
    /*!
      Instantiates the checkout and finds the correct checkout class.
    */
    function eZCheckout()
    {
        $ini =& INIFile::globalINI();

        $Checkout = $ini->read_var( "eZTradeMain", "Checkout" );

        // check for local checkout code
        if ( eZFile::file_exists( "checkout/classes/ezcheckoutsupplier.php" ) )
        {
            include_once( "checkout/classes/ezcheckoutsupplier.php" );
        }
        else
        {
            include_once( "eztrade/classes/ezcheckoutsupplier.php" );
        }
        
        $this->CheckoutObject = new eZCheckoutSupplier( );
    }

    /*!
      Returns the checkout instance.
    */
    function &instance()
    {
        return $this->CheckoutObject;
    }

    /// Checkout object which handles checkouts.
    var $CheckoutObject;

}


?>
