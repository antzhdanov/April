<?php

namespace April\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->testsDir = __DIR__ . '/Data/';
        $this->casesDir = dirname(__DIR__) . '/cases/';

        $this->setApplication(new \April\April(array()));
    }

    /**
     * Execute command
     *
     * @param array
     * @param function
     */
    public function execute($command = '', $beforeInit = null)
    {
        ob_start();

        $this->april->setRawArgs(
            array_merge(array('april'), explode(' ', $command))
        );

        if ($beforeInit)
            $beforeInit($this->april);

        $this->april
            ->init()
            ->run();

        $out = ob_get_contents();
        ob_end_clean();

        return $out;
    }

    public function setApplication($april = null)
    {
        $this->april = $april;
        return $this;
    }

    protected function getTestResults()
    {
        $name = 'unittest_testcase_sample.php';
        copy($this->testsDir . $name, $this->casesDir . $name);

        return $this->execute('run unittest_testcase_sample');
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
