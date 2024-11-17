<?
// 
// $Id: ezcheckoutsupplier.php,v 1.2 2001/02/20 16:12:48 bf Exp $
//
// Definition of eZCheckoutSupplier class
//
// B�rd Farstad <bf@ez.no>
// Created on: <02-Feb-2001 15:22:14 bf>
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

//!! eZTrade
//! eZCheckoutSupplier handles checkout instances for the current checkout method.
/*!
  
*/
include_once( "classes/INIFile.php" );
class eZCheckoutSupplier
{
    /*!
      
    */
    function __construct()
    {
		
		$ini =& INIFile::globalINI();
		$PaymentMethods = $ini->read_var( "Checkout", "PaymentMethods" );
		$paymentArray = explode(';',$PaymentMethods);
		$i=1;
		foreach($paymentArray as $paymentItem)
			{
				$paymentItemArray = explode('|',$paymentItem);
				$this->PaymentMethods[] = array( "ID" => $i, "Text" => $paymentItemArray[0] );
				$i++;
			}
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
        }

        return $ret;
    }
    
    /*!
      Returns the file to include for the given payment type.
    */
    function &paymentFile( $id )
    {
		$ini =& INIFile::globalINI();
		$PaymentMethods = $ini->read_var( "Checkout", "PaymentMethods" );
		$paymentArray = explode(';',$PaymentMethods);
		$i=1;
		foreach ($paymentArray as $paymentArrayItem)
			{
				$retArray = explode('|',$paymentArrayItem);
				if ( $id == $i )
					$ret = "checkout/user/".$retArray[1];
				elseif ($i == 1 && $id != $i)
					$ret = "checkout/user/".$retArray[1];		
				$i++;				
			}

        return $ret;
    }
    var $PaymentMethods;
}

?>
