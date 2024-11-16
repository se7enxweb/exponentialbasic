<?php
// 
// $Id: eztechgenerator.php 9328 2002-03-04 12:49:40Z bf $
//
// Definition of eZTechGenerator class
//
// Created on: <18-Oct-2000 15:55:16 bf>
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
//! eZTechGenerator generates  XML contents for articles.
/*!
  This class will generate a tech XML article. This class is ment
  as an example of how to write your own special generator.

*/

/*!TODO
  
*/

include_once( "ezxml/classes/ezxml.php" );

class eZTechGenerator
{
    /*!
      Creates a new eZTechGenerator object.
    */
    function eZTechGenerator( &$contents )
    {
        $this->Level = 0;
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
        $newContents .= "<article><generator>tech</generator>\n";

        //add the contents
        // What does strip_tags do? needed anymore?
//          $newContents .= "<intro>" . strip_tags( $this->Contents[0], "<bold>,<italic>,<strike>,<underline>" ) . "</intro>";
        $newContents .= "<intro>" . $this->generatePage( $this->Contents[0] ) . "</intro>";

        // get every page in an array
        $pages = split( "<page>" , $this->Contents[1] );

        $body = "";
        foreach ( $pages as $page )
        {
            $tmpPage = $page;

            $tmpPage = $this->generatePage( $tmpPage );

            $body .= "<page>" . $tmpPage  . "</page>";        
        }

        $this->PageCount = count( $pages );
        

        $newContents .= "<body>" . $body . "</body></article>";

        return $newContents;
    }

    /*!
      \private
      
    */
    function &generatePage( $tmpPage )
    {
        $tmpPage = $this->generateImage( $tmpPage );

        $tmpPage = $this->generateLink( $tmpPage );

        $tmpPage = $this->generateModule( $tmpPage );
        
        // replace & with &amp; to prevent killing the xml parser..
        // is that a bug in the xmltree(); function ? answer to bf@ez.no
        $tmpPage = ereg_replace ( "&", "&amp;", $tmpPage );

        $tmpPage = $this->generateHTML( $tmpPage );

        $tmpPage = $this->generateUnknowns( $tmpPage );

        return $tmpPage;
    }

    /*!
      \private
      
    */
    function &generateUnknowns( $tmpPage )
    {
        // make unknown tags readable.. look-ahead assertion is used ( ?! ) 
        $tmpPage = preg_replace( "/<(?!(page|php|\/|image|cpp|shell|sql|hea|lin|iconlink|per|bol|ita|und|str|pre|ver|lis|ezhtml|html|java|ezanchor|mail|module|bullet))/", "&lt;", $tmpPage );

        // look-behind assertion is used here (?<!) 
        // the expression must be fixed width eg just use the 3 last letters of the tag

        $tmpPage = preg_replace( "#(?<!(age|php|age|cpp|ell|sql|der|erl|old|lic|ine|ike|pre|tim|isp|tml|ava|let))>#", "&gt;", $tmpPage );
        // make better..
        $tmpPage = preg_replace( "#/&gt;#", "/>", $tmpPage );

        return $tmpPage;
    }

