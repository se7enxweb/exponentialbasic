
CREATE TABLE eZSiteManager_Section (
  ID int NOT NULL,
  Name varchar(200) default NULL,
  Created int NOT NULL,
  Description varchar(255),
  SiteDesign varchar(30) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZSiteManager_Section   ( ID,  Name, Created, Description,  SiteDesign ) VALUES ( 1, 'Standard Section', 1, NULL, 'standard' );
