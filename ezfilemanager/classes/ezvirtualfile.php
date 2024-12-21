<?php
//
// $Id: ezvirtualfile.php 9565 2002-05-23 11:42:58Z jhe $
//
// Definition of eZVirtualFile class
//
// Created on: <10-Dec-2000 15:36:36 bf>
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

//!! eZFileManager
//! The eZVirtualFile represents a file in the virtual file manager.
/*!

*/

/*!TODO
 */

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezfile.php" );

class eZVirtualfile
{
    /*!
      Constructs a new eZVirtualfile object.
    */
    function __construct( $id="" )
    {
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZVirtualFile object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $filename = $db->escapeString( $this->FileName );
        $originalfilename = $db->escapeString( $this->OriginalFileName );

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZFileManager_File" );
            $nextID = $db->nextID( "eZFileManager_File", "ID" );

            $result = $db->query( "INSERT INTO eZFileManager_File
                                  ( ID, Name, Description, FileName, OriginalFileName, UserID )
                                  VALUES ( '$nextID',
                                           '$name',
                                           '$description',
                                           '$filename',
                                           '$originalfilename',
                                           '$this->UserID' )
                                  " );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $result = $db->query( "UPDATE eZFileManager_File SET
                                 Name='$name',
                                 Description='$description',
                                 FileName='$filename',
                                 OriginalFileName='$originalfilename',
                                 UserID='$this->UserID'
                                 WHERE ID='$this->ID'
                                 " );
        }

        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Delete the eZVirtualFile object from the database and the filesystem.
    */
    function delete()
    {
        // Delete from the database
        $db =& eZDB::globalDatabase();

        if ( isSet( $this->ID ) )
        {
            $db->begin();

            $results[] = $db->query( "DELETE FROM eZFileManager_File WHERE ID='$this->ID'" );
            $results[] = $db->query( "DELETE FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
            $results[] = $db->query( "DELETE FROM eZFileManager_FilePermission WHERE ObjectID='$this->ID'" );

            $commit = true;
            foreach (  $results as $result )
            {
                if ( $result == false )
                    $commit = false;
            }
            if ( $commit == false )
                $db->rollback( );
            else
                $db->commit();
        }

        // Delete from the filesystem
        if ( ( eZFile::file_exists( $this->filePath( true ) ) ) && $commit )
        {
            eZFile::unlink( $this->filePath( true ) );
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
            $db->array_query( $virtualfile_array, "SELECT * FROM eZFileManager_File WHERE ID='$id'" );

            if ( count( $virtualfile_array ) > 1 )
            {
                die( "Error: VirtualFile's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $virtualfile_array ) == 1 )
            {
                $this->ID =& $virtualfile_array[0][$db->fieldName( "ID" )];
                $this->Name =& $virtualfile_array[0][$db->fieldName( "Name" )];
                $this->Description =& $virtualfile_array[0][$db->fieldName( "Description" )];
                $this->FileName =& $virtualfile_array[0][$db->fieldName( "FileName" )];
                $this->OriginalFileName =& $virtualfile_array[0][$db->fieldName( "OriginalFileName" )];
                $this->UserID =& $virtualfile_array[0][$db->fieldName( "UserID" )];
                $ret = true;
            }
        }
        return $ret;
    }


    /*!
      Returns all the files found in the database.

      The files are returned as an array of eZVirtualFile objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT ID, FROM eZFileManager_File ORDER BY Name" );

        for ( $i = 0; $i < count( $category_array ); $i++ )
        {
            $return_array[$i] = new eZVirtualFile( $category_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Does a search in the filemanager.

      Default limit is set to 30.
     */
    function &search( &$queryText, $offset = 0, $limit = 30, $userID = -1 )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $returnArray = array();
        if ( $userID > -1 )
            $user = new eZUser( $userID );
        else
            $user =& eZUser::currentUser();
        $groupString = "AND f.ID=p.ObjectID AND fo.ID=ffl.FolderID AND ffl.FileID=f.ID AND fp.ObjectID=fo.ID AND fp.ReadPermission='1' AND ( ( ( (p.GroupID='-1' AND fp.GroupID='-1')";
        if ( $user )
        {
            foreach ( $user->groups( false ) as $group )
            {
                $groupString .= "OR (p.GroupID='$group' AND fp.GroupID='$group') ";
                $groupString .= "OR (p.GroupID='-1' AND fp.GroupID='$group') ";
                $groupString .= "OR (p.GroupID='$group' AND fp.GroupID='-1') ";
            }
        }
        $groupString .= ") AND p.ReadPermission='1' ) OR ( f.UserID='$userID' ) )";
        $query = new eZQuery( array( "f.Name", "f.Description" ), $queryText );
        $query->setPartialCompare( true );
        $queryString = "SELECT f.ID, f.Name as Count FROM eZFileManager_File as f";
        if ( $user && $user->hasRootAccess() )
        {
            $groupString = "";
        }
        else
        {
            $queryString .= ", eZFileManager_FilePermission as p, eZFileManager_Folder as fo,
                               eZFileManager_FileFolderLink as ffl, eZFileManager_FolderPermission as fp";
        }
        $queryString .= " WHERE (" . $query->buildQuery() . ") $groupString ";
        $queryString .= " GROUP BY f.ID ORDER BY f.Name";
        $limit = array( "Limit" => $limit,
                        "Offset" => $offset );
        $db->array_query( $fileArray, $queryString, $limit );
        foreach ( $fileArray as $file )
        {
            $returnArray[] = new eZVirtualFile( $file[$db->fieldName( "ID" )] );
        }
        return $returnArray;
    }

    /*!
      Returns the total count of a query.
     */
    function searchCount( &$queryText, $userID = -1 )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $returnArray = array();
        if ( $userID > -1 )
            $user = new eZUser( $userID );
        else
            $user =& eZUser::currentUser();
        $groupString = "AND f.ID=p.ObjectID AND fo.ID=ffl.FolderID AND ffl.FileID=f.ID AND fp.ObjectID=fo.ID AND fp.ReadPermission='1' AND ( ( ( (p.GroupID='-1' AND fp.GroupID='-1')";
        if ( $user )
        {
            foreach ( $user->groups( false ) as $group )
            {
                $groupString .= "OR (p.GroupID='$group' AND fp.GroupID='$group') ";
                $groupString .= "OR (p.GroupID='-1' AND fp.GroupID='$group') ";
                $groupString .= "OR (p.GroupID='$group' AND fp.GroupID='-1') ";
            }
        }
        $groupString .= ") AND p.ReadPermission='1' ) OR ( f.UserID='$userID' ) )";
        $query = new eZQuery( array( "f.Name", "f.Description" ), $queryText );
        $query->setPartialCompare( true );
        $queryString = "SELECT COUNT(f.ID) as Count FROM eZFileManager_File as f";
        if ( $user && $user->hasRootAccess() )
        {
            $groupString = "";
        }
        else
        {
            $queryString .= ", eZFileManager_FilePermission as p, eZFileManager_Folder as fo,
                               eZFileManager_FileFolderLink as ffl, eZFileManager_FolderPermission as fp";
        }
        $queryString .= " WHERE (" . $query->buildQuery() . ") $groupString ";
        $queryString .= " GROUP BY f.ID ORDER BY f.Name";
        $db->array_query( $fileArray, $queryString );

        $ret = $fileArray[0][$db->fieldName( "Count" )];

        return $ret;
    }

    /*!
      Get all the files that is not assigned to a category.

      The images are returned as an array of eZVirtualFile objects.
     */
    static public function &getUnassigned()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $fileArray, "SELECT File.ID, Link.FileID
                                        FROM eZFileManager_File AS File
                                        LEFT JOIN  eZFileManager_FileFolderLink AS Link
                                        ON File.ID=Link.FileID
                                        WHERE FileID IS NULL" );

        foreach ( $fileArray as $file )
        {
            $returnArray[] = new eZVirtualFile( $file[$db->fieldName( "ID" )] );
        }
        return $returnArray;
    }


    /*!
      Returns the id of the virtual file.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the virtual file.
    */
    function &name( $html = true )
    {
        if ( $html )
            return htmlspecialchars( $this->Name );
        else
            return $this->Name;
    }

    /*!
      Returns the description of the virtual file.
    */
    function &description( $html = true )
    {
        if ( $html )
            return htmlspecialchars( $this->Description );
        else
            return $this->Description;
    }

    /*!
      Returns the filename of the virtual file.
    */
    function &fileName()
    {
        return $this->FileName;
    }

    /*!
      Returns the original file name of the virtual file.
    */
    function &originalFileName()
    {
        return $this->OriginalFileName;
    }


    /*!
      Returns a eZUser object.
    */
    function &user( $as_object = true )
    {
        if ( $this->UserID != 0 )
        {
            $ret = $as_object ? new eZUser( $this->UserID ) : $this->UserID;
        }

        return $ret;
    }

    /*!
      \Static
      Returns true if the given user is the owner of the given object.
      $user is either a userID or an eZUser.
      $file is the ID of the file.
     */
    function isOwner( $user, $file )
    {
        if ( !is_a( $user, "eZUser" ) )
            return false;

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZFileManager_File WHERE ID='$file'");
        $userID = $res[$db->fieldName( "UserID" )];
        if ( $userID == $user->id() )
            return true;

        return false;
    }


    /*!
      Returns the path and filename to the original virtualfile.

      If $relative is set to true the path is returned relative.
      Absolute is default.
    */
    function &filePath( $relative = false )
    {
        if ( $relative == true )
        {
            $path = "ezfilemanager/files/" . $this->FileName;
        }
        else
        {
            $path = "/ezfilemanager/files/" . $this->FileName;
        }

        return $path;
    }

    /*!
      Returns the size of the file.
    */

    function &fileSize()
    {
        $filepath =& $this->filePath( true );
        $size = eZFile::filesize( $filepath );

        return $size;
    }

    /*!
      Returns the size of the file in a shortened form useful for printing to the user,
      the returned value is an array with the filesize, the size as a shortened string
      and the unit. The keys used for fetching the various items in the array are:
      "size" - The full file size
      "size-string" - The shortened file size as a string
      "unit" - The unit for the shortened size, either B, KB, MB or GB
    */

    function &siFileSize()
    {
        $size = $this->fileSize();
        return eZFile::siFileSize( $size );
    }

    /*!
      Sets the virtual file name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the virtual file description.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the original virtual filename.
    */
    function setOriginalFileName( $value )
    {
        $this->OriginalFileName = $value;
    }

    /*!
      Sets the user of the file.
    */
    function setUser( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $this->UserID = $user->id();
        }
        else if ( is_numeric( $user ) )
        {
            $this->UserID = $user;
        }
    }

