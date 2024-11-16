# eZ Publish Basic

eZ Publish Basic is an open-source project to modernize the classic eZ Publish 2.x CMS, bringing its functionality up-to-date with PHP 8 standards while retaining its simplicity and community-focused design.

This project is led by [7x](https://se7enx.com) and aims to ensure the long-term viability of eZ Publish 2.x for community portal solutions and their future portability (export to another cms).

Documentation for this project will in the future be covering importing eZ Publish 2.x Database Content, Exporting eZ Publish 2.x Database content for import (As required to start, like say eZ Article or eZ FileManager or eZ ImageCatalog) from eZ Publish 2 into eZ Publish 6 (Latest stable version of eZ Publish 3 Kernel APIs) Open Source Code and Flexible Content Management where importing data is common and accepted part of any website project using a CMS.

---

 * LICENSE: GNU GPLv2 (or later)
 * Source Code [https://github.com/se7enxweb/ezpublishbasic](https://github.com/se7enxweb/ezpublishbasic)
 * Maintainer: [7x](https://se7enx.com)
 * Document Last revised: 2024.11.16

---

## About

We are actively working to bring **eZ Publish Basic 2.4.0** to the general public.

eZ Publish 2.4 is the completed work started by 7x years ago to upgrade eZ Publish 2 Kernel Implementation of a easy to use PHP Personal Home Page CMS or Community Website Building Platform to more modern PHP versions. 

Previously this was developed and released as eZ Community 2.3 the community portal distribution which provided partial incomplete PHP5 Port of the eZ Publish 2 Official Repository (Containing addition code never released to the public).

Today is a new day and a new chance to complete the work started by 7x with the eZ Community Distribution of eZ Pubilsh 2+.

To distinguish between different product releases we have decided to release the finished work under the original name with a brand identifier 'basic' to indicate that eZ publish basic or eZ Publish Basic is supporting PHP8.3 Web Servers and can be used today in 2024 to learn how to more quickly build simple web applications in minutes rather than days as common needs can follow existing developed code patterns in implementation phase(s). Learn by example, Learn by doing, build a bridge to a new world not a wall. 

We are also incrementing the version number from 2.3.x to 2.4.x.x to give more version number space while clearly marking this release as unique. Short standard for the version numbers will be 2.4 

### Goals:

- Provide an **educational project** for new developers to learn eZ Publish development concepts.
- Deliver a **simple starter solution** for building a PHP-based CMS.
- Enable a lightweight alternative for **personal home page-based CMS** needs.

### The work

This project involves documenting and updating the following:

- All 34+ admin module views.
- All 34+ user module views used or exposed by default.
- All (unknown number of) user module views not used or exposed by default.
- All (unknown number of) module views as authenticated or not.
- All (unknown number of) module views uniue POST/GET Actions Provided Per Module View(s).
- Refactoring and extending existing PHP4/5 code to full PHP 8 compatibility.

### Status Summary: 

- EL5: eZ User Websites Boot (Lead the expected page site wide without errors)
- Extended: eZ Administration Website Boots (loads the UI without fatal or distracting errors)
- Real story: eZ Admin and eZ User Website Currently Require Extensive Development to free eZ Publish Basic from existing implementation limitations resulting in conflicts with the language preventing error free usage on a per siteacccess, module view, module view action(s) per module view.
---
- Default eZPB User Modules Views Boot with Minror warnings or visual errors.
- Default eZPB Admin Module Views Boot With Major warnings or errors per module view set.
- eZ User, eZ SysInfo, eZ Site Manager Are the First 3 Upgraded Default eZPB Admin siteaccess module views to Run 98% without visual or noticeable issues.

---

## Modules Tested with PHP 8.3

- Several to be documented

---

## Admin Modules That Need Work

- ezuser
  - userlist -  Tests as 100% Functional.
  - grouplist - Tests as OK but with 1 - 2 missing icons.
  - useredit/new - Tests as 100% Functional.
  - groupedit/new - Tests as OK but with 7 - 10 warnings.
  - authorlist - Tests as 100% Functional.
  - extsearch - Tests as 100% Functional.
  - sessioninfo - Needs work. Fatal Errors on Page Load.
  - passwordchange - Needs work. Usage error as screen is blank and no form to change password is displayed from admin header link to change password.
- ezsitemanager
  - section/list - Tests as Loading Page OK.
  - cache - Tests as OK but with 1 - 2 warnings.
  - file/list - Tests as Loading Page OK.
  - template/list - Tests as 100% Functional.
  - siteconfig - Tests as OK but with 1 - 2 warnings.
  - menu/list - Tests as Loading Page OK.
  - sqladmin/query - Tests as Loading Page OK. (Non Functional) Fatal Errors On Usage.
- ezsysinfo
  - sysinfo - Tests as Loading Page OK. Verify System Information Valid In Greater Detail.
  - netinfo - Tests as 100% Functional.
  - hwinfo - Tests as 100% Functional.
  - meminfo - Tests as 99% Functional. One critical template system problem requireing refactoring to solve a array to string conversion (Warning: Array to string conversion in /home/ez/public_html/classes/eztemplate.php on line 618).
  - fileinfo - Tests as 100% Functional.
- ezstats
  - overview - Needs work. Fatal Errors on Page Load.
  - entryexitreport - Needs work. Empty template variables on page and no stats.
  - pageviewlist/last/20 - Needs work. Fatal Errors on Page Load.
  - visitorlist/top/20 - Needs work. Fatal Errors on Page Load.
  - refererlist/top/20/ - Needs work. Fatal Errors on Page Load.
  - browserlist/top/25/ - Needs work. Fatal Errors on Page Load.
  - requestpagelist/top/20/ - Needs work. Fatal Errors on Page Load.
  - yearreport - Needs work. Fatal Errors on Page Load.
  - monthreport - Needs work. Empty template variables on page and no stats.
  - dayreport - Needs work. Fatal Errors on Page Load.
- ezquiz
  - game/list - Tests as Loading Page OK. Edit Page Link Displays Errors Before Form.
  - game/new - Needs work. Fatal Errors on Page Load.
- ezmessage
  - list - Tests as Loading Page OK.
  - edit - Needs work. Warnings on Page Load. Fatal errors on user action form submit preview.
- ezbulkmail
  - categorylist - Needs work. Fatal Errors on Page Load.
  - templatelist - Needs work. Fatal Errors on Page Load.
  - drafts - Needs work. Fatal Errors on Page Load.
  - mailedit - Needs work. Fatal Errors on Page Load.
  - templateedit - Tests as Loading Page OK.
  - masssubscribe - Needs work. Fatal Errors on Page Load.
  - userlist - Needs work. Fatal Errors on Page Load.
- ezform
  - form/list - Needs work. Fatal Errors on Page Load.
  - form/new - Needs work. Fatal Errors on Page Load.
- ezadddress
  - phonetype/list - Needs work. Fatal Errors on Page Load.
  - addresstype/list - Needs work. Fatal Errors on Page Load.
  - onlinetype/list - Tests as OK but with 7 - 10 warnings.
  - country/list/ - Needs work. Fatal Errors on Page Load.
- ezmediacatalogue
  - category/list -  Needs work. Fatal errors on user action form submit new media or warnings on edit view link click.
  - category/new - Tests as OK but with 7 - 10 warnings.
  - media/new - Needs work. Fatal Errors on Page Load.
  - unassigned - Empty page on load. Tests as OK.
  - typelist - Tests as 99% Functional. Some Edit View Link Click Warnings On Form.
  - typeedit - Tests as OK but with 7 - 10 warnings.
- ezimagecatalogue
  - image/list - Tests as 99% Functional. Some Edit View Link Click Warnings On Form.
  - category/new - Tests as 80% Functional. Some Warnings Before Form.
  - image/new - Tests as 80% Functional. Some Warnings Before Form.
  - unassigned - Empty page on load. Tests as OK.
- ezfilemanager
  - list - Tests as 80% Functional. Some Warnings on Page Load.
  - folder/new - Tests as 80% Functional. Some Warnings on Page Load.
  - new - Tests as 80% Functional. Some Warnings on Page Load.
  - unassigned - Needs work. Fatal Errors on Page Load.
- ezcalendar
  - typelist - Needs work. Fatal Errors on Page Load.
  - typeedit/new - Needs work. Fatal Errors on Page Load.
- eztodo
  - categorytypelist - Tests as 80% Functional. Some Warnings on Page Load.
  - prioritytypelist - Tests as 80% Functional. Some Warnings on Page Load.
  - statustypelist - Tests as 80% Functional. Some Warnings on Page Load.
- ezcontact
  - company/list - Tests as 80% Functional. Some Warnings on Page Load.
  - person/list - Needs work. Edit buttons all trigger fatal user form load and store Fatal errors.
  - consultation/list -  Needs work. Edit buttons all trigger fatal user form load and store Fatal errors.
  - company/new - Needs work. Massive warnings blocking display.
  - person/new - Needs work. Massive warnings blocking display.
  - projecttype/list - Tests as 80% Functional. Some Warnings on Page Load. Does not store fatal error on form submission.
  - consultationtype/list - Needs work. Fatal Errors on Page Load.
- ezbug
  - unhandled - Tests as 100% Functional. Clicking edit on listed item results in Fatal error.
  - archive - Tests as 100% Functional. Clicking edit on listed item results in Fatal error.
  - priority/edit - Tests as 90% Functional. Fatal errors on submit.
  - priority/list - Tests as 100% Functional.
  - category/list - Tests as 100% Functional.
  - module/list - Needs work. Fatal Errors on Page Load.
  - status/list - Tests as 100% Functional.
- eznewsfeed
  - unpublished - Needs work. Fatal Errors on Page Load.
  - archive - Needs work. Fatal Errors on Page Load.
  - news/new - Tests as 90% Functional. Fatal errors on submit.
  - category/new - Tests as 90% Functional. Fatal errors on submit.
  - sourcesite/new - Needs work. Fatal Errors on Page Load.
  - importnews - Needs work. Warnings and Fatal errors on submit buttons usage.
- ezad
  - archive - Needs work. Warning Errors on Page Load.
  - category/new - Tests as 90% Functional. Warnings on Page Load. Fatal errors on submit.
  - ad/new - Tests as 90% Functional. Warnings on Page Load. Fatal errors on submit.
- ezpoll
  - pollist - Needs work. Tests as 90% Functional. Some warnings on Page Load.
  - polledit/new - Needs work. Tests as 90% Functional. Some warnings on Page Load.
- ezlink
  - category/0 - Tests as 100% Functional.
  - categoryedit/edit/1 - Needs work. Warnings blocking form.
  - unacceptedlist -  Needs work. Warnings on page load.
  - typelist - Needs Further Testing as no data displayed from system. Tests OK.
  - typeedit - Needs work. Warnings blocking form.
  - categoryedit/new - Needs work. Warnings blocking form.
  - linkedit/new - Needs work. Fatal Errors on Page Load.
- ezforum
  - unapprovedlist - Tests as 100% Functional. No errors but no listing.
  - categorylist/ - Tests as 100% Functional. No errors but no listing.
  - categoryedit/new - Needs work. Warnings blocking form.
  - forumedit/new - Needs work. Fatal errors on page load.
- ezarticle
  - archive - Needs work. Tests as 90% Functional. Actions not yet tested as error free. View does not display current articles only categories.
  - article/search - Needs Work. Tests as 80% Functional. Warnings on page blocking display after search.
  - unpublished - Needs testing. No results on page load. Tested Ok.
  - pendinglist - Needs testing. No results on page load. Tested Ok.
  - sitemap - Needs testing. No articles displayed on page load. Tested Ok.
  - topiclist - Needs testing. No errors on page load but Actions need testing.
  - type/list - Needs testing. No errors on page load.
  - type/edit - Needs work. Warnings on page load.
  - categoryedit/new - Needs work. Fatal Errors on Page Load.
  - articleedit/new - Needs work major warnings on page load. Exect errors on article storage.
  - search - Needs major work, fatal error upon search results view POST.

Note: Printable admin view features currently has fatal errors and distracting warnings.

## Admin Module Default Views That Test OK

- ezurltranslator
- ezuser
- ezsysinfo

### Mostly Finished

- ezurltranslator
- ezuser
  - userlist -  Tests as 100% Functional.
  - useredit/new - Tests as 100% Functional.
  - authorlist - Tests as 100% Functional.
  - extsearch - Tests as 100% Functional.
- ezsysinfo - the stats were variable on our vm so this may need improvement on the actual data displayed.
  - sysinfo - Tests as Loading Page OK. Verify System Information Valid In Greater Detail.
  - netinfo - Tests as 100% Functional.
  - hwinfo - Tests as 100% Functional.
  - fileinfo - Tests as 100% Functional.

## Admin Module Menu Views That Test OK

- ezurltranslator
   - urledit - Tests as 100% Functional.


---

## Modules Available

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



## Module Views Available

The following modules views are included in eZ Publish Basic:

- ezuser
  - userlist
  - grouplist
  - useredit/new
  - groupedit/new
  - authorlist
  - extsearch
  - sessioninfo
  - passwordchange
- ezsitemanager
  - section/list
  - cache
  - file/list
  - template/list
  - siteconfig
  - menu/list
  - sqladmin/query
- ezsysinfo
  - sysinfo
  - netinfo
  - hwinfo
  - meminfo
  - fileinfo
- ezstats
  - overview
  - entryexitreport
  - pageviewlist/last/20
  - visitorlist/top/20 
  - refererlist/top/20
  - browserlist/top/25
  - requestpagelist/top/20
  - yearreport
  - monthreport
  - dayreport
- ezquiz
  - game/list
  - game/new
- ezmessage
  - list
  - edit
- ezbulkmail
  - categorylist
  - templatelist
  - drafts
  - mailedit
  - templateedit
  - masssubscribe
  - userlist
- ezform
  - form/list
  - form/new
- ezadddress
  - phonetype/list
  - addresstype/list
  - onlinetype/list
  - country/list/
- ezmediacatalogue
  - category/list
  - category/new
  - media/new
  - unassigned
  - typelist
  - typeedit
- ezimagecatalogue
  - image/list
  - category/new
  - image/new
  - unassigned
- ezfilemanager
  - list
  - folder/new
  - new
  - unassigned
- ezcalendar
  - typelist
  - typeedit/new
- eztodo
  - categorytypelist
  - prioritytypelist
  - statustypelist
- ezcontact
  - company/list
  - person/list
  - consultation/list
  - company/new
  - person/new
  - projecttype/list
  - consultationtype/list
- ezbug
  - unhandled
  - archive
  - priority/edit
  - priority/list
  - category/list
  - module/list
  - status/list
- eznewsfeed
  - unpublished
  - archive
  - news/new
  - category/new
  - sourcesite/new
  - importnews
- ezad
  - archive
  - category/new
  - ad/new
- ezpoll
  - pollist
  - polledit/new
- ezlink
  - category/0
  - categoryedit/edit/1
  - unacceptedlist
  - typelist
  - typeedit
  - categoryedit/new
  - linkedit/new
- ezforum
  - unapprovedlist
  - categorylist
  - categoryedit/new
  - forumedit/new 
- ezarticle
  - archive
  - article/search
  - unpublished
  - pendinglist
  - sitemap
  - topiclist
  - type/list
  - type/edit
  - categoryedit/new
  - articleedit/new
  - search

---

## Community eZ Publish 2 Modules

The worldwide eZ community on the internet likely holds old copies of custom modules. Some community members may be willing to contribute their modules for inclusion in eZ Publish Basic.

## Installation

Follow these steps to set up eZ Publish Basic:

1. Clone the repository:
   ```bash
   git clone https://github.com/se7enxweb/ezpublishbasic.git

2. Navigate to the project directory:
   ```bash 
   cd ezpublishbasic

3. Install dependencies:
   ```bash
    composer install

4. Configure your environment:

5. Update settings files as needed.

6. Ensure your server supports PHP 8.

7. Set up your database and initialize the application in your web browser.

# Contributions

We welcome contributions from the community! To get involved:

1. Fork the repository. 
2. Create a new branch for your feature or bugfix. 
3. Submit a pull request.

Check out the Contributing Guidelines for more details.

---

Developed with ❤️ by 7x.

---

## About (Original)

Originally released as **eZ Publish Version 2** by eZ Systems (now Ibexa) in 1999, this CMS has a storied history of innovation.

In mid-2001, **7x** began development based on **eZ Community 2.x**, a PHP/MySQL CMS derived from eZ Publish 2.2.x.

### Notable Milestones:
- Released in part as **eZ Community 2.3** around 2006.
- Enhanced stability and refined CMS/e-commerce features for USA-based users.
- This repository is a direct descendant of the deprecated `ezcommunity2-contributions` repository, restructured for simpler maintenance and support.

---