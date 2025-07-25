<?php

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

include_once( "eztrade/classes/ezproductcategory.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
		     "eztrade/user/intl/", $Language, "sitemap.php" );

$t->set_file( "sitemap_page_tpl", "sitemap.tpl" );
$t->set_block( "sitemap_page_tpl", "sitemap_line_tpl", "sitemap_line" );

$t->set_var("intl-top", "Shop");
$t->set_var("category", "Product Site Map");
$t->set_var("module", "trade");
$t->set_var("module_list", "product_list");
$t->set_var("intl-productlist", "Product Site Map");

$category = new eZProductCategory();
$categoryArray = $category->getTree();

foreach ( $categoryArray as $catItem )
{
  // graham : former riderhaus static category display restriction removal
  //if ( $catItem[0]->id() != 101 and $catItem[0]->id() != 102 )

  if ( $catItem[0]->id() != "" and $catItem[0]->id() != "312" )
    {
      //if($catItem[0]->id() == 380){
      //$test = $catItem;
      //}
      $t->set_var( "id", $catItem[0]->id() );
      $t->set_var( "parent", $catItem[0]->parent() );
      $t->set_var( "name", $catItem[0]->name() );

      $ident = str_repeat( "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $catItem[1] );

      $t->set_var( "ident", $ident );

      $t->parse( "sitemap_line", "sitemap_line_tpl", true );
    }
}

$t->pparse( "output", "sitemap_page_tpl" );

#<td>{ident}- <a href="/trade/productlist/{id}/">{name}</a></td>


?>