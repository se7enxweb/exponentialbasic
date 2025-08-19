<?php
// 
// $Id: menubox.php 8099 2001-10-30 17:35:04Z fh $
//
// Created on: <25-Nov-2000 17:53:52 bf>
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

if ( $ini->variable( "eZBulkMailMain", "UseEZUser" ) == "enabled" )
{
$menuItems = array(
    array( "/bulkmail/categorylist/", "{intl-category_list}" ),
    array( "/bulkmail/templatelist/", "{intl-templates}" ),
    array( "/bulkmail/drafts/", "{intl-drafts}" ),
    array( "/bulkmail/mailedit/", "{intl-new_mail}" ),
    array( "/bulkmail/templateedit/", "{intl-new_template}" ),
    array( "/bulkmail/userlist/", "{intl-user_list}" )
    );

}
else
{
$menuItems = array(
    array( "/bulkmail/categorylist/", "{intl-category_list}" ),
    array( "/bulkmail/templatelist/", "{intl-templates}" ),
    array( "/bulkmail/drafts/", "{intl-drafts}" ),
    array( "/bulkmail/mailedit/", "{intl-new_mail}" ),
    array( "/bulkmail/templateedit/", "{intl-new_template}" ),
    array( "/bulkmail/masssubscribe/", "{intl-mass_subscribe}" ),
    array( "/bulkmail/userlist/", "{intl-user_list}" )
    );
}

?>
