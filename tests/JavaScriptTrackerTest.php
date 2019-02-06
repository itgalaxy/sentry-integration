<?php

namespace Itgalaxy\Tests\PHPTracker;

use Itgalaxy\SentryIntegration\JavaScriptTracker;

class JavaScriptTrackerTest extends \WP_UnitTestCase
{
    private $tracker = null;

    public function setUp()
    {
        $this->tracker = JavaScriptTracker::get_instance();
    }

    public function test_get_instance()
    {
        $this->assertInstanceOf(
            get_class($this->tracker),
            JavaScriptTracker::get_instance()
        );
    }

    public function set_dsn()
    {
        $this->assertEmpty($this->tracker->get_dsn());

        $sentry_integration_dsn =
            'https://1fbf25e90f114a3d83a19aa4fa432dcf@sentry.itgalaxy.company/85';

        $this->tracker->set_dns($sentry_integration_dsn);

        $this->assertEquals($sentry_integration_dsn, $this->tracker->get_dsn());
    }

    public function test_get_dsn()
    {
        $this->assertEmpty($this->tracker->get_dsn());

        $sentry_integration_dsn =
            'https://1fbf25e90f114a3d83a19aa4fa432dcf@sentry.itgalaxy.company/85';

        $closure = function () use ($sentry_integration_dsn) {
            return $sentry_integration_dsn;
        };

        add_filter('sentry_integration_public_dsn', $closure);

        $this->assertEquals($sentry_integration_dsn, $this->tracker->get_dsn());

        remove_filter('sentry_integration_public_dsn', $closure);
    }

    public function test_set_options()
    {
        $options = ['option' => 1];

        $this->tracker->set_options($options);

        $this->assertEquals($options, $this->tracker->get_options());
    }

    public function test_get_options()
    {
        $this->assertEquals(
            $this->tracker->get_default_options(),
            $this->tracker->get_options()
        );

        $closure = function ($options) {
            return array_merge($options, ['my_options' => true]);
        };

        add_filter('sentry_integration_public_options', $closure);

        $this->assertEquals(
            $closure($this->tracker->get_default_options()),
            $this->tracker->get_options()
        );

        remove_filter('sentry_integration_public_options', $closure);
    }

    public function test_get_default_options()
    {
        $default_options = $this->tracker->get_default_options();

        $this->assertEquals(null, $default_options['release']);
        $this->assertEquals('unspecified', $default_options['environment']);
        $this->assertEquals(
            [
                'wordpress' => get_bloginfo('version'),
                'language' => get_bloginfo('language')
            ],
            $default_options['tags']
        );
    }

    public function tearDown()
    {
        $reflection = new \ReflectionClass($this->tracker);
        $property = $reflection->getProperty("instance");

        $property->setAccessible(true);
        $property->setValue(null);
    }
}
