<form method="post" action="{www_dir}{index}/article/articleedit/{action_value}/{article_id}/" >

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-article_name}</p>
<input type="text" name="Name" size="40" value="{article_name}" />

<p class="boxtext">{intl-category}</p>
<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">Tekst:</p>
<textarea name="Contents[]" cols="40" rows="15" wrap="soft">{article_contents_0}</textarea>
<br /><br />

<p class="boxtext">Pris:</p>
<textarea name="Contents[]" cols="40" rows="5" wrap="soft">{article_contents_1}</textarea>
<br /><br />


<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Image" value="{intl-pictures}" />
<input class="stdbutton" type="submit" name="Preview" value="{intl-preview}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input  class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/article/articleedit/cancel/{article_id}/" >
	<input  class="okbutton" type="submit" value="Avbryt" />	
	</form>
	</td>
</tr>
</table>

