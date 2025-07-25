<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp; <a href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{intl-headline}</h1>

<table class="list" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th colspan="2" width="1%">{intl-name}:</th>
   	<th>{intl-desc}:</th>
</tr>


<!-- BEGIN category_item_tpl -->
<tr bgcolor="{color}">
    <td class="{td_class}" width="1%">
    <img src="{www_dir}/images/folder.gif" width="16" height="16" border="0" alt="" />
	</td>
    <td class="{td_class}" width="50%">
    <a href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
    </td>
    <td class="{td_class}" width="49%">
    <span class="small">{category_description}</span>
    </td>
</tr>
<!-- END category_item_tpl -->
</table><br />
        </div>
        <!-- end #contentWrap -->
