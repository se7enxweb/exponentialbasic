<?php
//
// $Id: content.php 7590 2001-09-29 12:35:15Z kaid $
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

    global $url_array;
    $page = $url_array[3];
    $viewer = $url_array[2];
$str = <<<EOD
<div style="float: right;">
<table cellspacing="0" cellpadding="2" border="0">
<tr>
    <td bgcolor="#bcbcbc">
        <h2>Module Descriptions</h2>
    </td>
</tr>
<tr>
    <td bgcolor="#f0f0f0">
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezad">eZ ad</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezaddress">eZ address</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezbug">eZ bug</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezcalendar">eZ calendar</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezcontact">eZ contact</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezfilemanager">eZ filemanager</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezforum">eZ forum</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezimagecatalogue">eZ imagecatalogue</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezlink">eZ link</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#eznewsfeed">eZ newsfeed</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezpoll">eZ poll</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezstats">eZ stats</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#eztodo">eZ todo</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#eztrade">eZ trade</a><br />
        <a href="$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/article/$viewer/$page/3/#ezuser">eZ user</a><br />
    </td>
</tr>
</table>
</div>
EOD;

if( $viewer != "articleprint" )
{
    echo $str;
}

?>