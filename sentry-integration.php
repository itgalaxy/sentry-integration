<?php

/**
 * Plugin Name: Sentry Integration
 * Plugin URI: https://github.com/itgalaxy/sentry-integration
 * Description: A (unofficial) WordPress plugin to report PHP, JavaScript and security headers errors to Sentry.
 * Version: 2.2.9
 * Author: Alexander Krasnoyarov
 * Author URI: https://github.com/evilebottnawi
 * License: MIT
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

use Itgalaxy\SentryIntegration\ExpectCTTracker;
use Itgalaxy\SentryIntegration\JavaScriptTracker;
use Itgalaxy\SentryIntegration\PHPTracker;
use Itgalaxy\SentryIntegration\XXSSProtectionTracker;

// If the plugin was already loaded as a mu-plugin do not load again.
if (defined('SENTRY_INTEGRATION_MU_LOADED')) {
    return;
}

// Resolve the sentry plugin file.
define(
    'SENTRY_INTEGRATION_PLUGIN_FILE',
    call_user_func(function () {
        global $wp_plugin_paths;

        $plugin_file = __FILE__;

        if (!empty($wp_plugin_paths)) {
            $wp_plugin_real_paths = array_flip($wp_plugin_paths);
            $plugin_path = wp_normalize_path(dirname($plugin_file));

            if (isset($wp_plugin_real_paths[$plugin_path])) {
                $plugin_file = str_replace(
                    $plugin_path,
                    $wp_plugin_real_paths[$plugin_path],
                    $plugin_file
                );
            }
        }

        return $plugin_file;
    })
);

$autoloaderPath = __DIR__ . '/vendor/autoload.php';

if (!file_exists($autoloaderPath)) {
    wp_die(
        wp_kses_post(
            __(
                'You must run <code>composer install</code> from the Sentry Integration directory.',
                'sentry-integration'
            )
        ),
        esc_html(__('Autoloader not found', 'sentry-integration'))
    );
}

require_once $autoloaderPath;

register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
register_activation_hook(__FILE__, 'flush_rewrite_rules');

// Define the sentry version.
if (!defined('SENTRY_INTEGRATION_RELEASE')) {
    define('SENTRY_INTEGRATION_RELEASE', wp_get_theme()->get('Version'));
}

// Load the Expect-CT tracker if we have a private DSN
if (
    defined('SENTRY_INTEGRATION_EXPECT_CT_DSN') &&
    SENTRY_INTEGRATION_EXPECT_CT_DSN
) {
    add_filter(
        'sentry_integration_expect_ct_dsn',
        function () {
            return SENTRY_INTEGRATION_EXPECT_CT_DSN;
        },
        1,
        0
    );

    ExpectCTTracker::get_instance();
}

if (!defined('SENTRY_INTEGRATION_PUBLIC_DSN_ENQUEUE_MODE')) {
    define('SENTRY_INTEGRATION_PUBLIC_DSN_ENQUEUE_MODE', 'inline');
}

// Load the Javascript tracker if we have a public DSN
if (defined('SENTRY_INTEGRATION_PUBLIC_DSN') && SENTRY_INTEGRATION_PUBLIC_DSN) {
    add_filter(
        'sentry_integration_public_dsn',
        function () {
            return SENTRY_INTEGRATION_PUBLIC_DSN;
        },
        1,
        0
    );

    JavaScriptTracker::get_instance();
}

// Load the PHP tracker if we have a private DSN
if (defined('SENTRY_INTEGRATION_DSN') && SENTRY_INTEGRATION_DSN) {
    add_filter(
        'sentry_integration_dsn',
        function () {
            return SENTRY_INTEGRATION_DSN;
        },
        1,
        0
    );

    PHPTracker::get_instance();
}

// Load the X-XSS-Protection tracker if we have a private DSN
if (
    defined('SENTRY_INTEGRATION_X_XSS_PROTECTION_DSN') &&
    SENTRY_INTEGRATION_X_XSS_PROTECTION_DSN
) {
    add_filter(
        'sentry_integration_x_xss_protection_dsn',
        function () {
            return SENTRY_INTEGRATION_X_XSS_PROTECTION_DSN;
        },
        1,
        0
    );

    XXSSProtectionTracker::get_instance();
}
