<?php
// 
// $Id: sendmail.php 6927 2001-09-04 12:06:17Z jhe $
//
// Created on: <14-Aug-2001 15:43:17 jhe>
//
// This source file is part of eZ publish, publishing software.
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

// include_once( "ezcontact/classes/ezcompany.php" );
// include_once( "ezcontact/classes/ezperson.php" );

$toArray = array();

if ( isset( $ContactArrayID ) )
{
    foreach ( $ContactArrayID as $contact )
    {
        if ( $CompanyEdit )
            $contact = new eZCompany( $contact );
        else
            $contact = new eZPerson( $contact );
        $onlines = $contact->onlines();
        $onlineCount = count( $onlines );
        $loop = true;
        $i = 0;
        while ( $i < $onlineCount && $loop )
        {
            $onlineType = $onlines[$i]->onlineType();
            if ( $onlineType->urlPrefix() == "mailto:" )
            {
                $toArray["Email"][] = $onlines[$i]->url();
                $toArray["ID"][] = $contact->id();
                $loop = false;
            }
            $i++;
        }
    }
}
$toArray["CompanyEdit"] = $CompanyEdit;
include( "kernel/ezmail/user/mailedit.php" );

?>