    function &generateHTML( $tmpPage )
    {
        // Begin html tag replacer
        // replace all < and >  between <ezhtml> and </ezhtml>
        // and to the same for <php> </php>
        // ok this is a bit slow code, but it works
        $startHTMLTag = "<html>";
        $endHTMLTag = "</html>";
        
        $starteZHTMLTag = "<ezhtml>";
        $endeZHTMLTag = "</ezhtml>";

        $startPHPTag = "<php>";
        $endPHPTag = "</php>";
            
        $numberBeginHTML = substr_count( $tmpPage, $startHTMLTag );
        $numEndHTML = substr_count( $tmpPage, $endHTMLTag );

        if ( $numberBeginHTML != $numEndHTML )
        {
            print( "Unmatched ezhtml tags, check that you have end tags for all begin tags" );
        }

        $numberBegineZHTML = substr_count( $tmpPage, $starteZHTMLTag );
        $numEndeZHTML = substr_count( $tmpPage, $endeZHTMLTag );

        if ( $numberBegineZHTML != $numEndeZHTML )
        {
            print( "Unmatched ezhtml tags, check that you have end tags for all begin tags" );
        }
        
        $numberBeginPHP = substr_count( $tmpPage, $startPHPTag );
        $numEndPHP = substr_count( $tmpPage, $endPHPTag );
            
        if ( $numberBegin != $numEnd )
        {
            print( "Unmatched PHP tags, check that you have end tags for all begin tags" );
        }

        if ( ( $numberBeginPHP > 0 ) || ( $numberBegineZHTML > 0 ) || ( $numberBeginHTML > 0 ) )
        {
            $resultPage = "";
            $isInsideHTML = false;
            $isInsideeZHTML = false;
            $isInsidePHP = false;
            for ( $i=0; $i<strlen( $tmpPage ); $i++ )
            {    
                if ( substr( $tmpPage, $i - strlen( $startHTMLTag ), strlen( $startHTMLTag ) ) == $startHTMLTag )
                {
                    $isInsideHTMLTag = true;
                }

                if ( substr( $tmpPage, $i, strlen( $endHTMLTag ) ) == $endHTMLTag )
                {
                    $isInsideHTMLTag = false;
                }

                if ( substr( $tmpPage, $i - strlen( $starteZHTMLTag ), strlen( $starteZHTMLTag ) ) == $starteZHTMLTag )
                {
                    $isInsideeZHTMLTag = true;
                }

                if ( substr( $tmpPage, $i, strlen( $endeZHTMLTag ) ) == $endeZHTMLTag )
                {
                    $isInsideeZHTMLTag = false;
                }
                
                if ( substr( $tmpPage, $i - strlen( $startPHPTag ), strlen( $startPHPTag ) ) == $startPHPTag )
                {
                    $isInsidePHPTag = true;
                }

                if ( substr( $tmpPage, $i, strlen( $endPHPTag ) ) == $endPHPTag )
                {
                    $isInsidePHPTag = false;
                }
                
                if ( ( $isInsideHTMLTag == true ) || ( $isInsideeZHTMLTag == true ) ||  ( $isInsidePHPTag == true ) )
                {
                    switch ( $tmpPage[$i] )
                    {
                        case "<" :
                        {
                            $resultPage .= "&lt;";
                        }
                        break;

                        case ">" :
                        {
                            $resultPage .= "&gt;";
                        }
                        break;
            
                        default:
                        {
                            $resultPage .= $tmpPage[$i];
                        }
                    }
                }
                else
                {
                    $resultPage .= $tmpPage[$i];
                }
            }

            $tmpPage = $resultPage;
        }
        return $tmpPage;
    }

    /*!
      \private
      Converts the link tags to valid XML tags.
    */
    function &generateLink( $tmpPage )
    {
        // convert <link ez.no ez systems> to valid xml
        // $tmpPage = "<link ez.no ez systems> <link ez.no ez systems>";
        $tmpPage = preg_replace( "#(<link\s+?([^ ]+)\s+?([^>]+)>)#", "<link href=\"\\2\" text=\"\\3\" />", $tmpPage );

        $tmpPage = preg_replace( "#(<iconlink\s+?([^ ]+)\s+?([^>]+)>)#", "<iconlink href=\"\\2\" text=\"\\3\" />", $tmpPage );
        
        // convert <ezanchor anchor> to <ezanchor href="anchor" />
        $tmpPage = preg_replace( "#<ezanchor\s+?(.*?)>#", "<ezanchor href=\"\\1\" />", $tmpPage );
        
        // convert <mail adresse@domain.tld subject line, link text>
        // to valid xml
        $tmpPage = preg_replace( "#<mail\s+?([^ ]*?)\s+?(.*?),\s+?([^>]*?)>#", "<mail to=\"\\1\" subject=\"\\2\" text=\"\\3\" />", $tmpPage );

        return $tmpPage;
    }

    /*!
      \private
      Generates valid module xml tags
    */
    function &generateModule( $tmpPage )
    {
        // convert <module modulename>
        // to <module name="modulename" />
        $tmpPage = preg_replace( "#(<module\s+([^ ]+?)\s*>)#", "<module name=\"\\2\" />", $tmpPage );

        return $tmpPage;
    }
    
    /*!
      \private
    */
    function &generateImage( $tmpPage )
    {
        // parse the <image id align size> tag and convert it
        // to <image id="id" align="align" size="size" />
        $tmpPage = preg_replace( "/(<image\s+?([^ ]+)\s+?([^ ]+)\s+?([^( |>)]+)([^>]*?)>)/", "<image id=\"\\2\" align=\"\\3\" size=\"\\4\" />", $tmpPage );

        $tmpPage = preg_replace( "/(<image\s+?([0-9]+?)>)/", "<image id=\"\\2\" align=\"float\" size=\"medium\" />", $tmpPage );
        
        return $tmpPage;
    }


