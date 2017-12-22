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
        return self::$instance ?: self::$instance = new self();
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
     * Target of set_current_user action.
     *
     * @access private
     */
    public function on_enqueue_scripts()
    {
        echo sprintf(
            '<script>%s</script>',
            file_get_contents(
                plugin_dir_path(SENTRY_INTEGRATION_PLUGIN_FILE) . 'public/raven.min.js'
            )
        );

        echo sprintf(
            '<script>Raven.config("'
            . $this->get_dsn()
            . '",'
            . wp_json_encode($this->get_options())
            . ').install();</script>'
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function bootstrap()
    {
        // Register on front-end using the highest priority.
        add_action('wp_head', [$this, 'on_enqueue_scripts'], 0, 1);

        // Register on admin using the highest priority.
        add_action('admin_head', [$this, 'on_enqueue_scripts'], 0, 1);

        // Register on login using the highest priority.
        add_action('login_head', [$this, 'on_enqueue_scripts'], 0, 1);
    }
}
