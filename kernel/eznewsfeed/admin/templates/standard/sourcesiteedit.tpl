<form method="post" action="{www_dir}{index}/newsfeed/sourcesite/{action_value}/{source_site_id}">

<h1>{intl-sourcesite_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-source_site_name}:</p>
<input type="text" class="box" size="40" name="SourceSiteName" value="{source_site_name_value}" />

<p class="boxtext">{intl-source_site_category}:</p>
<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">{intl-source_site_url}:</p>
<input type="text" class="box" size="40" name="SourceSiteURL" value="{source_site_url_value}" />

<p class="boxtext">{intl-source_site_login}:</p>
<input type="text" class="box" size="40" name="SourceSiteLogin" value="{source_site_login_value}" />

<p class="boxtext">{intl-source_site_password}:</p>
<input type="text" class="box" size="40" name="SourceSitePassword" value="{source_site_password_value}" />

<p class="boxtext">{intl-source_site_decoder}:</p>
<!--<input type="text" size="40" name="SourceSiteDecoder" value="{source_site_decoder_value}" />-->

<select name="DecoderChoice">
<!-- BEGIN decoder_tpl -->
<option value="{decoder_value}" {choice_selected}>{decoder_value}</option>
<!-- END decoder_tpl -->
</select>

<br /><br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
  	<td>
	<p class="boxtext">{intl-source_site_isactive}:</p>
	<input {source_site_isactive_value} type="checkbox" name="SourceSiteIsActive" />
	</td>
	<td>
	<p class="boxtext">{intl-source_auto_publish}:</p>
	<input {source_site_auto_publish_value} type="checkbox" name="SourceSiteAutoPublish" />
	</td>
</tr>
</table>

<br />
<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td>
    <input type="submit" class="okbutton" value="{intl-ok}" />
  </td>
  <td>&nbsp</td>
  <td>
    <input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
  </td>
</tr>
</table>

</form>