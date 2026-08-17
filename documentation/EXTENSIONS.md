# Exponential Basic — Developer Extension Guide

## What this guide is for

This document shows how to write, install, and activate extensions for **Exponential Basic** (the eZ Publish 2/PHP 8 port maintained by 7x).  Extensions live in the `extension/` directory and can override settings, add design resources, and (in the next wave of work) provide new modules, translations, and PHP classes without changing the core application files.

The guide is split into two parts:

1. **Quick start** — get a working extension in under 10 minutes.
2. **The long version** — how each layer works, complete examples, reference tables, and troubleshooting.

---

# Part 1 — Quick start

## 1. Create an extension directory

Every extension is a folder under `extension/`.  The folder name is the extension name.  Create a sample extension called `myfirst`:

```bash
mkdir -p extension/myfirst/settings
mkdir -p extension/myfirst/design/standard
```

## 2. Activate the extension

Edit `settings/site.ini` and add the extension name to `[ExtensionSettings] ActiveExtensions[]`:

```ini
[ExtensionSettings]
ExtensionDirectory=extension
ActiveExtensions[]
ActiveExtensions[]=helloworld
ActiveExtensions[]=myfirst
```

Always keep the empty `ActiveExtensions[]` line first.  Extension settings are read from `extension/<name>/settings/` when the site boots.

## 3. Add a settings override

Create `extension/myfirst/settings/site.ini.append`:

```ini
[site]
MyFirstGreeting=Hello from myfirst!
```

The value `MyFirstGreeting` is now available anywhere `eZINI::instance('site.ini')->variable('site','MyFirstGreeting')` is called.

## 4. Add a design override

Create `extension/myfirst/design/standard/frame_head.append.php`:

```php
<meta name="x-myfirst-extension" content="myfirst extension loaded" />
```

When the `standard` design is used, `design/standard/frame.php` now loads this file through the `eZDesign` resolver and the meta tag is printed in `<head>`.

## 5. Clear cache and reload

After any INI, class, or design change, run:

```bash
bash bin/shell/clearcache.sh
```

If you added or renamed a PHP class, also regenerate autoloads:

```bash
php bin/shell/php/ezpgenerateautoloads.php -k
```

Visit the front page and view the page source.  You should see the `<meta>` tag from `myfirst` and the value from `helloworld` if you kept that sample active.

---

# Part 2 — The long version

## What an extension can do today

Exponential Basic now supports the following extension features:

| Feature | Status | Path used by the system |
|---|---|---|
| Settings overrides | Working | `extension/<ext>/settings/site.ini.append` |
| Siteaccess-specific settings | Working | `extension/<ext>/settings/siteaccess/<sa>/site.ini.append` |
| Design file resolution | Working | `extension/<ext>/design/<design>/...` |
| Frame / CSS / append files | Working | `extension/<ext>/design/<design>/frame.php`, `style.css`, `*.append.php` |
| Template overrides | Working | `extension/<ext>/design/<design>/templates/<module>/<file>.tpl` |
| Module dispatch | Working | `extension/<ext>/modules/<mod>/<type>/datasupplier.php` |
| Translation overrides | Planned | `extension/<ext>/translations/` |
| PHP classes and autoloading | Working through `var/autoload/ezp_kernel.php` regen | `extension/<ext>/classes/` |

The sections below explain each feature in detail.

---

## Extension directory layout

A fully-populated extension looks like this:

```
extension/myext/
  extension.xml                # Optional human/machine readable metadata
  README.md                    # Optional human documentation
  settings/
    site.ini.append            # Global settings overrides
    siteaccess/
      user/
        site.ini.append        # Overrides only for the 'user' siteaccess
      admin/
        site.ini.append        # Overrides only for the 'admin' siteaccess
  design/
    standard/
      frame.php                # Whole frame override (use with care)
      style.css                # Style override
      responsive.css           # Responsive style override
      frame_head.append.php    # Generic <head> append hook
      frame_head_calendar.append.php
                                 # eZ calendar hook
      templates/               # eZ template files (future/when wired)
        white/
          footer.tpl
    trade/
      style.css                # Design for the 'trade' design
    news/
      ...
  modules/
    mymodule/
      datasupplier.php         # User view entry point (Phase 2)
      admin/
        datasupplier.php       # Admin view entry point (Phase 2)
      module.info              # Menu/permission metadata (Phase 2)
  translations/
    eng-GB/                    # Translation catalogues (Phase 2)
      translation.ts
  classes/                     # PHP classes
    myclass.php
  autoloads/                   # Optional explicit autoload map
    myext_autoload.php
```

