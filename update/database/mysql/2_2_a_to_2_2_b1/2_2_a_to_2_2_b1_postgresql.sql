alter table eZTrade_OrderItem drop PriceIncVAT;
alter table eZTrade_OrderItem change VATValue VAT decimal(10,2);

alter table eZTrade_Option add RemoteID varchar(100);
alter table eZTrade_CartOptionValue add Count int default 1;

alter table eZTrade_Order add Comment text;

/* speeding up the keywords */
alter table eZArticle_ArticleKeyword add index (Keyword);
alter table eZArticle_ArticleKeyword add index (ArticleID);
