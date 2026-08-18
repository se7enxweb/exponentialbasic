<?php
//
// extension/helloworld/modules/helloworld/admin/datasupplier.php
//
// Admin view for the Hello World sample extension module.

$ini = eZINI::instance( 'site.ini' );
if ( isset( $GlobalSectionIDOverride ) )
{
    $GlobalSectionID = $GlobalSectionIDOverride;
}
else
{
    $GlobalSectionID = $ini->variable( 'eZUserMain', 'DefaultSection' );
}

$Language = $ini->variable( 'site', 'Language' );

$templateDir = eZDesign::file( 'templates/helloworld' );
if ( $templateDir === false )
    $templateDir = 'design/standard/templates/helloworld';

$moduleBaseDir = eZExtension::moduleBaseDir( 'helloworld', 'admin' );
if ( $moduleBaseDir === false )
    $moduleBaseDir = 'extension/helloworld/modules/helloworld';
$intlDir = "$moduleBaseDir/admin/intl";
$t = new eZTemplate( $templateDir, $intlDir, $Language, 'datasupplier' );
$t->setAllStrings();

$t->set_file( 'welcome', 'welcome.tpl' );

if ( isset( $t->TextStrings['strings']['hello'] ) )
    $t->set_var( 'hello', $t->TextStrings['strings']['hello'] );
else
    $t->set_var( 'hello', 'Hello from the extension module!' );

if ( isset( $t->TextStrings['strings']['description'] ) )
    $t->set_var( 'description', $t->TextStrings['strings']['description'] );
else
    $t->set_var( 'description', 'This page is served by the Hello World admin view.' );

// Make sure the storage table exists, then handle adds and list the latest messages.
eZHelloWorldItem::createTable();

if ( isset( $_POST['name'] ) && isset( $_POST['message'] ) && trim( $_POST['name'] ) !== '' && trim( $_POST['message'] ) !== '' )
{
    $item = eZHelloWorldItem::create( trim( $_POST['name'] ), trim( $_POST['message'] ) );
    $item->store();
    $t->set_var( 'form_status', 'Message stored.' );
}
else
{
    $t->set_var( 'form_status', '' );
}

$items = eZHelloWorldItem::fetchList( 10 );

$t->set_block( 'welcome', 'item_list_tpl', 'item_list' );
if ( count( $items ) > 0 )
{
    foreach ( $items as $item )
    {
        $t->set_var( 'item_name', $item->Name );
        $t->set_var( 'item_message', $item->Message );
        $t->set_var( 'item_created', $item->createdDate() );
        $t->parse( 'item_list', 'item_list_tpl', true );
    }
}
else
{
    $t->set_var( 'item_name', '' );
    $t->set_var( 'item_message', 'No messages stored yet.' );
    $t->set_var( 'item_created', '' );
    $t->parse( 'item_list', 'item_list_tpl', false );
}

$t->set_block( 'welcome', 'add_form_tpl', 'add_form' );
$t->set_var( 'form_status', $t->get_var( 'form_status' ) );
$t->parse( 'add_form', 'add_form_tpl', false );

$templatePath = eZDesign::file( 'templates/helloworld' );
if ( $templatePath === false )
    $templatePath = 'design/standard/templates/helloworld';
$translationPath = "$moduleBaseDir/admin/intl/$Language/datasupplier.ini";
$t->set_var( 'edit_hint', 'Change this page text by editing the template ' . $templatePath . '/welcome.tpl and strings translation at ' . $translationPath . '.' );

$t->pparse( 'output', 'welcome' );
