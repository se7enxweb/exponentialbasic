<form action="{www_dir}{index}/article/search/" method="post">

<input type="hidden" name="SectionIDOverride" value="{section_id}">

<h1>{intl-advanced_search}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-containing}:</p>
<input type="text" name="SearchText" size="60" value="" />


<p class="boxtext">{intl-search_categories}:</p>
<select multiple size="{num_select_categories}" name="CategoryArray[]">
<option value="0" selected>{intl-all}</option>
<!-- BEGIN category_item_tpl -->
<option value="{category_id}">{option_level}{option_name}</option>
<!-- END category_item_tpl -->
</select>

<p class="boxtext">{intl-article_author}:</p>
<select name="ContentsWriterID">
<option value="0">{intl-none}</option>
<!-- BEGIN author_item_tpl -->
<option value="{author_id}">{author_name}</option>
<!-- END author_item_tpl -->
</select>

<p class="boxtext">{intl-photographer}:</p>
<select name="PhotographerID">
<option value="0">{intl-none}</option>
<!-- BEGIN photographer_item_tpl -->
<option value="{photographer_id}" {selected}>{photographer_name}</option>
<!-- END photographer_item_tpl -->
</select>

<br /><br />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td colspan="5">
	<p class="boxtext">{intl-start_date}:</p>
	</td>
</tr>
<tr>
	<td >
	<span class="small">{intl-day}:</span>
	</td>
	<td >
	<span class="small">{intl-month}:</span>
	</td>
	<td >
	<span class="small">{intl-year}:</span>
	</td>

	<td >
	<span class="small">{intl-hour}:</span>
	</td>
	<td >
	<span class="small">{intl-minute}:</span>
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="2" name="StartDay" value="" />&nbsp;&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StartMonth" value="" />&nbsp;&nbsp;
	</td>
	<td>
	<input type="text" size="4" name="StartYear" value="" />&nbsp;&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StartHour" value="" />&nbsp;&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StartMinute" value="" />
	</td>
</tr>
</table>

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td colspan="5">
	<br />
	<p class="boxtext">{intl-stop_date}:</p>
	</td>
</tr>
<tr>
	<td >
	<span class="small">{intl-day}:</span>
	</td>
	<td>
	<span class="small">{intl-month}:</span>
	</td>
	<td>
	<span class="small">{intl-year}:</span>
	</td>

	<td>
	<span class="small">{intl-hour}:</span>
	</td>
	<td>
	<span class="small">{intl-minute}:</span>
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="2" name="StopDay" value="" />&nbsp;&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StopMonth" value="" />&nbsp;&nbsp;
	</td>
	<td>
	<input type="text" size="4" name="StopYear" value="" />&nbsp;&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StopHour" value="" />&nbsp;&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StopMinute" value="" />
	</td>
</tr>
</table>



<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-search}" />
	</td>
</tr>
</table>
</form>

