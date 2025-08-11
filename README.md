# eZ Publish Basic

![20BD8EBA-3B49-4ACB-8252-4165196D87B9_1_102_o](https://github.com/user-attachments/assets/9651c6e1-c110-4344-a151-de1b58a473e8)

eZ Publish Basic is an open-source project to modernize the classic eZ Publish 2.x CMS, bringing its functionality up-to-date with PHP 8 standards while retaining its simplicity and community-focused design.

This project is led by [7x](https://se7enx.com) and aims to ensure the long-term viability of eZ Publish 2.x for community portal solutions and their future portability (export to another cms).

---

 * Current release: Version 2.4.0.0 (Stable) [Release](https://github.com/se7enxweb/ezpublishbasic/releases/tag/v2.4.0.0)
 * Download: [zip](https://github.com/se7enxweb/ezpublishbasic/archive/refs/tags/v2.4.0.0.zip) or [tar.gz](https://github.com/se7enxweb/ezpublishbasic/archive/refs/tags/v2.4.0.0.tar.gz)
 * LICENSE: [GNU GPLv2 (or later)](COPYRIGHT.md)
 * Website:  [https://basic.ezpublish.one](https://basic.ezpublish.one)
 * Current unreleased Version In Development: Version 2.4.0.1 (Stable)
 * Source Code [https://github.com/se7enxweb/ezpublishbasic](https://github.com/se7enxweb/ezpublishbasic)
 * Maintainer: [7x](https://se7enx.com)
 * Document Last revised: 2025.08.09

---

# About

Read more [about](documentation/ABOUT.md) our project and it's historic value added services it provided early users, developers and businesses in the early 2000s era of web based application development environments and their required systems.

# Installation via Git & GitHub

Follow these steps to set up eZ Publish Basic via GitHub:

1. Clone the repository:
   ```bash
   git clone https://github.com/se7enxweb/ezpublishbasic.git

2. Navigate to the project directory:
   ```bash 
   cd ezpublishbasic

3. Install dependencies:
   ```bash
    composer install

## Installation via Composer & Packagist

Follow these steps to set up eZ Publish Basic via Composer:

1. Clone the latest release:
   ```bash
   composer create-project se7enxweb/ezpublishbasic -s dev ezpublishbasic;
   cd ezpublishbasic;

## Configure your installation of the software package

1. Configure your environment:

- Web Server setup a new virtual host for the website(s) that power this software package. There are two by default. User and Admin websites.
  - You will require separate virtual hosts for www and admin domains.

- Database Server
  - You will require a new database and database user and password access configured.

- Filesystem User and Group Permissions

- Generate eZ Publish Basics Autoloads
  - Use this command: ```bin/shell/php/ezpgenerateautoloads.php -k;```

- Create the two most used shell script alias commands via Shell script command aliases or by creating symbolic links in Linux / Unix / BSD / GNU.
   - Use this command: ```ln -s ./bin/shell/php/ezpgenerateautoloads.php regenerate-autoloads; ln -s bin/shell/clearcache.sh clear-cache;``` 
   -- Then you can run ```./reginerate-autoloads -k;``` (your shell autocomplete feature will make this easy to type twice. also shell aliases) or run ```./clear;```.

2. Set up your database in your database server.

- You will first need to generate the database SQL file. Run this command: ```cd /path/to/ezpb/; ./bin/shell/db-generate.sh```

- Then load the MySQL Schema SQL file generated from: ```mysql -u user databaseName -p < ./update/generated/publish_mysql.sql;``

- Then load the SQLite Schmea SQL SQL file generated from: ```sqlite3 var/site/db/site.db < ./update/generated/publish_sqlite.sql;``

2.1 Install Default Content Database Data and Image File Content into a Mysql Database
- Then load the MySQL Default Data SQL file from: ```mysql -u user databaseName -p < ./update/database/content/data_mysql.sql;```
- Then uncompress the Default File Data tar.gz file from: ```tar -vzxf ./share/data/data.tar.gz;```
- Then finally run permissions assignment the script from: ```./bin/shell/modfix.sh;```

2.1 Install Default Content Database Data and Image File Content into a SQLite Database
- Then load the MySQL Default Data SQL file from: ```sqlite3 var/site/db/site.db < ./update/database/content/data_sqlite.sql;```
- Then uncompress the Default File Data tar.gz file from: ```tar -vzxf ./share/data/data.tar.gz;```
- Then finally run permissions assignment the script from: ```./bin/shell/modfix.sh;```

3. Update settings files

Update settings file ```settings/override/site.ini.append.php``` as needed to include the default settings customized to your own needs.

- Update site name, domain hostnames for user and admin websites, default design, database name and database connection username and password settings all in the site.ini in settings/override/site.ini.append.php

4. Configure .htacesss or Web Server Mod_Rewrite Rules

This will direct all trafic by hostname match to index.php or index_admin.php as needed.

4.1 Add your user website domain name (escape periods for syntax match) to the provided .htaccess file configuration.

4.2 Add your admin website domain name (escape periods for syntax match) to the provided .htaccess file configuration.

4.3 Add your IPv4 address (escape periods for syntax match) to the provided .htaccess file configuration.

5. Run shell script to set permissions for webserver to own the user and group and have file permissions of 775. Run shell script, ```./bin/shell/modfix.sh;```

6. Initialize the application in your web browser. We recommend loading the admin site first but it doesn't matter much.

# Default Admin Account in eZ Publish Basic

- Username: 'admin'
- Password: 'publish'

## Example usage of user login view

- Example usage via web browser using eZ User URL: https://basic.demo.ezpublish.one/user/login
- Example usage via web browser using eZ Admin URL: https://admin.basic.demo.ezpublish.one/user/login

Note: Your work is now done. Enjoy the free software and a healthy snack. :)

Up next default content creation in the admin for your first visitor to see!

# Documentation

Further documentation can be read from the [documentation](https://github.com/se7enxweb/ezpublishbasic/tree/master/documentation) directory [README](https://github.com/se7enxweb/ezpublishbasic/tree/master/documentation/README).

More information can be found at our [project website](https://basic.ezpublish.one)

Older documentation on the core of our framework design fundamentals should be studied at the [Older v2 Era Documentation @ Wayback Machine](http://web.archive.org/web/20060901154601/http://doc22.ez.no/)

This includes the following key documentation (from the above doc link archive):

- [eZ publish 2.2 User Manuals](http://web.archive.org/web/20060901154506/http://doc22.ez.no/article/archive/2/index.html)

- [eZ publish 2.2 Admin Manuals](http://web.archive.org/web/20060901154627/http://doc22.ez.no/article/archive/4/index.html)

- [eZ publish 2.2 Designer Manuals](http://web.archive.org/web/20060901154703/http://doc22.ez.no/article/archive/5/index.html)

- [eZ Publish 2.2 PHP Class Reference Documentation](http://web.archive.org/web/20060901154614/http://doc22.ez.no/class_ref/doc/view/)


## Features

eZ Publish Basic provides a feature rich cms based website building platform that is ready to use upon it's quick installation setup.

- Webserver support: Any web server (Really) preferably one that supports a url rewriting module like Apache's mod_rewrite.

- PHP Support for PHP 8.1+, 8.2+, 8.3+, 8.4+

- Database Support for MySQL, MariaDB (via the MySQL Driver) PostgreSQL, SQLite, Informix and More through plugin based system.

- Simple application kernel design. Easy to learn and change.

- Module based kernel application functionality. Extendable by default (Settings based).

- eZ Publish 2 Kernel Transformed into eZ Publish 3 Kernel in Directory Structure and Storage of Code. This was then transformed into a hybrid of eZ Publish 2.4.0.0 kernel and eZ Publish 6 kernel sub system classes forming eZ Publish v2.4.0.1 our second version.

- Administration that makes creating content quick and simple.

- User side provides full functionality to create content through existing module views. This helps your users engage with your site.

- eZ Publish (Basic) is one of the very oldest CMS Projects under the GPL with over 20 years of history. It stands as the leader in cms design throughout this time, leaving others to refactor their own solutions while eZ Publish Basic Developers just keep developing solutions with little need to refactor.

- eZ Publish Basic is Free Software! We respect your freedoms. Try our software and join our community.

- Support / Discussion Forums available on [Share eZ Publish! Forums for eZ Publish Basic](https://share.ezpublish.one/forums/ez-publish-basic)

- Heavily tested software that is supported. If you find an issue we will fix it promptly.

- Refactored kernel provides for future expansion to begin to provide support for even more extension based functionality.
-- Autoloadable functionality within a plugin known as an extension
-- Extensions could contain designs/settings/classes/modules/sql/doc/bin/etc.
-- Extensions could be loaded based on settings based whitelist array of extension names.

- eZ is a web based application suite. It delivers functionality ranging from publishing, web logs and diaries, through web shop functionality like shopping carts and wishlists and forums to intranet functions like contact handling and bug reporting.

- The software uses caching and other optimization techniques to speed up page serving. It handles users, user preferences and user tracking through a user database and both cookie-based and non-bookie sessions.

- It supports statistics for page views, links to followed and banner ads, both images and HTML with presentation logic.

- The package lends itself to customization, from changing the look and feel by changing templates, localizing the languages and other internationalization issues to add new functionality.

- The target audience for eZ is e-commerce, ASP (Application Service Providers), BSP (Business Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, polls, todo lists, appointments as well as personal web sites.

- Advertising with statistics
- Article Publication and Management
- Bug handling and reporting
- Calendar functionality for creating appointments and events
- Contact handling for keeping track of people and businesses
- File manager for keeping track of uploaded files
- Moderated forums for discussions
- Image manager for keeping track of uploaded images
- Link manager which is used to categorize links
- News Feeding, fetch news and headlines from other sites and to incorporate in your own
- Poll module for creating user polls.
- Session module for keeping track of users and their preferences
- Statistics module for information about page views and visitors
- To-do module for assigning tasks to people
- Trade module which is an online shop, with shopping cart and Wish list
- User management for registering users, giving access to different groups to different parts of the site


Learn more about eZ Publish Basic features in detail. Study our documentation, [FEATURES.md](documentation/FEATURES.md).

## Software Features Roadmap

Learn more about eZ Publish Basic features roadmap in detail. Study our documentation, [ROADMAP.md](documentation/ROADMAP.md).

## PHP Compatability

For the latest information about the development of php 8 compatibility read our documentation, [COMPATIBILITY.md](documentation/COMPATIBILITY.md) - eZ Publish Basic Module Compatibility with PHP8.x. 

## Module Documentation

For the latest detailed list of views in eZ Publish Basic read our documentation, [MODULES.md](documentation/MODULES.md).

## Contributions

We are currently seeking others with eZ Publish 2 code improvements to share them with our project to grow the default installation feature set even further.

### Community eZ Publish 2 Modules

The worldwide eZ community on the internet likely holds old copies of custom modules. Some community members may be willing to contribute their modules for inclusion in eZ Publish Basic.

## How to Contribute

We welcome contributions from the community! To get involved:

1. Fork the repository. 
2. Create a new branch for your feature or bugfix. 
3. Submit a pull request.

We welcome you to create new code not just bugfix existing code:

1. You could contribute a new module providing additional desired features of any kind.
2. You could add new template system support for additional templating engines like smarty or twig.
3. You could add new database driver support for additional database server implementations as needed or desired. Note: they are very simple in design currently and quick to get working; 7x just added SQLite Database Support in the latest GitHub version (v2.4.0.1; unreleased officially) 
4. You could help extend the kernel to support cleaner features like settings based debug output by type. We are working on this in the near future.
5. You could help extend the language translation files to support more complete translation of all language file strings and phrases. Also you could provide new translations in new languages based on eng_US (the new default for 2.4.0.1).
7. You could help extend the library of site designs by providing a new design folder implementation. 7x just did this in providing the ecommerce design and related module templates design + templates based on eng_US language for strings in v2.4.0.1. It's very simple to create a custom design and share it.
 
Try today for starters. Become a eZ Publish Contributor and Share your work with everyone.

Check out the Contributing Guidelines for more details.

---

Developed with ❤️ by 7x. The company driving eZ in 2025 and beyond.
