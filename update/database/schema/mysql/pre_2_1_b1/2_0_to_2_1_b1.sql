#
#
# SQL upgrade information for eZ publish 2.0-> 2.1 Beta 1
#
# -------------------------------------------------
#
# Table structure for table 'eZMail_Mail'
#
DROP TABLE IF EXISTS eZMail_Mail;
CREATE TABLE eZMail_Mail (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) default '0', 
  ToField varchar(100),
  FromField varchar(100),
  FromName varchar(100),
  Cc varchar(255),
  Bcc varchar(255),
  MessageID varchar(200),
  Reference varchar(100),
  ReplyTo varchar(100),
  Subject varchar(255),
  BodyText text,
  Status int(1) default '0' NOT NULL,
  Size int(11) default '0',
  UDate int(15) default '0',
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZMail_Account'
#
DROP TABLE IF EXISTS eZMail_Account;
CREATE TABLE eZMail_Account (
  ID int(11) default '0' NOT NULL auto_increment,
  UserID int(11) default '0',
  Name varchar(200) default NULL,
  LoginName varchar(100),
  Password varchar(50),
  Server varchar(150),
  ServerPort int(5) default '0',
  DeleteFromServer int(1) default '1',
  ServerType int(2), 
  IsActive int(1) default '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZMail_Folder'
#
DROP TABLE IF EXISTS eZMail_Folder;
CREATE TABLE eZMail_Folder (
  ID int(11) default '0' NOT NULL auto_increment,
  UserID int(11) default '0',
  ParentID int(11) default '0',
  Name varchar(200) default NULL,
  FolderType int(2), 
  PRIMARY KEY (ID)
);


#
# Table structure for table 'eZMail_MailFolderLink'
#

DROP TABLE IF EXISTS eZMail_MailFolderLink;
CREATE TABLE eZMail_MailFolderLink (
  MailID int(11) NOT NULL default '0',
  FolderID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,FolderID)
)


#
# Table structure for table 'eZMail_MailAttachmentLink'
#

DROP TABLE IF EXISTS eZMail_MailAttachmentLink;
CREATE TABLE eZMail_MailAttachmentLink (
  MailID int(11) NOT NULL default '0',
  FileID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,FileID)
)

#
# Table structure for table 'eZMail_MailImageLink'
#

DROP TABLE IF EXISTS eZMail_MailImageLink;
CREATE TABLE eZMail_MailImageLink (
  MailID int(11) NOT NULL default '0',
  ImageID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,ImageID)
)


#
# Table structure for table 'eZMail_FilterRule'
#

DROP TABLE IF EXISTS eZMail_FilterRule;
CREATE TABLE eZMail_FilterRule (
  ID int(11) default '0' NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  FolderID int(11) NOT NULL default '0',
  HeaderType int(2) default '0',
  CheckType int(2) default '0',
  MatchValue varchar(200),
  IsActive int(1) default '0',
  PRIMARY KEY (ID)
)


#
# Table structure for table 'eZMail_FetchedMail'
#

DROP TABLE IF EXISTS eZMail_FetchedMail;
CREATE TABLE eZMail_FetchedMail (
  UserID int(11) NOT NULL,
  MessageID varchar(100) NOT NULL,
  PRIMARY KEY (UserID, MessageID)
)


#
# Table structure for table 'eZBulkMail_Mail'
#
DROP TABLE IF EXISTS eZBulkMail_Mail;
CREATE TABLE eZBulkMail_Mail (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) default '0', 
  FromField varchar(100),
  FromName varchar(100),
  ReplyTo varchar(100),
  Subject varchar(255),
  BodyText text,
  SentDate timestamp(14) NOT NULL,
  IsDraft int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZBulkMail_Category'
#
DROP TABLE IF EXISTS eZBulkMail_Category;
CREATE TABLE eZBulkMail_Category (
  ID int(11) default '0' NOT NULL auto_increment,
  Name varchar(200) default NULL,
  Description text,
  PRIMARY KEY (ID)
);


#
# Table structure for table 'eZBulkMail_MailCategoryLink'
#

DROP TABLE IF EXISTS eZBulkMail_MailCategoryLink;
CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,CategoryID)
)

#
# Table structure for table 'eZBulkMail_MailTemplateLink'
#

DROP TABLE IF EXISTS eZBulkMail_MailTemplateLink;
CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int(11) NOT NULL default '0',
  TemplateID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID)
)


#
# Table structure for table 'eZBulkMail_Template'
#

DROP TABLE IF EXISTS eZBulkMail_Template;
CREATE TABLE eZBulkMail_Template (
  ID int(11) default '0' NOT NULL auto_increment,
  Name varchar(200) default NULL,
  Header text,
  Footer text,
  PRIMARY KEY (ID)
)

#
# Table structure for table 'eZBulkMail_SubscriptionLink'
#

DROP TABLE IF EXISTS eZBulkMail_SubscriptionLink;
CREATE TABLE eZBulkMail_SubscriptionLink (
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (AddressID,CategoryID)
)

#
# Table structure for table 'eZBulkMail_SubscriptionAddress'
#

DROP TABLE IF EXISTS eZBulkMail_SubscriptionAddress;
CREATE TABLE eZBulkMail_SubscriptionAddress (
  ID int(11) default '0' NOT NULL auto_increment,
  EMail varchar(255),
  PRIMARY KEY (ID)
)

#
# Table structure for table 'eZBulkMail_SentLog'
#

DROP TABLE IF EXISTS eZBulkMail_SentLog;
CREATE TABLE eZBulkMail_SentLog (
  ID int(11) default '0' NOT NULL auto_increment,
  MailID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  SentDate timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
)



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

# Add auto cookie login
alter table eZUser_User add CookieLogin int default 0; 
create table eZUser_Cookie ( ID int auto_increment primary key, UserID int default 0, Hash char(33), Time timestamp );    