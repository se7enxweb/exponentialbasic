# eZ Publish Basic - About Software / Project Documentation

## About

We are actively working to bring **eZ Publish Basic 2.4.0** to the general public.

eZ Publish 2.4 is the completed work started by 7x years ago to upgrade eZ Publish 2 Kernel Implementation of a easy to use PHP Personal Home Page CMS or Community Website Building Platform to more modern PHP versions.

## The story

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

- EL5: eZ User Websites Boot (Expected pages load correctly site wide without errors)
- Extended: eZ Administration Website Boots (loads the UI without fatal or distracting errors) yet the eZ Admin Website requires additional refactoring for each admin module to work correctly in real life usage.
- Real story: eZ Admin and eZ User Website Currently Require Extensive Development to free eZ Publish Basic from existing implementation limitations resulting in conflicts with the language preventing error free usage on a per siteacccess, module view, module view action(s) per module view.

---

- Default eZPB User Modules Views Boot with Minror warnings or visual errors.
- Default eZPB Admin Module Views Boot With Major warnings or errors per module view set.
- eZ User, eZ SysInfo, eZ Site Manager Are the First 3 Upgraded Default eZPB Admin siteaccess module views to Run 98% without visual or noticeable issues.

### Modules Tested with PHP 8.3

- All modules have been tested, documented and bugfixed to run with known patterns of php4 code which still generate warnings and fatal errors upon usage beyond and including page loading.

#### Remaining work to provide full PHP 8.3+ Compatibility

The remaining work to test, bugfix and document all modules individually as PHP8 Compatible

- Current efforts surround going through each admin and user module views per module and fixing all warnings and errors on initial page load. (Page load error free)
- Next efforts surround going through each admin and user module views per module and fixing all usage / interaction / form submissions / CRUD operations php warnings and errors on normal module view Actions usage (Features usage error free). Most time consuming phase.
- Follow up efforts surround going through each admin and user module views per module an additional time and making certain the actual output is sane and correct for the given database content and feature interactions (Actions; Feature usage accurate and valid results output to user every time). This will require additional documentation to ensure repeatable results.
- Final efforts surround going through all module views with various error logging being monitored to capture debug, errors, warnings, any output that creates a log entry will be reviewed and if needed refactored as needed. This should be automated with testing software in the future.

# About (Historical)

Originally released as **eZ Publish Version 2** by eZ Systems (now Ibexa) in 1999, this CMS has a storied history of innovation.

In mid-2001, **7x** began development based on **eZ Community 2.x**, a PHP/MySQL CMS derived from eZ Publish 2.2.x.

# Notable Milestones:

- Release framework update to provide composer based autoloads using a free GPL solution from eZ Publish Legacy a script and classes which creates the required autoloads for composer from all detected classes in the installation.

- Released stable finished work (only small bugs **might** remain) **eZ Publish Basic 2.4.0.0** in 2024/12 as a quite release of a christmas gift from Graham Brookins and 7x to the eZ Community.

- Released (as is / example work in progress) **eZ Community 2.3** around 2006.

- Enhanced stability and refined CMS/e-commerce features for USA-based users. (Basis of USA States Support (eZ Region) code was implemented into eZ Address module)

- This repository is a direct descendant of the deprecated `ezcommunity2-contributions` repository, restructured for simpler maintenance and support.