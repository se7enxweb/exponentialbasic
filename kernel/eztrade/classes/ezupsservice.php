<?php
//
// dependancies
// include_once("classes/INIFile.php");
// include_once("classes/ezdb.php");
//
// eZ UPS class

class ezups {
    var $resultArray = array();
    var $xmlarray = array();
    var $xml = "";
    var $adminstate = "";
    var $adminzip = "";
    var $admincountry = "";
    var $userups = "";
    var $accessups = "";
    var $passups = "";
    var $flatservice = "";
    var $service = "";
    var $state = "";
    var $zip = "";
    var $country = "";
    var $totalarr = "";
    var $residential = "";
    var $package_type = "";
    var $emptyarr = "";
    var $addamount = "";
    var $ID = "";
    var $result = "";
    var $request = "";
    var $i = 0;
    function ezupsser($id = "", $service = 01, $state = false, $zip = false, $country = false, $totarr = false)
    {
        if ($id != "") {
            $ini = &eZINI::instance( 'site.ini' );
            $this->adminstate = $ini->variable("eZTradeMain", "Adminstate");
            $this->adminzip = $ini->variable("eZTradeMain", "Adminzip");
            $this->admincountry = $ini->variable("eZTradeMain", "Admincountry");
            $this->userups = $ini->variable("site", "UserUPS");
            $this->accessups = $ini->variable("site", "AccessUPS");
            $this->passups = $ini->variable("site", "PassUPS");
            $this->flatservice = $ini->variable("eZTradeMain", "FlatUPSService");
            $this->totalarr = $totarr;
            $this->ID = $id;
            $this->service = $service;
            $this->state = $state;
            $this->zip = $zip;
            $this->country = $country;
            $this->totalarr = $totarr;
            $this->residential = 0;
            $this->package_type = 02;
            $this->emptyarr = "false";
            // $this->addamount=0;
            $getresult = $this->get();

            return $getresult;
        } 
    } 
    function get()
    {
        $id = $this->ID;
        $service = $this->service;
        $state = $this->state;
        $zip = $this->zip;
        $country = $this->country;
        $totalarr = $this->totalarr;
        $getservice = $this->getservices($state, $country);
        return $getservice;
    } 

    function getservices($sta, $cou)
    {
        $ini = &eZINI::instance( 'site.ini' );
        $emptyarr = array();
        if (strtolower($cou) == "us") {
            if (($sta != "AF") && ($sta != "AA") && ($sta != "AC") && ($sta != "AE") && ($sta != "AM") && ($sta != "AP")) {
                if (($sta != "AK") && ($sta != "HI")) {
                    // $servicearr =array ( '02','03','12');
                    $serviceprearr = $ini->variable("eZTradeMain", "USExcept");

                    $splsitearr = explode(";", $serviceprearr);
                    for($sitecount = 0;$sitecount < count($splsitearr);$sitecount++) {
                        $servicearr[] = $splsitearr[$sitecount];
                    } 
                } else {
                    // $servicearr = array ('01','02');
                    $serviceprearr = $ini->variable("eZTradeMain", "USInclude");

                    $splsitearr = explode(";", $serviceprearr);
                    for($sitecount = 0;$sitecount < count($splsitearr);$sitecount++) {
                        $servicearr[] = $splsitearr[$sitecount];
                    } 
                } 
            } else {
                // If armed forces return nothing.
                return $emptyarr;
            } 
        } else {
            $jointcountry = $cou . "ups";
            $serviceprearr = $ini->variable("eZTradeMain", "$jointcountry");
            if (!empty($serviceprearr)) {
                $splsitearr = explode(";", $serviceprearr);
                for($sitecount = 0;$sitecount < count($splsitearr);$sitecount++) {
                    $servicearr[] = $splsitearr[$sitecount];
                } 
                // $servicearr = array('11');
            } else {
                $serviceprearr = $ini->variable("eZTradeMain", "Defaultups");
                $splsitearr = explode(';', $serviceprearr);
                for($sitecount = 0;$sitecount < count($splsitearr);$sitecount++) {
                    $servicearr[] = $splsitearr[$sitecount];
                } 
            } 
        } 
        $getthis = $this->getxml($servicearr);
        return $getthis;
    } 

