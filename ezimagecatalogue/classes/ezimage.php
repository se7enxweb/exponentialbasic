<?php
//
// $Id: ezimage.php 9705 2002-08-15 14:20:29Z gl $
//
// Definition of eZImage class
//
// Created on: <21-Sep-2000 11:22:21 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZImageCatalogue
//! The eZImage class hadles images in the image catalogue.
/*!
  Example code:
  \code
  // Fetch an uploaded file and store it in the imagecatalogue.
    $file = new eZImageFile();

    // userfile is the name of the input in the html form
    if ( $file->getUploadedFile( "userfile" ) )
    {
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );

        $image->store();
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    // Get an image from the database and request a variation from it

    // gets an image from the eZProduct class
    // can also use code like $mainImage = new eZImage( 2 );
    // where 2 is the id of the image in the catalogue.
    $mainImage = $product->mainImage();

    if ( $mainImage )
    {
        $variation = $mainImage->requestImageVariation( 250, 250 );

        // set some template variables
        $t->set_var( "main_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "main_image_width", $variation->width() );
        $t->set_var( "main_image_height", $variation->height() );
        $t->set_var( "main_image_caption", $mainImage->caption() );
    }
    else
    {
        $t->set_var( "main_image", "" );
    }

    \endcode
  \sa eZImageVariation eZImageVariationGroup eZImageFile
*/
/*!TODO
    $t in the example just pops out of nowhere, giving us no indication
    of where it was created or what connection it has with this class
 */
include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/eztexttool.php" );

include_once( "ezimagecatalogue/classes/ezimagevariation.php" );
include_once( "ezimagecatalogue/classes/ezimagevariationgroup.php" );
include_once( "ezimagecatalogue/classes/ezimagecategory.php" );

include_once( "ezarticle/classes/ezarticle.php" );
include_once( "eztrade/classes/ezproduct.php" );

