<?php
// 
// $Id: missingemailmessage.php 7074 2001-09-07 10:46:54Z pkej $
//
// Created on: <02-Mar-2001 10:19:02 ce>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->variable( "eZUserMain", "Language" ); //SF eZUserMain was eZErrorMain ?? 

$t = new eZTemplate( "kernel/ezuser/user/" . $ini->variable( "eZUserMain", "TemplateDir" ),
                     "kernel/ezuser/user/intl/", $Language, "missingemailmessage.php" );

$t->setAllStrings();
$t->set_file( array( "message" => "missingemailmessage.tpl" ) );

$t->set_block( "message", "mail_sent_tpl", "mail_sent" );
$t->set_block( "message", "user_not_exists_tpl", "user_not_exists" );
$t->set_block( "message", "generated_password_tpl", "generated_password" );                 

$t->pparse( "output", "message" );

?>