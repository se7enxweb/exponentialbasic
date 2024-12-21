<?php
// 
// $Id: ezslideshow.php 6219 2001-07-20 11:06:39Z jakobn $
//
// Definition of eZSlideshow class
//
// Created on: <27-Jun-2001 10:45:21 jhe>
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

//!! eZImageCatalogue
//! eZSlideshow documentation.
/*!

  Example code:
  \code
  \endcode

*/

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );

class eZSlideshow
{

    /*!
      Constructs a new eZSlideshow object
    */
    function __construct( $category, $user, $pos = 0 )
    {
        $this->CategoryID = $category;
        $this->User = $user;
        $this->Position = $pos;

        $this->get( $this->User, $this->CategoryID );
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $user, $category )
    {
        $this->ImageArray =& eZImageCategory::getImages( $user, $category );
    }

    /*!
      Returns the image at position pos
      If pos is omitted, the objects position will be used
    */
    function image( $pos = false )
    {
        if ( !$pos )
            $pos = $this->Position;

        if ( $pos >= count( $this->ImageArray ) )
            return false;
        else
            return $this->ImageArray[$pos];
    }

    /*!
      Returns the number of images in the slideshow
    */
    function size()
    {
        return count( $this->ImageArray );
    }
    
    /*!
      Returns the current position in the slideshow
    */
    function currentPosition()
    {
        return $this->Position;
    }

    var $CategoryID;
    var $Position;
    var $ImageArray;
    var $User;
}

?>