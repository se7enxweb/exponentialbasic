<?php
//
// $Id: typelist_pre.php 6436 2001-08-14 14:12:16Z jhe $
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


if ( !isset( $Max ) )
{
    $Max = 10;
}

if ( !isset( $Offset ) )
{
    $Offset = 0;
}
else if ( !is_numeric( $Offset ) )
{
    $Offset = 0;
}

if ( !isset( $SearchText ) )
{
    $SearchText = "";
    $search_encoded = "";
}
else
{
    $search_encoded = $SearchText;
    $search_encoded = eZURITool::encode( $search_encoded );
}

?>