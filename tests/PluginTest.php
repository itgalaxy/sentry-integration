<?php

namespace Itgalaxy\Tests\PHPTracker;

class PluginTest extends \WP_UnitTestCase
{
    public function test_plugin_entrypoint()
    {
        $sentry_integration_dsn =
            'https://1fbf25e90f114a3d83a19aa4fa432dcf:3c13a039710e4287900bc71552b1e268@sentry.domain.com/85';

        define('SENTRY_INTEGRATION_EXPECT_CT_DSN', $sentry_integration_dsn);
        define('SENTRY_INTEGRATION_DSN', $sentry_integration_dsn);
        define(
            'SENTRY_INTEGRATION_X_XSS_PROTECTION_DSN',
            $sentry_integration_dsn
        );

        $sentry_integration_public_dsn =
            'https://1fbf25e90f114a3d83a19aa4fa432dcf@sentry.itgalaxy.company/85';

        define('SENTRY_INTEGRATION_PUBLIC_DSN', $sentry_integration_public_dsn);

        $pluginPath = realpath(__DIR__ . '/../sentry-integration.php');

        include $pluginPath;

        $this->assertEquals($pluginPath, SENTRY_INTEGRATION_PLUGIN_FILE);

        $new_sentry_integration_expect_ct_dsn = apply_filters(
            'sentry_integration_expect_ct_dsn',
            null
        );

        $this->assertEquals(
            $sentry_integration_dsn,
            $new_sentry_integration_expect_ct_dsn
        );

        $new_sentry_integration_public_dsn = apply_filters(
            'sentry_integration_public_dsn',
            null
        );

        $this->assertEquals(
            $sentry_integration_public_dsn,
            $new_sentry_integration_public_dsn
        );

        $new_sentry_integration_dsn = apply_filters(
            'sentry_integration_dsn',
            null
        );

        $this->assertEquals(
            $sentry_integration_dsn,
            $new_sentry_integration_dsn
        );

        $new_sentry_integration_x_xss_protection_dsn = apply_filters(
            'sentry_integration_x_xss_protection_dsn',
            null
        );

        $this->assertEquals(
            $sentry_integration_dsn,
            $new_sentry_integration_x_xss_protection_dsn
        );
    }
}
