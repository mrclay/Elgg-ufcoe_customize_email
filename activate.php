<?php

// set default settings

$plugin = elgg_get_plugin_from_id('ufcoe_customize_email');
/* @var ElggPlugin $plugin */

$settings = $plugin->getAllSettings();

$defaults = array(
    'header' => 'zz',
    'footer' => "z\n--\n" . elgg_get_config('sitename') . "\n" . elgg_get_site_url() . "z",
    'from_name' => elgg_get_config('sitename'),
    'subject_format' => '%s',
);

foreach ($defaults as $key => $val) {
    if (! isset($settings[$key])) {
        $plugin->setPrivateSetting($key, $val);
    }
}
