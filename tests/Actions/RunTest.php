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

    
}
