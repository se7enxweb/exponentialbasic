<?php
//
// $Id: ezad.php 8615 2001-11-23 09:39:00Z br $
//
// Definition of eZAd class
//
// Created on: <22-Nov-2000 20:01:05 bf>
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

//!! eZAd
//! eZAd handles banner ads.
/*!

  \sa eZAdCategory
*/

/*!TODO

 */

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZAd
{
    /*!
      Constructs a new eZAd object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        $this->ImageID = 0;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a product to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $url = $db->escapeString( $this->URL );
        $htmlbanner = $db->escapeString( $this->HTMLBanner );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZAd_Ad" );

            $nextID = $db->nextID( "eZAd_Ad", "ID" );
            $queryText = "INSERT INTO eZAd_Ad
                         ( ID,
                           Name,
                           Description,
                           ImageID,
                           IsActive,
                           URL,
                           ClickPrice,
                           ViewPrice,
                           HTMLBanner,
                           UseHTML )
                          VALUES
                          ( '$nextID',
                            '$name',
                            '$description',
                            '$this->ImageID',
                            '$this->IsActive',
                            '$url',
                            '$this->ClickPrice',
                            '$this->ViewPrice',
                            '$htmlbanner',
                            '$this->UseHTML' )
                                 ";

            $res = $db->query( $queryText );

			      $this->ID = $nextID;

            $this->addPageView();
            $db->unlock();
        }
        else
        {
             $res = $db->query( "UPDATE eZAd_Ad SET
		                         Name='$name',
		                         Description='$description',
                                 ImageID='$this->ImageID',
                                 IsActive='$this->IsActive',
                                 URL='$url',
                                 ClickPrice='$this->ClickPrice',
                                 ViewPrice='$this->ViewPrice',
                                 HTMLBanner='$htmlbanner',
                                 UseHTML='$this->UseHTML'
                                 WHERE ID='$this->ID'
                                 " );
        }


        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();


        return true;
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
            $db->array_query( $ad_array, "SELECT * FROM eZAd_Ad WHERE ID='$id'" );
            if ( count( $ad_array ) > 1 )
            {
                die( "Error: Ad's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $ad_array ) == 1 )
            {
                $this->ID =& $ad_array[0][$db->fieldName("ID")];
                $this->Name =& $ad_array[0][$db->fieldName("Name")];
                $this->Description =& $ad_array[0][$db->fieldName("Description")];
                $this->IsActive =& $ad_array[0][$db->fieldName("IsActive")];
                $this->URL =& $ad_array[0][$db->fieldName("URL")];
                $this->ImageID =& $ad_array[0][$db->fieldName("ImageID")];
                $this->ViewPrice =& $ad_array[0][$db->fieldName("ViewPrice")];
                $this->ClickPrice =& $ad_array[0][$db->fieldName("ClickPrice")];
                $this->HTMLBanner =& $ad_array[0][$db->fieldName("HTMLBanner")];
                $this->UseHTML =& $ad_array[0][$db->fieldName("UseHTML")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZAd object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZAd_View WHERE AdID='$this->ID'" );
            $db->query( "DELETE FROM eZAd_Click WHERE AdID='$this->ID'" );

            $db->query( "DELETE FROM eZAd_AdCategoryLink WHERE AdID='$this->ID'" );
            $db->query( "DELETE FROM eZAd_Ad WHERE ID='$this->ID'" );
        }

        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the ad's name.
    */
    function &name()
    {
       return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the ad's description.
    */
    function &description()
    {
       return htmlspecialchars( $this->Description );
    }

    /*!
      Returns the ad's url.
    */
    function &url()
    {
       return $this->URL;
    }

    /*!
      Returns the ad's click price.
    */
    function &clickPrice()
    {
       return $this->ClickPrice;
    }

    /*!
      Returns the ad's view price.
    */
    function &viewPrice()
    {
       return $this->ViewPrice;
    }

    /*!
      Returns true if the ad is active false if not.
    */
    function isActive()
    {
       $ret = false;

       if ( $this->IsActive == "1" )
       {
           $ret = true;
       }
       return $ret;
    }

    /*!
      Returns true if the ad is set to use HTML banners.
    */
    function useHTML()
    {
       $ret = false;

       if ( $this->UseHTML == 1 )
       {
           $ret = true;
       }
       return $ret;
    }

    /*!
      Returns the ad's HTML banner.
    */
    function &htmlBanner()
    {
        return $this->HTMLBanner;
    }

    /*!
      Returns the view start date.
    */
    function &viewStartDate()
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->ViewStartDate );

       return $dateTime;
    }

    /*!
      Returns the view stop date.
    */
    function &viewStopDate()
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->ViewStopDate );

       return $dateTime;
    }

    /*!
      Sets the ad's name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the ad's description.
    */
    function setDescription( $value )
    {
       $this->Description = $value;
    }

    /*!
      Sets the URL which the ad should link to.
    */
    function setURL( $value )
    {
       $this->URL = $value;
    }

    /*!
      Sets the click price.
    */
    function setClickPrice( $value )
    {
       $this->ClickPrice = $value;
       setType( $this->ClickPrice, "double" );
    }

    /*!
      Sets the view price.
    */
    function setViewPrice( $value )
    {
       $this->ViewPrice = $value;
       setType( $this->ViewPrice, "double" );
    }

    /*!
     Sets the ad to active or not.
    */
    function setIsActive( $value )
    {
       if ( $value == true )
       {
           $this->IsActive = 1;
       }
       else
       {
           $this->IsActive = 0;
       }
    }

    /*!
      Sets the HTML banner code.
    */
    function setHTMLBanner( $value )
    {
        $this->HTMLBanner = $value;
    }

    /*!
     Sets the ad to use html banner or not.
    */
    function setUseHTML( $value )
    {
       if ( $value == true )
       {
           $this->UseHTML = 1;
       }
       else
       {
           $this->UseHTML = 0;
       }
    }

    /*!
      Returns the categrories an ad is assigned to.

      The categories are returned as an array of eZAdCategory objects.
    */
    function &categories()
    {
        $db =& eZDB::globalDatabase();

        $ret = array();
        $db->array_query( $category_array, "SELECT * FROM
                                                       eZAd_AdCategoryLink
                                                       WHERE AdID='$this->ID'" );

       foreach ( $category_array as $category )
       {
           $ret[] = new eZAdCategory( $category[$db->fieldName("CategoryID")] );
       }

       return $ret;
    }

    /*!
      Removes every category assignments from the current ad.
    */
    function removeFromCategories()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZAd_AdCategoryLink
                                WHERE AdID='$this->ID'" );
    }

    /*!
      Sets the banner ad image.
    */
    function setImage( $value )
    {
        if ( is_a( $value, "eZImage" ) )
        {
            $db =& eZDB::globalDatabase();

            $this->ImageID = $value->id();
        }
    }

    /*!
      Deletes the banner ad image.

      NOTE: the image also gets deleted from the image catalogue.
    */
    function deleteImage( $value )
    {
        if ( is_a( $value, "eZImage" ) )
        {
            $db =& eZDB::globalDatabase();

            $imageID = $value->id();

            $image = new eZImage( $imageID );
            $image->delete();
        }
    }

    /*!
      Returns the banner ad image.
    */
    function image()
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        $img = new eZImage( );

        if ( $img->get( $this->ImageID ) )
        {
            $ret = $img;
        }

        return $ret;
    }

    /*!
      Adds a pageview to the banner.
    */
    function addPageView( )
    {
        $db =& eZDB::globalDatabase();

        $db->begin();

        $date = new eZDate();
        $date = $date->timestamp( true );
        $db->lock( "eZAd_View" );

        $db->array_query( $view_result, "SELECT * FROM
                                         eZAd_View
                                         WHERE AdID='$this->ID'" );
        if ( count( $view_result ) == 0 )
        {

            $nextID = $db->nextID( "eZAd_View", "ID" );

            // get the lowest
            $db->array_query( $view_offset, "SELECT * FROM
                                         eZAd_View
                                         ORDER BY ViewOffsetCount" );

            // set all offsets to 1.
            $db->query( "UPDATE eZAd_View
                         SET ViewOffsetCount='1'" );

            if ( count( $view_offset ) > 0 )
                $offs = $view_offset[0][$db->fieldName("ViewOffsetCount")];
            else
                $offs = 1;

            $timeStamp =& (new eZDateTime())->timeStamp( true );

            $res = $db->query( "INSERT INTO eZAd_View
                         ( ID,
                           AdID,
                           ViewCount,
                           ViewOffsetCount,
                           ViewPrice,
                           Date )
                         VALUES
                         ( '$nextID',
                           '$this->ID',
                           '1',
                           '$offs',
                           '$this->ViewPrice',
                           '$timeStamp' )" );
        }
        else
        {
            $query = "UPDATE eZAd_View SET
                                         ViewCount=(ViewCount + 1),
                                         ViewOffsetCount=(ViewOffsetCount + 1),
                                         ViewPrice=(ViewPrice + $this->ViewPrice)
                                         WHERE AdID='$this->ID'";

            $res = $db->query( $query );
        }

        $db->unlock();

        if ( $res == false )
        {
            $db->rollback( );
        }
        else
        {
            $db->commit();
        }

    }

    /*!
      Returns the total number of times the banner has been viewed.
    */
    function viewCount( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $view_result, "SELECT sum(ViewCount) as ViewCount FROM
                                         eZAd_View
                                         WHERE AdID='$this->ID'" );

       return $view_result[0][$db->fieldName("ViewCount")];
    }

    /*!
      Returns the total number of times the banner has been clicked.
    */
    function clickCount( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $click_result, "SELECT count(*) AS Count FROM
                                                       eZAd_Click
                                                       WHERE AdID='$this->ID'" );

        return $click_result[0][$db->fieldName("Count")];
    }


    /*!
      Returns the banner's total view revenue.
    */
    function totalViewRevenue( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $view_result, "SELECT SUM(ViewPrice) AS Revenue
                                                    FROM eZAd_View WHERE AdID='$this->ID'" );

        return $view_result[0][$db->fieldName("Revenue")];
    }

    /*!
      Returns the banner's total click revenue.
    */
    function totalClickRevenue( )
    {
        $db =& eZDB::globalDatabase();

//        print( "SELECT SUM(ClickPrice) AS Revenue FROM eZAd_Click WHERE AdID='$this->ID'" );

        $db->array_query( $click_result, "SELECT SUM(ClickPrice) AS Revenue FROM eZAd_Click WHERE AdID='$this->ID'" );

       return $click_result[0][$db->fieldName("Revenue")];
    }

    var $ID;
    var $Name;
    var $Description;
    var $ImageID;
    var $URL;
    var $ViewStartDate;
    var $ClickPrice;
    var $ViewPrice;

    var $HTMLBanner;
    var $UseHTML;

    /// Indicates if the banner is active or not
    var $IsActive;

    /// Indicates if the banner should be viewed by date or by clicks.
    var $ViewRule;

    /// The URL to go to when the banner is clicked
    var $URL;

}

?>