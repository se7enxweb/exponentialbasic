<?php
// 
// $Id: export.php 7455 2001-09-25 13:48:56Z bf $
//
// Created on: <23-Sep-2001 16:55:39 bf>
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

// include_once( "classes/ezhttptool.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );


$Language = $ini->variable( "eZArticleMain", "Language" );
$TemplateDir = $ini->variable( "eZArticleMain", "TemplateDir" );

$t = new eZTemplate( "kernel/ezarticle/user/" . $TemplateDir,                     
                     "kernel/ezarticle/user/intl/", $Language, "articleview.php" );


$t->set_file( "article_view_tex_tpl", "tex.tpl"  );

$t->setAllStrings();

$ArticleID = 590;

$article = new eZArticle( );

if ( $article->get( $ArticleID ) )
{
    if ( $article->isPublished() )
    {
        // published article.
    }
    else
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }

    // convert images to eps

    $images =& $article->images();

    foreach ( $images as $image )
    {
        $image = $image["Image"];
        $fileName = $image->fileName();
        
        print( system( "convert ezimagecatalogue/catalogue/$fileName ezimagecatalogue/catalogue/" . $fileName . ".eps  " ) );

        
        print( $image->fileName() . "<br>" );
    }
    
    $template = "tex";
    $renderer = new eZArticleRenderer( $article, $template );

    $articleContents = $renderer->renderPage( -1 );
    
    $t->set_var( "article_name", $article->name() );
    $t->set_var( "author_name", $article->authorText() );

    $t->set_var( "article_intro", $articleContents[0] );
    $t->set_var( "article_body", $articleContents[1] );

}

print( "<pre>" );
$doc =& $t->parse( "output", "article_view_tex_tpl" );

$outputFile = "ezarticle/cache/article.tex";

$fp = eZPBFile::fopen( $outputFile, "w+");

fwrite ( $fp, $doc );
fclose( $fp );

// convert to dvi
//print( system( "cd ezarticle/cache/ && latex -interaction=batchmode $outputFile && cd .." ) );

print( htmlspecialchars( $doc ) );
print( "</pre>" );

?>