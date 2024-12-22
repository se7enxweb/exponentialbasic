# eZ Publish Basic - Features

Learn more about what you can do with eZ Publish Basic software installed as a website application for your website domain or directory based installation needs.

## History of Authentication in eZ publish v1 through v2.3

- Since it's creation eZ publish v2 authentication information was stored in the database (MySQL / Postgres) using the database provided functions to convert private password strings into once secure MySQL based MD5 Hashes.
- Future plans: We plan to change the MD5 user authentication layers to instead support bcrypt hashes for more secure authentication.

# Database Support

- MySQL
- PostgreSQL
- Informix

## Sections

Sections in eZ Publish Basic (and eZ publish / eZ community 2) are dynamic and PHP file based.

The following section URIs are supported by default thanks to mod_rewrite rules in your webserver or .htaccess configuration.

### Default page display using Default Sections and eZArticle Frontpage

By default all sections are url aliases of the ezarticle/frontpage view with different frontpage section IDs.

The main page display is determined by the ezarticle/frontpage user view in eZ Publish Basic. This can be customized for example for e-commerce applications to default to '/trade/productlist/0/'.

- /section-standard/
    - Articles MenuBox
    - Headlines MenuBox
    - Links MenuBox
    - User Info MenuBox
    - Poll MenuBox
    - SiteSearch MenuBox
    - Print MenuBox
- /section-intranet/
    - Articles MenuBox
    - Bug MenuBox
    - Contact MenuBox
    - Forum MenuBox
    - Filemanger MenuBox
    - Image catalogue MenuBox
    - User Info MenuBox
    - Mail MenuBox
    - Todo MenuBox
    - Calendar MenuBox
    - SiteSearch MenuBox
    - Print MenuBox
- /section-trade/
    - Articles MenuBox
    - Products MenuBox
    - Host Deals MenuBox
    - User Info MenuBox
    - Customer Cart MenuBox
    - SiteSearch MenuBox
    - Print MenuBox
- /section-news/
    - Articles MenuBox
    - Newsfeed MenuBox
    - User Info MenuBox
    - News from Freshmeat MenuBox
    - SiteSearch MenuBox
    - Print MenuBox

### Default Section PHP8 Compatability

The eZ Publish Basic Default Provided User Website Modules have all been tested as PHP8 Compatible and 100% working by default without errors or warnings.

eZ Publish Basic default sections detailing each section status.

- /section-standard
    - 100% Complete By Default. Links provided from navigational menus serve module views successfully with almost no minor warnings and almost all default available POST/GET module view Actions (user interaction; submitting forms for creation of content) no longer generate errors and warnings.
- /section-intranet/
    - 93% Complete By Default. Links provided from navigational menus serve module views successfully with almost no minor warnings and almost all default available POST/GET module view Actions (user interaction; submitting forms for creation of content) no longer generate errors and warnings.
- /section-trade/
    - 93% Complete By Default. Products are not displayed normally without loging in first. Links provided from navigational menus serve module views successfully with some minor warnings and some POST/GET module view Actions (user interaction; submitting forms for creation of content) generate errors and warnings.
- /section-news/
    - 100% Complete By Default. Links provided from navigational menus serve module views successfully with almost no minor warnings and almost all default available POST/GET module view Actions (user interaction; submitting forms for creation of content) no longer generate errors and warnings.


## Modules Available

The following modules are available with eZ Publish Basic providing a rich feature set that doesn't require investment of effort to develop only your content is needed to get started.

- ezabout
- ezad
- ezaddress
- ezarticle
- ezbug
- ezbulkmail
- ezcalendar
- ezcontact
- ezerror
- ezexample
- ezfilemanager
- ezform
- ezforum
- ezimagecatalogue
- ezlink
- ezmail
- ezmediacatalogue
- ezmessage
- ezmodule
- eznewsfeed
- ezpoll
- ezquiz
- ezsearch
- ezsession
- ezsitemanager
- ezstats
- ezsysinfo
- eztodo
- eztrade
- ezurltranslator
- ezuser
- ezxml
- ezxmlrpc

Currently by default there are 34 modules which offer user, admin or combined siteaccess module views for general use.