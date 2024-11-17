<form action="{www_dir}{index}/article/topiclist" method="post">

<h1>{intl-topic_edit}</h1>

<hr size="4" noshade="noshade" />
<!-- BEGIN topic_list_tpl -->

<table class="list" width="100%" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th>
	{intl-topic_name}:
	</th>
	<th>
	{intl-topic_description}:
	</th>
</tr>
<!-- BEGIN topic_item_tpl -->
<tr>
	<td class="{td_class}">
	<input type="hidden" name="IDArray[]" value="{id}" />
	<input type="text" class="halfbox" size="20" name="Name[]" value="{topic_name}" />
	</td>
	<td class="{td_class}">
	<input type="text" class="halfbox" size="20" name="Description[]" value="{topic_description}" />
	</td>
	<td class="{td_class}" align="right">
	<input type="checkbox" name="DeleteIDArray[]" value="{id}" />
	</td>
</tr>

<!-- END topic_item_tpl -->
</tr>
</table>
<!-- END topic_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="NewTopic" value="{intl-new}" />
<input class="stdbutton" type="submit" name="DeleteTopic" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />


</form>
