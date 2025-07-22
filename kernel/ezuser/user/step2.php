<?php

require( "ezuser/user/usercheck.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "classes/eztexttool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$SelectCountry = $ini->read_var( "eZUserMain", "SelectCountry" );
$SelectRegion = $ini->read_var( "eZUserMain", "SelectRegion" );
$AnonymousUserGroup = $ini->read_var( "eZUserMain", "AnonymousUserGroup" );

$AutoCookieLogin = eZHTTPTool::getVar( "AutoCookieLogin" );

$session =& eZSession::globalSession();

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezaddress/classes/ezcountry.php" );
include_once( "ezaddress/classes/ezregion.php" );
include_once( "ezmail/classes/ezmail.php" );



$user =& eZUser::currentUser();
$mainAddress = eZAddress::mainAddress( $user );    
$addressList = $user->addresses();
$form_type = "Account Registration";
if($user){
  $form_type = "Profile";
}

$default_country = 240; // 240 = U.S.
$canada = 39;

$countryList =& eZCountry::getAllArray();
$stateList =& eZRegion::getCountryArray($default_country); 
$castateList =& eZRegion::getCountryArray($canada); 
$stateList = array_merge($stateList, $castateList);

$states = array();
$states_unsorted = array();
foreach($stateList as $state){
  $states[" ".$state['ID']] = $state['Name'];
}
$states_unsorted = $states;
array_multisort($states, SORT_ASC, SORT_STRING);
//print_r($states);
foreach($states as $k=>$v){
  //  print "$k = $states[$k]";
}
$sameaddress_checked = 'checked';

$default_ship_country = $default_country;
$default_bill_country = $default_country;

if($next){

  // Report blank errors
  $error['bill_address1'] = error_check($bill_address1);
  $error['bill_city'] = error_check($bill_city);
  $error['bill_zip'] = error_check($bill_zip);
  $error['bill_phone'] = error_check($bill_phone);

  if(! $sameaddress){
    $error['ship_address1'] = error_check($ship_address1);
    $error['ship_city'] = error_check($ship_city);
    $error['ship_zip'] = error_check($ship_zip);
    $error['ship_phone'] = error_check($ship_phone);
    $sameaddress_checked = '';
  }

  // Check if there were any errors
  foreach($error as $k=>$v){
    if($v == 'red'){
      $errors_ind = 1;
      $all_fields_error = 'please complete all fields';
    }
  }

  $default_bill_state = $bill_state;
  $default_bill_country = $bill_country;
  $default_ship_state = $ship_state;
  $default_ship_country = $ship_country;

  // Add/Edit Address
  if(! $errors_ind){
    //$mainAddress = eZAddress::mainAddress( $user );    
    if($addressList[0]){
      $bill_address = $addressList[0];
    }else{
      $bill_add = 1;
      $bill_address = new eZAddress();
    }

    if($addressList[1]){
      $ship_address = $addressList[1];
    }else{
      $ship_add = 1;
      $ship_address = new eZAddress();
    }


    $bill_address->setStreet1($bill_address1);
    $bill_address->setStreet2($bill_address2);
    $bill_address->setZip($bill_zip);
    $bill_address->setPhone($bill_phone);
    $bill_address->setPlace($bill_city);
    $bill_address->setCountry($bill_country);
    $bill_address->setRegion($bill_state);
    $bill_address->store();
    if($bill_add){
      $user->addAddress($bill_address);
    }

    if(! $sameaddress){
      $ship_address->setStreet1($ship_address1);
      $ship_address->setStreet2($ship_address2);
      $ship_address->setZip($ship_zip);
      $ship_address->setPhone($ship_phone);
      $ship_address->setPlace($ship_city);
      $ship_address->setCountry($ship_country);
      $ship_address->setRegion($ship_state);
      $ship_address->store();
      if($ship_add){
	$user->addAddress($ship_address);
      }
    }
    header("Location: /index.php/user/confirmation/");
  }

}else{
  if($addressList){
    if($addressList[0]){
      $bill_address1 = $addressList[0]->Street1();
      $bill_address2 = $addressList[0]->Street2();
      $bill_city = $addressList[0]->Place();
      $bill_zip = $addressList[0]->Zip();
      $bill_state = $addressList[0]->Region();
      $bill_country = $addressList[0]->Country();
      $bill_phone = $addressList[0]->Phone();
      $default_bill_state = $bill_state->ID();
      $default_bill_country = $bill_country->ID();
    }
    if($addressList[1]){
      $ship_address1 = $addressList[1]->Street1();
      $ship_address2 = $addressList[1]->Street2();
      $ship_city = $addressList[1]->Place();
      $ship_zip = $addressList[1]->Zip();
      $ship_state = $addressList[1]->Region();
      $ship_country = $addressList[1]->Country();
      $ship_phone = $addressList[1]->Phone();
      $default_ship_state = $ship_state->ID();
      $default_ship_country = $ship_country->ID();

      $sameaddress_checked = '';
    }else{
      $sameaddress_checked = 'checked';
      $default_ship_country = $default_country;
    }
  }else{
    $default_bill_country = $default_country;
  }
}


function error_check($varcheck){
  if(!($varcheck && strlen($varcheck) <= 50)){
    return 'red';
  }else{
    return 'black';
  }
}




?>




<div id="breadcrumbs">
&nbsp; 
</div>

<div id="contentWrap">
<h1 class="mainHeading"><?= $form_type ?> - Step 2 of 2</h1>

<form name="todo" method="post">
<p>
<?= $form_type ?> Step 2 is only required to checkout or obtain an
accurate shipping and handling price for items in your shopping cart. All
fields are required.
</p>

<p>
<input type="button" value="Skip This Step >" onClick="window.location='/index.php/user/confirmation/';">
</p>

<br><br>
<table border="0" cellspacing="5" cellpadding="5" class="boxtext" style="font-size=13;">
<tr>
<td colspan="2"><center>Billing</center><br><br></td><td>&nbsp;&nbsp;</td>
<td colspan="2"><center>Shipping</center><br>
<center>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="sameaddress" value="1" <?= $sameaddress_checked ?> onClick="fillShipping();">
<span style="font-weight:normal;">Same as Billing</span>
</center>
</td>
</tr>


<tr>
<td style="color:<?= $error['bill_address1'] ?>;">Address 1: </td><td><input type="text" name="bill_address1" value="<?= $bill_address1 ?>"></td>
<td>&nbsp;&nbsp;</td>
<td style="color:<?= $error['ship_address1'] ?>;">Address 1: </td><td><input type="text" name="ship_address1" value="<?= $ship_address1 ?>"></td>
</tr>

<tr>
<td>Address 2: </td><td><input type="text" name="bill_address2" value="<?= $bill_address2 ?>"></td>
<td>&nbsp;&nbsp;</td>
<td>Address 2: </td><td><input type="text" name="ship_address2" value="<?= $ship_address2 ?>"></td>
</tr>

<tr>
<td style="color:<?= $error['bill_city'] ?>;">City : </td><td><input type="text" name="bill_city" value="<?= $bill_city ?>"></td>
<td>&nbsp;&nbsp;</td>
<td style="color:<?= $error['ship_city'] ?>;">City : </td><td><input type="text" name="ship_city" value="<?= $ship_city ?>"></td>
</tr>

<tr>
<td>State : </td><td>
<select name="bill_state">
<? foreach($states as $k=>$v): ?>
<? $check_state = ($k == $default_bill_state) ? 'selected' : ''; ?>
<option value="<?= $k ?>" <?= $check_state ?>><?= substr($v, 0, 26) ?></option>
<? endforeach; ?>
</select>
</td>
<td>&nbsp;&nbsp;</td>
<td>State : </td><td>
<select name="ship_state">
<? foreach($states as $k=>$v): ?>
<? $check_state = ($k == $default_ship_state) ? 'selected' : ''; ?>
<option value="<?= $k ?>" <?= $check_state ?>><?= substr($v, 0, 26) ?></option>
<? endforeach; ?>
</select>
</td>
</tr>

<tr>
<td style="color:<?= $error['bill_zip'] ?>;">Zip : </td><td><input type="text" name="bill_zip" value="<?= $bill_zip ?>"></td>
<td>&nbsp;&nbsp;</td>
<td style="color:<?= $error['ship_zip'] ?>;">Zip : </td><td><input type="text" name="ship_zip" value="<?= $ship_zip ?>"></td>
</tr>

<tr>
<td>Country : </td>
<td>
<select name="bill_country">
<? foreach($countryList as $country): ?>
<? $check_country = ($country['ID'] == $default_bill_country) ? 'selected' : ''; ?>
<option value="<?= $country['ID'] ?>" <?= $check_country ?>><?= substr($country['Name'], 0, 26) ?></option>
<? endforeach; ?>
</select>
</td>
<td>&nbsp;&nbsp;</td>
<td>Country : </td><td>
<select name="ship_country">
<? foreach($countryList as $country): ?>
<? $check_country = ($country['ID'] == $default_ship_country) ? 'selected' : ''; ?>
<option value="<?= $country['ID'] ?>" <?= $check_country ?>><?= substr($country['Name'], 0, 26) ?></option>
<? endforeach; ?>
</select>
</td>
</tr>

<tr>
<td style="color:<?= $error['bill_phone'] ?>;">Phone : </td><td><input type="text" name="bill_phone" value="<?= $bill_phone ?>"></td>
<td>&nbsp;&nbsp;</td>
<td style="color:<?= $error['ship_phone'] ?>;">Phone : </td><td><input type="text" name="ship_phone" value="<?= $ship_phone ?>" ></td>
</tr>

<tr>
<td colspan="5">
<p><br>
<center>
<input type="button" value="< Back" onClick="window.location='/index.php/user/step1/';"> &nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="Next >" name="next">
</center>
</p>

</td>
</tr>
</table>


<script language="Javascript">
function fillShipping(){
  if(document.forms.todo.sameaddress.checked){

    document.forms.todo.ship_address1.disabled = true;
    document.forms.todo.ship_address2.disabled = true;
    document.forms.todo.ship_city.disabled = true;
    document.forms.todo.ship_state.disabled = true;
    document.forms.todo.ship_country.disabled = true;
    document.forms.todo.ship_zip.disabled = true;
    document.forms.todo.ship_phone.disabled = true;

  }else{

    document.forms.todo.ship_address1.disabled = false;
    document.forms.todo.ship_address2.disabled = false;
    document.forms.todo.ship_city.disabled = false;
    document.forms.todo.ship_state.disabled = false;
    document.forms.todo.ship_country.disabled = false;
    document.forms.todo.ship_zip.disabled = false;
    document.forms.todo.ship_phone.disabled = false;

  }
}
fillShipping();
</script>



</form>