<?php
// 
// $Id: ezezgenerator.php 6206 2001-07-19 12:19:22Z jakobn $
//
// Definition of eZEzGenerator class
//
// Created on: <26-Oct-2000 13:45:54 bf>
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

//!! eZArticle
//! eZEzGenerator generates  XML contents for articles.
/*!
  This class will generate a ez XML article. This class implements
  the generator used at ez.no.
*/

/*!TODO
  
*/

class eZEzGenerator
{
    /*!
      Creates a new eZEzGenerator object.
    */
    function __construct( &$contents )
    {
        $this->PageCount = 0;
        $this->Contents = $contents;
    }

    /*!
      Generates valid XML data to use for storage.
    */
    function &generateXML()
    {
        // add the XML header.
        $newContents = "<?xml version=\"1.0\"?>";
        
        //add the generator, this is used for rendering.
        $newContents .= "<article><generator>ez</generator>\n";

        //add the contents
        $newContents .= "<intro>" . strip_tags( $this->Contents[0] ) . "</intro>";

        // get every page in an array
        $pages = split( "<page>" , $this->Contents[1] );

        $body = "";
        foreach ( $pages as $page )
        {
            $tmpPage = $page;

            // parse the <image id align size> tag and convert it
            // to <image id="id" align="align" size="size" />

            $tmpPage = preg_replace( "/(<image\s+?([^ ]+)\s+?([^ ]+)\s+?([^( |>)]+)([^>]*?)>)/", "<image id=\"\\2\" align=\"\\3\" size=\"\\4\" />", $tmpPage );

            // convert <link ez.no ez systems> to valid xml
            // $tmpPage = "<link ez.no ez systems> <link ez.no ez systems>";
            $tmpPage = preg_replace( "#(<link\s+?([^ ]+)\s+?([^>]+)>)#", "<link href=\"\\2\" text=\"\\3\" />", $tmpPage );

            // same as above for <ezlink ez.no ez systems> a design difference.
            $tmpPage = preg_replace( "#(<ezlink\s+?([^ ]+)\s+?([^>]+)>)#", "<ezlink href=\"\\2\" text=\"\\3\" />", $tmpPage );

            // convert <ezanchor anchor> to <ezanchor href="anchor" />
            $tmpPage = preg_replace( "#<ezanchor\s+?(.*?)>#", "<ezanchor href=\"\\1\" />", $tmpPage );

            // convert <mail adresse@domain.tld subject line, link text>
            // to valid xml
            $tmpPage = preg_replace( "#<mail\s+?([^ ]*?)\s+?(.*?),\s+?([^>]*?)>#", "<mail to=\"\\1\" subject=\"\\2\" text=\"\\3\" />", $tmpPage );

            
            // replace & with &amp; to prevent killing the xml parser..
            // is that a bug in the xmltree(); function ? answer to bf@ez.no
            $tmpPage = ereg_replace ( "&", "&amp;", $tmpPage );
            
            // make unknown tags readable.. look-ahead assertion is used ( ?! ) 
            $tmpPage = preg_replace( "/<(?!(page|php|\/|image|hea|lin|bol|ita|und|str|pre|ver|lis|ezlink|ezanchor|mail))/", "&lt;", $tmpPage );

            // look-behind assertion is used here (?<!) 
            // the expression must be fixed with eg just use the 3 last letters of the tag

            $tmpPage = preg_replace( "#(?<!(age|php|age|der|old|lic|ine|ike|pre|tim|isp))>#", "&gt;", $tmpPage );
            // make better..
            $tmpPage = preg_replace( "#/&gt;#", "/>", $tmpPage );
            

            $body .= "<page>" . $tmpPage  . "</page>";        
        }
        
        
        $this->PageCount = count( $pages );

        
        $newContents .= "<body>" . $body . "</body></article>";

        return $newContents;
    }

