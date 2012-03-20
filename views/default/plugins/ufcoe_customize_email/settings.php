<?php
/**
 * Custom Notify plugin settings.
 */

$plugin = $vars['entity'];
/* @var ElggPlugin $plugin */

//get form values if they exist
// Set title, form destination

$settings = $plugin->getAllSettings();

$form_body = "";

// test email
$form_body = "<p style='padding-top:5px; text-align:right'>"
    . elgg_echo('ufcoe_customize_email:send_me_email') . ": "
    . elgg_view(
        "output/url",
        array(
            'text' => 'elgg_send_email()',
            'href' => 'action/ufcoe_customize_email/send_test',
            'is_action' => true,
            'class' => 'elgg-button elgg-button-action',
        )
    ) . ' '
    . elgg_view(
        "output/url",
        array(
            'text' => elgg_echo('ufcoe_customize_email:trigger_notification'),
            'href' => 'action/ufcoe_customize_email/notify_test',
            'is_action' => true,
            'class' => 'elgg-button elgg-button-action',
        )
    ). "</p>";


if (! _ufcoe_customize_email_has_system_email_patch()) {
    $form_body .= "<p class='elgg-message elgg-state-error'><strong>"
        . elgg_echo('ufcoe_customize_email:not_patched') . "</strong></p>";
}

// from name
$form_body .= "<p><label>" . elgg_echo('ufcoe_customize_email:from_name') . "<br />";
$form_body .= elgg_view(
	"input/text",
	array(
		'name' => 'params[from_name]',
		'value' => $settings['from_name'],
));
$form_body .= "</label></p>";

// subject format
$form_body .= "<p><label>" . elgg_echo('ufcoe_customize_email:subject_format') . "<br />";
$form_body .= elgg_view(
	"input/text",
	array(
		'name' => 'params[subject_format]',
		'value' => $settings['subject_format'],
));
$form_body .= "</label></p>";

//header
$form_body .= "<p><label>" . elgg_echo('ufcoe_customize_email:header') . "<br />";
$form_body .= elgg_view(
	"input/plaintext",
	array(	
		'name' => 'params[header]',
		'value' => substr($settings['header'], 1, -1),
));
$form_body .= "</label></p>";

//footer
$form_body .= "<p><label>" . elgg_echo('ufcoe_customize_email:footer') . "<br />";
$form_body .= elgg_view(
	"input/plaintext",
	array(	
		'name' => 'params[footer]',
		'value' => substr($settings['footer'], 1, -1),
));
$form_body .= "</label></p>";

// live preview
$form_body .= "<div><label>" . elgg_echo('ufcoe_customize_email:live_preview') . "</label><br>
    <pre id=\"ufcoe_customize_email_preview\"></pre></div>";
$form_body .= elgg_view(
	"input/hidden",
	array(
		'name' => 'siteemail',
		'value' => elgg_get_config('siteemail'),
));
$form_body .= elgg_view(
	"input/hidden",
	array(
		'name' => 'wwwroot',
		'value' => elgg_get_config('wwwroot'),
));
$form_body .= "<script src='" . elgg_get_site_url() . "mod/ufcoe_customize_email/static/admin.js'></script>";


echo $form_body;
