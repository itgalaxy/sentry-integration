<?php
/**
 * PHPUnit bootstrap file
 */

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once dirname(__DIR__) . '/vendor/autoload.php';

$_WP_DEVELOPMENT_DIR = dirname(__DIR__) . '/vendor/wordpress/wordpress';
$_WP_PHPUNIT_DIR =
    getenv('WP_PHPUNIT_DIR') ?: $_WP_DEVELOPMENT_DIR . '/tests/phpunit';
$_WP_TESTS_CONFIG_PATH = dirname(__DIR__) . '/tests/wp-tests-config.php';

// WordPress test utils support WP_TESTS_CONFIG_FILE_PATH with `5.1` version
define('WP_TESTS_CONFIG_FILE_PATH', $_WP_TESTS_CONFIG_PATH);
// For old WordPress versions we just copy config
copy($_WP_TESTS_CONFIG_PATH, $_WP_DEVELOPMENT_DIR . '/wp-tests-config.php');

// Give access to tests_add_filter() function.
require_once $_WP_PHPUNIT_DIR . '/includes/functions.php';

tests_add_filter('muplugins_loaded', function () {
    // test set up, plugin activation, etc.
    require dirname(__DIR__) . '/sentry-integration.php';
});

// Start up the WP testing environment.
require $_WP_PHPUNIT_DIR . '/includes/bootstrap.php';

global $wp_version;

echo "WordPress " . $wp_version . ".\n";
