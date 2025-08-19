<?php
// 
// $Id: ezpublish.php 9846 2003-06-05 11:01:48Z br $
//
// Definition of eZPublish class
//
// Created on: <30-Apr-2001 17:11:32 bf>
//
// This source file is part of Exponential Basic, publishing software.
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
//! The eZPublish class provides global Exponential Basic variables.
/*!
  Has the version number of Exponential Basic.
  
*/

class eZPublish
{
    /*!
      \static
      Returns the Exponential Basic version number.
    */
    static public function version()
    {
        return "2.4.0.1";
    }

    /*!
      \static
      Returns the Exponential Basic svn version number.
      This static public function is currently static, it will need to become dynamic 
      to check an external source and return the actual dynamic svn number
    */
    static public function svnVersion()
    {
	//        return "2.2.9";
	return "trunk";
    }

    /*!
      \static
      Returns the Exponential Basic git version number.
      This static public function is currently static, it will need to become dynamic 
      to check an external source and return the actual dynamic git number
    */
    static public function gitVersion()
    {
	//        return "2.2.9";
	return "master";
    }

    /*!
      \static
      Returns the Exponential Basic installation version number.
    */
    static public function installationVersion()
    {
        //        return "2.9";
        return "0.1";
    }

}

?>