Only the directories you actually need are required.  The smallest working extension can be a single `settings/site.ini.append` file.

---

## How settings overrides work

### Loading order

`eZINI` builds a single `site.ini` by reading files in this order (later files win):

1. `settings/site.ini`
2. Active extension `settings/site.ini.append` files
3. Siteaccess extension settings (if any)
4. `settings/override/site.ini.append.php` (if present)
5. `settings/override/site.ini.append` (if present)

This means an extension can change any INI value, but values in `settings/override/site.ini.append` still have the final word.  That is intentional: `settings/override/` is where per-installation secrets and overrides live.

### Example: changing the site title per extension

`extension/branding/settings/site.ini.append`:

```ini
[site]
SiteTitle=My Custom Site
```

Because the active installation also ships `settings/override/site.ini.append.php` (a PHP-protected copy of site settings), that file may also set `SiteTitle`.  If it does, the override file wins.  To verify an extension override is being loaded, use a unique key that no other file defines:

`extension/branding/settings/site.ini.append`:

```ini
[site]
BrandingEnabled=true
BrandColor=#c0c0c0
```

Then in PHP:

```php
$ini = eZINI::instance( 'site.ini' );
if ( $ini->variable( 'site', 'BrandingEnabled' ) === 'true' )
{
    $brandColor = $ini->variable( 'site', 'BrandColor' );
}
```

### Example: array settings

If the base INI defines an array, the extension file appends to it:

`settings/site.ini`:

```ini
[SomeSettings]
AllowedHosts[]
AllowedHosts[]=example.com
```

`extension/myext/settings/site.ini.append`:

```ini
[SomeSettings]
AllowedHosts[]=myother.example.com
```

The resulting array contains `example.com` and `myother.example.com`.

### Siteaccess-specific overrides

Use `ActiveAccessExtensions[]` in `settings/site.ini` for extensions that should only load after a siteaccess is chosen, and place overrides under `extension/<ext>/settings/siteaccess/<siteaccess>/`.

`settings/site.ini`:

```ini
[ExtensionSettings]
ActiveAccessExtensions[]
ActiveAccessExtensions[]=myadminext
```

`extension/myadminext/settings/siteaccess/admin/site.ini.append`:

```ini
[site]
AdminHint=This value only appears in the admin siteaccess.
```

---

## How design resolution works

### The `eZDesign` class

The new `eZDesign` class in `kernel/classes/ezdesign.php` resolves a design resource by searching in this order:

1. `extension/<active-ext>/design/<current-design>/<file>` for each active extension
2. `design/<current-design>/<file>` (core)
3. `extension/<active-ext>/design/<additional-design>/<file>` for each design in `AdditionalSiteDesignList[]`
4. `design/<additional-design>/<file>` (core)
5. `extension/<active-ext>/design/<standard-design>/<file>`
6. `design/<standard-design>/<file>` (core, normally `standard`)

The first matching file is returned.  Extensions are searched before the core, so an extension can override any design file.

### PHP helpers

```php
// Return the filesystem path to the first matching design file.
$path = eZDesign::file( 'style.css' );

// Return a web URL (with WWWDir prepended) for a design asset.
$url  = eZDesign::url( 'style.css' );

// Resolve against a specific design.
$path = eZDesign::file( 'frame.php', 'trade' );
```

Both methods read the current site design from `site.ini` (`[site] SiteDesign`) and the standard fallback design from `[DesignSettings] StandardDesign`.

### Design override precedence example

If `SiteDesign=standard` and the active extensions are `helloworld` and `branding`, a request for `style.css` is resolved as:

1. `extension/branding/design/standard/style.css`
2. `extension/helloworld/design/standard/style.css`
3. `design/standard/style.css`

If `branding` does not provide the file but `helloworld` does, the `helloworld` CSS is used.

### Whole frame override

To replace the entire page frame, create:

```
extension/myext/design/standard/frame.php
```

This is powerful but should be rare, because every page on the site will use it.

### Partial append hooks

The `standard` design now supports these generic hooks:

- `frame_head.append.php` — anything to include in `<head>`
- `frame_head_calendar.append.php` — the existing eZ calendar hook

Additional hooks can be added to `design/standard/frame.php` or other designs by calling:

```php
$myFile = eZDesign::file( 'my_hook.append.php' );
if ( $myFile !== false )
    include_once( $myFile );
```

