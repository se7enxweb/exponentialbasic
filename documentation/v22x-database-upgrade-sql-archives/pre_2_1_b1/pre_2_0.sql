#
#
# SQL upgrade information for Exponential Basic pre 2.0
#
# ----------------------------------------------

NOTE: To upgrade the database from version 1.0.1 to 1.0.2 
      run the following SQL commands:

create table eZArticle_ArticleCategoryDefinition( ID int primary key auto_increment, ArticleID int not null, CategoryID int not null ); 
INSERT INTO eZArticle_ArticleCategoryDefinition ( ArticleID, CategoryID )  SELECT DISTINCT ArticleID, CategoryID from eZArticle_ArticleCategoryLink;  

NOTE: To upgrade the database from 1.0.2 to 1.0.3 and higher 
      run the following SQL commands:
 
ALTER TABLE eZSession_Session ADD Created timestamp;
ALTER TABLE eZSession_Session ADD LastAccessed timestamp;
ALTER TABLE eZSession_Session ADD SecondLastAccessed timestamp;

ALTER TABLE eZUser_Group ADD SessionTimeout int default 60;
 
CREATE TABLE eZArticle_ArticleForumLink( ID int primary key auto_increment, ArticleID int not NULL, ForumID int not NULL );

CREATE TABLE eZForum_ForumCategoryLink( ID int primary key auto_increment, ForumID int not null, CategoryID int not null );
INSERT INTO eZForum_ForumCategoryLink ( ForumID, CategoryID )  SELECT ID, CategoryID from eZForum_Forum;
ALTER TABLE eZForum_Forum DROP CategoryID;

NOTE: To upgrade the database from 1.0.3 to 1.0.4 and higher 
      run the following SQL commands:

ALTER TABLE eZContact_Address DROP AddressType;
ALTER TABLE eZContact_Address ADD AddressTypeID int;

NOTE: To upgrade the database from 1.0.5 and higher run the following SQL commands:


NOTE: Upgrading to 2.0 beta 1 is NOT recommened, please follow the notes if you still want to upgrade. And give feedback when updating.


Template updates
----------------
Put this new template into ezarticle/user/templates/{your design}/articleview.tpl
<!-- BEGIN attached_file_list_tpl -->
<h3>{intl-attached_files}:</h3>
<!-- BEGIN attached_file_tpl -->
<a href="/filemanager/download/{file_id}/{original_file_name}/">{file_name}</a><br />
<!-- END attached_file_tpl -->

<!-- END attached_file_list_tpl -->


# A new block template in the wishlist.tpl file:

		  <!-- BEGIN is_bought_tpl -->
		  {intl-is_bought}
		  <!-- END is_bought_tpl -->

		  <!-- BEGIN is_not_bought_tpl -->
		  {intl-is_not_bought}
		  <!-- END is_not_bought_tpl -->



Site.ini updates
-----------------
SiteURL=yoursite.com

[eZContactMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
DocumentRoot=./ezcontact/
Language=en_GB
CategoryImageWidth=100
CategoryImageHeight=100
MaxPersonConsultationList=5
MaxCompanyConsultationList=5
MaxPersonList=10
MaxCountryList=11
LastConsultations=5
AddressMinimum=1
AddressWidth=1
PhoneMinimum=3
PhoneWidth=3
OnlineMinimum=2
OnlineWidth=3

[eZBugMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_GB

[eZAddressMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
DocumentRoot=./ezaddress/
Language=en_GB
MaxCountryList=11

[eZArticleMain]
ArticleListLimit=5
AdminListLimit=5
UserListLimit=5
PublishNoticeReceiver=bf@ez.no
PublishNoticeSender=bf@ez.no

[eZImageCatalogueMain]
ThumbnailViewWidth=150
ThumbnailViewHight=150

[eZAdMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_GB

[eZCalendarMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_GB
DayStartTime=08:00
DayStopTime=18:00
DayInterval=00:30

[eZStatsMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_GB

[eZTodoMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=/images/standard/
Language=en_GB
DocumentRoot=./eztodo/

[eZFileManagerMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_GB

[eZNewsFeedMain]
AdminTemplateDir=templates/standard/
TemplateDir=templates/standard/
ImageDir=images/standard/
Language=en_GB
PageCaching=disabled

[eZTradeMain]
MainImageWidth=300
MainImageHeight=300
SmallImageWidth=150
SmallImageHeight=150
ThumbnailImageWidth=240
ThumbnailImageHeight=200
ShowBillingAddress=enabled


Changes this in your httpd configuration
----------------
RewriteRule     !\.(gif|css|jpg|png) /yoursite/index.php
To
RewriteRule     !\.(gif|css|jpg|png)$ /yoursite/index.php


SQL updates
-----------------
CREATE TABLE eZArticle_ArticleFileLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_AddressDefinition'
#
DROP TABLE IF EXISTS eZContact_AddressDefinition;
CREATE TABLE eZContact_AddressDefinition (
  AddressID int(11),
  UserID int(11) NOT NULL,
  PRIMARY KEY (UserID)
);

create table eZSession_Preferences( ID int primary key auto_increment, UserID int not null, Name char(50), Value char(50) );

#
# Updates in eZ forum
#
alter table eZForum_Forum add ModeratorID int not null;
alter table eZForum_Forum drop moderated;
alter table eZForum_Forum add IsModerated int not null default 0;

alter table eZForum_Message add IsApproved int not null default 1;     

# User defined sorting of articles pr category
alter table eZArticle_Category add SortMode int not null default 1;

# abolute placement of articles
alter table eZArticle_ArticleCategoryLink add Placement int not null;    
update eZArticle_ArticleCategoryLink set Placement=ArticleID;  

# User defines sorting of products pr category
alter table eZTrade_Category add SortMode int not null default 1;   

# Absolute placement of products pr category.
alter table eZTrade_ProductCategoryLink add Placement int not null;   
update eZTrade_ProductCategoryLink set Placement=ProductID;       

# Dates for products
alter table eZTrade_Product add Published timestamp; 
alter table eZTrade_Product add Altered timestamp;    

# Rigths to image and file:
insert into eZUser_Module set Name='eZFileManager';
insert into eZUser_Module set Name='eZImageCatalogue';
insert into eZUser_Permission set ModuleID='9', Name='WritePermission';
insert into eZUser_Permission set ModuleID='10', Name='WritePermission';
insert into eZUser_Permission set Name='WriteToRoot', ModuleID='9';
insert into eZUser_Permission set Name='WriteToRoot', ModuleID='10';


# trade
ALTER TABLE eZTrade_ProductImageLink add Created timestamp;
alter table eZTrade_WishList add IsPublic int not null default 0;
alter table eZTrade_CartItem add WishListItemID int not null default 0;
alter table eZTrade_WishListItem add IsBought int not null default 0;

# New ordering fields:
alter table eZTrade_Order drop AddressID; 
alter table eZTrade_Order add ShippingAddressID int;     
alter table eZTrade_Order add BillingAddressID int;                       


#
# Table structure for table 'eZTrade_ProductTypeLink'
#
DROP TABLE IF EXISTS eZTrade_ProductTypeLink;
CREATE TABLE eZTrade_ProductTypeLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  TypeID int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZTrade_Type'
#
DROP TABLE IF EXISTS eZTrade_Type;
CREATE TABLE eZTrade_Type (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);


#
# Table structure for table 'eZTrade_Attribute'
#
DROP TABLE IF EXISTS eZTrade_Attribute;
CREATE TABLE eZTrade_Attribute (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  TypeID int(11),
  Name char(150),
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZTrade_AttributeValue'
#
DROP TABLE IF EXISTS eZTrade_AttributeValue;
CREATE TABLE eZTrade_AttributeValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  AttributeID int(11),
  Value char(200),
  PRIMARY KEY (ID)
);


### BETA 1- BETA 2

alter table eZTrade_Order add IsExported int not null default 0;    

# user table
alter table eZUser_User add Signature text NOT NULL;   


alter table eZLink_LinkGroup add ImageID int;

alter table eZTrade_Order add IsExported int not null default 0;    
alter table eZTrade_Order add Date datetime;

# eZ stats (speed) update
# If you're having the site online do the following:
#
alter table eZStats_PageView add DateValue date not null;
alter table eZStats_PageView add TimeValue time not null;

# Then update the eZStats scripts. (copy the new code )
# Then run the following commands
Update eZStats_PageView SET Date=Date, DateValue= DATE_FORMAT( Date, "%Y-%m-%d" ) WHERE DateValue='0000-00-00';
Update eZStats_PageView SET Date=Date, TimeValue=DATE_FORMAT( eZStats_PageView.Date, "%H:%i:%S" ) WHERE TimeValue='00:00:00';

# Index for speed
alter table eZStats_PageView add index ( DateValue ); 
alter table eZStats_PageView add index ( TimeValue ); 
alter table eZStats_PageView add index ( Date );

# Set if order is active or not
alter table eZTrade_Order add IsActive int default 0;  

# set a group for eZ forum
alter table eZForum_Forum add GroupID int default 0;

# Permission to eZ imagecatalogue
alter table eZImageCatalogue_Image add ReadPermission int DEFAULT '1';
alter table eZImageCatalogue_Image add WritePermission int DEFAULT '1';
alter table eZImageCatalogue_Image add UserID int;

#
# Table structure for table 'eZImageCatalogue_Category'
#
CREATE TABLE eZImageCatalogue_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11),
  UserID int(11),
  WritePermission int(11) DEFAULT '1',
  ReadPermission int(11) DEFAULT '1',
  PRIMARY KEY (ID)
);

# Permission to eZ filemanager
alter table eZFileManager_File add ReadPermission int DEFAULT '1';
alter table eZFileManager_File add WritePermission int DEFAULT '1';
alter table eZFileManager_File add UserID int;

alter table eZFileManager_Folder add ReadPermission int DEFAULT '1';
alter table eZFileManager_Folder add WritePermission int DEFAULT '1';
alter table eZFileManager_Folder add UserID int;
alter table eZForum_Forum add GroupID int default 0;




### BETA 2 - BETA 3
#set a group for eZBugmodule
alter table eZBug_Module add OwnerGroupID int default 0;
INSERT INTO eZUser_Module VALUES (11, 'eZBug');
INSERT INTO eZUser_Permission VALUES (40, 11,'BugEdit'); 

alter table eZBug_Bug add OwnerID int default NULL; 

# Contact Persons in eZContact
ALTER TABLE eZContact_Company ADD ContactID int(11) NOT NULL DEFAULT '0';
UPDATE eZContact_Company SET ContactID=ContactType;
ALTER table eZContact_Company MODIFY ContactType int(1) NOT NULL DEFAULT '0';
UPDATE eZContact_Company SET ContactType='1';

# Contact user group permissions
UPDATE eZUser_Permission SET Name='CategoryAdd' WHERE Name='TypeAdd' AND ModuleID='6';
UPDATE eZUser_Permission SET Name='CategoryDelete' WHERE Name='TypeDelete' AND ModuleID='6';
UPDATE eZUser_Permission SET Name='CategoryModify' WHERE Name='TypeModify' AND ModuleID='6';
INSERT INTO eZUser_Permission VALUES('41','6','CompanyView');
INSERT INTO eZUser_Permission VALUES('42','6','CompanyList');
INSERT INTO eZUser_Permission VALUES('43','6','TypeAdmin');
INSERT INTO eZUser_Permission VALUES('44','6','Consultation');

# add tables for Images and Files in eZBug
CREATE TABLE eZBug_BugFileLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  BugID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_BugImageLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  BugID int(11) DEFAULT '0' NOT NULL,
  ImageID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

# Set the remote ID.
alter table eZTrade_Category add RemoteID varchar(100);  
alter table eZTrade_Product add RemoteID varchar(100);

#
# VAT handling
create table eZTrade_VATType( ID int primary key auto_increment, Name char(100), VATValue float not null default 0 );       
alter table eZTrade_VATType add Created timestamp;

alter table eZTrade_Product add VATTypeID int not null default 0;	

# image on link
alter table eZLink_Link add ImageID int not null default 0;

# Add more info to eZForum_Message.
ALTER TABLE eZForum_Message ADD IsTemporary int(1) DEFAULT '0' NOT NULL;
ALTER TABLE eZForum_Forum   ADD IsAnonymous int(1) DEFAULT '0' NOT NULL;

# Don't need int(11)
ALTER TABLE eZForum_Forum  MODIFY  IsModerated int(1) DEFAULT '0' NOT NULL; 
ALTER TABLE eZForum_Message  MODIFY  IsApproved int(1) DEFAULT '1' NOT NULL; 

# eZForum_Message uses y and n instead of 0 and 1. These fixes that:
SELECT ID, EmailNotice from eZForum_Message;

UPDATE eZForum_Message SET EmailNotice='N', PostingTime=PostingTime WHERE EmailNotice = "";  
ALTER TABLE eZForum_Message  MODIFY  EmailNotice int(1) DEFAULT '0' NOT NULL; 
UPDATE eZForum_Message SET EmailNotice='0', PostingTime=PostingTime  WHERE EmailNotice = "1";   
UPDATE eZForum_Message SET EmailNotice='1', PostingTime=PostingTime  WHERE EmailNotice = "2";


# eZForum_Category uses y and n instead of 0 and 1. These fixes that:
select ID, Private from eZForum_Category;

UPDATE eZForum_Category SET Private='N' WHERE Private = "";  
ALTER TABLE eZForum_Category  MODIFY  Private int(1) DEFAULT '0' NOT NULL; 
UPDATE eZForum_Category SET Private='0' WHERE Private = "1";   
UPDATE eZForum_Category SET Private='1' WHERE Private = "2";
ALTER TABLE eZForum_Category CHANGE Private IsPrivate int(1); 

# eZForum_Forum uses y and n instead of 0 and 1. These fixes that:
select ID, Private from eZForum_Forum;

UPDATE eZForum_Forum SET Private='N' where Private = "";  
ALTER TABLE eZForum_Forum  MODIFY  Private int(1) DEFAULT '0' NOT NULL; 
UPDATE eZForum_Forum SET Private='0' WHERE Private = "1";   
UPDATE eZForum_Forum SET Private='1' WHERE Private = "2";
ALTER TABLE eZForum_Forum CHANGE Private IsPrivate int(1); 


#
# permissions in eZArticle..
ALTER TABLE eZArticle_Category ADD OwnerGroupID int(11) DEFAULT NULL;
ALTER TABLE eZArticle_Article ADD OwnerGroupID int(11) DEFAULT NULL;
ALTER TABLE eZArticle_Category ADD ReadPermission int(1) DEFAULT '0';
ALTER TABLE eZArticle_Article ADD ReadPermission int(1) DEFAULT '0';
UPDATE eZArticle_Article SET ReadPermission='2', Published=Published, Created=Created, Modified=Modified ;
UPDATE eZArticle_Category SET ReadPermission='2';

CREATE TABLE eZArticle_CategoryReaderLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  GroupID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);   

CREATE TABLE eZArticle_ArticleReaderLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  GroupID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);  

#
# Shipping type handling
create table eZTrade_ShippingType( ID int primary key auto_increment, Name char(100) );
create table eZTrade_ShippingGroup( ID int primary key auto_increment, Name char(100) );
alter table eZTrade_ShippingType add Created timestamp;       
alter table eZTrade_ShippingGroup add Created timestamp;
create table eZTrade_ShippingValue( ID int primary key auto_increment, ShippingGroupID int not null, ShippingTypeID int not null, StartValue float not null default 0, AddValue float not null default 0 );


alter table eZTrade_Product add ShippingGroupID int not null default 0;
alter table eZTrade_ShippingType add IsDefault int not null default 0;

# Add description to link groups
alter table eZLink_LinkGroup add Description text;

# Price groups
CREATE TABLE eZTrade_PriceGroup (
  ID int(11) NOT NULL auto_increment,
  Name varchar(50) default NULL,
  Description text,
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPriceLink (
  ProductID int(11) NOT NULL default '0',
  PriceID int(11) NOT NULL default '0',
  OptionID int(11) NOT NULL default '0',
  ValueID int(11) NOT NULL default '0',
  Price float(10,2) default NULL,
  PRIMARY KEY (ProductID,PriceID,OptionID,ValueID)
);

CREATE TABLE eZTrade_GroupPriceLink (
  GroupID int(11) NOT NULL default '0',
  PriceID int(11) NOT NULL default '0',
  PRIMARY KEY (GroupID,PriceID)
)

# Alternative currency
create table eZTrade_AlternativeCurrency( ID int primary key auto_increment, Name char(100) not null, PrefixSign int not null default 0, Sign char(5) not null, Value float not null default 1 );
alter table eZTrade_AlternativeCurrency add Created timestamp;


### BETA 3 - PRE 1
#sync permission stuff with filemanager  
alter table eZBug_Bug add IsPrivate enum('true','false') default 'false'; 

# Permission for eZArticle
create table eZArticle_ArticlePermission( ID int primary key auto_increment, ObjectID int, GroupID int, ReadPermission int default 0, WritePermission int default 0 );     
create table eZArticle_CategoryPermission( ID int primary key auto_increment, ObjectID int, GroupID int, ReadPermission int default 0, WritePermission int default 0 );     

# Permission for eZImageCatalogue
create table eZImageCatalogue_ImagePermission( ID int primary key auto_increment, ObjectID int, GroupID int, ReadPermission int default 0, WritePermission int default 0 );     
create table eZImageCatalogue_CategoryPermission( ID int primary key auto_increment, ObjectID int, GroupID int, ReadPermission int default 0, WritePermission int default 0 );      

# Permission for eZFileManager
create table eZFileManager_FilePermission( ID int primary key auto_increment, ObjectID int, GroupID int, ReadPermission int default 0, WritePermission int default 0 );     
create table eZFileManager_FolderPermission( ID int primary key auto_increment, ObjectID int, GroupID int, ReadPermission int default 0, WritePermission int default 0 );      

# Option and option values
CREATE TABLE eZTrade_OptionValueHeader (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  OptionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OptionValueContent (
  ID int(11) NOT NULL auto_increment,
  Value varchar(30) default NULL,
  ValueID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
);

INSERT INTO eZTrade_OptionValueContent (Value, ValueID ) SELECT Name,ID FROM eZTrade_OptionValue;
INSERT INTO eZTrade_OptionValueHeader (Name,OptionID) SELECT 'Name', ID FROM eZTrade_Option;
ALTER TABLE eZTrade_OptionValue DROP Name;
ALTER TABLE eZTrade_OptionValue ADD Placement int(11) NOT NULL default '1';

# Permission for eZBug
create table eZBug_ModulePermission( ID int primary key auto_increment, ObjectID int, GroupID int, ReadPermission int default 0, WritePermission int default 0 );     

# drop old permission stuff in eZArticle
DROP TABLE IF EXISTS eZArticle_ArticleReaderLink;
ALTER TABLE eZArticle_Article DROP ReadPermission;
ALTER TABLE eZArticle_Category DROP ReadPermission;
ALTER TABLE eZArticle_Article DROP OwnerGroupID;
ALTER TABLE eZArticle_Category DROP OwnerGroupID;

#all categories should have an owner.
ALTER TABLE eZArticle_Category ADD OwnerID int(11) DEFAULT '0'; 
# IMPORTANT!!!! OLD CATEGORIES WILL NOT HAVE AN OWNER AND CAN BE UNREACHABLE!!!
# to fix the problem:
# 1. Find a suitable user to own the categories, run the following sql and write down the ID of the user you want.
# SELECT ID, FirstName, LastName FROM eZUser_User;
# 2. Run the following command and substitute XXX with the ID, this will set the user with the given ID as owner of all
# existing categories.
# UPDATE eZArticle_Category SET OwnerID='XXX';  

# The following SQL sentence is nice to run if you are having problems seeing and/or editing your old articles. It will set read/write  for everyone on your existing articles.
#insert into eZArticle_ArticlePermission( ObjectID, GroupID, ReadPermission, WritePermission ) select ID, '-1', '1', '1' from eZArticle_Article;
# the same goes for you old categories
#insert into eZArticle_CategoryPermission( ObjectID, GroupID, ReadPermission, WritePermission ) select ID, '-1', '1', '1' from eZArticle_Category;

### PRE 1 - PRE 2

# add remote ID to option values
alter table eZTrade_OptionValue add RemoteID char(40) not null; 
ALTER TABLE eZTrade_OptionValue ADD Price float(10,2) NOT NULL;

alter table  eZTrade_ShippingType add VATTypeID int not null;
alter table eZTrade_Order add ShippingVAT float not null;

alter table eZTrade_Order add ShippingTypeID int not null;

insert into eZUser_Permission set ModuleID='4', Name='AddOthers';
insert into eZUser_Permission set ModuleID='4', Name='ViewOtherUsers';

### 2.0 - 2.0.1

# Add quantity tables
CREATE TABLE eZTrade_Quantity (
  ID int(11) NOT NULL auto_increment,
  Quantity int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductQuantityDict (
  ProductID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,QuantityID)
);

CREATE TABLE eZTrade_ValueQuantityDict (
  ValueID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ValueID,QuantityID)
);

CREATE TABLE eZTrade_QuantityRange (
  ID int(11) NOT NULL auto_increment,
  MaxRange int(11) default NULL,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZTrade_QuantityRange VALUES ('',0,'Unavailable');
INSERT INTO eZTrade_QuantityRange VALUES ('',NULL,'Available');
INSERT INTO eZTrade_QuantityRange VALUES ('',-1,'Not applicable');

ALTER TABLE eZTrade_OptionValue MODIFY Price float(10,2);

create table eZTrade_PreOrder( ID int primary key auto_increment, Created timestamp );
alter table eZTrade_PreOrder add OrderID int not null;  
alter table eZTrade_Order drop IsActive ;

INSERT INTO eZUser_Permission VALUES('','6','CompanyStats');

CREATE TABLE eZContact_CompanyView (
  ID int(11) NOT NULL auto_increment,
  CompanyID int(11) NOT NULL default '0',
  Count int(11) NOT NULL default '0',
  Date date NOT NULL default '0000-00-00',
  PRIMARY KEY (ID,CompanyID,Date)
);

CREATE TABLE eZTrade_Link (
  ID int(11) NOT NULL auto_increment,
  SectionID int(11) NOT NULL default '0',
  Name varchar(60) default NULL,
  URL text,
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_LinkSection (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductSectionDict (
  ProductID int(11) NOT NULL default '0',
  SectionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,SectionID)
);

CREATE TABLE eZArticle_ArticleKeyword (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL,
  Keyword varchar(50) NOT NULL,
  Automatic int(1) NOT NULL,
  PRIMARY KEY (ID)
)

# Add SimultaneousLogins to eZUser_User
ALTER TABLE eZUser_User ADD SimultaneousLogins int(11) DEFAULT '0' NOT NULL;

# modification information on image variations. 
alter table eZImageCatalogue_ImageVariation add Modification char(20) not null default "";   

# headers for attributes
alter table eZTrade_Attribute add Placement int default 0;
alter table eZTrade_Attribute add AttributeType int default 1;    

# Unit for attribute list
alter table eZTrade_Attribute add Unit varchar(8);

# Discuss article
alter table eZArticle_Article add Discuss int default 0; 
#placement for article categories

ALTER TABLE eZArticle_Category ADD Placement int(11) DEFAULT '0';
update eZArticle_Category set Placement=ID;

DROP TABLE eZContact_ImageType;