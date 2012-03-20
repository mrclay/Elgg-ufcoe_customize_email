<?php
/**
 * Custom Notification Plugin
 * 
 */

elgg_register_event_handler('init', 'system', 'ufcoe_customize_email_init');

function ufcoe_customize_email_init() {
    $actions = __DIR__ . '/actions/ufcoe_customize_email';
    elgg_register_action('ufcoe_customize_email/settings/save', "$actions/settings/save.php", 'admin');
    elgg_register_action('ufcoe_customize_email/send_test', "$actions/send_test.php", 'admin');
    elgg_register_action('ufcoe_customize_email/notify_test', "$actions/notify_test.php", 'admin');

    register_notification_handler("email", "ufcoe_customize_email_handle_notification_email");
    elgg_register_plugin_hook_handler('email', 'system', 'ufcoe_customize_email_handle_email');
}

/**
 * custom header and footer are set in Elgg Admin Menu -> Settings -> Customize Notification
 * plugin settings form defined in: views/default/plugins/ufcoe_customize_email/settings.php
 *
 * @param string $hook
 * @param string $type
 * @param array $returnvalue
 * @param array $params
 *
 * @return array returns the modified $returnvalue array
 */
function ufcoe_customize_email_handle_email($hook, $type, $returnvalue, $params) {
    if (isset($params['params']['ufcoe_customize_email_applied'])
            || $returnvalue === null) {
        // changes already applied in notification handler
        // or patch for issue 4312 not likely applied.
        return null;
    }

	//fetch the header and footer values if they have been set
    $settings = _ufcoe_customize_email_get_send_settings();
    $returnvalue['body'] = trim($returnvalue['body']);
	if ($settings['header'] !== '') {
        $returnvalue['body'] = $settings['header'] . "\n" . $returnvalue['body'];
    }
    if ($settings['footer'] !== '') {
        $returnvalue['body'] .= "\n" . $settings['footer'];
    }
    $returnvalue['subject'] = sprintf($settings['subject_format'], $returnvalue['subject']);
    if ($settings['from_name'] !== '') {
        $returnvalue['from'] = "{$settings['from_name']} <{$returnvalue['from']}>";
    }

    return $returnvalue;
}

/**
 * @return bool true if engine/lib/notification.php appears to contain patch
 * @see http://trac.elgg.org/attachment/ticket/4312/notifications.patch
 */
function _ufcoe_customize_email_has_system_email_patch() {
    $file = dirname(dirname(__DIR__)) . '/engine/lib/notification.php';
    if (is_readable($file)) {
        $code = file_get_contents($file);
        $pos1 = strpos($code, 'function elgg_send_email(');
        if ($pos1 !== false) {
            $pos2 = strpos($code, 'broken_mta', $pos1);
            $code = substr($code, $pos1, ($pos2 - $pos1));
            return (false !== strpos($code, 'is_array($result)'));
        }
    }
    return false;
}

/**
 * Get and cache customization settings to improve performance when sending multiple emails
 * @return array
 */
function _ufcoe_customize_email_get_send_settings() {
    static $settings = array();
    if (empty($settings)) {
        $settings = elgg_get_plugin_from_id('ufcoe_customize_email')->getAllSettings();
        $settings['header'] = preg_replace('/(^z|z$)/', '', $settings['header']);
        $settings['footer'] = preg_replace('/(^z|z$)/', '', $settings['footer']);
    }
    return $settings;
}


/**
 * Send a notification via email.
 *
 * @param ElggEntity $from    The from user/site/object
 * @param ElggUser   $to      To which user?
 * @param string     $subject The subject of the message.
 * @param string     $message The message body
 * @param array      $params  Optional parameters (none taken in this instance)
 *
 * @return bool
 * @access private
 */
function ufcoe_customize_email_handle_notification_email(ElggEntity $from, ElggUser $to, $subject, $message,
array $params = NULL) {

	global $CONFIG;

	if (!$from) {
		$msg = elgg_echo('NotificationException:MissingParameter', array('from'));
		throw new NotificationException($msg);
	}

	if (!$to) {
		$msg = elgg_echo('NotificationException:MissingParameter', array('to'));
		throw new NotificationException($msg);
	}

	if ($to->email == "") {
		$msg = elgg_echo('NotificationException:NoEmailAddress', array($to->guid));
		throw new NotificationException($msg);
	}

	// To
	$to = $to->email;

	// From
	$site = get_entity($CONFIG->site_guid);
	// If there's an email address, use it - but only if its not from a user.
	if (!($from instanceof ElggUser) && $from->email) {
		$from = $from->email;
	} else if ($site && $site->email) {
		// Use email address of current site if we cannot use sender's email
		$from = $site->email;
	} else {
		// If all else fails, use the domain of the site.
		$from = 'noreply@' . get_site_domain($CONFIG->site_guid);
	}

    // alter according to settings
    $settings = _ufcoe_customize_email_get_send_settings();
    $message = trim($message);
    if ($settings['header'] !== '') {
        $message = $settings['header'] . "\n" . $message;
    }
    if ($settings['footer'] !== '') {
        $message .= "\n" . $settings['footer'];
    }
    $subject = sprintf($settings['subject_format'], $subject);
    if ($settings['from_name'] !== '') {
        $from = "{$settings['from_name']} <{$from}>";
    }
    $params['ufcoe_customize_email_applied'] = true;

	return elgg_send_email($from, $to, $subject, $message, $params);
}
