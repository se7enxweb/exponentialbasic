# Exponential Basic / eZ Basic Extension Support Plan

## Current state

The repository already ships with a modern-ish eZ Publish 4/5 utility layer under
`lib/ezutils/` (`eZINI`, `eZExtension`, `eZDir`, `eZDebug`, `eZPHPCreator`, etc.)
and `settings/site.ini` already exposes `[ExtensionSettings]` and `[DesignSettings]`.
However, the legacy Exponential Basic front-end in `kernel/classes/ezpbkernelweb.php`
still uses hard-coded eZ Publish 2-style paths (`design/<design>/frame.php`,
`kernel/ez<module>/user/datasupplier.php`, etc.).

This means **the extension plumbing is already in `lib/ezutils`**, but the kernel
never asks it where extension resources are.

## Goal

Add real, usable, developer-friendly eZ Publish 3-style extension support:

- Extension root directory named `extension/` (instead of the eZ4 default
  `extension/`).
- An extension layout like:

```
extension/myext/
  settings/
    site.ini.append          # override settings
  settings/siteaccess/user/  # siteaccess overrides
  design/
    standard/frame.php       # design override/addition
    standard/templates/      # .tpl files
  modules/
    mymodule/
      datasupplier.php       # user module entry point
      admin/                 # admin module entry point
  translations/              # locale files
  classes/                   # PHP classes
  autoloads/                 # autoload map
```

## Phase 1 — `extension/` directory + settings/design loading

### 1.1 Configure the extension base directory

- `settings/site.ini`:
  - Change `[ExtensionSettings] ExtensionDirectory=extension` to `ExtensionDirectory=extension`
    (or make `eZExtension::baseDirectory()` support a path list and keep `extension`
    for backward compatibility).
  - Populate `ActiveExtensions[]` with sample extensions when needed.

- `lib/ezutils/classes/ezextension.php`:
  - `eZExtension::baseDirectory()` already reads `ExtensionDirectory`.
  - `eZExtension::activeExtensions()` already returns the active list.
  - `eZExtension::activateExtensions()` already adds `extension/<ext>/settings`
    to `eZINI` override dirs.

**Result:** settings overrides from `extension/<ext>/settings/` should work with
no code changes once the INI value is `extension` and the extension is active.

### 1.2 Design / template path resolution

The current front-end resolves the frame here:

- `kernel/classes/ezpbkernelweb.php` line 793: `include( "design/$siteDesign/frame.php" );`
- `design/*/frame.php` templates hard-code `design/<?php print $GlobalSiteDesign; ?>/...`
- `kernel/classes/eztemplate.php` resolves `.tpl` files relative to the module that
  constructs it (e.g. `kernel/ezarticle/admin/templates/white/articleview.tpl`).

Required patches:

1. Add a `eZDesign` helper (or extend `eZSys`/`eZPBFile`) that, given a design file
   like `frame.php` or `style.css`, returns the first match from:
   - `design/<design>/...` (core)
   - `extension/<active-ext>/design/<design>/...` (each active extension in order)
   - `design/<standardDesign>/...` (fallback)
2. Patch `kernel/classes/ezpbkernelweb.php` to use that helper for `frame.php`,
   `simpleframe.php`, `loginframe.php`, favicon/header includes, etc.
3. Patch `design/*/frame.php` to call the same helper when building CSS/JS/image URLs
   (or replace hard-coded design URLs with a global `eZDesign::url()` function).
4. Patch `kernel/classes/eztemplate.php` to search `AdditionalSiteDesignList[]` +
   active extension design paths when loading `.tpl` files. Use
   `DesignSettings.StandardDesign` as the final fallback.

### 1.3 Create a sample extension

Create `extension/standard_extra/` (or similar) as a reference:

```
extension/standard_extra/settings/site.ini.append
extension/standard_extra/design/standard/frame.append.php
```

Use it to prove settings overrides and design fallbacks.

## Phase 2 — Extension modules, translations, classes, and autoload

### 2.1 Module dispatch — DONE

Current dispatch in `kernel/classes/ezpbkernelweb.php`:

- Line 638: `$content_page = "kernel/ez" . $url_array[1] . "/user/datasupplier.php";`
- Admin side uses similar `kernel/ez<module>/admin/...` paths.

