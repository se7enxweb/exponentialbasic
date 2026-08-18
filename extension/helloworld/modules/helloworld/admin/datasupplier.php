<?php
//
// extension/helloworld/modules/helloworld/admin/datasupplier.php
//
// Admin view for the Hello World sample extension module.
// Styled like the core eZ Article archive list.

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

$t->set_file( 'adminlist', 'adminlist.tpl' );

$t->set_var( 'head_line', 'Hello World Messages' );
$t->set_var( 'add_head_line', 'Add a message' );
$t->set_var( 'description', 'List of stored hello-world messages.' );
$t->set_var( 'item_name_header', 'Name' );
$t->set_var( 'item_message_header', 'Message' );
$t->set_var( 'item_created_header', 'Created' );
$t->set_var( 'no_items_text', 'No messages stored yet.' );
$t->set_var( 'name_label', 'Name' );
$t->set_var( 'message_label', 'Message' );
$t->set_var( 'store_label', 'Store' );
$t->set_var( 'form_status', '' );

// Make sure the storage table exists.
eZHelloWorldItem::createTable();

// Handle delete action.
if ( isset( $_POST['DeleteSelected'] ) && isset( $_POST['DeleteArrayID'] ) && is_array( $_POST['DeleteArrayID'] ) )
{
    foreach ( $_POST['DeleteArrayID'] as $deleteId )
    {
        eZHelloWorldItem::removeById( $deleteId );
    }
    $t->set_var( 'form_status', 'Selected messages deleted.' );
}

// Handle add action.
if ( isset( $_POST['name'] ) && isset( $_POST['message'] ) && trim( $_POST['name'] ) !== '' && trim( $_POST['message'] ) !== '' )
{
    $item = eZHelloWorldItem::create( trim( $_POST['name'] ), trim( $_POST['message'] ) );
    $item->store();
    $t->set_var( 'form_status', 'Message stored.' );
}

// Search or list all.
if ( isset( $_POST['SearchText'] ) && trim( $_POST['SearchText'] ) !== '' )
{
    $items = eZHelloWorldItem::fetchBySearch( trim( $_POST['SearchText'] ), 50 );
}
else
{
    $items = eZHelloWorldItem::fetchList( 50 );
}

$t->set_block( 'adminlist', 'item_list_tpl', 'item_list' );
$t->set_block( 'adminlist', 'item_list_block_tpl', 'item_list_block' );
$t->set_block( 'adminlist', 'no_items_tpl', 'no_items' );

if ( count( $items ) > 0 )
{
    $i = 0;
    foreach ( $items as $item )
    {
        $t->set_var( 'item_id', (int)$item->ID );
        $t->set_var( 'item_name', $item->Name );
        $t->set_var( 'item_message', $item->Message );
        $t->set_var( 'item_created', $item->createdDate() );
        $t->set_var( 'td_class', ( $i % 2 ) == 0 ? 'bglight' : 'bgdark' );
        $t->parse( 'item_list', 'item_list_tpl', true );
        $i++;
    }
    $t->parse( 'item_list_block', 'item_list_block_tpl', false );
    $t->set_var( 'no_items', '' );
}
else
{
    $t->set_var( 'item_list_block', '' );
    $t->set_var( 'no_items_text', 'No messages stored yet.' );
    $t->parse( 'no_items', 'no_items_tpl', false );
}

$t->pparse( 'output', 'adminlist' );
