# [Sentry Integration](https://wordpress.org/plugins/sentry-integration/)

[![Latest Stable Version](https://poser.pugx.org/itgalaxy/sentry-integration/v/stable)](https://packagist.org/packages/itgalaxy/sentry-integration)
[![Total Downloads](https://poser.pugx.org/itgalaxy/sentry-integration/downloads)](https://packagist.org/packages/itgalaxy/sentry-integration)
[![Latest Unstable Version](https://poser.pugx.org/itgalaxy/sentry-integration/v/unstable)](https://packagist.org/packages/itgalaxy/sentry-integration)
[![License](https://poser.pugx.org/itgalaxy/sentry-integration/license)](https://packagist.org/packages/itgalaxy/sentry-integration)
[![composer.lock](https://poser.pugx.org/itgalaxy/sentry-integration/composerlock)](https://packagist.org/packages/itgalaxy/sentry-integration)

A (unofficial)
[WordPress plugin](https://wordpress.org/plugins/sentry-integration/) to report
PHP, JavaScript and security headers (Expect-CT and X-XSS-Protection) errors
to [Sentry](https://sentry.io).

## Introduction

This plugin can report PHP errors (optionally), JavaScript errors
(optionally) and security headers (Expect-CT and X-XSS-Protection) (optionally)
to [Sentry](https://sentry.io) and integrates with its release tracking.

It will auto detect authenticated users and add context where possible. All
context/tags can be adjusted using filters mentioned below.

## Usage

1. Install this plugin by cloning or copying this repository to your
   `wp-contents/plugins` folder
2. Configure your DSN as explained below
3. Activate the plugin through the WordPress admin interface

**Note:** this plugin does not do anything by default and has no admin
interface. A DSN must be configured first.

## Configuration

### PHP tracker

Track PHP errors by adding this snippet to your `wp-config.php` and
replace `ADDRESS_YOUR_DSN` with your actual DSN that you find in Sentry:

```php
define('SENTRY_INTEGRATION_DSN', 'ADDRESS_YOUR_DSN');
// Example `ADDRESS_YOUR_DSN` value - https://1fbf25e90f114a3d83a19aa4fa432dcf:3c13a039710e4287900bc71552b1e268@sentry.io/1
```

**Note:** Do not set this constant to disable the PHP tracker.

---

**(Optionally)** Set the error types the PHP tracker will track:

```php
define(
  'SENTRY_INTEGRATION_ERROR_TYPES',
  E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_USER_DEPRECATED
);
```

### JavaScript tracker

Track JavaScript errors by adding this snippet to your
`wp-config.php` and replace `PUBLIC_ADDRESS_YOUR_DSN` with your actual public DSN that you
find in Sentry (**never use your private DSN**):

```php
define('SENTRY_INTEGRATION_PUBLIC_DSN', 'PUBLIC_ADDRESS_YOUR_DSN');
// Example `PUBLIC_ADDRESS_YOUR_DSN` value - https://1fbf25e90f114a3d83a19aa4fa432dcf@sentry.io/1
```

**Note:** Do not set this constant to disable the JavaScript tracker.

---

**(Optionally)** You can control how plugin should register and enqueue `sentry` JavaScript script (i.e. `raven.min.js`):

```php
define('SENTRY_INTEGRATION_PUBLIC_DSN_ENQUEUE_MODE', 'manual');
```

There are 3 values for `SENTRY_INTEGRATION_PUBLIC_DSN_ENQUEUE_MODE` constant.

1. `inline` (**by default** for better performance and avoid problems with order scripts from other plugins/themes) - print inline script and configuration in `head` html tag.
2. `standard` - use standard `WordPress` api for scripts (i.e. using `wp_register_script`, `wp_enqueue_script` and `wp_add_inline_script` functions on `wp_enqueue_scripts`, `login_enqueue_scripts` and `admin_enqueue_scripts` actions).
3. `manual` - don't register and enqueue script and configuration. You should manually register and enqueue `sentry` JavaScript script with configuration.

### Expect-CT header tracker

Track Expect-CT header errors by adding this snippet
to your `wp-config.php` and replace `ADDRESS_YOUR_DSN` with your actual DSN
that you find in Sentry:

```php
define('SENTRY_INTEGRATION_EXPECT_CT_DSN', 'ADDRESS_YOUR_DSN');
// Example `ADDRESS_YOUR_DSN` value - https://1fbf25e90f114a3d83a19aa4fa432dcf@sentry.io/1
```

**Note:** Do not set this constant to disable the Expect-CT tracker.
**Note:** You should send `Expect-CT` header
with `report-uri="http://you-site.com/sentry-integration/expect-ct/report/"`
using `.htaccess`, `php` or another prefer method. See
more about [Expect-CT](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Expect-CT)
header.

### X-XSS-Protection tracker

Track X-XSS-Protection header errors by adding this snippet
to your `wp-config.php` and replace `ADDRESS_YOUR_DSN` with your actual DSN
that you find in Sentry:

```php
define('SENTRY_INTEGRATION_X_XSS_PROTECTION_DSN', 'ADDRESS_YOUR_DSN');
// Example `ADDRESS_YOUR_DSN` value - https://1fbf25e90f114a3d83a19aa4fa432dcf@sentry.io/1
```

**Note:** Do not set this constant to disable the X-XSS-Protection tracker.
**Note:** You should send `X-XSS-Protection` header
with `report="http://you-site.com/sentry-integration/x-xss-protection/report/"`
using `.htaccess`, `php` or another prefer method. See
more about [X-XSS-Protection](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection)
header.

### Common configuration for all trackers

**(Optionally)** Define a version of your site; by default the theme version will be
used. This is used for tracking at which version of your site the error
occurred. When combined with release tracking this is a very powerful feature.

```php
define('SENTRY_INTEGRATION_VERSION', 'v2.1.3');
```

**(Optionally)** Define an environment of your site. Defaults to `unspecified`.

```php
define('SENTRY_INTEGRATION_ENV', 'production');
```

**Note:** By default `SENTRY_INTEGRATION_VERSION` constant contains `wp_get_theme()->get('Version')` result.

## Filters

This plugin provides the following filters to plugin/theme developers.

Please note that some filters are fired when the Sentry trackers are initialized
so they won't fire if you define them in you theme or in a plugin that loads
after Sentry Integration does.

### Common to PHP, JavaScript, Expect-CT and X-XSS-Protection trackers

#### `sentry_integration_user_context` (array)

You can use this filter to extend the Sentry user context for both PHP, JS and
security headers trackers.

> **WARNING:** These values are exposed to the public in the JS tracker, so make
> sure you do not expose anything private!

Example usage:

```php
/**
 * Customize sentry user context.
 *
 * @param array $user The current sentry user context.
 *
 * @return array
 */
function customize_sentry_user_context(array $user)
{
  return array_merge($user, array(
    'a-custom-user-meta-key' => 'custom value'
  ));
}

add_filter('sentry_integration_user_context', 'customize_sentry_user_context');
```

**Note:** _This filter fires on the WordPress `set_current_user` action._

### Specific to PHP tracker:

#### `sentry_integration_dsn` (string)

You can use this filter to override the Sentry DSN used for the PHP tracker.

Example usage:

```php
/**
 * Customize sentry dsn.
 *
 * @param string $dsn The current sentry public dsn.
 *
 * @return string
 */
function customize_sentry_dsn($dsn)
{
  return 'https://<key>:<secret>@sentry.io/<project>';
}

add_filter('sentry_integration_dsn', 'customize_sentry_dsn');
```

**Note:** _This filter fires on when Sentry Integration initializes and after
the WP `after_setup_theme`._

---

#### `sentry_integration_options` (array)

You can use this filter to customize the Sentry options used to initialize the
PHP tracker.

Example usage:

```php
/**
 * Customize sentry options.
 *
 * @param array $options The current sentry options.
 *
 * @return array
 */
function customize_sentry_options(array $options)
{
  return array_merge($options, array(
    'tags' => array(
      'my-custom-tag' => 'custom value'
    )
  ));
}

add_filter('sentry_integration_options', 'customize_sentry_options');
```

**Note:** _This filter fires on when Sentry Integration initializes and after
the WP `after_setup_theme`._

---

#### `sentry_integration_send_data` (array|bool)

Provide a function which will be called before Sentry PHP tracker sends any
data, allowing you both to mutate that data, as well as prevent it from being
sent to the server.

Example usage:

```php
/**
 * Customize sentry send data.
 *
 * @param array $data The sentry send data.
 *
 * @return array|bool Return the data array or false to cancel the send operation.
 */
function filter_sentry_send_data(array $data)
{
  $data['tags']['my_custom_key'] = 'my_custom_value';

  return $data;
}

add_filter('sentry_integration_send_data', 'filter_sentry_send_data');
```

**Note:** _This filter fires whenever the Sentry SDK is sending data to the
Sentry server._

### Specific to JavaScript tracker

#### `sentry_integration_public_dsn` (string)

You can use this filter to override the Sentry DSN used for the JS tracker.

> **WARNING:** This value is exposed to the public, so make sure you do not use
> your private DSN!

Example usage:

```php
/**
 * Customize public sentry dsn.
 *
 * @param string $dsn The current sentry public dsn.
 *
 * @return string
 */
function customize_public_sentry_dsn($dsn)
{
  return 'https://<key>@sentry.io/<project>';
}

add_filter('sentry_integration_public_dsn', 'customize_public_sentry_dsn');
```

---

#### `sentry_integration_public_options` (array)

You can use this filter to customize/override the sentry options used to
initialize the JS tracker.

> **WARNING:** These values are exposed to the public, so make sure you do not
> expose anything private !

Example usage:

```php
/**
 * Customize public sentry options.
 *
 * @param array $options The current sentry public options.
 *
 * @return array
 */
function customize_public_sentry_options(array $options)
{
  return array_merge($options, array(
    'tags' => array(
      'custom-tag' => 'custom value'
    )
  ));
}

add_filter(
  'sentry_integration_public_options',
  'customize_sentry_public_options'
);
```

### Specific to Expect-CT tracker:

#### `sentry_integration_expect_ct_dsn` (string)

You can use this filter to override the Sentry DSN used for the
Expect-CT tracker.

Example usage:

```php
/**
 * Customize sentry dsn.
 *
 * @param string $dsn The current sentry public dsn.
 *
 * @return string
 */
function customize_sentry_dsn($dsn)
{
  return 'https://<key>:<secret>@sentry.io/<project>';
}

add_filter('sentry_integration_expect_ct_dsn', 'customize_sentry_dsn');
```

**Note:** _This filter fires on when Sentry Integration initializes and after
the WP `after_setup_theme`._

---

#### `sentry_integration_expect_ct_options` (array)

You can use this filter to customize the Sentry options used to initialize the
Expect-CT tracker.

Example usage:

```php
/**
 * Customize sentry options.
 *
 * @param array $options The current sentry options.
 *
 * @return array
 */
function customize_sentry_options(array $options)
{
  return array_merge($options, array(
    'tags' => array(
      'my-custom-tag' => 'custom value'
    )
  ));
}

add_filter('sentry_integration_expect_ct_options', 'customize_sentry_options');
```

**Note:** _This filter fires on when Sentry Integration initializes and after
the WP `after_setup_theme`._

---

#### `sentry_integration_expect_ct_send_data` (array|bool)

Provide a function which will be called before Sentry Expect-CT tracker
sends any data, allowing you both to mutate that data, as well as prevent
it from being sent to the server.

Example usage:

```php
/**
 * Customize sentry send data.
 *
 * @param array $data The sentry send data.
 *
 * @return array|bool Return the data array or false to cancel the send operation.
 */
function filter_sentry_send_data(array $data)
{
  $data['tags']['my_custom_key'] = 'my_custom_value';

  return $data;
}

add_filter('sentry_integration_expect_ct_send_data', 'filter_sentry_send_data');
```

**Note:** _This filter fires whenever the Sentry SDK is sending data to the
Sentry server._

### Specific to X-XSS-Protection tracker:

#### `sentry_integration_x_xss_protection_dsn` (string)

You can use this filter to override the Sentry DSN used for the
X-XSS-Protection tracker.

Example usage:

```php
/**
 * Customize sentry dsn.
 *
 * @param string $dsn The current sentry public dsn.
 *
 * @return string
 */
function customize_sentry_dsn($dsn)
{
  return 'https://<key>:<secret>@sentry.io/<project>';
}

add_filter('sentry_integration_x_xss_protection_dsn', 'customize_sentry_dsn');
```

**Note:** _This filter fires on when Sentry Integration initializes and after
the WP `after_setup_theme`._

---

#### `sentry_integration_x_xss_protection_options` (array)

You can use this filter to customize the Sentry options used to initialize the
Expect-CT tracker.

Example usage:

```php
/**
 * Customize sentry options.
 *
 * @param array $options The current sentry options.
 *
 * @return array
 */
function customize_sentry_options(array $options)
{
  return array_merge($options, array(
    'tags' => array(
      'my-custom-tag' => 'custom value'
    )
  ));
}

add_filter(
  'sentry_integration_x_xss_protection_options',
  'customize_sentry_options'
);
```

**Note:** _This filter fires on when Sentry Integration initializes and after
the WP `after_setup_theme`._

---

#### `sentry_integration_x_xss_protection_send_data` (array|bool)

Provide a function which will be called before Sentry Expect-CT tracker
sends any data, allowing you both to mutate that data, as well as prevent
it from being sent to the server.

Example usage:

```php
/**
 * Customize sentry send data.
 *
 * @param array $data The sentry send data.
 *
 * @return array|bool Return the data array or false to cancel the send operation.
 */
function filter_sentry_send_data(array $data)
{
  $data['tags']['my_custom_key'] = 'my_custom_value';

  return $data;
}

add_filter(
  'sentry_integration_x_xss_protection_send_data',
  'filter_sentry_send_data'
);
```

**Note:** _This filter fires whenever the Sentry SDK is sending data to the
Sentry server._

## Catching plugin errors

Since this plugin is called `sentry-integration` it loads a bit late which could
miss errors or notices occuring in plugins that load before it.

You can remedy this by loading WordPress Sentry as a must-use plugin by creating
the file `wp-content/mu-plugins/sentry-integration.php` (if the `mu-plugins`
directory does not exists you must create that too).

```php
<?php

/**
 * Plugin Name: Sentry Integration
 * Plugin URI: https://github.com/itgalaxy/sentry-integration
 * Description: A (unofficial) WordPress plugin to report PHP and JavaScript and security headers errors to Sentry.
 * Version: must-use-proxy
 * Author: Alexander Krasnoyarov
 * Author URI: https://github.com/evilebottnawi
 * License: MIT
 */

$sentry_integration =
  __DIR__ . '/../plugins/sentry-integration/sentry-integration.php';

if (!file_exists($sentry_integration)) {
  return;
}

require $sentry_integration;

define('SENTRY_INTEGRATION_MU_LOADED', true);
```

Now `sentry-integration` will load always and before all other plugins.

**Note**: We advise you leave the original `sentry-integration` in the
`/wp-content/plugins` folder to still have updates come in through the WordPress
updater. However enabling or disabling does nothing if the above script is
active (since it will always be enabled).

## License

Sentry Integration plugin is open-sourced software
licensed under the [MIT license](http://opensource.org/licenses/MIT).