### Overriding CSS

To override the site stylesheet, create:

```
extension/myext/design/standard/style.css
```

Then the `<link>` tag generated by `eZDesign::url('style.css')` will point to it.  If you only want to add rules, import the core stylesheet at the top:

```css
@import url(../../../design/standard/style.css);

/* my extension rules */
body { background-color: #f8f8f8; }
```

### Overriding images and other assets

Any file under `design/<design>/` can be overridden.  For example, to replace the site logo for the `standard` design:

```
extension/myext/design/standard/images/exponential-basic-yourcontentmadeeasy.png
```

Then in a frame template the image can be referenced with:

```php
<img src="<?php print eZDesign::url( 'images/exponential-basic-yourcontentmadeeasy.png' ); ?>" />
```

If the frame still uses hard-coded `design/<?php print $GlobalSiteDesign; ?>/images/...`, the override will not be found.  Those templates are being migrated to `eZDesign::url()` over time.

### Section- and frontpage-specific designs

`eZDesign` also honors the `$GlobalSiteDesign` variable.  The eZ site manager sets `$GlobalSiteDesign` to the design of the current section or front page (for example `ecommerce` for `/section-ecommerce/`).  When `eZDesign::file()` or `eZDesign::url()` is called without an explicit design, it will use `$GlobalSiteDesign` first and fall back to `[site] SiteDesign` and finally to `[DesignSettings] StandardDesign`.  This keeps section-specific frames and CSS working while still allowing extension overrides.

---

## How template overrides work

`eZTemplate::set_file()` has been wired into `eZDesign::templateFile()`.  When a module asks for a template file (for example `loginmain.tpl`), the system now searches for an override in the active design before falling back to the core module template directory.

### Template search order

For a module template loaded from `kernel/<module>/<siteaccess>/templates/<design>/<file>.tpl` (for example `kernel/ezuser/user/templates/standard/loginmain.tpl`):

1. `extension/<active-ext>/design/<current-design>/templates/<module>/<siteaccess>/<file>.tpl`
2. `extension/<active-ext>/design/<current-design>/templates/<module>/<file>.tpl`
3. `extension/<active-ext>/design/<current-design>/templates/<file>.tpl`
4. `design/<current-design>/templates/<module>/<siteaccess>/<file>.tpl`
5. `design/<current-design>/templates/<module>/<file>.tpl`
6. `design/<current-design>/templates/<file>.tpl`
7. The same three paths under each additional and standard design
8. The original core module template directory

The most specific match wins, and an extension template is always preferred over a core template at the same level.

### Example: overriding a user template

`extension/myext/design/standard/templates/ezuser/user/login.tpl` overrides the user login template.  The more specific `/<siteaccess>/` path is useful when the same template name exists for both `user` and `admin`.

### Example: overriding a frontpage template

`extension/myext/design/standard/templates/ezarticle/frontpage.tpl` overrides the article frontpage for the `standard` design.

### Important note

Because `eZDesign` now honors the active section design (`$GlobalSiteDesign`), template overrides should be provided under the design the section uses.  If you are overriding a template that is shown in the `ecommerce` section, place it under `extension/myext/design/ecommerce/templates/...` (or rely on `standard` fallback).

---

## Example: the `helloworld` sample extension

The repository ships with a minimal working sample in `extension/helloworld/`.

### `extension/helloworld/settings/site.ini.append`

```ini
[site]
# Sample override from the extension/helloworld sample extension.
# This value is unique and proves the extension settings are merged.
HelloWorldGreeting=Hello from extension!
```

### `extension/helloworld/design/standard/frame_head.append.php`

```php
<meta name="x-helloworld-extension" content="frame_head.append.php loaded from extension/helloworld/design/standard/" />
```

### Activation

`settings/site.ini`:

```ini
[ExtensionSettings]
ActiveExtensions[]
ActiveExtensions[]=helloworld
```

### Verification

Clear cache, load the front page, and view source.  You should see:

```html
<meta name="x-helloworld-extension" content="frame_head.append.php loaded from extension/helloworld/design/standard/" />
```

A small CLI test is also available for settings:

```bash
php ai/bin/one/test_extension_settings.php
```

Expected output includes:

```
HelloWorldGreeting=Hello from extension!
```

### `extension/helloworld/modules/helloworld/user/datasupplier.php`

The sample also ships a minimal user module.  It sets `$GlobalSectionID`, loads a template from the extension design path, and prints its output to the main content buffer:

