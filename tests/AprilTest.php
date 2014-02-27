<?php

namespace April\Tests;

class AprilTest extends TestCase
{
    public function testShowsVersion()
    {
        $result = $this->execute();

        $this->assertTrue(strpos($result, 'April v.') !== false);
    }

    public function testCallsInitPlugins()
    {
        // mock project manager
        $pm = $this->getMock(
            'April\PluginManager', array('init'), array($this->april)
        );

        $pm->expects($this->once())
            ->method('init');

        $this->april->setPluginManager($pm);

        $this->execute();
    }

}