    /*!
      Decodes the xml chunk and returns the original array to the article. 
    */
    function &decodeXML()
    {
        $contentsArray = array();
        
        $xml = xmltree( $this->Contents );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZEzRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $into = "";
            $body = "";
            
            foreach ( $xml->root->children as $child )
            {
                if ( $child->name == "intro" )
                {
                    $intro = $child->children[0]->content;
                }
                

                if ( $child->name == "body" )
                {
                    $body = $child->children;
                }
            }

            $contentsArray[] = $intro;

            $bodyContents = "";
            $i=0;
            // loop on the pages
            foreach ( $body as $page )
            {
                $pageContent = "";
                // loop on the contents of the pages
                foreach ( $page->children as $paragraph )
                {
                    // ordinary text
                    if ( $paragraph->name == "text" )
                    {
                        $pageContent .= $paragraph->content;
                    }

                    // header
                    if ( $paragraph->name == "header" )
                    {
                        $pageContent .= "<header>" . $paragraph->children[0]->content . "</header>";
                    }


                    // php code 
                    if ( $paragraph->name == "php" )
                    {
                        $pageContent .= "<php>" . $paragraph->children[0]->content . "</php>";
                    }

                    // image 
                    if ( $paragraph->name == "image" )
                    {
                        foreach ( $paragraph->attributes as $imageItem )
                        {
                            switch ( $imageItem->name )
                            {

                                case "id" :
                                {
                                    $imageID = $imageItem->children[0]->content;
                                }
                                break;

                                case "align" :
                                {
                                    $imageAlignment = $imageItem->children[0]->content;
                                }
                                break;

                                case "size" :
                                {
                                    $imageSize = $imageItem->children[0]->content;
                                }
                                break;
                                
                            }
                        }
                        
                        $pageContent .= "<image $imageID $imageAlignment $imageSize>";
                    }

                    // link 
                    if ( $paragraph->name == "link" )
                    {
                        foreach ( $paragraph->attributes as $imageItem )
                        {
//                              print( $imageItem->name );
                            switch ( $imageItem->name )
                            {

                                case "href" :
                                {
                                    $href = $imageItem->children[0]->content;
                                }
                                break;

                                case "text" :
                                {
                                    $text = $imageItem->children[0]->content;
                                }
                                break;
                                
                            }
                        }
                        
                        $pageContent .= "<link $href $text>";
                    }

                    // link 
                    if ( $paragraph->name == "ezlink" )
                    {
                        foreach ( $paragraph->attributes as $imageItem )
                        {
//                              print( $imageItem->name );
                            switch ( $imageItem->name )
                            {

                                case "href" :
                                {
                                    $href = $imageItem->children[0]->content;
                                }
                                break;

                                case "text" :
                                {
                                    $text = $imageItem->children[0]->content;
                                }
                                break;
                                
                            }
                        }
                        
                        $pageContent .= "<ezlink $href $text>";
                    }
                    

                    // mail
                    if ( $paragraph->name == "mail" )
                    {
                        foreach ( $paragraph->attributes as $mailItem )
                        {
                            switch ( $mailItem->name )
                            {
                                case "to" :
                                {
                                    $to = $mailItem->children[0]->content;
                                }
                                break;

                                case "subject" :
                                {
                                    $subject = $mailItem->children[0]->content;
                                }
                                break;

                                case "text" :
                                {
                                    $text = $mailItem->children[0]->content;
                                }
                                break;
                            }
                        }
                        
                        $pageContent .= "<mail $to $subject, $text>";
                    }

                    // ez anchor
                    if ( $paragraph->name == "ezanchor" )
                    {
                        foreach ( $paragraph->attributes as $anchorItem )
                        {
                            switch ( $anchorItem->name )
                            {
                                case "href" :
                                {
                                    $href = $anchorItem->children[0]->content;
                                }
                                break;
                            }
                        }
                        
                        $pageContent .= "<ezanchor $href>";
                    }

                    
                    // bold text
                    if ( $paragraph->name == "bold" )
                    {
                        $pageContent .= "<bold>" . $paragraph->children[0]->content . "</bold>";
                    }

                    // italic text
                    if ( $paragraph->name == "italic" )
                    {
                        $pageContent .= "<italic>" . $paragraph->children[0]->content . "</italic>";
                    }

                    // underline text
                    if ( $paragraph->name == "underline" )
                    {
                        $pageContent .= "<underline>" . $paragraph->children[0]->content . "</underline>";
                    }

                    // strike text
                    if ( $paragraph->name == "strike" )
                    {
                        $pageContent .= "<strike>" . $paragraph->children[0]->content . "</strike>";
                    }

                    // pre defined text
                    if ( $paragraph->name == "pre" )
                    {
                        $pageContent .= "<pre>" . $paragraph->children[0]->content . "</pre>";
                    }

                    // verbatim text
                    if ( $paragraph->name == "verbatim" )
                    {
                        $pageContent .= "<verbatim>" . $paragraph->children[0]->content . "</verbatim>";
                    }
                }

                if ( $i > 0 )
                    $bodyContents .=  "<page>" . $pageContent;
                else
                    $bodyContents .=  $pageContent;
                    
                $i++;
            }

            $contentsArray[] = $bodyContents;
        }

        return $contentsArray;
    }

    /*!
      Returns the number of pages found in the article.
    */
    function pageCount( )
    {
        return $this->PageCount;
    }    

    var $Contents;
    var $PageCount;
}

?>