```php
<?php
$ini = eZINI::instance( 'site.ini' );
$GlobalSectionID = $ini->variable( 'eZUserMain', 'DefaultSection' );

$templateDir = eZDesign::file( 'templates/helloworld' );
if ( $templateDir === false )
    $templateDir = 'design/standard/templates/helloworld';

$t = new eZTemplate( $templateDir, '', '', 'datasupplier.php' );
$t->set_file( 'welcome', 'welcome.tpl' );
$t->set_var( 'hello', 'Hello from the extension module!' );
$t->pparse( 'output', 'welcome' );
```

### `extension/helloworld/design/standard/templates/helloworld/welcome.tpl`

```html
<h1>{hello}</h1>
<p>This page is served by extension/helloworld/modules/helloworld/user/datasupplier.php.</p>
```

### Module verification

Visit `https://<site>/helloworld/` and you should see the page rendered inside the site frame:

```
Hello from the extension module!
This page is served by extension/helloworld/modules/helloworld/user/datasupplier.php.
```

---

## Developing a new module

A module extension provides user and/or admin views.  The front controller now resolves module URLs through `eZExtension::moduleFile()`, which looks for an extension module before falling back to the core `kernel/ez<module>/<type>/datasupplier.php` path.

### Layout

```
extension/myext/modules/mymodule/
  user/
    datasupplier.php          # User view dispatcher
  admin/
    datasupplier.php          # Admin view dispatcher
  xmlrpc/
    datasupplier.php          # Optional XML-RPC dispatcher
  module.info                 # Title, permissions, menu position
```

### Dispatch order

For a request like `/mymodule/`, the front controller calls `eZExtension::moduleFile( 'mymodule', 'user' )`:

1. `extension/<active-ext>/modules/mymodule/user/datasupplier.php` (each active extension in order)
2. `kernel/ezmymodule/user/datasupplier.php` (core)

If an extension module exists, it wins over the core module with the same name.

### Module `datasupplier.php` skeleton

```php
<?php
// extension/myext/modules/mymodule/user/datasupplier.php

$ini = eZINI::instance( 'site.ini' );
if ( isset( $GlobalSectionIDOverride ) )
{
    $GlobalSectionID = $GlobalSectionIDOverride;
}
else
{
    $GlobalSectionID = $ini->variable( 'eZUserMain', 'DefaultSection' );
}

$templateDir = eZDesign::file( 'templates/mymodule' );
if ( $templateDir === false )
    $templateDir = 'design/' . $GlobalSiteDesign . '/templates/mymodule';

$t = new eZTemplate( $templateDir, '', '', 'datasupplier.php' );
$t->set_file( 'myview', 'myview.tpl' );
$t->set_var( 'hello', 'Hello from my module' );
$t->pparse( 'output', 'myview' );
```

The output is captured by the front controller and placed inside the frame.  You do not need to call `include( eZDesign::file( 'frame.php' ) );` yourself.

### `module.info`

```ini
[Module]
Name=My Module
Description=Example module in an extension.

[Views]
index=User index view
```

Admin module menus and link generation still read `kernel/ez<module>/module.info`; support for `extension/<ext>/modules/<module>/module.info` is the next wave.

---

## Translations (Phase 2 wiring)

Translation catalogues in extensions will be searched from `extension/<ext>/translations/<locale>/`.

Example:

```
extension/myext/translations/eng-GB/translation.ts
```

The `eZLocale` and `eZTemplate::setAllStrings()` layer will be extended to merge these catalogues with the core translations.

Until then, an extension can still ship plain PHP language files and load them from `extension/<ext>/classes/` or `extension/<ext>/settings/`.

---

## PHP classes and autoloading

### Adding a class

Create `extension/myext/classes/myclass.php`:

```php
<?php
class myClass
{
    public static function hello()
    {
        return 'Hello from myClass';
    }
}
```

After creating or renaming a class, regenerate the autoload map:

```bash
php bin/shell/php/ezpgenerateautoloads.php -k
```

The next request will load `myClass` automatically through `autoload.php` / `var/autoload/ezp_kernel.php`.

### Avoiding name collisions

Prefix extension classes to avoid colliding with core classes.  The convention used by 7x is `myextClassName` or `expMyextClassName`.

### Explicit autoload map

If a class cannot be discovered automatically (for example, it does not follow the naming convention), create:

```
extension/myext/autoloads/myext_autoload.php
```

