<?php

namespace April\Tests;

class AprilTest extends TestCase
{
    public function testShowsVersion()
    {
        $result = $this->execute();

        $this->assertTrue(strpos($result, 'April v.') !== false);
    }
}
