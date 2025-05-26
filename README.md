# eZ Publish Basic

![20BD8EBA-3B49-4ACB-8252-4165196D87B9_1_102_o](https://github.com/user-attachments/assets/9651c6e1-c110-4344-a151-de1b58a473e8)

eZ Publish Basic is an open-source project to modernize the classic eZ Publish 2.x CMS, bringing its functionality up-to-date with PHP 8 standards while retaining its simplicity and community-focused design.

This project is led by [7x](https://se7enx.com) and aims to ensure the long-term viability of eZ Publish 2.x for community portal solutions and their future portability (export to another cms).

Documentation for this project will in the future be covering importing eZ Publish 2.x Database Content, Exporting eZ Publish 2.x Database content for import (As required to start, like say eZ Article or eZ FileManager or eZ ImageCatalog) from eZ Publish 2 into eZ Publish 6 (Latest stable version of eZ Publish 3 Kernel APIs) Open Source Code and Flexible Content Management where importing data is common and accepted part of any website project using a CMS.

---

 * LICENSE: [GNU GPLv2 (or later)](COPYRIGHT.md)
 * Source Code [https://github.com/se7enxweb/ezpublishbasic](https://github.com/se7enxweb/ezpublishbasic)
 * Website:  [https://basic.ezpublish.one](https://basic.ezpublish.one)
 * Maintainer: [7x](https://se7enx.com)
 * Document Last revised: 2025.02.19

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
  - Use this command: bin/shell/php/ezpgenerateautoloads.php -k;

2. Set up your database in your database server.

3. Update settings files as needed. 

- Update site name, domain hostnames for user and admin websites, default design, database name and connecting username and password settings all in the site.ini in bin/ini/override/site.ini

3. Initialize the application in your web browser. We recommend loading the admin site first but it doesn't matter much.

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

Check out the Contributing Guidelines for more details.

---

Developed with ❤️ by 7x.
