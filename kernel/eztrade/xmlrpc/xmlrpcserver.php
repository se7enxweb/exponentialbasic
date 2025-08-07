<?php
//
// $Id: xmlrpcserver.php 6233 2001-07-20 11:42:02Z jakobn $
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

ob_end_clean();
ob_start();

// include_once( "classes/INIFile.php" );
// include_once( "classes/ezlocale.php" );

// eZ trade classes
// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezorder.php" );
// include_once( "eztrade/classes/ezpreorder.php" );

// eZ user
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );


// include the server
// include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );

// include the datatype(s) we need
// include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcresponse.php" );

// for payment information
// include_once( "eztrade/classes/ezcheckout.php" );


$VersionNumber = "Pre release 1.0";

$server = new eZXMLRPCServer( );

// register functions
$server->registerFunction( "version" );
$server->registerFunction( "newOrders", array( new eZXMLRPCString(), new eZXMLRPCString() ) );

// process the server requests
$server->processRequest();

// implemented functions
function version( )
{
    $VersionNumber = $GLOBALS["VersionNumber"];
    return new eZXMLRPCString( "This is eZ trade xml rpc version: $VersionNumber" );
}

//
// Returns all the new orders and sets them to exported.
//
function &newOrders( $args )
{
    $user = new eZUser();
    $user = $user->validateUser( $args[0]->value(), $args[1]->value() );

    if ( ( is_a( $user, "eZUser" ) ) and eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ) )
    {
        $ini =& eZINI::instance( 'site.ini' );

        $Language = $ini->variable( "eZTradeMain", "Language" );
        $locale = new eZLocale( $Language );

        $orders = array();

        // fetch all new orders
        $order = new eZOrder();

        // perform search
        $orderArray =& $order->getNew( );

        foreach ( $orderArray as $orderItem )
        {
            $preOrder = new eZPreOrder();
            $preOrder->getByOrderID( $orderItem->id() );

            // set the order item to be exported
            $orderItem->setIsExported( true );
            $orderItem->store();

            $datetime =& $orderItem->date();

            $date = $datetime->date();
            $time = $datetime->time();

            $user =& $orderItem->user();

            if ( $user )
            {
                $shippingAddress =& $orderItem->shippingAddress();
                $shippingCountry =& $shippingAddress->country();

                $billingAddress =& $orderItem->billingAddress();
                $billingCountry =& $billingAddress->country();

                $checkout = new eZCheckout();
                $instance =& $checkout->instance();
                $paymentMethod = $instance->paymentName( $orderItem->paymentMethod() );

                $itemArray = array();

                $items = $orderItem->items( $OrderType );

                $shippingUser = $orderItem->shippingUser();

                foreach ( $items as $item )
                {
                    $product = $item->product();

                    $optionValues =& $item->optionValues();

                    foreach ( $optionValues as $optionValue )
                    {
                        $optionArray[] = new eZXMLRPCStruct( array( "OptionName" => new eZXMLRPCString( $optionValue->optionName() ),
                                                                    "OptionValue" => new eZXMLRPCString( $optionValue->valueName() ),
                                                                    "OptionValueRemoteID" => new eZXMLRPCString( $optionValue->remoteID() ) )
                                                             );
                    }


                    $itemArray[] = new eZXMLRPCStruct( array( "ProductID" => new eZXMLRPCInt( $product->id() ),
                                                              "RemoteID" => new eZXMLRPCString( $product->remoteID() ),
                                                              "ProductNumber" => new eZXMLRPCInt( $product->productNumber() ),
                                                              "Name" => new eZXMLRPCString( $product->name() ),
                                                              "Count" => new eZXMLRPCInt( $item->count() ),
                                                              "Price" => new eZXMLRPCDouble( ( $item->price() / $item->count() ) ),
                                                              "TotalPrice" => new eZXMLRPCDouble( ($item->price() ) ),
                                                              "Options" => new eZXMLRPCArray( $optionArray )
                                                              ) );
                }

                $orders[] = new eZXMLRPCStruct(
                    array(
                        "OrderID" => new eZXMLRPCInt( $orderItem->id() ),
                        "PreOrderID" => new eZXMLRPCInt( $preOrder->id() ),
                        "PaymentMethod" => new eZXMLRPCString( $paymentMethod ),
                        "Date" => new eZXMLRPCString( $locale->format( $date ) ),
                        "Time" => new eZXMLRPCString( $locale->format( $time ) ),
                        "ShippingCharge" => new eZXMLRPCDouble( $orderItem->shippingCharge() ),
                        "ShippingFirstName" => new eZXMLRPCString( $shippingUser->firstName() ),
                        "ShippingLastName" => new eZXMLRPCString( $shippingUser->lastName() ),
                        "ShippingStreet1" => new eZXMLRPCString( $shippingAddress->street1() ),
                        "ShippingStreet2" => new eZXMLRPCString( $shippingAddress->street2() ),
                        "ShippingZip" => new eZXMLRPCString( $shippingAddress->zip() ),
                        "ShippingPlace" => new eZXMLRPCString( $shippingAddress->place() ),
                        "ShippingCountry" => new eZXMLRPCString(  $shippingCountry->name() ),
                        "BillingFirstName" => new eZXMLRPCString( $user->firstName() ),
                        "BillingLastName" => new eZXMLRPCString( $user->lastName()  ),
                        "BillingStreet1" => new eZXMLRPCString( $billingAddress->street1() ),
                        "BillingStreet2" => new eZXMLRPCString( $billingAddress->street2() ),
                        "BillingZip" => new eZXMLRPCString( $billingAddress->zip() ),
                        "BillingPlace" => new eZXMLRPCString( $billingAddress->place() ),
                        "BillingCountry" => new eZXMLRPCString(  $billingCountry->name() ),
                        "OrderLines" => new eZXMLRPCArray( $itemArray )
                        ) );
            }
        }

        $tmp = new eZXMLRPCArray( $orders );
    }
    else
    {
        $tmp = new eZXMLRPCResponse( );
        $tmp->setError( 100, "Authorization failed." );
    }

    return $tmp;
}

ob_end_flush();
exit();
?>
