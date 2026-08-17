<?php
//
// extension/helloworld/modules/helloworld/user/datasupplier.php
//
// Sample module in an extension. It uses an eZTemplate from the extension
// design path, falling back to a hard-coded output if the template cannot be
// loaded.

$ini = eZINI::instance( 'site.ini' );
if ( isset( $GlobalSectionIDOverride ) )
{
    $GlobalSectionID = $GlobalSectionIDOverride;
}
else
{
    $GlobalSectionID = $ini->variable( 'eZUserMain', 'DefaultSection' );
}

$templateDir = eZDesign::file( 'templates/helloworld' );
if ( $templateDir === false )
    $templateDir = 'design/standard/templates/helloworld';

$t = new eZTemplate( $templateDir, '', '', 'datasupplier.php' );
$t->set_file( 'welcome', 'welcome.tpl' );
$t->set_var( 'hello', 'Hello from the extension module!' );
$t->set_var( 'description', 'This page is served by extension/helloworld/modules/helloworld/user/datasupplier.php.' );
$t->pparse( 'output', 'welcome' );
