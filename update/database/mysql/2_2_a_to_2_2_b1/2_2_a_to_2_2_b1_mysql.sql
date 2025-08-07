alter table eZTrade_OrderItem drop PriceIncVAT;
alter table eZTrade_OrderItem change VATValue VAT float(10,2);
alter table eZContact_Person change BirthDate BirthDate int;

# XML contents in trade
alter table eZTrade_Product add Contents text not null;
Update eZTrade_Product set Contents=Concat( "<?xml version=\"1.0\"?><article><generator>qdom</generator><intro>", Brief, "</intro><body><page>", Description, "</page></body></article>" );  

alter table eZTrade_ProductImageLink add Placement int not null default 0; 
alter table eZForum_Message add UserName varchar(60) default null;

#Languages in sections
alter table eZSiteManager_Section add Language varchar(5) default NULL;

alter table eZTrade_Option add RemoteID varchar(100);
alter table eZTrade_CartOptionValue add Count int default 1;
alter table eZTrade_Cart add PersonID int default 0;
alter table eZTrade_Cart add CompanyID int default 0;

alter table eZTrade_Order add Comment text;

/* speeding up the keywords */
alter table eZArticle_ArticleKeyword add index (Keyword);
alter table eZArticle_ArticleKeyword add index (ArticleID);