```php
<?php
return array(
    'myCustomClass' => 'extension/myext/classes/custom.php',
);
```

This file will be merged into `var/autoload/ezp_extension.php` when autoloads are regenerated.

---

## Siteaccess and extension ordering

### Siteaccess-specific extensions

Two INI arrays control when extensions load:

- `ActiveExtensions[]` — loaded very early, before siteaccess matching.
- `ActiveAccessExtensions[]` — loaded after the siteaccess is chosen.

If an extension must change per siteaccess (for example, different settings for `user` and `admin`), use `ActiveAccessExtensions[]` and place overrides in `extension/<ext>/settings/siteaccess/<siteaccess>/`.

### Extension loading order

`ExtensionOrdering=enabled` (the default) tells Exponential Basic to sort extensions by the `LoadingOrder` declared in each extension's `extension.xml` or `settings/extension.ini.append`.

If two extensions override the same design file, the one loaded last wins (because it is searched first by `eZDesign`).

Example `extension/myext/settings/extension.ini.append`:

```ini
[ExtensionSettings]
LoadingOrder[]=after=helloworld
```

This requests that `myext` be loaded after `helloworld`.

### Tip: list active extensions

```php
print_r( eZExtension::activeExtensions( false ) );
```

`false` returns both `ActiveExtensions` and `ActiveAccessExtensions` combined.

---

## Complete walkthrough: a simple "branding" extension

This example changes the site title and adds a brand color to the `<head>`.

### Step 1 — create the directory

```bash
mkdir -p extension/branding/settings
mkdir -p extension/branding/design/standard
```

### Step 2 — settings override

`extension/branding/settings/site.ini.append`:

```ini
[site]
SiteTitle=Acme Company Portal
BrandColor=#0055aa
```

### Step 3 — design append

`extension/branding/design/standard/frame_head.append.php`:

```php
<style>
    :root { --brand-color: <?php print eZINI::instance('site.ini')->variable('site','BrandColor'); ?>; }
    h1, h2, h3 { color: var(--brand-color); }
</style>
```

### Step 4 — activate

Edit `settings/site.ini`:

```ini
[ExtensionSettings]
ActiveExtensions[]
ActiveExtensions[]=helloworld
ActiveExtensions[]=branding
```

### Step 5 — clear cache and test

```bash
bash bin/shell/clearcache.sh
```

Reload the site.  The title, favicon text, and headings should reflect the brand settings.

---

## Reference tables

### INI settings that control extensions

| Setting | File | Meaning |
|---|---|---|
| `ExtensionDirectory` | `settings/site.ini [ExtensionSettings]` | Base directory for extensions.  Always `extension` in Exponential Basic. |
| `ActiveExtensions[]` | `settings/site.ini [ExtensionSettings]` | Extensions loaded before siteaccess matching. |
| `ActiveAccessExtensions[]` | `settings/site.ini [ExtensionSettings]` | Extensions loaded after siteaccess matching. |
| `ExtensionOrdering` | `settings/site.ini [ExtensionSettings]` | `enabled` to sort by declared dependencies. |
| `StandardDesign` | `settings/site.ini [DesignSettings]` | Fallback design when a resource is missing in the active design. |
| `AdditionalSiteDesignList[]` | `settings/site.ini [DesignSettings]` | Extra designs to search before falling back to `StandardDesign`. |
| `SiteDesign` | `settings/site.ini [site]` | The active design. |

### `eZDesign` API

| Method | Returns | Example |
|---|---|---|
| `eZDesign::file( $file, $design = false )` | First matching filesystem path, or `false`.  Uses `$GlobalSiteDesign` when set. | `eZDesign::file('style.css')` |
| `eZDesign::url( $file, $design = false )` | Web URL ready for `href`/`src`, or `false`.  Uses `$GlobalSiteDesign` when set. | `eZDesign::url('images/logo.png')` |

### Extension file search path

For a request like `eZDesign::file('images/foo.png')` with `SiteDesign=standard` and active extensions `a`, `b`, `c`:

```
extension/c/design/standard/images/foo.png
extension/b/design/standard/images/foo.png
extension/a/design/standard/images/foo.png
design/standard/images/foo.png
extension/c/design/<AdditionalDesign1>/images/foo.png
...
design/<AdditionalDesign1>/images/foo.png
extension/c/design/<StandardDesign>/images/foo.png
...
design/<StandardDesign>/images/foo.png
```

### Cache commands

