<h1>{intl-configure_filter}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="{www_dir}{index}/mail/filteredit/{current_filter_id}" enctype="multipart/form-data" >

<b>{intl-if} </b>
<select name="HeaderSelect">
<!-- BEGIN header_item_tpl -->
<option value="{header_id}" {is_selected}>{header_name}</option>
<!-- END header_item_tpl -->
</select>
<br />
<br />
<select name="CheckSelect">
<!-- BEGIN check_item_tpl -->
<option value="{check_id}" {is_selected}>{check_name}</option>
<!-- END check_item_tpl -->
</select>
&nbsp;
<input type="text" size="40" name="Match" value="{match_value}"/>

<br/><br />
<b>{intl-move_to} </b>
<select name="FolderSelectID">
   <!-- BEGIN folder_item_tpl -->
   <option value="{folder_id}" {is_selected}>{folder_name}</option>
   <!-- END folder_item_tpl -->
</select>


<br />
<hr noshade="noshade" size="4">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" name="Ok" value="{intl-ok}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>

</form>