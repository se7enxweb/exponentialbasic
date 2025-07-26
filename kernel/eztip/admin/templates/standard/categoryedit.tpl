<form method="post" action="{www_dir}{index}/tip/category/{action_value}/{category_id}/">

<h1>{intl-headline}</h1>

<hr noshade size="4" />
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" class="box" size="40" name="Name" value="{name_value}"/>
	</td>	
</tr>
</table>

<br />
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr> 
      <td width=1% nowrap> 
        <p class="boxtext">{intl-sections}:</p>
        <select name="SectionArray[]" multiple size="7">
          <option value="0" {all-selected}>{intl-all}</option>
		  <!-- BEGIN section_item_tpl -->
		  <option value="{section_id}" {selected}>{section_name}</option>
		  <!-- END section_item_tpl -->
        </select>
      </td><td>
	  &nbsp;
	  </td><td valign="top">
        <p class="boxtext">{intl-tip-locations}:</p>
        <select name="LocationID">
		  <!-- BEGIN location_item_tpl -->
		  <option value="{location_id}" {selected}>{location_name}</option>
		  <!-- END location_item_tpl -->
        </select>
	  </td>
    </tr>
  </table>

  <br>
  <p class="boxtext">{intl-description}:<br>
    <textarea class="box" rows="5" cols="40" name="Description">{description_value}</textarea>
  </p>
  <p>
    <input type="checkbox" name="IsPublished" {is_published} value="1" />
    <span class="boxtext">{intl-is-published}</span><br />
    <br />
  </p>
  <hr noshade size="4" />

    <input type="hidden" name="CategoryID" value="{category_id}" />
    <input class="okbutton" type="submit" value="OK" />
    <input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
	