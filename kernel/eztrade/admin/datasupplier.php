<?php
//
// $Id: datasupplier.php 9275 2002-02-26 14:28:02Z ce $
//
// Created on: <21-Sep-2000 10:32:36 bf>
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

// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "eztrade/classes/ezproducttool.php" );

// include_once( "ezuser/classes/ezobjectpermission.php" );


$user =& eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZTrade", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "funkyhomo" :
    {

        if ( isset( $AddType ) )
            $Action = "AddType";

        if ( isset( $AddGroup ) )
            $Action = "AddGroup";

        if ( isset( $Store ) )
            $Action = "Store";

        if ( isset( $Delete ) )
            $Action = "DeleteSelected";


        include( "kernel/eztrade/admin/shippingtypes.php" );
    }
    break;
    /*
    case "export" :
    {
      // automated ftp or manual ?
      if ( $url_array[3] == 'froogle' )
      {  
	if ( $url_array[4] == 'download' )
	{
	  $Action = "export";
	} 
	else {
	  // $Action = "export-cron";
	  $Action = "display";
	}

	include( "kernel/eztrade/admin/export_froogle.php" );
      } 
      elseif ( $url_array[3] == 'yahoo' )
      {  
        if ( $url_array[4] == 'download' )
	{
	  $Action = "export";
	}
        else {
          // $Action = "export-cron";
          $Action = "display";
        }

        include( "kernel/eztrade/admin/export_yahoo.php" );
      } 
      elseif ( $url_array[3] == 'download' )
      {  
	$Action = "export";
        include( "kernel/eztrade/admin/export_froogle.php" );
      } 
      else {
	// $Action = "export-cron";
	$Action = "display";
	include( "kernel/eztrade/admin/export_froogle.php" );
      }

      // include( "kernel/eztrade/admin/froogle.php" );
    }
    break;
    */

    case "export" :
    {
      if ( $url_array[3] == 'froogle' )
      {
	if ( $url_array[4] == 'download' )
	{
	  $Action = "export";
        }
	else
	{
	  $Action = "export-cron";
        }
	include( "kernel/eztrade/admin/export_froogle.php" );
      }
      elseif ( $url_array[3] == 'yahoo' )
      {
	if ( $url_array[4] == 'download' )
	{
	  $Action = "export";
        }
	else
	{
	  $Action = "export-cron";
        }
	include( "kernel/eztrade/admin/export_yahoo.php" );
      }
      else {
	$Action = "export-cron";
	include( "kernel/eztrade/admin/export_froogle.php" );
      }
    }
    break;

    case "orderlist" :
    {
        if ( $url_array[3] != "" )
            $Offset = $url_array[3];
        else
            $Offset = 0;
        
        if( isset( $_REQUEST['SortBy'] ) )
            $OrderBy = $_REQUEST['SortBy'];
        else
            $OrderBy = "Date";

        include( "kernel/eztrade/admin/orderlist.php" );
    }
    break;

    case "orderedit" :
        $OrderID = $url_array[3];
        isset( $url_array[4] ) ? $Action = $url_array[4] : $Action = "Edit";
        include( "kernel/eztrade/admin/orderedit.php" );
    break;

    case "orderview" :
    {
        $OrderID = $url_array[3];
        include( "kernel/eztrade/admin/orderview.php" );
    }
    break;

    case "customerlist" :
    {
        if ( $url_array[3] != "" )
            $Offset = $url_array[3];
        else
            $Offset = 0;

        include( "kernel/eztrade/admin/customerlist.php" );
    }
    break;

    case "customerview" :
    {
        $CustomerID = $url_array[3];

        include( "kernel/eztrade/admin/customerview.php" );
    }
    break;


    case "categorylist" :
    {
      if( isset( $DetailView ) && $DetailView ){
        if ( ( $url_array[3] == "parent") && ( $url_array[4] != "" ) )
        {
            $ParentID = $url_array[4];

            if( isset( $url_array[5] ) )
            $Offset = $url_array[5];

            include( "kernel/eztrade/admin/categorylist.php" );
        }
        else
        {
            include( "kernel/eztrade/admin/detailcategorylist.php" );
        }
      }else{
        // else assume ( $NormalView )

	if ( ( $url_array[3] == "parent") && ( $url_array[4] != "" ) )
	{
            $ParentID = $url_array[4];
            if( isset( $url_array[5] ) )
                $Offset = $url_array[5];
            else
                $Offset = 0;
            include( "kernel/eztrade/admin/simplecategorylist.php" );
	}
    else
	{
	  include( "kernel/eztrade/admin/simplecategorylist.php" );
    }
//             include( "kernel/eztrade/admin/categorylist.php" );

      }
    }
        break;

    case "typelist" :
    {
        include( "kernel/eztrade/admin/typelist.php" );
    }
    break;

    case "voucherlist" :
    {
        include( "kernel/eztrade/admin/voucherlist.php" );
    }
    break;

    case "typeedit" :
    {
        if ( $url_array[3] == "edit" )
        {
            $TypeID = $url_array[4];
            $Action = "Edit";
        }
        if ( $url_array[3] == "delete" )
        {
            $TypeID = $url_array[4];
            $Action = "Delete";
        }
        if ( $url_array[3] == "up" )
        {
            $TypeID = $url_array[4];
            $AttributeID = $url_array[5];
            $Action = "up";
        }
        if ( $url_array[3] == "down" )
        {
            $TypeID = $url_array[4];
            $AttributeID = $url_array[5];
            $Action = "down";
        }

        if ( !isset( $Action ) )
        {
            $Action = "New";
        }
        include( "kernel/eztrade/admin/typeedit.php" );
    }
    break;

    case "voucheredit" :
    {
        if ( $url_array[3] == "edit" )
        {
            $VoucherID = $url_array[4];
            $Action = "Edit";
        }
        if ( $url_array[3] == "delete" )
        {
            $VoucherID = $url_array[4];
            $Action = "Delete";
        }
        include( "kernel/eztrade/admin/voucheredit.php" );
    }
    break;

    case "categoryedit" :
        if ( ( $url_array[3] == "insert") )
        {
            $Action = "Insert";
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "edit") )
        {
            $Action = "Edit";
            $CategoryID = $url_array[4];
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "update") )
        {
            $Action = "Update";
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "delete") )
        {
            $Action = "Delete";
            $CategoryID = $url_array[4];
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        else
        {
            $Action = "New";
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        break;

    case "voucher" :
        $UseVoucher = true;
    case "productedit" :
    {
        switch ( $url_array[3] )
        {
		
			   // filelist
             case "filelist" :
                {
                    $ProductID = $url_array[4];
                    include( "kernel/eztrade/admin/filelist.php" );
                    break;
                }
				
            // preview
            case "productpreview" :
                $ProductID = $url_array[4];
                include( "kernel/eztrade/admin/productpreview.php" );
                break;

			//files	
				case "fileedit" :
            {
                if ( isSet( $Browse ) )
                {
                    include( "kernel/ezfilemanager/admin/browse.php" );
                    break;
                }
                switch ( $url_array[4] )
                {
                    case "new" :
                    {
                        $Action = "New";
                        $ProductID = $url_array[5];
                        include( "kernel/eztrade/admin/fileedit.php" );
                    }
                    break;

                    case "edit" :
                    {
                        $Action = "Edit";
                        $ProductID = $url_array[6];
                        $FileID = $url_array[5];
                        include( "kernel/eztrade/admin/fileedit.php" );
                    }
                    break;

                    case "delete" :
                    {
                        $Action = "Delete";
                        $ProductID = $url_array[6];
                        $FileID = $url_array[5];
                        include( "kernel/eztrade/admin/fileedit.php" );
                    }
                    break;

		    default :
                    {
                        include( "kernel/eztrade/admin/fileedit.php" );
                    }
                }
            }
            break;
				

            // Images
            case "imagelist" :
                $ProductID = $url_array[4];
                include( "kernel/eztrade/admin/imagelist.php" );
                break;

            case "imageedit" :
                if ( isset ( $Browse ) )
                {
                    include ( "kernel/ezimagecatalogue/admin/browse.php" );
                    break;
                }
                if ( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $ImageID = $url_array[5];
                    $ProductID = $url_array[6];
                    include( "kernel/eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "delete" )
                {
                    $Action = "Delete";
                    $ImageID = $url_array[5];
                    $ProductID = $url_array[6];
                    include( "kernel/eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "new" )
                {
                    $ProductID = $url_array[5];
                    include( "kernel/eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "storedef" )
                {
                    $Action = "StoreDef";
                    if ( isset( $DeleteSelected ) )
                        $Action = "Delete";
		    if ( isset( $UpdateImages ) )
                        $Action = "UpdateImages";
                    $ProductID = $url_array[5];
                    include( "kernel/eztrade/admin/imageedit.php" );
                }
                else
                {
                    include( "kernel/eztrade/admin/imageedit.php" );
                }

                break;

            // Options
            case "optionlist" :
                $ProductID = $url_array[4];
                include( "kernel/eztrade/admin/optionlist.php" );
                break;

            case "attributeedit" :
            {
                $ProductID = $url_array[4];
                include( "kernel/eztrade/admin/attributeedit.php" );
            }
            break;

            case "formlist":
            {
                $ProductID = $url_array[4];
                include( "kernel/eztrade/admin/formlist.php" );
            }
            break;


            case "link" :
            {
                $ItemID = $url_array[5];
                // include_once( "kernel/eztrade/classes/ezproduct.php" );
                // include_once( "kernel/eztrade/classes/ezproducttool.php" );

                $INIGroup = "eZTradeMain";
                $DefaultSectionsName = "ProductLinkSections";
                $PreferencesSetting = "ProductLinkType";
                $ClientModuleName = "eZTrade";
                $ClientModuleType = "Product";
                $root = "/trade/productedit";
                $URLS = array( "back" => "$root/edit/%s",
                               "linklist" => "$root/link/list/%s",
                               "linkmoveup" => "$root/link/moveup/link/%d/%d/%d",
                               "linkmovedown" => "$root/link/movedown/link/%d/%d/%d",
                               "sectionmoveup" => "$root/link/moveup/section/%d/%d",
                               "sectionmovedown" => "$root/link/movedown/section/%d/%d",
                               "linkselect" => "$root/link/select/%s/%s/%s/%s/%s/0/%s",
                               "linkselect_basic" => "$root/link/select/",
                               "linkselect_std" => "$root/link/select/%s/%s/%s/%s/%s",
                               "urledit" => "$root/link/select/%s/%s/%s/%s",
                               "linkedit" => "$root/link/select/%s/%s/%s/0/0/%s" );
                $Funcs = array( "delete" => "deleteCacheHelper" );

                function deleteCacheHelper( $ProductID )
                    {
                        eZProductTool::deleteCache( $ProductID );
                    }

                switch( $url_array[4] )
                {
                    case "list":
                    {
                        include( "classes/admin/linklist.php" );
                        break;
                    }
                    case "select":
                    {
                        if ( isset( $url_array[6] ) )
                            $ModuleName = $url_array[6];
                        if ( isset( $url_array[7] ) )
                            $Type = $url_array[7];
                        if ( isset( $url_array[8] ) )
                            $SectionID = $url_array[8];
                        if ( isset( $url_array[9] ) )
                            $Category = $url_array[9];
                        if ( isset( $url_array[10] ) )
                            $Offset = $url_array[10];
                        if ( isset( $url_array[11] ) )
                            $LinkID = $url_array[11];

                        include( "classes/admin/linkselect.php" );
                        break;
                    }
                    case "moveup":
                        $MoveUp = true;
                    case "movedown":
                    {
                        if ( isset( $url_array[5] ) )
                            $ObjectType = $url_array[5];
                        if ( isset( $url_array[6] ) )
                            $ItemID = $url_array[6];
                        if ( isset( $url_array[7] ) )
                            $ObjectID = $url_array[7];
                        if ( isset( $url_array[8] ) )
                            $LinkID = $url_array[8];
                        include( "classes/admin/linkmove.php" );
                        break;
                    }
                    default:
                    {
                        eZHTTPTool::header( "Location: /error/404" );
                        break;
                    }
                }
                break;
            }

            case "optionedit" :
                if ( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $OptionID = $url_array[5];
                    $ProductID = $url_array[6];
                    include( "kernel/eztrade/admin/optionedit.php" );
                }
                else if ( $url_array[4] == "delete" )
                {
                    $Action = "Delete";
                    $OptionID = $url_array[5];
                    $ProductID = $url_array[6];
                    include( "kernel/eztrade/admin/optionedit.php" );
                }
                else if ( $url_array[4] == "new" )
                {
                    $Action = "New";
                    $ProductID = $url_array[5];
                    include( "kernel/eztrade/admin/optionedit.php" );
                }
                else
                {
                    include( "kernel/eztrade/admin/optionedit.php" );
                }

                break;

            case "insert" :
                $Action = "Insert";
                include( "kernel/eztrade/admin/productedit.php" );
                break;
            case "edit" :
            {
                $Action = "Edit";
                $ProductID = $url_array[4];
                if( eZObjectPermission::hasPermission( $ProductID, "trade_product", 'w' ) )
                {
                    include( "kernel/eztrade/admin/productedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
            }
            break;
            case "update" :
                $Action = "Update";
                include( "kernel/eztrade/admin/productedit.php" );
                break;

            case "cancel" :
            {
                $Action = "Cancel";
                include( "kernel/eztrade/admin/productedit.php" );
            }
            break;

            case "delete" :
                $Action = "Delete";
                $ProductID = $url_array[4];
                if( eZObjectPermission::hasPermission( $ProductID, "trade_product", 'w' ) )
                {
                    include( "kernel/eztrade/admin/productedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
                break;

            case "voucheredit":
                include( "kernel/eztrade/admin/voucheredit.php" );
                break;
            default:
                include( "kernel/eztrade/admin/productedit.php" );
                break;
        }
    }
        break;

    case "vattypes" :
    {
        if ( isset( $Add ) )
            $Action = "Add";

        if ( isset( $Store ) )
            $Action = "Store";

        if ( isset( $Delete ) )
            $Action = "Delete";

        include( "kernel/eztrade/admin/vattypes.php" );
    }
    break;

    case "boxtypes" :
    {
        if ( isset( $Add ) )
            $Action = "Add";

        if ( isset( $Store ) )
            $Action = "Store";

        if ( isset( $Delete ) )
            $Action = "Delete";

        include( "kernel/eztrade/admin/boxtypes.php" );
    }
    break;

    case "shippingtypes" :
    {
        if ( isset( $AddType ) )
            $Action = "AddType";

        if ( isset( $AddGroup ) )
            $Action = "AddGroup";

        if ( isset( $Store ) )
            $Action = "Store";

        if ( isset( $Delete ) )
            $Action = "DeleteSelected";


        include( "kernel/eztrade/admin/shippingtypes.php" );
        break;
    }

    case "pricegroups":
    {
        $Action = $url_array[3];
        switch( $Action )
        {
            case "list":
            {
                include( "kernel/eztrade/admin/pricegroups.php" );
                break;
            }
            case "new":
            case "edit":
            {
                if ( !isset( $PriceID ) )
                    $PriceID = $url_array[4];
                include( "kernel/eztrade/admin/pricegroupedit.php" );
                break;
            }
        }
        break;
    }

    case "search":
    {
        $Offset = $url_array[3];
        if ( isset( $Query ) )
            $Search = $Query;
        else
            $Search = $url_array[4];
        include( "kernel/eztrade/admin/productsearch.php" );
        break;
    }

    case "currency" :
    {
        if ( isset( $AddCurrency ) )
            $Action = "AddCurrency";

        if ( isset( $Store ) )
            $Action = "Store";

        if ( isset( $Delete ) )
            $Action = "DeleteSelected";


        include( "kernel/eztrade/admin/currency.php" );
        break;
    }

    default :
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

?>