class eZImage
{
    /*!
      Constructs a new eZImage object.
    */
    function __construct( $id = "" )
    {
        $this->PhotographerID = 0;
        $this->UserID = 0;
        $this->WritePermission = 0;
        $this->ReadPermission = 0;
        $this->NewImage = false;
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZImage object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $caption = $db->escapeString( $this->Caption );
        $filename = $db->escapeString( $this->FileName );
        $originalfilename = $db->fieldName( $this->OriginalFileName );
        $keywords = $db->escapeString( $this->Keywords );

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZImageCatalogue_Image" );

            $timeStamp =& eZDateTime::timeStamp( true );

            $this->ID = $db->nextID( "eZImageCatalogue_Image", "ID" );
            $res = $db->query( "INSERT INTO eZImageCatalogue_Image
                                           ( ID,
                                             Name,
                                             Caption,
                                             Description,
                                             FileName,
                                             UserID,
                                             WritePermission,
                                             ReadPermission,
                                             OriginalFileName,
                                             PhotographerID,
                                             Keywords,
                                             Created )
                                    VALUES ( '$this->ID',
                                             '$name',
                                             '$caption',
                                             '$description',
                                             '$filename',
                                             '$this->UserID',
                                             '$this->WritePermission',
                                             '$this->ReadPermission',
                                             '$originalfilename',
                                             '$this->PhotographerID',
                                             '$keywords',
                                             '$timeStamp' )");
            $db->unlock();
        }
        else
        {
            if ( $this->NewImage )
            {
                $variationArray =& $this->variations();

                foreach ( $variationArray as $variation )
                {
                    $variation->delete();
                }
            }

            $res = $db->query( "UPDATE eZImageCatalogue_Image SET
                                 Name='$name',
                                 Caption='$caption',
                                 Description='$description',
                                 FileName='$filename',
                                 UserID='$this->UserID',
                                 WritePermission='$this->WritePermission',
                                 ReadPermission='$this->ReadPermission',
                                 OriginalFileName='$originalfilename',
                                 PhotographerID='$this->PhotographerID',
                                 Keywords='$keywords'
                                 WHERE ID='$this->ID'
                                 " );
        }

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
    }

    /*
    function &search( $name, $literal = false )
    {
        $db =& eZDB::globalDatabase();
        $res = array();

        $query = new eZQuery( array( "Name", "Caption", "Description", "Keywords" ),
                              $name );
        $query->setIsLiteral( $literal );
        $where =& $query->buildQuery();

        $db->array_query( $image_array,
                          "SELECT ID FROM eZImageCatalogue_Image WHERE $where" );

        foreach( $image_array as $image )
        {
            $res[] =& new eZImage( $image[$db->fieldName("ID")] );
        }
        return $res;
        }*/

    /*!
      \static
      Searches the database for images.
    */
    function search( &$queryText, $offset = 0, $limit = 30, $userID = -1 )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $returnArray = array();
        if ( $userID > -1 )
            $user = new eZUser( $userID );
        else
            $user =& eZUser::currentUser();
        $groupString = "AND i.ID=p.ObjectID AND c.ID=icl.CategoryID AND icl.ImageID=i.ID AND cp.ObjectID=c.ID AND cp.ReadPermission='1' AND ( ( ( (p.GroupID='-1' AND cp.GroupID='-1')";
        if ( $user )
        {
            foreach ( $user->groups( false ) as $group )
            {
                $groupString .= "OR (p.GroupID='$group' AND cp.GroupID='$group') ";
                $groupString .= "OR (p.GroupID='-1' AND cp.GroupID='$group') ";
                $groupString .= "OR (p.GroupID='$group' AND cp.GroupID='-1') ";
            }
        }
        $groupString .= ") AND p.ReadPermission='1' ) OR ( i.UserID='$userID' ) )";
        $query = new eZQuery( array( "i.Name", "i.Description", "i.Caption" ), $queryText );
        $query->setPartialCompare( true );
        $queryString = "SELECT i.ID, i.Name as Count FROM eZImageCatalogue_Image as i";
        if ( $user && $user->hasRootAccess() )
        {
            $groupString = "";
        }
        else
        {
            $queryString .= ", eZImageCatalogue_ImagePermission as p, eZImageCatalogue_Category as c,
                               eZImageCatalogue_ImageCategoryLink as icl, eZImageCatalogue_CategoryPermission as cp";
        }
        $queryString .= " WHERE (" . $query->buildQuery() . ") $groupString ";
        $queryString .= " GROUP BY i.ID ORDER BY i.Name";
        $limit = array( "Limit" => $limit,
                        "Offset" => $offset );
        $db->array_query( $fileArray, $queryString, $limit );
        foreach ( $fileArray as $file )
        {
            $returnArray[] = new eZImage( $file[$db->fieldName( "ID" )] );
        }
        return $returnArray;
    }

