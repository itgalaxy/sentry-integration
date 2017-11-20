=== Sentry Integration ===
Contributors: evilebottnawi
Tags: sentry, errors, tracking
Requires at least: 4.4
Tested up to: 4.9
Stable tag: trunk
License: MIT
License URI: https://github.com/itgalaxy/sentry-integration/blob/master/LICENSE

A (unofficial) WordPress plugin to report PHP errors and JavaScript errors to Sentry.

== Description ==
This plugin can report PHP errors (optionally) and JavaScript errors (optionally) to Sentry and integrates with its release tracking.

It will auto detect authenticated users and add context where possible. All context/tags can be adjusted using filters mentioned below.

== Installation ==
1. Install this plugin by cloning or copying this repository to your `wp-contents/plugins` folder
2. Configure your DSN as explained below
2. Activate the plugin through the WordPress admin interface

**Note:** this plugin does not do anything by default and has no admin interface. A Sentry DSN must be configured.

(Optionally) track PHP errors by adding this snippet to your `wp-config.php` and replace `DSN` with your actual DSN that you find in Sentry:

`define( 'SENTRY_INTEGRATION_DSN', 'DSN' );`

(Optionally) set the error types the PHP tracker will track:

`define( 'SENTRY_INTEGRATION_ERROR_TYPES', E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_USER_DEPRECATED );`

**Note:** Remove or comment this constant to disable the PHP tracker.

(Optionally) track JavaScript errors by adding this snippet to your `wp-config.php` and replace `PUBLIC_DSN` with your actual public DSN that you find in Sentry (**never use your private DSN**):

`define( 'SENTRY_INTEGRATION_PUBLIC_DSN', 'PUBLIC_DSN' );`

**Note:** Remove or comment this constant to disable the JavaScript tracker.

(Optionally) define a version of your site; by default the theme version will be used. This is used for tracking at which version of your site the error occurred. When combined with release tracking this is a very powerful feature.

`define( 'SENTRY_INTEGRATION_VERSION', 'v2.1.3’ );`

(Optionally) define an environment of your site. Defaults to `unspecified`.

`define( 'SENTRY_INTEGRATION_ENV', 'production' );`

== Filters ==
This plugin provides the following filters to plugin/theme developers. For more information have a look at the README.md file.

Common to both trackers:
- `sentry_integration_user_context`

Specific to PHP tracker:

- `sentry_integration_dsn`
- `sentry_integration_options`
- `sentry_integration_send_data`

Specific to JS tracker:

- `sentry_integration_public_dsn`
- `sentry_integration_public_options`

== Changelog ==
= 1.0.0 =

* Initial release

== Contributors ==

evilebottnawi (https://github.com/evilebottnawi)
