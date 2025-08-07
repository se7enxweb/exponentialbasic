<?php
// 
// $Id: listtable.php,v 1.2 2001/07/19 12:48:35 jakobn Exp $
//
// Created on: <22-Jun-2001 13:12:22 br>
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

// include the class files.
// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "ezexample/classes/ezexample.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );
// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "classes/ezcurrency.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezdatetime.php" );
		
$ini =& eZINI::instance( 'site.ini' );

$PricesIncludeVAT = $ini->variable( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
$Dealer = $ini->variable( "homepage", "DealerDescription" );
$Distributor = $ini->variable( "homepage", "DistributorDescription" );
$DistProdCats = $ini->variable( "homepage", "DistProdCats" );
$DistThumbWidth = $ini->variable( "homepage", "DistThumbWidth" );
$DistThumbHeight = $ini->variable( "homepage", "DistThumbHeight" );
$DealerProdCats = $ini->variable( "homepage", "DealerProdCats" );
$DealerThumbWidth = $ini->variable( "homepage", "DealerThumbWidth" );
$DealerThumbHeight = $ini->variable( "homepage", "DealerThumbHeight" );
$ArticleLimit = $ini->variable( "homepage", "ArticleLimit" );
$ArticleThumbWidth = $ini->variable( "homepage", "ArticleThumbWidth" );
$ArticleThumbHeight = $ini->variable( "homepage", "ArticleThumbHeight" );
$HotDealsLimit = $ini->variable( "homepage", "HotDealsLimit" );
$HotDealsThumbWidth = $ini->variable( "homepage", "HotDealsThumbWidth" );
$HotDealsThumbHeight = $ini->variable( "homepage", "HotDealsThumbHeight" );
$GalleryLimit = $ini->variable( "homepage", "GalleryLimit" );
$CommentLimit = $ini->variable( "homepage", "CommentLimit" );
$CommentLetterLimit = $ini->variable( "homepage", "CommentLetterLimit" );
$Anonymous = $ini->variable( "eZForumMain", "AnonymousPoster" ); 
$GalleryThumbWidth = $ini->variable( "homepage", "GalleryThumbWidth" );
$GalleryThumbHeight = $ini->variable( "homepage", "GalleryThumbHeight" );
$Language = $ini->variable( "eZUserMain", "Language" );

$locale = new eZLocale( $Language );

$t = new eZTemplate( "kernel/ezarticle/user/" . $ini->variable( "eZArticleMain", "TemplateDir" ),
                       "kernel/ezarticle/user/intl", $Language, "homepage.php" );
$t->setAllStrings();

// get all fields from the database.

// $field = new eZExample( );
// $fieldArray =& $field->getAll();

// parse the template.

$t->set_file( "homepage_tpl", "homepage.tpl" );
//$t->set_block( "listtable_tpl", "row_tpl", "row" );
//$t->set_var( "row", "" );
$t->set_block( "homepage_tpl", "distributor_image_box_tpl", "distributor_image_box" );
$t->set_block( "distributor_image_box_tpl", "distributor_image_item_tpl", "distributor_image_item" );
$t->set_block( "distributor_image_item_tpl", "distributor_image_tpl", "distributor_image" );
$t->set_block( "homepage_tpl", "dealer_image_box_tpl", "dealer_image_box" );
$t->set_block( "dealer_image_box_tpl", "dealer_image_item_tpl", "dealer_image_item" );
$t->set_block( "dealer_image_item_tpl", "dealer_image_tpl", "dealer_image" );
$t->set_block( "homepage_tpl", "product_list_tpl", "product_list" );
$t->set_block( "product_list_tpl", "product_list_item_tpl", "product_list_item" );
$t->set_block( "product_list_item_tpl", "price_tpl", "price" );
$t->set_block( "product_list_item_tpl", "product_message_count_tpl", "product_message_count" );
$t->set_block( "homepage_tpl", "image_list_tpl", "image_list" );
$t->set_block( "image_list_tpl", "image_list_item_tpl", "image_list_item" );
$t->set_block( "homepage_tpl", "message_list_tpl", "message_list" );
$t->set_block( "message_list_tpl", "message_list_item_tpl", "message_list_item" );
$t->set_block( "homepage_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );
$t->set_block( "article_item_tpl", "article_image_tpl", "article_image" );
$t->set_block( "article_item_tpl", "message_count_tpl", "message_count" );

$t->set_var( "distributor_image_box", "" );
$t->set_var( "dealer_image_box", "" );
$t->set_var( "product_list", "" );
$t->set_var( "image_list", "" );
$t->set_var( "message_list", "" );
$t->set_var( "message_count", "" );
$t->set_var( "article_list", "" );

/*
for( $i=0; $i< count($fieldArray); $i++ )
{
    if ( $fieldArray[$i]->Text() != "" )
        $t->set_var( "row_text", $fieldArray[$i]->Text() );
    else
        $t->set_var( "row_text", "&nbsp;" );
    $t->parse( "row", "row_tpl", true );
}
$t->pparse( "output", "listtable_tpl" );
*/


$distProdArray = explode(';', $DistProdCats);
//print_r($distProdArray);
$dealerProdArray = explode(';', $DealerProdCats);
//print_r($dealerProdArray);

$user =& eZUser::currentUser();
$fei = 0;

foreach ($distProdArray as $distProdCatID )
{
	$fei++;
	$t->set_var("import_row_open", "");
	$t->set_var("import_row_close", "");
	if ($fei == 1 || is_integer(($fei-1) / 4)) { // for some reason modulus ( % ) wasn't working for me ( I suspect a personal grudge )
		$t->set_var("import_row_open", "<tr>");
	}
	if ( is_integer( $fei / 4 ) )
	{
		$t->set_var("import_row_close", "</tr>");
	}
	$distCategory = new eZProductCategory( $distProdCatID );
//		print_r($distCategory);
	$t->set_var( "category_id", $distCategory->id() );
	$t->set_var( "category_description", $distCategory->description() );
	$t->set_var( "category_name", $distCategory->name() );

	$t->set_var( "distributor_image", "" );
	$image = $distCategory->image();
	if ( $image->id() != 0 )
	{
		$variation = $image->requestImageVariation( $DistThumbWidth, $DistThumbHeight );
		$t->set_var( "thumbnail_image_uri", $variation->imagePath() );
		$t->set_var( "thumbnail_image_width", $variation->width() );
		$t->set_var( "thumbnail_image_height", $variation->height() );
		$t->parse( "distributor_image", "distributor_image_tpl", true );
	}
	$t->parse( "distributor_image_item", "distributor_image_item_tpl", true );
}
	
$t->parse( "distributor_image_box", "distributor_image_box_tpl", true );
$dei = 0;
foreach ($dealerProdArray as $dealerProdCatID )
{
	$dei++; 
	$t->set_var("dealer_row_open", "");
	$t->set_var("dealer_row_close", "");
	if ($dei == 1 || is_integer(($dei-1) / 4)) { 
		$t->set_var("dealer_row_open", "<tr>");
	}
	if ( is_integer( $fei / 4 ) )
	{
		$t->set_var("dealer_row_close", "</tr>");
	} 
	$t->set_var( "dealer", $Dealer );
		$t->set_var( "distributor", $Distributor );

	$dealerCategory = new eZProductCategory( $dealerProdCatID );
	$t->set_var( "category_id", $dealerCategory->id() );
	$t->set_var( "category_description", $dealerCategory->description() );
	$t->set_var( "category_name", $dealerCategory->name() );

	$t->set_var( "dealer_image", "" );
	$image = $dealerCategory->image();
	if ( $image->id() != 0 )
	{
		$variation = $image->requestImageVariation( $DealerThumbWidth, $DealerThumbHeight );
		$t->set_var( "thumbnail_image_uri", $variation->imagePath() );
		$t->set_var( "thumbnail_image_width", $variation->width() );
		$t->set_var( "thumbnail_image_height", $variation->height() );
		$t->parse( "dealer_image", "dealer_image_tpl", true );
	}
	$t->parse( "dealer_image_item", "dealer_image_item_tpl", true );
}
$t->parse( "dealer_image_box", "dealer_image_box_tpl", true );

// products
$product = new eZProduct();
$productList =& $product->hotDealProducts( $HotDealsLimit );

$i = 0;
foreach ($productList as $product)
{
	if ($i < $HotDealsLimit )
	{
		$t->set_var( "product_id", $product->id() );
		// prevent html from being excaped ! render that html!
		// $t->set_var( "product_intro_text", htmlspecialchars( $product->brief() ) );		
		$t->set_var( "product_intro_text", $product->brief() );
		$t->set_var( "product_name", $product->name() );
			
		if ( $product->showPrice() == true )
			{
				$currency = new eZCurrency();
				$currency->setValue( $product->correctPrice( $PricesIncludeVAT ) );
				$t->set_var( "product_price", $locale->format( $currency ) );
				$t->parse( "price", "price_tpl" );	
			}
		else
				$t->set_var( "price", "" );
	
		if ( $product->forum() )
			{
				$forum = $product->forum();
				$MessageCount = $forum->messageCount( false, true );
			if ( $MessageCount > 0 )
				{
					if ( $MessageCount == 1)
						$messageText = $t->get_var("intl-message");
					else
						$messageText = $t->get_var("intl-messages");

					$t->set_var( "messages", $MessageCount."&nbsp;".$messageText );
					$t->parse( "product_message_count", "product_message_count_tpl" );
				}
			else
				$t->set_var( "product_message_count", "" );
			}
		else
			$t->set_var( "product_message_count", "" );
	
		$image = $product->thumbnailImage();
		if( is_object( $image ) )
		{
		$t->set_var( "image_caption", $image->caption() );
		$variation = $image->requestImageVariation( $HotDealsThumbWidth, $HotDealsThumbHeight );
		$t->set_var( "product_image_path", $variation->imagePath() );
		$t->set_var( "product_image_width", $variation->width() );
		$t->set_var( "product_image_height", $variation->height() );
		$t->parse( "product_list_item", "product_list_item_tpl", true );
		}
	}
$i++;
}
$t->parse( "product_list", "product_list_tpl", true );

$i = 0;
if ( $i < $GalleryLimit )
{
	$imageCategories =& eZImageCategory::getAll($GalleryLimit);
	foreach ($imageCategories as $category)
	{
			$images = $category->images( "time", 0, 1 );
			if (count( $images ) < 1)
			{
				$GalleryLimit++;
			}
	}
	$i++;
}

$imageCategories =& eZImageCategory::getAll($GalleryLimit);
$iei=0;
foreach ($imageCategories as $category)
	{
		$iei++;
		// echo($iei);
		$t->set_var("image_row_open", "");
		$t->set_var("image_row_close", "");
		//	if ($iei == 1 || is_integer(($iei-1) / 5) )
		//	if ($iei == 1 || is_integer(($iei-1) / 3) )
		//	if ($iei == 1 || is_integer(($iei-1) / 3) )
		if ( $iei % 9 == 0 )
		{
			$t->set_var("image_row_open", "<tr>");
		}
		
		if ( ($iei + 1) % 9 == 0 )
		{
			$t->set_var("image_row_close", "</tr>");
		}
		$images = $category->images( "time", 0, 1 );
		$t->set_var( "category_id", $category->id() );
		if ( strlen( $category->name() ) > ($GalleryThumbWidth/10) )
			$name = substr($category->name(), 0, $GalleryThumbWidth/10);
		else
			$name = $category->name();
		$t->set_var( "category_name", stripslashes($name) );
		
		if ( $category->description() != "" )
			$t->set_var( "category_description", $category->description() );
		else
			$t->set_var( "category_description", stripslashes( $category->name() ) );
//		print_r( $images[0]->requestImageVariation( $GalleryThumbWidth, $GalleryThumbHeight ) ); exit();
//		$image = new eZImage( $images[0]->id() );
		if ( isset( $images[0] ) )
			{	
				$variation = $images[0]->requestImageVariation( $GalleryThumbWidth, $GalleryThumbHeight );
				$t->set_var( "thumbnail_image_uri", $variation->imagePath() );
				$t->set_var( "thumbnail_image_width", $variation->width() );
				$t->set_var( "thumbnail_image_height", $variation->height() );
				$t->parse( "image_list_item", "image_list_item_tpl", true );
			}
//				echo "<img src=\"http://" . $HTTP_HOST . $ini->WWWDir . "/" . $variation->imagePath() . 
//					"\" height=\"".$variation->height()."\" width=\"".
//					$variation->width()."\" /><br />";

	}

$t->parse( "image_list", "image_list_tpl", true );


$time = new eZDateTime();
$messages =& eZForumMessage::lastMessages( $CommentLimit );

foreach ($messages as $message)  
{	
		$t->set_var( "message_id", $message['ID'] );
	    $time->setTimeStamp( $message['PostingTime'] );
		$t->set_var( "date_posted", $locale->format( $time ) );	
		$t->set_var( "message_topic", stripslashes($message['Topic']) );
		if ( $message['UserID'] == 0 )
		    $messageAuthorName = $Anonymous;
		else
		{
			$messageAuthor = new eZUser( $message['UserID'] );
			$messageAuthorName = $messageAuthor->firstName() . " " . $messageAuthor->lastName();
		}

		$t->set_var( "author", $messageAuthorName );
		
		if( isset( $message['Body'] ) )
		{
			$messageHeader = substr($message['Body'], 0, $CommentLetterLimit );
			$t->set_var( "message_header", stripslashes($messageHeader)."..." );
		}

		$t->parse( "message_list_item", "message_list_item_tpl", true );
//		print_r( stripslashes($message['Topic']) );
//		echo "\n";
}
	
if ( count($messages) > 0 )
	$t->parse( "message_list", "message_list_tpl", true );
else
	$t->set_var( "message_list", "" );

$article = new eZArticle();
$articleList =& $article->articles( "time", false, 0, $ArticleLimit );

foreach($articleList as $article)
{
		$t->set_var( "article_id", $article->id() );
		$t->set_var( "category_id", $article->categoryDefinition( false ) );
	    $t->set_var( "title", $article->name() );
		
	    $thumbnailImage =& $article->thumbnailImage();
		if ( $thumbnailImage )
		{
			$variation =& $thumbnailImage->requestImageVariation( $ArticleThumbWidth, $ArticleThumbHeight );
			$t->set_var( "thumbnail_image_uri", $variation->imagePath() );
			$t->set_var( "thumbnail_image_width", $variation->width() );
			$t->set_var( "thumbnail_image_height", $variation->height() );
			$t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );
			$t->parse( "article_image", "article_image_tpl" );
		}
    	else
    		$t->set_var( "article_image", "" );
		
    $renderer = new eZArticleRenderer( $article );
    $t->set_var( "article_intro", $renderer->renderIntro( ) );

    $published =& $article->published();
    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "article_published", $locale->format( $publishedDateValue )."&nbsp;".$locale->format( $publishedTimeValue ) );

	$t->set_var( "messages", "" );
	if ( $article->forum() )
		{
			$forum = $article->forum();
			$MessageCount = $forum->messageCount( false, true );
				if ( $MessageCount > 0 )
					{
						if ( $MessageCount == 1)
							$messageText = $t->get_var("intl-message");
						else
							$messageText = $t->get_var("intl-messages");

						$t->set_var( "messages", $MessageCount."&nbsp;".$messageText );
						$t->parse( "message_count", "message_count_tpl" );
					}
				else
					$t->set_var( "message_count", "" );
				}
	else
		$t->set_var( "message_count", "" );
	$t->parse( "article_item", "article_item_tpl", true );
//		print_r($article->name());
//		echo "\n";
} 
$t->parse( "article_list", "article_list_tpl", true );

//echo "</pre>";


if ( isset( $GenerateStaticPage ) and $GenerateStaticPage == "true" and $cachedFile != "" )
{
    $fp = eZPBFile::fopen( $cachedFile, "w+");

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    // $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    // $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";
    // $output .= "\$eZLanguageOverride=\"$eZLanguageOverride\";\n";
    $output .= "?>\n";

    $output .= $t->parse( "output", "homepage_tpl" );

    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
	$t->pparse( "output", "homepage_tpl" );
}

?>