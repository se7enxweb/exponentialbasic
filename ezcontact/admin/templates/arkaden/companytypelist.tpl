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
<!-- BEGIN list_tpl -->
<h1>{intl-headline_list}</h1>
<!-- END list_tpl -->
<!-- BEGIN view_tpl -->
<h1>{intl-headline_view}</h1>
<!-- END view_tpl -->

<!-- BEGIN path_tpl -->
<hr noshade="noshade" size="4" />

<img src="{www_dir}/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/0">{intl-root_category}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/admin/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{parent_id}">{parent_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />
<!-- END path_tpl -->

<!-- BEGIN current_type_tpl -->
<h3>{current_name}</h3>
<p>{current_description}</p>
<!-- BEGIN image_item_tpl -->
<p class="boxtext">{intl-th_type_current_image}:</p>
<p><img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
</p>
<!-- END image_item_tpl -->
<!-- END current_type_tpl -->

<!-- BEGIN not_root_tpl -->
<p><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_edit}/{current_id}">{intl-button_edit}</a></p>
<!-- END not_root_tpl -->



<!-- BEGIN category_list_tpl -->
<h3>{intl-headline_categories}</h3>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{current_id}?OrderBy=ID">{intl-th_type_id}</a></th>
    <th><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{current_id}?OrderBy=Name">{intl-th_type_name}</a></th>
    <th><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{current_id}?OrderBy=Description">{intl-th_type_description}</a></th>
    <th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr class="{theme-type_class}">
    <td>{type_id}</td>
    <td><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{type_id}/">{type_name}</a></td>
    <td>{type_description}</td>
    <td width="1%"><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_edit}/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{type_id}-red','','{www_dir}/admin/images/redigerminimrk.gif',1)"><img name="ezuser{type_id}-red" border="0" src="{www_dir}/admin/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_delete}/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{type_id}-slett','','{www_dir}/admin/images/slettminimrk.gif',1)"><img name="ezuser{type_id}-slett" border="0" src="{www_dir}/admin/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END category_item_tpl -->

</table>
<!-- END category_list_tpl -->

<!-- BEGIN no_category_item_tpl -->
<h3>{intl-headline_no_categories}</h3>
{intl-error_no_categories}
<!-- END no_category_item_tpl -->



<!-- BEGIN type_list_tpl -->
<h3>{intl-headline_types}</h3>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{current_id}?OrderBy=ID">{intl-th_type_id}</a></th>
    <th><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{current_id}?OrderBy=Name">{intl-th_type_name}</a></th>
    <th><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{current_id}?OrderBy=Description">{intl-th_type_description}</a></th>
    <th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN type_item_tpl -->
<tr class="{theme-type_class}">
    <td>{type_id}</td>
    <td><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_view}/{type_id}/">{type_name}</a></td>
    <td>{type_description}</td>
    <td width="1%"><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_edit}/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{type_id}-red','','{www_dir}/admin/images/redigerminimrk.gif',1)"><img name="ezuser{type_id}-red" border="0" src="{www_dir}/admin/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_delete}/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{type_id}-slett','','{www_dir}/admin/images/slettminimrk.gif',1)"><img name="ezuser{type_id}-slett" border="0" src="{www_dir}/admin/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END type_item_tpl -->

</table>
<!-- END type_list_tpl -->

<!-- BEGIN no_type_item_tpl -->
<h3>{intl-headline_no_types}</h3>
{intl-error_no_types}
<!-- END no_type_item_tpl -->
