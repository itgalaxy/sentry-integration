<?php

namespace Itgalaxy\SentryIntegration;

/**
 * WordPress Sentry Javascript Tracker.
 */

final class JavaScriptTracker extends TrackerAbstract
{
    /**
     * Holds the class instance.
     *
     * @var JavaScriptTracker
     */
    private static $instance;

    /**
     * Get the sentry tracker instance.
     *
     * @return JavaScriptTracker
     */
    public static function get_instance()
    {
        return self::$instance ?: (self::$instance = new self());
    }

    /**
     * Get sentry dsn.
     *
     * @return string
     */
    public function get_dsn()
    {
        $dsn = parent::get_dsn();

        if (has_filter('sentry_integration_public_dsn')) {
            $dsn = (string) apply_filters(
                'sentry_integration_public_dsn',
                $dsn
            );
        }

        return $dsn;
    }

    /**
     * Get sentry options.
     *
     * @return array
     */
    public function get_options()
    {
        $options = parent::get_options();

        // Cleanup context for JS.
        $context = $this->get_context();

        foreach ($context as $key => $_) {
            if (empty($context[$key])) {
                unset($context[$key]);
            }
        }

        $options = array_merge($options, $context);

        if (has_filter('sentry_integration_public_options')) {
            $options = (array) apply_filters(
                'sentry_integration_public_options',
                $options
            );
        }

        return $options;
    }

    /**
     * Get sentry default options.
     * @return array
     */
    public function get_default_options()
    {
        return [
            'release' => SENTRY_INTEGRATION_RELEASE,
            'environment' => defined('SENTRY_INTEGRATION_ENV')
                ? SENTRY_INTEGRATION_ENV
                : 'unspecified',
            'tags' => [
                'wordpress' => get_bloginfo('version'),
                'language' => get_bloginfo('language')
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function bootstrap()
    {
        $enqueueMode = SENTRY_INTEGRATION_PUBLIC_DSN_ENQUEUE_MODE;

        if ($enqueueMode === 'manual') {
            return;
        }

        $config =
            'Raven.config("' .
            $this->get_dsn() .
            '",' .
            wp_json_encode($this->get_options()) .
            ').install();';

        if ($enqueueMode === 'inline') {
            $loader = function () use ($config) {
                $src =
                    plugin_dir_path(SENTRY_INTEGRATION_PLUGIN_FILE) .
                    'public/raven-hidden-source-map.min.js';
                $scriptData = file_get_contents($src);

                echo sprintf('<script>%s</script>', $scriptData);

                // echo base64_encode(hash("sha512", $scriptData, true));
                // $scriptNonce = "EB3Mqe84XDrGlWSfUX26VbP51apt6Cn00w22kJlF0kY4IL+Il0xRLr6FPIgMHv9XjXYTmGxdGzeok1B77jbFeQ==";
                // $configNonce = base64_encode(hash("sha512", $configData, true));

                echo sprintf('<script>' . $config . '</script>');
            };

            add_action('wp_head', $loader, 0, 1);
            add_action('admin_head', $loader, 0, 1);
            add_action('login_head', $loader, 0, 1);

            return;
        }

        if ($enqueueMode === 'standard') {
            $loader = function () use ($config) {
                $src =
                    plugin_dir_path(SENTRY_INTEGRATION_PLUGIN_FILE) .
                    'public/raven.min.js';

                wp_register_script(
                    'sentry-integration-java-script-tracker',
                    $src
                );
                wp_enqueue_script('sentry-integration-java-script-tracker');
                wp_add_inline_script(
                    'sentry-integration-java-script-tracker',
                    $config
                );
            };

            add_action('wp_enqueue_scripts', $loader, 0, 1);
            add_action('login_enqueue_scripts', $loader, 0, 1);
            add_action('admin_enqueue_scripts', $loader, 0, 1);
        }
    }
}
