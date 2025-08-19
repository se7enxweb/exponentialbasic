<?
// 
// $Id: menubox.php,v 1.2 2001/02/19 17:20:18 gl Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// This source file is part of Exponential Basic, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
    array( "/groupeventcalendar/categorylist/", "{intl-event_category_list}" ),
    array( "/groupeventcalendar/categoryedit/new/", "{intl-new_event_category}" ),
    array( "/groupeventcalendar/typelist/", "{intl-event_type_list}" ),
    array( "/groupeventcalendar/typeedit/new/", "{intl-new_event_type}" ),
    array( "/groupeventcalendar/editor/", "{intl-calendar_editor}" ),
    array( "/groupeventcalendar/grpdspl/", "{intl-group_dspl}" )
    );

?>