    function getxml($service)
    {
        $resultArray = array();
        $inc = 0;
        $j = 0;
        $boxesarr = array();
        $s_state = $this->adminstate;
        $s_zip = $this->adminzip;
        $s_country = $this->admincountry;
        // echo "count service ".count($service);
        $i = 0;
        foreach($service as $ser) {
            $packxml = '';
            $packxml2 = '';

            if ($service == '') {
                $this->request = 'shop';
            } else {
                $this->request = 'rate';
            } 

            $xml = "<?xml version=\"1.0\"?>
<AccessRequest xml:lang=\"en-US\">
<AccessLicenseNumber>$this->accessups</AccessLicenseNumber>
<UserId>$this->userups</UserId>
<Password>$this->passups</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<RatingServiceSelectionRequest xml:lang=\"en-US\">
<Request>
<TransactionReference>
<CustomerContext>Rating and Service</CustomerContext>
<XpciVersion>1.0001</XpciVersion>
</TransactionReference>
<RequestAction>Rate</RequestAction> 
<RequestOption>$this->request</RequestOption> 
</Request>
<PickupType>
<Code>01</Code>
</PickupType>
<Shipment>
<Shipper>
<Address>
<StateProvinceCode>$s_state</StateProvinceCode>
<PostalCode>$s_zip</PostalCode>
<CountryCode>$s_country</CountryCode>
</Address>
</Shipper>
<ShipTo>
<Address>
<StateProvinceCode>$this->state</StateProvinceCode>
<PostalCode>$this->zip</PostalCode>
<CountryCode>$this->country</CountryCode>
<ResidentialAddressIndicator>$this->residential</ResidentialAddressIndicator>
</Address>
</ShipTo>
<Service>
<Code>$ser</Code>
</Service>";
            // <? just a syntax highlighting restart
            $inc = 0;
            $gettotarr = $this->totalarr;
            $boxesarr = $gettotarr['box'];

            if ( isset($gettotarr['withoutbox'] ) && !empty($gettotarr['withoutbox']) ) 
            {
                foreach($boxesarr as $tot)
                {
                    $weight = $tot['weight'];
                    $length = $tot['length'];
                    $height = $tot['height'];
                    $width = $tot['width'];
                    $count = $tot['count'];
                    if (!$count < 1) {
                        while ($inc < $count) {
                            if ($ser == $this->flatservice && is_numeric($tot['flatups']) && $tot['flatups'] == 0) { // don't add it in if it is a free shipping item
                                $inc += 1;
                                continue;
                            } 
                            if ($ser == $this->flatservice && $tot['flatups'] != 'off' && !$tot['combineflat']) {
                                $this->addamount += $tot['flatups'];
                                $inc += 1;
                                continue;
                            } 
                            
                            $packxml .= "<Package>
    <PackagingType>
    <Code>02</Code>
    <Description>Package</Description>
    </PackagingType>
    <Dimensions>
    <UnitOfMeasurement>
    <Code>IN</Code>
    </UnitOfMeasurement>
    <Length>$length</Length>
    <Width>$width</Width>
    <Height>$height</Height>
    </Dimensions>
    <Description>Rate Shopping</Description>
    <PackageWeight>
    <Weight>$weight</Weight>
    </PackageWeight>
    </Package>";

                            $inc += 1;
                        } 
                    } 
                    $inc = 0;
                    $count = 0;
                } 
                $this->nocombine = true; // if $this->nocombine 
                if (!empty($boxesarr)) {
                    foreach ($boxesarr as $tot) {
                        if ($tot['flatups'] == 'off') {
                            $this->nocombine = false;
                        }
                    }
                }
                foreach ($gettotarr['withoutbox'] as $withoutbox) {
                    if ($withoutbox['flatups'] == 'off') {
                        $this->nocombine = false;
                    } 
                } 
                if (isset($gettotarr['withoutbox'][0]['weight'])) {
                    for ($j = 0; $j < sizeof($gettotarr['withoutbox']); $j++) {
                        if ($ser == $this->flatservice && is_numeric($gettotarr['withoutbox'][$j]['flatups']) && $gettotarr['withoutbox'][$j]['flatups'] == 0) continue; // free shipping
                        if ($ser == $this->flatservice && $gettotarr['withoutbox'][$j]['flatups'] != 'off' && !$gettotarr['withoutbox'][$j]['combineflat']) {
                            $this->addamount += $gettotarr['withoutbox'][$j]['flatups'];
                            continue;
                        } 
                        if ($this->nocombine && $ser == $this->flatservice && $gettotarr['withoutbox'][$j]['flatups'] != 'off' && $gettotarr['withoutbox'][$j]['combineflat']) {
                            $this->addamount += $gettotarr['withoutbox'][$j]['flatups'];
                            continue;
                        } 
                        $withoutweight = $gettotarr['withoutbox'][$j]['weight'];
                        $packxml2 .= "<Package>
    <PackagingType>
    <Code>02</Code>
    <Description>Package</Description>
    </PackagingType>
    <Dimensions>
    <UnitOfMeasurement>
    <Code>IN</Code>
    </UnitOfMeasurement>
    <Length>0</Length>
    <Width>0</Width>
    <Height>0</Height>
    </Dimensions>
    <Description>Rate Shopping</Description>
    <PackageWeight>
    <Weight>$withoutweight</Weight>
    </PackageWeight>
    </Package>";
                    } 
                } 
                $withoutbox = 0;
                $lastxml = "<ShipmentServiceOptions/></Shipment></RatingServiceSelectionRequest>";
                // echo ("ser: $ser <br>");
                // echo ("flatservice: $this->flatservice <br>");
                if ($this->nocombine && $ser == $this->flatservice) {
                    if (strlen($this->addamount) < 3 && strlen($this->addamount) > 0) $this->addamount = $this->addamount . '.00';
                    elseif (strlen($this->addamount) < 4 && strlen($this->addamount) > 0) $this->addamount = $this->addamount . '0';
                    $resultArray[$i] = $this->addamount . '||' . $this->flatservice;
                    $i++;
                } else {
                    $xml = $xml . $packxml . $packxml2 . $lastxml;
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, "https://www.ups.com/ups.app/xml/Rate");

                    curl_setopt($ch, CURLOPT_HEADER, 0);

                    curl_setopt($ch, CURLOPT_POST, 1);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");

                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $this->xmlreturndata = curl_exec($ch);

                    $this->xmlarray = $this->_get_xml_array($this->xmlreturndata); 
                    // curl_close($this->xmlarray);
                    curl_close($ch);
                    if ($this->xmlarray == "") {
                        $this->error = 0;

                        $this->errormsg = "Unable to retrieve the Shipping info"; 
                        // return NULL;
                    }

                    if( isset( $this->xmlarray['RatingServiceSelectionResponse']['Response'][0] ) )
                    {
                        $values = $this->xmlarray['RatingServiceSelectionResponse']['Response'][0];

                        if (isset ($values['ResponseStatusCode']) && $values['ResponseStatusCode'] == 0)
                        {
                            $this->error = $values['Error'][0]['ErrorCode'];
                            $this->errormsg = $values['Error'][0]['ErrorDescription']; 
                            // return NULL;
                        } 
                    }
                    
                    $resultArray[$i] = $this->get_rates();
                    $i++;
                } 
            } 
        }
        // die(print_r($resultArray));
        return $resultArray;
    } 

    function _get_xml_array($data)
    {
        $values = array();

        $index = array();

        $array = array();

        $parser = xml_parser_create();

        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);

        xml_parse_into_struct($parser, $data, $values, $index);

        xml_parser_free($parser);

        $i = 0;

        if( isset( $values[$i]['tag'] ) )
            $name = $values[$i]['tag'];
        else
            $name = false;

        if( isset( $values[$i]['attributes'] ) )
        $array[$name] = $values[$i]['attributes'];

        $array[$name] = $this->__get_xml_array($values, $i);

        return $array;
    } 

    function __get_xml_array($values, &$i)
    {
        $child = array();

        if (isset($values[$i]['value']) &&$values[$i]['value'])
            array_push($child, $values[$i]['value']);

        while (++$i < count($values)) {
            switch ($values[$i]['type']) {
                case 'cdata':

                    array_push($child, $values[$i]['value']);

                    break;

                case 'complete':

                    $name = $values[$i]['tag'];

                    $child[$name] = $values[$i]['value'];

                    if ($values[$i]['attributes']) {
                        $child[$name] = $values[$i]['attributes'];
                    } 

                    break;

                case 'open':

                    $name = $values[$i]['tag'];

                    $size = sizeof($child[$name]);

                    if ($values[$i]['attributes']) {
                        $child[$name][$size] = $values[$i]['attributes'];

                        $child[$name][$size] = $this->__get_xml_array($values, $i);
                    } else {
                        $child[$name][$size] = $this->__get_xml_array($values, $i);
                    } 

                    break;

                case 'close':

                    return $child;

                    break;
            } 
        } 
        return $child;
    } 

    function get_rates()
    { 
        // $retArray = array('service'=>'','basic'=>0,'option'=>0,'total'=>0,'days'=>'','time'=>'');
        $retArray = array();
        $servicevalue = false;
        $moneyvalue = false;
        if( isset( $this->xmlarray['RatingServiceSelectionResponse']['RatedShipment'] ) && (int)$this->xmlarray['RatingServiceSelectionResponse']['RatedShipment'] == null )
        {
            $values = $this->xmlarray['RatingServiceSelectionResponse']['RatedShipment'];
            //echo '<pre>';
            //var_dump($this->xmlarray);
            //echo '</pre>';
            if( isset($values) )
            {
                $ct = count($values);
            }
            else {
                $ct = 0;
            }
            
            // echo "count :".$ct;
            for($i = 0;$i < $ct;$i++) {
                $current = &$values[$i];

                $retArray[$i]['service'] = $current['Service'][0]['Code'];

                $retArray[$i]['basic'] = $current['TransportationCharges'][0]['MonetaryValue'];

                $retArray[$i]['option'] = $current['ServiceOptionsCharges'][0]['MonetaryValue'];

                $retArray[$i]['total'] = $current['TotalCharges'][0]['MonetaryValue'];

                $retArray[$i]['days'] = $current['GuaranteedDaysToDelivery'];

                $retArray[$i]['time'] = $current['ScheduledDeliveryTime'];
                $servicevalue = $retArray[$i]['service'];

                if ($this->flatservice == $retArray[$i]['service'] && $this->addamount != '' && !$this->nocombine) {
                    $moneyvalue = $retArray[$i]['total'] + $this->addamount;
                } else {
                    $moneyvalue = $retArray[$i]['total'];
                } 
                // echo "<br>service code :".$servicevalue;
                // echo "<br>cost  :".$moneyvalue;
            } 

            $getvalue = $moneyvalue . "||" . $servicevalue;
            return $getvalue;
        }
    } 

    function setservice($uid, $value)
    {
        $db = &eZDB::globalDatabase();

        $dbError = false;
        $db->begin(); 
        // lock the table
        $db->lock("eZUps_Servicecode");

        $query = "SELECT id FROM eZUps_Servicecode WHERE userid=$uid";

        $db->array_query($value_array, $query);

        if (count($value_array) == 1) {
            $res = $db->query("UPDATE eZUps_Servicecode SET servicecode=$value WHERE userid=$uid");
        } else {
            $nextID = $db->nextID("eZUps_Servicecode", "id");

            $res = $db->query("INSERT INTO eZUps_Servicecode(id,userid,servicecode,value)VALUES($nextID,$uid,$value,$value)");

            if ($res == false)
                $dbError = true;
        } 

        $db->unlock();

        if ($dbError == true)
            $db->rollback();
        else
            $db->commit();
    } 

    function getservice($uid)
    {
        $db = &eZDB::globalDatabase();
        $db->array_query($value_array, "select servicecode from eZUps_Servicecode where userid=$uid");

        $ret = $value_array[$db->fieldName("servicecode")];

        return $ret;
    } 
} 

?>
