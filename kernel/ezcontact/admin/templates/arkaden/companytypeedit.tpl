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
<h1>{intl-headline_edit}</h1>
<!-- BEGIN path_tpl -->
<hr noshade="noshade" size="4" />

<img src="{www_dir}/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/0">{intl-root_category}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/admin/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_view}/{parent_id}">{parent_name}</a>
<!-- END path_item_tpl -->

<!-- BEGIN current_path_item_tpl -->
<img src="{www_dir}/admin/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_view}/{parent_id}">{intl-current_edit}</a>
<!-- END current_path_item_tpl -->

<hr noshade="noshade" size="4" />
<!-- END path_tpl -->

<!-- BEGIN current_type_tpl -->
<form method="post" action="{www_dir}{index}/{intl-module_name}/{command_type}/{action_value}/{current_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">
<p class="boxtext">{intl-th_type_name}:</p>
<input type="text" size="40" name="TypeName" value="{current_name}">

<p class="boxtext">{intl-th_type_parent_name}:</p>
<select size="10" name="SelectParentID">

<option {root_selected} value="0">{intl-root_category}</option>
<!-- BEGIN parent_item_tpl -->
<option {selected} value="{select_parent_id}">{select_parent_name}</option>
<!-- END parent_item_tpl -->

</select>

<p class="boxtext">{intl-th_type_description}:</p>
<textarea rows="5" cols="40" name="TypeDescription" wrap="soft">{current_description}</textarea>
<input type="hidden" name="TypeID" value="{current_id}">
<input type="hidden" name="OldParentID" value="{parent_id}">

<!-- BEGIN image_item_tpl -->
<p class="boxtext">{intl-th_type_current_image}:</p>
<p><img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
</p>
<p>{intl-th_image_uploading_new_replaces_old}</p>
<!-- END image_item_tpl -->
<p class="boxtext">{intl-th_type_image}:</p>
<!-- BEGIN no_image_item_tpl -->
<br />
<!-- END no_image_item_tpl -->
<input size="40" name="ImageFile" type="file" />
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="{intl-command_ok}" value="{intl-button_ok}" />
</form>
<hr noshade="noshade" size="4" />

<form method="post" action="{www_dir}{index}/{intl-module_name}/{command_type}/{intl-command_list}/{parent_id}/">
<input class="okbutton" type="submit" name="{intl-command_back}" value="{intl-button_back}" />
</form>
<!-- END current_type_tpl -->
