<?php
// 
// $Id: ezarticlegenerator.php 7850 2001-10-15 12:08:50Z jhe $
//
// Definition of eZArticleGenerator class
//
// Created on: <18-Oct-2000 15:47:43 bf>
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
//! eZArticleGenerator handles article XML generation.
/*!
  This class handles generation of XML contents for
  the articles. It will read from the .ini file and find
  the default generator to use.
*/

include_once( "classes/INIFile.php" );

class eZArticleGenerator
{
    function __construct( $generatorType="" )
    {
        if ( $generatorType == "" )
        {
            $ini =& INIFile::globalINI();

            $Generator = $ini->read_var( "eZArticleMain", "Generator" );
            $generatorType = $Generator;
        }

        switch ( $generatorType )
        {            
            case "qdom" :
            {
                $this->GeneratorFile = "ezqdomgenerator.php";
                $this->GeneratorClass = "eZQDomGenerator";
            }
            break;

            case "tech" :
            {
                $this->GeneratorFile = "eztechgenerator.php";
                $this->GeneratorClass = "eZTechGenerator";
            }
            break;
            
            case "ez" :
            {
                $this->GeneratorFile = "ezezgenerator.php";
                $this->GeneratorClass = "eZEzGenerator";
            }
            break;
            
            case "flower" :
            {
                $this->GeneratorFile = "ezflowergenerator.php";
                $this->GeneratorClass = "eZFlowerGenerator";
            }
            break;
            
            case "simple":
            default :
            {
                $this->GeneratorFile = "ezsimplegenerator.php";
                $this->GeneratorClass = "eZSimpleGenerator";
            }
        }
    }

    /*!
      This function will parse the contents array and return valid
      XML data for insertion in the database.
    */
    function &generateXML( &$contents )
    {
        include_once( "ezarticle/classes/" . $this->GeneratorFile );

        $generator = new $this->GeneratorClass( $contents );

        $ret =& $generator->generateXML();

        $this->PageCount = $generator->pageCount();
             
        return $ret;
    }

    /*!
      This function will return the number of pages of the last parsed article.
    */
    function pageCount( )
    {
        return $this->PageCount;
    }
    
    
    /*!
      This function will return an array containing the original state
      of the article so it can be used in a edit form.
    */
    function &decodeXML( &$contents )
    {
        // find the generator used
        if ( preg_match("/<generator>(.*)<\/generator>/", substr( $contents, 0, 200 ), $regs ) )
        {
            $generator = $regs[1];
            switch ( $generator )
            {
                case "qdom" :
                {
                    $this->GeneratorFile = "ezqdomgenerator.php";
                    $this->GeneratorClass = "eZQDomGenerator";
                }
                break;

                case "tech" :
                {
                    $this->GeneratorFile = "eztechgenerator.php";
                    $this->GeneratorClass = "eZTechGenerator";
                }
                break;

                case "ez" :
                {
                    $this->GeneratorFile = "ezezgenerator.php";
                    $this->GeneratorClass = "eZEzGenerator";
                }
                break;
                
                case "flower" :
                {
                    $this->GeneratorFile = "ezflowergenerator.php";
                    $this->GeneratorClass = "eZFlowerGenerator";
                }
                break;

                case "simple" :
                {
                    $this->GeneratorFile = "ezsimplegenerator.php";
                    $this->GeneratorClass = "eZSimpleGenerator";
                }
                break;
            }
        }
        else
        {
            print( "<b>Error: eZArticleGenerator::decodeXML()  could not find generator in XML chunk.</b>" );
        }

        include_once( "ezarticle/classes/" . $this->GeneratorFile );

        $generator = new $this->GeneratorClass( $contents );
              
        return $generator->decodeXML();        
    }
        
    var $GeneratorClass;
    var $GeneratorFile;

    var $PageCount;
}

?>