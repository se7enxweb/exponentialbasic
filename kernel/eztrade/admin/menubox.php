<?php
// 
// $Id: menubox.php 7383 2001-09-21 14:31:50Z bf $
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
    array( "/trade/orderlist/", "{intl-orderlist}" ),
    array( "/trade/categorylist/", "{intl-categorylist}" ),
 //   array( "/trade/productedit/", "{intl-newproduct}" ),
    array( "/trade/customerlist/", "{intl-customerlist}" ),
    array( "/trade/categorylist/", "{intl-categorylist}" ),

    array( "/trade/categorylist/parent/3/", "{intl-manufacturers}" ),
    array( "/trade/categorylist/parent/133/", "{intl-product_type}" ),
    array( "/trade/categorylist/parent/81/", "{intl-mybike}" ),
    array( "/trade/categorylist/parent/312/", "{intl-bulk_import}" ),

    array( "/trade/typelist/", "{intl-typelist}" ),
    array( "/trade/vattypes/", "{intl-vattypes}" ),
    array( "/trade/boxtypes/", "{intl-boxtypes}" ),
    array( "/trade/shippingtypes/", "{intl-shippingtypes}" ),
    array( "/trade/currency/", "{intl-currency}" ),
    array( "/trade/pricegroups/list", "{intl-pricegroups}" ),
    array( "/trade/categoryedit/", "{intl-newcategory}" ),
    array( "/trade/typeedit/", "{intl-newtype}" ),
    array( "/trade/productedit/", "{intl-newproduct}" ),
    array( "/trade/voucher/", "{intl-newvoucher}" ),
    array( "/trade/voucherlist/", "{intl-voucher_list}" ),
    array( "", "&nbsp;" ),
    array( "/trade/export/froogle", "{intl-feed_froogle}" ),
    array( "/trade/export/froogle/download", "{intl-feed_froogle_download}" ),
    array( "/trade/export/yahoo", "{intl-feed_yahoo}"),
    array( "/trade/export/yahoo/download", "{intl-feed_yahoo_download}" )
	);

/*
 array( "/trade/export/", "{intl-export_froogle}" ),
 array( "/trade/export/froogle", "{intl-export_froogle}" )
 array( "/trade/export/froogle", "{intl-export_froogle}" ),
 array( "/trade/export/yahoo", "{intl-export_yahoo}"),
*/

?>
