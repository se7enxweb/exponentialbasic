<?php
// 
// $Id: ezxmlrpcfunction.php 2835 2001-01-25 09:23:53Z bf $
//
// Definition of eZXMLRPCFunction class
//
// B�rd Farstad <bf@ez.no>
// Created on: <16-Dec-2000 14:57:57 bf>
//
// This source file is part of Exponential Basic, publishing software.
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


//!! eZXMLRPC
//! eZXMLRPCFunction handles XML-RPC server functions.
/*!
  
*/

class eZXMLRPCFunction
{
    /*!
      Creates a new XML-RPC server function.
    */
    function __construct( $name, $parameters=0 )
    {
        $this->Name =& $name;
        $this->ParameterList = array();
    }

    /*!
      Returns the function name.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Adds a new parameter to the parameter list.
    */
    function addParameter( $param )
    {
        $this->ParameterList[] = $param;
    }

    /*!
      Returns the parameter list.
    */
    function parameters()
    {
        return $this->ParameterList;
    }

    /// The name of the XML-RPC function.
    var $Name;

    /// The list of parameters and their type
    var $ParameterList;    
}

?>