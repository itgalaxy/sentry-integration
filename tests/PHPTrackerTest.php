<?php

namespace Itgalaxy\Tests\PHPTracker;

use Itgalaxy\SentryIntegration\PHPTracker;

class PHPTrackerTest extends \WP_UnitTestCase
{
    protected $tracker = null;

    public function setUp()
    {
        $this->tracker = PHPTracker::get_instance();
    }

    public function test_get_instance()
    {
        $this->assertInstanceOf(
            get_class($this->tracker),
            PHPTracker::get_instance()
        );
    }

    public function set_dsn()
    {
        $this->assertEmpty($this->tracker->get_dsn());

        $sentry_integration_dsn =
            'https://1fbf25e90f114a3d83a19aa4fa432dcf:3c13a039710e4287900bc71552b1e268@sentry.domain.com/85';

        $this->tracker->set_dns($sentry_integration_dsn);

        $this->assertEquals($sentry_integration_dsn, $this->tracker->get_dsn());
    }

    public function test_get_dsn()
    {
        $this->assertEmpty($this->tracker->get_dsn());

        $sentry_integration_dsn =
            'https://1fbf25e90f114a3d83a19aa4fa432dcf:3c13a039710e4287900bc71552b1e268@sentry.domain.com/85';

        $closure = function () use ($sentry_integration_dsn) {
            return $sentry_integration_dsn;
        };

        add_filter('sentry_integration_dsn', $closure);

        $this->assertEquals($sentry_integration_dsn, $this->tracker->get_dsn());

        remove_filter('sentry_integration_dsn', $closure);
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

        add_filter('sentry_integration_options', $closure);

        $this->assertEquals(
            $closure($this->tracker->get_default_options()),
            $this->tracker->get_options()
        );

        remove_filter('sentry_integration_options', $closure);
    }

    public function test_get_default_options()
    {
        global $wpdb;

        $default_options = $this->tracker->get_default_options();

        $this->assertEquals(null, $default_options['release']);
        $this->assertEquals('unspecified', $default_options['environment']);
        $this->assertEquals(
            [
                'php' => phpversion(),
                'mysql' => $wpdb->db_version(),
                'wordpress' => get_bloginfo('version')
            ],
            $default_options['tags']
        );
    }

    public function test_get_client()
    {
        $sentry_integration_dsn =
            'https://1fbf25e90f114a3d83a19aa4fa432dcf:3c13a039710e4287900bc71552b1e268@sentry.domain.com/85';

        $this->tracker->set_dsn($sentry_integration_dsn);

        $client = $this->tracker->get_client();

        $this->assertInstanceOf(get_class(new \Raven_Client()), $client);

        $parsedDSN = $client::parseDSN($sentry_integration_dsn);

        $this->assertEquals($parsedDSN['server'], $client->server);
        $this->assertEquals($parsedDSN['project'], $client->project);
        $this->assertEquals($parsedDSN['public_key'], $client->public_key);
        $this->assertEquals($parsedDSN['secret_key'], $client->secret_key);
    }

    public function test_get_context()
    {
        $this->assertEquals(
            ['user' => null, 'tags' => [], 'extra' => []],
            $this->tracker->get_context()
        );
    }

    public function test_set_user_context()
    {
        $data = ['id' => 1];

        $this->tracker->set_user_context($data);

        $this->assertEquals($data, $this->tracker->get_user_context());
    }

    public function test_get_user_context()
    {
        $this->assertEmpty($this->tracker->get_user_context());
    }

    public function test_set_tags_context()
    {
        $data = ['key' => 'value'];

        $this->tracker->set_tags_context($data);

        $this->assertEquals($data, $this->tracker->get_tags_context());
    }

    public function test_get_tags_context()
    {
        $this->assertEmpty($this->tracker->get_tags_context());
    }

    public function test_set_extra_context()
    {
        $data = ['key' => 'value'];

        $this->tracker->set_extra_context($data);

        $this->assertEquals($data, $this->tracker->get_extra_context());
    }

    public function test_get_extra_context()
    {
        $this->assertEmpty($this->tracker->get_extra_context());
    }

    public function tearDown()
    {
        $reflection = new \ReflectionClass($this->tracker);
        $property = $reflection->getProperty("instance");

        $property->setAccessible(true);
        $property->setValue(null);
    }
}
