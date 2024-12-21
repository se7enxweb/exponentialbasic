<?php
//
// $Id: mailview.php 7100 2001-09-08 12:16:19Z ce $
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

// include_once( "ezbulkmail/classes/ezbulkmail.php" );
// include_once( "ezbulkmail/classes/ezbulkmailtemplate.php" );

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBulkMailMain", "Language" ); 

$t = new eZTemplate( "ezbulkmail/user/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                     "ezbulkmail/user/intl/", $Language, "mailview.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_view_page_tpl" => "mailview.tpl"
    ) );


$t->set_var( "site_style", $SiteDesign );

$mail = new eZBulkMail( $MailID );
if( is_object( $mail ) )
{
    $fromString = $mail->fromName() . " &lt;" . $mail->sender() ."&gt;";
    $t->set_var( "current_mail_id", $MailID );
    $t->set_var( "from", $fromString );
    $t->set_var( "subject", $mail->subject() );

    /** check if this mail has a template associated with it **/
    $body = $mail->body();
    $template = $mail->template();
    if( is_object( $template ) )
        $body = $template->header() . $body . $template->footer();
    $t->set_var( "mail_body", nl2br( $body ) );

    $category = $mail->categories();
    if( count( $category ) > 0 )
    {
        $t->set_var( "category", $category[0]->name() );
        $t->set_var( "category_id", $category[0]->id() );
    }
}

$t->pparse( "output", "mail_view_page_tpl" );

?>