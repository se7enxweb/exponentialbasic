<form method="post" action="{www_dir}{index}/article/articleedit/{action_value}/{article_id}/" >

<h1>{intl-head_line}</h1>

<!-- BEGIN error_message_tpl -->
<h3 class="error">{intl-error_parsing_xml}</h3>
<!-- END error_message_tpl -->

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-article_name}:</p>
	<input type="text" name="Name" size="40" value="{article_name}" />
	</td>
</tr>
</table>

<input type="hidden" name="CategoryID" value="3" />
<input type="hidden" name="IsPublished" value="on" />

<input type="hidden" name="Contents[]" value="" />

<p class="boxtext">{intl-contents}:</p>
<textarea name="Contents[]" cols="40" rows="20" wrap="soft">{article_contents_1}</textarea>
<br /><br />



<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input  class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/article/articleedit/cancel/{article_id}/" >
	<input  class="okbutton" type="submit" value="{intl-cancel}" />	
	</form>
	</td>
</tr>
</table>

