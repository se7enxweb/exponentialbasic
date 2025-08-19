<?php
// 
// $Id: articlelistrss.php,v 1.6.2.2 2003/07/22 09:55:52 vl Exp $
//
// Created on: <11-Dec-2000 09:44:51 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/eztexttool.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );

$ini =& eZINI::instance( 'site.ini' );
$Title = htmlspecialchars($ini->variable( "eZArticleRSS", "Title" ));
$Link = $ini->variable( "eZArticleRSS", "Link" );
$Description = htmlspecialchars($ini->variable( "eZArticleRSS", "Description" ));
$Language = $ini->variable( "eZArticleRSS", "Language" );

$Image = $ini->variable( "eZArticleRSS", "Image" );
$CategoryID = $ini->variable( "eZArticleRSS", "CategoryID" );
$Limit = $ini->variable( "eZArticleRSS", "Limit" );

$headerInfo = ( getallheaders() );
$Host =  $headerInfo["Host"] ;

// clear what might be in the output buffer
ob_end_clean();

// xml header
header( "Content-type: text/xml" );
print( "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n" );

// rss header
//print( "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\" \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n\n" );
print( "<rss version=\"0.92\">\n" );

print( "<channel>\n" );


print( "<title>$Title</title>\n" );
print( "<link>$Link</link>\n" );
print( "<description>$Description</description>\n" );
print( "<language>$Language</language>\n" );

// Print Channel Image Tag
print( "<image>\n" );
print( "<url>http://".$Host.$Image."</url>\n" );
print( "<link>http://".$Link."</link>\n" );
print( "<title>".Title."</title>\n" );
print( "</image>\n" );

// get articles. Always sort by date/time (newest first)
if ( $CategoryID == 0 )
{
    $article = new eZArticle();
    $articleList = $article->articles( "time", false, 0 , $Limit);
} 
else
{
    $category = new eZArticleCategory( $CategoryID );
    $articleList = $category->articles( "time", false, true, 0 , $Limit );
}


$locale = new eZLocale( $Language );
foreach ( $articleList as $article )
{
    $articleID = $article->id();

    
    print( "<item>\n" );
    print( "<title>" . htmlspecialchars($article->name()) . "</title>\n" );
    print( "<link>http://" . $Host . "/article/view/$articleID/</link>\n" );
    
//      $published = $article->published();
//      print( $locale->format( $published ) );

 
 // render the intro as HTML into the description tag:
 $renderer = new eZArticleRenderer( $article );
 $description = $renderer->renderIntro();
 
 // prefix relative Links in href and src attributes with the Hostname, so the feed does not contain relative links and feedreaders can parse the links and show the images.
 $description = str_replace("href=\"/", "href=\"http://".$Host."/", $description);
 $description = str_replace("src=\"/", "src=\"http://".$Host."/", $description);
   
// encode HTML special character like < , > and " and print the tag   
    print( "<description>" . htmlspecialchars( $description ). "</description>\n" );    

    print( "</item>\n" );
}

print( "\n</channel>\n" );
print( "\n</rss>\n" );

exit();

?>