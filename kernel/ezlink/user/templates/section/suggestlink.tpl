<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-headline}</h1>

<form method="post" action="{www_dir}{index}/link/suggestlink/insert">

<p class="error">{error_msg}</p>

<p class="boxtext">{intl-name}:</p>
<input tabindex="1" class="box" type="text" name="title" size="40" value="{title}">

<p class="boxtext">{intl-category}:</p>
<select name="linkgroup">
	<!-- BEGIN group_select_tpl -->
	<option {is_selected} value="{grouplink_id}">{grouplink_title}</option>
	<!-- END group_select_tpl -->
</select>

<p class="boxtext">{intl-url}:</p>

http://<input tabindex="2" class="box" type="text" name="url" size="40" value="{url}"><br />
<br />

<input class="stdbutton" type="submit" value="{intl-meta}" name="GetSite" />

<p class="boxtext">{intl-keywords}:</p>

<textarea wrap="soft" class="box" rows="5" cols="40" name="keywords">{keywords}</textarea>

<br />

<p class="boxtext">{intl-description}:</p>
<textarea wrap="soft" class="box" rows="5" cols="40" name="description">{description}</textarea>
<br /><br />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-ok}">
	</td>
	<td>&nbsp;</td>
	</form>
	<td>
	<form action="{www_dir}{index}/link/group/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>
