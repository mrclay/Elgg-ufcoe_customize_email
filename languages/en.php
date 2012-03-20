<?php
/**
 * Custom Notify language pack.
 */

$english = array(
	
	'ufcoe_customize_email:header' => 'Header (prepend to every e-mail body):',
	'ufcoe_customize_email:footer' => 'Footer (append to every e-mail body)',
    'ufcoe_customize_email:from_name' => 'Sender Name (appears in e-mail client)',
    'ufcoe_customize_email:subject_format' => 'Subject Format (as applied by <a href="http://php.net/sprintf">sprintf</a>). The default is <code>%s</code>',
    'ufcoe_customize_email:live_preview' => 'Live preview',
    'ufcoe_customize_email:test_msg_sent' => 'Test message sent.',
    'ufcoe_customize_email:test_notification_sent' => 'Test notification sent.',
    'ufcoe_customize_email:send_me_email' => 'Send me test e-mail',
    'ufcoe_customize_email:trigger_notification' => 'trigger notification',
    'ufcoe_customize_email:not_patched' => 'This plugin can alter most notification emails, but not e-mails sent directly with the elgg_send_email() function. For that you would need to apply this <a href="http://trac.elgg.org/attachment/ticket/4312/notifications.patch">patch</a> to the Elgg core.',
);

add_translation("en", $english);
