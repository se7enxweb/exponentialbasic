<?php
//
// $Id: searchsupplier.php 7714 2001-10-09 08:59:33Z jhe $
//
// Created on: <04-Oct-2001 14:13:53 bf>
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


$ModuleName = "Products";
$SearchResult[0]["DetailedSearchPath"] = "/trade/search/";
$SearchResult[0]["DetailedSearchVariable"] = "Query";
$SearchResult[0]["DetailViewPath"] = "/trade/productview/";
$SearchResult[0]["IconPath"] = "/design/base/images/icons/document.gif";

// include_once( "eztrade/classes/ezproduct.php" );

$product = new eZProduct();

$SearchResult[0]["Result"] =& $product->activeProductSearch( $SearchText, $Offset, $Limit );
$SearchResult[0]["SearchCount"] = $product->activeProductSearchCount( $SearchText );

?>