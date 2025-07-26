<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line}</h1>
	</td>
</tr>
</table>

<hr noshade size="4">

<!-- BEGIN error_list_tpl -->
<h2 class="error">{intl-error}</h2>
<!-- BEGIN error_item_tpl -->
<div class="error">{error_message}.</div>
<!-- END error_item_tpl -->
<hr noshade size="4" />
<br />
<!-- END error_list_tpl -->

<form method="post" action="{www_dir}{index}/survey/surveyedit/edit/{survey_id}/" name="surveyedit" >
  <!-- <input type="hidden" name="SurveyID" value="{survey_id}" /> -->
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	    <td colspan="2"><br></td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="boxtext">{intl-title}:</p>
        <input type="text" size="60" name="Title" value="{title_value}" maxlength="60" />
      </td>
    </tr>
    <tr>
	    <td colspan="2"><br></td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="boxtext">{intl-subtitle}:</p>
        <input type="text" size="60" name="SubTitle" value="{subtitle_value}" maxlength="60" />
      </td>
    </tr>
    <tr>
	    <td colspan="2"><br></td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="boxtext">{intl-info}:</p>
        <textarea rows="5" cols="60" name="Info" wrap="VIRTUAL">{info_value}</textarea>
      </td>
    </tr>
    <tr>
	    <td colspan="2"><br></td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="boxtext">{intl-email}:</p>
        <input type="text" size="30" name="EMail" value="{email_value}" maxlength="60" />
      </td>
    </tr>
    <tr>
	    <td colspan="2"><br></td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="boxtext">{intl-thankhead}:</p>
        <input type="text" size="30" name="ThankHead" value="{thankhead_value}" maxlength="60" />
      </td>
    </tr>
    <tr>
	    <td colspan="2"><br></td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="boxtext">{intl-thankbody}:</p>
        <textarea rows="5" cols="60" name="ThankBody" wrap="VIRTUAL">{thankbody_value}</textarea>
      </td>
    </tr>
    <tr>
      <td>
        <p class="boxtext">{intl-status}:</p>
        <select name="Status">
          <!-- BEGIN status_item_tpl -->
            <option value="{status_value}">{status_value}</option>
          <!-- END status_item_tpl -->
        </select>
      </td>
      <td>
        <p class="boxtext">{intl-section}:</p>
        <select name="Section">
          <!-- BEGIN section_item_tpl -->
            <option value="{section_value}" {selected}>{section_name}</option>
          <!-- END section_item_tpl -->
        </select>
      </td>
    </tr>
  </table>
  
  <!-- BEGIN element_list_tpl -->
  <table width="100%" class="list" border="0" cellspacing="0" cellpadding="4">
    <th>{intl-element_name}:</th>
    <th>&nbsp;</th>
    <th>{intl-element_type}:</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>{intl-size}</th>
    <!--
    <th>{intl-break}</th>
    -->
    <th>{intl-element_required}:</th>
    <th colspan="5">&nbsp;</th>

    <!-- BEGIN element_item_tpl -->
    <tr>
      <td class="{td_class}">
        <input type="hidden" name="elementID[]" value="{element_id}">
        <!-- BEGIN name_tpl -->
          <input type="text" class="halfbox" size="20" name="elementName[]" value="{element_name}">
        <!-- END name_tpl -->
        <!-- BEGIN no_name_tpl -->
          <input type="hidden" name="elementName[]" value="{element_name}" />
        <!-- END no_name_tpl -->
     </td>
      <td class="{td_class}">&nbsp;</td>
      
      <td class="{td_class}">
        <select name="elementTypeID[]" onChange="document.surveyedit.Update.click()">
          <option value="0">{intl-select_type}</option>
          <!-- BEGIN typelist_item_tpl -->
            <option value="{element_type_id}" {selected}>{element_type_name}</option>
          <!-- END typelist_item_tpl -->
        </select>
      </td>
      
      <td class="{td_class}">
        &nbsp;
        <!-- BEGIN fixed_values_tpl -->
          <a href="{www_dir}{index}/survey/values/{survey_id}/{element_id}/">{intl-fixed_values}</a>
        <!-- END fixed_values_tpl -->
      </td>
      
      <td class="{td_class}">
        &nbsp;
        <!-- BEGIN default_tpl -->
          <a href="{www_dir}{index}/survey/default/{survey_id}/{element_id}/">{intl-default}</a>
        <!-- END default_tpl -->
      </td>
  
      <td class="{td_class}">
        <!-- BEGIN size_tpl -->
          &nbsp;<input type="text" size="3" name="Size[]" value="{element_size}" />&nbsp;
        <!-- END size_tpl -->
        <!-- BEGIN no_size_tpl -->
          <input type="hidden" name="Size[]" value="{element_size}" />
        <!-- END no_size_tpl -->
      </td>
  
      <td width="1%" class="{td_class}" align="center">
        <!-- BEGIN required_tpl -->
          <input type="checkbox" {element_required} name="elementRequired[]" value="{element_id}" />
        <!-- END required_tpl -->
      </td>
      
      <td width="1%" class="{td_class}">
          <a href="{www_dir}{index}/survey/surveyedit/down/{survey_id}/?QuestionID={element_id}"><img src="{www_dir}/design/admin/images/move-down.gif" height="12" width="12" border="0" alt="{intl-move_up}" /></a>
      </td>
      <td width="1%" class="{td_class}">&nbsp;</td>
  
      <td width="1%" class="{td_class}">
        <a href="{www_dir}{index}/survey/surveyedit/up/{survey_id}/?QuestionID={element_id}"><img src="{www_dir}/design/admin/images/move-up.gif" height="12" width="12" border="0" alt="{intl-move_down}" /></a>
      </td>
      
      <td width="1%" class="{td_class}">&nbsp;</td>
      
      <td class="{td_class}" >
          <input type="checkbox" name="elementDelete[]" value="{element_id}" />
      </td>
    </tr>
  <!-- END element_item_tpl -->
  </table>
  <!-- END element_list_tpl -->
  
  <hr noshade size="4" />

  <input class="stdbutton" type="submit" name="NewElement" value="New Element" />
  <input class="stdbutton" type="submit" name="Update" value="Update" />
  <input class="stdbutton" type="submit" name="DeleteSelected" value="Delete selected elements" />
  <br />
  
  <hr noshade size="4">
  
  <table cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td valign="top">
        <input class="okbutton" type="submit" name="OK" value="OK" />
      </td>
      <td>&nbsp;</td>
      <td>
        <input class="okbutton" type="submit" name="Preview" value="Preview">
      </td>
      <!-- BEGIN stats_btn_tpl -->
      <td>&nbsp;</td>
      <td>
        <input class="okbutton" type="submit" name="Stats" value="Stats">
      </td>
      <!-- END stats_btn_tpl -->
      <td>&nbsp;</td>
      <td>
        <input class="okbutton" type="submit" name="Back" value="Cancel">
      </td>
    </tr>
  </table>

</form>
