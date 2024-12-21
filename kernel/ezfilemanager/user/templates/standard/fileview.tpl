<table width="100%" border="0">
<tr>
	<td>
	<h1>{intl-file_view}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/filemanager/search/" method="get">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>


<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN view_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <p class="boxtext">{intl-file_name}:</p>
    {file_name}

    <p class="boxtext">{intl-file_description}:</p>
    {file_description}
    
    <p class="boxtext">{intl-file_owner}:</p>
    {file_owner}

    <p class="boxtext">{intl-file_size}:</p>
    {file_size} {file_unit}

</tr>
</table>
<!-- END view_tpl -->
<br />

<form method="post" action="{www_dir}{index}/filemanager/edit/{file_id}" enctype="multipart/form-data">

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<!-- BEGIN delete_tpl -->
	<td>
	<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}">
	</td>
	<!-- END delete_tpl -->
	<!-- BEGIN edit_tpl -->
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="Edit" value="{intl-edit}">
	</td>
	<!-- END edit_tpl -->
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN download_tpl -->
<input class="okbutton" type="submit" name="Download" value="{intl-download}">
<!-- END download_tpl -->

</form>


