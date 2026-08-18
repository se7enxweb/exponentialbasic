# For Immediate Release

**Exponential Basic 2.4.0.3 Now Available: Extension Modules, Admin UI Refresh, and PHP 8 Hardening**

**August 18, 2026**

7x today announced the release of **Exponential Basic 2.4.0.3**, the latest version of the open-source, PHP 8-ready eZ Publish 2 kernel upgrade. This release completes the extension-module API, ships the first fully working sample extension with database storage, and hardens the admin interface and core modules for production use.

Exponential Basic is 7x's long-running project to preserve and modernise the eZ Publish 2 codebase, bringing the proven community-building and personal-home-page CMS engine to PHP 8.1 - 8.3. With 2.4.0.3, developers can now build and activate new modules, admin views and database-backed features without touching the core application files.

---

## What is new in 2.4.0.3

### Extension modules now work end-to-end

The biggest change in 2.4.0.3 is a complete extension-module pipeline. Active extensions can now supply their own `modules/<module>/{user,admin}/` views, per-view `intl/` translation files, `module.info` metadata, admin side-menu `menubox.php` files, and module icons. The front controller and `eZTemplate` system resolve these in the right order and merge extension translations into the active language.

New `eZExtension` helpers provide a stable API for discovering module files, menus, icons and translation directories without hard-coding `extension/` or `kernel/ez` paths. A revised `php bin/shell/php/create_extension.php` skeleton generator creates the new module layout, `module.info`, `menubox.php`, admin and user `datasupplier.php` files and `design/` templates.

### Hello World sample with eZDB storage

The `extension/helloworld/` sample has been expanded into a full create-and-list example. It includes:

- `eZHelloWorldItem`, a minimal `eZDB::globalDatabase()` storage class with `createTable()`, `fetch()`, `fetchList()`, `fetchBySearch()`, `store()` and `removeById()`.
- A public view that lists stored messages.
- An admin view styled like the core `article/archive/` list, with search, add form, row delete, alternating `bglight`/`bgdark` rows and `stdbutton` controls.
- Per-view `intl/` translation catalogues under the new `modules/helloworld/{admin,user}/intl/<language>/` layout.

This gives developers a concrete, copy-paste pattern for adding database-backed features to their own extensions.

### Admin UI refresh

The white admin design has been overhauled. GIF-tile borders have been replaced with CSS, desktop body spacing has been tightened, and mobile responsive styles have been improved. Footer branding now uses the Exponential Basic badge with better contrast and wording. Referrer list rendering has also been fixed so blank referrers display as "Direct" or "(none)" instead of producing broken `http://Direct` links.

### Core module stability

Dozens of PHP 8 compatibility and correctness fixes have been applied across the core modules, including:

- eZArticle and eZStats MySQL schema creation.
- eZImageVariation row insertion.
- eZCompany email address handling in orders and SETI export.
- eZStats reports, browser counts, reverse-DNS and blank-referrer handling.
- Cron scripts now include `autoload.php` and no longer rely on stale commented includes.
- Admin fatal/rendering errors resolved in `contact`, `filemanager`, `trade`, `article`, `newsfeed` and `eZPBFile`.

Other operational improvements include moving eZ Basic caches from `kernel/` to `var/cache/`, restoring `/proc`-based sysinfo pages with a PHP 8-safe fallback, defaulting the site root to the article front page, and updating the bundled `phpunit` dependency to a secure version.

---

## About Exponential Basic

Exponential Basic is the eZ Publish 2 kernel implementation upgraded for modern PHP. It is maintained by 7x and released under the GNU General Public License. The project provides a lightweight, educational, and production-suitable starting point for personal home pages, community websites and simple PHP applications.

"This release is a practical milestone," said Graham Brookins, founder of 7x. "Extension modules were always part of the eZ vision, and now they actually work again on PHP 8. With the `helloworld` storage sample and the updated admin list, developers can see exactly how to extend the system without changing the core."

---

## Availability and upgrade

Exponential Basic 2.4.0.3 is available immediately from the GitHub release page:

<https://github.com/se7enxweb/exponentialbasic/releases/tag/v2.4.0.3>

Existing sites should back up their `settings/override/` directory and database, then run:

```bash
php bin/shell/php/ezpgenerateautoloads.php -e
bash bin/shell/clearcache.sh
```

and restart PHP-FPM.

For the full technical changelog, see `doc/changelogs/2.4.0.3.md` and `documentation/CHANGELOG` in the repository.

---

**Media contact**

7x
<https://share.exponential.earth>
