<!-- BEGIN subject_admin_tpl -->
{intl-mail_subject_admin} {site_url}
<!-- END subject_admin_tpl -->
<!-- BEGIN subject_user_tpl -->
{intl-mail_subject_user} {site_url}
<!-- END subject_user_tpl -->
http://{site_url}
{intl-headline}
{stringline}
{intl-order_no}: {order_number}

<!-- BEGIN billing_address_tpl -->
{intl-billing_address}:
{customer_first_name} {customer_last_name}
{billing_street2}
{billing_street1}
{billing_zip} {billing_place}
{billing_country}
<!-- END billing_address_tpl -->

{intl-payment_method}:
{payment_method}

<!-- BEGIN shipping_address_tpl -->
{intl-shipping_address}:
{shipping_customer_first_name} {shipping_customer_last_name}
{shipping_street2} 
{shipping_street1}
{shipping_zip} {shipping_place}
{shipping_country}
<!-- END shipping_address_tpl -->

{intl-shipping_type}:
{shipping_type}

{stringline}
{product_string}{product_number_string}{count_string}{price_string}
{stringline}
<!-- BEGIN order_item_tpl -->
{order}{number}{count}{price} 
<!-- BEGIN option_item_tpl -->
{name}: {value}
<!-- END option_item_tpl -->
<!-- END order_item_tpl -->
{stringline}
{product_sub_total_string}{product_sub_total}
{product_ship_hand_string}{product_ship_hand}
{product_total_string}{product_total}
{stringline}
{stringline}

{intl-other_instructions_on_web_site} http://{site_url}
{stringline}
 MyGold.com - Impetex GmbH | Tel: +49 (8041) 794330
 Marktstr. 24              | Fax: +49 (8041) 70932
 D-83646 Bad Tölz          | info@mygold.com
{stringline}