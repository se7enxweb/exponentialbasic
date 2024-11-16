<?php
//
// $Id: ezmedia.php 9311 2002-02-28 09:55:43Z ce $
//
// Definition of eZMedia class
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
//!! eZMediaCatalogue
//! The eZMedia class hadles medias in the media catalogue.
/*!
  Example code:
  \code
  // Fetch an uploaded file and store it in the mediacatalogue.
    $file = new eZMediaFile();

    // userfile is the name of the input in the html form
    if ( $file->getUploadedFile( "userfile" ) )
    {
        $media = new eZMedia();
        $media->setName( $Name );
        $media->setCaption( $Caption );

        $media->setMedia( $file );

        $media->store();
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    \endcode
  \sa eZMediaCategory eZMediaAttribute eZMediaType
*/
/*!TODO
    $t in the example just pops out of nowhere, giving us no indication
    of where it was created or what connection it has with this class
 */
include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );

include_once( "ezmediacatalogue/classes/ezmediatype.php" );

class eZMedia
{
    /*!
      Constructs a new eZMedia object.
    */
    function __construct( $id="" )
    {
        $this->PhotographerID = 0;
        $this->NewMedia = false;
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZMedia object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $caption = $db->escapeString( $this->Caption );
        $filename = $db->escapeString( $this->FileName );
        $originalfilename = $db->fieldName( $this->OriginalFileName );

        if ( $this->ID == "" )
        {
            $db->lock( "eZMediaCatalogue_Media" );

            $timeStamp = eZDateTime::timeStamp( true );

            $this->ID = $db->nextID( "eZMediaCatalogue_Media", "ID" );
            $res = $db->query( "INSERT INTO eZMediaCatalogue_Media
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
                                             '$timeStamp' )");
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZMediaCatalogue_Media SET
                                 Name='$name',
                                 Caption='$caption',
                                 Description='$description',
                                 FileName='$filename',
                                 UserID='$this->UserID',
                                 WritePermission='$this->WritePermission',
                                 ReadPermission='$this->ReadPermission',
                                 OriginalFileName='$originalfilename',
                                 PhotographerID='$this->PhotographerID'
                                 WHERE ID='$this->ID'
                                 " );
        }

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Delete the eZMediaVariation object from the database and the filesystem.
    */
    function delete( $id=false )
    {
        $db =& eZDB::globalDatabase();

        if ( $id == false )
        {
            $id = $this->ID;
        }

        if ( isset( $id ) )
        {
            $db->query( "DELETE FROM eZMediaCatalogue_Media WHERE ID='$this->ID'" );
            $db->query( "DELETE FROM eZMediaCatalogue_MediaPermission WHERE ObjectID='$this->ID'" );
            $db->query( "DELETE FROM eZMediaCatalogue_MediaCategoryLink WHERE MediaID='$this->ID'" );
            $db->query( "DELETE FROM eZMediaCatalogue_MediaCategoryDefinition WHERE MediaID='$this->ID'" );
            $db->query( "DELETE FROM eZMediaCatalogue_TypeLink WHERE MediaID='$this->ID'" );

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
            $db->array_query( $media_array, "SELECT * FROM eZMediaCatalogue_Media WHERE ID='$id'" );
            if( count( $media_array ) > 0 )
            {
                if ( count( $media_array ) > 1 )
                {
                    print( "<br /><b>Error: Media's with the same ID was found in the database. This shouldent happen.</b><br />" );
                }
                $this->ID = $media_array[0][$db->fieldName("ID")];
                $this->Name = $media_array[0][$db->fieldName("Name")];
                $this->Caption = $media_array[0][$db->fieldName("Caption")];
                $this->Description = $media_array[0][$db->fieldName("Description")];
                $this->FileName = $media_array[0][$db->fieldName("FileName")];
                $this->OriginalFileName = $media_array[0][$db->fieldName("OriginalFileName")];
                $this->UserID = $media_array[0][$db->fieldName("UserID")];
                $this->WritePermission = $media_array[0][$db->fieldName("WritePermission")];
                $this->ReadPermission = $media_array[0][$db->fieldName("ReadPermission")];
                $this->PhotographerID = $media_array[0][$db->fieldName("PhotographerID")];

                $ret = true;
            }
        }

        return $ret;
    }

    /*!
      \static
      Fetches an media from the database if one with the same "original filename" is found.
    */
    function getByOriginalFileName( $id = "" )
    {
        $db =& eZDB::globalDatabase();
        $ret = new eZMedia();
        if ( $id != "" )
        {
            $db->array_query( $media_array, "SELECT * FROM eZMediaCatalogue_Media WHERE OriginalFileName='$id'" );
            if( count( $media_array ) > 0 )
            {
                if ( count( $media_array ) > 1 )
                {
                    print( "<br /><b>Error: Media's with the same  was found in the database. This shouldn't happen.</b><br />" );
                }
                $ret = new eZMedia( $media_array[0][$db->fieldName("ID")] );
            }
        }
        return $ret;
    }

    /*!
      Returns true if the media is assigned to the category given
      as argument. False if not.
    */
    function existsInCategory( &$category )
    {
       $ret = false;
       if ( is_a( $category, "eZMediaCategory" ) )
       {
           $db =& eZDB::globalDatabase();
           $catID = $category->id();

           $db->array_query( $ret_array, "SELECT ID FROM eZMediaCatalogue_MediaCategoryLink
                                    WHERE MediaID='$this->ID' AND CategoryID='$catID'" );

           if ( count( $ret_array ) == 1 )
           {
               $ret = true;
           }
       }
       return $ret;
    }

    /*!
      Set's the medias defined category. This is the main category for the media.
      Additional categories can be added with eZMediaCategory::addMedia();
    */
    function setCategoryDefinition( &$value )
    {
        if ( is_a( $value, "eZMediaCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $db->begin( );

            $categoryID = $value->id();

            $db->query( "DELETE FROM eZMediaCatalogue_MediaCategoryDefinition
                                     WHERE MediaID='$this->ID'" );

            $db->lock( "eZMediaCatalogue_MediaCategoryDefinition" );

            $nextID = $db->nextID( "eZMediaCatalogue_MediaCategoryDefinition", "ID" );

            $query = "INSERT INTO eZMediaCatalogue_MediaCategoryDefinition ( ID, CategoryID, MediaID )
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
      Returns the media's definition category.
    */
    function categoryDefinition( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT CategoryID FROM
                                            eZMediaCatalogue_MediaCategoryDefinition
                                            WHERE MediaID='$this->ID'" );

        $category = false;
        if ( count( $res ) == 1 )
        {
            $category = new eZMediaCategory( $res[0][$db->fieldName("CategoryID")] );
        }
        else
        {
            return -1;
        }

        return $category;
    }

    /*!
      Get all the medias that is not assigned to a category.

      The medias are returned as an array of eZMedia objects.
     */
    function getUnassigned()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $mediaArray, "SELECT Media.ID, Link.MediaID
                                        FROM eZMediaCatalogue_Media AS Media
                                        LEFT JOIN  eZMediaCatalogue_MediaCategoryLink AS Link
                                        ON Media.ID=Link.MediaID
                                        WHERE MediaID IS NULL" );

        foreach ( $mediaArray as $media )
        {
            $returnArray[] = new eZMedia( $media[$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Get the total count of all the unassigned medias.
     */
    function countUnassigned()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $media, "SELECT COUNT(Media.ID) as Count, Link.MediaID
                                        FROM eZMediaCatalogue_Media AS Media
                                        LEFT JOIN  eZMediaCatalogue_MediaCategoryLink AS Link
                                        ON Media.ID=Link.MediaID
                                        WHERE MediaID IS NULL
                                        GROUP By MediaID" );
        return $media[$db->fieldName("Count")];
    }

    /*!
      Returns the id of the media.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the media.
    */
    function name( $html = true )
    {
       if ( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Returns the caption of the media.
    */
    function caption( $html = true )
    {
       if ( $html && !is_null( $this->Caption ) )
           return htmlspecialchars( $this->Caption );
       else
           return $this->Caption;
    }

    /*!
      Returns the description of the media.
    */
    function description( $html = true )
    {
        if ( $html )
            return htmlspecialchars( $this->Description );
        else
            return $this->Description;
    }

    /*!
      Returns the filename of the media.
    */
    function fileName()
    {
        return $this->FileName;
    }

    /*!
      Returns the original file name of the media.
    */
    function originalFileName()
    {
        return $this->OriginalFileName;
    }

    function fileExists( $relative=false )
    {
       if ( $relative == true )
       {
           $path = "ezmediacatalogue/catalogue/" . $this->FileName;
       }
       else
       {
           $path = "/ezmediacatalogue/catalogue/" . $this->FileName;
       }

       $relPath = "ezmediacatalogue/catalogue/" . $this->FileName;

       return eZFile::file_exists( $relPath ) and is_file( $relPath );
    }

    /*!
      Returns the path and filename to the original media.

      If $relative is set to true the path is returned relative.
      Absolute is default.
    */
    function filePath( $relative=false )
    {
       $path = "ezmediacatalogue/catalogue/" . $this->FileName;

       if ( !$relative )
       {
           $path = '/' . $path;
       }
       
       if ( !eZFile::file_exists( $path ) or !eZFile::is_file( $path ) )
       {
           $path = "ezmediacatalogue/admin/medias/failedmedia.gif";
           if ( !$relative )
               $path = '/' . $path;
       }
       return $path;
    }

    /*!
      Same as filePath()
     */
    function mediaPath( $relative = false )
    {
        return $this->filePath( $relative );
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
      Sets the media name.
    */
    function setName( &$value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the media caption.
    */
    function setCaption( &$value )
    {
        $this->Caption = $value;
    }

    /*!
      Sets the media description.
    */
    function setDescription( &$value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the original medianame.
    */
    function setOriginalFileName( &$value )
    {
        $this->OriginalFileName = $value;
    }

    /*!
      Returns true if the file is a valid media.
    */
    function checkMedia( &$file )
    {
       if ( is_a( $file, "eZMediaFile" ) )
       {
           $name = $file->tmpName();
           return true;
       }
       return false;
    }

    /*!
      Makes a copy of the media and stores the media in the catalogue.

      If the media is not of the type .jpg or .gif the media is converted to .jpg.
    */
    function setMedia( &$file )
    {
       if ( is_a( $file, "eZMediaFile" ) )
       {
           $this->OriginalFileName = $file->name();
           $tmpname = $file->tmpName();

           $info = eZMediaFile::information( $this->OriginalFileName );
           $suffix = $info["suffix"];
           $postfix = $info["dot-suffix"];

           if ( $postfix != "" )
           {
               // Copy the file since we support it directly
               $file->copy( "ezmediacatalogue/catalogue/" . basename( $file->tmpName() ) . $postfix );
           }
           else
           {
               $postfix = "";
           }

           $this->FileName = basename( $file->tmpName() ) . $postfix;

           $name = $file->name();

           $this->OriginalFileName =& $name;

           return true;
       }
       return false;
    }

    /*!
      Sets the user of the eZMedia object.
    */
    function setUser( &$user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $userID = $user->id();

            $this->UserID = $userID;
        }
    }

    /*!
      Returns the media's categories.
    */
    function categories()
    {
        $db =& eZDB::globalDatabase();

        $res = array();
        $db->array_query( $res, "SELECT CategoryID, MediaID FROM
                                 eZMediaCatalogue_MediaCategoryLink
                                 WHERE MediaID='$this->ID'" );
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
      $media is the ID of the media.
     */
    function isOwner( &$user, &$media )
    {
        if( !is_a( $user, "eZUser" ) )
            return false;

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZMediaCatalogue_Media WHERE ID='$media'");
        $userID = $res[$db->fieldName("UserID")];
        if(  $userID == $user->id() )
            return true;

        return false;
    }

    /*!
      Sets the photographer of the media
    */
    function setPhotographer( &$author )
    {
        if ( is_a( $author, "eZAuthor" ) )
            $this->PhotographerID = $author->id();
        else if ( is_numeric( $author ) )
            $this->PhotographerID = $author;
    }

    /*!
      Returns the photographer og the media
    */
    function &photographer()
    {
        return new eZAuthor( $this->PhotographerID );
    }

    /*!
      Sets the links type.
    */
    function setType( &$type )
    {
        if ( is_a( $type, "eZMediaType" ) )
        {
            $db =& eZDB::globalDatabase();

            $db->begin();

            $typeID = $type->id();

            $res[] = $db->query( "DELETE FROM eZMediaCatalogue_AttributeValue
                                     WHERE MediaID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZMediaCatalogue_TypeLink
                                     WHERE MediaID='$this->ID'" );

            $db->lock( "eZMediaCatalogue_TypeLink" );

            $nextID = $db->nextID( "eZMediaCatalogue_TypeLink", "ID" );

            $query = "INSERT INTO eZMediaCatalogue_TypeLink
                      (ID, TypeID, MediaID)
                      VALUES
                      ('$nextID',
                       '$typeID',
                       '$this->ID')";

            $res[] = $db->query( $query );

            $db->unlock();

            if ( in_array( false, $res ) )
                $db->rollback();
            else
                $db->commit();

        }
    }

    /*!
      Returns the link's type.
    */
    function type()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT TypeID FROM
                                 eZMediaCatalogue_TypeLink WHERE MediaID='$this->ID'" );

        $type = false;

        if ( count( $res ) == 1 )
        {
            $type = new eZMediaType( $res[0][$db->fieldName("TypeID")] );
        }

        return $type;
    }

    /*!
      Removes the links type definition.
    */
    function removeType()
    {
        $db =& eZDB::globalDatabase();

        // delete values
        $db->query( "DELETE FROM eZMediaCatalogue_AttributeValue WHERE MediaID='$this->ID'" );

        $db->query( "DELETE FROM eZMediaCatalogue_TypeLink WHERE MediaID='$this->ID'" );

    }

    function attributeString( )
    {
        $type = $this->type();

        if ( $type )
        {
            $attributes = $type->attributes();
            $attString = '';

            foreach( $attributes as $attribute )
            {
                $attString .= " " . $attribute->name() . "=\"" . $attribute->value( $this ) . "\"";
            }
        }
        return $attString;
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
    var $NewMedia;
}

?>
