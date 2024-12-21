<form method="post" action="{www_dir}{index}/ad/ad/{action_value}" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-ad_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-ad_title}:</p>
<input type="text" class="box" size="40" name="AdTitle" value="{ad_title_value}" />

<p class="boxtext">{intl-ad_category}:</p>
<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_level}{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">{intl-ad_description}:</p>
<textarea class="box" cols="40" rows="5" wrap="soft" name="AdDescription">{ad_description_value}</textarea>

<p class="boxtext">{intl-ad_url}:</p>
<input type="text" class="box" size="40" name="AdURL" value="{ad_url_value}"/>

<p class="boxtext">{intl-ad_click_price}:</p>
<input type="text" class="box" size="40" name="ClickPrice" value="{ad_click_price_value}"/>

<p class="boxtext">{intl-ad_view_price}:</p>
<input type="text" class="box" size="40" name="ViewPrice" value="{ad_view_price_value}"/>


<br /><br />
<input type="checkbox" name="UseHTML" {use_html} />
<span class="boxtext">{intl-use_html}</span><br /><br />

<p class="boxtext">{intl-html_banner}:</p>
<textarea class="box" cols="40" rows="5" wrap="soft" name="HTMLBanner">{html_banner}</textarea>


<p class="boxtext">{intl-ad_image}:</p>
<input class="box" size="40" name="AdImage" type="file" />
<br /><br /><input class="stdbutton" type="submit" name="Browse" value="{intl-browse}" />


<!-- BEGIN image_tpl -->
<br /><br />
<img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
<!-- END image_tpl -->




<br /><br />
<input type="checkbox" name="IsActive" {ad_is_active} />
<span class="boxtext">{intl-ad_is_active}</span><br /><br />

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Preview" value="{intl-update}" />

<hr noshade="noshade" size="4" />

<input type="submit" class="okbutton" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
<input type="hidden" value="{action_value}" name="Action" />
<input type="hidden" value="{ad_id}" name="AdID" />

</form>
