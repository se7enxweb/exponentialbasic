<?php
// 
// $Id: menubox.php 6248 2001-07-24 15:42:35Z ce $
//
// Created on: <24-Jul-2001 10:46:27 ce>
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

// Supply $menuItems to get a menubox

$menuItems = array(
    array( "/mediacatalogue/category/list/", "{intl-category_list}" ),
    array( "/mediacatalogue/category/new/", "{intl-new_category}" ),
    array( "/mediacatalogue/media/new/", "{intl-new_media}" ),
    array( "/mediacatalogue/unassigned/", "{intl-unassigned}" ),
    array( "/mediacatalogue/typelist/", "{intl-typelist}" ),
    array( "/mediacatalogue/typeedit/", "{intl-newtype}" )

    );

?>