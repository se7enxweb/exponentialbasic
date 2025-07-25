<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/link/search/" method="post">
	       <input type="text" size="12" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade size="4">

<form method="post" action="{www_dir}{index}/link/categoryedit/{action_value}/{category_id}" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">


<p class="error">{error_msg}</p>

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" name="Name" size="40" value="{category_name}">

<table with="100%" border="0">
<tr>
     <td><p class="boxtext">{intl-where}:</p>
     <select name="ParentCategory">
     <option value="0">{intl-topcat}</option>
     <!-- BEGIN parent_category_tpl -->
     <option {is_selected} value="{categorylink_id}">{option_level}{categorylink_name}</option>
     <!-- END parent_category_tpl -->
     </select></td>

     <td><p class="boxtext">{intl-section_select}:</p>
     <select name="SectionID">
     <!-- BEGIN section_item_tpl -->
     <option value="{section_id}" {section_is_selected}>{section_name}</option>
     <!-- END section_item_tpl -->
     </select></td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" class="box" rows="4" name="Description">{category_description}</textarea>

<!-- BEGIN image_item_tpl -->
<p class="boxtext">{intl-th_type_current_image}:</p>
<p><img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
</p>
<input type="checkbox" name="DeleteImage">{intl-delete_image}
<!-- END image_item_tpl -->
<p class="boxtext">{intl-th_type_image}:</p>
<!-- BEGIN no_image_item_tpl -->

<!-- END no_image_item_tpl -->
<input size="40" class="box" name="ImageFile" type="file" />&nbsp;
<br /><br /><input class="stdbutton" type="submit" name="Browse" value="{intl-browse}" />
<br /><br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-submit_text}">
	</td>
	<td>&nbsp;</td>
	</form>
	<td>
	<form method="post" action="{www_dir}{index}/link/category/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>

</table>
	
