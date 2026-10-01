<?php
// Plugin admin settings page.
// Lets the site admin map each of the 7 display groups to its Moodle category ID.

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    $settings = new admin_settingpage(
        'local_cri_progress',
        get_string('pluginname', 'local_cri_progress')
    );

    $settings->add(new admin_setting_heading(
        'local_cri_progress/heading',
        get_string('settings_heading', 'local_cri_progress'),
        get_string('settings_heading_desc', 'local_cri_progress')
    ));

    // One setting per sub-category. Defaults are intentionally blank — category
    // IDs change between environments (localhost, dev, production), so the admin
    // must enter the correct ID for this install via this settings page.
    $keys = [
        'cat_mindset',
        'cat_relationships',
        'cat_learning',
        'cat_work',
        'cat_money',
        'cat_health',
        'cat_reentry',
    ];

    foreach ($keys as $key) {
        $settings->add(new admin_setting_configtext(
            'local_cri_progress/' . $key,
            get_string('settings_' . $key, 'local_cri_progress'),
            '',
            '',
            PARAM_INT
        ));
    }

    $ADMIN->add('localplugins', $settings);
}
