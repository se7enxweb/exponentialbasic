<h1>{intl-todo_overview}</h1>

<hr noshade size="4">

<form method="post" action="{www_dir}{index}/todo/todolist/">
<p class="boxtext">{intl-user}:</p>
<select name="GetByUserID">
<!-- BEGIN user_item_tpl -->
<option value="{user_id}" {user_is_selected}>{user_firstname} {user_lastname}</option>
<!-- END user_item_tpl -->
</select>

<input type="hidden" name="Action" value="ShowTodosByUser">
<input class="stdbutton" type="submit" name="Show" value="{intl-show}">

<br /><br />

<select name="StatusTodoID">
<option value="0" {all_selected}>{intl-status_all}</option>
<!-- BEGIN status_item_tpl -->
<option value="{status_id}" {is_selected}>{status_name}</option>
<!-- END status_item_tpl -->
</select>


&nbsp;
<select name="CategoryTodoID">
<option value="0" {category_selected}>{intl-category_all}</option>
<!-- BEGIN category_item_tpl -->
<option value="{category_id}" {is_selected}>{category_name}</option>
<!-- END category_item_tpl -->
</select>
<input class="stdbutton" type="submit" name="ShowButton" value="{intl-show}" />
</form>
<br />

<form action="{www_dir}{index}/todo/">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-category}:</th>
	<th>{intl-date}:</th>
	<th>{intl-priority}:</th>
	<th>{intl-is_public}:</th>
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
	<span class="small">{todo_category_id}</span>
	</td>

	<td class="{td_class}">
	<span class="small">{todo_date}</span>
	</td>

	<td class="{td_class}">
	<span class="small">{todo_priority_id}</span>
	</td>

	<!-- BEGIN todo_is_public_tpl -->
	<td class="{td_class}">
	<span class="small">{intl-todo_is_public}</span>
	</td>
	<!-- END todo_is_public_tpl -->

	<!-- BEGIN todo_is_not_public_tpl -->
	<td class="{td_class}">
	<span class="small">{intl-todo_is_not_public}</span>
	</td>
	<!-- END todo_is_not_public_tpl -->

	<td class="{td_class}">
	<span class="small">{todo_status}</span>
	</td>

	<td class="{td_class}">
	<a href="{www_dir}{index}/todo/todoedit/edit/{todo_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('et{todo_id}-red','','/eztodo/images/redigerminimrk.gif',1)"><img name="et{todo_id}-red" border="0" src="{www_dir}/eztodo/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}">
<!--	<a href="{www_dir}{index}/todo/todoedit/delete/{todo_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('et{todo_id}-slett','','/eztodo/images/slettminimrk.gif',1)"><img name="et{todo_id}-slett" border="0" src="{www_dir}/eztodo/images/slettmini.gif" width="16" height="16" align="top"></a> -->
         <input type="checkbox" name="DeleteArrayID[]" value="{todo_id}" />
	</td>
</tr>
<!-- END todo_item_tpl -->

<!-- BEGIN no_found_tpl -->
<tr>
	<td colspan="8">
	<h2>{intl-noitem}</h2>
	</td>
</tr>
<!-- END no_found_tpl -->
</table>



<hr noshade size="4">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td>
    <input class="stdbutton" name="New" type="submit" value="{intl-newtodo}" />
  </td>
  <td>&nbsp;</td>
  <td>
    <input class="stdbutton" name="Delete" type="submit" value="{intl-delete_selected}" />
  </td>
</tr>
</table>
</form>
