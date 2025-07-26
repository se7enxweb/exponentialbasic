<script language="javascript"> 

    function isBlank( element )
    {
        var i;
        
        if ( element.length )
        {
            for ( i = 0; i < element.length; i++ )
            {
                if( element[i].checked == true )
                {
                    return false;
                }
            }
        }
        else
        {
            if ( element.value != '' )
            {
                return false;
            }
        }
        
        return true;
    }
    
    function checkValues()
    {
        <!-- BEGIN check_value_tpl -->
            if ( isBlank( document.form['{element_item}'] ) )
            {
                alert( '{question_name} is required' );
                return false;
            }
        <!-- END check_value_tpl -->
        return true;
    }

</script>
<table width="560" border="0" cellspacing="0" cellpadding="0">
  <tr> 
	<td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
	<td> 
	  <h1>{title}</h1>
	</td>
  <!-- BEGIN already_responded_tpl -->
  <td>
    {intl-already_responded}
  </td>
  <!-- END already_responded_tpl -->
	<td align="right" valign="top"> 
	  <table border="0" cellspacing="0" cellpadding="0" class="steps">
      <tr align="center" valign="middle">
      <!-- BEGIN step_tpl -->
        <!-- BEGIN step_on_tpl -->
          <td background="{www_dir}{index}/images/flor_on.gif" height="22" width="28" class="steps">{page}</td>
        <!-- END step_on_tpl -->
        <!-- BEGIN step_off_tpl -->
          <td background="{www_dir}{index}/images/flor_off.gif" height="22" width="28" class="steps">{page}</td>
        <!-- END step_off_tpl -->
      <!-- END step_tpl -->
      </tr>
    </table>
  </td>
  </tr>
  <tr class="TitleLine"> 
	<td height="1" colspan="2"><img src="{www_dir}{index}/images/0.gif" width="1" height="1"></td>
  </tr>
  <!-- BEGIN subtitle_section_tpl -->
  <tr> 
	<td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
	<td colspan="2"> 
	  <h2>{subtitle}</h2>
	</td>
  </tr>
  <!-- END subtitle_section_tpl -->
  <tr> 
	<td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
	<td colspan="2">{info}</td>
  </tr>
  <tr> 
	<td colspan="2">&nbsp;</td>
  </tr>
