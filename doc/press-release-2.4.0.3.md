7x Releases Exponential Basic 2.4.0.3
======================================

7x is very proud of the work released today as **Exponential Basic 2.4.0.3**, a major upgrade to the 2.4 series that completes the extension-module API, ships a working database-backed sample extension, and hardens the admin experience and core modules for PHP 8.

This release provides a lot more features under the hood while keeping the same simple-to-use CMS on the surface. Developers can now build and activate new modules, admin views, and database-backed features without touching the core application files.

[Download the 2.4.0.3][1] release and give it a try. It is well documented with simple-to-follow instructions for getting started.

Have a question? Create a [forum][2] post and ask the community for help answering your questions.

Changes from 2.4.0.2 to 2.4.0.3
---------------------------

EXTENSION SYSTEM

- Added: eZDesign design resolver and extension/helloworld sample.
- Added: eZExtension::moduleFile(), moduleMenuFile(), moduleBaseDir(), moduleIcon(),
  moduleName(), moduleUrlName(), availableAdminModules() and related helpers.
- Added: eZTemplate translation override merging from active extension modules,
  loading strings from modules/<module>/{admin,user}/intl/<language>/<phpFile>.ini.
- Added: extension module discovery from module.info for admin menus, icons,
  menubox.php side-menu boxes and per-view intl paths.
- Added: php bin/shell/php/create_extension.php skeleton generator.
- Added: eZHelloWorldItem storage class with eZDB-backed create, fetch, fetchList,
  fetchBySearch, store, removeById and createTable in extension/helloworld.
- Added: admin list template (adminlist.tpl) for helloworld, styled after the core
  article archive list, with search, add-form, row delete and alternating row colours.
- Added: sample eZHelloWorld class and sample eZHelloWorldItem storage class.
- Renamed: helloworld translation catalogues to
  modules/helloworld/{admin,user}/intl/<language>/<phpFile>.ini.
- Updated: eZTemplate::loadExtensionTranslations() now searches legacy
  extension/<ext>/translations/ and the new modules/<module>/{admin,user}/intl/ paths.
- Updated: create_extension.php now generates the modules/<module>/{admin,user}/intl
  layout, module.info, admin menubox.php and datasupplier skeletons.
- Updated: documentation/EXTENSIONS.md with the extension API reference, eZExtension
  helper table, admin menubox example and eZDB storage class example.

ADMINISTRATION UI

- Updated: white admin style box borders and templates; replaced GIF tile borders
  with CSS, tightened desktop body spacing and improved mobile responsive styles.
- Updated: footer badge references now use Exponential Basic branding with improved
  contrast and wording.
- Updated: preserve the active admin site design in ezuser datasupplier.
- Updated: refererlist eZ TPL block parsing so blank referers display as "Direct" or
  "(none)" and do not produce broken http://Direct links.

CORE MODULE STABILITY

- Fixed: remove trailing commas that broke MySQL schema creation for eZArticle and eZStats.
- Fixed: eZImageVariation::store() now inserts image variation rows.
- Updated: order views and SETI export use customer emailAddress() as a string and
  avoid undefined index errors.
- Added: eZCompany::emailAddress() method returning the first mailto online URL.
- Updated: company type list guards requestImageVariation failures for category and
  company logos.
- Updated: cron scripts include require('autoload.php') and stale commented includes
  were removed.
- Updated: admin fatal/rendering errors resolved in contact, filemanager, trade,
  article, newsfeed and eZPBFile views.
- Updated: eZStats reports and queries (dates, browser counts, visitor reverse-DNS,
  blank referer handling).

SITE & OPERATIONS

- Updated: default IndexPage and DefaultPage point to the article frontpage so root / serves content.
- Updated: eZ Basic caches moved out of kernel/ and into var/cache/.
- Updated: restore /proc-based sysinfo pages and fix PHP 8 compatibility; also restore
  system info values when /proc is not directly readable.
- Updated: Calendar time slot color set to white for default readability.
- Updated: README.md with a Troubleshooting section for first-time installs.
- Updated: composer.json phpunit package require from insecure v10 to v13.
- Updated: documentation/ABOUT.md now targets the 2.4.0.3 release.

---

[1]: https://github.com/se7enxweb/exponentialbasic/releases/tag/v2.4.0.3
[2]: https://share.se7enx.com/forums
