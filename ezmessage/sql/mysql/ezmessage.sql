CREATE TABLE eZMessage_Message (
  ID int(11) NOT NULL auto_increment,
  FromUserID int(11) NOT NULL default '0',
  ToUserID int(11) NOT NULL default '0',
  Created int(11) NOT NULL,
  IsRead int(11) NOT NULL default '0',
  Subject varchar(255) NOT NULL default '',
  Description text,
  PRIMARY KEY (ID)
);