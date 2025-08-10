<?php /*
#?ini charset="utf-8"?
# eZ Publish configuration file.
#
# NOTE: It is not recommended to edit this files directly, instead
#       a file in override should be created for setting the
#       values that is required for your site. Either create
#       a file called settings/override/site.ini.append or
#       settings/override/site.ini.append.php for more security
#       in non-virtualhost modes (the .php file may already be present
#       and can be used for this purpose).


/*
#
# If you have a production site and a staging site with different settings
# you can create a directory called "override" in the main publish directory.
#
# In that directory you can have a completely different site.ini file which will
# be used instead of the correct one (this). This is great if the working
# site.ini is commited to CVS and you don't want to do changes to it.
#
# You can also create a file called site.ini.append in the override directory,
# that file will then be appended and override only those settings set in that
# file. This function can be used to select a staging database instead of the
# production database, but in all other parts use the correct site.ini settings.
#

########################################
# Site Accessibility Settings for eZ Publish Basic
########################################

[site]
SitePath=/home/demo/doc/
SiteURL=basic.demo.ezpublish.one
UserSiteURL=basic.demo.ezpublish.one
AdminSiteURL=admin.basic.demo.ezpublish.one
UserSiteProtocol=https
AdminSiteProtocol=https


########################################
# Debug Output Settings for eZ Publish Basic
########################################

# DebugOutput=enabled
# DebugOutput=disabled
DebugOutput=disabled
DebugTemplate=disabled
DebugLanguage=disabled
DebugDatabaseTransactions=disabled


########################################
# Design Settings for eZ Publish Basic
########################################

# SiteDesign=standard
# SiteDesign=ecommerce
SiteDesign=standard
SiteStyle=white

AdminDesign=ezpublish
AdminSiteStyle=white


########################################
# Database Settings for eZ Publish Basic
########################################
Database=ezpbasicdbname
User=ezpbasicdbuser
Password=db-ezpbasic-secret-key-2001
Server=localhost
# Port=3306
# Port=27017
Port=3306
# If you need to specify the socket to use with mysql use this variable
MySQLSocket=disabled


########################################
# Database Implementation Setting for eZ Publish Basic
########################################
## DatabaseImplementation setting can be set to mysql|postgresql|sqlite|informix
DatabaseImplementation=mysql


########################################
# SQLite Database Settings for eZ Publish Basic
########################################
DatabaseSQLitePath=var/site/db/demo
DatabaseSQLiteFile=site-demo.db


########################################
# Site Meta Information: Title, Description 
# and Keywords Settings for eZ Publish Basic
########################################

SiteTitle=eZ Publish Basic a community based framework and website cms
Keywords=eZPublishBasic CMS Framework Website Content Management System e-commerce ecommerce website building cms 7x tools web application system.
# Example site Language setting options:
# Note: You can create any language you wish to support via file override via setting feature.
# Note: en_US support by default is the next development target for eZ Publish 2.4.0.1

# Replace value with USA English setting option en_US as shown bellow.
# Language=en_US
# Replace value with British English setting option en_GB as shown bellow.
# Language=en_GB
Language=en_US


########################################
# External API Connection Key 
# Settings for eZ Publish Basic
########################################
# Froogle export
UserFroogle=remote-api-service-user-name
PasswordFroogle=remote-api-service-user-name
ServerFroogle=hedwig.google.com

# yahoo export
UserYahoo=remote-api-service-user-name
PasswordYahoo=remote-api-service-user-name
ServerYahoo=ftp.productsubmit.adcentral.yahoo.com

# shipping module info
UserUPS=remote-api-service-user-name
AccessUPS=CBA8236E8100AC06
PassUPS=remote-api-service-user-name
UserUSPSServer=http://production.shippingapis.com/ShippingAPI.dll
UserUSPS=remote-api-service-user-name
PassUSPS=remote-api-service-user-name

# Meta content variable
SiteAuthor=7x
SiteCopyright=7x &copy; 1998 - 2025
SiteDescription=eZ publish basic - the web application suite
SiteKeywords=Content Management System, CMS, e-commerce, ecommerce, website, building, cms

# HelpLanguage=en_GB
HelpLanguage=en_US
SiteTmpDir=/tmp/


# can be e.g. /article/view/42 or disabled
# Default Page : can be e.g. /article/view/42 or disabled
# DefaultPage=/article/articleview/44/1/9/
# DefaultPage=/article/frontpage/3/
# DefaultPage=/article/homepage/
DefaultPage=/article/frontpage/1/

EnabledModules=eZArticle;eZTrade;eZForum;eZLink;eZPoll;eZAd;eZNewsFeed;eZBug;eZContact;eZTodo;eZCalendar;eZGroupEventCalendar;eZFileManager;eZImageCatalogue;eZMediaCatalogue;eZAddress;eZForm;eZBulkMail;eZMessage;eZQuiz;eZSurvey;eZTip;eZStats;eZURLTranslator;eZSiteManager;eZUser;eZSysinfo
EnabledAdminModules=eZArticle|ezarticle;eZTrade|eztrade;eZForum|ezforum;eZLink|ezlink;eZPoll|ezpoll;eZAd|ezad;eZNewsFeed|eznewsfeed;eZBug|ezbug;eZContact|ezcontact;eZTodo|eztodo;eZCalendar|ezcalendar;eZGroupEventCalendar|ezgroupeventcalendar;eZFileManager|ezfilemanager;eZImageCatalogue|ezimagecatalogue;eZMediaCatalogue|ezmediacatalogue;eZAddress|ezaddress;eZForm|ezform;eZBulkMail|ezbulkmail;eZMessage|ezmessage;eZQuiz|ezquiz;eZSurvey|ezsurvey;eZTip|eztip;eZStats|ezstats;eZURLTranslator|ezurltranslator;eZSiteManager|ezsitemanager;eZUser|ezuser;eZSysinfo|ezsysinfo;eZError|ezerror

ModuleList=eZArticle;eZTrade;eZForum;eZLink;eZPoll;eZAd;eZNewsFeed;eZBug;eZContact;eZTodo;eZCalendar;eZGroupEventCalendar;eZFileManager;eZImageCatalogue;eZMediaCatalogue;eZAddress;eZForm;eZBulkMail;eZMessage;eZQuiz;eZSurvey;eZTip;eZStats;eZURLTranslator;eZSiteManager;eZUser;eZSysinfo

URLTranslationKeyword=section-standard;section-intranet;section-trade;section-news;7x;games;section-ecommerce;contact;gallery;shop;forums;links;/sitemap/article;/sitemap/product;news;reviews;reports;/account/logout
CacheHeaders=true
CheckDependence=enabled
LogDir=var/log/
LogFileName=error_reports
DemoSite=disabled
ModuleTab=enabled
Sections=enabled
DefaultSection=1

# Site cache now works on simple sites, with no user specific data. E.g. an pure article site.
# SiteCache=enabled
SiteCache=disabled
# How long before a cache times out, cache timeout setting in minutes. Default: 2 Hours;
SiteCacheTimeout=120

# Charsets for admin that can be used to display different languages
# You can leave this value blank to disble this option
CharsetSwitch=disabled
Charsets=en_US-English;en_GB-English;en_UC-Unicode;no_NO-Norwegian;ru_RU-Russian;lv_LV-Latvian;
#fr_FR-French;it_IT-Italian;de_DE-German;

# eZ publish image import scipt
SiteTmpDir=/home/ezpbasiclatest/doc/var/site/import/images/
# eZ publish froogle export script
SiteFroogleExportDir=/home/ezpbasiclatest/doc/var/site/export/froogle/
# eZ publish yahoo export script
SiteYahooExportDir=/home/ezpbasiclatest/doc/var/site/export/yahoo/



########################################
# Framework Specific Settings aka 
# the Framework Main Settings.
########################################

[classes]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageConversionProgram=convert
DefaultSection=1

########################################
# Settings specific to eztrade's 
# unique homepage module view operations.
########################################

[homepage]
DealerDescription=Shop for products that fit your needs
DistributorDescription=Shop for products by manufacturer
DistSplit=6
DistProdCats=16;185;13;339;338;15;12;183;369;439;247;481;521
DistThumbWidth=115
DistThumbHeight=65
DealerSplit=5
DealerProdCats=152;80;425;282;170;195;82;83;88;85;87;86
DealerThumbWidth=115
DealerThumbHeight=65
ArticleLimit=4
ArticleThumbWidth=100
ArticleThumbHeight=100
HotDealsLimit=3
HotDealsThumbWidth=100
HotDealsThumbHeight=100
GalleryLimit=6
GalleryThumbWidth=100
GalleryThumbHeight=75
CommentLimit=5
CommentLetterLimit=250

########################################
# Module Specific Settings aka 
# the Module Main Settings.
########################################

[eZAboutMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_US


[eZAdMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_US
DefaultCategory=1
DefaultSection=1


[eZAddressMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
DocumentRoot=./kernel/ezaddress/
Language=en_US
MaxCountryList=11
MaxRegionList=11
DefaultSection=1


[eZArticleMain]
AdminTemplateDir=templates/standard/
# TemplateDir=templates/standard/
# ImageDir=/images/standard/
TemplateDir=templates/ecommerce
ImageDir=/images/ecommerce/
ThumbnailImageWidth=150
ThumbnailImageHeight=150
ThumbnailGroupImageWidth=150
ThumbnailGroupImageHeight=150
SmallImageWidth=100
SmallImageHeight=100
MediumImageWidth=200
MediumImageHeight=200
LargeImageWidth=300
LargeImageHeight=300
# size of images in the list in articleview
ListImageWidth=150
ListImageHeight=150
ShowModuleLinker=true
ModuleList=eZContact;eZArticle;eZTrade
DefaultSectionName=Related Links
ArticleLinkSections=Related Articles
Language=en_GB
Generator=qdom
PageCaching=enabled
CapitalizeHeadlines=disabled
UserComments=enabled
DefaultLinkText=Read more
AdminListLimit=20
UserListLimit=20
PublishNoticeReceiver=nospam@example.org
PublishNoticeSender=nospam@example.org
PublishNoticePadding=3
AuthorLimit=10
AuthorArticleLimit=10
UserSubmitArticles=enabled
MixUnpublished=disabled
GrayScaleImageList=disabled
CanUserPublish=disabled
SearchListLimit=10
SearchWithinSections=disabled
CategoryImageWidth=50
CategoryImageHeight=50
DefaultSection=5
HeadlinesImageWidth=50
HeadlinesImageHeight=50
LowerCaseManualKeywords=enabled
UserEditOwnArticle=enabled
# Add ability for fast edit inside the article, url translation,
# administrator can easy assign shortcuts for the articles
AdminURLTranslator=disabled

# Ability to use XML tags inside the category description, administrator have a possibility
# to format category description. This switch must be set before category creation. If you
# change this switch after some content created, YOU WILL LOOSE CATEGORIES !!!
# This feature is not currently supported in eZ publish desktop edition.
CategoryDescriptionXML=disabled

# if the article view should show the path of the categorydefinition even if linked from 
# other category
ForceCategoryDefinition=disabled

MailToFriendSender=nospam@example.org

# add extra tags here if you want to have your own custom tags in eZ publish
#
CustomTags=logo

# How often a word should be present to be ignored 0-1 (1==100%)
# StopWordFrequency=0.7
StopWordFrequency=10.0

# HTTP Referer Check URL URI
FromURL=/article/

[eZArticleRSS]
# Channel Title, Link, Description and Language
Title=eZ publish 2
Link=http://ezcommunity.net/
Description=News
Language=en_US
# Channel Image
# Width should be 1-144, Height should be 1-400 (default: 31)
Image=/design/standard/images/rss_image.gif
# Category, from which the articles will be fetched (0=complete site) 
# and number of articles in Feed
CategoryID=0
Limit=10

[eZBugMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_US
DefaultSection=2
MailAccount=bug
MailPassword=tjobing
MailServer=mail.example.org
MailServerPort=110
MailReplyToAddress=bug@example.org


[eZBulkMailMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
DefaultSection=4
UseBulkmailSenderDefaults=enabled
BulkmailSenderAddress=admin@example.org
BulkmailSenderName=Administrator
UseEZUser=disabled


[eZCalendarMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_US
DayStartTime=08:00
DayStopTime=18:00
DayInterval=00:30
DefaultSection=1
OnlyShowTrustees=disabled


[eZCCMain]
PID=fi345g121net32it77
Language=0
VendorID=252
i=
Currency=0
p=0
DefaultSection=1


[eZContactMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
DocumentRoot=./kernel/ezcontact/
Language=en_US
CategoryImageWidth=100
CategoryImageHeight=100
MaxPersonConsultationList=5
MaxCompanyConsultationList=5
MaxPersonList=25
MaxCompanyList=25
CompanyOrder=name
MaxCountryList=11
MaxRegionList=11
LastConsultations=5
AddressMinimum=1
AddressWidth=1
PhoneMinimum=2
PhoneWidth=2
OnlineMinimum=2
OnlineWidth=2
CompanyViewLogin=true
CompanyEditLogin=true
ShowCompanyContact=true
ShowCompanyStatus=true
DefaultSection=2
ShowAllConsultations=disabled
ShowRelatedConsultations=enabled

[eZErrorMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
DefaultSection=1


[eZExampleMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
DefaultSection=1


[eZFileManagerMain]
AdminTemplateDir=templates/standard/
#TemplateDir=templates/standard/
TemplateDir=templates/ecommerce/
ImageDir=images/standard/
Language=en_US
SearchListLimit=40
DefaultSection=2
AutoSyncronize=1
LocalSyncronizeDir=/home/jhe/sync
SyncronizeReadGroup=2
SyncronizeWriteGroup=1
SyncronizedFilesOwner=1
Limit=50
ShowUpFolder=enabled
DownloadOriginalFilename=true
# bulk file/document import
# FileCat=destination image category number
FileCat=1
# SyncDir=path to images for import
SyncDir=/home/ezpbasiclatest/doc/var/site/import/files/

[eZFormMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
DefaultSection=1
DefaultElementName=New element
AdminFormListLimit=20
UseDefaultRedirectPage=disabled
DefaultRedirectPage=/select/url
UseDefaultInstructionPage=disabled
DefaultInstructionPage=/select/url
CreateEmailDefaults=disabled
# HTTP Referer Check URL URI
FromURL=/article/articleview/
FromURL2=/form/form/process/


[eZForumMain]
AdminTemplateDir=templates/standard/
Language=en_US
# TemplateDir=templates/standard/
TemplateDir=templates/ecommerce/
DocumentRoot=./kernel/ezforum/
ReplyPrefix=RE: 
PageCaching=disabled
MessageUserLimit=40
SearchUserLimit=40
SearchAdminLimit=10
SimpleUserList=40
MessageAdminLimit=20
ForumUserLimit=30
UnApprovdLimit=10
FutureDate=In the future
AllowedTags=<a>,<i>,<b>,<blockquote>,<p>,<u>,<img>,<br><div>
ReplyStartTag=<blockquote>
ReplyEndTag=</blockquote>
ReplyTags=disabled
AllowHTML=enabled
AnonymousPoster=Anonymous
ReplyAddress=noreply@example.org
ShowReplies=enabled
# Number of days to count messages as new
NewMessageLimit=10
ShowMessageLimit=10
DefaultSection=2
#
# Shows which modules are linked to what forum categories (format eZModule:ForumID)
# Not needed for eZArticle yet
LinkModules=eZTrade:5,eZImageCatalogue:6
ExcludeFeatureForums=disabled

[eZImageCatalogueMain]
# AdminTemplateDir=templates/standard/
AdminTemplateDir=templates/ecommerce/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_US
ImageViewWidth=500
ImageViewHeight=600
ThumbnailViewWidth=150
ThumbnailViewHight=150
TinyImageWidth=40
TinyImageHeight=40
ShowOriginal=enabled
ListImagesPerPage=15
ListImagesPerRow=3
DefaultSection=2
#
# Failed Image Variation (Creation), File/Dir/System File Permissions / Security
# DefaultFailedImage=/home/ezpbasiclatest/doc/kernel/ezimagecatalogue/admin/images/failedimage.gif
# DefaultFailedImage=/home/ezpbasiclatest/doc/kernel/ezimagecatalogue/admin/images/failedimage_missing.gif
DefaultFailedImage=kernel/ezimagecatalogue/admin/images/failedimage_missing.gif
#
# bulk image import
# PicCat=destination image category number
PicCat=1
# SyncDir=path to images for import
SyncDir=/home/ezpbasiclatest/doc/var/site/import/images/
# enabled = use normal header/footer; disabled = use print header/footer
SlideShowHeaderFooter=enabled
SlideShowOriginalImage=disabled
#enables user comments mod
UserComments=enabled
RandImageCategory=2

[watermark]
watermarkEnabled=false
#watermarkEnabled - true | false
# position - south | north | east | west | center | southeast | southwest | #northeast | northwest. Default is south.
position=northeast
# watermarkImage - Path to watermark image.
# watermarkImage=design/ecommerce/images/watermarks/ezwater.gif
watermarkImage=design/ecommerce/images/watermarks/ezwater-br-brightglow.png
# watermarkImageBr=design/ecommerce/images/watermarks/ezwater-br.png
watermarkImageBr=design/ecommerce/images/watermarks/ezwater-br-brightglow.png
# watermarkImageBrSmall=design/ecommerce/images/watermarks/ezwater-br-small.png
watermarkImageBrSmall=design/ecommerce/images/watermarks/ezwater-br-brightglow-small.png
#
# minWidth - minimum width set in pixels. Default is 400.
# minWidth=400
# minWidth=110
minWidth=200
# minHeight - minimum height set in pixels. Default is 400.
# minHeight=400
# minHeight=110
minHeight=200

[eZLinkMain]
AdminTemplateDir=templates/standard/
DocumentRoot=./kernel/ezlink/
# TemplateDir=templates/standard/
TemplateDir=templates/ecommerce/
PageCaching=disabled
Language=en_US
CategoryImageWidth=150
CategoryImageHeight=150
LinkImageWidth=150
LinkImageHeight=150
UserSearchLimit=40
UserLinkLimit=40
AdminLinkLimit=20
AdminAcceptLimit=20
AdminSearchLimit=20 
DefaultSection=1
AcceptSuggestedLinks=0
CategoryIDSequence=

[eZMailMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
DefaultSection=2
ReplyPrefix=Re: 
HTMLMail=enabled
MailPerPageDefault=40


[eZMediaCatalogueMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_US
ListMediaPerPage=10
ListMediaPerRow=4
DefaultSection=1
SyncDir=/home/ezpbasiclatest/doc/var/site/import/media/


[eZMessageMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
DefaultSection=1


[eZModuleMain]
Language=en_US
DefaultSection=1


[eZNewsFeedMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_US
PageCaching=disabled
DefaultSection=4


[eZPollMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_US
DocumentRoot=./kernel/ezpoll/
PageCaching=disabled
DefaultSection=1
AllowDoubleVotes=disabled

# if eZ poll should check IP or cookie
# valid values: ip | cookie
DoubleVoteCheck=ip


[eZQuizMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
AdminListLimit=30
ListLimit=20
PageCaching=disabled
ScoreLimit=10
ScoreCurrent=enabled
DefaultSection=1


[eZSearchMain]
AdminTemplateDir=templates/standard/
# TemplateDir=templates/standard/
TemplateDir=templates/ecommerce/
Language=en_US
#Contains the modules to search through. E.g. eZArticle;eZForum
# SearchModules=eZArticle;eZContact;eZForum;eZTrade
# SearchModules=eZTrade;eZArticle;eZImageCatalogue;eZForum;eZLink;eZContact
SearchModules=eZTrade;eZArticle;eZImageCatalogue;eZForum;eZLink
DefaultSection=1


[eZSiteManagerMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
AdminListLimit=20
DefaultSection=1


[eZStatsMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_US
StoreStats=enabled
DefaultSection=1

[eZSurveyMain]
AdminTemplateDir=templates/standard
TemplateDir=templates/standard
Language=en_GB
DefaultSection=3
SurveyListLimit=5
ReportTemplateID=8

[eZSysinfoMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
DefaultSection=1

[eZTipMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_GB
DefaultCategory=1
DefaultSection=3
#TipLocations=tipLocation1;tipLocation2;tipLocation3
TipLocations=tipLocation1

[eZTodoMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_US
DocumentRoot=./kernel/eztodo/
NotDoneID=2
DoneID=1
DefaultSection=2


[eZTradeMain]
AuthorizeNetMode=test
# AuthorizeNetMode=test
# AuthorizeNetMode=standard
#
# Third Key - new May 31, 2005
AuthorizeNetKey=remote-api-service-user-name
AuthorizeNetLogin=remote-api-service-user-name
AuthorizeNetPassword=remote-api-service-user-name

FreeShippingUser=0
FreeShippingDealer=0
SingleBoxMaxWeight=65
ShippingMarkupPct=0
ShippingMarkupFlat=3.50


# UPS
UPSOFF=1
Adminstate=RI
Adminzip=02883
Admincountry=US

# UPS : Dependancies
USExcept=01;02;03;12
USInclude=01;02
CAups=11;07;08
INups=07;08
Defaultups=07;08
Adminstate=RI
Adminzip=02883
Admincountry=US
FlatUPSService=03

# AdminTemplateDir=templates/standard/
AdminTemplateDir=templates/ecommerce/
DocumentRoot=./kernel/eztrade/
# TemplateDir=templates/standard/
TemplateDir=templates/ecommerce/

ImageDir=/images/standard/
Language=en_US
PageCaching=disabled
#Order Disclaimer
OrderDisclaimer=enabled
OrderDisclaimerText=By submitting this order, I hereby agree to the Terms and Conditions, Return Policy and Shipping Policy
ShippingDisclaimerText=Please call us before ordering to confirm availability if you need the items delivered by a specific date!
OrderSenderEmail=nospam@example.org
OrderReceiverEmail=nospam@example.org
mailToAdmin=nospam@example.org
HotDealColumns=1
HotDealImageWidth=40
HotDealImageHeight=40
Checkout=standard
MainImageWidth=350
MainImageHeight=350
SmallImageWidth=150
SmallImageHeight=150
ThumbnailImageWidth=140
ThumbnailImageHeight=140
ShowBillingAddress=enabled
ForceSSL=enabled
ProductLimit=45
AdminProductLimit=50
ProductSearchLimit=25
PriceGroupsEnabled=true
RequireUserLogin=disabled
StandardOptionHeaders=
MinimumOptionHeaders=1
MinimumOptionValues=1
SimpleOptionHeaders=true
ShowQuantity=true
ShowNamedQuantity=true
RequireQuantity=false
ShowOptionQuantity=true
ShowModuleLinker=true
# ModuleList=eZContact;eZArticle
ModuleList=eZContact;eZArticle;eZTrade;eZFileManager
DefaultSectionName=Related Links
ProductLinkSections=Related Products
DiscontinueQuantityless=false
PurchaseProduct=true
MailEncrypt=none
RecipientGPGKey=KeyHolder
ApacheUser=UserApacheRunsAs
MaxSearchForProducts=200
DefaultSection=5
CategoryImageWidth=150
CategoryImageHeight=150
ShowOrderStatusToUser=true
# enable product reviews (extension of eZForum)
UserReviews=enabled
# If this setting is set to enabled the system will use the countrylist to decide 
# if prices and totals will include VAT.
CountryVATDiscrimination=enabled

# If this setting is set to enabled prices shown to anonymous users will include VAT.
PricesIncVATBeforeLogin=enabled

# If this setting is set to enabled prices shown to logged in users will include 
# VAT ( This will be overridden by the CountryVATDiscrimination variable ).
PricesIncludeVAT=enabled

ShowExTaxColumn=enabled
ShowIncTaxColumn=enabled
# If this setting is set to enabled order confirmation email will include 
# VAT ( This will be overridden by the CountryVATDiscrimination variable ).
EmailShowExTaxColumn=enabled
EmailShowIncTaxColumn=enabled
#ShowTaxBasis=show tax basis details in cart/checkout
ShowTaxBasis=disabled
#TaxRegions=;-delimited eZRegion IDs to charge sales tax
TaxRegions=38
# This is the columns which will be shown in the admin interface and should be
# set to enabled since most countries require you to have this information.
AdminShowExTaxColumn=enabled
AdminShowIncTaxColumn=enabled

# This setting will be overridden by the ShowExTaxColumn setting.
ShowExTaxTotal=enabled

# Is the number of columns which are the difference between the product list and the totals below!
# Default is 9 - 3 = 6
ColSpanSizeTotals=6

# Send e-mail to seller n days before sold service expires (-1 dissables)
EmailBeforeExpire=2

# Should TAX be enabled by default if user is not logged in?
NoUserShowVAT=disabled

ShowPrintableRecieptLink=enabled
ReviewLimit=25

#
#DefaultWeight=default item weight if none assigned
DefaultWeight=10
#
## Import
#CSVImportPath=detailed path to filename for CSV file import
CSVImportPath=/home/ezpbasiclatest/doc/import/importcsv/products.csv
#CSVImportCat=target product category for CSV file import
CSVImportCat=312
#CSVDelimiter=CSV file delimiter
CSVDelimiter=,
#DefaultDealerPriceGroup=default dealer price group for CSV file import
DefaultDealerPriceGroup=2
# ####################################
# SETI integration variables below
SetiUser=fulluser
SetiPassword=codel
Code=remote-api-service-user-name
Version=2.0
#UPSXMLShipping=enabled/disabled, whether to enable UPS XML rates retrieval
UPSXMLShipping=disabled
#USPSXMLShipping=enabled/disabled, whether to enable US Post Office XML rates retrieval
USPSXMLShipping=disabled
#UPSMaxBoxWeight=Max weight limit for a single UPS shipment
UPSMaxBoxWeight=50
#USPSWeightLimit=Max weight limit for domestic USPS shipments
USPSWeightLimit=50
#HandlingFee=Amount for handling fee
HandlingFee=2

#StateTaxBilling=enable state tax using billing address
StateTaxBilling=enabled
#StateTaxShipping=enable state tax using shipping address
StateTaxShipping=enabled

CategoryListProductImages=enabled

[Checkout]
#PaymentMethods=allowed payment methods, Name1|file1.php;Name2|file2.php
#Allowable:
#PaymentMethods=VISA/MC|visa.php;Paypal|paypal.php;Invoice|invoice.php
#DO NOT delete or alter sequence once live!  Add new methods to end.
#First method listed is default
PaymentMethods=VISA/MC|visa.php;Paypal|paypal.php;Invoice|invoice.php

#PaypalMode=Paypal (LIVE) or Sandbox (TEST)
#see www.paypal.com or www.sandbox.paypal.com for info
PaypalMode=Sandbox
#PaypalEmail=primary Paypal email/login
PaypalEmail=admin@example.org
#CurrencyCode=currency code used: USD, EUR, GBP, CAD, or JPY
CurrencyCode=USD
#SiteLogo=complete URL of the 150x50-pixel image you would like to use as your logo. 
#Only recommended for SSL-hosted images (https://), else buyer will see security popup
SiteLogo=
#PageStyle=Sets the Custom Payment Page Style for Paypal payment pages (optional)
PageStyle=
#LanguageCode=Buyer Paypal checkout language. US (US English), UK (UK English),
#DE (German), JP (Japanese)
LanguageCode=US
#Variables to support Authorize.net payment (future gateway)
#TransKey=authorize.net merchant transaction key
AuthTransKey=
#AuthLogin=authorize.net merchant login
AuthLogin=
#AuthPassword=authorize.net merchant password
AuthPassword=

[eZURLTranslatorMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
Language=en_US
DefaultSection=1


[eZUserMain]
AdminTemplateDir=templates/standard/
# TemplateDir=templates/standard/
TemplateDir=templates/ecommerce/
ImageDir=/images/standard/
Language=en_US
DocumentRoot=./ezuser/
AnonymousUserGroup=2
SelectCountry=enabled
SelectRegion=enabled
UserWithAddress=enabled
RequireUserLogin=disabled
SimultaneousLogins=enabled
DefaultSimultaneousLogins=0
MaxUserList=75
DefaultCountry=240
DefaultRegion=2
# DefaultRedirect=/

#DefaultRedirect=/user/confirmation
DefaultRedirect=/user/withaddress
DefaultSection=1
ReminderMailFromAddress=nospam@example.org
RequireAddress=disabled
RequireFirstAddress=disabled
# RequireFirstAddress=enabled

OverrideUserWithAddress=disabled
#OverrideUserWithAddress=ezuser/user/userwithaddress_full.php
UserPersonLink=enabled

[eZSurveyMain]
AdminTemplateDir=templates/standard
TemplateDir=templates/standard
Language=en_GB
DefaultSection=2
SurveyListLimit=25
ReportTemplateID=8

[eZGroupEventCalendarMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_US
DayStartTime=00:00
DayStopTime=24:00
DayInterval=00:15
Priority=2
Status=0
SubGroupSelect=disabled
TwelveHourSelect=enabled
MinutesSelectInterval=15
UserComments=enabled
TruncateTitle=enabled
TruncateTitleSize=20
LinkModules=eZGroupEventCalendar:1000
YearsPrint=19
DefaultSection=5

[eZXMLRPC]
UserIndex=/index.php

########################################
# END OF FILE
########################################

*/ ?>