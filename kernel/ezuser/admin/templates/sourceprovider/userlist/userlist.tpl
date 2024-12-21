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

<div onLoad="MM_preloadImages('../images/redigerminimrk.gif','../images/slettminimrk.gif')"></div>

<h1>{intl-head_line}</h1>

<hr noshade size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">



<tr>
	<td>

	<form method="post" action="{www_dir}{index}/user/userlist/">

	<select name="GroupID">
	<option value="0">Alle</option>
	<!-- BEGIN group_item_tpl -->
	<option {is_selected} value="{group_id}">{group_name}</option>
	<!-- END group_item_tpl -->
	</select>
	<input class="stdbutton" type="submit" value="Vis">

	</form>


	</td>
</tr>

<tr>
	<th>
	{intl-name}
	</th>

	<th>
	{intl-email}
	</th>


	<th>
	{intl-login}
	</th>

	<th>
	&nbsp;
	</th>

	<th>
	&nbsp;
	</th>

</tr>
<!-- BEGIN user_item_tpl -->
<tr>
	<td class="{td_class}">
	{first_name} {last_name}
	</td>

	<td class="{td_class}">
	{email}
	</td>

	<td class="{td_class}">
	{login_name}
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/user/useredit/edit/{user_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{user_id}-red','','/admin/images/redigerminimrk.gif',1)"><img name="ezuser{user_id}-red" border="0" src="{www_dir}/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/user/useredit/delete/{user_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{user_id}-slett','','/admin/images/slettminimrk.gif',1)"><img name="ezuser{user_id}-slett" border="0" src="{www_dir}/admin/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	
</tr>
<!-- END user_item_tpl -->

</table>
