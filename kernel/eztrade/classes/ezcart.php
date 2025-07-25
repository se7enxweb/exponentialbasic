<?php

use function PHPUnit\Framework\isEmpty;
//
// $Id: ezcart.php 9276 2002-02-26 14:41:12Z ce $
//
// Definition of eZCart class
//
// Created on: <25-Sep-2000 11:23:17 bf>
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
//!! eZTrade
//! eZCart handles a shopping cart
/*!

  Example:
  \code

  // Create a new cart
  $cart = new eZCart();
  $cart->setSession( $session );

  // Store the cart to the database
  $cart->store();

  // Fetch all cart items
  $items = $cart->items();

  // print contents of the cart if it exists
  if  ( $items )
  {
      foreach ( $items as $item )
      {
          $product = $item->product();
          print( $product->name() . "<br>");
      }
  }

  \endcode
  \sa eZCartItem eZProductCategory eZOption
*/


// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezcartitem.php" );
// include_once( "eztrade/classes/ezshippingtype.php" );

//include_once( "eztrade/classes/ezproduct.php" );
//include_once( "eztrade/classes/ezboxtype.php" );
//include_once( "eztrade/classes/ezcartitem.php" );

class eZCart
{
    /*!
      Constructs a new eZCart object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = "" )
    {
        $this->PersonID = 0;
        $this->CompanyID = 0;
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a cart to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_Cart" );
            $nextID = $db->nextID( "eZTrade_Cart", "ID" );

            $res = $db->query( "INSERT INTO eZTrade_Cart
                                ( ID,
                                  SessionID,
                                  PersonID,
                                  CompanyID )
                                VALUES
                                ( '$nextID',
                                  '$this->SessionID',
                                  '$this->PersonID',
                                  '$this->CompanyID' )
                               " );
            $db->unlock();

            $this->ID = $nextID;
        }
        else
        {
            $query = "UPDATE eZTrade_Cart SET
                                SessionID='$this->SessionID',
                                PersonID='$this->PersonID',
                                CompanyID='$this->CompanyID'
                                WHERE ID='$this->ID'";
            $res = $db->query( $query );
        }

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Returns the person we are shopping for
    */
    function personID()
    {
        return $this->PersonID;
    }

    /*!
      Returns the company we are shopping for
    */
    function companyID()
    {
        return $this->CompanyID;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_Cart WHERE ID='$id'" );
           

//qcomp AMin.

	    $db->array_query( $cartshipoptions_array, "SELECT * FROM eZTrade_CartShipOptions WHERE CartID='$id'" );

//End qcomp AMin.

            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName( "ID" )];
                $this->SessionID = $cart_array[0][$db->fieldName( "SessionID" )];
                $this->PersonID = $cart_array[0][$db->fieldName( "PersonID" )];
                $this->CompanyID = $cart_array[0][$db->fieldName( "CompanyID" )];
//qcomp AMin.


if ( count( $cartshipoptions_array ) == 1 )
{
  $this->ShipServiceCode = $cartshipoptions_array[0][$db->fieldName( "ServiceCode" )];
  $this->AddressID = $cartshipoptions_array[0][$db->fieldName( "AddressID" )];
}

//End qcomp AMin.