| Command | Why |
|---|---|
| `bash bin/shell/clearcache.sh` | Clear generated caches after INI/class/design/template changes. |
| `php bin/shell/php/ezpgenerateautoloads.php -k` | Regenerate `var/autoload/ezp_kernel.php` after new classes. |

---

## Testing and debugging

### CLI settings test

```bash
php ai/bin/one/test_extension_settings.php
```

This prints:

- `ExtensionDirectory`
- `ActiveExtensions`
- Override directories used by `eZINI`
- A few `site.ini` variables, including the sample `HelloWorldGreeting`.

### CLI design test

```bash
php ai/bin/one/test_ezdesign.php
```

This resolves `style.css`, `responsive.css`, and `test.txt` to show the design search order.

### Local web smoke test

Run a local PHP server from the site root:

```bash
php -S 127.0.0.1:8080
```

Then:

```bash
curl -s http://127.0.0.1:8080/ | grep -i 'x-helloworld'
```

You should see the meta tag injected by the `helloworld` extension.

### Common problems

| Symptom | Likely cause | Fix |
|---|---|---|
| Extension value not visible | `eZINI` cache is stale. | Run `bash bin/shell/clearcache.sh`. |
| New class not found | Autoload map not updated. | Run `php bin/shell/php/ezpgenerateautoloads.php -k`. |
| Design file not loading | `eZDesign::file()` not used in the frame/template. | Patch the frame/template to call `eZDesign::file()` or `eZDesign::url()`. |
| CSS still loading from core | Extension `style.css` is not in the correct design sub-directory. | Place it at `extension/<ext>/design/<current-design>/style.css`. |
| `ActiveExtensions` is empty | `ExtensionDirectory` or `ActiveExtensions[]` is wrong in `site.ini`. | Check `settings/site.ini` and clear cache. |
| Override `site.ini.append` does not apply | A later file (`settings/override/site.ini.append.php`) sets the same key. | Use a unique key, or remove the conflicting override. |

---

## Writing extension metadata

An optional `extension.xml` helps humans and future tooling understand your extension.

`extension/myext/extension.xml`:

```xml
<?xml version="1.0" encoding="utf-8"?>
<extension>
    <name>myext</name>
    <version>1.0.0</version>
    <description>A short description of the extension.</description>
    <author>Your Name</author>
    <license>GPL-2.0</license>
    <depends>
        <extension name="helloworld" />
    </depends>
</extension>
```

Not all of this is parsed by the core today, but it is a good habit and will be used by future tooling.

---

## Best practices

1. **Never commit credentials in an extension.**  Put database credentials, API keys, and secrets in `settings/override/site.ini.append` only (which is `.gitignore`d / not committed).
2. **Keep overrides small.**  Only override the INI keys and design files you actually need.  This makes upgrades easier.
3. **Use unique INI keys for testing.**  If you want to prove an extension is loading, add a key like `MyExtEnabled=true` instead of overriding common keys that might be shadowed by `settings/override/`.
4. **Prefix classes and file names.**  Avoid colliding with core names.
5. **Clear cache and regenerate autoloads after changes.**  Many "it did not take effect" problems are simply stale cache.
6. **Test with a local server.**  CLI tests verify INI and class loading, but the real test is a full HTTP request through `index.php`.
7. **Document your extension.**  Include a `README.md` in `extension/<ext>/README.md` explaining what it does and any special setup.

---

## Appendix A — `eZINI` override dir fix

Exponential Basic includes a fix to `lib/ezutils/classes/ezini.php` that makes `eZINI::prependOverrideDir()`, `appendOverrideDir()`, and related methods actually modify the global override directory list.  Without this fix, extension settings are not merged.  The change uses PHP references (`&`) so extension directories are stored in the static `GlobalOverrideDirArray` and per-instance `LocalOverrideDirArray`.

If you are porting this work to another installation, make sure the `eZINI` override methods use references.

---

## Appendix B — Migration from old `ezextensions/` or `extensions/` paths

Earlier experiments used `ezextensions/` and `extensions/` (plural).  The canonical Exponential Basic / eZ Publish 4 style directory is `extension/` (singular).  If you have code or notes referring to the older paths, change them to `extension/`.

---

## Appendix C — Future roadmap for extensions

The next waves of extension work will add:

1. Module dispatch from `extension/<ext>/modules/<module>/`.
2. Translation loading from `extension/<ext>/translations/`.
3. Admin module menu/link support from `module.info` in extensions.
4. A command-line tool to create a new extension skeleton.

This guide will be updated as those features land.
