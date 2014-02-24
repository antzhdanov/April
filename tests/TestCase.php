<?php

namespace April\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->testsDir = __DIR__ . '/Data/';
        $this->casesDir = dirname(__DIR__) . '/cases/';
    }

    public function execute($command = '')
    {
        return shell_exec('./april ' . $command);
    }

    public function assertSubstring($needle, $haystack)
    {
        $this->assertTrue(strpos($haystack, $needle) !== false, 'String does not contain "'. $needle .'"');
    }

    public function tearDown()
    {
        $files = array_filter(scandir('cases/'), function($file) { return preg_match('/^unittest/', $file); });

        foreach ($files as $file) {
            unlink("cases/$file");
        }
    }
}
