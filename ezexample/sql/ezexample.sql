#
# Table structure for table 'eZExample_Test'
#
DROP TABLE IF EXISTS eZExample_Test;
CREATE TABLE eZExample_Test (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Text varchar(255),
  Created int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZExample_Test'
#

INSERT INTO eZExample_Test VALUES (1,'Example Test','');
