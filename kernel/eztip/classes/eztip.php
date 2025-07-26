<?php
// 
// $Id: eztip.php,v 1.28.2.1 2001/11/23 09:39:00 br Exp $
//
// Definition of eZTip class
//
// Created on: <22-Nov-2000 20:01:05 bf>
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

//!! eZTip
//! eZTip handles tips.
/*!

  \sa eZTipCategory  
*/

/*!TODO

 */

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZTip
{
    /*!
      Constructs a new eZTip object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        $this->ImageID = 0;
        $this->IsActive = 1;
        $this->UseHTML = 0;
        $this->ViewStartDate = 0;
        $this->HTMLBanner = "";
        $this->Name = "";
        $this->Description = "";
        $this->URL = "";
        $this->ViewRule = "clicks"; // default view rule is clicks.

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
        $res = $db->query( "UPDATE eZTip_View
		 					SET ViewOffsetCount = 1" );
        
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $url = $db->escapeString( $this->URL );
        $htmlbanner = $db->escapeString( $this->HTMLBanner );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTip_Tip" );

            $nextID = $db->nextID( "eZTip_Tip", "ID" );
            
            $res = $db->query( "INSERT INTO eZTip_Tip
                         ( ID,  
                           Name,
                           Description,
                           ImageID,
                           IsActive,
                           URL,
                           HTMLBanner,
                           UseHTML )
                          VALUES
                          ( '$nextID',
                            '$name',
                            '$description',
                            '$this->ImageID',
                            '$this->IsActive',
                            '$url',
                            '$htmlbanner',
                            '$this->UseHTML' )
                                 " );

			
			$this->ID = $nextID;

            $this->addPageView( );
            $db->unlock();
        }
        else
        {
             $res = $db->query( "UPDATE eZTip_Tip SET
		                         Name='$name',
		                         Description='$description',
                                 ImageID='$this->ImageID',
                                 IsActive='$this->IsActive',
                                 URL='$url',
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
            $db->array_query( $tip_array, "SELECT * FROM eZTip_Tip WHERE ID='$id'" );
            if ( count( $tip_array ) > 1 )
            {
                die( "Error: Tip's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $tip_array ) == 1 )
            {
                $this->ID =& $tip_array[0][$db->fieldName("ID")];
                $this->Name =& $tip_array[0][$db->fieldName("Name")];
                $this->Description =& $tip_array[0][$db->fieldName("Description")];
                $this->IsActive =& $tip_array[0][$db->fieldName("IsActive")];
                $this->URL =& $tip_array[0][$db->fieldName("URL")];
                $this->ImageID =& $tip_array[0][$db->fieldName("ImageID")];
                $this->HTMLBanner =& $tip_array[0][$db->fieldName("HTMLBanner")];
                $this->UseHTML =& $tip_array[0][$db->fieldName("UseHTML")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZTip object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZTip_View WHERE TipID='$this->ID'" );            
            $db->query( "DELETE FROM eZTip_Click WHERE TipID='$this->ID'" );            

            $db->query( "DELETE FROM eZTip_TipCategoryLink WHERE TipID='$this->ID'" );            
            $db->query( "DELETE FROM eZTip_Tip WHERE ID='$this->ID'" );
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

      The categories are returned as an array of eZTipCategory objects.
    */
    function &categories()
    {
        $db =& eZDB::globalDatabase();

        $ret = array();
        $db->array_query( $category_array, "SELECT * FROM
                                                       eZTip_TipCategoryLink
                                                       WHERE TipID='$this->ID'" );
       foreach ( $category_array as $category )
       {           
           $ret[] = new eZTipCategory( $category[$db->fieldName("CategoryID")] );
       }

       return $ret;
    }
    
    /*!
      Removes every category assignments from the current ad.
    */
    function removeFromCategories()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZTip_TipCategoryLink
                                WHERE TipID='$this->ID'" );        
    }

    /*!
      Sets the banner ad image.
    */
    function setImage( $value )
    {
        if ( get_class( $value ) == "ezimage" )
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
        if ( get_class( $value ) == "ezimage" )
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
      Tipds a pageview to the banner.
    */
    function addPageView( )
    {
        $db =& eZDB::globalDatabase();

        $db->begin();
 
        $dateTime = new eZDateTime();
        $date = $dateTime->timeStamp( true );
       
        $db->lock( "eZTip_View" );
            
        $db->array_query( $view_result, "SELECT * FROM
                                         eZTip_View
                                         WHERE TipID='$this->ID'" );        
        if ( count( $view_result ) == 0 )
        {

            $nextID = $db->nextID( "eZTip_View", "ID" );

            // get the lowest
            $db->array_query( $view_offset, "SELECT * FROM
                                         eZTip_View
                                         ORDER BY ViewOffsetCount" );

            // set all offsets to 1.
            $db->query( "UPDATE eZTip_View
                         SET ViewOffsetCount='1'" );
            
            if ( count( $view_offset ) > 0 )
                $offs = $view_offset[0][$db->fieldName("ViewOffsetCount")];
            else
                $offs = 1;
 
            $dateTime = new eZDateTime();
            $timeStamp= $dateTime->timeStamp( true );
 
            $res = $db->query( "INSERT INTO eZTip_View
                         ( ID,   
                           TipID,
                           ViewCount,
                           ViewOffsetCount,
                           Date )
                         VALUES
                         ( '$nextID',
                           '$this->ID',
                           '1',
                           '$offs',
                           '$timeStamp' )" );
        }
        else
        {
            $query = "UPDATE eZTip_View SET 
                                         ViewCount=(ViewCount + 1),
                                         ViewOffsetCount=(ViewOffsetCount + 1)
                                         WHERE TipID='$this->ID'";

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
                                         eZTip_View
                                         WHERE TipID='$this->ID'" );

       return $view_result[0][$db->fieldName("ViewCount")];
    }
  
    /*!
      Returns the total number of times the banner has been clicked.
    */
    function clickCount( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $click_result, "SELECT count(*) AS Count FROM
                                                       eZTip_Click
                                                       WHERE TipID='$this->ID'" );

        return $click_result[0][$db->fieldName("Count")];
    }
    

    var $ID;
    var $Name;
    var $Description;
    var $ImageID;
    var $ViewStartDate;
    var $HTMLBanner;
    var $UseHTML;

    /// Indicates if the banner is active or not
    var $IsActive;

    /// Indicates if the banner should be viewed by date or by clicks.
    var $ViewRule;

    /// The URL to go to when the banner is clicked
    var $URL;
    var $ClickPrice;
    var $ViewPrice;
    
}

?>