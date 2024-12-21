<!-- BEGIN subject_admin_tpl -->
{intl-mail_subject_admin} yourshop.com
<!-- END subject_admin_tpl -->
<!-- BEGIN subject_user_tpl -->
{intl-mail_subject_user} yourshop.com
<!-- END subject_user_tpl -->

http://yourshop.com {intl-headline}

{intl-lead_in}

<!-- BEGIN billing_address_tpl -->{intl-billing_address}

{billing_street1}
{billing_street2}
{billing_zip} {billing_place}
{billing_country}
<!-- END billing_address_tpl -->

<!-- BEGIN shipping_address_tpl -->{intl-shipping_address}

{shipping_street1}
{shipping_street2}
{shipping_zip} {shipping_place}
{shipping_country}
<!-- END shipping_address_tpl -->

{intl-order_no}: {order_number}

{product_string}{count_string}{price_string}
{stringline}
<!-- BEGIN order_item_tpl -->
{order}{count}{price} 
<!-- BEGIN option_item_tpl -->
{name}: {value}
<!-- END option_item_tpl -->
<!-- END order_item_tpl -->
{stringline}
{product_sub_total_string}{product_sub_total}
{product_ship_hand_string}{product_ship_hand}
{product_total_string}{product_total}

{intl-other_instructions_on_web_site} http://yourshop.com
