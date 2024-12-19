<?php
//
// $Id: searchsupplier.php 7713 2001-10-09 08:58:34Z jhe $
//
// Created on: <09-Oct-2001 11:46:44 jhe>
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

$ModuleName = "eZ contact";
$SearchResult[0]["DetailedSearchPath"] = "/contact/search/company/";
$SearchResult[0]["DetailedSearchVariable"] = "SearchText";
$SearchResult[0]["DetailViewPath"] = "/contact/company/view/";
$SearchResult[0]["IconPath"] = "/design/base/images/icons/document.gif";

include_once( "ezcontact/classes/ezcompany.php" );

$SearchResult[0]["Result"] =& eZCompany::search( $SearchText );
$SearchResult[0]["SearchCount"] = count( $SearchResult[0]["Result"] );
$SearchResult[0]["SubModuleName"] = "Company";

$SearchResult[1]["DetailedSearchPath"] = "/contact/search/person/";
$SearchResult[1]["DetailedSearchVariable"] = "SearchText";
$SearchResult[1]["DetailViewPath"] = "/contact/person/view/";
$SearchResult[1]["IconPath"] = "/design/base/images/icons/document.gif";

include_once( "ezcontact/classes/ezperson.php" );

$SearchResult[1]["Result"] =& eZPerson::search( $SearchText );
$SearchResult[1]["SearchCount"] = count( $SearchResult[1]["Result"] );
$SearchResult[1]["SubModuleName"] = "Person";

?>