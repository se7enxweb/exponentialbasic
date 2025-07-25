<!-- BEGIN group_list_tpl -->
<h1>{intl-group_editor}</h1>

<hr size="4" noshade="noshade" />
<br />

<table width="100%" cellpadding="3" cellspacing="3" border="0">
<tr>
	<th>
	{intl-group_name}:
	</th>
	<th>
	{intl-group_editors}:
	</th>
</tr>
<!-- BEGIN group_item_tpl -->
<tr valign="top">
  <td class="{bgcolor}">
    <a href="/groupeventcalendar/editor/edit/{group_id}/">{group_name}</a>
  </td>

  <td class="{bgcolor}">
    <!-- BEGIN editor_list_tpl -->
    <ul>
      <!-- BEGIN editor_tpl -->
      <li>{group_editor_fname} {group_editor_lname}</li>
      <!-- END editor_tpl -->
    </ul>
    <!-- END editor_list_tpl -->

    <!-- BEGIN no_editor_tpl -->
      <b>{intl-no_editor}</b>
    <!-- END no_editor_tpl -->
  </td>
</tr>

<!-- END group_item_tpl -->
</tr>
</table>
<br />
<!-- END group_list_tpl -->

<!-- BEGIN group_edit_tpl -->
<h1>{intl-group_editor}: &nbsp;{group_name}</h1>

<hr size="4" noshade="noshade" />
<br />

<form action="/groupeventcalendar/editor/edit/{group_id}/" method="post">

<!-- BEGIN editor_name_list_tpl -->
<table width="400" cellpadding="3" cellspacing="3" border="0">
  <tr>
    <th>
      {intl-member_name}:
    </th>
    <td>
      &nbsp;
    </td>
  </tr>

  <!-- BEGIN editor_name_item_tpl -->
  <tr>
    <td class="{bgcolor}">

      <input type="hidden" name="IDArray[]" value="{editor_id}" />
      <select name="MemberID[]">
	<option value=""></option>
      <!-- BEGIN editor_name_tpl -->
	  <option {user_is_selected} value="{group_editor_user_id}">{group_editor_user_fname} {group_editor_user_lname}</option>
      <!-- END editor_name_tpl -->
      </select>

    </td>

    <td class="{bgcolor}" width="1%">
      <input type="checkbox" name="RemoveMemberIdArray[]" value="{editor_id}" />
    </td>
  </tr>
  <!-- END editor_name_item_tpl -->

</table>
<!-- END editor_name_list_tpl -->

<!-- BEGIN none_selected_tpl -->
<h3>{intl-none_selected}</h3>
<!-- END none_selected_tpl -->

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="NewEditor" value="{intl-new}" />
<input class="stdbutton" type="submit" name="DeleteEditor" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>
<!-- END group_edit_tpl -->
