<form method="post" action="{www_dir}{index}/sitemanager/section/edit/{section_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" name="Name" value="{section_name}" />
<br />

<p class="boxtext">{intl-sitedesign}:</p>
<input type="text" class="box" size="40" name="SiteDesign" value="{section_sitedesign}" />
<br />

<p class="boxtext">{intl-templatestyle}:</p>
<input type="text" class="box" size="40" name="TemplateStyle" value="{section_templatestyle}" />
<br />

<p class="boxtext">{intl-language}:</p>
<input type="text" class="box" size="40" name="SecLanguage" value="{section_language}" />
<br />

<p class="boxtext">{intl-description}:</p>
<textarea name="Description" class="box" wrap="soft" cols="40" rows="10">{section_description}</textarea>
<br /><br />


<h2>{intl-frontpage}</h2>
<hr noshade="noshade" size="4" /><br />
<!-- BEGIN setting_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
         <th>{intl-column}</th>
	 <th>{intl-category}</th>
	 <th>&nbsp;</th>
	 <th>&nbsp;</th>
	 <th>&nbsp;</th>
</tr>
<!-- BEGIN setting_item_tpl -->
<tr class="{td_class}">
	<td width="98%">

        <select Name="SettingID[]">
        <!-- BEGIN settings_tpl -->
        <option {selected} value="{setting_id}">{setting_name}</option>
        <!-- END settings_tpl -->
        </select>
        </td>
	<td width="98%">
	<!-- BEGIN article_category_list_tpl -->
        <select Name="CategoryID[]">
	<option value="0">{intl-all}</option>

	<!-- BEGIN article_category_item_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END article_category_item_tpl -->
        </select>
	<!-- END article_category_list_tpl -->
	<!-- BEGIN product_category_list_tpl -->
        <select Name="CategoryID[]">
	<!-- BEGIN product_category_item_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END product_category_item_tpl -->
        </select>
	<!-- END product_category_list_tpl -->
	&nbsp;
        </td>
	

<!-- BEGIN item_move_down_tpl -->
	<td width="1%"><a href="{www_dir}{index}/sitemanager/section/edit/{section_id}/down/{row_id}/"><img src="{www_dir}/design/admin/images/move-down.gif" height="12" width="12" border="0" alt="Move down" /></a></td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%"><a href="{www_dir}{index}/sitemanager/section/edit/{section_id}/up/{row_id}/"><img src="{www_dir}/design/admin/images/move-up.gif" height="12" width="12" border="0" alt="Move up" /></a></td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_up_tpl -->


	<td width="1%">
	<input type="checkbox" name="RowDeleteArrayID[]" value="{row_id}">
	<input type="hidden" name="RowArrayID[]" value="{row_id}">
	</td>
</tr>
<!-- END setting_item_tpl -->
</table>
<!-- END setting_list_tpl -->
<input class="stdbutton" type="submit" name="Store" value="{intl-store_rows}" />&nbsp;
<input class="stdbutton" type="submit" name="AddRow" value="{intl-add_row}" />&nbsp;
<input class="stdbutton" type="submit" name="DeleteRows" value="{intl-delete_rows}" />

<br /><br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
	
