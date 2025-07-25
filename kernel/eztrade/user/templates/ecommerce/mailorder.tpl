{intl-confirming-order}
{equal_line}<!-- BEGIN shipping_address_tpl -->
>>>>>>>>>> {intl-shipping_address}:  >>>>>

          {shipping_first_name} {shipping_last_name}
          {shipping_street1}
          {shipping_street2}
          {shipping_place}, {shipping_region}  {shipping_zip}
          {shipping_country}

<!-- END shipping_address_tpl -->{equal_line}


{intl-thanks_for_shopping}

{intl-order_no}: {order_id}
<!-- BEGIN billing_address_tpl -->
{intl-billing_address}:
{customer_first_name} {customer_last_name}
{billing_street1}
{billing_street2}
{billing_place}, {billing_region} {billing_zip}
{billing_country}
<!-- END billing_address_tpl --> 
{intl-payment_method}: {payment_method} 

{intl-shipping_type}: {shipping_type}

{intl-comment}: {comment}

{intl-goods_list}:<!-- BEGIN full_cart_tpl --> <!-- BEGIN cart_item_list_tpl -->
{headers}
{hyphen_line}<!-- BEGIN cart_item_tpl -->
{product_number}{product_name}{product_savings}{product_price}{product_count}{product_total_ex_tax}{product_total_inc_tax}
<!-- BEGIN cart_item_option_tpl -->
{option_indent}{option_value}{option_name}{option_price}
<!-- END cart_item_option_tpl -->
<!-- END cart_item_tpl -->
<!-- END cart_item_list_tpl -->
{hyphen_line}
{intl-subtotal}{subtotal_ex_tax}{subtotal_inc_tax}
{intl-shipping}{shipping_ex_tax}{shipping_inc_tax}
{sales-tax}{sales-tax-inc}
{hyphen_line}
{intl-total}{total_ex_tax}{total_inc_tax}
{equal_line}

{intl-other_instructions_on_web_site} http://{site_url}/

<!-- END full_cart_tpl -->
