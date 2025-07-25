<?php
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/eztemplate.php" );
$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMessageMain", "Language" );

$t = new eZTemplate( "ezmessage/admin/" . $ini->read_var( "eZMessageMain", "AdminTemplateDir" ),
                     "ezmessage/admin/intl", $Language, "reciverpopup.php" );

$t->set_file( "message_page_tpl", "reciverpopup.tpl" );

$t->set_block( "message_page_tpl", "message_list_tpl", "message_list" );
$t->set_block( "message_list_tpl", "message_item_tpl", "message_item" );
$t->set_block( "message_item_tpl", "message_user_tpl", "message_user" );

if ( !is_numeric( $Max ) )
    $Max = 10;
if ( !is_numeric( $Index ) )
    $Index = 0;

$userList = $user->getAll( $OrderBy, true, false, $Max, $Index );

foreach( $userList as $userItem )
{
	$t->set_var( "first_name", $userItem->firstName() );
        $t->set_var( "last_name", $userItem->lastName() );
        $t->set_var( "login_name", $userItem->login() );
	$t->parse( "message_user", "message_user_tpl" );   
	
	$t->parse( "message_item", "message_item_tpl", true );
}

$t->parse( "message_list", "message_list_tpl" );

$t->pparse( "output", "message_page_tpl" );


include_once( "classes/ezlocale.php" );