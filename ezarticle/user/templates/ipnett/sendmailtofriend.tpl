<!-- *** Mail subject *** -->

<!-- BEGIN mail_subject_tpl -->
{intl-mail_subject_head} {server_name}
<!-- END mail_subject_tpl -->

<!-- *** Mail body *** -->

<!-- BEGIN mail_body_tpl -->
{intl-mail_send_to} {server_name}
{intl-mail_from}:
{from_name}

{intl-the_article}:
** {name} **
{intro}
<!-- BEGIN mail_comment_tpl -->
{intl-the_users_comment}:
{comment}

<!-- END mail_comment_tpl -->
{intl-url}:
<!-- BEGIN article_url_tpl -->
http://{site_url}/article/view/{art_id}
<!-- END article_url_tpl -->
<!-- END mail_body_tpl -->