                $ret = true;
            }
        }
        return $ret;
    }


    /*!
      Returns a eZCart object.
    */
    function getBySession( $session  )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( is_a( $session, "eZSession" ) )
        {
            $sid = $session->id();

            $query = "SELECT * FROM eZTrade_Cart WHERE SessionID='$sid'";

            $db->array_query( $cart_array, $query );

            if ( count( $cart_array ) == 1 )
            {
                $ret = new eZCart( $cart_array[0][$db->fieldName( "ID" )] );
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZCart object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $items = $this->items();

        if ( $items )
        {
            $i = 0;
            foreach ( $items as $item )
            {
                $item->delete();
            }
        }

        $res = $db->query( "DELETE FROM eZTrade_Cart WHERE ID='$this->ID'" );
	$res2 = $db->query( "DELETE FROM eZTrade_CartShipOptions WHERE CartID='$this->ID'" );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

	if ( $res2 == false )
            $db->rollback( );
        else
	{
            $db->commit();
	}

        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Sets the session the cart belongs to.

      Return false if the applied argument is not and eZSession object.
    */
    function setSession( &$session )
    {
        if ( is_a( $session, "eZSession" ) )
        {
            $this->SessionID = $session->id();
        }
    }

    /*!
      Sets the person we are shopping for
    */
    function setPersonID( $userID )
    {
        if ( is_a( $userID, "eZUser" ) )
            $id = $userID->ID();
        else
            $id = $userID;
        if ( is_numeric( $id ) )
        {
            $this->PersonID = $id;
        }
    }


    /*!
      Sets the ship service code
    */

    function setShipServiceCode( $code )
    {
       $this->currentTypeID[0] = $code;
       $this->ShipServiceCode = $code;
    }


    /*!
      Sets the company we are shopping for
    */
    function setCompanyID( $id )
    {
        if ( is_numeric( $id ) )
        {
            $this->CompanyID = $id;
        }
    }

    /*!
      Returns all the cart items in the cart.

      An array of eZCartItem objects are retunred if successful, an empty array.
    */
    function items( )
    {
       $db =& eZDB::globalDatabase();

       $ret = array();
       $query = "SELECT * FROM
                                       eZTrade_CartItem
                                       WHERE CartID='$this->ID'";

       $db->array_query( $cart_array, $query );

       if ( count( $cart_array ) > 0 )
       {
           foreach ( $cart_array as $item )
           {
               $ret[] = new eZCartItem( $item[$db->fieldName( "ID" )] );
           }
       }

       if ( $ret )
           return $ret;
       else
           return array();
    }

    /*
        This function calculates the totals of the cart contents.
     */

    function cartTotals( &$tax, &$total, $voucher=false,$checkout=false, $calcVAT=false)
    {
        $ini =& INIFile::globalINI();
	
        // $checkups = $ini->read_var( "eZTradeMain", "UPSOFF" );
        $upscheck = $ini->read_var( "eZTradeMain", "UPSXMLShipping" ) == 'enabled'?1:0;
        $uspscheck = $ini->read_var( "eZTradeMain", "USPSXMLShipping" ) == 'enabled'?1:0;

        $this->userFreeShipping = $ini->read_var( "eZTradeMain", "FreeShippingUser" );
        $this->dealerFreeShipping = $ini->read_var( "eZTradeMain", "FreeShippingDealer" );
        $this->singleBoxMaxWeight = $ini->read_var( "eZTradeMain", "SingleBoxMaxWeight" );
        $this->shippingMarkupPct = $ini->read_var( "eZTradeMain", "ShippingMarkupPct" );
        $this->shippingMarkupFlat = $ini->read_var( "eZTradeMain", "ShippingMarkupFlat" );
              
        $tax = array();
        $total = array();
        $products = false;
        $totalExTax = 0;
        $totalIncTax = 0;
        $shippingCost = 0;
        $shippingVAT = 0;
        $shippingVATPercentage = "";

        $items = array();
        $voucher = false;

    
        if ( !$voucher )
        {
            $items = $this->items();

            foreach( $items as $item )
            {
                $itemquantity = $item->Count;

                $product =& $item->product();
                $vatPercentage = $product->vatPercentage();
                
                $tax["$vatPercentage"] = array();
                $tax["$vatPercentage"]["basis"] = false;
                $tax["$vatPercentage"]["tax"] = false;
                $tax["$vatPercentage"]["percentage"] = false;

                $exTax = $item->correctPrice( true, true, false );

                if ($calcVAT)
                        $incTax = $item->correctPrice( true, true, true );
                else
                  $incTax = $exTax;

                if ( $product->productType() != 2 )
                {
                    $products = true;
                }
                else
                {
                    $info =& $product->voucherInformation();

                    if ( $info->mailMethod() == 2 )
                        $products = true;
                }

                $totalExTax += $exTax;
                $totalIncTax += $incTax;

                $tax["$vatPercentage"]["basis"] += $exTax;
                $tax["$vatPercentage"]["tax"] += $incTax - $exTax;
                $tax["$vatPercentage"]["percentage"] = $vatPercentage;
                    }
                }
                else if ( is_a ( $voucher, "eZVoucher" ) )
                {
                    $product =& $voucher->product();
                    $vatPercentage = $product->vatPercentage();

                    $exTax = $voucher->correctPrice( false );
                    $incTax = $voucher->correctPrice( true );


                    $totalExTax += $exTax;
                    $totalIncTax += $incTax;

                    $tax["$vatPercentage"]["basis"] += $exTax;
                    $tax["$vatPercentage"]["tax"] += $incTax - $exTax;
                    $tax["$vatPercentage"]["percentage"] = $vatPercentage;
                }


                $total["subinctax"] = $totalIncTax;
                $total["subextax"] = $totalExTax;
                $total["subtax"] = $totalIncTax - $totalExTax;


                if ( $products == true )
                {
                    $currentTypeID = eZHTTPTool::getVar( "ShippingTypeID" );
            
            if ( isset($currentTypeID[0] ) && is_numeric ( $currentTypeID[0] )
                && !$upscheck 
                && !$uspscheck	)
                    {
              $shippingType = new eZShippingType( $currentTypeID[0] );
                    }
                    else
                    {
                        $type = new eZShippingType( );
                        $shippingType =& $type->defaultType();
                    }
                    $shippingCost = $this->shippingCost( $shippingType );
                    $shippingVAT = $this->shippingVAT( $shippingType );
                    $shippingVATPercentage = $this->extractShippingVATPercentage( $shippingType );

            ///////////////////////////////////////////////////////
            //print_r($shippingVATPercentage); exit();
                }

                if ( $shippingVATPercentage == "" || !$calcVAT)
                    $shippingVATPercentage = 0;

          // $shippingVATPercentage = 6;

                $user =& eZUser::currentUser();
                $useVAT = true;

          if ( $shippingVATPercentage != 0 
              && !$upscheck
              && !$uspscheck
              )
          {
            $tax["$shippingVATPercentage"]["basis"] += $shippingCost - $shippingVAT;
            $tax["$shippingVATPercentage"]["tax"] += $shippingVAT;
            $tax["$shippingVATPercentage"]["percentage"] = $shippingVATPercentage;
          }
          
          //RS        $total["shipinctax"] = $shippingCost;
          //RS        $total["shipextax"] = $shippingCost - $shippingVAT;
          

          $total["shipinctax"] = $shippingCost + $shippingVAT;
          $total["shipextax"] = $shippingCost;
          $total["shiptax"] = $shippingVAT;

          $total["inctax"] = $total["subinctax"] + $total["shipinctax"];
          $total["extax"] = $total["subextax"] + $total["shipextax"];
          $total["tax"] = $total["subtax"] + $total["shiptax"];
          
        //Qcomp Codes Start here.

        /////////////////////////////////////////////////////////
        //Ups service,USPS service calculations start here 

        // include_once( "ezuser/classes/ezuser.php" );
        // include_once( "eztrade/classes/ezboxtype.php" );
        // include_once( "ezaddress/classes/ezaddress.php" );

        $getnames =array ( 
            '01' => 'UPS Next Day Air',
            '02' => 'UPS 2nd Day Air',
            '03' => 'UPS Ground',
            '07' => 'UPS Worldwide Express',
            '08' => 'UPS Worldwide Expedited',
            '11' => 'UPS Standard',
            '12' => 'UPS 3 Day Select',
            '13' => 'UPS Next Day Air Saver',
            '14' => 'UPS Next Day Air Early A.M.',
            '54' => 'UPS Worldwide Express Plus',
            '59' => 'UPS 2nd Day Air A.M.',
            '64' => '',
            '65' => 'UPS Express Saver',
          );

        $arrcount=0;
        $withoutbox=0;

        $boxarr = array();
        $totalarr = array();


        ///////////////////////////////////////////////////////
        //if both(UPS,USPS) is disabled,then get default value.

        if(($upscheck==0)&&($uspscheck==0))
          $checkups=0;
        else
          $checkups=1;

        // START UPS CHECK
        if($checkups==1)
        {
          // include_once( "eztrade/classes/ezupsservice.php" );
          // include_once( "eztrade/classes/ezusps.php" );
          
          // Depricated: $uspsserver = "http://production.shippingapis.com/ShippingAPI.dll";

          $uspsserver = $ini->read_var( "site", "UserUSPSServer" );
          $uspsuser = $ini->read_var( "site", "UserUSPS" );
          $uspspass = $ini->read_var( "site", "PassUSPS" );
          
          $user =& eZUser::currentUser();

          if($user)
          {
            $id = $user->id();
            $isaddress = $user->addresses($id);

            if ($this->ShipServiceCode)
            {
              $currentTypeID[0] =$this->ShipServiceCode;
            }

            if ($this->AddressID)
            {
              $shipaddressID  =$this->AddressID;
            }

            if ((eZHTTPTool::getVar( "ShippingTypeID" )) || (eZHTTPTool::getVar( "ShippingAddressID" )))
            {
              $shipaddressID = eZHTTPTool::getVar("ShippingAddressID");
              $currentTypeID = eZHTTPTool::getVar( "ShippingTypeID" );

              $this->AddressID=$shipaddressID;
              $this->ShipServiceCode=$currentTypeID;

              if( isset( $currentTypeID ) )
              {
                $this->storeshipoptions($currentTypeID[0],$shipaddressID);      
              }
            }

            // graham : debug
            // echo $currentTypeID[0]." hh ".$shipaddressID."<br />";
            
            if( $isaddress )
            {
                if( !empty( $shipaddressID ) )
          {
            // include_once("ezaddress/classes/ezaddress.php");

            $ezadd = new eZAddress($shipaddressID);

            if($ezadd)
            {
              $zip = $ezadd->zip();
              $country = $ezadd->country();
              $countryiso = $country->iso();
              $countryname = $country->name();
              $region = $ezadd->region();
              $regionAbb = $region->Abbreviation();
            }
            else
            {
              $addpoint = $isaddress[0];
              $zip = $addpoint->zip();
              $country = $addpoint->country();

              $countryiso = $country->iso();
              $countryname = $country->name();
              $region = $addpoint->region();
              $regionAbb = $region->Abbreviation();

            }
          }
          else
          {
            $addpoint = $isaddress[0];
            $zip = $addpoint->zip();
            $country = $addpoint->country();
            $countryiso = $country->iso();
            $countryname = $country->name();

            // graham : 2005-05-27 : without this check : non-us countries 
                  // without a region cause the cart to crash
            // this is a bug fix (undocumented) to bob sims work

            if ( $addpoint->region() )
              $region = $addpoint->region();
            
            if ( $region )
              $regionAbb = $region->Abbreviation();
          }

          $items = $this->items();
          $getcartid =$this->id();
          
          // This will be incremented when $this->singleBoxMaxWeight is exceeded, representing another box.
          $curboxnum = 0; 

          $skipground = true;

          foreach($items as $item)
          {
            $getcount = $item->count();
            $proobj = $item->product();
            $proid = $proobj->id();
            
          // echo "<br>CURBOXNUM : $curboxnum ID: $proid QUANT: $getcount WEIGHT: $proweight SINGLE: " .$proobj->weight() ."<br>";
            
            $proboxtypeID = $proobj->BoxTypeID();
            
            if ($proboxtypeID>0) {
              $proweight = $proobj->weight();
            } else {
              $proweight = $proobj->weight() * $getcount;
            }

            $proboxtype = new eZBoxType($proboxtypeID);
            $proflatups = $proobj->flatups();
            $proflatusps = $proobj->flatusps();
            $procombineflat = $proobj->flatcombine();

            // 
            $getlength = $proboxtype->length();
            $getwidth =  $proboxtype->width();
            $getheight =  $proboxtype->height();
            
            if ($procombineflat || $proflatups == 'off')
              $skipground = false;
            if ($procombineflat || $proflatups == 'off')
              $skipparcel = false;
            if($proboxtypeID>0)
            {
              $boxarr[$arrcount] =array("weight"=>$proweight,"length"=>$getlength,"height"=>$getheight,"width"=>$getwidth,"count"=>$getcount, 'flatups'=>$proflatups, 'flatusps'=>$proflatusps, 'combineflat'=>$procombineflat,); 
            }
            else
            {
              $without = array();
              $without[$curboxnum] = array();
              $without[$curboxnum]['weight'] = false;
              $without[$curboxnum]['count'] = false;


              if ($proweight > $this->singleBoxMaxWeight ) {
            //echo "$proweight is > $this->singleBoxMaxWeight<br>";
              $loopNum = floor($proweight / $this->singleBoxMaxWeight);
              // add to any box going
              if ( ($this->singleBoxMaxWeight - $without[$curboxnum]['weight']) > 0) {
                  $proweight = $proweight - ($this->singleBoxMaxWeight - $without[$curboxnum]['weight']);
                $without[$curboxnum]['weight'] = $this->singleBoxMaxWeight;
                $without[$curboxnum]['count'] += $getcount;
                $without[$curboxnum]['flatups'] = $proflatups;
                $without[$curboxnum]['flatusps'] = $proflatusps;
                $without[$curboxnum]['combineflat'] = $procombineflat;
                }
              for ($l=0; $l<$loopNum; $l++) {
                if ($proweight - $this->singleBoxMaxWeight > 0) {
                  $curboxnum++;
            
                  $without[$curboxnum]['weight'] += $this->singleBoxMaxWeight;
                    $without[$curboxnum]['count'] += $getcount;
                  $without[$curboxnum]['flatups'] = $proflatups;
                  $without[$curboxnum]['flatusps'] = $proflatusps;
                  $without[$curboxnum]['combineflat'] = $procombineflat;
                  $proweight = $proweight - $this->singleBoxMaxWeight;
                }
                
              }
              $curboxnum++;
              }
            
              if ( isset($without[$curboxnum]['weight']) && ($without[$curboxnum]['weight'] + $proweight) > $this->singleBoxMaxWeight && isset($without[$curboxnum]['weight']) )
              { 
                // echo $without[$curboxnum]['weight'] . ' + ' . $proweight .' is more than ' . $this->singleBoxMaxWeight;
                $curboxnum++;
              }

            $without[$curboxnum]['weight'] += $proweight;
            $without[$curboxnum]['count'] += $getcount;
            $without[$curboxnum]['flatups'] = $proflatups;
            $without[$curboxnum]['flatusps'] = $proflatusps;
            $without[$curboxnum]['combineflat'] = $procombineflat; 
            }
            $arrcount +=1;
          }
          /*
            ### DEBUG STUFF
            #for ($i = 0; $i<sizeof($without); $i++) {
            #	echo("Box $i : " . $without[$i]['weight'].'<br>');
            #}
              ###
          */
          // $without = array("weight"=>$withoutbox,"count"=>$totcount);

          $totalarr = array( "box" => $boxarr );
          if( isset($withoutbox) )
          {
              $totalarr["withoutbox"] = $withoutbox;  
          }
          // graham : 2005-05-24 : this gets the shipping types for UPS
          // if UPS is disabled,these codes are skipped.
          if($upscheck==1)
          {  
            $upsclass = new ezups();   
            $upsresults = $upsclass->ezupsser("$id","02","$regionAbb","$zip","$countryiso",$totalarr);  
          
            foreach($upsresults as $upsnullvalue){
              if(($upsnullvalue==0)||($upsnullvalue==0.00)||($upsnullvalue=='0')||($upsnullvalue=='0.00')){
                $upsresults=array();
              }
            }
          }
          else
            $upsresults=array();
          

          // if USPS is disabled,these codes are skipped.
          if($uspscheck==1)
          {
            $uspsclass = new ezuspsservices();
            // graham : question : why is State & Zip hard coded to PA instead of RI / ini variable ?
            $uspsres = $uspsclass->ezuspsget($uspsserver,$uspsuser,$uspspass,$totalarr,"10","$zip","$regionAbb","$countryname","19004","PA","US","", "true");

            // print_r( '>>'. $uspsres .'<<');
            // print_r( $countryname );
            /*
            if ( !in_array("", $uspsres) )
              die("sorry, please try again...");
            */
                  
            if( !empty($uspsres) )
            {
              foreach($uspsres as $uspsnullvalue)
              {
                if(($uspsnullvalue==0)||($uspsnullvalue=='0')||($uspsnullvalue==0.00)||($uspsnullvalue=='0.00')){
            $uspsres=array();
                }
              }
            }

          }
          else
            $uspsres=array();
  
          // print_r( $uspsres );
          if( is_array($upsresults) && is_array($uspsres) )
              $upsresults=array_merge($upsresults,$uspsres);
          else
              $upsresults=array();
          $res = asort($upsresults);
          
          //$currentTypeID = eZHTTPTool::getVar( "ShippingTypeID" );
          
          $i=1;
          // print_r($shippingVAT); exit();
          
          $ugroups = $user->groups();
          $isdealer = false;
          foreach ($ugroups as $curgroup)
          {
            if ($curgroup->Name == 'Dealers')
              $isdealer = true;
          }
          if($this->shippingMarkupPct)
              $upsresults = $this->applyPctShippingMarkup ( $upsresults );
          if ($this->shippingMarkupFlat)
            $upsresults = $this->applyFlatShippingMarkup ( $upsresults );
          if ($isdealer = true && $this->dealerFreeShipping > 0) {
            if ($total['subextax'] >= $this->dealerFreeShipping)
              $upsresults = $this->applyFreeShipping( $upsresults );
          }
          if (!$isdealer && $this->userFreeShipping > 0)
          {
            if ($total['subextax'] >= $this->userFreeShipping)
              $upsresults = $this->applyFreeShipping( $upsresults );
          }

          foreach($upsresults as $upsr)
          {
              $splups=explode("\|\|",$upsr);
              // echo $splups[1];
              // die();

              if( $i == 1 )
              {
                $first = $splups[0];
              }

              /*
              echo $splups[0];
              die();

              // graham
              //	    print $currentTypeID[0];
              //	    die();
              */
              
              if( !empty( $currentTypeID ) )
              {
                  if( isset( $splups[1] ) && $currentTypeID[0] == $splups[1] )
                  {
                      //eZHTTPTool::setcookie("lastshipID",$shipaddressID);
                      $shippingVAT = $this->extractShippingVAT( $splups[0],$shippingVATPercentage );
                      $total["shipinctax"] =$splups[0]+$shippingVAT;
                      $total["shipextax"] =$splups[0]; //-$shippingVAT;
                      $total["shiptax"] = $shippingVAT;
                      $tax["$shippingVATPercentage"]["basis"] += $splups[0];
                      $tax["$shippingVATPercentage"]["tax"] += $shippingVAT;
                      $tax["$shippingVATPercentage"]["percentage"] = $shippingVATPercentage;
                      
                      break;
                    }
                    else { 
                      // there is a currentTypeID but this isn't it
                      if( $splups[0]< $first ){
                        $first = $splups[0];
                      }

                      $shippingVAT = $this->extractShippingVAT( $first, $shippingVATPercentage );

                      $total["shipinctax"] = $first . $shippingVAT;
                      $total["shipextax"] = (float)$first;              // -$shippingVAT;
                      $total["shiptax"] = $shippingVAT;
                      $tax["$shippingVATPercentage"]["basis"] += (float)$first;
                      $tax["$shippingVATPercentage"]["tax"] += $shippingVAT;
                      $tax["$shippingVATPercentage"]["percentage"] = $shippingVATPercentage;
                      //echo "ddd";
                    }
                }
                else // there is no currentTypeID
                {
              if($i==1){
                $first = $splups[0];}
              else
              {
                if($splups[0]<$first)
                {
                  $first=$splups[0];
                }
                else
                {
                  $first = $first;
                }

                $shippingVAT = $this->extractShippingVAT( $first, $shippingVATPercentage );
                $total["shipinctax"] =$first+$shippingVAT;
                $total["shipextax"] =$first; //-$shippingVAT;
                $total["shiptax"] = $shippingVAT;

                $tax["$shippingVATPercentage"]["basis"] += $first;
                $tax["$shippingVATPercentage"]["tax"] += $shippingVAT;
                $tax["$shippingVATPercentage"]["percentage"] = $shippingVATPercentage;

                //echo "ccc";

                break;
              }
                  }
                $i=$i+1;
              }
              }
            }

                $total["inctax"] = $total["subinctax"] + (float)$total["shipinctax"];
                $total["extax"] = $total["subextax"] + $total["shipextax"];
                $total["tax"] = $total["subtax"] + $total["shiptax"];
            }
        // END UPS CHECK


        if( ($checkout==true ) && ($checkups==1 ))
        {
          // setcookie("last_access_code", $currentTypeID[0]);
          // setcookie("last_access_address", $shipaddressID);
          return $upsresults;
        }
 
   // UPS,USPS Calculations end here.
} // Qcomp codes End here.

    /*!
      Applies a percentage shipping markup from the ini file.
    */
	function applyPctShippingMarkup( $shiplist )
	{
		for ($i=0; $i<sizeof($shiplist); $i++) {
		  $li = explode('\|\|', $shiplist[$i]);
	   	  $li[0] = $li[0] + ( $li[0] / ( 100 / $this->shippingMarkupPct ) );
	   	  $shiplist[$i] = $li[0] . '||' . $li[1];
		}
		return $shiplist;
	}

	function applyFlatShippingMarkup( $shiplist )
	{
		for ($i=0; $i<sizeof($shiplist); $i++)
    {
		  	$li = explode('\|\|', $shiplist[$i]);
	   	  $li[0] = $li[0] . $this->shippingMarkupFlat;
        if( isset( $li[1] ))
            $shiplist[$i] = $li[0] . '||' . $li[1];
        else
            $shiplist[$i] = $li[0];
		}
		return $shiplist;
	}

	function applyFreeShipping( $shiplist )
	{
		  for ($i=0; $i<sizeof($shiplist); $i++) {
		 $li = explode('\|\|', $shiplist[$i]);
	   	  if ($li[1] == '03') { // we may want to switch the service check to an ini var at some point
		   $shiplist[$i] = '0.00' . '||' . $li[1];
		  break;
		  }
		}
		return $shiplist;
	}

/*!
      Calculates the shipping cost with the given shippment type.
      The argument must be a eZShippingType object.
    */
    function shippingCost( $shippingType )
    {
       $items =& $this->items( );
       $ShippingCostValues = array();

       foreach ( $items as $item )
       {
           $product =& $item->product();
           $shippingGroup =& $product->shippingGroup();

           $shippingGroup =& $product->shippingGroup();
           if ( $shippingGroup )
           {
               $values =& $shippingGroup->startAddValue( $shippingType );

               $shipid = $shippingGroup->id();
               if( isset( $ShippingCostValues[$shipid] ) )
               {
                   $count = $item->count() + $ShippingCostValues[$shipid]["Count"];
               }
               else
               {
                   $count = $item->count();
               }

               $ShippingCostValues[$shipid]["Count"] = $count;
               $ShippingCostValues[$shipid]["ID"] = $shipid;
               $ShippingCostValues[$shipid]["Values"] = $values;
           }
       }
       $cost = 0;

       $max = 0;
       $max_id = 0;

       // Find largest start sum first
       foreach( $ShippingCostValues as $value )
       {
           $val = $value["Values"]["StartValue"];
           if ( $val > $max )
           {
               $max = $val;
               $max_id = $value["ID"];
           }
       }
       $cost += $max;
       foreach ( $ShippingCostValues as $value )
       {
           $count = $value["Count"];
           if ( $value["ID"] == $max_id )
               --$count;
           // Add additional values if any
           $cost += $value["Values"]["AddValue"]*$count;
       }

       return $cost;
    }


    /*!
     Obsolete. Use addShippingVAT() or extractShippingVAT() instead.
    */
    function shippingVAT( $shippingType )
    {
       return $this->extractShippingVAT( $shippingType );
    }


    /*!
      Returns the shipping VAT. That is the VAT value
      of the shipping cost.

      The argument must be a eZShippingType object.
    */
    function &extractShippingVAT( $shippingType )
    {
        $shippingVAT = 0;
        if( is_a( $shippingType, "eZShippingType" ) )
        {
            $vatType =& $shippingType->vatType();

            $shippingCost = $this->shippingCost( $shippingType );

            if ( $vatType )
            {
                $value =& $vatType->value();
                $shippingVAT = ( $shippingCost / ( $value + 100  ) ) * $value;
            }
        }
        return $shippingVAT;
    }

    /*!
      Returns the shipping VAT in percentage.

      The argument must be a eZShippingType object.
    */
    function &extractShippingVATPercentage( $shippingType )
    {
        $shippingVAT = 0;
        if( is_a( $shippingType, "eZShippingType" ) )
        {
            $vatType =& $shippingType->vatType();

            $shippingCost = $this->shippingCost( $shippingType );

            if ( $vatType )
            {
                $VATPercentage =& $vatType->value();
            }
        }
        return $VATPercentage;
    }



    /*!
      Returns the VAT value of the product.

      If a value is given as argument this value is used for VAT calculation.
      This is used in carts where you have multiple products and prices on options.
    */
    function &addShippingVAT( $shippingType )
    {
        $shippingVAT = 0;
        if( is_a( $shippingType, "eZShippingType" ) )
        {
            $vatType =& $shippingType->vatType();

            $shippingCost = $this->shippingCost( $shippingType );

            if ( $vatType )
            {
                $value =& $vatType->value();
                $shippingVAT = ( $shippingCost * $value ) / 100;
            }
        }
        return $shippingVAT;
    }


    /*!
      Empties out the cart.
    */
    function clear()
    {
       $db =& eZDB::globalDatabase();
       $db->begin();

       $items = $this->items();

       // delete the option values and cart items
       foreach ( $items as $item )
       {
           $itemID = $item->id();
           $res[] = $db->query( "DELETE FROM
                                eZTrade_CartOptionValue
                                WHERE CartItemID='$itemID'" );

           $res[] = $db->query( "DELETE FROM
                                eZTrade_CartItem
                                WHERE ID='$itemID'" );
       }

       if ( in_array( false, $res ) )
           $db->rollback( );
       else
           $db->commit();

       $this->delete();

    }

//qcomp AMin.

 function storeshipoptions($shipservice,$addressid)
 {
    if (!$shipservice){$shipservice=0;}

    if (!$addressid){$addressid=0;}
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->array_query( $cartshipoptions_array, "SELECT * FROM eZTrade_CartShipOptions WHERE CartID='$this->ID'" );
        $ss = "SELECT * FROM eZTrade_CartShipOptions WHERE CartID='$this->ID'";

    if ( count( $cartshipoptions_array ) == 1 )
    {
        $sqlx="UPDATE eZTrade_CartShipOptions SET ";

        if ($addressid)
        {
            $sqlx=$sqlx." AddressID='".$addressid."',";
        }

        if ($shipservice)
        {
            $sqlx=$sqlx."ServiceCode='".$shipservice."'";
        }

        $sqlx= $sqlx . " WHERE CartID='".$this->ID."'";

        $res = $db->query( $sqlx );
    }
    else
    {
        $db->lock( "eZTrade_CartShipOptions" );
        $nextID = $db->nextID( "eZTrade_CartShipOptions", "ID" );
        $res = $db->query( "INSERT INTO eZTrade_CartShipOptions
                                        ( ID,
                                          AddressID,
                                          ServiceCode,
                                          CartID )
                                        VALUES
                                        ( '$nextID',
                                          '$addressid',
                                          '$shipservice',
                                          '$this->ID' )
                                      " );
          $db->unlock();
      }

      if ( $res == false )

          $db->rollback( );

      else

          $db->commit();

      return true;
    } //End qcomp AMin.

    var $ID;
    var $SessionID;
    var $PersonID;
    var $CompanyID;



//qcomp AMin.

    var $ShipServiceCode;
    var $userFreeShipping;
    var $dealerFreeShipping;
    var $singleBoxMaxWeight;
    var $shippingMarkupPct;
    var $shippingMarkupFlat;

    var $AddressID;

//End qcomp AMin.



}
?>