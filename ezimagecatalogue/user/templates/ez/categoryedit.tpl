
<form method="post" action="{www_dir}{index}/imagecatalogue/category/{action_value}/{category_id}" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-category_edit}</h1>


<hr noshade="noshade" size="4" />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>
    <!-- BEGIN error_write_permission -->
    <li>{intl-error_write_permission}
    <!-- END error_write_permission -->

    <!-- BEGIN error_name_tpl -->
    <li>{intl-error_name}
    <!-- END error_name_tpl -->

    <!-- BEGIN error_description_tpl -->
    <li>{intl-error_description}
    <!-- END error_description_tpl -->

    <!-- BEGIN error_read_check_tpl -->
    <li>{intl-error_read_check}
    <!-- END error_read_check_tpl -->

    <!-- BEGIN error_write_check_tpl -->
    <li>{intl-error_write_check}
    <!-- END error_write_check_tpl -->

</ul>

<hr noshade size="4"/>

<br />
<!-- END errors_tpl -->

<p class="boxtext">{intl-category}:</p>

<select name="ParentID">
<option value="0" {selected}>{intl-root_level}</option>
<!-- BEGIN value_tpl -->
<option value="{option_value}" {is_selected}>{option_level}{option_name}</option>
<!-- END value_tpl -->

</select>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <p class="boxtext">{intl-category_name}:</p>
    <input type="text" size="40" name="Name" value="{category_name}"/>

    <p class="boxtext">{intl-category_description}:</p>
	
	<textarea name="Description" cols="40" rows="5" wrap="soft">{category_description}</textarea>
	
    </td>
</tr>
<tr>
    <td>
    <br />
    <p class="boxtext">{intl-read_permissions}</p>
    <input type="radio" name="Read" value="User" {user_read_checked} /> {intl-user}
    <input type="radio" name="Read" value="Group" {group_read_checked} />{intl-group}
    <input type="radio" name="Read" value="All" {all_read_checked} /> {intl-all}
    <br /><br />
    </td>
</tr>
<tr>    
    <td>
    <p class="boxtext">{intl-write_permissions}</p>
    <input type="radio" name="Write" value="User" {user_write_checked} /> {intl-user}
    <input type="radio" name="Write" value="Group" {group_write_checked} /> {intl-group}
    <input type="radio" name="Write" value="All" {all_write_checked} /> {intl-all}
    <br /><br />
    </td>
</tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ArticleID" value="{article_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />

	</td>
	<td>&nbsp;</td>
	<td>

	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

	</td>

</tr>
</table>

</form>


