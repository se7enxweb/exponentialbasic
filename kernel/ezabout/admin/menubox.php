
<?php
// 
// $Id: menubox.php 7534 2001-09-27 15:20:35Z bf $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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
    array( "/sitemanager/cache/", "{intl-cache_admin}" ),
    array( "/sitemanager/cache/variation", "{intl-variation_admin}" ),
    array( "/sitemanager/siteconfig/", "{intl-site_config}" ),
    array( "/sitemanager/csseditor/", "{intl-css_editor}" ),
    array( "/sitemanager/file/list/", "{intl-file_list}" ),
    array( "/sitemanager/template/list/", "{intl-template_list}" ),
    array( "/sitemanager/section/list/", "{intl-section_list}" ),
    array( "/sitemanager/menu/list/", "{intl-menu_config}" ),
    array( "/sitemanager/sqladmin/query/", "{intl-sql_admin}" )
    );

?>
