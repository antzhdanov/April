<?php

namespace April\Tests\Actions;

use April\Tests\TestCase;

class RunTest extends TestCase
{
    public function testProvidesItsMenuItems()
    {
        $result = $this->execute();

        $this->assertTrue(strpos($result, 'run') !== false, 'Run action doesn\'t expose its menu items');
    }

    public function testRunExistsOnEmptyTestCase()
    {
        $result = $this->execute('run');

        $this->assertSubstring('Please specify the test case to run', $result);
    }

    public function testRunExistsOnWrongTestcaseFilename()
    {
        $result = $this->execute('run $$%$%fgdfg');

        $this->assertSubstring('Wrong test case name. Filename contains restricted characters.', $result);
    }

    public function testGeneratesScriptsBasedOnTestcaseFile()
    {
        // copy test case
        $result = $this->getTestResults();

        $this->assertFileExists(
            sys_get_temp_dir() . '/april/unittest_testcase_sample/foreach.php', 'Test script 1 is not created!'
        );
        $this->assertFileExists(
            sys_get_temp_dir() . '/april/unittest_testcase_sample/array_map.php', 'Test script 2 is not created!'
        );

        // ensure that files are generated properly
        $arrayMapFile = file_get_contents(sys_get_temp_dir() . '/april/unittest_testcase_sample/array_map.php');
        $foreachFile = file_get_contents(sys_get_temp_dir() . '/april/unittest_testcase_sample/foreach.php');

        // @init section
        $this->assertContains(
            '$array = range(1, 500);',
            $foreachFile,
            'Generated file doesn\'t include @init section'
        );
        $this->assertContains(
            '$result = array();',
            $foreachFile,
            'Generated file doesn\'t include @init section'
        );
        $this->assertContains(
            '$array = range(1, 500);',
            $arrayMapFile,
            'Generated file doesn\'t include @init section'
        );
        $this->assertContains(
            '$result = array();',
            $arrayMapFile,
            'Generated file doesn\'t include @init section'
        );

        // assert actual test
        $this->assertContains(
            '$result = array_map(function($value) { return $value * 2; }, $array);',
            $arrayMapFile,
            'Generated file doesn\'t include @test section'
        );
        $this->assertContains(
            'foreach ($array as $key => $value) {',
            $foreachFile,
            'Generated file doesn\'t include @test section'
        );
    }

    public function testOutput()
    {
        $result = $this->getTestResults();
        $this->assertContains('Test foreach', $result, 'Wrong output');
        $this->assertContains('Ran 1000 iterations in ', $result, 'Wrong output');
    }

    public function testCallsPlugins()
    {
        $result = $this->getTestResults();

        // include process plugin
        $this->markTestIncomplete('NYI');
    }

    protected function getTestResults()
    {
        $name = 'unittest_testcase_sample.php';
        copy($this->testsDir . $name, $this->casesDir . $name);

        return $this->execute('run unittest_testcase_sample');
    }
}
