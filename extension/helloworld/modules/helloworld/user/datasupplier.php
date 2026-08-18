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

$extensionDir = eZExtension::baseDirectory();
$intlDir = "$extensionDir/helloworld/module/user/intl";
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

$t->set_var( 'edit_hint', '' );

$t->pparse( 'output', 'welcome' );
