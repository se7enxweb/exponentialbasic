<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td valign="bottom">
	<h1>{head_line}</h1>
	</td>
	<td rowspan="2" align="right">
	</td>
	<td rowspan="2" align="right">
	<form action="/helloworld/" method="post">
	<input type="text" name="SearchText" class="searchbox" size="10" />  
	<input class="stdbutton" type="submit" value="Search" />
	</form> 
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{description}</div></div>

<!-- BEGIN item_list_block_tpl -->
<form method="post" action="/helloworld/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="2">{item_name_header}</th>
	<th>{item_message_header}</th>
	<th>{item_created_header}</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN item_list_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	<img src="/design/base/images/icons/document.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
	<td width="20%" class="{td_class}">
	{item_name}&nbsp;
	</td>
	<td width="50%" class="{td_class}">
	{item_message}&nbsp;
	</td>
	<td width="20%" class="{td_class}">
	<span class="small">{item_created}</span>
	</td>
	<td width="1%" class="{td_class}" align="center">
	<input type="checkbox" name="DeleteArrayID[]" value="{item_id}" />
	</td>
</tr>
<!-- END item_list_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input type="submit" class="stdbutton" Name="DeleteSelected" value="Delete selected" />
</form>
<!-- END item_list_block_tpl -->

<!-- BEGIN no_items_tpl -->
<div class="spacer"><div class="p">{no_items_text}</div></div>
<!-- END no_items_tpl -->

<hr noshade="noshade" size="4" />

<h2>{add_head_line}</h2>
<p><strong>{form_status}</strong></p>
<form method="post" action="/helloworld/">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
	<td><p class="boxtext">{name_label}:</p></td>
	<td><input type="text" name="name" class="box" size="40" /></td>
</tr>
<tr>
	<td><p class="boxtext">{message_label}:</p></td>
	<td><textarea name="message" class="box" rows="4" cols="60"></textarea></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input class="stdbutton" type="submit" value="{store_label}" /></td>
</tr>
</table>
</form>
