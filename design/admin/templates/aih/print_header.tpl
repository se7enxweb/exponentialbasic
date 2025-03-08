
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>

<title>eZ publish administrasjon</title>

<link rel="stylesheet" type="text/css" href="{www_dir}/admin/templates/{site_style}/style.css" />

<meta http-equiv="Content-Type" content="text/html; charset={charset}" />

<script language="JavaScript1.2">
<!--//

	function MM_swapImgRestore() 
	{
		var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
	}

	function MM_preloadImages() 
	{
		var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
		var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
	}

	function MM_findObj(n, d) 
	{
		var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
	}

	function MM_swapImage() 
	{
		var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
		if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
	}

	function verify( msg, url )
	{
    	if ( confirm( msg ) )
    	{
    	    this.location = url;
    	}
	}

	function popup ( url, target ) 
	{
	    numbers = "width=500, height=400, left=4, top=4, toolbar=1, statusbar=0, scrollbars=1, resizable=1";
	    newWin = window.open ( url, target, numbers );
	    return false;
	}
	
//-->
</script> 


</head>

<body bgcolor="#FFFFFF" onload="MM_preloadImages('/images/{site_style}/redigerminimrk.gif','/images/{site_style}/slettminimrk.gif','/images/{site_style}/downloadminimrk.gif')">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
		<span class="path">{intl-site}:</span> {site_url}
	</td>
	<td align="center">
		<span class="path">{intl-user_name}:</span> {first_name}&nbsp;{last_name}
	</td>
	<td align="right">
	<a class="path" href="{www_dir}{index}{current_url}">&lt;&lt;&nbsp;{intl-back_to_normal_view}</a>
	</td>
</tr>
</table>
