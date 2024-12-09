create table eZURLTranslator_URL( ID int primary key auto_increment, Source char(200), Dest char(200) );
alter table eZURLTranslator_URL add Created timestamp;

alter table eZBulkMail_SubscriptionAddress add Password varchar(50) default '' not null;
#
# Table structure for table 'eZBulkMail_Forgot'
#
DROP TABLE IF EXISTS eZBulkMail_Forgot;
CREATE TABLE eZBulkMail_Forgot (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Mail varchar(255) DEFAULT '' NOT NULL,
  Password varchar(50) DEFAULT '' NOT NULL,
  Hash char(33),
  Time timestamp(14),
  PRIMARY KEY (ID)
);

ALTER TABLE eZBug_Bug ADD Version varchar(150) DEFAULT '';
alter table eZArticle_Article add AuthorEmail varchar(100);

alter table eZBulkMail_Category ADD IsPublic int(1) default '0';
#
# Table structure for table 'eZBulkMail_GroupCategoryLink'
#

DROP TABLE IF EXISTS eZBulkMail_GroupCategoryLink;
CREATE TABLE eZBulkMail_GroupCategoryLink (
  CategoryID int(11) NOT NULL default '0',
  GroupID int(11) NOT NULL default '0',
  PRIMARY KEY (GroupID, CategoryID)
)

alter table eZBulkMail_SentLog add Mail varchar(255); 
alter table eZBulkMail_SentLog drop AddressID; 

alter table eZTodo_Todo change Permission IsPrivate int default 0;
create table eZTodo_Log( ID int auto_increment primary key, Log text, Created timestamp);      
create table eZTodo_TodoLogLink ( ID int auto_increment primary key, TodoID int, LogID int );

#alter table eZBulkMail_SentLog drop AddressID; 
alter table eZBulkMail_Category add IsSingleCategory int(1) default '0'; 

#
# Table structure for table 'eZArticle_BulkMailCategoryLink'
#
DROP TABLE IF EXISTS eZArticle_BulkMailCategoryLink;
CREATE TABLE eZArticle_BulkMailCategoryLink (
  ArticleCategoryID int(11) DEFAULT '0' NOT NULL,
  BulkMailCategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ArticleCategoryID, BulkMailCategoryID)
);