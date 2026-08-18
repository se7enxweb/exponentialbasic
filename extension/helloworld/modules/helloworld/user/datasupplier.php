<?php
//
// extension/helloworld/modules/helloworld/user/datasupplier.php
//
// Sample module in an extension. It uses an eZTemplate from the extension
// design path, with extension translation strings merged into the template.

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

$moduleBaseDir = eZExtension::moduleBaseDir( 'helloworld', 'user' );
if ( $moduleBaseDir === false )
    $moduleBaseDir = 'extension/helloworld/modules/helloworld';
$intlDir = "$moduleBaseDir/user/intl";
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
    $t->set_var( 'description', 'This page is served by extension/helloworld/modules/helloworld/user/datasupplier.php.' );

// Make sure the storage table exists, then list the latest stored messages.
eZHelloWorldItem::createTable();
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

$t->set_var( 'edit_hint', '' );

$t->pparse( 'output', 'welcome' );
