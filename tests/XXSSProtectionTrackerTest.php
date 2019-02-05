<?php

namespace Itgalaxy\Tests\PHPTracker;

use Itgalaxy\SentryIntegration\XXSSProtectionTracker;
use PHPUnit\Framework\TestCase;

class XXSSProtectionTrackerTest extends \WP_UnitTestCase
{
    private $tracker = null;

    public function setUp()
    {
        $this->tracker = XXSSProtectionTracker::get_instance();
    }

    public function test_get_instance()
    {
        $this->assertInstanceOf(
            get_class($this->tracker),
            XXSSProtectionTracker::get_instance()
        );
    }

    public function tearDown()
    {
        unset($this->tracker);
    }
}