    /*!
      Makes a copy of the file and stores the file in the file manager.

    */
    function setFile( &$file )
    {
        if ( is_a( $file, "eZFile" ) )
        {
            if ( eZFile::file_exists( $this->filePath( true ) ) )
            {
                eZFile::unlink( $this->filePath( true ) );
            }

            $this->OriginalFileName = $file->name();

            $suffix = "";
            if ( preg_match( "\\.([a-z]+)$", $this->OriginalFileName, $regs ) )
            {
                // We got a suffix, make it lowercase and store it
                $suffix = strtolower( $regs[1] );
            }

            // the path to the catalogue

            // Copy the file since we support it directly
            $file->copy( "ezfilemanager/files/" . basename( $file->tmpName() ) . $postfix );

            $this->FileName = basename( $file->tmpName() ) . $postfix;

            $name = $file->name();

            $this->OriginalFileName =& $name;
            return true;
        }
        return false;
    }

    /*!
      Retuns the folder for this eZVirtualFile object.
    */
    function &folder( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $result = array();

        $query = ( "SELECT FolderID FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
        $db->array_query( $result, $query );

        foreach ( $result as $folder )
        {
            return $as_object ? new eZVirtualFolder( $folder[$db->fieldName( "FolderID" )] ) : $folder[$db->fieldName( "FolderID" )];
        }
    }


    /*!
      Removes the file from every folders.
    */
    function removeFolders()
    {
        $db =& eZDB::globalDatabase();

        $query = ( "DELETE FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
        $db->query( $query );
    }

    /*!
      Adds a pagview to the file.
    */
    function addPageView( $pageView )
    {
        if ( is_a( $pageView, "eZPageView" ) )
        {
            $db =& eZDB::globalDatabase();

            $pageViewID = $pageView->id();

            $db->lock( "eZFileManager_FilePageViewLink" );
            $nextID = $db->nextID( "eZFileManager_FilePageViewLink", "ID" );

            $query = ( "INSERT INTO eZFileManager_FilePageViewLink
                       ( ID, PageViewID, FileID )
                       VALUES ( '$nextID', '$this->ID', '$pageViewID' ) " );

            $result = $db->query( $query );
            $db->unlock();
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Checks if a file exists in a virtual directory
    */
    function fileExists( $dir, $file )
    {
        $parent = eZVirtualFolder::getByName( $dir );
        $ret = false;
        if ( $parent )
        {
            $directory = new eZVirtualFolder( $parent  );
            $ret = $directory->hasFile( $file );
        }
        return $ret;
    }

    var $ID;
    var $Name;
    var $Caption;
    var $Description;
    var $FileName;
    var $OriginalFileName;
    var $UserID;

}

?>