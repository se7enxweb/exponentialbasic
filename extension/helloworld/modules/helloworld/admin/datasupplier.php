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

$intlDir = 'extension/helloworld/module/admin/intl';
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

$templatePath = eZDesign::file( 'templates/helloworld' );
if ( $templatePath === false )
    $templatePath = 'design/standard/templates/helloworld';
$translationPath = 'extension/helloworld/module/admin/intl/' . $Language . '/datasupplier.ini';
$t->set_var( 'edit_hint', 'Change this page text by editing the template ' . $templatePath . '/welcome.tpl and strings translation at ' . $translationPath . '.' );

$t->pparse( 'output', 'welcome' );
