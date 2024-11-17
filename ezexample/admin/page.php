<h1>This is an eZ publish admin page</h1>

<hr noshade="noshade" size="4" />
<br />

<form action="<? print $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/example/page/" method="post">

<input type="text" name="Value" value="" />

<input class="stdbutton" type="submit" value="send" />


</form>


<?
if ( isset( $Value ) )
{
    print( "<pre>" . $Value . "</pre>" );
    print( "You entered: -" . nl2br( $Value ) . "-" );
}


?>

<br />
