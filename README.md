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

2. Set up your database in your database server.

- You will first need to generate the database SQL file. Run this command: ```cd /path/to/ezpb/; ./bin/shell/db-generate.sh```
- Then load the SQL file generated from: ```mysql -u user databaseName -p < ./update/generated/publish_mysql.sql;``

2.1 Install Default Content Database Data and Image File Content
- Then load the SQL file from: ```mysql -u user databaseName -p < ./update/data_mysql.sql;```
- Then uncompress the tar.gz file from: ```tar -vzxf ./share/data/data.tar.gz;```
- Then finally run the script from: ```./bin/shell/modfix.sh;```

3. Update settings files

Update settings file ```settings/override/site.ini``` as needed to include the default settings customized to your own needs.

- Update site name, domain hostnames for user and admin websites, default design, database name and database connection username and password settings all in the site.ini in settings/override/site.ini

4. Configure .htacesss or Web Server Mod_Rewrite Rules

This will direct all trafic by hostname match to index.php or index_admin.php as needed.

4.1 Add your user website domain name (escape periods for syntax match) to the provided .htaccess file configuration.

4.2 Add your admin website domain name (escape periods for syntax match) to the provided .htaccess file configuration.

4.3 Add your IPv4 address (escape periods for syntax match) to the provided .htaccess file configuration.

5. Initialize the application in your web browser. We recommend loading the admin site first but it doesn't matter much.

# Default Admin Account in eZ Publish Basic

- Username: 'admin'
- Password: 'publish'

## Example usage of user login view

- Example usage via web browser using eZ User URL: https://basic.demo.ezpublish.one/user/login
- Example usage via web browser using eZ Admin URL: https://admin.basic.demo.ezpublish.one/user/login

Note: Your work is now done. Enjoy the free software and a healthy snack. :)

# Documentation

Further documentation can be read from the [documentation](https://github.com/se7enxweb/ezpublishbasic/tree/master/documentation) directory [README](https://github.com/se7enxweb/ezpublishbasic/tree/master/documentation/README).

## Software Features

eZ Publish Basic provides a feature rich cms based website building platform that is ready to use upon it's quick installation setup.

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
5. You could help extend the language translation files to support more complete translation of all language file strings and phrases. Also you could provide new translations in new languges based on eng_US (the new default for 2.4.0.1).
7. You could help extend the library of site designs by providing a new design folder implementation. 7x just did this in providing the ecommerce design and related module templates design + templates based on eng_US language for strings in v2.4.0.1. It's very simple to create a custom design and share it.
 
Try today for starters. Become a eZ Publish Contributor and Share your work with everyone.

Check out the Contributing Guidelines for more details.

---

Developed with ❤️ by 7x. The company driving eZ in 2025 and beyond.
