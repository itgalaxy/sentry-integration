<?php

namespace Itgalaxy\Tests\PHPTracker;

use Itgalaxy\SentryIntegration\JavaScriptTracker;
use PHPUnit\Framework\TestCase;

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

    public function tearDown()
    {
        unset($this->tracker);
    }
}
