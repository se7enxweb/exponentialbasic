<form method="post" action="{www_dir}{index}/sitemanager/menu/edit/{menu_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" name="Name" value="{menu_name}" />
<br />

<p class="boxtext">{intl-link}:</p>
<input type="text" class="box" size="40" name="Link" value="{menu_link}" />
<br />

<p class="boxtext">{intl-parent}:</p>
<select name="ParentID" size="5">
<option {root_select} value="0">{intl-root}</option>
<!-- BEGIN menu_item_tpl -->
<option {selected} value="{select_id}">{select_name}</option>
<!-- END menu_item_tpl -->
</select>

<p class="boxtext">{intl-type}:</p>
{intl-header}: <input {1_checked} value="1" type="radio" name="Type"><br />
{intl-link}: <input {2_checked} value="2" type="radio" name="Type">

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
	
