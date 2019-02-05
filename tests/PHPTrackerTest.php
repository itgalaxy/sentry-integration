<?php

namespace Itgalaxy\Tests\PHPTracker;

use Itgalaxy\SentryIntegration\PHPTracker;
use PHPUnit\Framework\TestCase;

class PHPTrackerTest extends \WP_UnitTestCase
{
    private $tracker = null;

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

    public function tearDown()
    {
        unset($this->tracker);
    }
}
