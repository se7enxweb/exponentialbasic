create table eZBulkMail_UserCategoryLink ( UserID int default 0, CategoryID int default 0 ); 

create table eZBulkMail_UserSubscriptionCategorySettings( CategoryID int default 0, UserID int default 0, Delay int default 0 );

# Article search
alter table eZArticle_ArticleWordLink add Frequency float default 0.2;
alter table eZArticle_Word add Frequency float default 0.2;

# cache of article index
CREATE TABLE eZArticle_ArticleKeywordFirstLetter (
  ID int(11) NOT NULL default '0',
  Letter char(1) NOT NULL default ''
);

alter table eZTrade_Voucher add UserID int default 0; 
alter table eZTrade_VoucherUsed add OrderID int default 0;
create table eZTrade_ProductPriceRange( ID int NOT NULL, Min int default 0, Max int default 0, ProductID int default 0 );        

alter table eZBulkMail_UserSubscriptionCategorySettings rename eZBulkMail_UserCategorySettings;  

create table eZBulkMail_UserCategoryDelay ( ID int NOT NULL, CategoryID int default 0, UserID int default 0, Delay int default 0, MailID int default 0 );   

alter table eZTrade_Product add IncludesVAT int(1) default '0';

alter table eZArticle_Category add ListLimit int default '0';

alter table eZCalendar_Appointment add AllDay int default 0;
alter table eZFileManager_FolderPermission add UploadPermission int(11) default '0';
alter table eZImageCatalogue_CategoryPermission add UploadPermission int(11) default '0';

insert into eZUser_Permission (ModuleID, Name) VALUES ('6', 'Buy');

alter table eZTrade_CartItem add VoucherInformationID int default 0;

alter table eZTrade_Category add SectionID int default 1;

alter table eZForum_Category add SectionID int default 1;       

alter table eZFileManager_Folder add SectionID int default 1;

alter table eZImageCatalogue_Category add SectionID int default '1';

alter table eZLink_Category add SectionID int default '1';

CREATE TABLE eZTrade_VoucherInformation (
  ID int(11) NOT NULL default '0',
  VoucherID int(11) default '0',
  OnlineID int(11) default '0',
  AddressID int(11) default '0',
  Description text,
  PreOrderID int(11) default '0',
  Price int(11) default '0',
  MailMethod int(11) default '1',
  ToName varchar(80) default NULL,
  FromName varchar(80) default NULL,
  FromOnlineID int(11) default '0',
  ProductID int(11) default '0'
);

CREATE TABLE eZTrade_VoucherUsed (
  ID int(11) default '0',
  Used int(11) default '0',
  Price float default NULL,
  VoucherID int(11) default '0',
  OrderID int(11) default '0',
  UserID int(11) default '0'
)

alter table eZTrade_Voucher add VoucherInformationID int default 0;  
alter table eZTrade_Voucher add TotalValue float default 0;  


CREATE TABLE eZForum_MessageWordLink (
  MessageID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  WordID int(11) NOT NULL default '0'
);

CREATE TABLE eZForum_Word (
  ID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  Word varchar(50) NOT NULL default ''
);

alter table eZSession_SessionVariable change Value Value text;

create table eZSiteManager_Menu( ID int NOT NULL, Name varchar(40), Link varchar(40), Type int default 1, ParentID int default 0 ); 

alter table eZTrade_ProductImageLink change Placement Placement int default 0;  
update eZTrade_ProductImageLink set Placement='0' where Placement IS NULL;