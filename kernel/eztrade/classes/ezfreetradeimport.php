<?
//
// $Id: ezfreetradeimport.php 6849 2001-09-03 11:13:38Z ce $
//
// ezfreetradeimport class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <12-Jul-2001 12:23:54 ce>
//
// Copyright (C) Christoffer A. Elo.  All rights reserved.
//

//!! ezfreetradeimport
//! ezfreetradeimport documentation.
/*!

  Example code:
  \code
  \endcode

*/
// include_once( "classes/ezdb.php" );
// include_once( "classes/ezmysqldb.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezproductcategory.php" );

class eZFreeTradeImport
{

    /*!
      constructor
    */
    function __construct()
    {
    }

    function importCategories( )
    {
        set_time_limit( 0 );
        $categories = $this->getCategoriesFromImport();
        $db =& eZDB::globalDatabase();

        if ( is_array ( $categories ) )
        {
            foreach( $categories as $importCategory )
            {
                $importID = $importCategory["ID"];
                $parentID = $importCategory["Parent"];
                $db->array_query( $ParentFromRemoteID, "SELECT ID FROM eZTrade_Category WHERE RemoteID='$parentID'" );

                if ( count ( $ParentFromRemoteID ) == 1 )
                    $parentID = $ParentFromRemoteID[0]["ID"];
                else
                    $parentID = 0;

                $category = new eZProductCategory();
                $category->setName( mysql_escape_string( $importCategory["Name"] ) );
                $category->setDescription( mysql_escape_string( $importCategory["Description"] ) );
                $category->setParent( mysql_escape_string( $parentID ) );
                $category->setRemoteID( mysql_escape_string( $importID ) );

                if ( $importCategory["Graphic"] )
                {
                    $imageFromFS = "freetrade/images/departments/" . $importCategory["Graphic"];
                    if ( file_exists( $imageFromFS ) )
                    {
                        $image = new eZImage();
                        $file = new eZPBImageFile();
                        if ( $file->getFile( $imageFromFS ) )
                        {
                            $image->setName( "Image" );
                            $image->setImage( $file );

                            $image->store();
                            $category->setImage( $image );
                        }
                    }
                }
                $category->store();
            }
            return true;
        }
        else
            return false;
    }

    function importItems( )
    {
        set_time_limit( 0 );
        $categories = $this->getItemsFromImport();

        $db =& eZDB::globalDatabase();
        if ( is_array ( $categories ) )
        {
            foreach( $categories as $category )
            {
                $depID = $category["Department"];
                $importID = "item-" . $category["ID"];
                $db->array_query( $ParentFromRemoteID, "SELECT ID FROM eZTrade_Category WHERE RemoteID='$depID'" );

                if ( count ( $ParentFromRemoteID ) == 1 )
                {
                    $parentID = $ParentFromRemoteID[0]["ID"];

                    $categoryObj = new eZProductCategory();
                    $categoryObj->setName( mysql_escape_string( strip_tags( $category["Name"] ) ) );
                    $categoryObj->setDescription( mysql_escape_string( strip_tags( $category["Description"] ) ) );
                    $categoryObj->setParent( mysql_escape_string( $parentID ) );
                    $categoryObj->setRemoteID( mysql_escape_string( $importID ) );
                    $categoryObj->store();

//                    print( "Added: ". $categoryObj->name() . "<br>" );
                }
            }
        }
    }

    function importProducts( )
    {
        set_time_limit( 0 );
        $products = $this->getProductsFromImport();

        $db =& eZDB::globalDatabase();
        if ( is_array ( $products ) )
        {
            $i=0;
            foreach( $products as $importProduct )
            {

                $product = new eZProduct();

                $product->setName( mysql_escape_string( $importProduct["Name"] ) );
//                $product->setDescription( mysql_escape_string( $importProduct["Description"] ) );
//                $product->setKeywords( mysql_escape_string( $importProduct["Keywords"] ) );
                $product->setRemoteID( $importProduct["ID"] );

                if ( $importProduct["SalePrice" ] != 0 )
                {
                    $product->setPrice( $importProduct["SalePrice" ] );
                }
                elseif ( $importProduct["ListPrice" ] )
                {
                    $price = ( ( $importProduct["ListPrice" ] * 10 ) / 100 );
                    $product->setPrice( $importProduct["ListPrice" ] - $price );
                }

                if ( $importProduct["Active"] == "Y" )
                    $product->setShowProduct( true );
                else
                    $product->setShowProduct( false );

                $product->store();

                /*
                if ( $importProduct["Graphic"] )
                {
                    $imageFromFS = "freetrade/images/items/" . $importProduct["Graphic"];
                    if ( file_exists( $imageFromFS ) )
                    {
                        $image = new eZImage();
                        $file = new eZPBImageFile();
                        if ( $file->getFile( $imageFromFS ) )
                        {
                            $image->setName( "Image" );
                            $image->setImage( $file );

                            $image->store();
                            $product->addImage( $image );
                            $product->setThumbnailImage( $image );
                            $product->setMainImage( $image );
                            $product->store();
                        }
                    }
                }
                */


                $parent = "item-" . $importProduct["Item"];
                $db->array_query( $CategoryIDFromRemoteID, "SELECT ID FROM eZTrade_Category WHERE RemoteID='$parent'" );

                $category = new eZProductCategory( $CategoryIDFromRemoteID[0][0] );

                if ( is_numeric( $category->id() ) )
                {
                    $category->addProduct( $product );
                    $product->setCategoryDefinition( $category );
                }

                $this->importOptions( $product );

//                print( "Lagt til produkt: " . $product->name() . "<br>" );
                $i++;

            }
//            print( $i );
            return true;
        }
        else
            return false;
    }

