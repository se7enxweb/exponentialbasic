CREATE TABLE eZAd_Ad(
  ID int NOT NULL,
  Name varchar(150) default NULL,
  ImageID int default NULL,
  URL varchar(200) default NULL,
  Description text,
  IsActive int not null,
  ViewPrice float default 0.0,
  ClickPrice float default 0.0,
  HTMLBanner text default null,
  UseHTML int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_AdCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  AdID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Category (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  Description text,
  ParentID int not NULL,
  ExcludeFromSearch int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Click (
  ID int(11) NOT NULL,
  AdID int(11) default NULL,
  PageViewID int(11) default NULL,
  ClickPrice float(10,2) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_View (
  ID int NOT NULL,
  AdID int default NULL,
  ViewCount int NOT NULL,
  ViewOffsetCount int NOT NULL,
  ViewPrice float(10,2) NOT NULL,
  Date int default NULL,
  PRIMARY KEY (ID)
);CREATE TABLE eZAddress_Address (
  ID int(11) NOT NULL,
  Street1 varchar(50),
  Street2 varchar(50),
  AddressTypeID int(11),
  Place varchar(50),
  Zip varchar(10),
  CountryID int(11),
  Name varchar(50),
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_Address VALUES (1,'Adminstreet1','Adminstreet2',0,'No Place','42',0,'Default address');

CREATE TABLE eZAddress_AddressDefinition (
  UserID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (UserID,AddressID)
);

CREATE TABLE eZAddress_AddressType (
  ID int(11) NOT NULL,
  Name varchar(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_AddressType VALUES (1,'Home address',1,0);
INSERT INTO eZAddress_AddressType VALUES (2,'Office address',1,0);
INSERT INTO eZAddress_AddressType VALUES (3,'Offsite address',1,0);

CREATE TABLE eZAddress_Country (
  ID int(11) NOT NULL,
  ISO varchar(2),
  Name varchar(100),
  HasVAT int(1) DEFAULT '0',
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_Country VALUES (2,'AF','Afghanistan',0,0);
INSERT INTO eZAddress_Country VALUES (3,'AL','Albania',0,0);
INSERT INTO eZAddress_Country VALUES (4,'DZ','Algeria',0,0);
INSERT INTO eZAddress_Country VALUES (5,'AS','American Samoa',0,0);
INSERT INTO eZAddress_Country VALUES (6,'AD','Andorra',0,0);
INSERT INTO eZAddress_Country VALUES (7,'AO','Angola',0,0);
INSERT INTO eZAddress_Country VALUES (8,'AI','Anguilla',0,0);
INSERT INTO eZAddress_Country VALUES (9,'AQ','Antarctica',0,0);
INSERT INTO eZAddress_Country VALUES (10,'AG','Antigua and Barbuda',0,0);
INSERT INTO eZAddress_Country VALUES (11,'AR','Argentina',0,0);
INSERT INTO eZAddress_Country VALUES (12,'AM','Armenia',0,0);
INSERT INTO eZAddress_Country VALUES (13,'AW','Aruba',0,0);
INSERT INTO eZAddress_Country VALUES (14,'AU','Australia',0,0);
INSERT INTO eZAddress_Country VALUES (15,'AT','Austria',0,0);
INSERT INTO eZAddress_Country VALUES (16,'AZ','Azerbaijan',0,0);
INSERT INTO eZAddress_Country VALUES (17,'BS','Bahamas',0,0);
INSERT INTO eZAddress_Country VALUES (18,'BH','Bahrain',0,0);
INSERT INTO eZAddress_Country VALUES (19,'BD','Bangladesh',0,0);
INSERT INTO eZAddress_Country VALUES (20,'BB','Barbados',0,0);
INSERT INTO eZAddress_Country VALUES (21,'BY','Belarus',0,0);
INSERT INTO eZAddress_Country VALUES (22,'BE','Belgium',0,0);
INSERT INTO eZAddress_Country VALUES (23,'BZ','Belize',0,0);
INSERT INTO eZAddress_Country VALUES (24,'BJ','Benin',0,0);
INSERT INTO eZAddress_Country VALUES (25,'BM','Bermuda',0,0);
INSERT INTO eZAddress_Country VALUES (26,'BT','Bhutan',0,0);
INSERT INTO eZAddress_Country VALUES (27,'BO','Bolivia',0,0);
INSERT INTO eZAddress_Country VALUES (28,'BA','Bosnia and Herzegovina',0,0);
INSERT INTO eZAddress_Country VALUES (29,'BW','Botswana',0,0);
INSERT INTO eZAddress_Country VALUES (30,'BV','Bouvet Island',0,0);
INSERT INTO eZAddress_Country VALUES (31,'BR','Brazil',0,0);
INSERT INTO eZAddress_Country VALUES (32,'IO','British Indian Ocean Territory',0,0);
INSERT INTO eZAddress_Country VALUES (33,'BN','Brunei Darussalam',0,0);
INSERT INTO eZAddress_Country VALUES (34,'BG','Bulgaria',0,0);
INSERT INTO eZAddress_Country VALUES (35,'BF','Burkina Faso',0,0);
INSERT INTO eZAddress_Country VALUES (36,'BI','Burundi',0,0);
INSERT INTO eZAddress_Country VALUES (37,'KH','Cambodia',0,0);
INSERT INTO eZAddress_Country VALUES (38,'CM','Cameroon',0,0);
INSERT INTO eZAddress_Country VALUES (39,'CA','Canada',0,0);
INSERT INTO eZAddress_Country VALUES (40,'CV','Cape Verde',0,0);
INSERT INTO eZAddress_Country VALUES (41,'KY','Cayman Islands',0,0);
INSERT INTO eZAddress_Country VALUES (42,'CF','Central African Republic',0,0);
INSERT INTO eZAddress_Country VALUES (43,'TD','Chad',0,0);
INSERT INTO eZAddress_Country VALUES (44,'CL','Chile',0,0);
INSERT INTO eZAddress_Country VALUES (45,'CN','China',0,0);
INSERT INTO eZAddress_Country VALUES (46,'CX','Christmas Island',0,0);
INSERT INTO eZAddress_Country VALUES (47,'CC','Cocos (Keeling) Islands',0,0);
INSERT INTO eZAddress_Country VALUES (48,'CO','Colombia',0,0);
INSERT INTO eZAddress_Country VALUES (49,'KM','Comoros',0,0);
INSERT INTO eZAddress_Country VALUES (50,'CG','Congo',0,0);
INSERT INTO eZAddress_Country VALUES (51,'CK','Cook Islands',0,0);
INSERT INTO eZAddress_Country VALUES (52,'CR','Costa Rica',0,0);
INSERT INTO eZAddress_Country VALUES (53,'CI','Cote d\'Ivoire',0,0);
INSERT INTO eZAddress_Country VALUES (54,'HR','Croatia',0,0);
INSERT INTO eZAddress_Country VALUES (55,'CU','Cuba',0,0);
INSERT INTO eZAddress_Country VALUES (56,'CY','Cyprus',0,0);
INSERT INTO eZAddress_Country VALUES (57,'CZ','Czech Republic',0,0);
INSERT INTO eZAddress_Country VALUES (58,'DK','Denmark',0,0);
INSERT INTO eZAddress_Country VALUES (59,'DJ','Djibouti',0,0);
INSERT INTO eZAddress_Country VALUES (60,'DM','Dominica',0,0);
INSERT INTO eZAddress_Country VALUES (61,'DO','Dominican Republic',0,0);
INSERT INTO eZAddress_Country VALUES (62,'TP','East Timor',0,0);
INSERT INTO eZAddress_Country VALUES (63,'EC','Ecuador',0,0);
INSERT INTO eZAddress_Country VALUES (64,'EG','Egypt',0,0);
INSERT INTO eZAddress_Country VALUES (65,'SV','El Salvador',0,0);
INSERT INTO eZAddress_Country VALUES (66,'GQ','Equatorial Guinea',0,0);
INSERT INTO eZAddress_Country VALUES (67,'ER','Eritrea',0,0);
INSERT INTO eZAddress_Country VALUES (68,'EE','Estonia',0,0);
INSERT INTO eZAddress_Country VALUES (69,'ET','Ethiopia',0,0);
INSERT INTO eZAddress_Country VALUES (70,'FK','Falkland Islands (Malvinas)',0,0);
INSERT INTO eZAddress_Country VALUES (71,'FO','Faroe Islands',0,0);
INSERT INTO eZAddress_Country VALUES (72,'FJ','Fiji',0,0);
INSERT INTO eZAddress_Country VALUES (73,'FI','Finland',0,0);
INSERT INTO eZAddress_Country VALUES (74,'FR','France',0,0);
INSERT INTO eZAddress_Country VALUES (75,'FX','France, Metropolitan',0,0);
INSERT INTO eZAddress_Country VALUES (76,'GF','French Guiana',0,0);
INSERT INTO eZAddress_Country VALUES (77,'PF','French Polynesia',0,0);
INSERT INTO eZAddress_Country VALUES (78,'TF','French Southern Territories',0,0);
INSERT INTO eZAddress_Country VALUES (79,'GA','Gabon',0,0);
INSERT INTO eZAddress_Country VALUES (80,'GM','Gambia',0,0);
INSERT INTO eZAddress_Country VALUES (81,'GE','Georgia',0,0);
INSERT INTO eZAddress_Country VALUES (82,'DE','Germany',0,0);
INSERT INTO eZAddress_Country VALUES (83,'GH','Ghana',0,0);
INSERT INTO eZAddress_Country VALUES (84,'GI','Gibraltar',0,0);
INSERT INTO eZAddress_Country VALUES (85,'GR','Greece',0,0);
INSERT INTO eZAddress_Country VALUES (86,'GL','Greenland',0,0);
INSERT INTO eZAddress_Country VALUES (87,'GD','Grenada',0,0);
INSERT INTO eZAddress_Country VALUES (88,'GP','Guadeloupe',0,0);
INSERT INTO eZAddress_Country VALUES (89,'GU','Guam',0,0);
INSERT INTO eZAddress_Country VALUES (90,'GT','Guatemala',0,0);
INSERT INTO eZAddress_Country VALUES (91,'GN','Guinea',0,0);
INSERT INTO eZAddress_Country VALUES (92,'GW','Guinea-Bissau',0,0);
INSERT INTO eZAddress_Country VALUES (93,'GY','Guyana',0,0);
INSERT INTO eZAddress_Country VALUES (94,'HT','Haiti',0,0);
INSERT INTO eZAddress_Country VALUES (95,'HM','Heard Island and McDonald Islands',0,0);
INSERT INTO eZAddress_Country VALUES (96,'HN','Honduras',0,0);
INSERT INTO eZAddress_Country VALUES (97,'HK','Hong Kong',0,0);
INSERT INTO eZAddress_Country VALUES (98,'HU','Hungary',0,0);
INSERT INTO eZAddress_Country VALUES (99,'IS','Iceland',0,0);
INSERT INTO eZAddress_Country VALUES (100,'IN','India',0,0);
INSERT INTO eZAddress_Country VALUES (101,'ID','Indonesia',0,0);
INSERT INTO eZAddress_Country VALUES (102,'IR','Iran (Islamic Republic of)',0,0);
INSERT INTO eZAddress_Country VALUES (103,'IQ','Iraq',0,0);
INSERT INTO eZAddress_Country VALUES (104,'IE','Ireland',0,0);
INSERT INTO eZAddress_Country VALUES (105,'IL','Israel',0,0);
INSERT INTO eZAddress_Country VALUES (106,'IT','Italy',0,0);
INSERT INTO eZAddress_Country VALUES (107,'JM','Jamaica',0,0);
INSERT INTO eZAddress_Country VALUES (108,'JP','Japan',0,0);
INSERT INTO eZAddress_Country VALUES (109,'JO','Jordan',0,0);
INSERT INTO eZAddress_Country VALUES (110,'KZ','Kazakhstan',0,0);
INSERT INTO eZAddress_Country VALUES (111,'KE','Kenya',0,0);
INSERT INTO eZAddress_Country VALUES (112,'KI','Kiribati',0,0);
INSERT INTO eZAddress_Country VALUES (113,'KP','Korea, Democratic People\'s Republic of',0,0);
INSERT INTO eZAddress_Country VALUES (114,'KR','Korea, Republic of',0,0);
INSERT INTO eZAddress_Country VALUES (115,'KW','Kuwait',0,0);
INSERT INTO eZAddress_Country VALUES (116,'KG','Kyrgyzstan',0,0);
INSERT INTO eZAddress_Country VALUES (117,'LA','Lao People\'s Democratic Republic',0,0);
INSERT INTO eZAddress_Country VALUES (118,'LT','Latin America',0,0);
INSERT INTO eZAddress_Country VALUES (119,'LV','Latvia',0,0);
INSERT INTO eZAddress_Country VALUES (120,'LB','Lebanon',0,0);
INSERT INTO eZAddress_Country VALUES (121,'LS','Lesotho',0,0);
INSERT INTO eZAddress_Country VALUES (122,'LR','Liberia',0,0);
INSERT INTO eZAddress_Country VALUES (123,'LY','Libyan Arab Jamahiriya',0,0);
INSERT INTO eZAddress_Country VALUES (124,'LI','Liechtenstein',0,0);
INSERT INTO eZAddress_Country VALUES (125,'LX','Lithuania',0,0);
INSERT INTO eZAddress_Country VALUES (126,'LU','Luxembourg',0,0);
INSERT INTO eZAddress_Country VALUES (127,'MO','Macau',0,0);
INSERT INTO eZAddress_Country VALUES (128,'MK','Macedonia',0,0);
INSERT INTO eZAddress_Country VALUES (129,'MG','Madagascar',0,0);
INSERT INTO eZAddress_Country VALUES (130,'MW','Malawi',0,0);
INSERT INTO eZAddress_Country VALUES (131,'MY','Malaysia',0,0);
INSERT INTO eZAddress_Country VALUES (132,'MV','Maldives',0,0);
INSERT INTO eZAddress_Country VALUES (133,'ML','Mali',0,0);
INSERT INTO eZAddress_Country VALUES (134,'MT','Malta',0,0);
INSERT INTO eZAddress_Country VALUES (135,'MH','Marshall Islands',0,0);
INSERT INTO eZAddress_Country VALUES (136,'MQ','Martinique',0,0);
INSERT INTO eZAddress_Country VALUES (137,'MR','Mauritania',0,0);
INSERT INTO eZAddress_Country VALUES (138,'MU','Mauritius',0,0);
INSERT INTO eZAddress_Country VALUES (139,'YT','Mayotte',0,0);
INSERT INTO eZAddress_Country VALUES (140,'MX','Mexico',0,0);
INSERT INTO eZAddress_Country VALUES (141,'FM','Micronesia (Federated States of)',0,0);
INSERT INTO eZAddress_Country VALUES (142,'MD','Moldova, Republic of',0,0);
INSERT INTO eZAddress_Country VALUES (143,'MC','Monaco',0,0);
INSERT INTO eZAddress_Country VALUES (144,'MN','Mongolia',0,0);
INSERT INTO eZAddress_Country VALUES (145,'MS','Montserrat',0,0);
INSERT INTO eZAddress_Country VALUES (146,'MA','Morocco',0,0);
INSERT INTO eZAddress_Country VALUES (147,'MZ','Mozambique',0,0);
INSERT INTO eZAddress_Country VALUES (148,'MM','Myanmar',0,0);
INSERT INTO eZAddress_Country VALUES (149,'NA','Namibia',0,0);
INSERT INTO eZAddress_Country VALUES (150,'NR','Nauru',0,0);
INSERT INTO eZAddress_Country VALUES (151,'NP','Nepal',0,0);
INSERT INTO eZAddress_Country VALUES (152,'NL','Netherlands',0,0);
INSERT INTO eZAddress_Country VALUES (153,'AN','Netherlands Antilles',0,0);
INSERT INTO eZAddress_Country VALUES (154,'NC','New Caledonia',0,0);
INSERT INTO eZAddress_Country VALUES (155,'NZ','New Zealand',0,0);
INSERT INTO eZAddress_Country VALUES (156,'NI','Nicaragua',0,0);
INSERT INTO eZAddress_Country VALUES (157,'NE','Niger',0,0);
INSERT INTO eZAddress_Country VALUES (158,'NG','Nigeria',0,0);
INSERT INTO eZAddress_Country VALUES (159,'NU','Niue',0,0);
INSERT INTO eZAddress_Country VALUES (160,'NF','Norfolk Island',0,0);
INSERT INTO eZAddress_Country VALUES (161,'MP','Northern Mariana Islands',0,0);
INSERT INTO eZAddress_Country VALUES (162,'NO','Norway',0,0);
INSERT INTO eZAddress_Country VALUES (163,'OM','Oman',0,0);
INSERT INTO eZAddress_Country VALUES (164,'PK','Pakistan',0,0);
INSERT INTO eZAddress_Country VALUES (165,'PW','Palau',0,0);
INSERT INTO eZAddress_Country VALUES (166,'PA','Panama',0,0);
INSERT INTO eZAddress_Country VALUES (167,'PG','Papua New Guinea',0,0);
INSERT INTO eZAddress_Country VALUES (168,'PY','Paraguay',0,0);
INSERT INTO eZAddress_Country VALUES (169,'PE','Peru',0,0);
INSERT INTO eZAddress_Country VALUES (170,'PH','Philippines',0,0);
INSERT INTO eZAddress_Country VALUES (171,'PN','Pitcairn',0,0);
INSERT INTO eZAddress_Country VALUES (172,'PL','Poland',0,0);
INSERT INTO eZAddress_Country VALUES (173,'PT','Portugal',0,0);
INSERT INTO eZAddress_Country VALUES (174,'PR','Puerto Rico',0,0);
INSERT INTO eZAddress_Country VALUES (175,'QA','Qatar',0,0);
INSERT INTO eZAddress_Country VALUES (176,'RE','Reunion',0,0);
INSERT INTO eZAddress_Country VALUES (177,'RO','Romania',0,0);
INSERT INTO eZAddress_Country VALUES (178,'RU','Russian Federation',0,0);
INSERT INTO eZAddress_Country VALUES (179,'RW','Rwanda',0,0);
INSERT INTO eZAddress_Country VALUES (180,'SH','Saint Helena',0,0);
INSERT INTO eZAddress_Country VALUES (181,'KN','Saint Kitts and Nevis',0,0);
INSERT INTO eZAddress_Country VALUES (182,'LC','Saint Lucia',0,0);
INSERT INTO eZAddress_Country VALUES (183,'PM','Saint Pierre and Miquelon',0,0);
INSERT INTO eZAddress_Country VALUES (184,'VC','Saint Vincent and the Grenadines',0,0);
INSERT INTO eZAddress_Country VALUES (185,'WS','Samoa',0,0);
INSERT INTO eZAddress_Country VALUES (186,'SM','San Marino',0,0);
INSERT INTO eZAddress_Country VALUES (187,'ST','Sao Tome and Principe',0,0);
INSERT INTO eZAddress_Country VALUES (188,'SA','Saudi Arabia',0,0);
INSERT INTO eZAddress_Country VALUES (189,'SN','Senegal',0,0);
INSERT INTO eZAddress_Country VALUES (190,'SC','Seychelles',0,0);
INSERT INTO eZAddress_Country VALUES (191,'SL','Sierra Leone',0,0);
INSERT INTO eZAddress_Country VALUES (192,'SG','Singapore',0,0);
INSERT INTO eZAddress_Country VALUES (193,'SK','Slovakia',0,0);
INSERT INTO eZAddress_Country VALUES (194,'SI','Slovenia',0,0);
INSERT INTO eZAddress_Country VALUES (195,'SB','Solomon Islands',0,0);
INSERT INTO eZAddress_Country VALUES (196,'SO','Somalia',0,0);
INSERT INTO eZAddress_Country VALUES (197,'ZA','South Africa',0,0);
INSERT INTO eZAddress_Country VALUES (198,'GS','South Georgia and the South Sandwich Island',0,0);
INSERT INTO eZAddress_Country VALUES (199,'ES','Spain',0,0);
INSERT INTO eZAddress_Country VALUES (200,'LK','Sri Lanka',0,0);
INSERT INTO eZAddress_Country VALUES (201,'SD','Sudan',0,0);
INSERT INTO eZAddress_Country VALUES (202,'SR','Suriname',0,0);
INSERT INTO eZAddress_Country VALUES (203,'SJ','Svalbard and Jan Mayen Islands',0,0);
INSERT INTO eZAddress_Country VALUES (204,'SZ','Swaziland',0,0);
INSERT INTO eZAddress_Country VALUES (205,'SE','Sweden',0,0);
INSERT INTO eZAddress_Country VALUES (206,'CH','Switzerland',0,0);
INSERT INTO eZAddress_Country VALUES (207,'SY','Syrian Arab Republic',0,0);
INSERT INTO eZAddress_Country VALUES (208,'TW','Taiwan, Republic of China',0,0);
INSERT INTO eZAddress_Country VALUES (209,'TJ','Tajikistan',0,0);
INSERT INTO eZAddress_Country VALUES (210,'TZ','Tanzania, United Republic of',0,0);
INSERT INTO eZAddress_Country VALUES (211,'TH','Thailand',0,0);
INSERT INTO eZAddress_Country VALUES (212,'TG','Togo',0,0);
INSERT INTO eZAddress_Country VALUES (213,'TK','Tokelau',0,0);
INSERT INTO eZAddress_Country VALUES (214,'TO','Tonga',0,0);
INSERT INTO eZAddress_Country VALUES (215,'TT','Trinidad and Tobago',0,0);
INSERT INTO eZAddress_Country VALUES (216,'TN','Tunisia',0,0);
INSERT INTO eZAddress_Country VALUES (217,'TR','Turkey',0,0);
INSERT INTO eZAddress_Country VALUES (218,'TM','Turkmenistan',0,0);
INSERT INTO eZAddress_Country VALUES (219,'TC','Turks and Caicos Islands',0,0);
INSERT INTO eZAddress_Country VALUES (220,'TV','Tuvalu',0,0);
INSERT INTO eZAddress_Country VALUES (221,'UG','Uganda',0,0);
INSERT INTO eZAddress_Country VALUES (222,'UA','Ukraine',0,0);
INSERT INTO eZAddress_Country VALUES (223,'AE','United Arab Emirates',0,0);
INSERT INTO eZAddress_Country VALUES (224,'GB','United Kingdom',0,0);
INSERT INTO eZAddress_Country VALUES (225,'UM','United States Minor Outlying Islands',0,0);
INSERT INTO eZAddress_Country VALUES (226,'UY','Uruguay',0,0);
INSERT INTO eZAddress_Country VALUES (227,'UZ','Uzbekistan',0,0);
INSERT INTO eZAddress_Country VALUES (228,'VU','Vanuatu',0,0);
INSERT INTO eZAddress_Country VALUES (229,'VA','Vatican City State (Holy See)',0,0);
INSERT INTO eZAddress_Country VALUES (230,'VE','Venezuela',0,0);
INSERT INTO eZAddress_Country VALUES (231,'VN','Viet Nam',0,0);
INSERT INTO eZAddress_Country VALUES (232,'VG','Virgin Islands (British)',0,0);
INSERT INTO eZAddress_Country VALUES (233,'VI','Virgin Islands (U.S.)',0,0);
INSERT INTO eZAddress_Country VALUES (234,'WF','Wallis and Futuna Islands',0,0);
INSERT INTO eZAddress_Country VALUES (235,'EH','Western Sahara',0,0);
INSERT INTO eZAddress_Country VALUES (236,'YE','Yemen',0,0);
INSERT INTO eZAddress_Country VALUES (237,'YU','Yugoslavia',0,0);
INSERT INTO eZAddress_Country VALUES (238,'ZR','Zaire',0,0);
INSERT INTO eZAddress_Country VALUES (239,'ZM','Zambia',0,0);
INSERT INTO eZAddress_Country VALUES (240,'US','United States of America',0,0);

CREATE TABLE eZAddress_Online (
  ID int(11) NOT NULL,
  URL varchar(255),
  OnlineTypeID int(11),
  PRIMARY KEY (ID)
);

CREATE TABLE eZAddress_OnlineType (
  ID int(11) NOT NULL,
  Name varchar(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  URLPrefix varchar(30) DEFAULT '' NOT NULL,
  PrefixLink int(1) DEFAULT '0' NOT NULL,
  PrefixVisual int(1) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_OnlineType VALUES (1,'Email',1,'mailto:',1,0,0);
INSERT INTO eZAddress_OnlineType VALUES (2,'Email : Secondary',1,'mailto:',1,0,0);
INSERT INTO eZAddress_OnlineType VALUES (3,'Website : Personal',1,'',1,0,0);
INSERT INTO eZAddress_OnlineType VALUES (4,'Website : Office',1,'',1,0,0);

CREATE TABLE eZAddress_Phone (
  ID int(11) NOT NULL,
  Number varchar(22),
  PhoneTypeID int(11),
  PRIMARY KEY (ID)
);

CREATE TABLE eZAddress_PhoneType (
  ID int(11) NOT NULL,
  Name varchar(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_PhoneType VALUES (1,'Personal Phone',1,0);
INSERT INTO eZAddress_PhoneType VALUES (2,'Personal Cell',1,0);
INSERT INTO eZAddress_PhoneType VALUES (3,'Personal Fax',1,0);
INSERT INTO eZAddress_PhoneType VALUES (4,'Office Phone',1,0);
INSERT INTO eZAddress_PhoneType VALUES (5,'Office Cell',1,0);
INSERT INTO eZAddress_PhoneType VALUES (6,'Office Fax',1,0);



CREATE TABLE eZArticle_Article (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Contents text,
  ContentsWriterID int default NULL,
  LinkText varchar(50) default NULL,
  AuthorID int NOT NULL default '0',
  Modified int NOT NULL,
  Created int NOT NULL,
  Published int NOT NULL,
  PageCount int default NULL,
  IsPublished int default '0',
  Keywords text,
  Discuss int default '0',
  TopicID int NOT NULL default '0',
  StartDate int NOT NULL,
  StopDate int NOT NULL,
  ImportID varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleCategoryDefinition (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (ID)
);


CREATE TABLE eZArticle_ArticleCategoryLink (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  Placement int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleFileLink (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  FileID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleFormDict (
  ID int NOT NULL,
  ArticleID int default NULL,
  FormID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleForumLink (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  ForumID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleImageDefinition (
  ArticleID int NOT NULL default '0',
  ThumbnailImageID int default NULL,
  PRIMARY KEY (ArticleID )
);

CREATE TABLE eZArticle_ArticleImageLink (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  ImageID int NOT NULL default '0',
  Created int NOT NULL,
  Placement int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleKeyword (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  Keyword varchar(50) NOT NULL default '',
  Automatic int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticlePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleTypeLink (
  ID int NOT NULL,
  ArticleID int default NULL,
  TypeID int default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZArticle_Attribute (
  ID int NOT NULL,
  TypeID int default NULL,
  Name char(150) default NULL,
  Placement int default NULL,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_AttributeValue (
  ID int NOT NULL,
  ArticleID int default NULL,
  AttributeID int default NULL,
  Value text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_BulkMailCategoryLink (
  ArticleCategoryID int NOT NULL default '0',
  BulkMailCategoryID int NOT NULL default '0',
  PRIMARY KEY (ArticleCategoryID,BulkMailCategoryID)
);

CREATE TABLE eZArticle_Category (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int default '0',
  ExcludeFromSearch int default '0',
  SortMode int NOT NULL default '1',
  OwnerID int default '0',
  Placement int default '0',
  SectionID int NOT NULL default '0',
  ImageID int default NULL,
  EditorGroupID int default '0',
  ListLimit int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_CategoryReaderLink (
  ID int NOT NULL,
  CategoryID int NOT NULL default '0',
  GroupID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_Log (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  Created int NOT NULL,
  Message text NOT NULL,
  UserID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_Topic (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  Description text,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_Type (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleMediaLink (
  ID int(11) NOT NULL,
  ArticleID int(11) NOT NULL default '0',
  MediaID int(11) NOT NULL default '0',
  Created int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleWordLink (
  ArticleID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  WordID int(11) NOT NULL default '0'
);

CREATE TABLE eZArticle_Word (
  ID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  Word varchar(50) NOT NULL default ''
);

CREATE TABLE eZArticle_ArticleKeywordFirstLetter (
  ID int(11) NOT NULL default '0',
  Letter char(1) NOT NULL default ''
);


CREATE INDEX Article_Name ON eZArticle_Article (Name);
CREATE INDEX Article_Published ON eZArticle_Article (Published);
# CREATE FULLTEXT INDEX Article_Fulltext ON eZArticle_Article (Contents);
# CREATE FULLTEXT INDEX Article_FulltextName ON eZArticle_Article (Name);

CREATE INDEX Link_ArticleID ON eZArticle_ArticleCategoryLink (ArticleID);
CREATE INDEX Link_CategoryID ON eZArticle_ArticleCategoryLink (CategoryID);
CREATE INDEX Link_Placement ON eZArticle_ArticleCategoryLink (Placement);

CREATE INDEX WordLink_ArticleID ON eZArticle_ArticleWordLink (ArticleID);
CREATE INDEX WordLink_WordID ON eZArticle_ArticleWordLink (WordID);
CREATE INDEX Word_Word ON eZArticle_Word (Word);
CREATE UNIQUE INDEX Word_ID ON eZArticle_Word (ID);
CREATE UNIQUE INDEX ArticleID ON eZArticle_ArticleImageDefinition (ArticleID);

CREATE INDEX ArticlePermission_ObjectID ON eZArticle_ArticlePermission (ObjectID);
CREATE INDEX ArticlePermission_GroupID ON eZArticle_ArticlePermission (GroupID);
CREATE INDEX ArticlePermission_WritePermission ON eZArticle_ArticlePermission (WritePermission);
CREATE INDEX ArticlePermission_ReadPermission ON eZArticle_ArticlePermission (ReadPermission);

CREATE INDEX Def_ArticleID ON eZArticle_ArticleCategoryDefinition (ArticleID);
CREATE INDEX Def_CategoryID ON eZArticle_ArticleCategoryDefinition (CategoryID);

CREATE INDEX ArticleKeyword_Keyword ON eZArticle_ArticleKeyword (Keyword);
CREATE INDEX ArticleKeyword_ArticleID ON eZArticle_ArticleKeyword (ArticleID);

CREATE INDEX ArticleAttribute_Placement ON eZArticle_Attribute (Placement);
CREATE INDEX ArticleAttributeValue_ArticleID ON eZArticle_AttributeValue (ArticleID, AttributeID);CREATE TABLE eZBug_Bug (
  ID int NOT NULL,
  Name varchar(150),
  Description text,
  UserID int DEFAULT '0' NOT NULL,
  Created int,
  IsHandled int DEFAULT '0' NOT NULL,
  PriorityID int DEFAULT '0' NOT NULL,
  StatusID int DEFAULT '0' NOT NULL,
  IsClosed int DEFAULT '0',
  Version varchar(150) DEFAULT '',
  UserEmail varchar(100) DEFAULT '',
  OwnerID int default NULL,
  IsPrivate int default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Bug VALUES (1,'Help!','It dosent work!',33,997357856,0,0,0,null,'','',null,'0');

CREATE TABLE eZBug_BugCategoryLink (
  ID int NOT NULL,
  CategoryID int,
  BugID int,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_BugCategoryLink VALUES (1,2,1);

CREATE TABLE eZBug_BugFileLink (
  ID int NOT NULL,
  BugID int NOT NULL default '0',
  FileID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_BugImageLink (
  ID int NOT NULL,
  BugID int NOT NULL default '0',
  ImageID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_BugModuleLink (
  ID int NOT NULL,
  ModuleID int,
  BugID int,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_BugModuleLink VALUES (1,1,1);

CREATE TABLE eZBug_Category (
  ID int NOT NULL,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Category VALUES (1,'GUI','');
INSERT INTO eZBug_Category VALUES (2,'Feature request','');
INSERT INTO eZBug_Category VALUES (3,'Suggestion','');
INSERT INTO eZBug_Category VALUES (4,'Enhancement','');
INSERT INTO eZBug_Category VALUES (5,'Bug','');

CREATE TABLE eZBug_Log (
  ID int NOT NULL,
  BugID int DEFAULT '0' NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  Description text,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZBug_Module (
  ID int NOT NULL,
  ParentID int,
  Name varchar(150),
  Description text,
  OwnerGroupID int default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Module VALUES (1,0,'My program','', 1);

CREATE TABLE eZBug_Priority (
  ID int NOT NULL,
  Name varchar(150) DEFAULT '' NOT NULL,
  Value int,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Priority VALUES (1,'High',NULL);
INSERT INTO eZBug_Priority VALUES (2,'Medium',NULL);
INSERT INTO eZBug_Priority VALUES (3,'Low',NULL);
INSERT INTO eZBug_Priority VALUES (4,'Critical',NULL);

CREATE TABLE eZBug_Status (
  ID int NOT NULL,
  Name varchar(150) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Status VALUES (1,'Fixed');
INSERT INTO eZBug_Status VALUES (2,'Duplicate');
INSERT INTO eZBug_Status VALUES (3,'Invalid');
INSERT INTO eZBug_Status VALUES (4,'Will not fix');
INSERT INTO eZBug_Status VALUES (5,'Works here');
INSERT INTO eZBug_Status VALUES (6,'Future addition');


CREATE TABLE eZBug_ModulePermission (
  ID int(11) NOT NULL,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
);CREATE TABLE eZBulkMail_Category (
  ID int(11) NOT NULL,
  Name varchar(200) default NULL,
  Description text,
  IsPublic int NOT NULL,
  IsSingleCategory int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_Mail (
  ID int(11) NOT NULL,
  UserID int(11) default '0',
  FromField varchar(100) default NULL,
  FromName varchar(100) default NULL,
  ReplyTo varchar(100) default NULL,
  Subject varchar(255) default NULL,
  BodyText text,
  SentDate int(14) default 0,
  IsDraft int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,CategoryID)
);

CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int(11) NOT NULL default '0',
  TemplateID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID)
);

CREATE TABLE eZBulkMail_SentLog (
  ID int(11) NOT NULL,
  MailID int(11) NOT NULL default '0',
  Mail varchar(255) default NULL,
  SentDate int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionAddress (
  ID int(11) NOT NULL,
  Password varchar(50) NOT NULL,
  EMail varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionLink (
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (AddressID,CategoryID)
);

CREATE TABLE eZBulkMail_Template (
  ID int(11) NOT NULL,
  Name varchar(200) default NULL,
  Description text default NULL,
  Header text,
  Footer text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_GroupCategoryLink (
  CategoryID int NOT NULL,
  GroupID int NOT NULL,
  PRIMARY KEY (CategoryID, GroupID)
);

CREATE TABLE eZBulkMail_Forgot (
  ID int(11) NOT NULL,
  Mail varchar(255) NOT NULL,
  Password varchar(50) NOT NULL,
  Hash varchar(33),
  Time int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_CategoryDelay (
  ID int(11) NOT NULL,
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  Delay int(11) default '0',
  MailID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_Offset (
  ID int(11) NOT NULL,
  Hour int(11) default NULL,
  Daily int(11) default NULL,
  Weekly int(11) default NULL,
  Monthly int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionCategorySettings (
  ID int(11) NOT NULL,
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  Delay int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_UserCategoryDelay (
  ID int(11) NOT NULL,
  CategoryID int(11) default '0',
  UserID int(11) default '0',
  Delay int(11) default '0',
  MailID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_UserCategoryLink (
  UserID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (UserID, CategoryID)
);

CREATE TABLE eZBulkMail_UserCategorySettings (
  ID int(11) NOT NULL,
  CategoryID int(11) default '0',
  UserID int(11) default '0',
  Delay int(11) default '0',
  PRIMARY KEY (ID)
);CREATE TABLE eZCalendar_Appointment (
  ID int NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  Date int,
  Duration int,
  AllDay int,
  AppointmentTypeID int DEFAULT '0' NOT NULL,
  EMailNotice int DEFAULT '0',
  IsPrivate int,
  Name varchar(200),
  Description text,
  Priority int DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZCalendar_AppointmentType (
  ID int NOT NULL,
  ParentID int DEFAULT '0' NOT NULL,
  Description varchar(200) DEFAULT NULL,
  Name varchar(200),
  PRIMARY KEY (ID)
);
CREATE TABLE eZContact_Company (
  ID int NOT NULL,
  CreatorID int DEFAULT '0' NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  Comment text,
  ContactType int DEFAULT '0' NOT NULL,
  CompanyNo varchar(20) DEFAULT '' NOT NULL,
  ContactID int DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZContact_CompanyAddressDict (
  CompanyID int NOT NULL,
  AddressID int NOT NULL,
  PRIMARY KEY (CompanyID,AddressID)
);

CREATE TABLE eZContact_CompanyImageDefinition (
  CompanyID int NOT NULL,
  CompanyImageID int DEFAULT '0' NOT NULL,
  LogoImageID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID)
);

CREATE TABLE eZContact_CompanyIndex (
  CompanyID int(11) NOT NULL default '0',
  Value varchar(255) NOT NULL default '',
  Type int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,Value)
);

CREATE TABLE eZContact_CompanyOnlineDict (
  CompanyID int NOT NULL,
  OnlineID int NOT NULL,
  PRIMARY KEY (CompanyID,OnlineID)
);

CREATE TABLE eZContact_CompanyPersonDict (
  CompanyID int NOT NULL,
  PersonID int NOT NULL,
  PRIMARY KEY (CompanyID,PersonID)
);

CREATE TABLE eZContact_CompanyPhoneDict (
  CompanyID int NOT NULL,
  PhoneID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,PhoneID)
);

CREATE TABLE eZContact_CompanyProjectDict (
  CompanyID int NOT NULL,
  ProjectID int NOT NULL,
  PRIMARY KEY (CompanyID,ProjectID)
);

CREATE TABLE eZContact_CompanyType (
  ID int NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  Description text,
  ParentID int DEFAULT '0' NOT NULL,
  ImageID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE INDEX CompanyType_ParentID ON eZContact_CompanyType (ParentID);
CREATE INDEX CompanyType_Name ON eZContact_CompanyType (Name);

CREATE TABLE eZContact_CompanyTypeDict (
  CompanyTypeID int NOT NULL,
  CompanyID int NOT NULL,
  PRIMARY KEY (CompanyTypeID,CompanyID)
);

CREATE TABLE eZContact_Consultation (
  ID int NOT NULL,
  ShortDesc varchar(100) DEFAULT '' NOT NULL,
  Description text NOT NULL,
  Date int,
  StateID int DEFAULT '0' NOT NULL,
  EmailNotifications varchar(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZContact_ConsultationCompanyDict (
  ConsultationID int DEFAULT '0' NOT NULL,
  CompanyID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,CompanyID)
);

CREATE TABLE eZContact_ConsultationCompanyUserDict (
  ConsultationID int NOT NULL,
  CompanyID int DEFAULT '0' NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,CompanyID,UserID)
);

CREATE TABLE eZContact_ConsultationGroupsDict (
  ConsultationID int NOT NULL,
  GroupID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,GroupID)
);

CREATE TABLE eZContact_ConsultationPersonUserDict (
  ConsultationID int NOT NULL,
  PersonID int DEFAULT '0' NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,PersonID,UserID)
);

CREATE TABLE eZContact_ConsultationType (
  ID int NOT NULL,
  Name varchar(50),
  ListOrder int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZContact_ContactType (
  ID int NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZContact_Person (
  ID int NOT NULL,
  FirstName varchar(50),
  LastName varchar(50),
  BirthDate int,
  Comment text,
  ContactTypeID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZContact_PersonAddressDict (
  PersonID int NOT NULL,
  AddressID int NOT NULL,
  PRIMARY KEY (PersonID,AddressID)
);

CREATE TABLE eZContact_PersonIndex (
  PersonID int(11) NOT NULL default '0',
  Value varchar(255) NOT NULL default '',
  Type int(11) NOT NULL default '0',
  PRIMARY KEY (PersonID,Value)
);

CREATE TABLE eZContact_PersonOnlineDict (
  PersonID int NOT NULL,
  OnlineID int NOT NULL,
  PRIMARY KEY (PersonID,OnlineID)
);

CREATE TABLE eZContact_PersonPhoneDict (
  PersonID int NOT NULL,
  PhoneID int NOT NULL,
  PRIMARY KEY (PersonID,PhoneID)
);

CREATE TABLE eZContact_PersonProjectDict (
  PersonID int NOT NULL,
  ProjectID int NOT NULL,
  PRIMARY KEY (PersonID,ProjectID)
);

CREATE TABLE eZContact_ProjectType (
  ID int NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  ListOrder int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZContact_UserCompanyDict (
  UserID int NOT NULL,
  CompanyID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (UserID,CompanyID)
);

CREATE UNIQUE INDEX eZContactUserCompanyDictCompanyID ON eZContact_UserCompanyDict(CompanyID);
CREATE UNIQUE INDEX eZContactUserCompanyDictUserID ON eZContact_UserCompanyDict(UserID);

CREATE TABLE eZContact_UserPersonDict (
  UserID int NOT NULL,
  PersonID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (UserID,PersonID)
);

CREATE UNIQUE INDEX eZContactUserPersonDictPersonID ON eZContact_UserPersonDict(PersonID);
CREATE UNIQUE INDEX eZContactUserPersonDictUserID ON eZContact_UserPersonDict(UserID);

CREATE TABLE eZContact_CompanyView (
  ID int NOT NULL,
  CompanyID int default '0' NOT NULL,
  Count int default '0' NOT NULL,
  Date int NOT NULL,
  PRIMARY KEY (ID,CompanyID,Date)
);

CREATE TABLE eZContact_CompanyImageDict (
  CompanyID int DEFAULT '0' NOT NULL,
  ImageID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,ImageID)
);CREATE TABLE eZExample_Test (
  ID int DEFAULT '0' NOT NULL,
  Text varchar(255),
  Created int,
  PRIMARY KEY (ID)
);
Create TABLE eZFileManager_File (
  ID int(11) DEFAULT '0' NOT NULL,
  Name char(200),
  Description char(200),
  FileName char(200),
  OriginalFileName char(200),
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FileFolderLink (
  ID int(11) DEFAULT '0' NOT NULL,
  FolderID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FilePageViewLink (
  ID int(11) DEFAULT '0' NOT NULL,
  PageViewID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_Folder (
  ID int(11) DEFAULT '0' NOT NULL,
  Name varchar(100),
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  SectionID int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FolderPermission (
  ID int(11) DEFAULT '0' NOT NULL,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT '0',
  WritePermission int(11) DEFAULT '0',
  UploadPermission int(11) DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZFileManager_FilePermission (
  ID int(11) DEFAULT '0' NOT NULL,
  ObjectID int(11) DEFAULT NULL,
  GroupID int(11) DEFAULT NULL,
  ReadPermission int(11) DEFAULT '0',
  WritePermission int(11) DEFAULT '0',
  PRIMARY KEY (ID)
);CREATE TABLE eZForm_Form (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Receiver varchar(255) default NULL,
  CC varchar(255) default NULL,
  Sender varchar(255) default NULL,
  SendAsUser varchar(1) default NULL,
  CompletedPage varchar(255) default NULL,
  InstructionPage varchar(255) default NULL,
  Counter int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForm_FormElement (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Required int(1) default '0',
  ElementTypeID int(11) default NULL,
  Size int(11) default '0',	
  Break int(11) default '0',		
  PRIMARY KEY (ID)
);

CREATE TABLE eZForm_FormElementDict (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  FormID int(11) default NULL,
  ElementID int(11) default NULL,
  Placement int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForm_FormElementType (
  ID int(11) NOT NULL,
  Name varchar(255) default NULL,
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForm_FormElementFixedValues (
  ID int(11) NOT NULL default '0',
  Value varchar(80) default NULL,
  PRIMARY KEY (ID)
);
 
CREATE TABLE eZForm_FormElementFixedValueLink (
  ID int(11) NOT NULL default '0',
  ElementID int(11) default '0',
  FixedValueID int(11) default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZForm_FormElementType VALUES (1,'text_field_item','HTML text field (input type="text")');
INSERT INTO eZForm_FormElementType VALUES (2,'text_area_item','HTML text area (textarea)');
INSERT INTO eZForm_FormElementType VALUES (3,'dropdown_item','HTML Select');
INSERT INTO eZForm_FormElementType VALUES (4,'multiple_select_item','HTML Multiple Select');
INSERT INTO eZForm_FormElementType VALUES (5,'checkbox_item','HTML CheckBox');
INSERT INTO eZForm_FormElementType VALUES (6,'radiobox_item','HTML RadioBox');CREATE TABLE eZForum_Category (
  ID int NOT NULL,
  Name varchar(255) DEFAULT NULL,
  Description varchar(255) DEFAULT NULL,
  IsPrivate int DEFAULT NULL,
  SectionID int DEFAULT 1,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_Forum (
  ID int NOT NULL,
  Name varchar(255) NOT NULL DEFAULT '',
  Description varchar(255) DEFAULT NULL,
  IsPrivate int DEFAULT NULL,
  ModeratorID int NOT NULL DEFAULT '0',
  IsModerated int NOT NULL DEFAULT '0',
  GroupID int DEFAULT '0',
  IsAnonymous int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_ForumCategoryLink (
  ID int NOT NULL,
  ForumID int NOT NULL DEFAULT '0',
  CategoryID int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_Message (
  ID int NOT NULL,
  ForumID int NOT NULL DEFAULT '0',
  Topic varchar(255) DEFAULT NULL,
  Body text,
  UserName varchar(60) DEFAULT NULL,
  UserID int DEFAULT NULL,
  Parent int DEFAULT NULL,
  EmailNotice int NOT NULL DEFAULT '0',
  PostingTime int NOT NULL,
  TreeID int NOT NULL,
  ThreadID int NOT NULL,
  Depth int NOT NULL,
  IsApproved int NOT NULL DEFAULT '1',
  IsTemporary int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_MessageWordLink (
  MessageID int(11) NOT NULL DEFAULT '0',
  Frequency float DEFAULT 0.2,
  WordID int(11) NOT NULL DEFAULT '0'
);

CREATE TABLE eZForum_Word (
  ID int(11) NOT NULL DEFAULT '0',
  Frequency float DEFAULT 0.2,
  Word varchar(50) NOT NULL DEFAULT ''
);

CREATE INDEX Forum_TreeID ON eZForum_Message (TreeID);
CREATE INDEX Forum_PostingTime ON eZForum_Message (PostingTime);
CREATE INDEX Forum_ThreadID ON eZForum_Message (ThreadID);
CREATE INDEX Forum_Depth ON eZForum_Message (Depth);
CREATE INDEX Forum_ForumID ON eZForum_Message (ForumID);

CREATE INDEX ForumMessage_IsTemporary ON eZForum_Message (IsTemporary);
CREATE INDEX ForumMessage_IsApproved ON eZForum_Message (IsApproved);
CREATE INDEX ForumMessage_PostingTime ON eZForum_Message (PostingTime);
CREATE INDEX ForumMessage_TreeID ON eZForum_Message (TreeID);

CREATE INDEX ForumWordLink_MessageID ON eZForum_MessageWordLink (MessageID);
CREATE INDEX ForumWordLink_WordID ON eZForum_MessageWordLink (WordID);
CREATE INDEX ForumWord_Word ON eZForum_Word (Word);
CREATE UNIQUE INDEX ForumWord_ID ON eZForum_Word (ID);


CREATE INDEX eZForumForumCategoryLink_ForumID ON eZForum_ForumCategoryLink (ForumID);CREATE TABLE eZImageCatalogue_Category (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int default NULL,
  UserID int default NULL,
  WritePermission int default '1',
  ReadPermission int default '1',
  SectionID int default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  UploadPermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_Image (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Caption text,
  Description text,
  PhotographerID int,
  Created int,
  FileName varchar(100) default NULL,
  OriginalFileName varchar(100) default NULL,
  ReadPermission int default '1',
  WritePermission int default '1',
  UserID int default NULL,
  Keywords varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImagePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  ImageID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageVariation (
  ID int NOT NULL,
  ImageID int NOT NULL,
  VariationGroupID int NOT NULL,
  ImagePath varchar(100) default NULL,
  Width int default NULL,
  Height int default NULL,
  Modification varchar(20) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  ID int NOT NULL,
  Width int default NULL,
  Height int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageMap (
  ID int NOT NULL,
  ImageID int default NULL,
  Link varchar(50) NOT NULL,
  AltText text,
  Shape int NOT NULL,
  StartPosX int NOT NULL,
  StartPosY int NOT NULL,
  EndPosX int NOT NULL,
  EndPosY int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageCategoryDefinition (
  ID int NOT NULL,
  ImageID int default NULL,
  CategoryID int default NULL,
  PRIMARY KEY (ID)
);


CREATE INDEX ImageCatalogue_ImageVariationGroup_VariationGroupID ON  eZImageCatalogue_ImageVariation  (VariationGroupID);
CREATE INDEX ImageCatalogue_ImageVariationGroup_ImageID  ON  eZImageCatalogue_ImageVariation  (ImageID);
CREATE INDEX ImageCatalogue_ImageVariationGroup_ModificationID ON  eZImageCatalogue_ImageVariation  (Modification);
CREATE INDEX eZImageCatalogue_Image_OriginalFileName ON eZImageCatalogue_Image(OriginalFileName);
CREATE INDEX eZImageCatalogue_ImagePermission_GroupID ON eZImageCatalogue_ImagePermission(GroupID);
CREATE INDEX eZImageCatalogue_ImagePermission_ObjectID ON eZImageCatalogue_ImagePermission(ObjectID);
CREATE INDEX eZImageCatalogue_ImagePermission_ReadPermission ON eZImageCatalogue_ImagePermission(ReadPermission);
CREATE INDEX eZImageCatalogue_CategoryPermission_GroupID ON eZImageCatalogue_CategoryPermission(GroupID);
CREATE INDEX eZImageCatalogue_CategoryPermission_ObjectID ON eZImageCatalogue_CategoryPermission(ObjectID);
CREATE INDEX eZImageCatalogue_CategoryPermission_ReadPermission ON eZImageCatalogue_CategoryPermission(ReadPermission);



CREATE TABLE eZLink_Hit (
  ID int NOT NULL,
  Link int default NULL,
  Time int NOT NULL,
  RemoteIP varchar(15) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Link (
  ID int NOT NULL ,
  Name varchar(100) default NULL,
  Description text,
  KeyWords text default NULL,
  Modified int NOT NULL,
  Accepted int,
  Created int default NULL,
  Url text default NULL,
  ImageID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_LinkCategoryDefinition (
  ID int NOT NULL ,
  LinkID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_LinkCategoryLink (
  ID int NOT NULL ,
  LinkID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Category (
  ID int NOT NULL,
  Parent int NOT NULL,
  Name varchar(100) default NULL,
  ImageID int NOT NULL,
  Description varchar(200),
  SectionID int default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Attribute (
  ID int NOT NULL,
  TypeID int default NULL,
  Name varchar(150) default NULL,
  Created int default NULL,
  Placement int default 0,
  Unit varchar(8) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_AttributeValue (
  ID int NOT NULL,
  LinkID int default NULL,
  AttributeID int default NULL,
  Value char(200) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_LinkGroup (
  ID int NOT NULL,
  Parent int default '0',
  Title varchar(100) default NULL,
  ImageID int default NULL,
  Description text,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_Type (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_TypeLink (
  ID int NOT NULL,
  LinkID int default NULL,
  TypeID int default NULL,
  PRIMARY KEY (ID)
);
CREATE TABLE eZMail_Mail (
  ID int(11) NOT NULL,
  UserID int(11) default '0', 
  ToField varchar(100),
  FromField varchar(100),
  FromName varchar(100),
  Cc varchar(255),
  Bcc varchar(255),
  MessageID varchar(200),
  Reference varchar(100),
  ReplyTo varchar(100),
  Subject varchar(255),
  BodyText text,
  Status int(1) default '0' NOT NULL,
  Size int(11) default '0',
  UDate int(15) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_Account (
  ID int(11) NOT NULL,
  UserID int(11) default '0',
  Name varchar(200) default NULL,
  LoginName varchar(100),
  Password varchar(50),
  Server varchar(150),
  ServerPort int(5) default '0',
  DeleteFromServer int(1) default '1',
  ServerType int(2), 
  IsActive int(1) default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_Folder (
  ID int(11) NOT NULL,
  UserID int(11) default '0',
  ParentID int(11) default '0',
  Name varchar(200) default NULL,
  FolderType int(2), 
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_MailFolderLink (
  MailID int(11) NOT NULL default '0',
  FolderID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,FolderID)
);

CREATE TABLE eZMail_MailAttachmentLink (
  MailID int(11) NOT NULL default '0',
  FileID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,FileID)
);

CREATE TABLE eZMail_MailImageLink (
  MailID int(11) NOT NULL default '0',
  ImageID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,ImageID)
);

CREATE TABLE eZMail_FilterRule (
  ID int(11) NOT NULL,
  UserID int(11) NOT NULL default '0',
  FolderID int(11) NOT NULL default '0',
  HeaderType int(2) default '0',
  CheckType int(2) default '0',
  MatchValue varchar(200),
  IsActive int(1) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMail_FetchedMail (
  UserID int(11) NOT NULL,
  MessageID varchar(100) NOT NULL,
  PRIMARY KEY (UserID, MessageID)
);

CREATE TABLE eZMail_MailContactLink (
  ID int NOT NULL,
  MailID int NOT NULL default '0',
  PersonID int,
  CompanyID int,
  PRIMARY KEY (ID)
);DROP TABLE IF EXISTS `eZMediaCatalogue_Attribute`;
CREATE TABLE eZMediaCatalogue_Attribute (
  ID int(11) NOT NULL,
  TypeID int(11) default NULL,
  Name varchar(150) default NULL,
  Created int(11) default NULL,
  Placement int(11) default '0',
  Unit varchar(8) default NULL,
  DefaultValue varchar(100) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZMediaCatalogue_Attribute VALUES (1,1,'width',996137421,0,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (2,1,'height',996137432,1,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (3,1,'type',996137440,2,'','video/quicktime');
INSERT INTO eZMediaCatalogue_Attribute VALUES (4,1,'controller',996137447,3,'','true');
INSERT INTO eZMediaCatalogue_Attribute VALUES (5,1,'autoplay',996137455,4,'','true');
INSERT INTO eZMediaCatalogue_Attribute VALUES (6,2,'width',996137483,5,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (7,2,'height',996137631,6,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (8,2,'controller',996137641,7,'','true');
INSERT INTO eZMediaCatalogue_Attribute VALUES (9,2,'loop',996137662,8,'','false');
INSERT INTO eZMediaCatalogue_Attribute VALUES (10,2,'autoplay',996137674,9,'','true');
INSERT INTO eZMediaCatalogue_Attribute VALUES (11,3,'quality',996137872,10,'','high');
INSERT INTO eZMediaCatalogue_Attribute VALUES (12,3,'pluginspage',996137887,11,'','http://www.macromedia.com/shockwave/download/index.cgi?P1_=Prod_Version=3DShockwaveFlash');
INSERT INTO eZMediaCatalogue_Attribute VALUES (13,3,'type',996137896,12,'','application/x-shockwave-flash');
INSERT INTO eZMediaCatalogue_Attribute VALUES (14,3,'width',996137906,13,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (15,3,'height',996137917,14,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (16,2,'type',996139826,15,'','application/x-mplayer2');
INSERT INTO eZMediaCatalogue_Attribute VALUES (17,4,'width',1004640070,16,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (18,4,'height',1004640076,17,'','');
INSERT INTO eZMediaCatalogue_Attribute VALUES (19,4,'controls',1004640087,18,'','ImageWindow');
INSERT INTO eZMediaCatalogue_Attribute VALUES (20,4,'autostart',1004640100,19,'','true');

DROP TABLE IF EXISTS `eZMediaCatalogue_Category`;
CREATE TABLE eZMediaCatalogue_Category (
  ID int(11) NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int(11) default NULL,
  UserID int(11) default NULL,
  WritePermission int(11) default '1',
  ReadPermission int(11) default '1',
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_Media`;
CREATE TABLE eZMediaCatalogue_Media (
  ID int(11) NOT NULL,		    
  Name varchar(100) default NULL,
  Caption text,
  Description text,
  FileName varchar(100) default NULL,
  OriginalFileName varchar(100) default NULL,
  ReadPermission int(11) default '1',
  WritePermission int(11) default '1',
  UserID int(11) default NULL,
  PhotographerID int(11) default NULL,
  Created int(11) default NULL,
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_CategoryPermission`;
CREATE TABLE eZMediaCatalogue_CategoryPermission (
  ID int(11) NOT NULL,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
);


DROP TABLE IF EXISTS `eZMediaCatalogue_MediaCategoryDefinition`;
CREATE TABLE eZMediaCatalogue_MediaCategoryDefinition (
  ID int(11) NOT NULL,
  MediaID int(11) default NULL,
  CategoryID int(11) default NULL,
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_MediaCategoryLink`;
CREATE TABLE eZMediaCatalogue_MediaCategoryLink (
  ID int(11) NOT NULL,		    
  CategoryID int(11) default NULL,
  MediaID int(11) default NULL,
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_MediaPermission`;
CREATE TABLE eZMediaCatalogue_MediaPermission (
  ID int(11) NOT NULL,		    
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_Type`;
CREATE TABLE eZMediaCatalogue_Type (
  ID int(11) NOT NULL,		  
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS `eZMediaCatalogue_TypeLink`;
CREATE TABLE eZMediaCatalogue_TypeLink (
  ID int(11) NOT NULL,		  
  TypeID int(11) default 0,
  MediaID int(11) default 0,
  PRIMARY KEY (ID)
);

INSERT INTO eZMediaCatalogue_Type VALUES (1,'QuickTime');
INSERT INTO eZMediaCatalogue_Type VALUES (2,'Windows Media Player');
INSERT INTO eZMediaCatalogue_Type VALUES (3,'ShockWave Flash');
INSERT INTO eZMediaCatalogue_Type VALUES (4,'Real Player');

DROP TABLE IF EXISTS `eZMediaCatalogue_AttributeValue`;
CREATE TABLE eZMediaCatalogue_AttributeValue (
  ID int(11) NOT NULL auto_increment,
  MediaID int(11) default NULL,
  AttributeID int(11) default NULL,
  Value varchar(200) default NULL,
  PRIMARY KEY (ID)
);CREATE TABLE eZMessage_Message (
  ID int(11) NOT NULL auto_increment,
  FromUserID int(11) NOT NULL default '0',
  ToUserID int(11) NOT NULL default '0',
  Created int(11) NOT NULL,
  IsRead int(11) NOT NULL default '0',
  Subject varchar(255) NOT NULL default '',
  Description text,
  PRIMARY KEY (ID)
);CREATE TABLE eZModule_LinkModuleType (
  ID int NOT NULL,
  Module varchar(40) NOT NULL default '',
  Type varchar(40) NOT NULL default '',
  PRIMARY KEY (ID,Module,Type)
);
CREATE TABLE eZNewsFeed_Category (
  ID int(11) NOT NULL auto_increment,
  Name varchar(150) DEFAULT '' NOT NULL,
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZNewsFeed_Category VALUES (1,'News from freshmeat','',0);

CREATE TABLE eZNewsFeed_News (
  ID int(11) NOT NULL auto_increment,
  IsPublished int NOT NULL DEFAULT '0',
  PublishingDate int NOT NULL,
  OriginalPublishingDate int NOT NULL,
  Name varchar(150) DEFAULT '' NOT NULL,
  Intro text,
  KeyWords varchar(200),
  URL varchar(200),
  Origin varchar(150),
  PRIMARY KEY (ID)
);

CREATE TABLE eZNewsFeed_NewsCategoryLink (
  ID int(11) NOT NULL auto_increment,
  NewsID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZNewsFeed_SourceSite (
  ID int(11) NOT NULL auto_increment,
  URL varchar(250),
  Login varchar(30),
  Password varchar(30),
  CategoryID int(11) DEFAULT '0' NOT NULL,
  Name varchar(100),
  Decoder varchar(50),
  IsActive int DEFAULT '0',
  AutoPublish int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZNewsFeed_SourceSite VALUES (1,'http://freshmeat.net/backend/fm.rdf','','',1,'Freshmeat','rdf',1,1);

CREATE TABLE eZPoll_MainPoll (
  ID int NOT NULL,
  PollID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_Poll (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  Anonymous int NOT NULL default '0',
  IsEnabled int NOT NULL default '0',
  IsClosed int NOT NULL default '0',
  ShowResult int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_PollChoice (
  ID int NOT NULL,
  PollID int default NULL,
  Name varchar(100) default NULL,
  Offs int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_Vote (
  ID int NOT NULL,
  PollID int default NULL,
  ChoiceID int default NULL,
  VotingIP varchar(20) default NULL,
  UserID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZPoll_PollForumLink (
  ID int NOT NULL,
  PollID int NOT NULL default '0',
  ForumID int NOT NULL default '0',
  PRIMARY KEY (ID)
);
CREATE TABLE eZQuiz_Alternative (
  ID int(11) NOT NULL,
  QuestionID int(11) default '0',
  Name char(100) default NULL,
  IsCorrect int(11) default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZQuiz_Alternative VALUES (1,1,'',0);
INSERT INTO eZQuiz_Alternative VALUES (2,2,'test 1',1);
INSERT INTO eZQuiz_Alternative VALUES (3,2,'test 2',0);

CREATE TABLE eZQuiz_Answer (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default '0',
  AlternativeID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZQuiz_Game (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  Description text,
  StartDate int(11) default NULL,
  StopDate int(11) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZQuiz_Game VALUES (1,'test','wegwegweg',UNIX_TIMESTAMP('2001-12-12'),null);

CREATE TABLE eZQuiz_Question (
  ID int(11) NOT NULL auto_increment,
  Name char(100) default NULL,
  GameID int(11) default '0',
  Placement int(11) default '0',
  Score int(11) default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZQuiz_Question VALUES (1,'hei for you',1,0,0);
INSERT INTO eZQuiz_Question VALUES (2,'',1,1,0);

CREATE TABLE eZQuiz_Score (
  ID int(11) NOT NULL auto_increment,
  GameID int(11) default '0',
  UserID int(11) default '0',
  TotalScore int(11) default '0',
  LastQuestion int(11) default '0',
  FinishedGame int(1) default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZQuiz_AllTimeScore (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default '0',
  TotalScore int(11) default '0',
  GamesPlayed int(11) default '0',
  PRIMARY KEY (ID)
);CREATE TABLE eZSession_Preferences (
  ID int(11) NOT NULL,
  UserID int(11) DEFAULT '0' NOT NULL,
  Name char(50),
  Value char(255),
  GroupName char(50) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSession_Session (
  ID int(11) NOT NULL,
  Hash char(33) NOT NULL,
  Created int not null,
  LastAccessed int not null,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSession_SessionVariable (
  ID int(11) NOT NULL,
  SessionID int(11) not NULL,
  Name varchar(25) not NULL,
  Value Text not NULL,
  GroupName varchar(50) NOT NULL,
  PRIMARY KEY (ID)
);


CREATE INDEX Session_Hash  ON eZSession_Session (Hash);
CREATE INDEX Session_Created  ON eZSession_Session (Created);
CREATE INDEX Session_LastAccessed  ON eZSession_Session (LastAccessed);

CREATE INDEX Session_VariableName  ON eZSession_SessionVariable (Name);
CREATE INDEX Session_VariableGroupName  ON eZSession_SessionVariable (GroupName);
CREATE INDEX Session_VariableSessionID  ON eZSession_SessionVariable (SessionID);

CREATE TABLE eZSiteManager_Section (
  ID int(11) NOT NULL ,
  Name varchar(200) default NULL,
  Description text,
  SiteDesign varchar(30) default NULL,
  Created int(11) default NULL,
  TemplateStyle varchar(255) default NULL,
  Language varchar(5) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSiteManager_SectionFrontPageRow (
  ID int(11) NOT NULL default '0',
  SettingID int(11) default '0',
  CategoryID int(11) default '0',
  Placement int(11) default '0',
  PRIMARY KEY (ID)
);
 
CREATE TABLE eZSiteManager_SectionFrontPageRowLink (
  ID int(11) NOT NULL default '0',
  FrontPageID int(11) default '0',
  SectionID int(11) default '0',
  PRIMARY KEY (ID)
);
 
CREATE TABLE eZSiteManager_SectionFrontPageSetting (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
);
 
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (1,'1column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (2,'2column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (3,'1short');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (4,'1columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (5,'2columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (6,'ad');

INSERT INTO eZSiteManager_Section ( ID,  Name, Created, Description, SiteDesign, TemplateStyle, Language) VALUES ( 1, 'Standard Section', 1, NULL, 'standard', NULL, NULL);


CREATE TABLE eZSiteManager_Menu (
  ID int(11) NOT NULL default '0',
  Name varchar(40) default NULL,
  Link varchar(40) default NULL,
  Type int(11) default '1',
  ParentID int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZSiteManager_MenuType (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY  (ID)
);CREATE TABLE eZStats_BrowserType (
  ID int NOT NULL,
  BrowserType varchar(250) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_PageView (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  BrowserTypeID int NOT NULL default '0',
  RemoteHostID int NOT NULL default '0',
  RefererURLID int NOT NULL default '0',
  Date int NOT NULL,
  RequestPageID int NOT NULL default '0',
  DateValue int NOT NULL,
  TimeValue int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RefererURL (
  ID int NOT NULL,
  Domain varchar(100) default NULL,
  URI varchar(200) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RemoteHost (
  ID int NOT NULL,
  IP varchar(15) default NULL,
  HostName varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RequestPage (
  ID int NOT NULL,
  URI varchar(250) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_RequestedPage (
  ID int NOT NULL,
  Month int,
  URI varchar(250) default NULL,
  Count int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_PageView (
  ID int NOT NULL,
  Hour int NOT NULL,
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_UniqueVisits (
  ID int NOT NULL,
  Day int NOT NULL,
  Count int NOT NULL DEFAULT '0',
  Language varchar(5) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_BrowserType (
  ID int NOT NULL,
  Browser varchar(250) default NULL,
  Count int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_RefererURL (
  ID int NOT NULL,
  Month int NOT NULL,
  Domain varchar(100) default NULL,
  URI varchar(200) default NULL,
  Count int NOT NULL DEFAULT '0',
  Language varchar(5) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_Users (
  ID int NOT NULL,
  UserID int NOT NULL,
  Month int NOT NULL,
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_Archive_RemoteHost (
  ID int NOT NULL,
  IP varchar(15) default NULL,
  HostName varchar(150) default NULL,
  Count int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE INDEX PageView_TimeValue ON eZStats_PageView (TimeValue);

CREATE INDEX eZStats_Archive_RequestedPageMonth ON eZStats_Archive_RequestedPage (Month);
CREATE INDEX eZStats_Archive_RequestedPageURI ON eZStats_Archive_RequestedPage (URI);
CREATE INDEX eZStats_Archive_RefererURLMonth ON eZStats_Archive_RefererURL (Month);
CREATE INDEX eZStats_Archive_RemoteHostIP ON eZStats_Archive_RemoteHost (IP);
CREATE INDEX eZStats_Archive_PageViewHour ON eZStats_Archive_PageView (Hour);

CREATE INDEX eZStats_PageViewDate ON eZStats_PageView(Date);
CREATE INDEX eZStats_Archive_RefererURLURI ON eZStats_Archive_RefererURL(URI);
CREATE INDEX eZStats_Archive_RefererURLDomain ON eZStats_Archive_RefererURL(Domain);
CREATE INDEX eZStats_Archive_RemoteHostHostName ON eZStats_Archive_RemoteHost(HostName);
CREATE INDEX eZStats_Archive_UsersUserID ON eZStats_Archive_Users(UserID);
CREATE INDEX eZStats_Archive_UsersMonth ON eZStats_Archive_Users(Month);
CREATE INDEX eZStats_Archive_BrowserTypeBrowser ON eZStats_Archive_BrowserType(Browser);
CREATE TABLE eZTodo_Category (
  ID int(11) NOT NULL,
  Description text,
  Name varchar(30),
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Priority (
  ID int(11) NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Todo (
  ID int(11) NOT NULL,
  Category int(11),
  Priority int(11),
  Permission int(11) default 0,
  UserID int(11),
  OwnerID int(11),
  Name varchar(30),
  Date int(11),
  Due int(11),
  Description text,
  Status int(11) DEFAULT '0',
  IsPublic int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Status (
  ID int(11) NOT NULL,
  Name varchar(30),
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Log (
  ID int(11) NOT NULL,
  Log text,
  Created int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_TodoLogLink (
  ID int(11) NOT NULL auto_increment,
  TodoID int(11) default NULL,
  LogID int(11) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZTodo_Category VALUES (1,'','Work');    

INSERT INTO eZTodo_Status (Description, ID, Name) VALUES (NULL,1,'Not done');
INSERT INTO eZTodo_Status (Description, ID, Name) VALUES (NULL,2,'Done');

INSERT INTO eZTodo_Priority (ID, Name) VALUES (1,'Low');
INSERT INTO eZTodo_Priority (ID, Name) VALUES (2,'Medium');
INSERT INTO eZTodo_Priority (ID, Name) VALUES (3,'High');CREATE TABLE eZTrade_AlternativeCurrency (
  ID int NOT NULL,
  Name varchar(100) NOT NULL default '',
  PrefixSign int(11) NOT NULL default '0',
  Sign varchar(5) NOT NULL default '',
  Value float NOT NULL default '1',
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Attribute (
  ID int NOT NULL,
  TypeID int(11) default NULL,
  Name varchar(150) default NULL,
  Created int(11) NOT NULL,
  Placement int(11) default '0',
  AttributeType int(11) default '1',
  Unit varchar(8) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_AttributeValue (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  AttributeID int(11) default NULL,
  Value varchar(200) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Cart (
  ID int NOT NULL,
  SessionID int(11) default NULL,
  CompanyID int(11) default '0',
  PersonID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_CartItem (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  Count int(11) default NULL,
  CartID int(11) default NULL,
  WishListItemID int(11) NOT NULL default '0',
  VoucherInformationID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_CartOptionValue (
  ID int NOT NULL,
  CartItemID int(11) default NULL,
  OptionID int(11) default NULL,
  OptionValueID int(11) default NULL,
  RemoteID varchar(100) default NULL,
  Count int(11) default NULL,	
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Category (
  ID int NOT NULL,
  Parent int(11) default NULL,
  Description text,
  Name varchar(100) default NULL,
  ImageID int(11) default NULL,
  SortMode int(11) NOT NULL default '1',
  RemoteID varchar(100) default NULL,
  SectionID int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_CategoryOptionLink (
  ID int NOT NULL,
  CategoryID int(11) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_CategoryPermission (
  ID int NOT NULL,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductFormDict (
  ID int NOT NULL,
  ProductID int default NULL,
  FormID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_GroupPriceLink (
  GroupID int(11) NOT NULL default '0',
  PriceID int(11) NOT NULL default '0',
  PRIMARY KEY (GroupID,PriceID)
);

CREATE TABLE eZTrade_Link (
  ID int NOT NULL,
  SectionID int(11) NOT NULL default '0',
  Name varchar(60) default NULL,
  URL text,
  Placement int(11) NOT NULL default '0',
  ModuleType int(11) NOT NULL default '0',
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
  OptionID int(11) default NULL,
  Placement int(11) NOT NULL default '1',
  Price float(10,2) default NULL,
  RemoteID varchar(100) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OptionValueContent (
  ID int NOT NULL,
  Value varchar(30) default NULL,
  ValueID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OptionValueHeader (
  ID int NOT NULL,
  Name varchar(30) default NULL,
  OptionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Order (
  ID int NOT NULL,
  UserID int(11) NOT NULL default '0',
  ShippingCharge float(10,2) default NULL,
  PaymentMethod text,
  ShippingAddressID int(11) default NULL,
  BillingAddressID int(11) default NULL,
  IsExported int(11) NOT NULL default '0',
  Date int(11) default NULL,
  ShippingVAT float NOT NULL default '0',
  ShippingTypeID int(11) NOT NULL default '0',
  IsVATInc int(11) default '0',
  CompanyID int(11) default '0',
  PersonID int(11) default '0',
  Comment text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OrderItem (
  ID int NOT NULL,
  OrderID int(11) NOT NULL default '0',
  Count int(11) default NULL,
  Price float(10,2) default NULL,
  ProductID int(11) default NULL,
  VAT float(10,2) default NULL,
  ExpiryDate int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OrderOptionValue (
  ID int NOT NULL,
  OrderItemID int(11) default NULL,
  OptionName text default NULL,
  ValueName text default NULL,
  RemoteID varchar(100) default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OrderStatus (
  ID int NOT NULL,
  StatusID int(11) NOT NULL default '0',
  Altered int(11) NOT NULL,
  AdminID int(11) default NULL,
  OrderID int(11) NOT NULL default '0',
  Comment text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_OrderStatusType (
  ID int NOT NULL,
  Name varchar(25) NOT NULL default '',
  PRIMARY KEY (ID),
  UNIQUE KEY Name(Name)
);

INSERT INTO eZTrade_OrderStatusType VALUES (1,'intl-initial');
INSERT INTO eZTrade_OrderStatusType VALUES (2,'intl-sendt');
INSERT INTO eZTrade_OrderStatusType VALUES (3,'intl-payed');
INSERT INTO eZTrade_OrderStatusType VALUES (4,'intl-undefined');

CREATE TABLE eZTrade_PreOrder (
  ID int NOT NULL,
  Created int(11) NOT NULL,
  OrderID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_PriceGroup (
  ID int NOT NULL,
  Name varchar(50) default NULL,
  Description text,
  Placement int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPriceRange (
  ID int(11) NOT NULL,
  Min int(11) default '0',
  Max int(11) default '0',
  ProductID int(11) default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Product (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Contents text,
  Brief text,
  Description text,
  Keywords varchar(100) default NULL,
  Price float(10,5) default NULL,
  ShowPrice int(11) default '1',
  ShowProduct int(11) default '1',
  Discontinued int(11) default '0',
  ProductNumber varchar(100) default NULL,
  ExternalLink varchar(200) default NULL,
  IsHotDeal int(11) default '0',
  RemoteID varchar(100) default NULL,
  VATTypeID int(11) NOT NULL default '0',
  ShippingGroupID int(11) NOT NULL default '0',
  ProductType int(11) default '1',
  ExpiryTime int(11) NOT NULL default '0',
  Published int(11) default NULL,
  IncludesVAT int(1) default '1',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductCategoryDefinition (
  ID int NOT NULL,
  ProductID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductCategoryLink (
  ID int NOT NULL,
  CategoryID int(11) default NULL,
  ProductID int(11) default NULL,
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int(11) NOT NULL default '0',
  ThumbnailImageID int(11) default NULL,
  MainImageID int(11) default NULL,
  PRIMARY KEY (ProductID)
);

CREATE TABLE eZTrade_ProductImageLink (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  Placement int(11) default '0',
  ImageID int(11) default NULL,
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductOptionLink (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPermission (
  ID int NOT NULL,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID),
  KEY ProductPermissionObjectID(ObjectID),
  KEY ProductPermissionGroupID(GroupID),
  KEY ProductPermissionWritePermission(WritePermission),
  KEY ProductPermissionReadPermission(ReadPermission)
);

CREATE TABLE eZTrade_ProductPermissionLink (
  ID int(11) NOT NULL default '0',
  ProductID int(11) NOT NULL default '0',
  GroupID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ProductPriceLink (
  ProductID int(11) NOT NULL default '0',
  PriceID int(11) NOT NULL default '0',
  OptionID int(11) NOT NULL default '0',
  ValueID int(11) NOT NULL default '0',
  Price float(10,2) default NULL,
  PRIMARY KEY (ProductID,PriceID,OptionID,ValueID)
);

CREATE TABLE eZTrade_ProductQuantityDict (
  ProductID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,QuantityID)
);

CREATE TABLE eZTrade_ProductSectionDict (
  ID int(11) NOT NULL default '0',
  ProductID int(11) NOT NULL default '0',
  SectionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,SectionID)
);

CREATE TABLE eZTrade_ProductTypeLink (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  TypeID int(11) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_Quantity (
  ID int NOT NULL,
  Quantity int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_QuantityRange (
  ID int NOT NULL,
  MaxRange int(11) default NULL,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ShippingGroup (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ShippingType (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Created int(11) NOT NULL,
  IsDefault int(11) NOT NULL default '0',
  VATTypeID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ShippingValue (
  ID int NOT NULL,
  ShippingGroupID int(11) NOT NULL default '0',
  ShippingTypeID int(11) NOT NULL default '0',
  StartValue float NOT NULL default '0',
  AddValue float NOT NULL default '0',
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
  VATValue float NOT NULL default '0',
  Created int(11) NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_ValueQuantityDict (
  ValueID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ValueID,QuantityID)
);

CREATE TABLE eZTrade_Voucher (
  ID int(11) NOT NULL default '0',
  Created int(11) default '0',
  Price float default '0',
  Available int(11) default '0',
  KeyNumber varchar(50) default NULL,
  MailMethod int(11) default '1',
  UserID int(11) default '0',
  ProductID int(11) default '0',
  VoucherID int(11) default '0',	
  TotalValue int(11) default '0',		
  PRIMARY KEY  (ID)
);

CREATE TABLE eZTrade_VoucherInformation (
  ID int(11) NOT NULL default '0',
  VoucherID int(11) default '0',
  OnlineID int(11) default '0',
  ToAddressID int(11) default '0',
  Description text,
  PreOrderID int(11) default '0',
  Price int(11) default '0',
  MailMethod int(11) default '1',
  ToName varchar(80) default NULL,
  FromName varchar(80) default NULL,
  FromOnlineID int(11) default '0',
  FromAddressID int(11) default '0',
  ProductID int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZTrade_VoucherUsed (
  ID int(11) NOT NULL default '0',
  Used int(11) default '0',
  Price float default NULL,
  VoucherID int(11) default '0',
  OrderID int(11) default '0',
  UserID int(11) default '0',
  PRIMARY KEY  (ID)
);

CREATE TABLE eZTrade_WishList (
  ID int NOT NULL,
  UserID int(11) default NULL,
  IsPublic int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_WishListItem (
  ID int NOT NULL,
  ProductID int(11) default NULL,
  Count int(11) default NULL,
  WishListID int(11) default NULL,
  IsBought int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTrade_WishListOptionValue (
  ID int NOT NULL,
  WishListItemID int(11) default NULL,
  OptionID int(11) default NULL,
  OptionValueID int(11) default NULL,
  PRIMARY KEY (ID)
);


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

CREATE INDEX TradeCartOptionValue_CartItemID ON eZTrade_CartOptionValue (CartItemID);CREATE TABLE eZURLTranslator_URL (
  ID int NOT NULL,
  Source varchar(200) default NULL,
  Dest varchar(200) default NULL,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);
CREATE TABLE eZUser_User (
  ID int NOT NULL,
  PersonID int NULL, 
  Login varchar(50) NOT NULL default '',
  Password varchar(50) NOT NULL default '',
  Email varchar(50) default NULL,
  FirstName varchar(50) default NULL,
  LastName varchar(50) default NULL,
  InfoSubscription int default '0',
  Signature text NOT NULL,
  SimultaneousLogins int NOT NULL default '0',
  CookieLogin int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_UserGroupLink (
  ID int NOT NULL,
  UserID int default NULL,
  GroupID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_UserAddressLink (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  AddressID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Author (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  EMail varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Cookie (
  ID int NOT NULL,
  UserID int default '0',
  Hash varchar(33) default NULL,
  Time int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Forgot (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  Hash varchar(33) default NULL,
  Time int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Group (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  SessionTimeout int default '60',
  IsRoot int default '0',
  GroupURL varchar(200) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_GroupPermissionLink (
  ID int NOT NULL,
  GroupID int default NULL,
  PermissionID int default NULL,
  IsEnabled int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Module (
  ID int NOT NULL,
  Name varchar(100) NOT NULL default '',
  PRIMARY KEY (ID),
  UNIQUE KEY Name (Name)
);

CREATE TABLE eZUser_Permission (
  ID int NOT NULL,
  ModuleID int default NULL,
  Name varchar(100) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Trustees (
  ID int(11) NOT NULL,
  OwnerID int(11) NOT NULL,
  UserID int(11) NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_UserShippingLink (
  ID int(11) NOT NULL default '0',
  UserID int(11) default '0',
  AddressID int(11) default '0',
  PRIMARY KEY  (ID)
);

INSERT INTO eZUser_Module (ID, Name) VALUES (1,'eZTrade');
INSERT INTO eZUser_Module (ID, Name) VALUES (2,'eZPoll');
INSERT INTO eZUser_Module (ID, Name) VALUES (3,'eZUser');
INSERT INTO eZUser_Module (ID, Name) VALUES (4,'eZTodo');
INSERT INTO eZUser_Module (ID, Name) VALUES (5,'eZNewsfeed');
INSERT INTO eZUser_Module (ID, Name) VALUES (6,'eZContact');
INSERT INTO eZUser_Module (ID, Name) VALUES (7,'eZForum');
INSERT INTO eZUser_Module (ID, Name) VALUES (8,'eZLink');
INSERT INTO eZUser_Module (ID, Name) VALUES (9,'eZFileManager');
INSERT INTO eZUser_Module (ID, Name) VALUES (10,'eZImageCatalogue');
INSERT INTO eZUser_Module (ID, Name) VALUES (11,'eZBug');
INSERT INTO eZUser_Module (ID, Name) VALUES (12,'eZArticle');
INSERT INTO eZUser_Module (ID, Name) VALUES (13,'eZBulkMail');
INSERT INTO eZUser_Module (ID, Name) VALUES (14,'eZStats');
INSERT INTO eZUser_Module (ID, Name) VALUES (15,'eZSysInfo');
INSERT INTO eZUser_Module (ID, Name) VALUES (16,'eZSiteManager');
INSERT INTO eZUser_Module (ID, Name) VALUES (17, 'eZMediaCatalogue');
INSERT INTO eZUser_Module (ID, Name) VALUES (18, 'eZAd');
INSERT INTO eZUser_Module (ID, Name) VALUES (19, 'eZForm');
INSERT INTO eZUser_Module (ID, Name) VALUES (20, 'eZURLTranslator');
INSERT INTO eZUser_Module (ID, Name) VALUES (21, 'eZMessage');
INSERT INTO eZUser_Module (ID, Name) VALUES (22, 'eZAddress');
INSERT INTO eZUser_Module (ID, Name) VALUES (23, 'eZCalendar');


INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (1,3,'UserAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (2,3,'UserDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (3,3,'UserModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (4,3,'GroupDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (5,3,'GroupAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (6,3,'GroupModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (11,8,'LinkGroupModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (8,3,'AdminLogin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (10,8,'LinkGroupAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (9,8,'LinkGroupDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (12,8,'LinkModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (13,8,'LinkAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (14,8,'LinkDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (15,7,'CategoryAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (16,7,'CategoryModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (17,7,'CategoryDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (18,7,'ForumDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (19,7,'ForumAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (20,7,'ForumModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (21,7,'MessageModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (22,7,'MessageAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (23,7,'MessageDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (24,6,'PersonAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (25,6,'CompanyAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (26,6,'CategoryAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (27,6,'PersonDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (28,6,'CompanyDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (29,6,'CategoryDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (30,6,'PersonModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (31,6,'CompanyModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (32,6,'CategoryModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (33,6,'PersonView');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (34,6,'PersonList');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (35,3,'UserLogin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (36,9,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (39,10,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (41,6,'CompanyView');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (42,6,'CompanyList');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (43,6,'TypeAdmin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (44,6,'Consultation');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (45,4,'ViewOtherUsers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (46,4,'AddOthers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (47,4,'EditOthers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (48,6,'CompanyStats');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (49,1,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (50,2,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (51,3,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (52,4,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (53,5,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (54,6,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (55,7,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (56,8,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (57,9,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (58,10,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (59,11,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (60,12,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (61,13,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (62,14,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (63,15,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (64,12,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (65,16,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (66, 17, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (69, 21, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (68, 21, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (70, 9, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (71, 13, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (72, 16, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (73, 1, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (74, 14, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (75, 18, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (76, 18, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (77, 19, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (78, 19, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (79, 20, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (80, 20, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (81, 2, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (82, 8, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (85, 11, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (86, 4, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (87, 5, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (88, 7, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (89, 6, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (90, 22, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (91, 22, 'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (92, 23, 'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (93, 23, 'WriteToRoot');

CREATE TABLE eZUser_UserGroupDefinition (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  GroupID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZUser_User (ID, Login, Password, Email, FirstName, LastName, InfoSubscription, Signature, SimultaneousLogins, CookieLogin)  
VALUES (1,'admin','0c947f956f7aa781','postmaster@yourdomain','admin','user','0','',0,0);

INSERT INTO eZUser_Group (ID, Name, Description, SessionTimeout, IsRoot) VALUES (1,'Administrators','All rights',7200,1);
INSERT INTO eZUser_Group (ID, Name, Description, SessionTimeout, IsRoot) VALUES (2,'Anonymous','Anonymous users',7200,0);
INSERT INTO eZUser_UserGroupLink (ID, UserID, GroupID) VALUES (1,1,1);

CREATE TABLE eZUser_Photographer (
  ID int(11) NOT NULL,
  Name char(255) default NULL,
  EMail char(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE INDEX UserGroupLink_UserID ON eZUser_UserGroupLink (UserID);
CREATE INDEX UserGroupLink_GroupID ON eZUser_UserGroupLink (GroupID);

CREATE UNIQUE INDEX User_Login ON eZUser_User (Login);  