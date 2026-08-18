#!/usr/bin/env php
<?php
/**
 * Create a new Exponential Basic extension skeleton.
 *
 * Usage:
 *     php bin/shell/php/create_extension.php myextension
 *     php bin/shell/php/create_extension.php myextension --dry-run
 *     php bin/shell/php/create_extension.php myextension --output-dir /var/tmp
 *
 * Creates the canonical extension/ directory layout described in
 * ./documentation/EXTENSIONS.md and fills it with working sample files.
 */

$script = basename( $argv[0] );

// Parse arguments manually so the script has no external dependencies.
$name = null;
$dryRun = false;
$outputDir = __DIR__ . '/../../..' . '/extension';

$positional = array();
for ( $i = 1; $i < $argc; $i++ )
{
    $arg = $argv[$i];
    if ( $arg === '--dry-run' )
    {
        $dryRun = true;
    }
    elseif ( $arg === '--output-dir' )
    {
        if ( !isset( $argv[$i + 1] ) )
        {
            fwrite( STDERR, "FAIL: --output-dir requires a value\n" );
            exit( 1 );
        }
        $outputDir = $argv[$i + 1];
        $i++;
    }
    elseif ( $arg === '--help' || $arg === '-h' )
    {
        showHelp( $script );
        exit( 0 );
    }
    elseif ( strpos( $arg, '-' ) === 0 )
    {
        fwrite( STDERR, "FAIL: unknown option $arg\n" );
        showHelp( $script );
        exit( 1 );
    }
    else
    {
        $positional[] = $arg;
    }
}

if ( count( $positional ) !== 1 )
{
    fwrite( STDERR, "FAIL: exactly one extension name is required\n" );
    showHelp( $script );
    exit( 1 );
}

$name = $positional[0];

if ( !isValidExtensionName( $name ) )
{
    fwrite( STDERR, "FAIL: extension name \"$name\" is invalid. Use lowercase letters, numbers, underscores, or hyphens.\n" );
    exit( 1 );
}

if ( !is_dir( $outputDir ) )
{
    fwrite( STDERR, "FAIL: output directory $outputDir does not exist\n" );
    exit( 1 );
}

$extDir = realpath( $outputDir ) . '/' . $name;
if ( is_dir( $extDir ) || is_file( $extDir ) )
{
    fwrite( STDERR, "FAIL: $extDir already exists. Use a different name or remove it first.\n" );
    exit( 1 );
}

$files = sampleFiles( $name );
$created = array();
foreach ( $files as $relPath => $content )
{
    $path = $extDir . '/' . $relPath;
    if ( $dryRun )
    {
        echo "CREATE $path\n";
        $created[] = $path;
        continue;
    }

    $dir = dirname( $path );
    if ( !is_dir( $dir ) )
    {
        if ( !mkdir( $dir, 0777, true ) )
        {
            fwrite( STDERR, "FAIL: could not create directory $dir\n" );
            exit( 1 );
        }
    }

    if ( file_put_contents( $path, $content ) === false )
    {
        fwrite( STDERR, "FAIL: could not write $path\n" );
        exit( 1 );
    }
    $created[] = $path;
}

if ( $dryRun )
{
    echo "\nDRY RUN: " . count( $created ) . " files would be created in $extDir\n";
    exit( 0 );
}

echo "PASS: created " . count( $created ) . " files in $extDir\n";
echo "Next steps:\n";
echo "  1. Add ActiveExtensions[]=$name to settings/site.ini\n";
echo "  2. Run: bash bin/shell/clearcache.sh\n";
echo "  3. Run: php bin/shell/php/ezpgenerateautoloads.php -e\n";
echo "  4. Visit https://<site>/$name/\n";

exit( 0 );


function showHelp( $script )
{
    echo "Usage: php $script <extension-name> [options]\n";
    echo "\n";
    echo "Options:\n";
    echo "  --dry-run        Print files that would be created without writing them.\n";
    echo "  --output-dir <d> Create the extension under <d> instead of extension/.\n";
    echo "  --help           Show this help.\n";
}


function isValidExtensionName( $name )
{
    return preg_match( '/^[a-z][a-z0-9_-]*$/', $name ) === 1;
}


function className( $name )
{
    $parts = preg_split( '/[-_]/', $name );
    $class = 'eZ';
    foreach ( $parts as $part )
    {
        $class .= ucfirst( $part );
    }
    return $class;
}


function title( $name )
{
    return ucwords( str_replace( array( '-', '_' ), ' ', $name ) );
}


