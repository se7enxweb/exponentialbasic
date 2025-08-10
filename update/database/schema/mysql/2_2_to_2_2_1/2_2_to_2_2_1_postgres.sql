alter table eZTrade_ProductSectionDict add ID int NOT NULL default 0;
alter table eZTrade_Voucher add TotalValue int default 0;
update eZAddress_Country set HasVAT='1';