    /*!
      Decodes the xml chunk and returns the original array to the article. 
    */
    function &decodeXML()
    {
        $contentsArray = array();

        $xml =& eZXML::domTree( $this->Contents );

//          $xml =& qdom_tree( $this->Contents );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $into = "";
            $body = "";

            foreach ( $xml->children as $child )
            {
                if ( $child->name == "article" )
                {
                    foreach ( $child->children as $article )
                    {
                        if ( $article->name == "intro" )
                        {
                            $intro = $this->decodePage( $article );
                        }                        
                        
                        if ( $article->name == "body" )
                        {
                            $body = $article->children;
                        }
                        
                    }
                }
            }

            $contentsArray[] = $intro;


            $bodyContents = "";
            $i=0;
            // loop on the pages
            foreach ( $body as $page )
            {
                $pageContent = "";

                $pageContent = $this->decodePage( $page );

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
      \private

    */
    function decodePage( $page )
    {
        $value = "";
        if ( count( $page->children ) > 0 )
        {            
            foreach ( $page->children as $paragraph )
            {
                // ordinary text
                if ( $this->Level == 0  )
                {
                    if ( $paragraph->name == "text" || $paragraph->name == "#text" )
                    {
                        $value .= $paragraph->content;
                    }
                }

                // decode sub items
                $this->Level = $this->Level + 1;
                $subitem = $this->decodePage( $paragraph );
                $this->Level = $this->Level - 1;

                // can have sub items
                $value = $this->decodeStandards( $value, $paragraph, $subitem );

                // can not have sub items
                $value = $this->decodeImage( $value, $paragraph );
                $value = $this->decodeLink( $value, $paragraph );
                $value = $this->decodeModule( $value, $paragraph );
                $value = $this->decodeCode( $value, $paragraph );                
            }
        }

        return $value;
    }
    

    function &decodeCode( $pageContent, $paragraph )
    {
        // php code 
        if ( $paragraph->name == "php" )
        {
            $pageContent .= "<php>" . $paragraph->children[0]->content . "</php>";
        }

        // ezhtml code 
        if ( $paragraph->name == "ezhtml" )
        {
            $pageContent .= "<ezhtml>" . $paragraph->children[0]->content . "</ezhtml>";
        }

        // html code 
        if ( $paragraph->name == "html" )
        {
            $pageContent .= "<html>" . $paragraph->children[0]->content . "</html>";
        }
        
        // java code 
        if ( $paragraph->name == "java" )
        {
            $pageContent .= "<java>" . $paragraph->children[0]->content . "</java>";
        }

        // sql code
        if ( $paragraph->name == "sql" )
        {
            $pageContent .= "<sql>" . $paragraph->children[0]->content . "</sql>";
        }

        // shell code
        if ( $paragraph->name == "shell" )
        {
            $pageContent .= "<shell>" . $paragraph->children[0]->content . "</shell>";
        }

        // c++  code
        if ( $paragraph->name == "cpp" )
        {
            $pageContent .= "<cpp>" . $paragraph->children[0]->content . "</cpp>";
        }

        // perl  code
        if ( $paragraph->name == "perl" )
        {
            $pageContent .= "<perl>" . $paragraph->children[0]->content . "</perl>";
        }

        // lisp  code
        if ( $paragraph->name == "lisp" )
        {
            $pageContent .= "<lisp>" . $paragraph->children[0]->content . "</lisp>";
        }


        
        return $pageContent;
    }

    /*!
      \private
      
    */
    function &decodeImage( $pageContent, $paragraph )
    {
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
        return $pageContent;
    }

    /*!
      \private
    */
    function &decodeLink( $pageContent, $paragraph )
    {
        // link
        if ( $paragraph->name == "link" )
        {
            foreach ( $paragraph->attributes as $imageItem )
                {
//                      print( $imageItem->name );
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

        if ( $paragraph->name == "iconlink" )
        {
            foreach ( $paragraph->attributes as $imageItem )
                {
//                      print( $imageItem->name );
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
                        
            $pageContent .= "<iconlink $href $text>";
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
        
        return $pageContent;
    }

    /*!
      \private
      Decodes the module xml and generates user friendly module code.
    */
    function &decodeModule( $pageContent, $paragraph )
    {
        // module
        if ( $paragraph->name == "module" )
        {
            foreach ( $paragraph->attributes as $moduleItem )
            {            
                switch ( $moduleItem->name )
                {
                    case "name" :
                    {
                        $name = $moduleItem->children[0]->content;
                    }
                    break;
                }
            }
            $pageContent .= "<module $name>";
        }
        return $pageContent;        
    }

    function &decodeStandards( $pageContent, $paragraph, $subitem )
    {
        
        // header
        if ( $paragraph->name == "header" )
        {
            $pageContent .= "<header>" . $paragraph->children[0]->content . "</header>";
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

        // bullet list
        if ( $paragraph->name == "bullet" )
        {
            $pageContent .= "<bullet>" . $paragraph->children[0]->content . $subitem . "</bullet>";
        }
        
        return $pageContent;
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

    var $Level;
}

?>
