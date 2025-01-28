CREATE TABLE eZAddress_Address (
  ID int(11) NOT NULL,
  Street1 varchar(50),
  Street2 varchar(50),
  AddressTypeID int(11),
  Place varchar(50),
  Zip varchar(10),
  RegionID int(11),
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

#
# Table structure for table 'eZAddress_Region'
#

CREATE TABLE eZAddress_Region (
  ID int(11) NOT NULL auto_increment,
  CountryID int(11),
  Abbreviation char(10),
  Name char(100),
  UserAdded int(1) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_Region'
#

INSERT INTO eZAddress_Region VALUES (1,240,'AL','Alabama',0,0);
INSERT INTO eZAddress_Region VALUES (2,240,'AK','Alaska',0,0);
INSERT INTO eZAddress_Region VALUES (3,240,'AZ','Arizona',0,0);
INSERT INTO eZAddress_Region VALUES (4,240,'AR','Arkansas',0,0);
INSERT INTO eZAddress_Region VALUES (5,240,'CA','California',0,0);
INSERT INTO eZAddress_Region VALUES (6,240,'CO','Colorado',0,0);
INSERT INTO eZAddress_Region VALUES (7,240,'CT','Connecticut',0,0);
INSERT INTO eZAddress_Region VALUES (8,240,'DE','Delaware',0,0);
INSERT INTO eZAddress_Region VALUES (9,240,'FL','Florida',0,0);
INSERT INTO eZAddress_Region VALUES (10,240,'GA','Georgia',0,0);
INSERT INTO eZAddress_Region VALUES (11,240,'HI','Hawaii',0,0);
INSERT INTO eZAddress_Region VALUES (12,240,'ID','Idaho',0,0);
INSERT INTO eZAddress_Region VALUES (13,240,'IL','Illinois',0,0);
INSERT INTO eZAddress_Region VALUES (14,240,'IN','Indiana',0,0);
INSERT INTO eZAddress_Region VALUES (15,240,'IA','Iowa',0,0);
INSERT INTO eZAddress_Region VALUES (16,240,'KS','Kansas',0,0);
INSERT INTO eZAddress_Region VALUES (17,240,'KY','Kentucky',0,0);
INSERT INTO eZAddress_Region VALUES (18,240,'LA','Louisiana',0,0);
INSERT INTO eZAddress_Region VALUES (19,240,'ME','Maine',0,0);
INSERT INTO eZAddress_Region VALUES (20,240,'MD','Maryland',0,0);
INSERT INTO eZAddress_Region VALUES (21,240,'MA','Massachusetts',0,0);
INSERT INTO eZAddress_Region VALUES (22,240,'MI','Michigan',0,0);
INSERT INTO eZAddress_Region VALUES (23,240,'MN','Minnesota',0,0);
INSERT INTO eZAddress_Region VALUES (24,240,'MS','Mississippi',0,0);
INSERT INTO eZAddress_Region VALUES (25,240,'MO','Missouri',0,0);
INSERT INTO eZAddress_Region VALUES (26,240,'MT','Montana',0,0);
INSERT INTO eZAddress_Region VALUES (27,240,'NE','Nebraska',0,0);
INSERT INTO eZAddress_Region VALUES (28,240,'NV','Nevada',0,0);
INSERT INTO eZAddress_Region VALUES (29,240,'NH','New Hampshire',0,0);
INSERT INTO eZAddress_Region VALUES (30,240,'NJ','New Jersey',0,0);
INSERT INTO eZAddress_Region VALUES (31,240,'NY','New Mexico',0,0);
INSERT INTO eZAddress_Region VALUES (32,240,'NY','New York',0,0);
INSERT INTO eZAddress_Region VALUES (33,240,'NC','North Carolina',0,0);
INSERT INTO eZAddress_Region VALUES (34,240,'ND','North Dakota',0,0);
INSERT INTO eZAddress_Region VALUES (35,240,'OH','Ohio',0,0);
INSERT INTO eZAddress_Region VALUES (36,240,'OK','Oklahoma',0,0);
INSERT INTO eZAddress_Region VALUES (37,240,'OR','Oregon',0,0);
INSERT INTO eZAddress_Region VALUES (38,240,'PA','Pennsylvania',0,0);
INSERT INTO eZAddress_Region VALUES (39,240,'RI','Rhode Island',0,0);
INSERT INTO eZAddress_Region VALUES (40,240,'SC','South Carolina',0,0);
INSERT INTO eZAddress_Region VALUES (41,240,'SD','South Dakota',0,0);
INSERT INTO eZAddress_Region VALUES (42,240,'TN','Tennessee',0,0);
INSERT INTO eZAddress_Region VALUES (43,240,'TX','Texas',0,0);
INSERT INTO eZAddress_Region VALUES (44,240,'UT','Utah',0,0);
INSERT INTO eZAddress_Region VALUES (45,240,'VT','Vermont',0,0);
INSERT INTO eZAddress_Region VALUES (46,240,'VA','Virginia',0,0);
INSERT INTO eZAddress_Region VALUES (47,240,'WA','Washington',0,0);
INSERT INTO eZAddress_Region VALUES (48,240,'WV','West Virginia',0,0);
INSERT INTO eZAddress_Region VALUES (49,240,'WI','Wisconsin',0,0);
INSERT INTO eZAddress_Region VALUES (50,240,'WY','Wyoming',0,0);

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