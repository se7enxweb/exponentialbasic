<?php
//
// $Id: ezimagevariation.php 9854 2003-06-12 14:33:53Z br $
//
// Definition of eZImageVariation class
//
// Created on: <21-Sep-2000 17:28:57 bf>
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
        $db =& eZDB::globalDatabase();
        $db->begin();

        $db->lock( "eZImageCatalogue_ImageVariation" );

        $this->ID = $db->nextID( "eZImageCatalogue_ImageVariation", "ID" );

        $query = "INSERT INTO eZImageCatalogue_ImageVariation
                                 ( ID, ImageID, VariationGroupID, Width, Height, ImagePath, Modification ) VALUES
                                 ( '$this->ID',
                                   '$this->ImageID',
                                   '$this->VariationGroupID',
                                   '$this->Width',
                                   '$this->Height',
                                   '$this->ImagePath',
                                   '$this->Modification' )";

        $res = $db->query( $query );
        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $image_variation_array, "SELECT * FROM eZImageCatalogue_ImageVariation WHERE ID='$id'" );
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
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZImageCatalogue_ImageVariation WHERE ID='$this->ID'" );
        }

        // Delete from the filesystem
        if ( eZFile::file_exists( $this->imagePath( true ) ) )
        {
            eZFile::unlink( $this->imagePath( true ) );
        }
    }


    /*!
      Fetches the object information from the database.
    */
    function getByGroupAndImage( $groupID, $imageID, $modification )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $groupID != "" )
        {
            $db->array_query( $image_variation_array, "SELECT * FROM eZImageCatalogue_ImageVariation
            WHERE VariationGroupID='$groupID'
            AND ImageID='$imageID' AND Modification='$modification'", array( "Limit" => 1, "Offset" => 0 ) );

            if ( count( $image_variation_array ) > 0 )
            {
                $this->ID =& $image_variation_array[0][$db->fieldName("ID")];
                $this->ImageID =& $image_variation_array[0][$db->fieldName("ImageID")];
                $this->VariationGroupID =& $image_variation_array[0][$db->fieldName("VariationGroupID")];
                $this->ImagePath =& $image_variation_array[0][$db->fieldName("ImagePath")];
                $this->Width =& $image_variation_array[0][$db->fieldName("Width")];
                $this->Height =& $image_variation_array[0][$db->fieldName("Height")];
                $this->Modification =& $image_variation_array[0][$db->fieldName("Modification")];

                $ret = true;
            }

            if ( !eZFile::file_exists( $this->ImagePath ) or !is_file( $this->ImagePath ) )
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

                $imageFile = new eZImageFile();
                $imageFile->getFile( $image->filePath( true ) );
                $imageFile->setType( "image/jpeg" );

                $info = eZImageFile::information( $image->originalFileName(), true );
                $suffix = $info["suffix"];
                $postfix = $info["dot-suffix"];
                $imageFile->setType( $info["image-type"] );

                $dest = "kernel/ezimagecatalogue/catalogue/variations/" . $image->id() . "-"
                      . $variationGroup->width() . "x". $variationGroup->height() . $modification . $postfix;

                $result = $imageFile->scaleCopy( $dest, $variationGroup->width(), $variationGroup->height(), $convertToGray );

                if ( !is_bool( $result ) and $result == "locked" )
                {
                    if ( $variation->getByGroupAndImage( $variationGroup->id(), $image->id(), $modification ) )
                    {
                        $ret =& $variation;
                    }
                    else {
                        if (eZFile::file_exists($dest) or is_file($dest))
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
                    if ( !eZFile::file_exists( $dest ) or !is_file( $dest ) )
                        return $allow_error ? false : eZImageVariation::createErrorImage();
                    $size = GetImageSize( $dest );
                    if ( !$size )
                        return $allow_error ? fales : eZImageVariation::createErrorImage();

                    $variation->setWidth( $size[0] );
                    $variation->setHeight( $size[1] );
                    $variation->setImagePath( $dest );
                    $variation->setImageID(  $image->id() );
                    $variation->setVariationGroupID(  $variationGroup->id() );
                    $variation->setModification( $modification );


                    $variation->store();

                    $ret =& $variation;
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