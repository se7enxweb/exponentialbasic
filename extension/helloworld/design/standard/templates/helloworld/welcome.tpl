<h1>{hello}</h1>
<p>{description}</p>

<!-- BEGIN item_list_tpl -->
<h2>Messages</h2>
<div class="helloworld-item">
<h3>{item_name}</h3>
<p>{item_message}</p>
<p><small>{item_created}</small></p>
</div>
<!-- END item_list_tpl -->
<p>{item_list}</p>

<!-- BEGIN add_form_tpl -->
<h2>Add a message</h2>
<p class="form-status"><strong>{form_status}</strong></p>
<form method="post" action="/helloworld/">
<p><input type="text" name="name" placeholder="Your name" required /></p>
<p><textarea name="message" rows="4" placeholder="Your message" required></textarea></p>
<p><button type="submit">Store message</button></p>
</form>
<!-- END add_form_tpl -->
<p>{add_form}</p>

<p><em>{edit_hint}</em></p>
