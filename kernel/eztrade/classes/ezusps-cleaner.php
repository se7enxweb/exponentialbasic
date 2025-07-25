<?php
// 
// Class: eZ USPS (Services)
// Licence: GNU GPL
//
///////////////////////////////////////////////////

class ezuspsservices
{
    var $server;
    var $user;
    var $pass;
    var $totalarr;
    var $weight;
    var $destzip;
    var $deststate;
    var $destcountry;
    var $orginzip;
    var $state;
    var $country;
    var $mach;
    var $destaddress1;
    var $destaddress2;
    var $container;
    var $size;
    var $service;
    var $pounds;
    var $ounce;
    var $status;    
    var $num;
    var $res;
    var $xmlreturndata;
    var $xmlreturnstatus;
    var $totsize;
    var $count;
    var $height;
    var $width;
    var $length;
    var $machinable;
    var $nocombine;

  function ezuspsget($ser=false,$user=false,$pwd=false,$totarr=false,$wgt="",$dzip=false,$dsta=false,$dcou=false,$ozip=false,$sta=false,$cou=false,$street1=false,$street2=false,$mach="false")
  {
    $this->server = $ser;
    $this->user = $user;
    $this->pass = $pwd;
    $this->totalarr = $totarr;

    //$this->weight = $wgt;

    $this->destzip = $dzip;
    $this->deststate = $dsta;
    $this->destcountry = $dcou;
    $this->orginzip = $ozip;
    $this->state = $sta;
    $this->country = $cou;
    $this->mach = $mach;

    $this->destaddress1 = $street1;
    $this->destaddress2 = $street2;
    
    // $this->container = "Flat Rate Box";
    $this->container = "None";
    
    $this->size = "Oversize"; 
    $this->getservice();

    //$this->getweight();
    //$this->setpounds();
    //$this->setcontainer();
    //$this->setsize();

    $getres = $this->getresult();
    $cc = $this->num;

    // print_r ($getres);

    return $getres;
}


///////////////////////////////////////////////////
function getservice()
{
  $n=0;
  if(($this->destcountry=="United States of America")&&(($this->deststate=="AK")||($this->deststate=="HI")))
  {
    $servicearr[0] = "Parcel";
    $servicearr[1] = "Priority";
    $this->service = $servicearr;
    $this->status = "true";
  }
  elseif(($this->destcountry=="United States of America")&&(($this->deststate=="AF")||($this->deststate=="AA")||($this->deststate=="AC")||($this->deststate=="AE")||($this->deststate=="AM")||($this->deststate=="AP")))
  {
    $servicearr[0] = "Parcel";
    $servicearr[1] = "Priority";
    $this->service = $servicearr;
    $this->status = "true";
  }
  elseif($this->country=="Canada")
  {
    $servicearr[0] = "Airmail Parcel";
    
    $servicearr[1] = "Priority Lg";
    $servicearr[2] = "Priority Sm";
    $servicearr[3] = "Priority Var";
    $this->service = $servicearr;
  }
  elseif(($this->country!="Canada")||($this->country!="United States of America"))
  {
    $servicearr[0] = "Airmail Parcel";
    
    $servicearr[1] = "Priority Lg";
    $this->num = $n;
    $servicearr[2] = "Priority Sm";
    $servicearr[3] = "Priority Var";
    $this->service = $servicearr;
  }
  return $this->service;
}


///////////////////////////////////////////////////
function getweight()
{
  if($this->weight<0.1)
    $this->weight = 0.1;
  else
    $this->weight = $this->weight;
  
  return $this->weight;
}


///////////////////////////////////////////////////
function setpounds()
{
  $fullpounds = $this->weight;
  $this->pounds = floor($this->weight);
  $this->ounce = round(16*($fullpounds-$this->pounds));
  if(empty($this->ounce))
    $this->ounce=0;
}


///////////////////////////////////////////////////
function setcontainer()
{
  // $this->container = "None"; //for Customer Package.
  $this->container = "None";
  // $this->container = "Flat Rate Box";
}


///////////////////////////////////////////////////
function setsize()
{
  $tot = $this->totalarr;
  foreach($tot['box'] as $to)
  {
    $this->height = $to['height'];
    $this->width = $to['width'];
    $this->length = $to['length'];
    $this->count = $to['count'];

    // $this->container = "Flat Rate Box";
    $this->container = "None"; //for Customer Package.

    $this->totsize = $this->length + (2*($this->width+$this->height)); 

    if(($this->totsize<=84)&&($this->weight<15))
      $this->size = "Regular";
    elseif(($this->totsize>84)&&($this->totsize<=108)&&($this->weight<15))
      $this->size = "Large";
    else
      $this->size = "Oversize";
  }
}


///////////////////////////////////////////////////
function verifyaddress() { 
  $str = "API=Verify&XML=<AddressValidateRequest USERID=\""; 
  $str .= $this->user . "\" PASSWORD=\"" . $this->pass . "\">"; 
  $str .= '<Address ID="0"><Address1></Address1>'.$this->destaddress1.'<Address2>'.$this->destaddress2.'</Address2><City>'.$this->destcity.'</City><State>'.$this->deststate.'</State><Zip5>'.$this->destzip.'</Zip5><Zip4></Zip4></Address></AddressValidateRequest>';

  // Curl : Options
  $ch = curl_init(); 
  
  curl_setopt($ch, CURLOPT_URL,"http://production.shippingapis.com/shippingAPI.dll"); 
 
  curl_setopt($ch, CURLOPT_HEADER,0);
  curl_setopt($ch, CURLOPT_POST,1);  
  curl_setopt($ch, CURLOPT_POSTFIELDS,"$str"); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
  
  $this->xmladdressver = curl_exec($ch);

  echo ($this->xmladdressver);

  if (stristr($this->xmladdressver, '<Error>')) {
	return false;
  }

  return true;
}


///////////////////////////////////////////////////
function getresult()
{
  $this->nocombine = true;
  $ratestotal = false;
  $noofservices = 0;

	foreach ($this->totalarr["box"] as $boxflatcheck)
	{
	  if ($boxflatcheck['flatusps'] == 'off') 
	    $this->nocombine = false;
	}
  if( $this->totalarr["withoutbox"] != null)  
  {

    foreach ($this->totalarr["withoutbox"] as $woutflatcheck)
    {
      if ($woutflatcheck['flatusps'] == 'off') 
        $this->nocombine = false;
    }

    if ($this->nocombine) {
      return $this->getFlatResult();
    }
    
    $i=0;
    $response = array();
    $rate = array();
    $arrpush = array();
    $rates = array();
    $this->machinable ="True";
    
    if($this->destcountry=="United States of America")
          {    	
      //  If (!$this->verifyaddress()) {
      //	return false;
      //  }
      // $str = $this->server. "?API=Rate&XML=<RateRequest USERID=\""; 
        
      $str = "API=Rate&XML=<RateRequest USERID=\""; 
      $str .= $this->user . "\" PASSWORD=\"" . $this->pass . "\">"; 
      
      // print_r($this->totalarr[box]);
      
      $cou=0;
      foreach($this->totalarr[box] as $getbox)
      {
        $this->weight = $getbox[weight];
        $this->height = $getbox[height];
        $this->length = $getbox[length];
        $this->width = $getbox[width];
        $this->count = $getbox[count];
        
        $this->flatusps = $getbox['flatusps'];
        $this->combineflat = $getbox['combineflat'];
        $this->totsize = $this->length + (2*($this->width+$this->height)); 
        
        if(($this->totsize<=84)&&($this->weight<15))
          $this->size = "Regular";
        elseif(($this->totsize>84)&&($this->totsize<=108)&&($this->weight<15))
          $this->size = "Large";
        else
          $this->size = "Oversize";
        
        if($this->weight<0.1)
          $this->weight = 0.1;
        else
          $this->weight = $this->weight;
        
        $fullpounds = $this->weight;
        $this->pounds = floor($this->weight);
        $this->ounce = round(16*($fullpounds-$this->pounds));
      
        if(empty($this->ounce))
          $this->ounce=0;
        
        for($cou=0;$cou<$this->count;$cou++)
        {
          $str .= "<Package ID=\"$cou\"><Service>".Parcel . "</Service><ZipOrigination>" . $this->orginzip . "</ZipOrigination>"; 
          $str .= "<ZipDestination>" . $this->destzip . "</ZipDestination>"; 
          $str .= "<Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounce . "</Ounces>"; 
          $str .= "<Container>" . $this->container . "</Container><Size>" . $this->size . "</Size>"; 
          $str .= "<Machinable>" . $this->machinable . "</Machinable></Package>";
        }
      }

      for ($j=0; $j<sizeof($this->totalarr[withoutbox]);$j++)
      {   
        $this->wt = $this->totalarr[withoutbox][$j][weight];
        $this->addamount = 0;

        if (is_numeric($this->totalarr[withoutbox][$j][flatusps]) && $this->totalarr[withoutbox][$j][flatusps] == 0) continue;
        if ($this->totalarr[withoutbox][$j][flatusps] != 'off' && !$this->totalarr[withoutbox][$j][combineflat])
        {
          $this->addamount += $this->totalarr[withoutbox][$j][flatusps];
          continue;
        }
        
        if($this->wt<0.1)
          $this->wt = 0.1;
        else
          $this->wt = $this->wt;
        
        $fullpnds = $this->wt;
        $this->pnds = floor($this->wt);
        $this->oun = round(16*($fullpnds-$this->pnds));
        
        if(empty($this->oun))
          $this->oun=0;
        if(empty($this->pnds)&&empty($this->oun))
          $this->oun=0.1;
        /* 
              $this->withoutcount = $this->totalarr[withoutbox][count];
              $this->withoutcount = $cou + $this->withoutcount;
              $inc = $cou;
        */

        $str .= "<Package ID=\"$inc\"><Service>".Parcel . "</Service><ZipOrigination>" . $this->orginzip . "</ZipOrigination>"; 
        $str .= "<ZipDestination>" . $this->destzip . "</ZipDestination>"; 
        $str .= "<Pounds>" . $this->pnds . "</Pounds><Ounces>" . $this->oun . "</Ounces>"; 
        $str .= "<Container>" . $this->container . "</Container><Size>Regular</Size>"; 
        $str .= "<Machinable>" . $this->machinable . "</Machinable></Package>";
      }
      $str .= "</RateRequest>"; 
    }
    // echo $str;
    
    // ### INTERNATIONAL ##############################################
    if($this->destcountry!="United States of America")
    {
      // $str = "API=IntlRate&XML=<RateRequest USERID=\""; 

      $str = "API=IntlRate&XML=<IntlRateRequest USERID=\""; 
      $str .= $this->user . "\" PASSWORD=\"" . $this->pass . "\">"; 
      
        foreach($this->totalarr["box"] as $getbox)
        {
      $this->weight = $getbox["weight"];
      $this->height = $getbox["height"];
      $this->length = $getbox["length"];
      $this->width = $getbox["width"];
      $this->count = $getbox["count"];
      $this->totsize = $this->length + (2*($this->width+$this->height)); 

      if(($this->totsize<=84)&&($this->weight<15))
        $this->size = "Regular";
      elseif(($this->totsize>84)&&($this->totsize<=108)&&($this->weight<15))
        $this->size = "Large";
      else
        $this->size = "Oversize";
      
      if($this->weight<0.1)
        $this->weight = 0.1;
      else
        $this->weight = $this->weight;
      
      $fullpounds = $this->weight;
      $this->pounds = floor($this->weight);
      $this->ounce = round(16*($fullpounds-$this->pounds));

      if(empty($this->ounce))
        $this->ounce=0;
    
      for($cou=0;$cou<$this->count;$cou++)
      {
        $str .= "<Package ID=\"$cou\"><Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounce . "</Ounces>"; 
        $str .= "<MailType>" ."Package". "</MailType><Country>" . $this->destcountry."</Country>"; 
        $str .= "</Package>";
      }
        }
        if($this->totalarr["withoutbox"]!="")
        {
          for ($j=0; $j<sizeof($this->totalarr["withoutbox"]);$j++)
          {
              $this->wt = $this->totalarr["withoutbox"][$j]["weight"];
              
              if($this->wt<0.1)
                $this->wt = 0.1;
              else
                $this->wt = $this->wt;
              
              $fullpnds = $this->wt;
              $this->pnds = floor($this->wt);
              $this->oun = round(16*($fullpnds-$this->pnds));

              if(empty($this->oun))
                $this->oun=0;
              if(empty($this->pnds)&&empty($this->oun))
                $this->oun=0.1;
                
              $str .= "<Package ID=\"$inc\"><Pounds>" . $this->pnds . "</Pounds><Ounces>" . $this->oun . "</Ounces>"; 
              $str .= "<MailType>" .Package . "</MailType><Country>" . $this->destcountry."</Country>"; 
              $str .= "</Package>";
          }

          $str .="</IntlRateRequest>"; 
        }
        
    }

    
    // ####################################################################################


    ///////////////////////////////////////////////////
    // Curl : Options
    $ch = curl_init(); 
    
    // ini -- UserUSPSServer=http://production.shippingapis.com/ShippingAPI.dll
    $c_server = "http://production.shippingapis.com/shippingAPI.dll";
    curl_setopt($ch, CURLOPT_URL, $c_server); 
  
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_POST,1);  
    curl_setopt($ch, CURLOPT_POSTFIELDS,"$str"); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    
    $this->xmlreturndata = curl_exec($ch);
    $body = $this->xmlreturndata;
    // echo $body;

    if($this->destcountry=="United States of America")
    {
      $noofservices=1;
      while (true) {
        if ($start = strpos($body, '<Package')) {
    $body = substr($body, $start);
    $end = strpos($body, '</Package>');
    $response[] = substr($body, 0, $end+10);
    $body = substr($body, $end+9);
        } else {
    break;
        }
      }
      
      // if ($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY) {
      if (sizeof($response) == '1') {
        if (ereg('<Error>', $response[0])) {
    
    $number = ereg('<Number>(.*)</Number>', $response[0], $regs);
    $number = $regs[1];
    
    $description = ereg('<Description>(.*)</Description>', $response[0], $regs);
    $description = $regs[1];
    
    return array('error' => $number . ' - ' . $description);
        }
      }
          
      $nopackage = sizeof($response);
      
      for ($i=0; $i<$nopackage; $i++) {
        if (strpos($response[$i], '<Postage>')) {
    
    $service = ereg('<Service>(.*)</Service>', $response[$i], $regs);
    $service = $regs[1];
    $postage = ereg('<Postage>(.*)</Postage>', $response[$i], $regs);
    $postage = $regs[1];
    
    $rates[0][0] = $postage+$rates[0][0];
    $rates[1][0] ="USPS $service";
        }
      }
    }
    
    if($this->destcountry!="United States of America")
    {  
      while (true) {
        
        if ($start = strpos($body, '<Package ID=')) {
    $body = substr($body, $start);
    $end = strpos($body, '</Package>');
    $response[] = substr($body, 0, $end+10);
    $body = substr($body, $end+9);
        } else {
    break;
        }
      }
      
      $rates = array();
      
      if (sizeof($response) == '1') {
        if (ereg('<Error>', $response[0])) {
    $number = ereg('<Number>(.*)</Number>', $response[0], $regs);
    $number = $regs[1];
    $description = ereg('<Description>(.*)</Description>', $response[0], $regs);
    $description = $regs[1];
    
    return array('error' => $number . ' - ' . $description);
        }
      }
      
      $nopackage = sizeof($response);
      
      //echo "total count". $n;
      //print_r($response);
      
      for ($j=0; $j<$nopackage; $j++) {
        $body = $response[$i];
        $xs=0;
        $closedx=true;
        while ($closedx) {
    // $xs=$xs+1;
    // if ($xs==6) {break;}
    
    $start = strpos($body, '<Service ID=');
    if ($start===false) {
      $closedx=false;
      break;
    }
    else
    {
      // $body = substr($body, $start);
      // echo $body ."<br><br>";
      $end = strpos($body, '</Service>');
      $end =$end +10;
      $servicex[] = substr($body,$start,$end-$start);
      // echo $servicex[$xs-1]. "- $start - $end <br><br>";
      $body = substr($body, $end);
    } 
        }  
  
        // echo $j;
        // print_r($servicex); echo "<br /><hr />";
      
      $n = sizeof($servicex);
      $noofservices=$n;
      $this->num = $n;
      for ($i=0; $i<$n; $i++) 
      {
          $body = $servicex[$i];
          $bod = $servicex[$i];
          if ($start = strpos($body, '<Postage'))
          {
        $body = substr($body, $start);
        
        $end = strpos($body, '</Postage>');
        $post = substr($body,9,$end-9);
      // echo $post;
        $postvalue[$j]=$postvalue[$j]+$post;
        $body = substr($body, $end+10);
          }
          if ($ss = strpos($bod,'<SvcDescription>'))
          {
        $bod = substr($bod, $ss);
        $end = strpos($bod, '</SvcDescription>');
        $desc = substr($bod,16,$end-16);
        //echo $desc;
        //$totdesc.$i = $totdesc.$i;
        $body = substr($bod, $end+17);
          }
          
          $rates[0][$i]=$post+$rates[0][$i];
          $rates[1][$i]="USPS $desc";
        }
    }
    }


    // building the whole shiping method string
    for ($ij=0;$ij<$noofservices;$ij++) 
    {
      $ratestotal[$ij]=$rates[0][$ij]."||".$rates[1][$ij];
    }

    // graham : static : usps : shippment option limits

      function unset_by_val($needle,&$haystack) {
        // removes all entries in array $haystack,
        // who's value is $needle
        foreach($haystack as $lil_haystack)
        {
    while(($gotcha = array_search($needle,$lil_haystack)) > -1)
      unset($lil_haystack[$gotcha]);
        }
      }
      /*
      $ring = array('gollum','smeagol','gollum','gandalf',
        'deagol','gandalf');
      print_r($ring); echo "<br>";
      unset_by_val('gollum',$ring);
      print_r($ring);
      */


      // the function that will recursivly search an array.
      function searchArrayRecursive($needle, $haystack){

        // loop through the haystack that has been passed in
        foreach ($haystack as $key => $arr) {

    // check to make sure that the element is an array
    if(is_array($arr)) {

      /* this is the tricky line, this will take the value
          or $arr and call the function again each time
          the function is called, $ret is set with the
          return value of the function call, this builds
          the string that get's returned.
      */
      $ret=searchArrayRecursive($needle, $arr);

      /* check to make sure that the function call did
          not return -1 and return the value of the $key and
          the $ret
      */
      if($ret!=-1) return $key.','.$ret;

    } else {

      /* check the array element and see if it matches the
          search term. if it does, return the $key of the
          element.
      */
      if($arr == $needle) return (string)$key;

    }

        }

        // nothing was found, return -1

        return -1;

      }
      
      
    // strip out canada?
    if ( $this->destcountry == "Canada" ) {
      /* Remove / Blank out a single entry
          unset( $rates[1][0] );
      */
      unset( $ratestotal );
      // print_r( $ratestotal );
      // echo "<br /><hr />";
    } elseif ( $this->destcountry == "United States of America" ) {
      /* Remove / Blank out a single entry
          unset( $rates[1][0] );
      */
      unset( $ratestotal );
      // print_r( $ratestotal );
      // echo "<br /><hr />";
    } 
    /*
    elseif ( $this->destcountry != "United States of America" ) {
      //unset( $ratestotal );
      print_r( $ratestotal );
      // echo "<br /><hr />";
    } 
    */
    elseif ( $this->destcountry == "Australia" ) {
      /* Remove / Blank out a single entry
          unset( $rates[1][0] );
      */
      // unset( $ratestotal );
      //print_r( $ratestotal );

      // print_r( $ratestotal[0] ); echo "<br />";
      unset( $ratestotal[0] );

      // print_r( $ratestotal[1] ); echo "<br />";
      unset( $ratestotal[1] );

      // print_r( $ratestotal[2] ); echo "<br />";
      // unset( $ratestotal[2] );

      // print_r( $ratestotal[3] ); echo "<br />";
      // unset( $ratestotal[3] );

      // print_r( $ratestotal[4] ); echo "<br />";
      unset( $ratestotal[4] ); 

      // echo "<br /><hr />";
      
      // print_r( $ratestotal );

      // echo "<br /><hr />";
    } elseif ( $this->destcountry == "Britain" ) {

      print_r( $ratestotal[0] ); echo "<br />";
      unset( $ratestotal[0] );

      print_r( $ratestotal[1] ); echo "<br />";
      unset( $ratestotal[1] );

      print_r( $ratestotal[2] ); echo "<br />";
      // unset( $ratestotal[2] );

      print_r( $ratestotal[3] ); echo "<br />";
      // unset( $ratestotal[3] );

      print_r( $ratestotal[4] ); echo "<br />";
      unset( $ratestotal[4] );

      echo "<br /><hr />";

      print_r( $ratestotal );

    } else {
      // echo $this->destcountry;
      
      print_r( $ratestotal );
      
      // print_r($rates);
      // print_r( $rates[1] );
      // echo "<br /><hr />";
    }  
    
    //exit;
  }
  return $ratestotal;
}


///////////////////////////////////////////////////
function getFlatResult()
{
	if (!empty($this->totalarr[box])) {
	foreach ($this->totalarr[box] as $boxnum => $box) {
		$this->addamount = $this->addamount + $box[flatusps];
	}
	}
	if (!empty($this->totalarr[withoutbox])) {
	foreach ($this->totalarr[withoutbox] as $woutnum => $wout) {
		$this->addamount = $this->addamount + $wout[flatusps];
	}
	}
	if ($this->addamount == 0) {
		return '0.00' . '||' . 'USPS Parcel (Free Shipping)';
	}
	elseif (strlen($this->addamount) < 3)
	{
		$this->addamount = $this->addamount . '.00';
	}
	elseif (strlen($this->addamount) < 4)
	{
		$this->addamount = $this->addamount . '0';
	}
	return array ( 0 => $this->addamount . '||' . 'USPS Parcel (Flat Shipping)'); 

}

}
?>