Implemented:

1. `eZExtension::moduleFile( $module, $type )` in `lib/ezutils/classes/ezextension.php`
   searches, in order:
   - `extension/<active-ext>/modules/<module>/<type>/datasupplier.php`
   - `kernel/ez<module>/<type>/datasupplier.php`
2. `kernel/classes/ezpbkernelweb.php` uses `eZExtension::moduleFile()` for user
   module dispatch (main and default-page paths).
3. `kernel/classes/ezpbkerneladmin.php` uses `eZExtension::moduleFile()` for admin
   module dispatch and the redirect-only `gotolink` view.

### 2.2 Admin module menu and URLs

- `kernel/ezuser/classes/ezmodule.php` and `kernel/classes/ezmodulelink.php`
  build module links from `kernel/ez<module>/module.info`.
- Make them also read `extension/<ext>/modules/<module>/module.info`.

### 2.3 Translations — DONE

- `kernel/classes/eztemplate.php` now calls `loadExtensionTranslations()` from
  its constructor. It merges `[strings]` blocks from
  `extension/<ext>/translations/<language>/<phpFile>.ini` into the template's
  text strings, allowing extension translation overrides.
- `eZLocale` still loads core locale files from `kernel/ez<module>/.../intl/<lang>/...`.
  The `eZTemplate` layer is the primary consumer.

### 2.4 Class autoload — DONE

- `var/autoload/ezp_extension.php` and `var/autoload/ezp_kernel.php` are
  generated by `bin/shell/php/ezpgenerateautoloads.php`.
- The `-e` flag scans `extension/<ext>/classes/` and builds
  `var/autoload/ezp_extension.php`, which `autoload.php` merges at runtime.
- No changes were required; the sample `eZHelloWorld` class in
  `extension/helloworld/classes/helloworld.php` was picked up and loads
  automatically after autoload regeneration.

### 2.5 Extension information / discovery

- `lib/ezutils/classes/ezextension.php` already has `extensionInfo()` and
  `nameFromPath()`. Re-use them.
- Consider an `extension/<ext>/extension.xml` or `README.md` for metadata.

## Phase 3 — Developer tooling and documentation

- Update `bin/shell/modfix.sh` and `bin/shell/clearcache.sh` to create/clear
  `extension/` and its subdirectories, not `kernel/*/cache`.
- Add `ai/doc/extension_developer_guide.md` covering:
  - Directory layout
  - `site.ini` activation
  - Settings override order
  - Design override order
  - Module development
  - Translation files
  - Autoload rules
- Add a `extension/helloworld/` reference extension that demonstrates all
  features.

## Files most likely to be touched

- `settings/site.ini` — `ExtensionSettings` / `DesignSettings`
- `lib/ezutils/classes/ezextension.php` — path expansion helpers
- `kernel/classes/ezpbkernelweb.php` — front-controller dispatch + frame include
- `kernel/classes/eztemplate.php` — template loading + design search
- `kernel/classes/ezlocale.php` — translation search
- `kernel/ezuser/classes/ezmodule.php` and `kernel/classes/ezmodulelink.php` —
  module metadata + links
- `design/standard/frame.php` (and other design frames) — design asset URLs
- `bin/php/ezpgenerateautoloads.php` — class scanning
- `bin/shell/modfix.sh` / `bin/shell/clearcache.sh` — runtime dir setup

## Open questions

1. Should the extension root be `extension/` (eZ3-style) or should the code
   support both `extension/` and `extension/`?
2. Should modules be `extension/<ext>/modules/<module>/` (eZ4/5) or
   `extension/<ext>/kernel/ez<module>/` (Basic-style)?
3. Do we keep using the legacy Basic `datasupplier.php` entry point or adopt the
   eZ4 `module.php` front-controller?

## Recommendation

Start with **Phase 1** because `lib/ezutils` already provides the INI override
mechanism; only the design/template resolver in `kernel/classes/ezpbkernelweb.php`
needs the new design search path. Once designs and settings load from
`extension/`, adding module dispatch and translations in Phase 2 is a
straight-forward extension of the same path-resolution helper.
