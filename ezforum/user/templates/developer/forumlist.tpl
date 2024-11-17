<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/forum/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>


<hr noshade size="4" />

	<img src="{www_dir}/images/path-arrow.gif" height="10" width="15" border="0">
	<a class="path" href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a> 
	<img src="{www_dir}/images/path-slash.gif" height="10" width="20" border="0">
    <a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>

<hr noshade size="4" />

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <th>{intl-forum}:</th>
        <th>{intl-forum_description}:</th>
        <th>{intl-threads}:</th>
        <th>{intl-messages}:</th>
    </tr>
    <!-- BEGIN forum_item_tpl -->
    <tr>
	<td class="{td_class}">
	    <a href="{www_dir}{index}/forum/messagelist/{forum_id}/">
	    {name}
	    </a>
        </td>
	<td class="{td_class}">
	    {description}
        </td>
	<td class="{td_class}">
	    {threads}
        </td>
	<td class="{td_class}">
	    {messages}
        </td>
    </tr>
    <!-- END forum_item_tpl -->

</table>
<br />

