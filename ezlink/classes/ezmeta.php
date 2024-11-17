<?php
//
// $Id: ezmeta.php 8110 2001-10-31 08:48:33Z jhe $
//
// Created on: <01-Nov-2000 16:44:39 bf>
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

function &fetchURLInfo( $url )
{
    $list = array();
    $fp = @fopen( $url, "r" );
    if ( !$fp )
        $fp = @fopen( $url . "/", "r" );
    if ( $fp )
    {
        $output = fread( $fp, 5000 ); // First 5k should be enough
        fclose( $fp );
        if ( preg_match( "#<title>([^<]+)</title>#i", $output, $regs ) )
        {
            $title = trim( $regs[1] );
            $list["title"] = $title;
        }
        if ( preg_match( "#<meta[ \t\n]+name[ \t\n]*=[ \t\n]*\"abstract\"[ \t\n]+content[ \t\n]*=[ \t\n]*\"([^\"]+)\"[ \t\n\/]*>#i", $output, $regs ) )
        {
            $abstract = trim( $regs[1] );
            $list["abstract"] = $abstract;
        }
        if ( preg_match( "#<meta[ \t\n]+name[ \t\n]*=[ \t\n]*\"description\"[ \t\n]+content[ \t\n]*=[ \t\n]*\"([^\"]+)\"[ \t\n\/]*>#i", $output, $regs ) )
        {
            $description = trim( $regs[1] );
            $list["description"] = $description;
        }
        if ( preg_match( "#<meta[ \t\n]+name[ \t\n]*=[ \t\n]*\"keywords\"[ \t\n]+content[ \t\n]*=[ \t\n]*\"([^\"]+)\"[ \t\n\/]*>#i", $output, $regs ) )
        {
            $keywords = trim( $regs[1] );
            $list["keywords"] = $keywords;
        }
        return $list;
    }
    else
    {
        return false;
    }
}

?>
