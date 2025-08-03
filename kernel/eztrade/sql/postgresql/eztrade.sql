CREATE TABLE eZTrade_AlternativeCurrency (
  ID int NOT NULL,
  Name varchar(100) NOT NULL default '',
  PrefixSign int NOT NULL default '0',
  Sign varchar(5) NOT NULL default '',
  Value decimal NOT NULL default '1',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Attribute (
  ID int NOT NULL,
  TypeID int default NULL,
  Name varchar(150) default NULL,
  Created int NOT NULL,
  Placement int default '0',
  AttributeType int default '1',
  Unit varchar(8) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_AttributeValue (
  ID int NOT NULL,
  ProductID int default NULL,
  AttributeID int default NULL,
  Value varchar(200) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Cart (
  ID int NOT NULL,
  SessionID int default NULL,
  CompanyID int default '0',
  PersonID int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_CartItem (
  ID int NOT NULL,
  ProductID int default NULL,
  Count int default NULL,
  CartID int default NULL,
  WishListItemID int NOT NULL default '0',
  VoucherInformationID int NOT NULL default '0',	
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_CartOptionValue (
  ID int NOT NULL,
  CartItemID int default NULL,
  OptionID int default NULL,
  OptionValueID int default NULL,
  RemoteID varchar(100) default NULL,
  Count int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Category (
  ID int NOT NULL,
  Parent int default NULL,
  Description text,
  Name varchar(100) default NULL,
  ImageID int default NULL,
  SortMode int NOT NULL default '1',
  RemoteID varchar(100) default NULL,
  SectionID int NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_CategoryOptionLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  OptionID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductFormDict (
  ID int NOT NULL,
  ProductID int default NULL,
  FormID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_GroupPriceLink (
  GroupID int NOT NULL default '0',
  PriceID int NOT NULL default '0',
  PRIMARY KEY (GroupID,PriceID)
);

CREATE TABLE eZTrade_Link (
  ID int NOT NULL,
  SectionID int NOT NULL default '0',
  Name varchar(60) default NULL,
  URL text,
  Placement int NOT NULL default '0',
  ModuleType int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_LinkSection (
  ID int NOT NULL,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Option (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  RemoteID varchar(100) default NULL,	
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OptionValue (
  ID int NOT NULL,
  OptionID int default NULL,
  Placement int NOT NULL default '1',
  Price decimal(10,2) default NULL,
  RemoteID varchar(100) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OptionValueContent (
  ID int NOT NULL,
  Value varchar(30) default NULL,
  ValueID int NOT NULL default '0',
  Placement int NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OptionValueHeader (
  ID int NOT NULL,
  Name varchar(30) default NULL,
  OptionID int NOT NULL default '0',
  Placement int NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Order (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  ShippingCharge decimal(10,2) default NULL,
  PaymentMethod text,
  ShippingAddressID int default NULL,
  BillingAddressID int default NULL,
  IsExported int NOT NULL default '0',
  Date int default NULL,
  ShippingVAT decimal NOT NULL default '0',
  ShippingTypeID int NOT NULL default '0',
  IsVATInc int default '0',
  CompanyID int default '0',
  PersonID int default '0',
  Comment text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OrderItem (
  ID int NOT NULL,
  OrderID int NOT NULL default '0',
  Count int default NULL,
  Price decimal(10,2) default NULL,
  ProductID int default NULL,
  VAT decimal(10,2) default NULL,
  ExpiryDate int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OrderOptionValue (
  ID int NOT NULL,
  OrderItemID int default NULL,
  OptionName text default NULL,
  ValueName text default NULL,
  RemoteID varchar(100) default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OrderStatus (
  ID int NOT NULL,
  StatusID int NOT NULL default '0',
  Altered int NOT NULL,
  AdminID int default NULL,
  OrderID int NOT NULL default '0',
  Comment text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OrderStatusType (
  ID int NOT NULL,
  Name varchar(25) NOT NULL default '',
  PRIMARY KEY (ID)
);

INSERT INTO eZTrade_OrderStatusType VALUES (1,'intl-initial');
INSERT INTO eZTrade_OrderStatusType VALUES (2,'intl-sendt');
INSERT INTO eZTrade_OrderStatusType VALUES (3,'intl-payed');
INSERT INTO eZTrade_OrderStatusType VALUES (4,'intl-undefined');

CREATE TABLE eZTrade_PreOrder (
  ID int NOT NULL,
  Created int NOT NULL,
  OrderID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_PriceGroup (
  ID int NOT NULL,
  Name varchar(50) default NULL,
  Description text,
  Placement int NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPriceRange (
  ID int NOT NULL,
  Min int default '0',
  Max int default '0',
  ProductID int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Product (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Contents text,
  Brief text,
  Description text,
  Keywords varchar(100) default NULL,
  Price decimal(10,5) default NULL,
  ShowPrice int default NULL,
  ShowProduct int default NULL,
  Discontinued int default NULL,
  ProductNumber varchar(100) default NULL,
  ExternalLink varchar(200) default NULL,
  IsHotDeal int default '0',
  RemoteID varchar(100) default NULL,
  VATTypeID int NOT NULL default '0',
  ShippingGroupID int NOT NULL default '0',
  ProductType int default '1',
  ExpiryTime int NOT NULL default '0',
  Published int default NULL,
  IncludesVAT int default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductCategoryDefinition (
  ID int NOT NULL,
  ProductID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  ProductID int default NULL,
  Placement int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int NOT NULL default '0',
  ThumbnailImageID int default NULL,
  MainImageID int default NULL,
  PRIMARY KEY (ProductID)
);

CREATE TABLE eZTrade_ProductImageLink (
  ID int NOT NULL,
  ProductID int default NULL,
  Placement int NOT NULL default '0',
  ImageID int default NULL,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductOptionLink (
  ID int NOT NULL,
  ProductID int default NULL,
  OptionID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPermissionLink (
  ID int NOT NULL default '0',
  ProductID int NOT NULL default '0',
  GroupID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPriceLink (
  ProductID int NOT NULL default '0',
  PriceID int NOT NULL default '0',
  OptionID int NOT NULL default '0',
  ValueID int NOT NULL default '0',
  Price decimal(10,2) default NULL,
  PRIMARY KEY (ProductID,PriceID,OptionID,ValueID)
);

CREATE TABLE eZTrade_ProductQuantityDict (
  ProductID int NOT NULL default '0',
  QuantityID int NOT NULL default '0',
  PRIMARY KEY (ProductID,QuantityID)
);

CREATE TABLE eZTrade_ProductSectionDict (
  ID int NOT NULL default '0',
  ProductID int NOT NULL default '0',
  SectionID int NOT NULL default '0',
  Placement int NOT NULL default '0',
  PRIMARY KEY (ProductID,SectionID)
);

CREATE TABLE eZTrade_ProductTypeLink (
  ID int NOT NULL,
  ProductID int default NULL,
  TypeID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Quantity (
  ID int NOT NULL,
  Quantity int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_QuantityRange (
  ID int NOT NULL,
  MaxRange int default NULL,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ShippingGroup (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ShippingType (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Created int NOT NULL,
  IsDefault int NOT NULL default '0',
  VATTypeID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ShippingValue (
  ID int NOT NULL,
  ShippingGroupID int NOT NULL default '0',
  ShippingTypeID int NOT NULL default '0',
  StartValue decimal NOT NULL default '0',
  AddValue decimal NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Type (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_VATType (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  VATValue decimal NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ValueQuantityDict (
  ValueID int NOT NULL default '0',
  QuantityID int NOT NULL default '0',
  PRIMARY KEY (ValueID,QuantityID)
);

CREATE TABLE eZTrade_Voucher (
  ID int NOT NULL default '0',
  Created int default '0',
  Price float default '0',
  Available int default '0',
  KeyNumber varchar(50) default NULL,
  MailMethod int default '1',
  UserID int default '0',
  ProductID int default '0',
  VoucherID int default '0',	
  TotalValue int default '0',	
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_VoucherInformation (
  ID int NOT NULL default '0',
  VoucherID int default '0',
  OnlineID int default '0',
  ToAddressID int default '0',
  Description text,
  PreOrderID int default '0',
  Price int default '0',
  MailMethod int default '1',
  ToName varchar(80) default NULL,
  FromName varchar(80) default NULL,
  FromOnlineID int default '0',
  FromAddressID int default '0',
  ProductID int default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZTrade_VoucherUsed (
  ID int NOT NULL default '0',
  Used int default '0',
  Price float default NULL,
  VoucherID int default '0',
  OrderID int default '0',
  UserID int default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZTrade_WishList (
  ID int NOT NULL,
  UserID int default NULL,
  IsPublic int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_WishListItem (
  ID int NOT NULL,
  ProductID int default NULL,
  Count int default NULL,
  WishListID int default NULL,
  IsBought int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_WishListOptionValue (
  ID int NOT NULL,
  WishListItemID int default NULL,
  OptionID int default NULL,
  OptionValueID int default NULL,
  PRIMARY KEY (ID)
);

CREATE UNIQUE INDEX OrderStatusType_Name_ID ON eZTrade_OrderStatusType (Name);   

CREATE INDEX TradeCategory_Name ON eZTrade_Category (Name);
CREATE INDEX TradeCategory_Parent ON eZTrade_Category (Parent);
CREATE INDEX TradeProduct_Name ON eZTrade_Product (Name);
CREATE INDEX TradeProduct_Keywords ON eZTrade_Product (Keywords);
CREATE INDEX TradeProduct_Price ON eZTrade_Product (Price);
CREATE INDEX TradeProductLink_CategoryID ON eZTrade_ProductCategoryLink (CategoryID);
CREATE INDEX TradeProductLink_ProductID ON eZTrade_ProductCategoryLink (ProductID);
CREATE INDEX TradeProductOption_ProductID ON eZTrade_ProductOptionLink (ProductID);
CREATE INDEX TradeProductOption_OptionID ON eZTrade_ProductOptionLink (OptionID);
CREATE INDEX TradeProductOption_OptionValueContent ON  eZTrade_OptionValueContent  (ValueID);
CREATE INDEX Trade_CartSessionID ON  eZTrade_Cart  (SessionID);
CREATE INDEX TradeProductDef_ProductID ON eZTrade_ProductCategoryDefinition (ProductID);
CREATE INDEX TradeProductPermission_ObjectID ON eZTrade_ProductPermission (ObjectID);
CREATE INDEX TradeProductPermission_GroupID ON eZTrade_ProductPermission (GroupID);
CREATE INDEX TradeProductPermission_WritePermission ON eZTrade_ProductPermission (WritePermission);
CREATE INDEX TradeProductPermission_ReadPermission ON eZTrade_ProductPermission (ReadPermission);

