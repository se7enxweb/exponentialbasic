alter table eZTrade_ProductSectionDict add ID int NOT NULL default 0;
alter table eZTrade_Voucher add TotalValue int default 0;
update eZAddress_Country set HasVAT='1';
alter table eZTrade_Voucher add TotalValue int default 0;        


# This is a fix from the 2_2_pre1_to_2_2.sql, if you have run this update, please do the following things:
#
# drop table eZTrade_UserShippingLink;
# create table eZUser_UserShippingLink ( ID int NOT NULL primary key, UserID int default 0, AddressID int default 0, OrderID int default 0 );
# insert into eZUser_UserShippingLink (ID, AddressID, UserID) select eZTrade_Order.ShippingAddressID, eZTrade_Order.ShippingAddressID, eZUser_UserAddressLink.UserID from eZTrade_Order, eZUser_UserAddressLink where eZTrade_Order.ShippingAddressID = eZUser_UserAddressLink.AddressID GROUP BY eZTrade_Order.ShippingAddressID;

