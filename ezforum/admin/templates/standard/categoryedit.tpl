<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/forum/search/" method="post">
	       <input size="12" type="text" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/forum/categoryedit/{action_value}/{category_id}/" method="post">
<input type="hidden" name="page" value="{docroot}/admin/category.php">

<p class="error">{error_msg}</p>

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" value="{category_name}" name="Name">

<p class="boxtext">{intl-description}:</p>
<input type="text" class="box" size="40" value="{category_description}" name="Description">

<p class="boxtext">{intl-section_select}:</p>
<select name="SectionID">
<!-- BEGIN section_item_tpl -->
<option value="{section_id}" {section_is_selected}>{section_name}</option>
<!-- END section_item_tpl -->
</select>

<br /><br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="add" value="{intl-ok}">
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/forum/categorylist/">
	<input class="okbutton" type="submit" value="{intl-cancel}">
	</form>
	</td>
</tr>
</table>


