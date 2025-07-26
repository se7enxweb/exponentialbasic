<form action="{www_dir}{index}/survey/preview/{survey_id}/" method="post">

<h1>{intl-preview}</h1>

<hr noshade="noshade" size="4" />

<input type="hidden" name="Page" value="{page_number}" />

<h2>{title}</h2>
<!-- BEGIN subtitle_section_tpl -->
<br />
<h3>{subtitle}</h3>
<!-- END subtitle_section_tpl -->
{info}
<table width="100%" class="list" border="0" cellspacing="0" cellpadding="4">
<!-- BEGIN value_list_tpl -->
  <tr>
    <td>{question_name}</td>
  </tr>
  <!-- BEGIN yesno_tpl -->
  <tr>
      <td>
        &nbsp;&nbsp;&nbsp;<input type="radio" name="Value[{id}]" value="Y" {selected_y}> Yes<br />
        &nbsp;&nbsp;&nbsp;<input type="radio" name="Value[{id}]" value="N" {selected_n}> No
      </td>
  </tr>
  <!-- END yesno_tpl -->
  <!-- BEGIN text_tpl -->
  <tr>
      <td>
        &nbsp;&nbsp;&nbsp;<input type="text" size="{size}" name="Value[{id}]" value="{default}">
      </td>
  </tr>
  <!-- END text_tpl -->
  <!-- BEGIN essay_tpl -->
  <tr>
      <td>
        &nbsp;&nbsp;&nbsp;<textarea name="Value[{id}]" rows="5" cols="60" wrap="virtual">{default}</textarea>
      </td>
  </tr>
  <!-- END essay_tpl -->
  <!-- BEGIN radio_tpl -->
    <!-- BEGIN radio_item_tpl -->
    <tr>
        <td>          
          &nbsp;&nbsp;&nbsp;<input type="radio" name="Value[{id}]" value="{value}" {selected} />{value_name}
        </td>
    </tr>
    <!-- END radio_item_tpl -->
    <!-- BEGIN radio_other_tpl -->
    <tr>
        <td>
          &nbsp;&nbsp;&nbsp;<input type="radio" name="Value[{id}]" value="{value}" {selected} />Other: <input type="text" />
        </td>
    </tr>
    <!-- END radio_other_tpl -->
  <!-- END radio_tpl -->
  <!-- BEGIN checkbox_tpl -->
    <!-- BEGIN checkbox_item_tpl -->
    <tr>
        <td>
          &nbsp;&nbsp;&nbsp;<input type="checkbox" name="Value[{id}]" value="{value}" {selected} />{value_name}
        </td>
    </tr>
    <!-- END checkbox_item_tpl -->
    <!-- BEGIN checkbox_other_tpl -->
    <tr>
        <td>
          &nbsp;&nbsp;&nbsp;<input type="checkbox" name="Value[{id}]" value="{value}" {selected} />Other: <input type="text" />
        </td>
    </tr>
    <!-- END checkbox_other_tpl -->
  <!-- END checkbox_tpl -->
  <!-- BEGIN dropdown_tpl -->
  <tr>
      <td>
        &nbsp;&nbsp;&nbsp;
        <select name="Value[{id}]">
        <!-- BEGIN dropdown_item_tpl -->
          <option value="{value}" {selected}>{value_name}</option>
        <!-- END dropdown_item_tpl -->
        </select>
      </td>
  </tr>
  <!-- END dropdown_tpl -->
  <!-- BEGIN rate_tpl -->
  <tr>
      <td>
        <blockquote>
          <table border="0" cellspacing="1" cellpadding="0">
            <td>&nbsp;</td>
            <td width="40" align="center" class="bglight">1</td>
            <td width="40" align="center" class="bgdark">2</td>
            <td width="40" align="center" class="bglight">3</td>
            <td width="40" align="center" class="bgdark">4</td>
            <td width="40" align="center" class="bglight">5</td>
            <!-- BEGIN rate_item_tpl -->
              <tr>
                <td>{value_name}</td>
                <td width="40" align="center" class="bglight"><input type="radio" name="Value[{id}][{id2}]" value="0"></td>
                <td width="40" align="center" class="bgdark"><input type="radio" name="Value[{id}][{id2}]" value="1"></td>
                <td width="40" align="center" class="bglight"><input type="radio" name="Value[{id}][{id2}]" value="2"></td>
                <td width="40" align="center" class="bgdark"><input type="radio" name="Value[{id}][{id2}]" value="3"></td>
                <td width="40" align="center" class="bglight"><input type="radio" name="Value[{id}][{id2}]" value="4"></td>
              </tr>
            <!-- END rate_item_tpl -->
          </table>
        </blockquote>
      </td>
  </tr>
  <!-- END rate_tpl -->
  <!-- BEGIN date_tpl -->
  <tr>
      <td>
        &nbsp;&nbsp;&nbsp;<input type="text" size="{size}" name="Value[{id}]">
      </td>
  </tr>
  <!-- END date_tpl -->
  <!-- BEGIN numeric_tpl -->
  <tr>
      <td>
        &nbsp;&nbsp;&nbsp;<input type="text" size="{size}" name="Value[{id}]" value="{default}">
      </td>
  </tr>
  <!-- END numeric_tpl -->
  <tr>
    <td>&nbsp;</td>
  </tr>
<!-- END value_list_tpl -->
</table>

Page {page_number} of {page_total}

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<!-- BEGIN previous_page_button_tpl -->
  <input class="okbutton" type="submit" name="PreviousPage" value="{intl-previous_page}" />&nbsp;
<!-- END previous_page_button_tpl -->
<!-- BEGIN next_page_button_tpl -->
  <input class="okbutton" type="submit" name="NextPage" value="{intl-next_page}" />&nbsp;
<!-- END next_page_button_tpl -->

</form>
