<?php

namespace Itgalaxy\Tests\PHPTracker;

use Itgalaxy\SentryIntegration\ExpectCTTracker;
use PHPUnit\Framework\TestCase;

class ExpectCTTrackerTest extends \WP_UnitTestCase
{
    private $tracker = null;

    public function setUp()
    {
        $this->tracker = ExpectCTTracker::get_instance();
    }

    public function test_get_instance()
    {
        $this->assertInstanceOf(
            get_class($this->tracker),
            ExpectCTTracker::get_instance()
        );
    }

    public function tearDown()
    {
        unset($this->tracker);
    }
}
