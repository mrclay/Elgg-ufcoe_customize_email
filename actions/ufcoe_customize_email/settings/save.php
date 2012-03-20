<?php

$plugin = elgg_get_plugin_from_id('ufcoe_customize_email');
/* @var ElggPlugin $plugin */
$plugin_name = $plugin->getManifest()->getName();

// get_input() applies trim(), so we must wrap the POST values with something
// so all whitespace will be maintained.
$_REQUEST['params']['header'] = "z{$_REQUEST['params']['header']}z";
$_REQUEST['params']['footer'] = "z{$_REQUEST['params']['footer']}z";

foreach ((array) get_input('params') as $k => $v) {
    $v = filter_tags($v);
    $v = str_replace("\r\n", "\n", $v);
    if ($k === 'subject_format' && $v === '') {
        $v = '%s';
    }
    $result = $plugin->setSetting($k, $v);
    if (! $result) {
        register_error(elgg_echo('plugins:settings:save:fail', array($plugin_name)));
        forward(REFERER);
    }
}

system_message(elgg_echo('plugins:settings:save:ok', array($plugin_name)));
forward(REFERER);
