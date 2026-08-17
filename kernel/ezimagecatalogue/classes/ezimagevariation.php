<?php
//
// $Id: ezimagevariation.php 9854 2003-06-12 14:33:53Z br $
//
// Definition of eZImageVariation class
//
// Created on: <21-Sep-2000 17:28:57 bf>
//
// This source file is part of Exponential Basic, publishing software.
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
//! The eZImageVariation class hadles images variations.
/*!

  \sa eZImage eZImageVariationGroup
*/

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezimagefile.php" );
//  // include_once( "classes/ezimage.php" );
//  // include_once( "classes/ezimagevariationgroup.php" );

class eZImageVariation
{
    /** Pre-fetched variation rows keyed by "groupID|imageID|modification". null = confirmed absent. */
    static $cache = [];

    /*!
      Batch-prefetches variation records for a list of image IDs sharing the same group.
      Reduces N individual SELECTs to one IN() query.
    */
    static function prefetchByGroup( $groupID, array $imageIDs, $modification = '' )
    {
        if ( empty( $imageIDs ) ) return;
        $db = eZDB::globalDatabase();
        $idList = implode( ',', array_map( 'intval', $imageIDs ) );
        $mod    = $db->escapeString( $modification );
        $rows   = [];
        $db->array_query( $rows, "SELECT * FROM eZImageCatalogue_ImageVariation
            WHERE VariationGroupID='$groupID' AND ImageID IN ($idList) AND Modification='$mod'" );
        foreach ( $rows as $row )
        {
            $key = $groupID . '|' . $row[$db->fieldName( 'ImageID' )] . '|' . $modification;
            self::$cache[$key] = $row;
        }
        // Mark all requested IDs as resolved so getByGroupAndImage skips DB on miss too
        foreach ( $imageIDs as $imageID )
        {
            $key = $groupID . '|' . $imageID . '|' . $modification;
            if ( !isset( self::$cache[$key] ) )
                self::$cache[$key] = null; // confirmed absent
        }
    }

    /*!
      Constructs a new eZImageVariation object.
    */
    function __construct( $id="" )
    {
      $this->ImageID = 0;
      $this->VariationGroupID = 0;
      $this->Width = 0;
      $this->Height = 0;
      $this->ImagePath = "";
      $this->Modification = "";

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZImageVariation object to the database.
    */
    function store()
    {
        $db = eZDB::globalDatabase();
        $db->begin();

        $db->lock( "eZImageCatalogue_ImageVariation" );

        $this->ID = (int) $db->nextID( "eZImageCatalogue_ImageVariation", "ID" );

        $existing = array();
        $db->array_query( $existing, "SELECT ID FROM eZImageCatalogue_ImageVariation WHERE ID=" . $this->ID );

        $res = false;
        if ( count( $existing ) === 0 )
        {
            $id = $this->ID;
            $imageID = (int) $this->ImageID;
            $variationGroupID = (int) $this->VariationGroupID;
            $width = (int) $this->Width;
            $height = (int) $this->Height;
            $imagePath = $db->escapeString( $this->ImagePath );
            $modification = $db->escapeString( $this->Modification );

            $query = "INSERT INTO eZImageCatalogue_ImageVariation
                                 ( ID, ImageID, VariationGroupID, Width, Height, ImagePath, Modification ) VALUES
                                 ( '$id',
                                   '$imageID',
                                   '$variationGroupID',
                                   '$width',
                                   '$height',
                                   '$imagePath',
                                   '$modification' )";
            $res = $db->query( $query );
        }
        $db->unlock();

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();

        return $res;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db = eZDB::globalDatabase();

        if ( $id != "" )
        {
            $query = "SELECT * FROM eZImageCatalogue_ImageVariation WHERE ID='$id'";

            $db->array_query( $image_variation_array, $query );
            if ( count( $image_variation_array ) > 1 )
            {
                print( "Error: ImageVariations's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $image_variation_array ) == 1 )
            {
                $this->ID =& $image_variation_array[0][$db->fieldName("ID")];
                $this->ImageID =& $image_variation_array[0][$db->fieldName("ImageID")];
                $this->VariationGroupID =& $image_variation_array[0][$db->fieldName("VariationGroupID")];
                $this->ImagePath =& $image_variation_array[0][$db->fieldName("ImagePath")];
                $this->Width =& $image_variation_array[0][$db->fieldName("Width")];
                $this->Height =& $image_variation_array[0][$db->fieldName("Height")];
                $this->Modification =& $image_variation_array[0][$db->fieldName("Modification")];
            }
        }
    }

    /*!
      Delete the eZImageVariation object from the database and the filesystem.
    */
    function delete()
    {
        $db = eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZImageCatalogue_ImageVariation WHERE ID='$this->ID'" );
        }

        // Delete from the filesystem
        if ( file_exists( $this->imagePath( true ) ) )
        {
            eZPBFile::unlink( $this->imagePath( true ) );
        }
    }


    /*!
      Fetches the object information from the database.
    */
    function getByGroupAndImage( $groupID, $imageID, $modification )
    {
        // Fast path: use pre-fetched cache populated by prefetchByGroup()
        $key = "$groupID|$imageID|$modification";
        if ( array_key_exists( $key, self::$cache ) )
        {
            $row = self::$cache[$key];
            if ( $row === null ) return false; // confirmed absent
            $db = eZDB::globalDatabase();
            $this->ID             = $row[$db->fieldName( "ID" )];
            $this->ImageID        = $row[$db->fieldName( "ImageID" )];
            $this->VariationGroupID = $row[$db->fieldName( "VariationGroupID" )];
            $this->ImagePath      = $row[$db->fieldName( "ImagePath" )];
            $this->Width          = $row[$db->fieldName( "Width" )];
            $this->Height         = $row[$db->fieldName( "Height" )];
            $this->Modification   = $row[$db->fieldName( "Modification" )];
            if ( !file_exists( $this->ImagePath ) or !is_file( $this->ImagePath ) )
                return false;
            return true;
        }

        $db = eZDB::globalDatabase();
        $ret = false;

        if ( $groupID != "" )
        {
            $db->array_query( $image_variation_array, "SELECT * FROM eZImageCatalogue_ImageVariation
            WHERE VariationGroupID='$groupID'
            AND ImageID='$imageID' AND Modification='$modification'", array( "Limit" => 1, "Offset" => 0 ) );

            if ( count( $image_variation_array ) > 0 )
            {
                $this->ID             =& $image_variation_array[0][$db->fieldName("ID")];
                $this->ImageID        =& $image_variation_array[0][$db->fieldName("ImageID")];
                $this->VariationGroupID =& $image_variation_array[0][$db->fieldName("VariationGroupID")];
                $this->ImagePath      =& $image_variation_array[0][$db->fieldName("ImagePath")];
                $this->Width          =& $image_variation_array[0][$db->fieldName("Width")];
                $this->Height         =& $image_variation_array[0][$db->fieldName("Height")];
                $this->Modification   =& $image_variation_array[0][$db->fieldName("Modification")];
                self::$cache[$key]   = $image_variation_array[0]; // store for future calls
                $ret = true;
            }
            else
            {
                self::$cache[$key] = null; // cache confirmed absent
            }

            if ( !file_exists( $this->ImagePath ) or !is_file( $this->ImagePath ) )
            {
                $ret = false;
            }
        }

        return $ret;
    }

    /*!
      Returns the variation if the variation exists, if it does not exist it is created.

      False is returned if the variation could not be created.
    */
    function requestVariation( &$image, &$variationGroup, $convertToGray = false, $allow_error = false )
    {
        $ret = false;

        if ( is_a( $image, "eZImage" ) && is_a( $variationGroup, "eZImageVariationGroup" ) )
        {
            $variation = new eZImageVariation();

            $modification = "";
            if ( $convertToGray == true )
                $modification .= "gray";

            if ( $variation->getByGroupAndImage( $variationGroup->id(), $image->id(), $modification ) == true )
            {
                $ret =& $variation;
            }
            else
            {
                if ( !$image->fileExists( true ) )
                    return $allow_error ? false : eZImageVariation::createErrorImage();

                $imageFile = new eZPBImageFile();
                $imageFile->getFile( $image->filePath( true ) );
                $imageFile->setType( "image/jpeg" );

                $info = eZPBImageFile::information( $image->originalFileName(), true );
                $suffix = $info["suffix"];
                $postfix = $info["dot-suffix"];
                $imageFile->setType( $info["image-type"] );

                $dest = "var/site/storage/ezimagecatalogue/variations/" . $image->id() . "-"
                      . $variationGroup->width() . "x". $variationGroup->height() . $modification . $postfix;

                $result = $imageFile->scaleCopy( $dest, $variationGroup->width(), $variationGroup->height(), $convertToGray );

                if ( !is_bool( $result ) and $result == "locked" )
                {
                    if ( $variation->getByGroupAndImage( $variationGroup->id(), $image->id(), $modification ) )
                    {
                        $ret =& $variation;
                    }
                    else {
                        if (file_exists($dest) or is_file($dest))
                        {
                            $variation->setImagePath($dest);
                            $variation->store();
                            $ret =& $variation;
                        }
                        else {
                            return $allow_error ? false : eZImageVariation::createErrorImage();
                            print( "<br><b>Timeout when retrieveing variation</b><br>" );
                        }
                    }
                }
                else if ( $result )
                {
                    if ( !file_exists( $dest ) or !is_file( $dest ) )
                        return $allow_error ? false : eZImageVariation::createErrorImage();
                    $size = GetImageSize( $dest );
                    if ( !$size )
                        return $allow_error ? false : eZImageVariation::createErrorImage();

                    $variation->setWidth( $size[0] );
                    $variation->setHeight( $size[1] );
                    $variation->setImagePath( $dest );
                    $variation->setImageID(  $image->id() );
                    $variation->setVariationGroupID(  $variationGroup->id() );
                    $variation->setModification( $modification );

                    $variation->store();

                    $ret = $variation;
                }
                else
                    return $allow_error ? false : eZImageVariation::createErrorImage();
            }
        }

        return $ret;
    }

    /*!
      Returns the ImageID
    */
    function imageID()
    {
       return $this->ImageID;
    }

    /*!
      Returns the VariationGroupID
    */
    function variationGroupID()
    {
       return $this->VariationGroupID;
    }

    /*!
      Returns the variation path
    */
    function imagePath()
    {
       return $this->ImagePath;
    }

    /*!
      Returns the image width
    */
    function width()
    {
       return $this->Width;
    }

    /*!
      Returns the image height
    */
    function height()
    {
       return $this->Height;
    }

    /*!
      Returns the variations id
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Sets the ImageID
    */
    function setImageID( $value )
    {
       $this->ImageID = $value;
    }

    /*!
      Sets the VariationGroupID
    */
    function setVariationGroupID( $value )
    {
       $this->VariationGroupID = $value;
    }

    /*!
      Sets the image path
    */
    function setImagePath( $value )
    {
       $this->ImagePath = $value;
    }

    /*!
      Sets the width
    */
    function setWidth( $value )
    {
       $this->Width = $value;
    }

    /*!
      Sets the height
    */
    function setHeight( $value )
    {
       $this->Height = $value;
    }

    /*!
      Sets the image modification information, e.g. grayscale version.
    */
    function setModification( $value )
    {
       $this->Modification = $value;
    }


    /*!
      Function which displays an error message, used if the variation could not be created.
    */
    function createErrorImage()
    {
        $imageVar = new eZImageVariation();
        $imageVar->setImagePath( "kernel/ezimagecatalogue/admin/images/failedimage.gif" );
        $imageVar->ImageID = -1;
        $imageVar->setWidth( 120 );
        $imageVar->setHeight( 40 );
        return $imageVar;
    }

    var $ID;
    var $ImageID;
    var $VariationGroupID;
    var $ImagePath;
    var $Width;
    var $Height;
    var $Modification;
}

?>