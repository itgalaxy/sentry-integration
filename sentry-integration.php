<?php

/**
 * Plugin Name: Sentry Integration
 * Plugin URI: https://github.com/itgalaxy/sentry-integration
 * Description: A (unofficial) WordPress plugin to report PHP and JavaScript errors to Sentry.
 * Version: 1.0.0
 * Author: Alexander Krasnoyarov
 * Author URI: https://github.com/evilebottnawi
 * License: MIT
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

use Itgalaxy\SentryIntegration\JavaScriptTracker;
use Itgalaxy\SentryIntegration\PHPTracker;

// If the plugin was already loaded as a mu-plugin do not load again.
if (defined('SENTRY_INTEGRATION_MU_LOADED')) {
    return;
}

// Resolve the sentry plugin file.
define('SENTRY_INTEGRATION_PLUGIN_FILE', call_user_func(function () {
    global $wp_plugin_paths;

    $plugin_file = __FILE__;

    if (!empty($wp_plugin_paths)) {
        $wp_plugin_real_paths = array_flip($wp_plugin_paths);
        $plugin_path = wp_normalize_path(dirname($plugin_file));

        if (isset($wp_plugin_real_paths[$plugin_path])) {
            $plugin_file = str_replace($plugin_path, $wp_plugin_real_paths[$plugin_path], $plugin_file);
        }
    }

    return $plugin_file;
}));

$autoloaderPath = __DIR__ . '/vendor/autoload.php';

if (!file_exists($autoloaderPath)) {
    wp_die(
        wp_kses_post(__(
            'You must run <code>composer install</code> from the Sentry Integration directory.',
            'sentry-integration'
        )),
        esc_html(__(
            'Autoloader not found',
            'sentry-integration'
        ))
    );
}

require_once $autoloaderPath;

// Define the sentry version.
if (!defined('SENTRY_INTEGRATION_RELEASE')) {
    define('SENTRY_INTEGRATION_RELEASE', wp_get_theme()->get('Version'));
}

// Load the PHP tracker if we have a private DSN
if (defined('SENTRY_INTEGRATION_DSN') && !empty(SENTRY_INTEGRATION_DSN)) {
    add_filter('sentry_integration_dsn', function () {
        return SENTRY_INTEGRATION_DSN;
    }, 1, 0);

    PHPTracker::get_instance();
}

// Load the Javascript tracker if we have a public DSN
if (defined('SENTRY_INTEGRATION_PUBLIC_DSN') && !empty(SENTRY_INTEGRATION_PUBLIC_DSN)) {
    add_filter('sentry_integration_public_dsn', function () {
        return SENTRY_INTEGRATION_PUBLIC_DSN;
    }, 1, 0);

    JavaScriptTracker::get_instance();
}