    function importOptions( $product )
    {
        if ( is_a( $product, "eZProduct" ) )
        {
            $productID = $product->id();

            $options = $this->getOptionsFromImport( $productID );

            if ( count ( $options ) > 0 )
            {
                if ( is_array( $options ) )
                {
                    foreach( $options as $importOption )
                    {
                        $option = new eZOption();
                        $option->setName( $importOption[6]["Name"] );
                        $option->store();

                        $product->addOption( $option );

                        $value = new eZOptionValue();
                        $value->setRemoteID( $importOption["ID"] );
                        $value->store();
                        $value->addDescription( mysql_escape_string( $importOption["Name"] ) );
                        $option->addValue( $value );



//                $quantityArray = $this->getQuantity( $importOption["ID"] );

                        //              $value->setTotalQuantity( $quantityArray["Available"] );

//                print( "ID: " . $value->id() . " - Price: ". $importOption["SalePrice" ] . "<br>");
                    }
                }
            }
        }
    }

    function getOptionsFromImport( $id )
    {
        $this->dbInit();

        if ( is_numeric( $id ) )
        {
            $db =& eZDB::globalDatabase();
            $db->array_query( $RemoteIDFromProductID, "SELECT RemoteID FROM eZTrade_Product WHERE ID='$id'" );

            $remoteID = $RemoteIDFromProductID[0][0];

            $this->dbImport->array_query( $array, "select variation.* from variation, sku_variation WHERE sku_variation.Variation = variation.ID AND sku_variation.SKU='$remoteID'" );

            for( $i=0; $i<count( $array ); $i++ )
            {
                if ( is_numeric( $array[$i]["Attribute"] ) )
                {
                    $att = array();
                    $id = $array[$i]["Attribute"];
                    $this->dbImport->array_query( $att, "select * from attribute where ID='$id'" );
                    array_push( $array[$i], $att[0] );
                }

            }
            return $array;
        }
        else
            $this->dbImport->array_query( $array, "SELECT * FROM sku" );

        $this->dbImport->close();

        return $array;
    }

    function getCategoriesFromImport()
    {
        $this->dbInit();
        $this->dbImport->array_query( $array, "SELECT * FROM department ORDER BY Parent" );


        $this->dbImport->close();

        return $array;
    }

    function getProductsFromImport()
    {
        $this->dbInit();
//        $this->dbImport->array_query( $array, "SELECT * FROM item" );
        $this->dbImport->array_query( $array, "SELECT * FROM sku" );

        $this->dbImport->close();

        return $array;
    }

    function getItemsFromImport()
    {
        $this->dbInit();
        $this->dbImport->array_query( $array, "SELECT * FROM item" );

        $this->dbImport->close();

        return $array;
    }

    function getQuantity( $id )
    {
        $this->dbInit();

        if ( is_numeric( $id ) )
        {
            $this->dbImport->array_query( $array, "SELECT * FROM inventory WHERE SKU='$id'" );

            $this->dbImport->close();

            return $array[0];
        }
        else
            return false;
    }

    function makeOption()
    {
        $db =& eZDB::globalDatabase();
        $this->dbInit();

        $this->dbImport->array_query( $attribute, "SELECT * FROM attribute" );

        foreach( $attribute as $att )
        {
            $option = new eZOption();

            $option->setName( $att["Name"] );
            $option->setDescription( $att["Description"] );
            $option->setRemoteID( $att["ID"] );
            $option->store();
         }
    }

    function makeLinks()
    {
        $this->dbInit();
        $this->dbImport->array_query( $links, "SELECT * FROM sku_variation" );
        foreach ( $links as $link )
        {
            $product = eZProduct::getByRemoteID( $link["SKU"] );
            $optionValue = eZOptionValue::getByRemoteID( $link["Variation"] );
            if ( ( $product ) and ( $optionValue ) )
            {
                $option = $optionValue->option();
                $product->addOption( $option );
            }

            if ( !$product )
                print( "Product dosen't exists!: " . $link["SKU"] . "\n" );
            if ( !$option )
                print( "Option dosen't exists!: " . $link["Variation"] . "\n" );

        }
    }

    function makeValue( )
    {

        $db =& eZDB::globalDatabase();
        $this->dbInit();
        $this->dbImport->array_query( $optionValues, "SELECT * FROM variation" );

        foreach ( $optionValues as $value )
        {
            if ( $value["Attribute"] != 0 )
            {
                $value1 = new eZOptionValue( );
                $value1->setRemoteID( $value["ID"] );
                $value1->store();
                $value1->addDescription( $value["Name"] );

                $option = eZOption::getByRemoteID( $value["Attribute"] );
                $option->addValue( $value1 );
            }
        }
    }

    function setDatabaseImport( $host="localhost", $username="import", $password="import", $database="import" )
    {
        $this->Host = $host;
        $this->UserName = $username;
        $this->Password = $password;
        $this->Database = $database;
    }

    function dbInit()
    {
        $this->dbImport = new eZMySQLDB( $this->Host, $this->UserName, $this->Password, $this->Database );
    }

    var $Host;
    var $UserName;
    var $Password;
    var $Database;
    var $dbImport;
}

?>