
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title>eZ publish administrasjon</title>
<link rel="stylesheet" type="text/css" href="{www_dir}{index}/templates/{site_style}/style.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>

<SCRIPT LANGUAGE="JavaScript1.2">
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
</SCRIPT> 

</head>

<body bgcolor="#777777" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6" onLoad="MM_preloadImages('{www_dir}/images/{site_style}/redigerminimrk.gif','{www_dir}/images/{site_style}/slettminimrk.gif','{www_dir}/images/{site_style}/downloadminimrk.gif')">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="repeaty" width="1" background="{www_dir}/admin/images/{site_style}/top-l02.gif" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/top-l01.gif" width="10" height="10" border="0" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/admin/images/{site_style}/top-m01.gif" valign="absmiddle" bgcolor="#b5b5b5" align="left"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/admin/images/{site_style}/top-m01.gif" valign="absmiddle" bgcolor="#b5b5b5" align="left"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" /><br /></td>
    <td class="repeaty" width="1" background="{www_dir}/admin/images/{site_style}/top-r02.gif" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/top-r01.gif" width="10" height="10" border="0" /><br /></td>
</tr>
<tr>
    <td class="repeaty" width="1" background="{www_dir}/admin/images/{site_style}/top-l02.gif" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="10" height="10" border="0" /><br /></td>
    <td class="repeatx" colspan="2" width="98%" valign="absmiddle" bgcolor="#b5b5b5" align="left">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="1%" class="tdmini">
	<a href="http://www.tradecentrum.com" target="_vblank"><img src="{www_dir}/admin/images/{site_style}/tradecentrum-logo.gif" width="300" height="40" border="0" alt="" /></a><br />
	</td>
	<td width="92%">
	<img src="{www_dir}/admin/images/1x1.gif" width="20" height="10" border="0" alt="" />
	</td>
	<td width="1%">
	<span class="top">{intl-site_url}:</span><br />
	<span class="topusername">{site_url}</span><br />
	<img src="{www_dir}/admin/images/1x1.gif" width="120" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="20" height="10" border="0" alt="" /></td>
	<td width="1%">
	<span class="top">{intl-user_name}:</span><br />
	<span class="topusername">{first_name}&nbsp;{last_name}</span><br />
	<img src="{www_dir}/admin/images/1x1.gif" width="120" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="20" height="10" border="0" alt="" /></td>
	<td width="1%">
	<img src="{www_dir}/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/user/passwordchange/">{intl-change_user_info}</a><br />
	<img src="{www_dir}/admin/images/1x1.gif" width="150" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="20" height="10" border="0" alt="" /></td>
	<td width="1%" align="right">
	<a  href="{www_dir}{index}/user/login/logout/"><img src="{www_dir}/admin/images/{site_style}/top-logout.gif" width="35" height="40" border="0" alt="" /></a>
	</td>
</tr>
</table>

	</td>
    <td class="repeaty" width="%" background="{www_dir}/admin/images/{site_style}/top-r02.gif" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="10" height="10" border="0" /><br /></td>
</tr>
<tr>
    <td class="repeaty" width="1" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/top-l03.gif" width="10" height="10" border="0" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/admin/images/{site_style}/top-m02.gif" valign="absmiddle" align="left" bgcolor="#b5b5b5"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/admin/images/{site_style}/top-m02.gif" valign="absmiddle" align="left" bgcolor="#b5b5b5"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" /><br /></td>
    <td class="repeaty" width="1" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/top-r03.gif" width="10" height="10" border="0" /><br /></td>
</tr>
</table>
<img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="6" height="6" border="0" /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="1%" valign="top">

