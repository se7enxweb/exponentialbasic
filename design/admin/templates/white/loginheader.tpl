<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{intl-title}</title>
    <link rel="stylesheet" type="text/css" href="{www_dir}/design/admin/templates/{site_style}/style.css" />
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
	
	//-->
	</script>
</head>

<body bgcolor="#ffffff" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6" onload="MM_preloadImages('{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif','{www_dir}/design/admin/images/{site_style}/slettminimrk.gif','{www_dir}/design/admin/images/{site_style}/downloadminimrk.gif')">

<div class="topbox">
<div class="topbox-content">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="1%" class="tdmini">
	<a href="http://basic.exponential.earth" target="_vblank"><img src="{www_dir}/design/base/images/logo/exponential-basic.png" width="250" height="40" border="0" alt="" /></a><br />
	</td>
</tr>
</table>
</div>
</div>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="100%" valign="top">

    <div class="mainbox">
    <div class="mainbox-header">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="1%">

        <a href="{www_dir}{index}/">
        <!-- Icon: Start -->
        <img src="{www_dir}/kernel/ez{module_name}/admin/images/module_icon.png" width="32" height="32" border="0" align="absmiddle" alt="{intl-module_name}" />
        <!-- Icon: End -->
        </a>

        </td>
        <td width="98%" style="padding-left: 6px;">
        <span class="modulename">

        <!-- Modulename: Start -->
        {intl-module_name}
        <!-- Modulname: End -->

        </span>
        </td>
    </tr>
    </table>
    </div>
    <div class="mainbox-content">
    <table width="100%" cellpadding="4" cellspacing="0" border="0">
    <tr>
        <td>

    <!-- Main view: Start -->