    /*!
      \static
      Searches the database for images.
    */
    function searchCount( $name, $literal = false )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $returnArray = array();
        if ( $userID > -1 )
            $user = new eZUser( $userID );
        else
            $user =& eZUser::currentUser();
        $groupString = "AND i.ID=p.ObjectID AND c.ID=icl.CategoryID AND icl.ImageID=i.ID AND cp.ObjectID=c.ID AND cp.ReadPermission='1' AND ( ( ( (p.GroupID='-1' AND cp.GroupID='-1')";
        if ( $user )
        {
            foreach ( $user->groups( false ) as $group )
            {
                $groupString .= "OR (p.GroupID='$group' AND cp.GroupID='$group') ";
                $groupString .= "OR (p.GroupID='-1' AND cp.GroupID='$group') ";
                $groupString .= "OR (p.GroupID='$group' AND cp.GroupID='-1') ";
            }
        }
        $groupString .= ") AND p.ReadPermission='1' ) OR ( i.UserID='$userID' ) )";
        $query = new eZQuery( array( "i.Name", "i.Description", "i.Caption" ), $queryText );
        $query->setPartialCompare( true );
        $queryString = "SELECT COUNT(i.ID) AS Count FROM eZImageCatalogue_Image as i";
        if ( $user && $user->hasRootAccess() )
        {
            $groupString = "";
        }
        else
        {
            $queryString .= ", eZImageCatalogue_ImagePermission as p, eZImageCatalogue_Category as c,
                               eZImageCatalogue_ImageCategoryLink as icl, eZImageCatalogue_CategoryPermission as cp";
        }
        $queryString .= " WHERE (" . $query->buildQuery() . ") $groupString ";
        $queryString .= " GROUP BY i.ID ORDER BY i.Name";
        $db->array_query( $fileArray, $queryString );
        $ret = $fileArray[0][$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Delete the eZImageVariation object from the database and the filesystem.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $variationArray =& $this->variations();

            foreach ( $variationArray as $variation )
            {
                $variation->delete();
            }

            $db->query( "DELETE FROM eZImageCatalogue_Image WHERE ID='$this->ID'" );
            $db->query( "DELETE FROM eZImageCatalogue_ImagePermission WHERE ObjectID='$this->ID'" );
            $db->query( "DELETE FROM eZImageCatalogue_ImageCategoryLink WHERE ImageID='$this->ID'" );
            $db->query( "DELETE FROM eZImageCatalogue_ImageCategoryDefinition WHERE ImageID='$this->ID'" );
            $db->query( "DELETE FROM eZImageCatalogue_ImageMap WHERE ImageID='$this->ID'" );

            // Delete from the filesystem
            if ( eZFile::file_exists( $this->filePath( true ) ) )
            {
                eZFile::unlink( $this->filePath( true ) );
            }
        }
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $image_array, "SELECT * FROM eZImageCatalogue_Image WHERE ID='$id'" );
            if ( count( $image_array ) > 0 )
            {
                if ( count( $image_array ) > 1 )
                {
                    print( "<br /><b>Error: Image's with the same ID was found in the database. This shouldent happen.</b><br />" );
                }
                $this->ID =& $image_array[0][$db->fieldName( "ID" )];
                $this->Name =& $image_array[0][$db->fieldName( "Name" )];
                $this->Caption =& $image_array[0][$db->fieldName( "Caption" )];
                $this->Description =& $image_array[0][$db->fieldName( "Description" )];
                $this->FileName =& $image_array[0][$db->fieldName( "FileName" )];
                $this->OriginalFileName =& $image_array[0][$db->fieldName( "OriginalFileName" )];
                $this->UserID =& $image_array[0][$db->fieldName( "UserID" )];
                $this->WritePermission =& $image_array[0][$db->fieldName( "WritePermission" )];
                $this->ReadPermission =& $image_array[0][$db->fieldName( "ReadPermission" )];
                $this->PhotographerID =& $image_array[0][$db->fieldName( "PhotographerID" )];
                $this->Keywords =& $image_array[0][$db->fieldName( "Keywords" )];

                $ret = true;
            }
        }

        return $ret;
    }

    /*!
        \static
      Fetches an image from the database if one with the same "original filename" is found.
    */
    function getByOriginalFileName( $id = "" )
    {
        $db =& eZDB::globalDatabase();
        $ret = new eZImage();
        if ( $id != "" )
        {
            $db->array_query( $image_array, "SELECT * FROM eZImageCatalogue_Image WHERE OriginalFileName='$id'" );
            if ( count( $image_array ) > 0 )
            {
                if ( count( $image_array ) > 1 )
                {
                    print( "<br /><b>Error: Image's with the same  was found in the database. This shouldn't happen.</b><br />" );
                }
                $ret = new eZImage( $image_array[0][$db->fieldName( "ID" )] );
            }
        }
        return $ret;
    }

    /*!
      Returns true if the image is assigned to the category given
      as argument. False if not.
    */
    function existsInCategory( $category )
    {
       $ret = false;
       if ( is_a( $category, "eZImageCategory" ) )
       {
           $db =& eZDB::globalDatabase();
           $catID = $category->id();

           $db->array_query( $ret_array, "SELECT ID FROM eZImageCatalogue_ImageCategoryLink
                                    WHERE ImageID='$this->ID' AND CategoryID='$catID'" );

           if ( count( $ret_array ) == 1 )
           {
               $ret = true;
           }
       }
       return $ret;
    }

    /*!
      Set's the images defined category. This is the main category for the image.
      Additional categories can be added with eZImageCategory::addImage();
    */
    function setCategoryDefinition( $value )
    {
        if ( is_a( $value, "eZImageCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $db->begin();

            $categoryID = $value->id();

            $db->query( "DELETE FROM eZImageCatalogue_ImageCategoryDefinition
                                     WHERE ImageID='$this->ID'" );

            $db->lock( "eZImageCatalogue_ImageCategoryDefinition" );

            $nextID = $db->nextID( "eZImageCatalogue_ImageCategoryDefinition", "ID" );

            $query = "INSERT INTO eZImageCatalogue_ImageCategoryDefinition ( ID, CategoryID, ImageID )
                      VALUES ( '$nextID', '$categoryID', '$this->ID' )";

            $res = $db->query( $query );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Returns the image's definition category.
    */
    function categoryDefinition( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT CategoryID FROM
                                            eZImageCatalogue_ImageCategoryDefinition
                                            WHERE ImageID='$this->ID'" );

        $category = false;
        if ( count( $res ) == 1 )
        {
            $category = new eZImageCategory( $res[0][$db->fieldName( "CategoryID" )] );
        }
        else
        {
            return -1;
        }

        return $category;
    }

    /*!
      Get all the images that is not assigned to a category.

      The images are returned as an array of eZImage objects.
     */
    static public function getUnassigned( $offset = -1, $limit = -1 )
    {
        $db =& eZDB::globalDatabase();
        $returnArray = array();

        if ( $offset > 0 || $limit > 0 )
            $limitArray = array( "Offset" => $offset, "Limit" => $limit );
        else
            $limitArray = array();
        $db->array_query( $imageArray, "SELECT Image.ID, Link.ImageID
                                        FROM eZImageCatalogue_Image AS Image
                                        LEFT JOIN  eZImageCatalogue_ImageCategoryLink AS Link
                                        ON Image.ID=Link.ImageID
                                        WHERE ImageID IS NULL", $limitArray );

        foreach ( $imageArray as $image )
        {
            $returnArray[] = new eZImage( $image[$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Get the total count of all the unassigned images.
     */
    function countUnassigned()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $image, "SELECT COUNT(Image.ID) as Count, Link.ImageID
                                        FROM eZImageCatalogue_Image AS Image
                                        LEFT JOIN eZImageCatalogue_ImageCategoryLink AS Link
                                        ON Image.ID=Link.ImageID
                                        WHERE ImageID IS NULL
                                        GROUP By ImageID" );

        return $image[$db->fieldName( "Count" )];
    }

    /*!
      Check what read permission the user have to this eZVirtualFile object.

      Returns:
      User - if the user owns the file
      Group - if the user is member of the group
      All - if the file can be read by everybody
      False - if the user don't have access
    */
    function checkReadPermission( &$currentUser )
    {
        $ret = false;

        $read = $this->readPermission();

        if ( is_a( $currentUser, "eZUser" ) )
        {
            if ( $read == "User" )
            {
                if ( $this->UserID != 0 )
                {
                    if ( $currentUser->id() == $this->UserID )
                    {
                        $ret = "User";
                    }
                    else
                    {
                        return $ret;
                    }
                }
            }
            else if ( $read == "Group" )
            {
                if ( $this->UserID != 0 )
                {
                    $currentGroups =& $currentUser->groups();
                    foreach ( $currentGroups as $Groups )
                    {
                        $user = new eZUser( $this->UserID );
                        $userGroups =& $user->groups();

                        foreach ( $userGroups as $userGroup )
                        {
                            if ( $Groups->id() == $userGroup->id() )
                            {
                                $ret = "Group";
                            }
                            else
                            {
                                return $ret;
                            }
                        }
                    }
                }
            }
            else if ( $read == "All" )
            {
                $ret = "Group";
            }
        }
        else
        {
            if ( $read == "All" )
            {
                $ret = "All";
            }
        }

        return $ret;

    }

    /*!
      Check what write permission the user have to this eZVirtualFile object.

      Returns:
      User - if the user owns the file
      Group - if the user is member of the group
      All - if the file can be write by everybody
      False - if the user don't have access
    */
    function checkWritePermission( &$currentUser )
    {
        $ret = false;

        $write = $this->writePermission();

        if ( is_a( $currentUser, "eZUser" ) )
        {

            if ( $write == "User" )
            {
                if ( $this->UserID != 0 )
                {
                    if ( $currentUser->id() == $this->UserID )
                    {
                        $ret = "User";
                    }
                    else
                    {
                        return $ret;
                    }
                }
            }
            else if ( $write == "Group" )
            {
                if ( $this->UserID != 0 )
                {
                    $currentGroups =& $currentUser->groups();
                    foreach ( $currentGroups as $Groups )
                    {
                        $user = new eZUser( $this->UserID );
                        $userGroups =& $user->groups();

                        foreach ( $userGroups as $userGroup )
                        {
                            if ( $Groups->id() == $userGroup->id() )
                            {
                                $ret = "Group";
                            }
                            else
                            {
                                return $ret;
                            }
                        }
                    }
                }
            }
            else if ( $write == "All" )
            {
                $ret = "Group";
            }
        }
        else
        {
            if ( $write == "All" )
            {
                $ret = "All";
            }
        }

        return $ret;
    }

    /*!
      Returns the id of the image.
    */
    function id()
    {
        if ( isset( $this->ID ) )
            return $this->ID;
        else
            return;
    }

    /*!
      Returns the name of the image.
    */
    function name( $html = true )
    {
       if ( $html )
           return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Name ) );
       else
           return $this->Name;
    }

    /*!
      Returns the caption of the image.
    */
    function caption( $html = true )
    {
       if ( $html )
           return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Caption ) );
       else
           return $this->Caption;
    }

    /*!
      Returns the description of the image.
    */
    function description( $html = true )
    {
       if ( $html )
           return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Description ) );
       else
           return $this->Description;
    }

    /*!
      Returns the filename of the image.
    */
    function fileName()
    {
        return $this->FileName;
    }

    /*!
      Returns the original file name of the image.
    */
    function originalFileName()
    {
        return $this->OriginalFileName;
    }

    function fileExists( $relative=false )
    {
       if ( $relative == true )
       {
           $path = "ezimagecatalogue/catalogue/" .$this->FileName;
       }
       else
       {
           $path = "/ezimagecatalogue/catalogue/" .$this->FileName;
       }

       $relPath = "ezimagecatalogue/catalogue/" . $this->FileName;

       return eZFile::file_exists( $relPath ) and is_file( $relPath );
    }
    /*!
      Returns the path and filename to the original image.

      If $relative is set to true the path is returned relative.
      Absolute is default.
    */
    function filePath( $relative = false )
    {
       $relPath = "ezimagecatalogue/catalogue/" . $this->FileName;

       if ( $relative == true )
       {
           $path = "ezimagecatalogue/catalogue/" . $this->FileName;
       }
       else
       {
           $path = "/ezimagecatalogue/catalogue/" . $this->FileName;
       }

       if ( !eZFile::file_exists( $relPath ) or !is_file( $relPath ) )
       {
           $path = "ezimagecatalogue/admin/images/failedimage.gif";
           if ( !$relative )
               $path = "/$path";
       }
       return $path;
    }

    /*!
      Same as filePath()
     */
    function imagePath( $relative=false )
    {
        return $this->filePath( $relative );
    }

    /*!
      Returns the eZImageVariation object to a scaled version of the image.
      If the scaled version does not exist it is created.

      The required image variation group is also created if it does not exist.

      The path to the file is returned.

      False is returned if the original image does not exist.
    */
    function requestImageVariation( $width, $height, $convertToGray = false, $allow_error = false )
    {
       $group = new eZImageVariationGroup();
       $variation = new eZImageVariation();

       if ( $group->groupExists( $width, $height ) )
       {
           $group->get( $group->groupExists( $width, $height ) );

           $ret =& $variation->requestVariation( $this, $group, $convertToGray, $allow_error );
       }
       else
       {
           $group->setWidth( $width );
           $group->setHeight( $height );
           $group->store();


           $ret =& $variation->requestVariation( $this, $group, $convertToGray, $allow_error );
       }

       return $ret;
    }

    /*!
      Returns the writePermission permission of the eZImage object.
    */
    function writePermission()
    {
       switch( $this->WritePermission )
       {
           case 1:
           {
               $ret = "User";
           }
           break;

           case 2:
           {
               $ret = "Group";
           }
           break;

           case 3:
           {
               $ret = "All";
           }
           break;

           default:
               $ret = "User";
       }

       return $ret;
    }

    /*!
      Returns the read permission of the eZImage object.
    */
    function readPermission()
    {
       switch( $this->ReadPermission )
       {
           case 1:
           {
               $ret = "User";
           }
           break;

           case 2:
           {
               $ret = "Group";
           }
           break;

           case 3:
           {
               $ret = "All";
           }
           break;

           default:
               $ret = "User";
       }

       return $ret;
    }

    /*!
      Returns a eZUser object.
    */
    function user()
    {
        if ( $this->UserID != 0 )
        {
            $ret = new eZUser( $this->UserID );
        }

        return $ret;
    }


    /*!
      Sets the image name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the image caption.
    */
    function setCaption( $value )
    {
        $this->Caption = $value;
    }

    /*!
      Sets the image description.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the original imagename.
    */
    function setOriginalFileName( $value )
    {
        $this->OriginalFileName = $value;
    }

    /*!
      Returns true if the file is a valid image.
    */
    function checkImage( &$file )
    {
       if ( is_a( $file, "eZImageFile" ) )
       {
           $name = $file->tmpName();
           if ( !file_exists( $name ) or !is_file( $name ) )
               return false;
           return true;
       }
       return false;
    }

    /*!
      Makes a copy of the image and stores the image in the catalogue.

      If the image is not of the type .jpg or .gif the image is converted to .jpg.
    */
    function setImage( &$file )
    {
       if ( is_a( $file, "eZImageFile" ) )
       {
           $this->OriginalFileName = $file->name();
           $tmpname = $file->tmpName();
           if ( !file_exists( $tmpname ) or !is_file( $tmpname ) )
               return false;

           $info = eZImageFile::information( $this->OriginalFileName );
           $suffix = $info["suffix"];
           $postfix = $info["dot-suffix"];

           if ( $postfix != "" )
           {
               // Copy the file since we support it directly
               $file->copy( "ezimagecatalogue/catalogue/" . basename( $file->tmpName() ) . $postfix );
           }
           else
           {
               // Convert it to jpg.
               if ( !$file->convertCopy( "ezimagecatalogue/catalogue/" . basename( $file->tmpName() ) . ".jpg" ) )
                   return false;
               $postfix = ".jpg";
           }

           $this->FileName = basename( $file->tmpName() ) . $postfix;

           $name = $file->name();

           $this->OriginalFileName =& $name;
           $this->NewImage = true;

           return true;
       }
       return false;
    }

    /*!
      Sets the writePermission permission of the eZImage object.

      1 = User
      2 = Group
      3 = All

    */
    function setWritePermission( $value )
    {
       switch ( $value )
       {
           case "User":
           {
               $value = 1;
           }
           break;

           case "Group":
           {
               $value = 2;
           }
           break;

           case "All":
           {
               $value = 3;
           }
           break;

           default:
               $value = 1;
       }

       $this->WritePermission = $value;
    }

    /*!
      Sets the read permission of the eZImage object.

      1 = User
      2 = Group
      3 = All

    */
    function setReadPermission( $value )
    {
       switch ( $value )
       {
           case "User":
           {
               $value = 1;
           }
           break;

           case "Group":
           {
               $value = 2;
           }
           break;

           case "All":
           {
               $value = 3;
           }
           break;

           default:
               $value = 1;
       }

       $this->ReadPermission = $value;
    }

    /*!
      Sets the user of the eZImage object.
    */
    function setUser( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $userID = $user->id();

            $this->UserID = $userID;
        }
    }

    /*!
      Returns the width of the image.
    */
    function width()
    {
        if ( eZFile::file_exists( $this->filePath( true ) ) and is_file( $this->filePath( true ) ) )
        {
            $size = getimagesize( $this->filePath( true ) );
            return $size[0];
        }
        else
            return 120;
    }

    /*!
      Returns the height of the image.
    */
    function height()
    {
        if ( eZFile::file_exists( $this->filePath( true ) ) and is_file( $this->filePath( true ) ) )
        {
            $size = getimagesize( $this->filePath( true ) );
            return $size[1];
        }
        else
            return 40;
    }

    /*!
      Returns every variation to a image as a array of eZVariation objects.
    */
    function variations()
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();

        $db->array_query( $variationArray, "SELECT ID, Width, Height
                                            FROM eZImageCatalogue_ImageVariation
                                            WHERE ImageID='$this->ID' ORDER BY Width, Height" );

        foreach ( $variationArray as $variation )
        {
            $returnArray[] = new eZImageVariation( $variation[$db->fieldName("ID")] );
        }

        return $returnArray;
    }

    /*!
      Returns the image's categories.
    */
    function categories()
    {
        $db =& eZDB::globalDatabase();

        $res = array();
        $db->array_query( $res, "SELECT CategoryID, ImageID FROM
                                 eZImageCatalogue_ImageCategoryLink
                                 WHERE ImageID='$this->ID'" );
        $category = false;

        if ( count( $res ) > 0 )
            $category = array();

        for ( $i = 0; $i < count( $res ); $i++ )
        {
            array_push( $category, $res[$i][$db->fieldName("CategoryID")] );
        }

        return $category;
    }


    /*!
      \Static
      Returns true if the given user is the owner of the given object.
      $user is either a userID or an eZUser.
      $image is the ID of the image.
     */
    static public function isOwner( $user, $image )
    {
        if ( !is_a( $user, "eZUser" ) )
            return false;

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZImageCatalogue_Image WHERE ID='$image'");
        $userID = $res[$db->fieldName("UserID")];
        if(  $userID == $user->id() )
            return true;

        return false;
    }

    /*!
      Sets the photographer of the image
    */
    function setPhotographer( $author )
    {
        if ( is_a( $author, "eZAuthor" ) )
            $this->PhotographerID = $author->id();
        else if ( is_numeric( $author ) )
            $this->PhotographerID = $author;
    }

    /*!
      Returns the photographer og the image
    */
    function photographer( $as_object = true )
    {
        return $as_object ? new eZAuthor( $this->PhotographerID ) : $this->PhotographerID;
    }

    /*!
      Sets the keywords of the image
    */
    function setKeywords( $keywords )
    {
        if( $keywords != "" )
        {
            $this->Keywords = $keywords;
        }
    }

    /*!
      Returns the photographer og the image
    */
    function keywords()
    {
        return $this->Keywords;
    }

    /*!
      Returns the articles that this image is used in.
    */
    function articles()
    {
        $db =& eZDB::globalDatabase();

        $res = array();
        $db->array_query( $res, "SELECT ArticleID FROM
                                 eZArticle_ArticleImageLink
                                 WHERE ImageID='$this->ID'" );
        $articles = array();

        for ( $i = 0; $i < count( $res ); $i++ )
        {
            $articles[] = new eZArticle( $res[$i][$db->fieldName("ArticleID")] );
        }

        return $articles;
    }

    /*!
      Returns the products that this image is used in.
    */
    function products()
    {
        $db =& eZDB::globalDatabase();

        $res = array();
        $db->array_query( $res, "SELECT ProductID FROM
                                 eZTrade_ProductImageLink
                                 WHERE ImageID='$this->ID'" );
        $articles = array();

        for ( $i = 0; $i < count( $res ); $i++ )
        {
            $articles[] = new eZProduct( $res[$i][$db->fieldName("ProductID")] );
        }

        return $articles;
    }

    /*!
      Returns a random image from a category.
    */
    function randomImage( $categoryID=0 )
    {
        $db =& eZDB::globalDatabase();

        $res = array();

        if ( is_numeric ( $categoryID ) )
            $db->query_single( $res, "SELECT ImageID as ID, ((ImageID*0)+RAND()) AS Random FROM eZImageCatalogue_ImageCategoryLink WHERE CategoryID='$categoryID' ORDER BY Random LIMIT 1" );
        else
            $db->query_single( $res, "SELECT ID, ((ID*0)+RAND()) AS Random FROM eZImageCatalogue_Image ORDER BY Random LIMIT 1" );

        return new eZImage( $res["ID"] );
    }

    /*!
      Returns true if the current image has defined an image map.
    */
    function hasMap()
    {
        $db =& eZDB::globalDatabase();

        $res = array();
        $db->array_query( $res, "SELECT * FROM eZImageCatalogue_ImageMap WHERE ImageID='$this->ID'" );

        if ( count( $res ) > 0 )
            return true;
        else
            return false;
    }

    var $ID;
    var $Name;
    var $Caption;
    var $Description;
    var $FileName;
    var $OriginalFileName;
    var $ReadPermission;
    var $WritePermission;
    var $UserID;
    var $PhotographerID;
    var $NewImage;
    var $Keywords;
}

?>