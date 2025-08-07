<?php
    header ("Content-Type: text/vnd.wap.wml");
    echo  "<?xml version=\"1.0\" ?>\n"; 

    $SiteTitle = $ini->variable( "site", "SiteTitle" );
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml><card id="main_card" title="<?=$SiteTitle; ?>">
<p><img src="/sitedesign/wap/logo.wbmp" alt="<?=$SiteTitle; ?>" align="middle"/></p>

<? print( $MainContents ); ?>
</card></wml>