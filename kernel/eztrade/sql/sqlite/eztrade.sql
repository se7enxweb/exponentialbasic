CREATE TABLE eZTrade_AlternativeCurrency (
  `ID` int(11) NOT NULL DEFAULT 0,
  TypeID int(11) DEFAULT NULL,
  `Name` varchar(150) DEFAULT NULL,
  Created int(11) NOT NULL DEFAULT 0,
  Placement int(11) DEFAULT 0,
  AttributeType int(11) DEFAULT 1,
  Unit varchar(8) DEFAULT NULL);

CREATE TABLE eZTrade_Attribute (
  ID int NOT NULL,
  TypeID int(11) default NULL,
  Name varchar(150) default NULL,
  Created int(11) NOT NULL,
  Placement int(11) default '0',
  AttributeType int(11) default '1',
  Unit varchar(8) default NULL);

CREATE TABLE eZTrade_AttributeValue (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) DEFAULT NULL,
  AttributeID int(11) DEFAULT NULL,
  `Value` varchar(200) DEFAULT NULL);

CREATE TABLE eZTrade_BoxType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Length int(3) NOT NULL DEFAULT 0,
  Height int(3) NOT NULL DEFAULT 0,
  Width int(3) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_Cart (
  `ID` int(11) NOT NULL DEFAULT 0,
  SessionID int(11) DEFAULT NULL,
  CompanyID int(11) DEFAULT 0,
  PersonID int(11) DEFAULT 0);

CREATE TABLE eZTrade_CartItem (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) DEFAULT NULL,
  Count int(11) DEFAULT NULL,
  CartID int(11) DEFAULT NULL,
  WishListItemID int(11) NOT NULL DEFAULT 0,
  VoucherInformationID int(11) NOT NULL DEFAULT 0,
  OptionParentID int(11) NOT NULL DEFAULT 0,
  OptionRequired tinyint(1) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_CartOptionValue (
  `ID` int(11) NOT NULL DEFAULT 0,
  CartItemID int(11) DEFAULT NULL,
  OptionID int(11) DEFAULT NULL,
  OptionValueID int(11) DEFAULT NULL,
  RemoteID varchar(100) DEFAULT NULL,
  Count int(11) DEFAULT NULL);

CREATE TABLE eZTrade_CartShipOptions (
  `ID` int(11) NOT NULL DEFAULT 0,
  AddressID int(11) DEFAULT NULL,
  ServiceCode varchar(250) DEFAULT NULL,
  CartID int(11) DEFAULT NULL);

CREATE TABLE eZTrade_Category (
  `ID` int(11) NOT NULL DEFAULT 0,
  Parent int(11) DEFAULT NULL,
  Description text DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  ImageID int(11) DEFAULT NULL,
  SortMode int(11) NOT NULL DEFAULT 1,
  RemoteID varchar(100) DEFAULT NULL,
  SectionID int(11) NOT NULL DEFAULT 1);

CREATE TABLE eZTrade_CategoryOptionLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT NULL,
  OptionID int(11) DEFAULT NULL);

CREATE TABLE eZTrade_CategoryPermission (
  `ID` int(11) NOT NULL DEFAULT 0,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT 0,
  WritePermission int(11) DEFAULT 0);

