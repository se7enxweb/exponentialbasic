<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
  <title>{intl-header}: {site_url}</title>
  <meta http-equiv="Content-Type" content="text/html; charset={charset}"/>

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
	
	function SwitchCharset()
	{
	    CharsetSwitch.submit();
	}
  //-->
  </script> 

  <link rel="stylesheet" type="text/css" href="{www_dir}/design/admin/templates/{site_style}/style.css" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>

<body bgcolor="#777777" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6" onload="MM_preloadImages('{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif','{www_dir}/design/admin/images/{site_style}/slettminimrk.gif','{www_dir}/design/admin/images/{site_style}/downloadminimrk.gif','{www_dir}/design/admin/images/{site_style}/imagemapminimrk.gif')">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="repeaty" width="1" background="{www_dir}/design/admin/images/{site_style}/top-l02.gif" valign="top" align="left"><img src="{www_dir}/design/admin/images/{site_style}/top-l01.gif" width="10" height="10" border="0" alt="" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/design/admin/images/{site_style}/top-m01.gif" valign="absmiddle" bgcolor="#b5b5b5" align="left"><img src="{www_dir}/design/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" alt="" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/design/admin/images/{site_style}/top-m01.gif" valign="absmiddle" bgcolor="#b5b5b5" align="left"><img src="{www_dir}/design/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" alt="" /><br /></td>
    <td class="repeaty" width="1" background="{www_dir}/design/admin/images/{site_style}/top-r02.gif" valign="top" align="left"><img src="{www_dir}/design/admin/images/{site_style}/top-r01.gif" width="10" height="10" border="0" /><br /></td>
</tr>
<tr>
    <td class="repeaty" width="1" background="{www_dir}/design/admin/images/{site_style}/top-l02.gif" valign="top" align="left"><img src="{www_dir}/design/admin/images/{site_style}/1x1.gif" width="10" height="10" border="0" alt="" /><br /></td>
    <td class="repeatx" colspan="2" width="98%" valign="absmiddle" bgcolor="#b5b5b5" align="left">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="1%" class="tdmini">
	<a href="{admin_site_protocol}://{admin_site_host}" target=""><img src="{www_dir}/design/base/images/logo/ezpublish-logo-200x40.png" width="200" height="40" border="0" alt="" /></a><br />
	</td>
	<td width="1%"><img src="{www_dir}/design/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<!-- BEGIN charset_switch_tpl -->
	<td width="91%" valign="top">
	<form action="{charset_submit_url}" method="post" name="CharsetSwitch">
	<select name="page_charset" onchange="SwitchCharset()">
            <!-- BEGIN charset_switch_item_tpl --> 
	    <option value="{charset_code}" {charset_selected}>{charset_description}</option>
	    <!-- END charset_switch_item_tpl -->
        </select>
        <input type="submit" class="stdbutton" value="Set" />
	</form>
	</td>
        <!-- END charset_switch_tpl -->
	<td width="1%">&nbsp;</td>
	<td width="1%">
	<span class="top">{intl-ezpublish_version}:</span><br />
	<span class="topusername">{ezpublish_version}</span>.<span class="topusername">{ezpublish_installation_version}</span><br />
	<img src="{www_dir}/design/admin/images/1x1.gif" width="80" height="10" border="0" alt="" /><br />
	</td>	
	<td width="1%"><img src="{www_dir}/design/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%">
	<span class="top">{intl-site_url}:</span><br />
	<span class="topusername">{site_url}</span><br />
	<img src="{www_dir}/design/admin/images/1x1.gif" width="100" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/design/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%">
	<span class="top">{intl-user_name}:</span><br />
	<span class="topusername">{first_name}&nbsp;{last_name}</span><br />
	<img src="{www_dir}/design/admin/images/1x1.gif" width="80" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/design/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%">
	<span class="top">{intl-ip_address}:</span><br />
	<span class="topusername">{ip_address}</span><br />
	<img src="{www_dir}/design/admin/images/1x1.gif" width="80" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/design/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%" valign="top">
	<img src="{www_dir}/design/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/user/passwordchange/">{intl-change_user_info}</a><br />
	<img src="{www_dir}/design/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/user/settings?RefURL={ref_url}">{intl-user_settings}</a><br />
	<img src="{www_dir}/design/admin/images/1x1.gif" width="100" height="1" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/design/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%" align="left">
	<a  href="{www_dir}{index}/user/login/logout/"><img src="{www_dir}/design/admin/images/{site_style}/top-logout.gif" width="35" height="40" border="0" alt="logout" title="logout" /></a>
	</td>
</tr>
</table>

<!-- BEGIN module_list_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<!-- BEGIN module_item_tpl -->
	<td align="center"><a href="{www_dir}{index}/module/{module_action}/{ez_module_name}?RefURL={ref_url}"><img src="{www_dir}/{ez_dir_name}/admin/images/module_icon.png" width="32" height="32" border="0" alt="{module_name}" title="{module_name}" /></a></td>
<!-- END module_item_tpl -->
<!-- BEGIN module_control_tpl -->
	<td>&nbsp;&nbsp;</td>
	<td align="left">
	<img src="{www_dir}/design/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/module/activate/all?RefURL={ref_url}">{intl-all}</a><br />
	<img src="{www_dir}/design/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/module/activate/none?RefURL={ref_url}">{intl-none}</a>
	</td>
<!-- END module_control_tpl -->
</tr>
</table>
<!-- END module_list_tpl -->
	
	</td>
    <td class="repeaty" width="%" background="{www_dir}/design/admin/images/{site_style}/top-r02.gif" valign="top" align="left"><img src="{www_dir}/design/admin/images/{site_style}/1x1.gif" width="10" height="10" border="0" alt="" /><br /></td>
</tr>
<tr>
    <td class="repeaty" width="1" valign="top" align="left"><img src="{www_dir}/design/admin/images/{site_style}/top-l03.gif" width="10" height="10" border="0" alt="" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/design/admin/images/{site_style}/top-m02.gif" valign="absmiddle" align="left" bgcolor="#b5b5b5"><img src="{www_dir}/design/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" alt="" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/design/admin/images/{site_style}/top-m02.gif" valign="absmiddle" align="left" bgcolor="#b5b5b5"><img src="{www_dir}/design/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" alt="" /><br /></td>
    <td class="repeaty" width="1" valign="top" align="left"><img src="{www_dir}/design/admin/images/{site_style}/top-r03.gif" width="10" height="10" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td colspan="4" class="tdmini"><img src="{www_dir}/design/admin/images/{site_style}/1x1.gif" width="6" height="6" border="0" alt="" /><br /></td>
</tr>
</table>


<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<!-- BEGIN menu_tpl -->

	<td width="1%" valign="top">

<!-- END menu_tpl -->

<!-- Menues: Start -->
