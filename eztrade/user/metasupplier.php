<?php
//
// $Id: metasupplier.php 6233 2001-07-20 11:42:02Z jakobn $
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

switch ( $url_array[2] )
{
    case "product" :
        ?>
        <meta name="author" content="eZ systems"/>
        <meta name="copyright" content="eZ systems &copy; 2000"/>
        <meta name="description" content="Shopping tags..."/>
        <meta name="keywords" content="product info "/>        
        <?
        break;
    case "search" :
        ?>
        <meta name="author" content="eZ systems"/>
        <meta name="copyright" content="eZ systems &copy; 2000"/>
        <meta name="description" content="Search for products on zez"/>
        <meta name="keywords" content="fast search and transfer :) "/>        
        <?        
        break;
    default :
        ?>
        <meta name="author" content="eZ systems"/>
        <meta name="copyright" content="eZ systems &copy; 2000"/>
        <meta name="description" content="Page not found, sorry about that"/>
        <meta name="keywords" content="404 sorry.. what can I say."/>
        <?
        break;
}
?>