function sampleFiles( $name )
{
    $class = className( $name );
    $title = title( $name );
    $map = array(
        '__NAME__' => $name,
        '__CLASS__' => $class,
        '__TITLE__' => $title,
    );

    $templates = array(
        'README.md' => placeholder( <<<'EOT'
# __TITLE__ Extension

This is the `__NAME__` extension for Exponential Basic.

## Purpose

Describe what this extension does.

## Installation

1. Activate the extension in `settings/site.ini`:

```ini
[ExtensionSettings]
ActiveExtensions[]
ActiveExtensions[]=__NAME__
```

2. Clear the cache and regenerate autoloads:

```bash
bash bin/shell/clearcache.sh
php bin/shell/php/ezpgenerateautoloads.php -e
```

## Files

- `settings/site.ini.append` — global INI overrides
- `design/standard/frame_head.append.php` — `<head>` hook
- `modules/__NAME__/user/datasupplier.php` — sample user view
- `classes/__CLASS__.php` — sample PHP class
EOT
        ),

        'extension.xml' => placeholder( <<<'EOT'
<?xml version="1.0" encoding="utf-8"?>
<extension>
    <name>__NAME__</name>
    <version>1.0.0</version>
    <description>__TITLE__ extension for Exponential Basic.</description>
    <author>Your Name</author>
    <license>GPL-2.0</license>
    <depends>
        <extension name="helloworld" />
    </depends>
</extension>
EOT
        ),

        'settings/site.ini.append' => placeholder( <<<'EOT'
[site]
# __TITLE__ extension settings.
# Use a unique key to prove the extension is being loaded.
__CLASS__Enabled=true
__CLASS__Greeting=Hello from __NAME__!
EOT
        ),

        'settings/siteaccess/admin/site.ini.append' => placeholder( <<<'EOT'
[site]
# Admin-only override for the __TITLE__ extension.
#__CLASS__AdminHint=true
EOT
        ),

        'settings/siteaccess/user/site.ini.append' => placeholder( <<<'EOT'
[site]
# User siteaccess-only override for the __TITLE__ extension.
#__CLASS__UserHint=true
EOT
        ),

        'design/standard/frame_head.append.php' => placeholder( <<<'EOT'
<meta name="x-__NAME__-extension" content="__NAME__ extension loaded" />
EOT
        ),

        'design/standard/style.css' => placeholder( <<<'EOT'
/* __TITLE__ extension stylesheet */

/* Import the core standard stylesheet, then add rules. */
@import url(../../../design/standard/style.css);

body { background-color: #ffffff; }
EOT
        ),

        'design/standard/templates/__NAME__/welcome.tpl' => placeholder( <<<'EOT'
<h1>{hello}</h1>
<p>This page is served by extension/__NAME__/modules/__NAME__/user/datasupplier.php.</p>
EOT
        ),

        'modules/__NAME__/user/datasupplier.php' => placeholder( <<<'EOT'
<?php
// __TITLE__ extension user view.

$ini = eZINI::instance( 'site.ini' );
if ( isset( $GlobalSectionIDOverride ) )
{
    $GlobalSectionID = $GlobalSectionIDOverride;
}
else
{
    $GlobalSectionID = $ini->variable( 'eZUserMain', 'DefaultSection' );
}

$templateDir = eZDesign::file( 'templates/__NAME__' );
if ( $templateDir === false )
    $templateDir = 'design/' . $GlobalSiteDesign . '/templates/__NAME__';

$t = new eZTemplate( $templateDir, '', '', 'datasupplier.php' );
$t->set_file( 'welcome', 'welcome.tpl' );
$t->set_var( 'hello', 'Hello from the __NAME__ extension!' );
$t->pparse( 'output', 'welcome' );
EOT
        ),

        'modules/__NAME__/admin/datasupplier.php' => placeholder( <<<'EOT'
<?php
// __TITLE__ extension admin view placeholder.
// Point to an admin template or return an admin dashboard.

echo "<h1>__TITLE__ Admin</h1>";
EOT
        ),

        'modules/__NAME__/module.info' => placeholder( <<<'EOT'
[Module]
Name=__TITLE__
Description=__TITLE__ module from the __NAME__ extension.

[Views]
index=User index view
admin=Admin index view
EOT
        ),

        'modules/__NAME__/xmlrpc/datasupplier.php' => placeholder( <<<'EOT'
<?php
// __TITLE__ extension XML-RPC dispatcher placeholder.
EOT
        ),

        'translations/en_US/datasupplier.ini' => placeholder( <<<'EOT'
[strings]
hello=Hello from the __NAME__ extension (translated)!
description=This page is served by extension/__NAME__/modules/__NAME__/user/datasupplier.php.
EOT
        ),

        'classes/__CLASS__.php' => placeholder( <<<'EOT'
<?php
class __CLASS__
{
    public static function greeting()
    {
        $ini = eZINI::instance( 'site.ini' );
        $greeting = $ini->variable( 'site', '__CLASS__Greeting' );
        return $greeting;
    }
}
EOT
        ),

        'autoloads/__NAME___autoload.php' => placeholder( <<<'EOT'
<?php
// Optional explicit autoload map for the __TITLE__ extension.
$extensionAutoloadMap = array(
    '__CLASS__' => 'extension/__NAME__/classes/__CLASS__.php',
);
EOT
        ),
    );

    $result = array();
    foreach ( $templates as $relPath => $content )
    {
        $key = str_replace( array_keys( $map ), array_values( $map ), $relPath );
        $result[$key] = str_replace( array_keys( $map ), array_values( $map ), $content );
    }

    return $result;
}


function placeholder( $text )
{
    return $text;
}
