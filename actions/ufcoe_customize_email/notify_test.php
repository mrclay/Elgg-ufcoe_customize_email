<?php

$subject = 'An example notification';
$body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vel nibh turpis, id luctus libero. Aenean vel ipsum eget nulla volutpat scelerisque. Suspendisse eget nisi purus, id pellentesque risus. Etiam tellus ipsum, adipiscing sit amet elementum nec, vulputate vitae diam.

See this example here:
' . elgg_get_site_url() . 'example/url/123';

global $NOTIFICATION_HANDLERS;

if (isset($NOTIFICATION_HANDLERS['email']) && ! empty($NOTIFICATION_HANDLERS['email']->handler)) {
    $handler = $NOTIFICATION_HANDLERS['email']->handler;
    call_user_func(
        $handler,
        elgg_get_site_entity(),
        elgg_get_logged_in_user_entity(),
        $subject,
        $body,
        array()
    );
}

system_message(elgg_echo('ufcoe_customize_email:test_notification_sent'));
forward(REFERER);
