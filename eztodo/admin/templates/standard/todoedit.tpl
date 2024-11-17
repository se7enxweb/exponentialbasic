<form method="post" action="{www_dir}{index}/todo/todoedit/">
<h1>{head_line}</h1>

<hr noshade size="4"/>

<br>

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="br" colspan="2">
	<p class="boxtext">{intl-title}</p>
	<input type="text" size="30" name="Title" value="{title}">
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">{intl-user}</p>
	<select name="UserID">{user_select}</select>
	<br><br>
	</td>
	<td class="br">
	<p class="boxtext">{intl-owner}</p>
	<select name="OwnerID">{owner_select}</select>
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class=boxtext>{intl-date}</p>
	<span class="small">Klokkeslett:</span> <input size="2" type="text" name="Hour" value="{hour}">:<input size="2" type="text" name="Minute" value="{hour}">&nbsp;
	<span class="small">Dato:</span> <input size="2" type="text" name="Mnd" value="{mnd}">-<input size="2" type="text" name="Day" value="{day}">&nbsp;
	<span class="small">År:</span> <input size="4" type="text" name="Year" value="2000">&nbsp;
	<br><br>
	</td>
	<td class="br">
	<p class="boxtext">Gitt dato:</p>xx.xx.2000
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">{intl-cat}</p>
	<select name="CategoryID">{category_select}</select>
	<br><br>
	</td>
	<td class="br">
	<p class="boxtext">{intl-pri}</p>
	<select name="PriorityID">{priority_select}</select>
	<br><br>
	</td>
</tr>
<tr>
	<td class="br" colspan="2">
	<p class="boxtext">{intl-desc}</p>
	<textarea cols="30" rows="10" name="Text">{text}</textarea>
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">Status:</p>
	<div class="check"><input type="checkbox" name="Status" {status}>&nbsp;{intl-status}</div>
	</td>
	<td class="br">
	<p class="boxtext">Visning:</p>
	<div class="check"><input type="checkbox" name="Permission" {permission}>&nbsp;{intl-public}</div>
	</td>
</tr>
</table>

<input type="hidden" name="TodoID" value="{todo_id}">
<input type="hidden" name="Action" value="{action_value}">

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{submit_text}">

</form>
