<?php

require( "ezuser/user/usercheck.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "classes/eztexttool.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZUserMain", "Language" );
$SelectCountry = $ini->variable( "eZUserMain", "SelectCountry" );
$SelectRegion = $ini->variable( "eZUserMain", "SelectRegion" );
$AnonymousUserGroup = $ini->variable( "eZUserMain", "AnonymousUserGroup" );

$AutoCookieLogin = eZHTTPTool::getVar( "AutoCookieLogin" );

$session =& eZSession::globalSession();

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezaddress/classes/ezcountry.php" );
include_once( "ezaddress/classes/ezregion.php" );
include_once( "ezmail/classes/ezmail.php" );



$user =& eZUser::currentUser();
$form_type = "Registration";
$error = array();

if($next){
  // Report blank errors
  foreach($_POST as $k=>$v){
    $error[$k] = error_check($v);
  }
  
  // Don't change blank passwords for users
  if($user &! $password1){
    $error['password1'] = 'black';
    $error['password2'] = 'black';
  }

  // Report non-matching password errors
  if($password1 != $password2){
    $error['password1'] = 'red';
    $error['password2'] = 'red';    
  }

  // Check if there were any errors
  foreach($error as $k=>$v){
    if($v == 'red'){
      $errors_ind = 1;
    }
  }
  
  if(! $errors_ind){
    
    if(! $user){
      $user = new eZUser();
    }
    $user->setLogin($username);
    $user->setEmail($email);
    $user->setLastName($last_name);
    $user->setFirstName($first_name);
    $user->setInfoSubscription($newsletter_ind ? 1 : 0);
    if($password1){
      $user->setPassword($password1);
    }      
    $user->store();
    $user = $user->validateUser( $username, $password1 );
  }
  
}

function error_check($varcheck){
  if(!($varcheck && strlen($varcheck) <= 50)){
    return 'red';
  }else{
    return 'black';
  }
}


if($user){
  $first_name=$user->firstName();
  $last_name=$user->lastName();
  $email=$user->email();
  $username=$user->Login();
  $Info = $user->InfoSubscription() ? 'checked' : '';
  $form_type = "Profile";
}

print_r($user);
?>

<div id="breadcrumbs">
&nbsp; 
</div>

<div id="contentWrap">
<h1 class="mainHeading"><?= $form_type ?> - Step 1 of 2</h1>

<p>
Completion of <?= $form_type ?> Step 1 allows you to post in our user forums and save products to a personal wish list.
</p>

<form method="post">
<table border="0" cellspacing="2" cellpadding="2" class="boxtext" style="font-size=13;">
<tr><td style="color:<?= $error['first_name'] ?>;">First Name: </td><td><input type="text" name="first_name" value="<?= $first_name ?>"></td>
    <td>&nbsp;&nbsp;</td>
    <td style="color:<?= $error['last_name'] ?>;">Last Name: </td><td><input type="text" name="last_name" value="<?= $last_name ?>"></td>
</tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr><td style="color:<?= $error['email'] ?>;">Email: </td><td><input type="text" name="email" value="<?= $email ?>"></td><td colspan="3"></td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr><td style="color:<?= $error['username'] ?>;">Username</td><td><input type="text" name="username" value="<?= $username ?>"></td><td colspan="3"></td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr><td style="color:<?= $error['password1'] ?>;">Password: </td><td><input type="password" name="password1"></td>
    <td>&nbsp;&nbsp;</td>
    <td style="color:<?= $error['password2'] ?>;">Repeat Password: </td><td><input type="password" name="password2"></td>
</tr>
<tr><td colspan="5">&nbsp;</td></tr>
</table>

<table border="0" cellspacing="2" cellpadding="2" class="boxtext" style="font-size=13;">
<tr><td><input type="checkbox" name="newsletter_ind" value="1" <?= $Info ?>></td><td>Check here to receive updates on sales & special offers</td>
</table>

<p>
<center><input type="submit" name="next" value="Next >">&nbsp;&nbsp;&nbsp;&nbsp;</center>
</p>

<br>

<p>
<center>FullThrottle.com respects your <a href="http://www.FullThrottle.com/index.php/article/articlestatic/75/1/5/">privacy</a> &nbsp;&nbsp;&nbsp;&nbsp;</center>
</p>
</form>

</div>


