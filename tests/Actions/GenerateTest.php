<?php

namespace April\Tests\Actions;

use April\Tests\TestCase;

class GenerateTest extends TestCase
{
    public function testProvidesItsMenuItems()
    {
        $result = $this->execute();

        $this->assertTrue(strpos($result, 'new') !== false, 'Generate action doesn\'t expose its menu items');
    }

    public function testGeneratesTestCase()
    {
        $name = $this->getTestcaseName();
        $result = $this->execute('new ' . $name);

        $this->assertSubstring($name, $result);
    }

    public function testGeneratesTwoTestsByDefault()
    {
        $name = $this->getTestcaseName();
        $result = $this->execute('new ' . $name);

        $this->assertFileExists("cases/$name.php", 'Test case file is not generated');

        $case = file_get_contents("cases/$name.php");
        $matches = array();

        preg_match_all('/@test/', $case, $matches);

        $this->assertTrue(isset($matches[0]), 'Tests are not generated');
        $this->assertEquals(2, count($matches[0]) , '2 tests expected, got ' . count($matches));
    }

    public function testGeneratesDesiredAmountOfTests()
    {
        $name = $this->getTestcaseName();
        $testNames = array('array_map', 'foreach', 'different');
        $result = $this->execute('new ' . $name . ' ' . implode(' ', $testNames));

        $case = file_get_contents("cases/$name.php");
        $matches = array();

        preg_match_all('/@test\s([a-zA-Z0-9_×]+)/', $case, $matches);

        $this->assertTrue(isset($matches[0]), 'Tests are not generated');
        $this->assertEquals(3, count($matches[0]) , '3 tests expected, got ' . count($matches));
        $this->assertEquals($testNames, $matches[1], 'test names are not preserved');
    }

    protected function getTestcaseName()
    {
        return 'unittest' . microtime(true);
    }
}
