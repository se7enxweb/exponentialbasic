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

<div onLoad="MM_preloadImages('{www_dir}/ezcontact/admin/images/redigerminimrk.gif','{www_dir}/ezcontact/admin/images/slettminimrk.gif')"></div>

<h1>{intl-headline}</h1>aaa

<!-- BEGIN path_tpl -->

<img src="{www_dir}/ezarticle/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="{www_dir}{index}/contact/company/list/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="{www_dir}/ezarticle/admin/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="{www_dir}{index}/contact/company/list/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade size="4"/>

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h2>{categories}</h2>
	</td>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/contact/company/list/{category_id}">{category_name}&nbsp;</a>
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/companytype/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{category_id}-red','','{www_dir}/admin/images/redigerminimrk.gif',1)"><img name="ezuser{category_id}-red" border="0" src="{www_dir}/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/companytype/delete/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{category_id}-slett','','{www_dir}/admin/images/slettminimrk.gif',1)"><img name="ezuser{category_id}-slett" border="0" src="{www_dir}/admin/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_list_tpl -->


<!-- BEGIN company_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<h2>{companys}</h2>
</tr>
<!-- BEGIN company_item_tpl -->
<tr>
	<td class="{td_class}">
	{company_name}
	</td>
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/company/edit/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{company_id}-red','','{www_dir}/admin/images/redigerminimrk.gif',1)"><img name="ezuser{company_id}-red" border="0" src="{www_dir}/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/company/delete/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{company_id}-slett','','{www_dir}/admin/images/slettminimrk.gif',1)"><img name="ezuser{company_id}-slett" border="0" src="{www_dir}/admin/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	
</tr>
<!-- END company_item_tpl -->
</table>
<!-- END company_list_tpl -->
