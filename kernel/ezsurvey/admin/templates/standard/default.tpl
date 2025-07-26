<form action="{www_dir}{index}/survey/default/{survey_id}/{question_id}" method="post">

<h1>{intl-default}</h1>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN value_list_tpl -->
<table width="100%" class="list" border="0" cellspacing="0" cellpadding="4">
    <!-- BEGIN yesno_tpl -->
    <tr>
        <td class="{td_class}">
          &nbsp;&nbsp;&nbsp;<input type="radio" name="Value[]" value="Y" {selected_y}> Yes<br />
          &nbsp;&nbsp;&nbsp;<input type="radio" name="Value[]" value="N" {selected_n}> No
        </td>
    </tr>
    <!-- END yesno_tpl -->
    <!-- BEGIN text_tpl -->
    <tr>
        <td>
          <input type="text" size="{size}" name="Value[]" value="{default}">
        </td>
    </tr>
    <!-- END text_tpl -->
    <!-- BEGIN essay_tpl -->
    <tr>
        <td>
          <textarea name="Value[]" rows="5" cols="60" wrap="virtual">{default}</textarea>
        </td>
    </tr>
    <!-- END essay_tpl -->
    <!-- BEGIN radio_tpl -->
    <tr>
        <td class="{td_class}">
          <input type="radio" name="Value[]" value="{value}" {selected} />{value_name}
        </td>
    </tr>
    <!-- END radio_tpl -->
    <!-- BEGIN checkbox_tpl -->
    <tr>
        <td class="{td_class}">
          <input type="checkbox" name="Value[]" value="{value}" {selected} />{value_name}
        </td>
    </tr>
    <!-- END checkbox_tpl -->
    <!-- BEGIN dropdown_tpl -->
    <tr>
        <td class="{td_class}">
          <select name="Value[]">
          <!-- BEGIN dropdown_item_tpl -->
            <option value="{value}" {selected}>{value_name}</option>
          <!-- END dropdown_item_tpl -->
          </select>
        </td>
    </tr>
    <!-- END dropdown_tpl -->
    <!-- BEGIN numeric_tpl -->
    <tr>
        <td>
          <input type="text" size="{size}" name="Value[]" value="{value}">
        </td>
    </tr>
    <!-- END numeric_tpl -->
</table>
<!-- END value_list_tpl -->

<br />
<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Store" value="{intl-store}" />&nbsp;
<input class="okbutton" type="submit" name="Clear" value="{intl-clear}" />

</form>
