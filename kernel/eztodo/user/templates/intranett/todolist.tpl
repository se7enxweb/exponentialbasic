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

<div onLoad="MM_preloadImages('{www_dir}/eztodo/images/redigerminimrk.gif','{www_dir}/eztodo/images/slettminimrk.gif')"></div>

<h1>{intl-todo_overview}</h1>

<hr noshade size="4">

<form method="post" action="{www_dir}{index}/todo/todolist/">
<p class="boxtext">{intl-user}:</p>
<select name="GetByUserID">
<!-- BEGIN user_item_tpl -->
<option {user_is_selected} value="{user_id}">{user_firstname} {user_lastname}</option>
<!-- END user_item_tpl -->
</select>

<input type="hidden" name="Action" value="ShowTodosByUser">
<input class="stdbutton" type="submit" value="{intl-show}">

<br />

<!--
<select name="Show">
<option {all_selected} value="All">{intl-show_all}</option>
<option {not_done_selected} value="All">{intl-show_not_done}</option>
<option {done_selected} value="All">{intl-show_done}</option>
</select>
-->

<select name="StatusTodoID">
<option {is_selected} value="0">{intl-status_all}</option>
<!-- BEGIN status_item_tpl -->
<option {is_selected} value="{status_id}">{status_name}</option>
<!-- END status_item_tpl -->
</select>


&nbsp;
<select name="CategoryTodoID">
<option {is_selected} value="0">{intl-category_all}</option>
<!-- BEGIN category_item_tpl -->
<option {is_selected} value="{category_id}">{category_name}</option>
<!-- END category_item_tpl -->
</select>
<input class="stdbutton" type="submit" name="ShowButton" value="{intl-show}" />

</form>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-category}:</th>
	<th>{intl-date}:</th>
	<th>{intl-priority}:</th>
	<th>{intl-view}:</th>
	<th>{intl-status}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN todo_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/todo/todoview/{todo_id}/">{todo_name}</a>
	</td>

	<td class="{td_class}">
	{todo_category_id}
	</td>

	<td class="{td_class}">
	<span class="small">{todo_date}</span>
	</td>

	<td class="{td_class}">
	{todo_priority_id}
	</td>

	<td class="{td_class}">
	{todo_permission}
	</td>

	<td class="{td_class}">
	{todo_status}
	</td>

	<td class="{td_class}">
	<a href="{www_dir}{index}/todo/todoedit/edit/{todo_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('et{todo_id}-red','','/eztodo/images/redigerminimrk.gif',1)"><img name="et{todo_id}-red" border="0" src="{www_dir}/eztodo/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/todo/todoedit/delete/{todo_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('et{todo_id}-slett','','/eztodo/images/slettminimrk.gif',1)"><img name="et{todo_id}-slett" border="0" src="{www_dir}/eztodo/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END todo_item_tpl -->

<!-- BEGIN no_found_tpl -->
<tr>
	<td>
	<p class="error">{intl-noitem}</p>
	</td>
</tr>
<!-- END no_found_tpl -->
</table>

<form action="{www_dir}{index}/todo/todoedit/new">

<hr noshade size="4">

<input class="okbutton" type="submit" value="{intl-newtodo}">
</form>
