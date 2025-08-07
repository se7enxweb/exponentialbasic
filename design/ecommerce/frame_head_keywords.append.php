<? 
  // eZ publish : Application : Page Meta Keywords
  // #############################################################################
?>
<? /* <!-- set the content meta information -->  */ ?>
<meta name="author" content="<?php

    $SiteAuthor = $ini->variable( "site", "SiteAuthor" );
    print( $SiteAuthor );

?>" />
<meta name="copyright" content="<?php

    $SiteCopyright = $ini->variable( "site", "SiteCopyright" );
    $SiteCopyright = htmlspecialchars($SiteCopyright);
    $SiteCopyright = strip_tags( $SiteCopyright );
    
    print( $SiteCopyright );

?>" />
<meta name="description" content="<?php

if ( isset( $SiteDescriptionOverride ) )
{
    print( $SiteDescriptionOverride );
}
else
{
    $SiteDescription = $ini->variable( "site", "SiteDescription" );
    print( $SiteDescription );
}

?>" />
<meta name="generator" content="HTML Tidy, see www.w3.org" />
<meta name="keywords" content="<?php
if ( isset( $SiteKeywordsOverride ) )
{
    print( $SiteKeywordsOverride );
}
else
{
    $SiteKeywords = $ini->variable( "site", "SiteKeywords" );
    print( $SiteKeywords );
}

?>" />
