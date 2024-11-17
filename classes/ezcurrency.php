<?php
// 
// $Id: ezcurrency.php 6200 2001-07-19 11:33:57Z jakobn $
//
// Definition of eZCompany class
//
// Created on: <06-Sep-2000 16:20:20 bf>
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
//! The eCurrency class provides currency functions.
/*!

*/

/*!TODO
  Add support for locale
  
  Add a function importString(); which returns true
  if the imported string is a local currency format.
  
  Add a function for exporting a localalized currency format.
  
*/

class eZCurrency
{
    /*!
      Constructs a new eZCurrency object.
    */
    function __construct( $value=0 )
    {
        $this->Value = $value;
        settype( $this->Value, 'double' );
    }

    /*!
      Returns the value for the currency.
    */
    function value()
    {
        return $this->Value;
    }

    /*!
      Sets the currency value.
    */
    function setValue( $value  )
    {
        $this->Value = $value;
        settype( $this->Value, 'double' );
    }

    /*!
      Returns true if the currency is negative, false if not.
    */
    function isNegative( )
    {
        if ( $this->Value < 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    var $Value;
}