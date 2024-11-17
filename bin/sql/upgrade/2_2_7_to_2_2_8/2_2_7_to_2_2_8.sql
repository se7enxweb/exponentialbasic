
# Fix eZ stat performance problems
create index eZStats_Archive_RequestedPageMonth on eZStats_Archive_RequestedPage (Month);
create index eZStats_Archive_RequestedPageURI on eZStats_Archive_RequestedPage (URI);

create index eZStats_Archive_RefererURLMonth on eZStats_Archive_RefererURL (Month);

create index eZStats_Archive_RemoteHostIP on eZStats_Archive_RemoteHost (IP);
create index eZStats_Archive_PageViewHour on eZStats_Archive_PageView (Hour);


# Fix eZImageCatalogue performance problems

create index eZImageCatalogue_Image_OriginalFileName on eZImageCatalogue_Image(OriginalFileName);
create index eZImageCatalogue_ImagePermission_GroupID on eZImageCatalogue_ImagePermission(GroupID);
create index eZImageCatalogue_ImagePermission_ObjectID on eZImageCatalogue_ImagePermission(ObjectID);
create index eZImageCatalogue_ImagePermission_ReadPermission on eZImageCatalogue_ImagePermission(ReadPermission);
create index eZImageCatalogue_CategoryPermission_GroupID on eZImageCatalogue_CategoryPermission(GroupID);
create index eZImageCatalogue_CategoryPermission_ObjectID on eZImageCatalogue_CategoryPermission(ObjectID);
create index eZImageCatalogue_CategoryPermission_ReadPermission on eZImageCatalogue_CategoryPermission(ReadPermission);