</table>
<!-- BEGIN error_list_tpl -->
<h6>{intl-error}</h6>
<!-- BEGIN error_item_tpl -->
<h6>{error_message}</h6>
<!-- END error_item_tpl -->
<!-- END error_list_tpl -->
<form action="{www_dir}{index}/survey/surveylist/list/{survey_id}/" method="post" name="form">
  <table width="560" border="0" cellspacing="0" cellpadding="4">
	<!-- BEGIN value_list_tpl -->
	<tr> 
	  <td colspan="3" height="30" valign="top"> 
		<h2>
      <!-- BEGIN qnumber_tpl -->
        <input type="hidden" name="QuestionID[]" value="{question_id}" />
        {question_num})
      <!-- END qnumber_tpl -->
       {question_name} 
      <!-- BEGIN required_tpl -->
      <font color="#ff0000">*</font> 
      <!-- END required_tpl -->
    </h2>
	  </td>
	</tr>
	<!-- BEGIN yesno_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> &nbsp;&nbsp;&nbsp; 
		<input type="radio" name="Value[{id}]" value="Y" {selected_y}>
		{intl-yes} </td>
	  <td>&nbsp;</td>
	</tr>
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> &nbsp;&nbsp;&nbsp; 
		<input type="radio" name="Value[{id}]" value="N" {selected_n}>
		{intl-no} </td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END yesno_tpl -->
	<!-- BEGIN text_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> &nbsp;&nbsp;&nbsp; 
		<input type="text" size="{size}" name="Value[{id}]" value="{default}">
	  </td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END text_tpl -->
	<!-- BEGIN essay_tpl -->
	<tr> 
	  <td width="20" height="100">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle" height="100"> &nbsp;&nbsp;&nbsp; 
		<textarea name="Value[{id}]" rows="5" cols="{size}" wrap="VIRTUAL">{default}</textarea>
	  </td>
	  <td height="100">&nbsp;</td>
	</tr>
	<!-- END essay_tpl -->
	<!-- BEGIN radio_tpl -->
	<!-- BEGIN radio_item_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> &nbsp;&nbsp;&nbsp; 
		<input type="radio" name="Value[{id}][0]" value="{value}" {selected} />
		{value_name} </td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END radio_item_tpl -->
	<!-- BEGIN radio_other_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> &nbsp;&nbsp;&nbsp; 
		<input type="radio" name="Value[{id}][0]" value="{value}" {selected} />
		Other: 
		<input type="text" name="Value[{id}][1]" size="{size}" value="{radio_other}"/>
	  </td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END radio_other_tpl -->
	<!-- END radio_tpl -->
	<!-- BEGIN checkbox_tpl -->
	<!-- BEGIN checkbox_item_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> &nbsp;&nbsp;&nbsp; 
		<input type="checkbox" name="Value[{id}][0][]" value="{value}" {selected} />
		{value_name}</td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END checkbox_item_tpl -->
	<!-- BEGIN checkbox_other_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> &nbsp;&nbsp;&nbsp; 
		<input type="checkbox" name="Value[{id}][0][]" value="{value}" {selected} />
		Other: 
		<input type="text" name="Value[{id}][1][]" size="{size}" value="{checkbox_other}"/>
	  </td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END checkbox_other_tpl -->
	<!-- END checkbox_tpl -->
	<!-- BEGIN dropdown_tpl -->
	<tr> 
	  <td width="20" height="40">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle" height="40"> &nbsp;&nbsp;&nbsp; 
		<select name="Value[{id}]">
		  <!-- BEGIN dropdown_item_tpl -->
		  <option value="{value}" {selected}>{value_name}</option>
		  <!-- END dropdown_item_tpl -->
		</select>
	  </td>
	  <td height="40">&nbsp;</td>
	</tr>
	<!-- END dropdown_tpl -->
	<!-- BEGIN rate_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> &nbsp;&nbsp;&nbsp; 
		<table border="0" cellspacing="0" cellpadding="0" width="80%">
		  <tr>
			<td width="10">&nbsp;</td>
			<td>&nbsp;</td>
			<td align="center" class="pop3">1</td>
			<td align="center" class="pop3">2</td>
			<td align="center" class="pop3">3</td>
			<td align="center" class="pop3">4</td>
			<td align="center" class="pop3">5</td>
			<!-- BEGIN rate_item_tpl -->
		  <tr>
			<td height="30">&nbsp;</td>
			<input type="hidden" name="Rank[{id}][]" value="{choice_id}" />
			<td height="30" class="pop3">{value_name}</td>
			<td align="center" height="30" > 
			  <input type="radio" name="Value[{id}][{id2}]" value="0" {selected_rank0}>
			</td>
			<td align="center" height="30" > 
			  <input type="radio" name="Value[{id}][{id2}]" value="1" {selected_rank1}>
			</td>
			<td align="center" height="30" > 
			  <input type="radio" name="Value[{id}][{id2}]" value="2" {selected_rank2}>
			</td>
			<td align="center" height="30" > 
			  <input type="radio" name="Value[{id}][{id2}]" value="3" {selected_rank3}>
			</td>
			<td align="center" height="30" > 
			  <input type="radio" name="Value[{id}][{id2}]" value="4" {selected_rank4}>
			</td>
		  </tr>
		  <!-- END rate_item_tpl -->
		</table>
	  </td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END rate_tpl -->
	<!-- BEGIN date_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> 
		<input type="text" size="{size}" name="Value[{id}]" value="{default_date}">
	  </td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END date_tpl -->
	<!-- BEGIN numeric_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td class="pop3" width="450" valign="middle"> 
		<input type="text" size="{size}" name="Value[{id}]" value="{default}">
	  </td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END numeric_tpl -->
	<tr> 
	  <td width="20" align="center">&nbsp;</td>
	  <td width="300">&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
	<!-- END value_list_tpl -->
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td width="300">&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td width="300">&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td align="center" width="300"> 
		<!-- BEGIN previous_page_button_tpl -->
		<input type="submit" name="PreviousPage" value="{intl-previous_page}" onClick="if ( !checkValues() ) return false" />
		&nbsp; 
		<!-- END previous_page_button_tpl -->
		<!-- BEGIN next_page_button_tpl -->
		<input type="submit" name="NextPage" value="{intl-next_page}" onClick="if ( !checkValues() ) return false" />
		&nbsp; 
		<!-- END next_page_button_tpl -->
		<!-- BEGIN finish_button_tpl -->
		<input type="submit" name="Finish" value="{intl-finish}" onClick="if ( !checkValues() ) return false" />
		&nbsp; 
		<!-- END finish_button_tpl -->
	  </td>
	  <td align="center">&nbsp;</td>
	</tr>
	<tr> 
	  <td width="20">&nbsp;</td>
	  <td align="center" width="300">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	</tr>
  </table>
  <input type="hidden" name="Page" value="{page_number}" />
  <input type="hidden" name="ResponseID" value="{response_id}" />
</form>