CREATE TABLE eZTrade_GroupPriceLink (
  GroupID int(11) NOT NULL DEFAULT 0,
  PriceID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_Link (
  `ID` int(11) NOT NULL DEFAULT 0,
  SectionID int(11) NOT NULL DEFAULT 0,
  `Name` varchar(60) DEFAULT NULL,
  URL text DEFAULT NULL,
  Placement int(11) NOT NULL DEFAULT 0,
  ModuleType int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_LinkSection (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(30) DEFAULT NULL);

CREATE TABLE eZTrade_Option (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Description text DEFAULT NULL,
  `Comment` text NOT NULL DEFAULT '',
  RemoteID varchar(100) DEFAULT NULL);

CREATE TABLE eZTrade_OptionValue (
  `ID` int(11) NOT NULL,
  OptionID int(11) DEFAULT NULL,
  Placement int(11) NOT NULL DEFAULT 1,
  Price float(10,2) DEFAULT NULL,
  RemoteID varchar(100) NOT NULL DEFAULT '',
  ProductID int(11) DEFAULT NULL);

CREATE TABLE eZTrade_OptionValueContent (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Value` varchar(30) DEFAULT NULL,
  ValueID int(11) NOT NULL DEFAULT 0,
  Placement int(11) NOT NULL DEFAULT 1,
  IsProduct tinyint(1) NOT NULL DEFAULT 0,
  ProductNumber varchar(255) DEFAULT NULL);

CREATE TABLE eZTrade_OptionValueHeader (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(30) DEFAULT NULL,
  OptionID int(11) NOT NULL DEFAULT 0,
  Placement int(11) NOT NULL DEFAULT 1);

CREATE TABLE eZTrade_Order (
  `ID` int(11) NOT NULL DEFAULT 0,
  UserID int(11) NOT NULL DEFAULT 0,
  ShippingCharge float(10,2) DEFAULT NULL,
  PaymentMethod text DEFAULT NULL,
  ShippingAddressID int(11) DEFAULT NULL,
  BillingAddressID int(11) DEFAULT NULL,
  IsExported int(11) NOT NULL DEFAULT 0,
  `Date` int(11) DEFAULT NULL,
  ShippingVAT float NOT NULL DEFAULT 0,
  ShippingTypeID varchar(255) NOT NULL DEFAULT '0',
  IsVATInc int(11) DEFAULT 0,
  CompanyID int(11) DEFAULT 0,
  PersonID int(11) DEFAULT 0,
  `Comment` text DEFAULT NULL);

CREATE TABLE eZTrade_OrderItem (
  `ID` int(11) NOT NULL DEFAULT 0,
  OrderID int(11) NOT NULL DEFAULT 0,
  Count int(11) DEFAULT NULL,
  Price float(10,2) DEFAULT NULL,
  ProductID int(11) DEFAULT NULL,
  VAT float(10,2) DEFAULT NULL,
  ExpiryDate int(11) DEFAULT NULL);

CREATE TABLE eZTrade_OrderOptionValue (
  `ID` int(11) NOT NULL DEFAULT 0,
  OrderItemID int(11) DEFAULT NULL,
  OptionName text DEFAULT NULL,
  ValueName text DEFAULT NULL,
  RemoteID varchar(100) DEFAULT '');

CREATE TABLE eZTrade_OrderPayment (
  `ID` int(11) NOT NULL DEFAULT 0,
  OrderID int(11) NOT NULL DEFAULT 0,
  CardNumber tinytext NOT NULL DEFAULT '',
  CardExpiration tinytext NOT NULL DEFAULT '',
  CardAuthorization varchar(25) DEFAULT NULL,
  CardTransID varchar(25) DEFAULT NULL,
  CardCVV2 char(1) DEFAULT NULL,
  CardAVS char(3) DEFAULT NULL);

CREATE TABLE eZTrade_OrderStatus (
  `ID` int(11) NOT NULL DEFAULT 0,
  StatusID int(11) NOT NULL DEFAULT 0,
  Altered int(11) NOT NULL DEFAULT 0,
  AdminID int(11) DEFAULT NULL,
  OrderID int(11) NOT NULL DEFAULT 0,
  `Comment` text DEFAULT NULL);

CREATE TABLE eZTrade_OrderStatusType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(25) NOT NULL DEFAULT '');

INSERT INTO eZTrade_OrderStatusType VALUES (1,'intl-initial');
INSERT INTO eZTrade_OrderStatusType VALUES (2,'intl-sent');
INSERT INTO eZTrade_OrderStatusType VALUES (3,'intl-paid');
INSERT INTO eZTrade_OrderStatusType VALUES (4,'intl-undefined');

CREATE TABLE eZTrade_PreOrder (
  `ID` int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0,
  OrderID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_PriceGroup (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(50) DEFAULT NULL,
  Description text DEFAULT NULL,
  Placement int(11) NOT NULL DEFAULT 1);

CREATE TABLE eZTrade_Product (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Contents text DEFAULT NULL,
  Brief text DEFAULT NULL,
  Description text DEFAULT NULL,
  Keywords varchar(100) DEFAULT NULL,
  Price float(10,5) DEFAULT NULL,
  ListPrice float(10,2) DEFAULT NULL,
  ShowPrice int(11) DEFAULT 1,
  ShowProduct int(11) DEFAULT 1,
  Discontinued int(11) DEFAULT 0,
  ProductNumber varchar(100) DEFAULT NULL,
  ExternalLink varchar(200) DEFAULT NULL,
  IsHotDeal int(11) DEFAULT 0,
  Weight int(11) NOT NULL DEFAULT 0,
  RemoteID varchar(100) DEFAULT NULL,
  VATTypeID int(11) NOT NULL DEFAULT 0,
  BoxTypeID int(11) NOT NULL DEFAULT 0,
  ShippingGroupID int(11) NOT NULL DEFAULT 0,
  ProductType int(11) DEFAULT 1,
  ExpiryTime int(11) NOT NULL DEFAULT 0,
  StockDate int(11) DEFAULT NULL,
  Published int(11) DEFAULT NULL,
  IncludesVAT int(1) DEFAULT 1,
  FlatUPS varchar(9) NOT NULL DEFAULT 'off',
  FlatUSPS varchar(9) NOT NULL DEFAULT 'off',
  FlatCombine smallint(1) NOT NULL DEFAULT 0,
  CatalogNumber varchar(255) DEFAULT NULL);

CREATE TABLE eZTrade_ProductCategoryDefinition (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ProductCategoryLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  CategoryID int(11) DEFAULT NULL,
  ProductID int(11) DEFAULT NULL,
  Placement int(11) NOT NULL DEFAULT 0);

CREATE TABLE `eZTrade_ProductFileLink` (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) NOT NULL DEFAULT 0,
  FileID int(11) NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ProductFormDict (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) DEFAULT NULL,
  FormID int(11) DEFAULT NULL);

CREATE TABLE `eZTrade_ProductForumLink` (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) NOT NULL DEFAULT 0,
  ForumID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int(11) NOT NULL DEFAULT 0,
  ThumbnailImageID int(11) DEFAULT NULL,
  MainImageID int(11) DEFAULT NULL);

CREATE TABLE eZTrade_ProductImageLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) DEFAULT NULL,
  Placement int(11) DEFAULT 0,
  ImageID int(11) DEFAULT NULL,
  Created int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ProductOptionLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) DEFAULT NULL,
  OptionID int(11) DEFAULT NULL);

CREATE TABLE eZTrade_ProductPermission (
  ID int NOT NULL,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0');

CREATE TABLE eZTrade_ProductPermissionLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) NOT NULL DEFAULT 0,
  GroupID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ProductPriceLink (
  ProductID int(11) NOT NULL DEFAULT 0,
  PriceID int(11) NOT NULL DEFAULT 0,
  OptionID int(11) NOT NULL DEFAULT 0,
  ValueID int(11) NOT NULL DEFAULT 0,
  Price float(10,2) DEFAULT NULL);

CREATE TABLE eZTrade_ProductPriceRange (
  `ID` int(11) NOT NULL DEFAULT 0,
  Min int(11) DEFAULT 0,
  Max int(11) DEFAULT 0,
  ProductID int(11) DEFAULT 0);

CREATE TABLE eZTrade_ProductQuantityDict (
  ProductID int(11) NOT NULL DEFAULT 0,
  QuantityID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ProductSectionDict (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) NOT NULL DEFAULT 0,
  SectionID int(11) NOT NULL DEFAULT 0,
  Placement int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ProductTypeLink (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) DEFAULT NULL,
  TypeID int(11) DEFAULT NULL);

CREATE TABLE eZTrade_Quantity (
  `ID` int(11) NOT NULL DEFAULT 0,
  Quantity int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_QuantityRange (
  `ID` int(11) NOT NULL DEFAULT 0,
  MaxRange int(11) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL);

CREATE TABLE eZTrade_ShippingGroup (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Created int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ShippingType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  Created int(11) NOT NULL DEFAULT 0,
  IsDefault int(11) NOT NULL DEFAULT 0,
  VATTypeID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ShippingValue (
  `ID` int(11) NOT NULL DEFAULT 0,
  ShippingGroupID int(11) NOT NULL DEFAULT 0,
  ShippingTypeID int(11) NOT NULL DEFAULT 0,
  StartValue float NOT NULL DEFAULT 0,
  AddValue float NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_Type (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(150) DEFAULT NULL,
  Description text DEFAULT NULL);

CREATE TABLE eZTrade_VATType (
  `ID` int(11) NOT NULL DEFAULT 0,
  `Name` varchar(100) DEFAULT NULL,
  VATValue float NOT NULL DEFAULT 0,
  Created int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_ValueQuantityDict (
  ValueID int(11) NOT NULL DEFAULT 0,
  QuantityID int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_Voucher (
  `ID` int(11) NOT NULL DEFAULT 0,
  Created int(11) DEFAULT 0,
  Price float DEFAULT 0,
  Available int(11) DEFAULT 0,
  KeyNumber varchar(50) DEFAULT NULL,
  MailMethod int(11) DEFAULT 1,
  UserID int(11) DEFAULT 0,
  ProductID int(11) DEFAULT 0,
  VoucherID int(11) DEFAULT 0,
  TotalValue int(11) DEFAULT 0);

CREATE TABLE eZTrade_VoucherInformation (
  `ID` int(11) NOT NULL DEFAULT 0,
  VoucherID int(11) DEFAULT 0,
  OnlineID int(11) DEFAULT 0,
  ToAddressID int(11) DEFAULT 0,
  Description text DEFAULT NULL,
  PreOrderID int(11) DEFAULT 0,
  Price int(11) DEFAULT 0,
  MailMethod int(11) DEFAULT 1,
  ToName varchar(80) DEFAULT NULL,
  FromName varchar(80) DEFAULT NULL,
  FromOnlineID int(11) DEFAULT 0,
  FromAddressID int(11) DEFAULT 0,
  ProductID int(11) DEFAULT 0);

CREATE TABLE eZTrade_VoucherUsed (
  `ID` int(11) NOT NULL DEFAULT 0,
  Used int(11) DEFAULT 0,
  Price float DEFAULT NULL,
  VoucherID int(11) DEFAULT 0,
  OrderID int(11) DEFAULT 0,
  UserID int(11) DEFAULT 0);

CREATE TABLE eZTrade_WishList (
  `ID` int(11) NOT NULL,
  UserID int(11) DEFAULT NULL,
  IsPublic int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_WishListItem (
  `ID` int(11) NOT NULL DEFAULT 0,
  ProductID int(11) DEFAULT NULL,
  Count int(11) DEFAULT NULL,
  WishListID int(11) DEFAULT NULL,
  IsBought int(11) NOT NULL DEFAULT 0);

CREATE TABLE eZTrade_WishListOptionValue (
  `ID` int(11) NOT NULL DEFAULT 0,
  WishListItemID int(11) DEFAULT NULL,
  OptionID int(11) DEFAULT NULL,
  OptionValueID int(11) DEFAULT NULL);

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

CREATE INDEX TradeAttributeValue_ProductID ON eZTrade_AttributeValue (ProductID);
CREATE INDEX TradeAttributeValue_AttributeID ON eZTrade_AttributeValue (AttributeID);

CREATE INDEX TradeCart_Session ON eZTrade_Cart (SessionID);

CREATE INDEX TradeCartOptionValue_CartItemID ON eZTrade_CartOptionValue (CartItemID);


