alter table eZTrade_VoucherInformation add FromAddressID int default 0; 
alter table eZTrade_VoucherInformation add ProductID int default 0; 
alter table eZTrade_VoucherInformation change AddressID ToAddressID int default 0;        

alter table eZFileManager_Folder add SectionID int(11);

alter table eZTrade_OrderOptionValue change OptionName OptionName text;
alter table eZTrade_OrderOptionValue change ValueName ValueName text;

CREATE TABLE eZSiteManager_SectionFrontPageRow (
  ID int(11) NOT NULL default '0',
  SettingID int(11) default '0',
  CategoryID int(11) default '0',
  Placement int(11) default '0',
  PRIMARY KEY (ID)
)
 
CREATE TABLE eZSiteManager_SectionFrontPageRowLink (
  ID int(11) NOT NULL default '0',
  FrontPageID int(11) default '0',
  SectionID int(11) default '0',
  PRIMARY KEY (ID)
)
 
CREATE TABLE eZSiteManager_SectionFrontPageSetting (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
)
 
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (1,'1column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (2,'2column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (3,'1short');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (4,'1columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (5,'2columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (6,'ad');

CREATE TABLE eZForm_FormElementFixedValues (
  ID int(11) NOT NULL default '0',
  Value varchar(80) default NULL,
  PRIMARY KEY (ID)
)
 
CREATE TABLE eZForm_FormElementFixedValueLink (
  ID int(11) NOT NULL default '0',
  ElementID int(11) default '0',
  FixedValueID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZContact_CompanyIndex (
  CompanyID int(11) NOT NULL default '0',
  Value varchar(255) NOT NULL default '',
  Type int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,Value)
);

CREATE TABLE eZContact_PersonIndex (
  PersonID int(11) NOT NULL default '0',
  Value varchar(255) NOT NULL default '',
  Type int(11) NOT NULL default '0',
  PRIMARY KEY (PersonID,Value)
);

INSERT INTO eZContact_CompanyIndex (CompanyID, Value, Type) SELECT ID, lower(Name), '0' FROM eZContact_Company;
INSERT INTO eZContact_CompanyIndex (CompanyID, Value, Type) SELECT C.ID, lower(O.URL), '2' FROM eZContact_Company AS C, eZAddress_Online AS O, eZContact_CompanyOnlineDict AS OD WHERE OD.CompanyID=C.ID AND OD.OnlineID=O.ID;
INSERT INTO eZContact_CompanyIndex (CompanyID, Value, Type) SELECT C.ID, lower(P.Number), '1' FROM eZContact_Company AS C, eZAddress_Phone AS P, eZContact_CompanyPhoneDict as PD WHERE PD.CompanyID=C.ID AND PD.PhoneID=P.ID;

INSERT INTO eZContact_PersonIndex (PersonID, Value, Type) SELECT ID, lower(FirstName), '0' FROM eZContact_Person;
INSERT INTO eZContact_PersonIndex (PersonID, Value, Type) SELECT ID, lower(LastName), '0' FROM eZContact_Person;
INSERT INTO eZContact_PersonIndex (PersonID, Value, Type) SELECT P.ID, lower(O.URL), '2' FROM eZContact_Person AS P, eZAddress_Online AS O, eZContact_PersonOnlineDict AS OD WHERE OD.PersonID=P.ID AND OD.OnlineID=O.ID;
INSERT INTO eZContact_PersonIndex (PersonID, Value, Type) SELECT P.ID, lower(Ph.Number), '1' FROM eZContact_Person AS P, eZAddress_Phone AS Ph, eZContact_PersonPhoneDict as PD WHERE PD.PersonID=P.ID AND PD.PhoneID=Ph.ID;

INSERT INTO eZForm_FormElementType VALUES (1,'text_field_item','HTML text field (input type="text")');
INSERT INTO eZForm_FormElementType VALUES (2,'text_area_item','HTML text area (textarea)');
INSERT INTO eZForm_FormElementType VALUES (3,'dropdown_item','HTML Select');
INSERT INTO eZForm_FormElementType VALUES (4,'multiple_select_item','HTML Multiple Select');
INSERT INTO eZForm_FormElementType VALUES (6,'radiobox_item','HTML RadioBox');
INSERT INTO eZForm_FormElementType VALUES (5,'checkbox_item','HTML CheckBox');

create table eZUser_UserShippingLink ( ID int NOT NULL primary key, UserID int default 0, AddressID int default 0 );
insert into eZUser_UserShippingLink (ID, AddressID, UserID) select eZTrade_Order.ShippingAddressID, eZTrade_Order.ShippingAddressID, eZUser_UserAddressLink.UserID from eZTrade_Order, eZUser_UserAddressLink where eZTrade_Order.ShippingAddressID = eZUser_UserAddressLink.AddressID GROUP BY eZTrade_Order.ShippingAddressID;

ALTER TABLE eZLink_Hit CHANGE RemoteIP RemoteIP varchar(15);

alter table eZTrade_Voucher add TotalValue int default 0;        