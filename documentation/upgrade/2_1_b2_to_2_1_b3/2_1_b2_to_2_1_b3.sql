#
# Admin privelidges!! READ THIS!!
#
# When uppgrading to version 2.1 beta 3 initially nobody will be able to access the admin site.
# This is because access to admin modules are now restricted and the default value is set to no
# access. To fix this, give the admin group root privelidges (they will then be able to see all modules)
# and give the admin groups access to the correct modules by giving them "ModuleEdit" on that module.
# The procedure is as follows..
# First run this file...
# Then find the ID of the administrators group...
# select * from eZUser_Group; 
# It is most probably ID 1.
# Now run:
# update eZUser_Group set IsRoot='1' WHERE ID='xxx'; #where xxx is the id you just found.
# You can now log in with your admin user and set the correct permissions...
alter table eZUser_Group add IsRoot int(1) default '0';


# 
# Sections
#
alter table eZArticle_Category add SectionID int not null; 

CREATE TABLE eZSiteManager_Section (
  ID int(11) NOT NULL auto_increment,
  Name varchar(200) default NULL,
  Created timestamp(14) NOT NULL,
  Description text,
  SiteDesign varchar(30) default NULL,
  PRIMARY KEY (ID)
)

ALTER TABLE eZTrade_Link ADD ModuleType int(11) NOT NULL;
CREATE TABLE eZModule_LinkModuleType
       (ID int(11) NOT NULL AUTO_INCREMENT,
        Module varchar(40) NOT NULL,
        Type varchar(40) NOT NULL,
        PRIMARY KEY(ID,Module,Type));

insert into eZUser_Permission SET ModuleID='1', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='2', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='3', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='4', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='5', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='6', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='7', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='8', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='9', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='10', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='11', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='12', Name='ModuleEdit';
insert into eZUser_Permission SET ModuleID='13', Name='ModuleEdit';
insert into eZUser_Module set Name='eZArticle';
insert into eZUser_Module set Name='eZBulkMail';
insert into eZUser_Module set Name='eZStats';
insert into eZUser_Permission SET ModuleID='14', Name='ModuleEdit';
insert into eZUser_Module set Name='eZSysInfo';
insert into eZUser_Permission SET ModuleID='15', Name='ModuleEdit';

ALTER TABLE eZSession_SessionVariable ADD GroupName char(50);
ALTER TABLE eZSession_Preferences ADD GroupName char(50);
ALTER TABLE eZSession_SessionVariable ADD INDEX (GroupName,Name);
ALTER TABLE eZSession_Preferences ADD INDEX (GroupName,Name);

alter table eZTrade_CartOptionValue add RemoteID varchar(100);

ALTER TABLE eZTrade_Product MODIFY Price float(10,5);

insert into eZUser_Permission set ModuleID='12', Name='WriteToRoot';

insert into eZUser_Module set Name='eZSiteManager';    
insert into eZUser_Permission set ModuleID='16', Name='ModuleEdit';   

insert into eZUser_GroupPermissionLink set IsEnabled='true', PermissionID='47', GroupID='1';  