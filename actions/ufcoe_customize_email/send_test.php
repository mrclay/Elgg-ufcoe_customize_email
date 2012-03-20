<?php

$from = elgg_get_config('siteemail');
$to = elgg_get_logged_in_user_entity()->email;
$subject = 'An example message';
$body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vel nibh turpis, id luctus libero. Aenean vel ipsum eget nulla volutpat scelerisque. Suspendisse eget nisi purus, id pellentesque risus. Etiam tellus ipsum, adipiscing sit amet elementum nec, vulputate vitae diam.

See this example here:
' . elgg_get_site_url() . 'example/url/123';

elgg_send_email($from, $to, $subject, $body);

system_message(elgg_echo('ufcoe_customize_email:test_msg_sent'));
forward(REFERER);
