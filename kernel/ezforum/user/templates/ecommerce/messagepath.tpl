<!-- start #breadcrumbs -->
<div id="breadcrumbs">
<!-- BEGIN article_message_tpl -->
&nbsp; <a href="{www_dir}{index}/article/view/{article_id}/">{intl-article}: {article_name}</a> &raquo; 
    <!-- BEGIN article_topic_tpl -->
<!--
	<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="" />
	<a class="path" href="{www_dir}{index}/forum/message/{message_id}">{message_topic}</a>
-->
    <!-- END article_topic_tpl -->
<!-- END article_message_tpl -->

<!-- BEGIN forum_message_tpl -->
 &nbsp; <a href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
 &raquo; <a href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
 &raquo; <a href="{www_dir}{index}/forum/messagelist/{forum_id}">{forum_name}</a>
    <!-- BEGIN forum_topic_tpl -->
<!--
	<img src="{www_dir}/mages/path-slash.gif" height="10" width="16" border="0" alt="" />
	<a class="path" href="{www_dir}{index}/forum/message/{message_id}">{message_topic}</a>
-->
    <!-- END forum_topic_tpl -->
<!-- END forum_message_tpl -->
</div>
<!-- end #breadcrumbs -->
