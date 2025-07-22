<?php
//
// $Id: user.php 6235 2001-07-20 11:45:40Z jakobn $
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

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
if( $Command == "list" ) // Return a list of users and their ID's 
{
    $userList = eZUser::getAll( "Login", true );
    $users = array();
    foreach( $userList as $user )
    {
        $users[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezuser", "user", $user->id() ),
                                              "Name" => new eZXMLRPCString( $user->login( false ) )
                                              )
                                       );
    }
    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => array(),
                                             "Elements" => $users ) );
}
else if( $Command == "data" || $Command == "currentuser" )
{
    $user = 0;
    if( $Command == "data" )
    {
        $user = new eZUser( $ID );
        $cu = false;
    }
    else
    {
        $user = $User;
        $cu = true;
    }

    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezuser", "user", $user->id() ),
                                             "FirstName" => new eZXMLRPCString( $user->firstName( false ) ),
                                             "LastName" => new eZXMLRPCString( $user->lastName( false ) ),
                                             "Login" => new eZXMLRPCString( $user->login( false ) ),
                                             "EMail" => new eZXMLRPCString( $user->email() ),
                                             "InfoSubscription" => new eZXMLRPCBool( $user->infoSubscription() ),
                                             "Signature" => new eZXMLRPCString( $user->signature() ),
                                             "CookieLogin" => new eZXMLRPCBool( $user->cookieLogin() ),
                                             "SimultaneousLogins" => new eZXMLRPCInt( $user->simultaneousLogins() ),
                                             "IsCurrentUser" => new eZXMLRPCBool( $cu )
                                             )
                                      );
}

?>