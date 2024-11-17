alter table eZBulkMail_UserCategoryLink drop ID;

CREATE TABLE eZSiteManager_Menu (
  ID int(11) NOT NULL default '0',
  Name varchar(40) default NULL,
  Link varchar(40) default NULL,
  Type int(11) default '1',
  ParentID int(11) default '0'
);

CREATE TABLE eZSiteManager_MenuType (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL
);

CREATE TABLE eZTrade_UserShippingLink ( ID int NOT NULL primary key, UserID int default 0, AddressID int default 0, OrderID int default 0 );
create table eZTrade_UserBillingLink ( ID int NOT NULL primary key, UserID int default 0, AddressID int default 0, OrderID int default 0 );

insert into eZUser_UserShippingLink (ID, AddressID, UserID) select eZTrade_Order.ShippingAddressID, eZTrade_Order.ShippingAddressID, eZUser_UserAddressLink.UserID from eZTrade_Order, eZUser_UserAddressLink where eZTrade_Order.ShippingAddressID = eZUser_UserAddressLink.AddressID GROUP BY eZTrade_Order.ShippingAddressID;
alter table eZTrade_Voucher add TotalValue int default 0;        

CREATE TABLE eZBulkMail_SubscriptionCategorySettings (
  ID int(11) NOT NULL,
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  Delay int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SentLog (
  ID int(11) NOT NULL,
  MailID int(11) NOT NULL default '0',
  Mail varchar(255) default NULL,
  SentDate int(11) default NULL,
  PRIMARY KEY (ID)
)

alter table eZAd_View change ViewPrice ViewPrice float(10,2) default 0;

alter table eZLink_Link change KeyWords KeyWords text;

alter table eZForm_FormElement add Break int default 0;
alter table eZForm_FormElement add Size int default 0;