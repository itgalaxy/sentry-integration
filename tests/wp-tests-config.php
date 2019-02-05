<?php

/* Path to the WordPress codebase you'd like to test. Add a forward slash in the end. */
if (!defined('ABSPATH')) {
    if (
        basename(__DIR__) === 'wordpress'
        && basename(dirname(__DIR__)) === 'wordpress'
    ) {
        define('ABSPATH', dirname(__FILE__) . '/src/');
    } else {
        define(
            'ABSPATH',
            dirname(dirname(__FILE__)) . '/vendor/wordpress/wordpress/src/'
        );
    }
}

/*
 * Path to the theme to test with.
 *
 * The 'default' theme is symlinked from test/phpunit/data/themedir1/default into
 * the themes directory of the WordPress installation defined above.
 */
if (!defined('WP_DEFAULT_THEME')) {
    define('WP_DEFAULT_THEME', 'default');
}

// Test with multisite enabled.
// Alternatively, use the tests/phpunit/multisite.xml configuration file.
// define( 'WP_TESTS_MULTISITE', true );

// Force known bugs to be run.
// Tests with an associated Trac ticket that is still open are normally skipped.
// define( 'WP_TESTS_FORCE_KNOWN_BUGS', true );

// Test with WordPress debug mode (default).
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}

// ** MySQL settings ** //

// This configuration file will be used by the copy of WordPress being tested.
// wordpress/wp-config.php will be ignored.

// WARNING WARNING WARNING!
// These tests will DROP ALL TABLES in the database with the prefix named below.
// DO NOT use a production database or one that is shared with something else.

if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('WP_DB_NAME') ?: 'wp_phpunit_tests');
}

if (!defined('DB_USER')) {
    define('DB_USER', getenv('WP_DB_USER') ?: 'root');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', getenv('WP_DB_PASS') ?: '');
}

if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}

if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8');
}

if (!defined('DB_COLLATE')) {
    define('DB_COLLATE', '');
}

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
if (!defined('AUTH_KEY')) {
    define('AUTH_KEY', 'put your unique phrase here');
}
if (!defined('SECURE_AUTH_KEY')) {
    define('SECURE_AUTH_KEY', 'put your unique phrase here');
}
if (!defined('LOGGED_IN_KEY')) {
    define('LOGGED_IN_KEY', 'put your unique phrase here');
}
if (!defined('NONCE_KEY')) {
    define('NONCE_KEY', 'put your unique phrase here');
}
if (!defined('AUTH_SALT')) {
    define('AUTH_SALT', 'put your unique phrase here');
}
if (!defined('SECURE_AUTH_SALT')) {
    define('SECURE_AUTH_SALT', 'put your unique phrase here');
}
if (!defined('LOGGED_IN_SALT')) {
    define('LOGGED_IN_SALT', 'put your unique phrase here');
}
if (!defined('NONCE_SALT')) {
    define('NONCE_SALT', 'put your unique phrase here');
}

$table_prefix = 'wptests_'; // Only numbers, letters, and underscores please!

if (!defined('WP_TESTS_DOMAIN')) {
    define('WP_TESTS_DOMAIN', 'example.org');
}

if (!defined('WP_TESTS_EMAIL')) {
    define('WP_TESTS_EMAIL', 'admin@example.org');
}

if (!defined('WP_TESTS_TITLE')) {
    define('WP_TESTS_TITLE', 'Test Blog');
}

if (!defined('WP_PHP_BINARY')) {
    define('WP_PHP_BINARY', 'php');
}

if (!defined('WPLANG')) {
    define('WPLANG', '');
}
