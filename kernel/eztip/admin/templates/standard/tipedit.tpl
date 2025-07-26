
<form method="post" action="{www_dir}{index}/tip/tip/{action_value}" enctype="multipart/form-data">
  <input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-tip_edit}</h1>

<hr noshade size="4" />

<p class="boxtext">{intl-tip_title}:</p>
<input type="text" class="box" size="40" name="TipTitle" value="{tip_title_value}" />

<p class="boxtext">{intl-tip_category}:</p>
<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_level}{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">{intl-tip_description}:</p>
<textarea class="box" cols="40" rows="5" wrap="soft" name="TipDescription">{tip_description_value}</textarea>

<p class="boxtext">{intl-tip_url}:</p>
  <p> 
    <input type="text" class="box" size="40" name="TipURL" value="{tip_url_value}"/>
    <br />
  </p>
  <table border="0" cellspacing="0" cellpadding="5" width="350">
    <tr align="left"> 
      <td width="5%"> 
        <input type="radio" name="UseHTML" value="1" {use_html}>
      </td>
      <td width="45%">{intl-use_html}</td>
      <td width="5%"> 
        <input type="radio" name="UseHTML" value="0" {use_image}>
      </td>
      <td width="45%">{intl-use_image}</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p class="boxtext">{intl-html_banner}:</p>
<textarea class="box" cols="40" rows="5" wrap="soft" name="HTMLBanner">{html_banner}</textarea>


<p class="boxtext">{intl-tip_image}:</p>
<input class="box" size="40" name="TipImage" type="file" />
<br /><br /><input class="stdbutton" type="submit" name="Browse" value="{intl-browse}" />


<!-- BEGIN image_tpl -->
<br /><br />
  <img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /> 
  <!-- END image_tpl -->
  <br />
  <br />
  <input type="checkbox" name="IsActive" {tip_is_active} value="on" />
  <span class="boxtext">{intl-tip_is_active}</span><br />
  <br />

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="Preview" value="{intl-update}" />

<hr noshade size="4" />

<input type="submit" class="okbutton" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
<input type="hidden" value="{action_value}" name="Action" />
<input type="hidden" value="{tip_id}" name="TipID" />

</form>