<form action="{www_dir}{index}/forum/categoryedit/{action_value}/{category_id}/" method="post">
<input type="hidden" name="page" value="{docroot}/admin/category.php">

<h1>{headline}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input type="text" value="{category_name}" name="Name">

<p class="boxtext">{intl-description}:</p>
<input type="text" value="{category_description}" name="Description">

<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="add" value="OK">
</form>
